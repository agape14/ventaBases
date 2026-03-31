<?php

namespace App\Http\Controllers\User; 

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CashOnDelivery;
use App\Models\PaymentInfo;
use App\Models\PaymentTransition;
use App\Models\ProductVariant;
use App\Models\StockHistory;
use App\Models\CountOrder;
use App\Models\Customer;
use App\Models\PersonaNatural;
use App\Models\PersonaJuridica;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Notifications\UserOrderNotification;
use Illuminate\Support\Facades\Notification;
use App\Services\NiubizService;
use App\Mail\OrderConfirmation;
use App\Mail\UserCreatedNotification;
use Illuminate\Support\Facades\Mail;
use App\Models\VoucherDetail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    protected $niubizService;
    
    /**
     * Extensiones de imagen permitidas
     */
    private $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    /**
     * MIME types de imagen permitidos
     */
    private $allowedImageMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    /**
     * Valida y procesa una imagen de forma segura
     */
    private function validateAndProcessImage($file): array
    {
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $this->allowedImageExtensions)) {
            return ['valid' => false, 'filename' => null, 'error' => 'Extensión no permitida.'];
        }
        
        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, $this->allowedImageMimes)) {
            return ['valid' => false, 'filename' => null, 'error' => 'Tipo de archivo no permitido.'];
        }
        
        $imageInfo = @getimagesize($file->getPathname());
        if ($imageInfo === false) {
            return ['valid' => false, 'filename' => null, 'error' => 'No es una imagen válida.'];
        }
        
        $secureFilename = Str::random(40) . '.' . $extension;
        return ['valid' => true, 'filename' => $secureFilename, 'error' => null];
    }

    public function __construct(NiubizService $niubizService)
    {
        $this->niubizService = $niubizService;
    }

    //track order by invoice
    public function trackOrder(Request $request){
        $validation = Validator::make($request->all(),[
            'invoiceNumber'=> 'required',
        ]);
        if($validation->fails()){
            return back()->with(['error'=>'El código de pedido es obligatorio.']);
        }

        $order = Order::select('*')->where('invoice_number',$request->invoiceNumber)->withCount('orderItem')->first();
        if($order){
            return view('frontEnd.orderTracking')->with(['order'=>$order]);
        }else{
            return back()->with(['error'=>'Su código de pedido no es válido']);
        }
    }

    //create
    public function createOrder(Request $request){
        //empty cart checking
        if(Session::has('cart')){
            if(count(Session::get('cart')) == 0){
                return back()->with(['error'=>'¡Tu carrito está vacío!']);
            }
        }else{
            return back()->with(['error'=>'¡Tu carrito está vacío!']);
        }

        $rules = [
            'tipo_persona' => 'required|in:N,J',
            'tipo_comprobante' => 'required|string',
            'stateDivisionId' => 'required|integer',
            'cityId' => 'required|integer',
            'paymentMethod' => 'required|string',
        ];

        if ($request->input('tipo_persona') === 'N') {
            $rules['persona_natural.dni'] = 'required|string|max:8';
            $rules['persona_natural.email'] = 'required|email';
            $rules['persona_natural.phone'] = 'required|string';
            $rules['persona_natural.name'] = 'required|string';
            $rules['persona_natural.address'] = 'required|string';
            $rules['persona_natural.distrito'] = 'required|integer';
            if ($request->input('tipo_comprobante') === 'F') {
                $rules['persona_natural.ruc'] = 'required|string|digits:11';
            }
        }

        if ($request->input('tipo_persona') === 'J') {
            $rules['persona_juridica.ruc'] = 'required|string|digits:11';
            $rules['persona_juridica.email'] = 'required|email';
            $rules['persona_juridica.phone'] = 'required|string';
            $rules['persona_juridica.razon_social'] = 'required|string';
            $rules['persona_juridica.address'] = 'required|string';
            $rules['persona_juridica.distrito'] = 'required|integer';

            $rules['representante_legal.dni'] = 'required|string|digits:8';
            $rules['representante_legal.name'] = 'required|string';
            $rules['representante_legal.email'] = 'required|email';
            $rules['representante_legal.address'] = 'required|string';
            $rules['representante_legal.phone'] = 'required|string';
            $rules['representante_legal.distrito'] = 'required|integer';
        }

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return back()->withErrors($validation)->withInput();
        }

        if($request->paymentMethod == 'tarjeta'){
            $orderId = $this->insertOrderData($request);
            $this->destroySessionData();
            $this->notifyToAdmin($orderId,'realizó un nuevo pedido');
            return redirect()->route('user#misPagos', ['id' => $orderId])->with(['orderSuccess'=>'Orden exitosamente']);
        }

        $checkPaymentMethod = PaymentInfo::where('status','1')->where('type',$request->paymentMethod)->exists();
        if(!$checkPaymentMethod){
            return back()->with(['error'=>'Este método de pago no está disponible actualmente. ¡Elija otro!']);
        }

        $data = $request->all();
        return view('frontEnd.payment')->with(['data'=>$data]);
    }

    //confirm payment
    public function confirmPayment(Request $request){
        // ============================================
        // PARCHE DE SEGURIDAD - Enero 2026
        // ============================================
        
        $validation = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'stateDivisionId' => 'required',
            'cityId' => 'required',
            'townshipId' => 'required',
            'address' => 'required',
            'paymentMethod' => 'required',
            'paymentScreenshot' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'paymentInfoId' => 'required',
        ]);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        // Validación adicional de seguridad para el screenshot
        $ssFile = $request->file('paymentScreenshot');
        $imageResult = $this->validateAndProcessImage($ssFile);
        if (!$imageResult['valid']) {
            return back()->withErrors(['paymentScreenshot' => $imageResult['error']])->withInput();
        }

        //insert data to order table and order items table
        $orderId = $this->insertOrderData($request);

        //insert data to payment_transitions
        $paymentData = [
            'order_id' => $orderId,
            'payment_info_id' => $request->paymentInfoId,
            'created_at' => Carbon::now(),
        ];

        // Usar nombre seguro generado
        $ssFileName = $imageResult['filename'];
        $ssFile->move(public_path().'/uploads/payment/',$ssFileName);
        $paymentData['payment_screenshot'] = $ssFileName;
        PaymentTransition::create($paymentData);

        //all session destroy
        $this->destroySessionData();

        //new order notify to admin
        $this->notifyToAdmin($orderId,'realizó un nuevo pedido');

        return redirect()->route('user#myOrder')->with(['orderSuccess'=>'Pedido realizado exitosamente']);
    }

    //destory session data
    private function destroySessionData(){
        Session::forget('cart');
        Session::forget('coupon');
        Session::forget('subTotal');
    }

    //insert order data to ( order table and orderitems table )
    private function insertOrderData($request){
        $countOrder = CountOrder::first();
        $userId = null;

        if (auth()->check()) {
            $userId = auth()->id();
        }else {
            $documento = null;
            if ($request->input('tipo_persona') === 'N') {
                $name = $request->input('persona_natural.name');
                $email = $request->input('persona_natural.email');
                $phone = $request->input('persona_natural.phone');
                $address = $request->input('persona_natural.address');
                $distrito = $request->input('persona_natural.distrito');
                $documento = $request->input('persona_natural.dni');
            } else {
                $name = $request->input('persona_juridica.razon_social');
                $email = $request->input('persona_juridica.email');
                $phone = $request->input('persona_juridica.phone');
                $address = $request->input('persona_juridica.address');
                $distrito = $request->input('persona_juridica.distrito');
                $documento = $request->input('persona_juridica.ruc');
            }
            $customerData = [
                'customer_type' => $request->input('tipo_persona') === 'N' ? 'natural' : 'juridica',
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'created_at' => Carbon::now(),
            ];

            $user = User::where('email', $email)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($documento),
                    'role' => 'user',
                ]);

                $emailTo = Mail::to($email);
                $ccc = env('MAIL_SEND_CC', 'ti03@emilima.com.pe');
                if ($ccc) {
                    $emailTo->cc($ccc);
                }
                $emailTo->send(new UserCreatedNotification($name, $email, $documento));
            }
            if (!Auth::check()) {
                if ($user) {
                    Auth::login($user);
                }
            }
            $userId = $user->id;

            $customerId = Customer::insertGetId($customerData);
            if ($request->input('tipo_persona') === 'N') {
                $ruc_persona_natural=null;
                if($request->input('tipo_comprobante') === 'F'){
                    $ruc_persona_natural=$request->input('persona_natural.ruc');
                }
                $personaNaturalId = PersonaNatural::insertGetId([
                    'customer_id' => $customerId,
                    'dni' => $request->input('persona_natural.dni'),
                    'ruc' => $ruc_persona_natural,
                    'created_at' => Carbon::now(),
                ]);
            } else {
                $customerRepresentante = [
                    'customer_type' => 'natural',
                    'name' => $request->input('representante_legal.name'),
                    'email' => $request->input('representante_legal.email'),
                    'phone' => $request->input('representante_legal.phone'),
                    'address' => $request->input('representante_legal.address'),
                    'created_at' => Carbon::now(),
                ];

                $representanteId = Customer::insertGetId($customerRepresentante);
                $representanteNaturalId = PersonaNatural::insertGetId([
                    'customer_id' => $representanteId,
                    'dni' => $request->input('representante_legal.dni'),
                    'created_at' => Carbon::now(),
                ]);
                $personaJuridicaId = PersonaJuridica::insertGetId([
                    'customer_id' => $customerId,
                    'ruc' => $request->input('persona_juridica.ruc'),
                    'razon_social' => $request->input('persona_juridica.razon_social'),
                    'representante_legal_id' => $representanteNaturalId,
                    'representante_legal_distrito' => $request->input('representante_legal.distrito'),
                    'created_at' => Carbon::now(),
                ]);
            }
        }

        $data = [
            'user_id' => $userId,
            'customer_id' => $customerId,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'state_division_id' => $request->stateDivisionId,
            'city_id' => $request->cityId,
            'township_id' => $distrito,
            'address' => $address,
            'note' => $request->note,
            'payment_method' => $request->paymentMethod,
            'sub_total' => Session::get('subTotal'),
            'invoice_number' => $countOrder->order_number,
            'order_date' => Carbon::now()->format('d/m/Y'),
            'order_month' => Carbon::now()->locale('es')->format('F'),
            'order_year' => Carbon::now()->format('Y'),
            'tipo_persona' => $request->input('tipo_persona'),
            'tipo_comprobante' => $request->input('tipo_comprobante'),
            'status' => 'pendiente',
            'created_at' => Carbon::now(),
        ];

        if(Session::has('coupon')){
            $coupon = Session::get('coupon');
            $data['coupon_id'] = $coupon['couponId'];
            $data['coupon_discount'] = $coupon['discountAmount'];
            $data['grand_total'] = $coupon['grandTotal'];
        }else{
            $data['grand_total'] = Session::get('subTotal');
        }

        $orderId = Order::insertGetId($data);

        $carts = Session::get('cart');
        foreach($carts as $key => $cart){
            OrderItem::create([
                'order_id' => $orderId,
                'product_id' => $cart['product_id'],
                'product_variant_id' => $key,
                'color_id' => $cart['colorId'] ,
                'size_id' => $cart['sizeId'] ,
                'unit_price' => $cart['price'],
                'quantity'=> $cart['quantity'],
                'total_price' => $cart['price'] * $cart['quantity'],
            ]);
        }
        if($orderId){
            $countOrder->order_number += 1;
            $countOrder->save();
        }
        return $orderId;
    }

    //order notify to admin
    private function notifyToAdmin($orderId,$message){
        $data = Order::where('order_id',$orderId)->with('user')->first();
        $data->message = $message;
        $admin = User::where('role','admin')->get();
        Notification::send($admin, new UserOrderNotification($data));
    }

    public function completePurchase($id, Request $request)
    {
        $transactionToken = $request->input('transactionToken');
        $ordervalida = Order::where('order_id', $id)->firstOrFail();
        if (!Auth::check()) {
            $user = User::find($ordervalida->user_id);
            if ($user) {
                Auth::login($user);
            }
        }
        if ($transactionToken) {
            $responseniubiztrans = $this->processNiubizTransaction($transactionToken, $id);

            if (isset($responseniubiztrans['dataMap'])) {
                $responseCode = $responseniubiztrans['dataMap']['ACTION_CODE'];
                $responseMeg=$responseniubiztrans['dataMap']['ACTION_DESCRIPTION'];

                if ($responseCode == '000') {
                    $this->changeOrderStatus($id, 'pagado', 'confirmed_date');
                    $currencyMap = [
                        '0604' => 'Soles',
                        '0480' => 'Dólares',
                    ];
                    $this->decreaseStock($id);
                    $nropedido=$responseniubiztrans['dataMap']['TRACE_NUMBER'];
                    $fechahorapedido=$responseniubiztrans['dataMap']['TRANSACTION_DATE'];
                    $parsedDate = Carbon::createFromFormat('ymdHis', $fechahorapedido);
                    $formattedDate = $parsedDate->format('d/m/y H:i:s');
                    $montopagado=$responseniubiztrans['dataMap']['AMOUNT'];
                    $tipomoneda = $responseniubiztrans['dataMap']['CURRENCY'];
                    $tipomonedaTexto = $currencyMap[$tipomoneda] ?? 'desconocido';
                    $tarjeta = $responseniubiztrans['dataMap']['CARD'];
                    $tipotarjeta = $responseniubiztrans['dataMap']['BRAND'];

                    $mensajeSuccessFormateado = "<b>Número de pedido:</b> $nropedido<br>" .
                     "<b>Fecha y hora del pedido:</b> $formattedDate<br>" .
                     "<b>Importe pagado:</b> $montopagado<br>" .
                     "<b>Tipo de moneda:</b> $tipomonedaTexto<br>".
                     "<b>Tarjeta:</b> $tarjeta<br>" .
                     "<b>Tipo Tarjeta:</b> $tipotarjeta";

                    $order = Order::where('order_id', $id)->first();
                    Order::where('order_id', $id)->update([
                        'note' => $responseniubiztrans['dataMap']
                    ]);

                    $email = Mail::to($order->email);
                    $cc=env('MAIL_SEND_CC', 'ti03@emilima.com.pe');
                    if ($cc) {
                        $email->cc($cc);
                    }
                    $email->send(new OrderConfirmation($order, $mensajeSuccessFormateado));

                    if (!Auth::check()) {
                        $user = User::find($order->user_id);
                        if ($user) {
                            Auth::login($user);
                        }
                    }
                    return redirect()->route('user#myOrder')->with(['niubizbtnpagorealizado'=>$mensajeSuccessFormateado]);
                } else {
                    return redirect()->back()->with('error', $responseMeg);
                }
            } elseif (isset($responseniubiztrans['data'])) {
                $responseCode = $responseniubiztrans['data']['ACTION_CODE'] ?? null;
                $responseMeg = $responseniubiztrans['data']['ACTION_DESCRIPTION'] ?? 'Descripción no disponible';
                $nropedido = $responseniubiztrans['data']['TRACE_NUMBER'] ?? 'S/N';
                $fechahorapedido = $responseniubiztrans['data']['TRANSACTION_DATE'] ?? null;

                if ($fechahorapedido) {
                    try {
                        $parsedDate = Carbon::createFromFormat('ymdHis', $fechahorapedido);
                        $formattedDate = $parsedDate->format('d/m/y H:i:s');
                    } catch (\Exception $e) {
                        $formattedDate = 'Fecha inválida';
                    }
                } else {
                    $formattedDate = '';
                }

                Order::where('order_id', $id)->update([
                    'note' => $responseniubiztrans['data']
                ]);
                $mensajeErrorFormateado = "<b>Número de pedido:</b> $nropedido<br>" .
                     "<b>Fecha y hora del pedido:</b> $formattedDate<br>" .
                     "<b>Descripción de la denegación:</b> $responseMeg";
                return redirect()->back()->with('error', $mensajeErrorFormateado );
            }
            else {
                $responseMeg = 'Error: formato de respuesta no reconocido';
                return redirect()->back()->with('error', $responseMeg);
            }
        } else {
            return redirect()->back()->with('error', '<b>No</b>  se pudo procesar la transacción. Inténtelo de nuevo. <br>'.$request->input('errorMessage'));
        }
    }

    private function processNiubizTransaction($transactionToken, $id)
    {
        $order = Order::where('order_id', $id)->first();
        $respuesta=$this->niubizService->generateAuthorization($order->grand_total,$order->invoice_number,$transactionToken);
        return $respuesta;
    }

    private function decreaseStock($id)
    {
        $orderItems = OrderItem::where('order_id', $id)->get();
        foreach ($orderItems as $orderItem) {
            $productVariant = ProductVariant::where('product_variant_id', $orderItem->product_variant_id)->first();
            $stock = [
                'available_stock' => $productVariant->available_stock - $orderItem->quantity,
            ];
            ProductVariant::where('product_variant_id', $orderItem->product_variant_id)->update($stock);

            StockHistory::create([
                'product_id' => $productVariant->product_id,
                'product_variant_id' => $productVariant->product_variant_id,
                'quantity' => $orderItem->quantity,
                'note' => 'user order',
                'type' => 'out',
                'created_at' => Carbon::now(),
            ]);
        }
    }

    private function changeOrderStatus($id, $status, $statusDate)
    {
        Order::where('order_id', $id)->update([
            'status' => $status,
            $statusDate => Carbon::now(),
        ]);
    }

    public function registeOrder(Request $request){
        $rules = [
            'tipo_persona' => 'required|in:N,J',
            'tipo_comprobante' => 'required|string',
            'stateDivisionId' => 'required|integer',
            'cityId' => 'required|integer',
            'paymentMethod' => 'required|string',
            'invoice_number'=> 'required|integer',
            'order_date'=> 'required|date',
            'grand_total'=> 'required|numeric',
            'product_id'=> 'required|integer',
            'quantity'=> 'required|integer',
            // PARCHE: Agregar validación para screenshot
            'paymentScreenshot' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ];

        if ($request->input('tipo_persona') === 'N') {
            $rules['persona_natural.dni'] = 'required|string|max:8';
            $rules['persona_natural.email'] = 'required|email';
            $rules['persona_natural.phone'] = 'required|string';
            $rules['persona_natural.name'] = 'required|string';
            $rules['persona_natural.address'] = 'required|string';
            $rules['persona_natural.distrito'] = 'required|integer';
            if ($request->input('tipo_comprobante') === 'F') {
                $rules['persona_natural.ruc'] = 'required|string|digits:11';
            }
        }

        if ($request->input('tipo_persona') === 'J') {
            $rules['persona_juridica.ruc'] = 'required|string|digits:11';
            $rules['persona_juridica.email'] = 'required|email';
            $rules['persona_juridica.phone'] = 'required|string';
            $rules['persona_juridica.razon_social'] = 'required|string';
            $rules['persona_juridica.address'] = 'required|string';
            $rules['persona_juridica.distrito'] = 'required|integer';

            $rules['representante_legal.dni'] = 'required|string|digits:8';
            $rules['representante_legal.name'] = 'required|string';
            $rules['representante_legal.email'] = 'required|email';
            $rules['representante_legal.address'] = 'required|string';
            $rules['representante_legal.phone'] = 'required|string';
            $rules['representante_legal.distrito'] = 'required|integer';
        }

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return back()->withErrors($validation)->withInput();
        }

        if($request->paymentMethod == 'transferencia'){
            $orderId = $this->insertOrderManualData($request);
            $this->notifyToAdmin($orderId,'realizó un nuevo pedido');
            return redirect()->route('admin#order')->with(['orderSuccess'=>'Se registró exitosamente']);
        }
    }

    private function insertOrderManualData($request){
        if ($request->input('tipo_persona') === 'N') {
            $name = $request->input('persona_natural.name');
            $email = $request->input('persona_natural.email');
            $phone = $request->input('persona_natural.phone');
            $address = $request->input('persona_natural.address');
            $distrito = $request->input('persona_natural.distrito');
        } else {
            $name = $request->input('persona_juridica.razon_social');
            $email = $request->input('persona_juridica.email');
            $phone = $request->input('persona_juridica.phone');
            $address = $request->input('persona_juridica.address');
            $distrito = $request->input('persona_juridica.distrito');
        }

        $customerData = [
            'customer_type' => $request->input('tipo_persona') === 'N' ? 'natural' : 'juridica',
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'created_at' => Carbon::now(),
        ];

        $customerId = Customer::insertGetId($customerData);
        if ($request->input('tipo_persona') === 'N') {
            $ruc_persona_natural=null;
            if($request->input('tipo_comprobante') === 'F'){
                $ruc_persona_natural=$request->input('persona_natural.ruc');
            }
            $personaNaturalId = PersonaNatural::insertGetId([
                'customer_id' => $customerId,
                'dni' => $request->input('persona_natural.dni'),
                'ruc' => $ruc_persona_natural,
                'created_at' => Carbon::now(),
            ]);
        } else {
            $customerRepresentante = [
                'customer_type' => 'natural',
                'name' => $request->input('representante_legal.name'),
                'email' => $request->input('representante_legal.email'),
                'phone' => $request->input('representante_legal.phone'),
                'address' => $request->input('representante_legal.address'),
                'created_at' => Carbon::now(),
            ];

            $representanteId = Customer::insertGetId($customerRepresentante);
            $representanteNaturalId = PersonaNatural::insertGetId([
                'customer_id' => $representanteId,
                'dni' => $request->input('representante_legal.dni'),
                'created_at' => Carbon::now(),
            ]);
            $personaJuridicaId = PersonaJuridica::insertGetId([
                'customer_id' => $customerId,
                'ruc' => $request->input('persona_juridica.ruc'),
                'razon_social' => $request->input('persona_juridica.razon_social'),
                'representante_legal_id' => $representanteNaturalId,
                'representante_legal_distrito' => $request->input('representante_legal.distrito'),
                'created_at' => Carbon::now(),
            ]);
        }

        $orderDate = Carbon::createFromFormat('Y-m-d', $request->input('order_date'));
        $mes = $orderDate->format('m');
        $año = $orderDate->format('Y');
        $formattedDate = $orderDate->format('d/m/Y');

        $data = [
            'user_id' => auth()->user()->id,
            'customer_id' => $customerId,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'state_division_id' => $request->stateDivisionId,
            'city_id' => $request->cityId,
            'township_id' => $distrito,
            'address' => $address,
            'note' => $request->note,
            'payment_method' => $request->paymentMethod,
            'sub_total' => $request->input('grand_total'),
            'order_month' => $mes,
            'order_year' => $año,
            'tipo_persona' => $request->input('tipo_persona'),
            'tipo_comprobante' => $request->input('tipo_comprobante'),
            'invoice_number' => $request->input('invoice_number'),
            'order_date' => $formattedDate,
            'grand_total' => $request->input('grand_total'),
            'status' => 'pagado',
            'confirmed_date' => Carbon::now(),
            'created_at' => Carbon::now(),
        ];

        $orderId = Order::insertGetId($data);

        OrderItem::create([
            'order_id' => $orderId,
            'product_id' => $request->input('product_id'),
            'product_variant_id' => $request->input('product_id'),
            'unit_price' => $request->input('grand_total'),
            'quantity'=> $request->input('quantity'),
            'total_price' => $request->input('grand_total'),
        ]);

        // ============================================
        // PARCHE DE SEGURIDAD - Enero 2026
        // ============================================
        if ($request->hasFile('paymentScreenshot')) {
            $ssFile = $request->file('paymentScreenshot');
            
            // Validar imagen de forma segura
            $imageResult = $this->validateAndProcessImage($ssFile);
            if ($imageResult['valid']) {
                $ssFileName = $imageResult['filename'];
                $ssFile->move(public_path('uploads/payment/'), $ssFileName);
                $voucherData = [
                    'order_id' => $orderId,
                    'voucher_image' => $ssFileName,
                    'created_at' => Carbon::now(),
                ];
                $voucherId = VoucherDetail::insertGetId($voucherData);
            } else {
                \Log::warning('Voucher rechazado por seguridad: ' . $imageResult['error']);
            }
        }

        return $orderId;
    }
}
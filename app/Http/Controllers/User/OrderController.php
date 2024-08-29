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
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Notifications\UserOrderNotification;
use Illuminate\Support\Facades\Notification;
use App\Services\NiubizService;

class OrderController extends Controller
{
    protected $niubizService;
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
    public function createOrder(Request $request){ dd($request);
        //empty cart checking
        if(Session::has('cart')){
            if(count(Session::get('cart')) == 0){
                return back()->with(['error'=>'¡Tu carrito está vacío!']);
            }
        }else{
            return back()->with(['error'=>'¡Tu carrito está vacío!']);
        }
        //validation
        $validation = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'stateDivisionId' => 'required',
            'cityId' => 'required',
            'townshipId' => 'required',
            'address' => 'required',
            'paymentMethod' => 'required',
        ]);//dd($request);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        //cash on delivery
        if($request->paymentMethod == 'tarjeta'){

            $checkCos = CashOnDelivery::where('status','1')->where('township_id',$request->townshipId)->exists();
            if(!$checkCos){
                return back()->with(['error'=>'El pago contra reembolso no está disponible actualmente para su ubicación. ¡Elija otra!']);
            }

            //insert data to order table and order items table
            $orderId = $this->insertOrderData($request);

            //all session destroy
            $this->destroySessionData();

            //new order notify to admin
            $this->notifyToAdmin($orderId,'realizó un nuevo pedido');

            return redirect()->route('user#misPagos', ['id' => $orderId])->with(['orderSuccess'=>'Orden exitosamente']);
            //return redirect()->route('user#myOrder')->with(['orderSuccess'=>'Orden exitosamente']);

        }
        //cash
        $checkPaymentMethod = PaymentInfo::where('status','1')->where('type',$request->paymentMethod)->exists();
        if(!$checkPaymentMethod){
            return back()->with(['error'=>'Este método de pago no está disponible actualmente. ¡Elija otro!']);
        }

        $data = $request->all();

        return view('frontEnd.payment')->with(['data'=>$data]);
    }


    //confirm payment
    public function confirmPayment(Request $request){
        $validation = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'stateDivisionId' => 'required',
            'cityId' => 'required',
            'townshipId' => 'required',
            'address' => 'required',
            'paymentMethod' => 'required',
            'paymentScreenshot' => 'required',
            'paymentInfoId' => 'required',
        ]);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

         //insert data to order table and order items table
        $orderId = $this->insertOrderData($request);

        //insert data to payment_transitions
        $paymentData = [
            'order_id' => $orderId,
            'payment_info_id' => $request->paymentInfoId,
            'created_at' => Carbon::now(),
        ];
        $ssFile = $request->file('paymentScreenshot');
        $ssFileName = uniqid().'-'.$ssFile->getClientOriginalName();
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
        //insert data to order table 'MYSHOP'.'-'. //mt_rand(10000000,99999999),
        $countOrder = CountOrder::first();
        $data = [
            'user_id' => auth()->user()->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'state_division_id' => $request->stateDivisionId,
            'city_id' => $request->cityId,
            'township_id' => $request->townshipId,
            'address' => $request->address,
            'note' => $request->note,
            'payment_method' => $request->paymentMethod,
            'sub_total' => Session::get('subTotal'),
            'invoice_number' => $countOrder->order_number,
            'order_date' => Carbon::now()->format('d/m/Y'),
             'order_month' => Carbon::now()->locale('es')->format('F'),
             'order_year' => Carbon::now()->format('Y'),
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

          //insert data to order items
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
        //notification
        $data = Order::where('order_id',$orderId)->with('user')->first();
        $data->message = $message;

        //notify to all admin
        $admin = User::where('role','admin')->get();
        Notification::send($admin, new UserOrderNotification($data));
    }

    public function completePurchase($id, Request $request)
    {
        // Obtener el token de transacción y otros datos de la solicitud
        $transactionToken = $request->input('transactionToken');
        // Aquí puedes procesar la transacción con Niubiz y manejar los errores
        if ($transactionToken) {
            // Simulación de la respuesta de la API de Niubiz (debes reemplazar esto con la integración real)
            $responseniubiztrans = $this->processNiubizTransaction($transactionToken, $id);
            if (isset($responseniubiztrans['dataMap'])) {
                $responseCode = $responseniubiztrans['dataMap']['ACTION_CODE'];
                $responseMeg=$responseniubiztrans['dataMap']['ACTION_DESCRIPTION'];
                // Manejo de diferentes códigos de respuesta
                if ($responseCode == '000') {
                    // Código 000 significa transacción exitosa
                    $this->changeOrderStatus($id, 'pagado', 'confirmed_date');

                    // Disminuir stock de productos
                    $this->decreaseStock($id);

                    // Redirigir a la página de éxito
                    return redirect()->route('user#myOrder')->with('status', 'Compra completada con éxito.');
                } else {
                    return redirect()->back()->with('error', $responseMeg);
                }
            } elseif (isset($responseniubiztrans['data'])) {
                $responseCode = $responseniubiztrans['data']['ACTION_CODE'];
                $responseMeg=$responseniubiztrans['data']['ACTION_DESCRIPTION'];
                return redirect()->back()->with('error', $responseMeg);
            }
            else {
                // Puedes lanzar una excepción, retornar un error específico o manejarlo de otra manera
                $responseMeg = 'Error: formato de respuesta no reconocido';
                return redirect()->back()->with('error', $responseMeg);
            }
        } else {
            // En caso de que no se reciba el token
            return redirect()->back()->with('error', 'No se pudo procesar la transacción. Inténtelo de nuevo.');
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

            // Historial de stock
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


}

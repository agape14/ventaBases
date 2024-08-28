<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\City;
use App\Models\Coupon;
use App\Models\Township;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\NiubizService;
use Illuminate\Http\Request;
use App\Models\StateDivision;
use App\Models\CompanySetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    protected $niubizService;
    public function __construct(NiubizService $niubizService)
    {
        $this->niubizService = $niubizService;
    }

    //checkout page
    public function checkoutPage(){
        /*
        $carts = Session::get('cart');
        $amount = 0;
        $subtotal=0;
        foreach($carts as $key => $cart){
            $productSubtotal = floatval($cart['price']) * intval($cart['quantity']);
            $subtotal += $productSubtotal;
        }
        $amount=$subtotal;
        $detallePago = "Detalle de pago";
        $token = $this->niubizService->generateToken();
        $sessionKey = $this->niubizService->generateSesion($amount, $token);
        $purchaseNumber = $this->niubizService->generatePurchaseNumber();
        */
        $stateDivisions = StateDivision::get();
        return view('frontEnd.checkout')->with(['stateDivisions'=>$stateDivisions]);
        //return view('frontEnd.checkout', compact('amount', 'detallePago', 'sessionKey', 'purchaseNumber'))->with(['stateDivisions'=>$stateDivisions]);
    }

    //get city ajax
    public function getCity(Request $request){
        $cities = City::where('state_division_id',$request->id)->get();
        return response()->json([
            'cities' => $cities,
        ]);
    }

    //get township ajax
    public function getTownship(Request $request){
        $townships = Township::where('city_id',$request->id)->get();
        return response()->json([
            'townships' => $townships,
        ]);
    }

    public function pagarconfirmar($id,Request $request){ //dd($id);
        if($id){
            $order = Order::where('order_id',$id)->with(['stateDivision','city','township','user','paymentTransition'])->first();
            if($order->status=='pendiente'){
                $orderItems = OrderItem::where('order_id',$id)->with(['product','color','size'])->get();
                $companyInfo = CompanySetting::orderBy('id','desc')->first();
                $token = $this->niubizService->generateToken();
                $sessionKey = $this->niubizService->generateSesion($order->grand_total, $token);
                return view('frontEnd.profile.payment', compact('sessionKey'))->with(['order'=>$order,'orderItems'=>$orderItems,'companyInfo'=>$companyInfo]);
            }else if($order->status=='confirmed'){
                return redirect()->route('user#myOrder')->with('status', 'La compra ya se realizó anteriormente');
            }else{
                return redirect()->route('user#myOrder')->with('status', 'El estado de la compra no corresponde para pago');
            }
        }else{
            return redirect()->route('user#myOrder')->with('status', 'El codigo de la compra no corresponde para pago');
        }
    }


}

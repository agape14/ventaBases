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
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    protected $niubizService;
    public function __construct(NiubizService $niubizService)
    {
        $this->niubizService = $niubizService;
    }

    //checkout page
    public function checkoutPage()
    {
        $stateDivisions = StateDivision::get();
        $user = Auth::user(); // Usuario autenticado

        return view('frontEnd.checkout', [
            'stateDivisions' => $stateDivisions,
            'user' => $user,
        ]);
    }
    //get city ajax
    public function getCity(Request $request){
        //$cities = City::where('state_division_id',$request->id)->get();
        $cities = City::where('city_id',1)->get();
        return response()->json([
            'cities' => $cities,
        ]);
    }

    //get township ajax
    public function getTownship(Request $request){
        //$townships = Township::where('city_id',$request->id)->get();
        $townships = Township::where('city_id',1)->orderBy('name','asc')->get();
        return response()->json([
            'townships' => $townships,
        ]);
    }

    public function pagarconfirmar($id,Request $request){
        if($id){
            $order = Order::where('order_id',$id)->with(['stateDivision','city','township','user','paymentTransition'])->first();
            if($order->status=='pendiente'){
                $orderItems = OrderItem::where('order_id',$id)->with(['product','color','size'])->get();
                $companyInfo = CompanySetting::orderBy('id','desc')->first();
                $token = $this->niubizService->generateToken();
                $sessionKey = $this->niubizService->generateSesion($order->grand_total, $token,$order->email,$order->customer_id,$order->created_at);
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

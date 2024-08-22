<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\City;
use App\Models\Coupon;
use App\Models\Township;
use App\Services\NiubizService;
use Illuminate\Http\Request;
use App\Models\StateDivision;
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
        $carts = Session::get('cart');
        $amount = 0; // Aquí defines el monto que vas a cobrar
        $subtotal=0;
        foreach($carts as $key => $cart){
            $productSubtotal = floatval($cart['price']) * intval($cart['quantity']);
            $subtotal += $productSubtotal;
        }
        $amount=$subtotal;
        //dd($carts,$subtotal,$amount);
        $detallePago = "Detalle de pago";

        $token = $this->niubizService->generateToken();
        $sessionKey = $this->niubizService->generateSesion($amount, $token);

        $purchaseNumber = $this->niubizService->generatePurchaseNumber();

        $stateDivisions = StateDivision::get();
        //return view('checkout', compact('amount', 'detallePago', 'sessionKey', 'purchaseNumber'));
        return view('frontEnd.checkout', compact('amount', 'detallePago', 'sessionKey', 'purchaseNumber'))->with(['stateDivisions'=>$stateDivisions]);
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




}

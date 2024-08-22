<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransition;
use Illuminate\Http\Request;

class PaymentTransitionController extends Controller
{
    //index
    public function index(){
        $data = PaymentTransition::with('order','order.user','paymentInfo')->orderBy('id','DESC')->get();
        return view('admin.paymentTransition.index',compact('data'));
    }

    //show
    public function showPaymentTransition($id){
        $paymentTransition = PaymentTransition::where('id',$id)->with('order','order.user','paymentInfo')->first();
        return view('admin.paymentTransition.show')->with(['paymentTransition'=>$paymentTransition]);
    }

}
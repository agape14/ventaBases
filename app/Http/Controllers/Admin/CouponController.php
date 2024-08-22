<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class CouponController extends Controller
{
    //index
    public function index(){
        $data = Coupon::withCount('order')->get();
        return view('admin.coupon.index')->with(['data'=>$data]);
    }

    //create
    public function createCoupon(){
        return view('admin.coupon.create');
    }

    //store
    public function storeCoupon(Request $request){
        $validaion = $this->validateRequestCouponData($request);
        if($validaion->fails()){
            return back()->withErrors($validaion)->withInput();
        }
        $data = $this->requestCouponData($request);
        Coupon::create($data);
        return redirect()->route('admin#coupon')->with(['success'=>'Coupon created successfully']);
    }

    //edit
    public function editCoupon($id){
        $coupon = Coupon::where('coupon_id',$id)->first();
        $data = Coupon::get();
        return view('admin.coupon.edit')->with(['coupon'=>$coupon,'data'=>$data]);
    }

    //update
    public function updateCoupon($id,Request $request){
        $validaion = $this->validateRequestCouponData($request);
        if($validaion->fails()){
            return back()->withErrors($validaion)->withInput();
        }
        $updateData = $this->requestCouponData($request);
        Coupon::where('coupon_id',$id)->update($updateData);
        return redirect()->route('admin#coupon')->with(['success'=>'Coupon updated successfully']);
    }

    //delete
    public function deleteCoupon($id){
        Coupon::where('coupon_id',$id)->delete();
        return redirect()->route('admin#coupon')->with(['success'=>'Coupon Deleted successfully']);
    }

    //validaion
    private function validateRequestCouponData($request){
        return  Validator::make($request->all(),[
            'couponCode' => 'required',
            'couponDiscount' => 'required',
            'startDate' => 'required',
            'endDate' => 'required|after:startDate'
        ]);
    }
    //get request coupon data
    private function requestCouponData($request){
        return [
            'coupon_code' => strtoupper($request->couponCode),
            'coupon_discount' => $request->couponDiscount,
            'start_date' => $request->startDate,
            'end_date' => $request->endDate,
            'created_at' => Carbon::now(),
        ];
    }
}
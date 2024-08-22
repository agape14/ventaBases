<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    //index
    public function index(){
        $data = ProductReview::with('product','user')->get();
        return view('admin.productReview.index')->with(['data'=>$data]);
    }

    //pending review
    public function pendingReview(){
        $data = ProductReview::where('status','0')->with('product','user')->get();
        return view('admin.productReview.index')->with(['data'=>$data]);
    }
    //approve review
    public function approveReview($id){
        ProductReview::where('product_review_id',$id)->update([
            'status' => '1'
        ]);
        return redirect()->route('admin#productReview');
    }

    //review detail
    public function showReview($id){
        $review = ProductReview::where('product_review_id',$id)->with('product','user')->first();
        return view('admin.productReview.detail')->with(['review'=>$review]);
    }

    //delete reveiw
    public function deleteReview($id){
        ProductReview::where('product_review_id',$id)->delete();
        return redirect()->route('admin#productReview');
    }
}

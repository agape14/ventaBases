<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    //store review
    public function storeReview($id,Request $request){
        $request->validate([
            'title'=>'required',
            'comment'=>'required'
        ]);
        $data = [
          'title' => $request->title,
          'comment' => $request->comment,
          'user_id' => Auth::user()->id,
          'product_id' => $id,
          'created_at' => Carbon::now()
        ];

        ProductReview::create($data);
        return back()->with(['success'=>'Your Review will prove by Admin']);
    }
}
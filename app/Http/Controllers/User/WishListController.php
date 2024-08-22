<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\WishList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WishListController extends Controller
{
    //index page
    public function index(){
        return view('frontEnd.wishlist');
    }

    //get wish list ajax
    public function getWishlist(){
        $wishlists = WishList::select('wish_lists.*','products.*')
                            ->join('products','products.product_id','wish_lists.product_id')
                            ->where('user_id',auth()->user()->id)
                            ->get();
        return response()->json([
            'wishlists' => $wishlists,
        ]);
    }

    //create wishlist
    public function addWishlist(Request $request,$id){
        $wishList = WishList::where('user_id',auth()->user()->id)->where('product_id',$id)->first();
        if(!isset($wishList)){
            $data = [
                'user_id' => auth()->user()->id,
                'product_id' => $id,
                'created_at' => Carbon::now(),
            ];
        }else{
            return response()->json([
                'error' => 'Already added to wishlist',
            ]);
        }
        WishList::create($data);
        return response()->json([
            'success' => 'Added to wishlist successfully',
        ]);
    }

    //deleteWishlist
    public function deleteWishlist($id){
        if(auth()->check()){
            WishList::where('user_id',auth()->user()->id)->where('wish_list_id',$id)->delete();
        }
        return response()->json([
            'success'=>'wishlist deleted successfully',
        ]);
    }
}
<?php

namespace App\Http\Controllers\FrontEnd;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    //index
    public function index(){
        $user = User::where('id',auth()->user()->id)->first();
        return view('frontEnd.profile.index')->with(['user'=>$user]);
    }

    //edit profile
    public function updateProfile(Request $request){
        $validation = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
        ]);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if($request->hasFile('photo')){
            $newFile = $request->file('photo');
            $newFileName = uniqid().'-'.$newFile->getClientOriginalName();

            //get old file
            $oldFileName = User::where('id',auth()->user()->id)->value('profile_photo_path');
            if(!empty($oldFileName)){
                if(File::exists(public_path().'/uploads/user/'.$oldFileName)){
                    File::delete(public_path().'/uploads/user/'.$oldFileName);
                }
                $newFile->move(public_path().'/uploads/user/',$newFileName);
                $updateData['profile_photo_path'] = $newFileName;
            }else{
                $newFile->move(public_path().'/uploads/user/',$newFileName);
                $updateData['profile_photo_path'] = $newFileName;
            }
        }

        User::where('id',Auth::user()->id)->update($updateData);
        return back()->with(['success'=>'Your Profile updated successfully']);
    }

    //edit password
    public function editPassword(){
        return view('frontEnd.profile.editPassword');
    }

    //update password
    public function updatePassword(Request $request){
        $validation = Validator::make($request->all(),[
            'oldPassword' => 'required',
            'newPassword' => 'required|min:8',
            'confirmPassword' => 'required|min:8'
        ]);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        $oldPassword = $request->oldPassword;
        $newPassword = $request->newPassword;
        $confirmPassword = $request->confirmPassword;

        $user = User::where('id',auth()->user()->id)->first();
        $hashOldPassword = $user->password;

        if(Hash::check($oldPassword,$hashOldPassword)){
            if($newPassword == $confirmPassword){
                User::where('id',auth()->user()->id)->update([
                    'password' => Hash::make($newPassword)
                ]);
                return back()->with(['success'=> 'Your password changed successfully']);
            }else{
                return back()->with(['message' => 'new password and confirm password do not match']);
            }
        }else{
            return back()->with(['message' => 'your old password does not match']);
        }

    }

    //user review
    public function myReview(){
        $reviews = ProductReview::where('user_id',auth()->user()->id)->with('product')->get();
        return view('frontEnd.profile.myReview')->with('reviews',$reviews);
    }

    //order lists
    public function myOrder(){
        $orders = Order::where('user_id',auth()->user()->id)->get();
        return view('frontEnd.profile.myOrder')->with(['orders'=>$orders]);
    }

    //order detail
    public function orderDetail($id){
        $order = Order::where('order_id',$id)->with(['stateDivision','city','township'])->first();
        $orderItems = OrderItem::where('order_id',$id)->with(['product','color','size'])->get();
        return view('frontEnd.profile.orderDetail')->with(['order'=>$order,'orderItems'=>$orderItems]);
    }

    //download invoice
    public function downloadInvoice($orderId){
        $order = Order::where('order_id',$orderId)->with(['stateDivision','city','township'])->first();
        $orderItems = OrderItem::where('order_id',$orderId)->with(['product','color','size'])->get();

        $pdf = Pdf::loadView('frontEnd.profile.invoice',compact('order','orderItems'))->setPaper('a4')->setOptions([
            'tempDir' => public_path(),
            'chroot' => public_path(),
        ]);
        return $pdf->download('invoice.pdf');
    }
}
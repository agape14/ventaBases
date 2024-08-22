<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    //index
    public function index(){
        $data = User::where('id',Auth::user()->id)->first();
        return view('admin.profile.editProfile')->with(['data'=>$data]);
    }

    //edit profile
    public function editProfile(Request $request){
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

    //edit password page
    public function editPassword(){
        return view('admin.profile.editPassword');
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
}
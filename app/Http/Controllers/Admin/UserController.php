<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    //index
    public function userList(){
        $data = User::where('role','user')->get();
        return view('admin.user.userList')->with(['data'=>$data]);
    }

    //admin list
    public function adminList(){
        $data = User::where('role','admin')->get();
        return view('admin.user.adminList')->with(['data'=>$data]);
    }

    //edit user page
    public function editUser($id){
        $user = User::where('id',$id)->first();
        return view('admin.user.edit')->with(['user'=>$user]);
    }

    //update user
    public function updateUser($id,Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'role' => 'required',
        ]);
        User::where('id',$id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role
        ]);
        return redirect()->route('admin#userList')->with(['success'=>'User Data Updated Successfully']);
    }

    //delete user
    public function deleteUser($id){
        User::where('id',$id)->delete();
        return back()->with(['success'=>'User deleted successfully']);
    }
}
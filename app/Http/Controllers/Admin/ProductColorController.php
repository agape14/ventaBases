<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductColorController extends Controller
{
    //
    public function index(){
        $data = ProductColor::get();
        return view('admin.productColor.index',compact('data'));
    }

    public function createColor(Request $request){
        $validation = Validator::make($request->all(),[
            'name' => 'required',
        ]);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        $data = [
            'name' => $request->name,
        ];
        ProductColor::create($data);

        return back()->with(['success'=>'Color created successfully']);
    }

    public function editColor($id){
        $color = ProductColor::where('color_id',$id)->first();
        $data = ProductColor::get();
        return view('admin.productColor.edit',)->with(['color'=>$color,'data'=>$data]);
    }

    public function updateColor(Request $request,$id){
        $validation = Validator::make($request->all(),[
            'name' => 'required',
        ]);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        $updateData = [
            'name' => $request->name,
        ];
        ProductColor::where('color_id',$id)->update($updateData);

        return redirect()->route('admin#color')->with(['success'=>'Color updated successfully']);
    }

    public function deleteColor($id){
        ProductColor::where('color_id',$id)->delete();
        return redirect()->route('admin#color')->with(['success'=>'color deleted successfully']);
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductSize;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductSizeController extends Controller
{
    //
    public function index(){
        $data = ProductSize::get();
        return view('admin.productSize.index',compact('data'));
    }

    public function createSize(Request $request){
        $validation = Validator::make($request->all(),[
            'name' => 'required',
        ]);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        $data = [
            'name' => $request->name,
        ];
        ProductSize::create($data);

        return back()->with(['success'=>'Size created successfully']);
    }

    public function editSize($id){
        $size = ProductSize::where('size_id',$id)->first();
        $data = ProductSize::get();
        return view('admin.productSize.edit',)->with(['size'=>$size,'data'=>$data]);
    }

    public function updateSize(Request $request,$id){
        $validation = Validator::make($request->all(),[
            'name' => 'required',
        ]);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        $updateData = [
            'name' => $request->name,
        ];
        ProductSize::where('size_id',$id)->update($updateData);

        return redirect()->route('admin#size')->with(['success'=>'Size updated successfully']);
    }

    public function deleteSize($id){
        ProductSize::where('size_id',$id)->delete();
        return redirect()->route('admin#size')->with(['success'=>'Size deleted successfully']);
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    //redirect to index page
    public function index(){
        $data = Brand::get();
        return view('admin.brand.index')->with(['data'=>$data]);
    }

    //create brand
    public function createBrand(Request $request){
        //validation
        $validation = Validator::make($request->all(),[
            'name' => 'required',
            'image' => 'required',
        ]);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        //get image
        $file = $request->file('image');
        $fileName = uniqid().'_'.$file->getClientOriginalName();

        //get data
        $data = [
          'name' => $request->name,
           'image' => $fileName,
        ];

        //store data
        $file->move(public_path().'/uploads/brands/',$fileName);
        Brand::create($data);

        return back()->with(['success'=>'New brand added successfully']);
    }

    //edit page
    public function editBrand($id){
        $brand = Brand::where('brand_id',$id)->first();
        $data = Brand::get();
        return view('admin.brand.edit')->with(['brand'=>$brand,'data'=>$data]);
    }

    //update brand
    public function updateBrand(Request $request,$id){
        //validation
        $validation = Validator::make($request->all(),[
            'name' => 'required',
        ]);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        $data = [
            'name' => $request->name,
        ];

        //check image
        if($request->hasFile('image')){
            //delete old image
            $brand = Brand::where('brand_id',$id)->first();
            $oldFileName = $brand->image;
            if(File::exists(public_path().'/uploads/brands/'.$oldFileName)){
                File::delete(public_path().'/uploads/brands/'.$oldFileName);
            }

            //update new image
            $file = $request->file('image');
            $fileName = uniqid().'_'.$file->getClientOriginalName();
            $file->move(public_path().'/uploads/brands/',$fileName);

            $data['image'] = $fileName;
        }

        Brand::where('brand_id',$id)->update($data);

        return redirect()->route('admin#brand')->with(['success'=>'Brand updated successfully']);
    }

    //delete brand
    public function deleteBrand($id){
        //delete image
        $brand = Brand::where('brand_id',$id)->first();
        $fileName = $brand->image;
        if(File::exists(public_path().'/uploads/brands/'.$fileName)){
            File::delete(public_path().'/uploads/brands/'.$fileName);
        }

        //delete data from db
        Brand::where('brand_id',$id)->delete();

        return redirect()->route('admin#brand')->with(['success'=>'Brand deleted successfully']);
    }
}
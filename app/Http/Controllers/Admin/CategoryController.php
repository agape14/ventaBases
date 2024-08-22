<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    //direct to category page
    public function index(){
        $data = Category::get();
        return view('admin.category.index')->with(['data'=>$data]);
    }

    //create category
    public function createCategory(Request $request){
        //validation
        $validation = Validator::make($request->all(),[
            'name' => 'required',
            'image' => 'required'
        ]);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }
        //get image data
        $file = $request->file('image');
        $fileName = uniqid().'_'.$file->getClientOriginalName();

        //get data
        $data = [
            'name'=> $request->name,
            'image' => $fileName,
        ];

        //store data
        $file->move(public_path().'/uploads/category/',$fileName);
        Category::create($data);

        return back()->with(['success'=>'Category created successfully']);
    }

    //redirect edit page
    public function editCategory($id){
        $category = Category::where('category_id',$id)->first();
        $data = Category::get();
        return view('admin.category.edit')->with(['category'=>$category,'data'=>$data]);
    }

    //update category
    public function updateCategory(Request $request,$id){
        //validation
        $validation = Validator::make($request->all(),[
            'name' => 'required',
        ]);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        $updateData = [
            'name' => $request->name,
        ];
        //check image
        if($request->hasFile('image')){
            //delete old image
            $category = Category::where('category_id',$id)->first();
            $oldFileName = $category->image;
            if(File::exists(public_path().'/uploads/category/'.$oldFileName)){
                File::delete(public_path().'/uploads/category/'.$oldFileName);
            };

            //store new image to folder
            $file = $request->file('image');
            $newFileName = uniqid().'_'.$file->getClientOriginalName();
            $file->move(public_path().'/uploads/category/',$newFileName);

            $updateData['image'] = $newFileName;
        }

        Category::where('category_id',$id)->update($updateData);

        return redirect()->route('admin#category')->with(['success'=>'Category updated successfully']);
    }

    //delete category
    public function deleteCategory($id){
        //delete image
        $category = Category::where('category_id',$id)->first();
        $fileName = $category->image;
        if(File::exists(public_path().'/uploads/category'.$fileName)){
            File::delete(public_path().'/uploads/category/'.$fileName);
        }

        //delete db data
        Category::where('category_id',$id)->delete();
        return redirect()->route('admin#category')->with(['success'=>'Category deleted successfully']);
    }

}
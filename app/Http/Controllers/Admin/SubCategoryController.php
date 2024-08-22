<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    //redirect to index page
    public function index(){
        $data = SubCategory::get();
        $categories = Category::get();
        return view('admin.subCategory.index')->with(['data'=>$data,'categories'=>$categories]);
    }

    //create subcategroy
    public function createSubCategory(Request $request){
        //validation
        $validation = $this->subCategoryValidation($request);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        //get data
        $data = $this->requestSubCategoryData($request);

        //store data
        SubCategory::create($data);
        return back()->with(['success'=>'Subcategory created successfully']);

    }

    //redirect edit page
    public function editSubCategory($id){
        $subCategory = SubCategory::where('subcategory_id',$id)->first();
        $data = SubCategory::get();
        $categories = Category::get();
        return view('admin.subCategory.edit')->with(['subCategory'=>$subCategory,'data'=>$data,'categories'=>$categories]);
    }

    //update data
    public function updateSubCategory(Request $request,$id){
        //validation
        $validation = $this->subCategoryValidation($request);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        //getdata
        $updateData = $this->requestSubCategoryData($request);

        //update data
        SubCategory::where('subcategory_id',$id)->update($updateData);
        return redirect()->route('admin#subCategory')->with(['success'=>'SubCategory updated successfully']);
    }

    //delete sub cat
    public function deleteSubCategory($id){
        SubCategory::where('subcategory_id',$id)->delete();
        return redirect()->route('admin#subCategory')->with(['success'=>'SubCategory deleted successfully']);
    }

    //sub cat validation
    private function subCategoryValidation($request){
        return Validator::make($request->all(),[
            'name' => 'required',
            'categoryId' => 'required',
        ]);
    }

    //get request data
    private function requestSubCategoryData($request){
        return [
            'name' => $request->name,
            'category_id' => $request->categoryId,
        ];
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Models\SubSubCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SubSubCategoryController extends Controller
{
    //redirect to index page
    public function index(){
        $data = SubSubCategory::get();
        $categories = Category::get();
        return view('admin.subsubCategory.index')->with(['data'=>$data,'categories'=>$categories]);
    }

    //get subCategory ajax for create form
    public function getSubCategory(Request $request){
        $subCategories = SubCategory::where('category_id',$request->id)->get();
        return response()->json([
            'subCategories' => $subCategories,
        ]);
    }

    //create subsubcat
    public function createSubSubCat(Request $request){
        //validation
        $validation = $this->subSubCatValidation($request);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        //get data
        $data = $this->requestSubSubCatData($request);

        //store data
        SubSubCategory::create($data);

        return back()->with(['success'=>'sub-subcategory created successfully']);
    }

    //redirect edit page
    public function editSubSubCat($id){
        $subsubCategory = SubSubCategory::where('subsubcategory_id',$id)->first();
        //for list table
        $data = SubSubCategory::get();
        $categories = Category::get();
        //for subcat
        $subCategories = SubCategory::where('category_id',$subsubCategory->category_id)->get();
        return view('admin.subsubCategory.edit')->with([
            'subsubCategory'=>$subsubCategory,
            'subCategories'=>$subCategories,
            'data'=>$data,
            'categories'=>$categories
        ]);
    }

    //update data
    public function updateSubSubCat(Request $request,$id){
        //validation
        $validation = $this->subSubCatValidation($request);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        //get data
        $updateData = $this->requestSubSubCatData($request);

        //update data
        SubSubCategory::where('subsubcategory_id',$id)->update($updateData);
        return redirect()->route('admin#subSubCat')->with(['success'=>'sub-subcategory updated successfully']);
    }

    //delete data
    public function deleteSubSubCat($id){
        SubSubCategory::where('subsubcategory_id',$id)->delete();
        return redirect()->route('admin#subSubCat')->with(['success'=>'sub-subcategory deleted successfully']);
    }

    //subsubcat validation
    private function subSubCatValidation($request){
        return Validator::make($request->all(),[
            'name' => 'required',
            'categoryId' => 'required',
            'subCategoryId' => 'required'
        ]);
    }

    //request subsubcat data
    private function requestSubSubCatData($request){
        return [
            'name' => $request->name,
            'category_id' => $request->categoryId,
            'subcategory_id' => $request->subCategoryId,
        ];
    }
}
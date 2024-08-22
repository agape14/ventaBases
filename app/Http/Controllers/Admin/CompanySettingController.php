<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\CompanySetting;
use PhpParser\Node\Expr\FuncCall;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class   CompanySettingController extends Controller
{
    //index
    public function index(){
        $data = CompanySetting::orderBy('id','desc')->get();
        return view('admin.companySetting.index')->with(['data'=>$data]);
    }

    //create
    public function createCompanySetting(){
        return view('admin.companySetting.create');
    }

    //store
    public function storeCompanySetting(Request $request){
        //validataion
        $validation = $this->validateRequestData($request,'required|');
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }
        $data = $this->getRequestData($request);
        //for logo
        if($request->hasFile('logo')){
            $file = $request->file('logo');
            $fileName = uniqid().'-'.$file->getClientOriginalName();

            $file->move(public_path().'/uploads/logo/',$fileName);
            $data['logo'] = $fileName;
        }

        $data['created_at'] = Carbon::now();
        CompanySetting::create($data);

        return redirect()->route('admin#companySetting')->with(['success'=> 'company setting created successfully']);
    }

    //edit
    public function editCompanySetting($id){
        $companySetting = CompanySetting::where('id',$id)->first();
        return view('admin.companySetting.edit')->with(['companySetting' => $companySetting]);
    }

    //update
    public function updateCompanySetting($id,Request $request){
        //validation
        $validation = $this->validateRequestData($request,'');
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        $updateData = $this->getRequestData($request);

        //for logo
        if($request->hasFile('logo')){
            //delete old file
            $setting = CompanySetting::select('logo')->where('id',$id)->first();
            $oldFileName = $setting->logo;
            if(File::exists(public_path().'/uploads/logo/'.$oldFileName)){
                File::delete(public_path().'/uploads/logo/'.$oldFileName);
            }

            //save new file
            $newFile = $request->file('logo');
            $newFileName = uniqid().'-'.$newFile->getClientOriginalName();
            $newFile->move(public_path().'/uploads/logo/',$newFileName);
            $updateData['logo'] = $newFileName;
        }
        CompanySetting::where('id',$id)->update($updateData);
        return redirect()->route('admin#companySetting')->with(['success'=>'company setting updated successfully']);
    }

    //delete
    public function deleteCompanySetting($id){
        //check
        $data = CompanySetting::get();
        if($data->count() != 1){
            CompanySetting::where('id',$id)->delete();
            return back()->with(['success'=>'company setting deleted successfully']);
        }
    }

    //get request data
    private function getRequestData($request){
        $data = [
            'company_name' => $request->companyName,
            'phone_one' => $request->phoneOne,
            'phone_two' => $request->phoneTwo,
            'address' => $request->address,
            'email' => $request->email,
        ];
        if($request->facebook){
            $data['facebook'] = $request->facebook;
        }
        if($request->youtube){
            $data['youtube'] = $request->youtube;
        }
        if($request->linkedin){
            $data['linkedin'] = $request->linkedin;
        }
        return $data;
    }

    //validate for request data
    private function validateRequestData($request,$logo){
        return Validator::make($request->all(),[
            'companyName' => 'required',
            'logo' => $logo.'mimes:jpeg,png,jpg,gif,svg|dimensions:ratio=1/1',
            'phoneOne' => 'required',
            //'phoneTwo' => 'required',
            'address' => 'required',
            'email' => 'required',
        ],[
            'image.dimensions' => 'Image ratio must be square.',
        ]);
    }


}

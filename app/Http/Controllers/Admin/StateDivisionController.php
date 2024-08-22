<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StateDivision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StateDivisionController extends Controller
{
    //index
    public function index(){
        $data = StateDivision::get();
        return view('admin.stateDivision.index',compact('data'));
    }

    //create
    public function createStateDivision(Request $request){
        $validation = Validator::make($request->all(),[
            'name' => 'required',
        ]);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        $data = [
            'name' => $request->name,
        ];
        StateDivision::create($data);
        return back()->with(['success'=>'State Division created successfully']);
    }

    //edit
    public function editStatedivision($id){
        $stateDivision = StateDivision::where('state_division_id',$id)->first();
        $data = StateDivision::get();
        return view('admin.stateDivision.edit')->with(['stateDivision' => $stateDivision,'data' => $data]);
    }

    //update
    public function updateStatedivision($id,Request $request){
        $validation = Validator::make($request->all(),[
            'name' => 'required',
        ]);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        $updateData = [
            'name' => $request->name,
        ];
        StateDivision::where('state_division_id',$id)->update($updateData);
        return redirect()->route('admin#statedivision')->with(['success'=>'State Division updated successfully']);
    }

    //delete
    public function deleteStatedivision($id){
        StateDivision::where('state_division_id',$id)->delete();
        return redirect()->route('admin#statedivision')->with(['success'=>'State Division deleted successfully']);
    }
}
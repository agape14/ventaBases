<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\StateDivision;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    //index
    public function index(){
        $data = City::select('cities.*','state_divisions.name as stateDivisionName')
                        ->leftJoin('state_divisions','state_divisions.state_division_id','cities.state_division_id')
                        ->get();
        $stateDivisions = StateDivision::get();
        return view('admin.city.index')->with(['stateDivisions'=>$stateDivisions,'data'=>$data]);
    }

    //create
    public function createCity(Request $request){
        $validation = Validator::make($request->all(),[
            'name' => 'required',
            'stateDivisionId' => 'required'
        ]);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        $data = [
            'name' => $request->name,
            'state_division_id' => $request->stateDivisionId,
        ];
        City::create($data);
        return back()->with(['success'=>'City created successfully']);
    }

    //edit
    public function editCity($id){
        $city = City::where('city_id',$id)->first();
        $stateDivisions = StateDivision::get();
        $data = City::select('cities.*','state_divisions.name as stateDivisionName')
                        ->leftJoin('state_divisions','state_divisions.state_division_id','cities.state_division_id')
                        ->get();
        return view('admin.city.edit')->with(['city' => $city,'data' => $data,'stateDivisions'=>$stateDivisions]);
    }

    //update
    public function updateCity($id,Request $request){
        $validation = Validator::make($request->all(),[
            'name' => 'required',
            'stateDivisionId' => 'required',
        ]);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        $updateData = [
            'name' => $request->name,
            'state_division_id' => $request->stateDivisionId,
        ];
        City::where('city_id',$id)->update($updateData);
        return redirect()->route('admin#city')->with(['success'=>'City updated successfully']);
    }

    //delete
    public function deleteCity($id){
        City::where('city_id',$id)->delete();
        return redirect()->route('admin#city')->with(['success'=>'City deleted successfully']);
    }
}

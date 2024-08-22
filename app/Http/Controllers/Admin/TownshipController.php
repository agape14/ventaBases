<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Township;
use Illuminate\Http\Request;
use App\Models\StateDivision;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TownshipController extends Controller
{
    //index
    public function index(){
        $data = Township::select('townships.*','state_divisions.name as stateDivisionName','cities.name as cityName')
                        ->leftJoin('cities','cities.city_id','townships.city_id')
                        ->leftJoin('state_divisions','state_divisions.state_division_id','townships.state_division_id')
                        ->get();
        $stateDivisions = StateDivision::get();
        return view('admin.township.index')->with(['stateDivisions'=>$stateDivisions,'data'=>$data]);
    }

    //get city ajax
    public function getCity(Request $request){
        $cities = City::where('state_division_id',$request->id)->get();
        return response()->json([
            'cities'=>$cities,
        ]);
    }

    //create
    public function createTownship(Request $request){
        $validation = Validator::make($request->all(),[
            'name' => 'required',
            'stateDivisionId' => 'required',
            'cityId' => 'required'
        ]);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        $data = [
            'name' => $request->name,
            'state_division_id' => $request->stateDivisionId,
            'city_id' => $request->cityId
        ];
        Township::create($data);
        return back()->with(['success'=>'Township created successfully']);
    }

    //edit
    public function editTownship($id){
        $township = Township::where('township_id',$id)->first();
        $cities = City::where('state_division_id',$township->state_division_id)->get();
        $stateDivisions = StateDivision::get();
        $data = Township::select('townships.*','state_divisions.name as stateDivisionName','cities.name as cityName')
                        ->leftJoin('cities','cities.city_id','townships.city_id')
                        ->leftJoin('state_divisions','state_divisions.state_division_id','townships.state_division_id')
                        ->get();
        return view('admin.township.edit')->with(['township' => $township,'cities'=> $cities,'data' => $data,'stateDivisions'=>$stateDivisions]);
    }

    //update
    public function updateTownship($id,Request $request){
        $validation = Validator::make($request->all(),[
            'name' => 'required',
            'stateDivisionId' => 'required',
            'cityId' => 'required'
        ]);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        $updateData = [
            'name' => $request->name,
            'state_division_id' => $request->stateDivisionId,
            'city_id' => $request->cityId
        ];
        Township::where('township_id',$id)->update($updateData);
        return redirect()->route('admin#township')->with(['success'=>'Township updated successfully']);
    }

    //delete
    public function deleteTownship($id){
        Township::where('township_id',$id)->delete();
        return redirect()->route('admin#township')->with(['success'=>'Township deleted successfully']);
    }
}
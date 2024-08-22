<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Township;
use Illuminate\Http\Request;
use App\Models\StateDivision;
use App\Models\CashOnDelivery;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCashOnDeliveryRequest;

class CashOnDeliveryController extends Controller
{
    //index
    public function index(){
        $data = CashOnDelivery::with('stateDivision','city','township')->get();
        $stateDivisions = StateDivision::get();
        return view('admin.cashOnDelivery.index')->with(['data'=>$data,'stateDivisions'=>$stateDivisions]);
    }

    //get township ajax
    public function getTownship(Request $request){
        $townships = Township::where('city_id',$request->id)->get();
        return response()->json([
            'townships'=>$townships,
        ]);
    }

    //create
    public function store(StoreCashOnDeliveryRequest $request){
        $data = $this->getRequestCosData($request);
        CashOnDelivery::create($data);
        return redirect()->route('admin#cos')->with(['success'=>'Cash on delivery added successfully']);
    }

    //edit
    public function edit($id){
        $cos = CashOnDelivery::with('stateDivision','city','township')->where('id',$id)->first();
        $data = CashOnDelivery::with('stateDivision','city','township')->get();
        return view('admin.cashOnDelivery.edit')->with(['cos'=>$cos,'data'=>$data]);
    }

    //update
    public function update(StoreCashOnDeliveryRequest $request,$id){
        $updateData = $this->getRequestCosData($request);
        CashOnDelivery::where('id',$id)->update($updateData);
        return redirect()->route('admin#cos')->with(['success'=>'Cash on delivery updated successfully']);
    }

    //delete
    public function delete($id){
        CashOnDelivery::where('id',$id)->delete();
        return redirect()->back()->with(['success'=>'Cash on delivery deleted successfully']);
    }

    //get request data
    private function getRequestCosData($request){
        return [
            'state_division_id' => $request->stateDivisionId,
            'city_id' => $request->cityId,
            'township_id' => $request->townshipId,
            'status' => $request->status,
        ];
    }
}
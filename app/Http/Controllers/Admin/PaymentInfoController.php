<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\PaymentInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class PaymentInfoController extends Controller
{
    //index
    public function index(){
        $data = PaymentInfo::withCount('paymentTransition')->get();
        return view('admin.paymentInfo.index')->with(['data'=>$data]);
    }

    //create
    public function createPaymentInfo(){
        return view('admin.paymentInfo.create');
    }

    //store
    public function storePaymentInfo(Request $request){
        $this->validatePaymentInfo($request);
        if(!$this->checkPaymentStatus($request)){
            return back()->with(['error'=>'Payment status can not be active.If you want to change active status,another active (same type payment) status must be change to unactive status']);
        }
        $data = $this->requestPaymentData($request);
        $data['created_at'] = Carbon::now();

        if($request->hasFile('qrCode')){
            $file = $request->file('qrCode');
            $fileName = uniqid().'_'.$file->getClientOriginalName();

            $file->move(public_path().'/uploads/QRCode/',$fileName);

            $data['qr_code'] = $fileName;
        }

        PaymentInfo::create($data);
        return redirect()->route('admin#paymentInfo')->with(['success'=>'payment added successfully']);
    }

    //edit
    public function editPaymentInfo($id){
        $paymentInfo = PaymentInfo::where('id',$id)->first();
        return view('admin.paymentInfo.edit')->with(['paymentInfo'=>$paymentInfo]);
    }

    //update
    public function updatePaymentInfo($id,Request $request){
        $this->validatePaymentInfo($request);
        if(!$this->checkPaymentStatus($request)){
            return back()->with(['error'=>'Payment status can not be active.If you want to change active,another active (same type payment) must be change to unactive']);
        }
        $updateData = $this->requestPaymentData($request);
        //for qr code
        if($request->hasFile('qrCode')){
            //delete old file if exists
            $oldQRCode = PaymentInfo::select('qr_code')->where('id',$id)->first();
            if($oldQRCode->qr_code){
                $oldFileName = $oldQRCode->qr_code;
                if(File::exists(public_path().'/uploads/QRCode/'.$oldFileName)){
                    File::delete(public_path().'/uploads/QRCode/'.$oldFileName);
                }
            }
            //add new file
            $newFile = $request->file('qrCode');
            $newFileName = uniqid().'-'.$newFile->getClientOriginalName();
            $newFile->move(public_path().'/uploads/QRCode/',$newFileName);
            $updateData['qr_code'] = $newFileName;
        }
        PaymentInfo::where('id',$id)->update($updateData);
        return redirect()->route('admin#paymentInfo')->with(['success'=>'Payment updated successfully']);
    }

    //delete
    public function deletePaymentInfo($id){
        $paymentInfo = PaymentInfo::select('qr_code')->where('id',$id)->first();
        if($paymentInfo->qr_code){
            $fileName = $paymentInfo->qr_code;
            if(File::exists(public_path().'/uploads/QRCode/'.$fileName)){
                File::delete(public_path().'/uploads/QRCode/'.$fileName);
            }
        }
        PaymentInfo::where('id',$id)->delete();
        return redirect()->route('admin#paymentInfo')->with(['success'=>'Payment deleted successfully']);
    }

    //check payment info status only one payment type must be active
    private function checkPaymentStatus($request){
        if($request->status == '1'){
            $activePayment = PaymentInfo::where('type',$request->type)->where('status','1')->get();
            if($activePayment->count() >= 1){
                return false;
            }
        }
        return true;
    }

    private function validatePaymentInfo($request){
        $request->validate([
            'name' => 'required',
            'accountNumber' => 'required',
            'type' => 'required',
            'status' => 'required',
        ]);
    }

    private function requestPaymentData($request){
        return [
            'name' => $request->name,
            'account_number' => $request->accountNumber,
            'type' => $request->type,
            'status' => $request->status,
        ];
    }
}

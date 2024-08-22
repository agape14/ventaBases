<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{

    //report
    public function report(){
        return view('admin.report.report');
    }

    //search by date
    public function searchByDate(Request $request){
        $date = Carbon::parse($request->date)->format('d F Y');
        // dd($date);
        $data = Order::where('order_date',$date)->with('user')->get();
        return view('admin.report.reportView')->with(['data'=>$data]);
    }

    //search by month
    public function searchByMonth(Request $request){
        $request->validate([
            'month' => 'required',
            'year' => 'required',
        ]);
        $data = Order::where('order_month',$request->month)->where('order_year',$request->year)->with('user')->get();
        return view('admin.report.reportView')->with(['data'=>$data]);
    }

    //search by year
    public function searchByYear(Request $request){
        $request->validate([
            'year' => 'required',
        ]);
        $data = Order::where('order_year',$request->year)->with('user')->get();
        return view('admin.report.reportView')->with(['data'=>$data]);
    }
}
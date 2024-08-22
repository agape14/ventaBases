<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockHistory;
use Illuminate\Http\Request;

class StockHistoryController extends Controller
{
    //index
    public function index(){
        $data = StockHistory::with('product','productVariant','productVariant.color','productVariant.size')->orderBy('created_at','desc')->get();
        return view('admin.stockHistory.index')->with(['data'=>$data]);
    }
}

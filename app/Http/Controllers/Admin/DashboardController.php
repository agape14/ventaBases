<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    //index
    public function index(){
        $orders = Order::select(DB::raw("COUNT(*) as count"))
                            ->whereYear('created_at',date('Y'))
                            ->groupBy(DB::raw('Month(created_at)'))
                            ->pluck('count');
        $totalSales = Order::select(DB::raw("SUM(grand_total) as totalSales"))
                            ->whereYear('created_at',date('Y'))
                            ->groupBy(DB::raw('Month(created_at)'))
                            ->pluck('totalSales');
        $months = Order::select(DB::raw("Month(created_at) as month"))
                            ->whereYear('created_at',date('Y'))
                            ->groupBy(DB::raw('Month(created_at)'))
                            ->pluck('month');
        $data = [0,0,0,0,0,0,0,0,0,0,0,0];
        $salesByMonth = [0,0,0,0,0,0,0,0,0,0,0,0];
        // dd($orders,$data,$months);//0=>6 , 0 , 0=>8
        foreach($months as $index=>$month){
            $data[$month-1] = $orders[$index]; // $data[8] = $orders[0] //  8->6 // index(8) => 6 orders // index 8 is not 8 th value; array start at zero
            $salesByMonth[$month-1] = $totalSales[$index];
        }

        $topProducts = Product::
                        select('products.*', DB::raw('SUM(order_items.total_price) as total_sales'))
                        ->join('order_items','products.product_id','order_items.product_id')
                        ->groupBy('order_items.product_id')
                        ->orderBy('total_sales','desc')
                        ->limit(5)
                        ->get();
        $topCustomers = User::select('users.*',DB::raw('SUM(orders.grand_total) as total_sales'))
                        ->join('orders','orders.user_id','users.id')
                        ->groupBy('users.id')
                        ->orderBy('total_sales','desc')
                        ->limit(5)
                        ->get();
                        // dd($topUsers->toArray());

        return view('admin.dashboard')->with(['data'=>$data,'salesByMonth'=>$salesByMonth,'topProducts'=> $topProducts,'topCustomers'=>$topCustomers]);
    }
}
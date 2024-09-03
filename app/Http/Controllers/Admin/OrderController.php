<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\StockHistory;
use Carbon\Carbon;

class OrderController extends Controller
{
    //index
    public function index(){
        $orders = Order::where('status', 'pagado')
        ->with('user')
        ->orderby('order_id', 'desc')
        ->get();

        // Crear un array para almacenar los datos procesados
        $ordersWithBrand = $orders->map(function($order) {
            // Decodificar el JSON en el campo note
            $paymentData = json_decode($order->note, true);

            // Extraer el valor de BRAND
            $order->brand = $paymentData['BRAND'] ?? '';

            return $order;
        });

        return view('admin.order.index', compact('ordersWithBrand'));
    }

    //ordrer filter
    public function filterOrder(Request $request){
        $data = Order::with('user')->where('status','like','%'.$request->orderStatus.'%')->get();
        return view('admin.order.index')->with(['data'=>$data]);
    }

    //pending order
    public function pendingOrder(){
        $data = Order::where('status','pending')->get();
        return view('admin.order.pendingOrder')->with(['data'=>$data]);
    }

    //order detail page
    public function showOrder($id,$notiId = null){
        $order = Order::where('order_id',$id)
        ->with(['stateDivision', 'city', 'township', 'user', 'paymentTransition', 'customer'])
        ->first();

        $orderItems = OrderItem::where('order_id',$id)->with(['product','color','size'])->get();
        if($notiId){
            if($notiId){
                auth()->user()->notifications->where('id',$notiId)->markAsRead();
            }
        }
        return view('admin.order.detail')->with(['order'=>$order,'orderItems'=>$orderItems]);
    }

    //change order status
    public function confirmOrder($id){
        //confirm order status
        $this->changeOrderStatus($id,'pagado','confirmed_date');
        //decrease product stock
        $orderItems = OrderItem::where('order_id',$id)->get();
        foreach($orderItems as $orderItem){
            $productVariant = ProductVariant::where('product_variant_id',$orderItem->product_variant_id)->first();
            $stock = [
                'available_stock' => $productVariant->available_stock - $orderItem->quantity,
            ];
            ProductVariant::where('product_variant_id',$orderItem->product_variant_id)->update($stock);

            //stock history
            StockHistory::create([
                'product_id' => $productVariant->product_id,
                'product_variant_id' => $productVariant->product_variant_id,
                'quantity' => $orderItem->quantity,
                'note' => 'user order',
                'type' => 'out',
                'created_at' => Carbon::now(),
            ]);
        }
        return back()->with(['success'=>'Estado del pedido actualizado correctamente']);
    }

    public function processOrder($id){
        $this->changeOrderStatus($id,'procesando','processing_date');
        return back()->with(['success'=>'Estado del pedido actualizado correctamente']);
    }

    public function pickOrder($id){
        $this->changeOrderStatus($id,'seleccionado','picked_date');
        return back()->with(['success'=>'Estado del pedido actualizado correctamente']);
    }
    public function shipOrder($id){
        $this->changeOrderStatus($id,'enviado','shipped_date');
        return back()->with(['success'=>'Estado del pedido actualizado correctamente']);
    }
    public function deliverOrder($id){
        //update order status
        $this->changeOrderStatus($id,'entregado','delivered_date');
        return back()->with(['success'=>'Estado del pedido actualizado correctamente']);
    }

    private function changeOrderStatus($id,$status,$statusDate){
        Order::where('order_id',$id)->update([
            'status'=>$status,
            $statusDate => Carbon::now(),
        ]);
    }
}

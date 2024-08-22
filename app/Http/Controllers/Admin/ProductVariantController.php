<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\ProductColor;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductVariantController extends Controller
{
    //redirect create page
    public function createVariant($id){
        $product = Product::select('product_id','name')->where('product_id',$id)->first();
        $colors = ProductColor::get();
        $sizes = ProductSize::get();
        $data = ProductVariant::where('product_id',$id)->get();
        return view('admin.productVariant.create')->with([
            'data'=>$data,
            'product'=>$product,
            'colors'=>$colors,
            'sizes'=>$sizes
        ]);
    }

    //store data
    public function storeVariant(Request $request){
        $validation = Validator::make($request->all(),[
            'avaiStock' => 'required'
        ]);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        $data = $this->requestVariantData($request);

        $productVariantId = ProductVariant::insertGetId($data);
        $productVariant = ProductVariant::select('product_id')->where('product_variant_id',$productVariantId)->first();

         //for stock history
        StockHistory::create([
            'product_id'=>$productVariant->product_id,
            'product_variant_id' => $productVariantId,
            'quantity' => $request->avaiStock,
            'note' => 'new product variant added',
            'type' => 'in',
            'created_at' => Carbon::now(),
        ]);

        return back()->with(['success'=>'variant create successfully...']);
    }

    //redirect edit page
    public function editVariant($id){
        $variant = ProductVariant::where('product_variant_id',$id)->first();

        $product = Product::select('product_id','name')->where('product_id',$variant->product_id)->first();
        $colors = ProductColor::get();
        $sizes = ProductSize::get();
        //lists
        $data = ProductVariant::where('product_id',$variant->product_id)->get();
        return view('admin.productVariant.edit')->with([
            'data'=>$data,
            'product'=>$product,
            'colors'=>$colors,
            'sizes'=>$sizes,
            'variant' => $variant,
        ]);
    }

    //update page
    public function updateVariant(Request $request,$id){
        $validation = Validator::make($request->all(),[
            'avaiStock' => 'required'
        ]);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        //for stock history
        $this->addedStockHistory($request,$id);

        $data = $this->requestVariantData($request);

        ProductVariant::where('product_variant_id',$id)->update($data);

        return back()->with(['success'=>'variant create successfully...']);
    }

    //stock history check
    private function addedStockHistory($request,$id){
        $productVariant = ProductVariant::where('product_variant_id',$id)->first();
        $data = [
            'product_id'=>$productVariant->product_id,
            'product_variant_id' => $id,
            'note' => 'update product variant',
            'created_at' => Carbon::now(),
        ];
        if($request->avaiStock != $productVariant->available_stock){
            //check stock greater or less
            if($request->avaiStock > $productVariant->available_stock){
                //stock in
                $data['quantity'] = $request->avaiStock - $productVariant->available_stock;
                $data['type'] = 'in';
            }else{
                //stock out
                $data['quantity'] = $productVariant->available_stock - $request->avaiStock;
                $data['type'] = 'out';
            }
            StockHistory::create($data);
        }
    }

    //delete variant
    public function deleteVariant($id){
        ProductVariant::where('product_variant_id',$id)->delete();
        return back()->with(['success'=>'product variant deleted successfully']);
    }

    //request variant data
    private function requestVariantData($request){
        $data = [
            'product_id' => $request->productId,
            'available_stock' => $request->avaiStock,
        ];
        if($request->colorId){
            $data['color_id'] = $request->colorId;
        }
        if($request->sizeId){
            $data['size_id'] = $request->sizeId;
        }
        return $data;
    }
}

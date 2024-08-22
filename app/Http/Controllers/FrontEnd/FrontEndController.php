<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\MultiImage;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class FrontEndController extends Controller
{
    //index
    public function index(Request $request){
        $newProduct = Product::where('publish_status','1')->orderBy('product_id','desc')->limit(6)->get();
        $products = Product::where('publish_status',1)->orderBy('product_id','asc')->paginate(4);
        if($request->ajax()){

            return response()->json([
                'products' => $products
            ]);
        }
        $brands = Brand::get();
        return view('frontEnd.index')->with([
            'products'=>$products,
            'newProduct'=>$newProduct,
            'brands'=>$brands,
        ]);
    }

    //search product ( auto complete search )
    public function searchProduct(Request $request){
        $result = Product::when(isset($request->searchKey),function($query) use ($request){
            return $query->where('publish_status',1)->where('name','like','%'.$request->searchKey.'%');
        })->get();
        return response()->json([
            'searchResult' => $result
        ]);
    }

    //all Products page
    public function showAllProduct(){
        $products = Product::where('publish_status',1)->paginate(9);
        $categories = Category::get();
        $brands = Brand::get();
        return view('frontEnd.allproduct')->with([
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
        ]);;
    }

    //Products by category page
    public function categoryProduct($id){
        $products = Product::where('publish_status',1)->where('category_id',$id)->paginate(9);
        $categories = Category::get();
        $brands = Brand::get();
        return view('frontEnd.categoryProduct')->with([
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
        ]);;
    }

    //Products by category page
    public function subcategoryProduct($id){
        $products = Product::where('subcategory_id',$id)->paginate(9);
        $categories = Category::get();
        $brands = Brand::get();
        return view('frontEnd.categoryProduct')->with([
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
        ]);;
    }

    //Products by category page
    public function subsubcategoryProduct($id){
        $products = Product::where('subsubcategory_id',$id)->paginate(9);
        $categories = Category::get();
        $brands = Brand::get();
        return view('frontEnd.categoryProduct')->with([
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
        ]);;
    }

    //Products by brands
    public function brandProduct($id){
        $products = Product::where('brand_id',$id)->paginate(9);
        $categories = Category::get();
        $brands = Brand::get();
        return view('frontEnd.categoryProduct')->with([
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
        ]);;

    }

    //filter By price & date
    public function filterProduct(Request $request){

        $minPrice = $request->minPrice;
        $maxPrice = $request->maxPrice;
        $startDate = $request->startDate;
        $endDate = $request->endDate;

        $query = Product::select('*');

        //for date
        if(!is_null($startDate) && is_null($endDate)){
            //have startDate
            $query->whereDate('created_at','>=',$startDate);
        }else if(is_null($startDate) && !is_null($endDate)){
            //have endDate
            $query->whereDate('created_at','<=',$endDate);
        }else if(!is_null($startDate) && !is_null($endDate)){
            //have both
            $query->whereDate('created_at','>=',$startDate)
                    ->whereDate('created_at','<=',$endDate);
        }

        //for price
        if(!is_null($minPrice) && is_null($maxPrice)){
            //have minPrice
            $query->where('selling_price','>=',$minPrice);
        }else if(is_null($minPrice) && !is_null($maxPrice)){
            //have maxPrice
            $query->where('selling_price','<=',$maxPrice);
        }else if(!is_null($minPrice) && !is_null($maxPrice)){
            //have both
            $query->where('selling_price','>=',$minPrice)
                    ->where('selling_price','<=',$maxPrice);
        }

        $query = $query->paginate(9);
        $query->appends($request->all());
        $categories = Category::get();
        $brands = Brand::get();
        return view('frontEnd.categoryProduct')->with([
            'products' => $query,
            'categories' => $categories,
            'brands' => $brands,
        ]);;

    }

    //product detail page
    public function showProduct($id){
        $product = Product:: where('product_id',$id)->with('productReview','productReview.user')->first();
        $multiImages = MultiImage::where('product_id',$id)->get();
        $colors = ProductVariant::select('product_variants.*','product_colors.name as colorName')
                                ->join('product_colors','product_colors.color_id','product_variants.color_id')
                                ->groupBy('product_variants.color_id')
                                ->where('product_variants.product_id',$id)
                                ->get();
        $sizes = ProductVariant::select('product_variants.*','product_sizes.name as sizeName')
                                ->join('product_sizes','product_sizes.size_id','product_variants.size_id')
                                ->groupBy('product_variants.size_id')
                                ->where('product_variants.product_id',$id)
                                ->get();
                                // dd($colors->toArray());
        return view('frontEnd.detail')->with([
            'product'=>$product,
            'multiImages'=>$multiImages,
            'colors' => $colors,
            'sizes' => $sizes,
        ]);
    }

    //getProductSize ajax
    public function getProductSize(Request $request){
        $sizes = ProductVariant::select('product_variants.*','product_sizes.name as sizeName')
                        ->join('product_sizes','product_sizes.size_id','product_variants.size_id')
                        ->where('product_variants.product_id',$request->productId)
                        ->where('product_variants.color_id',$request->colorId)
                        ->get();
        return response()->json([
            'sizes'=> $sizes,
        ]);
    }


}
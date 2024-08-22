<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\MultiImage;
use App\Models\ProductSize;
use App\Models\SubCategory;
use App\Models\ProductColor;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\SubSubCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\StockHistory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    //redirect to index page
    public function index(){
        $data = Product::select('products.*',DB::raw('count(product_variants.product_id) as totalVariants'))
                        ->leftJoin('product_variants','product_variants.product_id','products.product_id')
                        ->groupBy('products.product_id')
                        ->with('brand','category','subcategory','subsubcategory')
                        ->orderBy('product_id','desc')
                        ->get();
        return view('admin.product.index')->with([
            'data'=> $data,
        ]);
    }

    //redirect create page
    public function createProduct(){
        $brands = Brand::get();
        $categories = Category::get();
        $colors = ProductColor::get();
        $sizes = ProductSize::get();

        return view('admin.product.create')->with([
            'brands' => $brands,
            'categories' => $categories,
            'colors' => $colors,
            'sizes' => $sizes,
        ]);
    }

    //get subcategory with ajax
    public function getSubCategory(Request $request){
        $subCategories = SubCategory::where('category_id',$request->id)->get();
        return response()->json([
            'subCategories' => $subCategories,
        ]);
    }
    //get sub-subcategory with ajax
    public function getSubSubCategory(Request $request){
        $subsubCategoires = SubSubCategory::where('subcategory_id',$request->id)->get();
        return response()->json([
            'subsubCategories' => $subsubCategoires
        ]);
    }

    //store product data
    public function storeProduct(Request $request){
        //validation
        $validation = $this->productValidation($request);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        //get preview image
        $file = $request->file('previewImage');
        $fileName = uniqid().'_'.$file->getClientOriginalName();
        //get data
        $data = $this->requestProductData($request);
        $data['preview_image'] = $fileName;
        $data['created_at'] = Carbon::now();
        //store data
        $file->move(public_path().'/uploads/products/',$fileName);
        $productId = Product::insertGetId($data);
        // dd($productId);

        //check multi image
        if($request->hasFile('multiImage')){
            $multiImageFiles = $request->file('multiImage');
            foreach($multiImageFiles as $img){
                $multiImageName = uniqid().'_'.$img->getClientOriginalName();
                //store image
                $img->move(public_path().'/uploads/products/',$multiImageName);
                MultiImage::create([
                    'product_id' => $productId,
                    'image' => $multiImageName,
                ]);
            }

        }

        //for variant
        $variantData = $this->requestProductVariantData($request,$productId);
        $productVariantId = ProductVariant::insertGetId($variantData);

        //for stock history
        StockHistory::create([
            'product_id'=>$productId,
            'product_variant_id' => $productVariantId,
            'quantity' => $variantData['available_stock'],
            'note' => 'new product added',
            'type' => 'in',
            'created_at' => Carbon::now(),
        ]);

        return redirect()->route('admin#product')->with(['success'=>'Product created successfully']);

    }

    //redirect to edit page
    public function editProduct($id){
        $product = Product::where('product_id',$id)->first();
        $brands = Brand::get();
        $categories = Category::get();
        $subCategories = SubCategory::where('category_id',$product->category_id)->get();
        $subsubCategories = SubSubCategory::where('subcategory_id',$product->subcategory_id)->get();
        $multiImages = MultiImage::where('product_id',$id)->get();
        return view('admin.product.edit')->with([
            'product'=>$product,
            'brands'=>$brands,
            'categories'=>$categories,
            'subCategories' => $subCategories,
            'subsubCategories' => $subsubCategories,
            'multiImages' => $multiImages
        ]);
    }

    //delete multiImage ajax
    public function deleteImg(Request $request){
        $multiImage = MultiImage::where('multi_image_id',$request->id)->first();
        $fileName = $multiImage->image;

        if(File::exists(public_path().'/uploads/products/'.$fileName)){
            File::delete(public_path().'/uploads/products/'.$fileName);
        }
        MultiImage::where('multi_image_id',$request->id)->delete();
        return response()->json([
            'success'=>'deleted successfully',
        ]);
    }

    //update data
    public function updateProduct(Request $request,$id){
        //validation
        $validation =  Validator::make($request->all(),[
            'brandId' => 'required',
            'categoryId' => 'required',
            'subCategoryId' => 'required',
            'subsubCategoryId' => 'required',
            'name' => 'required',
            'smallDescription' => 'required',
            'longDescription' => 'required',
            'originalPrice' => 'required',
            'sellingPrice' => 'required',
            'publishStatus' => 'required',
        ]);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }
        //get data
        $updateData = $this->requestProductData($request);

        //check preview image
        if($request->hasFile('previewImage')){
            //delete old image
            $product = Product::where('product_id',$id)->first();
            $oldFileName = $product->preview_image;
            if(File::exists(public_path().'/uploads/products/'.$oldFileName)){
                File::delete(public_path().'/uploads/products/'.$oldFileName);
            }
            //update new image
            $newFile = $request->file('previewImage');
            $newFileName = uniqid().'_'.$newFile->getClientOriginalName();
            $newFile->move(public_path().'/uploads/products/',$newFileName);

            $updateData['preview_image'] = $newFileName;

        }

        Product::where('product_id',$id)->update($updateData);

        //check multi image
        if($request->hasFile('multiImage')){
            //store multi image
            $multiImageFiles = $request->file('multiImage');
            foreach($multiImageFiles as $img){
                $multiImageName = uniqid().'_'.$img->getClientOriginalName();
                $img->move(public_path().'/uploads/products/',$multiImageName);

                MultiImage::create([
                    'product_id' => $id,
                    'image' => $multiImageName
                ]);
            }

        }

        return redirect()->route('admin#product')->with(['success'=>'Product updated successfully']);
    }

    //show product detail page
    public function showProduct($id){
        $product = Product::where('product_id',$id)->with('brand','category','subcategory','subsubcategory')->first();
        $variants = ProductVariant::select('product_variants.*','product_colors.name as colorName','product_sizes.name as sizeName')
                        ->leftJoin('product_colors','product_colors.color_id','product_variants.color_id')
                        ->leftJoin('product_sizes','product_sizes.size_id','product_variants.size_id')
                        ->where('product_id',$id)
                        ->get();
        $multiImages = MultiImage::where('product_id',$id)->get();
        return view('admin.product.detail')->with([
            'product'=>$product,
            'variants' => $variants,
            'multiImages' => $multiImages,
            ]);
    }

    //delete product
    public function deleteProduct($id){
        // delete preview img folder
        $product = Product::where('product_id',$id)->first();
        $previewImgName = $product->preview_image;
        if(File::exists(public_path().'/uploads/products/'.$previewImgName)){
            File::delete(public_path().'/uploads/products/'.$previewImgName);
        }
        // delete multi img
        $multiImage = MultiImage::where('product_id',$id)->get();
        if(!$multiImage->count() == 0){
            //delete multi img folder
            foreach($multiImage as $img){
                $multiImageName = $img->image;
                if(File::exists(public_path().'/uploads/products/'.$multiImageName)){
                    File::delete(public_path().'/uploads/products/'.$multiImageName);
                }
            }
            //delete multi img db
            MultiImage::where('product_id',$id)->delete();
        }
        // delete product
        Product::where('product_id',$id)->delete();
        // delete variants
        ProductVariant::where('product_id',$id)->delete();
        return back()->with(['success'=>'Products deleted successfully']);
    }

    //product stock page
    public function productStock(){
        $data = Product::select('product_id','name','preview_image','publish_status')->with('productVariant','productVariant.color','productVariant.size')->get();
        // dd($data->toArray());
        return view('admin.productStock.index')->with(['data'=>$data->toArray(),'products'=>$data]);
    }

    //product stock filter
    public function productStockFilter(Request $request){
        $data = Product::select('product_id','name','preview_image','publish_status')
                        ->where('product_id',$request->productId)
                        ->with('productVariant','productVariant.color','productVariant.size')
                        ->get();
        $products = Product::select('product_id','name')->get();
        return view('admin.productStock.index')->with(['data'=>$data->toArray(),'products'=>$products]);
    }

    //get request data
    private function requestProductData($request){
        $data = [
            'brand_id' => $request->brandId,
            'category_id' => $request->categoryId,
            'subcategory_id' => $request->subCategoryId,
            'subsubcategory_id' => $request->subsubCategoryId,
            'name' => $request->name,
            'short_description' => $request->smallDescription,
            'long_description' => $request->longDescription,
            'original_price' => $request->originalPrice,
            'selling_price' => $request->sellingPrice,
            'discount_price' => $request->discountPrice,
            'publish_status' => $request->publishStatus,
            'special_offer' => $request->specialOffer,
            'featured' => $request->featured,
        ];
        if(isset($request->previewImage)){
            $data['preview_image'] = $request->previewImage;
        }
        return $data;
    }

    //get variant request data
    private function requestProductVariantData($request,$id){
        $data = [
            'product_id' => $id,
            'available_stock' => $request->avaiStock,
        ];
        if(isset($request->colorId)){
            $data['color_id'] = $request->colorId;
        }
        if(isset($request->sizeId)){
            $data['size_id'] = $request->sizeId;
        }
        return $data;
    }

    //validation
    private function productValidation($request){
        return Validator::make($request->all(),[
            'brandId' => 'required',
            'categoryId' => 'required',
            'subCategoryId' => 'required',
            'subsubCategoryId' => 'required',
            'name' => 'required',
            'smallDescription' => 'required',
            'longDescription' => 'required',
            'previewImage' => 'required',
            'originalPrice' => 'required',
            'sellingPrice' => 'required',
            'publishStatus' => 'required',
            'avaiStock' => 'required',
        ]);
    }

}
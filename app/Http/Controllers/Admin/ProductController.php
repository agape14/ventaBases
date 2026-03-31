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
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Extensiones de imagen permitidas
     */
    private $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    /**
     * MIME types de imagen permitidos
     */
    private $allowedImageMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    /**
     * Valida y procesa una imagen de forma segura
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @return array ['valid' => bool, 'filename' => string|null, 'error' => string|null]
     */
    private function validateAndProcessImage($file): array
    {
        // 1. Validar extensión
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $this->allowedImageExtensions)) {
            return [
                'valid' => false,
                'filename' => null,
                'error' => 'Extensión de archivo no permitida: ' . $extension
            ];
        }
        
        // 2. Validar MIME type real
        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, $this->allowedImageMimes)) {
            return [
                'valid' => false,
                'filename' => null,
                'error' => 'Tipo de archivo no permitido.'
            ];
        }
        
        // 3. Verificar que es una imagen real
        $imageInfo = @getimagesize($file->getPathname());
        if ($imageInfo === false) {
            return [
                'valid' => false,
                'filename' => null,
                'error' => 'El archivo no es una imagen válida.'
            ];
        }
        
        // 4. Generar nombre seguro (NUNCA usar getClientOriginalName)
        $secureFilename = Str::random(40) . '.' . $extension;
        
        return [
            'valid' => true,
            'filename' => $secureFilename,
            'error' => null
        ];
    }

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
        // ============================================
        // PARCHE DE SEGURIDAD - Enero 2026
        // ============================================
        
        //validation
        $validation = $this->productValidation($request);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        //get preview image
        $file = $request->file('previewImage');
        
        // Validar imagen de preview de forma segura
        $imageResult = $this->validateAndProcessImage($file);
        if (!$imageResult['valid']) {
            return back()->withErrors(['previewImage' => $imageResult['error']])->withInput();
        }
        $fileName = $imageResult['filename'];
        
        $nombrepdf="";
        if ($request->hasFile('order_pdf_file')) {
            $filenew = $request->file('order_pdf_file');
            // Validar que es PDF
            if ($filenew->getMimeType() !== 'application/pdf') {
                return back()->withErrors(['order_pdf_file' => 'El archivo debe ser un PDF válido.'])->withInput();
            }
            // Generar nombre seguro para PDF
            $filenamenew = Str::random(40) . '.pdf';
            $filenew->storeAs('public', $filenamenew);
            $nombrepdf = $filenamenew;
        }

        //get data
        $data = $this->requestProductData($request);
        $data['preview_image'] = $fileName;
        $data['created_at'] = Carbon::now();
        $data['order_pdf_filename'] = $nombrepdf;

        //store data
        $file->move(public_path().'/uploads/products/',$fileName);
        $productId = Product::insertGetId($data);

        //check multi image
        if($request->hasFile('multiImage')){
            $multiImageFiles = $request->file('multiImage');
            foreach($multiImageFiles as $img){
                // Validar cada imagen de forma segura
                $multiImageResult = $this->validateAndProcessImage($img);
                if (!$multiImageResult['valid']) {
                    // Log el error pero continúa con las demás imágenes
                    \Log::warning('Imagen múltiple rechazada: ' . $multiImageResult['error']);
                    continue;
                }
                $multiImageName = $multiImageResult['filename'];
                
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
        // ============================================
        // PARCHE DE SEGURIDAD - Enero 2026
        // ============================================
        
        //validation
        $validation = Validator::make($request->all(),[
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
            'subject_mail' => 'nullable|string|max:255',
            'order_pdf_file' => 'nullable|file|mimes:pdf|max:10240',
            'previewImage' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'multiImage.*' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        //get data
        $updateData = $this->requestProductData($request);

        //check preview image
        if($request->hasFile('previewImage')){
            $newFile = $request->file('previewImage');
            
            // Validar imagen de forma segura
            $imageResult = $this->validateAndProcessImage($newFile);
            if (!$imageResult['valid']) {
                return back()->withErrors(['previewImage' => $imageResult['error']])->withInput();
            }
            $newFileName = $imageResult['filename'];

            //delete old image
            $product = Product::where('product_id',$id)->first();
            $oldFileName = $product->preview_image;
            if(File::exists(public_path().'/uploads/products/'.$oldFileName)){
                File::delete(public_path().'/uploads/products/'.$oldFileName);
            }

            $newFile->move(public_path().'/uploads/products/',$newFileName);
            $updateData['preview_image'] = $newFileName;
        }

        if ($request->hasFile('order_pdf_file')) {
            $file = $request->file('order_pdf_file');
            // Validar que es PDF
            if ($file->getMimeType() !== 'application/pdf') {
                return back()->withErrors(['order_pdf_file' => 'El archivo debe ser un PDF válido.'])->withInput();
            }
            // Generar nombre seguro
            $filenameedit = Str::random(40) . '.pdf';
            $file->storeAs('public', $filenameedit);
            $updateData['order_pdf_filename'] = $filenameedit;
        }

        Product::where('product_id',$id)->update($updateData);

        //check multi image
        if($request->hasFile('multiImage')){
            //store multi image
            $multiImageFiles = $request->file('multiImage');
            foreach($multiImageFiles as $img){
                // Validar cada imagen de forma segura
                $multiImageResult = $this->validateAndProcessImage($img);
                if (!$multiImageResult['valid']) {
                    \Log::warning('Imagen múltiple rechazada en update: ' . $multiImageResult['error']);
                    continue;
                }
                $multiImageName = $multiImageResult['filename'];

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
            'subject_mail' => $request->subject_mail,
            'html_details' => $request->html_details,
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
            'previewImage' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'multiImage.*' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'originalPrice' => 'required',
            'sellingPrice' => 'required',
            'publishStatus' => 'required',
            'avaiStock' => 'required',
            'subject_mail' => 'nullable|string|max:255',
            'order_pdf_file' => 'nullable|file|mimes:pdf|max:10240',
        ]);
    }
}
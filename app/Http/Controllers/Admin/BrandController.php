<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * Extensiones de imagen permitidas
     */
    private $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    /**
     * MIME types de imagen permitidos
     */
    private $allowedImageMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    //redirect to index page
    public function index(){
        $data = Brand::get();
        return view('admin.brand.index')->with(['data'=>$data]);
    }

    //create brand
    public function createBrand(Request $request){
        // ============================================
        // PARCHE DE SEGURIDAD - Enero 2026
        // ============================================
        
        $validation = Validator::make($request->all(),[
            'name' => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        $file = $request->file('image');
        
        // ============================================
        // VALIDACIÓN ADICIONAL DE SEGURIDAD
        // ============================================
        
        // 1. Validar extensión
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $this->allowedImageExtensions)) {
            return back()->withErrors(['image' => 'Extensión de archivo no permitida.'])->withInput();
        }
        
        // 2. Validar MIME type real
        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, $this->allowedImageMimes)) {
            return back()->withErrors(['image' => 'Tipo de archivo no permitido.'])->withInput();
        }
        
        // 3. Verificar que es una imagen real
        $imageInfo = @getimagesize($file->getPathname());
        if ($imageInfo === false) {
            return back()->withErrors(['image' => 'El archivo no es una imagen válida.'])->withInput();
        }
        
        // 4. Generar nombre seguro (NUNCA usar getClientOriginalName)
        $fileName = Str::random(40) . '.' . $extension;

        //get data
        $data = [
          'name' => $request->name,
           'image' => $fileName,
        ];

        //store data
        $file->move(public_path().'/uploads/brands/',$fileName);
        Brand::create($data);
        return back()->with(['success'=>'New brand added successfully']);
    }

    //edit page
    public function editBrand($id){
        $brand = Brand::where('brand_id',$id)->first();
        $data = Brand::get();
        return view('admin.brand.edit')->with(['brand'=>$brand,'data'=>$data]);
    }

    //update brand
    public function updateBrand(Request $request,$id){
        // ============================================
        // PARCHE DE SEGURIDAD - Enero 2026
        // ============================================
        
        $validation = Validator::make($request->all(),[
            'name' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        $data = [
            'name' => $request->name,
        ];

        //check image
        if($request->hasFile('image')){
            $file = $request->file('image');
            
            // ============================================
            // VALIDACIÓN ADICIONAL DE SEGURIDAD
            // ============================================
            
            // 1. Validar extensión
            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, $this->allowedImageExtensions)) {
                return back()->withErrors(['image' => 'Extensión de archivo no permitida.'])->withInput();
            }
            
            // 2. Validar MIME type real
            $mimeType = $file->getMimeType();
            if (!in_array($mimeType, $this->allowedImageMimes)) {
                return back()->withErrors(['image' => 'Tipo de archivo no permitido.'])->withInput();
            }
            
            // 3. Verificar que es una imagen real
            $imageInfo = @getimagesize($file->getPathname());
            if ($imageInfo === false) {
                return back()->withErrors(['image' => 'El archivo no es una imagen válida.'])->withInput();
            }
            
            // 4. Generar nombre seguro
            $fileName = Str::random(40) . '.' . $extension;

            //delete old image
            $brand = Brand::where('brand_id',$id)->first();
            $oldFileName = $brand->image;
            if(File::exists(public_path().'/uploads/brands/'.$oldFileName)){
                File::delete(public_path().'/uploads/brands/'.$oldFileName);
            }

            $file->move(public_path().'/uploads/brands/',$fileName);
            $data['image'] = $fileName;
        }

        Brand::where('brand_id',$id)->update($data);
        return redirect()->route('admin#brand')->with(['success'=>'Brand updated successfully']);
    }

    //delete brand
    public function deleteBrand($id){
        //delete image
        $brand = Brand::where('brand_id',$id)->first();
        $fileName = $brand->image;
        if(File::exists(public_path().'/uploads/brands/'.$fileName)){
            File::delete(public_path().'/uploads/brands/'.$fileName);
        }
        //delete data from db
        Brand::where('brand_id',$id)->delete();
        return redirect()->route('admin#brand')->with(['success'=>'Brand deleted successfully']);
    }
}
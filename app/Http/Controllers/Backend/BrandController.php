<?php

namespace App\Http\Controllers\Backend;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Image;

class BrandController extends Controller
{
    public function AllBrand(){
        $brands = Brand::latest()->get();
        return view('backend.brand.brand_all',compact('brands'));
    }// Brand End Method

    public function AddBrand(){
        return view('backend.brand.brand_add');
    }// Brand End Method

    public function StoreBrand(Request $request)
    {
        $image = $request->file('brand_image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(300,300)->save('uploade/brand/'.$name_gen);
        $save_url = 'uploade/brand/'.$name_gen;

        Brand::insert([
            'brand_name'=>$request->brand_name,
            'brand_slug'=> strtolower(str_replace(' ','-',$request->brand_name)),
            'brand_image'=>$save_url ,
        ]);

         $notification = array(
            'message' => 'Brand Added Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('all.brand')->with($notification);
    } // End Methord

    public function EditBrand($id)
    {
        // code...
        $brand = Brand::findOrFail($id);

        return view('backend.brand.brand_edit',compact('brand'));

    }// End Methord

    public function UpdateBrand(Request $request)
    {
        $brand_id = $request->id;
        $old_img = $request->old_image;


        if ($request->file('brand_image')) {
            $image = $request->file('brand_image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(300,300)->save('uploade/brand/'.$name_gen);
        $save_url = 'uploade/brand/'.$name_gen;

// old image delete
        Brand::findOrFail($brand_id)->update([
            'brand_name'=>$request->brand_name,
            'brand_slug'=> strtolower(str_replace(' ','-',$request->brand_name)),
            'brand_image'=>$save_url ,
        ]);

        if (file_exists($old_img)) {
            unlink($old_img);
        }
// old image delete end
         $notification = array(
            'message' => 'Brand Update with image Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('all.brand')->with($notification);
        }else{
            Brand::findOrFail($brand_id)->update([
            'brand_name'=>$request->brand_name,
            'brand_slug'=> strtolower(str_replace(' ','-',$request->brand_name)),
         
        ]);

         $notification = array(
            'message' => 'Brand Update whithout image Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('all.brand')->with($notification);
        }
    }// End Methord


    public function DeleteBrand($id)
    {
        $brand = Brand::findOrFail($id);
        $img = $brand->brand_image;
        unlink($img);

        Brand::findOrFail($id)->delete();

         $notification = array(
            'message' => 'Brand Delete whith image Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('all.brand')->with($notification);
    
    }// End Methord

}

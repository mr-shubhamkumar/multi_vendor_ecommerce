<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\MultiImg;
use App\Models\Brand;
use Image;
use Carbon\Carbon;

class ProductController extends Controller
{
    //AllProduct Method
    public function AllProduct(){
        $products = Product::latest()->get();
        return view('backend.product.product_all',compact('products'));
    }//AllProduct Method End


    //AddProduct Method
    public function AddProduct(){
        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();
        $activeVendor = User::where('status','active')->where('role','vendor')->latest()->get();
        
        return view('backend.product.product_add',compact('brands','categories','activeVendor'));
    }//AddProduct Method End



    //StoreProduct
    public function StoreProduct(Request $request){

        $image = $request->file('product_thambnail');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(800,800)->save('uploade/products/thambnail/'.$name_gen);
        $save_url = 'uploade/products/thambnail/'.$name_gen;
      

      $product_id = Product::insertGetId([

        'brand_id' => $request->brand_id,
        'category_id' =>$request->category_id,
        'subcategory_id' =>$request->subcategory_id,
        'product_name' =>$request->product_name,
        'product_slug' => strtolower(str_replace(' ','-',$request->product_name)),

        'product_code' => $request->product_code,
        'product_qty'  => $request->product_qty,
        'product_tags' => $request->product_tags,  
        'product_size'  => $request->product_size, 
        'product_color'  => $request->product_color,

        'selling_price'  => $request->selling_price,
        'discount_price' => $request->discount_price,
        'short_descp' => $request->short_descp,
        'long_descp'  => $request->long_descp,

      
        'hot_deals' => $request->hot_deals,
        'featured' => $request->featured, 
        'special_offer'=> $request->special_offer, 
        'special_deals' => $request->special_deals,

        'status' => 1,  
        'product_thambnail' => $save_url,
        'vendor_id'=>$request->vendor_id,
        'created_at' => Carbon::now(),  
        ]);

      // Multiple Image Upload From Her 
      $images = $request->file('multi_img');
      foreach($images as $img){

        $make_name = hexdec(uniqid()).'.'.$img->getClientOriginalExtension();
        Image::make($img)->resize(800,800)->save('uploade/products/multi-image/'.$make_name);

        $uploadPath  = 'uploade/products/multi-image/'.$make_name;

        MultiImg::insert([
            'product_id' => $product_id,
            'photo_name' => $uploadPath,
            'created_at' => Carbon::now(),  

        ]);


      } // end foreach
      // Multiple Image Upload From Her

      $notification = array(
            'message' => 'Product Added Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('all.product')->with($notification);

    }//Method End


    public function EditProduct($id){
        $multiImg = MultiImg::where('product_id',$id)->get();
        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();
        $subcategory = SubCategory::latest()->get();
        $activeVendor = User::where('status','active')->where('role','vendor')->latest()->get();
        $products = Product::findOrFail($id);
        return view('backend.product.product_edit',compact('brands','products','categories','activeVendor','subcategory','multiImg'));

    }// Method End

    public function UpdateProduct(Request $request){
        $product_id = $request->id;

        Product::findOrFail($product_id)->update([

        'brand_id' => $request->brand_id,
        'category_id' =>$request->category_id,
        'subcategory_id' =>$request->subcategory_id,
        'product_name' =>$request->product_name,
        'product_slug' => strtolower(str_replace(' ','-',$request->product_name)),

        'product_code' => $request->product_code,
        'product_qty'  => $request->product_qty,
        'product_tags' => $request->product_tags,  
        'product_size'  => $request->product_size, 
        'product_color'  => $request->product_color,

        'selling_price'  => $request->selling_price,
        'discount_price' => $request->discount_price,
        'short_descp' => $request->short_descp,
        'long_descp'  => $request->long_descp,

      
        'hot_deals' => $request->hot_deals,
        'featured' => $request->featured, 
        'special_offer'=> $request->special_offer, 
        'special_deals' => $request->special_deals,

        'status' => 1,  
        'vendor_id'=>$request->vendor_id,
        'created_at' => Carbon::now(),  
        ]);

        $notification = array(
            'message' => 'Product Udated Without Images Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('all.product')->with($notification);

    }

    public function UpdateProductThambnail(Request $request){

        $products_id = $request->id;
        $old_img = $request->old_img;

        $image = $request->file('product_thambnail');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(800,800)->save('uploade/products/thambnail/'.$name_gen);
        $save_url = 'uploade/products/thambnail/'.$name_gen;

        if (file_exists($old_img)) {
            unlink($old_img);
        }

        Product::findOrFail($products_id)->update([
            'product_thambnail'=>$save_url,
            'updated_at'=> Carbon::now()
        ]);

         $notification = array(
            'message' => 'Product Image Thambnail Updated Successfully',
            'alert-type' => 'success'
        );

         return redirect()->back()->with($notification);

    }

// Multi Image Update
    public function UpdateProductMultiImage(Request $request){
        $imgs = $request->multi_img;

        foreach($imgs as $id => $img ){
            $imgDel = MultiImg::findOrFail($id);
            unlink($imgDel->photo_name);

             $make_name = hexdec(uniqid()).'.'.$img->getClientOriginalExtension();
        Image::make($img)->resize(800,800)->save('uploade/products/multi-image/'.$make_name);

        $uploadPath  = 'uploade/products/multi-image/'.$make_name;

        MultiImg::where('id',$id)->update([
            'photo_name'=> $uploadPath,
            'updated_at'=>Carbon::now()
        ]);


        } // End Foreach

        $notification = array(
            'message' => 'Product Multi Image  Updated Successfully',
            'alert-type' => 'success'
        );

         return redirect()->back()->with($notification);
    }




    public function MultiImageDelete($id){
        $oldImg = MultiImg::findOrFail($id);
        unlink($oldImg->photo_name);

        MultiImg::findOrFail($id)->delete();

         $notification = array(
            'message' => 'Product Multi Image  Delete Successfully',
            'alert-type' => 'success'
        );

         return redirect()->back()->with($notification);

    }


    public function ProductInactive($id){
        Product::findOrFail($id)->update(['status'=>0]);

        $notification = array(
            'message' => 'Product Inactive',
            'alert-type' => 'success'
        );

         return redirect()->back()->with($notification);

    } 

    public function ProductActive($id){
        Product::findOrFail($id)->update(['status'=>1]);

        $notification = array(
            'message' => 'Product Active',
            'alert-type' => 'success'
        );

         return redirect()->back()->with($notification);

    }




    // Delete Products 
    public function DeleteProduct($id){
        $product = Product::findOrFail($id);
        unlink($product->product_thambnail);
        Product::findOrFail($id)->delete();

        $imges = MultiImg::where('product_id',$id)->get();
        foreach($imges as $img){
            unlink($img->photo_name);
            MultiImg::where('product_id',$id)->delete();
        }
        $notification = array(
            'message' => 'Product Delete Successfully',
            'alert-type' => 'success'
        );

         return redirect()->back()->with($notification);

    }



}

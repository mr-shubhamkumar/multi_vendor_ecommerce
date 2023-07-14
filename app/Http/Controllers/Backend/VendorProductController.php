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
use Illuminate\Support\Facades\Auth;

class VendorProductController extends Controller
{
    //AllProduct Method
    public function VendorAllProduct(){
        $id = Auth::user()->id;
        $products = Product::where('vendor_id',$id)->latest()->get();
        return view('vendor.backend.product.vendor_product_all',compact('products'));
    }//AllProduct Method End



    //AddProduct Method
    public function VendorAddProduct(){
        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();
       
        
        return view('vendor.backend.product.vendor_product_add',compact('brands','categories',));
    }//AddProduct Method End



    public function vendorGetSubCategory($category_id){
        $subcat = SubCategory::where('category_id',$category_id)->orderBy('subcategory_name','ASC')->get();
        return json_encode($subcat);

    }






    //StoreProduct
    public function VendorStoreProduct(Request $request){

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
        'vendor_id'=>Auth::user()->id,
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
            'message' => 'Vendor Product Added Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('vendor.all.product')->with($notification);

    }//Method End




        public function VendorEditProduct($id){
        $multiImg = MultiImg::where('product_id',$id)->get();
        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();
        $subcategory = SubCategory::latest()->get();
        
        $products = Product::findOrFail($id);
        return view('vendor.backend.product.vendor_product_edit',compact('brands','products','categories','subcategory','multiImg'));

    }// Method End




    public function VendorUpdateProduct(Request $request){
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
        'created_at' => Carbon::now(),  
        ]);

        $notification = array(
            'message' => 'VendorProduct Udated Without Images Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('vendor.all.product')->with($notification);

    }



     public function VendorUpdateProductThambnail(Request $request){

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
    public function VendorUpdateProductMultiimage(Request $request){
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


    public function VendorProductMultiimageDelete($id){
        $oldImg = MultiImg::findOrFail($id);
        unlink($oldImg->photo_name);

        MultiImg::findOrFail($id)->delete();

         $notification = array(
            'message' => 'Product Multi Image  Delete Successfully',
            'alert-type' => 'success'
        );

         return redirect()->back()->with($notification);

    }



      public function VendorProductInactive($id){
        Product::findOrFail($id)->update(['status'=>0]);

        $notification = array(
            'message' => 'Product Inactive',
            'alert-type' => 'success'
        );

         return redirect()->back()->with($notification);

    } 

    public function VendorProductAnactive($id){
        Product::findOrFail($id)->update(['status'=>1]);

        $notification = array(
            'message' => 'Product Active',
            'alert-type' => 'success'
        );

         return redirect()->back()->with($notification);

    }


      // Delete Products 
    public function VendorDeleteProduct($id){
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
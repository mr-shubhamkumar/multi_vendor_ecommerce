<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\User;
use App\Models\MultiImg;
use App\Models\Brand;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Color;
use App\Models\ProductImage;
use App\Models\Size;
use App\Models\Variant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class IndexController extends Controller
{

    public function Index(){
        $skip_category_0 = Category::skip(0)->first();
        $skip_product_0 = Product::where('status',1)->where('category_id',$skip_category_0->id)->orderBy('id','DESC')->limit(5)->get();

        $skip_category_2 = Category::skip(2)->first();
        $skip_product_2 = Product::where('status',1)->where('category_id',$skip_category_2->id)->orderBy('id','DESC')->limit(5)->get();

        $skip_category_8 = Category::skip(7)->first();
        $skip_product_8 = Product::where('status',1)->where('category_id',$skip_category_8->id)->orderBy('id','DESC')->limit(5)->get();

        $hot_deals = Product::where('hot_deals',1)->where('discount_price','!=',NULL)->orderBy('id','DESC')->limit(3)->get();


         $special_offer = Product::where('special_offer',1)->orderBy('id','DESC')->limit(3)->get();

         $new = Product::where('status',1)->orderBy('id','DESC')->limit(3)->get();

         $special_deals = Product::where('special_deals',1)->where('status',1)->orderBy('id','DESC')->limit(3)->get();

        return view('frontend.index',compact('skip_category_0','skip_product_0','skip_category_2','skip_product_2','skip_category_8','skip_product_8','hot_deals','special_offer','new','special_deals'));
    }// End Method



    public function ProductDetails($id, $slug)
    {
        $product = Product::findOrFail($id);
        $color = $product->product_color;
        $product_color = explode(',', $color);

        $size = $product->product_size;
        $product_size = explode(',', $size);

        $multiImag = MultiImg::where('product_id',$id)->get();


        $cat_id = $product->category_id;
        $relatedProducts = Product::where('category_id',$cat_id)->where('id','!=',$id)->limit(4)->get();
        return view('frontend.product.product_details',compact('product','product_color','product_size','multiImag','relatedProducts'));
        
    }



    public function VendorDetails($id)
    {
        $vendor = User::findOrFail($id);
        $product = Product::where('vendor_id',$id)->get();
        return view('frontend.vendor.vendor_details',compact('vendor','product'));
    }// End Method


    public function AllVendor()
    {
        $vendor = User::where('status','active')->where('role','vendor')->orderBy('id','DESC')->get();
        return view('frontend.vendor.vendor_all',compact('vendor'));
    }// End Method


    public function CatWiseProduct($id,$slug)
    {
        $product = Product::where('status',1)->where('category_id',$id)->orderBy('id','DESC')->get();
        $category = Category::orderBy('category_name','ASC')->get();
        $bredcat = Category::where('id',$id)->first();

        $newProduct = Product::orderBy('id','DESC')->limit(3)->get();

        return view('frontend.product.category_view',compact('product','category','bredcat','newProduct'));
    }



    public function SubCatWiseProduct($id,$slug)
    {
         $product = Product::where('status',1)->where('subcategory_id',$id)->orderBy('id','DESC')->get();
        $category = Category::orderBy('category_name','ASC')->get();

        $bredsubcat = SubCategory::where('id',$id)->first();

        $newProduct = Product::orderBy('id','DESC')->limit(3)->get();


        return view('frontend.product.subcategory_view',compact('product','category','bredsubcat','newProduct'));

    }


    public function ProductViewModel($id)
    {
        $product = Product::with('category','brand')->findOrFail($id);

         $color = $product->product_color;
        $product_color = explode(',', $color);

        $size = $product->product_size;
        $product_size = explode(',', $size);

// dd($product);
        return response()->json(array(
            'product' => $product,
            'color' => $product_color,
            'size' => $product_size,
        ));
    }
}

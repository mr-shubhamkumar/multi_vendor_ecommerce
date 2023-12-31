<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use Image;

class BannerController extends Controller
{
    public function AllBanner(){
        $banner = Banner::latest()->get();
        return view('backend.banner.banner_all',compact('banner'));
    }// Banner End Method


     public function AddBanner(){
        return view('backend.banner.banner_add');
    }// Banner End Method



      public function StorerBanner(Request $request)
    {
        $image = $request->file('banner_image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(768,450)->save('uploade/banner/'.$name_gen);
        $save_url = 'uploade/banner/'.$name_gen;

        Banner::insert([
            'banner_title'=>$request->banner_title,
            'banner_url'=>$request->banner_url,
            'banner_image'=>$save_url ,
        ]);

         $notification = array(
            'message' => 'Banner Added Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('all.banner')->with($notification);
    } // End Methord


       public function EditBanner($id)
    {
        $banner = Banner::findOrFail($id);

        return view('backend.banner.banner_edit',compact('banner'));

    }// End Methord


      public function UpdateBanner(Request $request)
    {
        $banner_id = $request->id;
        $old_img = $request->old_image;


        if ($request->file('banner_image')) {
          $image = $request->file('banner_image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(768,450)->save('uploade/banner/'.$name_gen);
        $save_url = 'uploade/banner/'.$name_gen;


        Banner::findOrFail($banner_id)->update([
            'banner_title'=>$request->banner_title,
            'banner_url'=>$request->banner_url,
            'banner_image'=>$save_url 
        ]);

        // old image delete end
        if (file_exists($old_img)) {
            unlink($old_img);
        }


         $notification = array(
            'message' => 'Banner Update with image Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('all.banner')->with($notification);


        }else{
       Banner::findOrFail($banner_id)->update([
            'banner_title'=>$request->banner_title,
            'banner_url'=>$request->banner_url,
           
        ]);

         $notification = array(
            'message' => 'Banner Update whithout image Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('all.banner')->with($notification);
        }
    }// End Methord




     public function DeleteBanner($id)
    {
        $banner = Banner::findOrFail($id);
        $img = $banner->banner_image;
        unlink($img);

        Banner::findOrFail($id)->delete();

         $notification = array(
            'message' => 'Banner Delete whith image Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('all.banner')->with($notification);
    
    }// End Methord
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use Image;

class SliderController extends Controller
{
    public function AllSlider(){
        $slider = Slider::latest()->get();
        return view('backend.slider.slider_all',compact('slider'));
    }// Slider End Method


     public function AddSlider(){
        return view('backend.slider.slider_add');
    }// Slider End Method



     public function StoreSlider(Request $request)
    {
        $image = $request->file('slider_image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(2376,807)->save('uploade/slider/'.$name_gen);
        $save_url = 'uploade/slider/'.$name_gen;

        Slider::insert([
            'slider_title'=>$request->slider_title,
            'short_title'=>$request->short_title,
            'slider_image'=>$save_url ,
        ]);

         $notification = array(
            'message' => 'Slider Added Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('all.slider')->with($notification);
    } // End Methord




     public function EditSlider($id)
    {
        $slider = Slider::findOrFail($id);

        return view('backend.slider.slider_edit',compact('slider'));

    }// End Methord





    public function UpdateSlider(Request $request)
    {
        $slider_id = $request->id;
        $old_img = $request->old_image;


        if ($request->file('slider_image')) {
          $image = $request->file('slider_image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(2376,807)->save('uploade/slider/'.$name_gen);
        $save_url = 'uploade/slider/'.$name_gen;

// old image delete
        Slider::findOrFail($slider_id)->update([
            'slider_title'=>$request->slider_title,
            'short_title'=>$request->short_title,
            'slider_image'=>$save_url ,
        ]);

        if (file_exists($old_img)) {
            unlink($old_img);
        }
// old image delete end



         $notification = array(
            'message' => 'Slider Update with image Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('all.slider')->with($notification);
        }else{
         Slider::findOrFail($slider_id)->update([
            'slider_title'=>$request->slider_title,
            'short_title'=>$request->short_title,
        ]);

         $notification = array(
            'message' => 'Slider Update whithout image Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('all.slider')->with($notification);
        }
    }// End Methord

    public function DeleteSlider($id)
    {
        $slider = Slider::findOrFail($id);
        $img = $slider->slider_image;
        unlink($img);

        Slider::findOrFail($id)->delete();

         $notification = array(
            'message' => 'Slider Delete whith image Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('all.slider')->with($notification);
    
    }// End Methord



}

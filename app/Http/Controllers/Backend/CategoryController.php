<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Image;

class CategoryController extends Controller
{
    public function AllCategory(){
        $categorys = Category::latest()->get();
        return view('backend.category.category_all',compact('categorys'));
    }// Brand End Method


    public function AddCategory(){
        return view('backend.category.category_add');
    }// Brand End Method



     public function StoreCategory(Request $request)
    {
        $image = $request->file('category_image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(120,120)->save('uploade/category/'.$name_gen);
        $save_url = 'uploade/category/'.$name_gen;

        Category::insert([
            'category_name'=>$request->category_name,
            'category_slug'=> strtolower(str_replace(' ','-',$request->category_name)),
            'category_image'=>$save_url ,
        ]);

         $notification = array(
            'message' => 'Category Added Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('all.category')->with($notification);
    } // End Methord



     public function EditCategory($id)
    {
        // code...
        $category = Category::findOrFail($id);

        return view('backend.category.category_edit',compact('category'));

    }// End Methord



    public function UpdateCategory(Request $request)
    {
        $category_id = $request->id;
        $old_img = $request->old_image;


        if ($request->file('category_image')) {
            $image = $request->file('category_image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(120,120)->save('uploade/category/'.$name_gen);
        $save_url = 'uploade/category/'.$name_gen;

// old image delete
        Category::findOrFail($brand_id)->update([
            'category_name'=>$request->category_name,
            'category_slug'=> strtolower(str_replace(' ','-',$request->category_name)),
            'category_image'=>$save_url ,
        ]);

        if (file_exists($old_img)) {
            unlink($old_img);
        }
// old image delete end
         $notification = array(
            'message' => 'Category Update with image Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('all.category')->with($notification);
        }else{
            Category::findOrFail($category_id)->update([
            'category_name'=>$request->category_name,
            'category_slug'=> strtolower(str_replace(' ','-',$request->category_name)),
         
        ]);

         $notification = array(
            'message' => 'Category Update whithout image Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('all.category')->with($notification);
        }
    }// End Methord




    public function DeleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $img = $category->category_image;
        unlink($img);

        Category::findOrFail($id)->delete();

         $notification = array(
            'message' => 'Category Delete whith image Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('all.category')->with($notification);
    
    }// End Methord
}

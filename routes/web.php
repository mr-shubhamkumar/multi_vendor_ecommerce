<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\VendorController;
use  App\Http\Controllers\AdminController;
use  App\Http\Controllers\UserController;
use  App\Http\Controllers\Backend\BrandController;
use  App\Http\Controllers\Backend\CategoryController;
use  App\Http\Controllers\Backend\SubCategoryController;
use  App\Http\Controllers\Backend\ProductController;
use  App\Http\Controllers\Backend\VendorProductController;
use  App\Http\Controllers\Backend\SliderController;
use  App\Http\Controllers\Backend\BannerController;

use  App\Http\Controllers\User\WishlistController;
use  App\Http\Controllers\User\CompareController;

use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Frontend\CartController;
use  App\Http\Middleware\RedirectIfAuthenticated;




Route::get('/',[IndexController::class ,'Index']);


Route::middleware('auth')->group(function(){
    Route::get('/dashboard',[UserController::class ,'UserDashboard'])->name('dashboard');
    Route::post('/user/profile/store',[UserController::class, 'UserProfileStore'])->name('user.profile.store');
    Route::get('/user/logout',[UserController::class, 'UserLogout'])->name('user.logout');
    Route::post('/user/update/password',[UserController::class, 'UserUpdatePassword'])->name('user.update.password');
});

require __DIR__.'/auth.php';



//Admin Dashbord
Route::middleware(['auth','role:admin'])->group(function(){
 Route::get('/admin/dashboard',[AdminController::class, 'AdminDashboard'])->name('admin.dashboard');

  Route::get('/admin/logout',[AdminController::class, 'AdminDestroy'])->name('admin.logout');

  Route::get('/admin/profile',[AdminController::class, 'AdminProfile'])->name('admin.profile');
  Route::post('/admin/profile/store',[AdminController::class, 'AdminProfileStore'])->name('admin.profile.store');
  Route::get('/admin/change/password',[AdminController::class, 'AdminChangePassword'])->name('admin.change.password');
  Route::post('/admin/update/password',[AdminController::class, 'AdminUpdatePassword'])->name('update.password');


});

 Route::get('/admin/login',[AdminController::class, 'AdminLogin'])->middleware(RedirectIfAuthenticated::class);



//Vendor Dashboard
Route::middleware(['auth','role:vendor'])->group(function(){
 Route::get('/vendor/dashboard',[VendorController::class, 'VendorDashboard'])->name('vendor.dashboard');
 Route::get('/vendor/logout',[VendorController::class, 'VendorDestroy'])->name('vendor.logout');

 Route::get('/vendor/profile',[VendorController::class, 'VendorController'])->name('vendor.profile');
 Route::post('/vendor/profile/store',[VendorController::class, 'VendorProfileStore'])->name('vendor.profile.store');

 Route::get('/vendor/change/password',[VendorController::class, 'VendorChangePassword'])->name('vendor.change.password');
Route::post('/vendor/update/password',[VendorController::class, 'VendorUpdatePassword'])->name('vendor.update.password');

//Vendor Product Route
Route::controller(VendorProductController::class)->group(function(){
    Route::get('/vendor/all/product','VendorAllProduct')->name('vendor.all.product');
    Route::get('/vendor/add/product','VendorAddProduct')->name('vendor.add.product');
    Route::post('/vendor/store/product','VendorStoreProduct')->name('vendor.store.product');
    Route::get('/vendor/edit/product/{id}','VendorEditProduct')->name('vendor.edit.product');


    Route::post('/vendor/update/product','VendorUpdateProduct')->name('vendor.update.product');
    Route::post('/vendor/update/product/thambnail','VendorUpdateProductThambnail')->name('vendor.update.product.thambnail');
    Route::post('/vendor/update/product/multiimage','VendorUpdateProductMultiimage')->name('vendor.update.product.multiimage');
    Route::get('/vendor/product/multiimage/delete/{id}','VendorProductMultiimageDelete')->name('vendor.product.multiimage.delete');

    Route::get('/vendor/product/inactive/{id}','VendorProductInactive')->name('vendor.product.inactive');
    Route::get('/vendor/product/active/{id}','VendorProductAnactive')->name('vendor.product.active');

    Route::get('/vendor/delete/product/{id}','VendorDeleteProduct')->name('vendor.delete.product');

    


     Route::get('/vendor/subcategory/ajax/{category_id}','vendorGetSubCategory');
    
});


});
Route::get('/vendor/login',[VendorController::class, 'VendorLogin'])->name('vendor.login')->middleware(RedirectIfAuthenticated::class);
Route::post('/vendor/register',[VendorController::class, 'VendorRegister'])->name('vendor.register');
Route::get('/become/vendor',[VendorController::class, 'BecomeVendor'])->name('become.vendor');

//Vendor Dashboard

Route::middleware(['auth','role:admin'])->group(function(){

// Brand All Route
Route::controller(BrandController::class)->group(function(){
    Route::get('/all/brand','AllBrand')->name('all.brand');
    Route::get('/add/brand','AddBrand')->name('add.brand');
    Route::post('/store/brand','StoreBrand')->name('store.brand');
    Route::post('/update/brand','UpdateBrand')->name('update.brand');
    Route::get('/edit/brand/{id}','EditBrand')->name('edit.brand');
    Route::get('/delete/brand/{id}','DeleteBrand')->name('delete.brand');
});


// Category All Route
Route::controller(CategoryController::class)->group(function(){
    Route::get('/all/category','AllCategory')->name('all.category');
    Route::get('/add/category','AddCategory')->name('add.category');
    Route::post('/store/category','StoreCategory')->name('store.category');
    Route::post('/update/category','UpdateCategory')->name('update.category');
    Route::get('/edit/category/{id}','EditCategory')->name('edit.category');
    Route::get('/delete/category/{id}','DeleteCategory')->name('delete.category');
});


 

// SubCategory All Route
Route::controller(SubCategoryController::class)->group(function(){
    Route::get('/all/subcategory','AllSubCategory')->name('all.subcategory');
    Route::get('/add/subcategory','AddSubCategory')->name('add.subcategory');
    Route::post('/store/subcategory','StoreSubCategory')->name('store.subcategory');
    Route::post('/update/subcategory','UpdateSubCategory')->name('update.subcategory');
    Route::get('/edit/subcategory/{id}','EditSubCategory')->name('edit.subcategory');
    Route::get('/delete/subcategory/{id}','DeleteSubCategory')->name('delete.subcategory');
    Route::get('/subcategory/ajax/{category_id}','GetSubCategory');
});




// Vendor Active and Inactive  All Route
Route::controller(AdminController::class)->group(function(){
    Route::get('/inactive/vendor','InactiveVendor')->name('inactive.vendor');
    Route::get('/active/vendor','ActiveVendor')->name('active.vendor');
    Route::get('/inactive/vendor/details/{id}','InactiveVendorDetails')->name('inactive.vendor.details');
    Route::get('/active/vendor/details/{id}','ActiveVendorDetails')->name('active.vendor.details');
    Route::get('/active/vendor/approve','ActiveVendorApprove')->name('active.vendor.approve');
    Route::get('/inactive/vendor/approve','InactiveVendorApprove')->name('inactive.vendor.approve');
});

// Product All Route
Route::controller(ProductController::class)->group(function(){
    Route::get('/all/product','AllProduct')->name('all.product');
    Route::get('/add/product','AddProduct')->name('add.product');
    Route::post('/store/product','StoreProduct')->name('store.product');
    Route::get('/edit/product/{id}','EditProduct')->name('edit.product');
    Route::post('/update/product','UpdateProduct')->name('update.product');
    Route::post('/update/product/thambnail','UpdateProductThambnail')->name('update.product.thambnail');

    Route::post('/update/product/multiimage','UpdateProductMultiImage')->name('update.product.multiimage');

     Route::get('/product/multiimage/delete/{id}','MultiImageDelete')->name('product.multiimage.delete');

    Route::get('/product/inactive/{id}','ProductInactive')->name('product.inactive');
    Route::get('/product/active/{id}','ProductActive')->name('product.active');

    Route::get('/delete/product/{id}','DeleteProduct')->name('delete.product');

    
});



//Slider Controller 
Route::controller(SliderController::class)->group(function(){
    Route::get('/all/slider','AllSlider')->name('all.slider');
    Route::get('/add/slider','AddSlider')->name('add.slider');
    Route::post('/store/slider','StoreSlider')->name('store.slider');
    Route::post('/update/slider','UpdateSlider')->name('update.slider');
    Route::get('/edit/slider/{id}','EditSlider')->name('edit.slider');
    Route::get('/delete/slider/{id}','DeleteSlider')->name('delete.slider');
});//Slider Controller End


//Banner Controller 
Route::controller(BannerController::class)->group(function(){
    Route::get('/all/banner','AllBanner')->name('all.banner');
    Route::get('/add/banner','AddBanner')->name('add.banner');
    Route::post('/store/banner','StorerBanner')->name('store.banner');
    Route::post('/update/banner','UpdateBanner')->name('update.banner');
    Route::get('/edit/banner/{id}','EditBanner')->name('edit.banner');
    Route::get('/delete/banner/{id}','DeleteBanner')->name('delete.banner');
});//Banner Controller End


}); //Admin End Middleware

// Frontend Products Details All Route

Route::controller(IndexController::class)->group(function(){
Route::get('/product/details/{id}/{slug}','ProductDetails');
Route::get('/vendor/details/{id}','VendorDetails')->name('vendor.details');
Route::get('/all/vendor/','AllVendor')->name('vendor.all');
Route::get('/product/category/{id}/{slug}','CatWiseProduct');
Route::get('/product/subcategory/{id}/{slug}','SubCatWiseProduct');

// Product View Model
Route::get('/product/view/modal/{id}','ProductViewModel');

});




Route::controller(CartController::class)->group(function(){
Route::post('/dcart/data/store/{id}',  'AddToDCart');
Route::post('/cart/data/store/{id}',  'AddToCart');
Route::get('/product/mini/cart',  'AddMiniCart');
Route::get('/minicart/product/remove/{rowId}',  'RemoveMiniCart');
   
});//Cart Controller End


// Compere Product
Route::post('/add-to-compare/{compere_id}', [CompareController::class, 'AddToCompere']);

//ADD TO Wish List 
Route::post('/add-to-wishlist/{product_id}',[WishlistController::class,'addToWishList']);


//USER ALL ROUTE
Route::middleware(['auth','role:user'])->group(function(){

//Wishlist Controller 
Route::controller(WishlistController::class)->group(function(){
    Route::get('/wishlist','AllWishList')->name('wishlist');
    Route::get('/get-wishlist-product/','GetWishlistProduct');
    Route::get('/wishlist-remove/{id}','RemoveWishlist');
   
});//Wishlist Controller End

//Wishlist Controller 
Route::controller(CompareController::class)->group(function(){
    Route::get('/compare','AllCompare')->name('compare');
    Route::get('/get-compare-product/','GetCompareProduct');
    Route::get('/compare-remove/{id}','RemoveCompere');
   
});//Compare Controller End


// Cart Controller 
Route::controller(CartController::class)->group(function(){
    Route::get('/mycart','MyCart')->name('mycart');
    Route::get('/get-cart-product','GetCartProduct');
    Route::get('/remove-cart/{rowId}','CartRemove');

    Route::get('/cart-decrement/{rowId}','CartDecrement');
    Route::get('/cart-increment/{rowId}','CartIncrement');
   
});//Cart Controller End

}); // End Group Middleware
<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Coupon;
use App\Models\ShipState;
use App\Models\ShipDistrict;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function AddToCart(Request $request, $id){

        $product = Product::findOrFail($id);

        if(Session::has('coupon')){
            Session::forget('coupon');
        }

        if ($product->discount_price == NULL) {

            Cart::add([

                'id' => $id,
                'name' => $request->product_name,
                'qty' => $request->quantity,
                'price' => $product->selling_price,
                'weight' => 1,
                'options' => [
                    'image' => $product->product_thambnail,
                    'color' => $request->color,
                    'size' => $request->size,
                    'vendor' => $request->vendor,
                ],
            ]);

   return response()->json(['success' => 'Successfully Added on Your Cart' ]);

        }else{

            Cart::add([

                'id' => $id,
                'name' => $request->product_name,
                'qty' => $request->quantity,
                'price' => $product->selling_price - $product->discount_price,
                'weight' => 1,
                'options' => [
                    'image' => $product->product_thambnail,
                    'color' => $request->color,
                    'size' => $request->size,
                    'vendor' => $request->vendor,
                ],
            ]);

   return response()->json(['success' => 'Successfully Added on Your Cart' ]);

        }

    }// End Method



    function AddMiniCart(){
        $carts = Cart::content();
        $cartQty  = Cart::count();
        $cartTotal = Cart::total();
        return response()->json(
            array(
                'carts' => $carts,
                'cartQty' => $cartQty,
                'cartTotal' => $cartTotal,
            )
        );

    }// End Method

    function RemoveMiniCart($rowId){
        Cart::remove($rowId);
        return response()->json(['success' => 'Successfully Remove Product on  Cart' ]);

    }// End Method




    public function AddToDCart(Request $request, $id){

        $product = Product::findOrFail($id);

        if ($product->discount_price == NULL) {

            Cart::add([

                'id' => $id,
                'name' => $request->product_name,
                'qty' => $request->quantity,
                'price' => $product->selling_price,
                'weight' => 1,
                'options' => [
                    'image' => $product->product_thambnail,
                    'color' => $request->color,
                    'size' => $request->size,
                    'vendor' => $request->vendor,
                ],
            ]);

   return response()->json(['success' => 'Successfully Added on Your Cart' ]);

        }else{

            Cart::add([

                'id' => $id,
                'name' => $request->product_name,
                'qty' => $request->quantity,
                'price' => $product->selling_price - $product->discount_price,
                'weight' => 1,
                'options' => [
                    'image' => $product->product_thambnail,
                    'color' => $request->color,
                    'size' => $request->size,
                    'vendor' => $request->vendor,
                    
                ],
            ]);

   return response()->json(['success' => 'Successfully Added on Your Cart' ]);

        }

    }// End Method


// My Cart Page

    public function MyCart()
    {
        return view("frontend.mycart.view_mycart");
    } // End Method



    public function GetCartProduct()
    {
        $carts = Cart::content();
        $cartQty  = Cart::count();
        $cartTotal = Cart::total();
        return response()->json(
            array(
                'carts' => $carts,
                'cartQty' => $cartQty,
                'cartTotal' => $cartTotal,
            )
        );
    }// End Method



    public function CartRemove($rowId)
    {
        Cart::remove($rowId);

         if (Session::has('coupon')) {
            $coupon_name = Session::get('coupon')['coupon_name'];
            $coupon = Coupon::where('coupon_name',$coupon_name)->first();

            Session::put('coupon',[
            'coupon_name'=> $coupon->coupon_name,
            'coupon_discount'=> $coupon->coupon_discount,
            'discount_amout'=> round(Cart::total()*$coupon->coupon_discount/100),
            'total_amount'=> round(Cart::total() - Cart::total() * $coupon->coupon_discount/100 )
        ]);
        }
        return response()->json(['success'=>'Cart Product Successfully Remove']);

    }// End Method


    public function CartDecrement($rowId)
    {
        $row = Cart::get($rowId);
        Cart::update($rowId, $row->qty -1);

        if (Session::has('coupon')) {
            $coupon_name = Session::get('coupon')['coupon_name'];
            $coupon = Coupon::where('coupon_name',$coupon_name)->first();

            Session::put('coupon',[
            'coupon_name'=> $coupon->coupon_name,
            'coupon_discount'=> $coupon->coupon_discount,
            'discount_amout'=> round(Cart::total()*$coupon->coupon_discount/100),
            'total_amount'=> round(Cart::total() - Cart::total() * $coupon->coupon_discount/100 )
        ]);
        }

        return response()->json('Decrement');

    }// End Method



    public function CartIncrement($rowId)
    {
        $row = Cart::get($rowId);
        Cart::update($rowId, $row->qty +1);

        if (Session::has('coupon')) {
            $coupon_name = Session::get('coupon')['coupon_name'];
            $coupon = Coupon::where('coupon_name',$coupon_name)->first();
            
            Session::put('coupon',[
            'coupon_name'=> $coupon->coupon_name,
            'coupon_discount'=> $coupon->coupon_discount,
            'discount_amout'=> round(Cart::total()*$coupon->coupon_discount/100),
            'total_amount'=> round(Cart::total() - Cart::total() * $coupon->coupon_discount/100 )
        ]);
        }

        return response()->json('Increment');

    }// End Method



    //Coupon Apply start
    public function ApplyCoupon(Request $request)
    {
      $coupon = Coupon::where('coupon_name',$request->coupon_name)->where('coupon_validity','>=',Carbon::now()->format('Y-m-d'))->first();
      if ($coupon) {
        Session::put('coupon',[
            'coupon_name'=> $coupon->coupon_name,
            'coupon_discount'=> $coupon->coupon_discount,
            'discount_amout'=> round(Cart::total()*$coupon->coupon_discount/100),
            'total_amount'=> round(Cart::total() - Cart::total() * $coupon->coupon_discount/100 )
        ]);

        return response()->json([
            'validity'=> true,
            'success'=> 'Coupon Applied Successfully'
        ]);
      }else{
        return response()->json([
            'error'=> 'Coupon Code Invalid'
        ]);
      }
    }


    public function CouponCalculation(){
        if (Session::has('coupon')) {
            # code...
            return response()->json([
                'subtotal'=> Cart::total(),
                'coupon_name'=> session()->get('coupon')['coupon_name'],
                'coupon_discount'=> session()->get('coupon')['coupon_discount'],
                'discount_amout'=> session()->get('coupon')['discount_amout'],
                'total_amount'=> session()->get('coupon')['total_amount'],
            ]);
        }else{
            return response()->json([
                'total'=> Cart::total()
            ]);
        }
    }// End Method

    public function CouponRemove()
    {
        Session::forget('coupon');
        return response()->json([
            'success'=> 'Coupon Remove Successfully'
        ]);
    }


    // CheckoutCreate start
    public function CheckoutCreate()
    {
       if (Auth::user()) {

           if (Cart::total() > 0) {
            $carts = Cart::content();
            $cartQty  = Cart::count();
            $cartTotal = Cart::total();
            $state = ShipState::orderBy('state_name','ASC')->get();
            // dd($carts,$cartQty,$cartTotal,$state);


            return view('frontend.checkout.checkout_view',compact('carts','cartQty','cartTotal','state'));

           }else{
            $notification = array(
            'message' => 'Shopping At list One Product',
            'alert-type' => 'error'
        );

         return redirect()->to('/')->with($notification);

           }



       }else{
        $notification = array(
            'message' => 'You need to Login First',
            'alert-type' => 'error'
        );

         return redirect()->route('login')->with($notification);

       }


    }// CheckoutCreate End


   

}

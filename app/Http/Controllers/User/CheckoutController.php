<?php

namespace App\Http\Controllers\User;

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

class CheckoutController extends Controller
{
    public function DistrictAjax($id)
    {

    $district = ShipDistrict::where('state_id',$id)->orderBy('districts_name','ASC')->get();
    return json_encode($district);
        
    }


    public function CheckoutStore(Request $request)
    {
        $data = array();
        $data['shipping_name']=$request->shipping_name;
        $data['shipping_email']=$request->shipping_email;
        $data['state_id']=$request->state_id;
        $data['shipping_phone']=$request->shipping_phone;
        $data['district_id']=$request->district_id;
        $data['shipping_pincode']=$request->shipping_pincode;
        $data['shipping_address']=$request->shipping_address;
        $data['info']=$request->info;
        $data['shipping_address']=$request->shipping_address;
        $cartTotal = Cart::total();

        if ($request->payment_option == 'stripe') {
            return view('frontend.payment.stripe',compact('data','cartTotal'));
        }elseif ($request->payment_option == 'cash') {
             return view('frontend.payment.cash',compact('data','cartTotal'));
        }else{
            return view('frontend.payment.cash',compact('data','cartTotal'));
        };
    }// End Method





    
}

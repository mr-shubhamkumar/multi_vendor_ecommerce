<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class VendorController extends Controller
{
    public function VendorDashboard(){
        return view('vendor.index');

    } // End Method


    public function VendorLogin(){

        return view('vendor.vendor_login');
    }  // End Method


     public function VendorDestroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('vendor/login');
    } // End Method



     public function VendorController(){
        $id = Auth::user()->id;
        $vendorData = User::find($id);

        return view('vendor.vendor_profile_view', compact('vendorData'));
    } // End Method


    public function VendorProfileStore(Request $request)
    {
        $id = Auth::user()->id;
        $data = User::find($id);

        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->vendor_join = $request->vendor_join;
        $data->vendor_short_info = $request->vendor_short_info;


        if ($request->file('photo')) {
            $file = $request->file('photo');
            if ($data->photo == $request->file('photo')) {
                unlink(public_path('uploade/vendor_images/').$data->photo);
            }
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('uploade/vendor_images/'),$filename);
            $data['photo'] = $filename;
        }

        $data->save();

        $notification = array(
            'message' => 'Vendor Profile Update Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method


      public function VendorChangePassword(){
        return view('vendor.vendor_change_password');

    }// End Method

    public function VendorUpdatePassword(Request $request){

        //Validation
        $request->validate([
            'old_password'=> 'required',
            'new_password'=> 'required|confirmed',
            
        ]);

        //Match The Old Password
        if (!Hash::check($request->old_password, auth::user()->password)) {
            return back()->with('error', "Old Password Doesn't Match!!");
        }

        //Update The New Password
        User::whereId(Auth::user()->id)->update([
            'password'=> Hash::make($request->new_password)
        ]);

        return back()->with('status',"Password Update Successfully");

    }// End Method

    // ===================================== Become Vendor =======================================

    public function BecomeVendor(){
        return view('auth.become_vendor');
    }

    public function VendorRegister(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'vendor_join' => $request->vendor_join,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'vendor',
            'status' => 'inactive',
        ]);

         $notification = array(
            'message' => 'Vendor Rgister Successfully',
            'alert-type' => 'success'
        );
      

        return redirect()->route('vendor.login')->with('notification');
    }


}

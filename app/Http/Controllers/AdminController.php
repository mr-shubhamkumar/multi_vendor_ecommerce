<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class AdminController extends Controller
{
    public function AdminDashboard(){

        return view('admin.index');
    } // End Method

    public function AdminLogin(){

        return view('admin.admin_login');
    }  // End Method

     public function AdminDestroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('admin/login');
    } // End Method



    public function AdminProfile(){
        $id = Auth::user()->id;
        $adminData = User::find($id);

        return view('admin.admin_profile_view', compact('adminData'));
    } // End Method

    public function AdminProfileStore(Request $request)
    {
        $id = Auth::user()->id;
        $data = User::find($id);

        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;


        if ($request->file('photo')) {
            $file = $request->file('photo');
            // unlink(public_path('uploade/admin_images/').$data->photo);
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('uploade/admin_images/'),$filename);
            $data['photo'] = $filename;
        }

        $data->save();

        $notification = array(
            'message' => 'Admin Profile Update Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method


    public function AdminChangePassword(){
        return view('admin.admin_change_password');

    }// End Method



    public function AdminUpdatePassword(Request $request){

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


    // Inactive Vendor 
    public function InactiveVendor(){ 
        $InactiveVendor = User::where('status','inactive')->where('role','vendor')->latest()->get();

        return view('backend.vendor.vendor_inactive',compact('InactiveVendor'));
    }

    // Active Vendor 
    public function ActiveVendor(){ 
        $ActiveVendor = User::where('status','active')->where('role','vendor')->latest()->get();

        return view('backend.vendor.vendor_active',compact('ActiveVendor'));
    }


    public function InactiveVendorDetails($id){
        $inactiveVendorDetails = User::findOrFail($id);
        return view('backend.vendor.vendor_inactive_details',compact('inactiveVendorDetails'));
    }


    public function ActiveVendorApprove(Request $request){
        $vendor_id = $request->id;
        $user = User::findOrFail($vendor_id)->update([
            'status'=> 'active'
        ]);

        $notification = array(
            'message' => 'Vendor  Active Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('active.vendor')->with($notification);
    }


     public function ActiveVendorDetails($id){
        $activeVendorDetails = User::findOrFail($id);
        return view('backend.vendor.vendor_active_details',compact('activeVendorDetails'));
    }


       public function InactiveVendorApprove(Request $request){
        $vendor_id = $request->id;
        $user = User::findOrFail($vendor_id)->update([
            'status'=> 'inactive'
        ]);

        $notification = array(
            'message' => 'Vendor  Inactive Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('inactive.vendor')->with($notification);
    }
}

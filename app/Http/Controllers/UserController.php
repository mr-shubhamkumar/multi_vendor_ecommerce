<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function UserDashboard(){
        $id = Auth::user()->id;
        $userData = User::find($id);
        return view('index',compact('userData'));
    }// End Methord


    public function UserProfileStore(Request $request)
    {
        $id = Auth::user()->id;
        $data = User::find($id);

        $data->username = $request->username;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;


        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('uploade/user_images/').$data->photo);
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('uploade/user_images/'),$filename);
            $data['photo'] = $filename;
        }

        $data->save();

        $notification = array(
            'message' => 'User Profile Update Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method



    public function UserLogout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
          $notification = array(
            'message' => 'Logout Successfully',
            'alert-type' => 'success'
        );

        return redirect('/login')->with($notification);
    } // End Method

      public function UserUpdatePassword(Request $request){

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


}

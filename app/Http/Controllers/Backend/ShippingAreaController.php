<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShipStateShipState;
use App\Models\ShipDistrict;
use App\Models\ShipState;
use Carbon\Carbon;

class ShippingAreaController extends Controller
{
///////////////////////// Division Crud Start //////////////////////////////////////////////////////////
    public function AllDivision(){
        $state = ShipState::latest()->get();
        return view('backend.ship.state.state_all',compact('state') );
    }// End Method


    public function AddDivision(){
        return view('backend.ship.state.state_add');
    }// Coupon End Method




    public function StoreDivision(Request $request)
    {
        ShipState::insert([
            'state_name'=>$request->state_name,
            
            
        ]);

         $notification = array(
            'message' => 'State Added Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('all.state')->with($notification);
    }// End Method



    public function EditDivision($id)
    {
        $state = ShipState::findOrFail($id);
        return view('backend.ship.state.state_edit',compact('state'));
    }// End Method




    public function UpdateDivision(Request $request){
        $state_id = $request->id;
         ShipState::findOrFail($state_id)->update([
          'state_name'=>$request->state_name,
            
        ]);
 
         $notification = array(
            'message' => 'State Update Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('all.state')->with($notification);
    }// End Method





     public function DeleteDivision($id){
        ShipState::findOrFail($id)->delete();
        $notification = array(
            'message' => 'State Delete Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('all.state')->with($notification);
    }// End Method
  

///////////////////////// Division Crud End//////////////////////////////////////////////////////////


///////////////////////// District Crud Start //////////////////////////////////////////////////////////
    public function AllDistrict(){
        $district = ShipDistrict::with('state')->latest()->get();

        return view('backend.ship.district.district_all',compact('district') );
    }// End Method



    public function AddDistrict(){
        $state = ShipState::all();
        return view('backend.ship.district.district_add',compact('state'));
    }// Coupon End Method


    public function StoreDistrict(Request $request)
    {
        ShipDistrict::insert([
            'state_id'=>$request->state_id,
            'districts_name'=>$request->districts_name,    
        ]);

         $notification = array(
            'message' => 'District Added Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('all.district')->with($notification);
       
    }// End Method



    public function EditDistrict($id)
    {
        $district = ShipDistrict::findOrFail($id);
        $state = ShipState::all();
        return view('backend.ship.district.district_edit',compact('district','division'));
    }// End Method


     public function UpdateDistrict(Request $request)
    {
        $district_id = $request->id;
        ShipDistrict::findOrFail($district_id)->update([
            'state_id'=>$request->division_id,
            'districts_name'=>$request->districts_name,    
        ]);



         $notification = array(
            'message' => 'District Update Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('all.district')->with($notification);
       
    }// End Method


    public function DeleteDistrict($id)
    {
        ShipDistrict::findOrFail($id)->delete();
         $notification = array(
            'message' => 'District Delete Successfully',
            'alert-type' => 'success'
        );

         return redirect()->route('all.district')->with($notification);

    }

    ///////////////////////// District Crud End //////////////////////////////////////////////////////////
}

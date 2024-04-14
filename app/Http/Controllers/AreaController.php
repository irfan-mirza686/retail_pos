<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use App\Models\Area;
use App\Models\Customer;
use Exception;

class AreaController extends Controller
{
    /*====================================*/
    public function authenticateRole($module_page)
    {
        $permissionCheck =  checkRolePermission($module_page);
        if($permissionCheck->access == 0){
            return redirect()->to('/dashboard')->send()->with('error','You have no permission!');
        }
    }
    /*===================================================*/
    public function index()
    {
        $this->authenticateRole($module_page='settings');
        Session::put('page','areas');
        $areas = Area::get();
        return view('area.view', compact('areas'));
    }
    /*==========================================================*/
    public function addArea(Request  $request)
    {
        $this->authenticateRole($module_page='settings');
        if ($request->isMethod('post')) {

            $data = $request->all();
            try {
                $areas = new Area;
                $areas->name = $data['name'];
                $areas->save();
                return redirect('/areas')->with('success','Area Successfully Created!');
            } catch (Exception $e) {
                Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
                return redirect()->back()->with($e->getMessage());
            }
        }else{
            Session::put('page','areas');
            return view('area.add');
        }
    }
    /*==========================================================*/
    public function editArea(Request $request, $id=null)
    {
        $this->authenticateRole($module_page='settings');
        if ($request->isMethod('post')) {
            $data =  $request->all();
            try {
                $updateArea = Area::find($id);
                $updateArea->name = $data['name'];
                $updateArea->save();
                return redirect('/areas')->with('success','Area Successfully Updated!');
            } catch (Exception $e) {
                Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
                return redirect()->back()->with($e->getMessage());
            }
        }else{
            $editArea = Area::find($id);
            return view('area.edit', compact('editArea'));
        }
    }
    /*==========================================================*/
    public function deleteArea($id=null)
    {
        $this->authenticateRole($module_page='settings');
        $getArea = Customer::select('area_id')->where('area_id',$id)->first();
        // echo "<pre>"; print_r($getSales); exit();
        if ($getArea) {
           return redirect()->back()->with('error','Area cannot deleted!');
            
        }else{
            Area::find($id)->delete();
            return redirect()->back()->with('success','Area Successfully deleted');
        }
    }
    /*==========================================================*/
}

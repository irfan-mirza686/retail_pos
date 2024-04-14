<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use App\Models\Unit;
use App\Models\Product;
use Exception;

class UnitController extends Controller
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
        Session::put('page','units');
    	$units = Unit::get();
    	return view('units.view', compact('units'));
    }
    /*==========================================================*/
    public function createUnit(Request  $request)
    {
        $this->authenticateRole($module_page='settings');
    	if ($request->isMethod('post')) {

    		$data = $request->all();
    		try {
    			$units = new Unit;
    			$units->name = $data['name'];
    			$units->save();
    			return redirect('/units')->with('success','Unit Successfully Created!');
    		} catch (Exception $e) {
    			Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
				return redirect()->back()->with($e->getMessage());
    		}
    	}else{
    		return view('units.create');
    	}
    }
    /*==========================================================*/
    public function editUnit(Request $request, $id=null)
    {
        $this->authenticateRole($module_page='settings');
    	if ($request->isMethod('post')) {
    		$data =  $request->all();
    		try {
    			$updateUnit = Unit::find($id);
    			$updateUnit->name = $data['name'];
    			$updateUnit->save();
    			return redirect('/units')->with('success','Unit Successfully Updated!');
    		} catch (Exception $e) {
    			Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
				return redirect()->back()->with($e->getMessage());
    		}
    	}else{
    		$editUnits = Unit::find($id);
    		return view('units.edit', compact('editUnits'));
    	}
    }
    /*==========================================================*/
    public function deleteUnit($id=null)
    {
        $this->authenticateRole($module_page='settings');
        $getUnit = Product::select('unit_id')->where('unit_id',$id)->first();
        // echo "<pre>"; print_r($getSales); exit();
        if ($getUnit) {
           return redirect()->back()->with('error','Unit cannot deleted!');
            
        }else{
            Unit::find($id)->delete();
            return redirect()->back()->with('success','Unit Successfully deleted');
        }
    }
}

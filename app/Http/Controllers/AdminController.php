<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use App\User;
use App\Group;
use App\Models\Sale;
use App\Models\PurchasePayment;
use App\Models\Purchase;
use Hash;
use Carbon\Carbon;
use DB;
use App\Models\GroupPermission;
use App\Models\Employee;
use Exception;

class AdminController extends Controller
{
	/*====================================*/
    public function authenticateRole($module_page)
    {
    	// dd($module_page);
        $permissionCheck =  checkRolePermission($module_page);
        if($permissionCheck->access == 0){
        	return redirect()->to('/dashboard')->send()->with('error','You have no permission!');
        }
    }
	/*===================================================*/
	public function login(Request $request)
	{
		$data = $request->input();
		if(Auth::attempt(['email'=>$data['email'],'password'=>$data['password'],'status'=>'1'])){
			return redirect('/dashboard')->with('success','Welcome  '. Auth::user()->name);
		}else{
			return redirect('/')->with('flash_message_error','Invalid username or password');
		}
	}
	/*===================================================*/
	public function dashboard()
	{
		$permissionCheck =  checkRolePermission($module_page='dashboard');
	
		$page_dashboard = "dashboard";
		Session::put('page','dashboard');

		/* Calculate Purchase Start */
		$purchase = Purchase::where('status','received')->select(DB::raw("sum(amount) as sum"))->whereYear('date',date('Y'))->groupBy(DB::raw("Month(date)"))->pluck('sum');
		
		$purchaseMonth = Purchase::where('status','received')->select(DB::raw("Month(date) as month"))->whereYear('date',date('Y'))->groupBy(DB::raw("Month(date)"))->pluck('month');
		
		$purchaseData = [];
		$purMonthNum = range(1, 12);
		$purJoiner = array_combine($purchaseMonth->toArray(), $purchase->toArray());
		foreach($purMonthNum as $number) {
			$checkPurMonth = isset($purJoiner[$number])? $purJoiner[$number]:0;
			if(isset($checkPurMonth) && ($checkPurMonth>0)){
				$purchaseData[] = $checkPurMonth;
			}else{
				$purchaseData[] = 0;
			}
		}
		/* Calculate Purchase Ends */
		
		/* Calculate Sale Start */
		$sales = Sale::where('status',1)->select(DB::raw("sum(amount) as sum"))->whereYear('date',date('Y'))->groupBy(DB::raw("Month(date)"))->pluck('sum');
		
		$months = Sale::where('status',1)->select(DB::raw("Month(date) as month"))->whereYear('date',date('Y'))->groupBy(DB::raw("Month(date)"))->pluck('month');

		$datas = [];
		$monthNum = range(1, 12);
		$joiner = array_combine($months->toArray(), $sales->toArray());
		foreach($monthNum as $number) {
			$checkMonth = isset($joiner[$number])? $joiner[$number]:0;
			if(isset($checkMonth) && ($checkMonth>0)){
				$datas[] = $checkMonth;
			}else{
				$datas[] = 0;
			}
		}
		/* Calculate Sale Ends */

		$employees = Employee::get()->toArray();

		return view('dashboard',compact('page_dashboard','datas','purchaseData','employees','permissionCheck'));
	}
	/*===================================================*/
	public function logout()
	{
		Session::flush();
		return redirect('/')->with('flash_message_success','Logout Successfully');
	}
	/*===================================================*/
	public function  viewUsers()
	{
		$this->authenticateRole($module_page='users');
		try {
				Session::put('page','viewUser');

				$data['allData'] = User::get()->toArray();
    	// echo "<pre>"; print_r($data['allData']); exit();
				return view('users.view',$data);
			
		} catch (Exception $e) {
			Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
			return redirect()->back()->with($e->getMessage());
		}
		
	}
	/*===================================================*/
	public function addUser(Request $request)
	{
		$this->authenticateRole($module_page='users');
		if ($request->isMethod('post')) {

			$data = $request->all();
			/*Laravel Validation Start...................*/
			$rules = [
				'name' => 'required|regex:/^[\pL\s\-]+$/u',
				'email' => 'required|unique:users'
			];
			$customMessage = [
				'name.required' => 'user name is required',
				'name.regex' => 'valid user name is required',
				'email.required' => 'email is required',
				'email.unique' => 'email must be unique'

			];
			$this->validate($request, $rules, $customMessage);
			/*Laravel Validation End...................*/
			try {
				$admin = new User();
				$admin->name = $data['name'];
				$admin->group_id = $data['group_id'];
				$admin->email  = $data['email'];
				$admin->address  = $data['address'];
				$admin->password = Hash::make($data['password']);

				$admin->mobile = $data['mobile'];
				$admin->save();
				return redirect('/view-users')->with('success','User Successfully Added!');
			} catch (Exception $e) {
				Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
				return redirect('/add-user')->with($e->getMessage());
			}

		}else{
			$groups = Group::get();
			Session::put('page','addUser');
			return view('users.create',compact('groups'));
			
		}
	}
	/*===================================================*/
	public function editUser(Request $request, $id=null)
	{
		$this->authenticateRole($module_page='users');
		if ($request->isMethod('post')) {
			$data = $request->all();
			try {
				$editAdmin = User::find($id);
				$editAdmin->name = $data['name'];
				$editAdmin->group_id = $data['group_id'];
				$editAdmin->mobile = $data['mobile'];
				$editAdmin->address = $data['address'];
				$editAdmin->save();
				return redirect('/view-users')->with('success','User Successfully Updated!');
			} catch (Exception $e) {
				Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
				return redirect('/add-user')->with($e->getMessage());
			}
		}else{
			
			$editAdmin = User::find($id);
			$groups = Group::get();
    		// echo "<pre>"; print_r($editAdmin->toArray()); exit();
			return view('users.create',compact('editAdmin','groups'));
			
		}
	}
	/*===================================================*/
	public function deleteUser($id=null)
	{
		$this->authenticateRole($module_page='users');
			$checkUser = User::find($id);
			$userEmail = $checkUser['email'];
			if ($userEmail === 'admin@gmail.com') {
    		// dd('ok');
				return redirect()->back()->with('error','Supper Admin cannot be deleted');
			}else{
    		// dd('not ok');
				$deleteUser = User::find($id);
				$deleteUser->delete();
				return redirect('/view-users')->with('success','User Successfully Deleted!');
			}
		
	}
}

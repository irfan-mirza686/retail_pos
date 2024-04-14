<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Exception;
use Hash;
use Session;

class ProfileController extends Controller
{
	/*====================================================================*/
	public function index()
	{
		Session::put('page','manageProfile');
		$id = Auth::user()->id;
		$user = User::find($id)->toArray();
    	// echo "<pre>"; print_r($user); exit();
		return view('users.user-profile',compact('user'));
	}
	/*====================================================================*/
	public function editUser(Request $request, $id=null)
	{
		if ($request->isMethod('post')) {
			$updateProfile = User::find($id);
			// echo "<pre>"; print_r($request->all());exit();
			$data = $request->all();
			try {
				$updateProfile->name = $data['name'];
				$updateProfile->email = $data['email'];
				$updateProfile->mobile = $data['mobile'];
				$updateProfile->address = $data['address'];
				$updateProfile->gender = $data['gender'];
				// $updateProfile->password = Hash::make($data['password']);

				$updateProfile->save();
				return redirect('/user-profile')->with('success','User Profile Successfully Updated!');
			} catch (Exception $e) {
				Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
				return redirect('/user-profile')->with($e->getMessage());
			}
		}else{
			$editUser = User::find($id)->toArray();
			return view('users.edit-user-profile', compact('editUser'));
		}
	}
	/*====================================================================*/
	public function checkCurrentPassword(Request $request)
	{

		$data = $request->all();
    	// echo "<pre>"; print_r($data); exit();
		if(Hash::check($data['current_pwd'], Auth::user()->password)){
			echo "true";
		}else{
			echo "false";
		}
	}
	/*====================================================================*/
	public function updatePassword(Request $request)
	{

		if ($request->isMethod('post')) {
			$data = $request->all();
    		// Check Current Password is correct or not......
			if(Hash::check($data['current_pwd'], Auth::user()->password)){
    			// check new and confirm password is matching....
				if($data['new_pwd'] == $data['confirm_pwd']){
					User::where('id',Auth::user()->id)->update(['password' => bcrypt($data['new_pwd'])]);
    				// Session::flash('flash_message_success','Your password is update Successfully');
					return redirect()->back()->with('success','Your password is update Successfully!');
				}else{
    				// Session::flash('flash_message_error','Your New  Password and Confrim Password does not matched');

					return redirect()->back()->with('error','Confrim Password does not Matched!');
				}
			}else{
             // Session::flash('flash_message_error','Your Current Password is incorrect');

				return redirect()->back()->with('error','Your Current Password is incorrect!');
			}
			return redirect()->back();
		}else{
			Session::put('page','changePassword');
			$adminDetails = User::where('email',Auth::user()->email)->first(); 
			return view('users.update-password', compact('adminDetails'));
		}
	}
	/*====================================================================*/
}

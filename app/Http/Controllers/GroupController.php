<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Group;
use App\GroupPermission;
use App\GroupModule;
use Auth;
use Exception;
use DB;

class GroupController extends Controller
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
    public function index(Request $request)
    {
        $this->authenticateRole($module_page='roles');
        Session::put('page','roles');
      $groups = Group::get();
      return view('roles.view',compact('groups')); 
  }
      /*====================================*/
    public function addRole(Request $request)
    {
    $this->authenticateRole($module_page='roles');
    Session::put('page','roles');
        if ($request->isMethod('post')) {
            $inputData = $request->all();
            try {
                $groupName = new Group;

                $groupName->name = $inputData['name'];

                $groupName->save();

                $groupId = DB::getPdo()->lastInsertId();

                /*Get Last Insert ID of Group_Name_Table and Insert into Group_Permissions_Table */
                $insertData = $request->all();

                $module_id = $request->get('txtModID');
                $permission_modulename = $request->get('txtModname');
                $permission_modulepage = $request->get('txtModpage');
                $permission_access = $request->get('txtaccess');
           
            $permission = [];
                foreach($permission_access as $key => $val){

                        $permission[$val] = isset($permission_modulepage[$val])?1:0;
                    }
                // $checkPermission = ['txtaccess' => $permission];
                foreach ($permission_modulepage as $Pkey => $PID) {
               
                    $insertData = new GroupPermission;
                    $insertData->group_id = $groupId;
                    $insertData->module_id = $module_id[$Pkey];
                    $insertData->module_name = $permission_modulename[$Pkey];
                    $insertData->module_page = $permission_modulepage[$Pkey];
                    $insertData->access = isset($permission[$Pkey])?1:0;
                    $insertData->save();
                }
                return redirect()->route('roles')->with('success','Role Created!');

            } catch(Exception $e) {
                Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
                return redirect()->back()->with($e->getMessage());
            }
        }else{
            
                $groupModule = GroupModule::get();
                return view('roles.create',compact('groupModule'));
           
        }
    }

    /*====================================*/
    public function editRole(Request $request, $id=null)
    {
  
        $this->authenticateRole($module_page='roles');
        Session::put('page','roles');
        if ($request->isMethod('post')) {
            $groups = Group::findorfail($id);
            $inputData = $request->all();
            try {

                $groups->name = $request->get('name');
                $groups->save();

                $permission_access = $request->get('txtaccess');
                $permission = [];
                foreach($permission_access as $key => $val){

                    $permission[$val] = isset($permission_modulepage[$val])?1:0;
                }

                for ($i =0; $i<count($request->txtModID); $i++) {

                    GroupPermission::where('group_id', $id)->where('module_id', $request->txtModID[$i])
                    ->update([   
                        'access' => isset($permission[$i])?1:0 

                    ]);

                }
           
                return redirect()->route('roles')->with('success','Role Updated!');

            } catch(Exception $e) {
                Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
                return redirect()->back()->with($e->getMessage());
            }
        }else{

            $data['groupModule'] = GroupModule::get()->toArray();
            $data['editGroup'] = Group::where(['id' => $id])->first();
            $data['editPermission'] = GroupPermission::select('module_page')->where(['group_id' =>$id])->where('access',1)->get()->toArray();
            $data['mergeArr'] = array_column($data['editPermission'], 'module_page');

            return view('roles.edit',$data);

        }
    }
    /**********************************************/
}

<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\Models\Employee,App\Models\EmployeeAdvance;
use App\Models\AdvanceHistory;
use DB;
use Exception;

class EmployeeAdvanceController extends Controller
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
    public function advanceSalary(Request $request,$id=null)
    {
        $this->authenticateRole($module_page='employees');
        if ($request->isMethod('post')) {
            $data = $request->all();
            try {
                $employeeAdvanceHistory = new AdvanceHistory;
                $employeeAdvanceHistory->description = $request->description;
                $employeeAdvanceHistory->employee_id = $id;
                $employeeAdvanceHistory->current_paidAmount = $request->advance_amount;
                $employeeAdvanceHistory->date = date('Y-m-d',strtotime($data['date']));
                $employeeAdvanceHistory->status = isset($data['status'])?$data['status']:0;
                $employeeAdvanceHistory->save();

                return redirect('/employees')->with('success','Employee Advance Added!');
            } catch (Exception $e) {
                Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
                return redirect()->back()->with($e->getMessage());
            }
        }else{
            $employee = Employee::find($id);
            return view('employees.employee_advance',compact('employee'));
        }
    }
    /*============================================================*/
}

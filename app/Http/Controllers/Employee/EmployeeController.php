<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\Models\Employee,App\Models\EmployeeAdvance;
use App\Models\MonthlyEmployeeSalaryLog;
use App\Models\AdvanceHistory;
use App\Models\EmployeeReturnAdvance;
use Exception;

class EmployeeController extends Controller
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
        $this->authenticateRole($module_page='employees');
        Session::put('page','employees');
        $employees = Employee::orderBy('id','DESC')->get();
    	return view('employees.index',compact('employees'));
    }
    /*==========================================================*/
    public function create(Request $request)
    {
    	$this->authenticateRole($module_page='employees');
    	if ($request->isMethod('post')) {
    		$data = $request->all();
            try {
            $employee = new Employee;
            $employee->name = $data['name'];
            $employee->cnic = $data['cnic'];
            $employee->mobile = $data['mobile'];
            $employee->address = $data['address'];
            $employee->salary = isset($data['salary'])?$data['salary']:'N/A';
            $employee->save();
            return redirect('/employees')->with('success','Employee Successfully Added!');
            } catch (Exception $e) {
                Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
                return redirect('/user-profile')->with($e->getMessage());
            }
    	}else{

    		Session::put('page','createEmployee');
    		return view('employees.create');
    	}
    }
    /*==========================================================*/
    public function edit(Request $request, $id=null)
    {  
        $this->authenticateRole($module_page='employees');
        $editEmployee = Employee::find($id);
        $data = $request->all();
        if ($request->isMethod('post')) {
            try {
            $editEmployee->name = $data['name'];
            $editEmployee->cnic = $data['cnic'];
            $editEmployee->mobile = $data['mobile'];
            $editEmployee->address = $data['address'];
            $editEmployee->save();
            return redirect('/employees')->with('success','Employee Successfully Upadated!');
            } catch (Exception $e) {
                Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
                return redirect('/user-profile')->with($e->getMessage());
            }
        }else{
            return view('employees.create',compact('editEmployee'));
        }
    }
    
    /*===========================================================*/
    public function employeeDetails($id =null)
    {
        $this->authenticateRole($module_page='employees');
        $employeeDetails = MonthlyEmployeeSalaryLog::where('employee_id',$id)->get();
        $employee = Employee::find($id);
        return view('employees.employee_details',compact('employee','employeeDetails'));
    }
    /*==========================================================*/
    public function returnAdvance(Request $request, $id=null)
    {
        $this->authenticateRole($module_page='employees');
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); exit();
            try {
                $returnAdvance = new EmployeeReturnAdvance;
                $returnAdvance->employee_id = $id;
                $returnAdvance->date = date('Y-m-d',strtotime($data['date']));
                $returnAdvance->return_amount = $data['return_amount'];
                $returnAdvance->createdBy = Auth::user()->id;
                $returnAdvance->save();
                return redirect('/employees')->with('success','Advance Return Added!');
            } catch (Exception $e) {
                Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
                return redirect('/employees')->with($e->getMessage());
            }
        }else{
            Session::put('page','createEmployee');  
            $employee = Employee::find($id);
            $checkEmployeeAdvance = AdvanceHistory::where('employee_id',$id)->first();
            // echo"<pre>"; print_r($checkEmployeeAdvance); exit();
            if ($checkEmployeeAdvance) {
              
            return view('employees.return_advance',compact('employee'));
            }else{
                return redirect()->back()->with('error','This employee did not receive an advance.');
            }
            
        }
    }
    /*==========================================================*/
}

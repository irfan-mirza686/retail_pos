<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Session;
use App\Models\EmployeeLeave;
use App\Models\LeavePurpose;
use App\Models\EmployeeAdvance;
use App\Models\EmployeeAttendance;
use App\Models\AdvanceHistory;
use App\Models\Employee;
use App\Models\MonthlySalary;
use App\Models\EmployeeReturnAdvance;
use Exception;

class MonthlySalaryController extends Controller
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
    public function view()
    {
        $this->authenticateRole($module_page='employees');
    	Session::put('page','employeeMonthlySalary');
        $monthlySalary = MonthlySalary::with('employee')->get();
        // echo "<pre>"; print_r($monthlySalary->toArray());exit();
    	return view('employees.monthly_salaries.view',compact('monthlySalary'));
    }
    /*==============================================================*/
    public function monthlySalaryDatewiseGet(Request $request)
    {

    /* Calculate Monthly Advance */
     function testinggetMonthlySalary($employee_id){
        // dd($employee_id);
        $empMonthly = AdvanceHistory::where('employee_id',$employee_id)->get()->toArray();
        // echo"<pre>"; print_r($empMonthly); exit();
        $empAdvance = [];
        foreach ($empMonthly as $key => $value) {
            $empAdvance[] = $value['current_paidAmount'];
        }
        return $empAdvance;
    }
    /* Calculate Monthly Advance */

    /* Calculate Monthly Return Advacne */
     function returnMonthlySalary($employee_id){
        $empReturnMonthly = EmployeeReturnAdvance::where('employee_id',$employee_id)->get()->toArray();
        // echo"<pre>"; print_r($empReturnMonthly); exit();
        $empReturnAdvance = [];
        foreach ($empReturnMonthly as $key => $value) {
            $empReturnAdvance[] = $value['return_amount'];
        }
        return $empReturnAdvance;
    }
    /* Calculate Monthly Return Advacne */
        $date = date('Y-m',strtotime($request->date));
    	if ($date !='') {
    		$where[] = ['date','like',$date.'%'];
    	}
    	$data = Employee::get()->toArray();
        // echo "<pre>"; print_r($data); exit();

    	$html['thsource'] = '<th>SL</th>';
    	$html['thsource'] .= '<th>Employee Name</th>';
    	$html['thsource'] .= '<th>Total Advance</th>';
        $html['thsource'] .= '<th>Salary(This Month)</th>';
        $html['thsource'] .= '<th>Select</th>';
        foreach ($data as $key => $attend) {
            $check = $attend['id'];
            $advanceSalary = testinggetMonthlySalary($check);
            $advanceSalary = array_sum($advanceSalary);

            $returnMonthlyAdvance = returnMonthlySalary($check);
            $returnMonthlyAdvance = array_sum($returnMonthlyAdvance);
            // dd($advanceSalary);
        $account_salary = MonthlySalary::where('employee_id',$attend['id'])->where($where)->first();

        if ($account_salary !=null) {
            // dd('ok');
            $checked='checked';
        }else{
            $checked='';
         }


    		// $totalattend = EmployeeAttendance::with(['employee'])->where($where)->where('employee_id',$attend['id'])->get();
            // // echo "<pre>"; print_r($totalattend->toArray()); exit();
    		// $absentCount = count($totalattend->where('attend_status','Absent'));




    		// $color = 'success';

    		/*=================*/
    		$html[$key]['tdsource'] = '<td>'.($key+1).'</td>';
            $html[$key]['tdsource'] .= '<td>'.$attend['name'].'</td>';



            $remainAdvance = $advanceSalary - $returnMonthlyAdvance;

            $html[$key]['tdsource'] .= '<td>'.$remainAdvance.'</td>';



            // echo "<pre>"; print_r($finalfee); exit();
            $html[$key]['tdsource'] .='<td>'.$attend['salary'].' <input type="hidden" name="amount[]" value="'.$attend['salary'].'"'.'</td>';
            $html[$key]['tdsource'] .='<td>';
            $html[$key]['tdsource'] .=' <input type="hidden" name="employee_id[]" value="'.$attend['id'].'">'.'<input type="checkbox" name="checkmanage[]" value="'.$key.'" '.$checked.' style="transform: scal(1.5);margin-left: 10px;">'.'</td>';
            $html[$key]['tdsource'] .='</td>';

    	}
    	return response()->json(@$html);
    }


    /*==============================================================*/
    public function paySalary(Request $request)
    {
        $this->authenticateRole($module_page='employees');
        if ($request->isMethod('post')) {
            $date = date('Y-m', strtotime($request->date));
            MonthlySalary::where('date',$date)->delete();
            $checkdata = $request->checkmanage;
            if ($checkdata !=null) {
                for ($i=0; $i <count($checkdata); $i++) {
                    $data = new MonthlySalary;
                    $data->date = $date;
                    $data->employee_id = $request->employee_id[$checkdata[$i]];
                    $data->amount = $request->amount[$checkdata[$i]];
                    $data->save();
                }
            }
            if (!empty(@$data) || empty($checkdata)) {
                return redirect('/employee-monthly-salary')->with('success','Employee salary successfully paid!');
            }else{
                return redirect()->back()->with('error','Sorry! Data not saved');
            }
        }else{
            return view('employees.monthly_salaries.create');
        }
    }
    /*==============================================================*/
}

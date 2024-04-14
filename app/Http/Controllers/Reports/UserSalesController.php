<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerOpeningBalance;
use App\User;
use App\Models\Sale;
use Session;
use PDF;

class UserSalesController extends Controller
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
        $this->authenticateRole($module_page='reports');
            Session::put('page','userSaleReport');
            $users = User::get();
            return view('reports.user_sales.index',compact('users'));
    }
    /*=====================================================================*/
    public function getUsersSale(Request $request)
    {
        $data = $request->all();
        $customerPayment = '';
        // echo"<pre>"; print_r($data); exit();
        $startDate = date('Y-m-d',strtotime($data['startDate']));
        $endDate = date('Y-m-d',strtotime($data['endDate']));
       // Customer Wise Else Start
            if ($data['user_id']=='all') { // Fetch Sales Report All Customer
                $customerPayment = Sale::with(['customers','users'])->where('status',1)->whereBetween('date',[$startDate,$endDate])->get()->toArray();


        }else{ // Fetch Sales Report by Customer
            $customerPayment = Sale::with(['customers','users'])->where('status',1)->whereBetween('date',[$startDate,$endDate])->where('added_by',$data['user_id'])->get()->toArray();

            
        }
         // Customer Wise Else Ends
        if ($customerPayment) {
                $html['thsource'] =  '<th>#</th>';
                $html['thsource'] .= '<th>Date</th>';
                $html['thsource'] .= '<th>Invoice#</th>';
                $html['thsource'] .= '<th>Customer Name</th>';
                $html['thsource'] .= '<th>Sale By</th>';
                $html['thsource'] .= '<th>Total Amount</th>';

                $html['tdsource'] = null;
                $totalAmount = 0;
                $returnTotalAmount = 0;

                foreach ($customerPayment as $key => $value) {
                    // echo"<pre>"; print_r($value['amount']); exit();
                    $totalAmount = $value['amount'] + $totalAmount;
                    

                    $html[$key]['tdsource'] = '<td>'.($key+1).'</td>';
                    $html[$key]['tdsource'] .= '<td>'.date('d M Y',strtotime($value['date'])).'</td>';
                    $html[$key]['tdsource'] .= '<td><a target="_blank" href="' .url("sale-invoice").'/'.$value['id'].'">'.$value['invoice_no'].'</a></td>';
                    $html[$key]['tdsource'] .= '<td>'.$value['customers']['name'].'</td>';
                    $html[$key]['tdsource'] .= '<td>'.$value['users']['name'].'</td>';
                    $html[$key]['tdsource'] .= '<td style="text-align: right;">'.number_format($value['amount'],2).'</td>';
                    
                    
                }
                $returnTotalAmount = $totalAmount;
                $html['tfootsource'] = '<tr style="background: gray; font-weight: bold; color:white;"><td colspan="5">Total</td><td style="text-align: right; font-weight: bold; color:white;">'.number_format($returnTotalAmount,2).'</td></tr>';

                return response(@$html);
                // return response()->json(@$html);
            }else{
                return "false";
            }
        
    }
    /*=====================================================================*/
    public function downloadUserSalesPdf(Request $request)
    {
        $data = $request->all();
        // echo "<pre>"; print_r($data); exit();
        $startDate = date('Y-m-d',strtotime($data['startDate']));
        $endDate = date('Y-m-d',strtotime($data['endDate']));
         // Customer Wise Else Start
            if ($data['user_id']=='all') { // Fetch Sales Report All Supplier
            $customerPayment = Sale::with(['customers','users'])->where('status',1)->whereBetween('date',[$startDate,$endDate])->get()->toArray();

        }else{
            $customerPayment = Sale::with(['customers','users'])->where('status',1)->whereBetween('date',[$startDate,$endDate])->where('added_by',$data['user_id'])->get()->toArray();
        }
        // Customer Wise Else Ends
        
        
        
        $pdf = PDF::loadView('reports.pdf.sales.user-sale-report',compact('customerPayment','startDate','endDate'));
        return $pdf->stream('sale-report.pdf');
    }
    /*=====================================================================*/
    public function compare_date($element1, $element2) {
               
                $datetime1 = strtotime($element1['created_at']);
                $datetime2 = strtotime($element2['created_at']);
                return $datetime1 - $datetime2;
            }
    public function paymentsByStaff()
    {
        $this->authenticateRole($module_page='reports');
            Session::put('page','customerPaymentsByStaff');
            $users = User::get();
            return view('reports.user_sales.customerPaymentsByStaff',compact('users'));
    }
    /*=====================================================================*/
    public function downloadPaymentsByStaffPdf(Request $request)
    {
        $data = $request->all();
            // echo "<pre>"; print_r($data); exit();
        $startDate = date('Y-m-d',strtotime($data['startDate']));

        $endDate = date('Y-m-d',strtotime($data['endDate']));
        $customerPayment = '';

            if ($data['user_id']=='all') { // Fetch Sales Report All Customer
                $customerPaymentDebit = CustomerOpeningBalance::with(['customers','users'])->select('invoice_no','date','description','amount','created_at','customer_id','user_id')->whereBetween('date',[$startDate,$endDate])->where('type','debit')->get()->toArray();
                $customerPaymentCredit = CustomerOpeningBalance::with(['customers','users'])->select('invoice_no','date','description','amount','created_at','customer_id','user_id')->whereBetween('date',[$startDate,$endDate])->where('type','credit')->get()->toArray();


            }else{ 
                $customerPaymentDebit = CustomerOpeningBalance::with(['customers','users'])->select('invoice_no','date','description','amount','created_at','customer_id','user_id')->whereBetween('date',[$startDate,$endDate])->where('user_id',$request->user_id)->where('type','debit')->get()->toArray();
                $customerPaymentCredit = CustomerOpeningBalance::with(['customers','users'])->select('invoice_no','date','description','amount','created_at','customer_id','user_id')->whereBetween('date',[$startDate,$endDate])->where('user_id',$request->user_id)->where('type','credit')->get()->toArray();


            }
            // echo "<pre>"; print_r($customerPaymentDebit); exit();
            $arrDebitBalance = [];
            foreach ($customerPaymentDebit as $key => $value) {
             $arrDebitBalance[] = [
                'invoice_no' => "VCH-".($value['invoice_no']),
                'date' => $value['date'],
                'description' => $value['description'],
                'credit' => '',
                'debit' => $value['amount'],
                'created_at' => $value['created_at'],
                'customer' => $value['customers']['name'],
                'staff' => $value['users']['name']
            ];
        }

        $arrCreditBalance = [];
        foreach ($customerPaymentCredit as $key => $value) {
           $arrCreditBalance[] = [
            'invoice_no' => "VCH-".($value['invoice_no']),
            'date' => $value['date'],
            'description' => $value['description'],
            'credit' => $value['amount'],
            'debit' => '',
            'created_at' => $value['created_at'],
            'customer' => $value['customers']['name'],
            'staff' => $value['users']['name']
        ];
    }

    $debitCredit = array_merge($arrDebitBalance,$arrCreditBalance);
// echo "<pre>"; print_r($debitCredit); exit();
    usort($debitCredit, array("App\Http\Controllers\Reports\UserSalesController", 'compare_date'));
    $pdf = PDF::loadView('reports.pdf.sales.customer-paymentsByStaff-report',compact('debitCredit','startDate','endDate'));
    return $pdf->stream('sale-report.pdf');
    
}
}

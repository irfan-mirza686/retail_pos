<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;
use PDF;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\AdvanceCustomerPayment;
use App\Models\CustomerOpeningBalance;
use App\Models\Area;
class CreditDebitController extends Controller
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
            Session::put('page','creditDebitReport');
            $customers = Customer::get();
            $areas = Area::get();
            return view('reports.credit_debit_report.create',compact('customers','areas'));
    }
    /*=======================================================*/
   public function compare_date($element1, $element2) {
               
                $datetime1 = strtotime($element1['created_at']);
                $datetime2 = strtotime($element2['created_at']);
                return $datetime1 - $datetime2;
            } 
    public function creditDebitReport(Request $request)
    {
        if ($request->isMethod('post')) {

            $data = $request->all();
            // echo "<pre>"; print_r($data); exit();
            $startDate = date('Y-m-d',strtotime($data['startDate']));
            
            $endDate = date('Y-m-d',strtotime($data['endDate']));
            
            
            
            $newDate = date('Y-m-d',strtotime($data['startDate'] . '-1 days'));

            $oldDate = date('1990-01-01');

            $area = '';
            $areaID = '';
            $sale_type = $data['sale_type'];
            $receivable = '';
            $payable = '';
            $customerTotalBalance = '';
            $customerOPBlncInReport = '';
            $paymentDiscount = '';
            $customer = '';
            $debitCredit = '';
            $openingBalance = '';
         
            // echo "<pre>"; print_r($data); exit();
            if ($data['sale_type']=='areaWise') {
                $areaID = $data['area_id'];
                $area = Area::where('id',$data['area_id'])->first();

                
            }else{
                $customer_id = $data['customer_id'];
                $openingBalance = CustomerOpeningBalance::select('date','description','amount')->where('customer_id',$customer_id)->first();


               $totalAmount = Sale::select('invoice_no','date','description','amount','created_at')->where('customer_id',$customer_id)->whereBetween('date',[$startDate,$endDate])->get()->toArray();
            //   echo"<pre>"; print_r($totalAmount); exit();
               $arrSaleDebit = [];
               foreach ($totalAmount as $key => $value) {
                 $arrSaleDebit[] = [
                    'invoice_no' => "SN-".($value['invoice_no']),
                    'date' => $value['date'],
                    'description' => $value['description'],
                    'credit' => '',
                    'debit' => $value['amount'],
                    'created_at' => $value['created_at']
                ];
            }
               // echo "<pre>"; print_r($totalAmount); exit();
               $totalPayable = CustomerOpeningBalance::select('invoice_no','date','description','amount','created_at')->whereBetween('date',[$startDate,$endDate])->where('customer_id',$customer_id)->where('type','debit')->get()->toArray();
               // $debitAmount = array_merge($totalPayable, $totalAmount);
               
               $arrDebitBalance = [];
               foreach ($totalPayable as $key => $value) {
                 $arrDebitBalance[] = [
                    'invoice_no' => "VCH-".($value['invoice_no']),
                    'date' => $value['date'],
                    'description' => $value['description'],
                    'credit' => '',
                    'debit' => $value['amount'],
                    'created_at' => $value['created_at']
                ];
            }
           

               $totalAdvance = AdvanceCustomerPayment::select('invoice_no','date','description','amount','created_at')->whereBetween('date',[$startDate,$endDate])->where('customer_id',$customer_id)->get()->toArray();
                // echo "<pre>"; print_r($totalAdvance); exit();
               $arrCustomerPaymentCredit = [];
               foreach ($totalAdvance as $key => $value) {
                 $arrCustomerPaymentCredit[] = [
                    'invoice_no' => "CP-".($value['invoice_no']),
                    'date' => $value['date'],
                    'description' => $value['description'],
                    'credit' => $value['amount'],
                    'debit' => '',
                    'created_at' => $value['created_at']
                ];
            }
              
               $totalReceivable = CustomerOpeningBalance::select('invoice_no','date','description','amount','created_at')->whereBetween('date',[$startDate,$endDate])->where('customer_id',$customer_id)->where('type','credit')->get()->toArray();
               // $creditAmount = array_merge($totalReceivable, $totalAdvance);
               
               $arrCreditBalance = [];
               foreach ($totalReceivable as $key => $value) {
                 $arrCreditBalance[] = [
                    'invoice_no' => "VCH-".($value['invoice_no']),
                    'date' => $value['date'],
                    'description' => $value['description'],
                    'credit' => $value['amount'],
                    'debit' => '',
                    'created_at' => $value['created_at']
                ];
            }
            
            $debitCredit = array_merge($arrDebitBalance,$arrCreditBalance,$arrCustomerPaymentCredit,$arrSaleDebit);
            // echo "<pre>"; print_r($debitCredit); exit();

                        // Comparison function
              

            // Sort the array 
             // usort($debitCredit,function() {});
             // usort(array($this, 'compareDates'));
            // usort($debitCredit, array($this, "compare_date"));
            usort($debitCredit, array("App\Http\Controllers\Reports\CreditDebitController", 'compare_date'));
            // echo "<pre>"; print_r($debitCredit); exit();
               $paymentDiscount = AdvanceCustomerPayment::where('customer_id',$customer_id)->sum('payment_discount');
              
               
               $customerTotalBalance = ($this->paymentDicsount($customer_id) + $this->advanceAmount($customer_id) + $this->customerCreditAmount($customer_id)) - ($this->totalAmount($customer_id) + $this->customerDebitAmount($customer_id));

               $customerOPBlncInReport = $this->customerOPBalance($oldDate,$newDate,$customer_id);
    
               $customer = Customer::where('id',$request->customer_id)->first(); 
           }
           $sale_type = $data['sale_type'];
           $pdf = PDF::loadView('reports.pdf.credit_debit.report',compact('startDate','endDate','debitCredit','openingBalance','areaID','area','sale_type','customerTotalBalance','customerOPBlncInReport','customer','paymentDiscount'));
           return $pdf->stream('credit-debit-report.pdf');
       }
   }
    /*=======================================================*/
    public function customerOPBalance($oldDate,$newDate,$customer_id)
    {

        $customerBlncReturn = NULL;
        $totalSaleAmount = Sale::whereBetween('date',[$oldDate,$newDate])->where('customer_id',$customer_id)->where('status',1)->sum('amount');

        $customerPaymets = AdvanceCustomerPayment::where('customer_id',$customer_id)->whereBetween('date',[$oldDate,$newDate])->sum('amount');
        
        $customerDebitAmount = CustomerOpeningBalance::whereBetween('date',[$oldDate,$newDate])->where('customer_id',$customer_id)->where('type','debit')->sum('amount');

        $customerCreditAmount = CustomerOpeningBalance::whereBetween('date',[$oldDate,$newDate])->where('customer_id',$customer_id)->where('type','credit')->sum('amount');
        // dd($customerCreditAmount);
        $customerOP_blnc = ($this->paymentDicsount($customer_id) + $customerCreditAmount + $customerPaymets) - ($totalSaleAmount + $customerDebitAmount);

        if (!empty($customerOP_blnc)) {
                $customerBlncReturn = $customerOP_blnc;
            } else {
                $customerBlncReturn = 0;
            }

            return $customerBlncReturn;
    }
    /*=======================================================*/
}

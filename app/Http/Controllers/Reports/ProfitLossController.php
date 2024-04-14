<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\Models\Purchase,App\Models\PurchasePayment;
use App\Models\Expense;
use App\Models\Sale,App\Models\SalePaymentDetail;
use App\Models\AdvanceCustomerPayment; 
use App\Models\CustomerOpeningBalance;
use App\Models\ReceivablePayable;
use App\Models\SupplierPayment;
use App\Models\SupplierOpeningBalance;
use App\Models\MonthlySalary;
use App\Models\AdvanceHistory;
use App\Models\EmployeeReturnAdvance;

class ProfitLossController extends Controller
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
    public function profitLoss(Request $request)
    {
        $this->authenticateRole($module_page='reports');
        Session::put('page','profitLoss');
        /*SUM Employees Salarie*/
        $data['employees_salarie'] = MonthlySalary::sum('amount'); //DB Amount
        /*SUM Employees Advance*/
        $data['employees_advance']  = AdvanceHistory::sum('current_paidAmount'); //DB Amount
        /*SUM Employees Return Advance*/
        $data['employees_return_advance'] = EmployeeReturnAdvance::sum('return_amount'); //CR Amount


        /*Fetch Total Amount of Purchase*/
        $data['getTotalPurchaseAmount'] = Purchase::where('status','received')->sum('amount');
        
        /*SUM Suppliers CR/DB Amounts*/
        $data['suppliersPayment'] = SupplierPayment::sum('amount'); //DB
        $data['suppliers_credit_amount'] = SupplierOpeningBalance::where('type','credit')->sum('amount'); //CR
        $data['suppliers_debit_amount'] = SupplierOpeningBalance::where('type','debit')->sum('amount'); //DB
        
        /*SUM Customers CR/DB Amount*/
        $data['customersPayment'] = AdvanceCustomerPayment::sum('amount'); //CR
        $data['customers_credit_amount'] = CustomerOpeningBalance::where('type','credit')->sum('amount'); //CR
        $data['customers_debit_amount'] = CustomerOpeningBalance::where('type','debit')->sum('amount'); //DB


        /*Fetch Expenses*/
        $data['expenses'] = Expense::sum('amount');

        /*Fetch Total Amount of Sales*/
        $data['getTotalSalesAmount'] = Sale::where('status',1)->sum('amount');
        /*Fetch Total Paid Amount of Sales*/
        $data['salesDiscount'] = Sale::where('status',1)->sum('discount');
        
        $data['customerPaymentDiscount'] = AdvanceCustomerPayment::sum('payment_discount');
        

        

        $getItemsTotalAmount = 0;
        $returnItemsTotalAmount = 0;

        $getCalculatedCostAmount = 0;
        $returnCalculatedCostAmount = 0;

        $grossProfit = 0;
        $totalGrossProfit = 0;
        $returnGrossProfit = 0;


        /*Fetch Calculations of Sales*/
        $getSales = Sale::where('status',1)->get()->toArray();
        // echo "<pre>"; print_r($getSales); exit();

        
        foreach ($getSales as $key => $value) {
        //     $customerSale = $value['pos_sale'];
    
        // foreach ($customerSale as $key => $value) {
            
            /*Fetch Calculated Cost & Items Total for Gross Profit*/
            $product_addons = unserialize($value['items_addon']);
            
            foreach ($product_addons as $key => $addons) {
                $calculatedCost = $addons['calculatedCost'];
                $itemsTotal = $addons['amount'];
                $quantity = $addons['quantity'];
                $getCalculatedCostAmount = $calculatedCost + $getCalculatedCostAmount;
                $getItemsTotalAmount = $itemsTotal + $getItemsTotalAmount;

                $grossProfit = $getItemsTotalAmount - $getCalculatedCostAmount;
                $totalGrossProfit = $grossProfit;

            }
        }
        // }
        
        /*Calculate Gross Profit*/
        $returnGrossProfit = $totalGrossProfit - ($data['salesDiscount'] + $data['customerPaymentDiscount']);
      

        /*Calculate Net Profit*/
        $dataCashOut = $data['suppliers_debit_amount'] + $data['suppliersPayment'] + $data['customers_debit_amount'] + $data['expenses'];
        
        $netProfit = ((float)$data['suppliers_credit_amount'] + $data['customersPayment'] + $data['customers_credit_amount']) - (float)$dataCashOut;
        // dd($netProfit);
        /*Fetch Items wise Profit*/
        $data['products'] = Sale::where('status',1)->get()->toArray();
        // echo "<pre>"; print_r($data['products'][0]['items_addon']); exit();
        

        return view('reports.profit_loss.index',$data,compact('returnGrossProfit','netProfit'));
    }
    /*==============================================================================*/
}

<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\PurchasePayment;
use App\Models\PurchasePaymentDetail;
use App\Models\Purchase;
use Session;
use Auth;
use DB;
use App\Models\Sale;
use App\Models\Product;
use App\Models\SalePaymentDetail;
use App\Models\AdvanceCustomerPayment;
use App\Models\CustomerOpeningBalance;
use App\Models\SupplierPayment;
use App\Models\AdvancePayable;
use App\Models\SupplierOpeningBalance;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    
    
                    /*=====*/

            
    /*==============================================================*/
    
    
    
    
    
          
    
    /*===============================================================*/
    /* Check Customer Total Amount Function Start*/
    public  function totalAmount($customer_id) {
        $returnTotalAmount = 0;

        $totalAmount = Sale::where('customer_id',$customer_id)->where('status',1)->get();

        $totalAmountOutPut = 0;
        if ($totalAmount === NULL) {
            $returnTotalAmount = 0;
        } else {
            $total_amount = $totalAmount;
            foreach ($total_amount as $value) {
                $totalAmountOutPut = $value->amount + $totalAmountOutPut;
            }

            $returnTotalAmount = $totalAmountOutPut;
        }

        return $returnTotalAmount;
    }
    /* Check Customer Total Amount Function Ends*/

    /* Check Customer Pre Receivable Amount Function Start*/
    public  function customerDebitAmount($customer_id) {
        $returnReceivable = 0;

        $totalReceivable = CustomerOpeningBalance::where('customer_id',$customer_id)->where('type','debit')->get();

        $receivableOutPut = 0;
        if ($totalReceivable === NULL) {
            $returnReceivable = 0;
        } else {
            $total_receivable = $totalReceivable;
            foreach ($total_receivable as $value) {
                $receivableOutPut = $value->amount + $receivableOutPut;
            }

            $returnReceivable = $receivableOutPut;
        }

        return $returnReceivable;
    }
    /* Check Customer Pre Receivable Amount Function Ends*/

    /* Check Customer Advance Amount Function Start*/
    public  function advanceAmount($customer_id) {
        $returnAdvance = 0;

        $totalAdvance = AdvanceCustomerPayment::where('customer_id',$customer_id)->get();

        $advanceOutPut = 0;
        if ($totalAdvance === NULL) {
            $returnAdvance = 0;
        } else {
            $total_advance = $totalAdvance;
            foreach ($total_advance as $value) {
                $advanceOutPut = $value->amount + $advanceOutPut;
            }

            $returnAdvance = $advanceOutPut;
        }
        // dd($returnAdvance);
        return $returnAdvance;
    }
    /* Check Customer Advance Amount Function Ends*/

    /* Check Customer Payment Discount Amount Function Start*/
    public  function paymentDicsount($customer_id) {
        $returnDiscount = 0;

        $paymentDiscount = AdvanceCustomerPayment::where('customer_id',$customer_id)->get();

        $discountOutPut = 0;
        if ($paymentDiscount === NULL) {
            $returnDiscount = 0;
        } else {
            $total_discount = $paymentDiscount;
            foreach ($total_discount as $value) {
                $discountOutPut = $value->payment_discount + $discountOutPut;
            }

            $returnDiscount = $discountOutPut;
        }
        // dd($returnDiscount);
        return $returnDiscount;
    }
    /* Check Customer Payment Discount Amount Function Ends*/

    /* Check Customer Pre Payable Amount Function Start*/
    public  function customerCreditAmount($customer_id) {
        // dd($customer_id);
        $returnPayable = 0;

        $totalPayable = CustomerOpeningBalance::where('customer_id',$customer_id)->where('type','credit')->get();
        // echo "<pre>"; print_r($totalPayable->toArray()); exit();

        $payableOutPut = 0;
        if ($totalPayable === NULL) {
            $returnPayable = 0;
        } else {
            $total_payable = $totalPayable;
            foreach ($total_payable as $value) {
                $payableOutPut = $value->amount + $payableOutPut;
            }

            $returnPayable = $payableOutPut;
        }
                // dd($returnPayable);
        return $returnPayable;
    }
    /* Check Customer Pre Payable Amount Function Ends*/

    /*===============================================================*/
    /* Check Supplier Total Amount Function Start*/
    public  function supplierTotalAmount($supplier_id) {
        $returnTotalAmount = 0;

        $purchase_total_amount = Purchase::where('supplier_id',$supplier_id)->where('status','received')->get();

        $totalAmountOutPut = 0;
        if ($purchase_total_amount === NULL) {
            $returnTotalAmount = 0;
        } else {
            $total_amount = $purchase_total_amount;
            foreach ($total_amount as $value) {
                $totalAmountOutPut = $value->amount + $totalAmountOutPut;
            }

            $returnTotalAmount = $totalAmountOutPut;
        }

        return $returnTotalAmount;
    }
    /* Check Supplier Total Amount Function Ends*/

    /* Check Supplier Pre Payable Amount Function Start*/
    public  function supplierCreditAmount($supplier_id) {
        // dd($supplier_id);
        $returnPayable = 0;

        $totalPayable = SupplierOpeningBalance::where('supplier_id',$supplier_id)->where('type','credit')->get();
        // echo "<pre>"; print_r($totalPayable->toArray()); exit();

        $payableOutPut = 0;
        if ($totalPayable === NULL) {
            $returnPayable = 0;
        } else {
            $total_payable = $totalPayable;
            foreach ($total_payable as $value) {
                $payableOutPut = $value->amount + $payableOutPut;
            }

            $returnPayable = $payableOutPut;
        }
                // dd($returnPayable);
        return $returnPayable;
    }
    /* Check Customer Pre Payable Amount Function Ends*/

    /* Check Customer Pre Receivable Amount Function Start*/
    public  function paymentToSupplier($supplier_id) {
        $return_supplier_payment = 0;

        $payment_to_supplier = SupplierPayment::where('supplier_id',$supplier_id)->get();

        $supplierPaymentOutPut = 0;
        if ($payment_to_supplier === NULL) {
            $return_supplier_payment = 0;
        } else {
            $totalPaymentToSupplier = $payment_to_supplier; 
            foreach ($totalPaymentToSupplier as $value) {
                $supplierPaymentOutPut = $value->amount + $supplierPaymentOutPut;
            }

            $return_supplier_payment = $supplierPaymentOutPut;
        }

        return $return_supplier_payment;
    }
    /* Check Customer Pre Receivable Amount Function Ends*/

    /* Check Customer Advance Amount Function Start*/
    public  function supplierDebitAmount($supplier_id) {
        $returnSupplierAdvance = 0;

        $supplierAdvanceAmount = SupplierOpeningBalance::where('supplier_id',$supplier_id)->where('type','debit')->get();

        $supplierAdvanceOutPut = 0;
        if ($supplierAdvanceAmount === NULL) {
            $returnSupplierAdvance = 0;
        } else {
            $total_advance = $supplierAdvanceAmount;
            foreach ($total_advance as $value) {
                $supplierAdvanceOutPut = $value->amount + $supplierAdvanceOutPut;
            }

            $returnSupplierAdvance = $supplierAdvanceOutPut;
        }
        // dd($returnSupplierAdvance);
        return $returnSupplierAdvance;
    }
    /* Check Customer Advance Amount Function Ends*/

    /* Check Customer Payment Discount Amount Function Start*/
    public  function supplierPaymentDicsount($supplier_id) {
        $returnDiscount = 0;

        $paymentDiscount = SupplierPayment::where('supplier_id',$supplier_id)->get();

        $discountOutPut = 0;
        if ($paymentDiscount === NULL) {
            $returnDiscount = 0;
        } else {
            $total_discount = $paymentDiscount;
            foreach ($total_discount as $value) {
                $discountOutPut = $value->payment_discount + $discountOutPut;
            }

            $returnDiscount = $discountOutPut;
        }
        // dd($returnDiscount);
        return $returnDiscount;
    }
    /* Check Customer Payment Discount Amount Function Ends*/

    
    /*==============================================================*/
}

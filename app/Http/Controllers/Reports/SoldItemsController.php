<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\Product;
use App\User;
use PDF;
use Exception;

class SoldItemsController extends Controller
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
        Session::put('page','soldItems');
        $getProducts = Product::get();
        return view('reports.sold_items.index',compact('getProducts'));
    }
    /*===================================================================*/
    public function getSoldProducts(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            $startDate = date('Y-m-d',strtotime($data['startDate']));
            $endDate = date('Y-m-d',strtotime($data['endDate']));
            // echo "<pre>"; print_r($data); exit();
            if ($data['product_id']=='all') {
                $products = Sale::with('customers')->where('status',1)->whereBetween('date',[$startDate,$endDate])->get()->toArray();
                
                // echo "<pre>"; print_r($products); exit();
                $html['thsource'] = '<th>#</th>';
                $html['thsource'] .= '<th>Invoice#</th>';
                $html['thsource'] .= '<th>Date</th>';
                $html['thsource'] .= '<th>Customer</th>';
                $html['thsource'] .= '<th>Product Name</th>';
                $html['thsource'] .= '<th>Selling Price</th>';
                $html['thsource'] .= '<th>Item Sales Count</th>';
                $html['thsource'] .= '<th>Sales Amount</th>';
                
                $html['tdsource'] = null;
                $totalAmount = 0;
                $returnTotalAmount = 0;
                $counter = 0;
                foreach ($products as $productKey => $mainProduct) {
                    $mainProductID = $mainProduct['id'];
                    $invoice_no = $mainProduct['invoice_no'];
                    $customerName = $mainProduct['customers']['name'];
                    $date = date('d M Y',strtotime($mainProduct['date']));
                    $variations = unserialize($mainProduct['items_addon']);
                    
                    foreach ($variations as $key => $variation){ 
                        $product_id = $variation['product_id'];
                        $productName = $variation['productName'];
                        $unit = $variation['unit']; 
                        $selling_price = $variation['selling_price'];
                        $quantity = $variation['quantity'];
                        $amount = $variation['amount'];
                        
                        $totalAmount = $variation['amount'] + $totalAmount;

                    $soldValue = $quantity * $selling_price;
                    $counter = $counter+1;

                    $html['tdsource'] .= '<tr><td>'.$counter.'</td>';
                    $html['tdsource'] .= '<td><a target="_blank" href="' .url("sale-invoice").'/'.$mainProduct['id'].'">'.$invoice_no.'</a></td>';
                    $html['tdsource'] .= '<td>'.$date.'</td>';
                    $html['tdsource'] .= '<td>'.$customerName.'</td>';
                    $html['tdsource'] .= '<td>'.$productName.'</td>';
                    $html['tdsource'] .= '<td style="text-align: right;">'.$selling_price.'</td>';
                    $html['tdsource'] .= '<td style="text-align: center;">'.$quantity.'</td>';
                    $html['tdsource'] .= '<td style="text-align: right;">'.$soldValue.'</td></tr>';
                    }
                    $returnTotalAmount = $totalAmount;
                    
                    
                }
                $html['tfootsource'] = '<tr><td colspan="7" style="background: gray; font-weight: bold; color:white;">Total</td><td style="text-align: right; background: gray; font-weight: bold; color:white;">'.$returnTotalAmount.'</td></tr>';

                return response(@$html);
                
            }else{
                $products = Sale::with('customers')->where('status',1)->whereBetween('date',[$startDate,$endDate])->get()->toArray();
                // echo "<pre>"; print_r($products); exit();
                
                $html['thsource'] = '<th>#</th>';
                $html['thsource'] .= '<th>Invoice#</th>';
                $html['thsource'] .= '<th>Date</th>';
                $html['thsource'] .= '<th>Customer</th>';
                $html['thsource'] .= '<th>Product Name</th>';
                $html['thsource'] .= '<th>Selling Price</th>';
                $html['thsource'] .= '<th>Item Sales Count</th>';
                $html['thsource'] .= '<th>Sales Amount</th>';
                
                $html['tdsource'] = null;
                $itemSoldCount = 0;
                $returnItemSoldCount = 0;
                $totalAmount = 0;
                $returnTotalAmount = 0;
                $counter = 0;
                foreach ($products as $mainProduct) {
                    // echo "<pre>"; print_r($mainProduct); exit();
                    $mainProductID = $mainProduct['id'];
                    $invoice_no = $mainProduct['invoice_no'];
                    $customerName = $mainProduct['customers']['name'];
                    $date = date('d M Y',strtotime($mainProduct['date']));
                    $variations = unserialize($mainProduct['items_addon']);

                    
                    foreach ($variations as $keys => $variation){
                    if ($variation['product_id']==$data['product_id']) { 
                        
                        $productName = $variation['productName'];
                        $unit = $variation['unit']; 
                        $selling_price = $variation['selling_price'];
                        $quantity = $variation['quantity'];
                        $amount = $variation['amount'];
                        
                        $itemSoldCount = $variation['quantity'] + $itemSoldCount;
                        $totalAmount = $variation['amount'] + $totalAmount;

                    $soldValue = $quantity * $selling_price;
                    $counter = $counter+1;

                    $html['tdsource'] .= '<tr><td>'.$counter.'</td>';
                    $html['tdsource'] .= '<td><a target="_blank" href="' .url("sale-invoice").'/'.$mainProduct['id'].'">'.$invoice_no.'</a></td>';
                    $html['tdsource'] .= '<td>'.$date.'</td>';
                    $html['tdsource'] .= '<td>'.$customerName.'</td>';
                    $html['tdsource'] .= '<td>'.$productName.'</td>';
                    $html['tdsource'] .= '<td style="text-align: right;">'.$selling_price.'</td>';
                    $html['tdsource'] .= '<td style="text-align: center;">'.$quantity.'</td>';
                    $html['tdsource'] .= '<td style="text-align: right;">'.$soldValue.'</td></tr>';
                    }
                }
                    $returnItemSoldCount = $itemSoldCount;
                    $returnTotalAmount = $totalAmount;
                    
                }
                $html['tfootsource'] = '<tr><td colspan="6" style="background: gray; font-weight: bold; color:white;">Total</td><td style="text-align: center; background: gray; font-weight: bold; color:white;">'.$returnItemSoldCount.'</td><td style="text-align: right; background: gray; font-weight: bold; color:white;">'.$returnTotalAmount.'</td></tr>';
                return response(@$html);
            }
        }
    }
    /*===================================================================*/
    public function downloadSoldItemsPdf(Request $request)
    {
        $data = $request->all();
        $product_id = $data['product_id'];
        // dd($product_id);
        $startDate = date('Y-m-d',strtotime($data['startDate']));
        $endDate = date('Y-m-d',strtotime($data['endDate']));
        if ($data['product_id']=='all') { // Fetch Sales Report All Supplier
            $products = Sale::with('customers')->where('status',1)->whereBetween('date',[$startDate,$endDate])->get()->toArray();
        }else{
            $products = Sale::with('customers')->where('status',1)->whereBetween('date',[$startDate,$endDate])->get()->toArray();
        }

        $pdf = PDF::loadView('reports.pdf.sales.sold-items-report',compact('products','startDate','endDate','product_id'));
        return $pdf->stream('sold-items-report.pdf');
    }
    /*===================================================================*/
}

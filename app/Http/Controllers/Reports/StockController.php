<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\Models\Product;
use PDF;
use Exception;

class StockController extends Controller
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
        Session::put('page','stock');
        $getProducts = Product::get();
        return view('reports.stock.index',compact('getProducts'));
    }
    /*=========================================================================*/
    public function getStock(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            if ($data['product_id']=='all') {
                $products = Product::with('units')->get()->toArray();

                $html['thsource'] = '<th>#</th>';
                $html['thsource'] .= '<th>Product Name</th>';
                $html['thsource'] .= '<th>Cost</th>';
                $html['thsource'] .= '<th>Sale Price</th>';
                $html['thsource'] .= '<th>Current Stock</th>';
                $html['thsource'] .= '<th>Stock Value</th>';
                
                $html['tdsource'] = null;
                $totalAmount = 0;
                $returnTotalAmount = 0;
                $counter = 0;
                foreach ($products as $mainProduct) {
                    $counter = $counter+1;
                   $mainProductID = $mainProduct['id'];
                    $mainProductName = $mainProduct['name'];
                    $quantity = $mainProduct['quantity'];
                    $cost = $mainProduct['cost'];
                    $unit = $mainProduct['units']['name'];
                    $selling_price = $mainProduct['selling_price'];
                    
                    $stockValue = (float)$quantity * (float)$cost;
                    $totalAmount = $stockValue + $totalAmount;
                    
                    $html['tdsource'] .= '<tr><td>'.$counter.'</td>';
                    $html['tdsource'] .= '<td>'.$mainProductName.'</td>';
                    $html['tdsource'] .= '<td style="text-align: center;">'.number_format($cost,2).'</td>';
                    $html['tdsource'] .= '<td style="text-align: center;">'.number_format($selling_price,2).'</td>';
                    $html['tdsource'] .= '<td style="text-align: center;">'.$quantity .'-'. $unit.'</td>';
                    $html['tdsource'] .= '<td style="text-align: right;">'.number_format($stockValue,2).'</td></tr>';
                  
                    
                    
                }
                $returnTotalAmount = $totalAmount;
                $html['tfootsource'] = '<tr style="background: gray; font-weight: bold; color:white;"><td colspan="5">Total</td><td style="text-align: right; font-weight: bold; color:white;">'.number_format($returnTotalAmount,2).'</td></tr>';
                return response(@$html);
                
            }else{
                $products = Product::with('units')->where('id',$data['product_id'])->get()->toArray();

                $html['thsource'] = '<th>#</th>';
                $html['thsource'] .= '<th>Product Name</th>';
                $html['thsource'] .= '<th>Cost</th>';
                $html['thsource'] .= '<th>Sale Price</th>';
                $html['thsource'] .= '<th>Current Stock</th>';
                $html['thsource'] .= '<th>Stock Value</th>';
                
                $html['tdsource'] = null;
                $totalAmount = 0;
                $returnTotalAmount = 0;
                $counter = 0;
                foreach ($products as $mainProduct) {
                    $counter = $counter+1;
                    $mainProductID = $mainProduct['id'];
                    $mainProductName = $mainProduct['name'];
                    $quantity = $mainProduct['quantity'];
                    $cost = $mainProduct['cost'];
                    $unit = $mainProduct['units']['name'];
                    $selling_price = $mainProduct['selling_price'];
                    
                    $stockValue = (float)$quantity * (float)$selling_price;
                    $totalAmount = $stockValue + $totalAmount;
                    
                    $html['tdsource'] .= '<tr><td>'.$counter.'</td>';
                    $html['tdsource'] .= '<td>'.$mainProductName.'</td>';
                    $html['tdsource'] .= '<td style="text-align: center;">'.number_format($cost,2).'</td>';
                    $html['tdsource'] .= '<td style="text-align: center;">'.number_format($selling_price,2).'</td>';
                    $html['tdsource'] .= '<td style="text-align: center;">'.$quantity .'-'. $unit.'</td>';
                    $html['tdsource'] .= '<td style="text-align: right;">'.number_format($stockValue,2).'</td></tr>';
                     
                }
                $returnTotalAmount = $totalAmount;
                $html['tfootsource'] = '<tr style="background: gray; font-weight: bold; color:white;"><td colspan="5">Total</td><td style="text-align: right; font-weight: bold; color:white;">'.number_format($returnTotalAmount,2).'</td></tr>';
                return response(@$html);
            }
        }
    }
    /*=========================================================================*/
    public function downloadStockPdf(Request $request)
    {
        $data = $request->all();
        if ($data['product_id']=='all') {
            $products = Product::with('units')->get()->toArray();
        }else{
            $products = Product::with('units')->where('id',$data['product_id'])->get()->toArray();
        }
        $pdf = PDF::loadView('reports.pdf.stock.stock-report',compact('products'));
        return $pdf->stream('stock-report.pdf');
    }
    /*=========================================================================*/
}

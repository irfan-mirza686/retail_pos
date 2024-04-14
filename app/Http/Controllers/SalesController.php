<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Auth;
use DB;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Area;
use App\Models\SalePayment;
use App\Models\SalePaymentDetail;
use App\Services\SaleService;
use Exception;
use App\ZatcaWrapper\ZatcaWrapper;

class SalesController extends Controller
{
    protected $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }


    /*====================================*/
    public function authenticateRole($module_page)
    {
        $permissionCheck = checkRolePermission($module_page);
        if ($permissionCheck->access == 0) {

            return redirect()->to('/dashboard')->send()->with('error', 'You have no permission!');
        }
    }
    /*===================================================*/
    public function index()
    {
        $this->authenticateRole($module_page = 'sales');
        try {
            Session::put('page', 'viewInvoice');
            $sales = Sale::with(['customers'])->orderBy('id', 'DESC')->get();
            // echo "<pre>"; print_r($sales->toArray()); exit();
            return view('sales.view', compact('sales'));
        } catch (Exception $e) {
            Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
            return redirect('dashboard')->with($e->getMessage());
        }
    }
    /*=======================================================================*/
    public function checkProductStock(Request $request)
    {
        $data = $request->all();

        $product = Product::where('id', $data['productID'])->first()->toArray();
        if ($product['quantity'] < $data['quantity']) {
            return response()->json(
                [
                    'error' => true,
                    'message' => $product['name'] . ' Product is ' . $product['quantity'] . ' remaining!'
                ]
            );
        }
    }
    /*=======================================================================*/
    public function addSale(Request $request)
    {
        $this->authenticateRole($module_page = 'sales');
        if ($request->isMethod('post')) {
            $data = $request->all();

            try {
                /*Check Stock Start*/
                foreach ($data['productName'] as $i => $newData) {
                    $product_id = $data['product_id'][$i];
                    $productName = $data['productName'][$i];
                    $quantity = $data['quantity'][$i]; // new Quantity...
                    $selectSingleProduct = Product::where('id', $product_id)->first();
                    $stockQty = $selectSingleProduct['quantity'];
                    if ($selectSingleProduct['id'] == $product_id) {
                        if ($stockQty < $quantity) {
                            return redirect()->back()->with('flash_message_error', $productName . ' Product is ' . $stockQty . ' remaining!');
                        }

                    }
                }
                /*Check Stock Ends*/

                $this->saleService->saveSale($data, $request);
                $this->saleService->updateSaleStock($data, $request);

                return redirect('sales')->with('success', 'Invoice Successfully Created!');
            } catch (Exception $e) {
                Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
                return redirect()->back()->with($e->getMessage());
            }
        } else {
            Session::put('page', 'createInvoice');
            $areas = Area::get();

            $invoice_data = Sale::orderBy('id', 'DESC')->first();

            if ($invoice_data == null) {

                $firstReg = '0';
                $invoice_no = $firstReg + 1;
            } else {
                $invoice_data = Sale::orderBy('id', 'DESC')->first()->invoice_no;

                $invoice_no = $invoice_data + 1;
                // echo "<pre>"; print_r($invoice_no); exit();
            }

            return view('sales.create', compact('invoice_no', 'areas'));
        }
    }
    /*===========================================================*/
    public function editSale(Request $request, $id = null)
    {
        $this->authenticateRole($module_page = 'sales');
        $updateSale = Sale::with('customers')->find($id);

        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); exit();
            $getSale = Sale::where('id', $id)->first();
            try {
                if ($data['status'] == 0) { // 0 == Cancel Sale

                    if ($getSale['status'] == 0) {
                        $this->saleService->updateSale($data, $request, $id);
                    } else {

                        $this->saleService->cancelSale($data, $request, $id);

                        $this->saleService->updateSale($data, $request, $id);

                        Sale::where('id', $id)->update(['status' => 0]);
                    }

                } else if ($data['status'] == 1) { // 1 == Confirmed Sale
                    if ($getSale['status'] == 1) {
                        $this->saleService->cancelSale($data, $request, $id);
                        $this->saleService->updateSale($data, $request, $id);
                        $this->saleService->saleStockPlusWithID($data, $request, $id);
                    } else {
                        $this->saleService->updateSale($data, $request, $id);
                        $this->saleService->saleStockPlusWithID($data, $request, $id);
                        Sale::where('id', $id)->update(['status' => 1]);
                    }

                }
                return redirect('sales')->with('success', 'Invoice Successfully Updated!');
            } catch (Exception $e) {
                Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
                return redirect()->back()->with($e->getMessage());
            }
        } else {
            $getSaleStatus = Sale::select('status')->where('id', $id)->first();
            Session::put('page', 'viewInvoice');
            $salesItmes = unserialize($updateSale['items_addon']);
            return view('sales.edit', compact('updateSale', 'salesItmes'));
        }
    }

    /*===========================================================*/
    public function searchSaleProducts(Request $request)
    {
        if ($request->ajax()) {
            $nakasiProduct = [];
            $products = Product::with('units')->where('name', 'LIKE', "%" . $request->term . "%")->orWhere('product_code', 'LIKE', "%" . $request->term . "%")->paginate(5);
            if ($products) {
                foreach ($products as $key => $value) {
                    $nakasiProduct[] = array(
                        "value" => $value->name,
                        "productID" => $value->id,
                        "productName" => $value->name,
                        "productQty" => $value->quantity,
                        "sellingPrice" => $value->selling_price,
                        "cost" => $value->cost,
                        "productUnit" => $value['units']->name,
                    );
                }
                $dataRetrun = json_encode($nakasiProduct);
                return Response($dataRetrun);
            }
        }
    }
    /*=============================================================*/
    public function saleInvoice($id = null)
    {
        $this->authenticateRole($module_page = 'sales');
        try {
            Session::put('page', 'viewInvoice');

            $saleInvoice = Sale::with(['customers'])->where('id', $id)->first()->toArray();
            $saleitmesAddons = unserialize($saleInvoice['items_addon']);
            $customerBalance = ($this->totalAmount($saleInvoice['customer_id']) + $this->customerDebitAmount($saleInvoice['customer_id'])) - ($this->advanceAmount($saleInvoice['customer_id']) + $this->customerCreditAmount($saleInvoice['customer_id']));
            // echo "<pre>"; print_r($customerBalance); exit();
            // $paymentHistory = SalePaymentDetail::with('users')->where('sale_id',$id)->get()->toArray();
            return view('invoices.sale-invoice', compact('saleInvoice', 'saleitmesAddons', 'customerBalance'));
        } catch (Exception $e) {
            Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
            return redirect()->back()->with($e->getMessage());
        }

    }
    /*=============================================================*/
    public function printSaleInvoice($id = null)
    {
        try {
            $print_footer = "print_footer";
            $saleInvoice = Sale::with(['customers'])->where('id', $id)->first()->toArray();

            $saleitmesAddons = unserialize($saleInvoice['items_addon']);
            $customerBalance = ($this->totalAmount($saleInvoice['customer_id']) + $this->customerDebitAmount($saleInvoice['customer_id'])) - ($this->advanceAmount($saleInvoice['customer_id']) + $this->customerCreditAmount($saleInvoice['customer_id']));
            // echo "<pre>"; print_r($customerBalance); exit();
            $base64 = (new ZatcaWrapper())
            ->sellerName('Irfan')
            ->vatRegistrationNumber("300456416500003")
            ->timestamp("2021-12-01T14:00:09Z")
            ->totalWithVat('100.00')
            ->vatTotal('15.00')
            ->csrCommonName('irfan')
            ->csrSerialNumber('123456789')
            ->csrOrganizationIdentifier('0686')
            ->csrOrganizationUnitName('IT')
            ->csrOrganizationName('OutSeller')
            ->csrCountryName('Pakistan')
            ->csrInvoiceType('Sell')
            ->csrLocationAddress('Pakistan')
            ->csrIndustryBusinessCategory('IT Industry')
            ->toBase64();
            return view('print_invoice.print_sale_invoice', compact('print_footer', 'saleInvoice', 'saleitmesAddons', 'customerBalance','base64'));
        } catch (Exception $e) {
            Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
            return redirect()->back()->with($e->getMessage());
        }
    }
    /*=============================================================*/
    public function customerPayment(Request $request, $id = null)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            try {

                if ($data['new_paid_amount'] < $data['paid_amount']) {
                    return redirect()->back()->with('error', 'Sorry! you have paid maxium value');
                } else {

                    if ($data['paid_status'] == 'full_paid') {
                        if ($data['new_paid_amount'] <= 0) {
                            return redirect()->back()->with('error', 'Sorry! you have paid maxium value');
                        }
                        $customerPayment = Sale::where('id', $id)->update([
                            'paid_amount' => Sale::where('id', $id)->first()['paid_amount'] + $data['new_paid_amount'],
                            'due_amount' => 0
                        ]);
                        $customerPaymentDetails = new SalePaymentDetail;
                        $customerPaymentDetails->current_paid_amount = $data['new_paid_amount'];

                    } else if ($data['paid_status'] == 'partial_paid') {

                        Sale::where('id', $id)->update([
                            'paid_amount' => Sale::where('id', $id)->first()['paid_amount'] + $data['paid_amount'],
                            'due_amount' => Sale::where('id', $id)->first()['due_amount'] - $data['paid_amount']
                        ]);
                        $customerPaymentDetails = new SalePaymentDetail;
                        $customerPaymentDetails->current_paid_amount = $data['paid_amount'];

                    }
                    $customerPaymentDetails->sale_id = $id;
                    $customerPaymentDetails->invoice_no = $data['invoice_no'];
                    $customerPaymentDetails->date = date('Y-m-d', strtotime($request->date));
                    $customerPaymentDetails->updated_by = Auth::user()->id;
                    $customerPaymentDetails->save();

                    return redirect()->back()->with('success', 'Payment successfully submit!');
                }
            } catch (Exception $e) {
                Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
                return redirect()->back()->with($e->getMessage());
            }
        } else {
            $customer_payment = Sale::with('customers')->where('id', $id)->first()->toArray();

            $salesAddons = unserialize($customer_payment['items_addon']);

            return view('sales.payments.add_payment', compact('customer_payment', 'salesAddons'));

        }
    }
    /*=============================================================*/
    public function deleteSale($id = null)
    {
        $this->authenticateRole($module_page = 'sales');
        Sale::where('id', $id)->delete();
        return redirect()->back()->with('success', 'sale successfully delete');

    }
    /*=============================================================*/
}

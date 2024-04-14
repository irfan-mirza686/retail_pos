<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Auth;
use Exception;
use App\Models\Customer;
use App\Models\Area;
use App\Models\Sale;
use App\Models\AdvanceCustomerPayment;
use App\Models\CustomerOpeningBalance;
use Illuminate\Foundation\Str;


class CustomerController extends Controller
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
        $this->authenticateRole($module_page='customers');
    	Session::put('page','customers');
    	$customers = Customer::with('user')->orderBy('id','DESC')->get()->toArray();
    	return view('customers.view',compact('customers'));
    }
    /*==================================================*/
    public function createCustomer(Request $request)
    {
        $this->authenticateRole($module_page='customers');
    	if ($request->isMethod('post')) {
    		$data = $request->all();
    		// echo "<pre>"; print_r($data); exit();
    		try {
    			$customer = new Customer;
    			$customer->user_id = Auth::user()->id;
                $customer->area_id = $data['area_id'];
    			$customer->name = $data['name'];
    			$customer->mobile = $data['mobile'];
    			$customer->cnic = $data['cnic'];
    			$customer->address = $data['address'];
    			$customer->save();
    			return redirect('/customers')->with('success','Customer Successfully Created!');
    		} catch (Exception $e) {
    			Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
				return redirect()->back()->with($e->getMessage());
    		}
    	}else{
    		Session::put('page','createCustomers');
            $areas = Area::get();
    		return view('customers.create',compact('areas'));
    	}
    }
    /*==================================================*/
    public function editCustomer(Request $request, $id=null)
    {
        $this->authenticateRole($module_page='customers');
    	if ($request->isMethod('post')) {
    		$data = $request->all();
    		try {
    			$updateCustomer = Customer::find($id);
    			$updateCustomer->user_id = Auth::user()->id;
                $updateCustomer->area_id = $data['area_id'];
    			$updateCustomer->name = $data['name'];
    			$updateCustomer->mobile = $data['mobile'];
    			$updateCustomer->cnic = $data['cnic'];
    			$updateCustomer->address = $data['address'];
    			$updateCustomer->save();
    			return redirect('/customers')->with('success','Customer Successfully Created!');
    		} catch (Exception $e) {
    			Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
				return redirect()->back()->with($e->getMessage());
    		}
    	}else{
    		$editCustomer = Customer::find($id);
            $areas = Area::get();
    		return view('customers.edit',compact('editCustomer','areas'));
    	}
    }
    /*==================================================*/
    public function ajaxAddCustomer(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); exit();
            if ($data['customerName']=='') {
                return "false";
            }
            if ($data['mobile']=='') {
                return "false";
            }
            if ($data['cnic']=='') {
                return "false";
            }
            if ($data['address']=='') {
                return "false";
            }
            // echo "<pre>"; print_r($data); exit();
            $customer = new Customer;
            $customer->user_id = Auth::user()->id;
            $customer->area_id = $data['area_id'];
            $customer->name = $data['customerName'];
            $customer->mobile = $data['mobile'];
            $customer->cnic = $data['cnic'];
            $customer->address = $data['address'];
            $customer->save();

            return response()->json(
             [
                'success' => true,
                'message' => 'Customer Successfully Added',
                'customerName' => $data['customerName'],
                "customerID" => $customer->id,
                "areaID" => $customer->area_id,
            ]
        );
        }
    }
    /*==================================================*/
    public function searchCustomer(Request $request)
    {
        if ($request->ajax()) {


            $cuttomerAuto = [];
            $customers = Customer::where('name', 'LIKE', "%" . $request->term . "%")->orWhere('mobile', 'LIKE', "%" . $request->term . "%")->paginate(5);

            if ($customers) {
                foreach ($customers as $key => $value_customers) {
                    $customerBalance = ($this->totalAmount($value_customers->id) + $this->customerDebitAmount($value_customers->id)) - ($this->paymentDicsount($value_customers->id) + $this->advanceAmount($value_customers->id) + $this->customerCreditAmount($value_customers->id));
                    $cuttomerAuto[] = array(
                        "value" => $value_customers->name,
                        "customerID" => $value_customers->id,
                        "customerName" => $value_customers->name,
                        "areaID" => $value_customers->area_id,
                        "customerBalance" => $customerBalance,
                    );
                }
                $dataRetrun = json_encode($cuttomerAuto);
                return Response($dataRetrun);
            }
        }
    }
    /*==================================================*/
    public function addCustomerPayment(Request $request)
    {
        $this->authenticateRole($module_page='customers_payment');
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); exit();
            try {
                $customerPayment = new AdvanceCustomerPayment;
                $customerPayment->invoice_no = $data['voucher_no'];
                $customerPayment->date = date('Y-m-d',strtotime($data['date']));
                $customerPayment->customer_id = $data['customer_id'];
                // $customerPayment->area_id = $data['area_id'];
                $customerPayment->description = $data['description'];
                $customerPayment->amount = $data['amount'];
                $customerPayment->payment_discount = $data['payment_discount'];
                $customerPayment->user_id = Auth::user()->id;
                $customerPayment->save();
                return redirect()->back()->with('success','Customer Payment Successfully Added!');
            } catch (Exception $e) {
                Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
                return redirect()->back()->with($e->getMessage());
            }
        }else{
            Session::put('page','customerPayment');
            $voucher_data = AdvanceCustomerPayment::orderBy('id','DESC')->first();

            if ($voucher_data == null) {

                $firstReg = '0';
                $voucher_no = $firstReg+1;
            }else{
                $voucher_data = AdvanceCustomerPayment::orderBy('id','DESC')->first()->invoice_no;
                // echo "<pre>"; print_r($voucher_data); exit();
                $voucher_no = $voucher_data+1;
            }
            $customers = Customer::get();
            $title = "Save";
            $editCustomerPayment = '';
            $customersPayment = AdvanceCustomerPayment::with(['users','customers'])->orderBy('id','DESC')->get();
            return view('customers.payments.debit_amount',compact('voucher_no','customers','editCustomerPayment','customersPayment','title'));
        }
    }

    /*==================================================*/
    public function editCustomerPayment(Request $request, $id=null)
    {
        $this->authenticateRole($module_page='customers_payment');
        $editCustomerPayment = AdvanceCustomerPayment::with('customers')->find($id);

        if ($request->isMethod('post')) {
            $data = $request->all();
           // echo "<pre>"; print_r($data); exit();
            try {
                $editCustomerPayment->voucher_no = $data['voucher_no'];
                $editCustomerPayment->date = date('Y-m-d',strtotime($data['date']));
                $editCustomerPayment->customer_id = $data['customer_id'];
                $editCustomerPayment->area_id = $data['area_id'];
                $editCustomerPayment->description = $data['description'];
                $editCustomerPayment->amount = $data['amount'];
                $editCustomerPayment->payment_discount = $data['payment_discount'];
                $editCustomerPayment->user_id = Auth::user()->id;
                $editCustomerPayment->save();
                return redirect('/add-customer-payment')->with('success','Customer Payment Successfully Updated!');
            } catch (Exception $e) {
                Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
                return redirect()->back()->with($e->getMessage());
            }
        }else{
            Session::put('page','customerPayment');
            $customers = Customer::get();
            $title = "Update";
            $customersPayment = AdvanceCustomerPayment::with(['users','customers'])->orderBy('id','DESC')->get();
            return view('customers.payments.debit_amount',compact('editCustomerPayment','customersPayment','customers','title'));
        }
    }
    /*==================================================*/
    public function deleteCustomerPayment($id=null)
    {
        $this->authenticateRole($module_page='customers_payment');
        AdvanceCustomerPayment::find($id)->delete();
        return redirect('/add-customer-payment')->with('success',' Customer Payment Successfully Deleted!');
    }
    /*===== Customer Pre Receivable/ Payable Start ====*/
    public function addOpeningBalance(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();

            try {
                $openingBalance = new CustomerOpeningBalance;
                $openingBalance->invoice_no = $data['voucher_no'];
                $openingBalance->date = date('Y-m-d',strtotime($data['date']));
                $openingBalance->customer_id = $data['customer_id'];
                $openingBalance->area_id = $data['area_id'];
                $openingBalance->type = $data['type'];
                $openingBalance->description = $data['description'];
                $openingBalance->amount = $data['amount'];
                $openingBalance->user_id =  Auth::user()->id;

                $openingBalance->save();
                return redirect()->back()->with('success','Customer Payment Successfully Added');
            } catch (Exception $e) {
                Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
                return redirect()->back()->with($e->getMessage());
            }
        }else{
            Session::put('page','customerOpeningBalance');
            $voucher_data = CustomerOpeningBalance::orderBy('id','DESC')->first();

            if ($voucher_data == null) {

                $firstReg = '0';
                $voucher_no = $firstReg+1;
            }else{
                $voucher_data = CustomerOpeningBalance::orderBy('id','DESC')->first()->invoice_no;
                // echo "<pre>"; print_r($voucher_data); exit();
                $voucher_no = $voucher_data+1;
                // $voucher_no  = "CP-".($voucher_data + 1);
                // echo "<pre>"; print_r($voucher_no); exit();
            }
            $customers = Customer::get();
            $title = "Save";
            $editOpeningBalance = array();
            $customer_opening_balances = CustomerOpeningBalance::with(['users','customers'])->orderBy('id','DESC')->get();
            return view('customers.opening_balance.create',compact('voucher_no','customers','editOpeningBalance','customer_opening_balances','title'));
        }
    }

    /*==================================================*/
    public function editOpeningBalance(Request $request, $id=null)
    {
       $editOpeningBalance = CustomerOpeningBalance::with('customers')->find($id);
       // echo "<pre>"; print_r($editReceivablePayable->toArray()); exit();
        if ($request->isMethod('post')) {
            $data = $request->all();
             // echo "<pre>"; print_r($openingBalance); exit();
            try {
                $editOpeningBalance->invoice_no = $data['voucher_no'];
                $editOpeningBalance->date = date('Y-m-d',strtotime($data['date']));
                $editOpeningBalance->customer_id = $data['customer_id'];
                $editOpeningBalance->area_id = $data['area_id'];
                $editOpeningBalance->type = $data['type'];
                $editOpeningBalance->description = $data['description'];
                $editOpeningBalance->amount = $data['amount'];
                $editOpeningBalance->user_id =  Auth::user()->id;

                $editOpeningBalance->save();
                return redirect('/customer/payments')->with('success','Customer Payment Successfully Updated!');
            } catch (Exception $e) {
                Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
                return redirect()->back()->with($e->getMessage());
            }
        }else{
            Session::put('page','customerOpeningBalance');
            $customers = Customer::get();
            $title = "Update";
            $customer_opening_balances = CustomerOpeningBalance::with(['users','customers'])->orderBy('id','DESC')->get();
            return view('customers.opening_balance.create',compact('editOpeningBalance','customer_opening_balances','customers','title'));
        }
    }
    /*==================================================*/
    public function deleteReceivablePayable($id=null)
    {
        CustomerOpeningBalance::find($id)->delete();
        return redirect('/customer-opening-balance')->with('success',' Customer Payment Successfully Deleted!');
    }
    /*===== Customer Pre Receivable/ Payable Ends ====*/
    /*==================================================*/
    public function checkCustomername(Request $request)
    {
        $data = $request->all();
        $countName = Customer::where('name',$data['name'])->count();
        // echo "<pre>"; print_r($countName); exit();
        if ($countName>0) {
            // dd('ok');
            return "false";
        }else{
            // dd('notok');
            return "true";
        }
    }
    /*==================================================*/
    public function checkCustomernum(Request $request)
    {
        // dd('pl');
        $data = $request->all();
        $countMobile = Customer::where('mobile',$data['mobile'])->count();
        // echo "<pre>"; print_r($countMobile); exit();
        if ($countMobile>0) {
            // dd('ok');
            return "false";
        }else{
            // dd('notok');
            return "true";
        }
    }
    /*==================================================*/
    public function deleteCustomer($id=null)
    {
        $this->authenticateRole($module_page='customers');
        $getSales = Sale::select('customer_id')->where('customer_id',$id)->first();
        // echo "<pre>"; print_r($getSales); exit();
        if ($getSales) {
           return redirect()->back()->with('error','Customer cannot deleted!');

        }else{
            Customer::find($id)->delete();
            return redirect()->back()->with('success','Customer Successfully deleted');
        }


    }
}

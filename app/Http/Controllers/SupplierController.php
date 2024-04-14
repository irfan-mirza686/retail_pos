<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Auth;
use Exception;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\SupplierPayment;
use App\Models\SupplierOpeningBalance;

class SupplierController extends Controller
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
        $this->authenticateRole($module_page='supplier');
    	Session::put('page','suppliers');
    	$suppliers = Supplier::get();
    	return view('suppliers.view',compact('suppliers'));
    }
    /*=======================================================*/
    public function createSupplier(Request $request)
    {
        $this->authenticateRole($module_page='supplier');
    	if ($request->isMethod('post')) {
    		$data = $request->all();
    		try {
    			$supplier = new Supplier;
    			$supplier->user_id = Auth::user()->id;
    			$supplier->name = $data['name'];
    			$supplier->mobile = $data['mobile'];
    			$supplier->cnic = $data['cnic'];
    			$supplier->description = $data['description'];
    			$supplier->address = $data['address'];
    			$supplier->save();
    			return redirect('/suppliers')->with('success','Supplier Successfully Created!');
    		} catch (Exception $e) {
    			Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
				return redirect()->back()->with($e->getMessage());
    		}
    	}else{
    		Session::put('page','createSupplier');
    		return view('suppliers.create');
    	}
    }
    /*=======================================================*/
    public function editSupplier(Request $request, $id=null)
    {
        $this->authenticateRole($module_page='supplier');
    	if ($request->isMethod('post')) {
    		$data = $request->all();
    		try {
    			$updateSupplier = Supplier::find($id);
    			$updateSupplier->user_id = Auth::user()->id;
    			$updateSupplier->name = $data['name'];
    			$updateSupplier->mobile = $data['mobile'];
    			$updateSupplier->cnic = $data['cnic'];
    			$updateSupplier->description = $data['description'];
    			$updateSupplier->address = $data['address'];
    			$updateSupplier->save();
    			return redirect('/suppliers')->with('success','Supplier Successfully Updated!');
    		} catch (Exception $e) {
    			Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
				return redirect()->back()->with($e->getMessage());
    		}
    	}else{
    		$editSupplier = Supplier::find($id);
    		return view('suppliers.edit',compact('editSupplier'));
    	}
    }
    /*==================================================*/
    public function checkSuppliername(Request $request)
    {
        $data = $request->all();
        $countName = Supplier::where('name',$data['name'])->count();
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
    public function checkSuppliernum(Request $request)
    {
        // dd('pl');
        $data = $request->all();
        $countMobile = Supplier::where('mobile',$data['mobile'])->count();
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
    public function searchSupplier(Request $request)
    {
        if ($request->ajax()) {


            $supplierAuto = [];
            $suppliers = Supplier::where('name', 'LIKE', "%" . $request->term . "%")->orWhere('mobile', 'LIKE', "%" . $request->term . "%")->paginate(5);

            if ($suppliers) {
                foreach ($suppliers as $key => $supplier) {
                    $supplierBalance = ($this->supplierTotalAmount($supplier->id) + $this->supplierCreditAmount($supplier->id)) - ($this->supplierPaymentDicsount($supplier->id) + $this->supplierDebitAmount($supplier->id) + $this->paymentToSupplier($supplier->id));
                    $supplierAuto[] = array(
                        "value" => $supplier->name,
                        "customerID" => $supplier->id,
                        "customerName" => $supplier->name,
                        "areaID" => $supplier->area_id,
                        "supplierBalance" => $supplierBalance,
                    );
                }
                $dataRetrun = json_encode($supplierAuto);
                return Response($dataRetrun);
            }
        }
    }
    /*==================================================*/
    
    public function addSupplierPayment(Request $request)
    {
        $this->authenticateRole($module_page='suppliers_payment');
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); exit();
            try {
                $addSupplierPayment = new SupplierPayment;
                $addSupplierPayment->purchase_no = $data['voucher_no'];
                $addSupplierPayment->date = date('Y-m-d',strtotime($data['date']));
                $addSupplierPayment->supplier_id = $data['supplier_id'];
                $addSupplierPayment->amount = $data['amount'];
                $addSupplierPayment->payment_discount = $data['payment_discount'];
                $addSupplierPayment->description = $data['description'];
                $addSupplierPayment->user_id = Auth::user()->id;
                $addSupplierPayment->save();
                return redirect()->back()->with('success','Supplier Payment Successfully Added!');
            } catch (Exception $e) {
                Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
                return redirect()->back()->with($e->getMessage());
            }
        }else{
            Session::put('page','supplierPayment');
            $voucher_data = SupplierPayment::orderBy('id','DESC')->first();

            if ($voucher_data == null) {

                $firstReg = '0';
                $voucher_no = $firstReg+1;
            }else{
                $voucher_data = SupplierPayment::orderBy('id','DESC')->first()->voucher_no;
                // echo "<pre>"; print_r($voucher_data); exit();
                $voucher_no = $voucher_data+1;
            }
            $title = "Save";
            $editSupplierPayment = '';
            $supplierPayment = SupplierPayment::with(['users','suppliers'])->orderBy('id','DESC')->get();
            return view('suppliers.payments.debit_amount',compact('voucher_no','editSupplierPayment','supplierPayment','title'));
        }
    }
    /*==================================================*/
    public function editSupplierPayment(Request $request, $id=null)
    {   
        $this->authenticateRole($module_page='suppliers_payment');
        $editSupplierPayment = SupplierPayment::with('suppliers')->find($id);
        
        if ($request->isMethod('post')) {
            $data = $request->all();
           // echo "<pre>"; print_r($data); exit();
            try {
                $editSupplierPayment->voucher_no = $data['voucher_no'];
                $editSupplierPayment->date = date('Y-m-d',strtotime($data['date']));
                $editSupplierPayment->supplier_id = $data['supplier_id'];
                $editSupplierPayment->amount = $data['amount'];
                $editSupplierPayment->payment_discount = $data['payment_discount'];
                $editSupplierPayment->description = $data['description'];
                $editSupplierPayment->user_id = Auth::user()->id;
                $editSupplierPayment->save();
                return redirect('/add-supplier-payment')->with('success','Supplier Payment Successfully Updated!');
            } catch (Exception $e) {
                Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
                return redirect()->back()->with($e->getMessage());
            }
        }else{
            Session::put('page','customerPayment');
            $title = "Update";
            $supplierPayment = SupplierPayment::with(['users','suppliers'])->orderBy('id','DESC')->get();
            return view('suppliers.payments.debit_amount',compact('editSupplierPayment','supplierPayment','title'));
        }
    }
    /*==================================================*/
    public function deleteSupplierPayment($id=null)
    {
        $this->authenticateRole($module_page='suppliers_payment');
        supplierPayment::find($id)->delete();
        return redirect('/add-supplier-payment')->with('success',' Supplier Payment Successfully Deleted!');
    }
    /*===== Supplier Advance/ Payable Start ====*/
    
    public function addOpeningBalance(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            try {
                $addOpeningBalance = new SupplierOpeningBalance;
                $addOpeningBalance->voucher_no = $data['voucher_no'];
                $addOpeningBalance->date = date('Y-m-d',strtotime($data['date']));
                $addOpeningBalance->supplier_id = $data['supplier_id'];
                $addOpeningBalance->type = $data['type'];
                $addOpeningBalance->amount = $data['amount'];
                $addOpeningBalance->description = $data['description'];
                $addOpeningBalance->user_id = Auth::user()->id;
                $addOpeningBalance->save();
                return redirect()->back()->with('success','Supplier Payment Successfully Added!');
            } catch (Exception $e) {
                Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
                return redirect()->back()->with($e->getMessage());
            }
        }else{
            Session::put('page','supplierOpeningBalance');

            $voucher_data = SupplierOpeningBalance::orderBy('id','DESC')->first();
          
            if ($voucher_data == null) {
                // dd('null');
                $firstReg = '0';
                $voucher_no = $firstReg+1;
            }else{
                // dd('not null');
                $voucher_data = SupplierOpeningBalance::orderBy('id','DESC')->first()->voucher_no;
                // echo "<pre>"; print_r($voucher_data); exit();
                $voucher_no = $voucher_data+1;

            }
            $title = "Save";
            $editOpeningBalance = '';
            $supplierAdvancePayable = SupplierOpeningBalance::with(['users','suppliers'])->orderBy('id','DESC')->get();
            return view('suppliers.advancePayable.add_payment',compact('voucher_no','editOpeningBalance','supplierAdvancePayable','title'));
        }
    }
    /*==================================================*/
    public function editOpeningBalance(Request $request, $id=null)
    {
       $editOpeningBalance = SupplierOpeningBalance::with('suppliers')->find($id);
       // echo "<pre>"; print_r($editOpeningBalance->toArray()); exit(); 
        if ($request->isMethod('post')) {
            $data = $request->all();
            try {
                $editOpeningBalance->date = date('Y-m-d',strtotime($data['date']));
                $editOpeningBalance->supplier_id = $data['supplier_id'];
                $editOpeningBalance->type = $data['type'];
                $editOpeningBalance->amount = $data['amount'];
                $editOpeningBalance->user_id = Auth::user()->id;
                $editOpeningBalance->save();
                return redirect('/supplier-opening-balance')->with('success','Supplier Payment Successfully Updated!');
            } catch (Exception $e) {
                Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
                return redirect()->back()->with($e->getMessage());
            }
        }else{
            Session::put('page','supplierOpeningBalance');
            $title = "Update";
            $supplierAdvancePayable = SupplierOpeningBalance::with(['users','suppliers'])->orderBy('id','DESC')->get();
            return view('suppliers.advancePayable.add_payment',compact('editOpeningBalance','supplierAdvancePayable','title'));
        } 
    }
    /*==================================================*/
    public function deleteOpeningBalance($id=null)
    {
        SupplierOpeningBalance::find($id)->delete();
        return redirect('/supplier-opening-balance')->with('success',' Supplier Payment Successfully Deleted!');
    }
    /*===== Supplier Advance/ Payable Ends ====*/
    /*=======================================================*/
    public function deleteSupplier($id=null)
    {
        $this->authenticateRole($module_page='supplier');
        $getSupplier = Purchase::select('supplier_id')->where('supplier_id',$id)->first();
        // echo "<pre>"; print_r($getSales); exit();
        if ($getSupplier) {
           return redirect()->back()->with('error','Supplier cannot deleted!');
            
        }else{
            Supplier::find($id)->delete();
            return redirect()->back()->with('success','Supplier Successfully deleted');
        }
    }
}

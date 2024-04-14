<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PurchaseService;
use Session;
use Auth;
use DB;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\PurchasePayment;
use App\Models\PurchasePaymentDetail;
use Exception;

class PurchaseController extends Controller
{
	protected $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }
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
		$this->authenticateRole($module_page='purchase');
		Session::put('page','purchaseOrders');
		$purchaseOrders = Purchase::with(['suppliers','users'])->orderBy('id','DESC')->get();
		// echo "<pre>"; print_r($purchaseOrders->toArray()); exit();
		return view('purchase.view',compact('purchaseOrders'));
	}
	/*==================================================================================*/
	public function addPurchase(Request $request)
	{
		$this->authenticateRole($module_page='purchase');
		if ($request->isMethod('post')) {
			$data = $request->all();
			// echo "<pre>"; print_r($data); exit();
			try {

				$this->purchaseService->savePurchase($data, $request);
                $this->purchaseService->updatePurchaseStock($data, $request);
				return redirect('/purchase-orders')->with('success','Purchase Order Successfully Created!');
			} catch (Exception $e) {
				Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
				return redirect()->back()->with($e->getMessage());
			}

		}else{
			Session::put('page','createPurchase');
			$voucher_data = Purchase::orderBy('id','DESC')->first();

            if ($voucher_data == null) {

                $firstReg = '0';
                $purchase_no = $firstReg+1;
            }else{
                $voucher_data = Purchase::orderBy('id','DESC')->first()->purchase_no;
                // echo "<pre>"; print_r($voucher_data); exit();
                $purchase_no = $voucher_data+1;
            }
			$suppliers = Supplier::get();
			return view('purchase.create',compact('suppliers','purchase_no'));
		}
	}
	/*===========================================================*/
	public function editPurchase(Request $request, $id=null)
	{
		$this->authenticateRole($module_page='purchase');
		if ($request->isMethod('post')) {
			$data = $request->all();
			$getStatus = Purchase::select('status')->where('id',$id)->first();
			$checkStatus = $getStatus['status'];

			try {

				if ($request->status=='cancel') {
					if ($checkStatus=='cancel') {
						$this->purchaseService->updatePurchase($data, $request, $id);
					}else{
						$this->purchaseService->cancelPurchase($data, $request,$id);
						$this->purchaseService->updatePurchase($data, $request, $id);
						purchase::where('id',$id)->update(['status'=>'cancel']);
					}

				}else if ($request->status=='received') {

					if ($checkStatus=='received') {

						$this->purchaseService->cancelPurchase($data, $request,$id);
						$this->purchaseService->updatePurchase($data, $request, $id);
						$this->purchaseService->purchaseStockUpdateWithID($data, $request,$id);
					}else{
						$this->purchaseService->updatePurchase($data, $request, $id);
						$this->purchaseService->purchaseStockUpdateWithID($data, $request,$id);
						purchase::where('id',$id)->update(['status'=>'received']);
					}
				}
				return redirect('/purchase-orders')->with('success','Purchase Order Successfully Updated!');
			} catch (Exception $e) {
				Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
				return redirect()->back()->with($e->getMessage());
			}
		}else{
			$editPurchase = Purchase::with('suppliers')->find($id);

			$itemAddons = unserialize($editPurchase->items_addon);
			$suppliers = Supplier::get();
			return view('purchase.edit',compact('editPurchase','suppliers','itemAddons'));
		}
	}
	/*==================================================================================*/
	public function searchRawProducts(Request $request)
	{
		if ($request->ajax()) {
			$rawProducts = [];
			$products = Product::with('units')->where('name', 'LIKE', "%" . $request->term . "%")->orWhere('product_code', 'LIKE', "%" . $request->term . "%")->paginate(5);
			if ($products) {
				foreach ($products as $key => $value) {
					$rawProducts[] = array(
						"value" => $value->name,
						"productID" => $value->id,
						"productName" => $value->name,
						"productUnit" => $value['units']->name,
						"quantity" => $value['quantity'],
						"purchasePrice" => $value['cost']
					);
				}
				$dataRetrun = json_encode($rawProducts);
				return Response($dataRetrun);
			}
		}
	}
	/*==================================================================================*/
	public function supplierPayment(Request $request, $purchase_id=null)
	{
		$this->authenticateRole($module_page='purchase');
		if ($request->isMethod('post')) {
			$data = $request->all();
			try {
				if($data['new_paid_amount'] < $data['paid_amount']){
					return redirect()->back()->with('error','Sorry! you have paid maxium value');
				}else{
					$purchasePayment = PurchasePayment::where('purchase_id',$purchase_id)->first();
					$purchasePaymentDetails = new PurchasePaymentDetail;
					$purchasePayment->paid_status = $data['paid_status'];
					if($data['paid_status'] =='full_paid'){
						if ($data['new_paid_amount'] <= 0) {
							return redirect()->back()->with('error','Sorry! you have paid maxium value');
						}
						$purchasePayment->paid_amount = PurchasePayment::where('purchase_id',$purchase_id)->first()['paid_amount']+$data['new_paid_amount'];
						$purchasePayment->due_amount = '0';
						$purchasePaymentDetails->current_paid_amount = $data['new_paid_amount'];
					}elseif($data['paid_status']=='partial_paid'){
						$purchasePayment->paid_amount = PurchasePayment::where('purchase_id',$purchase_id)->first()['paid_amount']+$data['paid_amount'];
						$purchasePayment->due_amount = PurchasePayment::where('purchase_id',$purchase_id)->first()['due_amount']-$data['paid_amount'];
						$purchasePaymentDetails->current_paid_amount = $data['paid_amount'];
					}
					$purchasePayment->save();
					$purchasePaymentDetails->purchase_id = $purchase_id;
					$purchasePaymentDetails->date = date('Y-m-d',strtotime($request->date));
					$purchasePaymentDetails->updated_by = Auth::user()->id;
					$purchasePaymentDetails->save();

					return redirect('/purchase-orders')->with('success','Payment successfully submit!');
				}
			} catch (Exception $e) {
				Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
				return redirect()->back()->with($e->getMessage());
			}

		}else{
			$checkPurchaseStatus = Purchase::select('status')->where('id',$purchase_id)->first();
			if ($checkPurchaseStatus['status']=='cancel') {
				return redirect()->back()->with('error','You have canceled this purchase!');
			}else{
			// dd($purchase_id);
				$purchase_payment = PurchasePayment::with(['suppliers','purchase'])->where('purchase_id',$purchase_id)->first()->toArray();

			// $purchaseDetails  = Purchase::where('id',$purchase_id)->first();

				$purchaseItemAddons = unserialize($purchase_payment['purchase']['items_addon']);
			// echo"<pre>"; print_r($purchaseItemAddons); exit();
				return view('purchase.payments.add_payments',compact('purchase_payment','purchaseItemAddons'));
			}

		}
	}
	/*==================================================================================*/
	public function deletePurchase($id=null)
	{
		$this->authenticateRole($module_page='purchase');
		$checkPurchaseStatus = Purchase::select('status')->where('id',$id)->first();
		if ($checkPurchaseStatus['status']=='received') {
			return redirect()->back()->with('error','You have canceled this purchase!');
		}else{

			Purchase::where('id',$id)->delete();
			return redirect('/purchase-orders')->with('success','Purchase Order Successfully Deleted!');
		}

	}
}

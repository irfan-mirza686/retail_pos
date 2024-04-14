<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Auth;
use App\Models\Product;
use App\Models\Unit;
use Exception;
class ProductController extends Controller
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
		$this->authenticateRole($module_page='product');
		Session::put('page','viewProducts');
		$products = Product::with(['users','units'])->get();
		return view('products.view',compact('products'));
	}
	/*==============================================================*/
	public function addProduct(Request $request)
	{
		$this->authenticateRole($module_page='product');
		if ($request->isMethod('post')) {
			$data = $request->all();
			try {
				if ( is_numeric($data['unit_id']) && ($data['unit_id'] > 0)) {
					$product = new Product;
					$product->name = $data['name'];
					$product->unit_id = $data['unit_id'];
					$product->cost = $data['cost'];
					$product->selling_price = $data['selling_price'];
					$product->quantity = isset($data['quantity'])?$data['quantity']:0;
					$product->product_code = $data['product_code'];
					$product->user_id = Auth::user()->id;
					$product->save();
					return redirect('/products')->with('success','Product successfully Added!');
				}else{
					return redirect()->back()->with('error','Unit cannot be nulled!');
				}
				
				
			} catch (Exception $e) {
				Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
				return redirect()->back()->with($e->getMessage());
			}
		}else{
			Session::put('page','addProduct');
			$units = Unit::get();
			$title = "Add Product";
			return view('products.add_prodcut',compact('units','title'));
		}
	}
	/*==============================================================*/
	public function editProduct(Request $request,$id=null)
	{
		$this->authenticateRole($module_page='product');
		$editProduct = Product::find($id);
		$data = $request->all();
		if ($request->isMethod('post')) {
			try {
				$editProduct->name = $data['name'];
				$editProduct->unit_id = $data['unit_id'];
				$editProduct->cost = $data['cost'];
				$editProduct->selling_price = $data['selling_price'];
				$editProduct->quantity = $data['quantity'];
				$editProduct->user_id = Auth::user()->id;
				$editProduct->product_code = $data['product_code'];
				$editProduct->save();
				return redirect('/products')->with('success','Product successfully Updated!');
			} catch (Exception $e) {
				Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
				return redirect()->back()->with($e->getMessage());
			}
		}else{
			$units = Unit::get();
			$title = "Update Product";
			return view('products.add_prodcut',compact('units','editProduct','title'));
		}
	}
	
	/*==============================================================*/
	public function deleteProduct($id=null)
	{
		
	}
	/*==============================================================*/
}

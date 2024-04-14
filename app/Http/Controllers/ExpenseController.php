<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Auth;
use Exception;
use App\Models\Expense;
use App\Models\ExpenseDetail;
use App\Models\ExpenseCategory;
use DB;

class ExpenseController extends Controller
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
		$this->authenticateRole($module_page='expenses');
		Session::put('page','expenses');
		$expenses = Expense::with(['user'])->get();
		return view('expenses.view_expenses',compact('expenses'));
	}
	/*=======================================================*/
	public function addExpenses(Request $request)
	{
		$this->authenticateRole($module_page='expenses');
		if ($request->isMethod('post')) {
			$data = $request->all();
			try {
				$expense = new Expense;
				$expense->date = date('Y-m-d',strtotime($data['date']));
				$expense->exp_category_id = $data['exp_category_id'];
				$expense->expense_for = $data['expense_for'];
				$expense->amount = $data['amount'];
				$expense->note = $data['note'];
				$expense->user_id = Auth::user()->id;
				$expense->save();
				return redirect()->back()->with('success','Expense added successfully!');
			} catch (Exception $e) {
				Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
				return redirect()->back()->with($e->getMessage());
			}

		}else{
			Session::put('page','addExpenses');
			$expCategories = ExpenseCategory::get(); 
			return view('expenses.add_expenses',compact('expCategories'));
		}
	}
	/*=======================================================*/
	public function editExpenses(Request $request, $id=null)
	{
		$this->authenticateRole($module_page='expenses');
		$data = $request->all();
		$editExpense = Expense::find($id);
		if ($request->isMethod('post')) {
			try {
				$editExpense->date = date('Y-m-d',strtotime($data['date']));
				$editExpense->exp_category_id = $data['exp_category_id'];
				$editExpense->expense_for = $data['expense_for'];
				$editExpense->amount = $data['amount'];
				$editExpense->note = $data['note'];
				$editExpense->user_id = Auth::user()->id;
				$editExpense->save();
				return redirect('/expenses')->with('success','Expense updated successfully!');
			} catch (Exception $e) {
				Session::flash('flash_message_error', "Oops, Something went wrong. Try again");
				return redirect()->back()->with($e->getMessage());
			}
		}else{
			$editExpenses = Expense::find($id);
			// echo "<pre>"; print_r($editExpenses->toArray()); exit();
			
			$expCategories = ExpenseCategory::get();

			
			return view('expenses.edit_expenses',compact('editExpenses','expCategories','editExpense'));
		}
	}
	/*=======================================================*/
	public function expenseCategories()
	{
		$this->authenticateRole($module_page='expenses');
		Session::put('page','expenseCategories');
		$expenseCategories = ExpenseCategory::get();
		return view('expenses.expense_categories', compact('expenseCategories'));
	}
	/*=======================================================*/
	public function addExpCat(Request $request)
	{
		$this->authenticateRole($module_page='expenses');
		if ($request->isMethod('post')) {
			$expCategory = new ExpenseCategory;
			$expCategory->name = $request->name;
			$expCategory->save();
			return redirect('/expense-categories')->with('success','Expense Category Created!');
		}else{
			return view('expenses.add_exp_category');
		}
	}
	/*=======================================================*/
	public function deleteExpense($id=null)
	{
		$this->authenticateRole($module_page='expenses');
		Expense::find($id)->delete();
		return redirect()->back()->with('success','Expense successfully deleted!');
	}
	/*=======================================================*/
	public function deleteExpCategory($id=null)
	{
		$this->authenticateRole($module_page='expenses');
		$getExpCat = Expense::select('exp_category_id')->where('exp_category_id',$id)->first();
        // echo "<pre>"; print_r($getExpCat); exit();
        if ($getExpCat) {
           return redirect()->back()->with('error','Category cannot deleted!');
            
        }else{
            ExpenseCategory::find($id)->delete();
            return redirect()->back()->with('success','Category Successfully deleted');
        }
	}
}

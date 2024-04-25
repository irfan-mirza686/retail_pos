<?php
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Route::get('/cache-clear', function () {
//    Artisan::call('optimize:clear');
//    Artisan::call('config:cache');
//    Artisan::call('cache:clear');
//    Artisan::call('config:clear');
//    Artisan::call('view:clear');
//    Artisan::call('key:generate');
//    echo "cache cleared";
// });

Route::post('/login','AdminController@login')->name('login');
Route::post('login/member','AdminController@loginMember')->name('login.member');
Route::post('check/email','AdminController@checkEmail')->name('check.email');

// Route::group(['middleware' => ['auth']], function() {

    Route::get('/dashboard','AdminController@dashboard')->name('dashboard');
    Route::get('/logout','AdminController@logout');

    //User Roles Routes...
    Route::get('roles','GroupController@index')->name('roles');
    Route::match(['get','post'],'/add-role','GroupController@addRole')->name('role.add');
    Route::match(['get','post'],'/edit-role/{id}','GroupController@editRole')->name('role.edit');
    Route::get('delete-role/{id}','GroupController@deleteRole')->name('delete.role');
    // Users Route.....
    Route::get('/view-users','AdminController@viewUsers');
    Route::match(['get','post'],'/add-user','AdminController@addUser');
    Route::match(['get','post'],'/edit-user/{id}','AdminController@editUser');
    Route::get('/delete-user/{id}','AdminController@deleteUser');
    // User Profile Route.....
    Route::get('/user-profile','ProfileController@index');
    Route::match(['get','post'],'/edit-user-profile/{id}','ProfileController@editUser');
    Route::match(['get','post'],'/update-password','ProfileController@updatePassword');
    Route::post('/check-current-pwd','ProfileController@checkCurrentPassword');



    // Customers Routes.......
    Route::get('/customers','CustomerController@index');
    Route::match(['get','post'],'/create-customer','CustomerController@createCustomer');
    Route::match(['get','post'],'/edit-customer/{id}','CustomerController@editCustomer');
    Route::get('/delete-customer/{id}','CustomerController@deleteCustomer');
    Route::post('/ajax-add-customer','CustomerController@ajaxAddCustomer');
    Route::get('/customer-auto-complete','CustomerController@searchCustomer');
    Route::match(['get','post'],'/check-customername','CustomerController@checkCustomername');
    Route::match(['get','post'],'/customer-number','CustomerController@checkCustomernum');
    //Customer Receiving Payment Route....
    Route::match(['get','post'],'/add-customer-payment','CustomerController@addCustomerPayment');
    Route::match(['get','post'],'/edit-customer-payment/{id}','CustomerController@editCustomerPayment');
    Route::get('/delete-customer-payment/{id}','CustomerController@deleteCustomerPayment');
    //Customer Opening Balance Route....
    Route::match(['get','post'],'/customer/payments','CustomerController@addOpeningBalance');
    Route::match(['get','post'],'/edit/customer/payments/{id}','CustomerController@editOpeningBalance');
    Route::get('/delete-customer-opening-balance/{id}','CustomerController@deleteReceivablePayable');
    // Suppliers Routes.......
    Route::get('/suppliers','SupplierController@index');
    Route::match(['get','post'],'/create-supplier','SupplierController@createSupplier');
    Route::match(['get','post'],'/edit-supplier/{id}','SupplierController@editSupplier');
    Route::get('/delete-supplier/{id}','SupplierController@deleteSupplier');
    Route::match(['get','post'],'/check-suppliername','SupplierController@checkSuppliername');
    Route::match(['get','post'],'/supplier-number','SupplierController@checkSuppliernum');
    Route::get('/supplier-auto-complete','SupplierController@searchSupplier');
    //Supplier Payment Route....
    Route::match(['get','post'],'/add-supplier-payment','SupplierController@addSupplierPayment');
    Route::match(['get','post'],'/edit-supplier-payment/{id}','SupplierController@editSupplierPayment');
    Route::get('/delete-supplier-payment/{id}','SupplierController@deleteSupplierPayment');
    //Supplier Opening Balance Route....
    Route::match(['get','post'],'/supplier-opening-balance','SupplierController@addOpeningBalance');
    Route::match(['get','post'],'/edit-supplier-opening-balance/{id}','SupplierController@editOpeningBalance');
    Route::get('/delete-supplier-opening-balance/{id}','SupplierController@deleteOpeningBalance');
    // Expenses Routes.....
    Route::get('/expenses','ExpenseController@index')->name('expenses');
    Route::match(['get','post'],'/add-expense','ExpenseController@addExpenses');
    Route::match(['get','post'],'/edit-expense/{id}','ExpenseController@editExpenses');
    Route::get('/expense-categories','ExpenseController@expenseCategories');
    Route::match(['get','post'],'/add-expense-category','ExpenseController@addExpCat');
    Route::get('/delete-expense/{id}','ExpenseController@deleteExpense');
    Route::get('/delete-expCate/{id}','ExpenseController@deleteExpCategory');
    // Unit Routes.....
    Route::get('/units','UnitController@index');
    Route::match(['get','post'],'/create-unit','UnitController@createUnit');
    Route::match(['get','post'],'/edit-unit/{id}','UnitController@editUnit');
    Route::get('/delete-unit/{id}','UnitController@deleteUnit');

    // Area Routes.....
    Route::get('/areas','AreaController@index');
    Route::match(['get','post'],'/add-area','AreaController@addArea');
    Route::match(['get','post'],'/edit-area/{id}','AreaController@editArea');
    Route::get('/delete-area/{id}','AreaController@deleteArea');

    /* Employees Routes Starts Here */
    Route::namespace('Employee')->group(function (){
    Route::get('/employees','EmployeeController@index');
    Route::match(['get','post'],'/create-employee','EmployeeController@create');
    Route::match(['get','post'],'/edit-employee/{id}','EmployeeController@edit');
    Route::get('/employee-details/{id}','EmployeeController@employeeDetails');
    //Employee Advance Route....
    Route::match(['get','post'],'/employee-advance-salary/{id}','EmployeeAdvanceController@advanceSalary');
    // Monthly Employee Salary Increment Route....
    Route::match(['get','post'],'/monthly-employee-salary-increment/{id}','MonthlyEmployeeIncrementController@increment');
    // Monthly Employee Return Advance Route...
    Route::match(['get','post'],'/monthly-employee-return-advance/{id}','EmployeeController@returnAdvance');

    /* Employee Leave Routes Starts */
    Route::get('/employees-leaves','LeaveController@index');
    Route::match(['get','post'],'/add-employee-leave','LeaveController@addLeave');
    Route::match(['get','post'],'/edit-employee-leave/{id}','LeaveController@editLeave');
    /* Employee Leave Routes Ends */

    /* Employee Attendance Routes Starts */
    Route::get('/employees-attendance','AttendanceController@index');
    Route::match(['get','post'],'/add-employee-attendance','AttendanceController@create');
    Route::match(['get','post'],'/edit-employee-attendance/{date}','AttendanceController@editAttendance');
    Route::get('/employee-attendance-details/{date}','AttendanceController@attendanceDetails');

    /* Employee Attendance Routes Ends */

    /* Employee Monthly Salary Routes Starts */
    Route::get('/employee-monthly-salary','MonthlySalaryController@view');
    Route::get('/employee-monthly-salary-datewise-get','MonthlySalaryController@monthlySalaryDatewiseGet');
    Route::get('/employee-monthly-salary-payslip/{employee_id}','MonthlySalaryController@paySlip');
    Route::match(['get','post'],'/pay-employee-mothly-salary','MonthlySalaryController@paySalary');

    /* Employee Monthly Salary Routes Starts */
    });
    /* Employees Routes Ends Here */

    /* Products Routes Starts Here */
    Route::get('/products','ProductController@index');
    Route::match(['get','post'],'/add-product','ProductController@addProduct');
    Route::match(['get','post'],'/edit-product/{id}','ProductController@editProduct');
    Route::get('/delete-product/{id}','ProductController@deleteProduct');
    /* Products Routes Starts Here */

    /*Check Product Stock with Ajax Route*/
    Route::get('/check-prodcut-stock','SalesController@checkProductStock');
    /*Check Product Stock with Ajax Route Ends*/

    /* Purchase Functionality Routes Starts Here */
    Route::get('/purchase-orders','PurchaseController@index');
    Route::match(['get','post'],'/add-purchase','PurchaseController@addPurchase');
    Route::match(['get','post'],'/edit-purchase/{id}','PurchaseController@editPurchase');
    Route::get('/delete-purchase/{id}','PurchaseController@deletePurchase');
    Route::get('/search-supplier','PurchaseController@serachSupplier');
    Route::get('/search-raw-product','PurchaseController@searchRawProducts');
    //Supplier Payment ....
    Route::match(['get','post'],'/supplier-payment/{purchase_id}','PurchaseController@supplierPayment');
    /* Purchase Functionality Routes Ends Here */

    /* Sales Routes Starts Here */
    Route::get('/sales','SalesController@index');
    Route::match(['get','post'],'add-sale','SalesController@addSale');
    Route::match(['get','post'],'edit-sale/{id}','SalesController@editSale');
    Route::get('/delete-sale/{id}','SalesController@deleteSale');
    Route::get('/sale-invoice/{id}','SalesController@saleInvoice');
    Route::get('/print-sale-invoice/{id}','SalesController@printSaleInvoice');
    // Customer Payements Route...
    Route::match(['get','post'],'/customer-payment/{id}','SalesController@customerPayment');

    Route::get('/search-sale-product','SalesController@searchSaleProducts');
    /* Sales Routes Starts Here */

    /* Reports Controllers Starts Here */
    Route::namespace('Reports')->group(function (){

        /* Staff Sales Report */
        Route::get('/report-staff-sales','UserSalesController@index');
        Route::post('/report-get-staff-sales','UserSalesController@getUsersSale');
        Route::post('/report-salesByStaff-pdf','UserSalesController@downloadUserSalesPdf');

        /* Customer Payments By Staff Report */
        Route::get('/report/customer/payments/by/staff','UserSalesController@paymentsByStaff');
        Route::post('/report-paymentsByStaff-pdf','UserSalesController@downloadPaymentsByStaffPdf');

        /* Profit && Loss Report */
        Route::get('/report-profit-loss','ProfitLossController@profitLoss');

        /* Customer Credit/Debit Report */
        Route::get('/report-credit-debit','CreditDebitController@index');
        Route::post('/report-credit-debit-pdf','CreditDebitController@creditDebitReport');

        /* Supplier Credit/Debit Report */
        Route::get('/report-supplier-credit-debit','SupplierCreditDebitController@index');
        Route::post('/report-supplier-credit-debit-pdf','SupplierCreditDebitController@supplierCreditDebitReport');
        /* Purchase Report */
        Route::get('/report-purchase','PurchaseController@index');
        Route::get('/report-get-purchase','PurchaseController@getPurchase');
        Route::post('/report-purchase-pdf','PurchaseController@downloadPurchasePdf');

        /* Sales Report */
        Route::get('/report-sales','SalesController@index');
        Route::get('/report-get-sales','SalesController@getSales');
        Route::post('/report-sales-pdf','SalesController@downloadSalesPdf');

        /* Sold Items Report */
        Route::get('/report-sold-items','SoldItemsController@index');
        Route::get('/get-sold-products','SoldItemsController@getSoldProducts');
        Route::post('/sold-items-report-pdf','SoldItemsController@downloadSoldItemsPdf');

        /* Stock Report */
        Route::get('/report-stock','StockController@index');
        Route::get('/get-product-stock','StockController@getStock');
        Route::post('/report-stock-pdf','StockController@downloadStockPdf');

        /* Expense Report */
        Route::get('/report-expense','ExpenseReportController@index');
        Route::get('/get-expense','ExpenseReportController@getExpense');
        Route::post('/report-expense-pdf','ExpenseReportController@downloadExpensePdf');


    });
    /* Reports Controllers Starts Here */
// });


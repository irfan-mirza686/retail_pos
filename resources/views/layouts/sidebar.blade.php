<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/dashboard') }}" class="brand-link">
      <img src="{{asset('images/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light"><strong>POS</strong></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('images/avatar5.png') }}" class="img-circle elevation-2" alt="User Image" style="height: 40px; width: 45px;">
        </div>
        <div class="info">
          <a href="{{ url('/user-profile') }}" class="d-block">&nbsp;&nbsp; {{ Auth::user()->name }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2 text-sm">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               @if(Session::get('page') == "dashboard")
          <?php $active = "active"; ?>
          @else
          <?php $active = ""; ?>
          @endif
          <li class="nav-item has-treeview">
            <a href="{{ url('/dashboard') }}" class="nav-link {{ $active }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>

          </li>
          <!-- MANAGE USERS--------------------------->

          @if(Session::get('page') == "addUser" || Session::get('page') == "viewUser" || Session::get('page') == "roles")
          <?php $active = "active"; ?>
          @else
          <?php $active = ""; ?>
          @endif
          <li class="nav-item has-treeview @if(Session::get('page') == "addUser" || Session::get('page') == "viewUser" || Session::get('page') == "roles") menu-open @endif">
            <a href="#" class="nav-link {{ $active }}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Manage Users
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">

              @if(Session::get('page') == "addUser")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif
              <li class="nav-item">
                <a href="{{ url('add-user') }}" class="nav-link {{ $active }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add User</p>
                </a>
              </li>
              <!-- View Users--------------------------->
              @if(Session::get('page') == "viewUser")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif
              <li class="nav-item">
                <a href="{{ url('view-users') }}" class="nav-link {{ $active }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View Users</p>
                </a>
              </li>

              <!-- View Roles--------------------------->
              @if(Session::get('page') == "roles")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif
              <li class="nav-item">
                <a href="{{ url('roles') }}" class="nav-link {{ $active }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Roles</p>
                </a>
              </li>
            </ul>
          </li>



           <!-- INVOICE MANAGEMENT--------------------------->
          @if(Session::get('page') == "createInvoice" || Session::get('page') == "viewInvoice" || Session::get('page') == "pendingPurchase")
          <?php $active = "active"; ?>
          @else
          <?php $active = ""; ?>
          @endif
          <li class="nav-item has-treeview @if(Session::get('page') == "createInvoice" || Session::get('page') == "viewInvoice" || Session::get('page') == "pendingInvoice") menu-open @endif">
            <a href="#" class="nav-link {{ $active }}">

              <i class="nav-icon fas fa-shopping-cart"></i>
              <p>
                Sales
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <!-- View Invoice--------------------------->
              @if(Session::get('page') == "createInvoice")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif

              <li class="nav-item">
                <a href="{{ url('/add-sale') }}" class="nav-link {{ $active }}">
                  <i class="far fa-plus-square nav-icon"></i>
                  <p>New Sales</p>
                </a>
              </li>
              <!-- Invoice List--------------------------->
              @if(Session::get('page') == "viewInvoice")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif

              <li class="nav-item">
                <a href="{{ url('/sales') }}" class="nav-link {{ $active }}">
                  <i class="fas fa-list nav-icon"></i>

                  <p>Sales List</p>
                </a>
              </li>

            </ul>
          </li>
          <!-- CUSTOMER MANAGEMENT--------------------------->
          @if(Session::get('page') == "customers" ||  Session::get('page') == "createCustomers" || Session::get('page') == "customerPayment" || Session::get('page') == "customerOpeningBalance")
          <?php $active = "active"; ?>
          @else
          <?php $active = ""; ?>
          @endif
          <li class="nav-item has-treeview @if(Session::get('page') == "customers" ||  Session::get('page') == "createCustomers" || Session::get('page') == "customerPayment" || Session::get('page') == "customerOpeningBalance") menu-open @endif">
            <a href="#" class="nav-link {{ $active }}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Customers
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <!-- Create Customers--------------------------->
              @if(Session::get('page') == "createCustomers")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif
              <li class="nav-item">
                <a href="{{ url('/create-customer') }}" class="nav-link {{ $active }}">
                  <i class="far fa-plus-square nav-icon"></i>
                  <p>Create Customer</p>
                </a>
              </li>
              <!-- Customers List--------------------------->
              @if(Session::get('page') == "customers")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif
              <li class="nav-item">
                <a href="{{ url('/customers') }}" class="nav-link {{ $active }}">
                  <i class="fas fa-list nav-icon"></i>
                  <p>Customers List</p>
                </a>
              </li>
              <!-- Customer Deposits--------------------------->
              @if(Session::get('page') == "customerPayment")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif

              <!-- <li class="nav-item">
                <a href="{{ url('/add-customer-payment') }}" class="nav-link {{ $active }}">
                  <i class="fas fa-money-check-alt nav-icon"></i>
                  <p>Customer Payment</p>
                </a>
              </li> -->
              <!-- Customer Pre Receivable/Payable--------------------------->
              @if(Session::get('page') == "customerOpeningBalance")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif

            <li class="nav-item">
                <a href="{{ url('/customer/payments') }}" class="nav-link {{ $active }}">
                  <i class="fas fa-money-check-alt nav-icon"></i>
                  <p>Customer Payments</p>
                </a>
              </li>

            </ul>
          </li>
          <!-- PURCHASE MANAGEMENT--------------------------->
          @if(Session::get('page') == "createPurchase" || Session::get('page') == "purchaseOrders")
          <?php $active = "active"; ?>
          @else
          <?php $active = ""; ?>
          @endif
          <li class="nav-item has-treeview @if(Session::get('page') == "createPurchase" || Session::get('page') == "purchaseOrders") menu-open @endif">
            <a href="#" class="nav-link {{ $active }}">
              <i class="nav-icon fas fa-th-large"></i>
              <p>
               Purchase
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <!-- Add Purchase Order --------------------------->
              @if(Session::get('page') == "createPurchase")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif
              <li class="nav-item">
                <a href="{{ url('/add-purchase') }}" class="nav-link {{ $active }}">
                  <i class="far fa-plus-square nav-icon"></i>
                  <p>New Purchase</p>
                </a>
              </li>
              <!-- Purchase Orders --------------------------->
              @if(Session::get('page') == "purchaseOrders")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif
              <li class="nav-item">
                <a href="{{ url('/purchase-orders') }}" class="nav-link {{ $active }}">
                  <i class="fas fa-list nav-icon"></i>
                  <p>Purchase List</p>
                </a>
              </li>
            </ul>
          </li>
          <!-- SUPPLIER MANAGEMENT--------------------------->
          @if(Session::get('page') == "suppliers" ||  Session::get('page') == "createSupplier" || Session::get('page') == "supplierPayment" || Session::get('page') == "supplierOpeningBalance")
          <?php $active = "active"; ?>
          @else
          <?php $active = ""; ?>
          @endif
          <li class="nav-item has-treeview @if(Session::get('page') == "suppliers" ||  Session::get('page') == "createSupplier" || Session::get('page') == "supplierPayment" || Session::get('page') == "supplierOpeningBalance") menu-open @endif">
            <a href="#" class="nav-link {{ $active }}">
              <i class="nav-icon fas fa-user-plus"></i>
              <p>
                Suppliers

                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <!-- Create Suppliers--------------------------->
              @if(Session::get('page') == "createSupplier")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif
              <li class="nav-item">
                <a href="{{ url('/create-supplier') }}" class="nav-link {{ $active }}">
                  <i class="far fa-plus-square nav-icon"></i>
                  <p>Create Supplier</p>
                </a>
              </li>
              <!-- Suppliers List--------------------------->
              @if(Session::get('page') == "suppliers")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif
              <li class="nav-item">
                <a href="{{ url('/suppliers') }}" class="nav-link {{ $active }}">
                  <i class="fas fa-list nav-icon"></i>
                  <p>Suppliers List</p>
                </a>
              </li>
              <!-- Add Payment to Supplier--------------------------->
              @if(Session::get('page') == "supplierPayment")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif

              <li class="nav-item">
                <a href="{{ url('/add-supplier-payment') }}" class="nav-link {{ $active }}">
                  <i class="fas fa-money-check-alt nav-icon"></i>
                  <p>Supplier Payment</p>
                </a>
              </li>
              <!-- Supplier Advance/Payable--------------------------->
              @if(Session::get('page') == "supplierOpeningBalance")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif

              <!-- <li class="nav-item">
                <a href="{{ url('/supplier-opening-balance') }}" class="nav-link {{ $active }}">
                  <i class="fas fa-money-check-alt nav-icon"></i>
                  <p>Opening Balance</p>
                </a>
              </li> -->

            </ul>
          </li>
          <!-- PRODUCT MANAGEMENT--------------------------->
          @if(Session::get('page') == "viewProducts" || Session::get('page') == "addProduct")
          <?php $active = "active"; ?>
          @else
          <?php $active = ""; ?>
          @endif
          <li class="nav-item has-treeview @if(Session::get('page') == "viewProducts" || Session::get('page') == "addProduct") menu-open @endif">
            <a href="#" class="nav-link {{ $active }}">

              <i class="nav-icon fas fa-cubes"></i>
              <p>
                Manage Products
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <!-- Add Product--------------------------->
              @if(Session::get('page') == "addProduct")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif
              <li class="nav-item">
                <a href="{{ url('/add-product') }}" class="nav-link {{ $active }}">
                  <i class="far fa-plus-square nav-icon"></i>
                  <p>Add Product</p>
                </a>
              </li>
              <!-- View Products--------------------------->
              @if(Session::get('page') == "viewProducts")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif
              <li class="nav-item">
                <a href="{{ url('products') }}" class="nav-link {{ $active }}">
                  <i class="fas fa-list nav-icon"></i>
                  <p>Products List</p>
                </a>
              </li>


            </ul>
          </li>

          <!-- Employees MANAGEMENT--------------------------->
          @if(Session::get('page') == "employees" ||  Session::get('page') == "createEmployee" || Session::get('page') == "employeesLeaves" || Session::get('page') == "employeesAttendance" || Session::get('page') == "employeeMonthlySalary")
          <?php $active = "active"; ?>
          @else
          <?php $active = ""; ?>
          @endif
          <li class="nav-item has-treeview @if(Session::get('page') == "employees" ||  Session::get('page') == "createEmployee" || Session::get('page') == "employeesLeaves" || Session::get('page') == "employeesAttendance" || Session::get('page') == "employeeMonthlySalary") menu-open @endif">
            <a href="#" class="nav-link {{ $active }}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Emloyees
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <!-- Create Employee--------------------------->
              @if(Session::get('page') == "createEmployee")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif
              <li class="nav-item">
                <a href="{{ url('/create-employee') }}" class="nav-link {{ $active }}">
                  <i class="far fa-plus-square nav-icon"></i>
                  <p>Create Employee</p>
                </a>
              </li>
              <!-- Employee List--------------------------->
              @if(Session::get('page') == "employees")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif
              <li class="nav-item">
                <a href="{{ url('/employees') }}" class="nav-link {{ $active }}">
                  <i class="fas fa-list nav-icon"></i>
                  <p>Employees List</p>
                </a>
              </li>

             <!-- Monthly Employee Salary--------------------------->
              @if(Session::get('page') == "employeeMonthlySalary")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif

              <li class="nav-item">
                <a href="{{ url('/employee-monthly-salary') }}" class="nav-link {{ $active }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Monthly Salry</p>
                </a>
              </li>

            </ul>
          </li>

          <!-- EXPENSES MANAGEMENT--------------------------->
          @if(Session::get('page') == "addExpenses" || Session::get('page') == "expenses" || Session::get('page') == "expenseCategories")
          <?php $active = "active"; ?>
          @else
          <?php $active = ""; ?>
          @endif
          <li class="nav-item has-treeview @if(Session::get('page') == "addExpenses" || Session::get('page') == "expenses" || Session::get('page') == "expenseCategories") menu-open @endif">
            <a href="#" class="nav-link {{ $active }}">
              <i class="nav-icon fas fa-minus-circle"></i>
              <p>
                Expenses
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <!-- View Categories--------------------------->
              @if(Session::get('page') == "addExpenses")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif
              <li class="nav-item">
                <a href="{{ url('/add-expense') }}" class="nav-link {{ $active }}">
                  <i class="far fa-plus-square nav-icon"></i>
                  <p>Add Expenses</p>
                </a>
              </li>
              @if(Session::get('page') == "expenses")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif
              <li class="nav-item">
                <a href="{{ url('/expenses') }}" class="nav-link {{ $active }}">
                  <i class="fas fa-list nav-icon"></i>
                  <p>Expenses</p>
                </a>
              </li>
              @if(Session::get('page') == "expenseCategories")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif
              <li class="nav-item">
                <a href="{{ url('/expense-categories') }}" class="nav-link {{ $active }}">
                  <i class="nav-icon fas fa-th-list"></i>
                  <p>Expense Categories</p>
                </a>
              </li>
            </ul>
          </li>


          <!-- SETTINGS--------------------------->
          @if(Session::get('page') == "units" ||  Session::get('page') == "areas")
          <?php $active = "active"; ?>
          @else
          <?php $active = ""; ?>
          @endif
          <li class="nav-item has-treeview @if(Session::get('page') == "units" ||  Session::get('page') == "areas") menu-open @endif">
            <a href="#" class="nav-link {{ $active }}">

              <i class="nav-icon fas fa-cog"></i>
              <p>
                Settings
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <!-- Add Area--------------------------->
              @if(Session::get('page') == "areas")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif
              <li class="nav-item">
                <a href="{{ url('/areas') }}" class="nav-link {{ $active }}">
                  <i class="fas fa-list nav-icon"></i>
                  <p>Areas</p>
                </a>
              </li>
              <!-- Units List--------------------------->
              @if(Session::get('page') == "units")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif
              <li class="nav-item">
                <a href="{{ url('/units') }}" class="nav-link {{ $active }}">
                  <i class="fas fa-list nav-icon"></i>
                  <p>Units List</p>
                </a>
              </li>

            </ul>
          </li>

          <!-- Reports--------------------------->
          @if(Session::get('page') == "userSaleReport" || Session::get('page') == "customerPaymentsByStaff" || Session::get('page') == "profitLoss" || Session::get('page') == "supplierCreditDebitReport" || Session::get('page') == "creditDebitReport" || Session::get('page') == "reportPurchase" || Session::get('page') == "reportSales" || Session::get('page') == "stock" || Session::get('page') == "soldItems" || Session::get('page') == "expenseReport")
          <?php $active = "active"; ?>
          @else
          <?php $active = ""; ?>
          @endif
          <li class="nav-item has-treeview @if(Session::get('page') == "userSaleReport" || Session::get('page') == "customerPaymentsByStaff" || Session::get('page') == "profitLoss" || Session::get('page') == "supplierCreditDebitReport" || Session::get('page') == "creditDebitReport" || Session::get('page') == "reportPurchase" || Session::get('page') == "reportSales" || Session::get('page') == "stock" || Session::get('page') == "soldItems" || Session::get('page') == "expenseReport") menu-open @endif">
            <a href="#" class="nav-link {{ $active }}">
              <i class="nav-icon fas fa-file-invoice"></i>
              <p>
                Reports
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <!-- Staff Credit/Debit Report--------------------------->
              @if(Session::get('page') == "userSaleReport")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif
              <li class="nav-item">
                <a href="{{ url('/report-staff-sales') }}" class="nav-link {{ $active }}">
                  <i class="far fa-file-alt nav-icon"></i>
                  <p>Staff Sales Report</p>
                </a>
              </li>

              <!-- Customer Pyaments By Staff Report --------------------------->
              @if(Session::get('page') == "customerPaymentsByStaff")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif
              <li class="nav-item">
                <a href="{{ url('/report/customer/payments/by/staff') }}" class="nav-link {{ $active }}">
                  <i class="far fa-file-alt nav-icon"></i>
                  <p>Customer Payments By Staff</p>
                </a>
              </li>

              <!-- Profit and Loss Report--------------------------->
              @if(Session::get('page') == "profitLoss")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif
              <li class="nav-item">
                <a href="{{ url('/report-profit-loss') }}" class="nav-link {{ $active }}">
                  <i class="far fa-file-alt nav-icon"></i>
                  <p>Profit & Loss Report</p>
                </a>
              </li>
              <!-- Supplier Credit/Debit Report--------------------------->
              @if(Session::get('page') == "supplierCreditDebitReport")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif
              <li class="nav-item">
                <a href="{{ url('/report-supplier-credit-debit') }}" class="nav-link {{ $active }}">
                  <i class="far fa-file-alt nav-icon"></i>
                  <p>Supplier Credit/Debit Report</p>
                </a>
              </li>
              <!-- Purchase Report--------------------------->
              @if(Session::get('page') == "reportPurchase")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif
              <li class="nav-item">
                <a href="{{ url('/report-purchase') }}" class="nav-link {{ $active }}">
                  <i class="far fa-file-alt nav-icon"></i>
                  <p>Purchase Report</p>
                </a>
              </li>
              <!-- Customer Credit/Debit Report--------------------------->
              @if(Session::get('page') == "creditDebitReport")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif
              <li class="nav-item">
                <a href="{{ url('/report-credit-debit') }}" class="nav-link {{ $active }}">
                  <i class="far fa-file-alt nav-icon"></i>
                  <p>Customer Credit/Debit Report</p>
                </a>
              </li>

              <!-- Sales Report--------------------------->
              @if(Session::get('page') == "reportSales")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif
              <li class="nav-item">
                <a href="{{ url('/report-sales') }}" class="nav-link {{ $active }}">
                  <i class="far fa-file-alt nav-icon"></i>
                  <p>Sales Report</p>
                </a>
              </li>
              <!-- Sold Items Report--------------------------->
              @if(Session::get('page') == "soldItems")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif
              <li class="nav-item">
                <a href="{{ url('/report-sold-items') }}" class="nav-link {{ $active }}">
                  <i class="far fa-file-alt nav-icon"></i>
                  <p>Item Sales Report</p>
                </a>
              </li>

              <!-- Stock Report--------------------------->
              @if(Session::get('page') == "stock")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif
              <li class="nav-item">
                <a href="{{ url('/report-stock') }}" class="nav-link {{ $active }}">
                  <i class="far fa-file-alt nav-icon"></i>
                  <p>Stock Report</p>
                </a>
              </li>

              <!-- Expense Report--------------------------->
              @if(Session::get('page') == "expenseReport")
              <?php $active = "active"; ?>
              @else
              <?php $active = ""; ?>
              @endif
              <li class="nav-item">
                <a href="{{ url('/report-expense') }}" class="nav-link {{ $active }}">
                  <i class="far fa-file-alt nav-icon"></i>
                  <p>Expense Report</p>
                </a>
              </li>

            </ul>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

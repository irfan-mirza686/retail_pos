@extends('layouts.layout')
@section('title', '| Add Sale')
@section('content')



<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Add Sale</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{{ url('/admin/dashboard')}}">Dashboard</a></li>
						<li class="breadcrumb-item active">Add Sale</li>
					</ol>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			@if(Session::has('flash_message_error'))
			<div class="alert alert-danger">

				<strong> {!! session('flash_message_error') !!} </strong>
			</div>

			@endif
			@if(Session::has('flash_message_success'))
			<div class="alert alert-success">

				<strong> {!! session('flash_message_success') !!} </strong>
			</div>
			@endif
			<!-- SELECT2 EXAMPLE -->
			<form action="{{ url('/add-sale') }}" method="post" id="purchaseForm">
				@csrf
				<div class="card card-default">

					<div class="card-header">
						<a href="{{ url('/sales') }}" class="btn btn-block btn-success" style="width: 150px; display: inline-block;"><i  class="fa fa-list"></i>&nbsp;&nbsp;Sales List</a>

						<div class="card-tools">
							<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
							<button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<!-- /.card-header -->
					<div class="card-body">
						

						<!--Row Strat----->
						<div class="row"> 
							<!-- <div class="col-md-6"> -->
								<div class="form-group col-sm-12 col-md-2">
									<label for="name">Invoice No# <font style="color: red;">*</font></label>
									<input type="text" class="form-control" name="invoice_no" id="invoice_no" value="{{ $invoice_no }}" readonly="" style="background-color: #D8FDBA">
									<font style="color: red;">
										{{($errors->has('invoice_no'))?($errors->first('invoice_no')):''}}
									</font>
								</div>
								
								<div class="form-group col-sm-12 col-md-3">
									<label for="date">Date <font style="color: red;">*</font></label>
									
									<input type="text" class="form-control datepicker" name="date" id="datepicker" placeholder="DD-MM-YYYY" value="" placeholder="DD-MM-YYYY">
									<font style="color: red;">
										{{($errors->has('date'))?($errors->first('date')):''}}
									</font>
								</div>
								
								<div class="form-group col-sm-12 col-md-3">
									<label for="supplier">Customer <font style="color: red;">*</font></label>
									<div class="input-group mb-4">
										<input type="text" class="form-control" placeholder="Search Customer" name="customerName" id="customerName">
										<input type="hidden" name="customer_id" id="customer_id">
										<input type="hidden" name="area_id" id="area_id">
										
									</div>
								</div>
								<div class="form-group col-sm-12 col-md-3">
									<label for="name">Balance</label>
									<input type="text" class="form-control" id="chkCustomerBalance" readonly="" style="background-color: #D8FDBA">
								</div>
								<!-- <div class="form-group col-sm-12 col-md-3"> -->
									
									
									
							<!-- <div class="col-sm-10 text-right">
								<label for="date">Previous Amount </label>
								<div class="col-sm-10">
								<div class="icheck-success d-inline">
									<input type="checkbox" id="checkboxSuccess2" name="pre_check" value="1">
									<label for="checkboxSuccess2">
									</label>
								</div>
							</div>
							</div> -->
								<!-- </div> -->
								<div class="form-group col-sm-12 col-md-12">
									<label for="details">Description </label>
									<textarea class="form-control form-control-sm" id="details" name="description" rows="3"></textarea>
								</div>
								

								<!-- /.col -->
							</div>
							<!--Row Strat----->
						</div>
						<!-- /.card-body -->
					</div>

					<div class="card-body">

						<table class="table-sm table-bordered table-responsive" width="100%">
							<thead class="bg-blue">
								<tr>
									<th>Product</th>
									<th>Unit</th>
									<th>Selling Price</th>
									<th>Quantity</th>
									<th width="10%">Item Total</th>
									<th width="10%">Action</th>
								</tr>
							</thead>
							<tbody id="addRow" class="field_wrapper">
								<tr class="delete_add_more_item" id="delete_add_more_item">

									<td>
										<input type="text" name="productName[]" class="form-control ProdcutName" id="ProdcutName" placeholder="search product...">
										<input type="hidden" name="product_id[]" id="productID" class="productID">
										<input type="hidden" name="productOldQty[]" id="productQty" class="productQty">
										<input type="hidden" name="cost[]" id="cost" class="cost">
										<input type="hidden" name="calculatedCost[]" id="calculatedCost" class="calculatedCost">
									</td>
									<td>
										<input type="text" name="unit[]" id="unit" class="form-control unit" readonly="" style="background-color: #D8FDBA">
									</td>
									<td>
										<input type="text" name="selling_price[]" class="form-control selling_price text-right">

									</td>
									<td>
										<input type="text" name="quantity[]" class="form-control quantity text-right">

									</td>

									<td>
										<input class="form-control text-right amount" name="amount[]">
									</td> 
									<td><i class="btn btn-success btn-sm fas fa-plus-square add_button"></i> | <i class="btn btn-danger btn-sm fa fa-window-close remove_button"></i> </td>
								</tr>
							</tbody>
							<tbody>
								<!-- <tr style="display: none;" class="previous_amount">
									
									<td class="text-right" colspan="4"><strong>Previous Amount:</strong></td>
									<td>
										<input type="text" name="previous_amount" id="previous_amount" class="form-control form-control-sm text-right previous_amount">
										
									</td>
									<td></td>
								</tr> -->
								
								<tr>
									
									<td class="text-right" colspan="4"><strong>Discount:</strong></td>
									<td>
										<input type="text" name="discount" value="0" id="discount" class="form-control form-control-sm text-right discount">
										
									</td>
									<td></td>
								</tr>
								<tr>
									
									<td class="text-right" colspan="4"><strong>Total:</strong></td>
									<td>
										<input type="text" name="total_amount" value="0.00" id="total_amount" class="form-control form-control-sm text-right total_amount" readonly="" style="background-color: #D8FDBA">
										
									</td>
									<td></td>
								</tr>
								
								

							</tbody>
						</table>
						<br>
						<div class="form-group">
							<button type="submit" class="btn btn-success" id="storeButton" style="float: right;">Add Sale</button>
						</div>
					</form>
				</div>
			</div><!-- /.container-fluid -->
			
		</section>
		<!-- /.content -->
	</div>
	


	<script type="text/javascript">

		
		// Add Remove Multiple Fields....

		$(document).ready(function(){
    var maxField = 100; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector

    var wrapper = $('.field_wrapper' ); //Input field wrapper
    var fieldHTML = '<tr class="delete_add_more_item" id="delete_add_more_item"><td><input type="text" name="productName[]" class="form-control ProdcutName" id="ProdcutName" placeholder="search product..."><input type="hidden" name="product_id[]" id="productID" class="productID"><input type="hidden" name="productOldQty[]" id="productQty" class="productQty"><input type="hidden" name="cost[]" id="cost" class="cost"><input type="hidden" name="calculatedCost[]" id="calculatedCost" class="calculatedCost"></td><td><input type="text" name="unit[]" id="unit" class="form-control unit" readonly="" style="background-color: #D8FDBA"></td><td><input type="text" name="selling_price[]" class="form-control selling_price text-right"></td><td><input type="text" name="quantity[]" class="form-control quantity text-right"></td><td><input class="form-control text-right amount" name="amount[]"></td><td><i class="btn btn-danger btn-sm fa fa-window-close remove_button"></i></td></tr>'; //New input field html 
    var x = 1; //Initial field counter is 1
    
    //Once add button is clicked
    $(addButton).click(function(){
        //Check maximum number of input fields
        if(x < maxField){ 
            x++; //Increment field counter

            $(wrapper).append(fieldHTML); //Add field html
        }
        searchItems();
    });
    $(wrapper).on("click", ".remove_button", function(event){
    	$(this).closest(".delete_add_more_item").remove();
    	totalPurchaseAmount(); 
    });
    $(document).on('keyup click', '.amount',function(){

    	totalPurchaseAmount();
    });

			// calculate sum of amount in invoice
			function totalPurchaseAmount(){
				var sum=0;
				var due_amount = 0;
				$(".amount").each(function(){
					var value = $(this).val();
					var discount = $(".discount").val();
					var paid_amount = $(".paid_amount").val();
					
					if(!isNaN(value) && value.length !=0) {
						sum += parseFloat(value);
						afterDiscount_total = sum - discount;
					}
				});
				$('#total_amount').val(sum);
				$('#total_amount').val(afterDiscount_total);
			}
			searchItems();
			function searchItems(){
				$(".ProdcutName").autocomplete({

					source: "/search-sale-product",
					minLength: 1,
					select: function (event, ui) {
						var item = ui.item;
						if (item) {
							$(this).closest('.delete_add_more_item').find("input.ProdcutName").val(item.productName);
							$(this).closest('.delete_add_more_item').find("input.productID").val(item.productID);
							$(this).closest('.delete_add_more_item').find("input.productQty").val(item.productQty);
							$(this).closest('.delete_add_more_item').find("input.selling_price").val(item.sellingPrice);
							$(this).closest('.delete_add_more_item').find("input.unit").val(item.productUnit);
							$(this).closest('.delete_add_more_item').find("input.cost").val(item.cost);
						}
					}
				});
			}

			$(document).on('keyup click', '.selling_price,.discount,.quantity,.paid_amount',function(){
				var cost = $(this).closest("tr").find("input.cost").val();
				var selling_price = $(this).closest("tr").find("input.selling_price").val();
				var discount = $(this).closest("tr").find("input.discount").val();
				var quantity = $(this).closest("tr").find("input.quantity").val();
				var paid_amount = $(this).closest("tr").find("input.paid_amount").val();
				var total = (quantity * selling_price);
				var costCalculate = quantity * cost;
				$(this).closest("tr").find("input.amount").val(total);
				$(this).closest("tr").find("input.calculatedCost").val(costCalculate);
				totalPurchaseAmount();
			});

			$(document).on('keyup click', '.quantity',function(){
				var quantity = $(this).closest("tr").find("input.quantity").val();
				var productID = $(this).closest("tr").find("input.productID").val();
				
				$.ajax({
					url: "/check-prodcut-stock/",
					type: 'GET',
					data: {
						quantity:quantity,
						productID:productID
					},
					success: function (response) {
						console.log(response);
						
						if (response.error==true) {
							$.notify(response.message, {globalPosition: 'top right',className: 'error'});
						}


					}
				});

			});

		});


	</script>
	<!-- Extra Add Exist Item ----------->


	<script type="text/javascript">
		$(document).ready(function () {

			$('#purchaseForm').validate({
				rules: {
					customerName: {
						required: true,
					},
					date: {
						required: true,
					},
					terms: {
						required: true
					},
				},
				messages: {
					
				},
				errorElement: 'span',
				errorPlacement: function (error, element) {
					error.addClass('invalid-feedback');
					element.closest('.form-group').append(error);
				},
				highlight: function (element, errorClass, validClass) {
					$(element).addClass('is-invalid');
				},
				unhighlight: function (element, errorClass, validClass) {
					$(element).removeClass('is-invalid');
				}
			});
		});
	</script>
	<script type="text/javascript">
		$(document).on('change','#paid_status',function(){
			var paid_status = $(this).val();
			if(paid_status=='partial_paid'){
				$('#paid_amount').show();
			}else{
				$('#paid_amount').hide();
			}
		});
		$(document).on('click','#checkboxSuccess2',function(){
			// alert('okk');
			var value = $(this).val();
			// alert(value);
			if (value==1) {
				$('.previous_amount').show();
				$('#checkboxSuccess2').val(0);
			}else{
				$('.previous_amount').hide();
				$('#checkboxSuccess2').val(1);
			}
			
			// $('#previous_amount').closest("tr").find("input.previous_amount").show();
		});
		
	</script>
	@endsection
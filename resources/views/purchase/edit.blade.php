@extends('layouts.layout')
@section('title', '| Edit Purchase')
@section('content')



<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Edit Purchase</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{{ url('/admin/dashboard')}}">Dashboard</a></li>
						<li class="breadcrumb-item active">Edit Purchase</li>
					</ol>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			
			<!-- SELECT2 EXAMPLE -->
			<form action="{{ url('/edit-purchase/'.$editPurchase['id']) }}" method="post" id="purchaseForm">
				@csrf
				<div class="card card-default">

					<div class="card-header">
						<a href="{{ url('/purchase-orders') }}" class="btn btn-block btn-success" style="width: 150px; display: inline-block;"><i  class="fa fa-list"></i>&nbsp;&nbsp;Purchase List</a>

						<div class="card-tools">
							<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
							<button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<!-- /.card-header -->
					<div class="card-body">
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

						<!--Row Strat----->
						<div class="row"> 
							<!-- <div class="col-md-6"> -->
								<div class="form-group col-sm-12 col-md-3">
									<label for="name">Purchase No# <font style="color: red;">*</font></label>
									<input type="text" class="form-control" name="purchase_no" placeholder="Purchase  Number" id="purchase_no" value="{{ $editPurchase['purchase_no'] }}" readonly="" style="background-color: #D8FDBA">
									<font style="color: red;">
										{{($errors->has('name'))?($errors->first('name')):''}}
									</font>
								</div>
								
								<div class="form-group col-sm-12 col-md-3">
									<label for="date">Date <font style="color: red;">*</font></label>
									<input type="text" name="date" id="datepicker" class="form-control" value="{{date('d-m-Y',strtotime($editPurchase['date']))}}">
									<font style="color: red;">
										{{($errors->has('date'))?($errors->first('date')):''}}
									</font>
								</div>
								<div class="form-group col-sm-12 col-md-3">
									<label for="supplier">Supplier <font style="color: red;">*</font></label>
									<div class="input-group mb-4">
										<input type="text" class="form-control" placeholder="search supplier..." name="supplierName" id="supplierName" value="{{$editPurchase['suppliers']['name']}}">
										<input type="hidden" name="supplier_id" id="supplier_id" value="{{$editPurchase['supplier_id']}}">
									</div>
								</div>
								<div class="form-group col-sm-12 col-md-3">
									<label for="status">Satus <font style="color: red;">*</font></label>
									
									<select name="status" id="status" class="form-control select2" style="width: 100%;">
										<option selected="selected" disabled="true">Select Status</option>
										<option value="cancel" {{($editPurchase['status']=='cancel')?'selected':''}}>Cancel</option>
										<option value="received" {{($editPurchase['status']=='received')?'selected':''}}>Received</option>
									</select>
									<font style="color: red;">
										{{($errors->has('status'))?($errors->first('status')):''}}
									</font>
								</div>
								<div class="form-group col-sm-12 col-md-12">
									<label for="details">Description </label>
									<textarea class="form-control form-control-sm" id="details" name="description" rows="3">{{$editPurchase['description']}}</textarea>
								</div>
								

								<!-- /.col -->
							</div>
							<!--Row Strat----->
						</div>
						<!-- /.card-body -->
					</div>

					<div class="card-body">

						<table class="table-sm table-bordered" width="100%">
							<thead class="bg-blue">
								<tr>
									<th>Product</th>
									<th>Unit</th>
									<th>Unit Price</th>
									<th>Quantity</th>
									<th width="10%">Item Total</th>
									<th width="10%">Action</th>
									
								</tr>
							</thead>
							<tbody id="addRow" class="field_wrapper">
								@foreach($itemAddons as $item)
								<tr class="delete_add_more_item" id="delete_add_more_item">

									<td>
										<input type="text" name="productName[]" class="form-control purchaseProdcutName" value="{{$item['productName']}}" id="purchaseProdcutName">
										<input type="hidden" name="product_id[]" id="productID" value="{{$item['product_id']}}" class="productID">
										
										<input type="hidden" name="stockQty[]" id="stockQty" class="stockQty" value="{{$item['stockQty']}}">
									</td>
									<td>
										<input type="text" name="unit[]" id="unit" value="{{$item['unit']}}" class="form-control unit">
									</td>
									<td>
										<input type="text" name="price[]" value="{{$item['price']}}" class="form-control price">

									</td>
									<td>
										<input type="text" name="quantity[]" value="{{$item['quantity']}}" class="form-control quantity">

									</td>

									<td>
										<input class="form-control text-right amount" name="item_amount[]" readonly="" value="{{$item['amount']}}">
									</td> 
									<td><i class="btn btn-success btn-sm fas fa-plus-square add_button"></i> | <i class="btn btn-danger btn-sm fa fa-window-close remove_button"></i> </td>
									
								</tr>
								@endforeach
							</tbody>
							<tbody>
								<tr>
									
									<td class="text-right" colspan="4"><strong>Discount:</strong></td>
									<td>
										<input type="text" name="discount" value="{{$editPurchase['discount']}}" id="discount" class="form-control form-control-sm text-right discount">
										
									</td>
									<td></td>
								</tr>
								<tr>
									
									<td class="text-right" colspan="4"><strong>Total Amount:</strong></td>
									<td>
										<input type="text" name="amount" value="{{$editPurchase['amount']}}" id="total_amount" class="form-control form-control-sm text-right total_amount" readonly="" style="background-color: #D8FDBA">
										
									</td>
									<td></td>
								</tr>

							</tbody>
						</table>
						
						<br>
						<div class="form-group">
							<button type="submit" class="btn btn-success" id="storeButton" style="float: right;">Update Purchase</button>
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
    var fieldHTML = '<tr class="delete_add_more_item" id="delete_add_more_item"><td><input type="text" name="productName[]" class="form-control purchaseProdcutName" id="purchaseProdcutName" placeholder="search product..."></td><input type="hidden" name="product_id[]" id="productID" class="productID"><input type="hidden" name="stockQty[]" id="stockQty" class="stockQty"><td><input type="text" name="unit[]" id="unit" class="form-control unit"></td><td><input type="text" name="price[]" class="form-control price"></td><td><input type="text" name="quantity[]" class="form-control quantity"></td><td><input class="form-control text-right amount" name="item_amount[]"></td><td><i class="btn btn-danger btn-sm fa fa-window-close remove_button"></i></td></tr>'; //New input field html 
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
				$(".amount").each(function(){
					var value = $(this).val();
					var discount = $(".discount").val();
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
    			$(".purchaseProdcutName").autocomplete({

    				source: "/search-raw-product",
    				minLength: 1,
    				select: function (event, ui) {
    					var item = ui.item;
    					if (item) {
    						$(this).closest('.delete_add_more_item').find("input.purchaseProdcutName").val(item.productName);
    						$(this).closest('.delete_add_more_item').find("input.productID").val(item.productID);
    						$(this).closest('.delete_add_more_item').find("input.unit").val(item.productUnit);
    						$(this).closest('.delete_add_more_item').find("input.price").val(item.purchasePrice);
    						$(this).closest('.delete_add_more_item').find("input.stockQty").val(item.quantity);
    					}
    				}
    			});
    		}

			$(document).on('keyup click', '.price,.discount,.quantity',function(){
				var price = $(this).closest("tr").find("input.price").val();
				var discount = $(this).closest("tr").find("input.discount").val();
				var quantity = $(this).closest("tr").find("input.quantity").val();
				var total = price * quantity;
				$(this).closest("tr").find("input.amount").val(total);
				totalPurchaseAmount();
			});

		
            
});


</script>
<!-- Extra Add Exist Item ----------->


	<script type="text/javascript">
		$(document).ready(function () {

			$('#purchaseForm').validate({
				rules: {
					purchase_no: {
						required: true,
					},
					supplier_id: {
						required: true,
					},
					date: {
						required: true,
					},
					status: {
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
	@endsection
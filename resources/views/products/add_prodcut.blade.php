@extends('layouts.layout')
@section('title', '| Add Product')
@section('content')


<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
				</div>
				
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{{ url('/home')}}">Dashboard</a></li>
						<li class="breadcrumb-item active">Add Product</li>
					</ol>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid col-md-10">
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
			<form name="gateForm" id="gateForm" action="{{(@$editProduct)?url('edit-product/'.$editProduct['id']):url('/add-product') }}"  method="post">
				@csrf
				
				
				<!-- Start another Card Here--------->
				<div class="card card-dark">
					<div class="card-header">
						<h3 class="card-title">Add Product</h3>
						<a href="{{ url('/products') }}" class="btn btn-block btn-warning btn-sm" style="width: 120px; float: right; display: inline-block; color: black;"><i  class="fa fa-list"></i>&nbsp;&nbsp; Products List</a>
					</div>
					<!-- /.card-header -->
					<!-- form start -->
					<form class="form-horizontal">
						<div class="card-body box-profile">
							<div class="form-group row">
								<label for="inputProductName" class="col-sm-2 col-form-label">Product Name <font style="color: red;">*</font></label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="name" placeholder="Enter Product Name" id="name" value="{{(@$editProduct)?$editProduct['name']:old('name') }}" required="">
								</div>
							</div>
							<div class="form-group row">
								<label for="inputUnits" class="col-sm-2 col-form-label">Units <font style="color: red;">*</font></label>
								<div class="col-sm-10">
									<select name="unit_id" id="unit_id" class="form-control select2" required="" style="width: 100%;">
										<option selected="selected" readonly="" value="0">Select Unit</option>
										@foreach($units as $unit)
										<option value="{{$unit['id']}}" {{(@$editProduct['unit_id']==$unit['id'])?'selected':''}}>{{$unit['name']}}</option>
										@endforeach

									</select>

								</div>
							</div>
							
							<div class="form-group row">
								<label for="inputProductCost" class="col-sm-2 col-form-label">Purchase Price <font style="color: red;">*</font></label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="cost" placeholder="Purchase Price" id="cost" value="{{(@$editProduct)?$editProduct['cost']:old('cost') }}">
								</div>
							</div>
							<div class="form-group row">
								<label for="inputSellingPrice" class="col-sm-2 col-form-label">Selling Price <font style="color: red;">*</font></label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="selling_price" placeholder="Selling Price" id="selling_price" value="{{(@$editProduct)?$editProduct['selling_price']:old('selling_price') }}">
								</div>
							</div>
							<div class="form-group row">
								<label for="quantity" class="col-sm-2 col-form-label">Quantity</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="quantity" placeholder="Quantity" id="quantity" value="{{(@$editProduct)?$editProduct['quantity']:old('quantity') }}">
								</div>
							</div>
							<div class="form-group row">
								<label for="product_code" class="col-sm-2 col-form-label">Product Code <font style="color: red;">*</font></label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="product_code" placeholder="Product Code" id="product_code" required="" value="{{(@$editProduct)?$editProduct['product_code']:old('product_code') }}">
								</div>
							</div>
						</div>
						<!-- /.card-body -->
						<div class="card-footer">
							<button type="submit" class="btn btn-info">{{ $title }}</button>
							
							<a href="{{'/dashboard'}}"  class="btn btn-warning float-right">Cancel</a>
						</div>
						<!-- /.card-footer -->
					</form>
				</div>
			</form>
		</div><!-- /.container-fluid -->
	</section>
	<!-- /.content -->
</div>

<!-- <script type="text/javascript">
	$(document).ready(function () {

		$('#gateForm').validate({
			rules: {
				name: {
					required: true
				},
				unit_id: {
					required: true
				}
			},
			messages: {
				name: {
					required: "Please enter Name",
				},
				unit_id: {
					required: "Please select unit",
				}
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
</script> -->
@endsection
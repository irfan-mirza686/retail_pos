@extends('layouts.layout')
@section('title', '| Edit-Customer')
@section('content')


<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Edit Customer</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{{ url('/dashboard')}}">Dashboard</a></li>
						<!-- <li class="breadcrumb-item active">Edit Customer</li> -->
					</ol>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			
			<!-- SELECT2 EXAMPLE -->
			<form name="customerForm" id="customerForm" action="{{ url('edit-customer/'.$editCustomer['id']) }}"  method="post">
				@csrf
				
				<div class="card card-default">
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
					
					<div class="card-header">
						<a href="{{ url('/customers') }}" class="btn btn-block btn-success btn-sm" style="width: 150px; display: inline-block;"><i  class="fa fa-list"></i>&nbsp;&nbsp;Customers List</a>
						
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
								<div class="form-group col-sm-12 col-md-3">
									<label for="name">Name</label>
									<input type="text" class="form-control" name="name" placeholder="Enter Customer Name" id="name" value="{{ $editCustomer['name'] }}" required="">
									<font style="color: red;">
										{{($errors->has('name'))?($errors->first('name')):''}}
									</font>
								</div>
								<div class="form-group col-sm-12 col-md-3">
									<label for="mobile">Mobile</label>
									<input type="text" class="form-control" value="{{ $editCustomer['mobile'] }}" name="mobile">
								</div>
								<div class="form-group col-sm-12 col-md-3">
									<label for="cnic">CNIC</label>
									<input type="text" class="form-control" name="cnic" value="{{ $editCustomer['cnic'] }}">
								</div>
								<div class="form-group col-sm-12 col-md-3">
									<label for="area">Area <font style="color: red;">*</font></label>
									<select name="area_id" id="area_id" class="form-control select2" style="width: 100%;">
										<option selected="selected" disabled="true">-Select-</option>
										@foreach($areas as $area)
										<option value="{{$area['id']}}" {{(@$editCustomer['area_id']==$area['id'])?'selected':''}}>{{$area['name']}}</option>
										@endforeach
									</select>
									<font style="color: red;">
										{{($errors->has('name'))?($errors->first('name')):''}}
									</font>
								</div>
								<!-- /.col -->
							</div>
							<div class="row">
								<div class="form-group col-sm-12 col-md-12">
									<label for="address">Address</label>
									<textarea class="form-control" id="description" name="address" rows="3" placeholder="Enter Customer Address">{{ $editCustomer['address'] }}</textarea>
								</div>
							</div>
							<!--Row Strat----->
						</div>
						<!-- /.card-body -->
						<div class="card-footer">
							<div>
								<button type="submit" class="btn btn-success" style="float: right;">Update Customer</button>
							</div>
							<div style="margin-right: 150px;">
								<a href="{{'/customers'}}"  class="btn btn-warning" style="float: right;">Cancel</a>
							</div>
						</div>
					</div>
				</form>
			</div><!-- /.container-fluid -->
		</section>
		<!-- /.content -->
	</div>

	<script type="text/javascript">
		$(document).ready(function () {

			$('#customerForm').validate({
				rules: {
					name: {
						required: true
					},
					
				},
				messages: {
					name: {
						required: "Please enter Name",
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
	</script>
	@endsection
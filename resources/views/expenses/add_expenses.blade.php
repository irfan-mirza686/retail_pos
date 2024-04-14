@extends('layouts.layout')
@section('title', '| Add Expense')
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
						<li class="breadcrumb-item active">Add Expense</li>
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
			<form name="gateForm" class="form-horizontal" id="gateForm" action="{{ url('add-expense') }}"  method="post">
				@csrf
				
				<!-- Start another Card Here--------->
				<div class="card card-dark">
					<div class="card-header">
						<h3 class="card-title">Add Expense</h3>
						<a href="{{ url('/expenses') }}" class="btn btn-block btn-warning btn-sm" style="width: 120px; float: right; display: inline-block; color: black;"><i  class="fa fa-list"></i>&nbsp;&nbsp; Expense List</a>
					</div>
					<!-- /.card-header -->
					<!-- form start -->
					
					<div class="card-body box-profile">
						<div class="row">
							<div class="col-md-8">
								<div class="form-group row">
									<label for="inputDate" class="col-sm-2 col-form-label">Date <font style="color: red;">*</font></label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="date" id="datepicker" placeholder="DD-MM-YYYY">
									</div>
								</div>
								
								<div class="form-group row">
									<label for="inputCategory" class="col-sm-2 col-form-label">Category <font style="color: red;">*</font></label>
									<div class="col-sm-10">
										<select name="exp_category_id" id="exp_category_id" class="form-control select2" style="width: 100%;">
											<option selected="selected" disabled="true">-Select-</option>
											@foreach($expCategories as $category)
											<option value="{{$category['id']}}">{{$category['name']}}</option>
											@endforeach

										</select>
									</div>
									
								</div>
								<div class="form-group row">
								<label for="inputExpenseFor" class="col-sm-2 col-form-label">Expense For <font style="color: red;">*</font></label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="expense_for" placeholder="Expense For" id="expense_for" value="{{ old('expense_for') }}" required="">
								</div>
							</div>
								<div class="form-group row">
									<label for="inputAmount" class="col-sm-2 col-form-label">Amount <font style="color: red;">*</font></label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="amount" placeholder="Search Pathair Employee" id="amount" value="{{ old('amount') }}" required="">
									</div>
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group row">
									<label for="inputEmployee" class="col-sm-2 col-form-label">Note </label>
									<div class="col-sm-10">
										<textarea class="form-control form-control-sm" id="note" name="note" rows="3"></textarea>
									</div>
								</div>
							</div>
						</div>
						
						
						
						
					</div>
					<!-- /.card-body -->
					<div class="card-footer">
						<button type="submit" class="btn btn-info">Add Expense</button>

						<a href="{{'/dashboard'}}"  class="btn btn-warning float-right">Cancel</a>
					</div>
					<!-- /.card-footer -->
					
				</div>
			</form>
		</div><!-- /.container-fluid -->
	</section>
	<!-- /.content -->
</div>

<script type="text/javascript">
	$(document).ready(function () {

		$('#gateForm').validate({
			rules: {
				gate_id: {
					required: true
				},
				date: {
					required: true
				},
				terms: {
					required: true
				},
			},
			messages: {
				gate_id: {
					required: "Please enter Name",
				},
				date : {
					required : 'Please select date',
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
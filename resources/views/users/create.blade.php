@extends('layouts.layout')
@section('title', '| Add Edit Admin')
@section('content')

<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>
						@if(isset($editAdmin))
						Update Admin
						@else
						Manage Admin
						@endif
					</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{{ url('/home')}}">Dashboard</a></li>
						<li class="breadcrumb-item active">Manage Admin</li>
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
			<!-- For Laravel Builtin Validations------------>
			@if ($errors->any())
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
			@endif
			<!-- SELECT2 EXAMPLE -->
			<form name="userForm" id="userForm" action="{{(@$editAdmin)?url('edit-user/'.$editAdmin['id']):url('add-user') }}"  method="post" enctype="multipart/form-data">
				@csrf
				<input type="hidden" name="id" value="{{@$editAdmin['id']}}">
				<div class="card card-default">

					<div class="card-header">
						<a href="{{ url('/admin/admins') }}" class="btn btn-block btn-success" style="width: 150px; display: inline-block;"><i  class="fa fa-list"></i>&nbsp;&nbsp;Admins List</a>
						<div class="card-tools">
							<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
							<button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<!-- /.card-header -->
					<div class="card-body">
						<!--Row Strat----->
						<div class="form-row"> 
							
							<!-- <div class="col-md-6"> -->
								<div class="form-group col-sm-12 col-md-4">
									<label for="name">Name <font style="color: red;">*</font></label>
									<input type="text" class="form-control" name="name" placeholder="Enter Name" id="name" value="{{(@$editAdmin)?$editAdmin['name']:old('name') }}">
								</div>
								@if(@$editAdmin['email'])
								<div class="form-group col-sm-12 col-md-4">
									<label for="email">Email <font style="color: red;">*</font></label>
									<div class="input-group mb-3">
										<div class="input-group-prepend">
											<span class="input-group-text">@</span>
										</div>
										<input type="email" class="form-control" name="email" id="email" disabled="true" placeholder="Email" value="{{(@$editAdmin)?$editAdmin['email']:old('email') }}">
										
									</div>
								</div>
								<div class="form-group col-sm-12 col-md-4">
									<label for="mobile">Mobile No</label>
									<input type="text" class="form-control" name="mobile" placeholder="Enter Mobile No" id="mobile" value="{{(@$editAdmin)?$editAdmin['mobile']:old('mobile') }}">
								</div>
								@else
								<div class="form-group col-sm-12 col-md-4">
									<label for="email">Email <font style="color: red;">*</font></label>
									<div class="input-group mb-3">
										<div class="input-group-prepend">
											<span class="input-group-text">@</span>
										</div>
										<input type="email" class="form-control" name="email" id="email" placeholder="Email" value="{{(@$editAdmin)?$editAdmin['email']:old('email') }}">
										
									</div>
								</div>
								<div class="form-group col-sm-12 col-md-4">
									<label for="mobile">Mobile No</label>
									<input type="text" class="form-control" name="mobile" placeholder="Enter Mobile No" id="mobile" value="{{(@$editAdmin)?$editAdmin['mobile']:old('mobile') }}">
								</div>
								@endif
								@if(!@$editAdmin)
								<div class="form-group col-sm-12 col-md-4">
									<label for="password">Password <font style="color: red;">*</font></label>
									<div class="input-group mb-3">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fas fa-lock"></i></span>
										</div>
										<input type="password" class="form-control" name="password" id="password" placeholder="Password">

									</div>
								</div>
								<div class="form-group col-sm-12 col-md-4">
									<label for="confirm_password">Confirm Password <font style="color: red;">*</font></label>
									<div class="input-group mb-3">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fas fa-lock"></i></span>
										</div>
										<input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm Password">

									</div>
								</div>
								@endif

								<div class="form-group col-sm-12 col-md-4">
								<label for="inputUnits" class="col-sm-2 col-form-label">Roles <font style="color: red;">*</font></label>
								<div class="col-sm-10">
									<select name="group_id" id="group_id" class="form-control select2" required="" style="width: 100%;">
										<option selected="selected" readonly="" value="0">-select-</option>
										@foreach($groups as $group)
										<option value="{{$group['id']}}" {{(@$editAdmin['group_id']==$group['id'])?'selected':''}}>{{$group['name']}}</option>
										@endforeach

									</select>

								</div>
							</div>
								
								<div class="form-group col-sm-12 col-md-8">
									<label for="address">Address </label>
									<textarea class="form-control form-control-sm" id="address" name="address" rows="3">{{(@$editAdmin)?$editAdmin['address']:old('address') }}</textarea>
								</div>
								
							
								
								
							</div>
							<!--Row Strat----->
						</div>
						<!-- /.card-body -->
						<div class="card-footer">
							<div>
								<button type="submit" class="btn btn-success btn-sm" style="float: right;">{{(@$editAdmin)?'Update Admin':'Create Admin'}}</button>
							</div>
							<div style="margin-right: 150px;">
								<a href="{{'/home'}}"  class="btn btn-warning btn-sm" style="float: right;">Cancel</a>
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

			$('#userForm').validate({
				rules: {
					gender: {
						required: true,
					},
					religion: {
						required: true,
					},
					name: {
						required: true,
					},
					fname: {
						required: true,
					},
					cnic: {
						required: true,
					},
					mobile: {
						required: true,
					},
					
					address: {
						required: true,
					},
					
					dob: {
						required: true,
					},
					salary: {
						required: true,
					},
					join_date: {
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
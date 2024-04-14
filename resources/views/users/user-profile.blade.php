@extends('layouts.layout')
@section('title', '| User Profile')
@section('content')



<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				
				<div class="col-sm-12">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
						<li class="breadcrumb-item active">Profile</li>
					</ol>
				</div>
				<div class="col-sm-12">
					<h1 style="text-align: center;">Manage Profile</h1>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-12 offset-md-4">


				<!-- Profile Image -->
					<div class="card card-primary card-outline col-md-4">
						<div class="card-body box-profile">
							<div class="text-center">
								<img class="profile-user-img img-fluid img-circle"
								src="{{asset('images/avatar5.png')}}"
								alt="User profile picture" style="height: 75px; width: 80px;">
							</div>

							<h3 class="profile-username text-center">{{$user['name']}}</h3>

							<p class="text-muted text-center">Admin</p>

							<table style="width: 100%%;" class="table table-bordred">
								<tbody>
									<tr>
										<td><strong>Email</strong></td>
										<td>{{$user['email']}}</td>
									</tr>
									<tr>
										<td><strong>Mobile</strong></td>
										<td>{{$user['mobile']}}</td>
									</tr>
									<tr>
										<td><strong>Gender</strong></td>
										<td>{{$user['gender']}}</td>
									</tr>
									<tr>
										<td><strong>Address</strong></td>
										<td>{{$user['address']}}</td>
									</tr>
								</tbody>
							</table>

							<a href="{{ url('/edit-user-profile/'.$user['id']) }}" class="btn btn-primary btn-block"><b>Edit Profile</b></a>
						</div>
						<!-- /.card-body -->
					</div>
					<!-- /.card -->
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</section>
	<!-- /.content -->
</div>
@endsection
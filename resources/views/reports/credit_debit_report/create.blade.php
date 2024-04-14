@extends('layouts.layout')
@section('title', '| Credit/Debit Report')
@section('content')

<style type="text/css">
.customerLoader {
	display: none;
	margin: 0;
	position: absolute;
	top: 50%;
	left: 50%;
	margin-right: -50%;
	transform: translate(-50%, -50%);
}
</style>

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
						<li class="breadcrumb-item active">Credit/Debit Report</li>
					</ol>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			
			<!-- SELECT2 EXAMPLE -->
			<form name="gateForm" id="gateForm" action="{{ url('/report-credit-debit-pdf') }}"  method="post">
				@csrf
				
				
				<!-- Start another Card Here--------->
				<div class="card card-dark">
					<div class="card-header">
						<h3 class="card-title">Credit/Debit Report</h3>
					</div>
					<!-- /.card-header -->
					<!-- form start -->
					<div class="card-body box-profile">
						
						
						<div class="form-group row">
							<label for="saleType" class="col-sm-2 col-form-label">Report Type</label>
							<div class="col-sm-10 col-md-4">
								<select name="sale_type" id="sale_type" class="form-control select2" style="width: 100%;">
									<option selected="selected" disabled="true">-Select-</option>
									<option value="customerWise">Customer Wise</option>
									<option value="areaWise">Area Wise</option>
								</select>
								
							</div>
						</div>
						<!-- <div class="form-group row customerWise" style="display: none;">
							<label for="inputSupplier" class="col-sm-2 col-form-label">Customers</label>
							<div class="col-sm-10 col-md-4">
								<input type="text" class="form-control" placeholder="search Customer" name="customerName" id="customerName">
										<input type="hidden" name="customer_id" id="customer_id">
										<input type="hidden" name="area_id" id="area_id">
							</div>
						</div> -->
						<!-- <div class="form-group row customerWise" style="display: none;">
									<label for="supplier">Customer <font style="color: red;">*</font></label>
									<div class="input-group mb-4">
										<input type="text" class="form-control" placeholder="search Customer" name="customerName" id="customerName">
										<input type="hidden" name="customer_id" id="customer_id">
										<input type="hidden" name="area_id" id="area_id">
										
									</div>
								</div> -->

								<div class="form-group row customerWise" style="display: none;">
									<label for="customer" class="col-sm-2 col-form-label">Customers</label>
									<div class="col-sm-10 col-md-4">
										<select name="customer_id" id="customer_id" class="form-control select2" style="width: 100%;">
											<option selected="selected" disabled="true">-Select-</option>
											@foreach($customers as $customer)
											<option value="{{$customer['id']}}">{{$customer['name']}}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="form-group row customerWise" style="display: none;">
							<label for="inputFromDate" class="col-sm-2 col-form-label">From Date</label>
							<div class="col-sm-10 col-md-4">
								<input type="text" placeholder="DD-MM-YYYY" class="form-control" name="startDate" id="startDate">
							</div>
							<label for="inputToDate" class="col-sm-2 col-form-label">To Date</label>
							<div class="col-sm-10 col-md-4">
								<input type="text" placeholder="DD-MM-YYYY" class="form-control" name="endDate" id="endDate">
							</div>
						</div>
								<div class="form-group row areaWise" style="display: none;">
									<label for="area" class="col-sm-2 col-form-label">Area</label>
									<div class="col-sm-10 col-md-4">
										<select name="area_id" id="area_id" class="form-control select2" style="width: 100%;">
											<option selected="selected" disabled="true">-Select-</option>
											@foreach($areas as $area)
											<option value="{{$area['id']}}">{{$area['name']}}</option>
											@endforeach
										</select>
									</div>
								</div>


							</div>
							<!-- /.card-body -->
							<div class="card-footer">

								<button type="submit" class="btn btn-primary" id="downloadPdf" style="margin-right: 5px; float:right;">
									<i class="fas fa-download"></i> Generate PDF
								</button>
								<!-- <a class="btn btn-info" id="show_report" style=" color: white">Show Report</a> -->
								<!-- <a href="{{'/dashboard'}}"  class="btn btn-warning float-right">Cancel</a> -->
							</div>
							<!-- /.card-footer -->
						</div>

					</form>

				</div><!-- /.container-fluid -->
			</section>
			<!-- /.content -->
		</div>
		<script type="text/javascript">
			$(document).on('change','#sale_type',function(){

				var sale_type = $(this).val();
			// alert(sale_type);
			if(sale_type=='customerWise'){
				$('.customerWise').show();
				$('.areaWise').hide();

			}else if(sale_type=='areaWise'){
				$('.customerWise').hide();
				$('.areaWise').show();
			}
		});
			function checkCustomer() {
				var customer_id = $("#customer_id").val();
				if(customer_id==null){
					$.notify("Customer is required", {globalPosition: 'top right',className: 'error'});
					return false;
				}
			}
			function startDate() {
				var start_date = $("#startDate").val();
				if(start_date==''){
					$.notify("Start Date is required", {globalPosition: 'top right',className: 'error'});
					return false;
				}
			}
			function endDate() {
				var end_date = $("#endDate").val();
				if(end_date==''){
					$.notify("End Date is required", {globalPosition: 'top right',className: 'error'});
					return false;
				}
			}
			function checkArea() {
				var area_id = $("#area_id").val();
				if(area_id==null){
					$.notify("Area is required", {globalPosition: 'top right',className: 'error'});
					return false;
				}
			}
			$(document).on('click','#downloadPdf',function(){
				var sale_type = $("#sale_type").val();
				if (sale_type=='customerWise') {
					checkCustomer();
					startDate();
					endDate();
					// return false;
				}else{
					checkArea();
					
				}

			});
		</script>

		@endsection
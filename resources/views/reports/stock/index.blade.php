@extends('layouts.layout')
@section('title', '| Stock Report')
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
						<li class="breadcrumb-item active">Stock Report</li>
					</ol>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			
			<!-- SELECT2 EXAMPLE -->
			<form name="gateForm" id="gateForm" action="{{ url('/report-stock-pdf') }}"  method="post">
				@csrf
				
				
				<!-- Start another Card Here--------->
				<div class="card card-dark">
					<div class="card-header">
						<h3 class="card-title">Stock Report</h3>
					</div>
					<!-- /.card-header -->
					<!-- form start -->
					<div class="card-body box-profile">
						
						<div class="form-group row">
							<label for="inputCustomer" class="col-sm-2 col-form-label">Products</label>
							<div class="col-sm-10 col-md-4">
								<select name="product_id" id="product_id" class="form-control select2" style="width: 100%;">
									<option selected="selected" disabled="true">-Select-</option>
									<option value="all" id="all_products">All</option>
									@foreach($getProducts as $product)
									<option value="{{$product['id']}}">{{$product['name']}}</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
					<!-- /.card-body -->
					<div class="card-footer">
						<button type="button" id="show_report" class="btn btn-info">Show Report</button>
						<button type="submit" class="btn btn-primary" id="downloadPdf" style="margin-right: 5px; float:right;">
							<i class="fas fa-download"></i> Generate PDF
						</button>
					</div>
					<!-- /.card-footer -->
				</div>
				<div class="card card-dark">
					<div class="card-body">
						<div class="card-body">
							<div class="customerLoader"><img src="{{asset('images/spiner.gif')}}" style="width: 50px; height: 50px;"></div>
							<div id="DocumentResults"></div>
							<script id="document-template" type="text/x-handlebars-template">
								<table class="table-sm table-bordered table-hover" style="width: 100%">
									<thead>
										<tr style="background-color: gray; color: white;">
											@{{{thsource}}}
										</tr>
									</thead>
									<tbody>
										
											@{{{tdsource}}}
										
									</tbody>
									<tfoot>
										@{{{tfootsource}}}
									</tfoot>
								</table>
							</script>
						</div>
					</div>
				</div>
			</form>

		</div><!-- /.container-fluid -->
	</section>
	<!-- /.content -->
</div>
<script type="text/javascript">
	$(document).on('click','#show_report',function(){

		var all_products = $("#all_products").val();
		var product_id = $("#product_id").val();
		if(product_id==null){
			$.notify("Product is required", {globalPosition: 'top right',className: 'error'});
			return false;
		}
		$.ajax({ 
			url: '/get-product-stock',
			type: 'GET',
			data:{
				product_id:product_id
			},
			beforSend: function(){
				$('.customerLoader').show();
			},
			success: function(data){
				
				if (data=='false') {
					$.notify("there is no Stock data found!", {globalPosition: 'top right',className: 'error'});
			return false;
				}
				var source = $("#document-template").html();
				var template = Handlebars.compile(source);
				var html = template(data);
				$('#DocumentResults').html(html);
				$('[data-toggle="tooltip"]').tooltip();
				$('.customerLoader').hide();
			}
		});
	});
</script>

@endsection
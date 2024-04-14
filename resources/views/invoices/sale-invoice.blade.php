@extends('layouts.layout')
@section('title', '| Sale Invoice')
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
						<li class="breadcrumb-item active">Sale Invoice</li>
					</ol>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					


					<!-- Main content -->
					<div class="invoice p-3 mb-3">
						<!-- title row -->
						<div class="row">
							<div class="col-12">
								<h4>
									<i class="fas fa-globe"></i> Abid Traders, Pvt.
									<small class="float-right">Date: {{date('M d, Y',strtotime($saleInvoice['date']))}}</small>
								</h4>
							</div>
							<!-- /.col -->
						</div>
						<!-- info row -->
						<div class="row invoice-info">
							<div class="col-sm-4 invoice-col">
								From
								<address>
									<strong>{{Auth::user()->name}}, Inc.</strong><br>
									<b>Address</b>: {{Auth::user()->address}}<br>
									
									<b>Phone</b>: {{Auth::user()->mobile}}<br>
									
								</address>
							</div>
							<!-- /.col -->
							<div class="col-sm-4 invoice-col">
								Customer,
								<address>
									<strong>{{$saleInvoice['customers']['name']}}</strong><br>
									<b>Address</b>: {{(@$saleInvoice['customers']['address'])?$saleInvoice['customers']['address']:''}}<br>
									<b>Phone</b>: {{(@$saleInvoice['customers']['mobile'])?$saleInvoice['customers']['mobile']:''}}<br>
									
								</address>
							</div>
							<!-- /.col -->
							<div class="col-sm-4 invoice-col">
								<b>Invoice # {{"SN-".($saleInvoice['invoice_no'])}}</b><br>
								<br>
								<b>Order Status: </b> {{(@$saleInvoice['status']==1)?'Confirmed':'Canceled'}}<br>
								<?php $previous_balance = $customerBalance - $saleInvoice['amount']; ?>
								
								<!-- <b>Payment Due:</b> 2/22/2014<br>
								<b>Account:</b> 968-34567 -->
							</div>
							<!-- /.col -->
						</div>
						<!-- /.row -->

						<!-- Table row -->
						<div class="row">
							<div class="col-12 table-responsive">
								<table class="table table-striped table-sm">
									<thead>
										<tr>
											<th>SL #</th>
											
											<th>Product</th>
											<th class="text-center">Price</th>
											<th class="text-center">Quantity</th>
											<th class="text-right">Subtotal</th>
										</tr>
									</thead>
									<tbody>
										<?php 
										$counter = 0;
										?>
										@foreach($saleitmesAddons as $addons)
										<?php

										$counter = $counter+1;
										?>
										<tr>
											<td width="6%">{{$counter}}</td>
											<td width="8%">{{$addons['productName']}}</td>
											<td width="7%" class="text-center">{{$addons['selling_price']}}</td>
											<td width="7%" class="text-center">{{$addons['quantity']}} - {{$addons['unit']}}</td>
											<td width="7%" class="text-right">{{number_format($addons['amount'],2)}}</td>
										</tr>
										@endforeach
										@if($saleInvoice['discount']>0)
										<tr>
											<td colspan="4" style="text-align: right; background: white; font-weight: bold; color:black;">Discount
											</td>
											<td style="text-align: right; background: white; font-weight: bold; color:black;">
												{{number_format($saleInvoice['discount'],2)}}
											</td>
										</tr>
										@endif
										<tr>
											<td colspan="4" style="text-align: right; font-weight: bold;">Total
											</td>
											<td style="text-align: right; font-weight: bold;">
												{{number_format($saleInvoice['amount'],2)}}
											</td>
										</tr>
										<tr>
											<td colspan="4" style="text-align: right; background: white; font-weight: bold; color:black;">Previous Balance
											</td>
											<td style="text-align: right; background: white; font-weight: bold; color:black;">
												{{(@$previous_balance<0)?(-1)*$previous_balance. ' CR':$previous_balance. ' DB'}}
											</td>
										</tr>
										<tr>
											<td colspan="4" style="text-align: right; background: gray; font-weight: bold; color:white;">Total Balance
											</td>
											<td style="text-align: right; background: gray; font-weight: bold; color:white;">
												{{(@$customerBalance<0)?(-1)*$customerBalance. ' CR':$customerBalance. ' DB'}}
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<!-- /.col -->
						</div>
						<!-- /.row -->
						<hr>
						
						
						<div class="row">
							<!-- accepted payments column -->
							<div class="col-6">
								<table>
									<tr>
										<td colspan="12"><b>Abid Traders, </b>Pakistan (Pvt) Ltd.</td>
									</tr>
									<!-- 15nd Row -->
									<!-- 16nd Row -->
									<tr>
										<td colspan="12" style="padding-top: 20px;"> <b>Abid</b></td>
									</tr>
								</table>
							</div>
							<!-- /.col -->
							
							<!-- /.col -->
						</div>
						<!-- /.row -->

						<!-- this row will not appear when printing -->
						<div class="row no-print">
							<div class="col-12">
								<a href="{{url('/print-sale-invoice',[$saleInvoice['id']]) }}" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
								
							</div>
						</div>
					</div>
					<!-- /.invoice -->

				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</section>
</div>
@endsection
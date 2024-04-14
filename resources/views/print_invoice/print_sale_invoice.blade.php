<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
  <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0">
  	<meta http-equiv="X-UA-Compatible" content="ie=edge"> -->
  	<!-- <title>Document</title> -->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  	<style>

  	body {
  		font-family: Geneva, Verdana, sans-serif;
  	}

  	h6 {
  		font-size: 16px;
  		margin: 0;
  		margin-bottom: 10px;

  	}

  	p {
  		font-size: 14px;
  	}

  	.p-text {
  		margin: 0;
  		margin-bottom: 5px;
  		font-size: 12px;
  	}

  	.table-suply {
  		border: 1px solid black;
  		border-top: none;
  		margin-top: 20px;
  	}

  	.table-suply tr th,
  	.table-suply tr td {
  		border-top: 1px solid #000000;
  		text-align: center;
  		border-right: 1px solid #000000;
  		padding: 3px;

  	}

  	.table-suply tr th:last-child,
  	.table-suply tr td:last-child {
  		border-right: none;
  	}

  	.p-text-1 {
  		text-decoration: underline;
  		padding-top: 10px;
  	}

  	.p-text-2 {
  		padding-top: 10px;
  	}

  	.cont-challan-single {
  		float: left;
  		width: 100%;
  		border: 1px solid #000000;
  		padding: 5px 10px;
  		margin-bottom: 20px;
  	}

  	.challan-single-title {
  		float: left;
  		width: 50%;
  	}

  	.suplier {
  		width: 22%;
  		border-right: 1px solid black;
  		padding: 5px 0px;
  	}

  	.cont-suplier .challan-single-title:last-child {
  		width: 77%;
  		text-align: center;
  		padding: 5px 0px;
  	}

  	.cont-challan-single.cont-suplier {
  		padding: 0px 10px;
  	}

  	.table-suply.no-border tr td {
  		border-top: none;
  		border-right: none;
  		padding: 0px;
  	}

  	.table-suply.no-border tr th {
  		border-right: none;
  	}

  	.table-challan tr th {
  		border-top: 1px solid #000000;
  	}

  	.table-challan tr th,
  	.table-challan tr td {

  		text-align: center;
  	}

  	.table-challan tr th:first-child,
  	.table-challan tr td:first-child {
  		border-left: 1px solid #000000;
  		border-bottom: 1px solid #000000;

  	}

  	.table-challan tr th:last-child,
  	.table-challan tr td:last-child {
  		border-right: 1px solid #000000;
  		border-bottom: 1px solid #000000;

  	}

  	.table-challan tr th:last-child,
  	.table-challan tr th:first-child {
  		border-bottom: none;
  	}

  	.p-tr td {
  		padding: 0px !important;
  		padding: 3px 8px 3px 3px !important;
  		border-right: 1px solid #000000;
  		border-top: 1px solid #000;
  	}
  	.invoice-total tr td,
  	.invoice-total tr th
  	{
  		text-align: right;
  	}
  </style>
</head>


<body>

	<center style=" width: 500px; margin: 0px auto;">
		<table cellspacing="0" cellpadding="0" border="0"
		style="font-family: Geneva, Verdana,  sans-serif; font-size:12px; color:#000000; line-height: 1rem;">
		<tr>
			<td colspan="12" style=" width: 100%; padding-top: 30px;"></td>
		</tr>
		<tr>
			<td colspan="12" width="100%">
				<table cellspacing="0" cellpadding="0" border="0" width="100%">

					<tr>
						<!-- company address -->

						<td   style="font-size: 10px; paddding: 0px; width: 40%;">
							<span style="font-size: 12px; font-weight: bold;">{{ Auth::user()->name }}</span>
							{{ Auth::user()->address }}. Tel: {{ Auth::user()->mobile }}
							<!-- company address -->
							<hr style=" width: 100%; border-color: #000000;">

							<!-- subject -->
							<p><b>Customer</b>: {{(@$saleInvoice['customers']['name'])?$saleInvoice['customers']['name']:''}}</p><!-- address -->
							<!-- subject -->
							<!-- address -->
							<p><b>Phone</b>: {{(@$saleInvoice['customers']['mobile'])?$saleInvoice['customers']['mobile']:''}}</p><!-- address -->
						</td>

						<td  style="font-size: 10px; width:20%; "></td>
						<td style="font-size: 10px; width: 40%;">
							<table cellspacing="0" class="table-challan" cellpadding="2" border="0"
							style="font-size: 12px; width: 100%;">
							<tr>
								<th style="text-align: left; border: none; padding-bottom: 13px;font-size: 14px;">
								Invoice</th>
							</tr>
							<tr>
								<th colspan="6" width="50%">Invoice No:</th>
								<th colspan="6" >Date</th>
							</tr>
							<tr>
								<td colspan="6" width="50%">{{"SN-".($saleInvoice['invoice_no'])}}</td>
								<td colspan="6">{{date('M d, Y',strtotime($saleInvoice['date']))}}</td>
							</tr>
							<tr>
								<th colspan="12" style="border: none; padding-top: 20px;"></th>
							</tr>

						</table>
						<table cellspacing="0" class="table-challan" cellpadding="2" border="0"
						style="font-size: 12px; width: 100%;">
						<tr>
							<th style="text-align: left; border: none; padding-bottom: 13px;font-size: 14px;">
							Order Status: {{(@$saleInvoice['status']==1)?'Confirmed':'Canceled'}}</th>
						</tr>


					</table>
				</td>
			</tr>

		</table>
	</td>
</tr>
<!-- 2nd Row -->

<!-- 2nd Row -->
<!-- 5nd Row -->
<tr>
	<td colspan="12" style="width: 100%;">
		<table class="table-suply" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td colspan="12" align="center"><b>Sale Invoice</b></td>
			</tr>
			<!-- Table Heading -->
			<tr>
				<th colspan="1">#</th>
				<th colspan="2">Product</th>
				<th colspan="3">Price</th>
				<th colspan="3">Quantity</th>
				<th colspan="3">Subtotal</th>

			</tr><!-- Table Heading -->
			<!-- Table content row 1 -->
			<?php
			$counter = 0;
			?>
			@foreach($saleitmesAddons as $addons)
			<?php

			$counter = $counter+1;
			?>
			<tr>
				<td colspan="1">{{$counter}}</td>
				<td colspan="2">{{$addons['productName']}}</td>
				<td colspan="3">{{$addons['selling_price']}}</td>
				<td colspan="3">{{$addons['quantity']}} - {{$addons['unit']}}</td>
				<td colspan="3" class="text-right">{{number_format($addons['amount'],2)}}</td>
			</tr>
			@endforeach
			@if($saleInvoice['discount']>0)
			<tr>
				<td colspan="9" class="text-left"><b>Discount</b></td>
				<td colspan="3" class="text-right">{{number_format($saleInvoice['discount'],2)}}</td>
			</tr>
			@endif
			<tr>
				<td colspan="9" class="text-left"><b>Total Amount</b></td>
				<td colspan="3" class="text-right">{{number_format($saleInvoice['amount'],2)}}</td>
			</tr>
			<?php $previous_balance = $customerBalance - $saleInvoice['amount']; ?>
			<tr>
				<td colspan="9" class="text-left"><b>Previous Balance</b></td>
				<td colspan="3" class="text-right">{{(@$previous_balance<0)?(-1)*$previous_balance. ' CR':$previous_balance. ' DB'}}</td>
			</tr>
			<tr>
				<td colspan="9" class="text-left"><b>Total Balance</b></td>
				<td colspan="3" class="text-right"><b>{{(@$customerBalance<0)?(-1)*$customerBalance. ' CR':$customerBalance. ' DB'}}</b></td>
			</tr>
			<!-- Table content row 1 -->
		</table>

	</td>
</tr>
<!-- 5nd Row -->
<tr>
	<td colspan="12" align="center"  width="100%" style="padding-top: 20px;"></td>
</tr>
<tr>
	<td colspan="12" align="center"  width="100%" style="padding-top: 20px;"></td>
</tr>
<tr>
	<td colspan="5" align="center" style="padding-top: 20px;">
    <strong><?php echo DNS2D::getBarcodeHTML(@$base64, 'QRCODE', 3,3);?> </strong></td>
</tr>
<!-- 5nd Row -->
<!-- 6nd Row -->

<!-- 6nd Row -->
<!-- 7nd row -->

<!-- 7nd row -->
<!-- 8nd row -->

<!-- 8nd Row -->
<!-- 9nd Row -->

<!-- 10nd row -->

<!-- 10nd Row -->
<!-- 11nd Row -->

<tr>
	<th colspan="12" align="left">

	</th>
</tr> <!-- 12nd Row -->
<tr>
	<td colspan="12"  style="font-size: small;">

	</td>
</tr> <!-- 12nd Row -->

<tr>
	<td colspan="12" style="padding-top: 20px;"></td>
</tr>
</table>
</center>
<script type="text/javascript">
	window.addEventListener("load", window.print());
</script>
</body>

</html>

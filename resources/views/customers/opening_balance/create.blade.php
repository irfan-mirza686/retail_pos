@extends('layouts.layout')
@section('title', '| Payment Receivable/Payable')
@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Customers</h1> -->
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/admin/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Customer Opening Balance</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-12">
        <form name="customerPaymentForm" id="customerPaymentForm" action="{{(@$editOpeningBalance)?url('/edit/customer/payments/'.$editOpeningBalance['id']):url('/customer/payments') }}"  method="post">
        @csrf
        <div class="card card-dark">

          @if ($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif
          <div class="card-header">
            <h3 class="card-title">Customer Payments</h3>
            </div>
            <div class="card-body box-profile">
            
            <div class="form-group row">
            
               
              <label for="voucher" class="col-sm-2 col-form-label">Voucher#</label>
              <div class="col-sm-10 col-md-4">
                <input type="text" class="form-control" name="voucher_no" id="voucher" style="background-color: #D8FDCD" value="{{(@$editOpeningBalance)?$editOpeningBalance['invoice_no']:$voucher_no }}">
              </div>
              <label for="inputToDate" class="col-sm-2 col-form-label">Date</label>
              <div class="col-sm-10 col-md-4">
                <input type="text" placeholder="DD-MM-YYYY" class="form-control" value="{{(@$editOpeningBalance)?date('d-m-Y',strtotime($editOpeningBalance['date'])):'' }}" name="date" id="datepicker">
              </div>
              
              
            </div>
            
            
            <div class="form-group row customerWise">
              <label for="inputCustoers" class="col-sm-2 col-form-label">Customers</label>
              <div class="col-sm-10 col-md-4">
                <input type="text" class="form-control" placeholder="search Customer" name="customerName" id="customerName" value="{{(@$editOpeningBalance)?$editOpeningBalance['customers']['name']:old('customerName') }}">
                    <input type="hidden" name="customer_id" id="customer_id" value="{{(@$editOpeningBalance)?$editOpeningBalance['customer_id']:old('customerName') }}">
                    <input type="hidden" name="area_id" id="area_id" value="{{(@$editOpeningBalance)?$editOpeningBalance['area_id']:old('area_id') }}">
                    <span id="blnceTitle" style="display:none;">Balance: <span id="customerBlnce" style="display: none;"></span></span>
              </div>
              <label for="paymentType" class="col-sm-2 col-form-label">Balance Type</label>
              <div class="col-sm-10 col-md-4">
                <select name="type" id="type" class="form-control select2" style="width: 100%;">
                  <option selected="selected" disabled="true">-Select-</option>
                  
                  <option value="debit"{{(@$editOpeningBalance['type']=='debit')?'selected':''}}>Debit</option>
                  <option value="credit"{{(@$editOpeningBalance['type']=='credit')?'selected':''}}>Credit</option>
                  
                </select>
              </div>
              
              
            </div>
            <div class="form-group row customerWise">
              <label for="amount" class="col-sm-2 col-form-label">Amount</label>
              <div class="col-sm-10 col-md-4">
                <input type="text" placeholder="Enter Amount" class="form-control" name="amount" id="customerAmount" value="{{(@$editOpeningBalance)?$editOpeningBalance['amount']:old('amount') }}">
              </div>
              <label for="description" class="col-sm-2 col-form-label">Description</label>
              <div class="col-sm-10 col-md-4">
                <textarea placeholder="Enter Description..." class="form-control" name="description" id="description">{{(@$editOpeningBalance)?$editOpeningBalance['description']:old('description') }}</textarea>
              </div>
            </div>
            
            
          </div>

          <div class="card-footer">
            <button type="submit" class="btn btn-primary" id="downloadPdf" style="margin-right: 5px; float:right;">
              <i class="fas fa-money-check-alt"></i> {{$title}}
            </button>
            <!-- <a class="btn btn-info" id="show_report" style=" color: white">Show Report</a> -->
            <!-- <a href="{{'/dashboard'}}"  class="btn btn-warning float-right">Cancel</a> -->
          </div>
        </div>
      </form>
      </div>
    </div>
    @if(!$editOpeningBalance)
    <div class="row">
      <div class="col-12">


        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Customers Payment</h3>
            
          </div>
          <!-- /.card-header -->
          <div class="card-body">
           
            <table id="example1" class="table table-bordered table-striped table-sm">
              <thead>
                <tr>
                  <th>ID #</th>
                  <th>Date</th>
                  <th>Customer</th>
                  <th>Payment Type</th>
                  <th>Amount</th>
                  <th>Added By</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $counter = 0;
                ?>
                @foreach($customer_opening_balances as $payment)
                <?php

                $counter = $counter+1;
                ?>
                <tr>
                  <td>{{ $counter }}</td>
                  
                  <td>{{ date('M d, Y',strtotime($payment['date'])) }}</td>
                  <td>{{ $payment['customers']['name'] }}</td>
                  
                  <td class="text-center">
                    @if($payment['type']=='credit')
                    <span class="badge badge-success">Credit</span>
                    @else
                    <span class="badge badge-warning">Debit</span>
                    @endif
                  </td>
                  <td class="text-right">{{ number_format($payment['amount'],2)}}</td>
                  <td>{{ $payment['users']['name']}}</td>
                  
                  
                  
                  <td class="center">
                    <a href="{{URL::to('/edit-customer-opening-balance',[$payment['id']]) }}" title="Edit?"><i class="far fa-edit"></i></a>
                    &nbsp;&nbsp;
                    @if(!$editOpeningBalance)
                    <a href="" title="Delete?" data-toggle="modal" class="deleteRecord" href="javascript:" rel="{{ $payment['id'] }}" rel1="delete-customer-opening-balance" id="delete"><i class="fas fa-trash"></i></a>
                    @endif
                    
                  </td>
                </tr>
                @endforeach

              </tbody>
              
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    @endif
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>

  @endsection
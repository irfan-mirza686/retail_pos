@extends('layouts.layout')
@section('title', '| Customer Payment')
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
            <li class="breadcrumb-item active">Customer Payment</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-12">
        <form name="customerPaymentForm" id="customerPaymentForm" action="{{(@$editCustomerPayment)?url('edit-customer-payment/'.$editCustomerPayment['id']):url('/add-customer-payment') }}"  method="post">
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
              <h3 class="card-title">Add Customer Payment</h3>
            </div>
            <div class="card-body box-profile">
              
              <div class="form-group row">
                <div class="form-group col-sm-12 col-md-4">
                  <label for="voucher">Voucher#</label>
                  <input type="text" class="form-control" name="voucher_no" value="{{(@$editCustomerPayment)?$editCustomerPayment['invoice_no']:$voucher_no }}" id="voucher" style="background-color: #D8FDCD">
                </div>
                <div class="form-group col-sm-12 col-md-4">
                  <label for="inputSupplier">Customers</label>
                  <input type="text" class="form-control" placeholder="search Customer" name="customerName" id="customerName" value="{{(@$editCustomerPayment)?$editCustomerPayment['customers']['name']:old('customerName') }}">
                  <input type="hidden" name="customer_id" id="customer_id" value="{{(@$editCustomerPayment)?$editCustomerPayment['customer_id']:old('customerName') }}">
                  <input type="hidden" name="area_id" id="area_id" value="{{(@$editCustomerPayment)?$editCustomerPayment['area_id']:old('area_id') }}">
                  
                </div>
                
                
                <div class="form-group col-sm-12 col-md-4">
                  <label for="amount">Balance</label>
                  <input type="text" class="form-control" id="chkCustomerBalance" readonly="" style="background-color: #D8FDBA">
                </div>
                
                <!-- </div> -->
              </div>
              
              
              <div class="form-group row customerWise">
                
                
                
                
                <div class="form-group col-sm-12 col-md-4">
                  <label for="amount">Amount</label>
                  <input type="text" placeholder="Enter Amount" class="form-control" name="amount" id="customerAmount" value="{{(@$editCustomerPayment)?$editCustomerPayment['amount']:old('amount') }}">
                </div>
                
                <div class="form-group col-sm-12 col-md-4" id="check_discount">
                  <label for="name">Discount</label>
                  <input type="text" class="form-control" id="discount" placeholder="Enter Discount" name="payment_discount" value="{{(@$editCustomerPayment)?$editCustomerPayment['payment_discount']:0 }}" style="background-color: #D8FDB">
                </div>
                <div class="form-group col-sm-12 col-md-4">
                  <label for="amount">Date</label>
                  <input type="text" placeholder="DD-MM-YYYY" class="form-control" name="date" id="datepicker" value="{{(@$editCustomerPayment)?date('d-m-Y',strtotime($editCustomerPayment['date'])):old('date') }}">
                </div>
                <div class="form-group col-sm-12 col-md-12">
                  <label for="description">Description</label>
                  <textarea placeholder="Enter Description..." class="form-control" name="description" id="description">{{(@$editCustomerPayment)?$editCustomerPayment['description']:old('description') }}</textarea>
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
                  <th>Voucher#</th>
                  <th>Date</th>
                  <th>Customer</th>
                  <th>Amount</th>
                  <th>Received By</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $counter = 0;
                ?>
                @foreach($customersPayment as $payment)
                <?php

                $counter = $counter+1;
                ?>
                <tr>
                  <td>{{"VCH-".($payment['invoice_no'])}}</td>
                  
                  <td>{{ date('M d, Y',strtotime($payment['date'])) }}</td>
                  <td>{{ $payment['customers']['name'] }}</td>
                  <td class="text-right">{{ number_format($payment['amount'],2)}} <span style="color: red;">{{($payment->payment_discount>'0')?"- Discounted":""}}</span></td>
                  <td>{{ $payment['users']['name']}}</td>
                  
                  
                  
                  <td class="center">
                    <a href="{{URL::to('/edit-customer-payment',[$payment['id']]) }}" title="Edit?"><i class="far fa-edit"></i></a>
                    &nbsp;&nbsp;
                    @if(!$editCustomerPayment)
                    <a href="" title="Delete?" data-toggle="modal" class="deleteRecord" href="javascript:" rel="{{ $payment['id'] }}" rel1="delete-customer-payment" id="delete"><i class="fas fa-trash"></i></a>
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
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>

<script type="text/javascript">
 
    // $(document).on('click','#checkboxSuccess2',function(){
    //   // alert('okk');
    //   var value = $(this).val();
    //   // alert(value);
    //   if (value==1) {
    //     $('#check_discount').show();
    //     $('#checkboxSuccess2').val(0);
    //   }else{
    //     $('#check_discount').hide();
    //     $('#checkboxSuccess2').val(1);
    //   }
    
    //   // $('#previous_amount').closest("tr").find("input.previous_amount").show();
    // });
    
  </script>

  @endsection
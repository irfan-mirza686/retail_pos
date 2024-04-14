@extends('layouts.layout')
@section('title', '| Purchase Orders')
@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Purchase Orders</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Purchase Orders</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-12">


        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Purchase Orders</h3>
            <a href="{{ url('/add-purchase') }}" class="btn btn-block btn-success btn-sm" style="width: 180px; float: right; display: inline-block;">New Order</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
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

            <table id="example1" class="table table-bordered table-hover table-sm">
              <thead>
                <tr>
                  <th>ID #</th>
                  <th>Date</th>
                  <th>Supplier</th>
                  <th>Total Amount</th>
                  <th>Order Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $counter = 0;
                ?>
                @foreach($purchaseOrders as $orders)
                <?php

                $counter = $counter+1;
                ?>
                <tr>
                  <td>{{ $counter }}</td>
                  
                  <td>{{ date('M d, Y',strtotime($orders['date']))}}</td>
                  <td>{{ $orders['suppliers']['name'] }}</td>
                  <td class="text-right">{{ number_format($orders['amount'],2) }}</td>
                  <td class="text-center">
                   
                    @if($orders['status']=='cancel')
                    <span class="badge badge-danger">Cancelled</span>

                    @elseif($orders['status']=='received')
                    <span class="badge badge-success">Received</span>
                    @else 
                    <span class="badge badge-primary">Ordered</span>
                    @endif
                    
                  </td>
                  
                  
                 
                  <td class="center">
                    <ul class="navbar-nav ml-auto">
                      <!-- Notifications Dropdown Menu -->
                      <li class="nav-item dropdown">
                        <a class="btn btn-primary btn-sm btn-o dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                          Action <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        
                         <a class="dropdown-item" href="{{url('/edit-purchase',[$orders['id']]) }}">
                            <i class="fas fa-edit text-blue"></i>
                            {{ __('Edit') }}
                          </a>
                          @if($orders['status']=='cancel')
                          <a class="dropdown-item deleteRecord" data-toggle="modal" class="deleteRecord" href="javascript:" rel="{{ $orders['id'] }}" rel1="delete-purchase" id="delete">
                            <i class="fas fa-trash text-red"></i>
                            {{ __('Delete') }}
                          </a>
                          @endif
                        </div>
                      </li>
                    </ul>
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

@endsection
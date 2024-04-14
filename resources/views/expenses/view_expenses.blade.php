@extends('layouts.layout')
@section('title', '| Expenses')
@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Expenses</h1> -->
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/admin/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Expenses</li>
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
            <h3 class="card-title">View Expenses</h3>
            <a href="{{ url('/add-expense') }}" class="btn btn-block btn-success btn-sm" style="width: 150px; float: right; display: inline-block;">Add Expenses</a>
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
          <!-- Laravel Validations Errors------>
          @if ($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>ID #</th>
                  <th>Date</th>
                  <th>Expense For</th>
                  <th>Total Expense</th>
                  <th>Created By</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $counter = 0;
                ?>
                @foreach($expenses as $expense)
                <?php

                $counter = $counter+1;
                ?>
                <tr>
                  <td>{{ $counter }}</td>
                
                  <td>{{ $expense['date'] }}</td>
                  <td>{{ $expense['expense_for']}}</td>
                  <td>{{ $expense['amount']}}</td>
                  
                    <td>
                      {{ $expense['user']['name']}}
                    </td>
                  
                  
                  <td class="center">
                    <ul class="navbar-nav ml-auto">
                      <!-- Notifications Dropdown Menu -->
                      <li class="nav-item dropdown">
                        <a class="btn btn-primary btn-sm btn-o dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                          Action <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                          
                        
                          <a class="dropdown-item" href="{{URL::to('/edit-expense',[$expense['id']]) }}">
                            <i class="fa fa-fw fa-edit text-gray"></i>
                            {{ __('Edit') }}
                          </a>
                        
                          
                          <a class="dropdown-item deleteRecord" data-toggle="modal" class="deleteRecord" href="javascript:" rel="{{ $expense['id'] }}" rel1="delete-expense" id="delete">
                            <i class="fas fa-trash text-red"></i>
                            {{ __('Delete') }}
                          </a>


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
@extends('layouts.layout')
@section('title', '| Employees Monthly Salary')
@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Employees Monthly Salary</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Employees Monthly Salary</li>
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
            <h3 class="card-title">View Employees Monthly Salary</h3>
            <a href="{{ url('/pay-employee-mothly-salary') }}" class="btn btn-block btn-success btn-sm" style="width: 150px; float: right; display: inline-block;">Pay Salary</a>
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
         
            <table id="example1" class="table table-bordered table-striped table-sm">
              <thead>
                <tr>
                  <th>ID #</th>
                  <th>Name</th>
                  <th>Date</th>
                  <th>Salary</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $counter = 0;
                ?>
                @foreach($monthlySalary as $salary)
                <?php

                $counter = $counter+1;
                ?>
                <tr>
                  <td>{{ $counter }}</td>
                  
                  <td>{{ $salary['employee']['name'] }}</td>
                  <td>{{ date('M Y',strtotime($salary['date'])) }}</td>
                  <td>{{ $salary['amount']}}</td>
                
                  
                  
                  <td class="center">
                    <span class="badge badge-success">Paid</span>
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
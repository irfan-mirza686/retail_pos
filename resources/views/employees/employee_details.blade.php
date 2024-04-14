@extends('layouts.layout')
@section('title', '| Employee Details')
@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Employee Details</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Employee Details</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
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
    <div class="row">
      <div class="col-12">


        <div class="card">
          <div class="card-header">
            <h3 class="card-title">View Employees</h3>
            
          </div>
          <!-- /.card-header -->
          <div class="card-body">
           
         <strong>Employee Name :&nbsp;&nbsp;</strong>{{$employee['name']}},&nbsp;&nbsp; <strong>CNIC :&nbsp;&nbsp;</strong>{{$employee['cnic']}}
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>ID #</th>
                  <th>Previous Salary</th>
                  <th>Increment Salary</th>
                  <th>Current Salary</th>
                  <th>Effected Date</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $counter = 0;
                ?>
                @foreach($employeeDetails as $key => $details)
                <?php

                $counter = $counter+1;
                ?>
                <tr>
                  @if($key==0)
                  <td colspan="6" class="text-center"><strong>Joining Salary :</strong>{{$details['previous_salary']}}</td>
                  @else
                  <td>{{ $counter }}</td>
                  <td>{{ $details['previous_salary'] }}</td>
                  <td>{{ $details['increment_salary'] }}</td>
                  <td>{{ $details['current_salary']}}</td>
                  <td>{{ date('Y-m-d',strtotime($details['effected_date']))}}</td>
                  @endif
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
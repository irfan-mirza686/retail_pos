@extends('layouts.layout')
@section('title', '| View Employees')
@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>View-Employees</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/admin/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Employees</li>
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
            <a href="{{ url('/create-employee') }}" class="btn btn-block btn-success btn-sm" style="width: 150px; float: right; display: inline-block;">Add Employee</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-sm table-bordered table-striped">
              <thead>
                <tr>
                  <th>ID #</th>
                  <th>Name</th>
                  <th>Mobile</th>
                  <th>Address</th>
                  <th>CNIC</th>
                  <th>Salary</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $counter = 0;
                ?>
                @foreach($employees as $employee)
                <?php

                $counter = $counter+1;
                ?>
                <tr>
                  <td>{{ $counter }}</td>
                  <td>{{ $employee['name'] }}</td>
                  <td>{{ $employee['mobile'] }}</td>
                  <td>{{ $employee['address']}}</td>
                  <td>{{ $employee['cnic']}}</td>
                  <td>{{ $employee['salary']}}</td>
                  <td class="center">
                    <ul class="navbar-nav ml-auto">
                      <!-- Notifications Dropdown Menu -->
                      <li class="nav-item dropdown">
                        <a class="btn btn-primary btn-sm btn-o dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                          Action <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                          <a class="dropdown-item" href="{{URL::to('/employee-advance-salary',[$employee['id']]) }}">
                            <i class="fas fa-money-check-alt"></i>
                            {{ __('Advance') }}
                          </a>
                          
                          <a class="dropdown-item" href="{{URL::to('/monthly-employee-return-advance',[$employee['id']]) }}">
                            <!-- <i class="fas fa-plus-circle text-green"></i> -->
                            <i class="fas fa-undo text-blue"></i>
                            {{ __('Return Advance?') }}
                          </a>
                        

                          <a class="dropdown-item" href="{{URL::to('/monthly-employee-salary-increment',[$employee['id']]) }}">
                            <i class="fas fa-plus-circle text-green"></i>
                            {{ __('Increment?') }}
                          </a>
                         
                          <a class="dropdown-item" href="{{URL::to('/edit-employee',[$employee['id']]) }}">
                            <i class="fa fa-fw fa-edit text-gray"></i>
                            {{ __('Edit') }}
                          </a>
                        
                          <a class="dropdown-item" href="{{URL::to('/employee-details',[$employee['id']]) }}">
                            <i class="fas fa-eye text-blue"></i>
                            {{ __('View') }}
                          </a>
                         
                          
                          <a class="dropdown-item deleteRecord" data-toggle="modal" class="deleteRecord" href="javascript:" rel="{{ $employee['id'] }}" rel1="delete-employee" id="delete">
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
@extends('layouts.layout')
@section('title', '| View Users')
@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>View-Users</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/admin/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Users</li>
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
            <h3 class="card-title">View Users</h3>
            <a href="{{ url('/add-user') }}" class="btn btn-block btn-success btn-sm" style="width: 150px; float: right; display: inline-block;">Add User</a>
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
            <table id="example1" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>ID #</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Mobile</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $counter = 0;
                ?>
                @foreach($allData as $user)
                <?php

                $counter = $counter+1;
                ?>
                <tr>
                  <td>{{ $counter }}</td>
                  
                  <td>{{ $user['name'] }}</td>
                  <td>{{ $user['email'] }}</td>
                  <td>{{ $user['mobile']}}</td>
                  
                  
                  <td class="center">
                    <a href="{{URL::to('/edit-user',[$user['id']]) }}" title="Edit?"><i class="far fa-edit"></i></a>
                    &nbsp;&nbsp;
                    <a href="" title="Delete?" data-toggle="modal" class="deleteRecord" href="javascript:" rel="{{ $user['id'] }}" rel1="delete-user" id="delete"><i class="fas fa-trash"></i></a>
                    
                    
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
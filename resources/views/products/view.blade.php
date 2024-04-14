@extends('layouts.layout')
@section('title', '| Products')
@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Products</h1> -->
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/admin/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Products</li>
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
            <h3 class="card-title">View Products</h3>
            <a href="{{ url('/add-product') }}" class="btn btn-block btn-success btn-sm" style="width: 150px; float: right; display: inline-block;">Add Product</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            
            </ul>
          </div>
            <table id="example1" class="table table-bordered table-striped table-sm">
              <thead>
                <tr>
                  <th>ID #</th>
                  <th>Code</th>
                  <th>Name</th>
                  <th>Unit</th>
                  <th>Stock</th>
                  <th>Cost</th>
                  <th>Selling Price</th>
                  <th>Created By</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $counter = 0;
                ?>
                @foreach($products as $product)
                <?php

                $counter = $counter+1;
                ?>
                <tr>
                  <td>{{ $counter }}</td>
                  <td>{{ $product['product_code'] }}</td>
                  <td>{{ $product['name'] }}</td>
                  <td>{{ $product['units']['name'] }}</td>
                  <td class="text-center">{{ $product['quantity']}} - {{$product['units']['name']}}</td>
                  <td class="text-center">{{ $product['cost']}}</td>
                  <td class="text-center">{{ $product['selling_price']}}</td>
                    <td>
                      {{ $product['users']['name']}}
                    </td>
                    
                  
                  <td class="center">
                    <ul class="navbar-nav ml-auto">
                      <!-- Notifications Dropdown Menu -->
                      <li class="nav-item dropdown">
                        <a class="btn btn-primary btn-sm btn-o dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                          Action <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                          <a class="dropdown-item" href="{{URL::to('/edit-product',[$product['id']]) }}">
                            <i class="fa fa-fw fa-edit text-gray"></i>
                            {{ __('Edit') }}
                          </a>
                          <!-- <a class="dropdown-item deleteRecord" data-toggle="modal" class="deleteRecord" href="javascript:" rel="{{ $product['id'] }}" rel1="delete-product" id="delete">
                            <i class="fas fa-trash text-red"></i>
                            {{ __('Delete') }}
                          </a> -->


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
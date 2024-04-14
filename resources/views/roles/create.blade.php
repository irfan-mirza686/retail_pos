@extends('layouts.layout')
@section('title', '| Create-Role')
@section('content')


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Role</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard')}}">Dashboard</a></li>
                        <!-- <li class="breadcrumb-item active">Create Role</li> -->
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- SELECT2 EXAMPLE -->
            <form name="groupForm" id="groupForm" action="{{ route('role.add') }}"  method="post">
                @csrf
                
                <div class="card card-default">
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
                    
                    <div class="card-header">
                        <a href="{{ url('/units') }}" class="btn btn-block btn-success btn-sm" style="width: 150px; display: inline-block;"><i  class="fa fa-list"></i>&nbsp;&nbsp;Units List</a>
                        
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <!--Row Strat----->
                        <div class="row"> 
                            <!-- <div class="col-md-6"> -->
                                <div class="form-group col-sm-12 col-md-4">
                                    <label for="name">Group Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter Group Name" id="name" value="{{ old('name') }}" required="">

                                    @foreach($groupModule as $key => $data)
                                    <input type="hidden" name="txtModID[<?php echo $key; ?>]" value="<?php echo $data['id']; ?>">
                                        <input type="hidden" name="txtModname[<?php echo $key; ?>]" value="<?php echo $data['module_name']; ?>">
                                        <input type="hidden" name="txtModpage[<?php echo $key; ?>]" value="<?php echo $data['module_page']; ?>">
                                    @endforeach

                                    <font style="color: red;">
                                        {{($errors->has('name'))?($errors->first('name')):''}}
                                    </font>
                                </div>

                                <div class="form-group col-sm-12 col-md-8">
                                    <label for="name">-select-</label>
                                    <select class="form-control select2" name="txtaccess[]" id="e1"  multiple="multiple" >

                                @foreach($groupModule as $key => $data)
                                <option value="{{$key}}">&nbsp;&nbsp;&nbsp;{{$data->module_name}}</option>
                              
                                @endforeach
                            </select>
                            <input type="checkbox" id="checkbox" > Select All
                                </div>
                            <!--Row Strat----->
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <div>
                                <button type="submit" class="btn btn-success" style="float: right;">Save</button>
                            </div>
                            <div style="margin-right: 140px;">
                                <a href="{{ route('roles') }}"  class="btn btn-warning" style="float: right;">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

    <script type="text/javascript">
        $(document).ready(function () {

            $('#groupForm').validate({
                rules: {
                    name: {
                        required: true
                    },
                    
                },
                messages: {
                    name: {
                        required: "Please enter Name",
                    }
                    
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>

     <script type="text/javascript">
    $(document).ready(function(){
        $("#e1").select2();
        $("#checkbox").click(function(){
            if($("#checkbox").is(':checked') ){
                $("#e1 > option").prop("selected","selected");
                $("#e1").trigger("change");
            }else{
                $("#e1 > option").prop("selected", false);
                $("#e1").trigger("change");
            }
        });

});
    

</script>
    @endsection
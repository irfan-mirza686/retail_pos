<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>POS | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('css/adminlte.min.css')}}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <style>
    .notifyjs-corner {
      z-index: 10000 !important;
    }
    </style>
</head>

<body class="hold-transition login-page"
    style="height: 100%;background-repeat: no-repeat; background-image: url('images/bg1.jpg');">
    @include('includes.activity_modal')
    <div class="login-box">

        <div class="login-logo" style="margin-top: 100px;">
            <a href="#"><b style="color: whitesmoke;">Admin</b><span style="color: silver;">Panel</span></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
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

                <form action="{{ route('check.email') }}" id="checkLoginEmailForm">

                    <div class="input-group mb-3">
                        <input type="text" name="email" id="email" class="form-control" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="input-group mb-3">
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col-8" style="display: none;">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-block loginEmailBtn">Login</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

            </div>
            <!-- /.login-card-body -->
        </div>
    </div>

    @if(session()->has('success'))
    <script type="text/javascript">
      $(function(){
        $.notify("{{session()->get('success')}}",{globalPosition:'top right', className:'success'});
      });
    </script>
    @endif
    @if(session()->has('error'))
    <script type="text/javascript">
      $(function(){
        $.notify("{{session()->get('error')}}",{globalPosition:'top right', className:'error'});
      });
    </script>
    @endif
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('js/adminlte.min.js')}}"></script>
    <script type="text/javascript">
        window.setTimeout(function() {
            $(".alert").alert('close');
        }, 10000);
    </script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('js/login/login.js')}}"></script>
</body>

</html>

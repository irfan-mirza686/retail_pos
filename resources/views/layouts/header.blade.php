<nav class="main-header navbar navbar-expand navbar-gray navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
     <!--  <li class="nav-item d-none d-sm-inline-block" style="display: none;">
        <a href="index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block" style="display: none;">
        <a href="#" class="nav-link">Contact</a>
      </li> -->
    </ul>


    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fas fa-power-off"></i> &nbsp;&nbsp; 
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{ url('/user-profile') }}">
                {{ __('Profile') }}
            </a>
            <a class="dropdown-item" href="{{ url('/update-password') }}">
                {{ __('Change Password') }}
            </a>
            <a class="dropdown-item" href=""
               onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ url('/logout') }}" method="get" style="display: none;">
                @csrf
            </form>
        </div>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li> -->
    </ul>
  </nav>
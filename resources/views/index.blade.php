<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Rumah BUMN</title>
  <!-- base:css -->
  <link rel="stylesheet" href="{{ asset('template/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('template/vendors/css/vendor.bundle.base.css') }}">
  <!-- endinject -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('template/css/style.css') }}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ asset('template/images/favicon.png') }}" />
</head>

<body>
  <div class="container-scroller d-flex">
    <div class="row p-0 m-0 proBanner" id="proBanner">
      <div class="col-md-12 p-0 m-0">
      </div>
    </div>

   
   <nav class="sidebar sidebar-offcanvas" id="sidebar">
      <ul class="nav">
        <li class="nav-item sidebar-category">
         
        
            <img src="../template/temp/assets/img/logo-dark.png" alt="Image" class="img-fluid right-align" >
       
          
          <span></span>
        </li>
       @if(Auth::user()->role == 'admin' || Auth::user()->role == 'user')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin') }}">
                <i class="mdi mdi-view-quilt menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        @endif
        @if(Auth::user()->role == 'koordinator')
         <li class="nav-item">
            <a class="nav-link" href="{{ route('koordinator') }}">
                <i class="mdi mdi-view-quilt menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        @endif
        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'user')
       <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.absen') }} ">
                <i class="mdi mdi-file menu-icon"></i>
                <span class="menu-title">Kehadiran</span>
            </a>
        </li>
             
 <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.kerjaHarian') }}">
                <i class="mdi mdi-file menu-icon"></i>
                <span class="menu-title">Work Report</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('sop') }}">
                <i class="mdi mdi-file menu-icon"></i>
                <span class="menu-title">SOP</span>
            </a>
        </li>
 @endif
  @if(Auth::user()->role == 'koordinator')
 <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.rekapkehadiran') }} ">
                <i class="mdi mdi-file menu-icon"></i>
                <span class="menu-title">Rekap Kehadiran</span>
            </a>
        </li>
    <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.kerjaHarian') }}">
                <i class="mdi mdi-file menu-icon"></i>
                <span class="menu-title">Rekap Work Report</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('sop') }}">
                <i class="mdi mdi-file menu-icon"></i>
                <span class="menu-title">SOP</span>
            </a>
        </li>
 
  
        
          
        
        <li class="nav-item">
            <a class="nav-link" href="{{ route('kpi') }}">
                <i class="mdi mdi-calendar-check menu-icon"></i>
                <span class="menu-title">KPI</span>
            </a>
        </li>
 @endif
</li>
          @if(Auth::user()->role == 'admin')
         <li class="nav-item">
            <a class="nav-link" href="{{ route('user.show') }}">
                <i class="mdi mdi-file menu-icon"></i>
                <span class="menu-title">User Setting</span>
            </a>
        </li>
        @endif
         
      </ul>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:./partials/_navbar.html -->
      <nav class="navbar col-lg-12 col-12 px-0 py-0 py-lg-4 d-flex flex-row">
        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu" style="margin-right: 22px;"></span>
          </button>
          <div class="navbar-brand-wrapper">
           
          </div>
         
          <h4 class="font-weight-bold mb-0 d-none d-md-block mt-1" > Welcome back, {{ Auth::user()->name }}</h4>
               
          
          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item">
             
            </li>
           <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                
                <span class="nav-profile-name">{{Auth::user()->name }}</span>
               
               
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                <a class="dropdown-item"  href="{{ route('logout') }}" >
                  <i class="mdi mdi-logout text-primary"></i>
                  Logout
                </a>
              </div>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link icon-link">
           
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link icon-link">
           
              </a>
            </li>
          </ul>
        </div>
      </nav>      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
                  <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                      <div class="card-body">

                      @yield('content')
                      </div>
                    </div>
                  </div>
                          </div>
                        </div>

          <!-- row end -->
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:./partials/_footer.html -->
       
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- base:js -->
  <script src="{{ asset('template/vendors/js/vendor.bundle.base.js') }}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <script src="{{ asset('template/vendors/chart.js/Chart.min.js') }}"></script>
  <script src="{{ asset('template/js/jquery.cookie.js') }}" type="text/javascript"></script>
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="{{ asset('template/js/off-canvas.js') }}"></script>
  <script src="{{ asset('template/js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('template/js/template.js') }}"></script>
  <!-- endinject -->
  <!-- plugin js for this page -->
  <script src="{{ asset('template/js/jquery.cookie.js') }}" type="text/javascript"></script>
  <!-- End plugin js for this page -->
  <!-- Custom js for this page-->
  <script src="{{ asset('template/js/dashboard.js') }}"></script>
  <!-- End custom js for this page-->
</body>

</html>

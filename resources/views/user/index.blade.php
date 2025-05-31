<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Web Pemilihan</title>
  <!-- base:css -->
  <link rel="stylesheet" href="../template/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../template/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../template/css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../template/images/favicon.png" />
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
        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'superadm'|| Auth::user()->role == 'sekretaris'|| Auth::user()->role == 'user')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user') }}">
                <i class="mdi mdi-view-quilt menu-icon"></i>
                <span class="menu-title">Dashboard</span>
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
          @if(Auth::user()->role == 'user')
          <h4 class="font-weight-bold mb-0 d-none d-md-block mt-1" > Welcome back, User</h4>
                @endif
                @if(Auth::user()->role == 'admin')
          <h4 class="font-weight-bold mb-0 d-none d-md-block mt-1" > Welcome back, Admin</h4>
                @endif
                @if(Auth::user()->role == 'superadm')
          <h4 class="font-weight-bold mb-0 d-none d-md-block mt-1" > Welcome back, Koordinator</h4>
                @endif
                
          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item">
             
            </li>
           <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                @if(Auth::user()->role == 'user')
                <span class="nav-profile-name">User</span>
                @endif
                @if(Auth::user()->role == 'admin')
                <span class="nav-profile-name">Management Representative</span>
                @endif
                @if(Auth::user()->role == 'superadm')
                <span class="nav-profile-name">Direktur</span>
                @endif
                @if(Auth::user()->role == 'sekretaris')
                <span class="nav-profile-name">Sekretaris</span>
                @endif
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
      </nav>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
                  <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                      <div class="card-body">
                      @yield('content')
                      @if(Auth::user()->role == 'user')
                      <div class="table-responsive pt-3">
                      
                       
                        
                      </div>
                      
                      
                  <!-- index.blade.php -->
                  
<h1>Daftar Dokumen</h1>
<br>
<table class="table table-bordered">
  <thead>
      <tr>
        <th>Nama SOP</th>
        <th>Dokumen PDF</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>
</thead>
<tbody>
    @foreach ($sop as $sops)
        <tr>
            <td>{{ $sops->nama }}</td>
            <td>{{ $sops->file }}</td>           
            <td>{{ $sops->status }}</td>
           
                    <td>
                        <a href="{{ route('sop.show', $sops->id) }}"  class="btn btn-warning btn-xs">
                            Lihat PDF</i> 
                        </a>
                  </td>
                </tr>
            @endforeach
        </tbody>
    </table>


                      </div>
                    </div>
                  </div>
                          </div>
                        </div>

          <!-- row end -->
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:./partials/_footer.html -->
        <footer class="footer">
          <div class="card">
            <div class="card-body">
              <div class="d-sm-flex justify-content-center justify-content-sm-between py-2">
                <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © <a href="https://www.bootstrapdash.com/" target="_blank">bootstrapdash.com </a>2021</span>
                <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Only the best <a href="https://www.bootstrapdash.com/" target="_blank"> Bootstrap dashboard </a> templates</span>
              </div>
            </div>
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- base:js -->
  <script src="../template/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <script src="../template/vendors/chart.js/Chart.min.js"></script>
  <script src="../template/js/jquery.cookie.js" type="text/javascript"></script>
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="../template/js/off-canvas.js"></script>
  <script src="../template/js/hoverable-collapse.js"></script>
  <script src="../template/js/template.js"></script>
  <!-- endinject -->
  <!-- plugin js for this page -->
    <script src="../template/js/jquery.cookie.js" type="text/javascript"></script>
  <!-- End plugin js for this page -->
  <!-- Custom js for this page-->
  <script src="../template/js/dashboard.js"></script>
  <!-- End custom js for this page-->
  <!-- Delete icon trigger -->

@else
  <p>No data available</p>
  @endif
</body>

</html>
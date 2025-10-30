<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Coachsparkle Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ url('/public') }}/admin_assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="{{ url('/public') }}/admin_assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="{{ url('/public') }}/admin_assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="{{ url('/public') }}/admin_assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ url('/public') }}/admin_assets/vendors/mdi/css/materialdesignicons.min.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->

    <!-- <link rel="stylesheet" href="{{ url('/public') }}/admin_assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css"> -->
    <link rel="stylesheet" type="text/css" href="{{ url('/public') }}/admin_assets/js/select.dataTables.min.css">
    <link rel="stylesheet" href="{{ url('/public') }}/admin_assets/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="{{ url('/public') }}/admin_assets/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="{{ url('/public') }}/admin_assets/css/admin_dashboard.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ url('/public') }}/admin_assets/css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ url('/public') }}/admin_assets/images/fav.png" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('custom_css')
</head>

<body>
    <div class="container-scroller">

        <!-- partial:partials/_navbar.html -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
                <a class="navbar-brand brand-logo me-5" href="{{ route('admin.dashboard') }}"><img
                        src="{{ url('/public') }}/admin_assets/images/main_logo.png" class="me-2"
                        alt="logo" /></a>
                <a class="navbar-brand brand-logo-mini" href="{{ route('admin.dashboard') }}"><img
                        src="{{ url('/public') }}/admin_assets/images/fav.png" alt="logo" /></a>

            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="icon-menu"></span>
                </button>

                <ul class="navbar-nav navbar-nav-right">
                    <!-- <li class="nav-item dropdown">
                        <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#"
                            data-bs-toggle="dropdown">
                            <i class="icon-bell mx-0"></i>
                            <span class="count"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                            aria-labelledby="notificationDropdown">
                            <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-success">
                                        <i class="ti-info-alt mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject font-weight-normal">Application Error</h6>
                                    <p class="font-weight-light small-text mb-0 text-muted"> Just now </p>
                                </div>
                            </a>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-warning">
                                        <i class="ti-settings mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject font-weight-normal">Settings</h6>
                                    <p class="font-weight-light small-text mb-0 text-muted"> Private message </p>
                                </div>
                            </a>
                            <a class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-info">
                                        <i class="ti-user mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject font-weight-normal">New user registration</h6>
                                    <p class="font-weight-light small-text mb-0 text-muted"> 2 days ago </p>
                                </div>
                            </a>
                        </div>
                    </li> -->
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"
                            id="profileDropdown">
                            <img src="{{ url('/public') }}/admin_assets/images/faces/face28.jpg" alt="profile" />
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown"
                            aria-labelledby="profileDropdown">
                            <a class="dropdown-item">
                                <i class="ti-settings text-primary"></i> Settings </a>
                            <a class="dropdown-item" href="{{ route('admin.logout') }}">
                                <i class="ti-power-off text-primary"></i> Logout </a>
                        </div>
                    </li>

                </ul>

            </div>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            @include('admin.layouts.sidebar')
            <!-- partial -->
            @yield('content')
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

    <script src="{{ url('/public') }}/admin_assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <!-- <script src="{{ url('/public') }}/admin_assets/vendors/typeahead.js/typeahead.bundle.min.js"></script> -->
    <script src="{{ url('/public') }}/admin_assets/vendors/select2/select2.min.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{ url('/public') }}/admin_assets/vendors/chart.js/chart.umd.js"></script>
    <!-- <script src="{{ url('/public') }}/admin_assets/vendors/datatables.net/jquery.dataTables.js"></script> -->
    <!-- <script src="{{ url('/public') }}/admin_assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script> -->
    <!-- <script src="{{ url('/public') }}/admin_assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js"></script> -->
    <!-- <script src="{{ url('/public') }}/admin_assets/js/dataTables.select.min.js"></script> -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ url('/public') }}/admin_assets/js/off-canvas.js"></script>
    <script src="{{ url('/public') }}/admin_assets/js/template.js"></script>
    <script src="{{ url('/public') }}/admin_assets/js/settings.js"></script>
    <script src="{{ url('/public') }}/admin_assets/js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="{{ url('/public') }}/admin_assets/js/jquery.cookie.js" type="text/javascript"></script>
    <script src="{{ url('/public') }}/admin_assets/js/dashboard.js"></script>
    <!-- <script src="{{ url('/public') }}/admin_assets/js/Chart.roundedBarCharts.js"></script> -->
    <!-- <script src="{{ url('/public') }}/admin_assets/js/typeahead.js"></script> -->
    <script src="{{ url('/public') }}/admin_assets/js/select2.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script> -->
    <script src="https://cdn.datatables.net/2.3.0/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.0/js/dataTables.bootstrap5.js"></script>
    <script src="{{ url('/public') }}/admin_assets/js/fullcalendar.js"></script>
     <script src="{{ url('/public') }}/admin_assets/js/pooper.min.js"></script>
     <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <!-- End custom js for this page-->
          <script>
          	
          
          $(document).ready( function () {
            var table = $('#example').DataTable( {
              "bPaginate": false,
              "bInfo": false,
            });
          } );

          
        </script>
    @stack('scripts')
</body>

</html>

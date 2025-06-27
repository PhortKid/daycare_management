<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Daycare Management Dashboard</title>

    <!-- Custom fonts for this template-->
     
    <link href="/dashboard_assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
        
        <style>
         .battan-close{
            background-color:transparent;
            border-color:transparent;
         }
         
            .fa-user{
                 color:darkcyan;
             }
         </style>

    <!-- Custom styles for this template-->
    <link href="/dashboard_assets/css/sb-admin-2.min.css" rel="stylesheet" type="text/css">
    <link href="/dashboard_assets/css/custom.css" rel="stylesheet" type="text/css">
   <!-- <link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css"> -->
    <link href="/dashboard_assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">

    <link href="/chosen/chosen.min.css" rel="stylesheet" type="text/css">
    
   

   
    
    
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

    <?php 
    include 'sidebar.php';
    ?>


        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                       
                   

                        <!-- Nav Item - Alerts -->

                        
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                
                                <span class="badge badge-danger badge-counter">1
                              
                                </span>
                            </a>
                           
             <!-- DROP DOWN ALERT -->


   
             <!-- END OF DROP DOWN ALERT -->
                        

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                <?php if (session_status() == PHP_SESSION_NONE) {
                                    session_start();
                                }

                                try {
                                    if (isset($_SESSION['first_name'])) {
                                        echo htmlspecialchars($_SESSION['first_name']);
                                    }
                                } catch (Exception $e) {
                                    // Silent fail or log if needed
                                }
                                ?>
                            
                                 
                                 
</span>   
                                <img class="img-profile rounded-circle"
                                    src="/assets/user.png">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                               
                              
                              
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->
                      <!-- Begin Page Content -->
                <div class="container-fluid">
              
                    <!-- Page Heading -->


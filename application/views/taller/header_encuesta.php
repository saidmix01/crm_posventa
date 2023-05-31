<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ENCUESTA POST</title>
    <!-- Le dice al navegador que la web es responsiva -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Estilos Font Awesome -->
    <link rel="stylesheet" href="<?=base_url()?>plugins/fontawesome-free/css/all.min.css">
    <!-- Pack de Iconos Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Estilos Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="<?=base_url()?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?=base_url()?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="<?=base_url()?>plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?=base_url()?>dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?=base_url()?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="<?=base_url()?>plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="<?=base_url()?>plugins/summernote/summernote-bs4.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="shortcut icon" href="<?=base_url()?>media/logo/logo_codiesel_sinfondo.png" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- SweetAlert2 -->
   <!--  <link rel="stylesheet" href="<?=base_url()?>plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css"> -->
    <!-- Toastr -->
    <link rel="stylesheet" href="<?=base_url()?>plugins/toastr/toastr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>calendar/lib/main.css">
	<link rel="stylesheet" href="sweetalert2.min.css">
    <script src="<?=base_url()?>calendar/lib/main.min.js"></script>
    <script src="<?=base_url()?>calendar/lib/es.js"></script>
    <style type="text/css">
   .loader {
		position: fixed;
		left: 100px;
		top: 0px;
		width: 100%;
		max-width: 100%;
		height: 100%;
		z-index: 999999;
		background: url('<?=base_url()?>media/cargando7.gif') 50% 50% no-repeat;
		opacity: .9;
		display: none;
	}
	.valor {
		text-align: end;
	}

    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background-color: #2F3C4F;">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" style="color: #fff;" href="#"><i
                            class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="<?=base_url()?>login/iniciar" style="color: #fff;" class="nav-link"><i
                            class="fas fa-home"></i>&nbsp;&nbsp; Inicio</a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #3D3D3D; ">
            <!-- Brand Logo -->
            <br>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="">
                    <div class="image">
                        <img src="<?=base_url()?>media/logo/logo_codiesel.png" class="img-fluid" alt="Responsive image">
                    </div>
                </div>                
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

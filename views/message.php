<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Message Note</title>
  <link rel="shortcut icon" href="./public/img/logo.png" type="image/x-icon">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="./public/plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="./public/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="./public/dist/css/adminlte.min.css">
  <script src="./public/jquery-3.5.1.min.js"></script>
  <link rel="stylesheet" href="./public/mycss.css">
  <script src="./public/mycss.js"></script>
</head>

<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <span id="db-size" class="nav-link text-warning">
            <?php echo $data['db_size'] ?>
          </span>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link" href="./?url=logout">
            <i class="fas fa-sign-out-alt"></i>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>
      </ul>
    </nav>


    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <a href="./" class="brand-link">
        <img src="<?php echo $_SESSION['message_userAVATAR'] ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Message Note</span>
      </a>

     
      <div id="getHeightSidebar" class="sidebar" style="margin-top:60px;">
        <div class="form-inline">
          <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            
            <li class="nav-item">
              <a href="./" class="nav-link <?php if(!isset($_GET['url'])) echo 'active'; ?>">
                <i class="nav-icon fas fa-comment-alt"></i>
                <p>My Message</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="#" class="nav-link" data-toggle="modal" data-target="#staticBackdrop">
                <i class="nav-icon fas fa-user"></i>
                <p>Account</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link" id="show-active-log">
                <i class="nav-icon fas fa-user-clock"></i>
                <p>Active Log</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="./?url=forums" class="nav-link <?php CheckUrl('forums') ?>">
                <i class="nav-icon fas fa-globe-africa"></i>
                <p>Forums <span class="right badge badge-danger">New</span></p>
              </a>
            </li>

            <li class="nav-item">
              <a href="./?url=logout" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>Logout</p>
              </a>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0"><?php if(!isset($_GET['url'])) echo 'My Message'; else echo 'Forums'; ?></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="./">Home</a></li>
                <li class="breadcrumb-item active"><?php if(!isset($_GET['url'])) echo 'My Message'; else echo 'Forums'; ?></li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <?php require_once './views/pages/'.$data['page'].'.php'; ?>
      </section>


    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer">
      <strong>Copyright &copy; 2014-<?php echo date('Y') ?> <a href="./">Message</a>.</strong>
      All rights reserved.
      <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 3.2.0-rc
      </div>
    </footer>
  </div>
  <!-- ./wrapper -->


<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-uppercase" id="staticBackdropLabel">account information</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img id="img-avatar-user" class="rounded mx-auto d-block" src="<?php echo $_SESSION['message_userAVATAR'] ?>" style="border-radius: 40px !important; border: 1px solid; height: 80px; width: 80px;">
        <small class="form-text text-center text-muted">Click on avatar to change.</small>
        <input type="file" id="file-avatar" accept="image/png, image/jpeg" hidden>
        
        <form id="form-account-infomation" method="post" action="index.php?url=process-update-account">
          <div class="form-group">
              <label>User name</label>
              <input type="text" class="form-control" name="username" value="<?php echo $_SESSION['message_userNAME'] ?>" autocomplete="off" required>
              <?php if(isset($_COOKIE['error_message'])){ ?>
              <small class="form-text text-warning"><i class="icon fas fa-exclamation-triangle"></i> <?php echo $_COOKIE['error_message'] ?></small>
              <?php } ?>
          </div>
          <div class="form-group">
              <label>IP address</label>
              <input type="text" class="form-control" value="<?php echo $_SESSION['message_userIPADRESS'] ?>" readonly>
          </div>
          <div class="form-group">
              <label>Role</label>
              <input type="text" class="form-control text-capitalize" value="<?php echo $_SESSION['message_userROLE'] ?>" readonly>
          </div>
          <div class="form-group">
              <label>Join date</label>
              <input type="text" class="form-control" value="<?php echo ConvertDate($_SESSION['message_userJOIN']) ?>" readonly>
          </div>
          <div class="form-group">
              <label>A new password</label>
              <input type="password" name="new_password" class="form-control">
              <?php if(isset($_COOKIE['error_pass'])){ ?>
              <small class="form-text text-warning"><i class="icon fas fa-exclamation-triangle"></i> <?php echo $_COOKIE['error_pass'] ?></small>
              <?php } ?>
          </div>
          <div class="form-group">
              <label>Confirm password</label>
              <input type="password" name="confirm_password" class="form-control" required>
          </div>
          <button id="btn-summit" type="submit" class="btn btn-primary" hidden>Submit</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="btn-update" type="button" class="btn btn-primary">Update now</button>
      </div>
    </div>
  </div>
</div>

<!-- Active Log Modal -->
<div class="modal fade" id="ActiveLog" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticActiveLog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-uppercase" id="staticActiveLog">active log</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div id="append-active-log"></div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#img-avatar-user').click(function(){
            $('#file-avatar').click();
        });

        $("#file-avatar").change(function(){
            var fd = new FormData();
            var files = $('#file-avatar')[0].files;
            // Check file selected or not
            if(files.length > 0 ){
               fd.append('file',files[0]);

               $.ajax({
                    url: 'index.php?url=process-change-avatar',
                    type: 'post',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function(){
                        location.reload();
                    },
                });
            }else{
               alert("Please select a file!");
            }
        });

        $('#show-active-log').click(function(){
            $.ajax({
                url: 'index.php?url=ajax-get-active-log',
                type: 'post',
                data: {},
                success: function(data){
                    $('#append-active-log').html(data);
                    $('#ActiveLog').modal('show');
                },
            });
        });
    });
</script>

  <!-- REQUIRED SCRIPTS -->
  <!-- jQuery -->
  <script src="./public/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="./public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- overlayScrollbars -->
  <script src="./public/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- AdminLTE App -->
  <script src="./public/dist/js/adminlte.js"></script>

  <!-- PAGE PLUGINS -->
  <!-- jQuery Mapael -->
  <script src="./public/plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
  <script src="./public/plugins/raphael/raphael.min.js"></script>
  <script src="./public/plugins/jquery-mapael/jquery.mapael.min.js"></script>
  <script src="./public/plugins/jquery-mapael/maps/usa_states.min.js"></script>
  <!-- ChartJS -->
  
    
    <script type="text/javascript">
        $(document).ready(function(){
            $('#btn-update').click(function(){
                $('#btn-summit').click();
            });
        });
        <?php if(isset($_COOKIE['error_message']) || isset($_COOKIE['error_pass'])){ ?>
        $('#staticBackdrop').modal('show');
        <?php } ?>
    </script>
    
</body>

</html>
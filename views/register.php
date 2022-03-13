<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register Message Note</title>
  <link rel="shortcut icon" href="./public/img/logo.png" type="image/x-icon">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="./public/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="./public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="./public/dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="./" class="h1"><b>MESSAGE NOTE</b></a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Register to start your session</p>
        <p class="login-box-msg">
            <?php if(isset($_COOKIE['error_message'])){ ?>
            <div class="alert alert-warning alert-dismissible">
                <i class="icon fas fa-exclamation-triangle"></i>
                <?php echo $_COOKIE['error_message'] ?>
            </div>
            <?php } ?>
        </p>

        <form action="?url=process-register" method="post">
          <div class="input-group mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" value="<?php if(isset($_COOKIE['error_message'])) echo $_COOKIE['error_user'] ?>" autocomplete="off" maxlength="150" autofocus required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="social-auth-links text-center mt-2 mb-3">
            <button class="btn btn-block btn-primary">Register now</button>
          </div>
        </form>
        <p class="mb-0 text-center" style="width:100%;">
          <a href="./" class="text-center">Login with my account</a>
        </p>
      </div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="./public/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="./public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="./public/dist/js/adminlte.min.js"></script>
</body>

</html>
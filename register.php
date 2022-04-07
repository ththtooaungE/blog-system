<?php
session_start();
require "config/config.php";
require "config/common.php";

if ($_POST) {
  if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || strlen($_POST['password']) < 5) {
// Backend validation
    if (empty($_POST['name'])) {
      $nameError = "Name can't be empty!";
    }
    if (empty($_POST['email'])) {
      $emailError = "Email can't be empty!";
    }
    if (empty($_POST['password'])) {
      $passwordError = "Passord can't be empty!";
    }
    if (!empty($_POST['password']) && strlen($_POST['password']) < 5) {
      $passwordError = "Passord must be longer than 4 characters.";
    }
  } else {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");

    $stmt->bindValue(':email',$_POST['email']);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if(empty($user)) {
      $stmt = $pdo->prepare("INSERT INTO users(name, email, password) VALUES(:name, :email, :password)");
      $result = $stmt ->execute(array(':name'=>$_POST['name'],':email'=>$_POST['email'],':password'=>password_hash($_POST['password'],PASSWORD_DEFAULT)));

      if ($result) {
        echo "<script>alert('Your account is created. Please login.');window.location.href = 'login.php';</script>";
        exit();
      }
    }
    echo "<script>alert('This email is used.');</script>";
  }
}
 ?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Blogs | Log in</title>

   <!-- Google Font: Source Sans Pro -->
   <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
   <!-- Font Awesome -->
   <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
   <!-- icheck bootstrap -->
   <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
   <!-- Theme style -->
   <link rel="stylesheet" href="dist/css/adminlte.min.css">
 </head>
 <body class="hold-transition login-page">
 <div class="login-box">
   <div class="login-logo">
     <a href="../../index2.html"><b>Blog</b></a>
   </div>
   <!-- /.login-logo -->
   <div class="card">
     <div class="card-body login-card-body">
       <p class="login-box-msg">Register to create a new account</p>

       <form action="" method="post">
         <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
         <span style="font-size:13px; color:red;"><?= $nameError ?? "" ?></span>
         <div class="input-group mb-3">
           <input type="text" name="name" class="form-control" placeholder="Name" required>
           <div class="input-group-append">
             <div class="input-group-text">
               <span class="fas fa-user"></span>
             </div>
           </div>
         </div>
         <span style="font-size:13px; color:red;"><?= $emailError ?? "" ?></span>
         <div class="input-group mb-3">
           <input type="email" name="email" class="form-control" placeholder="Email" required>
           <div class="input-group-append">
             <div class="input-group-text">
               <span class="fas fa-envelope"></span>
             </div>
           </div>
         </div>
         <span style="font-size:13px; color:red;"><?= $passwordError ?? "" ?></span>
         <div class="input-group mb-3">
           <input type="password" name="password" class="form-control" placeholder="Password" required>
           <div class="input-group-append">
             <div class="input-group-text">
               <span class="fas fa-lock"></span>
             </div>
           </div>
         </div>
         <div class="row">
           <div class="col-12">
             <button type="submit" class="btn btn-primary btn-block">Register</button>
             <a class="d-block m-1" href="login.php">Login</a>
           </div>
         </div>

       </form>
       <!-- <p class="mb-0">
         <a href="register.html" class="text-center">Register a new membership</a>
       </p> -->
     </div>
     <!-- /.login-card-body -->
   </div>
 </div>
 <!-- /.login-box -->

 <!-- jQuery -->
 <script src="plugins/jquery/jquery.min.js"></script>
 <!-- Bootstrap 4 -->
 <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
 <!-- AdminLTE App -->
 <script src="dist/js/adminlte.min.js"></script>
 </body>
 </html>

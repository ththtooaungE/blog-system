<?php
session_start();
require "../config/config.php";
require "../config/common.php";

if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in']) || empty($_SESSION['role'])) {
  header("Location: login.php");
  exit();
}

include 'header.php';

if ($_POST) {

  if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || strlen($_POST['password']) < 5 || $_POST['role'] == null) {
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
    if ($_POST['role'] == null) {
      $roleError = "Role can't be empty!";
    }
  } else {
    //check if email is duplicated
      $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");
      $stmt->bindValue(':email',$_POST['email']);
      $stmt->execute();
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

    //creating a new user
      if (empty($user)) {
        $stmt = $pdo->prepare("INSERT INTO users(name, email, password, role) VALUES(:name, :email, :password, :role)");
        $stmt->bindValue(':name',$_POST['name']);
        $stmt->bindValue(':email',$_POST['email']);
        $stmt->bindValue(':password',password_hash($_POST['password'],PASSWORD_DEFAULT));
        $stmt->bindValue(':role',$_POST['role'], PDO::PARAM_INT);
        $result = $stmt->execute();

        if ($result) {
          echo "<script>alert('New account is created.');window.location.href = 'user_list.php';</script>";
          exit();
        }
      }
      echo "<script>alert('This email is used.');</script>";
  }
}
 ?>

     <!-- Main content -->
     <div class="content">
       <div class="container-fluid">
         <div class="row">
           <div class="col-12">
             <div class="card">
               <div class="card-body login-card-body">
                 <p class="login-box-msg">Create a new account</p>

                 <form action="" method="post">
                   <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
                   <span style="font-size:13px; color:red;"><?= $nameError ?? "" ?></span>
                   <div class="input-group mb-3">
                     <input type="text" name="name" class="form-control" placeholder="Name" required>
                     <div class="input-group-append">
                       <div class="input-group-text">
                         <i class="fas fa-user"></i>
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
                   <span style="font-size:13px; color:red;"><?= $roleError ?? "" ?></span>
                   <div class="form-check">
                     <input class="form-check-input" type="radio" value="1" name="role" id="admin" required>
                     <label class="form-check-label" for="admin">Admin</label>
                   </div>
                   <div class="form-check">
                     <input class="form-check-input" type="radio" value="0" name="role" id="user" required>
                     <label class="form-check-label" for="user">User</label>
                   </div>

                   <button type="submit" class="btn btn-primary btn-block mt-3">Create</button>

                 </form>
               </div>
             </div>
           </div>
         </div>
         <!-- /.row -->
       </div><!-- /.container-fluid -->
     </div>
     <!-- /.content -->

 <?php

 include "footer.html";

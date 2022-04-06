<?php
session_start();
require "../config/config.php";

if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in']) || empty($_SESSION['role'])) {
  header("Location: login.php");
  exit();
}

include "header.php";

if ($_POST) {
  // Backend validation
  if (empty($_POST['name']) || empty($_POST['email']) || $_POST['role'] == null || (!empty($_POST['password']) && strlen($_POST['password'])<5)) {
    if (empty($_POST['name'])) {
      $nameError = "Name can't be empty!";
    }
    if (empty($_POST['email'])) {
      $emailError = "Email can't be empty!";
    }
    if ($_POST['role'] == null) {
      $roleError = "Role can't be empty!";
    }
    if (!empty($_POST['password']) && strlen($_POST['password'])<5) {
      $passwordError = "Password must be longer than 4 characters.";
    }
  } else {
    //checking the edited email is duplicated
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND NOT id = ".$_GET['id']);
    $stmt->execute(array(':email'=>$_POST['email']));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$result) {

      if ($_POST['password']) {
        $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email, password = :password, role = :role WHERE id =".$_GET['id']);
        $result = $stmt->execute(array(':name' => $_POST['name'],':email' => $_POST['email'],':password' => password_hash($_POST['password'],PASSWORD_DEFAULT),':role' => $_POST['role'],));

      } else {
        $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email, role = :role WHERE id =".$_GET['id']);
        $result = $stmt->execute(array(':name' => $_POST['name'],':email' => $_POST['email'],':role' => $_POST['role'],));
      }
      if ($result) {
        echo "<script>alert('The record is successfully updated.');</script>";
        //try to update that
        // if ($_GET['id'] == $_SESSION['user_id']) {
        //   echo "<script>alert('Your account informations are updated successfully. Please log in.');</script>";
        //   header('location: user_delete.php');
        //   exit();
        // }
        echo "<script>window.location.href = 'user_list.php';</script>";
        exit();
      }
    }
  }
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id =".$_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

 ?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <form class="form" action="" method="post" enctype="multipart/form-data">
                  <div class="form-group">
                    <label for="name">Name</label>
                    <span style="font-size:13px; color:red;"><?= $nameError ?? "" ?></span>
                    <input type="text" name="name" class="form-control" value="<?= $result[0]['name'] ?> " required>
                  </div>
                  <div class="form-group">
                    <label for="email">Email</label>
                    <span style="font-size:13px; color:red;"><?= $emailError ?? "" ?></span>
                    <input type="text" name="email" class="form-control" value="<?= $result[0]['email'] ?>" required>
                  </div>
                  <div class="form-group">
                    <label for="email">Password</label>
                    <span style="font-size:13px; color:red;"><?= $passwordError ?? "" ?></span><br>
                    <span style="font-size:13px;">The user has already had the password.</span>
                    <input type="text" name="password" class="form-control" value="">
                  </div>
                  <span style="font-size:13px; color:red;"><?= $roleError ?? "" ?></span>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" value="1" <?= $result[0]['role']==1 ? "checked" : ""?> name="role" id="admin" required>
                    <label class="form-check-label" for="admin">Admin</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" value="0" <?= $result[0]['role']==0 ? "checked" : ""?> name="role" id="user" required>
                    <label class="form-check-label" for="user">User</label>
                  </div>
                  <div class="form-group">
                    <input type="submit" class="btn btn-success" name="" value="Submit">
                    <a href="user_list.php" type="button" class="btn btn-warning">Back</a>
                  </div>
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

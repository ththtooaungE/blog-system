<?php
session_start();
require "../config/config.php";

if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in']) || empty($_SESSION['role'])) {
  header("Location: login.php");
  exit();
}

include "header.php";

if ($_POST) {
  $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND NOT id = ".$_GET['id']);
  $stmt->execute(array(':email'=>$_POST['email']));
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if (!$result) {
    if ($_POST['role'] == "admin") {
      $role = 1;
    } else {
      $role = 0;
    }
    $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email, role = :role WHERE id =".$_GET['id']);
    $result = $stmt->execute(array(':name' => $_POST['name'],':email' => $_POST['email'],':role' => $role,)
    );
    if ($result) {
      echo "<script>alert('The record is successfully updated.');</script>";
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
                    <input type="text" name="name" class="form-control" value="<?= $result[0]['name'] ?>">
                  </div>
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" name="email" class="form-control" value="<?= $result[0]['email'] ?>">
                  </div>
                  <div class="form-group">
                    <label for="role">Role</label>
                    <input type="text" name="role" class="form-control" value="<?= $result[0]['role'] ? "admin" : "user"?>">
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

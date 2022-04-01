<?php
session_start();
require "../config/config.php";

if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in']) || empty($_SESSION['role'])) {
  header("Location: login.php");
}

include "header.php";

if ($_POST) {
  $file = $_FILES['image']['name'];
  $imagetype = pathinfo($file,PATHINFO_EXTENSION);

  if ($imagetype !== 'jpg' && $imagetype !== 'png' && $imagetype !== 'jpeg') {
    echo "<script>alert('The image type must be jpg or png or jpeg');</script>";
  } else {
    move_uploaded_file($_FILES['image']['tmp_name'],'../images/'.$file);
    $stmt = $pdo->prepare("INSERT INTO posts(title, content, image, author_id) VALUES(:title, :content, :image, :author_id)");
    $result = $stmt->execute(
      array(
        ":title"=>$_POST['title'],
        ":content"=>$_POST['content'],
        ":image"=>$_FILES['image']['name'],
        ":author_id"=>$_SESSION['user_id']
      )
    );
    if ($result) {
      echo "<script>alert('The new data is successfully added.');window.location.href = 'index.php';</script>";
    }
  }
}
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
                    <label for="title">Title</label>
                    <input type="text" name="title" class="form-control" value="" required>
                  </div>
                  <div class="form-group">
                    <label for="content">Content</label>
                    <textarea name="content" rows="8" cols="80" class="form-control" required></textarea>
                  </div>
                  <div class="form-group">
                    <label for="image">Image</label><br>
                    <input type="file" name="image" value="" required>
                  </div>
                  <div class="form-group">
                    <input type="submit" class="btn btn-success" name="" value="Submit">
                    <a href="index.php" type="button" class="btn btn-warning">Back</a>
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

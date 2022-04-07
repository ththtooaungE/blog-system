<?php
session_start();
require "../config/config.php";
require "../config/common.php";

if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in']) || empty($_SESSION['role'])) {
  header("Location: login.php");
  exit();
}

include "header.php";

if ($_POST) {
  if (empty($_POST['title']) || empty($_POST['content'])) {

    if (empty($_POST['title'])) {
      $titleError = "*Title can't be empty!";
    }
    if (empty($_POST['content'])) {
      $contentError = "*Content can't be empty!";
    }

  } else {
    if (!empty($_FILES['image']['name'])) {

      $imagetype = pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION);

      if ($imagetype !== 'png' && $imagetype !== 'jpg' && $imagetype !== 'jpeg') {
        echo "<script>alert('The image type must be jpg or png or jpeg.')</script>";
      } else {
        move_uploaded_file($_FILES['image']['tmp_name'],'../images/'.$_FILES['image']['name']);
        $stmt = $pdo->prepare("UPDATE posts SET title = :title, content = :content, image = :image WHERE id =".$_POST['id']);
        $result = $stmt->execute(
          array(
            ':title' => $_POST['title'],
            ':content' => $_POST['content'],
            ':image' => $_FILES['image']['name']
          )
        );
        if ($result) {
          echo "<script>alert('The record is successfully updated.');window.location.href = 'index.php';</script>";
          exit();
        }
      }
    } else {
      $stmt = $pdo->prepare("UPDATE posts SET title = :title, content = :content WHERE id =".$_POST['id']);
      $result = $stmt->execute(
        array(
          ':title' => $_POST['title'],
          ':content' => $_POST['content'],
        )
      );
      if ($result) {
        echo "<script>alert('The record is successfully updated.');window.location.href = 'index.php';</script>";
        exit();
      }
    } // $_FILES exists
  }


} //$_POST end

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id =".$_GET['id']);
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
                  <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
                  <input type="hidden" name="id" value="<?= $result[0]['id'] ?>">
                  <div class="form-group">
                    <label for="title">Title</label>
                    <br><span style="font-size:13px; color:red;"><?= $titleError ?? "" ?></span>
                    <input type="text" name="title" class="form-control" value="<?= escape($result[0]['title']) ?>">
                  </div>
                  <div class="form-group">
                    <label for="content">Content</label>
                    <br><span style="font-size:13px; color:red;"><?= $contentError ?? "" ?></span>
                    <textarea name="content" rows="8" cols="80" class="form-control"><?= escape($result[0]['content']) ?></textarea>
                  </div>
                  <div class="form-group">
                    <label for="image">Image</label><br>
                    <div>
                      <img width="150px" src="../images/<?= $result[0]['image'] ?>" alt="<?= $result[0]['title'] ?>">
                    </div><br>
                    <input type="file" name="image" value="">
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

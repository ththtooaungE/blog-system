<?php
  session_start();
  require "config/config.php";
  require "config/common.php";

  if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
  }

  // print_r($_SESSION);

  if ($_POST) {
    //backend validation
    if (empty($_POST['comment'])) {
      $commentError = "Comment must not be empty.";
    } else {
      $stmt = $pdo->prepare("INSERT INTO comments(content, author_id, post_id) VALUES(:content, :author_id, :post_id)");

      $stmt->bindValue(':content',$_POST['comment']);
      $stmt->bindValue(':author_id',$_SESSION['user_id']);
      $stmt->bindValue(':post_id',$_GET['id']);
      $result = $stmt->execute();
    }
  }
 ?>
 <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>tt's Blogs</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <div class="">

    <?php
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id =".$_GET['id']);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
     ?>
    <section class="content">
      <!-- Box Comment -->

      <div class="card card-widget">
        <section class="content-header">
            <h1 class="p-3 text-center"><?= $result[0]['title'] ?></h1>
        </section>
      </div>
      <div class="card-body">
        <img class="" width="100%" src="images/<?= $result[0]['image'] ?>" alt="<?= $result[0]['image'] ?>">
        <p><?= $result[0]['content'] ?></p>
        <h3>Comments</h3><hr>
      </div>
      <!-- /.card-body -->

      <?php
      $stmt = $pdo->prepare("SELECT * FROM comments WHERE post_id =".$_GET['id']);
      $stmt->execute();
      $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
       ?>
      <div class="card-footer card-comments">
        <?php
        if ($comments) {
          $i = 1;
          foreach ($comments as $comment) {
            $stmt = $pdo->prepare("SELECT name FROM users WHERE id =".$comment['author_id']);
            $stmt->execute();
            $user_name = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <div class="card-comment">
              <div class="comment-text ml-0">
                <span class="username"><?= $user_name[0]['name'] ?><span class="text-muted float-right"><?=  date("h:i:s d/M/Y", strtotime($comment['created_at'])) ?></span>
                </span><!-- /.username -->
                <?= $comment['content'] ?>
              </div>
            </div>
            <?php
            $i++;
          }
        }
         ?>
      </div>
      <!-- /.card-footer -->
      <div class="card-footer">
        <form action="" method="post">
          <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
          <!-- <img class="img-fluid img-circle img-sm" src="dist/img/user4-128x128.jpg" alt="Alt Text"> -->
          <!-- .img-push is used to add margin to elements next to floating images -->
          <!-- <input type="submit" class="btn btn-primary mb-3" name="" value="Done"> -->
          <span style="font-size:13px; color:red;"><?= $commentError ?? "" ?></span>
          <div class="img-push">
            <input type="text" name="comment" class="form-control form-control-sm" placeholder="Press enter to post comment">
          </div>
        </form>
        <a href="/blog?page_num=<?= $_GET['page_num'] ?>" class="btn btn-primary mt-3" width="300px">Back</a>

      </div>
        <!-- /.card-footer -->
    </section>

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer" style="margin-left:0">
    <strong>Copyright &copy; 2021-2022 <a href="#">A Programmer</a>.</strong> All rights reserved.
  </footer>

  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<!-- <script src="dist/js/demo.js"></script> -->
</body>
</html>

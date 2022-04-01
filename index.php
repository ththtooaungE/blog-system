<?php
  session_start();
  require "config/config.php";

  if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
  }

  require "header.php";

  if(!empty($_GET['page_num'])) {
    $page_num = $_GET['page_num'];
  } else {
    $page_num = 1;
  }


  $records_per_page = 6;

  $stmt = $pdo->prepare("SELECT COUNT(*) FROM posts");
  $stmt->execute();
  $total_records = $stmt->fetchColumn();

  $total_pages = ceil($total_records/$records_per_page);
  $offsetnum = ($page_num - 1) * $records_per_page;


  //getting blog posts according page numbers

  $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT :offsetnum, :recordsperpage");

  $stmt->bindValue(':offsetnum', $offsetnum, PDO::PARAM_INT);
  $stmt->bindValue(':recordsperpage', $records_per_page, PDO::PARAM_INT);
  $stmt->execute();

  $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
  // print"<pre>";
  // print_r($blogs);

   ?>

        <div class="row">
          <?php
          //showing blog posts
          if ($blogs) {
            $i = 1;
            foreach ($blogs as $blog) {
              ?>
              <div class="col-md-4">
                <div class="card card-widget m-3">
                  <div class="card-header">
                    <div class="card-title" style="float:none">
                      <h4 style="text-align:center"><?= $blog['title'] ?></h4>
                    </div>
                  </div>
                  <div class="card-body">
                    <a href="blogdetail.php?id=<?= $blog['id'] ?>&page_num=<?= $page_num ?>"><img style="width: 100%; height: 300px;object-fit: cover"
                      src="images/<?= $blog['image'] ?>" alt="<?= $result[0]['title'] ?>"></a>
                  </div>
                </div>
              </div>
              <?php
              $i++;
            }
          }
           ?>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <nav aria-lable="Page naviagtion example">
      <ul class="pagination justify-content-center">
        <li class="page-item  <?php if($page_num == 1) {echo "disabled";} ?>"><a class="page-link" href="?page_num=1"><<</a></li>
        <li class="page-item <?php if($page_num <= 1) {echo "disabled";} ?>">
          <a class="page-link" href="<?php if ($page_num <= 1) {echo "";} else {echo "?page_num=".($page_num-1);} ?>">Previous</a>
        </li>
        <li class="page-item active"><a class="page-link" href=""><?= $page_num ?></a></li>
        <li class="page-item <?php if($page_num >= $total_pages) echo "disabled"; ?>">
          <a class="page-link" href="<?php if ($page_num >= $total_pages) {echo "";} else {echo "?page_num=".($page_num+1);} ?>">Next</a>
        </li>
        <li class="page-item  <?php if($page_num == $total_pages) {echo "disabled";} ?>"><a class="page-link" href="?page_num=<?= $total_pages ?>">>></a></li>
      </ul>
    </nav>

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>

  <footer class="main-footer" style="margin-left:0;background-color:pink">
    <strong>Copyright &copy; 2021-2022 <a href="#">A Programmer</a>.</strong> All rights reserved.
    <a href="logout.php" class="btn btn-primary mr-5" style="float:right">Log out</a>

  </footer>

</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

</body>
</html>

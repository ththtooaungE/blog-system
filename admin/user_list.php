<?php
  session_start();
  require "../config/config.php";

  if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in']) || empty($_SESSION['role'])) {
    header("Location: login.php");
  }

  include "header.php";

  if (empty($_POST['search'])) {

    $stmt = $pdo->prepare("SELECT * FROM users");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

  } else {
//for search box
    $search = $_POST['search'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE CONCAT('%', :name, '%') OR email LIKE CONCAT('%', :email, '%')");

    $stmt->bindValue(':name', $search, PDO::PARAM_STR);
    $stmt->bindValue(':email', $search, PDO::PARAM_STR);
    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

  }




 ?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Blog Listings</h3>
              </div>


              <!-- /.card-header -->
              <div class="card-body">
                <a href="user_add.php" type="button" class="btn btn-primary mb-3">Create new user</a>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Role</th>
                      <th style="width: 200px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // print"<pre>";
                    // print_r($users);
                    if ($users) {
                      $i = 1;
                      foreach ($users as $user) {
                        ?>
                        <tr>
                          <td><?= $i.'.' ?></td>
                          <td><?= $user['name'] ?></td>
                          <td><?= $user['email'] ?></td>
                          <td><?php echo (int)$user['role'] ? "admin" : "user" ?></td>
                          <td>
                            <a href="user_edit.php?id=<?= $user['id'] ?>" type="button" class="btn btn-warning">Edit</a>
                            <a href="user_delete.php?id=<?= $user['id'] ?>" type="button"
                              onclick="return confirm('Are you sure you want do delete this item?')" class="btn btn-danger">Delete</a>
                          </td>
                        </tr>
                        <?php
                        $i++;
                      }
                    }
                     ?>

                  </tbody>
                </table>
              </div>
              <!-- card-body ends -->

            </div>

          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

<?php

include "footer.html";

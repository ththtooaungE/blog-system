<?php
// I used this passwordHashing.php file to hash plain text passwords in the database that I've already added


// require '../config/config.php';
//
// $stmt = $pdo->prepare("SELECT * FROM users");
// $stmt->execute();
// $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
// $i = 0;
//
// // print"<pre>";
// // print_r($user['password']);
//
// foreach($users as $user) {
//   $hashedPassword = password_hash($user['password'],PASSWORD_DEFAULT);
//   $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
//   $result = $stmt->execute(array(':password'=>$hashedPassword, 'id'=>$user['id'],));
// }

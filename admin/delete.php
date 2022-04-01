<?php
session_start();
require "../config/config.php";

if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in']) || empty($_SESSION['role'])) {
  header("Location: login.php");
  exit();
}

$stmt = $pdo->prepare("DELETE FROM posts WHERE id =".$_GET['id']);
$stmt->execute();

header("location: index.php");
exit();

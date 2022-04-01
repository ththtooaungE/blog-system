<?php

$options = array(
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
);

$pdo = new PDO(
  'mysql:host=localhost;dbname=blog','root','',$options
);

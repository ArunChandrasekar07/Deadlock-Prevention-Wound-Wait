<?php
function db_connect() {
  $host = "localhost";
  $user = "root";
  $pass = "";
  $db   = "railway_demo";
  $conn = new mysqli($host, $user, $pass, $db);
  if ($conn->connect_error) { die("DB connection failed"); }
  $conn->set_charset("utf8mb4");
  return $conn;
}

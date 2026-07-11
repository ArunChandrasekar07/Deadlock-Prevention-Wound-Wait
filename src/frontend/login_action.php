<?php
require_once __DIR__ . "/../backend/db.php";
require_once __DIR__ . "/../backend/auth_service.php";
require_once __DIR__ . "/../backend/session_guard.php";

$username = trim($_POST["user"] ?? "");
$password = $_POST["pass"] ?? "";

if ($username === "" || $password === "") {
    header("Location: login.html?error=" . urlencode("Enter both a username and password."));
    exit;
}

$conn = db_connect();

if (!verify_user($conn, $username, $password)) {
    header("Location: login.html?error=" . urlencode("Incorrect username or password."));
    exit;
}

$_SESSION["username"] = $username;
$_SESSION["login_ts"] = time();

header("Location: train.php");
exit;
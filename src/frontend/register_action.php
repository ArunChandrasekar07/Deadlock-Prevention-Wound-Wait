<?php
require_once __DIR__ . "/../backend/db.php";
require_once __DIR__ . "/../backend/auth_service.php";

$name     = trim($_POST["name"] ?? "");
$age      = intval($_POST["age"] ?? 0);
$city     = trim($_POST["city"] ?? "");
$email    = trim($_POST["email"] ?? "");
$username = trim($_POST["user"] ?? "");
$password = $_POST["pass"] ?? "";

if ($name === "" || $age <= 0 || $city === "" || $email === "" || $username === "" || $password === "") {
    header("Location: register.html?error=" . urlencode("Please fill in every field."));
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: register.html?error=" . urlencode("That doesn't look like a valid email."));
    exit;
}

$conn = db_connect();
$result = register_user($conn, $name, $age, $city, $email, $username, $password);

if (!$result["ok"]) {
    header("Location: register.html?error=" . urlencode($result["error"]));
    exit;
}

header("Location: login.html?registered=1");
exit;
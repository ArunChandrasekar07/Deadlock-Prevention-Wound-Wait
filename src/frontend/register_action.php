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

// --- TEMPORARY DEBUG BLOCK — remove once this is working ---
error_log("=== register attempt ===");
error_log("Connected DB: " . pg_dbname($conn) . " @ " . pg_host($conn) . ":" . pg_port($conn));
$check = pg_query($conn, "SELECT to_regclass('public.users') AS exists");
$row = pg_fetch_assoc($check);
error_log("users table visible to this connection: " . var_export($row["exists"], true));
// --- END DEBUG BLOCK ---

$result = register_user($conn, $name, $age, $city, $email, $username, $password);

// --- TEMPORARY DEBUG BLOCK — remove once this is working ---
if (!$result["ok"]) {
    error_log("register_user failed: " . $result["error"]);
    error_log("pg_last_error: " . pg_last_error($conn));
}
// --- END DEBUG BLOCK ---

if (!$result["ok"]) {
    header("Location: register.html?error=" . urlencode($result["error"]));
    exit;
}

header("Location: login.html?registered=1");
exit;
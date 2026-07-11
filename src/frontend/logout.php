<?php
require_once __DIR__ . "/../backend/session_guard.php";

$_SESSION = [];
session_destroy();

header("Location: login.html");
exit;
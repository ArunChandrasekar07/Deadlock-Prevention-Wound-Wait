<?php
function db_connect() {
    $host = getenv("DB_HOST");
    $port = getenv("DB_PORT") ?: 5432;
    $user = getenv("DB_USER");
    $pass = getenv("DB_PASS");
    $db   = getenv("DB_NAME");

    $conn_string = "host=$host port=$port dbname=$db user=$user password=$pass sslmode=require";
    $conn = pg_connect($conn_string);

    if (!$conn) {
        die("DB connection failed");
    }
    return $conn;
}
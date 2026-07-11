<?php
require_once __DIR__ . "/db.php";

/**
 * Registers a new user. Passwords are hashed with password_hash()
 * (bcrypt by default) — never store or compare plaintext passwords.
 */
function register_user($conn, $name, $age, $city, $email, $username, $password) {
    $existing = pg_query_params(
        $conn,
        "SELECT id FROM users WHERE username = $1 OR email = $2",
        [$username, $email]
    );
    if ($existing && pg_num_rows($existing) > 0) {
        return ["ok" => false, "error" => "That username or email is already registered."];
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $ins = pg_query_params(
        $conn,
        "INSERT INTO users(name, age, city, email, username, password_hash) VALUES ($1,$2,$3,$4,$5,$6)",
        [$name, $age, $city, $email, $username, $hash]
    );

    if (!$ins) {
        return ["ok" => false, "error" => "Registration failed. Please try again."];
    }
    return ["ok" => true];
}

/**
 * Verifies a username/password pair against the stored hash.
 * Returns true/false — never leaks whether it was the username
 * or the password that was wrong.
 */
function verify_user($conn, $username, $password) {
    $result = pg_query_params(
        $conn,
        "SELECT password_hash FROM users WHERE username = $1",
        [$username]
    );
    if (!$result || pg_num_rows($result) === 0) {
        return false;
    }
    $row = pg_fetch_assoc($result);
    return password_verify($password, $row["password_hash"]);
}
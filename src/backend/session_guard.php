<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Call at the top of any page that requires a logged-in user.
 * Redirects to login.html if no session exists.
 */
function require_login() {
    if (!isset($_SESSION["username"])) {
        header("Location: login.html?redirect=1");
        exit;
    }
}

function current_username() {
    return $_SESSION["username"] ?? null;
}
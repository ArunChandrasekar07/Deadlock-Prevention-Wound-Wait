<?php
require_once __DIR__ . "/../backend/db.php";
require_once __DIR__ . "/../backend/lock_service.php";
require_once __DIR__ . "/../backend/session_guard.php";

require_login();

$train_no = $_POST["train_no"] ?? "00000";
$seats = $_POST["seats"] ?? [];

if (!is_array($seats) || count($seats) === 0) {
  header("Location: seat.php?train_no=".urlencode($train_no));
  exit;
}

// CHANGED: transaction identity is now tied to the logged-in user
// instead of a random per-session token. This also fixes a bug
// where refreshing the page could silently generate a brand new
// (younger) transaction id, breaking wound-wait ordering.
$txn_id = current_username();
$ts_key = "txn_ts_" . $train_no;
if (!isset($_SESSION[$ts_key])) {
  $_SESSION[$ts_key] = time();
}
$txn_ts = intval($_SESSION[$ts_key]);

$conn = db_connect();
release_expired_locks($conn);

// CHANGED: everything below now runs inside one real transaction.
// Before, each acquire_seat_lock() call opened and closed its own
// implicit transaction (PHP's pg_query autocommits by default), so
// the "SELECT ... FOR UPDATE" inside it released its row lock the
// instant that single query finished — before the INSERT/DELETE
// that followed. Two concurrent requests could both read "seat
// free" and both insert. Wrapping the whole batch in BEGIN/COMMIT
// makes the row locks actually hold for the full operation.
//
// This also replaces the old "alert + redirect, but leave already
// -acquired locks dangling" behavior: on any failure we ROLLBACK,
// which undoes every lock acquired earlier in this same request.
pg_query($conn, "BEGIN");

$locked = [];
$failed = [];
foreach($seats as $seat){
  $ok = acquire_seat_lock($conn, $train_no."-".$seat, $txn_id, $txn_ts);
  if($ok) $locked[] = $seat; else $failed[] = $seat;
}

if (count($failed) > 0) {
  pg_query($conn, "ROLLBACK");
  $msg = "Someone else locked ".implode(', ', $failed)." first. Please choose different seats.";
  echo "<script>alert(".json_encode($msg)."); window.location.href='seat.php?train_no=".htmlspecialchars($train_no,ENT_QUOTES)."';</script>";
  exit;
}

pg_query($conn, "COMMIT");

header("Location: payment.php?train_no=".urlencode($train_no)."&seats=".urlencode(implode(',', $locked)));
exit;
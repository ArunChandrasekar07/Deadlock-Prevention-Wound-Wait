<?php
require_once __DIR__ . "/../backend/db.php";
require_once __DIR__ . "/../backend/lock_service.php";

$train_no = $_POST["train_no"] ?? "00000";
$seats = $_POST["seats"] ?? [];

if (!is_array($seats) || count($seats) === 0) {
  header("Location: seat.php?train_no=".urlencode($train_no));
  exit;
}

session_start();
if (!isset($_SESSION["txn_id"])) {
  $_SESSION["txn_id"] = bin2hex(random_bytes(6));
  $_SESSION["txn_ts"] = time();
}
$txn_id = $_SESSION["txn_id"];
$txn_ts = intval($_SESSION["txn_ts"]);

$conn = db_connect();

$locked = [];
$failed = [];
foreach($seats as $seat){
  $ok = acquire_seat_lock($conn, $train_no."-".$seat, $txn_id, $txn_ts);
  if($ok) $locked[] = $seat; else $failed[] = $seat;
}

if (count($failed) > 0) {
  $msg = "Deadlock risk: another user locked seats before you. Please retry.";
  echo "<script>alert(".json_encode($msg)."); window.location.href='seat.php?train_no=".htmlspecialchars($train_no,ENT_QUOTES)."';</script>";
  exit;
}

header("Location: payment.php?train_no=".urlencode($train_no)."&seats=".urlencode(implode(',', $locked)));
exit;
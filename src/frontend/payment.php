<?php
$train_no = $_GET["train_no"] ?? "00000";
$seats = $_GET["seats"] ?? "";
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment</title>
  <style>
    *{box-sizing:border-box;font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial}
    body{margin:0;background:#fff}
    header{background:#f39c12;padding:14px 22px;display:flex;justify-content:space-between;align-items:center}
    header a{text-decoration:none;color:#111;font-weight:700;margin-left:14px}
    .box{max-width:900px;margin:28px auto;padding:0 16px;text-align:center}
    .timer{font-weight:900;color:#b00020;margin-bottom:14px}
    table{margin:0 auto;border-collapse:collapse;width:min(700px,96%)}
    td,th{border:1px solid #ddd;padding:12px;text-align:center}
    th{background:#fafafa}
    .btn{margin-top:18px;display:inline-block;background:#2e7d32;color:#fff;padding:10px 16px;border-radius:10px;text-decoration:none;font-weight:900}
  </style>
</head>
<body>
  <header>
    <div style="font-weight:800">Railway Booking</div>
    <nav>
      <a href="../index.html">Home</a>
      <a href="train.php">demo</a>
      <a href="login.html">Logout</a>
    </nav>
  </header>

  <div class="box">
    <div class="timer" id="timer">Time left: 05:00</div>
    <h2>Confirm Your Payment</h2>
    <table>
      <tr><th>Train Number</th><th>Selected Seats</th></tr>
      <tr><td><?=htmlspecialchars($train_no)?></td><td><?=htmlspecialchars($seats)?></td></tr>
    </table>
    <a class="btn" href="tickets.php?train_no=<?=urlencode($train_no)?>&seats=<?=urlencode($seats)?>">Proceed to Payment</a>
    <p style="color:#333;margin-top:10px">Note: If you do not complete payment within 5 minutes, your selected seats will be released.</p>
  </div>

<script>
let secs = 300;
const el = document.getElementById("timer");
const tick = () => {
  const m = String(Math.floor(secs/60)).padStart(2,'0');
  const s = String(secs%60).padStart(2,'0');
  el.textContent = "Time left: " + m + ":" + s;
  secs--;
  if(secs < 0){ window.location.href = "train.php"; return; }
  setTimeout(tick, 1000);
};
tick();
</script>
</body>
</html>

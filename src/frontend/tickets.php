<?php
$train_no = $_GET["train_no"] ?? "00000";
$seats = $_GET["seats"] ?? "";
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Ticket</title>
  <style>
    *{box-sizing:border-box;font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial}
    body{margin:0;background:#fff}
    header{background:#f39c12;padding:14px 22px;display:flex;justify-content:space-between;align-items:center}
    header a{text-decoration:none;color:#111;font-weight:700;margin-left:14px}
    .wrap{max-width:900px;margin:24px auto;padding:0 16px}
    .card{border:1px solid #ddd;border-radius:12px;padding:18px}
    h2{text-align:center;margin:6px 0 16px}
    table{width:100%;border-collapse:collapse}
    td,th{border:1px solid #ddd;padding:10px}
    th{background:#fafafa}
    .btn{margin-top:16px;display:inline-block;background:#1976d2;color:#fff;padding:10px 16px;border-radius:10px;text-decoration:none;font-weight:900}
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

  <div class="wrap">
    <div class="card">
      <h2>My Ticket</h2>
      <table>
        <tr><th>Train No</th><td><?=htmlspecialchars($train_no)?></td></tr>
        <tr><th>Seats</th><td><?=htmlspecialchars($seats)?></td></tr>
        <tr><th>Status</th><td>Booked</td></tr>
      </table>
      <a class="btn" href="#" onclick="window.print(); return false;">Print</a>
    </div>
  </div>
</body>
</html

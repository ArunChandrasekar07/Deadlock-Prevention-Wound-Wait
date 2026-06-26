<?php
// Demo page: list trains
$trains = [
  ["12345","Express Train A","Station A","08:00","02:30","10:30","Station B"],
  ["54321","Local Train C","Station E","10:00","01:15","11:15","Station F"],
  ["98765","High-Speed Train D","Station G","11:30","01:50","13:20","Station H"]
];
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Train Details</title>
  <style>
    *{box-sizing:border-box;font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial}
    body{margin:0;background:#fff}
    header{background:#f39c12;padding:14px 22px;display:flex;justify-content:space-between;align-items:center}
    header a{text-decoration:none;color:#111;font-weight:700;margin-left:14px}
    h1{margin:24px 0 10px;text-align:center}
    table{width:min(1100px,95%);margin:0 auto 24px;border-collapse:collapse}
    th,td{border:1px solid #ddd;padding:10px;text-align:center}
    th{background:#fafafa}
    .btn{padding:8px 12px;border-radius:8px;background:#0b7285;color:#fff;text-decoration:none;font-weight:700;display:inline-block}
  </style>
</head>
<body>
  <header>
    <div style="font-weight:800">Railway Booking</div>
    <nav>
      <a href="../index.html">Home</a>
      <a href="train.php">Demo</a>
      <a href="login.html">Logout</a>
    </nav>
  </header>

  <h1>Train Details</h1>
  <table>
    <thead>
      <tr>
        <th>Train Number</th><th>Train Name</th><th>Source</th><th>Departure</th><th>Duration</th><th>Arrival</th><th>Destination</th><th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($trains as $t){ ?>
      <tr>
        <td><?=htmlspecialchars($t[0])?></td>
        <td><?=htmlspecialchars($t[1])?></td>
        <td><?=htmlspecialchars($t[2])?></td>
        <td><?=htmlspecialchars($t[3])?></td>
        <td><?=htmlspecialchars($t[4])?></td>
        <td><?=htmlspecialchars($t[5])?></td>
        <td><?=htmlspecialchars($t[6])?></td>
        <td><a class="btn" href="seat.php?train_no=<?=urlencode($t[0])?>">Select Seats</a></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</body>
</html>

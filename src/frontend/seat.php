<?php
$train_no = isset($_GET["train_no"]) ? $_GET["train_no"] : "00000";
$seats = [];
for($i=1;$i<=10;$i++) $seats[] = "B".$i;
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Select Seats</title>
  <style>
    *{box-sizing:border-box;font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial}
    body{margin:0;background:#fff}
    header{background:#f39c12;padding:14px 22px;display:flex;justify-content:space-between;align-items:center}
    header a{text-decoration:none;color:#111;font-weight:700;margin-left:14px}
    h1{text-align:center;margin:22px 0 6px}
    table{width:min(900px,95%);margin:0 auto;border-collapse:collapse}
    th,td{border:1px solid #ddd;padding:10px;text-align:center}
    th{background:#fafafa}
    .wrap{max-width:900px;margin:0 auto 30px;padding:0 12px}
    button{padding:10px 14px;border-radius:10px;border:0;background:#6a1b9a;color:#fff;font-weight:800;cursor:pointer;margin-top:14px}
  </style>
</head>
<body>
  <header>
    <div style="font-weight:800">Railway Booking</div>
    <nav>
      <a href="../index.html">Home</a>
      <a href="train.php">Back</a>
      <a href="login.html">Logout</a>
    </nav>
  </header>

  <div class="wrap">
    <h1>Seat Details</h1>
    <p style="text-align:center;margin:0 0 16px;color:#333">Train No: <b><?=htmlspecialchars($train_no)?></b></p>

    <form action="lock_selected_seats.php" method="POST">
      <input type="hidden" name="train_no" value="<?=htmlspecialchars($train_no)?>">
      <table>
        <thead><tr><th>Seat</th><th>Status</th><th>Select</th></tr></thead>
        <tbody>
          <?php foreach($seats as $s){ ?>
          <tr>
            <td><?=htmlspecialchars($s)?></td>
            <td>Available</td>
            <td><input type="checkbox" name="seats[]" value="<?=htmlspecialchars($s)?>"></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <div style="text-align:left">
        <button type="submit">Lock Selected Seats</button>
      </div>
    </form>
  </div>
</body>
</html>

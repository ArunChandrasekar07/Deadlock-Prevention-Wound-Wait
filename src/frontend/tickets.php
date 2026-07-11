<?php
$train_no = $_GET["train_no"] ?? "00000";
$seats = $_GET["seats"] ?? "";
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Ticket</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    :root{
      --bg:#0A0E16; --panel:#12192A; --panel-2:#1A2334; --line:#26314A; --track:#3A4558;
      --text:#EDEFF5; --text-dim:#8D97AE; --amber:#F2B84B; --red:#E5484D; --green:#35D07F; --yellow:#FFD23F;
    }
    *{box-sizing:border-box;margin:0;padding:0;font-family:'Inter',system-ui,sans-serif;}
    .mono{font-family:'JetBrains Mono',monospace;}
    body{background:var(--bg);color:var(--text);position:relative;min-height:100vh;overflow-x:hidden;}
    body::before{
      content:"";position:fixed;inset:0;z-index:0;pointer-events:none;
      background-image:repeating-linear-gradient(90deg, rgba(58,69,88,0.35) 0 8px, transparent 8px 46px);
      background-position:0 78%;background-size:100% 4px;opacity:0.5;
    }
    header.nav{
      position:relative;z-index:2;
      display:flex;align-items:center;justify-content:space-between;
      padding:22px 6vw;border-bottom:1px solid var(--line);
    }
    .brand{display:flex;align-items:center;gap:12px;font-family:'Bebas Neue',sans-serif;font-size:1.5rem;letter-spacing:0.06em;}
    .brand-mark{
      width:40px;height:40px;border-radius:8px;display:flex;align-items:center;justify-content:center;
      background:linear-gradient(145deg,var(--panel-2),var(--panel));border:1px solid var(--line);
    }
    nav.links{display:flex;gap:2rem;}
    nav.links a{color:var(--text-dim);text-decoration:none;font-size:0.95rem;font-weight:500;position:relative;}
    nav.links a::after{content:"";position:absolute;left:0;bottom:-6px;width:0;height:2px;background:var(--yellow);transition:width 0.25s ease;}
    nav.links a:hover{color:var(--text);}
    nav.links a:hover::after{width:100%;}

    .wrap{position:relative;z-index:2;max-width:560px;margin:0 auto;padding:7vw 6vw;}

    .eyebrow{
      display:flex;align-items:center;gap:8px;justify-content:center;
      font-family:'JetBrains Mono',monospace;font-size:0.7rem;letter-spacing:0.14em;text-transform:uppercase;
      color:var(--green);background:rgba(53,208,127,0.08);border:1px solid rgba(53,208,127,0.25);
      padding:6px 12px;border-radius:999px;margin:0 auto 20px;width:fit-content;
    }
    .eyebrow::before{content:"";width:6px;height:6px;border-radius:50%;background:var(--green);box-shadow:0 0 6px var(--green);}

    .ticket{
      background:var(--panel);border:1px solid var(--line);border-radius:18px;overflow:hidden;
      box-shadow:0 30px 60px -30px rgba(0,0,0,0.6);
    }
    .ticket-top{padding:30px 30px 22px;text-align:center;}
    .ticket-top h2{font-family:'Bebas Neue',sans-serif;font-size:2.2rem;letter-spacing:0.01em;margin-bottom:4px;}
    .ticket-top p{color:var(--text-dim);font-size:0.85rem;}

    .perforation{
      position:relative;
      height:1px;
      border-top:1px dashed var(--track);
      margin:0 0;
    }
    .perforation::before,.perforation::after{
      content:"";position:absolute;top:-11px;width:22px;height:22px;background:var(--bg);border-radius:50%;
    }
    .perforation::before{left:-11px;}
    .perforation::after{right:-11px;}

    .ticket-body{padding:24px 30px 30px;}
    table{width:100%;border-collapse:collapse;}
    tr{border-bottom:1px solid var(--line);}
    tr:last-child{border-bottom:none;}
    th,td{padding:14px 0;text-align:left;font-weight:400;}
    th{
      font-family:'JetBrains Mono',monospace;font-size:0.72rem;letter-spacing:0.08em;text-transform:uppercase;
      color:var(--text-dim);width:130px;
    }
    td{font-weight:600;font-size:1rem;}
    td.mono{font-family:'JetBrains Mono',monospace;color:var(--yellow);}
    td.status{color:var(--green);}
    td.status::before{content:"● ";font-size:0.7rem;}

    .btn{
      margin-top:20px;display:block;width:100%;
      background:var(--yellow);color:#161116;
      padding:12px 16px;border-radius:9px;text-decoration:none;font-weight:800;text-align:center;
      box-shadow:0 8px 24px -8px rgba(255,210,63,0.5);
      transition:transform 0.2s ease, box-shadow 0.2s ease;
      border:0;cursor:pointer;font-size:0.95rem;font-family:'Inter',sans-serif;
    }
    .btn:hover{transform:translateY(-2px);box-shadow:0 12px 30px -8px rgba(255,210,63,0.65);}

    @media print{
      header.nav, .eyebrow, .btn{display:none;}
      body::before{display:none;}
      body{background:#fff;color:#111;}
      .ticket{border-color:#ccc;box-shadow:none;}
    }
  </style>
</head>
<body>
  <header class="nav">
    <div class="brand">
      <div class="brand-mark">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
          <rect x="3" y="6" width="18" height="11" rx="2" fill="#FFD23F"/>
          <circle cx="7.5" cy="19" r="2" fill="#EDEFF5"/>
          <circle cx="16.5" cy="19" r="2" fill="#EDEFF5"/>
          <rect x="6" y="8.5" width="4" height="4" rx="0.6" fill="#0A0E16"/>
          <rect x="14" y="8.5" width="4" height="4" rx="0.6" fill="#0A0E16"/>
        </svg>
      </div>
      <span>Railway Booking</span>
    </div>
    <nav class="links">
      <a href="../index.html">Home</a>
      <a href="train.php">demo</a>
      <a href="login.html">Logout</a>
    </nav>
  </header>

  <div class="wrap">
    <div class="eyebrow">Booking confirmed</div>
    <div class="ticket">
      <div class="ticket-top">
        <h2>My Ticket</h2>
        <p>Present this ticket for boarding</p>
      </div>
      <div class="perforation"></div>
      <div class="ticket-body">
        <table>
          <tr><th>Train No</th><td class="mono"><?=htmlspecialchars($train_no)?></td></tr>
          <tr><th>Seats</th><td><?=htmlspecialchars($seats)?></td></tr>
          <tr><th>Status</th><td class="status">Booked</td></tr>
        </table>
        <a class="btn" href="#" onclick="window.print(); return false;">Print</a>
      </div>
    </div>
  </div>
</body>
</html>
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

    .head{
      position:relative;z-index:2;
      max-width:900px;margin:0 auto;padding:6vw 6vw 1.5vw;text-align:center;
    }
    .eyebrow{
      display:inline-flex;align-items:center;gap:8px;
      font-family:'JetBrains Mono',monospace;font-size:0.7rem;letter-spacing:0.14em;text-transform:uppercase;
      color:var(--yellow);background:rgba(255,210,63,0.08);border:1px solid rgba(255,210,63,0.25);
      padding:6px 12px;border-radius:999px;margin-bottom:16px;
    }
    .head h1{font-family:'Bebas Neue',sans-serif;font-size:clamp(2.2rem,4.5vw,3rem);letter-spacing:0.01em;margin-bottom:8px;}
    .head p{color:var(--text-dim);font-size:0.95rem;}
    .head p b{color:var(--yellow);}

    .wrap{position:relative;z-index:2;max-width:760px;margin:0 auto;padding:0 6vw 7vw;}

    .panel{
      background:var(--panel);border:1px solid var(--line);border-radius:16px;padding:30px 28px;
    }

    .seat-grid{
      display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:26px;
    }
    .seat{position:relative;}
    .seat input{position:absolute;opacity:0;inset:0;width:100%;height:100%;margin:0;cursor:pointer;}
    .seat label{
      display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px;
      padding:16px 8px;border-radius:10px;border:1px solid var(--line);background:var(--bg);
      cursor:pointer;transition:border-color 0.15s ease, background 0.15s ease, transform 0.15s ease;
    }
    .seat label .code{font-family:'JetBrains Mono',monospace;font-weight:600;font-size:0.95rem;}
    .seat label .status{font-size:0.68rem;letter-spacing:0.05em;text-transform:uppercase;color:var(--green);}
    .seat input:checked + label{
      border-color:var(--yellow);background:rgba(255,210,63,0.08);transform:translateY(-2px);
    }
    .seat input:checked + label .status{color:var(--yellow);}
    .seat input:focus-visible + label{outline:2px solid var(--yellow);outline-offset:2px;}

    button{
      width:100%;padding:13px 14px;border:0;border-radius:9px;
      background:var(--yellow);color:#161116;font-weight:700;font-size:0.95rem;cursor:pointer;
      box-shadow:0 8px 24px -8px rgba(255,210,63,0.5);
      transition:transform 0.2s ease, box-shadow 0.2s ease;
    }
    button:hover{transform:translateY(-2px);box-shadow:0 12px 30px -8px rgba(255,210,63,0.65);}

    @media (max-width:560px){
      .seat-grid{grid-template-columns:repeat(3,1fr);}
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
      <a href="train.php">Back</a>
      <a href="login.html">Logout</a>
    </nav>
  </header>

  <section class="head">
    <div class="eyebrow">Coach B &middot; Seat lock</div>
    <h1>Seat Details</h1>
    <p>Train No: <b class="mono"><?=htmlspecialchars($train_no)?></b></p>
  </section>

  <section class="wrap">
    <div class="panel">
      <form action="lock_selected_seats.php" method="POST">
        <input type="hidden" name="train_no" value="<?=htmlspecialchars($train_no)?>">
        <div class="seat-grid">
          <?php foreach($seats as $s){ ?>
          <div class="seat">
            <input type="checkbox" id="seat-<?=htmlspecialchars($s)?>" name="seats[]" value="<?=htmlspecialchars($s)?>">
            <label for="seat-<?=htmlspecialchars($s)?>">
              <span class="code"><?=htmlspecialchars($s)?></span>
              <span class="status">Available</span>
            </label>
          </div>
          <?php } ?>
        </div>
        <button type="submit">Lock Selected Seats</button>
      </form>
    </div>
  </section>
</body>
</html>
<?php
$train_no = $_GET["train_no"] ?? "00000";
$seats = $_GET["seats"] ?? "";
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment</title>
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

    .wrap{position:relative;z-index:2;max-width:640px;margin:0 auto;padding:6vw 6vw;text-align:center;}

    .timer{
      display:inline-flex;align-items:center;gap:8px;
      font-family:'JetBrains Mono',monospace;font-weight:700;font-size:0.95rem;
      color:var(--red);
      background:rgba(229,72,77,0.1);
      border:1px solid rgba(229,72,77,0.3);
      padding:8px 16px;border-radius:999px;
      margin-bottom:22px;
    }
    .timer::before{
      content:"";width:7px;height:7px;border-radius:50%;background:var(--red);
      box-shadow:0 0 6px var(--red);
      animation:pulse 1.1s ease-in-out infinite;
    }
    @keyframes pulse{0%,100%{opacity:1;}50%{opacity:0.3;}}

    .wrap h2{font-family:'Bebas Neue',sans-serif;font-size:clamp(2rem,4.5vw,2.8rem);letter-spacing:0.01em;margin-bottom:26px;}

    .panel{
      background:var(--panel);border:1px solid var(--line);border-radius:16px;
      padding:26px 24px;text-align:left;
    }
    table{width:100%;border-collapse:collapse;}
    th{
      font-family:'JetBrains Mono',monospace;font-size:0.72rem;letter-spacing:0.08em;text-transform:uppercase;
      color:var(--text-dim);text-align:left;padding-bottom:10px;border-bottom:1px solid var(--line);
    }
    td{padding-top:14px;font-size:1.02rem;font-weight:600;}
    td.mono{font-family:'JetBrains Mono',monospace;color:var(--yellow);}

    .btn{
      margin-top:22px;display:inline-block;width:100%;
      background:var(--green);color:#08150E;
      padding:13px 16px;border-radius:9px;text-decoration:none;font-weight:800;text-align:center;
      box-shadow:0 8px 24px -8px rgba(53,208,127,0.45);
      transition:transform 0.2s ease, box-shadow 0.2s ease;
    }
    .btn:hover{transform:translateY(-2px);box-shadow:0 12px 30px -8px rgba(53,208,127,0.6);}

    .note{color:var(--text-dim);margin-top:14px;font-size:0.85rem;line-height:1.5;}
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
    <div class="timer mono" id="timer">Time left: 05:00</div>
    <h2>Confirm Your Payment</h2>
    <div class="panel">
      <table>
        <tr><th>Train Number</th><th>Selected Seats</th></tr>
        <tr><td class="mono"><?=htmlspecialchars($train_no)?></td><td><?=htmlspecialchars($seats)?></td></tr>
      </table>
      <a class="btn" href="tickets.php?train_no=<?=urlencode($train_no)?>&seats=<?=urlencode($seats)?>">Proceed to Payment</a>
    </div>
    <p class="note">Note: If you do not complete payment within 5 minutes, your selected seats will be released.</p>
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
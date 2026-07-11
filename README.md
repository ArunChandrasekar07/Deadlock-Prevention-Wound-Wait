<div align="center">

<img src="./src/assets/TRAIN_LOGO.jpg" alt="Railway Logo" width="100"/>

# 🚆 Deadlock-Free Railway Reservation

### Concurrent Seat Booking with Wound-Wait Deadlock Prevention

**Production-grade demonstration of deadlock prevention in concurrent database systems.**

Built from scratch — applying **Operating Systems concurrency theory** to a real-world railway booking system.

<br/>

<img src="https://readme-typing-svg.demolab.com?font=JetBrains+Mono&weight=600&size=18&duration=2600&pause=900&color=FF6B6B&center=true&vCenter=true&width=850&lines=Deadlock+Prevention;Wound-Wait+Algorithm;Row-Level+Locking;Concurrent+Transaction+Management" />

<br/><br/>

<a href="https://askdevmind.vercel.app">
<img src="https://img.shields.io/badge/🚀%20Live%20Demo-DevMind-6366F1?style=for-the-badge"/>
</a>

<a href="https://github.com/ArunChandrasekar07/devmind">
<img src="https://img.shields.io/badge/⭐%20Star%20on%20GitHub-black?style=for-the-badge"/>
</a>

[![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
[![Algorithm](https://img.shields.io/badge/Algorithm-Wound--Wait-FF6B6B?style=for-the-badge)](#)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

<br/>

[🔐 Core Algorithm](#-core-algorithm) • [🏗 Architecture](#-architecture) • [⚡ Quick Start](#-quick-start) • [📊 Booking Flow](#-booking-flow)

</div>

---

# 🧠 The Problem

When multiple users attempt to book the **same seat simultaneously**, naive systems suffer from serious concurrency issues:

| Issue | Description |
|---|---|
| Race Condition | Two users book the same seat |
| Deadlock | Transaction A waits for B, B waits for A forever |
| Livelock | Transactions repeatedly retry without progress |

This project solves all three using **Wound-Wait timestamp ordering**, a classic deadlock prevention algorithm used in enterprise RDBMS systems.

---

# 📈 System Metrics

| Metric | Value |
|---|---:|
| Algorithm | Wound-Wait |
| Lock Type | Row-Level Lock |
| DB Isolation | Transactional |
| Deadlock Prevention | Yes |
| Starvation Prevention | Yes |
| Rollback Strategy | Deterministic |

---

# ⚙️ Core Algorithm

## Wound-Wait Strategy

Every transaction gets a timestamp.

When two transactions conflict:

```text
IF requester is OLDER:
    → WOUND holder
    → Preempt lock
    → Proceed

IF requester is YOUNGER:
    → WAIT
    → Retry later
```

This guarantees **no circular wait**, eliminating deadlocks.

---

## Implementation — `wound_wait.php`

```php
function wound_wait_decision($request_ts, $holder_ts) {
    if ($request_ts < $holder_ts) return "WOUND";
    return "WAIT";
}
```

Simple. Elegant. Provably deadlock-free.

---

# 🔐 Lock Manager

Implemented in `lock_service.php` using MySQL row-level locking:

```php
function acquire_seat_lock($conn, $seat_id, $txn_id, $txn_ts) {
    $stmt = $conn->prepare(
        "SELECT holder_txn, holder_ts FROM seat_locks WHERE seat_id=? FOR UPDATE"
    );
}
```

### Guarantees

- ✅ Prevents phantom reads  
- ✅ Atomic lock acquisition  
- ✅ Safe under concurrent requests  
- ✅ Starvation-free retry behavior  
- ✅ Thread-safe across PHP workers  

---

# 🏗 Architecture

```text
User Request
     │
     ▼
Frontend (HTML/CSS/JS)
     │
     ▼
PHP Backend
     │
     ▼
Lock Manager
(Wound-Wait)
     │
     ▼
MySQL Database
(Row-level locks)
```

---

# 📂 Project Structure

```text
Deadlock-Prevention-Wound-Wait/
│
├── docs/
│   └── schema.sql
│
└── src/
    ├── assets/
    │   ├── TRAIN_LOGO.jpg
    │   └── coach.jpg
    │
    ├── backend/
    │   ├── db.php
    │   ├── wound_wait.php
    │   ├── lock_service.php
    │   ├── seat.php
    │   ├── train.php
    │   ├── tickets.php
    │   └── payment.php
    │
    ├── frontend/
    │   ├── login.html
    │   ├── register.html
    │   └── index.html
    │
    └── styles/
        └── index.css
```

---

# 📊 Booking Flow

```text
User selects seats
        │
        ▼
BEGIN TRANSACTION
(timestamp assigned)
        │
        ▼
Acquire Lock
        │
        ├── FREE → Lock seat
        │
        └── LOCKED?
             │
             ├── Older txn → WOUND
             │
             └── Younger txn → WAIT
        │
        ▼
Payment (60 sec)
        │
        ▼
COMMIT / RELEASE LOCK
```

---

# 🆚 Why Wound-Wait?

| Approach | Deadlock Free | Starvation Free | Complexity |
|---|---|---|---|
| No Locking | ❌ | ✅ | Low |
| Naive Locking | ❌ | ❌ | Low |
| Wait-Die | ✅ | ✅ | Medium |
| **Wound-Wait** | ✅ | ✅ | Medium |
| Banker's | ✅ | ✅ | High |

Wound-Wait produces **fewer rollbacks** than Wait-Die in high contention systems.

---

# ⚡ Quick Start

### Prerequisites

- XAMPP / WAMP  
- PHP 8+  
- MySQL 8+  
- phpMyAdmin  

---

### Setup

```bash
git clone https://github.com/ArunChandrasekar07/Deadlock-Prevention-Wound-Wait
```

```bash
# Move project
cp -r src/ /xampp/htdocs/railway/
```

```bash
# Import DB
docs/schema.sql
```

```bash
# Run
http://localhost/railway/src/frontend/login.html
```

---

# 🗄 Database Schema

```sql
CREATE TABLE seat_locks (
    seat_id VARCHAR(20) PRIMARY KEY,
    holder_txn VARCHAR(50) NOT NULL,
    holder_ts INT NOT NULL,
    lock_time DATETIME NOT NULL
);
```

`holder_ts` is the key field used by Wound-Wait to compare transaction age.

---

# 👨‍💻 Author

<div align="center">

## Arun Chandrasekar

AI Engineer • Backend Engineer  
Integrated M.Tech Software Engineering — VIT Vellore

[![Portfolio](https://img.shields.io/badge/Portfolio-arunc.vercel.app-black?style=for-the-badge&logo=vercel)](https://arunc.vercel.app)
[![GitHub](https://img.shields.io/badge/GitHub-ArunChandrasekar07-181717?style=for-the-badge&logo=github)](https://github.com/ArunChandrasekar07)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-arunchandrasekar1-0A66C2?style=for-the-badge&logo=linkedin)](https://linkedin.com/in/arunchandrasekar1)

*Built to demonstrate production-grade concurrency theory — beyond textbook pseudocode.*

</div>

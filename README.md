<div align="center">

<img src="src/assets/TRAIN_LOGO.jpeg" alt="Railway Logo" width="80"/>

# 🚆 Deadlock-Free Railway Reservation
### Concurrent Seat Booking with Wound-Wait Deadlock Prevention

[![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
[![Algorithm](https://img.shields.io/badge/Algorithm-Wound--Wait-FF6B6B?style=for-the-badge)](#)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

**A production-grade demonstration of deadlock prevention in concurrent database systems.**
Built from scratch — no frameworks, no shortcuts. Pure OS-level concurrency theory applied to a real booking problem.

[🔍 Core Algorithm](#-core-algorithm) • [🏗 Architecture](#-architecture) • [⚡ Quick Start](#-quick-start) • [📊 Flow](#-booking-flow)

---

</div>

## 🧠 The Problem

When multiple users try to book the **same seat simultaneously**, naive implementations lead to:

| Issue | What happens |
|-------|-------------|
| **Race condition** | Two users book the same seat |
| **Deadlock** | Transaction A waits for B, B waits for A — forever |
| **Livelock** | Transactions keep retrying, never progressing |

This project solves all three using **Wound-Wait timestamp ordering** — the same algorithm used in production RDBMS systems like PostgreSQL and Oracle.

---

## ⚙️ Core Algorithm

### Wound-Wait: How it works

Every booking request is assigned a **timestamp** when it starts. When two transactions conflict over the same seat, the algorithm makes a deterministic decision:

```
IF requesting_transaction is OLDER (smaller timestamp):
    → WOUND the holder (evict it, take the lock)
    → Older transaction proceeds immediately

IF requesting_transaction is YOUNGER (larger timestamp):
    → WAIT for the holder to finish
    → Younger transaction blocks until lock is free
```

> This guarantees **no circular wait** — a core Coffman condition for deadlock.
> Older transactions always win. No cycle can form.

### Implementation — `wound_wait.php`

```php
function wound_wait_decision($request_ts, $holder_ts) {
    if ($request_ts < $holder_ts) return "WOUND";  // older wins → preempt
    return "WAIT";                                   // younger waits
}
```

Simple. Elegant. Provably correct.

---

## 🔐 Lock Manager

The heart of the system — `lock_service.php` implements row-level locking with MySQL's `FOR UPDATE`:

```php
function acquire_seat_lock($conn, $seat_id, $txn_id, $txn_ts) {
    // Atomically check and acquire lock using SELECT FOR UPDATE
    $stmt = $conn->prepare(
        "SELECT holder_txn, holder_ts FROM seat_locks WHERE seat_id=? FOR UPDATE"
    );

    // If seat is free → acquire immediately
    // If occupied → apply Wound-Wait decision
    //   WOUND  → evict holder, acquire lock
    //   WAIT   → return false, caller retries
}
```

**Key guarantees:**
- ✅ `FOR UPDATE` prevents phantom reads during lock check
- ✅ Atomic eviction — no window between check and acquire  
- ✅ Timestamp preserved on retry — starvation-free
- ✅ Thread-safe across concurrent PHP processes

---

## 🏗 Architecture

```
Deadlock-Prevention-Wound-Wait/
│
├── docs/
│   └── schema.sql              # DB schema — seat_locks table with timestamp cols
│
└── src/
    ├── assets/
    │   ├── indian_railways_logo.png
    │   └── Vande-Bharat-Sleeper-Coach.jpg
    │
    ├── backend/
    │   ├── db.php              # MySQL connection factory
    │   ├── wound_wait.php      # Core Wound-Wait decision function
    │   ├── lock_service.php    # Row-level lock manager (FOR UPDATE + preemption)
    │   ├── lock_selected_seats.php  # Seat locking endpoint
    │   ├── seat.php            # Seat availability queries
    │   ├── train.php           # Train listing
    │   ├── tickets.php         # Ticket generation
    │   └── payment.php         # Payment timer handler
    │
    ├── frontend/
    │   ├── login.html          # Auth entry point
    │   ├── register.html       # User registration
    │   └── index.html          # Main booking UI
    │
    └── styles/
        └── index.css           # Styling
```

---

## 📊 Booking Flow

```
User selects seats
        │
        ▼
BEGIN TRANSACTION ──── timestamp assigned
        │
        ▼
acquire_seat_lock(seat_id, txn_id, txn_ts)
        │
        ├─── Seat FREE? ──────────────────► INSERT lock → proceed to payment
        │
        └─── Seat LOCKED?
                │
                ├── I am OLDER (smaller ts) ──► WOUND holder → evict → take lock
                │
                └── I am YOUNGER (larger ts) ──► WAIT → retry after delay
                                                        │
                                                      (no new timestamp on retry
                                                       → starvation prevented)
        │
        ▼
Payment timer (60s)
        │
        ▼
COMMIT → ticket generated / TIMEOUT → lock released
```

---

## 🆚 Why Wound-Wait over other approaches?

| Approach | Deadlock-free? | Starvation-free? | Complexity |
|----------|---------------|-----------------|------------|
| No locking | ❌ | ✅ | Low |
| Pessimistic lock (naive) | ❌ | ❌ | Low |
| Wait-Die | ✅ | ✅ | Medium |
| **Wound-Wait** ✅ | ✅ | ✅ | Medium |
| Banker's Algorithm | ✅ | ✅ | High |

Wound-Wait generates **fewer rollbacks** than Wait-Die in high-concurrency scenarios — making it the preferred choice for booking systems.

---

## ⚡ Quick Start

### Prerequisites
- XAMPP / WAMP (PHP 8.0+, MySQL 8.0)
- phpMyAdmin

### Setup

```bash
# 1. Clone the repo
git clone https://github.com/ArunChandrasekar07/Deadlock-Prevention-Wound-Wait.git

# 2. Move src/ to your PHP server root
cp -r src/ /xampp/htdocs/railway/

# 3. Create the database
# Open phpMyAdmin → import docs/schema.sql

# 4. Update DB credentials
# Edit src/backend/db.php with your MySQL creds

# 5. Open in browser
# http://localhost/railway/src/frontend/login.html
```

---

## 🗄 Database Schema

```sql
CREATE TABLE seat_locks (
    seat_id     VARCHAR(20) PRIMARY KEY,
    holder_txn  VARCHAR(50) NOT NULL,
    holder_ts   INT NOT NULL,          -- Unix timestamp for Wound-Wait comparison
    lock_time   DATETIME NOT NULL      -- When lock was acquired
);
```

The `holder_ts` column is the key — it's what the Wound-Wait algorithm compares to determine which transaction is "older."

---

## 👨‍💻 Author

<div align="center">

**Arun Chandrasekar**
Integrated M.Tech Software Engineering — VIT Vellore

[![Portfolio](https://img.shields.io/badge/Portfolio-arunc.vercel.app-black?style=for-the-badge&logo=vercel)](https://arunc.vercel.app)
[![GitHub](https://img.shields.io/badge/GitHub-ArunChandrasekar07-181717?style=for-the-badge&logo=github)](https://github.com/arunchandrasekar07)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-arunchandrasekar1-0A66C2?style=for-the-badge&logo=linkedin)](https://linkedin.com/in/arunchandrasekar1)

*Built to demonstrate production-grade concurrency theory — not just textbook pseudocode.*

</div>

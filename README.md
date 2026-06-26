# Deadlock Prevention in Concurrent Ticket Booking (Wound–Wait)

This repository demonstrates seat-locking under concurrent booking requests and prevents deadlocks using timestamp ordering (Wound–Wait) with timeout-based behavior.

## Run (local)
1. Place the `src/` folder in your PHP server root (XAMPP htdocs).
2. Create DB + table using `docs/schema.sql` in phpMyAdmin.
3. Update DB creds in `src/backend/db.php`.
4. Open `src/index.html`.

## Flow
Register/Login → Train list → Seat select → Lock seats → Payment timer → Ticket print

## Core logic
- `src/backend/wound_wait.php`
- `src/backend/lock_service.php`

## Notes
This is a demo skeleton. Replace train/seat data with your own DB tables if needed.

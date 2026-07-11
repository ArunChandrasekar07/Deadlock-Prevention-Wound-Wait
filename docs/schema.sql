-- ============================================================
-- Existing table (unchanged) — kept here for reference so this
-- file is a complete, runnable schema.
-- ============================================================
CREATE TABLE IF NOT EXISTS seat_locks (
  seat_id VARCHAR(64) PRIMARY KEY,
  holder_txn VARCHAR(64) NOT NULL,
  holder_ts BIGINT NOT NULL,
  lock_time TIMESTAMP NOT NULL DEFAULT NOW()
);

-- ============================================================
-- NEW: users table — required for real authentication.
-- Nothing in the project currently creates or reads this.
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
  id SERIAL PRIMARY KEY,
  username VARCHAR(64) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  name VARCHAR(128) NOT NULL,
  age INT,
  city VARCHAR(128),
  email VARCHAR(128) UNIQUE NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

-- ============================================================
-- NEW: bookings table — the permanent record once a lock is
-- turned into a real ticket. seat_locks is intentionally
-- temporary/expiring; bookings is not.
-- ============================================================
CREATE TABLE IF NOT EXISTS bookings (
  id SERIAL PRIMARY KEY,
  train_no VARCHAR(32) NOT NULL,
  seats TEXT NOT NULL,          -- comma-separated seat codes, e.g. "B1,B2"
  username VARCHAR(64) NOT NULL REFERENCES users(username),
  status VARCHAR(32) NOT NULL DEFAULT 'booked',
  booked_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_bookings_train ON bookings(train_no);
CREATE INDEX IF NOT EXISTS idx_bookings_user ON bookings(username);
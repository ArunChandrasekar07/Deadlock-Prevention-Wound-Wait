<?php
require_once __DIR__ . "/wound_wait.php";

// Matches the 5-minute countdown shown in payment.php's JS timer.
define("LOCK_EXPIRY_SECONDS", 300);

/**
 * NEW. Deletes any lock that's older than the payment window.
 * Nothing previously called this — locks sat forever once created,
 * even after a user abandoned checkout.
 */
function release_expired_locks($conn) {
    pg_query_params(
        $conn,
        "DELETE FROM seat_locks WHERE lock_time < NOW() - ($1 || ' seconds')::interval",
        [LOCK_EXPIRY_SECONDS]
    );
}

/**
 * Unchanged wound-wait decision logic, but now also treats a
 * stale lock (older than LOCK_EXPIRY_SECONDS) as free regardless
 * of timestamp ordering — an abandoned checkout shouldn't block
 * a fresh, valid request forever.
 *
 * IMPORTANT: this function assumes the caller has already opened
 * a transaction with pg_query($conn, "BEGIN"). Without that, the
 * "FOR UPDATE" below does nothing — the row lock is released the
 * instant the SELECT finishes, before the INSERT/DELETE runs, and
 * two concurrent requests can both pass the check.
 */
function acquire_seat_lock($conn, $seat_id, $txn_id, $txn_ts) {
    $result = pg_query_params(
        $conn,
        "SELECT holder_txn, holder_ts, lock_time FROM seat_locks WHERE seat_id = $1 FOR UPDATE",
        [$seat_id]
    );

    if (!$result) return false;

    if (pg_num_rows($result) === 0) {
        $ins = pg_query_params(
            $conn,
            "INSERT INTO seat_locks(seat_id, holder_txn, holder_ts, lock_time) VALUES ($1, $2, $3, NOW())",
            [$seat_id, $txn_id, $txn_ts]
        );
        return $ins !== false;
    }

    $row = pg_fetch_assoc($result);
    $holder_txn = $row["holder_txn"];
    $holder_ts = intval($row["holder_ts"]);
    $lock_age_secs = time() - strtotime($row["lock_time"]);

    if ($holder_txn === $txn_id) return true;

    if ($lock_age_secs > LOCK_EXPIRY_SECONDS) {
        pg_query_params($conn, "DELETE FROM seat_locks WHERE seat_id = $1", [$seat_id]);
        $ins = pg_query_params(
            $conn,
            "INSERT INTO seat_locks(seat_id, holder_txn, holder_ts, lock_time) VALUES ($1, $2, $3, NOW())",
            [$seat_id, $txn_id, $txn_ts]
        );
        return $ins !== false;
    }

    $decision = wound_wait_decision($txn_ts, $holder_ts);

    if ($decision === "WOUND") {
        pg_query_params($conn, "DELETE FROM seat_locks WHERE seat_id = $1", [$seat_id]);
        $ins = pg_query_params(
            $conn,
            "INSERT INTO seat_locks(seat_id, holder_txn, holder_ts, lock_time) VALUES ($1, $2, $3, NOW())",
            [$seat_id, $txn_id, $txn_ts]
        );
        return $ins !== false;
    }

    return false;
}

/**
 * NEW. This is the piece that was completely missing — seat.php
 * had no way to know what was actually locked or booked. Checks
 * both the temporary seat_locks table and the permanent bookings
 * table, so a seat shows correctly whether it's mid-checkout by
 * someone else or already fully booked.
 */
function seat_status_map($conn, $train_no, $seat_codes) {
    release_expired_locks($conn);

    $status = [];
    foreach ($seat_codes as $s) $status[$s] = "Available";

    $locked = pg_query_params(
        $conn,
        "SELECT seat_id FROM seat_locks WHERE seat_id LIKE $1",
        [$train_no . "-%"]
    );
    if ($locked) {
        while ($row = pg_fetch_assoc($locked)) {
            $seat_code = substr($row["seat_id"], strlen($train_no) + 1);
            if (isset($status[$seat_code])) $status[$seat_code] = "Locked";
        }
    }

    $booked = pg_query_params(
        $conn,
        "SELECT seats FROM bookings WHERE train_no = $1 AND status = 'booked'",
        [$train_no]
    );
    if ($booked) {
        while ($row = pg_fetch_assoc($booked)) {
            foreach (explode(',', $row["seats"]) as $s) {
                $s = trim($s);
                if (isset($status[$s])) $status[$s] = "Booked";
            }
        }
    }

    return $status;
}
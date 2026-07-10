<?php
require_once __DIR__ . "/wound_wait.php";

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

    if ($holder_txn === $txn_id) return true;

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
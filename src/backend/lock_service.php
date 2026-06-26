<?php
require_once __DIR__ . "/wound_wait.php";

function acquire_seat_lock($conn, $seat_id, $txn_id, $txn_ts) {
    $stmt = $conn->prepare("SELECT holder_txn, holder_ts, lock_time FROM seat_locks WHERE seat_id=? FOR UPDATE");
    $stmt->bind_param("s", $seat_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        $ins = $conn->prepare("INSERT INTO seat_locks(seat_id, holder_txn, holder_ts, lock_time) VALUES(?,?,?,NOW())");
        $ins->bind_param("ssi", $seat_id, $txn_id, $txn_ts);
        return $ins->execute();
    }

    $row = $res->fetch_assoc();
    $holder_txn = $row["holder_txn"];
    $holder_ts = intval($row["holder_ts"]);

    if ($holder_txn === $txn_id) return true;

    $decision = wound_wait_decision($txn_ts, $holder_ts);

    if ($decision === "WOUND") {
        $del = $conn->prepare("DELETE FROM seat_locks WHERE seat_id=?");
        $del->bind_param("s", $seat_id);
        $del->execute();

        $ins = $conn->prepare("INSERT INTO seat_locks(seat_id, holder_txn, holder_ts, lock_time) VALUES(?,?,?,NOW())");
        $ins->bind_param("ssi", $seat_id, $txn_id, $txn_ts);
        return $ins->execute();
    }

    return false;
}

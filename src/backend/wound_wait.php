<?php
function wound_wait_decision($request_ts, $holder_ts) {
    if ($request_ts < $holder_ts) return "WOUND";
    return "WAIT";
}

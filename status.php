<?php

require __DIR__ . '/includes/Db.php';

if (!isset($_SESSION['auth'])) {
    die(json_encode(['status' => 419, 'message' => 'Something went wrong']));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user = $_SESSION['auth']['id'];
    $sql = "SELECT*  FROM users WHERE  id = :id LIMIT 1";
    $stmt = $con->prepare($sql);
    $stmt->execute([':id' => $user]);
    $row = $stmt->fetch();
    if (!$row) {
        die(json_encode(['status' => 422, 'message' => '404 not found']));
    }

    // $date = new DateTime();
    // $now = $date->format('Y-m-d H:i:s');

    if (isset($_POST['logout'])) {
        $settime = "UPDATE users SET last_seen = now(), `status`= 0 WHERE id = :id";
        $stmt = $con->prepare($settime);
        $stmt->execute([':id' => $user]);
    } else {
        // $settime = "UPDATE users SET `last_seen` = now(), `status`= 1 WHERE id = :id";
        // $stmt = $con->prepare($settime);
        // $stmt->execute([':id' => $user]);
        // $sql1 = "UPDATE  users SET  `status`= 0 WHERE UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(`last_seen`) > 180 AND `last_seen` IS NOT NULL";
        // $stmt1 = $con->prepare($sql1);
        // $stmt1->execute();
    }

    if (!$stmt) {
        die(json_encode(['status' => 500, 'message' => 'error']));
    }

    die(json_encode(['status' => 200, 'message' => 'success']));
}
die(json_encode(['status' => 500, 'message' => 'error']));

<?php

require __DIR__ . '/includes/Db.php';

if (!isset($_SESSION['auth'])) {
    echo json_encode(['status' => 419, 'message' => 'Something went wrong']);
    exit();
    die();
    return;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_SESSION['auth']['id'];

    $sql = "SELECT*  FROM users WHERE  id = :id LIMIT 1";
    $stmt = $con->prepare($sql);
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch();
    if (!$row) {
        echo json_encode(['status' => 419, 'message' => 'Something Went wrong']);
        die();
    }

    $date = new DateTime();
    $now = $date->format('Y-m-d H:i:s');
    $settime = "UPDATE users SET log_out = :log_out, `status`= 0 WHERE id = :id";
    $stmt = $con->prepare($settime);
    $stmt->execute([':log_out' => $now, ':id' => $id]);

    if (!$stmt) {
        echo json_encode(['status' => 500]);
        die();
    }
    unset($_SESSION['auth']);
    echo json_encode(['status' => 200, 'message' => 'Successfully logedd out']);
    die();
    return;
} else {
    echo  json_encode(['status' => 419, 'message' => 'password did not match']);
    die();
}
echo json_encode(['status' => 500, 'message' => 'server error, Please refresh your browser']);
die();

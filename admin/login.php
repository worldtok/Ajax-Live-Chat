<?php

require dirname(__DIR__) . '/includes/Db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        !isset($_POST['email'])
        || empty($_POST['email'])
        || !isset($_POST['password'])
        || empty($_POST['password'])
    ) {
        die(json_encode(['status' => 419, 'message' => 'password did not match']));
        exit();
    }
    $email = trim($_POST['email']);
    $pass = trim($_POST['password']);
    $sql = "SELECT*  FROM admins WHERE  email = :email LIMIT 1";
    $stmt = $con->prepare($sql);
    $stmt->execute([':email' => $email]);
    $row = $stmt->fetch();
    if (!$row) {
        echo json_encode(['status' => 422, 'message' => 'Try again']);
        die();
    }
    $hash = $row['password'];
    if (password_verify($pass, $hash)) {

        $date = new DateTime();
        $now = $date->format('Y-m-d H:i:s');
        $settime = "UPDATE admins SET log_in = :log_in, `status`= :stat WHERE email = :email";
        $stmt = $con->prepare($settime);
        $stmt->execute([':log_in' => $now, ':email' => $email, ':stat' => 1]);

        if (!$stmt) {
            die(json_encode(['status' => 500, 'message' => "Something went wrong"]));
        }
        $_SESSION['admin'] = [
            'id' => $row['id'], 'name' => $row['name'], 'status' => 1
        ];
        die(json_encode(['status' => 200, 'message' => 'Successfully loged in']));
        return;
    } else {
        die(json_encode(['status' => 419, 'message' => 'password did not match']));
    }
}
die(json_encode(['status' => 500, 'message' => 'server error, Please refresh your browser']));

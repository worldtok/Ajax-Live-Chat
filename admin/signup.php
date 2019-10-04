<?php

require __DIR__ . '/includes/Db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        !isset($_POST['name'])
        || empty($_POST['name'])
        || !isset($_POST['email'])
        || empty($_POST['email'])
        || !isset($_POST['password'])
        || empty($_POST['password'])
    ) {
        echo  json_encode(['status' => 419, 'message' => 'All fields are required']);
        die();
        exit();
        return;
    }
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass = trim($_POST['password']);
    $hash = password_hash($pass, PASSWORD_BCRYPT);

    $sql = "SELECT*  FROM admins WHERE  email = :email LIMIT 1";
    $stmt = $con->prepare($sql);
    $stmt->execute([':email' => $email]);
    $row = $stmt->fetch();
    if ($row) {
        echo json_encode(['status' => 422, 'message' => 'This email is Registered, You can login']);
        die();
    }

    $sql = "INSERT INTO 
            admins (`name`, `email`, `status`, `password`) 
            VALUES('$name', '$email', 1, '$hash')";
    $res = $con->exec($sql);

    if (!$res) {
        echo json_encode(['status' => 500, 'message' => 'Something went wrong, try again']);
        die();
    }
    $sql = "SELECT*  FROM users WHERE  email = :email LIMIT 1";
    $stmt = $con->prepare($sql);
    $stmt->execute([':email' => $email]);
    $row = $stmt->fetch();
    if (!$row) {
        echo json_encode(['status' => 422, 'message' => 'This email is not registered on our website']);
        die();
    }

    $_SESSION['auth'] = [
        'id' => $row['id'], 'name' => $row['name'], 'status' => 1
    ];
    echo json_encode(['status' => 200, 'message' => 'Sign up Successful']);
    die();
    return;
}
echo json_encode(['status' => 500, 'message' => 'server error, Please refresh your browser']);
die();

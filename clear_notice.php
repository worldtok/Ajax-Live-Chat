<?php

require __DIR__ . '/includes/Db.php';

if (!isset($_SESSION['auth'])) {
    die(json_encode(['status' => 419, 'message' => 'Something went wrong']));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
    $stmt = $con->prepare($sql);
    $stmt->execute([':id' => $_SESSION['auth']['id']]);
    $user =  $stmt->fetch();
    if (!$user) {
        die(json_encode(['status' => 419, 'message' => 'Something went wrong']));
    }

    if (!empty(trim($_POST['inbox']))) {
        /* update user last notification check */
        $update = "UPDATE users SET inbox_notice = now() WHERE id = :id";
        $stmt = $con->prepare($update);
        $stmt->execute([':id' => $_SESSION['auth']['id']]);
        die(json_encode(['status' => 200, 'inbox' => true]));
    }
    if (!empty(trim($_POST['group']))) {
        /* update user last notification check */
        $update = "UPDATE users SET group_notice = now() WHERE id = :id";
        $stmt = $con->prepare($update);
        $stmt->execute([':id' => $_SESSION['auth']['id']]);
        die(json_encode(['status' => 200, 'group' => true]));
    }
    die(json_encode(['status' => 200]));

    /* return a valid json */


    // /* update user last notification check */
    // $update = "UPDATE users SET inbox_notice = now() WHERE id = :id";
    // $stmt1 = $con->prepare($update);
    // $stmt1->execute([':id' => $_SESSION['auth']['id']]);



    /* update user last notification check */
    // $update = "UPDATE users SET group_notice = now() WHERE id = :id";
    // $stmt1 = $con->prepare($update);
    // $stmt1->execute([':id' => $_SESSION['auth']['id']]);

    /* Return a valid json*/
    // die(json_encode([
    //     'status' => 200,
    //     'data' => $notice,
    //     'type' => 'group',
    // ]));

    // die(json_encode(['message' => 'Nothing found']));
} else {
    die(json_encode(['status' => 500, 'message' => 'Please refresh your browser to continue']));
    exit();
}

<?php

require __DIR__ . '/includes/Db.php';

if (!isset($_SESSION['auth'])) {
    die(json_encode(['status' => 419, 'message' => 'Something went wrong']));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['u']) && !empty(trim($_POST['u']))) {
        $u = trim($_POST['u']);
        $sql = "SELECT id, name, status FROM users WHERE name LIKE ? AND id != ? ORDER BY id DESC";
        $stmt = $con->prepare($sql);
        $stmt->execute(["%$u%", $_SESSION['auth']['id']]);
        $user =  $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($user) < 1) {
            die(json_encode(['status' => 404, 'message' => 'Nothing Found']));
        }
        die(json_encode(['status' => 200, 'users' => $user, 'user' => true, 'message' => 'Done']));
    }
    die(json_encode(['status' => 400, 'message' => 'Sorry, Something went wrog']));
} else {
    die(json_encode(['status' => 500, 'message' => 'Please refresh your browser to continue']));
    exit();
}

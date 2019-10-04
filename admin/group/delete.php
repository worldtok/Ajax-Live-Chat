<?php


require dirname(dirname(__DIR__)) . '/includes/Db.php';
require dirname(dirname(__DIR__)) . '/includes/File.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    if (!isset($_POST['id']) || empty($_POST['id'])) {
        die(json_encode(['status' => 419, 'message' => 'Group not found']));
    }
    $id = trim($_POST['id']);
    $stmt = $con->prepare("DELETE FROM groups WHERE id = :id");
    // $res = $stmt->execute([':id' => $id]);

    $mes = $con->prepare("DELETE FROM group_messages WHERE group_id = :id");
    // $mes = $con->prepare("DELETE FROM group_messages WHERE group_id NOT IN(SELECT id FROM groups)");
    // $done = $mes->execute([':id' => $id]);


    if ($res && $done) {
        die(json_encode(['status' => 200, 'message' => "Group deleted successfully"]));
    }

    die(json_encode(['status' => 200, 'message' => 'Something went wrong, try again']));
}
echo json_encode(['status' => 500, 'message' => 'server error, Please refresh your browser']);
die();

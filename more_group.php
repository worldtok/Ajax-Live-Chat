<?php

require __DIR__ . '/includes/Db.php';

if (!isset($_SESSION['auth']) || !isset($_SESSION['group'])) {
    die(json_encode(['status' => 419, 'message' => 'Something went wrong']));
    exit();
}

if (isset($_GET['next']) && !empty(trim($_GET['next']))) {
    $group = (int) $_SESSION['group']['id'];
    $index = (int) $_GET['next'];
    $offset = (int) ($index * 10) - 10;

    // $sql = "SELECT `id`,`admin_id`,`user_id`, `message`,`created_at` 
    //     FROM `group_messages` ";
    $sql = "SELECT `id`,`admin_id`,`user_id`, `message`,`created_at` 
        FROM `group_messages`  WHERE `group_id` = :id  
        ORDER BY id DESC  LIMIT 10 OFFSET :offset";


    $stmt = $con->prepare($sql);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':id', $group, PDO::PARAM_INT);
    // $stmt->bindValue(':receiver_id', $receiver);
    $stmt->execute();

    $chat = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /* emoji variables */
    $a = '<img src="/emoji/img/blank.gif" class="img" style="display:inline-block;width:25px;height:25px;';
    $b0 = "background:url('/emoji/img/emoji_spritesheet_0.png')";
    $b1 = "background:url('/emoji/img/emoji_spritesheet_1.png')";
    $b2 = "background:url('/emoji/img/emoji_spritesheet_2.png')";
    $b3 = "background:url('/emoji/img/emoji_spritesheet_3.png')";
    $b4 = "background:url('/emoji/img/emoji_spritesheet_4.png')";
    $c = "no-repeat;background-size:";
    $emoji_params = [$a, $b0, $b1, $b2, $b3, $b4, $c];
    $emoji_replace = [':emoji_source:', ':emoji_bg0', ':emoji_bg1', ':emoji_bg2', ':emoji_bg3', ':emoji_bg4', ':emoji_size'];
    /* emoji variables */
    $messages = [];
    foreach ($chat as $mes) {
        $messages[] =    [
            'id' => $mes['id'],
            'user_id' => $mes['user_id'],
            'admin_id' => $mes['receiver_id'],
            'message' => str_replace($emoji_replace, $emoji_params, $mes['message']),
            'created_at' => $mes['created_at']
        ];
    }
    $data = ['data' => $messages, 'next' => $index + 1, 'status' => 200];
    die(json_encode($data));
} else {
    echo json_encode(['status' => 500, 'message' => 'Please refresh your browser to continue']);
}

<?php

require __DIR__ . '/includes/Db.php';


if (!isset($_SESSION['auth'])) {
    die(json_encode(['status' => 419, 'message' => 'Something went wrong']));
}

if (isset($_GET['user']) && !empty($_GET['user'])) {
    $receiver = (int) $_GET['user'];
    $_SESSION['friend'] = $receiver;
    $sql = "SELECT 
        `id`,`user_id`, `receiver_id`, `message`,`created_at`
            FROM `messages` 
            WHERE `user_id` = :user_id 
            AND `receiver_id` = :receiver_id ORDER BY id DESC LIMIT 10";

    $stmt = $con->prepare($sql);
    $stmt->execute([':user_id' => $auth, ':receiver_id' => $receiver]);
    $userSend =   $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt->execute([':user_id' => $receiver, ':receiver_id' => $auth]);
    $userReceive = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $merged = array_merge($userSend, $userReceive);

    usort($merged,  function ($a, $b) {
        if ($a["id"] == $b["id"]) {
            return 0;
        }
        return ($a["id"] < $b["id"]) ? -1 : 1;

        // return strcmp($a["id"], $b["id"]);
    });
    $unique = array_unique($merged, SORT_REGULAR);
    // echo json_encode($unique);
    // echo json_encode($merged);
    // $rev = $merged;
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
    $rev = array_reverse($unique);
    $chat = [];
    $i = 0;
    foreach ($rev as $arr) {
        array_push($chat, $arr);
        if ($i == 9) {
            break;
        }
        $i++;
    }
    $messages = [];
    foreach ($chat as $mes) {
        $messages[] =    [
            'id' => $mes['id'],
            'user_id' => $mes['user_id'],
            'receiver_id' => $mes['receiver_id'],
            'message' => str_replace($emoji_replace, $emoji_params, $mes['message']),
            'created_at' => $mes['created_at']
        ];
    }
    $chatreverse = array_reverse($messages);
    $data = ['data' => $chatreverse, 'next' => 2];
    /* update all messages to seen where receiver id is auth id and user id is $receiver*/

    $sql = "UPDATE messages SET `seen_at` = now() WHERE seen_at IS NULL AND receiver_id = :user AND user_id = :receiver";
    $stmt = $con->prepare($sql);
    $stmt->execute([':user' => $auth, ':receiver' => $receiver]);
    // $sql = "UPDATE  users SET  `status`= 0 WHERE `status` = 1 AND UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(`last_seen`) > 180";
    // $con->query($sql);
    die(json_encode($data));
} else {
    echo json_encode(['status' => 500, 'message' => 'Please refresh your browser to continue']);
}

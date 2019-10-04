<?php

require __DIR__ . '/includes/Db.php';


if (!isset($_SESSION['auth'])) {
    echo json_encode(['status' => 419, 'message' => 'Something went wrong']);
    exit();
    die();
    return;
}
// die(json_encode($_POST));
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['next']) && !empty($_POST['next'])) {
        $receiver =  $_SESSION['friend'];
        $index = trim($_POST['next']);
        $offset = ((int) $index * 10) - 10;

        $sql = "SELECT `id`,`user_id`, `receiver_id`, `message`,`created_at` 
         FROM `messages`  
         WHERE `user_id` = :user_id  
         AND `receiver_id` = :receiver_id 
        ORDER BY id DESC  LIMIT 10 OFFSET :offset";


        $stmt = $con->prepare($sql);
        $stmt->bindValue(':user_id', $auth, PDO::PARAM_INT);
        $stmt->bindValue(':receiver_id', $receiver, PDO::PARAM_INT);
        $stmt->bindValue(':offset',  $offset, PDO::PARAM_INT);
        $stmt->execute();

        $userSend =   $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt->bindValue(':user_id', $receiver, PDO::PARAM_INT);
        $stmt->bindValue(':receiver_id', $auth, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $userReceive = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $merged = array_merge($userSend, $userReceive);

        usort($merged,  function ($a, $b) {
            if ($a["id"] == $b["id"]) {
                return 0;
            }
            return ($a["id"] < $b["id"]) ? -1 : 1;
        });
        $rev = array_reverse($merged);

        $chat = [];
        $i = 0;
        foreach ($rev as $arr) {
            array_push($chat, $arr);
            if ($i == 9) {
                break;
            }
            $i++;
        }
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
                'receiver_id' => $mes['receiver_id'],
                'message' => str_replace($emoji_replace, $emoji_params, $mes['message']),
                'created_at' => $mes['created_at']
            ];
        }
        $data = ['data' => $messages, 'next' => $index + 1, 'status' => 200];
        die(json_encode($data));
    }
    die(json_encode(['message' => 'something went wrong']));
} else {
    die(json_encode(['status' => 500, 'message' => 'Please refresh your browser to continue']));
}

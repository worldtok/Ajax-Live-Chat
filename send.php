<?php

require __DIR__ . '/includes/Db.php';
require __DIR__ . '/includes/File.php';


if (!isset($_SESSION['auth'])) {
    die(json_encode(['status' => 419, 'message' => 'Something went wrong']));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        !isset($_POST['message'])
        && empty($_POST['message'])
        && !isset($_POST['images'])
        && empty(trim($_FILES['images']['name'][0]))
    ) {
        die(json_encode(['status' => 419, 'message' => 'All fields are required']));
        exit();
    }
    $message = trim($_POST['message']);

    $user = $_SESSION['auth']['id'];
    $receiver = $_SESSION['friend'];
    $a = '<img src="/emoji/img/blank.gif" class="img" style="display:inline-block;width:25px;height:25px;';
    $b0 = "background:url('/emoji/img/emoji_spritesheet_0.png')";
    $b1 = "background:url('/emoji/img/emoji_spritesheet_1.png')";
    $b2 = "background:url('/emoji/img/emoji_spritesheet_2.png')";
    $b3 = "background:url('/emoji/img/emoji_spritesheet_3.png')";
    $b4 = "background:url('/emoji/img/emoji_spritesheet_4.png')";
    $c = "no-repeat;background-size:";
    $emoji_params = [$a, $b0, $b1, $b2, $b3, $b4, $c];
    $emoji_replace = [
        ':emoji_source:',
        ':emoji_bg0',
        ':emoji_bg1',
        ':emoji_bg2',
        ':emoji_bg3',
        ':emoji_bg4',
        ':emoji_size'
    ];
    $message = str_ireplace($emoji_params, $emoji_replace, $message);

    $images = "";

    if (isset($_FILES['images']) && !empty(trim($_FILES['images']['name'][0]))) {

        if (count($_FILES['images']['name']) > 0) {

            $file_names = $_FILES['images']['name'];
            $file_types = $_FILES['images']['type'];
            $file_tmps = $_FILES['images']['tmp_name'];
            $file_errors = $_FILES['images']['error'];
            $file_sizes = $_FILES['images']['size'];
            // die(json_encode(['message' => [$file_names[0], $file_types[0], $file_tmps[0], $file_errors[0]]]));


            for ($i = 0; $i < count($file_names); $i++) {
                $images .= '<img class="inbox-image" src="/storage/' . File::upload(
                    [
                        'name' => $file_names[$i],
                        'type' => $file_types[$i],
                        'tmp_name' => $file_tmps[$i],
                        'error' => $file_errors[$i],
                        'size' => $file_sizes[$i]
                    ],
                    'inbox/messages/' . time() . "_" . $i . '_' . $user . '.' . extension([
                        'name' => $file_names[$i],
                        'type' => $file_types[$i],
                        'tmp_name' => $file_tmps[$i],
                        'error' => $file_errors[$i],
                        'size' => $file_sizes[$i]
                    ]),
                    999999999,
                    ['image' => [
                        'png', 'jpg', 'jpeg', 'gif', 'bmp', 'svg'
                    ]]
                ) . '">';
            }
        }
    }

    $sql = "INSERT INTO 
            messages (`user_id`, `receiver_id`, `message`) 
            VALUES('$user', '$receiver', :message)";
    $stmt = $con->prepare($sql);
    if ($images == "") {
        $stmt->bindValue(':message', $message, PDO::PARAM_STR);
    } else {
        $stmt->bindValue(':message', $message . '<br><p class="inbox-image-block">' . $images . '</p>', PDO::PARAM_STR);
    }
    $res = $stmt->execute();
    $message = str_replace($emoji_replace, $emoji_params, $message);

    if ($res) {
        if ($images == "") {
            die(json_encode(['status' => 200, 'message' => 'sent', 'mes' =>  $message]));
        }
        die(json_encode(['status' => 200, 'message' => 'sent', 'mes' =>  $message . '<br><p class="inbox-image-block">' . $images . '</p>']));
    } else {
        die(json_encode(['status' => 500, 'message' => 'Something went wrong']));
        exit();
    }
} else {
    die(json_encode(['status' => 500, 'message' => 'Please refresh your browser to continue']));
    exit();
}

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require dirname(__DIR__) . '/includes/head.php'; ?>
    <title>Messages</title>
    <link rel="stylesheet" href="/fontawsome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/emoji/css/emoji.css">
    <?php
    if (!isset($_SESSION['auth'])) {
        header('location:/');
    }
    ?>

</head>

<body>
    <header class=" mb-0">
        <?php
        // require dirname(__DIR__) . '/includes/header.php';
        if (isset($_GET['group']) && !empty($_GET['group'])) {
            $slug = trim($_GET['group']);
            $name = preg_replace('[-]', " ", $slug);


            $sql = "SELECT*  FROM groups WHERE  slug = :slug LIMIT 1";
            $stmt = $con->prepare($sql);
            $stmt->execute([':slug' => $slug]);
            $row = $stmt->fetch();
            if (!$row) {
                $_SESSION['error_page'] = "The page groups/?group=$name could not be found";
                header('location:/404.php');
                die();
            }

            /* get unread mesages */
            $limit_sql = "SELECT COUNT(*) AS unread FROM group_messages 
        WHERE group_id = :group_id AND user_id != :id 
                     AND seen_users REGEXP BINARY :regx";

            $limit_stmt = $con->prepare($limit_sql);
            $limit_stmt->bindValue(':group_id', $row['id'], PDO::PARAM_INT);
            $limit_stmt->bindValue(':id', $auth, PDO::PARAM_INT);
            $limit_stmt->bindValue(':regx', "^((?!,$auth,).)*$", PDO::PARAM_STR);
            $limit_stmt->execute();
            $unread = $limit_stmt->fetch();
            $unread = $unread['unread'];
            if ($unread != 0) {
                $_SESSION['unread'] = $unread;
                $limit = $unread;
            } else {
                $limit = 20;
                if (isset($_SESSION['unread'])) {
                    unset($_SESSION['unread']);
                }
            }
            /* get unread */


            $_SESSION['group'] = ['id' => $row['id'], 'slug' => $row['slug'], 'name' => $row['name']];
            $sql = "SELECT * FROM group_messages WHERE group_id = :group_id ORDER BY id DESC LIMIT :limit";
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':group_id', $row['id'], PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $messages =  $stmt->fetchAll(PDO::FETCH_ASSOC);
            // die(json_encode($messages));
            $messages = array_reverse($messages);

            //         $drop = 'DROP TABLE IF EXISTS group_seen';
            //         $con->query($drop);
            //         $create = "CREATE TABLE `group_seen` (
            //     `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
            //     `user_id` int(11) NULL,
            //     ``
            //     `last_seen` timestamp NULL
            //  ) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";

            /* update this message_users to include auth user id*/

            $sql = "UPDATE group_messages 
                SET `seen_users` = concat(seen_users, :seen_users)
                     WHERE group_id = :group_id AND user_id != :id AND id <= :last_id 
                     AND seen_users REGEXP BINARY :regx";
            // $con->query($sql);
            $stmt = $con->prepare($sql);
            $last_message = end($messages);
            $seen_users = $last_message['seen_users'];
            $x = explode(',', $seen_users);
            if (!in_array($auth, $x)) {
                $x[] = $auth;
            }
            $y = implode(',', $x);
            $stmt->bindValue(':seen_users', "$auth,", PDO::PARAM_STR);
            $stmt->bindValue(':group_id', $row['id'], PDO::PARAM_INT);
            $stmt->bindValue(':id', $auth, PDO::PARAM_INT);
            $stmt->bindValue(':last_id', $last_message['id'], PDO::PARAM_INT);
            // $stmt->bindValue(':regx', "[5]", PDO::PARAM_STR);
            $stmt->bindValue(':regx', "^((?!,$auth,).)*$", PDO::PARAM_STR);

            $stmt->execute();
            // $all = "UPDATE  group_messages SET  `seen_users`= 1";
            // $all = "UPDATE  group_messages SET  `seen_users`= concat(user_id, ',')";
            // $con->query($all);
            // $all = "UPDATE  group_messages SET  `seen_users`= concat(COALESCE(seen_users,','), 2) WHERE UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(`last_seen`) > 180 AND `last_seen` IS NOT NULL";
            require dirname(__DIR__) . '/includes/icon_header.php';

            ?>


    </header>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <?php
                // echo 
                // die(json_encode([$con->errorInfo(), $row['id'], $auth]));
                ?>
            <div class="col-12">
                <div class="inbox" <?php echo isset($_SESSION['unread']) ? '' : 'id="gscrol-box"'; ?>>
                    <div class="text-center" id="more-control">
                        <?php if (count($messages) > 19) { ?>
                            <button class="btn btn-primary btn-sm" type="button" id="gmore" data-next="2">Load previous chat</button>
                        <?php   } ?>
                    </div>
                    <div id="ginbox" data-auth="<?php echo $auth ?>">
                        <?php
                            $a = '<img src="/emoji/img/blank.gif" class="img" style="display:inline-block;width:25px;height:25px;';
                            $b0 = "background:url('/emoji/img/emoji_spritesheet_0.png')";
                            $b1 = "background:url('/emoji/img/emoji_spritesheet_1.png')";
                            $b2 = "background:url('/emoji/img/emoji_spritesheet_2.png')";
                            $b3 = "background:url('/emoji/img/emoji_spritesheet_3.png')";
                            $b4 = "background:url('/emoji/img/emoji_spritesheet_4.png')";
                            $c = "no-repeat;background-size:";
                            $emoji_params = [$a, $b0, $b1, $b2, $b3, $b4, $c];
                            $emoji_replace = [':emoji_source:', ':emoji_bg0', ':emoji_bg1', ':emoji_bg2', ':emoji_bg3', ':emoji_bg4', ':emoji_size'];
                            ?>
                        <?php foreach ($messages as  $message) { ?>
                            <div class="inbox-message <?php if ($_SESSION['auth']['id'] == $message['user_id']) {
                                                                    echo "user";
                                                                } else {
                                                                    echo "receiver";
                                                                } ?>">
                                <p class="uer">
                                    <small class="<?php if ($_SESSION['auth']['id'] == $message['user_id']) {
                                                                echo "text-cyan";
                                                            } else {
                                                                echo "text-pink";
                                                            } ?>">
                                        <?php echo getUser($message['user_id'], $con); ?></small> <br>
                                    <?php echo str_replace($emoji_replace, $emoji_params, $message['message']); ?>
                                    <img class="user icon" src="/images/user.svg">
                                    <br>
                                    <small><?php echo longTime($message['created_at'], $months); ?> </small>
                                    <?php /*
                                    <span class="edit" data-group-message="<?php echo $message['id'] ?> ">
                                        <button class="btn btn-sm btn-success"><i class="fa fa-pencil "></i></button><button class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i> </button>
                                    </span>
                                    */ ?>
                                </p>
                                <!-- nothing -->
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <form id="gmessageForm" class="">
                    <input type="hidden" id="gforpage" value="2">
                    <div class="text-left">
                        <p class="lead emoji-picker-container">
                            <textarea class="form-control textarea-control" id="gform-emoji" rows="2" placeholder="Type your message here" data-emojiable="true" name="message"></textarea>
                        </p>
                        <p>
                            <input type="file" name="images[]" id="gfile" placeholder="" aria-describedby="fileHelpId" accept="image/*" multiple>
                            <button type="submit" class="btn btn-primary btn-md btn-block" id="send">send</button>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php
} else {
    $_SESSION['error_page'] = 'Page not found';
    header('location:/404.php');
} ?>
<?php require dirname(__DIR__) . '/includes/footer.php'; ?>


<script>

</script>

</body>

</html>
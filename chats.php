<!DOCTYPE html>
<html lang="en">

<head>
    <title>Messages</title>
    <link rel="stylesheet" href="fontawsome/css/font-awesome.min.css">
    <link rel="stylesheet" href="emoji/css/emoji.css">
    <?php require __DIR__ . '/includes/head.php';
    if (!isset($_SESSION['auth']['id'])) {
        header('location: /index.php');
    }
    $auth = $_SESSION['auth']['id'];

    $sql = "SELECT m.id AS mid, u.id, m.user_id,  m.receiver_id, m.message, m.created_at, u.name, u.status
        FROM users AS u  INNER JOIN messages AS m ON u.id = m.user_id WHERE m.user_id != :id AND m.receiver_id = :id ORDER BY m.created_at DESC ";
    $stmt = $con->prepare($sql);
    $stmt->execute([':id' => $_SESSION['auth']['id'], ':id' => $_SESSION['auth']['id']]);

    $sql1 = "SELECT m.id AS mid, u.id, m.user_id,  m.receiver_id, m.message, m.created_at, u.name, u.status
        FROM users AS u  INNER JOIN messages AS m ON u.id = m.user_id WHERE m.user_id = :id AND m.receiver_id != :id ORDER BY m.created_at DESC ";
    $stmt1 = $con->prepare($sql1);
    $stmt1->execute([':id' => $_SESSION['auth']['id'], ':id' => $_SESSION['auth']['id']]);
    // $inbox = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // $inbox = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql_users = "SELECT id, name, status, created_at FROM users WHERE id != :id 
        AND id NOT IN
        (SELECT user_id FROM messages GROUP BY user_id) 
        AND id NOT IN(SELECT receiver_id FROM messages GROUP BY receiver_id)";
    $stmt_users = $con->prepare($sql_users);
    $stmt_users->execute([':id' => $_SESSION['auth']['id']]);
    $users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
    $a = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $b = $stmt1->fetchAll(PDO::FETCH_ASSOC);
    $merge = array_merge($a, $b);
    usort($merge,  function ($a, $b) {
        if ($a["mid"] == $b["mid"]) {
            return 0;
        }
        return ($a["mid"] < $b["mid"]) ? -1 : 1;

        // return strcmp($a["id"], $b["id"]);
    });
    $res = array_reverse($merge);
    ?>
</head>

<body>

    <header>
        <?php
        require __DIR__ . '/includes/icon_header.php';
        ?>
    </header>
    <div class="container">
        <div class="row">
            <div class="col">
                <?php
                // echo json_encode($con->errorInfo());
                // die(json_encode($users));
                ?>
                <div class="message-section" id="message-section">
                    <div class="row mb-2 inbox-heading">
                        <div class=" col-8">
                            Users
                        </div>
                        <div class="col-2">
                            Status
                        </div>
                        <div class="col-2">
                            Action
                        </div>
                    </div>
                    <div class="row mb-2" id="search-users">
                        <div class="col-12 text-center my-2">
                            <p class="text-info card-title font-weight-bold">Search Results</p>
                        </div>
                        <div class="col-12">
                            <div class="row" id="searched-users">
                            </div>
                        </div>
                        <div class="col-12 my-2">
                            <button type="button" id="clear-search-users" class="btn btn-danger btn-sm btn-block">Double Click to Clear Search <i class="fa fa-trash" aria-hidden="true"></i> </button>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <?php
                        /**
                         * preg replace emoji
                         */
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
                        $uids = [];
                        $chats = [];
                        foreach ($res as $value) {
                            if (in_array((int) ($value['id'] + $value['receiver_id']), $uids)) {
                                continue;
                            }
                            $uids[] = (int) ($value['id'] + $value['receiver_id']);
                            $chats[] = (int) ($value['id']);
                            $chats[] = (int) ($value['receiver_id']);
                            ?>
                            <div class=" col-8 mb-2">
                                <small>
                                    <?php if ($value['user_id'] == $_SESSION['auth']['id']) {
                                            $fid = $value['receiver_id'];
                                            $fname = getUser($fid, $con);
                                        } else {
                                            $fid = $value['id'];
                                            $fname = $value['name'];
                                        }
                                        echo $fname;
                                        echo countUnread($auth, $fid, $con);
                                        $stripmesage = substr(strip_tags($value['message']), 0, 150);
                                        $message = str_replace($emoji_replace, $emoji_params, $value['message']);

                                        if (preg_match('/^\:emoji_source.+:emoji_size/',  $stripmesage)) {
                                            $pmessage = preg_match('/^(<img src="\/emoji\/img\/blank\.gif".*?">){0,10}/', $message, $matches, PREG_OFFSET_CAPTURE);
                                            $match = $matches[0][0];
                                        } else {
                                            $match  = substr(strip_tags($message), 0, 34);
                                        }

                                        ?>
                                </small>
                                <br>
                                <small>
                                    <?php
                                        echo  $match . (strlen(strip_tags($message)) > 34 || preg_match('/\:emoji_source.+:emoji_size/',  $stripmesage)  ? " ..." : "");
                                        ?>
                                </small>
                                <?php echo (strlen(strip_tags($value['message'])) < 1 ? '<small style="color:red; font-weight:900;">&#x1F642; </small>' : ''); ?>
                            </div>
                            <div class="col-2 mb-2">
                                <?php echo getStatus($fid, $con) ?>
                            </div>
                            <div class="col-2 mb-2">
                                <?php if (isset($_SESSION['auth'])) { ?>
                                    <button type="button" class="btn btn-primary btn-rounded btn-sm p-1 chat-btn" title="chat with <?php echo $fname; ?>" data-user="<?php echo $fid; ?>" data-name="<?php echo $fname; ?>">
                                        <i class=" fa fa-envelope" aria-hidden="true"></i>
                                    </button>
                                <?php } else { ?>
                                    <button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#login">
                                        Login/Signup
                                    </button>
                                <?php } ?>
                            </div>
                            <div class="col-12 m-0 p-0">
                                <hr class=" alert-danger">
                            </div>
                        <?php } ?>

                    </div>
                    <?php
                    // $userids = [];
                    foreach ($users as $user) {
                        // $userids[] = (int) $user['id'];
                    }
                    // $leftUsers = array_diff($userids, $chats);
                    ?>
                    <?php
                    // if (count($leftUsers) > 0) { 
                    ?>
                    <div class="row mb-3">
                        <div class="col-12 text-center mb-2">
                            <p class="btn btn-primary rounded">
                                Start a conversation with the following Users
                            </p>
                        </div>
                        <?php
                        foreach ($users as $value) {
                            if (in_array((int) ($value['id']), $chats)) {
                                continue;
                            }
                            ?>
                            <div class=" col-8 mb-2">
                                <?php echo $value['name']; ?>
                                <br>
                                <small>Start a conversation </small>
                            </div>
                            <div class="col-2 mb-2">
                                <?php echo getStatus($value['id'], $con); ?>
                            </div>
                            <div class="col-2 mb-2">
                                <?php if (isset($_SESSION['auth'])) { ?>
                                    <button type="button" class="btn btn-primary btn-rounded btn-sm p-1 chat-btn" title="chat with <?php echo $value['name'] ?>" data-user="<?php echo $value['id']; ?>">
                                        <i class="fa fa-envelope" aria-hidden="true"></i> </button>
                                <?php } else { ?> <button class=" btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#login">
                                        Login/Signup
                                    </button>
                                <?php } ?>
                            </div>
                            <div class="col-12 m-0 p-0">
                                <hr class=" alert-danger">
                            </div>
                        <?php } ?>
                    </div>
                    <?php // } 
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php require __DIR__ . '/includes/footer.php'; ?>
</body>

</html>
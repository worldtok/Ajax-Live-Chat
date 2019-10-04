<?php

require __DIR__ . '/includes/Db.php';

if (!isset($_SESSION['auth'])) {
    die(json_encode(['status' => 419, 'message' => 'Something went wrong']));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $auth = $_SESSION['auth']['id'];
    $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
    $stmt = $con->prepare($sql);
    $stmt->execute([':id' => $_SESSION['auth']['id']]);
    $user =  $stmt->fetch();
    if (!$user) {
        die(json_encode(['status' => 419, 'message' => 'Something went wrong']));
    }


    $sql = "SELECT m.id, m.user_id, m.message, m.created_at, u.name
        FROM messages AS m INNER JOIN users AS u ON u.id = m.user_id WHERE m.created_at BETWEEN :notice AND now() AND m.user_id != :id AND m.receiver_id = :id AND m.seen_at IS NULL GROUP BY m.user_id";
    $stmt = $con->prepare($sql);
    $stmt->execute([':notice' => $user['inbox_notice'], ':id' => $_SESSION['auth']['id'], ':id' => $_SESSION['auth']['id']]);
    /* fetch inbox message for notification */
    $inbox = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /* fetch group */
    // $group_sql = "SELECT g.id, g.name, g.slug, gm.id, gm.message, gm.user_id, gm.created_at
    //     FROM groups AS g
    //     INNER JOIN group_messages AS gm ON g.id = gm.group_id";
    $group_sql = "SELECT g.id AS gid, g.name, g.slug, gm.id, gm.message, gm.user_id, gm.updated_at 
        FROM groups AS g 
            INNER JOIN group_messages AS gm ON g.id = gm.group_id
       WHERE gm.updated_at 
       BETWEEN :notice AND now()
    AND gm.user_id != :id AND gm.seen_users REGEXP BINARY :regx
    GROUP BY g.id 
    ORDER BY gm.updated_at
    ";
    // $stmt1 = $con->prepare($group_sql);
    // $stmt1->execute();
    // $groups = $stmt1->fetchAll(PDO::FETCH_ASSOC);
    // // die(json_encode($groups));
    // $group_sql = "SELECT `id`, `user_id`, `message`,`created_at`, `group_id` 
    //     FROM group_messages WHERE updated_at BETWEEN :notice AND now() AND user_id != :id LEFT JOIN groups ON group_messages.group_id = group.name GROUP BY group_id";
    $stmt1 = $con->prepare($group_sql);
    $stmt1->bindValue(':notice', $user['group_notice'], PDO::PARAM_STR);
    $stmt1->bindValue(':id', $_SESSION['auth']['id'], PDO::PARAM_STR);
    $stmt1->bindValue(':regx', "^((?!,$auth,).)*$", PDO::PARAM_STR);
    $stmt1->execute();
    /* fetch group message for notification */
    $group = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    /* Update online status and others */
    $settime = "UPDATE users SET `last_seen` = now(), `status`= 1 WHERE id = :id";
    $stmt_time = $con->prepare($settime);
    $stmt_time->execute([':id' => $auth]);

    /**
     *  update other users last seen incase they had unexpected shu
     *  without properly closiong the browser or logging out 
     **/
    $sql = "UPDATE  users SET  `status`= 0 WHERE `status` = 1 AND UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(`last_seen`) > 180";
    $con->query($sql);


    // die(json_encode($group));

    /* return a valid json */
    die(json_encode([
        'status' => 200,
        'inbox' => $inbox,
        'group' => $group,
        'con' => $con->errorInfo()
    ]));


    /* update user last notification check */
    $update = "UPDATE users SET inbox_notice = now() WHERE id = :id";
    $stmt1 = $con->prepare($update);
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

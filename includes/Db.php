<?php
session_start();
date_default_timezone_set('Africa/Lagos');
require dirname(__DIR__) . '/vendor/autoload.php';

try {
    $con = new \PDO(
        "mysql:host=localhost;dbname=chat;",
        'root',
        '',
        [
            \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_PERSISTENT => false


        ]
    );
    // $con->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
} catch (\PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit();
    die();
    return;
}

if (isset($_SESSION['auth'])) {
    $auth = $_SESSION['auth']['id'];
}

function getUser($id, $con)
{
    $sql = "SELECT* FROM users WHERE id = :id LIMIT 1";
    $stmt = $con->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $res = $stmt->fetch();
    if ($res) {
        return $res['name'];
    }
    return 'Anonimous';
}
function countUnread($user, $receiver, $con)
{
    $sql = "SELECT COUNT(*) AS unread FROM messages 
        WHERE seen_at IS NULL AND user_id = :receiver AND receiver_id = :user";
    $stmt = $con->prepare($sql);
    $stmt->bindValue(':receiver', $receiver, PDO::PARAM_INT);
    $stmt->bindValue(':user', $user, PDO::PARAM_INT);
    $stmt->execute();
    $res = $stmt->fetch();

    $sql = "SELECT created_at FROM messages 
        WHERE seen_at IS NULL AND user_id = :receiver AND receiver_id = :user LIMIT 1";
    $stmt = $con->prepare($sql);
    $stmt->bindValue(':receiver', $receiver, PDO::PARAM_INT);
    $stmt->bindValue(':user', $user, PDO::PARAM_INT);
    $stmt->execute();
    $first = $stmt->fetch();

    $sql = "SELECT created_at FROM messages 
        WHERE seen_at IS NULL AND user_id = :receiver AND receiver_id = :user ORDER BY id DESC LIMIT 1 ";
    $stmt = $con->prepare($sql);
    $stmt->bindValue(':receiver', $receiver, PDO::PARAM_INT);
    $stmt->bindValue(':user', $user, PDO::PARAM_INT);
    $stmt->execute();
    $last = $stmt->fetch();
    if ($res) {
        if ($res['unread'] != 0) {
            $date = new DateTime($first['created_at']);
            $datel = new DateTime($last['created_at']);
            $f = $date->format('Y') == date('Y') ? $date->format('D, jS M') : $date->format('l, jS F Y');
            $l = $datel->format('Y') == date('Y') ? $datel->format('D, jS M') : $datel->format('l, jS F Y');
            $a = '<br><b><small><i class="circle unread-count">' . $res['unread'] . '</i>';
            if ($f == $l) {
                $a .= '   From ' . $f;
            } else {
                $a .= '   From ' . $f;
                $a .= '   to ' . $l;
            }
            $a .= '</small></b>';
            return $a;
        }
    }
    return false;
}
function groupUnread($user, $group, $con)
{
    $sql = "SELECT COUNT(*) AS unread FROM group_messages 
        WHERE group_id = :group_id AND user_id != :id 
                     AND seen_users REGEXP BINARY :regx";

    $stmt = $con->prepare($sql);
    $stmt->bindValue(':group_id', $group, PDO::PARAM_INT);
    $stmt->bindValue(':id', $user, PDO::PARAM_INT);
    $stmt->bindValue(':regx', "^((?!,$user,).)*$", PDO::PARAM_STR);
    $stmt->execute();
    $res = $stmt->fetch();

    $first = "SELECT created_at FROM group_messages 
    WHERE group_id = :group_id AND user_id != :id 
    AND seen_users REGEXP BINARY :regx LIMIT 1";

    $stmt_first = $con->prepare($first);
    $stmt_first->bindValue(':group_id', $group, PDO::PARAM_INT);
    $stmt_first->bindValue(':id', $user, PDO::PARAM_INT);
    $stmt_first->bindValue(':regx', "^((?!,$user,).)*$", PDO::PARAM_STR);
    $stmt_first->execute();
    $first = $stmt_first->fetch();


    $last = "SELECT created_at FROM group_messages 
    WHERE id IN(SELECT MAX(id) FROM group_messages 
    WHERE group_id = :group_id AND user_id != :id 
    AND seen_users REGEXP BINARY :regx)";

    $stmt_last = $con->prepare($last);
    $stmt_last->bindValue(':group_id', $group, PDO::PARAM_INT);
    $stmt_last->bindValue(':id', $user, PDO::PARAM_INT);
    $stmt_last->bindValue(':regx', "^((?!,$user,).)*$", PDO::PARAM_STR);
    $stmt_last->execute();
    $last = $stmt_last->fetch();


    if ($res) {
        if ($res['unread'] != 0) {
            $date = new DateTime($first['created_at']);
            $datel = new DateTime($last['created_at']);
            $f = $date->format('Y') == date('Y') ? $date->format('D, jS M') : $date->format('l, jS F Y');
            $l = $datel->format('Y') == date('Y') ? $datel->format('D, jS M') : $datel->format('l, jS F Y');
            $a = '<small><i class="circle unread-count">' . $res['unread'] . '</i>';
            if ($f == $l) {
                $a .= '   From ' . $f;
            } else {
                $a .= '   From ' . $f;
                $a .= '   to ' . $l;
            }
            $a .= '</small>';
            return $a;
        }
    }
    return;
}
function getStatus($id, $con)
{
    $sql = "SELECT* FROM users WHERE id = :id LIMIT 1";
    $stmt = $con->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $res = $stmt->fetch();
    if ($res) {
        if ($res['status'] == 1) {
            return '<span class="badge badge-success">online</span>';
        } else {
            return '<span class="badge badge-warning">offline</span>';
        }
    }
    return  '<span class="badge badge-warning">offline</span>';
}

function groupDetail($group, $con)
{
    $sql = "SELECT id, name, slug, description, image FROM groups WHERE id = :id LIMIT 1";
    $stmt = $con->prepare($sql);
    $stmt->execute([':id' => $group]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        return $row;
    }
}

/* date time */
function determine($num)
{
    return $num > 1 ? 's' : '';
}
$days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat'];
$months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

function longTime($timestamp, $months)
{
    $now = time();
    $dt = strtotime($timestamp);
    $diff = ($now - $dt);
    // return $diff . ' dt = ' . $dt . ' now = ' . $now;
    $secsAgo =  abs(floor($diff));
    $minsAgo =  abs(floor($secsAgo / 60));
    $hrsAgo =  abs(floor($minsAgo / 60));
    $daysAgo =  abs(floor($hrsAgo / 24));
    $wksAgo =  abs(floor($daysAgo / 7));
    $mnthsAgo =  abs(floor($wksAgo / 4));
    $yrsAgo =  abs(floor($mnthsAgo / 12));
    if ($secsAgo < 60) {

        return $secsAgo . ' seconds ago';
    } else if ($minsAgo < 60) {

        return "$minsAgo minute" . determine($minsAgo) . " ago";
    } else if ($hrsAgo < 24) {

        return "$hrsAgo hour" . determine($hrsAgo) . " ago";
    } else if ($daysAgo < 7) {

        return "$daysAgo} day" . determine($daysAgo) . " ago";
    } else if ($wksAgo < 4) {

        return "$wksAgo week" . determine($wksAgo) . "ago";
    } else {
        return 'not available';
    }
}

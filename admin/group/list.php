 <?php
    $group_sql = "SELECT * FROM groups";
    $groups = $con->query($group_sql)->fetchAll(PDO::FETCH_ASSOC);

    $user_sql = "SELECT* FROM users ORDER BY id DESC";
    $users = $con->query($user_sql)->fetchAll(PDO::FETCH_ASSOC);
    ?>
 <header>
     <?php require dirname(__DIR__) . '/includes/header.php'; ?>
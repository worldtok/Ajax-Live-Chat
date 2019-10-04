<?php


require dirname(dirname(__DIR__)) . '/includes/Db.php';
require dirname(dirname(__DIR__)) . '/includes/File.php';

// $path = "C:xampphtdocswwwchatmessaging/storage/groups/profile/bestwebhost.jpg";
// $path1 = strrchr($path, "C:xampphtdocswwwchatmessaging/storage/");

// die(json_encode($_SERVER));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    if (!isset($_POST['name']) || empty($_POST['name'])) {
        die(json_encode(['status' => 419, 'message' => 'The name field is required']));
        exit();
        return;
    }
    if (isset($_FILES['image']) && !empty($_FILES['image'])) {
        $image = $_FILES['image'];
        $group_image = File::upload(
            $image,
            "groups/profile/" . time() . '.' . extension($image),
            30000000,
            ['image' => ['png', 'jpg', 'jpeg', 'gif']]
        );
    } else {
        $group_image = null;
    }


    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $admin_id = 1; //$_SESSION['admin']['id']
    $slug = strtolower(preg_replace("/\s+/", "-", preg_replace("/[^a-zA-Z0-9\s]/", " ", $name)));

    $sql = "SELECT*  FROM groups WHERE  slug = :slug LIMIT 1";
    $stmt = $con->prepare($sql);
    $stmt->execute([':slug' => $slug]);
    $row = $stmt->fetch();
    if ($row) {
        die(json_encode(['status' => 422, 'message' => 'This group name already exists']));
    }

    $sql = "INSERT INTO 
            groups (`name`, `slug`, `image`, `description`, `super_admin`, `admin_id`) 
            VALUES('$name', '$slug', '$group_image', '$description', '$admin_id', '$admin_id')";
    $res = $con->query($sql);

    $info = json_encode($con->errorInfo());

    if (!$res) {
        echo json_encode(['status' => 500, 'message' => "Something went wrong, try again $info"]);
        die();
    }

    echo json_encode(['status' => 200, 'message' => 'Group created successfully']);
    die();
    return;
}
echo json_encode(['status' => 500, 'message' => 'server error, Please refresh your browser']);
die();

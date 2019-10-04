<?php


require dirname(dirname(__DIR__)) . '/includes/Db.php';
require dirname(dirname(__DIR__)) . '/includes/File.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    if (!isset($_POST['name']) || empty($_POST['name'])) {
        die(json_encode(['status' => 419, 'message' => 'The name field is required']));
    }
    $id = $_SESSION['edit_group'];
    $slug = trim($_POST['slug']);
    $slug = strtolower(preg_replace("/\s+/", "-", preg_replace("/[^-a-zA-Z0-9\s]/", " ", $slug)));

    $slugs = $con->prepare("SELECT slug FROM groups WHERE id != :id");
    $slugs->execute([':id' => $id]);
    $slugs = $slugs->fetchAll(PDO::FETCH_COLUMN);
    if (in_array($slug, $slugs)) {
        die(json_encode(['message' => 'This url is already tied to another group']));
    }


    if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
        $image = $_FILES['image'];
        $group_image = File::upload(
            $image,
            "groups/profile/" . $slug . '.' . extension($image),
            30000000,
            ['image' => ['png', 'jpg', 'jpeg', 'gif']]
        );
    } else {
        $group_image = null;
    }


    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $admin = 1; //$_SESSION['admin']['id']
    if ($group_image == null) {
        $sql = "UPDATE groups SET 
            `name` = :name, `slug`= :slug,  `description`= :description, `admin_id`= :admin, `updated_at` = now()
            WHERE id = :id";
        $stmt = $con->prepare($sql);
        $res = $stmt->execute([':name' => $name, ':slug' => $slug, ':description' => $description, ':admin' => $admin, ':id' => $id]);
    } else {
        $sql = "UPDATE groups SET 
           `name` = :name, `slug`= :slug, image = :image, `description`= :description, `admin_id`= :admin, `updated_at` = now()
            WHERE id = :id";
        $stmt = $con->prepare($sql);
        $res = $stmt->execute([':name' => $name, ':slug' => $slug, ':image' => $group_image, ':description' => $description, ':admin' => $admin,  ':id' => $id]);
    }

    $info = json_encode($con->errorInfo());
    if (!$res) {
        echo json_encode(['status' => 500, 'message' => "Something went wrong, try again $info, $group_image"]);
        die();
    }

    echo json_encode(['status' => 200, 'message' => 'Group updated successfully']);
    die();
    return;
} else {
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        die(json_encode(['message' => 'Something went wrong', 'status' => 400]));
    }
    $id = trim($_GET['id']);
    $sql = "SELECT id, name, slug, description, image FROM groups WHERE id = :id LIMIT 1";
    $stmt = $con->prepare($sql);
    $stmt->execute([':id' => $id]);
    $group = $stmt->fetch(PDO::FETCH_ASSOC);
    $_SESSION['edit_group'] = $group['id'];
    die(json_encode(['status' => 200, 'data' => $group]));
}
echo json_encode(['status' => 500, 'message' => 'server error, Please refresh your browser']);
die();

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    if (!isset($_SESSION['admin'])) {
        header('location:/404.php');
    }
    ?>

    <link rel="stylesheet" href="fontawsome/css/font-awesome.min.css">
    <?php require dirname(__DIR__) . '/includes/head.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create new Admins</title>

</head>

<div class="container-fluid mt-5">
    <div class="row">
        
    </div>
</div>
<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
<script>
    const adminLogin = $("#adminLogin");
    adminLogin.on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "/admin/login.php",
            method: "POST",
            data: new FormData(this),
            dataType: "JSON",
            cache: false,
            contentType: false,
            processData: false,
            error: function(xhr, status) {
                noty('danger', status, 3000)
            },
            success: function(data) {
                if (data.status == 200) {
                    noty('success', data.message, 2000);
                    window.location.href = "/";
                    return;
                }
                noty('danger', data.message, 3000);
                return;
            }
        });
    });
</script>

<body>

</body>

</html>
<?php

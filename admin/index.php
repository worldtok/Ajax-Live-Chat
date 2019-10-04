<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    if (isset($_SESSION['admin'])) {
        header('location: /');
    }
    ?>

    <link rel="stylesheet" href="fontawsome/css/font-awesome.min.css">
    <?php require dirname(__DIR__) . '/includes/head.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login to continue</title>

</head>

<div class="container-fluid mt-5">
    <div class="row home">
        <div class="col-12 text-center mt-5">
            <h4 class="font-weight-bold home-title mt-2">
                <img src="/storage/images/logo.png" alt="awsome logo" class="logo">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                ADMIN SECTION
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </h4>
            <ul class="list-inline services">
                <li class="list-inline-item"><a href="/groups/?group=digital">DIGITAL</a></li>|
                <li class="list-inline-item"><a href="/groups/?grouo=technology">TECHNOLOGY</a></li>|
                <li class="list-inline-item"> <a href="/groups/?group=marketing">MARKETING</a></li>|
                <li class="list-inline-item"><a href="/groups/?group=social">SOCIAL</a></li>|
                <li class="list-inline-item"><a href="/groups/?group=support">SUPPORT</a></li>
            </ul>
        </div>
        <div class="col-12 text-center mt-5">
            <p>
                Administrator Login
            </p>
        </div>
        <div class="col-12 mt-5">
            <form id="adminLogin">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-sm">
                            <i class="fa fa-user"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" name="email" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-sm">
                            <i class="fa fa-lock"></i>
                        </span>
                    </div>
                    <input name="password" type="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1">
                </div>
                <button type="submit" class="btn btn-primary btn-sm btn-block">
                    <span class=" float-left">Login</span> <span class=" float-right"><i class="fa fa-sign-in"></i></span>
                </button>
            </form>
        </div>
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

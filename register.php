<!DOCTYPE html>
<html lang="en">

<head>
    <?php require __DIR__ . '/includes/head.php' ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<?php if (isset($_SESSION['auth'])) {
    header('location:/chats.php');
} ?>

<body>
    <div class="container">
        <div class="row">
            <div class="col">
                <div>
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Login</h4>
                            </div>
                            <div class="modal-body">
                                <form id="loginForm">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroup-sizing-sm">
                                                <i class="fa fa-envelope"></i>
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
                                    <button type="submit" name="commit" class="btn btn-primary btn-lg btn-block">
                                        <span>Login <i class="fa fa-sign-in"></i></span>
                                    </button>
                                    <div class="text-center">
                                        <a class="" href="" data-dismiss="modal" data-toggle="modal" data-target="#signup">Signup</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require __DIR__ . '/includes/footer.php' ?>
    <script src="/js/app.js"></script>


</body>

</html>
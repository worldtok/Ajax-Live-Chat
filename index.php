<!DOCTYPE html>
<html lang="en">

<head>

    <link rel="stylesheet" href="fontawsome/css/font-awesome.min.css">
    <?php require __DIR__ . '/includes/head.php'; ?>
    <title>Welcome to chat secetion</title>
</head>

<body>

    <header>
        <?php
        if (!isset($_SESSION['auth'])) {
            require __DIR__ . '/includes/header.php';
        } else {
            header('location:chats.php');
        }
        ?>
    </header>
    <div class="container-fluid mt-5">
        <div class="row home">
            <div class="col-12 text-center mt-5">
                <h4 class="font-weight-bold home-title mt-2">
                    <img src="/storage/images/logo.png" alt="awsome logo" class="logo">
                    CHAT SECTION FOR EVERYONE
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
                    LOgin To join the discussion
                </p>
            </div>
            <div class="col-12 mt-5">
                <form class="loginForm">
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
                    <div class="text-center">
                        <a class="" href="" data-dismiss="modal" data-toggle="modal" data-target="#signup">Signup</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php require __DIR__ . '/includes/footer.php'; ?>
</body>

</html>
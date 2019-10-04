<!DOCTYPE html>
<html lang="en">

<head>
    <?php require dirname(__DIR__) . '/includes/head.php'; ?>
    <title>Manage Groups and Users</title>
    <link rel="stylesheet" href="fontawsome/css/font-awesome.min.css">
    <link rel="stylesheet" href="emoji/css/emoji.css">
</head>

<body>

    </header>
    <div class="container">
        <div class="row mt-5">
            <div class="col mt-5">
                <div class="login-form mt-5">
                    <div class="login-content">
                        <div class="form-login-error">
                            <h3>Invalid login</h3>
                        </div>
                        <form method="post" role="form" id="form_login" novalidate="novalidate">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="entypo-user"></i>
                                    </div>
                                    <input type="text" class="form-control valid" name="username" id="username" placeholder="Username" autocomplete="off" aria-invalid="false">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="entypo-key"></i>
                                    </div>
                                    <input type="password" class="form-control valid" name="password" id="password" placeholder="Password" autocomplete="off" aria-required="true" aria-invalid="false">
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block btn-login">
                                    <i class="entypo-login"></i>
                                    Login
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require dirname(__DIR__) . '/includes/footer.php'; ?>
    <script src="emoji/js/config.js"></script>
    <script src="emoji/js/util.js"></script>
    <script src="emoji/js/jquery.emojiarea.js"></script>
    <script src="emoji/js/emoji-picker.js"></script>

    <script>
        $(function() {
            window.emojiPicker = new EmojiPicker({
                emojiable_selector: '[data-emojiable=true]',
                assetsPath: 'emoji/img/',
                popupButtonClasses: 'fa fa-smile-o'
            });
            window.emojiPicker.discover();
        });
    </script>
</body>

</html>
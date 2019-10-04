<!DOCTYPE html>
<html lang="en">

<head>

    <?php require __DIR__ . '/includes/head.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>404 <?php if (isset($_SESSION['error_page'])) { ?>
        <?php echo '-' . $_SESSION['error_page'];
        } else {
            echo "- page";
        } ?></title>
</head>

<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col text-center mt-5">
                <h1 class=" text-danger font-weight-bold p-3">
                    <?php if (isset($_SESSION['error_page'])) { ?>
                    <?php echo  $_SESSION['error_page'];
                    } else {
                        echo "You have arrived by default";
                    } ?>
                </h1>

            </div>
        </div>
    </div>

</body>
<?php unset($_SESSION['error_page']); ?>

</html>
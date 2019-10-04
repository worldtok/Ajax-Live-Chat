<!DOCTYPE html>
<html lang="en">

<head>
    <?php require __DIR__ . '/includes/head.php'; ?>
    <title>Messages</title>
    <link rel="stylesheet" href="/fontawsome/css/font-awesome.min.css">
</head>

<body>

    <header>
        <?php
        if (isset($_SESSION['auth'])) {
            require __DIR__ . '/includes/icon_header.php';
        } else {
            require __DIR__ . '/includes/header.php';
        }
        $group_sql = "SELECT id, group_id, user_id, message FROM group_messages WHERE id IN (SELECT MAX(id) FROM group_messages GROUP BY group_id) ORDER BY id DESC";
        $groups = $con->prepare($group_sql);
        $groups->execute();
        $groups = $groups->fetchAll(PDO::FETCH_ASSOC);
        $sql = "SELECT id, name, slug 
        FROM groups WHERE id NOT IN 
        (SELECT group_id FROM group_messages WHERE id IN 
        (SELECT MAX(id) FROM group_messages GROUP BY group_id) ORDER BY id DESC)";
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $others = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // if (count($groups) < 1) {
        //     header('location:/chats.php');
        // }
        ?>
    </header>
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center my-3">
                <!-- <h2 class=" font-weight-bold text-uppercase">groups</h2> -->
                <?php
                // echo json_encode($con->errorInfo());
                // die(json_encode($others));
                ?>
            </div>
            <div class="col-12 col-md-10 offse-md-1 col-lg-6 offset-lg-3">
                <?php
                /**
                 * preg replace emoji
                 */
                $a = '<img src="/emoji/img/blank.gif" class="img" style="display:inline-block;width:25px;height:25px;';
                $b0 = "background:url('/emoji/img/emoji_spritesheet_0.png')";
                $b1 = "background:url('/emoji/img/emoji_spritesheet_1.png')";
                $b2 = "background:url('/emoji/img/emoji_spritesheet_2.png')";
                $b3 = "background:url('/emoji/img/emoji_spritesheet_3.png')";
                $b4 = "background:url('/emoji/img/emoji_spritesheet_4.png')";
                $c = "no-repeat;background-size:";
                $emoji_params = [$a, $b0, $b1, $b2, $b3, $b4, $c];
                $emoji_replace = [
                    ':emoji_source:',
                    ':emoji_bg0',
                    ':emoji_bg1',
                    ':emoji_bg2',
                    ':emoji_bg3',
                    ':emoji_bg4',
                    ':emoji_size'
                ];
                foreach ($groups as $group) {
                    $detail = groupDetail($group['group_id'], $con);
                    ?>
                    <div class=" my-2">
                        <div class="btn-group btn-sm float-right" role="group" aria-label="Button group">
                            <button class=" btn btn-success btn-sm  group-edit" data-edit="<?php echo $group['group_id']; ?>"><i class="fa fa-pencil" aria-hidden="true"></i> </button>
                            <button class=" btn btn-danger btn-sm  group-delete" data-edit="<?php echo $group['group_id']; ?>"><i class="fa fa-trash-o" aria-hidden="true"></i> </button>
                        </div>
                        <a href="/group/<?php echo $detail['slug']; ?>">
                            <div class="media">
                                <img class="align-self-start media-icon" src="storage/<?php echo $detail['image']; ?>" alt="<?php echo $detail['name']; ?>">
                                <div class="media-body ml-3">
                                    <h5 class="mb-0"><?php echo $detail['name']; ?></h5>
                                    <?php
                                        if (isset($_SESSION['auth'])) {
                                            $stripmesage = substr(strip_tags($group['message']), 0, 150);
                                            $message = str_replace($emoji_replace, $emoji_params, $group['message']);

                                            if (preg_match('/^\:emoji_source.+:emoji_size/',  $stripmesage)) {
                                                $pmessage = preg_match('/^(<img src="\/emoji\/img\/blank\.gif".*?">){0,5}/', $message, $matches, PREG_OFFSET_CAPTURE);
                                                $match = $matches[0][0];
                                            } else if (strlen(strip_tags($message)) === 0) {
                                                $match = 'Sent an attachment';
                                            } else {
                                                $match  = substr(strip_tags($message), 0, 34);
                                            }


                                            echo '<b>' . groupUnread($auth, $group['group_id'], $con) . '</b><br class="m-0">';
                                            echo getUser($group['user_id'], $con) . ' ' . $match . (strlen(strip_tags($message)) > 34 || preg_match('/\:emoji_source.+:emoji_size/',  $stripmesage)  ? " ..." : "");
                                        } else {
                                            echo substr($detail['description'], 0, 80);
                                        }
                                        ?>
                                </div>
                            </div>
                        </a>
                        <hr>
                    </div>
                <?php  }
                ?>
                <!-- groups with no messages -->
                <div class="row my-2">
                    <div class="col-12 text-center mb-2">
                        <p class="btn btn-primary rounded">
                            New groups
                        </p>
                    </div>
                    <?php foreach ($others as $group) {
                        $detail = groupDetail($group['id'], $con);
                        ?>
                        <div class="col-12">
                            <div class="btn-group btn-sm float-right" role="group" aria-label="Button group">
                                <button class=" btn btn-success btn-sm  group-edit" data-edit="<?php echo $group['id']; ?>"><i class="fa fa-pencil" aria-hidden="true"></i> </button>
                                <button class=" btn btn-danger btn-sm  group-delete" data-edit="<?php echo $group['id']; ?>"><i class="fa fa-trash-o" aria-hidden="true"></i> </button>
                            </div>
                            <a href="/group/<?php echo $detail['slug']; ?>">
                                <div class="media">
                                    <img class="align-self-start media-icon" src="storage/<?php echo $detail['image']; ?>" alt="<?php echo $detail['name']; ?>">
                                    <div class="media-body ml-3">
                                        <h5 class="mb-0"><?php echo $detail['name']; ?></h5>
                                        <?php
                                            echo substr($detail['description'], 0, 80);
                                            ?>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 m-0 p-0">
                            <hr class=" alert-danger">
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <!-- add new group -->
        <div class="modal fade" id="newgroup" role="dialog">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Ceate new Group</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="groupForm" enctype="multipart/form-data">
                            <input type="hidden" name="a" id="action">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">
                                        <i class="fa fa-user"></i>
                                    </span>
                                </div>
                                <input type="text" name="name" class="form-control" placeholder="Group name" id="group-name">
                            </div>
                            <div class="input-group mb-3" style="display-none;" id="slug-div">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-link"></i>
                                    </span>
                                </div>
                                <input type="text" name="slug" class="form-control" placeholder="Group name" id="group-slug">
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-sm">
                                        <i class="fa fa-edit"></i>
                                    </span>
                                </div>
                                <textarea name="description" rows="4" class=" form-control" id="group-des" placeholder="group description"></textarea>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-edit"></i>
                                    </span>
                                </div>
                                <input type="file" name="image">
                                <img src="" id="group-image" height="100" width="100" style="display: none;">
                            </div>
                            <button type="submit" class="btn btn-success btn-md btn-block" id="submitBtn">
                                <span id="defaultBtn">create <i class="fa fa-sign-in"></i></span>
                                <div class="spinner-border text-white" role="status" id="loadingBtn" style="display: none;">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- add new group -->

    </div>
    <?php require __DIR__ . '/includes/footer.php'; ?>
    <script>
        var submitBtn = $("#submitBtn");
        var loadingBtn = $("#loadingBtn");
        var defaultBtn = $("#defaultBtn");
        var groupForm = $("#groupForm");
        var groupEdit = $(".group-edit");
        var groupDel = $(".group-delete");
        var action = $("#action");

        var groupName = $("#group-name");
        var groupSlug = $("#group-slug");
        var groupDes = $("#group-des");
        var groupImage = $("#group-image");
        var slugdiv = $("#slug-div");

        $(groupEdit).on('click', function(e) {
            e.preventDefault();
            $(action).val('/admin/group/update.php');
            $(groupEdit).disabled = true;
            $.ajax({
                url: "/admin/group/update.php?id=" + $(this).data().edit,
                method: "GET",
                dataType: "JSON",
                contentType: false,
                cache: false,
                processData: false,
                error: function(xhr, status) {
                    alert(status);
                    return;
                },
                success: function(data) {
                    $(groupEdit).removeAttr("disabled");
                    if (data.status == 200) {
                        $(slugdiv).css('display', 'flex');
                        $(groupName).val(data.data.name);
                        $(groupSlug).val(data.data.slug);
                        $(groupDes).val(data.data.description);
                        $(groupImage).attr('src', '/storage/' + data.data.image);
                        $(groupImage).css('display', 'inline-block');
                        $('#newgroup .modal-title').html('Edit ' + data.data.name);
                        $("#newgroup").modal("show");
                    }
                }
            });
        });

        $(groupForm).on('submit', function(e) {
            e.preventDefault();
            createGroup();
        })

        function createGroup() {
            $(loadingBtn).show();
            $(defaultBtn).hide();
            $(submitBtn).attr('disabled', true);

            $.ajax({
                url: $(action).val(),
                method: 'POST',
                data: new FormData(document.querySelector('#groupForm')),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                error: function(xhr, status) {
                    alert(status);
                    $(loadingBtn).hide();
                    $(defaultBtn).show();
                    $(submitBtn).removeAttr('disabled');
                    return;
                },
                success: function(data) {
                    $(submitBtn).removeAttr('disabled');
                    // var data = JSON.parse(data);
                    noty('success', data.message, 9000);
                    $(loadingBtn).hide();
                    $(defaultBtn).show();
                    return;

                    if (data.status == 200) {

                    } else if (data.status == 400) {
                        $("#loadingBtn").hide();
                        $('#defaultBtn').show();
                        $("#submitBtn").removeAttr('disabled');

                    }
                    $('html, body').animate({
                        scrollTop: 3
                    }, 'slow');
                    return false;
                }
            });
        };
        $(groupDel).on('click', function(e) {
            e.preventDefault();
            var c = confirm('Are you sure that you want to permantely delete this group and all of its messages? This action cannot be undone.');
            if (c) {
                var formdata = new FormData();
                formdata.append('id', $(this).data().edit);
                $.ajax({
                    url: '/admin/group/delete.php',
                    method: 'POST',
                    data: formdata,
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    error: function(xhr, status) {
                        noty('danger', 'Error', 1000);
                        return;
                    },
                    success: function(data) {
                        if (data.status == 200) {
                            noty('success', data.message, 500);
                            return window.location.reload();
                        }
                        return noty('danger', data.message, 500);
                    }
                });
            }
            return;
        });
    </script>
</body>

</html>
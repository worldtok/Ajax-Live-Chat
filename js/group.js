const gmessageForm = $("#gmessageForm");
let ginbox = $("#ginbox");
var gFormEmoji = $("#gform-emoji");
var gmoreBtn = $("#gmore");
var gscrollBox = $("#gscrol-box");
var gforpage = $("#gforpage");
if (/group\//.test(window.location.href)) {
    var user = $(ginbox).data().auth;
}

gmessageForm.click(function () {
    $(this).css("position", "absolute");
    $(this).css("bottom", "10%");
    $(this).css("right", "10px");
    $(this).css("left", "10px");
    $(this).css("background-color", "aqua");
});

$(document).click(function (e) {
    if (!$(e.target).closest("#gmessageForm").length) {
        $(gmessageForm).css("position", "relative");
        $(gmessageForm).css("bottom", "0");
    }
});
ggotoBottom();
setTimeout(ggotoBottom, 1000);

function ggotoBottom() {
    $(gscrollBox).scrollTop($(gscrollBox).prop("scrollHeight"));
    return false;
}

$(gmessageForm).on("submit", function (e) {
    e.preventDefault();
    // var emojiWiz = $('#gmessageForm > .emoji-wysiwyg-editor').val();
    var emojiWiz = document.querySelector("#gmessageForm .emoji-wysiwyg-editor")
        .innerHTML;
    gFormEmoji.val(emojiWiz);
    if (gFormEmoji.val().length === 0 && $('#gfile').val().length === 0) {
        ggotoBottom();
        noty("danger", "Pease write your message to send", 3000);
        return;
    }
    $(sendBtn).attr("disabled", true);
    $.ajax({
        url: "/group_chat.php",
        method: "POST",
        data: new FormData(this),
        dataType: "JSON",
        contentType: false,
        cache: false,
        processData: false,
        error: function (xhr, status) {
            alert(status);
            $(sendBtn).removeAttr("disabled");
            return;
        },
        success: function (data) {
            $(sendBtn).removeAttr("disabled");
            ggotoBottom();
            if (data.status == 200) {
                var mes = data.mes;
                var messageString = '<div class="inbox-message user">';
                messageString += `<p class="uer">${mes} <img class="user icon"src="/images/user.svg"><br><small>just now</small></p>`;
                messageString += `</div>`;
                $(ginbox).append(messageString);
                setTimeout(ggotoBottom, 1000);
                document.querySelector(
                    "#gmessageForm .emoji-wysiwyg-editor"
                ).innerHTML = "";
                $('#gfile').val('');
                noty("success", data.message, 2000);
                $(gmessageForm).css("position", "relative");
                $(gmessageForm).css("bottom", "0");
                return;
            }
            noty("danger", data.message, 4000);
            return false;
        }
    });
});
// ged message and format it;

function gloadMessages(receiver) {
    $(gmoreBtn).attr("disabled", true);
    $.get(`/chat.php?user=${receiver}`, function (data) {
        var data = JSON.parse(data);
        var messages = data.data;
        $(ginbox).html("");
        $("#chatModal").modal("show");
        if (messages.length === 0) {
            return;
        }
        for (let i = 0; i < messages.length; i++) {
            var messageString = "";
            messageString +=
                messages[i].receiver_id != receiver ?
                `<div class="inbox-message receiver"><p class="receiver">${messages[i].message}
               <img class="receiver icon" src="/images/whatsapp.svg">` :
                `<div class="inbox-message user"><p class="user">${messages[i].message}<img class = "user icon"src="/images/user.svg">`;
            messageString += `<br><small>${longTime(messages[i].created_at)}</small></p></div>`;
            $(ginbox).append(messageString);
        }
        if (messages.length > 9) {
            $(gmoreBtn).attr("disabled", false);
            $(gmoreBtn).attr("data-next", data.next);
        } else {
            $(gmoreBtn).attr("disabled", true);
        }
        // ggotoBottom()
    });
    setTimeout(function () {
        ggotoBottom();
    }, 3000);
    //   ggotoBottom();

    return;
}
$(gmoreBtn).on("click", function (e) {
    e.preventDefault();
    gmoreMessages(
        $(gforpage)
        .val()
        .trim()
    );
});

function gmoreMessages(index) {
    $.ajax({
        url: "/more_group.php?next=" + index,
        method: "GET",
        dataType: "JSON",
        contentType: false,
        cache: false,
        processData: false,
        error: function (xhr, status) {
            alert(status);
            $(gmoreBtn).removeAttr("disabled");
            return;
        },
        success: function (data) {
            $(gmoreBtn).removeAttr("disabled");
            if (data.status == 200) {
                var messages = data.data;
                for (let i = 0; i < messages.length; i++) {
                    var messageString = "";
                    messageString +=
                        messages[i].user_id != user ?
                        `<div class="inbox-message receiver"><p class="receiver">${messages[i].message}
               <img class="receiver icon" src="/images/whatsapp.svg">` :
                        `<div class="inbox-message user"><p class="user">${messages[i].message}<img class = "user icon"src="/images/user.svg">`;
                    messageString += `<br><small>${longTime(messages[i].created_at)}</small></p></div>`;
                    $(ginbox).prepend(messageString);
                }
                if (messages.length > 9) {
                    $(gmoreBtn).attr("disabled", false);
                    $(gforpage).val(data.next);
                } else {
                    $(gmoreBtn).attr("disabled", true);
                    $(gmoreBtn).fadeOut("fast");
                }
                return;
            }
            noty("danger", data.message, 4000);
            return false;
        }
    });
}
const messageSection = $("#message-section");
const messageForm = $("#messageForm");
// let sendBtn = $("#send");
let receiver = $("#receiver");
var chatBtn = $(".chat-btn");
let inbox = $("#inbox");
var FormEmoji = $("#form-emoji");
var modalForm = $("#modalForm");
var logout = $("#logout");
var moreBtn = $("#more");
var scrollBox = $("#scrol-box");
var forpage = $("#forpage");
var emojiTab = $(".emoji-picker-icon");

logout.on("click", function (e) {
  e.preventDefault();
  $.post(
    "logout.php",
    JSON.stringify({
      status: "Logout"
    }),

    function (data) {
      var data = JSON.parse(data);
      if (data.status == 200) {
        noty("success", data.message, 2000);
        window.location.href = "/";
      }
    }
  );
});

function gotoBottom() {
  $(scrollBox).scrollTop(900000);
  return;
}
chatBtn.on("click", function (e) {
  e.preventDefault();
  $("#next").val(2);
  loadMessages($(this).data().user);
  $('#friend').val($(this).data().user);
  $('#f-name').html($(this).data().name);
});

function noty(type, message, duration) {
  $("body")
    .append(`<div class="alert alert-${type} alert-dismissible fade show noty" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>${message}</strong>
    </div>`);
  setTimeout(function () {
    $("body")
      .find(".noty")
      .fadeOut("slow");
    $(sendBtn).attr("disabled", false);
  }, duration);
}
messageForm.on("submit", function (e) {
  e.preventDefault();
  var emojiWiz = document.querySelector('#messageForm .emoji-wysiwyg-editor').innerHTML;
  FormEmoji.val(emojiWiz);
  if (FormEmoji.val().length === 0 && $('#file').val().length === 0) {
    gotoBottom();
    noty("danger", "Pease write your message to send", 3000);
    return;
  }
  $(sendBtn).attr('disabled', true);
  $.ajax({
    url: "/send.php",
    method: 'POST',
    data: new FormData(this),
    dataType: 'JSON',
    contentType: false,
    cache: false,
    processData: false,
    error: function (xhr, status) {
      $(sendBtn).removeAttr('disabled');
      return;
    },
    success: function (data) {
      $(sendBtn).removeAttr('disabled');
      gotoBottom();
      if (data.status == 200) {
        var messageString = '<div class="inbox-message user">';
        messageString += `<p class="uer">${data.mes} <img class="user icon"src="/images/user.svg"><br><small>just now</small></p>`;
        messageString += `</div>`;
        $(inbox).append(messageString);
        setTimeout(gotoBottom, 100);
        document.querySelector('#messageForm .emoji-wysiwyg-editor').innerHTML = "";
        $('#file').val('');
        noty("success", "Message sent", 2000);
        $(sendBtn).attr("disabled", false);

        return;
      }
      $(sendBtn).attr("disabled", false);
      noty('danger', data.message, 1000);

    }
  });
});
// ged message and format it;

function loadMessages(receiver) {
  $(moreBtn).attr("disabled", true);
  $.get(`/chat.php?user=${receiver}`, function (data) {
    var data = JSON.parse(data);
    var messages = data.data;
    $(inbox).html("");
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
      messageString += `<br><small>${ longTime(messages[i].created_at) }</small></p></div>`;
      $(inbox).append(messageString);
    }
    if (messages.length > 9) {
      $(moreBtn).attr("disabled", false);
      $(moreBtn).attr("data-next", data.next);
    } else {
      $(moreBtn).attr("disabled", true);
    }
    // gotoBottom()
  });
  setTimeout(function () {
    gotoBottom();
  }, 3000);
  //   gotoBottom();

  return;
}
$(moreBtn).on("click", function () {
  moreMessages($("#next").val());
});

function moreMessages(next) {
  var formData = new FormData();
  formData.append('next', next);
  $.ajax({
    url: '/more_chat.php',
    method: "POST",
    data: formData,
    dataType: "JSON",
    contentType: false,
    cache: false,
    processData: false,
    error: function (xhr, status) {
      alert(status);
      $(moreBtn).removeAttr("disabled");
      return;
    },
    success: function (data) {
      $(moreBtn).removeAttr("disabled");
      if (data.status == 200) {
        var messages = data.data;
        for (let i = 0; i < messages.length; i++) {
          var messageString = "";
          messageString +=
            messages[i].receiver_id != $('#friend').val() ?
            `<div class="inbox-message receiver"><p class="receiver">${messages[i].message}
               <img class="receiver icon" src="/images/whatsapp.svg">` :
            `<div class="inbox-message user"><p class="user">${messages[i].message}<img class = "user icon"src="/images/user.svg">`;
          messageString += `<br><small>${longTime(messages[i].created_at)}</small></p></div>`;
          $(inbox).prepend(messageString);
        }
        if (messages.length > 9) {
          $(moreBtn).attr("disabled", false);
          $("#next").val(data.next);
        } else {
          $(moreBtn).attr("disabled", true);
        }
        return
      }
      return noty('danger', 'something went wrong', 2000);
    }
  });
}

setTimeout(function () {
  emojiTab = $('.emoji-picker-icon');
  $(emojiTab).on('click', function () {
    $(modalForm).css('margin-bottom', '150px');
    setTimeout(() => {
      $("#chatModal").scrollTop($('#chatModal').height());
    }, 100);
  });
  // $(emojiTab).on('dclik', function () {
  //   alert('done');
  // });
}, 2000);

$(document).click(function (e) {
  if (!$(e.target).closest(".emoji-picker-icon").length) {
    $(modalForm).css("margin-bottom", "0");
    $("#chatModal").scrollTop(-$('#chatModal').height());

  }
});
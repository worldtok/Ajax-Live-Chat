 <?php if (!isset($_SESSION['auth'])) { ?>
     <div class="modal fade" id="login" role="dialog">
         <div class="modal-dialog modal-md">
             <div class="modal-content">
                 <div class="modal-header">
                     <h4 class="modal-title">Login</h4>
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                 </div>
                 <div class="modal-body">
                     <form class="loginForm">
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
     </div>
     <div class="modal fade" id="signup" role="dialog">
         <div class="modal-dialog modal-md">
             <div class="modal-content">
                 <div class="modal-header">
                     <h4 class="modal-title">Signup</h4>
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                 </div>
                 <div class="modal-body">
                     <form class="signupForm">
                         <div class="input-group mb-3">
                             <div class="input-group-prepend">
                                 <span class="input-group-text" id="inputGroup-sizing-sm">
                                     <i class="fa fa-user"></i>
                                 </span>
                             </div>
                             <input name="name" type="text" class="form-control" placeholder="Name" aria-label="Name" aria-describedby="basic-addon1">
                         </div>
                         <div class="input-group mb-3">
                             <div class="input-group-prepend">
                                 <span class="input-group-text" id="inputGroup-sizing-sm">
                                     <i class="fa fa-envelope"></i>
                                 </span>
                             </div>
                             <input name="email" type="text" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1">
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
                             <span class=" float-left">Sign Up</span> <span class=" float-right"><i class="fa fa-sign-in"></i></span>
                         </button>
                         <div class="text-center">
                             <a class="" href="" data-dismiss="modal" data-toggle="modal" data-target="#login">Login</a>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
     </div>
 <?php } else { ?>
     <div id="chatModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
         <div class="modal-dialog" role="document">
             <div class="modal-content">
                 <p class=" text-center"><small class="px-5 pt-2 text-primary" id="f-name"></small> <button type="button" class="close float-right" data-dismiss="modal">&times;</button></p>
                 <div class="modal-body" id="modalForm">
                     <div class="inbox" id="scrol-box">
                         <div class="text-center" id="more-control">
                             <button class="btn btn-primary btn-sm" type="button" id="more">load more</button>
                         </div>
                         <div id="inbox">

                         </div>
                     </div>
                     <form id="messageForm" class=" mt-3 mb-5">
                         <div class="text-left">
                             <input type="hidden" id="next" value="2">
                             <input type="hidden" id="friend">
                             <p class="lead emoji-picker-container">
                                 <textarea class="form-control textarea-control" id="form-emoji" rows="3" placeholder="Textarea with emoji image input" data-emojiable="true" name="message"></textarea>
                             </p>
                             <input type="file" name="images[]" id="file" placeholder="choose file" aria-describedby="fileHelpId" accept="image/*" multiple>
                             <button type="submit" class="btn btn-primary btn-md float-right" id="send">send</button>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
     </div>
 <?php } ?>
 <script src="/js/jquery.min.js"></script>
 <script src="/js/bootstrap.min.js"></script>
 <script src="/js/TimeFormat.js"></script>
 <script src="/js/app.js"></script>
 <script src="/js/group.js"></script>
 <script src="/emoji/js/config.js"></script>
 <script src="/emoji/js/util.js"></script>
 <script src="/emoji/js/jquery.emojiarea.js"></script>
 <script src="/emoji/js/emoji-picker.js"></script>

 <script>
     //  emoji picker
     $(function() {
         window.emojiPicker = new EmojiPicker({
             emojiable_selector: '[data-emojiable=true]',
             assetsPath: '/emoji/img',
             popupButtonClasses: 'fa fa-smile-o'
         });
         window.emojiPicker.discover();
     });
     //  window.location.reload(true);
     <?php if (isset($_SESSION['auth'])) { ?>
         let sendBtn = $("#send");
         var logout = $("#logout");
         logout.on("click", function(e) {
             e.preventDefault();
             $.post(
                 "/logout.php",
                 JSON.stringify({
                     status: "Logout"
                 }),

                 function(data) {
                     var data = JSON.parse(data);
                     if (data.status == 200) {
                         noty("success", data.message, 2000);
                         window.location.href = "/";
                     }
                 }
             );
         });
     <?php } ?>

     function noty(type, message, duration) {
         $("body")
             .append(`<div class="alert alert-${type} alert-dismissible fade show noty" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>${message}</strong>
    </div>`);
         setTimeout(function() {
             $("body")
                 .find(".noty")
                 .fadeOut("slow");
         }, duration);
     }
 </script>
 <?php if (!isset($_SESSION['auth'])) { ?>
     <script>
         const loginForm = $(".loginForm");
         const signupForm = $(".signupForm");
         loginForm.on('submit', function(e) {
             e.preventDefault();
             $.post("/login.php", $(this).serialize(),
                 function(data) {
                     var data = JSON.parse(data);
                     if (data.status == 200) {
                         noty("success", data.message, 2000);
                         window.location.href = document.referrer;
                         return;
                     }
                     noty("danger", data.message, 4000);
                     return;

                 });
         });

         signupForm.on('submit', function(e) {
             e.preventDefault();
             $.post("/signup.php", $(this).serialize(),
                 function(data) {
                     var data = JSON.parse(data);
                     if (data.status == 200) {
                         noty("success", data.message, 2000);
                         window.location.href = document.referrer;
                         return;
                     }
                     noty("danger", data.message, 4000);
                     return;

                 });
         });
     </script>

 <?php }
    if (isset($_SESSION['auth'])) { ?>

     <script>
         window.addEventListener('beforeunload', function(e) {
             e.preventDefault();
             updateOffline();
             return;
         });

         function updateOffline() {
             var formData = new FormData();
             formData.append('logout', 'logout');
             $.ajax({
                 url: "/status.php",
                 method: "POST",
                 dataType: "JSON",
                 data: formData,
                 cache: false,
                 contentType: false,
                 processData: false,
                 success: function(data) {
                     return;
                 }
             });
         }

         //  working on notification
         var inboxNotice = $("#inboxNotice");
         var groupNotice = $("#groupNotice");

         $(inboxNotice).find('a').on('click', function(e) {
             //  e.preventDefault();
             if (/chats/.test(window.location.href)) {
                 e.preventDefault();
             }
             updateNow("inbox", '');
         });
         $(groupNotice).find('a').on('click', function(e) {
             //  e.preventDefault();
             updateNow("", 'group');
         });

         function updateNow(inbox = "", group = "") {
             var formData = new FormData();
             formData.append('inbox', inbox);
             formData.append('group', group);
             $.ajax({
                 url: '/clear_notice.php',
                 method: 'POST',
                 data: formData,
                 dataType: 'JSON',
                 contentType: false,
                 cache: false,
                 processData: false,
                 error: function(xhr, status) {
                     alert(status);
                     return;
                 },
                 success: function(data) {
                     if (data.status == 200) {
                         if (data.inbox) {
                             $('#inboxNotice > a > .badge').fadeOut('slow');
                             $('#inboxNotice > a > .badge').html('');
                             $('#inboxNotice > ul').html("");
                         }
                         if (data.group) {
                             $('#groupNotice >  a > .badge').fadeOut('slow');
                             $('#groupNotice >  a > .badge').html('');
                             $('#groupNotice > ul').html("");
                         }
                         return;
                     }
                     return;
                 }
             });
         }

         function notification() {
             $.ajax({
                 url: '/notification.php',
                 method: 'POST',
                 dataType: 'JSON',
                 contentType: false,
                 cache: false,
                 processData: false,
                 error: function(xhr, status) {
                     //  alert(status);
                     return;
                 },
                 success: function(data) {
                     if (data.status == 200) {
                         if (data.inbox.length > 0) {
                             $('#inboxNotice > a > .badge').show();
                             $('#inboxNotice > a > .badge').html(data.inbox.length);
                             var el = "";
                             $(data.inbox).each(function(index, element) {
                                 el += `<li class="list-group-item">
                             <button type="button" 
                             class="btn btn-primary btn-rounded btn-sm p-1 chat-j" 
                             title="New Message from ${element.name}" data-user="${element.user_id}">
                                        ${element.name.substr(0, element.name.indexOf(' '))}
                                     </button>
                                 </li>`;
                             });
                             $('#inboxNotice > ul').html(el);
                             setTimeout(checkChatj, 1000 * 2);
                         } else {
                             $('#inboxNotice > a > .badge').fadeOut('slow');
                             $('#inboxNotice > a > .badge').html('');
                         }
                         if (data.group.length > 0) {
                             $('#groupNotice >  a > .badge').show();
                             $('#groupNotice >  a > .badge').html(data.group.length);
                             var el = "";
                             $(data.group).each(function(index, element) {
                                 el += `<li class="list-group-item">
                             <a href="/group/${element.slug}" title="New Message from ${element.name}">
                                         ${element.name}
                                     </a>
                                 </li>`;
                             });
                             $('#groupNotice > ul').html(el);
                         } else {
                             $('#groupNotice >  a > .badge').fadeOut('slow');
                             $('#groupNotice >  a > .badge').html('');
                         }
                         return;
                     }
                     return;
                 }
             });
         }
         setTimeout(notification, 100);
         setInterval(notification, 1000 * 60);

         //  $('#inboxNotice > ul').click(function() {
         //      $('#inboxNotice > ul').css('display', 'none');
         //      $('#groupNotice > ul').css('display', 'none');
         //      updateNow('inbox');
         //  });
         $('#inboxNotice').hover(function() {
             $('#inboxNotice > ul').css('display', 'block');
             $('#groupNotice > ul').css('display', 'none');
         });
         $('#groupNotice').hover(function() {
             $('#groupNotice > ul').css('display', 'block');
             $('#inboxNotice > ul').css('display', 'none');
         });

         $(document).click(function(e) {
             if (!$(e.target).closest("#inboxNotice").length) {
                 $('#inboxNotice > ul').css('display', 'none');

             }
             if (!$(e.target).closest("#groupNotice").length) {
                 $('#groupNotice > ul').css('display', 'none');
             }
             return;
         });

         function checkChatj() {
             var chatj = $('.chat-j');
             chatj.on('click', function(e) {
                 $("#receiver").val($(this).data().user);
                 loadMessages($(this).data().user);
             });
         }

         var searchForm = $("#searchForm");
         var searchBox = $("#search");
         $(searchBox).on('keyup', validate);
         $(searchBox).on('keydown', validate);
         var errors = [];
         var Errors = [];

         function validate() {
             var el = $(this);
             if (el.val() < 1) {
                 el.removeClass('is-invalid');
                 el.removeClass('is-valid');
                 return;
             }
             //  ^((?!,$auth,).)*$
             if (!/^((?=[\w-./,'"\s\t]).)*$/.test($(this).val())) {
                 //  if (!/^[\w@#-,\s.]*$/.test($(this).val())) {
                 $(this).addClass('is-invalid');
                 el.val(el.val().replace(/[^\w-./,'"\s\t]/, ''));
                 errors.push(1);
                 if (errors.length > 4 && Errors.length < 5) {
                     noty('danger', 'Stop using odd characters, this may harm your device', 3000);
                     errors = [];
                     Errors.push(1)
                 }
                 if (Errors.length > 4) {
                     window.location.reload();
                 }
                 console.log(errors);
                 console.log(Errors);

                 return
             } else {
                 el.removeClass('is-invalid');
                 return el.addClass('is-valid');
             }
         }
         var searchUsers = $('#search-users');
         var searchedUsers = $('#searched-users')
         $(searchForm).on('submit', function(e) {
             e.preventDefault();
             var el = $(searchBox).val();
             if (el < 1) {
                 return noty('danger', 'Nothing to search', 500);
             }
             $.ajax({
                 url: '/search.php',
                 method: 'POST',
                 data: new FormData(this),
                 dataType: 'JSON',
                 contentType: false,
                 cache: false,
                 processData: false,
                 error: function(xhr, status) {
                     alert(status);
                     return;
                 },
                 success: function(data) {
                     if (data.status == 200) {
                         if (data.user && data.users.length > 0) {
                             $(searchedUsers).html('');
                             var us = "";
                             $(data.users).each(function(i, user) {
                                 us += `
                        <div class=" col-8 mb-2">
                            ${user.name}
                        </div>
                        <div class="col-2 mb-2">
                        ${user.status == 1 ?'<span class="badge badge-success">online</span>'
                        :'<span class="badge badge-warning">offline</span>'
                        }
                        </div>
                        <div class="col-2 mb-2">
                            <button type="button" 
                            class="btn btn-primary btn-rounded btn-sm p-1 chat-j" 
                            title="chat with ${user.name}" data-user="${user.id}">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                            </button>
                        </div>
                            `
                             });
                             $(searchedUsers).append(us);
                             $(searchUsers).css('display', 'flex');
                             setTimeout(checkChatj, 1000);

                         }
                         if (data.group) {

                         }
                         return;
                     }
                     return noty('danger', data.message, 1000);
                 }
             });
         })
         $('#clear-search-users').on('dblclick', function() {
             $(searchUsers).css('display', 'none');
             //  return alert('done');
         })
     </script>

 <?php } ?>
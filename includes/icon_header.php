  <nav class="icon-nav">
      <ul class="list-inline px-3">
          <li class="list-inline-item"><a href="/" class="text-white">home</a></li>
          <li class="list-inline-item"><a href="/chats.php" class="text-white">Inbox</a></li>
          <li class="list-inline-item"><a href="/groups.php" class="text-white">Groups</a></li>
          <li class="list-inline-item">
              <form method="get" id="searchForm">
                  <div class="input-group">
                      <input class="form-control" type="search" name="u" placeholder="Search for group" id="search" <div class="input-group-append">
                      <button type="submit"><i class="fa fa-search-plus" aria-hidden="true"></i> </button>
                  </div>
                  </div>
              </form>
          </li>
          <li class="list-inline-item">
              <a class=" text-white" href="" data-toggle="modal" data-target="#newgroup">New Group</a>
          </li>
          <li class="list-inline-item">
              <a href="" id="logout" class=" text-white">Logout<?php echo $_SESSION['auth']['id']; ?> </a>
          </li>
          <li class="list-inline-item ml-auto">
              <span class="notification" id="inboxNotice">
                  <a href="/chats.php">
                      <i class="notification-circle badge-danger badge"></i>
                      <i class="fa fa-envelope text-white" aria-hidden="true"></i>
                  </a>
                  <ul class="list-group list-group-flush">
                  </ul>
              </span>
          </li>
          <li class="list-inline-item ml-auto">
              <span class="notification" id="groupNotice">
                  <a href="#!">
                      <i class="notification-circle badge-danger badge"></i>
                      <i class="fa fa-bell text-white" aria-hidden="true"></i>
                  </a>
                  <ul class="list-group list-group-flush">

                  </ul>
              </span>
          </li>
          <li class="list-inline-item">
              <a href="" class=" text-white">

                  <?php echo $_SESSION['auth']['name'] ?? ""; ?>
              </a>
          </li>
      </ul>
  </nav>
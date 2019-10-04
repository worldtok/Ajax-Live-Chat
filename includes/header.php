  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top mb-5">
      <a class="navbar-brand">Brand</a>
      <button class="navbar-toggler" data-target="#my-nav" data-toggle="collapse" aria-controls="my-nav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
      </button>
      <div id="my-nav" class="collapse navbar-collapse">
          <ul class="navbar-nav ml-auto">
              <li class="nav-item active">
                  <a class="nav-link" href="#">Item 1 <span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
                  <button type="submit" class="btn btn-outline-primary btn-sm nav-link" data-toggle="modal" data-target="#newgroup">
                      Login/Signup
                  </button> </li>
              <li class="nav-item">
                  <?php if (!isset($_SESSION['auth'])) { ?>
                      <button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#login">
                          Login/Signup
                      </button>
                  <?php } else { ?>
                      <a href="#!" id="logout" class="nav-link">logout</a>
                  <?php } ?>
              </li>
          </ul>
      </div>
  </nav>
  <div class="mb-5"></div>
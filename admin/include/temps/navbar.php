<nav class="navbar navbar-inverse">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="nav navbar-nav menues">
        <li><a href="dashboard.php"><i class='fas fa-home fa-2x'></i></a></li>
        <li><a href="categories.php?do=Manage"><i class='fas fa-project-diagram fa-2x'></i></a></li>
        <li><a href="items.php?do=Manage"><i class='fas fa-tags fa-2x'></i></a></li>
        <li><a href="members.php?do=manage"><i class='fas fa-users fa-2x'></i></a></li>
        <li><a href="comments.php?do=Manage"><i class='fas fa-comments fa-2x'></i></a></li>
        <li><a href="../index.php"><i class='fas fa-store fa-2x'></i></a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            <i class='fas fa-cog fa-2x'></i>
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu">
            <li><a href="members.php?do=Edit&userid=<?php echo $_SESSION['ID'] ?>">Edit Profile</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
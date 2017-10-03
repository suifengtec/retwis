<nav id="bs-navbar" class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
        <li> <a href="index.php">home</a> </li>
        <li><a href="timeline.php">timeline</a> </li>
        <?php if(isLoggedIn()) {?>
        <li> <a href="logout.php">logout</a> </li>
		<?php }?>
      </ul>
</nav>
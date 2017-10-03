<?php

include("retwis.php");
include("header.php");
?>

<div class="col-md-12">
	<h2>Timeline</h2>

	<p>Latest registered users (an example of sorted sets)</p>
	<?php

	showLastUsers();

	?>
 </div>

<div class="col-md-12">
	<p>Latest 50 messages from users aroud the world!</p>
</div>
<?php
	showUserPosts(-1,0,50);
?>
 <?php include("footer.php") ?>
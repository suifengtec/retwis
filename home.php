<?php
include("retwis.php");
if (!isLoggedIn()) {
    header("Location: index.php");
    exit;
}
include("header.php");
$r = redisLink();
?>
<div id="postform" class="container">
	<form action="post.php"  method="POST">
			<div class="row">
				<div class="col-md-12">
					<p> Hi , <span class="username"><?php echo utf8entities($User['username']) ?> </span>, Say something? </p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3">
					<ul class="list-group">
				      <li class="list-group-item">
				        followers 
				        <span class="badge"><?php echo $r->zcard("followers:".$User['id'])?></span>
				      </li>
				      <li class="list-group-item">
				         following
				         <span class="badge"><?php echo $r->zcard("following:".$User['id'])?></span>
				      </li>
				    </ul>
				</div>
				<div class="col-md-9">
				<textarea   name="status"  class="form-control saysomething" placeholder="Say something..."></textarea>
					<br>
						<button type="submit" class="btn  btn-primary btn-sm pull-right" name="doit">Post</button>
				</div>

			</div>
		
	</form>
</div>
<?php

$start = gt("start") === false ? 0 : intval(gt("start"));
?>
<div class="row">
<div class="col-md-12 col-xs-12">
<?php
showUserPostsWithPagination(false,$User['id'],$start,10);
?>
</div>
</div>
<?php

include("footer.php")

?>
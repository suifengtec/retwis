<?php

/*

显示有多少个用户
显示有多少条消息

 */
include("retwis.php");


if (!$userId = isLoggedIn()) {
    header("Location: index.php");
    exit;
}
 
/*

global $User;
var_dump($User);
array(2) { ["id"]=> string(1) "1" ["username"]=> string(4) "ssff" }
 */


if('1'!=$userId){
    header("Location: home.php");
    exit;
}

include("header.php");

$r = redisLink();

$users = $r->zrevrange("users_by_time",0,999999);

$user_count = count( $users );

?>
<h2>Info</h2>
<ul class="list-group">
  <li class="list-group-item">
    Messages
     <span class="badge"><?php echo getPosts(0,9999999) ?></span>
  </li>
  <li class="list-group-item">
   Users
    <span class="badge"><?php echo $user_count ?></span>
  </li>


</ul>

<h2>User List</h2>
<div class="col-md-12">
<?php

showLastUsers(999999, 1);






?>
	
</div>


 <?php include("footer.php") ?>
<?php
/**
 * @Author: suifengtec
 * @Date:   2017-04-24 12:23:42
 * @Last Modified by:   suifengtec
 * @Last Modified time: 2017-04-24 13:40:17
 */

include("retwis.php");
if (!$userID=isLoggedIn()) {
    header("Location: index.php");
    exit;
}
include("header.php");
$r = redisLink();

$posts = $r->smembers("user:like:posts:$userID");



foreach($posts as $id){

	showPost($id, $is_moderation=false);
}

?>




<?php
include("footer.php")
?>
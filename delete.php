<?php
/**
 * @Author: suifengtec
 * @Date:   2017-04-24 11:22:51
 * @Last Modified by:   suifengtec
 * @Last Modified time: 2017-04-24 12:41:01
 */
include("retwis.php");

if (!$userId=isLoggedIn()) {
    header("Location: index.php");
    exit;
}
if('1'!=$userId){
    header("Location: index.php");
    exit;
}



$id = intval(g("post-id"));


$r = redisLink();

$r->del("post:$id");

/*$keys = $r->hkeys("post:$id");
if(!empty($keys)){
	foreach($keys as $key){
		$r->hdel("post:$id",$key);
	}
	
}*/


/*$post = $r->hgetall("post:$id");

*/
header("Location: moderation.php");
<?php
/**
 * @Author: suifengtec
 * @Date:   2017-04-24 11:01:17
 * @Last Modified by:   suifengtec
 * @Last Modified time: 2017-04-24 11:37:32
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

/*

$post = $r->hgetall("post:$id");

$userid = $post['user_id'];
$username = $r->hget("user:$userid","username");

$post['body']


从数据看转换为可显示的内容
utf8entities($post['body'])



 */



$content = g("post-content");
$id = intval(g("post-id"));

$content = str_replace("\n"," ",$content);


/*die(var_dump($content));
*/

$r = redisLink();

$r->hset("post:$id", 'body', $content);


$ref = g('ref');


header("Location: $ref#post-list-$id");

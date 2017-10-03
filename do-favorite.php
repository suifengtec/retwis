<?php
/**
 * @Author: suifengtec
 * @Date:   2017-04-24 13:00:16
 * @Last Modified by:   suifengtec
 * @Last Modified time: 2017-04-24 13:37:11
 */

include("retwis.php");

if (!$userId=isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$postID = intval(g("post-id"));

var_dump($postID);

$isLike = intval(gt('like'));

$ret = doLikeOrUnLike(  $postID, 0, $isLike );



$ref = g('ref');

$action = $isLike?'like':'unlike';
/*
?$action=$postID
 */
header("Location: $ref#post-list-$postID");
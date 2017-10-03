<?php
/**
 * @Author: suifengtec
 * @Date:   2017-04-24 10:33:45
 * @Last Modified by:   suifengtec
 * @Last Modified time: 2017-04-24 11:38:08
 */
/*
弃用
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


?>


<div class="col-md-12">
<h2>Moderation</h2>
<?php

$start = gt("start") === false ? 0 : intval(gt("start"));
$count = 999999;
showAllPostsWithPagination($start,$count);

?>
</div>


 <?php include("footer.php") ?>
<?php
include("retwis.php");
include("header.php");

$r = redisLink();
if (!gt("u") || !($userid = $r->hget("users",gt("u")))) {
    header("Location: index.php");
    exit(1);
}
?>
<h2 class="username">

<?php echo utf8entities(gt("u")) ?>
<?php

if (isLoggedIn() && $User['id'] != $userid) {
    $isfollowing = $r->zscore("following:".$User['id'],$userid);
    if (!$isfollowing) {

        $classes = 'btn btn-success btn-sm';
        $label = 'Follow this user';
        $v = '1';
    } else {

      $classes = 'btn btn-danger  btn-sm';
      $label = 'Stop following';
      $v = '0';

    }

    ?>
	<a  class="<?php echo $classes ?>" href="follow.php?uid=<?php echo $userid ?>&f=<?php echo $v; ?>" ><?php echo $label ?></a>
    <?php
}

?>

</h2>

<?php
$start = gt("start") === false ? 0 : intval(gt("start"));

showUserPostsWithPagination(gt("u"),$userid,$start,10);

include("footer.php");

?>
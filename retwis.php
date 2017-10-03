<?php

/*

使用 string 存储 post 的ID , $r->incr("next_post_id");

使用 hash, 以 post:$postID 为 key 来存储 post 的项目.

删除 post 时, 清除 hash 中 key 为 post:$postID 的项目, 并调整 list 的连续性?

=============



Features:
[v] post;
[v] follow post & user;
[v] edit post;
[v] delete post;
[v] my favorite;
[v] overview;

Coming:

[x] 实时推送;
[x] 插入图片;
[x] 表情支持;
[x] avatar;
[x] 插件化;
[x] 模板系统;



 */


require 'Predis/Autoloader.php';
Predis\Autoloader::register();

function getrand() {
/*    $fd = fopen("/dev/urandom","r");
    $data = fread($fd,16);
    fclose($fd);*/

    $random = random_int(time()-99999, time());
    return md5($random);
}

function isLoggedIn() {
    global $User, $_COOKIE;

    if (isset($User)) return $User['id'];

    if (isset($_COOKIE['auth'])) {
        $r = redisLink();
        $authcookie = $_COOKIE['auth'];
        if ($userid = $r->hget("auths",$authcookie)) {
            if ($r->hget("user:$userid","auth") != $authcookie) return false;
            loadUserInfo($userid);
            return $userid;
        }
    }
    return false;
}

function loadUserInfo($userid) {
    global $User;

    $r = redisLink();
    $User['id'] = $userid;
    $User['username'] = $r->hget("user:$userid","username");
    return true;
}

function redisLink() {
    static $r = false;

    if ($r) return $r;
    $r = new Predis\Client();
    return $r;
}

# Access to GET/POST/COOKIE parameters the easy way
function g($param) {
    global $_GET, $_POST, $_COOKIE;

    if (isset($_COOKIE[$param])) return $_COOKIE[$param];
    if (isset($_POST[$param])) return $_POST[$param];
    if (isset($_GET[$param])) return $_GET[$param];
    return false;
}

function gt($param) {
    $val = g($param);
    if ($val === false) return false;
    return trim($val);
}

function utf8entities($s) {
    return htmlentities($s,ENT_COMPAT,'UTF-8');
}

function goback($msg) {
    include("header.php");
    echo('<div id ="error">'.utf8entities($msg).'<br>');
    echo('<a href="javascript:history.back()">Please return back and try again</a></div>');
    include("footer.php");
    exit;
}

function strElapsed($t ) {
    $d = time()-$t;
    if ($d < 60) return "$d seconds";
    if ($d < 3600) {
        $m = (int)($d/60);
        return "$m minute".($m > 1 ? "s" : "");
    }
    if ($d < 3600*24) {
        $h = (int)($d/3600);
        return "$h hour".($h > 1 ? "s" : "");
    }
    $d = (int)($d/(3600*24));
    return "$d day".($d > 1 ? "s" : "");
}


function doLikeOrUnLike(  $postID , $userID=0 , $isLike=false ){

    if(!$postID){
        return false;
    }

    $userID = !$userID?isLoggedIn():$userID;

    /*return $isLike;*/

    if(!$userID){
        return false;
    }

    $r = redisLink();

  

    if($isLike){

        if(!$r->sismember("post:follower:$postID",$userID)){

            $r->sadd("post:follower:$postID", $userID);
        }


        if($r->sismember("user:like:posts:$userID", $postID)){

            return true;
        }else{
            $r->sadd("user:like:posts:$userID", $postID);
          
           /*
           点赞的人加1
            */
           $r->hincrby("post:$postID",'followerCounter', 1);

            return true;
        }

    }else{

        if($r->sismember("post:follower:$postID",$userID)){

            $r->srem("post:follower:$postID", $userID);
        }


        if($r->get("user:likeCounter:$userID")>=1){
            $uLikeCounter = $r->decr("user:likeCounter:$userID");
        }
        
        if($r->hget("post:$postID",'followerCounter')>=1){
         $pfollowerCounter = $r->hincrby("post:$postID",'followerCounter', -1);
        }


        if(!$r->sismember("user:like:posts:$userID", $postID)){
            return true;
        }else{

            $r->srem("user:like:posts:$userID", $postID);
            return true;
        }

    }

    
    return 1;

}





function showPost($id, $is_moderation=false) {

    $r = redisLink();
    $post = $r->hgetall("post:$id");
    if (empty($post)) return false;
   

    $userid = $post['user_id'];
    $username = $r->hget("user:$userid","username");
    $elapsed = strElapsed($post['time']);


/*
  <div class="col-md-12 alert alert-info">
        <a class="username" href="profile.php?u=<?php echo urlencode($username) ?>"><?php echo utf8entities($username) ?></a>   <?php echo utf8entities($post['body']) ?>
        <br>
        <i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo $elapsed ?> ago

    </div>
 */



    global $User;

    $is_admin = !empty($User['id'])&&'1'==$User['id']?true:false;

    $user_id = isLoggedIn();




?>


<div id="post-list-<?php echo $id; ?>" <?php if(!$is_moderation){ ?> class="col-md-12" <?php } ?>>
    <div class="panel panel-default">
      <div class="panel-heading"><a class="username" href="profile.php?u=<?php echo urlencode($username) ?>"><?php echo utf8entities($username) ?></a>  <i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo $elapsed ?> ago    

                
                <?php if($user_id): ?>

                    <?php if(!$r->sismember("post:follower:$id",$user_id)): ?> 
                        <span class="post-favorite" data-postid="<?php echo $id ?>" data-ref="<?php echo $_SERVER['PHP_SELF'] ; ?>" title=" you may like this!"> <i class="fa fa-thumbs-up" aria-hidden="true"></i> Like</span>
                    <?php else: ?>
                        <span class="post-unfavorite" data-postid="<?php echo $id ?>" data-ref="<?php echo $_SERVER['PHP_SELF'] ; ?>"  title="you liked!"> <i class="fa fa-thumbs-down" aria-hidden="true"></i> Like</span>
                    <?php endif; ?>

                  <?php

                      if( $followerCounter = $r->scard("post:follower:$id")){

                        echo  $followerCounter.' followers.';
                      }


/*                      if(!empty($post['followerCounter'])){
                         echo  $post['followerCounter'].' followers.';
                      }*/
                       

                   ?>
                <?php endif;?>


            <?php  if( $is_admin): ?>
                

                
                <div class="post-right pull-right" style="display:inline-block;"> 
                
                 <span class="post-edit" data-toggle="modal" data-target="#modal-post-edit-<?php echo $id; ?>"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> 编辑</span>  

                
                <span class="post-delete" data-postid="<?php echo $id; ?>"> <i class="fa fa-trash" aria-hidden="true"></i>删除</span>  
                </div>
            <?php  endif;?>

      </div>
      <div class="panel-body">
        <?php echo utf8entities($post['body']) ?>

      </div>
    </div>
</div>
<? //if($is_moderation&&$is_admin): ?>
<!-- Modal -->
<div class="modal fade" id="modal-post-edit-<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="modal-post-edit-<?php echo $id; ?>">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modal-post-edit-label-<?php echo $id; ?>"><?php echo utf8entities($username) ?> Say: </h4>
      </div>
        <form action="modify.php" method="POST">
          <div class="modal-body">
               <textarea class="form-control" name="post-content" id="post-content-<?php echo $id; ?>" cols="30" rows="10" style="width:100%"> <?php echo utf8entities($post['body']) ?></textarea>
               <input type="hidden" name="post-id" value="<?php echo $id; ?>">
               <input type="hidden" name="ref" value="<?php echo $_SERVER['PHP_SELF'] ; ?>">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
    </div>
  </div>
</div>
 <?php //endif;?>
<?php

    return true;
}


function getPosts( $start, $count, $userID=0 ){

 
    $key = !$userID ? "timeline" : "posts:$userID";
    $r = redisLink();
    $posts = $r->lrange($key,$start,$start+$count);
     $c = 0;

     foreach($posts as $id ) {
        if (!empty($r->hgetall("post:$id"))) $c++;
        if ($c == $count) break;
    }
    return count($posts) ;




}

/*

ID 是使用 list 存储的, list 是连续的;

删除 hash 时, hash 为空, list 不变(修改它的效率较低);



 */
function showUserPosts($userid,$start,$count, $is_moderation=false ) {
    $r = redisLink();
    $key = ($userid == -1) ? "timeline" : "posts:$userid";
    $posts = $r->lrange($key,$start,$start+$count);
    $c = 0;

    foreach($posts as  $id ) {

         if (showPost( $id, $is_moderation )) $c++;
         if(!$c){
            continue;
         }
        if ($c == $count) break;

    }

    return count($posts) == $count+1;
}

/*
显示带分页的全部消息
 */
function showAllPostsWithPagination($start,$count) {

    global $_SERVER;
    $thispage = $_SERVER['PHP_SELF'];

    $navlink = "";
    $next = $start+10;
    $prev = $start-10;
    $nextlink = $prevlink = false;
    if ($prev < 0) $prev = 0;

    $u =  "";
    if (showUserPosts(-1,$start,$count, true))
        $nextlink = "<a href=\"$thispage?start=$next".$u."\">Older posts &raquo;</a>";
    if ($start > 0) {
        $prevlink = "<a href=\"$thispage?start=$prev".$u."\">&laquo; Newer posts</a>".($nextlink ? " | " : "");
    }
    if ($nextlink || $prevlink)
        echo("<div class=\"rightlink\">$prevlink $nextlink</div>");


}





function showUserPostsWithPagination($username,$userid,$start,$count) {
    global $_SERVER;
    $thispage = $_SERVER['PHP_SELF'];

    $navlink = "";
    $next = $start+10;
    $prev = $start-10;
    $nextlink = $prevlink = false;
    if ($prev < 0) $prev = 0;

    $u = $username ? "&u=".urlencode($username) : "";

    if (showUserPosts($userid,$start,$count)){
        $nextlink = "<a href=\"$thispage?start=$next".$u."\">Older posts &raquo;</a>";
    }

    if ($start > 0) {
        $prevlink = "<a href=\"$thispage?start=$prev".$u."\">&laquo; Newer posts</a>".($nextlink ? " | " : "");
    }
    if ($nextlink || $prevlink)
        echo("<div class=\"rightlink\">$prevlink $nextlink</div>");
}

function showLastUsers( $count = 99,$forAdmin=false ) {
    $r = redisLink();
    $users = $r->zrevrange("users_by_time",0,$count);
    foreach($users as $u) {


        $userID=1;
        ?>
        <?php if(!$forAdmin): ?>
        <div class="col-md-2"><a target="_blank" class="username" href="profile.php?u=<?php echo urlencode($u)?>"><?php echo utf8entities($u) ?></a></div> 
        <?php else: ?>

          <li class="list-group-item">
           <a target="_blank" class="username" href="profile.php?u=<?php echo urlencode($u)?>"><?php echo utf8entities($u) ?></a> msg count:
             <span class="badge"><?php echo getPosts( $userID,9999999) ?></span>
         
          </li>

            <div class="col-md-12"><a target="_blank" class="username" href="profile.php?u=<?php echo urlencode($u)?>"><?php echo utf8entities($u) ?></a></div>  

          <ul class="list-group">
                <li class="list-group-item">
                msg count:
                 <span class="badge"><?php echo getPosts( $userID,9999999) ?></span>
                </li>
                <li class="list-group-item">
                followers:
                 <span class="badge"><?php echo getPosts( $userID,9999999) ?></span> 
                </li> 
                <li class="list-group-item">
                following posts:
                 <span class="badge"><?php $userID=1; echo $r->scard("user:like:posts:$userID"); ?></span> 
                </li>            
          </ul>

        <?php endif;?>

    <?php
     }

 
}

?>

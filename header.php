<?php

include_once('config.php');

?><!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv=X-UA-Compatible content="IE=edge">
		<meta name="renderer" content="webkit">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title><?php echo $appName; ?> - a simple Twitter clone using  Redis</title>
		<link href=https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css rel=stylesheet>
		<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
		<link href="css/style.css" rel="stylesheet" type="text/css">
	</head>
<body>
	<header id="top" class="navbar navbar-static-top bs-docs-nav">
		<div class="container">
			<div class="navbar-header">
			  <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#bs-navbar" aria-controls="bs-navbar" aria-expanded="false">
			    <span class="sr-only">Toggle navigation</span>
			    <span class="icon-bar"></span>
			    <span class="icon-bar"></span>
			    <span class="icon-bar"></span>
			  </button>
			  <a href="index.php" class="navbar-brand"><?php echo $appName; ?></a>
			</div>
			<nav id="bs-navbar" class="collapse navbar-collapse">
			      <ul class="nav navbar-nav">
			        <li> <a href="index.php">Home</a> </li>
			        <li><a href="timeline.php">Timeline</a> </li>
			      </ul>
  			        <?php if($userId = isLoggedIn()) {?>
  			        	<ul class="nav navbar-nav navbar-right">
  			        		<li> <a href="my-favorite.php">My Favorite</a> </li>
							<?php if(1==$userId) {?>
								<li> <a href="admin.php">Admin</a> </li>
							
							<?php } ?>
							
							<li> <a href="logout.php">Logout</a> </li>
			          </ul>
					<?php }?>
			</nav>
		</div>
	</header>
	<div class="bs-docs-header" id="content" tabindex="-1">
		<div class="container">
		<h1><?php echo $appName; ?></h1>
		<p><?php echo $appName; ?> , Powered by PHP & Redis , and Bootstrap. </p>
		</div>
	</div>
	<div id="page" class="container">
		<div class="row">

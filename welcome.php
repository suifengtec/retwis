<?php

/*
 id="welcomebox"
 */

?>
<div class="col-md-12">

	<div class="col-md-6">
	<p>Hello! Retwis is a very simple clone of <a href="http://twitter.com">Twitter</a>, as a demo for the <a href="https://redis.io">Redis</a> key-value database. Key points:
	</p>
	<ul class="list-group">
		<li class="list-group-item">Redis is a key-value DB, and it is <b>the only DB used</b> by this application, no MySQL or alike at all.</li>
		<li class="list-group-item">This application can scale horizontally since there is no point where the whole dataset is needed at the same point. With consistent hashing (not implemented in the demo to make it simpler) different keys can be stored in different servers.</li>
		<li class="list-group-item">The source code of this application, and a tutorial explaining its design, is available <a href="#">here</a>.
		<li class="list-group-item">PHP and the Redis server communicate using the PHP Redis library client written by Ludovico Mangocavallo and included inside the Redis tar.gz distribution.
	</ul>
	</div>


<div class="col-md-6">

	<ul  class="nav nav-pills">
		<li class="active"><a href="#signincontent" data-toggle="tab">Sign in</a></li>
		<li ><a  href="#signupcontent" data-toggle="tab">Sign up</a></li>
		
	</ul>
	<div class="tab-content clearfix">
		<div class="tab-pane" id="signupcontent">

			<h2>Register Now!</h2>
			<b>Want to try <span style="color:green">RePHPis</span> ? Create an account now!</b>
			<form method="POST" action="register.php">
				<div class="form-group">
					<label for="username">Username</label>
					<input type="text" class="form-control" id="username" name="username" placeholder="username">
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" class="form-control" id="password" name="password" placeholder="Password">
				</div>
				<div class="form-group">
					<label for="password2">Password</label>
					<input type="password" class="form-control" id="password2" name="password2" placeholder="password2">
				</div>
				<button type="submit" name="doit" class="btn btn-success"  class="btn btn-default">Sign up</button>
			</form>
		</div>

		<div class="tab-pane  active" id="signincontent">

				<h2>Already registered? Login here!</h2>
				<form method="POST" action="login.php">
					<div class="form-group">
						<label for="username">Username</label>
						<input type="text" class="form-control" id="username" name="username" placeholder="username">
					</div>
					<div class="form-group">
						<label for="password">Password</label>
						<input type="password" class="form-control" id="password" name="password" placeholder="Password">
					</div>
					<button type="submit" name="doit" class="btn btn-success"  class="btn btn-default">Sign in</button>
				</form>
		</div>
		
	</div>







	</div>


</div>

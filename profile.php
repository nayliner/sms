<?php
	include_once("header.php");
	include_once("left_menu.php");
	$sql = "select * from users where id='".$_SESSION['user_id']."'";
	$res = mysqli_query($link,$sql);
	if(mysqli_num_rows($res)){
		$row = mysqli_fetch_assoc($res);
	}else
		$row = array();
	$bool = false;
?>
<div class="main-panel">
	<?php include_once('navbar.php');?>
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="header">
							<h4 class="title">
								Update Profile
								<input type="button" class="btn btn-primary" value="Back" style="float:right !important" onclick="window.history.go(-1); return false;" />
							</h4>
							<p class="category">You can update your profile here.</p>
						</div>
						<div class="content table-responsive">
							<form action="server.php" data-parsley-validate novalidate enctype="multipart/form-data" method="post">
							<div class="form-group">
								<label>First Name</label>
								<input type="text" name="first_name" parsley-trigger="change" required placeholder="Enter first name..." class="form-control" value="<?php echo $row['first_name']?>">
							</div>
							<div class="form-group">
								<label>Last Name</label>
								<input type="text" name="last_name" parsley-trigger="change" required placeholder="Enter last name..." class="form-control" value="<?php echo $row['last_name']?>">
							</div>
							<div class="form-group">
								<label>Email</label>
								<input type="email" name="email" parsley-trigger="change" required placeholder="Enter phone number..." class="form-control" value="<?php echo $row['email']?>">
							</div>
							<div class="form-group">
								<label>Password</label><span class="showPass" style="margin-left:20px; cursor:pointer"><i class="fa fa-eye" onclick="showPassword()"></i></span>
								<input type="password" name="password" parsley-trigger="change" required placeholder="Enter password..." class="form-control" value="<?php echo decodePassword($row['password']);?>">
							</div>
							<div class="form-group">
								<label>Retype Password</label>
								<input type="password" name="retype_password" parsley-trigger="change" required placeholder="Retype password..." class="form-control">
							</div>
							<div class="form-group">
								<label>Business Name</label>
								<input type="text" name="business_name" parsley-trigger="change" required placeholder="Enter business name..." class="form-control" value="<?php echo $row['business_name']?>">
							</div>
							<div class="form-group text-right m-b-0">
								<button class="btn btn-primary waves-effect waves-light" type="submit">Update</button>
								<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
								<input type="hidden" name="cmd" value="update_profile" />
								<input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']?>" />
							</div>
						</form>
						</div>
					</div>
					
					<div class="card">
						<div class="header">
							<h4 class="title">
								Facebook Credentials
							</h4>
							<p class="category">Please setup your Facebook App Credentials.</p>
						</div>
						<div class="content table-responsive">
							<form action="api.php" data-parsley-validate novalidate enctype="multipart/form-data" method="post">
							<div class="form-group">
								<label>App ID</label>
								<input type="text" name="app_id" parsley-trigger="change" required placeholder="Enter App ID..." class="form-control" value="<?php echo $row['app_id']?>">
							</div>
							<div class="form-group">
								<label>App Secret</label>
								<input type="text" name="app_secret" parsley-trigger="change" required placeholder="Enter App Secret..." class="form-control" value="<?php echo $row['app_secret']?>">
							</div>
							<!--
							<div class="form-group">
								<label>User Access Token</label>
								<input type="text" name="fb_user_access_token" parsley-trigger="change" required placeholder="Enter user access token..." class="form-control" value="<?php echo $row['access_token']?>">
							</div>
							-->
							<div class="form-group text-right m-b-0">
								<button class="btn btn-primary waves-effect waves-light" type="submit">Update</button>
								<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
								<input type="hidden" name="cmd" value="facebook_credentials" />
								<input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']?>" />
							</div>
						</form>
						</div>
					</div>
					
					<div class="card">
						<div class="header">
							<h4 class="title">
								Twitter Credentials
							</h4>
							<p class="category">Please setup your Twitter App Credentials.</p>
						</div>
						<div class="content table-responsive">
							<form action="server.php" data-parsley-validate novalidate enctype="multipart/form-data" method="post">
							<div class="form-group">
								<label>Access Token</label>
								<input type="text" name="tw_access_token" parsley-trigger="change" required placeholder="Enter Access Token..." class="form-control" value="<?php echo $row['tw_access_token']?>">
							</div>
							<div class="form-group">
								<label>Access Token Secret</label>
								<input type="text" name="tw_access_token_secret" parsley-trigger="change" required placeholder="Access Token Secret..." class="form-control" value="<?php echo $row['tw_access_token_secret']?>">
							</div>
                            <div class="form-group">
								<label>Consumer Key</label>
								<input type="text" name="tw_consumer_key" parsley-trigger="change" required placeholder="Consumer Key..." class="form-control" value="<?php echo $row['tw_consumer_key']?>">
							</div>
                            <div class="form-group">
								<label>Consumer Secret</label>
								<input type="text" name="tw_consumer_secret" parsley-trigger="change" required placeholder="Consumer Secret..." class="form-control" value="<?php echo $row['tw_consumer_secret']?>">
							</div>

							<div class="form-group text-right m-b-0">
								<button class="btn btn-primary waves-effect waves-light" type="submit">Update</button>
								<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
								<input type="hidden" name="cmd" value="twitter_credentials" />
								<input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']?>" />
							</div>
						</form>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
	<?php include_once("footer_info.php");?>
</div>
<?php include_once("footer.php");?>
<script type="text/javascript" src="scripts/js/parsley.min.js"></script>
<script type="text/javascript">
	function hidePassword(){
		$('input[name="password"]').attr('type','password');
		$('input[name="retype_password"]').attr('type','password');
		$('.showPass').html('<i class="fa fa-eye" onclick="showPassword()"></i>');
	}
	function showPassword(){
		$('input[name="password"]').attr('type','text');
		$('input[name="retype_password"]').attr('type','text');
		$('.showPass').html('<i class="fa fa-eye-slash" onclick="hidePassword()"></i>');
	}
	$(document).ready(function(){
		$('form').parsley();
	});
</script>
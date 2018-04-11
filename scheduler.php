<?php
	include_once("header.php");
	include_once("left_menu.php");
	if($_REQUEST['id']!=''){
		$sql = "select * from schedulers where id='".$_REQUEST['id']."'";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			$row = mysqli_fetch_assoc($res);
		}else
			$row = array();
	}
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
								SMS Scheduler
								<input type="button" class="btn btn-primary" value="Back" style="float:right !important" onclick="window.location='view_scheduler.php'" />
							</h4>
							<p class="category" style="color:#F00"><?php echo date('g:iA \o\n l jS F Y')?></p>
						</div>
						<div class="content table-responsive">
							<form action="server.php" data-parsley-validate novalidate enctype="multipart/form-data" method="post">
							<div class="form-group">
								<label>Title*</label>
								<input type="text" name="title" parsley-trigger="change" required placeholder="Enter title..." class="form-control">
							</div>
							<div class="form-group">
								<label>Date*</label>
								<input type="text" class="form-control addDatePicker" name="date" required style="z-index:9999999">
							</div>
							<div class="form-group">
								<label>Time*</label>
								<select name="time" class="form-control" parsley-trigger="change" required>
								<?php
									$time = getTimeArray();
									foreach($time as $key => $value){
										echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
									}
								?>
								</select>
							</div>
							<div class="form-group">
								<label><input name="attach_mobile_device" value="1" type="checkbox" /> Attach mobile device</label>
							</div>
							<div class="form-group">
								<label>Select Group</label>
								<select class="form-control" name="group_id" onChange="getGroupNumbers(this.value)" parsley-trigger="change" required>
									<option value="">- Select One -</option>
								<?php
									$sqlg = "select id, title from campaigns where user_id='".$_SESSION['user_id']."'";
									$resg = mysqli_query($link,$sqlg);
									if(mysqli_num_rows($resg)){
										while($rowg = mysqli_fetch_assoc($resg)){
											echo '<option '.$sel.' value="'.$rowg['id'].'">'.$rowg['title'].'</option>';
										}	
									}else{
										echo '<option value="">No group found</option>';						
									}
								?>		
								</select>
							</div>
							<div class="form-group">
								<label>Select Number</label>
								<select name="phone_number" class="form-control" id="list_group_number"></select>
							</div>
							<div class="form-group">
								<label>Message</label>
								<textarea name="message" class="form-control textCounter" parsley-trigger="change" required></textarea>
								<span class="showCounter">
									<span class="showCount"><?php echo $maxLength?></span> Characters left
								</span>
							</div>
							<div class="form-group">
								<label>Media</label>
								<input type="file" name="media">
							</div>
							<div class="form-group text-right m-b-0">
								<button class="btn btn-primary waves-effect waves-light" type="submit"> Save </button>
								<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
								<input type="hidden" name="cmd" value="save_scheduler" />
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
<script>
	function getSchedulerNumber(groupID,numberID){
		var Qry = 'cmd=get_scheduler_numbers&group_id='+groupID+'&numberID='+numberID;
		$.post('server.php',Qry,function(r){
			$('#list_group_number').html(r);
		});
	}
	function getGroupNumbers(groupID){
		var Qry = 'cmd=get_group_numbers&group_id='+groupID;
		$.post('server.php',Qry,function(r){
			$('#list_group_number').html(r);
		});
	}
	$(document).ready(function(){
		$('form').parsley();
	});
</script>
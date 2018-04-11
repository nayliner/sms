<?php
	include_once("header.php");
	include_once("left_menu.php");
?>
<style>
.delay_table tr td{
	padding:5px !important;
} 
</style>
<div class="main-panel">
	<?php include_once('navbar.php');?>
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<form method="post" enctype="multipart/form-data" action="server.php">
					<div class="col-md-12">
						<div class="card">
							<div class="header">
								<h4 class="title">
									Make New Appointment
									<input type="button" class="btn btn-default" value="Back" style="float:right !important" onclick="window.location='view_apts.php'" />
								</h4>
								<p class="category">Make new appointment from here.</p>
							</div>
							<div class="content table-responsive">
								<div class="form-group">
									<label>Title</label>
									<input type="text" name="apt_title" class="form-control" />
								</div>
								<div class="form-group">
									<label>Date/Time</label><br />
									<input type="text" name="apt_date" class="form-control addDatePicker" style="width:49%; display:inline" />
									<select name="apt_time" class="form-control" style="width:49%; display:inline">
									<?php
										$timeArray = getTimeArray();
										foreach($timeArray as $key => $value){
											echo '<option value="'.$key.'">'.$value.'</option>';
										}
					?>				
									</select>
								</div>
								<div class="form-group">
									<label>Select Group</label>
									<select name="group_id" class="form-control" onchange="getGroupNumbers(this.value)">
									<?php
										$sql = "select id,title from campaigns where user_id='".$_SESSION['user_id']."'";
										$res = mysqli_query($link,$sql);
										if(mysqli_num_rows($res)){
											while($row=mysqli_fetch_assoc($res)){
												echo '<option value="'.$row['id'].'">'.$row['title'].'</option>';	
											}
										}
									?>
									</select>
								</div>
								<div class="form-group">
									<label>Select Recipient</label>
									<select name="phone_number_id" id="phone_number_id" class="form-control">
										<option value="">- Select Group Above -</option>
									</select>
								</div>
								<div class="form-group">
									<label>Message</label>
									<span style="clear: both !important;color:#451e89;display: block;font-size: 12px;">Appointment date = %apt_date%.</span>
									<textarea name="apt_message" class="form-control"></textarea>
								</div>

		<div class="col-lg-12" style="padding:0px !important;">
			<div class="portlet">
				<div class="portlet-heading bg-custom" style="background-color:orange !important; padding:2px 5px 2px 10px !important; border-radius:5px;">
					<h5 style="color:#FFF !important; margin-bottom:10px;">
						Appointment alerts
						<a data-toggle="" href="javascript:;"><i class="fa fa-plus" title="Add More" onClick="addMoreAlertMsg()" style="color:#FFF !important; float:right !important"></i></a>
					</h5>
					<div class="portlet-widgets">
						<span class="divider"></span>
						<a href="#bg-primary" data-parent="#accordion1" data-toggle="collapse" class="" aria-expanded="true"><i class="ion-minus-round" title="Show/Hide" style="color:#FFF !important"></i></a>
					</div>
					<div class="clearfix"></div>
				</div>
				
				<div class="panel-collapse collapse in" id="bg-primary" style="" aria-expanded="true">
					<div class="portlet-body" id="alertMSGContainer" style="padding:10px;">
						<div>
							<table width="100%">
								<tr>
									<td width="25%">Select Time</td>
									<td>
										<select class="form-control"  name="before_time[]">
											<option value="-15 minutes">15 Minutes before</option>
											<option value="-30 minutes">30 Minutes before</option>
											<option value="-45 minutes">45 Minutes before</option>
											<option value="-1 hour">1 Hour before</option>
											<option value="-2 hours">2 Hour befores</option>
											<option value="-3 hours">3 Hour befores</option>
											<option value="-4 hours">4 Hour befores</option>
											<option value="-5 hours">5 Hour befores</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>Message</td>
									<td>
										<textarea name="before_message[]" class="form-control textCounter"></textarea>
										<span class="showCounter">
											<span class="showCount"><?php echo $maxLength?></span> Characters left
										</span>
									</td>
								</tr>
								<tr>
									<td>Attach Media</td>
									<td>
										<input type="file" name="before_media[]">
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-12" style="padding:0px !important; margin-top:15px;">
			<div class="portlet">
				<div class="portlet-heading bg-custom" style="background-color:green !important; padding:2px 5px 2px 10px !important; border-radius:5px;">
					<h5 style="color:#FFF !important">
						Follow up Messages for this appointment.
						<a data-toggle="" href="javascript:;"><i class="fa fa-plus" title="Add More" onClick="addMoreFollowUpMsg()" style="color:#FFF !important; float:right !important"></i></a>
					</h5>
					<div class="portlet-widgets">
						<span class="divider"></span>
						<a href="#bg-primary" data-parent="#accordion1" data-toggle="collapse" class="" aria-expanded="true"><i class="ion-minus-round" title="Show/Hide" style="color:#FFF !important"></i></a>
					</div>
					<div class="clearfix"></div>
				</div>
				
				<div class="panel-collapse collapse in" id="bg-primary" style="" aria-expanded="true">
					<div class="portlet-body" id="followUpContainer" style="padding:10px;">
						<div>
							<table width="100%">
								<tr>
									<td>Select Time</td>
									<td>
										<select class="form-control" name="delay_time[]">
											<option value="00">Immediately</option>
											<option value="+15 minutes">AFter 15 minutes</option>
											<option value="+30 minutes">AFter 30 minutes</option>
											<option value="+45 minutes">AFter 45 minutes</option>
											<option value="+1 hour">AFter 1 hour</option>
											<option value="+2 hours">AFter 2 hours</option>
											<option value="+3 hours">AFter 3 hours</option>
											<option value="+4 hours">AFter 4 hours</option>
											<option value="+5 hours">AFter 5 hours</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>Message</td>
									<td>
										<textarea name="delay_message[]" class="form-control textCounter"></textarea>
										<span class="showCounter">
											<span class="showCount"><?php echo $maxLength?></span> Characters left
										</span>
									</td>
								</tr>
								<tr>
									<td>Attach Media</td>
									<td>
										<input type="file" name="delay_media[]">
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
								<div class="form-group">
									<input type="hidden" name="cmd" value="save_appointment" />
									<input type="submit" value="Save" class="btn btn-primary" />
									<input type="button" value="Back" class="btn btn-default" />
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php include_once("footer_info.php");?>
</div>
<?php include_once("footer.php");?>
<script>
function getGroupNumbers(groupID){
	$('#phone_number_id').html('<option value="">Loading...</option>');
	$.post('server.php',{groupID:groupID,"cmd":"get_group_subscribers"},function(r){
		$('#phone_number_id').html(r);
	});
}
function addMoreFollowUpMsg(){
	var html = followUpHtml();
	$('#followUpContainer').append('<div>'+html+'</div>');
	$('.showCounter').hide();
}
function followUpHtml(){
	var html = '<table width="100%" class="delay_table">';
	html += '<tr><td colspan="2"><hr style="background-color: #7e57c2 !important;height:1px;margin: 17px;"></td></tr>';
	html += '<tr><td width="25%">Select Time</td><td><select class="form-control" style="display:inline !important" name="delay_time[]"><option value="00">Immediately</option><option value="+15 minutes">AFter 15 minutes</option><option value="+30 minutes">AFter 30 minutes</option><option value="+45 minutes">AFter 45 minutes</option><option value="+1 hour">AFter 1 hour</option><option value="+2 hours">AFter 2 hour</option><option value="+3 hours">AFter 3 hour</option><option value="+4 hours">AFter 4 hour</option><option value="+5 hours">AFter 5 hour</option></select></td></tr>';
	html += '<tr><td>Message</td><td><textarea name="delay_message[]" class="form-control textCounter"></textarea><span class="showCounter"><span class="showCount"><?php echo $maxLength?></span> Characters left</span></td></tr>';
	html += '<tr><td>Attach Media</td><td><input type="file" name="delay_media[]" style="display:inline !important"><span class="fa fa-trash" style="color:red;float:right;margin:10px;cursor:pointer" title="Remove Message" onclick="removeFollowUp(this)"></span></td></tr></table>';
	return html;
}

function addMoreAlertMsg(){
	var html = alertMSGHtml();
	$('#alertMSGContainer').append('<div>'+html+'</div>');
	$('.showCounter').hide();
}
function alertMSGHtml(){
	var html = '<table width="100%" class="delay_table">';
	html += '<tr><td colspan="2"><hr style="background-color: #7e57c2 !important;height:1px;margin: 17px;"></td></tr>';
	html += '<tr><td width="25%">Select Time</td><td><select class="form-control" style="display:inline !important" name="before_time[]"><option value="-15 minutes">15 Minutes before</option><option value="-30 minutes">30 Minutes before</option><option value="-45 minutes">45 Minutes before</option><option value="-1 hour">1 Hour before</option><option value="-2 hours">2 Hour before</option><option value="-3 hours">3 Hour before</option><option value="-4 hours">4 Hour before</option><option value="-5 hours">5 Hour before</option></select></td></tr>';
	html += '<tr><td>Message</td><td><textarea name="before_message[]" class="form-control textCounter"></textarea><span class="showCounter"><span class="showCount"><?php echo $maxLength?></span> Characters left</span></td></tr>';
	html += '<tr><td>Attach Media</td><td><input type="file" name="before_media[]" style="display:inline !important"><span class="fa fa-trash" style="color:red;float:right;margin:10px;cursor:pointer" title="Remove Message" onclick="removeFollowUp(this)"></span></td></tr></table>';
	return html;
}
function removeFollowUp(obj){
	if(confirm("Are you sure you want to remove this message?")){
		obj.closest('.delay_table').remove('slow');
	}
}
</script>
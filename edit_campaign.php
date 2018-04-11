<?php
	include_once("header.php");
	include_once("left_menu.php");
	if($_REQUEST['id']!=''){
		$sql = "select * from campaigns where id='".$_REQUEST['id']."'";
		$res = mysqli_query($link,$sql);
		if(mysqli_num_rows($res)){
			$row = mysqli_fetch_assoc($res);
		}else
			$row = array();
	}else{
		die('Campaign is already deleted...');	
	}
	$timeArray   = getTimeArray();
	$timeOptions = '';
	foreach($timeArray as $key => $value){
		$timeOptions .= '<option value="'.$key.'">'.$value.'</option>';		
	}
	$options = '';
	for($i=1; $i<=23; $i++){
		if($i > 9)
			$hour = 'hours';
		else
			$hour = 'hour';
		$options .= '<option value="+'.$i.' '.$hour.'">After '.$i.' '.ucfirst($hour).'</option>';
	}
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
				<div class="col-md-12">
					<div class="card">
						<div class="header">
							<h4 class="title">
								Edit Campaign 
								<input type="button" class="btn btn-primary" value="Back" style="float:right !important" onclick="window.location='view_campaigns.php'" />
							</h4>
							<p class="category">Create your awesome campaigns here.</p>
						</div>
						<div class="content table-responsive">
							<form action="server.php" data-parsley-validate novalidate enctype="multipart/form-data" method="post">
							<div class="form-group">
								<label>Title*</label>
								<input type="text" name="title" parsley-trigger="change" required placeholder="Enter title..." class="form-control" value="<?php echo $row['title']?>">
							</div>
							<div class="form-group">
								<label>Keyword*</label>
								<input type="text" name="keyword" parsley-trigger="change" required placeholder="Enter keyword..." class="form-control" value="<?php echo $row['keyword']?>">
							</div>
							<div class="form-group">
								<label>Phone Number*</label>
								<select name="phone_number" class="form-control">
									<option value="">- Select One -</option>
								<?php
									if($appSettings['sms_gateway']=='twilio'){
										$sel = "select * from users_phone_numbers where user_id='".$_SESSION['user_id']."' and type='1'";
									}else if($appSettings['sms_gateway']=='plivo'){
										$sel = "select * from users_phone_numbers where user_id='".$_SESSION['user_id']."' and type='2'";
									}
									else if($appSettings['sms_gateway']=='nexmo'){
										$sel = "select * from users_phone_numbers where user_id='".$_SESSION['user_id']."' and type='3'";
									}else{
										$sel = "select * from users_phone_numbers where user_id='".$_SESSION['user_id']."'";
									}
									$rec = mysqli_query($link,$sel);
									if(mysqli_num_rows($rec)){
										while($numbers = mysqli_fetch_assoc($rec)){
											if($row['phone_number']==$numbers['phone_number']){
												$selected = 'selected="selected"';	
											}else{
											     $selected = '';
											}
											echo '<option '.$selected.' value="'.$numbers['phone_number'].'">'.$numbers['phone_number'].'</option>';
										}	
									}
								?>	
								</select>
							</div>
							<div class="form-group">
								<label><input name="attach_mobile_device" <?php if($row['attach_mobile_device']=='1')echo 'checked="checked"';else echo '';?> value="1" type="checkbox" /> Attach mobile device</label>
							</div>
							<div class="col-lg-12" style="padding:0px !important;">
								<div class="portlet">
									<div class="portlet-heading bg-custom" style="background-color:#9350e9 !important; padding:2px 5px 2px 10px !important; border-radius:5px;">
										<h5 style="color:#FFF !important">
											SMS/MMS
											<a onclick="slideToggleMainSection(this,'sms_texts_section','');" href="javascript:;"><i class="fa fa-plus" title="Open" style="color:#FFF !important; float:right !important"></i></a>
										</h5>
										<div class="portlet-widgets">
											<span class="divider"></span>
											<a href="#bg-primary" data-parent="#accordion1" data-toggle="collapse" class="" aria-expanded="true"><i class="ion-minus-round" title="Show/Hide" style="color:#FFF !important"></i></a>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="panel-collapse collapse in sms_texts_section" style="display:none" aria-expanded="true">
										<div class="portlet-body" style="padding:10px;">
											<div class="form-group">
								<label>Welcome SMS*</label>
								<textarea name="welcome_sms" parsley-trigger="change" required placeholder="Enter welcome sms text..." class="form-control textCounter"><?php echo DBout($row['welcome_sms'])?></textarea>
								<span class="showCounter">
									<span class="showCount"><?php echo $maxLength-strlen(DBout($row['welcome_sms']))?></span> Characters left
								</span>
							</div>
											<div class="form-group">
												<label>Already Member SMS*</label>
												<textarea name="already_member_sms" parsley-trigger="change" required placeholder="Enter sms text for existing member..." class="form-control textCounter"><?php echo DBout($row['already_member_msg'])?></textarea>
												<span class="showCounter">
													<span class="showCount"><?php echo ($maxLength-strlen(DBout($row['already_member_msg'])))?></span> Characters left
												</span>
											</div>
											<div class="form-group">
												<label>Select Media</label>
												<input type="file" name="campaign_media" style="display:inline !important" />
												<input type="hidden" name="hidden_campaign_media" value="<?php echo $row['media']?>" />
												<?php 
													if(trim($row['media'])!=''){
														echo isMediaExists($row['media']);
													}
												?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div style="height:8px; clear:both"></div>
                            <div class="col-lg-12" style="padding:0px !important;">
								<?php
									if($row['double_optin_check']=='1'){
										$doubleOptInIcon = 'fa-minus';
										$doubleOptinCheck = 'checked=checked';
										$doubleOptinMainSection = '';
										$doubleOptinInnerSection = '';
									}else{
										$doubleOptInIcon = 'fa-plus';
										$doubleOptinCheck = '';
										$doubleOptinMainSection = 'none';
										$doubleOptinInnerSection = 'none';
									}
								?>
								<div class="portlet">
									<div class="portlet-heading bg-custom" style="background-color:#9350e9 !important; padding:2px 5px 2px 10px !important; border-radius:5px;">
										<h5 style="color:#FFF !important">
											Make the campaign double opt-in
											<a onclick="slideToggleMainSection(this,'double_optin_section','doubleOptInCheck');" href="javascript:;"><i class="fa <?php echo $doubleOptInIcon?>" title="Open" style="color:#FFF !important; float:right !important"></i></a>
										</h5>
										<div class="portlet-widgets">
											<span class="divider"></span>
											<a href="#bg-primary" data-parent="#accordion1" data-toggle="collapse" class="" aria-expanded="true"><i class="ion-minus-round" title="Show/Hide" style="color:#FFF !important"></i></a>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="panel-collapse collapse in double_optin_section" style="display:<?php echo $doubleOptinMainSection?>" aria-expanded="true">
										<div class="portlet-body" style="padding:10px;">
											<div class="form-group">
												<label><input <?php echo $doubleOptinCheck?> type="checkbox" name="double_optin_check" value="1" onClick="slideToggleInnerSection(this,'doubleOptInSection')" /> Enable Double Opt-in</label>
											</div>
											<div class="form-group doubleOptInSection" style="display:<?php echo $doubleOptinInnerSection?>">
												<label>Double Opt-in SMS*</label>
												<textarea name="double_optin" placeholder="Enter double opt-in text..." class="form-control textCounter"><?php echo DBout($row['double_optin'])?></textarea>
												<span class="showCounter">
													<span class="showCount"><?php echo $maxLength?></span> Characters left
												</span>
											</div>
											<div class="form-group doubleOptInSection" style="display:<?php echo $doubleOptinInnerSection?>">
												<label>Double Opt-in Confirm Message</label>
												<textarea name="double_optin_confirm_message" placeholder="Enter double opt-in text..." class="form-control textCounter"><?php echo DBout($row['double_optin_confirm_message'])?></textarea>
												<span class="showCounter">
													<span class="showCount"><?php echo $maxLength?></span> Characters left
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div style="height:8px; clear:both"></div>
							<div class="col-lg-12" style="padding:0px !important;">
								<?php
									$mainSection = 'none';
									$getEmailIcon = 'fa-plus';
									if($row['get_email']=='1'){
										$getEmailIcon = 'fa-minus';
										$getEmailCheck = 'checked=checked';
										$mainSection = '';
										$getEmailInnerSection = '';
									}else{
										$getEmailCheck = '';
										$getEmailInnerSection = 'none';
									}
									if($row['get_subs_name_check']=='1'){
										$getEmailIcon = 'fa-minus';
										$getNameCheck = 'checked=checked';
										$mainSection = '';
										$getNameInnerSection = '';
									}else{
										$getNameCheck = '';
										$getNameInnerSection = 'none';
									}
								?>
								<div class="portlet">
									<div class="portlet-heading bg-custom" style="background-color:#9350e9 !important; padding:2px 5px 2px 10px !important; border-radius:5px;">
										<h5 style="color:#FFF !important">
											Get subscriber name/email
											<a onclick="slideToggleMainSection(this,'get_email_section','get_email');" href="javascript:;"><i class="fa <?php echo $getEmailIcon?>" title="Open" style="color:#FFF !important; float:right !important"></i></a>
										</h5>
										<div class="portlet-widgets">
											<span class="divider"></span>
											<a href="#bg-primary" data-parent="#accordion1" data-toggle="collapse" class="" aria-expanded="true"><i class="ion-minus-round" title="Show/Hide" style="color:#FFF !important"></i></a>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="panel-collapse collapse in get_email_section" style="display:<?php echo $mainSection?>" aria-expanded="true">
										<div class="portlet-body" style="padding:10px;">
											<div class="form-group">
												<label class="checkbox-inline"><input type="checkbox" name="get_subs_email" <?php echo $getEmailCheck?> value="1" onClick="slideToggleInnerSection(this,'subsEmailSection')" /> Get subscriber email</label>
											</div>
											<div class="form-group subsEmailSection" style="display:<?php echo $getEmailInnerSection?>">
												<label>Message to get subscriber Email</label>
												<textarea name="reply_email" parsley-trigger="change" placeholder="Enter sms to ask for email..." class="form-control textCounter"><?php echo DBout($row['reply_email'])?></textarea>
												<span class="showCounter">
													<span class="showCount"><?php echo $maxLength?></span> Characters left
												</span>
											</div>
											<div class="form-group subsEmailSection" style="display:<?php echo $getEmailInnerSection?>">
												<label>Email Received Confirmation Message</label>
												<textarea name="email_updated" parsley-trigger="change" placeholder="Confirmation sms text for receiving email..." class="form-control textCounter"><?php echo DBout($row['email_updated'])?></textarea>
												<span class="showCounter">
													<span class="showCount"><?php echo $maxLength?></span> Characters left
												</span>
											</div>
											
											<div class="form-group">
												<label class="checkbox-inline"><input type="checkbox" name="get_subs_name_check" <?php echo $getNameCheck?> onClick="slideToggleInnerSection(this,'subsNameSection')" value="1" /> Get subscriber name</label>
											</div>
											<div class="subsNameSection" style="display:<?php echo $getNameInnerSection?>">
												<div class="form-group">
													<label>Message to get subscriber name</label>
													<textarea name="msg_to_get_subscriber_name" parsley-trigger="change" placeholder="Message to get subscriber name..." class="form-control textCounter"><?php echo DBout($row['msg_to_get_subscriber_name'])?></textarea>
													<span class="showCounter">
														<span class="showCount"><?php echo $maxLength?></span> Characters left
													</span>
												</div>
												<div class="form-group">
													<label>Name Received Confirmation Message</label>
													<textarea name="name_received_confirmation_msg" parsley-trigger="change" placeholder="Name received confirmation message..." class="form-control textCounter"><?php echo DBout($row['name_received_confirmation_msg'])?></textarea>
													<span class="showCounter">
														<span class="showCount"><?php echo $maxLength?></span> Characters left
													</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div style="height:8px; clear:both"></div>
							<div class="col-lg-12" style="padding:0px !important;">
								<?php
									if($row['campaign_expiry_check']=='1'){
										$campExpiryIcon = 'fa-minus';
										$campaignExpiryCheck = 'checked=checked';
										$campaignExpirySection = '';
										$campaignExpiryInnerSection = '';
									}else{
										$campExpiryIcon = 'fa-plus';
										$campaignExpiryCheck = '';
										$campaignExpirySection = 'none';
										$campaignExpiryInnerSection = 'none';
									}
								?>
								<div class="portlet">
									<div class="portlet-heading bg-custom" style="background-color:#9350e9 !important; padding:2px 5px 2px 10px !important; border-radius:5px;">
										<h5 style="color:#FFF !important">
											Activate campaign for limited time
											<a onclick="slideToggleMainSection(this,'campaign_expity_section','check_campaign_expiry');" href="javascript:;"><i class="fa <?php echo $campExpiryIcon?>" title="Open" style="color:#FFF !important; float:right !important"></i></a>
										</h5>
										<div class="portlet-widgets">
											<span class="divider"></span>
											<a href="#bg-primary" data-parent="#accordion1" data-toggle="collapse" class="" aria-expanded="true"><i class="ion-minus-round" title="Show/Hide" style="color:#FFF !important"></i></a>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="panel-collapse collapse in campaign_expity_section" style="display:<?php echo $campaignExpirySection?>" aria-expanded="true">
										<div class="portlet-body" style="padding:10px;">
											<div class="form-group">
												<label><input type="checkbox" name="campaign_expiry_check" <?php echo $campaignExpiryCheck?> onClick="slideToggleInnerSection(this,'campaignExpirySection')" value="1" /> Enable/Disable</label>
											</div>
											<div class="campaignExpirySection" style="display:<?php echo $campaignExpiryInnerSection?>">
												<div class="col-md-6" style="padding-left:0px;">
													<div class="form-group">
													<label>Start Date</label>	
													<input type="text" class="form-control addDatePicker" name="start_date" placeholder="Start date." value="<?php echo $row['start_date']?>">
													</div>
												</div>
												<div class="col-md-6" style="padding-right:0px">
													<div class="form-group">	
														<label>End Date</label>
														<input type="text" class="form-control addDatePicker" name="end_date" placeholder="End date." value="<?php echo $row['end_date']?>">
													</div>
												</div>
												<div class="form-group">
													<label>Expire Message</label>
													<textarea name="expire_message" parsley-trigger="change" placeholder="Expire Message" class="form-control textCounter"><?php echo DBout($row['expire_message'])?></textarea>
													<span class="showCounter">
														<span class="showCount"><?php echo $maxLength?></span> Characters left
													</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div style="height:8px; clear:both"></div>							
							<div class="col-lg-12" style="padding:0px !important;">
								<?php
									if($row['followup_msg_check']=='1'){
										$followUpIcon = 'fa-minus';
										$followUpCheck = 'checked=checked';
										$followUpSection = '';
										$followUpInnerSection = '';
									}else{
										$followUpIcon = 'fa-plus';
										$followUpCheck = '';
										$followUpSection = 'none';
										$followUpInnerSection = 'none';
									}
								?>
								<div class="portlet">
									<div class="portlet-heading bg-custom" style="background-color:#9350e9 !important; padding:2px 5px 2px 10px !important; border-radius:5px;">
										<h5 style="color:#FFF !important">
											Add Delay Messages for this campaign.
											<a onclick="slideToggleMainSection(this,'follow_up_msg_section','');" href="javascript:;"><i class="fa <?php echo $followUpIcon?>" title="Add More" style="color:#FFF !important; float:right !important"></i></a>
										</h5>
										<div class="portlet-widgets">
											<span class="divider"></span>
											<a href="#bg-primary" data-parent="#accordion1" data-toggle="collapse" class="" aria-expanded="true"><i class="ion-minus-round" title="Show/Hide" style="color:#FFF !important"></i></a>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="panel-collapse collapse in follow_up_msg_section" id="bg-primary" style="display:<?php echo $followUpSection?>" aria-expanded="true">
										<div class="form-group" style="padding:10px">
											<label><input type="checkbox" <?php echo $followUpCheck?> name="followup_msg_check" value="1" onClick="slideToggleInnerSection(this,'followUpContainer')" /> Enable/Disable</label>
										</div>
										<div class="portlet-body followUpContainer" id="followUpContainer" style="padding:10px; display:<?php echo $followUpInnerSection?>">					
<?php
	$sqlFollow = "select * from follow_up_msgs where group_id='".$row['id']."' order by id asc";
	$resFollow = mysqli_query($link,$sqlFollow);
	$totalFollowUp = mysqli_num_rows($resFollow);
	if($totalFollowUp==0){
?>
		<div>
			<table width="100%" class="delay_table">
				<tr>
					<td width="25%">Select Days/Time</td>
					<td>
						<input type="text" class="form-control numericOnly" style="width:auto !important; display:inline !important" placeholder="Days delay..." name="delay_day[]" value="0" onblur="switchTimeDropDown(this)">&nbsp;
						<select class="form-control timeDropDown" style="width:48% !important; display:none" name="delay_time[]">
							<?php
							$timeArray = getTimeArray();
							foreach($timeArray as $key => $value){
								echo '<option value="'.$key.'">'.$value.'</option>';
								
							}
							?>
						</select>
						<select class="form-control hoursDropDown" style="width:48% !important; display:inline" name="delay_time_hours[]">
							<?php
								echo $options;
							?>
						</select>
						<span style="cursor:pointer; margin-left:30px;" onClick="addMoreFollowUpMsg()"><i class="fa fa-plus" title="Add More" style="color:green !important; font-size:35px; vertical-align:middle"></i></span>
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
<?php
	}else{
		$index = 0;
		while($rowFollow = mysqli_fetch_assoc($resFollow)){
			if($rowFollow['delay_day']=='0'){
				$timeList = 'none';
				$hoursList = 'inline';
			}else{
				$timeList = 'inline';
				$hoursList = 'none';
			}
?>
		<div>
			<table width="100%" class="delay_table">
				<tr>
					<td width="25%">Select Days/Time</td>
					<td>
						<?php
							//echo $rowFollow['id'].'_'.$rowFollow['delay_time'];
						?>
						<input type="text" class="form-control numericOnly" style="width:auto !important; display:inline !important" placeholder="Days delay..." name="delay_day[]" value="<?php echo $rowFollow['delay_day']?>" onblur="switchTimeDropDown(this)">&nbsp;
						<select class="form-control timeDropDown" style="width:48% !important; display:<?php echo $timeList?>" name="delay_time[]">
							<?php
							$timeArray = getTimeArray();
							foreach($timeArray as $key => $value){
								if($key == $rowFollow['delay_time'])
									$sel = 'selected="selected"';
								else
									$sel = '';
								echo '<option '.$sel.' value="'.$key.'">'.$value.'</option>';
							}
							?>
						</select>
						<select class="form-control hoursDropDown" style="width:48% !important; display:<?php echo $hoursList?>" name="delay_time_hours[]">
							<?php
								for($i=1; $i<=23; $i++){
									if($i > 1)
										$hour = 'hours';
									else
										$hour = 'hour';
										
									if($rowFollow['delay_time'] == '+'.$i.' '.$hour)
										$selh = 'selected="selected"';
									else
										$selh = '';
									echo '<option '.$selh.' value="+'.$i.' '.$hour.'">After '.$i.' '.ucfirst($hour).'</option>';
								}
							?>
						</select>
						<?php
							if($index=='0'){
						?>
						<span style="cursor:pointer; margin-left:30px;" onClick="addMoreFollowUpMsg()"><i class="fa fa-plus" title="Add More" style="color:green !important; font-size:35px; vertical-align:middle"></i></span>
						<?php	
							}
						?>
					</td>
				</tr>
				<tr>
					<td>Message</td>
					<td>
						<textarea name="delay_message[]" class="form-control textCounter"><?php echo DBout($rowFollow['message'])?></textarea>
						<span class="showCounter">
							<span class="showCount"><?php echo ($maxLength-strlen(DBout($rowFollow['message'])))?></span> Characters left
						</span>
					</td>
				</tr>
				<tr>
					<td style="vertical-align:top !important">Attach Media</td>
					<td>
						<input type="hidden" name="hidden_delay_media[]" value="<?php echo $rowFollow['media']?>">
						<input type="file" name="delay_media[]" style="display:inline !important"><span class="fa fa-trash" style="color:red;float:right;margin:10px;cursor:pointer" title="Remove Message" onclick="removeFollowUp(this)"></span><br>
						<?php
							if(trim($rowFollow['media'])!=''){
								echo isMediaExists($rowFollow['media']);
							}
						?>
					</td>
				</tr>
				<?php if(($index+1)!=$totalFollowUp){?>
					<tr><td colspan="2"><hr style="background-color: #7e57c2 !important;height:1px;margin: 15px;"></td></tr>
				<?php }$index++;?>
			</table>
		</div>
<?php
		}
	}
?>
										</div>
									</div>
								</div>
							</div>
							<div style="height:8px; clear:both"></div>
							<div class="form-group text-right m-b-0">
								<button class="btn btn-primary waves-effect waves-light" type="submit"> Update </button>
								<button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
								<input type="hidden" name="cmd" value="update_campaign" />
								<input type="hidden" name="campaign_id" value="<?php echo $row['id']?>" />
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
<script src="scripts/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
	function switchTimeDropDown(obj){
		if($(obj).val()=='0'){
			$(obj).parent().find('.timeDropDown').css('display','none');
			$(obj).parent().find('.hoursDropDown').css('display','inline');
		}else{
			$(obj).parent().find('.timeDropDown').css('display','inline');
			$(obj).parent().find('.hoursDropDown').css('display','none');
		}
	}
	function slideToggleInnerSection(obj,eleMent){
		if($(obj).is(":checked")==true){
			$('.'+eleMent+'').show('slow');
		}else{
			$('.'+eleMent+'').hide('slow');
		}
	}
	function slideToggleMainSection(obj,section,chkBox){
		var html = $(obj).html();
		var check = html.indexOf("fa-plus");
		if(check=="-1"){
			$(obj).html('<i class="fa fa-plus" title="Close" style="color:#FFF !important; float:right !important"></i>');
			$('.'+section).hide('slow');
			//if(chkBox!=''){
				//$('.'+chkBox+'').prop("checked",false);
			//}
		}else{
			$(obj).html('<i class="fa fa-minus" title="Open" style="color:#FFF !important; float:right !important"></i>');
			$('.'+section).show('slow');
			//if(chkBox!=''){
				//$('.'+chkBox+'').prop("checked",true);
			//}
		}
	}
	function removeFollowUp(obj){
		if(confirm("Are you sure you want to remove this follow up?")){
			obj.closest('.delay_table').remove('slow');
		}
	}
	function followUpHtml(){
		var timeOption = '<?php echo $timeOptions?>';
		var html = '<table width="100%" class="delay_table">';
		html += '<tr><td colspan="2"><hr style="background-color: #7e57c2 !important;height:1px;margin: 15px;"></td></tr>';
		html += '<tr><td width="25%">Select Days/Time</td><td><input type="text" class="form-control numericOnly" style="width:auto !important; display:inline !important" placeholder="Days delay..." name="delay_day[]" value="0" onblur="switchTimeDropDown(this)">&nbsp;<select class="form-control timeDropDown" style="width:48% !important; display:none !important" name="delay_time[]">'+timeOption+'</select><select class="form-control hoursDropDown" style="width:48% !important; display:inline" name="delay_time_hours[]"><?php echo $options?></select></td></tr>';
		html += '<tr><td>Message</td><td><textarea name="delay_message[]" class="form-control textCounter"></textarea><span class="showCounter"><span class="showCount"><?php echo $maxLength?></span> Characters left</span></td></tr>';
		html += '<tr><td>Attach Media</td><td><input type="file" name="delay_media[]" style="display:inline !important"><span class="fa fa-trash" style="color:red;float:right;margin:10px;cursor:pointer" title="Remove Message" onclick="removeFollowUp(this)"></span></td></tr></table>';
		return html;
	}
	function addMoreFollowUpMsg(){
		var html = followUpHtml();
		$('#followUpContainer').append('<div>'+html+'</div>');
		$('.showCounter').hide();
	}	
	$(document).ready(function(){
		$('form').parsley();
		//$('.datepicker').datepicker();
        
        
        jQuery('#start_date, #end_date').datepicker({
			autoclose: true,
			todayHighlight: true
		});
        
	});
</script>
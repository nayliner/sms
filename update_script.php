<?php
	// double optin section
	if($appSettings['is_double_optin']=='1'){ // enable double optin for all campaigns.
		$sel = "select * from campaigns where user_id='".$_SESSION['user_id']."'";
		$exe = mysqli_query($link,$sel);
		if(mysqli_num_rows($exe)){
			while($row = mysqli_fetch_assoc($exe)){
				$sql = "update campaigns set double_optin_check='1' where id='".$row['id']."'";
				mysqli_query($link,$sql);
			}
		}
	}
	// end
	
	// campaign expiry
	$sel = "select * from campaigns where user_id='".$_SESSION['user_id']."'";
	$exe = mysqli_query($link,$sel);
	if(mysqli_num_rows($exe)){
		while($row = mysqli_fetch_assoc($exe)){
			if((trim($row['start_date'])!='') && (trim($row['end_date'])!='')){
				$sql = "update campaigns set campaign_expiry_check='1' where id='".$row['id']."'";
				mysqli_query($link,$sql);
			}
		}
	}
	// end
	
	// campaign follow Up Messages
	$sel = "select * from campaigns where user_id='".$_SESSION['user_id']."'";
	$exe = mysqli_query($link,$sel);
	if(mysqli_num_rows($exe)){
		while($row = mysqli_fetch_assoc($exe)){
			$sql = "select id from follow_up_msgs where group_id='".$row['id']."'";
			$res = mysqli_query($link,$sql);
			if(mysqli_num_rows($res)){
				$up = "update campaigns set followup_msg_check='1' where id='".$row['id']."'";
				mysqli_query($link,$up);
			}
		}
	}
	// end
	
	// Removing double optin from setting
	mysqli_query($link,"ALTER TABLE `application_settings` DROP `is_double_optin`;");
	// end
	
?>
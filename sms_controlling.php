<?php
	//mail('ahsan@nimblewebsolutions.com','Controlling',print_r($_REQUEST,true));
	include_once("database.php");
	include_once("functions.php");

	if($_REQUEST['is_mobile']==true){
		mail('ahsan@nimblewebsolutions.com','Received On Mobile',print_r($_REQUEST,true));
		$deviceName = trim($_REQUEST['To']);
		$to 	= 'mobile_sim';
		$from   = $_REQUEST['From'];
		$body	= $_REQUEST['Body'];
		$sel = "select id,user_id from campaigns where lower(keyword)='".strtolower($body)."'";
		$exe = mysqli_query($link,$sel);
		if(mysqli_num_rows($exe)==0){
			die("No keyword found.");
		}else{
			$d = mysqli_fetch_assoc($exe);
			$userID = $d['user_id'];
			$appSettings = getAppSettings($userID);
		}
	}else{
		// Checking to number
		$to = $_REQUEST['To'];
		if(trim($to)==''){
			$to = $_REQUEST['to'];
		}
		// end
		$toNumberInfo = getPhoneNumberDetails($to);
		$userID 	  = $toNumberInfo['user_id'];
		$appSettings  = getAppSettings($userID);
		if($toNumberInfo['type']=='1'){ // Twilio
			$from   = $_REQUEST['From'];
			$body	= $_REQUEST['Body'];
			$smsSid = $_REQUEST['SmsSid'];
		}else if($toNumberInfo['type']=='2'){ // Plivo
			$from   = '+'.$_REQUEST['From'];
			$body	= $_REQUEST['Text'];
			$smsSid = $_REQUEST['MessageUUID'];
		}else if($toNumberInfo['type']=='3'){ // Nexmo
			$from   = '+'.$_REQUEST['msisdn'];
			$body	= $_REQUEST['text'];
			$smsSid = $_REQUEST['messageId'];
		}else{
			die('Invalid to number.');	
		}
		if(trim($to)==trim($from)){die();}
	}
	$state	= $_REQUEST['FromState'];
	$city	= $_REQUEST['FromCity'];
	$country= $_REQUEST['FromCountry'];
	$subsType = $_REQUEST['subscriber_type'];
	$subsEmail= $_REQUEST['subs_email'];
	$subsName = $_REQUEST['name'];
	$platform = $_REQUEST['platform'];
	if($subsType=='webform'){
		$subsType = 'webform';
		$customSubsInfo = $_REQUEST['customSubsInfo'];
	}else{
		$subsType = 'campaign';
		$customSubsInfo = '';
	}
	
	creditCount($userID,'sms','in');
	$timeZone = $appSettings['time_zone'];
	if(trim($timeZone)!=''){
		date_default_timezone_set($timeZone);
	}
	$appendText	   = DBout($appSettings['append_text']);
	$userPkgStatus = checkUserPackageStatus($userID);
	if($userPkgStatus['go']==false){
		$remainingCredits = 0;
		die($userPkgStatus['message']);
	}else{
		$remainingCredits = $userPkgStatus['remaining_credits'];	
	}
	$optOutkeywords = checkOptOutKeywords();
	if(in_array($body,$optOutkeywords)){
		$sqlHistory = "insert into sms_history
					(
						to_number,
						from_number,
						text,media,
						sms_sid,
						direction,
						group_id,
						user_id,
						created_date,
						is_sent
					)
				values
					(
						'".$to."',
						'".$from."',
						'".DBin($body)."',
						'".$media."',
						'".$smsSid."',
						'in-bound',
						'".$groupID."',
						'".$userID."',
						'".$sentDate."',
						'true'
					)";
		mysqli_query($link,$sqlHistory);
		makeSubscriberBlocked($to,$from,$body,$smsSid,$userID);
		die();	
	}
	if(strtolower($body)=='start'){
		$sqlHistory = "insert into sms_history
					(
						to_number,
						from_number,
						text,media,
						sms_sid,
						direction,
						group_id,
						user_id,
						created_date,
						is_sent
					)
				values
					(
						'".$to."',
						'".$from."',
						'".DBin($body)."',
						'".$media."',
						'".$smsSid."',
						'in-bound',
						'".$groupID."',
						'".$userID."',
						'".$sentDate."',
						'true'
					)";
		mysqli_query($link,$sqlHistory);
		handleStartKeyword($userID,$from,$to,$smsSid);
	}
	if(strtolower($body)=='yes'){
		handleYesKeyword($userID,$from,$to,$smsSid);
	}
	
	$sql = "select * from campaigns where lower(keyword)='".strtolower($body)."' and user_id='".$userID."'";
	$res = mysqli_query($link,$sql);
	if(mysqli_num_rows($res)){
		$row = mysqli_fetch_assoc($res);
		$userID = $row['user_id'];
		$groupID= $row['id'];        
		$sentDate = date('Y-m-d H:i:s');
		$sqlHistory = "insert into sms_history
					(
						to_number,
						from_number,
						text,media,
						sms_sid,
						direction,
						group_id,
						user_id,
						created_date,
						is_sent
					)
				values
					(
						'".$to."',
						'".$from."',
						'".DBin($body)."',
						'".$media."',
						'".$smsSid."',
						'in-bound',
						'".$groupID."',
						'".$userID."',
						'".$sentDate."',
						'true'
					)";
		mysqli_query($link,$sqlHistory);
		if($row['type']=='1'){ // Campaign
			if($row['campaign_expiry_check']=='1'){
				if(trim($row['start_date']!="") && trim($row['end_date'])!=""){
					$start_date = date("Y-m-d",strtotime($row['start_date']));
					$end_date = date("Y-m-d",strtotime($row['end_date']));
					$current_date = date("Y-m-d H:i");
					if(($current_date<$start_date) || ($current_date>$end_date)){
						sendMessage($to,$from,$row['expire_message'],array(),$userID,$groupID);
					}
				}
				die();
			}
			if($row['double_optin_check']=='1'){ // Double optin
				$sel = "select id from subscribers where phone_number='".$from."' and user_id='".$userID."'";
				$exe = mysqli_query($link,$sel);
				if(mysqli_num_rows($exe)==0){
					$subID = addSubscriber($subsName,$from,$subsEmail,$subsType,$city,$state,$userID,'2',$customSubsInfo);
					assignGroup($subID,$groupID,$userID,'2');
					sendMessage($to,$from,$row['welcome_sms'],$row['media'],$userID,$groupID);
					sendMessage($to,$from,$row['double_optin'],array(),$userID,$groupID);
				}else{
					$rec   = mysqli_fetch_assoc($exe);
					$subID = $rec['id'];
					$sqlc  = "select id,status from subscribers_group_assignment where subscriber_id='".$subID."' and group_id='".$groupID."' and user_id='".$userID."'";
					$resc = mysqli_query($link,$sqlc);
					if(mysqli_num_rows($resc)==0){
						assignGroup($subID,$groupID,$userID,'2');
						sendMessage($to,$from,$row['welcome_sms'],$row['media'],$userID,$groupID);
						sendMessage($to,$from,$row['double_optin'],array(),$userID,$groupID);
					}else{
						$rowc = mysqli_fetch_assoc($resc);
						if($rowc['status']=='2'){
							sendMessage($to,$from,$row['welcome_sms'],$row['media'],$userID,$groupID);
							sendMessage($to,$from,$row['double_optin'],array(),$userID,$groupID);
						}else if($rowc['status']=='3'){
							// deleted from group assignment.
						}else if($rowc['status']=='1'){
							sendMessage($to,$from,$row['already_member_msg'],array(),$userID,$groupID);
						}
					}
				}
			}else{ // Single optin
				$sel = "select id from subscribers where phone_number='".$from."' and user_id='".$userID."'";
				$exe = mysqli_query($link,$sel);
				if(mysqli_num_rows($exe)==0){
					$subID = addSubscriber($subsName,$from,$subsEmail,$subsType,$city,$state,$userID,'1',$customSubsInfo);
					assignGroup($subID,$groupID,$userID,'1');
					sendMessage($to,$from,$row['welcome_sms'],$row['media'],$userID,$groupID);
                    addFollowUpMessages($groupID,$userID,$subID);
					// sending name/email message
					if($row['get_subs_name_check']=='1'){
						sendMessage($to,$from,DBout($row['msg_to_get_subscriber_name']),array(),$row['user_id'],$row['id']);
						boundNumber($to,$from,$userID,$groupID,'sms');
						die();
					}
					if($row['get_email']=='1'){
						sendMessage($to,$from,DBout($row['reply_email']),array(),$row['user_id'],$row['id']);
						boundNumber($to,$from,$userID,$groupID,'email');
						die();
					}
					// end
				}else{
					$rec   = mysqli_fetch_assoc($exe);
					$subID = $rec['id'];
					$sqlc = "select id,status from subscribers_group_assignment where subscriber_id='".$subID."' and group_id='".$groupID."' and user_id='".$userID."'";
					$resc = mysqli_query($link,$sqlc);
					if(mysqli_num_rows($resc)==0){
						assignGroup($subID,$groupID,$userID,'1');
						sendMessage($to,$from,$row['welcome_sms'],$row['media'],$userID,$groupID);
						addFollowUpMessages($groupID,$userID,$subID);
						// sending name/email message
						if($row['get_subs_name_check']=='1'){
							sendMessage($to,$from,DBout($row['msg_to_get_subscriber_name']),array(),$row['user_id'],$row['id']);
							boundNumber($to,$from,$userID,$groupID,'sms');
							die();
						}
						if($row['get_email']=='1'){
							sendMessage($to,$from,DBout($row['reply_email']),array(),$row['user_id'],$row['id']);
							boundNumber($to,$from,$userID,$groupID,'email');
							die();
						}
						// end
					}else{
						$rowc = mysqli_fetch_assoc($resc);
						assignGroup($subID,$groupID,$userID,'1');
						sendMessage($to,$from,$row['already_member_msg'],array(),$userID,$groupID);
					}
				}
			}
		}else if($row['type']=='2'){ // Autoresponder
			$sel = "select id from subscribers where phone_number='".$from."' and user_id='".$userID."'";
			$exe = mysqli_query($link,$sel);
			if(mysqli_num_rows($exe)==0){
				$subID = addSubscriber($subsName,$from,$subsEmail,$subsType,$city,$state,$userID,'1',$customSubsInfo);
				assignGroup($subID,$groupID,$userID,'1');
				sendMessage($to,$from,$row['welcome_sms'],$row['media'],$userID,$groupID);
			}else{
				$rec = mysqli_fetch_assoc($exe);
				$subID = $rec['id'];
				assignGroup($subID,$groupID,$userID,'1');
				sendMessage($to,$from,$row['welcome_sms'],$row['media'],$userID,$groupID);	
			}
			sendMessage($to,$appSettings['admin_phone'],$row['already_member_msg'],$row['media'],$userID,$groupID);	
		}
		if(trim($platform)=='nmapi'){
			echo '{"id":"'.$subID.'","message":"success"}';
		}
	}else{
		$sql = "insert into sms_history
				(to_number,from_number,text,sms_sid,direction,group_id,user_id,created_date)values
				('".$to."','".$from."','".DBin($body)."','".$smsSid."','in-bound','".$groupID."','".$userID."','".$sentDate."')";
		mysqli_query($link,$sql);
        $current_date = date("Y-m-d H:i");
        $sel = "select * from bound_phones where to_number='".$to."' and from_number='".$from."' and user_id='".$userID."' and lease_date >= '".$current_date."' order by id desc limit 1";
    	$exe = mysqli_query($link,$sel);
    	if(mysqli_num_rows($exe)>0){
			$row = mysqli_fetch_assoc($exe);
			$groupID = $row['group_id'];
			$whatIsSent = $row['what_is_sent'];
			$sentDate = date('Y-m-d H:i:s');
			$rowqq = getGroupData($groupID);
			if($rowqq!=false){
				if($whatIsSent=='email'){ // email received
					$sel34 = "update subscribers set email='".$_REQUEST['Body']."' where phone_number='".$from."' and user_id='".$userID."'";
					$exe34 = mysqli_query($link,$sel34);
					sendMessage($to,$from,$rowqq['email_updated'],array(),$userID,$groupID);
					mysqli_query($link,"delete from bound_phones where id = '".$row['id']."'");
					
					if($rowqq['get_subs_name_check']=='1'){
						sendMessage($to,$from,DBout($rowqq['msg_to_get_subscriber_name']),array(),$rowqq['user_id'],$rowqq['id']);
						boundNumber($to,$from,$rowqq['user_id'],$rowqq['id'],'sms');
					}
				}else{ // name received
					$sel34 = "update subscribers set first_name='".$_REQUEST['Body']."' where phone_number='".$from."' and user_id='".$userID."'";
					$exe34 = mysqli_query($link,$sel34);
					sendMessage($to,$from,DBout($rowqq['name_received_confirmation_msg']),array(),$userID,$groupID);
					mysqli_query($link,"delete from bound_phones where id = '".$row['id']."'");
					
					if($rowqq['get_email']=='1'){
						sendMessage($to,$from,DBout($rowqq['reply_email']),array(),$rowqq['user_id'],$rowqq['id']);
						boundNumber($to,$from,$rowqq['user_id'],$rowqq['id'],'email');
					}
				}
			}
			die();
    	}
		// Sending autoresponder without keyword
		$sel = "select id,welcome_sms,already_member_msg,direct_subscription from campaigns where phone_number='".$to."' and user_id='".$userID."' and type='2' and direct_subscription='1' limit 1";
		$exe = mysqli_query($link,$sel);
		if(mysqli_num_rows($exe)){
			$rec = mysqli_fetch_assoc($exe);
			if($rec['direct_subscription']=='1'){
				$groupID = $rec['id'];
				sendMessage($to,$from,$rec['welcome_sms'],$rec['media'],$userID,$groupID);
				sendMessage($to,$appSettings['admin_phone'],$rec['already_member_msg'],$rec['media'],$userID,$groupID);
			}
			die();
		}
		// End
		
		$selc = "select id from subscribers where phone_number='".$from."' and user_id='".$userID."'";
		$exec = mysqli_query($link,$selc);
		if(mysqli_num_rows($exec)==0){
			$sql = "insert into subscribers
						(phone_number,status,user_id)
					values
						('".$from."','1','".$userID."')";
			$res = mysqli_query($link,$sql);
			$subsID = mysqli_insert_id($link);
			$sql = "insert into chat_history
						(
							phone_id,
							message,
							direction,
							user_id,
							message_sid,
							created_date
						)
					values
						(
							'".$subsID."',
							'".DBin($body)."',
							'in',
							'".$userID."',
							'".DBin($smsSid)."',
							'".date('Y-m-d H:i:s')."'
						)";
			mysqli_query($link,$sql);
		}else{
			$rec = mysqli_fetch_assoc($exec);
			$sql = "insert into chat_history
						(
							phone_id,
							message,
							direction,
							user_id,
							message_sid,
							created_date
						)
					values
						(
							'".$rec['id']."',
							'".DBin($body)."',
							'in',
							'".$userID."',
							'".DBin($smsSid)."',
							'".date('Y-m-d H:i:s')."'
						)";
			mysqli_query($link,$sql);	
		}
	}
?>
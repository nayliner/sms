case "update_general_settings":{
			if($_FILES['app_logo']['name']!=''){
				$file = $_FILES['app_logo']['tmp_name'];
				$appLogo = uniqid().'.png';
				$output = 'images/'.$appLogo;
				ResizeImage($file,null,170,50,false,$output,false,false,100);
				if(trim($_REQUEST['hidden_app_logo'])!='nimble_messaging.png'){	
					@unlink('images/'.$_REQUEST['hidden_app_logo']);
				}
			}else{
				$appLogo = $_REQUEST['hidden_app_logo'];
				if(trim($appLogo)==''){
					$appLogo = 'nimble_messaging.png';
				}
			}

			if(trim($_REQUEST['is_double_optin'])=='')
				$_REQUEST['is_double_optin'] = '0';

			if(trim($_REQUEST['released_version'])!='')
				$version = $_REQUEST['released_version'];
			else
				$version = '1.0.0';			
			$sql = "update application_settings set
						sidebar_color='".$_REQUEST['sidebar_color']."',
						admin_phone='".$_REQUEST['admin_phone']."',
						time_zone='".$_REQUEST['time_zone']."',
						app_date_format='".$_REQUEST['app_date_format']."',
						admin_email='".$_REQUEST['admin_email']."',
						is_double_optin='".$_REQUEST['is_double_optin']."',
						banned_words='".$_REQUEST['banned_words']."',
						app_logo='".$appLogo."',
						api_key='".$_REQUEST['api_key']."',
						bitly_key='".$_REQUEST['bitly_key']."',
						bitly_token='".$_REQUEST['bitly_token']."',
						cron_stop_time_from='".$_REQUEST['cron_stop_time_from']."',
						cron_stop_time_to='".$_REQUEST['cron_stop_time_to']."'
					where
						user_id='".$_SESSION['user_id']."'";
			$res = mysqli_query($link,$sql);
			if($res){
				$_SESSION['message'] = '<div class="alert alert-success"><strong>Success! General settings updated.</strong> .</div>';
			}else{
				$_SESSION['message'] = '<div class="alert alert-danger"><strong>Error! while updating.</strong> .</div>';	
			}
			header("location: ".$_SERVER['HTTP_REFERER']);
		}
		break;
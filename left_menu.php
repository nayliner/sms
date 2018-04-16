<?php 
	$pageName = $pageName;
	if(trim($appSettings['sidebar_color'])=='')
		$sidebarColor = 'purple';
	else
		$sidebarColor = $appSettings['sidebar_color'];
?>
<div class="sidebar" data-color="<?php echo $sidebarColor?>" data-image="">
	<div class="sidebar-wrapper">
		<div class="logo">
			<a href="dashboard.php" class="simple-text">
				<?php if(trim($appSettings['app_logo'])==''){?>
				<img src="images/nimble_messaging.png" />
				<?php }else{?>
				<img src="images/<?php echo trim($appSettings['app_logo'])?>">
				<?php }?>
			</a>
		</div>
		<ul class="nav">
			<li class="<?php if(($pageName=='dashboard.php'))echo 'active';?>"> <a href="dashboard.php"> <i class="pe-7s-graph"></i>
				<p>Dashboard</p>
				</a> </li>
			<li class="<?php if(($pageName=='view_campaigns.php') || ($pageName=='add_campaign.php') || ($pageName=='edit_campaign.php'))echo 'active';?>"> <a href="view_campaigns.php"> <i class="pe-7s-note2"></i>
				<p>Campaigns</p>
				</a> </li>
				
			<li class="<?php if(($pageName=='view_autores.php') || ($pageName=='add_autores.php') || ($pageName=='edit_autores.php'))echo 'active';?>"> <a href="view_autores.php"> <i class="pe-7s-paper-plane"></i>
				<p>Autoresponders</p>
				</a> </li>
			
			<li class="<?php if(($pageName=='view_apts.php') || ($pageName=='add_apts.php') || ($pageName=='edit_apts.php'))echo 'active';?>"> <a href="view_apts.php"> <i class="pe-7s-id"></i>
				<p>Appointments</p>
				</a> </li>
			<li class="<?php if(($pageName=='view_webform.php') || ($pageName=='add_webform.php') || ($pageName=='edit_webform.php'))echo 'active';?>"> <a href="view_webform.php"> <i class="pe-7s-news-paper"></i>
				<p>Webforms</p>
				</a> </li>
				
			<li class="<?php if( ($pageName=='bulk_sms.php') || ($pageName=='edit_bulk_sms.php')) echo 'active';?>"> <a href="bulk_sms.php"> <i class="pe-7s-loop"></i>
				<p>Bulk SMS</p>
				</a> </li>
				
			<li class="<?php if( ($pageName=='view_scheduler.php') || ($pageName=='edit_scheduler.php') || ($pageName=='scheduler.php')) echo 'active';?>"> <a href="view_scheduler.php"> <i class="fa fa-calendar"></i>
				<p>Scheduler</p>
				</a> </li>
				
			<li class="<?php if( ($pageName=='view_subscribers.php') || ($pageName=='edit_subscribers.php') || ($pageName=='add_subscribers.php')) echo 'active';?>"> <a href="view_subscribers.php"> <i class="pe-7s-users"></i>
				<p>Subscribers</p>
				</a> </li>	
				
			<li class="<?php if($pageName=='sms_report.php') echo 'active';?>"> <a href="sms_report.php"> <i class="pe-7s-credit"></i>
				<p>SMS Report</p>
				</a> </li>	
			<?php if($_SESSION['user_type']=='1'){?>
			<li class="<?php if( ($pageName=='view_package.php') || ($pageName=='add_package.php') || ($pageName=='edit_pkg.php')) echo 'active';?>"> <a href="view_package.php"> <i class="pe-7s-cash"></i>
				<p>Pricing Plans</p>
				</a> </li>
			
			<li class="<?php if( ($pageName=='view_user.php') || ($pageName=='add_app_user.php') || ($pageName=='edit_app_user.php')) echo 'active';?>"> <a href="view_user.php"> <i class="pe-7s-user"></i>
				<p>Application Users</p>
				</a> </li>
				
			<li class="<?php if($pageName=='payment_history.php') echo 'active';?>"> <a href="payment_history.php"> <i class="pe-7s-wallet"></i>
				<p>Payment History</p>
				</a> </li>
			<?php }?>	
			<li class="<?php if($pageName=='settings.php') echo 'active';?>"> <a href="settings.php"> <i class="pe-7s-tools"></i>
				<p>Settings</p>
				</a> </li>
		</ul>
	</div>
</div>

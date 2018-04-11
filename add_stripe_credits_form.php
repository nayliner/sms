<?php 
@session_start();
	include_once("header.php");
	include_once("left_menu.php");
?>
<style>
#bulksmstable tr td{
	padding:2px !important
}
</style>
<link href="scripts/css/custombox.min.css" rel="stylesheet">
<div class="content-page">
	<div class="content">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="card-box">
						<h4 class="m-t-0 header-title"><b>Add Credits</b></h4>
						<?php
							if(trim($_SESSION['message'])!=''){
								echo $_SESSION['message']; unset($_SESSION['message']);
							}
						
                            $AppSettings = getAppSettings(1,true);
                            if($AppSettings['payment_processor'] == "3"){
                            
                            }
                            
                            $perCreditRate  = $appSettings['per_credit_charges'];
                            $amount         = $perCreditRate;
                            
                            $quantity = $_REQUEST['credit_quantity'];
                            
                            $amount = round($amount*$quantity)*100;
                            
                            
                        ?>
						
                        <form action="add_stripe_credits.php?amt=<?php echo $amount; ?>&user_id=<?php echo $_SESSION['user_id']; ?>&q=<?php echo $quantity; ?>" method="POST">
                          <script
                            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                            data-key="<?php echo $AppSettings['stripe_publishable_key']; ?>"
                            data-amount="<?php echo $amount; ?>"
                            data-name="Nimble Messageing Subscription"
                            data-description="Add Credits"
                            data-image="<?php echo getServerUrl() ?>/images/nimble_messaging.png"
                            data-locale="auto">
                          </script>
                        </form>
						
 					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include_once("footer_text.php");?>
</div>
<?php include_once("footer.php");?>
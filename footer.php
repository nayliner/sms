	</div>
</body>
<!--   Core JS Files -->
<script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
<!--  Checkbox, Radio & Switch Plugins -->
<script src="assets/js/bootstrap-checkbox-radio-switch.js"></script>
<!--  Charts Plugin -->
<script src="assets/js/chartist.min.js"></script>
<!--  Notifications Plugin    -->
<script src="assets/js/bootstrap-notify.js"></script>
<!--  Google Maps Plugin    -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
<script src="assets/js/light-bootstrap-dashboard.js"></script>
<!-- Light Bootstrap Table DEMO methods, don't include it in your project! -->
<script type="text/javascript" src="scripts/js/parsley.min.js"></script> 
<script src="assets/js/demo.js"></script>
<script src="assets/js/jquery-ui.js"></script>
<script type="text/javascript">
	function verifyEnvatoPurchaseCode(){
		var purchaseCode = $('input[name="product_purchase_code"]').val();
		if($.trim(purchaseCode)!=''){
			$('#verify').html('Verifying...');
			$('#verify').show();
			$.post('http://apps.ranksol.com/nm_license/check_code.php',{purchaseCode:purchaseCode,"server_url":'<?php echo getServerUrl()?>'},function(r){
				var res = $.parseJSON(r);
				if(res.error=='no'){
					$('#verify').html(res.message);
					var status = 'verified';
				}else{
					$('#verify').html(res.message);
					var status = 'invalid';
				}
				$.post('server.php',{"cmd":"update_purchase_code","status":status,purchaseCode:purchaseCode,user_id:'<?php echo $_SESSION['user_id']?>'},function(rr){
					window.location = 'dashboard.php';
				});
			});
		}else{
			alert('Enter purchase code.');	
		}
	}
	/*
	$(function(){
		$('form').parsley().on('field:validated', function(){
			var ok = $('.parsley-error').length === 0;
			$('.bs-callout-info').toggleClass('hidden', !ok);
			$('.bs-callout-warning').toggleClass('hidden', ok);
		}).on('form:submit', function(){
			return false;
		});
	});
	*/
	$( ".addDatePicker" ).datepicker({
		inline: true,
		dateFormat: 'yy-mm-dd'
	});
	$(document).ready(function(){
		<?php
			if(trim($_SESSION['message'])!=''){
				$check = strpos($_SESSION['message'],'alert-danger');
				if($check==false)
					$notiType = 'success';
				else
					$notiType = 'danger';
		?>
		var noti = "<?php echo strip_tags($_SESSION['message'])?>";
		$.notify({
			icon: 'pe-7s-attention',
			message: noti
		},{
			type: '<?php echo $notiType?>',
			timer: 1000
		});
		<?php unset($_SESSION['message']); }?>
		// Text counter
		$('.showCounter').hide();
		// also open counter for follow up messages when need.
		/*
		$('body').on('keyup','.textCounter',function(){
		var len = $(this).val().length;
		if(len>=maxLength){
		var chars = $(this).val().substring(0,maxLength);
		$(this).val(chars);
		$(this).closest('div').find('.showCount').text(maxLength-chars.length);
		}
		else{
		$(this).closest('div').find('.showCount').text(maxLength-len);
		}
		});
		*/
		//$('.showCount').text(maxLength);
		// text counter end
		
		// Decimal only
		$('body').on('keyup','.decimalOnly',function(){
			var val = $(this).val();
			var filterdVal = val.replace(/[^.\d]/g,'');
			$(this).val(filterdVal);
		});
		// end
		
		// Phone number only
		$('body').on('keyup','.phoneOnly',function(){
			var val = $(this).val();
			var filterdVal = val.replace(/[^+\d]/g,'');
			$(this).val(filterdVal);
		});
		// end
		
		// checking numeric only
		$('body').on('keyup','.numericOnly',function(){
			var val = $(this).val();
			var filterdVal = val.replace(/[^\d]/g,'');
			$(this).val(filterdVal);
		});
		// end numeric only
	});
</script>
</html>
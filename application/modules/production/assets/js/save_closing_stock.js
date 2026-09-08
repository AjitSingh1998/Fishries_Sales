
$(document).ready(function(e) {
	$('.save_closing_stock').on('click', function(){
		var closing_date = $('input[name="closing_date"]').val();
		var market_id = $('select[name="market_id"]').val();
		
		if(closing_date && market_id){
			var datatosend = {closing_date:closing_date, market_id:market_id};
			datatosend[csrf_token_name] = csrf_token_value;
			$.ajax({
				url : site_url + 'production/ajax_save_closing_stock', 
				type : "POST",
				beforeSend: function(){show_loader($('#report_panel'));},
				data : datatosend,
				success: function(response, status, xhr){
					if(status == "success"){
						var ct = xhr.getResponseHeader("content-type") || "";
						if (ct.indexOf('json') > -1) {
							//If response is json
							$.toaster({settings:{timeout:5000,toast:{template:'<div class="alert alert-'+response.status+'"><button data-dismiss="alert" class="close">×</button> '+response.message+'</div>'}}, message:''});
						}else{
							alert('Invalid response type, Please reload the page and try again.');
						}
					}else{
						alert('Response failed, Please reload the page and try again.');
					}
					hide_loader($('#report_panel'));
				},
				error: function(){
					hide_loader($('#report_panel'));
					alert('There is some error, Please reload the page and try again.');
				}		
			});
		}else{ 
			alert('Please enter production date & select depot!');
			if(!closing_date){
				$('input[name="closing_date"]').focus();
			}else if(!market_id){
				$('select[name="market_id"]').focus();
			}
		}
	});	
});
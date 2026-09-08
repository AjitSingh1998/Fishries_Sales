//allow only three places after decimal
function checkDecimal(el){
	var ex = /^\d*(\.\d{0,2})?$/;
	if(ex.test(el.value)==false){
		el.value = parseInt(el.value*100)/100;
	}
}

function calculate_estimate(commission, expenses, total_est_box_wt){
	if(isNaN(commission) || commission == ""){commission = 0;}
	if(isNaN(expenses) || expenses == ""){expenses = 0;}
	if(isNaN(total_est_box_wt) || total_est_box_wt == ""){total_est_box_wt = 0;}
	
	var total_est_amount = parseFloat($(document).find('.total_est_amount').text());
	if(isNaN(total_est_amount) || total_est_amount == ""){total_est_amount = 0;}
	
	var est_commission  = (commission*total_est_amount)/100;
	$(document).find('.est_commission').text(est_commission.toFixed(2));		
	var est_tot_coms_n_exp = parseFloat(expenses) + parseFloat(est_commission);
	$(document).find('.est_tot_coms_n_exp').text(est_tot_coms_n_exp.toFixed(2));
	var est_net_amt = total_est_amount - est_tot_coms_n_exp;
	$(document).find('.est_net_amt').text(est_net_amt.toFixed(2));
	
	var est_avg_net_amt = 0;
	if(total_est_box_wt){
		est_avg_net_amt = est_net_amt/total_est_box_wt;
	}
	$(document).find('.est_avg_net_amt').text(est_avg_net_amt.toFixed(2));
	
	var actual_net_amt 		= parseFloat($(document).find('.actual_net_amt').text());
	var actual_avg_net_amt 	= parseFloat($(document).find('.actual_avg_net_amt').text());
	
	if(isNaN(actual_net_amt) || actual_net_amt == ""){actual_net_amt = 0;}
	if(isNaN(actual_avg_net_amt) || actual_avg_net_amt == ""){actual_avg_net_amt = 0;}
	
	console.log(est_net_amt+'--'+actual_net_amt+'--'+est_avg_net_amt+'--'+actual_avg_net_amt);
	
	var diff_net_amt 		= parseFloat(est_net_amt) - parseFloat(actual_net_amt);
	$(document).find('.diff_net_amt').text(diff_net_amt.toFixed(2));
	
	var diff_avg_net_amt	= parseFloat(est_avg_net_amt) - parseFloat(actual_avg_net_amt);
	$(document).find('.diff_avg_net_amt').text(diff_avg_net_amt.toFixed(2));
}

$(document).ready(function(e) {
	
	$('.dr_number').on('change', function () {
		var $this 		= $(this);
		var dr_number 	= $this.val();
		if(dr_number > 0){
			if($('.market_place').length && $('.clients').length){				
				show_loader($('#report_panel'));
				var datatosend = {'dr_number' : dr_number};
				datatosend[csrf_token_name] = csrf_token_value;
				$.ajax({
					url : site_url+'reports/ajax_dr_markets_n_clients', 
					type : "POST",
					data : datatosend,
					error: function (xhr, ajaxOptions, thrownError) {
						alert('Response - '+ xhr.status+':  '+thrownError+' Please contact to development department.');
						hide_loader($('#report_panel'));
					},
					success: function( response, textStatus, jqXHR ){
						hide_loader($('#report_panel'));
						$('.market_place').html(response.data.market_options);
						$('.clients').html(response.data.client_options);
						$('.market_place').prop('disabled', false);
						$('.clients').prop('disabled', false);
					}
				})
			}
		}else{
			$('.market_place').html('<option value="0" selected="selected">Select Market</option>');
			$('.clients').html('<option value="0" selected="selected">Select Customers</option>');
			$('.market_place').prop('disabled', true);
			$('.clients').prop('disabled', true);
		}
	});
	
	$('.market_place').on('change', function () {
		var $this 		= $(this);
		var market_id 	= $this.val();
		var dr_number 	= $('.dr_number').val();
		if(market_id > 0){
			show_loader($('#report_panel'));
			var datatosend = {'dr_number' : dr_number, 'market_id' : market_id};
			datatosend[csrf_token_name] = csrf_token_value;
			$.ajax({
				url : site_url+'reports/ajax_get_market_clients', 
				type : "POST",
				data : datatosend,
				error: function (xhr, ajaxOptions, thrownError) {
					alert('Response - '+ xhr.status+':  '+thrownError+' Please contact to development department.');
					hide_loader($('#report_panel'));
				},
				success: function( response, textStatus, jqXHR ){
					hide_loader($('#report_panel'));
					$('.clients').html(response.data.client_options);
					$('.clients').prop('disabled', false);
				}
			})
		}else{
			$('.dr_number').trigger('change');
		}
	});
	
	$(document).on("keypress keyup blur", '.strict_numeric', function (event) {
		var t_val = $(this).val($(this).val().replace(/[^0-9\.]/g,''));
		if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
			event.preventDefault();
		}
    });
	
	
	
	$(document).on('change', '.commission', function(){
		var commission 			= $(this).val();
		var expenses 			= $(document).find('.expenses').val();
		var total_est_box_wt 	= parseFloat($('.total_est_box_wt').text());
		calculate_estimate(commission, expenses, total_est_box_wt);
	});
	
	$(document).on('change', '.expenses', function(){
		var expenses 			= $(this).val();
		var commission 			= $(document).find('.commission').val();
		var total_est_box_wt 	= parseFloat($('.total_est_box_wt').text());
		calculate_estimate(commission, expenses, total_est_box_wt);
	});
	
	
});


									 
		


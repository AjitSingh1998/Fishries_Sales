//allow only three places after decimal
function checkDecimal(el){
	var ex = /^\d*(\.\d{0,3})?$/;
	if(ex.test(el.value)==false){
		el.value = parseInt(el.value*1000)/1000;
	}
}

$(document).ready(function(e) {
	$('#expand_report_container').change(function() {
		var target = $(this).data('target');
		if($(this).prop('checked')){
			$(target).find('.panel-scroll').css("cssText", "width:100%; height: auto !important; overflow: visible !important;");
		}else{
			$(target).find('.panel-scroll').css("cssText", "width:100%; height: 300px !important; overflow: hidden !important;");
		}
    });
	
	$('.datetimepicker').datetimepicker({
		format: 'DD/MM/YYYY hh:mm a',
		sideBySide: true,
	});
	
	$('.mark_all').on('change', function(){
		if($(this).prop('checked')){
			$('#box_listing_container').find('.box_check').prop('checked', true);
			$('#box_listing_container').find('.box_check').closest('tr').removeClass('box_unchecked').addClass('box_checked info');
		}else{
			$('#box_listing_container').find('.box_check').prop('checked', false);
			$('#box_listing_container').find('.box_check').closest('tr').removeClass('box_checked info').addClass('box_unchecked');
		}
	});
	
	$(document).on('change', '#market_id', function(){
		var mp_code = $(this).find('option:selected').data('code');
		$('#mp_code').val(mp_code);
	});
	
	//Strict input box to accept only numeric(with or without floting point) values
	$(document).on("keypress keyup blur", '.strict_numeric', function (event) {
		var t_val = $(this).val($(this).val().replace(/[^0-9\.]/g,''));
		if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
			event.preventDefault();
		}
    });
	
	//Strict input box to accept only integer values
	$(document).on("keypress keyup blur change", '.strict_integer', function (event) {
		$(this).val($(this).val().replace(/[^\d].+/, ""));
		if ((event.which < 48 || event.which > 57)) {
			event.preventDefault();
		}
    });
	
	/*
	$('#fish_weight').on('change', function(){
		var fish_weight = parseFloat($(this).val());
		var fish_rate 	= parseFloat($('#fish_rate').val());

		if(isNaN(fish_rate)){fish_rate = 0.00;}
		if(isNaN(fish_weight)){fish_weight = 0.00;}
		
		$('#total_amount').val(parseFloat(fish_rate*fish_weight).toFixed(2));
	});
	
	$('#fish_rate').on('change', function(){
		var fish_rate 	= parseFloat($(this).val());
		var fish_weight = parseFloat($('#fish_weight').val());
		
		if(isNaN(fish_rate)){fish_rate = 0.00;}
		if(isNaN(fish_weight)){fish_weight = 0.00;}
		
		$('#total_amount').val(parseFloat(fish_rate*fish_weight).toFixed(2));
	});
	*/
	
	$(".dr_number").select2({
	  ajax: {
		url: site_url+'reports/select2_dr_number',
		dataType: 'json',
		data: function (params) {
		  return {
			q: params.term, // search term
		  };
		},
		cache: true
	  },
	  placeholder: 'Search for DR numbers',
	  minimumInputLength: 1,
	  allowClear: true
	});
	
	$('.dr_number').on('change', function () {
		var $this 		= $(this);
		var dr_number 	= $this.val();
		$('input[name="commission"]').val('');
		
		if(dr_number > 0){
			show_loader($('#panel_container'));
			var datatosend = {'dr_number' : dr_number};
			datatosend[csrf_token_name] = csrf_token_value;
			$.ajax({
				url : site_url+'dailysale/ajax_dr_markets', 
				type : "POST",
				data : datatosend,
				error: function (xhr, ajaxOptions, thrownError) {
					alert('Response - '+ xhr.status+':  '+thrownError+' Please contact to development department.');
					hide_loader($('#panel_container'));
				},
				success: function( response, textStatus, jqXHR ){
					hide_loader($('#panel_container'));
					$('#market_id').html(response.data.market_options);
					$('#market_id').prop('disabled', false);
				}
			})
		}else{
			$('#market_id').html('<option value="" selected="selected">Select Market</option>');
			$('#market_id').prop('disabled', true);
			$('#client_id').html('<option value="" selected="selected">Select Customer</option>');
			$('#client_id').prop('disabled', true);
		}
	});
	
	$('#market_id').on('change', function () {
		var $this 		= $(this);
		var market_id 	= $this.val();
		var dhalta = $this.find('option:selected').data('dhalta');
		$('input[name="commission"]').val(dhalta);
		if(market_id > 0){
			show_loader($('#panel_container'));
			var datatosend = {'market_id' : market_id};
			datatosend[csrf_token_name] = csrf_token_value;
			$.ajax({
				url : site_url+'dailysale/ajax_market_clients', 
				type : "POST",
				data : datatosend,
				error: function (xhr, ajaxOptions, thrownError) {
					alert('Response - '+ xhr.status+':  '+thrownError+' Please contact to development department.');
					hide_loader($('#panel_container'));
				},
				success: function( response, textStatus, jqXHR ){
					hide_loader($('#panel_container'));
					$('#client_id').html(response.data.client_options);
					$('#client_id').prop('disabled', false);
				}
			})
		}else{
			$('#client_id').html('<option value="" selected="selected">Select Customer</option>');
			$('#client_id').prop('disabled', true);
		}
	});
	
	$('.save_outside_sale_data').on('click', function(){
		var datatosend = $('#outside_sale_form').serialize();
		$.ajax({
			url : site_url+'dailysale/ajax_outside_sale_data', 
			type : "POST",
			beforeSend: function(){
							show_loader($('#panel_container'));
						},
			data : datatosend,
			success: function(response, status, xhr){
				if(status == "success"){
					var ct = xhr.getResponseHeader("content-type") || "";
					if (ct.indexOf('json') > -1) {
						//If response is json
					  	if(response.status == 'success'){
							document.location.href = site_url+'dailysale/outside/edit/'+response.data;
						}else{
							hide_loader($('#panel_container'));
							$('.ajax-response').html(response.message);
						}
					}else{
						//If response is not json
						hide_loader($('#panel_container'));
						alert('Invalid response type, Please reload the page and try again.');
					}
				}else{
					hide_loader($('#panel_container'));
					alert('Response failed, Please reload the page and try again.');
				}
			},
			error: function(){
				hide_loader($('#panel_container'));
				alert('There is some error, Please reload the page and try again.');
			}		
		});
	});	
	
	$('.outside_edit_mode').on('click', function(){
		var urll = $(this).data('url');
		var datatosend = {};
		datatosend[csrf_token_name] = csrf_token_value;
		$.ajax({
			url : urll, 
			type : "POST",
			beforeSend: function(){show_loader($('#panel_container'));},
			data : datatosend,
			success: function(response, status, xhr){
				if(status == "success"){
					var ct = xhr.getResponseHeader("content-type") || "";
					if (ct.indexOf('json') > -1) {
						//If response is json
					  	if(response.status == 'success'){
							$('.panel-sale').html(response.data);
							hide_loader($('#panel_container'));
						}
					}else{
						//If response is not json
						hide_loader($('#panel_container'));
						alert('Invalid response type, Please reload the page and try again.');
					}
				}else{
					hide_loader($('#panel_container'));
					alert('Response failed, Please reload the page and try again.');
				}
			},
			error: function(){
				hide_loader($('#panel_container'));
				alert('There is some error, Please reload the page and try again.');
			}		
		});
	});	
	
	$('#switch_btn').change(function() {
		const container = document.querySelector('.panel-scroll');
		container.scrollTop = 0;
		var t_box_qty = t_carret_wt = t_disp_wt = t_gross_wt = t_net_wt = t_amount = 0;
		if($(this).prop('checked')){
			//'show all';
			$('#box_listing_container').find('.box_unchecked').fadeIn('slow');
			$('.box_row').each(function(index, element) {
                var box_row = $(element);
				box_row.find('.box_qty').each(function(index, element) {
					var this_val = $(this).val();
					if(isNaN(this_val) || this_val == ""){this_val = 0.00;}
					t_box_qty += parseFloat(this_val);
                });
				box_row.find('.carret_wt').each(function(index, element) {
					var this_val = $(this).val();
					if(isNaN(this_val) || this_val == ""){this_val = 0.00;}
					t_carret_wt += parseFloat(this_val);
                });
				box_row.find('.disp_wt').each(function(index, element) {
					var this_val = $(this).val();
					if(isNaN(this_val) || this_val == ""){this_val = 0.00;}
					t_disp_wt += parseFloat(this_val);
                });
				box_row.find('.gross_wt').each(function(index, element) {
					var this_val = $(this).val();
					if(isNaN(this_val) || this_val == ""){this_val = 0.00;}
					t_gross_wt += parseFloat(this_val);
                });
				box_row.find('.net_wt').each(function(index, element) {
					var this_val = $(this).val();
					if(isNaN(this_val) || this_val == ""){this_val = 0.00;}
					t_net_wt += parseFloat(this_val);
                });
				box_row.find('.amount').each(function(index, element) {
					var this_val = $(this).val();
					if(isNaN(this_val) || this_val == ""){this_val = 0.00;}
					t_amount += parseFloat(this_val);
                });
            });
		}else{
			//'show only checked';
			$('#box_listing_container').find('.box_unchecked').fadeOut('slow');
			$('.box_checked').each(function(index, element) {
                var box_checked = $(element);
				
				box_checked.find('.box_qty').each(function(index, element) {
					var this_val = $(this).val();
					if(isNaN(this_val) || this_val == ""){this_val = 0.00;}
					t_box_qty += parseFloat(this_val);
                });
				box_checked.find('.carret_wt').each(function(index, element) {
					var this_val = $(this).val();
					if(isNaN(this_val) || this_val == ""){this_val = 0.00;}
					t_carret_wt += parseFloat(this_val);
                });
				box_checked.find('.disp_wt').each(function(index, element) {
					var this_val = $(this).val();
					if(isNaN(this_val) || this_val == ""){this_val = 0.00;}
					t_disp_wt += parseFloat(this_val);
                });
				box_checked.find('.gross_wt').each(function(index, element) {
					var this_val = $(this).val();
					if(isNaN(this_val) || this_val == ""){this_val = 0.00;}
					t_gross_wt += parseFloat(this_val);
                });
				box_checked.find('.net_wt').each(function(index, element) {
					var this_val = $(this).val();
					if(isNaN(this_val) || this_val == ""){this_val = 0.00;}
					t_net_wt += parseFloat(this_val);
                });
				box_checked.find('.amount').each(function(index, element) {
					var this_val = $(this).val();
					if(isNaN(this_val) || this_val == ""){this_val = 0.00;}
					t_amount += parseFloat(this_val);
                });
            });
		}
		//$('.t_box_qty').val(t_box_qty.toFixed(2));
		$('.t_carret_wt').val(t_carret_wt.toFixed(2));
		$('.t_disp_wt').val(t_disp_wt.toFixed(2));
		$('.t_gross_wt').val(t_gross_wt.toFixed(2));
		$('.t_net_wt').val(t_net_wt.toFixed(2));
		$('.t_amount').val(t_amount.toFixed(2));
		
    })
	
	$('.box_check').change(function(){
		if($(this).prop('checked')){
			$(this).closest('.box_row').addClass('box_checked info').removeClass('box_unchecked');
		}else{
			$(this).closest('.box_row').removeClass('box_checked info').addClass('box_unchecked');
		}
	});
	
	$(".gross_wt").on('change', function(){
		var tr 			= $(this).closest('tr.box_item_row');
		calculate_total(tr);
	});
	
	$(".rate").on('change', function(){
		var tr 			= $(this).closest('tr.box_item_row');
		calculate_total(tr);
	});
	
	//Save outside items
	$('.save_sale').on('click', function(){
		var $this = $(this);
		var checked_boxes = $('input.box_check:checked').length;
		if(checked_boxes > 0){
			$this.prop('disabled', true);
			var validForm = 1;
			var datatosend = {};
			datatosend[csrf_token_name] = csrf_token_value;
			datatosend['action_mode'] 	= $('#action_mode').val();
			datatosend['sale_id'] 		= $('#sale_id').val();
			datatosend['client_id'] 	= $('#client_id').val();
			datatosend['sale_date'] 	= $('#sale_date').val();
			datatosend['market_id'] 	= $('#market_id').val();
			box_number 					= [];
			fish_code 					= [];
			fish_quantity 				= [];
			fish_weight 				= [];
			gross_wt					= [];
			net_wt						= [];
			rate						= [];
			amount						= [];
			
			$('.box_checked').each(function(index, element) {
                var box_checked 	= $(element);
				var box  			= $(element).find('.box_number').val();
				var box_item_row	= $(element).find('.box_item_row');
				
				box_item_row.each(function(index, element) {
					if($(element).find('.gross_wt').val() == ""){validForm = 0};
					if($(element).find('.rate').val() == ""){validForm = 0};
                    box_number.push(box);
					fish_code.push($(element).find('.fish_code').val());
					fish_quantity.push($(element).find('.fish_qty').val());
					fish_weight.push($(element).find('.box_wt').val());
					gross_wt.push($(element).find('.gross_wt').val());
					net_wt.push($(element).find('.net_wt').val());
					rate.push($(element).find('.rate').val());
					amount.push($(element).find('.amount').val());
                });
            });
			
			datatosend['box_number'] 	= box_number;
			datatosend['fish_code'] 	= fish_code;
			datatosend['fish_quantity'] = fish_quantity;
			datatosend['fish_weight'] 	= fish_weight;
			datatosend['gross_wt'] 		= gross_wt;
			datatosend['net_wt'] 		= net_wt;
			datatosend['rate'] 			= rate;
			datatosend['amount'] 		= amount;
			
			if(validForm){
				$.ajax({
					url : site_url+'dailysale/ajax_save_outside_sale_item', 
					type : "POST",
					beforeSend: function(){$('.ajax-response').html(''); show_loader($('#panel_container'));},
					data : datatosend,
					success: function(response, status, xhr){
						hide_loader($('#panel_container'));
						if(status == "success"){
							var ct = xhr.getResponseHeader("content-type") || "";
							if (ct.indexOf('json') > -1){
								//If response is json
								if(response.status == 'success'){
									$('.box_check').prop('checked', false);
									$('.box_check').trigger('change');
									$('#box_listing_container').find('.box_unchecked').fadeOut('slow');
									$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-success"><button data-dismiss="alert" class="close">×</button> '+response.message+'</div>'}}, message:''});
									if(response.redirect_url){
										setTimeout(function(){window.location.href = response.redirect_url;}, 100);
									}
								}else{
									$this.prop('disabled', false);
									$('.ajax_item_reponse').html(response.message);
								}
							}else{
								//If response is not json
								hide_loader($('#panel_container'));
								$this.prop('disabled', false);
								alert('Invalid response type, Please reload the page and try again.');
							}
						}else{
							$this.prop('disabled', false);
							alert('Response failed, Please reload the page and try again.');
						}
					},
					error: function(){
						hide_loader($('#panel_container'));
						$this.prop('disabled', false);
						alert('There is some error, Please reload the page and try again.');
					}		
				});
			}else{
				$this.prop('disabled', false);
				alert('Please insert valid fields before save.');
			}
		}else{
			$this.prop('disabled', false);
			alert('Please check atleast one item.');
		}
	});
	
});

function calculate_total(tr){
	var gross_wt 	= parseFloat(tr.find('.gross_wt').val());
	var commission 	= parseFloat($(document).find('#commission').val());
	if(isNaN(gross_wt) || gross_wt == ""){gross_wt = parseFloat(0);}
	if(isNaN(commission) || commission == ""){commission = parseFloat(0);}
	var net_wt 		= gross_wt - (gross_wt*(commission/100));
	tr.find('.net_wt').val(net_wt.toFixed(3));
	
	var rate 		= parseFloat(tr.find('.rate').val());
	var net_wt  	= parseFloat(tr.find('.net_wt').val());
	if(isNaN(rate) || rate == ""){rate = parseFloat(0);}
	if(isNaN(net_wt) || net_wt == ""){net_wt = parseFloat(0);}
	var amount 		= net_wt * rate;
	tr.find('.amount').val(amount.toFixed(2));
	
	//Calculate summary totals
	
	var t_gross_wt = 0;
	$('.gross_wt').each(function(index, element) {
		var this_gw = parseFloat($(this).val());
		if(isNaN(this_gw) || this_gw == ""){this_gw = 0.00;}
		t_gross_wt += parseFloat(this_gw);
	});
	$('.t_gross_wt').val(t_gross_wt.toFixed(3));
	
	var t_net_wt = 0;
	$('.net_wt').each(function(index, element) {
		var this_nw = parseFloat($(this).val());
		if(isNaN(this_nw) || this_nw == ""){this_nw = 0.00;}
		t_net_wt += parseFloat(this_nw);
	});
	$('.t_net_wt').val(t_net_wt.toFixed(3));
	
	var t_amount = 0;
	$('.amount').each(function(index, element) {
		var this_amount = parseFloat($(this).val());
		if(isNaN(this_amount) || this_amount == ""){this_amount = 0.00;}
		t_amount += parseFloat(this_amount);
	});
	$('.t_amount').val(t_amount.toFixed(2));
	
}














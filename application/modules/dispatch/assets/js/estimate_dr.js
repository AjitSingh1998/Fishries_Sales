$(document).ready(function(e) {
	
	$('body').on('change', '.estimate_by', function(){
		var estimate_by = $(this).val();
		var datatosend = {};
		datatosend[csrf_token_name] = csrf_token_value;		
		datatosend['estimate_by'] = estimate_by;
		datatosend['dr_number'] = $('input[name="dr_number"]').val();
		datatosend['dispatch_id'] = $('input[name="dispatch_id"]').val();
		datatosend['action_mode'] = $('input[name="action_mode"]').val();
		$.ajax({
			type: "POST",
			url: site_url+"dispatch/ajax_estimate_by",
			beforeSend: function(){$('.ajax-response').html(''); show_loader($('#panel_container'));},
			data: datatosend,
			success: function(response, status, xhr){
					hide_loader($('#panel_container'));
					if(status == "success"){
						var ct = xhr.getResponseHeader("content-type") || "";
						if (ct.indexOf('json') > -1){
							//If response is json
							if(response.status == 'success'){
								$('.div_estimate_by').html(response.data);
								
								var panelScroll = $('.div_estimate_by').find(".panel-scroll");
							    if (panelScroll.length) {
									 panelScroll.perfectScrollbar({
									 suppressScrollX : true
									 });
							    }
								//$('.div_estimate_by').find(".panel-scroll").scrollTop( $( ".panel-scroll" ).prop( "scrollHeight" ) );
								//$('.div_estimate_by').find(".panel-scroll").perfectScrollbar('update');
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
			error: function (xhr, ajaxOptions, thrownError) {
				hide_loader($('#panel_container'));
				alert('Response - '+ xhr.status+':  '+thrownError+' Please contact to development department.');
			}									
		});
	});
	
	$('body').on('click', '.save_estimate_by_box', function(){
		var estimate_row = $(this).closest('.estimate_row');
		var form_data = {};
		form_data[csrf_token_name] 	 = csrf_token_value;
		form_data['item_id'] 		 = estimate_row.find('input[name="item_id"]').val();
		form_data['estimated_price'] = estimate_row.find('input[name="estimated_price"]').val();
		form_data['box_number'] 	 = estimate_row.find('input[name="box_number"]').val();
		form_data['dr_number']		 = $('input[name="dr_number"]').val();
		
		//var estimated_price = estimate_row.find('.estimated_price').val();
		if(form_data['estimated_price'] > 0){
			show_loader($('#panel_container'));
			$.ajax({
				type: "POST",
				url: site_url+"dispatch/ajax_estimate_by_box",
				data: form_data,
				error: function (xhr, ajaxOptions, thrownError) {
					hide_loader($('#panel_container'));
					alert('Response - '+ xhr.status+':  '+thrownError+' Please contact to development department.');
				},
				success: function( data ){
					if(data.status == 'success'){
						estimate_row.find('.estimated_price').prop('disabled', true);
						estimate_row.find('.save_estimate_by_box').text('Edit').removeClass('btn-success save_estimate_by_box').addClass('btn-warning edit_estimate_by_box');
						$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-success"><button data-dismiss="alert" class="close">×</button> '+data.message+'</div>'}}, message:''});
					}else if(data.status == 'failed'){
						$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> '+data.message+'</div>'}}, message:''});
					}else{
						$('.ajax_reponse').html(data.message);
					}
					setTimeout(function(){hide_loader($('#panel_container'));}, 300);
				}						
			});
		}else{
			alert('Estimated price must be greater than 0.');
			estimate_row.find('.estimated_price').focus();
		}
	});
	
	$('body').on('click', '.edit_estimate_by_box', function(){
		var estimate_row = $(this).closest('.estimate_row');
		estimate_row.find('.estimated_price').prop('disabled', false);
		estimate_row.find('.edit_estimate_by_box').text('Update').removeClass('btn-warning edit_estimate_by_box').addClass('btn-info save_estimate_by_box');
		estimate_row.find('.estimated_price').focus();
	});
	
	$('body').on('click', '.save_estimate_by_fish', function(){
		var estimate_row = $(this).closest('.estimate_row');
		var form_data = {};
		form_data['dispatch_id'] = $('input[name="dispatch_id"]').val();
		form_data['dr_number'] = $('input[name="dr_number"]').val();
		form_data['estimated_price'] = estimate_row.find('input[name="estimated_price"]').val();
		form_data['fish_code'] = estimate_row.find('input[name="fish_code"]').val();
		form_data['fish_qty'] = estimate_row.find('input[name="fish_qty"]').val();
		form_data[csrf_token_name] = csrf_token_value;		
		
		var estimated_price = estimate_row.find('.estimated_price').val();
		if(estimated_price > 0){
			show_loader($('#panel_container'));
			$.ajax({
				type: "POST",
				url: site_url+"dispatch/ajax_estimate_by_fish",
				data: form_data,
				error: function (xhr, ajaxOptions, thrownError) {
					hide_loader($('#panel_container'));
					alert('Response - '+ xhr.status+':  '+thrownError+' Please contact to development department.');
				},
				success: function( data ){
					if(data.status == 'success'){
						estimate_row.find('.estimated_price').prop('disabled', true);
						estimate_row.find('.save_estimate_by_fish').text('Edit').removeClass('btn-success save_estimate_by_fish').addClass('btn-warning edit_estimate_by_fish');
						$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-success"><button data-dismiss="alert" class="close">×</button> '+data.message+'</div>'}}, message:''});
					}else if(data.status == 'failed'){
						$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> '+data.message+'</div>'}}, message:''});
					}else{
						$('.ajax_reponse').html(data.message);
					}
					setTimeout(function(){hide_loader($('#panel_container'));}, 300);
				}						
			});
		}else{
			alert('Estimated price must be greater than 0.');
			estimate_row.find('.estimated_price').focus();
		}
	});
	
	$('body').on('click', '.edit_estimate_by_fish', function(){
		var estimate_row = $(this).closest('.estimate_row');
		estimate_row.find('.estimated_price').prop('disabled', false);
		estimate_row.find('.edit_estimate_by_fish').text('Update').removeClass('btn-warning edit_estimate_by_fish').addClass('btn-info save_estimate_by_fish');
		estimate_row.find('.estimated_price').focus();
	});

	$(document).on('click', '.print_data', function(){
		var target = $(this).attr('data-target');
		if (typeof target !== typeof undefined && target !== false) {
			var printhtml = $(target).clone(true);
			printhtml.find('.panel-scroll').css("cssText", "width:100%; height: auto !important; overflow: visible !important;");
			printhtml.print();			
		}else{
			$("table.table").print();
		}
	});
	
	$('#expand_report_container').change(function() {
		var target = $(this).data('target');
		if($(this).prop('checked')){
			$(target).find('.panel-scroll').css("cssText", "width:100%; height: auto !important; overflow: visible !important;");
		}else{
			$(target).find('.panel-scroll').css("cssText", "width:100%; height: 300px !important; overflow: hidden !important;");
		}
    });

	$(document).on('click', '#save_dhalta', function(){		
		var dispatch_id = $('input[name="dispatch_id"]').val();
		var dr_number = $('input[name="dr_number"]').val();
		var dhalta = $('#dhalta').val();
		var commission = $('#commission').val();
		var expenses = $('#expenses').val();
		var formdata = {dispatch_id:dispatch_id, dr_number:dr_number, dhalta:dhalta, commission:commission, expenses:expenses};
		formdata[csrf_token_name] = csrf_token_value;
		
		$.ajax(site_url + 'dispatch/ajax_save_dhalta', {
			type:'POST',
			data: formdata,
			success:function(data){				
				$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-'+data.status+'"><button data-dismiss="alert" class="close">×</button> '+data.message+'</div>'}}, message:''});			
				console.log(data);
			}
		});
	});

	
});
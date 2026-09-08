//allow only two places after decimal point
function checkDecimal(el){
	var ex = /^\d*(\.\d{0,3})?$/;
	if(ex.test(el.value)==false){
		el.value = parseInt(el.value*1000)/1000; 
		//el.value.substring(0,el.value.length - 1);
	}
}

function calculate_total_pt_wt(point_wt, forfeiture_wt, forfeiture_rt_wt){
	if(isNaN(point_wt)){point_wt = parseFloat(0.00);}
	if(isNaN(forfeiture_wt)){forfeiture_wt = parseFloat(0.00);}
	if(isNaN(forfeiture_rt_wt)){forfeiture_rt_wt = parseFloat(0.00);}
	
	var total_pt_wt = parseFloat(point_wt+forfeiture_wt+forfeiture_rt_wt).toFixed(3);
	$('input[name="total_pt_wt"]').val(total_pt_wt);
	
	var total_wt = $('input[name="total_wt"]').val();
}

$(document).ready(function(e) {
	
	$('#expand_report_container').change(function() {
		var target = $(this).data('target');
		if($(this).prop('checked')){
			$(target).find('.panel-scroll').css("cssText", "width:100%; height: auto !important; overflow: visible !important;");
			$(target).find(".panel-scroll").perfectScrollbar('destroy');
		}else{
			$(target).find('.panel-scroll').css("cssText", "width:100%; height: 300px !important; overflow: hidden !important;");
			var panelScroll = $(target).find(".panel-scroll");
			if (panelScroll.length) {
				 panelScroll.perfectScrollbar({
				 suppressScrollX : true
				 });
			}
		}
    });

	$('.print_data').on('click', function(){
		var target = $(this).attr('data-target');
		if (typeof target !== typeof undefined && target !== false) {
			var printhtml = $(target).clone(true);
			printhtml.find('.panel-scroll').css("cssText", "width:100%; height: auto !important; overflow: visible !important;");
			printhtml.print();			
		}else{
			$("table.table").print();
		}
	});
	
	$(document).on('focus click', '.datepicker', function(){
		$(this).datepicker({
			format: 'dd/mm/yyyy',
			autoclose: true,
			todayHighlight: true
		});
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
	
	$(document).on('change', 'input[name="point_wt"]', function(){
		var point_wt 			= parseFloat($(this).val());
		var forfeiture_wt 		= parseFloat($('input[name="forfeiture_wt"]').val());
		var forfeiture_rt_wt 	= parseFloat($('input[name="forfeiture_rt_wt"]').val());
		
		if(isNaN(point_wt) || point_wt == ""){point_wt = parseFloat(0.00); $(this).val("0.00");}
		if(isNaN(forfeiture_wt)){forfeiture_wt = parseFloat(0.00);}
		if(isNaN(forfeiture_rt_wt)){forfeiture_rt_wt = parseFloat(0.00);}
		
		calculate_total_pt_wt(point_wt, forfeiture_wt, forfeiture_rt_wt);
	});
	
	$(document).on('change', 'input[name="forfeiture_wt"]', function(){
		var point_wt 			= parseFloat($('input[name="point_wt"]').val());
		var forfeiture_wt 		= parseFloat($(this).val());
		var forfeiture_rt_wt 	= parseFloat($('input[name="forfeiture_rt_wt"]').val());
		
		if(isNaN(point_wt)){point_wt = parseFloat(0.00);}
		if(isNaN(forfeiture_wt || forfeiture_wt == "")){forfeiture_wt = parseFloat(0.00); $(this).val("0.00");}
		if(isNaN(forfeiture_rt_wt)){forfeiture_rt_wt = parseFloat(0.00);}
		
		calculate_total_pt_wt(point_wt, forfeiture_wt, forfeiture_rt_wt);
	});
	
	$(document).on('change', 'input[name="forfeiture_rt_wt"]', function(){
		var point_wt 			= parseFloat($('input[name="point_wt"]').val());
		var forfeiture_wt 		= parseFloat($('input[name="forfeiture_wt"]').val());
		var forfeiture_rt_wt 	= parseFloat($(this).val());
		
		if(isNaN(point_wt)){point_wt = parseFloat(0.00);}
		if(isNaN(forfeiture_wt)){forfeiture_wt = parseFloat(0.00);}
		if(isNaN(forfeiture_rt_wt || forfeiture_rt_wt == "")){forfeiture_rt_wt = parseFloat(0.00); $(this).val("0.00");}
		
		calculate_total_pt_wt(point_wt, forfeiture_wt, forfeiture_rt_wt);
	});
	
	$(document).on('click', '.save_data',  function(){
		var form_url = $(this).data('url');
		var datatosend = $('.dp_form').serialize();
		$.ajax({
			url : form_url, 
			type : "POST",
			beforeSend: function(){show_loader($('#panel_container'));},
			data : datatosend,
			success: function(response, status, xhr){
				if(status == "success"){
					var ct = xhr.getResponseHeader("content-type") || "";
					if (ct.indexOf('json') > -1) {
						//If response is json
					  	if(response.status == 'success'){
							document.location.href = response.redirect_url;
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
	
	$('.dp_edit_mode').on('click', function(){
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
							$('.dp_heading').html(response.data);
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
		
});
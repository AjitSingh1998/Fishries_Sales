$(document).ready(function(e) {
	$('.edit_transfer').click(function(){
		var urll = $(this).data('url');
		var datatosend = {};
		datatosend[csrf_token_name] = csrf_token_value;
		$.ajax({
			type: "POST",
			url: urll,
			beforeSend: function(){$('.ajax-response').html(''); show_loader($('#panel_container'));},
			data: datatosend,
			success: function(response, status, xhr){
					if(status == "success"){
						var ct = xhr.getResponseHeader("content-type") || "";
						if (ct.indexOf('json') > -1){
							//If response is json
							if(response.status == 'success'){
								$('.panel').html(response.data);
							}
							hide_loader($('#panel_container'));
							$(".panel-scroll").perfectScrollbar('update');
							$(".panel-scroll").perfectScrollbar();
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
	
});
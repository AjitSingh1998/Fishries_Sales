
function add_box(form_data, s_no){
	$.ajax({
		type: "POST",
		url: site_url+"dispatch/ajax_add_dr_box/"+s_no,
		data: form_data,
		beforeSend: function(){show_loader($('#panel_container'));},
		success: function( data ){
			$('.ajax_box_reponse').html('');
			$(".panel-scroll").scrollTop( $( ".panel-scroll" ).prop( "scrollHeight" ) );
			$(".panel-scroll").perfectScrollbar('update');
			if(data.status == 'success'){
				$('#box_listing_container').append(data.data);
				$('input[name="box_number"]').val('');
				$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-success"><button data-dismiss="alert" class="close">×</button> '+data.message+'</div>'}}, message:''});
			}else if(data.status == 'failed'){
				$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> '+data.message+'</div>'}}, message:''});
			}else if(data.status == 'error'){
				$('.ajax_box_reponse').html(data.message);				
			}
			/*if(data.redirect_url != ''){
				setTimeout(function(){window.location.href = data.redirect_url;}, 3000);
			}*/
			setTimeout(function(){hide_loader($('#panel_container'));}, 500);
			$('input[name="box_number"]').focus();
		},
		error: function (xhr, ajaxOptions, thrownError) {
			hide_loader($('#panel_container'));
			alert('Response - '+ xhr.status+':  '+thrownError+' Please contact to development department.');
		}								
	});
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
	
	$('textarea').each(function() {
		$(this).height($(this).prop('scrollHeight'));
	});
	
	$('input[name="total_freight"], input[name="advance_freight"]').on('keyup', function(){
		var total_freight = $('input[name="total_freight"]').val() || 0;
		var advance_freight = $('input[name="advance_freight"]').val() || 0;
		var rf = total_freight-advance_freight;
		$('input[name="remaining_freight"]').val(parseInt(rf*100)/100);
	});
	
	$('body').on('click', '.save_dispatch_info', function(){
		show_loader($('#panel_container'));
		var form_data = $('#dispatch_form').serialize();	
		$.ajax({
			type: "POST",
			url: site_url+"dispatch/ajax_save_dr_info",
			data: form_data,
			error: function (xhr, ajaxOptions, thrownError) {
				hide_loader($('#panel_container'));
				alert('Response - '+ xhr.status+':  '+thrownError+' Please contact to development department.');
				
			},
			success: function( data ){
				if(data.status == 'success'){
					var dispatch_id = data.data;
					$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-success"><button data-dismiss="alert" class="close">×</button> '+data.message+'</div>'}}, message:''});
					if(data.redirect_url != ''){
						window.location.href = data.redirect_url;
					}
				}else if(data.status == 'failed'){
					$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> '+data.message+'</div>'}}, message:''});
				}else if(data.status == 'error'){
					$('.ajax_reponse').html(data.message);
				}
				setTimeout(function(){hide_loader($('#panel_container'));}, 500);
			}						
		});	
	});
	
	$('input[name="box_number"]').on("keypress", function(event) {
		var keycode = event.keyCode || event.which;
		if(keycode == '13') {
			event.preventDefault();
			var form_data = $('#add_box_form').serialize();
			var s_no = $('#box_listing_container').children().length;
			add_box(form_data, s_no);	
		}
	});
	
	$('.add_box').on('click', function(e){
		var form_data = $('#add_box_form').serialize();
		var s_no = $('#box_listing_container').children().length;	
		add_box(form_data, s_no);		
	});
	
	$('body').on('click', '.remove_box', function(){
		var box_number = $(this).data('box_number');
		var conf = confirm('Are you sure to delete box '+box_number+' ?');
		if(conf){
			var tr = $(this).closest('tr');
			var dispatch_id = $('input[name="dispatch_id"]').val();
			var dr_number = $('input[name="dr_number"]').val();
			if(box_number){
				var form_data = {'box_number': box_number, 'dispatch_id': dispatch_id, 'dr_number': dr_number};
				form_data[csrf_token_name] = csrf_token_value;
				$.ajax({
					type: "POST",
					url: site_url+"dispatch/ajax_remove_dr_box",
					data: form_data,
					beforeSend: function(){show_loader($('#panel_container'));},
					error: function (xhr, ajaxOptions, thrownError) {
						alert('Response - '+ xhr.status+':  '+thrownError+' Please contact to development department.');
						hide_loader($('#panel_container'));
					},
					success: function( data ){
						$('.ajax_box_reponse').html('');
						if(data.status == 'success'){
							tr.remove();
							var s_no = $('#box_listing_container').children().length;
							if(s_no){
								var i = 1;
								$('tr.box_row').each(function(index, element) {
									var ele = $(element);
									ele.find('.s_no').text(i);
									i++;
								});
							}
							$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-success"><button data-dismiss="alert" class="close">×</button>'+data.message+'</div>'}}, message:''});
						}else if(data.status == 'failed'){
							$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button>'+data.message+'</div>'}}, message:''});
						}else if(data.status == 'error'){
							$('.ajax_box_reponse').html(data.message);				
						}
						$('input[name="box_number"]').val('');
						setTimeout(function(){hide_loader($('#panel_container'));}, 500);
					}						
				});	
			}
		}
	});
	
	$('.edit_dr_detail').on('click', function(){
		$('#dispatch_form').removeClass('hidden');
		$(this).hide();
	});
	$('.cancel_edit_dr_detail').on('click', function(){
		$('#dispatch_form').addClass('hidden');
		$('.edit_dr_detail').show();
	});
	
});
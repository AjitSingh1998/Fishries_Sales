

$(document).ready(function(e) {
	
	$('.mark_all').on('change', function(){
		if($(this).prop('checked')){
			$('#box_listing_container').find('.boxes').prop('checked', true);
			$('.transfer_btn').prop('disabled', false);
		}else{
			$('#box_listing_container').find('.boxes').prop('checked', false);
			$('.transfer_btn').prop('disabled', true);
		}
	});
	
	$('.boxes').on('change', function(){
		var atleast_one_checked = false;
		$('#box_listing_container').find('.boxes').each(function(index, element) {
            var ele = $(element);
			if(ele.prop('checked')){
				atleast_one_checked = true;
			}
        });
		
		if(!atleast_one_checked){
			$('.transfer_btn').prop('disabled', true);
		}else{
			$('.transfer_btn').prop('disabled', false);
		}
	});
	
	$('input[name="total_freight"], input[name="advance_freight"]').on('keyup', function(){
		var total_freight = $('input[name="total_freight"]').val() || 0;
		var advance_freight = $('input[name="advance_freight"]').val() || 0;
		var rf = total_freight-advance_freight;
		$('input[name="remaining_freight"]').val(parseInt(rf*100)/100);
	});
	
	$('.transfer_btn').on('click', function(){
		var $this = $(this);
		$this.prop('disabled', true);
		show_loader($('#panel_container'));
		var urll = $(this).data('url');
		var form_data = $('#transfer_form').serialize();	
		$.ajax({
			type: "POST",
			url: urll,
			data: form_data,
			error: function (xhr, ajaxOptions, thrownError) {
				hide_loader($('#panel_container'));
				alert('Response - '+ xhr.status+':  '+thrownError+' please contact to development department.');
			},
			success: function( response ){
				if(response.status == 'success'){
					$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-success"><button data-dismiss="alert" class="close">×</button> '+response.message+'</div>'}}, message:''});
					if(response.redirect_url != ''){
						setTimeout(function(){window.location.href = response.redirect_url;}, 2000);
					}
				}else if(response.status == 'failed'){
					$this.prop('disabled', false);
					$('.ajax_reponse').html('<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> '+response.message+'</div>');
				}else{
					$this.prop('disabled', false);
					window.scrollTo(0, 0);
					$('.ajax_reponse').html(response.message);
				}
				setTimeout(function(){hide_loader($('#panel_container'));}, 500);
			}						
		});	
	});
});
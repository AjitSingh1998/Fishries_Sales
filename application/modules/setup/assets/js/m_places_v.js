$(document).ready(function(e) {	
	$('.market_type').on('change', function(){
		$this = $(this);
		var market_type = $this.val();
		if(market_type != '' && market_type != 'undefined'){
			var datatosend = {'market_type' : market_type};
			datatosend[csrf_token_name] = csrf_token_value;
			$.ajax({
				url : site_url+'setup/ajax_get_market_category', 
				type : "POST",
				beforeSend: function(){show_loader($('#panel_container'));},
				data : datatosend
			}).done(function(response){
				$('.market_category').prop('disabled', false);
				$('.market_category').val('').trigger("change");
				$('.market_category').html(response.data);
			});
		}else{
			$('.market_category').val('').trigger("change");
			$('.market_category').html('<option value="" selected="selected">Select Category</option>');
			$('.market_category').prop('disabled', true);
		}
	});
});
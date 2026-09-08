// JavaScript Document
$(document).ready(function() {
	$('body').off('change', '#market_type');
	
	//On change primary fisher main group type
	var city = $('#city');
	var market_type = $('#market_type');
	market_type.on('change', function(){
		$this = $(this);
		var market_type = $this.val();
		if(market_type != '' && market_type != 'undefined'){
			var datatosend = {'market_type' : market_type};
			datatosend[csrf_token_name] = csrf_token_value;
			$.ajax({
				url : site_url+'setup/ajax_get_market_places', 
				type : "POST",
				data : datatosend
			}).done(function(response){
				city.prop('disabled', false);
				city.html(response.data);
			});
		}else{
			city.val('');
			city.html('<option value="">Select Market</option>');
			city.prop('disabled', true);
		}
	});
	
});
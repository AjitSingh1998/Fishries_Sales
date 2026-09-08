//allow only two places after decimal
function checkDecimal(el){
	var ex = /^\d*(\.\d{0,2})?$/;
	if(ex.test(el.value)==false){
		el.value = parseInt(el.value*100)/100;
		//el.value = el.value.substring(0,el.value.length - 1);
	}
}

$(document).ready(function(e) {
	$(document).on('focus click', '.datetimepicker', function(){
		$(this).datetimepicker({
			format: 'DD/MM/YYYY hh:mm a',
			sideBySide: true
		});
	});
	
	/*
	$('.datepicker').datepicker({
		format: 'dd/mm/yyyy',
		autoclose: true,
		todayHighlight: true
	});
	*/
	
	//Strict input box to accept only numeric(with or without floting point) values
	$(document).on("keypress keyup blur change", '.strict_numeric', function (event) {
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
});
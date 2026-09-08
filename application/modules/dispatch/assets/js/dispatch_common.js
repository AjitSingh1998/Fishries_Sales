//allow only two places of decimal
function checkDecimal(el){
	var ex = /^\d*(\.\d{0,3})?$/;
	if(ex.test(el.value)==false){
		el.value = parseInt(el.value*1000)/1000;
		//el.value.substring(0,el.value.length - 1);
	}
}

$(document).ready(function(e) {
	
	if($.fn.datepicker){
		$('.datepicker').datepicker({
			format: 'dd/mm/yyyy',
			autoclose: true,
			todayHighlight: true
		});
	}
	
	if($.fn.datetimepicker){
		$('.datetimepicker').datetimepicker({
			format: 'DD/MM/YYYY hh:mm a',
			sideBySide: true,
		});
	}
	
	//Strict input box to accept only numeric(with floting point) values
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
	
	$('.print_data').on('click', function(){
		var print_data = $('<div></div>');
		print_data.append($('#header_table').clone());
		print_data.append($('#container_table').clone());
		if($.fn.print){
			print_data.print({
				noPrintSelector: ".no-print"
			});
		} else {
			window.print();
		}
	});
	
	
	$('.export_data').click(function(){
		var target = $(this).attr('data-target');
		var filename = $(this).attr('data-filename') || 'Report';
		var target_clone = $('#'+target).clone();
		target_clone.find('.no-exl').remove();
		tableToExcel(target_clone[0], 'Sheet 1', filename)
	});
	
	/*
	$('.export_data').click(function(){
		var report_name = $(this).data('filename');
		var target = $(this).attr('data-target');
		if (typeof target !== typeof undefined && target !== false) {
			$(target).table2excel({
				filename: report_name
			});
		}else{
			$("table.table").table2excel({
				filename: report_name
			});
		}
	});*/
});
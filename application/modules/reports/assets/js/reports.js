$('.export_data').prop('disabled', true);
$('#export_data').prop('disabled', true);
$('.print_data').prop('disabled', true);

function calculateTableColumnSize(){
	var report_header = $('.report_table_header tr.report_header th');
	var report_body = $('.report_table_body tr.record_data_yes').children();
	var report_footer = $('.report_table_footer tr.report_footer th');
	//console.log(report_body);
	$.each(report_header, function(index, element){
		var width = $(this).width();
		$(report_body[index]).css('width',width);
		$(report_footer[index]).css('width',width);		
	});
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
	
	$('#show_all_data').change(function() {
		var target = $(this).data('target');
		if($(this).prop('checked')){
			$(target).find('tr.record_data_no').show();
		}else{
			$(target).find('tr.record_data_no').hide();
		}
    });
	
	$('body').off('click', '.loadDailyTollInfo');
	
	$('.datepicker').datepicker({
		format: 'dd/mm/yyyy',
		autoclose: true,
		todayHighlight: true
	});
	/*
	$('.datetimepicker').datetimepicker({
		format: 'DD/MM/YYYY hh:mm a',
		sideBySide: true,
	});
	*/
	$('.monthpicker').datepicker({
		format: "M-yyyy",
		viewMode: "months", 
		minViewMode: "months",
		autoclose: true,
		todayHighlight: true
	});
	
	$('.yearpicker').datepicker({
		format: "yyyy",
		viewMode: "years", 
		minViewMode: "years",
		autoclose: true,
		todayHighlight: true
	});
	
	$(".vehicle_number").select2({
	  ajax: {
		url: site_url+'reports/select2_vehicle_numbers',
		dataType: 'json',
		data: function (params) {
		  return {
			q: params.term, // search term
		  };
		},
		cache: true
	  },
	  placeholder: 'Search for vehicle numbers',
	  minimumInputLength: 1,
	  allowClear: true
	});
	
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
	
	//$('.select2').select2();
	
	$('.find_report').on('click', function(){
		var url = $(this).data('url');
		var $search_bar = $(this).closest('div.row');
		var datatosend = {};
		$search_bar.find('input').each(function(index, element) {
            var name = $(element).attr('name');
            var val = $(element).val();
			datatosend[name] = val;
        });
		$search_bar.find('select').each(function(index, element) {
			var ele = $(element);
            var name = ele.attr('name');
            var val = ele.val();
			var text_val = ele.find("option:selected").text();
			datatosend[name] = val;
			datatosend[name+'_text'] = text_val;
        });
		//getData(url, datatosend);
		datatosend[csrf_token_name] = csrf_token_value;
		$.ajax({
			url : site_url+url, 
			type : "POST",
			beforeSend: function(){
							show_loader($('#report_panel'));						
							if ( $.fn.dataTable.isDataTable('#data-table-grid') ) {
							  $('#data-table-grid').DataTable().destroy();
							}
						},
			data : datatosend,
			success: function(response, status, xhr){
				//console.log(response+'==1='); console.log(status+'==2='); console.log(xhr);
				if(status == "success"){
					var ct = xhr.getResponseHeader("content-type") || "";
					
					if (ct.indexOf('json') > -1) {
						//If response is json
					  	if(response.status == 'success'){
							$('.ajax-response').html('');
							$('#search_key').html(response.data.search_key);
							$('#search_date').html(response.data.search_date);
							$('span#from_date').html(response.data.from_date);
							$('span#to_date').html(response.data.to_date);
							$('#report_data').html(response.data.tbody);
							$('#report_summary').html(response.data.tfoot);
							if(response.data.hasOwnProperty('colspan')){
								$('#search_date').attr('colspan', response.data.colspan);
							}
							$('.export_data').prop('disabled', false);
							$('#export_data').prop('disabled', false);
							$('.print_data').prop('disabled', false);						
							
							calculateTableColumnSize();
							
							if(!$.fn.dataTable.isDataTable('#data-table-grid') ) {
								var pagination = (url == 'reports/production/ajax_samiti')? false : true;
								$('#data-table-grid').DataTable({
									paging: pagination,
									searching: pagination, 
									bInfo: pagination, 
									"autoWidth": false, 
									"lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ]
								});	
							}
						}else{
							$('#search_key').html(response.data.search_key);
							$('#search_date').html(response.data.search_date);
							$('.ajax-response').html(response.message);
							$('#report_data').html('');
							$('#report_summary').html('');
						}
						
						hide_loader($('#report_panel'));
					}else{
						//If response is not json
						hide_loader($('#report_panel'));
						alert('Invalid response type, Please reload the page and try again.');
					}
				}else{
					hide_loader($('#report_panel'));
					alert('Response failed, Please reload the page and try again.');
				}
			},
			error: function(response, status, xhr){
				hide_loader($('#report_panel'));
				alert('There is some error, Please reload the page and try again.');
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
	
	$(document).on('click', '.export_data', function(){
		
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
	});
	
	$('.market_category').on('change', function(){
		if($('.market_place').length){
			$this = $(this);
			var market_category = $this.val();
			if(market_category > 0){
				show_loader($('#report_panel'));
				if(market_category != '' && market_category != 'undefined'){
					var datatosend = {'market_category' : market_category};
					datatosend[csrf_token_name] = csrf_token_value;
					$.ajax({
						url : site_url+'reports/ajax_get_market_places', 
						type : "POST",
						beforeSend: function(){show_loader($('#panel_container'));},
						data : datatosend
					}).done(function(response){
						//$('.market_place').prop('disabled', false);
						//$('.market_place').val('').trigger("change");
						$('.market_place').html(response.data);
						hide_loader($('#report_panel'));
					});
				}
			}else{
				//$('.market_place').val('').trigger("change");
				$('.market_place').html('<option value="0" selected="selected">All</option>');
				//$('.market_place').prop('disabled', true);
			}
		}
	});
	
	
	// local sale detail reports	
	$(document).on('click', 'a.sale_detail', function(event){
		event.preventDefault();
		show_loader($('.modal_panel_container'));
		var url = site_url + $(this).data('url');	
		$('#common-modal-asset').removeClass('horizontal right').addClass('vertical bottom');
		$('#common-modal-asset .modal-body').empty();
		$('#common-modal-asset').modal('show');
		$.getJSON(url, null, function (response) {
			$('#common-modal-asset .modal-title').addClass('text-center').html(response.page_title);
			$('#common-modal-asset .modal-body').html(response.page_content);
			$('#common-modal-asset .modal-footer').hide();
			hide_loader($('.modal_panel_container'));
		});
	});
	
	
	$('.save_closing_stock').on('click', function(){
		var closing_date = $('input[name="closing_date"]').val();
		var market_id = $('select[name="market_id"]').val();
		
		if(closing_date && market_id){
			var datatosend = {closing_date:closing_date, market_id:market_id};
			datatosend[csrf_token_name] = csrf_token_value;
			$.ajax({
				url : site_url + 'production/ajax_save_closing_stock', 
				type : "POST",
				beforeSend: function(){show_loader($('#report_panel'));},
				data : datatosend,
				success: function(response, status, xhr){
					if(status == "success"){
						var ct = xhr.getResponseHeader("content-type") || "";
						if (ct.indexOf('json') > -1) {
							//If response is json
							$.toaster({settings:{timeout:5000,toast:{template:'<div class="alert alert-'+response.status+'"><button data-dismiss="alert" class="close">×</button> '+response.message+'</div>'}}, message:''});
						}else{
							alert('Invalid response type, Please reload the page and try again.');
						}
					}else{
						alert('Response failed, Please reload the page and try again.');
					}
					hide_loader($('#report_panel'));
				},
				error: function(){
					hide_loader($('#report_panel'));
					alert('There is some error, Please reload the page and try again.');
				}		
			});
		}else{ 
			alert('Please enter production date & select depot!');
			if(!closing_date){
				$('input[name="closing_date"]').focus();
			}else if(!market_id){
				$('select[name="market_id"]').focus();
			}
		}
	});	
	
});


									 
		


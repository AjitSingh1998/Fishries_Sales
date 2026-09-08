<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends MY_Controller {
	var $userID;
	var $stylesheet_array;
	var $scriptsrc_array;
	var $datatable_scripts;
	var $datatable_script_body;
	
	//$market_type : Local Market = 1, Outside Market = 2
	//market_category: 1=>Point, 2=>Depot, 3=>Outside
	function __construct(){
		parent::__construct();
		$this->userID = checkUserLogin();
		$this->load->model('reports_model', 'RM');
		$this->stylesheet_array = array( base_url('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css'),
										 base_url('assets/plugins/select2/dist/css/select2.min.css'),
										 base_url('assets/plugins/DataTables/media/css/jquery.dataTables.min.css'),
										 );
		$this->scriptsrc_array = array( base_url('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js'),
										base_url('assets/plugins/select2/dist/js/select2.full.min.js'),
										base_url('assets/js/jQuery.print.min.js'),
										base_url('assets/js/jquery.table2excel.min.js'), // work with CLass
										base_url('assets/js/table2excel.custom.js'), // work with ID
										base_url('assets/plugins/DataTables/media/js/jquery.dataTables.js'),										
										site_url('reports/assets/js/reports.js')
										);
												
		 //dtTable.destroy(); retrieve: true,	  destroy: true,	.fnDestroy()								
		$this->datatable_scripts = '<style> 
									table.dataTable thead th, table.dataTable thead td { padding: 8px !important;}
									table.dataTable tbody th, table.dataTable tbody td { padding: 1px 5px !important;}
									</style>';
	}
	
	function index(){
		redirect(site_url('dashboard'));
	}
	
	//1. Daily Production Report [get_production_data]
	function production_report(){
		$data['content_view'] = 'reports/report/production_report_v';
		$data['ajax_url'] = 'reports/ajax_production_report';
		$data['page_title'] = 'Daily Production Report';
		$data['table_class'] = 'table_table';
		$data['all_points']	= $this->RM->get_market_places(1);
		$this->template->set('stylesheet', $this->stylesheet_array);
		$this->template->set('scriptsrc', $this->scriptsrc_array);
		
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($data);
	}
	
	function ajax_production_report(){
		$data = array('status' => 'danger', 'message' => '<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> No data found, Please try again.</div>', 'data'=>'');
		$form_validation = $this->__setFormRules('ajax_production_report');
		if($form_validation){
			$post = $this->input->post();
			$production_date = get_date('Y-m-d', str_replace('/', '-', $this->input->post('production_date')));
			$market_id = $this->input->post('market_id');
			$report_data = $this->RM->get_production_data($production_date, $market_id);
			$search_date = $this->input->post('production_date');
			$search_key = $this->input->post('market_id_text');;;
			$tbody = $tfoot = '';
			if(!empty($report_data)){
				$t_point_wt = $t_ft_wt = $t_ft_rt_wt = $t_total_point_wt = $t_destroyed_wt = $t_rotten_wt = $t_depot_wt = $t_sale = $t_free_sale = $t_total_wt = $t_surplus_wt = $t_surplus_perc = $t_jhinga_pt_wt = $t_jhinga_dp_wt = '0.00';
				
				foreach($report_data as $rep){
					$total_wt = ($rep['depot_wt'] + $rep['paid_sale_wt'] + $rep['free_sale_wt']);
					$surplus_wt = ($total_wt - $rep['total_pt_wt']);
					$surplus_perc = 0;
					if($rep['total_pt_wt'] > 0){
						$surplus_perc = (($surplus_wt / $rep['total_pt_wt']) * 100);
					}
					
					$tbody .='<tr>';
					$tbody .='    <td>'.$rep['point_name'].'</td>';
					$tbody .='    <td>'.$rep['point_wt'].'</td>';
					$tbody .='    <td>'.$rep['forfeiture_wt'].'</td>';
					$tbody .='    <td>'.$rep['forfeiture_rt_wt'].'</td>';
					$tbody .='    <td>'.$rep['total_pt_wt'].'</td>';
					$tbody .='    <td>'.$rep['destroyed_wt'].'</td>';
					$tbody .='    <td>'.$rep['rotten_wt'].'</td>';
					$tbody .='    <td>'.$rep['depot_wt'].'</td>';
					$tbody .='    <td>'.$rep['paid_sale_wt'].'</td>';
					$tbody .='    <td>'.$rep['free_sale_wt'].'</td>';
					$tbody .='    <td>'.($total_wt).'</td>';
					$tbody .='    <td>'.($surplus_wt).'</td>';
					$tbody .='    <td>'.($surplus_perc).'</td>';
					$tbody .='    <td>'.$rep['jhinga_point_wt'].'</td>';
					$tbody .='    <td>'.$rep['jhinga_depot_wt'].'</td>';
					$tbody .='</tr>';
					
					$t_point_wt 		+= $rep['point_wt'];
					$t_ft_wt 			+= $rep['forfeiture_wt'];
					$t_ft_rt_wt 		+= $rep['forfeiture_rt_wt'];
					$t_total_point_wt 	+= $rep['total_pt_wt'];
					$t_destroyed_wt		+= $rep['destroyed_wt'];
					$t_rotten_wt 		+= $rep['rotten_wt'];
					$t_depot_wt 		+= $rep['depot_wt'];
					$t_sale 			+= $rep['paid_sale_wt'];
					$t_free_sale 		+= $rep['free_sale_wt'];
					$t_total_wt 		+= $total_wt;
					$t_surplus_wt 		+= $surplus_wt;
					$t_jhinga_pt_wt 	+= $rep['jhinga_point_wt'];
					$t_jhinga_dp_wt 	+= $rep['jhinga_depot_wt'];
				}
				
				$tfoot .='<th>Total Wt:</th>';
				$tfoot .='<th>'.my_number_format($t_point_wt).'</th>';
				$tfoot .='<th>'.my_number_format($t_ft_wt).'</th>';
				$tfoot .='<th>'.my_number_format($t_ft_rt_wt).'</th>';
				$tfoot .='<th>'.my_number_format($t_total_point_wt).'</th>';
				$tfoot .='<th>'.my_number_format($t_destroyed_wt).'</th>';
				$tfoot .='<th>'.my_number_format($t_rotten_wt).'</th>';
				$tfoot .='<th>'.my_number_format($t_depot_wt).'</th>';
				$tfoot .='<th>'.my_number_format($t_sale).'</th>';
				$tfoot .='<th>'.my_number_format($t_free_sale).'</th>';
				$tfoot .='<th>'.($t_total_wt).'</th>';
				$tfoot .='<th>'.($t_surplus_wt).'</th>';
				
				if($t_total_point_wt > 0){
					$t_surplus_perc = (($t_surplus_wt / $t_total_point_wt) * 100);
				}
				
				$tfoot .='<th>'.($t_surplus_perc).'</th>';
				$tfoot .='<th>'.my_number_format($t_jhinga_pt_wt).'</th>';
				$tfoot .='<th>'.my_number_format($t_jhinga_dp_wt).'</th>';
				$tfoot .= $this->datatable_scripts;
				
				$html = array('tbody'=>$tbody, 'tfoot'=>$tfoot, 'search_key'=>$search_key, 'search_date'=>$search_date);
				$data = array('status' => 'success', 'message' => 'Report data', 'data'=> $html);
			}else{
				$html = array('tbody'=>$tbody, 'tfoot'=>$tfoot, 'search_key'=>$search_key, 'search_date'=>$search_date);
				$data = array('status' => 'danger', 'message' => '<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> No data found, Please try again.</div>', 'data'=> $html);
			}
		}else{
			$data = array('status' => 'danger', 'message' => validation_errors(), 'data'=>'');
		}
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	// ************************
	
	//2. Fish Category Wise Detail Report [get_fcw_detail_report]
	function fcw_detail_report(){
		$data['content_view'] = 'reports/report/fcw_detail_report_v';
		//$data['all_fishes'] = $this->RM->get_all_fishes();
		$data['all_depot'] = $this->RM->get_market_places(2);
		$data['ajax_url'] = 'reports/ajax_fcw_detail_report';
		$data['page_title'] = 'Fish Category Wise Detail Report';
		$data['table_class'] = 'table_table';
		$this->template->set('stylesheet', $this->stylesheet_array);
		$this->template->set('scriptsrc', $this->scriptsrc_array);
		
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($data);
	}
	
	function ajax_fcw_detail_report(){
		$data = array('status' => 'danger', 'message' => '<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> No data found, Please try again.</div>', 'data'=>'');
		$form_validation = $this->__setFormRules('ajax_fcw_detail_report');
		if($form_validation){
			$post = $this->input->post();
			$from_date = get_date('Y-m-d', str_replace('/', '-', $this->input->post('from_date')));
			$to_date = get_date('Y-m-d', str_replace('/', '-', $this->input->post('to_date')));
			$fish_code = $this->input->post('fish_code');
			$market_id = $this->input->post('market_id');
			$fish_code_text = $this->input->post('fish_code_text');
			$market_name = $this->input->post('market_id_text');
			$report_data = $this->RM->get_fcw_detail_report($from_date, $to_date, $fish_code, $market_id);
			$search_date = $this->input->post('from_date').' - '.$this->input->post('to_date');
			$search_key = $market_name;
			$tbody = $tfoot = '';
			if(!empty($report_data)){
				$total_qty = $total_wt = 0;
				foreach($report_data as $report){
					$each_fish_qty = $each_fish_wt = 0;
					foreach($report as $rep){
						$class = 'class="record_data_yes"';
						if($rep['fish_qty'] == 0 && $rep['fish_wt'] == 0){
							$class = 'class="record_data_no"';
						}
						$tbody .='<tr '.$class.'>';
						$tbody .='    <td>'.$rep['box_number'].'</td>';
						$tbody .='    <td>'.$rep['fish_code'].'</td>';
						$tbody .='    <td>'.$rep['fish_qty'].'</td>';
						$tbody .='    <td>'.$rep['fish_wt'].'</td>';
						$tbody .='    <td></td>';
						$tbody .='</tr>';
						
						$each_fish_qty += $rep['fish_qty'];
						$each_fish_wt += $rep['fish_wt'];
						
						$total_qty += $rep['fish_qty'];
						$total_wt += $rep['fish_wt'];
					}
					
					$tbody .='<tr>';
					$tbody .='    <td></td>';
					$tbody .='    <td></td>';
					$tbody .='    <th>'.my_number_format($each_fish_qty).'</th>';
					$tbody .='    <th>'.my_number_format($each_fish_wt).'</th>';
					$tbody .='    <td></td>';
					$tbody .='</tr>';
				}
				
				$tfoot .='<th colspan="2">Total :</th>';
				$tfoot .='<th>'.my_number_format($total_qty).'</th>';
				$tfoot .='<th>'.my_number_format($total_wt).'</th>';
				$tfoot .='<th></th>';
				$tfoot .= $this->datatable_scripts;
				
				$html = array('tbody'=>$tbody, 'tfoot'=>$tfoot, 'search_key'=>$search_key, 'search_date'=>$search_date);
				$data = array('status' => 'success', 'message' => 'Report data', 'data'=> $html);
			}else{
				$html = array('tbody'=>$tbody, 'tfoot'=>$tfoot, 'search_key'=>$search_key, 'search_date'=>$search_date);
				$data = array('status' => 'danger', 'message' => '<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> No data found, Please try again.</div>', 'data'=> $html);
			}
		}else{
			$data = array('status' => 'danger', 'message' => validation_errors(), 'data'=>'');
		}
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	//3. DR For Driver Report [get_dr_for_driver_report]
	function dr_for_driver(){
		$data['content_view'] = 'reports/report/dr_for_driver_report_v';
		$data['all_depot'] = $this->RM->get_market_places(2);
		$data['ajax_url'] = 'reports/ajax_dr_for_driver';
		$data['page_title'] = 'DR For Driver';
		$data['table_class'] = 'table_table';
		
		//$stylesheet = array_merge($this->stylesheet_array,array(base_url('assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')));
		$scriptsrc = array_merge($this->scriptsrc_array, array(site_url('reports/assets/js/dr_for_driver.js')));
		
		$this->template->set('stylesheet', $this->stylesheet_array);
		$this->template->set('scriptsrc', $scriptsrc);
		
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($data);
	}
	
	function ajax_dr_for_driver(){
		$data = array('status' => 'danger', 'message' => '<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> No data found, Please try again.</div>', 'data'=>'');
		$form_validation = $this->__setFormRules('ajax_dr_for_driver');
		if($form_validation){
			$post = $this->input->post();
			$dispatch_date = get_date('Y-m-d', str_replace('/', '-', $this->input->post('dispatch_date')));
			$market_id = $this->input->post('market_id');
			$market_name = $this->input->post('market_id_text');
			$vehicle_number = $this->input->post('vehicle_number');
			$report_data = $this->RM->get_dr_for_driver_report($dispatch_date, $vehicle_number, $market_id);
			$search_date = get_date('d-m-Y', str_replace('/', '-', $this->input->post('dispatch_date')));
			$search_key = $market_name;
			$tbody = $tfoot = '';
			if(!empty($report_data)){
				$total_box = $total_qty = $total_wt = 0;
				$tbody = '
					<tr>
                		<th colspan="5" style="text-align:center; font-size: 20px">
							DR.NO-'.$report_data['dr_number'].'-'.$report_data['vehicle_number'].'-('.$search_date.')
						</th>
                    </tr>
					<tr>
                		<th>Source Depot-</th>
                        <td>'.$report_data['dispatch_from'].'</td>
						<td>&nbsp;</td>
						<th>Destination-</th>
                        <td>'.$report_data['dispatch_to'].'</td>
                    </tr>
                    <!--<tr>
                		<td colspan="3">Destination-</td>
                        <td colspan="2">'.$report_data['dispatch_to'].'</td>
                    </tr>-->
                    <tr>
                		<th>Departure Time-</th>
                        <td>'.get_date('d-m-Y h:i A', $report_data['departure_time']).'</td>
						<td>&nbsp;</td>
						<th>Arrival Time-</th>
                        <td>'.get_date('d-m-Y h:i A', $report_data['arrival_time']).'</td>
                    </tr>
                    <!--<tr>
                		<td colspan="3">Arrival Time-</td>
                        <td colspan="2">'.get_date('d-m-Y h:i A', $report_data['arrival_time']).'</td>
                    </tr>-->
                    <tr>
                		<th>Driver Name-</th>
                        <td>'.$report_data['driver_name'].'</td>
						<td>&nbsp;</td>
						<th>Driver Mobile No-</th>
                        <td>'.$report_data['driver_mobile_no'].'</td>
                    </tr>
                    <!--<tr>
                		<td colspan="3">Driver Mobile No-</td>
                        <td colspan="2">'.$report_data['driver_mobile_no'].'</td>
                    </tr>-->
                    <tr>
                		<th>Total Freight Exp-</th>
                        <td>'.$report_data['total_freight'].'</td>
						<td>&nbsp;</td>
						<th>Advance At Depot-</th>
                        <td>'.$report_data['advance_freight'].'</td>
                    </tr>
                    <!--<tr>
                		<td colspan="3">Advance At Depot-</td>
                        <td colspan="2">'.$report_data['advance_freight'].'</td>
                    </tr>-->
                    <!--<tr>
                		<td colspan="3">Remaining Freight-</td>
                        <td colspan="2">'.$report_data['remaining_freight'].'</td>
                    </tr>-->
					<tr>
                		<th>Remark -</th>
                        <td colspan="4">'.$report_data['remark'].'</td>
                    </tr>
                    <tr>
                		<th>S.NO</th>
                        <th>Fish Name</th>
                        <th>Box</th>
                        <th>Qty</th>
                        <th>Wt</th>
                    </tr>';
				$serial_number = 1;
				$fish_total_box = 0;
				if(!empty($report_data['fish_data'])){
					foreach($report_data['fish_data'] as $fish_data){
						$tbody .='<tr>';
						$tbody .='    <td>'.$serial_number++.'</td>';
						$tbody .='    <td>'.$fish_data['name'].'</td>';
						$tbody .='    <td>'.$fish_data['total_box'].'</td>';
						$tbody .='    <td>'.$fish_data['quantity'].'</td>';
						$tbody .='    <td>'.my_number_format($fish_data['weight']).'</td>';
						$tbody .='</tr>';
						
						$fish_total_box += $fish_data['total_box'];
						$total_qty 		+= $fish_data['quantity'];
						$total_wt 		+= $fish_data['weight'];
					}
				}
				
				$mix_total_box = $mix_total_qty = $mix_total_wt = 0;
				if(!empty($report_data['mix_data'])){
					foreach($report_data['mix_data'] as $mix_data){
						$mix_total_box++;
						$mix_total_qty += $mix_data['quantity'];
						$mix_total_wt += $mix_data['weight'];
						
						$total_qty += $mix_data['quantity'];
						$total_wt += $mix_data['weight'];
					}
					$tbody .='<tr>';
					$tbody .='    <td>'.$serial_number++.'</td>';
					$tbody .='    <td>Mix</td>';
					$tbody .='    <td>'.$mix_total_box.'</td>';
					$tbody .='    <td>'.my_number_format($mix_total_qty).'</td>';
					$tbody .='    <td>'.my_number_format($mix_total_wt).'</td>';
					$tbody .='</tr>';
				}
				$total_boxes = $fish_total_box + $mix_total_box;
				$tbody .='<tr>';
				$tbody .='	<th></th>';
				$tbody .='	<th>TOTAL :</th>';
				$tbody .='	<th>'.$total_boxes.'</th>';
				$tbody .='	<th>'.my_number_format($total_qty).'</th>';
				$tbody .='	<th>'.my_number_format($total_wt).'</th>';
				$tbody .='</tr>';
				$tfoot .= $this->datatable_scripts;
				
				$html = array('tbody'=>$tbody, 'tfoot'=>$tfoot, 'search_key'=>$search_key, 'search_date'=>$search_date);
				$data = array('status' => 'success', 'message' => 'Report data', 'data'=> $html);
			}else{
				$html = array('tbody'=>$tbody, 'tfoot'=>$tfoot, 'search_key'=>$search_key, 'search_date'=>$search_date);
				$data = array('status' => 'danger', 'message' => '<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> No data found, Please try again.</div>', 'data'=> $html);
			}
		}else{
			$data = array('status' => 'danger', 'message' => validation_errors(), 'data'=>'');
		}
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	function ajax_get_vehicle_numbers(){
		$dispatch_from = $this->input->post('dispatch_from');
		$dispatch_date = get_date('Y-m-d', str_replace('/', '-', $this->input->post('dispatch_date')));
		
		$result = $this->db->select('vehicle_number')->get_where(PRODUCT_DISPATCH, array('dispatch_from'=>$dispatch_from, 'DATE(dispatch_date)'=>$dispatch_date, 'status'=>'Dispatched'))->result_array();
		
		if(!empty($result)){
			$option_html = '<option value="">Select Vehicle</option>';
			foreach($result as $row){
				$option_html .= '<option value="'.$row['vehicle_number'].'">'.$row['vehicle_number'].'</option>';
			}
		}else{
			$option_html = '<option value="">No Vehicles Found</option>';
		}
		$data = array('option_html' => $option_html);
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	//4. DR For Driver Report [get_dr_for_driver_report]
	function inward_outward(){
		
		$data['market_places'] = $this->RM->get_market_places(2);
		$data['content_view'] = 'reports/report/inward_outward_report_v';
		$data['ajax_url'] = 'reports/ajax_inward_outward';
		$data['page_title'] = 'Inward, Outward and Free Sale Detail';
		$data['table_class'] = 'report_content_container';
			
		$this->template->set('stylesheet', $this->stylesheet_array);
		$this->template->set('scriptsrc', $this->scriptsrc_array);
		
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($data);
	}
	
	function ajax_inward_outward(){
		$data = array('status' => 'danger', 'message' => '<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> No data found, Please try again.</div>', 'data'=>'');
		$form_validation = $this->__setFormRules('ajax_inward_outward');
		if($form_validation){
			$post = $this->input->post();
			$dispatch_date = get_date('Y-m-d', str_replace('/', '-', $this->input->post('closing_date')));
			$market_id = $this->input->post('market_id');
			
			$opening_date = get_date('Y-m-d', strtotime($dispatch_date .' -1 day'));
			$opening_stock = $this->RM->get_opening_stock($opening_date, $market_id);
			
			$current_production = $this->RM->get_total_production($dispatch_date, $market_id, 'Point', 'inward_current_stock');
			$transfer_production = $this->RM->get_total_production($dispatch_date, $market_id, 'Transfer');
			//echo $transfer_production; die;
			$deficiency_stock = $this->RM->get_total_production($dispatch_date, $market_id, 'Stock');
			$opening_stock_sale = $this->RM->get_total_sale($dispatch_date, $market_id, 'Opening', 'Stock');
			
			$deficiency_stock = $deficiency_stock + $opening_stock_sale;
			//echo $current_production  .'=='. $transfer_production .'=='. $deficiency_stock;		
			//die;
			$deficiency_stock = (($deficiency_stock > '0') ? ($deficiency_stock - $opening_stock['carret_opening_stock']) : '0.00');
			
			$total_production = ($current_production + $transfer_production + $deficiency_stock + $opening_stock['carret_opening_stock'] + $opening_stock['box_opening_stock']);
			
			
			
			$outward_data = $this->RM->get_outward_data($dispatch_date, $market_id);
			$free_sale = $this->RM->get_free_sale($dispatch_date, $market_id);
			
			$search_date = $this->input->post('closing_date');
			$search_key =  $this->input->post('market_id_text');
			$tbody = $tfoot = '';
			
			$tbody .= '<tr class="report_header">';
			$tbody .= '	<th class="text-center">Inward</th>';
			$tbody .= '	<th class="text-center">Outward</th>';
			$tbody .= '	<th class="text-center">Depot Free Sale Detail</th>';
			$tbody .= '</tr>';
			$tbody .= '<tr>';
			// inward report data
			$tbody .= '	<td style="vertical-align: top;">';
			
			$tbody .= '	<table class="table table-striped table-bordered">
							<tr>
								<th>Particular</th>
								<th>Weight</th>
							</tr>
							<tr>
								<th>Carret Opening Stock</th>
								<th>'.round($opening_stock['carret_opening_stock'],2).'</th>
							</tr>
							<tr>
								<th>Box Opening Stock</th>
								<th>'.round($opening_stock['box_opening_stock'],2).'</th>
							</tr>
							<tr>
								<th>Transfer Stock</th>
								<th>'.round($transfer_production,2).'</th>
							</tr>
							
							<tr>
								<th>Current Stock</th>
								<td>'.round($current_production,2).'</td>
							</tr>
							<tr>
								<th>Deficiency / Surplus</th>
								<td>'.round($deficiency_stock, 2).'</td>
							</tr>
							<tr>
								<th>Total Production</th>
								<td>'.round($total_production,2).'</td>
							</tr>
						</table>';							
			$tbody .= '	</td>';
			// outward report data
			$tbody .= '	<td style="vertical-align: top;">';
			if(!empty($outward_data)){
				$tbody .= '	<table class="table table-striped table-bordered">';
				$t_weight = 0;
				$tbody .='<tr>';
				$tbody .='<th>DR/Bill No.</th>';
				$tbody .='<th>Party/Market</th>';
				$tbody .='<th>Weight</th>';
				$tbody .='</tr>';
				foreach($outward_data as $outward){
					$tbody .= '<tr>';
					$tbody .= '	<td>'.$outward['invoice_number'].'</td>';
					$tbody .= '	<td>'.$outward['client_name'].'</td>';
					$tbody .= '	<td>'.number_format($outward['total_wt'], 2, '.', '').'</td>';
					$tbody .= '</tr>';
					$t_weight += $outward['total_wt'];
				}
				$tbody .= '<tr>';
				$tbody .= '	<th colspan="2">Closing Stock</th>';
				$tbody .= '	<th>'.number_format(($total_production - $t_weight), 2, '.', '').'</th>';
				$tbody .= '</tr>';
				$tbody .= '	</table>';
			}else{
				$tbody .= '<div class="alert alert-info">Data not found!</div>';
			}						
			
			$tbody .= '	</td>';
			// free sale report data
			$tbody .= '	<td style="vertical-align: top;">';			
			if(!empty($free_sale)){
				$tbody .= '	<table class="table table-striped table-bordered">';
				$t_weight = $t_quantity = '0';
				
				$tbody .='<tr>';
				//$tbody .='<th>Point</th>';
				$tbody .='<th>To</th>';
				$tbody .='<th>Order By</th>';
				$tbody .='<th>F.C.</th>';
				$tbody .='<th>Qty</th>';
				$tbody .='<th>Weight</th>';
				$tbody .='</tr>';
				
				foreach($free_sale as $rep){
					$tbody .='<tr>';
					//$tbody .='    <td>'.$rep['market_name'].'</td>';
					$tbody .='    <td>'.$rep['remark'].'</td>';
					$tbody .='    <td>'.$rep['client_name'].'</td>';
                   	//$tbody .='    <td>'.$rep['fish_category_name'].'</td>';
                    //$tbody .='    <td>'.$rep['fish_name'].'</td>';
					$tbody .='    <td>'.$rep['fish_code'].'</td>';
                    $tbody .='    <td>'.$rep['fish_quantity'].'</td>';
                    $tbody .='    <td>'.$rep['fish_weight'].'</td>';
                    $tbody .='</tr>';					
					$t_quantity 		+= $rep['fish_quantity'];
					$t_weight 			+= $rep['fish_weight'];
				}
				$tbody .='<tr>';
				$tbody .='<th colspan="3">Summary</th>';
				$tbody .='<th>'.$t_quantity.'</th>';
				$tbody .='<th>'.$t_weight.'</th>';
				$tbody .='</tr>';
				$tbody .= '	</table>';
			}else{
				$tbody .= '<div class="alert alert-info">Data not found!</div>';
			}			
			$tbody .= '	</td>';
			$tbody .= '</tr>';
			$tbody .= $this->datatable_scripts;
			$html = array('tbody'=>$tbody, 'tfoot'=>$tfoot, 'search_key'=>$search_key, 'search_date'=>$search_date);
			$data = array('status' => 'success', 'message' => 'Report data', 'data'=> $html);
			
		}else{
			$data = array('status' => 'danger', 'message' => validation_errors(), 'data'=>'');
		}
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	//5. Xbox Serialwise DR Detail report [model: get_xbox_report()]
	function xbox_report(){
		$data['content_view'] = 'reports/report/xbox_report_report_v';
		$data['market_places'] = $this->RM->get_market_places(2);
		$data['ajax_url'] = 'reports/ajax_xbox_report';
		$data['page_title'] = 'Xbox Serialwise DR Detail report';
		$data['table_class'] = 'table_table';
		$this->template->set('stylesheet', $this->stylesheet_array);
		$this->template->set('scriptsrc', $this->scriptsrc_array);
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($data);
	}
	
	function ajax_xbox_report(){
		$data = array('status' => 'danger', 'message' => '<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> No data found, Please try again.</div>', 'data'=>'');
		$form_validation = $this->__setFormRules('ajax_xbox_report');
		if($form_validation){
			$post 			= $this->input->post();
			$dispatch_date 	= get_date('Y-m-d', str_replace('/', '-', $this->input->post('dispatch_date')));
			$market_id 		= $this->input->post('market_id');
			$market_name 	= $this->input->post('market_id_text');
			$dr_number 		= $this->input->post('dr_number');
			$report_data 	= $this->RM->get_xbox_report($dispatch_date, $dr_number, $market_id);
			$search_date 	= get_date('d-m-Y', str_replace('/', '-', $this->input->post('dispatch_date')));
			$search_key 	= '';
			$tbody = $tfoot = '';
			if(isset($report_data['dr_number']) && !empty($report_data['dr_number'])){
				$search_key 	= 'DR.NO. '.$report_data['dr_number'].' '.$report_data['vehicle_number'].' ('.$search_date.')';
				
				$tbody = '<table class="table">
							<tr>
							<td style="vertical-align: top;">							
							<table class="table table-striped">
							<tr class="report_header">
                                <th>Box No.</th>
                                <th>Fish Coce</th>
                                <th>Quantity</th>
                                <th>Weight</th>
                                <th>Party</th>
                            </tr>';
				$serial_number = 1;
				$total_wt = 0;
				if(!empty($report_data['box_items'])){
					$total_box = count($report_data['box_items']);
					$odd = $total_box%2;
					$break_point = 0;
					if($odd){
						$break_point = ($total_box - 1)/2;
					}else{
						$break_point = $total_box/2;						
					}
									
					$i=0;
					foreach($report_data['box_items'] as $box_items){ $i++;
					
						$tbody .='<tr class="record_data_yes">';
						$tbody .='    <td>'.$box_items['box_number'].'</td>';
						$tbody .='    <td>'.$box_items['fish_code'].'</td>';
						$tbody .='    <td>'.$box_items['fish_qty'].'</td>';
						$tbody .='    <td>'.$box_items['box_wt'].'</td>';
						$tbody .='    <td></td>';
						$tbody .='</tr>';
						
						if($break_point == $i){
							
							$tbody .='
									<tr class="report_header">
										<th>Box No.</th>
										<th>Fish Coce</th>
										<th>Quantity</th>
										<th>Weight</th>
										<th>Party</th>
									</tr>
									</table>
									</td>
									<td>									
									<table class="table table-striped">
							<tr class="report_header">
                                <th>Box No.</th>
                                <th>Fish Coce</th>
                                <th>Quantity</th>
                                <th>Weight</th>
                                <th>Party</th>
                            </tr>';
						
						}
						
						$total_wt 		+= $box_items['box_wt'];
					}
					
				}
				$tbody .= '<tr class="report_header">
                                <th>Box No.</th>
                                <th>Fish Coce</th>
                                <th>Quantity</th>
                                <th>Weight</th>
                                <th>Party</th>
                            </tr></table></td></tr></table>';
				
				$tfoot .= '<th colspan="10" class="text-center"> Total Box: '.$report_data['total_boxes'].', Total Weight: '.my_number_format($total_wt).' KG</td>';
				$tfoot .= $this->datatable_scripts;
				
				$html = array('tbody'=>$tbody, 'tfoot'=>$tfoot, 'search_key'=>$search_key, 'search_date'=>$search_date);
				$data = array('status' => 'success', 'message' => 'Report data', 'data'=> $html);
			}else{
				$html = array('tbody'=>$tbody, 'tfoot'=>$tfoot, 'search_key'=>$search_key, 'search_date'=>$search_date);
				$data = array('status' => 'danger', 'message' => '<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> No data found, Please try again.</div>', 'data'=> $html);
			}
		}else{
			$data = array('status' => 'danger', 'message' => validation_errors(), 'data'=>'');
		}
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	//6. Comparison Report [get_xbox_report]
	function comparison_report(){
		$data['content_view']	= 'reports/report/comparison_report_v';
		//$data['market_places'] 	= $this->RM->get_market_places(3);
		//$data['all_clients'] 	= $this->RM->get_all_clients(2);
		$data['ajax_url'] 		= 'reports/ajax_comparison_report';
		$data['page_title'] 	= 'Comparison Report';
		$data['table_class'] 	= 'table_table';
		
		$scriptsrc = array_merge($this->scriptsrc_array, array(site_url('reports/assets/js/comparison_report.js')));
		
		$this->template->set('stylesheet', $this->stylesheet_array);
		$this->template->set('scriptsrc', $scriptsrc);
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($data);
	}
	
	function ajax_comparison_report_old(){
		$data = array('status' => 'danger', 'message' => '<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> No data found, Please try again.</div>', 'data'=>'');
		$form_validation = $this->__setFormRules('ajax_comparison_report');
		if($form_validation){
			$post = $this->input->post();
			
			//$dispatch_date 	= get_date('Y-m-d', str_replace('/', '-', $this->input->post('dispatch_date')));
			//$sale_date 		= get_date('Y-m-d', str_replace('/', '-', $this->input->post('sale_date')));
			$dr_number 		= $this->input->post('dr_number');
			$market_id 		= $this->input->post('market_id');
			$market_name 	= $this->input->post('market_id_text');
			$client_id 		= $this->input->post('client_id');
			$client_name 	= $this->input->post('client_id_text');
			
			$report_data = $this->RM->get_comparison_report($dr_number, $market_id, $client_id);
			//printr($report_data);
			$search_date = $market_name;
			$search_key = '';
			$tbody = $tfoot = $tbody = '';
			$total_est_box_wt = $total_est_net_wt = $total_est_amt = $total_act_box_wt = $total_sale_amt = $total_mqw = $total_mpv = 0;
			$act_commission = $act_expenses = 0;
			if(!empty($report_data)){
				$search_key = $report_data['dr_info'];
				$act_expenses = $report_data['expenses'];
				$serial_number = 1;
				if(!empty($report_data['boxes'])){
					foreach($report_data['boxes'] as $row){
						$sale_id			= $row['sale_id'];
						$client_name		= $row['client_name'];
						//echo ($row['commission']*$row['total_amount'])/100;
						//printr($row);
						$estimated_net_wt 	= $row['box_wt'] - ($row['commission']*$row['box_wt'])/100;
						$estimated_amt	  	= ($estimated_net_wt * $row['estimated_price']);
						
						$mqw = $realisation_cost = $mpv = 0.00;
						if($sale_id){
							$mqw 				= ($estimated_net_wt-$row['net_wt']);
							$realisation_cost 	= ($row['estimated_price']-$row['fish_rate']);
							$mpv 				= ($estimated_amt-$row['total_amount']);
							
							$act_commission		+= ($row['commission']*$row['total_amount'])/100;
						}
						
						$tbody .='<tr>';
						$tbody .='    <td>'.$serial_number++.'</td>';
						$tbody .='    <td>'.$row['box_number'].'</td>';
						$tbody .='    <td>'.$row['fish_code'].'</td>';
						$tbody .='    <td>'.$row['fish_qty'].'</td>';
						$tbody .='    <td>'.number_format($row['box_wt'], '3', '.', '').'</td>';
						$tbody .='    <td>'.number_format($estimated_net_wt, '3', '.', '').'</td>';
						$tbody .='    <td>'.number_format($row['estimated_price'], '2', '.', '').'</td>';
						$tbody .='    <td>'.number_format($estimated_amt, '2', '.', '').'</td>';
						$tbody .='    <td>'.number_format($row['net_wt'], '3', '.', '').'</td>';
						$tbody .='    <td>'.number_format($row['fish_rate'], '2', '.', '').'</td>';
						$tbody .='    <td>'.number_format($row['total_amount'], '2', '.', '').'</td>';
						$tbody .='    <td>'.$client_name.'</td>';
						$tbody .='    <td>'.number_format($mqw, '3', '.', '').'</td>';
						$tbody .='    <td>'.number_format($realisation_cost, '2', '.', '').'</td>';
						$tbody .='    <td>'.number_format($mpv, '2', '.', '').'</td>';
						$tbody .='</tr>';
						
						$total_est_box_wt 	+= $row['box_wt'];
						$total_est_net_wt   += $estimated_net_wt;
						$total_est_amt		+= $estimated_amt;
						
						$total_act_box_wt 	+= $row['net_wt'];
						$total_sale_amt 	+= $row['total_amount'];
						
						$total_mqw			+= $mqw;
						$total_mpv			+= $mpv;
					}
				}
				
				$tfoot .='<tr>';
				$tfoot .='  <th>Total</th>';
				$tfoot .='  <th>'.$report_data['total_boxes'].'</th>';
				$tfoot .='  <th></th>';
				$tfoot .='  <th></th>';
				$tfoot .='  <th class="total_est_box_wt">'.number_format($total_est_box_wt, '3', '.', '').'</th>';
				$tfoot .='  <th>'.number_format($total_est_net_wt, '3', '.', '').'</th>';
				$tfoot .='  <th></th>';
				$tfoot .='  <th class="total_est_amount">'.number_format($total_est_amt, '2', '.', '').'</th>';
				$total_act_box_wt = number_format($total_act_box_wt, '3', '.', '');
				$tfoot .='  <th class="total_act_box_wt">'.$total_act_box_wt.'</th>';
				$tfoot .='  <th></th>';
				$total_act_amount = number_format($total_sale_amt, '2', '.', '');
				$tfoot .='  <th class="total_act_amount">'.number_format($total_sale_amt, '2', '.', '').'</th>';
				$tfoot .='  <th></th>';
				$tfoot .='  <th>'.number_format($total_mqw, '3', '.', '').'</th>';
				$tfoot .='  <th></th>';
				$tfoot .='  <th>'.number_format($total_mpv, '2', '.', '').'</th>';
				$tfoot .='</tr>';
				
				$tfoot .='<tr>';
				$tfoot .='	<th colspan="6">Commission</th>';
				$tfoot .='	<th><input type="text" value="" min="0" style="width:50px;" class="strict_numeric commission" onkeyup="checkDecimal(this)" class="form-control" data-toggle="tooltip" data-title="Enter Commission Percentage" /></th>';
				$tfoot .='	<th class="est_commission"></th>';
				$tfoot .='	<th colspan="2">Actual Com.</th>';
				$act_commission = number_format($act_commission, '2', '.', '');
				$tfoot .='	<th class="act_commission">'.number_format($act_commission, '2', '.', '').'</th>';
				$tfoot .='	<th colspan="4"></th>';
				$tfoot .='</tr>';
				
				$tfoot .='<tr>';
				$tfoot .='	<th colspan="7">Expenses (Labour + Arat)</th>';
				$tfoot .='	<th><input type="text" value="" style="width:60px;" class="expenses" class="strict_numeric form-control" onkeyup="checkDecimal(this)" data-toggle="tooltip" data-title="Enter Expenses" /></th>';
				$tfoot .='	<th colspan="2">Actual Exp.</th>';
				$tfoot .='	<th class="act_expenses">'.number_format($act_expenses, '2', '.', '').'</th>';
				$tfoot .='	<th colspan="4"></th>';
				$tfoot .='</tr>';
				
				$tfoot .='<tr>';
				$tfoot .='	<th colspan="7"></th>';
				$tfoot .='	<th class="est_tot_coms_n_exp"></th>';
				$tfoot .='	<th colspan="2"></th>';
				$act_tot_coms_n_exp = ($act_commission + $act_expenses);
				$tfoot .='	<th class="act_tot_coms_n_exp">'.$act_tot_coms_n_exp.'</th>';
				$tfoot .='	<th colspan="4"></th>';
				$tfoot .='</tr>';
				
				$tfoot .='<tr><td colspan="15"></td></tr>';
				
				$tfoot .='<tr>';
				$tfoot .='	<th colspan="7">Estimated Net Amount</th>';
				$tfoot .='	<th class="est_net_amt"></th>';
				$tfoot .='	<th colspan="2">Act. Net Amt.</th>';
				$actual_net_amt = $total_act_amount - $act_tot_coms_n_exp;
				$tfoot .='	<th class="actual_net_amt">'.$actual_net_amt.'</th>';
				$tfoot .='	<th></th>';
				$tfoot .='	<th colspan="2">Diff. Net Amt.</th>';
				$tfoot .='	<th class="diff_net_amt"></th>';
				$tfoot .='</tr>';
				
				$tfoot .='<tr>';
				$tfoot .='	<th colspan="7">Estimated Average Net Amount</th>';
				$tfoot .='	<th class="est_avg_net_amt"></th>';
				$tfoot .='	<th colspan="2">Act. Avg. Net Amt.</th>';
				$actual_avg_net_amt = 0;
				if($total_act_box_wt > 0){
					$actual_avg_net_amt = ($actual_net_amt/$total_act_box_wt);
				}
				$tfoot .='	<th class="actual_avg_net_amt">'.number_format($actual_avg_net_amt, '2', '.', '').'</th>';
				$tfoot .='	<th></th>';
				$tfoot .='	<th colspan="2">Diff. Avg. Net Amt.</th>';
				$tfoot .='	<th class="diff_avg_net_amt"></th>';
				$tfoot .='</tr>';
				
				$tfoot .= $this->datatable_scripts;
				
				$html = array('tbody'=>$tbody, 'tfoot'=>$tfoot, 'search_key'=>$search_key, 'search_date'=>$search_date);
				$data = array('status' => 'success', 'message' => 'Report data', 'data'=> $html);
			}else{
				$html = array('tbody'=>$tbody, 'tfoot'=>$tfoot, 'search_key'=>$search_key, 'search_date'=>$search_date);
				$data = array('status' => 'danger', 'message' => '<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> No data found, Please try again.</div>', 'data'=> $html);
			}
		}else{
			$data = array('status' => 'danger', 'message' => validation_errors(), 'data'=>'');
		}
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	function ajax_comparison_report(){
		$data = array('status' => 'danger', 'message' => '<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> No data found, Please try again.</div>', 'data'=>'');
		$form_validation = $this->__setFormRules('ajax_comparison_report');
		if($form_validation){
			$post = $this->input->post();
			
			//$dispatch_date 	= get_date('Y-m-d', str_replace('/', '-', $this->input->post('dispatch_date')));
			//$sale_date 		= get_date('Y-m-d', str_replace('/', '-', $this->input->post('sale_date')));
			$dr_number 		= $this->input->post('dr_number');
			$market_id 		= $this->input->post('market_id');
			$market_name 	= $this->input->post('market_id_text');
			$client_id 		= $this->input->post('client_id');
			$client_name 	= $this->input->post('client_id_text');
					
			$report_data = $this->RM->get_comparison_report($dr_number, $market_id, $client_id);
			//printr($report_data);
			$search_date = $market_name;
			$search_key = '';
			$tbody = $tfoot = $tbody = '';
			$total_est_box_wt = $total_est_net_wt = $total_est_amt = $total_act_box_wt = $total_sale_amt = $total_mqw = $total_mpv = 0;
			$act_commission = $act_expenses = 0;
			if(!empty($report_data)){
				$search_key = $report_data['dr_info'];
				$act_expenses = $report_data['expenses'];
				$serial_number = 1;
				$disp_est_comsn = 0;
				$est_disp_array = array();
				if(!empty($report_data['boxes'])){
					foreach($report_data['boxes'] as $row){
						$sale_id			= $row['sale_id'];
						$client_name		= $row['client_name'];						
						
						$estimated_net_wt 	= $row['box_wt'] - ($row['est_dhalta']*$row['box_wt']/100);	
						
						$estimated_amt	  	= ($estimated_net_wt * $row['estimated_price']);						
						$mqw = $realisation_cost = $mpv = 0.00;
						if($sale_id){
							$mqw 				= ($estimated_net_wt-$row['net_wt']);
							$realisation_cost 	= ($row['estimated_price']-$row['fish_rate']);
							$mpv 				= ($estimated_amt-$row['total_amount']);							
							$act_commission		+= ($row['commission']*$row['total_amount'])/100;
						}
												
						$tbody .='<tr>';
						$tbody .='    <td class="danger">'.$serial_number++.'</td>';
						$tbody .='    <td class="danger">'.$row['box_number'].'</td>';
						$tbody .='    <td class="danger">'.$row['fish_code'].'</td>';
						$tbody .='    <td class="danger">'.$row['fish_qty'].'</td>';
						$tbody .='    <td class="danger">'.number_format($row['box_wt'], '3', '.', '').'</td>';
						$tbody .='    <td class="danger">'.number_format($estimated_net_wt, '3', '.', '').'</td>';
						$tbody .='    <td class="danger">'.number_format($row['estimated_price'], '2', '.', '').'</td>';
						$tbody .='    <td class="danger">'.number_format($estimated_amt, '2', '.', '').'</td>';
						$tbody .='    <td class="success">'.number_format($row['net_wt'], '3', '.', '').'</td>';
						$tbody .='    <td class="success">'.number_format($row['fish_rate'], '2', '.', '').'</td>';
						$tbody .='    <td class="success">'.number_format($row['total_amount'], '2', '.', '').'</td>';
						$tbody .='    <td class="success">'.$client_name.'</td>';
						$tbody .='    <td class="info">'.number_format($mqw, '3', '.', '').'</td>';
						$tbody .='    <td class="info">'.number_format($realisation_cost, '2', '.', '').'</td>';
						$tbody .='    <td class="info">'.number_format($mpv, '2', '.', '').'</td>';
						$tbody .='</tr>';
						
						$total_est_box_wt 	+= $row['box_wt'];
						$total_est_net_wt   += $estimated_net_wt;
						$total_est_amt		+= $estimated_amt;
						
						$total_act_box_wt 	+= $row['net_wt'];
						$total_sale_amt 	+= $row['total_amount'];
						
						$total_mqw			+= $mqw;
						$total_mpv			+= $mpv;
						$disp_est_comsn 	+= ($estimated_amt * $row['est_commission']) / 100;
						
						$est_disp_array[$row['dispatch_id']] = array('exp'=>$row['est_expenses'], 'cmsn'=>$row['est_commission']);
					}
				}
				
				
				
				$cmsn_percent = $expns_percent = '';
				foreach($est_disp_array as $dsp){
					$cmsn_percent .=  $dsp['cmsn'].'%, ';
					$expns_percent .= $dsp['exp'].'%, ';
				}
				$cmsn_percent = rtrim($cmsn_percent, ', ');
				$expns_percent = rtrim($expns_percent, ', ');
				$disp_est_expns = ($report_data['total_boxes'] * $row['est_expenses']);
				
				$tfoot .='<tr>';
				$tfoot .='  <th>Total</th>';
				$tfoot .='  <th>'.$report_data['total_boxes'].'</th>';
				$tfoot .='  <th></th>';
				$tfoot .='  <th></th>';
				$tfoot .='  <th class="danger total_est_box_wt">'.number_format($total_est_box_wt, '3', '.', '').'</th>';
				$tfoot .='  <th class="danger">'.number_format($total_est_net_wt, '3', '.', '').'</th>';
				$tfoot .='  <th class="danger"></th>';
				$tfoot .='  <th class="danger total_est_amount">'.number_format($total_est_amt, '2', '.', '').'</th>';
				$total_act_box_wt = number_format($total_act_box_wt, '3', '.', '');
				$tfoot .='  <th class="success total_act_box_wt">'.$total_act_box_wt.'</th>';
				$tfoot .='  <th class="success"></th>';
				$total_act_amount = number_format($total_sale_amt, '2', '.', '');
				$tfoot .='  <th class="success total_act_amount">'.number_format($total_sale_amt, '2', '.', '').'</th>';
				$tfoot .='  <th></th>';
				$tfoot .='  <th class="info">'.number_format($total_mqw, '3', '.', '').'</th>';
				$tfoot .='  <th class="info"></th>';
				$tfoot .='  <th class="info">'.number_format($total_mpv, '2', '.', '').'</th>';
				$tfoot .='</tr>';
				
				$tfoot .='<tr>';
				$tfoot .='	<th colspan="6">Commission</th>';
				$tfoot .='	<th>'.$cmsn_percent.'</th>';
				$tfoot .='	<th class="danger est_commission">'.$disp_est_comsn.'</th>';
				$tfoot .='	<th colspan="2">Actual Com.</th>';
				$act_commission = number_format($act_commission, '2', '.', '');
				$tfoot .='	<th class="success act_commission">'.number_format($act_commission, '2', '.', '').'</th>';
				$tfoot .='	<th colspan="4"></th>';
				$tfoot .='</tr>';
				
				$tfoot .='<tr>';
				$tfoot .='	<th colspan="6">Expenses (Labour + Arat)</th>';
				$tfoot .='	<th>'.$expns_percent.'</th>';
				$tfoot .='	<th class="danger">'.$disp_est_expns.'</th>';
				$tfoot .='	<th colspan="2">Actual Exp.</th>';
				$tfoot .='	<th class="success act_expenses">'.number_format($act_expenses, '2', '.', '').'</th>';
				$tfoot .='	<th colspan="4"></th>';
				$tfoot .='</tr>';
				
				$tfoot .='<tr>';
				$tfoot .='	<th colspan="7"></th>';
				$tfoot .='	<th class="danger est_tot_coms_n_exp">'.($disp_est_comsn + $disp_est_expns).'</th>';
				$tfoot .='	<th colspan="2"></th>';
				$act_tot_coms_n_exp = ($act_commission + $act_expenses);
				$tfoot .='	<th class="success act_tot_coms_n_exp">'.$act_tot_coms_n_exp.'</th>';
				$tfoot .='	<th colspan="4"></th>';
				$tfoot .='</tr>';
				
				$tfoot .='<tr><td colspan="15"></td></tr>';
				$est_net_amt = ($total_est_amt - ($disp_est_comsn + $disp_est_expns));
				$tfoot .='<tr>';
				$tfoot .='	<th colspan="7">Estimated Net Amount</th>';
				$tfoot .='	<th class="danger est_net_amt">'.$est_net_amt.'</th>';
				$tfoot .='	<th colspan="2">Act. Net Amt.</th>';
				$actual_net_amt = $total_act_amount - $act_tot_coms_n_exp;
				$tfoot .='	<th class="success actual_net_amt">'.$actual_net_amt.'</th>';
				$tfoot .='	<th></th>';
				$tfoot .='	<th colspan="2">Diff. Net Amt.</th>';
				$tfoot .='	<th class="info diff_net_amt">'.($est_net_amt-$actual_net_amt).'</th>';
				$tfoot .='</tr>';
				
				$est_evg_net_amnt = ($total_est_amt - ($disp_est_comsn + $disp_est_expns))/$total_est_net_wt;
				$est_evg_net_amnt = number_format($est_evg_net_amnt, '2', '.', '');
				$tfoot .='<tr>';
				$tfoot .='	<th colspan="7">Estimated Average Net Amount</th>';
				$tfoot .='	<th class="danger est_avg_net_amt">'.$est_evg_net_amnt.'</th>';
				$tfoot .='	<th colspan="2">Act. Avg. Net Amt.</th>';
				$actual_avg_net_amt = 0;
				if($total_act_box_wt > 0){
					$actual_avg_net_amt = ($actual_net_amt/$total_act_box_wt);
				}
				$tfoot .='	<th class="success actual_avg_net_amt">'.number_format($actual_avg_net_amt, '2', '.', '').'</th>';
				$tfoot .='	<th></th>';
				$tfoot .='	<th colspan="2">Diff. Avg. Net Amt.</th>';
				$tfoot .='	<th class="info diff_avg_net_amt">'.number_format(($est_evg_net_amnt-$actual_avg_net_amt),'2', '.', '').'</th>';
				$tfoot .='</tr>';
				
				$tfoot .= $this->datatable_scripts;
				
				$html = array('tbody'=>$tbody, 'tfoot'=>$tfoot, 'search_key'=>$search_key, 'search_date'=>$search_date);
				$data = array('status' => 'success', 'message' => 'Report data', 'data'=> $html);
			}else{
				$html = array('tbody'=>$tbody, 'tfoot'=>$tfoot, 'search_key'=>$search_key, 'search_date'=>$search_date);
				$data = array('status' => 'danger', 'message' => '<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> No data found, Please try again.</div>', 'data'=> $html);
			}
		}else{
			$data = array('status' => 'danger', 'message' => validation_errors(), 'data'=>'');
		}
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	//7. DR Summary [get_dr_summary]
	function dr_summary(){
		$data['content_view'] = 'reports/report/dr_summary_report_v';
		$data['market_places'] = $this->RM->get_market_places(2);
		$data['ajax_url'] = 'reports/ajax_dr_summary';
		$data['page_title'] = 'DR Summary';
		$data['table_class'] = 'table_table';		
		$this->template->set('stylesheet', $this->stylesheet_array);
		$this->template->set('scriptsrc', $this->scriptsrc_array);
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($data);
	}
	
	function ajax_dr_summary(){
		$data = array('status' => 'danger', 'message' => '<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> No data found, Please try again.</div>', 'data'=>'');
		$form_validation = $this->__setFormRules('ajax_dr_summary');
		if($form_validation){
			$post 			= $this->input->post();
			
			$from_date 		= get_date('Y-m-d', str_replace('/', '-', $this->input->post('from_date')));
			$to_date 		= get_date('Y-m-d', str_replace('/', '-', $this->input->post('to_date')));
			$market_id 		= $this->input->post('market_id');
			$market_name 	= $this->input->post('market_id_text');
			
			$report_data 	= $this->RM->get_dr_summary($from_date, $to_date, $market_id);
			//printr($report_data);
			$search_date 	= $this->input->post('from_date'). ' To '.$this->input->post('to_date');
			$search_key 	= $market_name;
			$tbody = $tfoot = '';
			$summary_qty = $summary_boxes = $summary_gross_wt = $summary_net_wt = $summary_boxes_sold = 0;
			if(!empty($report_data)){
				foreach($report_data as $record){
					$sr_no = 1;
					foreach($record as $row){
						$tbody .='<tr>';
						if($sr_no == 1){
							$tbody .='    <td>'.get_date('d-M-y', $row['dispatch_date']).'</td>';
							$tbody .='    <td>'.$row['vehicle_number'].'</td>';
							$tbody .='    <td>'.$row['total_box_qty'].'</td>';
							$tbody .='    <td>'.$row['dr_number'].'</td>';
							$tbody .='    <td>'.$row['total_box'].'</td>';
						}else{
							$tbody .='<td></td><td></td><td></td><td></td><td></td>';
						}
						$tbody .='    <td>'.$row['market_name'].'</td>';
						$tbody .='    <td>'.get_date('d-M-y', $row['sale_date']).'</td>';
						$tbody .='    <td><a title="View Challan" href="'.site_url('dailysale/view_challan/'.$row['sale_id']).'" target="_blank" class="text-danger">'.$row['client_name'].' </a></td>';
						$tbody .='    <td>'.$row['total_gross_wt'].'</td>';
						$tbody .='    <td>'.$row['total_net_wt'].'</td>';
						$tbody .='    <td>'.$row['boxes_sold'].'</td>';
						$tbody .='</tr>';
						
						$summary_qty 		+= $row['total_box_qty'];
						$summary_boxes 		+= $row['total_box'];
						$summary_gross_wt 	+= $row['total_gross_wt'];
						$summary_net_wt 	+= $row['total_net_wt'];
						$summary_boxes_sold += $row['boxes_sold'];
						
						$sr_no++;
					}
				}
				$tfoot .= '<tr>';
				$tfoot .= '	  <th colspan="2">SUMMARY</th>';
				$tfoot .= '	  <th>'.$summary_qty.'</th>';
				$tfoot .= '	  <th></th>';
				$tfoot .= '	  <th>'.$summary_boxes.'</th>';
				$tfoot .= '	  <th colspan="3"></th>';
				$tfoot .= '	  <th>'.number_format($summary_gross_wt, '3', '.', '').'</th>';
				$tfoot .= '	  <th>'.number_format($summary_net_wt, '3', '.', '').'</th>';
				$tfoot .= '	  <th>'.$summary_boxes_sold.'</th>';
				$tfoot .= '</tr>';
				
				$tfoot .= $this->datatable_scripts;
				
				$html = array('tbody'=>$tbody, 'tfoot'=>$tfoot, 'search_key'=>$search_key, 'search_date'=>$search_date);
				$data = array('status' => 'success', 'message' => 'Report data', 'data'=> $html);
			}else{
				$html = array('tbody'=>$tbody, 'tfoot'=>$tfoot, 'search_key'=>$search_key, 'search_date'=>$search_date);
				$data = array('status' => 'danger', 'message' => '<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> No data found, Please try again.</div>', 'data'=> $html);
			}
		}else{
			$data = array('status' => 'danger', 'message' => validation_errors(), 'data'=>'');
		}
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	//8. DR Summary [get_dr_summary]
	function all_local_sales_report(){
		$data['content_view'] = 'reports/report/all_local_sales_report_v';
		$data['market_places'] = $this->RM->get_market_places(1);
		$data['market_category'] = $this->RM->get_market_category(1);
		$data['ajax_url'] = 'reports/ajax_all_local_sales_report';
		$data['page_title'] = 'All Local Sales Report';
		$data['table_class'] = 'table_table';		
		$this->template->set('stylesheet', $this->stylesheet_array);
		$this->template->set('scriptsrc', $this->scriptsrc_array);
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($data);
	}
	
	function ajax_all_local_sales_report(){
		$data = array('status' => 'danger', 'message' => '<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> No data found, Please try again.</div>', 'data'=>'');
		$form_validation = $this->__setFormRules('ajax_local_sale');
		if($form_validation){
			$post = $this->input->post();
			
			$from_date = get_date('Y-m-d', str_replace('/', '-', $this->input->post('from_date')));
			$to_date = get_date('Y-m-d', str_replace('/', '-', $this->input->post('to_date')));			
			$market_category = $this->input->post('market_category');
			$market_id = $this->input->post('market_id');
			
			$report_data = $this->RM->get_local_sale($from_date, $to_date, $market_category, $market_id);
			
			$search_date = $this->input->post('from_date') . ' TO '. $this->input->post('to_date');		
			$search_key = $this->input->post('market_id_text');
			
			$tbody = $tfoot = '';
			if(!empty($report_data)){
				$t_fish_quantity = $t_fish_weight = $t_grand_total = $t_received_amt = $t_remaining_amt = 0;
				foreach($report_data as $rdata){
					$saleId = isset($rdata['sale_id']) ? $rdata['sale_id'] : ($rdata['id'] ?? '');
					$mpCode = !empty($rdata['mp_code']) ? $rdata['mp_code'].'-' : '';
					$clientName = $rdata['client_name'] ?? '';
					$pointName = $rdata['point_name'] ?? '';
					$paymentMode = $rdata['payment_mode'] ?? 'Cash';
					$invoiceNum = $rdata['invoice_number'] ?? '';
					$remark = $rdata['remark'] ?? '';
					$grandTotal = floatval($rdata['grand_total'] ?? 0);
					$receivedAmt = floatval($rdata['received_amt'] ?? 0);
					$balance = $grandTotal - $receivedAmt;

					$tbody .='<tr class="record_data_yes">';
					$tbody .='	  <td>'.get_date('d/m/Y', $rdata['sale_date']).'</td>';
					$tbody .='    <td><a href="javascript:void(0);" data-url="reports/ajax_local_sales_detail/'.$saleId.'" class="sale_detail text-danger">'.$clientName.'</a></td>';
					$tbody .='    <td>'.$paymentMode.'</td>';
					$tbody .='    <td>'.$pointName.'</td>';
					$tbody .='    <td>'.$mpCode.$invoiceNum.'</td>';
					$tbody .='    <td>'.$remark.'</td>';
					$tbody .='    <td>'.number_format($grandTotal, 2, '.', '').'</td>';
					$tbody .='    <td>'.number_format($receivedAmt, 2, '.', '').'</td>';
					$tbody .='    <td>'.number_format($balance, 2, '.', '').'</td>';
					$tbody .='</tr>';
					
					$t_grand_total 		+= $rdata['grand_total'];
					$t_received_amt 	+= $rdata['received_amt'];
					$t_remaining_amt	+= ($rdata['grand_total'] - $rdata['received_amt']);
				}
								
				$tfoot .='<th colspan="6">Summary</th>';
				$tfoot .='<th>'.number_format($t_grand_total,2,".","").'</th>';
				$tfoot .='<th>'.number_format($t_received_amt,2,".","").'</th>';
				$tfoot .='<th>'.number_format($t_remaining_amt,2,".","").'</th>';
				$tfoot .= $this->datatable_scripts;
				
				$html = array('tbody'=>$tbody, 'tfoot'=>$tfoot, 'search_key'=>$search_key, 'search_date'=>$search_date);
				$data = array('status' => 'success', 'message' => 'Report data', 'data'=> $html);
			}else{
				$html = array('tbody'=>$tbody, 'tfoot'=>$tfoot, 'search_key'=>$search_key, 'search_date'=>$search_date);
				$data = array('status' => 'danger', 'message' => '<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> No data found, Please try again.</div>', 'data'=> $html);
			}
		}else{
			$data = array('status' => 'danger', 'message' => validation_errors(), 'data'=>'');
		}
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	function ajax_local_sales_detail($sale_id){	
		$data['report_data'] = $this->RM->get_local_sale_detail($sale_id);
		$data['table_class'] = 'local_sale_detail';
		
$page_title = '<button type="button" class="btn btn-warning export_data" data-toggle="tooltip" data-title="Download Excel" data-target="#'.$data['table_class'].'" data-filename="'.$data['table_class'].'"> <i class="fa fa-file-excel-o"></i> Excel</button>&nbsp;
<a id="dlink" style="display:none;"></a>
<button type="button" class="btn btn-success print_data" data-target="#'.$data['table_class'].'"> <i class="fa fa-print"></i> Print</button>';


		$view_data = $this->load->view('report/local_sale_detail_v', $data, true);
		
		$data = array('status' => 'success', 'page_title' => $page_title, 'page_content'=> $view_data);
		
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	//9. Daily Production Report [get_production_data]
	function all_boxes_report(){
		$data['content_view'] = 'reports/report/all_boxes_report_v';
		$data['ajax_url'] = 'reports/ajax_all_boxes_report';
		$data['page_title'] = 'All Boxes Report';
		$data['table_class'] = 'table_table';
		$data['all_depots']	= $this->RM->get_market_places(2);
		$this->template->set('stylesheet', $this->stylesheet_array);
		$this->template->set('scriptsrc', $this->scriptsrc_array);
		
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($data);
	}
	
	function ajax_all_boxes_report(){
		$data = array('status' => 'danger', 'message' => '<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> No data found, Please try again.</div>', 'data'=>'');
		$form_validation = $this->__setFormRules('ajax_all_boxes_report');
		if($form_validation){
			$post 			 = $this->input->post();
			$production_date = get_date('Y-m-d', str_replace('/', '-', $this->input->post('production_date')));
			$depot_id 		 = $this->input->post('depot_id');
			$box_status		 = $this->input->post('box_status');			
			$report_data 	 = $this->RM->get_all_boxes_report($production_date, $depot_id, $box_status);
			$search_date 	 = $this->input->post('production_date');
			$search_key 	 = $this->input->post('depot_id_text');;;
			$tbody = $tfoot = '';
			if(!empty($report_data)){
				$i = 1;
				foreach($report_data as $rep){					
					$tbody .='<tr>';
					$tbody .='  <td>'.$i.'</td>';
					$tbody .='  <td>'.$rep['depot_name'].'</td>';
					$tbody .='  <td>'.$rep['point_name'].'</td>';
					$tbody .='  <td>'.$rep['box_number'].'</td>';
					$tbody .='  <td>'.$rep['status'].'</td>';
					$tbody .='</tr>';
					$i++;
				}
				
				$tfoot .='<th colspan="5" class="text-center">Total Boxes: ';
				$tfoot .=''.count($report_data).'</th>';
				$tfoot .= $this->datatable_scripts;
				
				$html = array('tbody'=>$tbody, 'tfoot'=>$tfoot, 'search_key'=>$search_key, 'search_date'=>$search_date);
				$data = array('status' => 'success', 'message' => 'Report data', 'data'=> $html);
			}else{
				$html = array('tbody'=>$tbody, 'tfoot'=>$tfoot, 'search_key'=>$search_key, 'search_date'=>$search_date);
				$data = array('status' => 'danger', 'message' => '<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> No data found, Please try again.</div>', 'data'=> $html);
			}
		}else{
			$data = array('status' => 'danger', 'message' => validation_errors(), 'data'=>'');
		}
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	//Common Function Start	
	function ajax_get_market_places(){
		$data = array('status'=>'danger', 'msg'=>'No data found', 'data'=>'<option value="">No data found</option>');
		$market_category = $this->input->post('market_category');
		if(!empty($market_category)){
			$result = $this->RM->get_market_places($market_category);
			$option = '<option value="0">All</option>';
			if(!empty($result)){
				foreach($result as $res){
					$option .= '<option value="'.$res['ID'].'">'.$res['name'].'</option>>';
				}
				$data = array('status'=>'success', 'msg'=>'Data found.', 'data'=>$option);
			}
		}	
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	//Select2 Vehicle Number
	function select2_vehicle_numbers(){
		$data['result'] = '';
		$q = $this->input->get('q');
		if(!empty($q)){
			$data = $this->RM->vehicle_numbers($q, $limit = 20);
		}
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	//Select2 DR Number
	function select2_dr_number(){
		$q = $this->input->get('q');
		$data = $this->RM->dr_numbers($q, 20);
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	//Formvalidation callback functions
	function validate_date(){
		$data = FALSE;
		$to_date = $this->input->post('to_date');
		//printr($to_date);
		if($to_date){
			$from_date = get_date('Y-m-d', str_replace('/', '-', $this->input->post('from_date')));
			$to_date = get_date('Y-m-d', str_replace('/', '-', $to_date));
			if(strtotime($to_date) >= strtotime($from_date)){
				$data = TRUE;
			}else{
				$this->form_validation->set_message('validate_date', 'To Date must be  greater than or equal to From Date.');
			}
		}else{
			$this->form_validation->set_message('validate_date', 'To Date is required.');
		}
		return $data;
	}
	
	function ajax_dr_markets_n_clients(){
		$dr_number = $this->input->post('dr_number');
		$market_type = 2;
		$market_category = 3;
		
		$market_places = $this->db->select('dispatch_to')->where('dr_number', $dr_number)->where_in('status', array('Active', 'Dispatched'))->get(PRODUCT_DISPATCH)->result_array();
		$data['market_options'] = '';
		if(!empty($market_places)){
			$market_places = array_column($market_places, 'dispatch_to');
			$all_markets = $this->RM->get_market_places($market_category, $market_places);
			if(empty($all_markets)){
				$all_markets = $this->RM->get_market_places(2, $market_places);
			}
			if(!empty($all_markets)){
				$data['market_options'] .= '<option value="">All</option>';
				foreach($all_markets as $row){
					$data['market_options'] .= '<option value="'.$row['ID'].'">'.$row['name'].'</option>';
				}
			}else{
				$data['market_options'] .= '<option value="">No Markets Found</option>';
			}
		}else{
			$data['market_options'] .= '<option value="">No Markets Found</option>';
		}
		
		$clients = $this->db->select('client_id')->where('dr_number', $dr_number)->where_in('status', array('Active', 'Dispatched'))->get(SALE)->result_array();
		if(empty($clients)){
			$clients = $this->db->select('client_id')->where('dr_number', $dr_number)->get(PRODUCT_DISPATCH_BOX)->result_array();
		}
		$data['client_options'] = '';
		if(!empty($clients)){
			$clients = array_filter(array_column($clients, 'client_id'));
			$all_clients = !empty($clients) ? $this->RM->get_all_clients('', $clients) : array();
			if(!empty($all_clients)){
				$data['client_options'] .= '<option value="">All</option>';
				foreach($all_clients as $row){
					$c_id = !empty($row['ID']) ? $row['ID'] : (!empty($row['id']) ? $row['id'] : '');
					$data['client_options'] .= '<option value="'.$c_id.'">'.$row['company_name'].'</option>';
				}
			}else{
				$data['client_options'] .= '<option value="">All</option>';
			}
		}else{
			$data['client_options'] .= '<option value="">All</option>';
		}
		$response = array('status'=>'success', 'msg'=>'Data found.', 'data'=>$data);
		
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($response));
	}
	
	function ajax_get_market_clients(){
		$dr_number = $this->input->post('dr_number');
		$market_id = $this->input->post('market_id');
		$clients = $this->db->select('client_id')->get_where(SALE, array('dr_number'=>$dr_number, 'market_id'=>$market_id, 'status'=>'Active'))->result_array();
		//printr($clients);
		$market_type = 2;
		
		$data['client_options'] = '';
		if(!empty($market_id)){
			if(!empty($clients)){
				$clients = array_column($clients, 'client_id');
				$all_clients = $this->RM->get_all_clients($market_type, $clients, $market_id);
				if(!empty($all_clients)){
					$data['client_options'] .= '<option value="">All</option>';
					foreach($all_clients as $row){
						$data['client_options'] .= '<option value="'.$row['ID'].'">'.$row['company_name'].'</option>';
					}
				}else{
					$data['client_options'] .= '<option value="">No Customers Found</option>';
				}
			}else{
				$data['client_options'] .= '<option value="">No Customers Found</option>';
			}
		}else{
			$data['client_options'] .= '<option value="">No Customers Found</option>';
		}
		$response = array('status'=>'success', 'msg'=>'Data found.', 'data'=>$data);
		
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($response));
	}
	
	function __setFormRules($setRulesFor = ''){
		switch($setRulesFor){
			case 'ajax_production_report':
				$this->form_validation->set_rules('production_date', 'Date', 'trim|required');
			break;
			
			case 'ajax_fcw_detail_report':
				$this->form_validation->set_rules('from_date', 'From Date', 'trim|required');
				$this->form_validation->set_rules('to_date', 'To Date', 'trim|required|min_length[1]|callback_validate_date');
			break;
			
			case 'ajax_dr_for_driver':
				$this->form_validation->set_rules('dispatch_date', 'Dispatch Time', 'trim|required|min_length[1]');
				$this->form_validation->set_rules('market_id', 'Depot', 'trim|required|integer');
				$this->form_validation->set_rules('vehicle_number', 'Vehicle Number', 'trim|required|min_length[1]');
			break;
			
			case 'ajax_inward_outward':
				$this->form_validation->set_rules('closing_date', 'Date', 'trim|required');
				$this->form_validation->set_rules('market_id', 'Depot', 'trim|required|integer');
			break;
			case 'ajax_local_sale':
				$this->form_validation->set_rules('from_date', 'From Date', 'trim|required');
				$this->form_validation->set_rules('to_date', 'To Date', 'trim|required');
				$this->form_validation->set_rules('market_id', 'Point', 'trim|required|integer');
			break;
			case 'ajax_xbox_report':
				$this->form_validation->set_rules('dispatch_date', 'Dispatch Date', 'trim|required');
				$this->form_validation->set_rules('dr_number', 'DR Number', 'trim|required|min_length[1]');
				$this->form_validation->set_rules('market_id', 'Depot', 'trim|required|integer');
			break;
			
			case 'ajax_dr_summary':
				$this->form_validation->set_rules('from_date', 'From Date', 'trim|required');
				$this->form_validation->set_rules('to_date', 'To Date', 'trim|required|callback_validate_date');
			break;
			
			case 'ajax_comparison_report':
				//$this->form_validation->set_rules('dispatch_date', 'Dispatch Date', 'trim|required');
				//$this->form_validation->set_rules('sale_date', 'Sale Date', 'trim|required');
				$this->form_validation->set_rules('dr_number', 'DR Number', 'trim|required|min_length[1]');
				$this->form_validation->set_rules('market_id', 'Market', 'trim|integer');
				$this->form_validation->set_rules('client_id', 'Customer', 'trim|integer');
			break;
			
			case 'ajax_all_boxes_report':
				$this->form_validation->set_rules('production_date', 'Date', 'trim|required');
				$this->form_validation->set_rules('depot_id', 'Depot', 'trim|integer|greater_than[0]');
			break;
		}
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert_msg margin-5 padding-5"><button data-dismiss="alert" class="close">×</button><i class="fa fa-times-circle"></i> ', '</div>');
		return $this->form_validation->run($this);
	}
}



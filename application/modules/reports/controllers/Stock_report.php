<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_report extends MY_Controller {
	var $userID;
	var $stylesheet_array;
	var $scriptsrc_array;
	var $datatable_scripts;
	var $datatable_script_body;
	
	//market_category: 1=>Point, 2=>Depot, 3=>Outside
	//$market_type : Local Market = 1, Outside Market = 2
	function __construct(){
		parent::__construct();
		$this->userID = checkUserLogin();
		$this->load->model('stock_report_model', 'SRM');
		$this->stylesheet_array = array( base_url('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css'),
										 base_url('assets/plugins/select2/dist/css/select2.min.css'),
										 base_url('assets/plugins/DataTables/media/css/jquery.dataTables.min.css'),
										 site_url('reports/assets/css/reports.css')
										 );
		$this->scriptsrc_array = array( base_url('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js'),
										base_url('assets/plugins/select2/dist/js/select2.full.min.js'),
										base_url('assets/js/jQuery.print.min.js'),
										base_url('assets/js/jquery.table2excel.min.js'), // work with Class
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
		
	//1. Closing Stock Report [get_closing_stock]
	function closing_stock(){
		$data['content_view'] = 'reports/stock_report/closing_stock_v';
		$data['ajax_url'] = 'reports/stock_report/ajax_closing_stock';
		$data['page_title'] = 'Closing Stock Report';
		$data['table_class'] = 'table_table';
		$data['allDepot'] = $this->SRM->get_market_places(2);
		$this->template->set('stylesheet', $this->stylesheet_array);
		$this->template->set('scriptsrc', $this->scriptsrc_array);
		
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($data);
	}
	
	function ajax_closing_stock(){
		
		$data = array('status' => 'danger', 'message' => '<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> No data found, Please try again.</div>', 'data'=>'');
		$form_validation = $this->__setFormRules('ajax_closing_stock');
		if($form_validation){
			$closing_date = get_date('Y-m-d', str_replace('/', '-', $this->input->post('closing_date')));
			$market_id = $this->input->post('market_id');
			$report_data = $this->SRM->get_closing_stock($closing_date, $market_id);
			
			//printr($report_data);
			$search_date = $this->input->post('closing_date');
			$search_key =  $this->input->post('market_id');
			$tbody = $tfoot = '';
			
			if(!empty($report_data)){
				$t_production_qty = $t_production_wt = $t_tranfer_in_qty = $t_tranfer_in_wt =  $t_tranfer_out_qty = $t_tranfer_out_wt = $t_sale_qty = $t_sale_wt =  $t_free_sale_qty = 
				$t_free_sale_wt = $t_dispatched_qty = $t_dispatched_wt =  $t_prepared_box_qty = $t_prepared_box_wt = $t_remaining_qty = $t_remaining_wt = 
				$t_bachat_fish_qty = $t_bachat_fish_wt = $t_bachat_sorting_fish_wt = $t_bachat_shortage_wt = $t_opening_fish_qty = 
				$t_opening_fish_wt = $t_opening_pb_qty = $t_opening_pb_wt = $t_opening_sorting_fish_wt = $t_opening_shortage_wt = $t_destroyed_fish_qty = $t_destroyed_fish_wt = '0';	
				$i = 0;			
				foreach($report_data as $rep){ $i++;
					$class = 'class="record_data_yes"';
					if($rep['tranfer_in_wt'] == '0.00' && $rep['tranfer_out_wt'] == '0.00' && $rep['production_wt'] == '0.000' && $rep['opening_fish_wt'] == '0.000'){
						$class = 'class="record_data_no"';
					}
					
					//$bachat_sortage_wt = ($rep['bsort_fish_wt'] > 0) ? $rep['bsort_fish_wt'] - $rep['bachat_fish_wt'] : 0;
					$bachat_sortage_wt = $rep['bsort_fish_wt'] - $rep['bachat_fish_wt'];
					$rep['bachat_shortage_wt'] = $bachat_sortage_wt;
					
					$opening_shortage_wt = $rep['opening_sorting_fish_wt'] - $rep['opening_fish_wt'];
					$rep['opening_shortage_wt'] = $opening_shortage_wt;
					
					$outward_qty = $rep['sale_qty'] + $rep['free_sale_qty'] + $rep['dispatched_qty'] + $rep['tranfer_out_qty']  + $rep['prepared_box_qty'] + $rep['destroyed_fish_qty'];					
					$outward_wt = $rep['sale_wt'] + $rep['free_sale_wt'] + $rep['dispatched_wt'] + $rep['tranfer_out_wt']  + $rep['prepared_box_wt'] + $rep['destroyed_fish_wt'];
					
					$inward_qty = $rep['production_qty'] + $rep['opening_fish_qty'] + $rep['opening_pb_qty'] + $rep['tranfer_in_qty'];
					$inward_wt = $rep['production_wt'] + $rep['opening_fish_wt'] + $rep['opening_pb_wt'] + $rep['tranfer_in_wt'] + ($rep['opening_shortage_wt']) + ($rep['bachat_shortage_wt']);
					
					$rep['remaining_qty'] =  $inward_qty - $outward_qty;
					$rep['remaining_wt'] = round(($inward_wt - $outward_wt), 2);
										
					
					$tbody .='<tr '.$class.'>';
                    $tbody .='	  <td>'.$i.'</td>';
                    //$tbody .='    <td>'.str_replace(' ','<br>',$rep['category_name']).'</td>';
                    //$tbody .='    <td>'.$rep['fish_name'].'</td>';
                    $tbody .='    <td>'.$rep['code'].'</td>';
					
                    $tbody .='    <td>'.$rep['production_qty'].'</td>';
                    $tbody .='    <td class="success">'.round($rep['production_wt'],2).'</td>';
					
					$tbody .='    <td>'.$rep['tranfer_in_qty'].'</td>';
                    $tbody .='    <td class="success">'.round($rep['tranfer_in_wt'],2).'</td>';
					
					$tbody .='    <td>'.$rep['tranfer_out_qty'].'</td>';
                    $tbody .='    <td class="danger">'.round($rep['tranfer_out_wt'],2).'</td>';
					
					$tbody .='    <td>'.$rep['bachat_fish_qty'].'</td>';
					$tbody .='    <td>'.round($rep['bachat_fish_wt'],2).'</td>';
					$tbody .='    <td>'.round($rep['bsort_fish_wt'],2).'</td>';
					$tbody .='    <td class="success">'.round($rep['bachat_shortage_wt'],2).'</td>';
					
					$tbody .='    <td>'.$rep['opening_fish_qty'].'</td>';
					$tbody .='    <td class="success">'.round($rep['opening_fish_wt'],2).'</td>';
					$tbody .='    <td>'.round($rep['opening_sorting_fish_wt'],2).'</td>';
					$tbody .='    <td class="success">'.round($rep['opening_shortage_wt'],2).'</td>';
					
					$tbody .='    <td>'.$rep['opening_pb_qty'].'</td>';
					$tbody .='    <td class="success">'.round($rep['opening_pb_wt'],2).'</td>';
					
					$tbody .='    <td>'.$rep['sale_qty'].'</td>';
                    $tbody .='    <td class="danger">'.round($rep['sale_wt'],2).'</td>';
					
					$tbody .='    <td>'.$rep['free_sale_qty'].'</td>';
                    $tbody .='    <td class="danger">'.round($rep['free_sale_wt'],2).'</td>';
					
					$tbody .='    <td>'.$rep['dispatched_qty'].'</td>';
                    $tbody .='    <td class="danger">'.round($rep['dispatched_wt'],2).'</td>';
					
					$tbody .='    <td>'.$rep['prepared_box_qty'].'</td>';
                    $tbody .='    <td class="danger">'.round($rep['prepared_box_wt'],2).'</td>';
					
					$tbody .='    <td>'.$rep['destroyed_fish_qty'].'</td>';
                    $tbody .='    <td class="danger">'.round($rep['destroyed_fish_wt'],2).'</td>';
															
					$tbody .='    <td>'.$rep['remaining_qty'].'</td>';
					$tbody .='    <td>'.round($rep['remaining_wt'],2).'</td>';
					
                    $tbody .='</tr>';
										
					$t_production_qty 			+= $rep['production_qty'];
					$t_production_wt 			+= $rep['production_wt'];
					
					$t_tranfer_in_qty 			+= $rep['tranfer_in_qty'];
					$t_tranfer_in_wt 			+= $rep['tranfer_in_wt'];
					
					$t_tranfer_out_qty 			+= $rep['tranfer_out_qty'];
					$t_tranfer_out_wt 			+= $rep['tranfer_out_wt'];
					
					$t_bachat_fish_qty			+= $rep['bachat_fish_qty'];
					$t_bachat_fish_wt 			+= $rep['bachat_fish_wt'];
					$t_bachat_sorting_fish_wt 	+= $rep['bsort_fish_wt'];
					$t_bachat_shortage_wt		+= $rep['bachat_shortage_wt'];
					
					$t_opening_fish_qty			+= $rep['opening_fish_qty'];
					$t_opening_fish_wt			+= $rep['opening_fish_wt'];
					$t_opening_sorting_fish_wt 	+= $rep['opening_sorting_fish_wt'];
					$t_opening_shortage_wt		+= $rep['opening_shortage_wt'];
					
					$t_opening_pb_qty 			+= $rep['opening_pb_qty'];
					$t_opening_pb_wt			+= $rep['opening_pb_wt'];
										
					$t_sale_qty 				+= $rep['sale_qty'];
					$t_sale_wt 					+= $rep['sale_wt'];
					
					$t_free_sale_qty 			+= $rep['free_sale_qty'];
					$t_free_sale_wt 			+= $rep['free_sale_wt'];
					
					$t_dispatched_qty 			+= $rep['dispatched_qty'];
					$t_dispatched_wt 			+= $rep['dispatched_wt'];
					
					$t_prepared_box_qty 		+= $rep['prepared_box_qty'];
					$t_prepared_box_wt 			+= $rep['prepared_box_wt'];
					
					$t_destroyed_fish_qty 		+= $rep['destroyed_fish_qty'];
					$t_destroyed_fish_wt 		+= $rep['destroyed_fish_wt'];
										
					$t_remaining_qty 			+= $rep['remaining_qty'];
					$t_remaining_wt 			+= $rep['remaining_wt'];
					
				}
				
				$tfoot .='<th colspan="2">Summary</th>';
				
				$tfoot .='<th>'.$t_production_qty.'</th>';
				$tfoot .='<th class="success">'.round($t_production_wt,2).'</th>';
				
				$tfoot .='<th>'.$t_tranfer_in_qty.'</th>';
				$tfoot .='<th class="success">'.round($t_tranfer_in_wt,2).'</th>';
				
				$tfoot .='<th>'.$t_tranfer_out_qty.'</th>';
				$tfoot .='<th class="danger">'.round($t_tranfer_out_wt,2).'</th>';
							
				$tfoot .='<th>'.$t_bachat_fish_qty.'</th>';
				$tfoot .='<th>'.round($t_bachat_fish_wt,2).'</th>';
				$tfoot .='<th>'.round($t_bachat_sorting_fish_wt,2).'</th>';
				$tfoot .='<th class="success">'.round($t_bachat_shortage_wt,2).'</th>';
				
				$tfoot .='<th>'.$t_opening_fish_qty.'</th>';
				$tfoot .='<th class="success">'.round($t_opening_fish_wt,2).'</th>';
				$tfoot .='<th>'.round($t_opening_sorting_fish_wt,2).'</th>';
				$tfoot .='<th class="success">'.round($t_opening_shortage_wt,2).'</th>';
				
				$tfoot .='<th>'.$t_opening_pb_qty.'</th>';
				$tfoot .='<th class="success">'.round($t_opening_pb_wt,2).'</th>';
				
				$tfoot .='<th>'.$t_sale_qty.'</th>';
				$tfoot .='<th class="danger">'.round($t_sale_wt,2).'</th>';
				
				$tfoot .='<th>'.$t_free_sale_qty.'</th>';
				$tfoot .='<th class="danger">'.round($t_free_sale_wt,2).'</th>';
				
				$tfoot .='<th>'.$t_dispatched_qty.'</th>';
				$tfoot .='<th class="danger">'.round($t_dispatched_wt,2).'</th>';
				
				$tfoot .='<th>'.$t_prepared_box_qty.'</th>';
				$tfoot .='<th class="danger">'.round($t_prepared_box_wt,2).'</th>';
				
				$tfoot .='<th>'.$t_destroyed_fish_qty.'</th>';
                $tfoot .='<th class="danger">'.round($t_destroyed_fish_wt,2).'</th>';
				
				$tfoot .='<th>'.$t_remaining_qty.'</th>';
				$tfoot .='<th>'.round($t_remaining_wt,2).'</th>';
								
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
	
		
	//2. Destroyed Report [get_closing_stock]
	function destroyed(){
		$data['content_view'] = 'reports/stock_report/destroyed_v';
		$data['ajax_url'] = 'reports/stock_report/ajax_destroyed';
		$data['page_title'] = 'Destroyed Report';
		$data['allDepot'] = $this->SRM->get_market_places(2);
		$data['table_class'] = 'report_content_container';
		$this->template->set('stylesheet', $this->stylesheet_array);
		$this->template->set('scriptsrc', $this->scriptsrc_array);
		
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($data);
	}
	
	function ajax_destroyed(){
		
		$data = array('status' => 'danger', 'message' => '<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> No data found, Please try again.</div>', 'data'=>'');
		$form_validation = $this->__setFormRules('ajax_destroyed_stock');
		if($form_validation){
			$from_date = get_date('Y-m-d', str_replace('/', '-', $this->input->post('from_date')));
			$to_date = get_date('Y-m-d', str_replace('/', '-', $this->input->post('to_date')));
			$market_id = $this->input->post('market_id');
			$report_data = $this->SRM->get_destroyed_stock($from_date, $to_date, $market_id);
			
			//printr($report_data);
			$search_date = $this->input->post('from_date').' - '.$this->input->post('to_date');
			$search_key =  $this->input->post('market_id_text');
			$tbody = $tfoot = '';
			if(!empty($report_data)){
				$t_quantity = $t_weight = '0';	
				$i = 0;			
				foreach($report_data as $rep){ $i++;
					$class = 'class="record_data_yes"';
					if($rep['production_qty'] == 0 && $rep['production_wt'] == 0){
						$class = 'class="record_data_no"';
					}
					$tbody .='<tr '.$class.'>';
                    $tbody .='	  <td>'.$i.'</td>';
                    $tbody .='    <td>'.$rep['market_name'].'</td>';
					$tbody .='    <td>'.$rep['category_name'].'</td>';
                    $tbody .='    <td>'.$rep['fish_name'].'</td>';
                    $tbody .='    <td>'.$rep['code'].'</td>';
                    $tbody .='    <td>'.$rep['production_qty'].'</td>';
                    $tbody .='    <td>'.$rep['production_wt'].'</td>';
                    $tbody .='</tr>';
					
					$t_quantity 		+= $rep['production_qty'];
					$t_weight 			+= $rep['production_wt'];
				}
				
				$tfoot .='<th colspan="5">Summary</th>';
				$tfoot .='<th>'.$t_quantity.'</th>';
				$tfoot .='<th>'.$t_weight.'</th>';
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
	
	//3.  Closing Stock Report [get_closing_stock]
	function free_sale(){
		$data['content_view'] = 'reports/stock_report/free_sale_v';
		$data['ajax_url'] = 'reports/stock_report/ajax_free_sale';
		$data['page_title'] = 'Free Sale Report';
		$data['table_class'] = 'report_content_container';
		$data['market_category'] = $this->SRM->get_market_category(1);
		$this->template->set('stylesheet', $this->stylesheet_array);
		$this->template->set('scriptsrc', $this->scriptsrc_array);
		
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($data);
	}
	
	function ajax_free_sale(){
		$data = array('status' => 'danger', 'message' => '<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> No data found, Please try again.</div>', 'data'=>'');
		$form_validation = $this->__setFormRules('ajax_free_sale');
		if($form_validation){
			$post = $this->input->post();
			$from_date = get_date('Y-m-d', str_replace('/', '-', $this->input->post('from_date')));
			$to_date = get_date('Y-m-d', str_replace('/', '-', $this->input->post('to_date')));
			$market_category = $this->input->post('market_category');
			
			$report_data = $this->SRM->get_free_sale_data($from_date, $to_date, $market_category);
			
			//printr($report_data);
			$search_date = $this->input->post('from_date').' - '.$this->input->post('to_date');
			$search_key = $this->input->post('market_category_text');
			$tbody = $tfoot = '';
			if(!empty($report_data)){
				$t_quantity = $t_weight = '0';	
				$i = 0;			
				foreach($report_data as $rep){ $i++;
					$class = 'class="record_data_yes"';
					if($rep['fish_quantity'] == 0 && $rep['fish_weight'] == 0){
						$class = 'class="record_data_no"';
					}
					$tbody .='<tr '.$class.'>';
                    $tbody .='	  <td>'.$i.'</td>';
					$tbody .='    <td>'.$rep['market_name'].'</td>';                    
					$tbody .='    <td>'.$rep['remark'].'</td>';
					$tbody .='    <td>'.$rep['client_name'].'</td>';
                    $tbody .='    <td>'.$rep['fish_category_name'].'</td>';
                    $tbody .='    <td>'.$rep['fish_name'].'</td>';
					$tbody .='    <td>'.$rep['fish_code'].'</td>';
                    $tbody .='    <td>'.$rep['fish_quantity'].'</td>';
                    $tbody .='    <td>'.$rep['fish_weight'].'</td>';
                    $tbody .='</tr>';
					
					$t_quantity 		+= $rep['fish_quantity'];
					$t_weight 			+= $rep['fish_weight'];
				}
				
				$tfoot .='<th colspan="7">Summary</th>';
				$tfoot .='<th>'.$t_quantity.'</th>';
				$tfoot .='<th>'.$t_weight.'</th>';
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
	
	
	//4. Point Bachat Report [get_point_bachat]
	function point_bachat(){
		$data['content_view'] = 'reports/stock_report/point_bachat_v';
		$data['ajax_url'] = 'reports/stock_report/ajax_point_bachat';
		$data['page_title'] = 'Point Bachat Report';
		$data['allDepot'] = $this->SRM->get_market_places(2);
		$data['table_class'] = 'report_content_container';
		$this->template->set('stylesheet', $this->stylesheet_array);
		$this->template->set('scriptsrc', $this->scriptsrc_array);
		
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($data);
	}
	
	function update_boxnumber(){
		$result = $this->db->select('pc.carret_number, pb.box_number')->from(PRODUCTION_CARRET .' pc')->join(PRODUCT_BOX.' pb', 'pb.carret_number = pc.carret_number', 'LEFT')->where(array('pb.box_number !=' =>  ""))->get()->result_array();
		//printr($result);
		foreach($result as $crr){
			//$this->db->where('carret_number', $crr['carret_number'])->update(PRODUCTION_CARRET, array('box_number' => $crr['box_number']));
			//$this->db->where('carret_number', $crr['carret_number'])->update(PRODUCTION_CARRET_ITEMS, array('box_number' => $crr['box_number']));
		}
	}
	
	function ajax_point_bachat(){
		
		$data = array('status' => 'danger', 'message' => '<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> No data found, Please try again.</div>', 'data'=>'');
		$form_validation = $this->__setFormRules('ajax_point_bachat');
		if($form_validation){
			$bachat_date = get_date('Y-m-d', str_replace('/', '-', $this->input->post('bachat_date')));
			$market_id = $this->input->post('market_id');
			$report_data = $this->SRM->get_point_bachat_data($bachat_date, $market_id);
			
			//printr($report_data);
			$search_date = $this->input->post('bachat_date');
			$search_key =  $this->input->post('market_id_text');
			$tbody = $tfoot = '';
			if(!empty($report_data)){
				$t_quantity = $t_weight = '0';	
				$i = 0;			
				foreach($report_data as $rep){ $i++;
					$class = 'class="record_data_yes"';
					if($rep['production_qty'] == 0 && $rep['production_wt'] == 0){
						$class = 'class="record_data_no"';
					}
					$tbody .='<tr '.$class.'>';
                    $tbody .='	  <td>'.$i.'</td>';
                    $tbody .='    <td>'.$rep['market_name'].'</td>';
					$tbody .='    <td>'.$rep['category_name'].'</td>';
                    $tbody .='    <td>'.$rep['fish_name'].'</td>';
                    $tbody .='    <td>'.$rep['fish_code'].'</td>';
                    $tbody .='    <td>'.$rep['production_qty'].'</td>';
                    $tbody .='    <td>'.$rep['production_wt'].'</td>';
                    $tbody .='</tr>';
					
					$t_quantity 		+= $rep['production_qty'];
					$t_weight 			+= $rep['production_wt'];
				}
				
				$tfoot .='<th colspan="5">Summary</th>';
				$tfoot .='<th>'.$t_quantity.'</th>';
				$tfoot .='<th>'.$t_weight.'</th>';
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
	
	//Formvalidation callback functions
	function validate_date(){
		$data = FALSE;
		$from_date = get_date('Y-m-d', str_replace('/', '-', $this->input->post('from_date')));
		$to_date = get_date('Y-m-d', str_replace('/', '-', $this->input->post('to_date')));
		if(strtotime($to_date) >= strtotime($from_date)){
			$data = TRUE;
		}
		return $data;
	}
	
	function __setFormRules($setRulesFor = ''){
		switch($setRulesFor){
			case 'ajax_closing_stock':
				$this->form_validation->set_rules('closing_date', 'Closing Date', 'trim|required');
				$this->form_validation->set_rules('market_id', 'Depot Name', 'trim|required');
			break;
			case 'ajax_destroyed_stock':
				$this->form_validation->set_rules('from_date', 'From Date', 'trim|required');
				$this->form_validation->set_rules('to_date', 'To Date', 'trim|required');
				$this->form_validation->set_rules('market_id', 'Depot Name', 'trim|required');
			break;
			case 'ajax_free_sale':
				$this->form_validation->set_rules('from_date', 'From Date', 'trim|required');
				$this->form_validation->set_rules('to_date', 'To Date', 'trim|required');
				$this->form_validation->set_rules('market_category', 'Market Type', 'trim|required');
			break;
			case 'ajax_point_bachat':
				$this->form_validation->set_rules('bachat_date', 'Bachat Date', 'trim|required');
				$this->form_validation->set_rules('market_id', 'Depot Name', 'trim|required');
			break;
			
			
		}
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert_msg margin-5 padding-5"><button data-dismiss="alert" class="close">×</button><i class="fa fa-times-circle"></i> ', '</div>');
		return $this->form_validation->run($this);
	}
	
}



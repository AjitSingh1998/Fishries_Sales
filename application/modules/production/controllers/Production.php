<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Production extends MY_Controller {
	// url_encryptor("encrypt", 1);
	// url_encryptor("decrypt", 1);
	//$market_type : Local Market=>1, Outside Market=>2
	//market_category: Point=>1, Depot=>2, Outside=>3
	var $userID, $userGroup, $actions, $session_year;
	
	public function __construct(){
		parent::__construct();
		$this->userID = checkUserLogin();
		$this->userGroup = loginUserInfo('group_id');
		$this->load->helper('production');
		$this->load->model('production_model', 'PM');
		$this->session_year = (defined('SESSION_YEAR') && !empty(constant('SESSION_YEAR'))) ? SESSION_YEAR : date('Y')."-".(date('y')+1);
	}
	
	public function index(){
		redirect('dashboard');
	}
	
	public function run_sql(){
		$sql = "ALTER TABLE `psac_daily_closing_stock` ADD `pb_qty` INT NOT NULL COMMENT 'Product Box Quantity' AFTER `d_wt`, ADD `pb_wt` FLOAT(10,3) NOT NULL COMMENT 'Product Box Weight' AFTER `pb_qty`;";
		$result = $this->db->query($sql);
		if($result){
			echo 'Updated';
		}else{
			echo 'Failed';
		}
		die('Operation Completed');
		
		/*$this->db->select('pb.box_id, pb.box_number, mp.code as mp_code');
		$this->db->from(PRODUCT_BOX.' pb');
		$this->db->join(MARKET_PLACE.' mp', 'mp.ID=pb.depot_id', 'LEFT');
		$result = $this->db->get()->result_array();
		if(!empty($result)){
			foreach($result as $res){
				$mp_code = strtoupper(substr($res['mp_code'], 0, 1));
				$pre_upd_data = array('box_number'=>$mp_code.$res['box_id']);
				$this->db->where('box_number',$res['box_number'])->update(PRODUCT_BOX, $pre_upd_data);
				$this->db->where('box_number',$res['box_number'])->update(PRODUCT_BOX_ITEMS, $pre_upd_data);
				$this->db->where('box_number',$res['box_number'])->update(PRODUCTION_CARRET, $pre_upd_data);
				$this->db->where('box_number',$res['box_number'])->update(PRODUCTION_CARRET_ITEMS, $pre_upd_data);
			}
		}*/
		
		$sql = 'SELECT carret_number,  box_number, name FROM `psac_production_carret` LEFT JOIN psac_market_place ON id = market_id WHERE depot_id = 9 AND packing_date = "2018-08-21" ORDER BY `psac_production_carret`.`carret_number` ASC';
		$result = $this->db->query($sql)->result_array();
		foreach($result as $carret){
			echo $carret['carret_number'] .'==='.$carret['name'].'==='.$carret['box_number']."<br>";
		}
	}
	
//==============================================================================
	//Dailyproduction listing
	public function daily_production($type="point"){
		$this->actions = checkUserPermission('production/daily_production/'.$type, $this->uri->segment(4));
		if(!empty($this->uri->segment(3)) && ($this->uri->segment(3) == 'add' || $this->uri->segment(3) == 'edit' || $this->uri->segment(3) == 'view')){redirect(site_url('production/daily_production'));}
		$crud = new grocery_CRUD();
		$crud->set_theme('bootstrap');
		$crud->unset_jquery();
		$crud->unset_bootstrap();
		$crud->unset_common_search();		
		$crud->unset_delete();		
		
		if(!in_array('add', $this->actions)){
			$crud->unset_add();
		}else{
			$crud->set_add_url_path(site_url('production/add_dp/add/'.$type));
			$crud->set_add_button_class('btn btn-primary');
		}
		
		if(!in_array('edit', $this->actions)){
			$crud->unset_edit();
		}else{
			//$crud->set_edit_button_text('Sale');
			$crud->set_edit_url_path(site_url('production/add_dp/edit/'.$type));
			$crud->set_edit_button_class('btn btn-default');
		}
		
		if(in_array('view', $this->actions)){
			$crud->set_read_url_path(site_url('production/view_production'));
			$crud->set_read_button_class('btn btn-default loadActionForm');
		}else{
			$crud->unset_read();
		}
		
		if(in_array('delete', $this->actions)){			
			//$crud->add_action('Delete', 'triggerBulkDelete text-danger', site_url('bulk_action/action/mark_delete'), 'fa fa-trash', array($this, '__callbackDeleteActionButton'), 'dialogbox');			
			//$crud->add_bulk_action('Delete', site_url('bulk_action/action/mark_delete'), ' text-danger', 'fa fa-trash', 'status');		
		}
		
		if(!in_array('export', $this->actions)){
			$crud->unset_export();
		}
		if(!in_array('print', $this->actions)){
			$crud->unset_print();
		}
		/*if(in_array('lock', $this->actions)){
			$crud->add_action('Lock Data', 'triggerBulkDelete text-danger','','fa fa-lock', array($this,'__callbackLockActionButton'), 'dialogbox');
			$crud->add_bulk_action('Lock Data', site_url('bulk_action/action/lock'), ' text-danger', 'fa fa-lock', 'editable');		
		}*/
		
		if($crud->getState() == 'ajax_list'){
			$postData = $this->input->post();
			if(isset($postData['search_field']) && !empty($postData['search_field'])){
				foreach($postData['search_field'] as $key=>$value){
					if($value == 'production_date'){
						$_POST['search_text'][$key] = get_date('Y-m-d', str_replace('/', '-', $postData['search_text'][$key]));
					}
				}
			}
		}
		
		$data = array('page_title'=> ucfirst($type).' Production', 'content_view'=>'production/summary_production_v');
		
		$crud->set_subject($data['page_title']);
		$crud->set_table(PRODUCTION);
		$crud->set_relation('market_id', MARKET_PLACE, 'name');
		$crud->set_relation('depot_id', MARKET_PLACE, 'name');
		$crud->where(array(PRODUCTION.'.editable !='=>'Lock', PRODUCTION.'.status !='=>'Deleted', 'LOWER('.PRODUCTION.'.production_type)'=>strtolower($type)));
		
		if(strtolower($type) != "transfer"){
			if(in_array('view_all', $this->actions)){	
				//For super admin user
				$crud->set_relation('added_by', ADMINISTRATOR, '{first_name} {last_name}');
				$crud->columns('production_date', 'depot_id', 'market_id', 'point_wt', 'forfeiture_wt', 'forfeiture_rt_wt', 'total_pt_wt', 'fresh_wt', 'rotten_wt', 'destroyed_wt', 'depot_wt', 'total_wt', 'jhinga_point_wt', 'jhinga_depot_wt','added_by');
			}else{
				//For all other users
				$crud->where(array(PRODUCTION.'.added_by'=>$this->userID));
				$crud->columns('production_date', 'depot_id', 'market_id', 'point_wt', 'forfeiture_wt', 'forfeiture_rt_wt', 'total_pt_wt', 'fresh_wt', 'rotten_wt', 'destroyed_wt', 'depot_wt', 'total_wt', 'jhinga_point_wt', 'jhinga_depot_wt');
			}
			$crud->order_by('production_date', 'DESC');
			$crud->display_as(array('client_id'=>'Client', 'depot_id'=>'Depot', 'market_id'=>'Point', 'total_wt'=>'Total Weight'));
			$crud->display_summary( 'point_wt', 'forfeiture_wt', 'forfeiture_rt_wt', 'total_pt_wt', 'fresh_wt', 'rotten_wt', 'destroyed_wt', 'depot_wt', 'total_wt', 'jhinga_point_wt', 'jhinga_depot_wt');
		}else{
			if(in_array('view_all', $this->actions)){	
				//For super admin user
				$crud->set_relation('added_by', ADMINISTRATOR, '{first_name} {last_name}');
				$crud->columns('production_date', 'market_id', 'depot_id',  'point_wt', 'total_pt_wt', 'fresh_wt', 'rotten_wt', 'destroyed_wt', 'depot_wt', 'total_wt', 'added_by');
			}else{
				//For all other users
				$crud->where(array(PRODUCTION.'.added_by'=>$this->userID));
				$crud->columns('production_date', 'market_id', 'depot_id', 'point_wt', 'total_pt_wt', 'fresh_wt', 'rotten_wt', 'destroyed_wt', 'depot_wt', 'total_wt');
			}
			$crud->order_by('production_date', 'DESC');
			$crud->display_as(array('client_id'=>'Client', 'depot_id'=>'To Depot', 'market_id'=>'From Depot', 'total_wt'=>'Total Weight'));
			$crud->display_summary( 'point_wt', 'total_pt_wt', 'fresh_wt', 'rotten_wt', 'destroyed_wt', 'depot_wt', 'total_wt');
		}
		//$crud->callback_column('sale_date', array($this, '__callaback_display_datetime'));
		$output = $crud->render();
		$data['stock'] = $this->PM->get_stock(get_date('Y-m-d'));
		$outputData = array_merge((array)$output, $data);
		$this->template->set('stylesheet', array(site_url('production/assets/css/panel.css')));
		$this->template->set('scriptsrc', array(site_url('setup/assets/js/setup.js')));
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($outputData);
	}
	
	//Add, Edit, view point sale
	public function add_dp($action_mode = 'add', $type="point", $production_id = NULL){
		$this->actions 				= checkUserPermission('production/daily_production/'.$type, $this->uri->segment(3));
		$data['go_back_url'] 		= site_url('production/daily_production/'.$type);
		$data['action_mode'] 		= 'add';
		$data['all_depots'] 		= $this->PM->get_market_places(2);
		$data['all_points'] 		= $this->PM->get_market_places(1, '', '', $type);
		$data['all_fishes'] 		= $this->PM->get_all_fishes();
		$data['page_title'] 		= ucfirst($type).' Production '.$this->session_year;
		$data['content_view'] 		= 'production/daily_production_v';
		$data['disabled_input'] 	= '';
		$data['datepicker_class'] 	= 'datepicker';
		$data['action_mode'] 		= (!empty($action_mode) ? $action_mode : 'add');
		$data['production_id'] 		= '';
		$data['item_table'] 		= 'hidden';
		$data['production_data'] 	= array( 'production_id' 	=> '',
											 'production_date' 	=> '',
											 'production_type' 	=> ucfirst($type),
											 'depot_id' 		=> '',
											 'depot_code'		=> '',
											 'market_id' 		=> '',
											 'mp_code' 			=> '',
											 'point_wt'			=> '',
											 'forfeiture_wt' 	=> '',
											 'forfeiture_rt_wt' => '',
											 'total_pt_wt' 		=> '',
											 'depot_wt' 		=> '',
											 'total_wt' 		=> '',
											 'surplus_wt' 		=> '',
											 'surplus_perc' 	=> '',
											 'jhinga_point_wt' 	=> '',
											 'jhinga_depot_wt' 	=> '',
											 'remark' 			=> ''
											 );
		
		$data['production_items']	= array();
		
		if(($action_mode == 'view' || $action_mode == 'edit') && $production_id != NULL){
			$data['action_mode'] 		= $action_mode;
			$data['production_id'] 		= $production_id;
			$data['disabled_input'] 	= 'readonly="readonly"';
			$data['datepicker_class'] 	= '';
			$data['item_table'] 		= '';
			$data['production_data'] 	= $this->PM->get_production_data($production_id);
			
			if(empty($data['production_data']) || (empty($data['production_data']['id']) && empty($data['production_data']['production_id']))){
				$this->messageci->set('Invalid request, Please try again.', 'error') ;
				redirect(site_url('production/daily_production/'.$type));
			}
			$data['all_data'] 	= $this->PM->get_production_items($production_id);
		}
		
		$this->template->set('stylesheet', array(base_url('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css'),
												 base_url('assets/plugins/select2/dist/css/select2.min.css')
												 ));
		$this->template->set('scriptsrc', array(base_url('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js'),
												base_url('assets/plugins/select2/dist/js/select2.full.min.js'),
												base_url('assets/plugins/bootstrap-typeahead/bootstrap3-typeahead.min.js'),
												base_url('assets/js/jQuery.print.min.js'),
												base_url('assets/js/table2excel.custom.js'),
												site_url('production/assets/js/daily_production.js'),
												site_url('production/assets/js/production_carret_box.js')
												));
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($data);
	}
	
	//add-edit daily production data
	public function ajax_save_dp_data(){
		$data = array('status' => 'failed', 'message' => 'No data posted.', 'data' => '');
		$form_valid = $this->__setFormRules('add_production');
		if($form_valid){
			$post 			= $this->input->post();
			$action_mode 	= $this->input->post('action_mode');
			$production_id 	= $this->input->post('production_id');
			$production_type= $this->input->post('production_type');
			$depot_id		= $this->input->post('depot_id');
			$market_id		= $this->input->post('market_id');
			$production_date= get_datetime('Y-m-d', str_replace('/', '-', $this->input->post('production_date')));
			
			$prepData = array('production_date' 	=> $production_date,
							  'production_type' 	=> $this->input->post('production_type'),
							  'depot_id' 			=> $depot_id,
							  'market_id' 			=> $market_id,
							  'point_wt'			=> $this->input->post('point_wt'),
							  'forfeiture_wt' 		=> $this->input->post('forfeiture_wt') ? $this->input->post('forfeiture_wt') : "0.000",
							  'forfeiture_rt_wt' 	=> $this->input->post('forfeiture_rt_wt') ? $this->input->post('forfeiture_rt_wt') : "0.000",
							  'total_pt_wt' 		=> $this->input->post('total_pt_wt'),
							  'jhinga_point_wt' 	=> $this->input->post('jhinga_point_wt') ? $this->input->post('jhinga_point_wt') : "0.000",
							  'jhinga_depot_wt' 	=> $this->input->post('jhinga_depot_wt') ? $this->input->post('jhinga_depot_wt') : "0.000",
							  'remark' 				=> $this->input->post('remark'),
							  'added_by'			=> $this->userID,
							  'added_date'			=> get_datetime('Y-m-d H:i:s'),
							  'action_microtime'	=> microtime(true), 'source' => SOURCE
							  );
			
			//INSERT or UPDATE only if date and point is not already available
			$exist_id = $this->PM->check_production_available($market_id, $production_date, $production_type, $depot_id);
			if($exist_id && $production_id != $exist_id){
				$data['status']  = 'failed';
				$data['message'] = '<div class="alert alert-danger" style="padding: 5px !important;"> 
										<button class="close" data-dismiss="alert">×</button> 
										Error: There is already added data on selected date and point
										Click <a target="_blank" href="'.site_url('production/add_dp/edit/'.strtolower($production_type).'/'.$exist_id).'" class="btn btn-primary btn-xs">here</a> to view.
									</div>';
			}else{
				if($action_mode=='edit'){
					//UPDATE				
					unset($prepData['added_date'], $prepData['added_by']);
					$prepData['updated_date'] 	= get_datetime('Y-m-d H:i:s');
					$prepData['updated_by'] 	= $this->userID;
					$result = $this->db->where('production_id', $production_id)->update(PRODUCTION, $prepData);
					if($result){
						$upd_data = array('packing_date'=>$production_date, 'depot_id'=>$depot_id, 'market_id'=>$market_id, 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime' => microtime(true));
						
						$this->db->where('production_id', $production_id)->update(PRODUCTION_CARRET, $upd_data);
						$this->db->where('production_id', $production_id)->update(PRODUCTION_CARRET_ITEMS, $upd_data);
						
						$data['action_mode'] 	= 'edit';
						$data['status']			= 'success';
						$data['message'] 		= 'Data updated successfully.';
						$data['redirect_url'] 	= site_url('production/add_dp/edit/'.strtolower($production_type).'/'.$production_id);
					}else{
						$data['status'] 	= 'danger';
						$data['message'] 	= 'Failed to update. Please try again.';
					}
				}elseif($action_mode=='add'){
					//INSERT
					$this->db->insert(PRODUCTION, $prepData);
					$insert_id = $this->db->insert_id();
					if($insert_id){
						$type_prefix = strtoupper(substr($production_type, 0, 2));
						if(strtolower($production_type) == 'point') $type_prefix = 'PT';
						else if(strtolower($production_type) == 'depot') $type_prefix = 'DP';
						else if(strtolower($production_type) == 'transfer') $type_prefix = 'TR';
						else if(strtolower($production_type) == 'bachat') $type_prefix = 'BACHAT';
						else if(strtolower($production_type) == 'stock') $type_prefix = 'ST';
						$prod_code = 'PROD-' . $type_prefix . '-' . sprintf("%03d", $insert_id);
						$this->db->where('id', $insert_id)->update(PRODUCTION, array('production_id' => $prod_code));
						
						$data['status'] 		= 'success';
						$data['message'] 		= 'Data inserted successfully.';
						$data['redirect_url'] 	= site_url('production/add_dp/edit/'.strtolower($production_type).'/'.$insert_id);
					}else{
						$data['status'] 	= 'danger';
						$data['message'] 	= 'Failed to insert. Please try again.';
					}
				}
			}
		}else{
			$data['status']  = 'failed';
			$data['message'] = validation_errors();
		}
		
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	//daily production edit mode
	public function ajax_dp_edit_mode($production_id){
		$data['action_mode'] 		= 'edit';
		$data['production_id'] 		= $production_id;
		$data['production_data'] 	= $this->PM->get_production_data($production_id);
		$data['production_type']    = $data['production_data']['production_type'];
		$data['all_depots'] 		= $this->PM->get_market_places(2);
		$data['all_points'] 		= $this->PM->get_market_places(1, '', '', strtolower($data['production_type']));
		$data['page_title'] 		= 'Edit '.$data['production_type'].' Production '.$this->session_year;
		$data['datepicker_class'] 	= 'datepicker';
		$setup_form = $this->load->view('ajax_dp_edit_mode_v', $data, true);
		$data = array('status'=>'success','data'=>$setup_form);
		$this->__return_json_output($data);
	}
	
	// Get next box number
	public function ajax_get_next_box_number(){
		$bx_res = $this->db->query("SELECT box_number FROM psac_product_box WHERE box_number REGEXP '^BOX-[0-9]+$' ORDER BY CAST(SUBSTRING(box_number, 5) AS UNSIGNED) DESC LIMIT 1")->row_array();
		$next_num = 101;
		if(!empty($bx_res['box_number']) && preg_match('/BOX-(\d+)/', $bx_res['box_number'], $m)){
			$next_num = intval($m[1]) + 1;
		}
		$next_box = 'BOX-' . $next_num;
		echo json_encode(array('status' => 'success', 'box_number' => $next_box));
	}
	
	//save daily production item
	public function ajax_save_carret($s_no = NULL){
		$response_data = array('status' => 'failed', 'message' => 'Invalid Request.');	
		
		// If make_box is Yes and box_number is empty, generate it before validation
		$make_box   = ($this->input->post('make_box') == "Yes") ? "Yes" : "No";
		$box_number = trim($this->input->post('box_number'));
		if($make_box === "Yes" && empty($box_number)){
			$bx_res = $this->db->query("SELECT box_number FROM psac_product_box WHERE box_number REGEXP '^BOX-[0-9]+$' ORDER BY CAST(SUBSTRING(box_number, 5) AS UNSIGNED) DESC LIMIT 1")->row_array();
			$next_num = 101;
			if(!empty($bx_res['box_number']) && preg_match('/BOX-(\d+)/', $bx_res['box_number'], $m)){
				$next_num = intval($m[1]) + 1;
			}
			$box_number = 'BOX-' . $next_num;
			$_POST['box_number'] = $box_number;
		}
		
		$form_valid = $this->__setFormRules('save_carret');
		if($form_valid){
			$post 		= $this->input->post();
			$make_box   = ($this->input->post('make_box') == "Yes") ? "Yes" : "No";
			$box_number = trim($this->input->post('box_number'));
			if($make_box === "Yes"){
				$box_existance = $this->PM->checkBoxExistance();
				if($box_existance == 'exist'){
					$response_data['status'] = 'error';
					$response_data['message'] = '<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> '.$box_number. ' box is already exist.</div>';
					$this->__return_json_output($response_data);
				}
			}
			
			$action_mode 		= $this->input->post('action_mode');
			$production_id 		= $this->input->post('production_id');
			$market_id 			= $this->input->post('market_id');
			$mp_code 			= $this->input->post('mp_code');
			$depot_id			= $this->input->post('depot_id');
			$packing_date 		= $this->input->post('production_date'); //this date input format is Y-m-d
			$production_type	= $this->input->post('production_type');
			$stock_type			= "Current";
			if($production_type == "Stock"){
				$stock_type		= "Opening";
			}elseif($production_type == "Bachat"){
				$stock_type		= "Bachat";
			}
			
			$box_weight 		= $this->input->post('box_weight');
			$carret_quantity	= $this->input->post('carret_quantity');
			$carret_weight 		= $this->input->post('carret_weight');
			$fish_type 			= $this->input->post('fish_type');
			$client_id 			= ($this->input->post('client_id')) ? $this->input->post('client_id') : 0;
			$client_name 		= $this->input->post('client_name');
			$client_mobile 		= $this->input->post('client_mobile');
			$client_email 		= $this->input->post('client_email');
			$package 			= $this->input->post('package');
			
			if(is_array($package)){
				$fish_name 	= @$package['fish_name'];
				$fish_code 	= @$package['fish_code'];
				$fish_qty 	= @$package['fish_qty'];
				$fish_wt 	= @$package['fish_wt'];
				$box_wt 	= @$package['box_wt'];
				$fish_rate 	= @$package['fish_rate'];
				
				$carret_data = array('packing_date' 	=> $packing_date,
									 /*'box_number' 	=> $box_number,*/
									 'production_id' 	=> $production_id,
									 'production_type' 	=> $production_type,
									 'client_id' 		=> $client_id,
									 'depot_id' 		=> $depot_id,
									 'market_id' 		=> $market_id,
									 'fish_type' 		=> $fish_type,
									 'carret_quantity' 	=> $carret_quantity,
									 'carret_weight' 	=> $carret_weight,
									 'added_by'			=> $this->userID,
									 'added_date'		=> get_datetime('Y-m-d H:i:s'),
									 'action_microtime'	=> microtime(true), 'source' => SOURCE
									 );
				
				
				$this->db->trans_begin();
				// Generate carret number				
				$result = $this->db->insert(PRODUCTION_CARRET, $carret_data);
				$carret_id = $this->db->insert_id();
				$c_num = sprintf("CRT-%03d", $carret_id);
				$this->db->where('id', $carret_id)->update(PRODUCTION_CARRET, array('carret_number' => $c_num));
				
				if($carret_id){
					$this->PM->update_production_summary($production_id);
					$carret_items 	= array();
					$prep_carret_items = array();
					$prep_sale_items = array();
					
					//Normalizing data
					foreach($fish_code as $key => $value){
						//prepared for frontend view
						//$fe_carret_data is used for frontend only
						$fe_carret_data = array( 'carret_number'	=> $c_num,
												 'fish_type' 		=> $fish_type,
												 'carret_quantity' 	=> $carret_quantity,
												 'carret_weight' 	=> $carret_weight,
												 'box_number'		=> ($make_box == "Yes") ? $box_number : '',
												 'box_weight'		=> ($make_box == "Yes") ? $box_weight : '0.00',
												 'box_status'		=> 'Active',
												 'client_name'		=> $client_name,
												 'sale_id'			=> ''
												 );
						
						//prepared for frontend view
						//$carret_items[] is used for frontend only
						$b_wt = ($make_box == "Yes" && !empty($box_wt[$key]) && $box_wt[$key] > 0) ? $box_wt[$key] : "0.00";
						$carret_items[] = array( 'fish_code'		=> $value,
												 'fish_name'		=> @$fish_name[$key],
												 'fish_qty' 		=> @$fish_qty[$key],
												 'fish_wt' 			=> @$fish_wt[$key],
												 'box_wt' 			=> $b_wt
												 );
						
						//Preparing box items according to box number
						//prepared to insert in db
						$prep_carret_items[] = array( 'production_id'	=> $production_id,
													  'carret_id'       => $carret_id,
													  'production_type' => $production_type,
													  'client_id' 		=> $client_id,
													  'depot_id'		=> $depot_id,
													  'market_id'		=> $market_id,
													  'packing_date' 	=> $packing_date,
													  'carret_number'	=> $c_num,
													  'box_number'		=> ($make_box == "Yes") ? $box_number : '',
													  'fish_type'		=> $fish_type,
													  'fish_code'		=> $value,
													  'fish_qty' 		=> @$fish_qty[$key],
													  'fish_wt' 		=> @$fish_wt[$key],
													  'box_wt' 			=> $b_wt,
													  'added_by'		=> $this->userID,
													  'added_date'		=> get_datetime('Y-m-d H:i:s'),
													  'action_microtime'=> microtime(true), 'source' => SOURCE
													 );
												
						//prepared sale_items
						$prep_sale_items[] = array('production_id'	=> $production_id,
												   'client_id' 		=> $client_id,
												   'depot_id'		=> $depot_id,
												   'market_id'		=> $market_id,
												   'packing_date' 	=> $packing_date,
												   'carret_number'	=> $c_num,
												   'fish_type'		=> $fish_type,
												   'fish_code'		=> $value,
												   'fish_qty' 		=> @$fish_qty[$key],
												   'fish_wt' 		=> @$fish_wt[$key],
												   'fish_rate' 		=> @$fish_rate[$key],
												   'stock_type' 	=> $stock_type,
												   'added_by'		=> $this->userID,
												   'added_date'		=> get_datetime('Y-m-d H:i:s'),
												   'action_microtime'=> microtime(true)
												   );
					}
					
					if(!empty($carret_items)){
						$production_with = 'Carret Only';
						$result = $this->db->insert_batch(PRODUCTION_CARRET_ITEMS, $prep_carret_items);					
						
						if($make_box == "Yes" && !empty($box_number)){
							$prep_box_data = array('production_id' 	=> $production_id,
												   'depot_id' 		=> $depot_id,
												   'market_id' 		=> $market_id,
												   'packing_date' 	=> $packing_date,
												   'carret_number' 	=> $c_num,
												   'box_weight' 	=> $box_weight,
												   'mp_code'		=> $mp_code
												   );
							$box_number = $this->PM->insert_box_detail($prep_box_data, $carret_data, $box_number);
							$fe_carret_data['box_number'] = $box_number;
							$this->PM->insert_box_items($prep_box_data, $prep_carret_items, $box_number);
							$production_with = 'Box';
						}
						
						if($client_id){
							//Preapare Invoice data
							$invoice_number = generate_invoice_number($depot_id);
							$sale_data = array(	'market_type' 		=> 1,
												'market_category' 	=> 2,
												'market_id' 		=> $depot_id,
												'mp_code'			=> $mp_code,
												'invoice_number'	=> $mp_code . $invoice_number,
												'sale_date' 		=> $packing_date,									
												'client_id' 		=> $client_id,
												'client_name' 		=> $client_name,
												'client_mobile' 	=> $client_mobile,
												'added_by' 			=> $this->userID,
												'added_date'		=> get_datetime('Y-m-d H:i:s'),
												'status'			=> 'Active'
											   );
							
							$where 		= array('DATE(sale_date)'=> $packing_date, 'market_id' => $depot_id, 'client_id'=>$client_id, 'status'=>'Active');
							$sale_result= $this->db->select('id, sale_id')->where($where)->get(SALE)->result_array();
							$sale_id 	= (isset($sale_result[0]['sale_id']) && !empty($sale_result[0]['sale_id']) ) ? $sale_result[0]['sale_id'] : ((isset($sale_result[0]['id']) && !empty($sale_result[0]['id'])) ? $sale_result[0]['id'] : '');							
							if(!$sale_id){
								$this->db->insert(SALE, $sale_data);
								$sale_id = $this->db->insert_id();
								$this->db->where(array('id'=>$sale_id))->update(SALE, array('sale_id' => $sale_id));
							}
							$this->db->where(array('id'=>$sale_id))->or_where(array('sale_id'=>$sale_id))->update(SALE, array('status'=>'Active'));
							$fe_carret_data['sale_id'] = $sale_id;
							$sale_item_d = array('client_id'=>$client_id, 'carret_number'=>$c_num, 'sale_id'=>$sale_id);
							$this->PM->insert_sale_items($sale_item_d, $prep_sale_items);
							$production_with = 'Sale';
						}
					}
				}
				
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					$response_data = array('status'=>'failed', 'message'=>'Data insertion failed!', 'data'=>'');
				}
				else
				{
					$this->db->trans_commit();
					$response_data = array(	'status' 		=> 'success', 
											'message'		=> 'Carret detail saved successfully!', 
											'data' 			=> generate_box_list_html(($s_no+1), $fe_carret_data, $carret_items, $action_mode),
											'extra_job'		=> $production_with
										   );
				}
			}
		}else{
			$response_data['status'] = 'error';
			$response_data['message'] = validation_errors();
		}
		$this->__return_json_output($response_data);
	}
	
	//daily production item edit mode
	public function ajax_carret_edit_mode($carret_number, $sr_no = '', $box_number = '', $sale_id = '', $mp_code = '', $production_type){
		$response_data = array('status' => 'success', 'page_title' => 'Edit Carret Number '.$carret_number, 'setup_form'=>'');
		$edit_permission = FALSE;
		if($box_number){
			$where 	= array('box_number' => $box_number, 'status' => 'Active' );
			$result = $this->db->where($where)->get(PRODUCT_BOX)->result_array();
			if(!empty($result)){
				$edit_permission = TRUE;
			}else{
				$response_data = array( 'status' 		=> 'failed', 
										'page_title' 	=> 'Edit Carret Number '.$carret_number, 
										'setup_form'	=> '<div class="alert alert-danger text-center">Carret box is dispatched, You can not edit this carret.</div>');
			}
		}else{
			$edit_permission = TRUE;
		}
		
		if($edit_permission){
			$data['all_clients'] 	= $this->PM->get_all_clients(1);
			$data['all_fishes'] 	= $this->PM->get_all_fishes();
			$data['carret_data'] 	= $this->PM->get_production_items('', $carret_number);
			$data['carret_data']	= $data['carret_data'][0];
			$data['production_type']= $production_type;
			$data['sr_no']			= $sr_no;
			$data['sale_id']		= $sale_id;
			$data['mp_code']		= $mp_code;
			$setup_form = $this->load->view('production/ajax_carret_edit_mode', $data, true);
			$response_data = array('status' => 'success', 'page_title' => 'Edit Carret Number '.$carret_number, 'setup_form'=>$setup_form);
		}
		$this->__return_json_output($response_data);
	}
	
	//update daily production item
	public function ajax_update_carret(){
		$response_data = array('status' => 'danger', 'message' => 'Invalid Request.');	
		$form_valid = $this->__setFormRules('update_carret');
		if($form_valid){
			$post 		= $this->input->post();
			$make_box   = ($this->input->post('make_box')) ? "Yes" : "No";
			$box_number = $this->input->post('box_number');
			$carret_number 	= $this->input->post('carret_number');
			$box_action = '';
			if($make_box === "Yes"){
				$box_existance = $this->PM->checkBoxExistance();
				if($box_existance == 'exist'){
					$response_data['status'] = 'error';
					$response_data['message'] = '<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> '.$box_number. ' box is already exist.</div>';
					$box_action = 'update';
					$this->__return_json_output($response_data);
				}
				$checkDispatch = $this->db->where(array('carret_number'=>$carret_number, 'status !=' => 'Active' ))->count_all_results(PRODUCT_BOX);
				if($checkDispatch > 0){
					$response_data = array('status' => 'danger', 'message' => 'Box number ('.$box_number.') has been dispatched, you cant change it!');	
					$this->__return_json_output($response_data);			
				}
			}else{
				$box_number = '';
			}
			
			$sr_no 			= $this->input->post('sr_no');
			$sale_id 		= $this->input->post('sale_id');
			$mp_code 		= $this->input->post('mp_code');
			$client_id 		= $this->input->post('client_id') ? $this->input->post('client_id') : 0;
			
			
			$box_weight 		= $this->input->post('box_weight');
			$carret_quantity	= $this->input->post('carret_quantity');
			$carret_weight 		= $this->input->post('carret_weight');
			$fish_type 			= $this->input->post('fish_type');
			
			$production_type	= $this->input->post('production_type');
			$stock_type			= "Current";
			if($production_type == "Stock"){
				$stock_type		= "Opening";
			}elseif($production_type == "Bachat"){
				$stock_type		= "Bachat";
			}
			
			$carrent_detail		= $this->db->where(array('carret_number'=>$carret_number))->get(PRODUCTION_CARRET)->result_array();
			$carret_data		= $carrent_detail[0];
			$production_id 		= $carret_data['production_id'];
			$depot_id			= $carret_data['depot_id'];
			$market_id 			= $carret_data['market_id'];
			$packing_date 		= $carret_data['packing_date'];
			$db_client_id 		= $carret_data['client_id'];
			
			$carret_items 		= array();
			$fe_carret_data		= array();
			$carret_items		= array();
			$prep_carret_items 	= array();
			$package 			= $this->input->post('package');
			if(is_array($package)){
				$fish_name 		= @$package['fish_name'];
				$fish_code 		= @$package['fish_code'];
				$fish_qty 		= @$package['fish_qty'];
				$fish_wt 		= @$package['fish_wt'];
				$box_wt 		= @$package['box_wt'];
				$fish_rate 		= @$package['fish_rate'];
				
				//Preparing box items according to box number
				foreach($fish_code as $key => $value){
					//prepared for frontend view
					//$fe_carret_data is used for frontend only
					$fe_carret_data = array( 'carret_number'	=> $carret_number,
											 'fish_type' 		=> $fish_type,
											 'carret_quantity' 	=> $carret_quantity,
											 'carret_weight' 	=> $carret_weight,
											 'box_number'		=> $box_number,
											 'box_weight'		=> $box_weight,
											 'client_name'		=> '',
											 'box_status'		=> 'Active',
											 'status' 			=> 'Active',
											 'sale_id' 			=> $sale_id
											 );
						
					//prepared for frontend view
					//$carret_items[] is used for frontend only
					$b_wt = ($box_wt[$key] > 0) ? $box_wt[$key] : "0.00";
					$carret_items[] = array( 'box_number'		=> $box_number,
											 'fish_code'		=> $value,
											 'fish_name'		=> @$fish_name[$key],
											 'fish_qty' 		=> @$fish_qty[$key],
											 'fish_wt' 			=> @$fish_wt[$key],
											 'box_wt' 			=> $b_wt,
											 'item_id' 			=> '',
											 'status' 			=> 'Active'
											 );
									
					//prepared carret item to insert in db
					$prep_carret_items[] = array( 'production_id'	=> $production_id,
												  'production_type' => $production_type,
												  'client_id' 		=> $client_id,
												  'depot_id'		=> $depot_id,
												  'market_id'		=> $market_id,
												  'packing_date' 	=> $packing_date,
												  'carret_number'	=> $carret_number,
												  'box_number'		=> $box_number,
												  'fish_type' 		=> $fish_type,
												  'fish_code'		=> $value,
												  'fish_qty' 		=> @$fish_qty[$key],
												  'fish_wt' 		=> @$fish_wt[$key],
											 	  'box_wt' 			=> $b_wt,
												  'added_by'		=> $this->userID,
												  'added_date'		=> get_datetime('Y-m-d H:i:s'),
												  'action_microtime'=> microtime(true), 'source' => SOURCE
												 );
											
					//prepared sale_items
					$prep_sale_items[] = array('production_id'	=> $production_id,
											   'client_id' 		=> $client_id,
											   'depot_id'		=> $depot_id,
											   'market_id'		=> $market_id,
											   'packing_date' 	=> $packing_date,
											   'carret_number'	=> $carret_number,
											   'fish_type'		=> $fish_type,
											   'fish_code'		=> $value,
											   'fish_qty' 		=> @$fish_qty[$key],
											   'fish_wt' 		=> @$fish_wt[$key],
											   'fish_rate' 		=> @$fish_rate[$key],
											   'stock_type' 	=> $stock_type,
											   'added_by'		=> $this->userID,
											   'added_date'		=> get_datetime('Y-m-d H:i:s'),
											   'action_microtime'=> microtime(true)
											   );
				}
			}
			
			if(!empty($prep_carret_items)){				
				$carret_update_data = array('fish_type'		=>$fish_type, 
											'carret_quantity'	=>$carret_quantity, 
											'carret_weight'		=>$carret_weight, 
											'client_id'			=>$client_id,
											'updated_by'		=>$this->userID, 
											'updated_date'		=>get_datetime('Y-m-d H:i:s'),
											'action_microtime' 	=> microtime(true)
											);
				$this->db->where(array('carret_number'=>$carret_number))->update(PRODUCTION_CARRET, $carret_update_data);
				
				$this->db->delete(PRODUCTION_CARRET_ITEMS, array('carret_number'=>$carret_number));
				$carret_result = $this->db->insert_batch(PRODUCTION_CARRET_ITEMS, $prep_carret_items);
				
				if($carret_result){
					$prep_box_data = array('production_id' 	=> $production_id,
										   'depot_id' 		=> $depot_id,
										   'market_id' 		=> $market_id,
										   'packing_date' 	=> $packing_date,
										   'carret_number' 	=> $carret_number,
										   'box_number' 	=> $box_number,
										   'box_weight' 	=> $box_weight,
										   'mp_code' 		=> $mp_code
										   );
					
					if($make_box == "Yes" && !empty($box_number)){
						$box_exist = $this->db->where(array('carret_number'=>$carret_number))->count_all_results(PRODUCT_BOX);
						if($box_exist == 0){
							$box_number = $this->PM->insert_box_detail($prep_box_data, $carret_data, $box_number);
						}else{
							//update box detail
							$upd_box_detail = array('fish_type'		=>$fish_type, 
													'carret_weight'	=>$carret_weight,
													'box_number'	=>$box_number, 
													'box_weight'	=>$box_weight,
													'updated_by'	=>$this->userID, 
													'updated_date'	=>get_datetime('Y-m-d H:i:s'),
													'action_microtime' => microtime(true)
													);
							$this->db->where('carret_number', $carret_number)->update(PRODUCT_BOX, $upd_box_detail);
						}
						$carret_data['box_number'] = $box_number;
						$fe_carret_data['box_number'] = $box_number;
						if($box_number){
							$this->db->delete(PRODUCT_BOX_ITEMS, array('carret_number'=>$carret_number));
							$this->PM->insert_box_items($prep_box_data, $prep_carret_items, $box_number);
						}
					}else{
						$checkBox = $this->db->where(array('carret_number'=>$carret_number, 'status'=>'Active'))->count_all_results(PRODUCT_BOX);
						if($checkBox > 0){
							$this->db->delete(PRODUCT_BOX, array('carret_number'=>$carret_number));
							$this->db->delete(PRODUCT_BOX_ITEMS, array('carret_number'=>$carret_number));
							$this->db->where(array('carret_number'=>$carret_number))->update(PRODUCTION_CARRET_ITEMS, array('box_number'=>'', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime' => microtime(true)));
							$fe_carret_data['box_number'] = '';
						}
					}
					
					if($client_id){
						$client_data = $this->db->get_where(CLIENT, array('ID'=>$client_id))->result_array();
						$fe_carret_data['client_name'] = !empty($client_data) ? $client_data[0]['company_name'] : '';
						//Preapare Invoice data
						$invoice_number = generate_invoice_number($depot_id);
						$sale_data = array(	'market_type' 		=> 1,
											'market_category' 	=> 2,
											'market_id' 		=> $depot_id,
											'mp_code'			=> $mp_code,
											'invoice_number'	=> $mp_code . $invoice_number,
											'sale_date' 		=> $packing_date,									
											'client_id' 		=> $client_id,
											'client_name' 		=> !empty($client_data) ? $client_data[0]['company_name'] : '',
											'client_mobile' 	=> !empty($client_data) ? $client_data[0]['contact_number'] : '',
											'added_by' 			=> $this->userID,
											'added_date'		=> get_datetime('Y-m-d H:i:s'),
											'status'			=> 'Active'
										   );

						$sale_where = array('market_id' => $depot_id, 'DATE(sale_date)'=>$packing_date, 'client_id'=>$client_id, 'status'=>'Active');
						$checkPreSale = $this->db->select('id, sale_id')->where($sale_where)->get(SALE)->result_array();
						if(!empty($checkPreSale)){
							$sale_id = !empty($checkPreSale[0]['sale_id']) ? $checkPreSale[0]['sale_id'] : $checkPreSale[0]['id'];
						}else{
							//Generate new invoice
							$this->db->insert(SALE, $sale_data);
							$sale_id = $this->db->insert_id();
							$this->db->where(array('id'=>$sale_id))->update(SALE, array('sale_id'=>$sale_id));
						}
						$this->db->where(array('id'=>$sale_id))->or_where(array('sale_id'=>$sale_id))->update(SALE, array('status'=>'Active'));
						$fe_carret_data['sale_id'] = $sale_id;
						$sale_item_d = array('client_id'=>$client_id, 'carret_number'=>$carret_number, 'sale_id'=>$sale_id);
						$this->PM->insert_sale_items($sale_item_d, $prep_sale_items);
						$production_with = 'Sale';
					}else if(!$client_id && $sale_id){
						$this->db->delete(SALE_ITEMS, array('carret_number'=> $carret_number));
					}
					
					$this->PM->update_production_summary($production_id);
					$tr = generate_box_list_html($sr_no, $fe_carret_data, $carret_items, 'edit', 'ajx_upd');
					$response_data = array(	'status' 		=> 'success', 
											'message'		=> 'Carret detail updated successfully!',
											'sr_no' 		=> $sr_no,
											'tr'			=> $tr
											);
				}else{
					$response_data = array('status'=>'danger', 'message'=>'Data updating failed!', 'data'=>'');
				}
			}
		}else{
			$response_data['status'] = 'danger';
			$response_data['message'] = validation_errors();
		}
		$this->__return_json_output($response_data);
	}
	
	//delete daily production item
	public function ajax_delete_carret(){
		$response_data = array('status' => 'failed', 'message' => 'Invalid Request.');	
		$form_valid = $this->__setFormRules('delete_carret');
		if($form_valid){
			$post_data 	= $this->input->post();
			$production_id 	= $this->input->post('production_id');
			$carret_number 	= $this->input->post('carret_number');
			$box_number 	= $this->input->post('box_number');
			$sale_id 		= $this->input->post('sale_id');
			
			
			
			//Delete box only when PRODUCT_BOX status is Active
			if(!empty($box_number)){
				$where 	= array('box_number' => $box_number, 'status' => 'Active' );
				$result = $this->db->where($where)->get(PRODUCT_BOX)->result_array();
				if(!empty($result)){
					$this->db->trans_begin();
					$result = $this->db->delete(PRODUCT_BOX_ITEMS, array('box_number' => $box_number));
					if($result){
						$this->db->delete(PRODUCT_BOX, array('box_number' => $box_number));
						$this->db->delete(BOX_NUMBER, array('box_number' => $box_number));
						$this->db->delete(PRODUCTION_CARRET, array('carret_number' => $carret_number ));
						$this->db->delete(PRODUCTION_CARRET_ITEMS, array('carret_number' => $carret_number ));
						$this->PM->update_production_summary($production_id);
					}
					if ($this->db->trans_status() === FALSE)
					{
						$this->db->trans_rollback();
						$response_data = array('status'=>'failed', 'message'=>'Failed to delete carret, Please try again.', 'data'=>'');
					}
					else
					{
						$this->db->trans_commit();
						$response_data = array('status' => 'success', 
											   'message'=> 'Carret deleted successfully.',
											   'data' 	=> ''
											   );
					}
				}else{
					$response_data = array('status' => 'failed', 
										   'message'=> 'Carret box is dispatched, You can not delete it.',
										   'data' 	=> ''
										   );
				}
			}else if(!empty($sale_id)){
				$this->db->trans_begin();
				$result = $this->db->delete(SALE_ITEMS, array('sale_id' => $sale_id, 'carret_number' => $carret_number));
				if($result){
					$this->db->delete(PRODUCTION_CARRET, array('carret_number' => $carret_number ));
					$this->db->delete(PRODUCTION_CARRET_ITEMS, array('carret_number' => $carret_number ));
					$this->PM->update_production_summary($production_id);
				}
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					$response_data = array('status'=>'failed', 'message'=>'Failed to delete carret, Please try again.', 'data'=>'');
				}
				else
				{
					$this->db->trans_commit();
					$response_data = array('status' => 'success', 
										   'message'=> 'Carret deleted successfully.',
										   'data' 	=> ''
										   );
				}
				
			}else{
				$this->db->trans_begin();
				$where 	= array('carret_number' => $carret_number );
				$result = $this->db->delete(PRODUCTION_CARRET_ITEMS, $where);
				if($result){
					$this->db->delete(PRODUCTION_CARRET, $where);
					$this->PM->update_production_summary($production_id);
				}
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					$response_data = array('status'=>'failed', 'message'=>'Failed to delete carret, Please try again.', 'data'=>'');
				}
				else
				{
					$this->db->trans_commit();
					$response_data = array('status' => 'success', 
										   'message'=> 'Carret deleted successfully.',
										   'data' 	=> ''
										   );
				}
			}
		}else{
			$response_data['status'] = 'error';
			$response_data['message'] = validation_errors();
		}
		$this->__return_json_output($response_data);
	}
	
	//View daily production
	public function view_production($id = NULL){
		if($id != NULL){
			$data['form_action'] = site_url('production/production/'.$id);
			$data['page_title'] = 'Daily Production';
			$data['action_mode'] = 'view';
			$data['datepicker_class'] = '';
			$data['sale_item_table'] = '';
			$data['production_data'] = $this->PM->get_production_data($id, $this->userID);
			
		}
		
		$data['scriptsrc'] =  array(base_url('assets/js/jQuery.print.min.js'),
									base_url('assets/js/jquery.table2excel.min.js')
							 		);
		$data['setup_form'] = $this->load->view('production/view_daily_production_v', $data, true);
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	//1. Closing Stock Report [get_closing_stock]
	public function closing_stock(){
		$data['content_view'] = 'production/closing_stock_v';
		$data['ajax_url'] = 'production/ajax_closing_stock';
		$data['page_title'] = 'Save Closing Stock';
		$data['table_class'] = 'table_table';
		$data['allDepot'] = $this->PM->get_market_places(2);	
		
		$stylesheet_array = array( base_url('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css'),
										 base_url('assets/plugins/select2/dist/css/select2.min.css'),
										 base_url('assets/plugins/DataTables/media/css/jquery.dataTables.min.css'),
										 site_url('reports/assets/css/reports.css')
										 );
		$scriptsrc_array = 	array( base_url('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js'),
										base_url('assets/plugins/select2/dist/js/select2.full.min.js'),
										base_url('assets/js/jQuery.print.min.js'),
										base_url('assets/js/jquery.table2excel.min.js'), // work with Class
										base_url('assets/js/table2excel.custom.js'), // work with ID
										base_url('assets/plugins/DataTables/media/js/jquery.dataTables.js'),
										site_url('reports/assets/js/reports.js')
										);
		
		$this->template->set('stylesheet', $stylesheet_array);
		$this->template->set('scriptsrc', $scriptsrc_array);
		
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($data);
	}
	
	function ajax_closing_stock(){
		$data = array('status' => 'danger', 'message' => '<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> No data found, Please try again.</div>', 'data'=>'');
		$form_validation = $this->__setFormRules('ajax_closing_stock');
		if($form_validation){
			$closing_date = get_date('Y-m-d', str_replace('/', '-', $this->input->post('closing_date')));
			$market_id = $this->input->post('market_id');
			$report_data = $this->PM->get_closing_stock($closing_date, $market_id);
			
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
										
					$rep['prepared_box_qty'] = $rep['opening_pb_qty'] + $rep['prepared_box_qty'];
					$rep['prepared_box_wt'] = $rep['opening_pb_wt'] + $rep['prepared_box_wt'];
					
					
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
	
	public function ajax_save_closing_stock(){
		$data = array('status' => 'danger', 'message' => 'No data found, Please try again!');
		$form_validation = $this->__setFormRules('ajax_closing_stock');
		if($form_validation){
			$closing_date = get_date('Y-m-d', str_replace('/', '-', $this->input->post('closing_date')));
			$market_id = $this->input->post('market_id');
			$report_data = $this->PM->get_closing_stock($closing_date, $market_id);
			
			$active_box = $this->PM->get_all_prepared_and_stocked_box($closing_date, $market_id);			
			if(!empty($active_box)){
				$box_array = array();
				foreach($active_box as $box){
					$box_array[] = array('depot_id'=>$market_id, 'closing_date' => $closing_date, 'box_number' => $box['box_number']);
				}
				if(!empty($box_array)){
					$this->db->delete(DAILY_CLOSING_STOCK_BOX, array('closing_date' => $closing_date, 'depot_id'=>$market_id));
					$result = $this->db->insert_batch(DAILY_CLOSING_STOCK_BOX, $box_array);
				}
			}
			
			
			if(!empty($report_data)){
				$closing_stock = array();
				
				foreach($report_data as $rep){
					
					//$bachat_sortage_wt = ($rep['bsort_fish_wt'] > 0) ? $rep['bsort_fish_wt'] - $rep['bachat_fish_wt'] : 0;
					$bachat_sortage_wt = $rep['bsort_fish_wt'] - $rep['bachat_fish_wt'];
					$rep['bachat_shortage_wt'] = $bachat_sortage_wt;
					
					$opening_shortage_wt = $rep['opening_sorting_fish_wt'] - $rep['opening_fish_wt'];
					$rep['opening_shortage_wt'] = $opening_shortage_wt;
					
					$rep['prepared_box_qty'] = $rep['opening_pb_qty'] + $rep['prepared_box_qty'];
					$rep['prepared_box_wt'] = $rep['opening_pb_wt'] + $rep['prepared_box_wt'];
					
					$outward_qty = $rep['sale_qty'] + $rep['free_sale_qty'] + $rep['dispatched_qty'] + $rep['tranfer_out_qty']  + $rep['prepared_box_qty'] + $rep['destroyed_fish_qty'];					
					$outward_wt = $rep['sale_wt'] + $rep['free_sale_wt'] + $rep['dispatched_wt'] + $rep['tranfer_out_wt']  + $rep['prepared_box_wt'] + $rep['destroyed_fish_wt'];
					
					$inward_qty = $rep['production_qty'] + $rep['opening_fish_qty'] + $rep['opening_pb_qty'] + $rep['tranfer_in_qty'];
					$inward_wt = $rep['production_wt'] + $rep['opening_fish_wt'] + $rep['opening_pb_wt'] + $rep['tranfer_in_wt'] + ($rep['opening_shortage_wt']) + ($rep['bachat_shortage_wt']);
					
					$rep['remaining_qty'] =  $inward_qty - $outward_qty;
					$rep['remaining_wt'] = round(($inward_wt - $outward_wt), 2);
										
					$closing_stock[] = array('production_date' => $closing_date,
											'depot_id' => $market_id,
											'fish_code' => $rep['code'],
											'p_qty' => $rep['production_qty'],
											'p_wt' => $rep['production_wt'],
											't_qty' => $rep['tranfer_in_qty'],
											't_wt' => $rep['tranfer_in_wt'],
											'b_qty' => $rep['bachat_fish_qty'],
											'b_wt' => $rep['bachat_fish_wt'],
											'bs_wt' => $rep['bachat_shortage_wt'],
											'o_qty' => $rep['opening_fish_qty'],
											'o_wt' => $rep['opening_fish_wt'],
											'os_wt' => $rep['opening_shortage_wt'],
											'ps_qty' => $rep['sale_qty'],
											'ps_wt' => $rep['sale_wt'],
											'fs_qty' => $rep['free_sale_qty'],
											'fs_wt' => $rep['free_sale_wt'],
											'd_qty' => $rep['dispatched_qty'],
											'd_wt' => $rep['dispatched_wt'],
											'pb_qty' => $rep['prepared_box_qty'],
											'pb_wt' => $rep['prepared_box_wt'],											
											'cs_qty' => $rep['remaining_qty'],
											'cs_wt' => $rep['remaining_wt'],
											'closing_by' => $rep['production_qty'],
											'closing_date' => get_datetime('Y-m-d H:i:s'),
											'added_by' => $this->userID,
											'added_date' => get_datetime('Y-m-d H:i:s'),
											'action_microtime' => microtime(true),
											'source' => SOURCE										
											);
				}
				
				if(!empty($closing_stock)){
					$this->db->delete(DAILY_CLOSING_STOCK, array('production_date' => $closing_date, 'depot_id' => $market_id));
					$result = $this->db->insert_batch(DAILY_CLOSING_STOCK, $closing_stock);
					if($result){
						$data = array('status' => 'success', 'message' => 'Closing stock saved successfully!');
					}else{
						$data = array('status' => 'danger', 'message' => 'Saving Closing stock failed!');
					}
				}
				
			}else{
				$data = array('status' => 'danger', 'message' => 'Closing stock data not found!');
			}
		}else{
			$data = array('status' => 'danger', 'message' => validation_errors());
		}
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	
	
	}
	
//================================================================================

	//Get customers for javascript select2 plugin
	//$market_type : Local Market = 1, Outside Market = 2
	public function get_client($market_type=NULL){
		$data['result1'] = '';
		$data['result2'] = '';
		$q = $this->input->get('q');
		if(!empty($q)){
			$data = $this->PM->get_clients_data($q, $offset = 0, $limit = 10, $market_type);
		}
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	public function typeahead_get_customers($market_type=NULL){
		$data['result1'] = '';
		$data['result2'] = '';
		$search_key = $this->input->post('search_key');
		if(!empty($search_key)){
			$data = $this->PM->typeahead_get_clients_data($search_key, $offset = 0, $limit = 10, $market_type);
		}
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	//called by multiple functions
	public function __return_json_output($data){
		//$this->output->set_content_type('application/json');
		//$this->output->set_output(json_encode($data));
		header("Content-type:application/json; charset=UTF8");
		echo json_encode($data);
		die;
	}
	
	public function __callbackDeleteActionButton($primary_key, $row){
		return site_url('bulk_action/action/mark_delete');
	}
	
	public function __callbackLockActionButton($primary_key, $row){
		return site_url('bulk_action/action/lock');
	}
		
	//called by mulitple functions
	public function __callaback_display_datetime($value, $row){
		return get_datetime('d M, Y, h:i A', $value);
	}
	
	//formvalidation callback
	public function checkBoxExistance(){
		$data = FALSE;
		$carret_number 	= $this->input->post('carret_number');
		$box_number 	= $this->input->post('box_number');
		if(!empty($box_number)){
			if($carret_number){
				$this->db->where(array('carret_number !='=>$carret_number));
			}
			$result = $this->db->where(array('box_number'=>$box_number))->count_all_results(PRODUCT_BOX);
			if($result == 0){
				$data = TRUE;
			}else{
				$this->form_validation->set_message('checkBoxExistance', 'Box '.$box_number.' is already exist.');
			}
		}else{
			$this->form_validation->set_message('checkBoxExistance', 'Box number is required.');
		}
		return $data;
	}
	
	//formvalidation callback
	public function checkBoxStatus(){
		$box_number 	= $this->input->post('carret_box_number');
		$data = FALSE;
		if(!empty($box_number)){
			$result = $this->db->get_where(PRODUCT_BOX, array('box_number'=>$box_number))->result_array();
			if(!empty($result) && $result[0]['status'] == "Active"){
				$data = TRUE;
			}else{
				$data = FALSE;
				$this->form_validation->set_message('checkBoxStatus', 'Carret Box is dispatched you can not update it.');
			}
		}else{
			$data = TRUE;
		}
		return $data;
	}
	
	//formvalidation callback
	public function checkBoxWeight(){
		$box_weight = $this->input->post('box_weight');
		$carret_weight = $this->input->post('carret_weight');
		$data = TRUE;
		if(!empty($box_weight)){
			if($box_weight > $carret_weight){
				$data = FALSE;
				$this->form_validation->set_message('checkBoxWeight', 'Total Box weight must be less than or equals to carret weight.');
			}
		}
		return $data;
	}
	
	public function checkBxWt(){
		$package 	= $this->input->post('package');
		$fish_code 	= $package['fish_code'];
		$fish_wt 	= $package['fish_wt'];
		$box_wt 	= $package['box_wt'];
		$data		= TRUE;
		foreach($fish_code as $key => $value){
			if($box_wt[$key] > $fish_wt[$key]){
				$data = FALSE;
				$this->form_validation->set_message('checkBxWt', 'Box weight must be less than or equals to carret weight.');
			}
		}
		return $data;
	}
	
	//formvalidation callback
	public function checkBoxFishType(){
		$box_number 	= $this->input->post('box_number');
		$fish_type 		= $this->input->post('fish_type');
		$data = TRUE;
		if(!empty($box_number)){
			if($fish_type == "Destroyed"){
				$data = FALSE;
				$this->form_validation->set_message('checkBoxFishType', 'You can not make Destroyed Fish box.');
			}
		}
		return $data;
	}
	
	public function checkBoxCustomer(){
		$box_number 	= $this->input->post('box_number');
		$client_id 		= $this->input->post('client_id');
		$data = TRUE;
		if(!empty($box_number) && !empty($client_id)){
			$data = FALSE;
			$this->form_validation->set_message('checkBoxCustomer', 'Please either sale or dispatch carret.');
		}
		return $data;
	}
	
	public function checkDestroyedSale(){
		$fish_type 		= $this->input->post('fish_type');
		$client_id 		= $this->input->post('client_id');
		$data = TRUE;
		if(!empty($client_id) && $fish_type == "Destroyed"){
			$data = FALSE;
			$this->form_validation->set_message('checkDestroyedSale', 'You can not sale destroyed fish to client.');
		}
		return $data;
	}
	
	public function checkPType(){
		$data = FALSE;
		$production_type = strtolower($this->input->post('production_type'));
		$depot_id		= $this->input->post('depot_id');
		$market_id		= $this->input->post('market_id');
		if($production_type == 'transfer'){
			if($depot_id != $market_id){
				$data = TRUE;
			}else{
				$this->form_validation->set_message('checkPType', 'From Depot can not be same as To Depot');
			}
		}else{
			$data = TRUE;
		}
		return $data;
	}
	
	private function __setFormRules($setRulesFor = ''){
		switch($setRulesFor){
			case 'add_production':
				$production_type = $this->input->post('production_type');
				$di_text = "Depot";
				$mi_text = "Point";
				if($production_type == 'Transfer'){
					$di_text = "To Depot";
					$mi_text = "From Depot";
				}
				$this->form_validation->set_rules('action_mode', 'Action Mode', 'trim|required|in_list[add,edit]');
				$this->form_validation->set_rules('depot_id', $di_text, 'trim|required|numeric|integer|greater_than[0]');
				$this->form_validation->set_rules('market_id', $mi_text, 'trim|required|numeric|integer|greater_than[0]');
				$this->form_validation->set_rules('production_date', 'Date', 'trim|required');
				
				$this->form_validation->set_rules('point_wt', $production_type.' Weight', 'trim|required|numeric|greater_than[0]');
				$this->form_validation->set_rules('production_type', 'Production Type', 'trim|required|in_list[Point,Stock,Transfer,Bachat,Depot,point,stock,transfer,bachat,depot]|callback_checkPType');
			break;
			
			case 'save_carret':
				// Pre-process package array to remove blank/empty rows
				$pkg = $this->input->post('package');
				if(is_array($pkg) && isset($pkg['fish_code'])){
					$clean_pkg = array('fish_code'=>array(), 'fish_name'=>array(), 'fish_qty'=>array(), 'fish_wt'=>array(), 'box_wt'=>array(), 'fish_rate'=>array());
					foreach($pkg['fish_code'] as $k => $fc){
						if(!empty($fc) && isset($pkg['fish_wt'][$k]) && floatval($pkg['fish_wt'][$k]) > 0){
							$clean_pkg['fish_code'][] = $fc;
							$clean_pkg['fish_name'][] = isset($pkg['fish_name'][$k]) ? $pkg['fish_name'][$k] : '';
							$clean_pkg['fish_qty'][]  = isset($pkg['fish_qty'][$k]) ? $pkg['fish_qty'][$k] : 0;
							$clean_pkg['fish_wt'][]   = $pkg['fish_wt'][$k];
							$clean_pkg['box_wt'][]    = isset($pkg['box_wt'][$k]) ? $pkg['box_wt'][$k] : 0;
							$clean_pkg['fish_rate'][] = isset($pkg['fish_rate'][$k]) ? $pkg['fish_rate'][$k] : 0;
						}
					}
					if(!empty($clean_pkg['fish_code'])){
						$_POST['package'] = $clean_pkg;
					}
				}
				$this->form_validation->set_rules('package[fish_code][]', 'Fish Code', 'trim|required');
				$this->form_validation->set_rules('package[fish_qty][]', 'Quantity', 'trim|required|numeric|greater_than_equal_to[0]');
				$this->form_validation->set_rules('package[fish_wt][]', 'Carret Weight', 'trim|required|numeric|greater_than[0]');
				$this->form_validation->set_rules('carret_weight', 'Total Carret Weight', 'trim|required|numeric|greater_than[0]');
				$this->form_validation->set_rules('fish_type', 'Fish Type', 'trim|required|in_list[Fresh,Rotten,Destroyed]');
				$this->form_validation->set_rules('customer_id', 'Customer ID', 'trim|callback_checkDestroyedSale');
				$make_box   = ($this->input->post('make_box') == "Yes") ? "Yes" : "No";
				$box_number = $this->input->post('box_number');
				$box_weight = $this->input->post('box_weight');
				if($make_box == "Yes"){
					$this->form_validation->set_rules('package[box_wt][]', 'Box Weight', 'trim|required|numeric|greater_than[0]|callback_checkBxWt');
					$this->form_validation->set_rules('box_number', 'Box Number', 'trim|required|callback_checkBoxExistance|callback_checkBoxFishType|callback_checkBoxCustomer');
					$this->form_validation->set_rules('box_weight', 'Total Box Weight', 'trim|required|numeric|callback_checkBoxWeight');
				}
			break;
			
			case 'dp_item':
				$this->form_validation->set_rules('production_id', 'Production ID', 'trim|required|numeric|integer|greater_than[0]');
				$this->form_validation->set_rules('type', 'Type', 'trim|required|in_list[Fresh,Rotten,Destroyed]');
				$this->form_validation->set_rules('fish_code', 'Fish', 'trim|required');
				$this->form_validation->set_rules('fish_weight', 'Fish Weight', 'trim|required|numeric|greater_than[0]');
			break;
			
			case 'edit_carret':
				$this->form_validation->set_rules('carret_number', 'Carret Number', 'trim|required');
			break;
			
			case 'delete_carret':
				$this->form_validation->set_rules('production_id', 'Production ID', 'trim|required|integer|greater_than[0]');
				$this->form_validation->set_rules('carret_number', 'Carret Number', 'trim|required');
			break;
			
			case 'update_carret':
				// Pre-process package array to remove blank/empty rows
				$pkg = $this->input->post('package');
				if(is_array($pkg) && isset($pkg['fish_code'])){
					$clean_pkg = array('fish_code'=>array(), 'fish_name'=>array(), 'fish_qty'=>array(), 'fish_wt'=>array(), 'box_wt'=>array(), 'fish_rate'=>array());
					foreach($pkg['fish_code'] as $k => $fc){
						if(!empty($fc) && isset($pkg['fish_wt'][$k]) && floatval($pkg['fish_wt'][$k]) > 0){
							$clean_pkg['fish_code'][] = $fc;
							$clean_pkg['fish_name'][] = isset($pkg['fish_name'][$k]) ? $pkg['fish_name'][$k] : '';
							$clean_pkg['fish_qty'][]  = isset($pkg['fish_qty'][$k]) ? $pkg['fish_qty'][$k] : 0;
							$clean_pkg['fish_wt'][]   = $pkg['fish_wt'][$k];
							$clean_pkg['box_wt'][]    = isset($pkg['box_wt'][$k]) ? $pkg['box_wt'][$k] : 0;
							$clean_pkg['fish_rate'][] = isset($pkg['fish_rate'][$k]) ? $pkg['fish_rate'][$k] : 0;
						}
					}
					if(!empty($clean_pkg['fish_code'])){
						$_POST['package'] = $clean_pkg;
					}
				}
				$this->form_validation->set_rules('carret_number', 'Carret Number', 'trim|required');
				$this->form_validation->set_rules('package[fish_code][]', 'Fish', 'trim|required');
				$this->form_validation->set_rules('package[fish_qty][]', 'Fish Quantity', 'trim|required|numeric|greater_than_equal_to[0]');
				$this->form_validation->set_rules('package[fish_wt][]', 'Fish Weight', 'trim|required|numeric|greater_than[0]');
				$this->form_validation->set_rules('carret_weight', 'Total Carret Weight', 'trim|required|numeric|greater_than[0]');
				$this->form_validation->set_rules('fish_type', 'Fish Type', 'trim|required|in_list[Fresh,Rotten,Destroyed]');
				$this->form_validation->set_rules('customer_id', 'Customer ID', 'trim|callback_checkDestroyedSale');
				$make_box   = ($this->input->post('make_box') == "Yes") ? "Yes" : "No";
				$box_number = $this->input->post('box_number');
				$box_weight = $this->input->post('box_weight');
				if($make_box == "Yes"){
					$this->form_validation->set_rules('package[box_wt][]', 'Box Weight', 'trim|required|numeric|greater_than[0]|callback_checkBxWt');
					$this->form_validation->set_rules('box_number', 'Box Number', 'trim|required|callback_checkBoxExistance|callback_checkBoxFishType|callback_checkBoxCustomer');
					$this->form_validation->set_rules('box_weight', 'Total Box Weight', 'trim|required|numeric|callback_checkBoxWeight');
				}
			break;
			case 'ajax_closing_stock':
				$this->form_validation->set_rules('closing_date', 'Closing Date', 'trim|required');
				$this->form_validation->set_rules('market_id', 'Depot Name', 'trim|required');
			break;
			
		}
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert_msg margin-5 padding-5"><button data-dismiss="alert" class="close">×</button><i class="fa fa-times-circle"></i> ', '</div>');
		return $this->form_validation->run($this);
	}

}


<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dispatch extends MY_Controller {
	var $userID, $userGroup, $actions, $session_year;
	
	// url_encryptor("encrypt", 1);
	// url_encryptor("decrypt", 1);
	//$market_type : Local Market=>1, Outside Market=>2
	//market_category: Point=>1, Depot=>2, Outside=>3
	public function __construct(){
		parent::__construct();
		$this->userID = checkUserLogin();
		$this->userGroup = loginUserInfo('group_id');
		$this->load->helper('dispatch');
		$this->load->model('dispatch_model', 'DM');
		$this->session_year = (defined('SESSION_YEAR') && !empty(constant('SESSION_YEAR'))) ? SESSION_YEAR : date('Y')."-".(date('y')+1);
	}
	
	public function index(){
		redirect('dashboard');
	}
	
	// 1. Product Box functions start
	//Listing Product Box
	public function prepare_box(){
		$this->actions = checkUserPermission('dispatch/prepare_box', $this->uri->segment(3));
		if(!empty($this->uri->segment(3)) && ($this->uri->segment(3) == 'add' || $this->uri->segment(3) == 'edit' || $this->uri->segment(3) == 'view')){redirect(site_url('dispatch/prepare_box'));}
		$actions = $this->actions;
		$crud = new grocery_CRUD();
		$crud->set_theme('bootstrap');
		$crud->unset_jquery();
		$crud->unset_bootstrap();
		$crud->unset_common_search();		
		$crud->unset_delete();
		$crud->unset_edit();
		$crud->unset_read();
		
		
		if(!in_array('add', $actions)){
			$crud->unset_add();
		}else{
			$crud->set_add_url_path(site_url('dispatch/add_box'));
		}
		
		if(in_array('edit', $actions)){
			$crud->add_action('Edit', '', '', 'fa fa-pencil', array($this, '__callbackEditActionButton'), '');
			//$crud->add_bulk_action('Active', site_url('bulk_action/action/active'), '', 'fa fa-check', 'status');
			//$crud->add_bulk_action('Inactive', site_url('bulk_action/action/inactive'), '', 'fa fa-ban', 'status');
		}
		
		if(!in_array('export', $actions)){
			$crud->unset_export();
		}
		
		if(!in_array('print', $actions)){
			$crud->unset_print();
		}
		
		//$crud->getState() list,ajax_list
		if($crud->getState() == 'ajax_list'){
			
			$columns = array('depot_name' =>'mp.name', 'market_place' =>'mp2.name', 'added_by' => 'admin.first_name'); 
			$postData = $this->input->post();
			if(isset($postData['search_field']) && !empty($postData['search_field'])){
				foreach($postData['search_field'] as $key => $value){
					if(array_key_exists($value, $columns)){
						$_POST['search_field'][$key] = $columns[$value];
					}
					
					if($value == 'packing_date'){
						$_POST['search_text'][$key] = get_date('Y-m-d', str_replace('/', '-', $postData['search_text'][$key]));
					}
				}
			}
		}
		
		$data = array('page_title'=>'Prepare Box', 'content_view'=>'dispatch/prepare_box/summary_box_v');
		
		$crud->set_subject('Box');
		$crud->set_table(PRODUCT_BOX);
		$crud->set_model('grocery_crud_custom_query_model');
		
		$user_condition = ' AND pb.added_by = '.$this->userID;
		$added_by_cond1 = "";
		$added_by_cond2 = "";
		$crud->columns('packing_date', 'market_place', 'total_box');
		
		if(in_array('view_all', $this->actions)){	
			//For super admin user
			$user_condition = '';
			$added_by_cond1 = "CONCAT(admin.first_name,' ',admin.last_name) as added_by,";
			$added_by_cond2 = "LEFT JOIN ".ADMINISTRATOR." admin ON admin.admin_id=pb.added_by";
			$crud->columns('packing_date', 'depot_name', 'market_place', 'total_box', 'added_by');
		}else{
			//For all other users
			$user_condition = ' AND pb.added_by = '.$this->userID;
			$added_by_cond1 = "";
			$added_by_cond2 = "";
			$crud->columns('packing_date', 'depot_name', 'market_place', 'total_box');
		}
		$crud->display_summary( 'total_box');
		
		$crud->basic_model->set_custom_query("SELECT pb.*, 
													 ".$added_by_cond1."
													 mp.name as depot_name,
													 mp2.name as market_place, 
													 DATE(pb.added_date) as added_date, 
													 count(pb.box_number) as total_box
											  FROM ". PRODUCT_BOX ." pb 
											  LEFT JOIN ".MARKET_PLACE." mp ON mp.ID=pb.depot_id
											  LEFT JOIN ".MARKET_PLACE." mp2 ON mp2.ID=pb.market_id
											  ".$added_by_cond2." 
											  WHERE pb.status != 'Deleted' AND pb.editable = 'Unlock' ".$user_condition, " GROUP BY DATE(pb.packing_date), market_place");
		
		$crud->order_by('DATE(pb.packing_date)', 'DESC');
		$crud->field_without_sorter(array('total_box'));
		$crud->unset_search(array('total_box'));
		
		$crud->display_as(array('packing_date'=>'Date', 'depot_name'=>'Depot', 'market_place'=>'Point'));				
		$crud->callback_column('packing_date', array($this, '__callback_packing_date'));
		$output = $crud->render();
		$data['stock'] = $this->DM->get_stock(get_date('Y-m-d'));
		$outputData = array_merge((array)$output, $data);	
		$this->template->set('stylesheet', array(site_url('dispatch/assets/css/panel.css')));
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($outputData);
	}
	
	public function __callbackEditActionButton($primary_key, $row){
		$date = str_replace('/', '-',$row->packing_date);
		$time = strtotime($date);
		return site_url('dispatch/add_box/'.$time.'/'.$row->depot_id.'/'.$row->market_id);	
	}
	
	public function __callback_packing_date($value, $row){ return get_datetime('d/m/Y', $value); }
	
	public function add_box($date_id = NULL, $depot_id = NULL, $market_id = NULL){
		$action_mode = ($date_id !== NULL) ? 'edit' : 'add';
		$this->actions = checkUserPermission('dispatch/prepare_box', $action_mode);
		
		$data['header'] = array('package_date' 	=> get_date('d/m/Y'), 
								'depot_id'	 	=> $depot_id,
								'market_id' 	=> $market_id
								);
		
		$data['go_back_url'] = site_url('dispatch/prepare_box');
		$data['all_boxes'] = array();
		$data['disabled_class'] = '';
		$data['datepicker'] = 'datepicker';
		$data['action_mode'] = 'add';
		$data['page_title'] = 'Add Product Box';
		if($date_id !== NULL){
			$data['point'] = $this->DM->get_market_places('', $market_id);
			$data['depot'] = $this->DM->get_market_places('', $depot_id);
			//printr($data['depot']);
			$date = get_date('Y-m-d', $date_id);
			$data['box_info']  = $this->DM->get_box_info($date, $market_id, $depot_id);
			if(empty($data['box_info'])){
				$this->messageci->set('Invalid request, Please try again.', 'error') ;
				redirect(site_url('dispatch/prepare_box'));
			}
			$data['all_boxes'] = $this->DM->get_all_boxes($date, $market_id, $depot_id);
			$data['action_mode'] = 'edit';
			$data['page_title'] = 'Edit Product Box';
			$data['datepicker'] = '';
			$data['disabled_class'] = 'disabled';
			$data['header'] = array('package_date' 	=> get_date('d/m/Y', $date), 
									'depot_id' 		=> $depot_id, 
									'market_id' 	=> $market_id
									);
		}
		
		$data['content_view'] = 'dispatch/prepare_box/add_box_v';
		$data['all_fishes'] = $this->DM->get_all_fishes();
		if($data['action_mode'] == 'add'){
			$data['market_places'] = $this->DM->get_market_places(1);
			$data['all_depots'] = $this->DM->get_market_places(2);
		}
		$this->template->set('stylesheet', array(base_url('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css'),
											base_url('assets/plugins/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')));
		$this->template->set('scriptsrc', array(base_url('assets/plugins/moment/min/moment-with-locales.min.js'),
												base_url('assets/plugins/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'),
												base_url('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js'),
												base_url('assets/modules/reports/jQuery.print.min.js'),
												base_url('assets/modules/reports/jquery.table2excel.min.js'),
												site_url('dispatch/assets/js/dispatch_common.js'),
												site_url('dispatch/assets/js/prepare_box.js')
											));
		
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($data);
	}
	
	public function ajax_save_box($sr_no = NULL){
		$response_data = array('status' => 'failed', 'message' => 'Invalid Request.');	
		$form_valid = $this->__setFormRules('save_box');
		if($form_valid){
			$post 			= $this->input->post();
			$action_mode 	= $this->input->post('action_mode');			
			$depot_id 		= $this->input->post('depot_id');
			$mp_code 		= $this->input->post('mp_code');
			$market_id 		= $this->input->post('market_id');
			$packing_date 	= str_replace('/', '-', $this->input->post('packing_date'));
			
			$box_number 	= strtoupper($this->input->post('box_number'));
			$box_weight 	= $this->input->post('box_weight');
			$box_quantity	= $this->input->post('carret_quantity');
			$carret_weight 	= $this->input->post('carret_weight');
			$fish_type 		= $this->input->post('fish_type');			
			
			$box_items 		= array();
			$prep_box_items	= array();
			$package 		= $this->input->post('package');
			
			//$code = (strlen($mp_code) > 1) ? strtoupper(substr($mp_code, 0, 1)) : strtoupper($mp_code);	
			//$number = generate_box_number($mp_code);
			//$box_number = $code.$box_number;
			
			$prepareData = array( 'box_number'		=> $box_number,
								  'depot_id'		=> $depot_id,
								  'market_id'		=> $market_id,
								  'packing_date'	=> get_datetime('Y-m-d', $packing_date),
								  'fish_type'		=> $fish_type,
								  'box_quantity'	=> $box_quantity,
								  'carret_weight'	=> $carret_weight,
								  'box_weight'		=> $box_weight,
								  'added_by'		=> $this->userID,
								  'added_date'		=> get_datetime('Y-m-d H:i:s'),
								  'action_microtime'=> microtime(true),
								  'source'			=>SOURCE
								  );
			$this->db->insert(PRODUCT_BOX, $prepareData);
			$box_id = $this->db->insert_id();
			if($box_id){
				/*$mp_code = strtoupper(substr($mp_code, 0, 1));
				$box_number = $mp_code.$box_id;
				$box_upd = array('box_number'=>$box_number);
				$this->db->where(array('box_id'=>$box_id))->update(PRODUCT_BOX, $box_upd);*/
				
				//$box_upd = array('mp_code'=>$mp_code, 'number'=>$number, 'box_number'=>$box_number);
				//$this->db->insert(BOX_NUMBER, $box_upd);
				
				if(is_array($package)){
					$fish_name 	= @$package['fish_name'];
					$fish_code 	= @$package['fish_code'];
					$fish_qty 	= @$package['fish_qty'];
					$fish_wt 	= @$package['fish_wt'];
					$box_wt 	= @$package['box_wt'];
					
					//Preparing box items according to box number
					foreach($fish_code as $key => $value){
						//prepared for frontend view
						$box_items[] = array('fish_code'		=> $value,
											 'fish_name'		=> @$fish_name[$key],
											 'fish_qty' 		=> @$fish_qty[$key],
											 'fish_wt' 			=> @$fish_wt[$key],
											 'box_wt' 			=> @$box_wt[$key]
											 );
						//prepared to insert in db
						$prep_box_items[] = array('box_id'			=> $box_id,
												  'packing_date' 	=> get_datetime('Y-m-d', $packing_date),
												  'depot_id'		=> $depot_id,
												  'market_id'		=> $market_id,
												  'box_number'		=> $box_number,
												  'fish_type'		=> $fish_type,
												  'fish_code'		=> $value,
												  'fish_qty' 		=> @$fish_qty[$key],
												  'fish_wt' 		=> @$fish_wt[$key],
												  'box_wt' 			=> @$box_wt[$key],
												  'added_by'		=> $this->userID,
												  'added_date'		=>get_datetime('Y-m-d H:i:s'),
												  'action_microtime'=> microtime(true),
												  'source'			=>SOURCE
												 );
												
					}
				}
				
				
				if(!empty($prep_box_items)){					
					$this->db->insert_batch(PRODUCT_BOX_ITEMS, $prep_box_items);
					$box_detail = array('box_number'   	=> $box_number,
										'box_quantity' 	=> $box_quantity,
										'box_weight'   	=> $box_weight,
										'box_status'   	=> 'Active'
										);
					$pk_time = strtotime($packing_date);
					$response_data = array(	'status' 		=> 'success', 
											'message'		=> 'Box detail saved successfully!', 
											'data' 			=> generate_box_list_html(($sr_no+1), $box_detail, $box_items, $action_mode),
											'action_mode' 	=> $action_mode,
											'redirect_url' 	=> site_url('dispatch/add_box/'.$pk_time.'/'.$depot_id.'/'.$market_id)
											);
				}else{
					$response_data = array('status'=>'failed', 'message'=>'There is some error, Please reload the page and try again.');
				}
			}else{
				$response_data = array('status'=>'failed', 'message'=>'Data insertion failed, Please try again.');
			}
		}else{
			$response_data['status'] = 'error';
			$response_data['message'] = validation_errors();
		}
		$this->__return_json_output($response_data);
	}
	
	public function ajax_box_edit_mode($box_number = '', $sr_no = ''){
		$response_data = array('status' => 'success', 'page_title' => 'Edit Box Number '.$box_number, 'setup_form'=>'');
		
		$edit_permission = FALSE;
		if(!empty($box_number)){
			$where 	= array('box_number' => $box_number, 'status' => 'Active' );
			$result = $this->db->where($where)->get(PRODUCT_BOX)->result_array();
			if(!empty($result)){
				$edit_permission = TRUE;
			}else{
				$response_data = array( 'status' 		=> 'failed', 
										'page_title' 	=> 'Edit Box Number '.$box_number, 
										'setup_form'	=> 'Box is dispatched, You can not edit this carret.'
									   );
			}
		}else{
			$edit_permission = TRUE;
		}
		if($edit_permission){
			$data['all_fishes'] 	= $this->DM->get_all_fishes();
			$data['box_data'] 		= $this->DM->get_box_items($box_number);
			$data['sr_no']			= $sr_no;
			$setup_form = $this->load->view('prepare_box/ajax_box_edit_mode', $data, true);
			$response_data = array('status' => 'success', 'page_title' => 'Edit Box Number '.$box_number, 'setup_form'=>$setup_form);
		}
		$this->__return_json_output($response_data);
	}
	
	public function ajax_update_box(){
		$response_data = array('status' => 'danger', 'message' => 'Invalid Request.');	
		$form_valid = $this->__setFormRules('update_box');
		if($form_valid){
			$post 				= $this->input->post();
			$sr_no 				= $this->input->post('sr_no');
			$box_id 			= $this->input->post('box_id');
			$old_box_number		= $this->input->post('old_box_number');
			$box_number 		= strtoupper($this->input->post('box_number'));
			$box_weight 		= $this->input->post('box_weight');
			$box_quantity		= $this->input->post('carret_quantity');
			$carret_weight 		= $this->input->post('carret_weight');
			$fish_type 			= $this->input->post('fish_type');
			
			$package 			= $this->input->post('package');
			$carret_items 		= array();
			$box_detail			= $this->db->where(array('id'=>$box_id))->get(PRODUCT_BOX)->result_array();
			$box_data			= $box_detail[0];
			$carret_number 		= $box_data['carret_number'];
			$depot_id 			= $box_data['depot_id'];
			$market_id 			= $box_data['market_id'];
			$packing_date 		= $box_data['packing_date'];
			$fe_box_data		= array();
			$box_items			= array();
			$prep_box_items 	= array();
			if(is_array($package)){
				$fish_name 		= @$package['fish_name'];
				$fish_code 		= @$package['fish_code'];
				$fish_qty 		= @$package['fish_qty'];
				$fish_wt 		= @$package['fish_wt'];
				$box_wt 		= @$package['box_wt'];
				//$this->PM->update_production_summary($production_id);
				//Preparing box items according to box number
				foreach($fish_code as $key => $value){
					//prepared for frontend view
					//$fe_carret_data is used for frontend only
					$fe_box_data = array('fish_type' 		=> $fish_type,
										 'box_quantity' 	=> $box_quantity,
										 'carret_weight' 	=> $carret_weight,
										 'box_number'		=> $box_number,
										 'box_weight'		=> $box_weight,
										 'box_status'		=> 'Active',
										 'status' 			=> 'Active'
										 );
						
					//prepared for frontend view
					//$carret_items[] is used for frontend only
					$box_items[] = array('box_number'		=> $box_number,
										 'fish_code'		=> $value,
										 'fish_name'		=> @$fish_name[$key],
										 'fish_qty' 		=> @$fish_qty[$key],
										 'fish_wt' 			=> @$fish_wt[$key],
										 'box_wt' 			=> @$box_wt[$key],
										 'item_id' 			=> '',
										 'status' 			=> 'Active'
										 );
					
									
					//prepared to insert in db
					$prep_box_items[] = array('box_id'			=> $box_id,
											  'depot_id'		=> $depot_id,
											  'market_id'		=> $market_id,
											  'packing_date' 	=> $packing_date,
											  'carret_number'	=> $carret_number,
											  'fish_type' 		=> $fish_type,
											  'fish_code'		=> $value,
											  'fish_qty' 		=> @$fish_qty[$key],
											  'fish_wt' 		=> @$fish_wt[$key],
											  'box_wt' 			=> @$box_wt[$key],
											  'added_by'		=> $this->userID,
											  'added_date'		=> get_datetime('Y-m-d H:i:s'),
											  'action_microtime'=> microtime(true)
											 );
											
				}
			}
			$result = 0;
			if(!empty($prep_box_items)){
				$this->db->delete(PRODUCT_BOX_ITEMS, array('box_number'=>$old_box_number));
				
				$prep_box_data = array('box_number'		=> $box_number,
									   'fish_type'		=> $fish_type, 
									   'box_quantity'	=> $box_quantity, 
									   'carret_weight'	=> $carret_weight, 
									   'box_weight'		=> $box_weight,
									   'updated_by'		=> $this->userID,
									   'updated_date'	=> get_datetime('Y-m-d H:i:s'),
									   'action_microtime'=>microtime(true)
									   );
				
				$this->DM->insert_box_items($carret_number, $box_number, $box_data, $prep_box_items);
				$result = $this->db->where(array('id'=>$box_id))->update(PRODUCT_BOX, $prep_box_data );
			}
			
			if($result){
				$tr = generate_box_list_html($sr_no, $fe_box_data, $box_items, 'edit', 'ajx_upd');
				$response_data = array(	'status' 	=> 'success', 
										'message'	=> 'Box detail updated successfully!',
										'sr_no' 	=> $sr_no,
										'tr'		=> $tr
										);
			}else{
				$response_data = array('status'=>'danger', 'message'=>'Data updating failed!', 'data'=>'');
			}
		}else{
			$response_data['status'] = 'danger';
			$response_data['message'] = validation_errors();
		}
		$this->__return_json_output($response_data);
	}
	
	public function ajax_delete_prepare_box(){
		$response_data = array('status' => 'failed', 'message' => 'Invalid Request.');	
		$form_valid = $this->__setFormRules('delete_product_box');
		if($form_valid){
			$post_data = $this->input->post();
			$box_number = $this->input->post('box_number');
			//Delete box only when PRODUCT_BOX status is Active
			$where = array('box_number' => $box_number);
			$box_data = $this->db->where($where)->get(PRODUCT_BOX)->result_array();
			if(!empty($box_data) && count($box_data)>0 && $box_data[0]['status'] == 'Active'){
				//delete box only when box_number not found in PRODUCT_DISPATCH_BOX -- NOT IMPLEMENTED YET
				//delete box only when box_number not found in SALE_ITEMS -- NOT IMPLEMENTED YET
				
				$this->db->delete(PRODUCT_BOX, $where);
				$result = $this->db->delete(PRODUCT_BOX_ITEMS, $where);
				$result = $this->db->delete(BOX_NUMBER, array('box_number' => $box_data[0]['box_number']));
				
				if($result){
					$response_data = array('status' => 'success', 
										   'message'=> 'Box deleted successfully.',
										   'data' 	=> ''
										   );
				}else{
					$response_data = array('status' => 'failed', 'message'=> 'Failed to delete box, Please try again.', 'data' => '');
				}
			}else{
				$response_data = array('status' => 'failed', 'message'=> 'Box is dispatched, You can not delete this box.', 'data' => '');
			}
		}else{
			$response_data['status'] = 'error';
			$response_data['message'] = validation_errors();
		}
		$this->__return_json_output($response_data);
	}
//Product box functions end
	
// 2. Prepare DR functions start
	//Listing Prepare Dispatch
	public function prepare_dr(){
		$this->actions = checkUserPermission('dispatch/prepare_dr', $this->uri->segment(3));
		if(!empty($this->uri->segment(3)) && ($this->uri->segment(3) == 'add' || $this->uri->segment(3) == 'edit' || $this->uri->segment(3) == 'view')){redirect(site_url('dispatch/prepare_dr'));}
		$crud = new grocery_CRUD();
		$crud->set_theme('bootstrap');
		$crud->unset_jquery();
		$crud->unset_bootstrap();
		$crud->unset_common_search();
						
		if(!in_array('export', $this->actions)){
			$crud->unset_export();
		}
		if(!in_array('print', $this->actions)){
			$crud->unset_print();
		}
				
		if(!in_array('edit', $this->actions)){
			$crud->unset_edit();
		}else{			
			$crud->set_edit_url_path(site_url('dispatch/dr/edit'));
		}
		
		if(!in_array('add', $this->actions)){
			$crud->unset_add();
		}else{
			$crud->set_add_url_path(site_url('dispatch/dr/add'));
		}
		
		if(in_array('view', $this->actions)){
			$crud->set_read_url_path(site_url('dispatch/dr/view'));
		}else{
			$crud->unset_read();
		}
		
		//$crud->add_bulk_action('Move To Estimate', site_url('bulk_action/action/move_to_estimate'), ' ', 'fa fa-check', 'status');
		$crud->add_bulk_action('Dispatch DR', site_url('bulk_action/action/move_to_dispatch'), ' ', 'fa fa-check', 'status');
		//$crud->unset_read();
		$crud->unset_delete();
		if(in_array('delete', $this->actions)){			
			$crud->add_action('Delete', 'triggerBulkDelete text-danger', '', 'fa fa-trash', array($this,'__callbackDeleteDispatchButton'), 'dialogbox');
			$crud->add_bulk_action('Delete', site_url('bulk_action/action/delete_dispatch'), ' text-danger', 'fa fa-trash', 'status');
		}
		/*
		if(in_array('lock', $this->actions)){
			$crud->add_action('Lock Data', 'triggerBulkDelete text-danger','','fa fa-lock',array($this,'__callbackLockActionButton'), 'dialogbox');	
			$crud->add_bulk_action('Lock Data', site_url('bulk_action/action/lock'), ' text-danger','fa fa-lock', 'editable');
		}
		*/
		
		if($crud->getState() == 'ajax_list'){
			$postData = $this->input->post();
			if(isset($postData['search_field']) && !empty($postData['search_field'])){
				//$columns = array('depot_name' =>'mp.name', 'market_place' =>'mp2.name', 'added_by' => 'admin.first_name');
				foreach($postData['search_field'] as $key=>$value){
					
					/*if(array_key_exists($value, $columns)){
						$_POST['search_field'][$key] = $columns[$value];
					}*/
					
					if($value == 'dispatch_date' || $value == 'departure_time' || $value == 'arrival_time'){
						$_POST['search_text'][$key] = get_date('Y-m-d', str_replace('/', '-', $postData['search_text'][$key]));
					}
				}
			}
		}
		
		$crud->set_subject('DR');
		$crud->set_table(PRODUCT_DISPATCH);
		$crud->set_relation('dispatch_from', MARKET_PLACE, 'name');
		$crud->set_relation('dispatch_to', MARKET_PLACE, 'name');
		$crud->set_relation('added_by', ADMINISTRATOR, 'first_name');
		$crud->where(array(PRODUCT_DISPATCH.'.status'=>'Active'));
		$crud->where(array(PRODUCT_DISPATCH.'.editable !='=>'Lock'));
		
		if(!in_array('view_all', $this->actions)){	
			$crud->where(array(PRODUCT_DISPATCH.'.added_by'=>$this->userID));
		}
		
		$crud->order_by('dispatch_date', 'DESC');
		
		$crud->columns('dr_number', 'total_box', 'total_box_qty', 'total_box_wt', 'dispatch_date', 'dispatch_from', 'dispatch_to', 'departure_time', 'arrival_time', 'vehicle_number', 'driver_name', 'driver_mobile_no', 'total_freight', 'advance_freight', 'remaining_freight', 'status', 'added_by');
		
		$crud->display_as(array( 'dr_number'=>'DR No.', 'total_box_qty'=>'Total Qty', 'total_box_wt'=>'Total Wt', 'dispatch_date'=>'Dispatch Date', 'departure_time'=>'Departure', 'arrival_time'=>'Arrival', 'driver_name'=>'Driver', 'driver_mobile_no'=>'Mob. No', 'market_id'=>'Depot'));				
		
		$crud->callback_column('dispatch_date', array($this, '__callaback_display_datetime'));
		$crud->callback_column('departure_time', array($this, '__callaback_display_datetime'));
		$crud->callback_column('arrival_time', array($this, '__callaback_display_datetime'));
		$output = $crud->render();
		$data = array('page_title'=> 'Prepare Dispatch', 'content_view'=>'setup/setting');		  
		$outputData = array_merge((array)$output, $data);	
		
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($outputData);	
	}
	
	public function __callbackDeleteDispatchButton($primary_key, $row){ 
		return site_url('bulk_action/action/delete_dispatch');
	}
	
	public function dr($action_mode = 'add', $dispatch_id = NULL){
		$this->actions = checkUserPermission('dispatch/prepare_dr', $action_mode);
		$data['go_back_url'] = site_url('dispatch/prepare_dr');
		$data['dispatch_info'] = array( 'dispatch_id'		=> '',
										'dr_number'			=> '',
										'dispatch_date'		=> get_datetime('d-m-Y H:i:s'),
										'dispatch_from'		=> '',
										'dispatch_to'		=> '',
										'market_id'			=> '',
										'departure_time' 	=> '',
										'arrival_time' 		=> '',
										'vehicle_number' 	=> '',
										'driver_name' 		=> '',
										'driver_mobile_no' 	=> '',
										'alter_mobile_no' => '',
										'total_freight' 	=> '',
										'advance_freight' 	=> '',
										'remaining_freight' => '',
										'remark' 			=> ''
										);
		
		$data['all_boxes'] = array();
		$data['disabled_date'] = '';
		$data['datepicker'] = 'datetimepicker';
		$data['page_title'] = 'Add DR';
		$data['action_mode'] = 'add';
		$data['content_view'] = 'dispatch/prepare_dr/prepare_dr_v';
		$dr_number = '';
		if($dispatch_id){
			$data['dispatch_info'] = $this->DM->get_dispatch_info($dispatch_id, 'Active');
			if(empty($data['dispatch_info'])){
				$this->messageci->set('Invalid request, Please try again.', 'error') ;
				redirect(site_url('dispatch/prepare_dr'));
			}
			$dr_number = $data['dispatch_info']['dr_number'];
			$data['all_boxes'] = $this->DM->get_despatched_boxes($dr_number, 'Active', $dispatch_id);
		}
		
		if($action_mode == 'add' || $action_mode == 'edit'){
			$data['all_depot'] 		 = $this->DM->get_market_places(2);
			$data['outside_markets'] = $this->DM->get_market_places(3);
		}
		
		if($action_mode == 'edit'){
			$data['action_mode'] = 'edit';
			$data['page_title'] = 'Edit DR No. '.$dr_number;
			$data['datepicker'] = 'datetimepicker';
			$data['disabled_date'] = '';
			
		}elseif($action_mode == 'view'){
			$data['action_mode'] = 'view';
			$data['page_title'] = 'DR No. '.$dr_number;
			$data['datepicker'] = '';
			$data['disabled_input'] = 'disabled="disabled"';
			$data['content_view'] = 'dispatch/prepare_dr/view_dr_v';
		}
		
		
		$this->template->set('stylesheet', array(base_url('assets/plugins/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')));
		$this->template->set('scriptsrc', array(base_url('assets/plugins/moment/min/moment-with-locales.min.js'),
												base_url('assets/plugins/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'),
												base_url('assets/modules/reports/jQuery.print.min.js'),
												base_url('assets/modules/reports/jquery.table2excel.min.js'),
												site_url('dispatch/assets/js/dispatch_common.js'),
												site_url('dispatch/assets/js/prepare_dr.js')
											));
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($data);
	}
	
	public function ajax_save_dr_info(){
		$response_data = array('status' => 'failed', 'message' => 'Invalid Request, Please reload the page and try again.', 'action_mode'=>'', 'data'=>'', 'redirect_url'=>'');	
		$form_valid = $this->__setFormRules('save_dispatch_info');
		if($form_valid){
			$post = $this->input->post();
			$action_mode = $post['action_mode'];
			$dispatch_id = $post['dispatch_id'];
			
			$dispatch_date = str_replace('/', '-', $post['dispatch_date']);
			$departure_time = str_replace('/', '-', $post['departure_time']);
			$arrival_time = str_replace('/', '-', $post['arrival_time']);
			
			$dispatch_info = array( 'dispatch_date'		=> get_datetime('Y-m-d H:i:s', $dispatch_date),
									'dispatch_from'		=> $post['dispatch_from'],
									'dispatch_to'		=> $post['dispatch_to'],
									'departure_time' 	=> get_datetime('Y-m-d H:i:s', $departure_time),
									'arrival_time' 		=> get_datetime('Y-m-d H:i:s', $arrival_time),
									'vehicle_number' 	=> $post['vehicle_number'],
									'driver_name' 		=> $post['driver_name'],
									'driver_mobile_no' 	=> $post['driver_mobile_no'],
									'alter_mobile_no' 	=> isset($post['alter_mobile_no']) ? $post['alter_mobile_no'] : '',
									'total_freight' 	=> $post['total_freight'],
									'advance_freight' 	=> $post['advance_freight'],
									'remaining_freight' => $post['remaining_freight'],
									'remark' 			=> isset($post['remark']) ? $post['remark'] : '',	
									'added_by'			=> $this->userID,
									'added_date'		=> get_datetime('Y-m-d H:i:s'),
									'status'			=> 'Active',
									'editable'			=> 'Unlock'
									);
			$result = FALSE;
			if($action_mode == 'add'){
				$dr_number = $this->DM->generate_dr_number(); //Generate DR Number
				if($dr_number){
					$dispatch_info['dr_number'] = $dr_number;
					$result 					= $this->db->insert(PRODUCT_DISPATCH, $dispatch_info);
					$dispatch_id 				= $this->db->insert_id();
					if($dispatch_id){
						$this->db->where(array('id'=>$dispatch_id))->update(PRODUCT_DISPATCH, array('dispatch_id'=>$dispatch_id));
						$this->db->where(array('dr_number'=>$dr_number))->update(PRODUCT_DR, array(
							'depot_id'  => $post['dispatch_from'],
							'market_id' => $post['dispatch_to'],
							'dr_date'   => get_datetime('Y-m-d', $dispatch_date),
							'status'    => 'Active'
						));
						$response_data = array('status' 		=> 'success', 
											   'message'		=> 'DR saved successfully!', 
											   'action_mode'	=> $action_mode,
											   'data' 			=> $dispatch_id,
											   'redirect_url' 	=> site_url('dispatch/dr/edit/'.$dispatch_id)
												);
					}else{
						$response_data = array('status' => 'failed', 'message'=> 'Insertion failed, Please try again.', 'data' => '');
					}
				}else{
					$response_data = array('status' => 'failed', 'message'=> 'Insertion failed, Please try again.', 'data' => '');
				}
			}elseif($action_mode == 'edit'){
				unset($dispatch_info['added_by'], $dispatch_info['added_date']);
				//Update only when dispatch status is Active
				$dispatch_res = $this->db->get_where(PRODUCT_DISPATCH, array('id'=>$dispatch_id, 'status'=>'Active'))->result_array();
				if(!empty($dispatch_res)){
					$result = $this->db->where(array('id' => $dispatch_id))->update(PRODUCT_DISPATCH, $dispatch_info);
					if($result){
						$response_data = array('status' 		=> 'success', 
											   'message'		=> 'DR updated successfully!', 
											   'action_mode'	=> $action_mode,
											   'data' 			=> $dispatch_id,
											   'redirect_url' 	=> ''
												);
					}else{
						$response_data = array('status' => 'failed', 'message'=> 'Updating failed, Please try again.', 'data' => '');
					}
				}else{
					$response_data['message'] = 'This DR is dispatched, You can not update this DR.';
				}
			}
			
		}else{
			$response_data['status'] = 'error';
			$response_data['message'] = validation_errors();
		}
		$this->__return_json_output($response_data);
	}
	
	public function ajax_add_dr_box($s_no = ''){
		$response_data = array('status' => 'failed', 'message' => 'Invalid Request, Please reload the page and try again.', 'redirect_url'=>'');
		$form_valid = $this->__setFormRules('add_dr_box');
		if($form_valid){
			$post_data 		= $this->input->post();
			$dispatch_id 	= $this->input->post('dispatch_id');
			$box_number 	= trim(strtoupper($this->input->post('box_number')));
			$dr_number 		= $this->input->post('dr_number');
			
			//Add Box only when dispatch status is Active
			$dispatch_info = $this->DM->get_dispatch_info($dispatch_id, 'Active');		
			if(!empty($dispatch_info) && isset($dispatch_info['dispatch_id']) && !empty($dispatch_info['dispatch_id'])){
				//Add Box only when box_number exist
				$box_existence = $this->db->where('box_number', $box_number)->count_all_results(PRODUCT_BOX);
				if($box_existence > 0){
					//If box exist check box is not already added in PRODUCT_DISPATCH_BOX
					$dispatch_box_existence = $this->db->where('box_number', $box_number)->count_all_results(PRODUCT_DISPATCH_BOX);
					if($dispatch_box_existence == 0){
						$product_dispatch_box = array('dispatch_id'		=> $dispatch_id,
													  'dr_number'		=> $dr_number,
													  'box_number'		=> $box_number,
													  'status'			=> 'Active',
													  'added_by'		=> $this->userID,
													  'added_date' 		=> get_datetime('Y-m-d H:i:s'),
													  'action_microtime'=> microtime(true),
													  'source' 			=> SOURCE													  
													  );
						$result = $this->db->insert(PRODUCT_DISPATCH_BOX, $product_dispatch_box);
						if($result){
							$this->DM->update_dispatch_summary($dispatch_id, $dr_number);
							$this->db->where(array('box_number'=>$box_number))->update(PRODUCT_BOX, array('status'=>'Prepared', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true)));
							$this->db->where(array('box_number'=>$box_number))->update(PRODUCT_BOX_ITEMS, array('status'=>'Prepared', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true)));
							$box_data = $this->DM->get_box_detail($box_number);
							$box_detail = array('box_number'   	 => $box_number,
												'box_quantity' 	 => $box_data['box_quantity'],
												'carret_weight'	 => $box_data['carret_weight'],
												'box_weight'   	 => $box_data['box_weight'],
												'dispatch_status'=> 'Active'
												);
							$html = '';
							$box_items = !empty($box_data['box_items']) ? explode('|', $box_data['box_items']) : array();
							if(is_array($box_items) && !empty($box_items)){
								$dataset = array();
								foreach($box_items as $box){
									$items = !empty($box) ? explode('/', $box) : array();
									$dataset[] = array('fish_code' 	=> @$items[0],
													   'fish_name' 	=> @$items[1],
													   'fish_qty' 	=> @$items[2],
													   'fish_wt' 	=> @$items[3],
													   'box_wt' 	=> @$items[4]
													   );
								}
								$html = dr_box_list_html(($s_no+1), $box_detail, $dataset, 'edit');
							}
							$response_data = array('status' => 'success', 
												   'message'=> 'Box number '.$box_number.' added successfully.',
												   'data' 	=> $html
												   );
						}else{
							$response_data = array('status' => 'failed', 'message'=> 'Data insertion failed!', 'data' => '');
						}
					}else{
						$response_data['status'] = 'error';
						$response_data['message'] = 'Box number is already dispatched.';
					}
				}else{
					$response_data['status'] = 'error';
					$response_data['message'] = 'Box number is not exist.';
				}
			}else{
				$response_data['message'] = 'This DR is dispatched, You can not add any box.';
				$response_data['redirect_url'] = site_url('dispatch/prepare_dr');
			}
		}else{
			$response_data['status'] = 'error';
			$response_data['message'] = validation_errors();
		}
		$this->__return_json_output($response_data);
	}
	
	public function ajax_remove_dr_box(){
		$response_data = array('status' => 'failed', 'message' => 'Invalid Request, Please reload the page and try again.', 'redirect_url'=>'');	
		$form_valid = $this->__setFormRules('delete_dr_box');
		if($form_valid){
			$post_data = $this->input->post();
			$box_number  = $this->input->post('box_number');
			$dispatch_id = $this->input->post('dispatch_id');
			$dr_number   = $this->input->post('dr_number');
			
			//Delete Box only when PRODUCT_DISPATCH_BOX status is Active
			$dispatch_info = $this->db->get_where(PRODUCT_DISPATCH_BOX, array('dispatch_id'=>$dispatch_id, 'dr_number'=>$dr_number, 'box_number'=>$box_number, 'status'=>'Active', 'added_by'=>$this->userID))->result_array();
			if(!empty($dispatch_info) && isset($dispatch_info[0]['ID']) && !empty($dispatch_info[0]['ID'])){
				//Delete Box only when box_number not found in SALE_ITEMS
				$sale_item_info = $this->db->where(array('box_number'=>$box_number, 'status'=>'Active'))->count_all_results(SALE_ITEMS);
				if($sale_item_info == 0){
					$pdb_id 	= $dispatch_info[0]['ID'];
					$result 	= $this->db->delete(PRODUCT_DISPATCH_BOX, array('ID' => $pdb_id, 'dr_number'=>$dr_number, 'box_number'=>$box_number));
					
					if($result){
						$this->db->where(array('box_number'=>$box_number))->update(PRODUCT_BOX, array('status'=>'Active', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true)));
						$this->db->where(array('box_number'=>$box_number))->update(PRODUCT_BOX_ITEMS, array('status'=>'Active', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true)));
						$this->DM->update_dispatch_summary($dispatch_id, $dr_number);
						$response_data = array('status' => 'success', 
											   'message'=> 'Box '.$box_number.' deleted successfully.',
											   'data' 	=> ''
											   );
					}else{
						$response_data = array('status' => 'failed', 'message'=> 'Deleting box failed, Please try again.', 'data' => '');
					}
				}else{
					$response_data = array('status' => 'failed', 'message'=> 'This box is sold, You can not delete it.', 'data' => '');
				}
			}else{
				$response_data['message'] = 'This DR is dispatched, You can not delete any box.';
				//$response_data['redirect_url'] = site_url('dispatch/prepare_dr');
			}
		}else{
			$response_data['status'] = 'error';
			$response_data['message'] = validation_errors();
		}
		$this->__return_json_output($response_data);
	}
//Prepare DR functions end

// 3. Dispatched DR functions start
	public function dispatched_dr(){
		$this->actions = checkUserPermission('dispatch/dispatched_dr', $this->uri->segment(3));
		if(!empty($this->uri->segment(3)) && ($this->uri->segment(3) == 'add' || $this->uri->segment(3) == 'edit' || $this->uri->segment(3) == 'view')){redirect(site_url('dispatch/dispatched_dr'));}
		$crud = new grocery_CRUD();
		$crud->set_theme('bootstrap');
		$crud->unset_jquery();
		$crud->unset_bootstrap();
		$crud->unset_common_search();
		$crud->unset_edit();
		$crud->unset_add();			
		$crud->unset_delete();
		
		if(!in_array('export', $this->actions)){
			$crud->unset_export();
		}
		if(!in_array('print', $this->actions)){
			$crud->unset_print();
		}
		
		if(in_array('view', $this->actions)){
			$crud->set_read_url_path(site_url('dispatch/dispatched/view'));
		}else{
			$crud->unset_read();
		}
		
		if(in_array('transfer', $this->actions)){
			$crud->add_action('Transfer', '', 'dispatch/transfer', 'fa fa-truck', array($this,'__callbackTransferButton'), '');
		}
		
		if(in_array('estimate', $this->actions)){
			$crud->add_action('Estimate', '', '', 'fa fa-pencil', array($this, '__callbackEstimateActionButton'), '');
			//$crud->add_bulk_action('Active', site_url('bulk_action/action/active'), '', 'fa fa-check', 'status');
			//$crud->add_bulk_action('Inactive', site_url('bulk_action/action/inactive'), '', 'fa fa-ban', 'status');
		}
		
		$crud->add_bulk_action('Move To Prepare DR', site_url('bulk_action/action/move_to_prepare'), ' ', 'fa fa-check', 'status');
		//$crud->add_bulk_action('Move To Estimate', site_url('bulk_action/action/move_to_estimate'), ' ', 'fa fa-check', 'status');
		
		if($crud->getState() == 'ajax_list'){
			$postData = $this->input->post();
			if(isset($postData['search_field']) && !empty($postData['search_field'])){
				//$columns = array('depot_name' =>'mp.name', 'market_place' =>'mp2.name', 'added_by' => 'admin.first_name');
				foreach($postData['search_field'] as $key=>$value){
					
					/*if(array_key_exists($value, $columns)){
						$_POST['search_field'][$key] = $columns[$value];
					}*/
					
					if($value == 'dispatch_date' || $value == 'departure_time' || $value == 'arrival_time'){
						$_POST['search_text'][$key] = get_date('Y-m-d', str_replace('/', '-', $postData['search_text'][$key]));
					}
				}
			}
		}
		
		$crud->set_subject('Dispatched DR');
		$crud->set_table(PRODUCT_DISPATCH);
		$crud->set_relation('dispatch_from', MARKET_PLACE, 'name');
		$crud->set_relation('dispatch_to', MARKET_PLACE, 'name');
		//$crud->where(array(PRODUCT_DISPATCH.'.editable !='=>'Lock'));
		$crud->where(array(PRODUCT_DISPATCH.'.status'=>'Dispatched'));
		$crud->order_by('added_date', 'DESC');
		
		$crud->columns('dr_number', 'dispatch_date', 'dispatch_from', 'dispatch_to', 'total_box', 'total_box_qty', 'total_box_wt', 'departure_time', 'arrival_time', 'vehicle_number', 'driver_name',/* 'driver_mobile_no',*/ 'total_freight', /*'advance_freight', 'remaining_freight',*/ 'status');
		$crud->display_as(array( 'dr_number'=>'DR No.', 'total_box_qty'=>'Total Qty', 'total_box_wt'=>'Total Wt', 'dispatch_date'=>'Dispatch Date', 'dispatch_from'=>'Source', 'dispatch_to'=>'Destination', 'departure_time'=>'Departure', 'arrival_time'=>'Arrival', 'driver_name'=>'Driver', 'driver_mobile_no'=>'Mob. No', 'market_id'=>'Depot'));				
		$crud->callback_column('total_boxes', array($this, '__callaback_total_boxes'));
		$crud->callback_column('dispatch_date', array($this, '__callaback_display_datetime'));
		$crud->callback_column('departure_time', array($this, '__callaback_display_datetime'));
		$crud->callback_column('arrival_time', array($this, '__callaback_display_datetime'));
		$output = $crud->render();
		$data = array('page_title'=> 'Dispatched DR', 'content_view'=>'setup/setting');		  
		$outputData = array_merge((array)$output, $data);	
		
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($outputData);	
	}
	
	public function __callaback_total_boxes($primary_key, $row){
		return $this->db->where(array('dispatch_id'=>$row->dispatch_id, 'dr_number'=>$row->dr_number, 'status'=>'Dispatched'))->count_all_results(PRODUCT_DISPATCH_BOX);
	}
	
	public function __callbackTransferButton($primary_key, $row){
		return site_url('dispatch/transfer_dr/transfer/'.$primary_key);
	}
	
	public function __callbackEstimateActionButton($primary_key, $row){
		return site_url('dispatch/estimate_dr/estimate/'.$primary_key);
	}
	
	public function dispatched($action_mode = 'view', $dispatch_id = NULL){
		$this->actions = checkUserPermission('dispatch/dispatched_dr', $action_mode);
		$data['go_back_url'] 	= site_url('dispatch/dispatched_dr');
		$data['dispatch_info'] 	= array('dispatch_id'		=> '',
										'dr_number'			=> '',
										'dispatch_from'		=> '',
										'dispatch_to'		=> '',
										'dispatch_date'		=> '',
										'market_id'			=> '',
										'departure_time' 	=> '',
										'arrival_time' 		=> '',
										'vehicle_number' 	=> '',
										'driver_name' 		=> '',
										'driver_mobile_no' 	=> '',
										'total_freight' 	=> '',
										'advance_freight' 	=> '',
										'remaining_freight' => ''
										);
		
		$data['all_boxes'] 		= array();
		$data['action_mode'] 	= 'view';
		$data['disabled_input'] = 'disabled="disabled"';
		$data['content_view'] 	= 'dispatch/dispatch_dr/dispatched_dr_v';
		$dr_number 				= '';
		if($dispatch_id){
			$data['dispatch_info'] = $this->DM->get_dispatch_info($dispatch_id, 'Dispatched');
			if(empty($data['dispatch_info'])){
				$this->messageci->set('Invalid request, Please try again.', 'error') ;
				redirect(site_url('dispatch/dispatched_dr'));
			}
			$dr_number = $data['dispatch_info']['dr_number'];
			$data['all_boxes'] = $this->DM->get_despatched_boxes($dr_number, 'Dispatched', $dispatch_id);
		}
		
		$data['page_title'] 	= 'DR No. '.$dr_number.' Detail';
		$this->template->set('scriptsrc', array(base_url('assets/modules/reports/jQuery.print.min.js'),
												base_url('assets/modules/reports/jquery.table2excel.min.js'),
												site_url('dispatch/assets/js/dispatched_dr.js')
							));
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($data);
	}
	
	public function estimate_dr($action_mode = 'estimate', $dispatch_id = NULL){
		$this->actions = checkUserPermission('dispatch/dispatched_dr', $action_mode);
		$data['go_back_url'] = site_url('dispatch/dispatched_dr');
		$data['dispatch_info'] = array( 'dispatch_id'		=> '',
										'dispatch_date'		=> '',
										'market_id'			=> '',
										'departure_time' 	=> '',
										'arrival_time' 		=> '',
										'vehicle_number' 	=> '',
										'driver_name' 		=> '',
										'driver_mobile_no' 	=> '',
										'total_freight' 	=> '',
										'advance_freight' 	=> '',
										'remaining_freight' => ''
										);
		
		$data['readonly'] = 'readonly="readonly"';
		
		$data['action_mode'] = 'edit';
		if($action_mode == 'view'){
			$data['page_title'] = 'Estimate Info';
			$data['action_mode'] = $action_mode;
		}
					
		$data['dispatch_info'] = $this->DM->get_dispatch_info($dispatch_id, 'Dispatched');
		if(empty($data['dispatch_info'])){
			$this->messageci->set('Invalid request, Please try again.', 'error') ;
			redirect(site_url('dispatch/dispatched_dr'));
		}
		$dr_number = $data['dispatch_info']['dr_number'];
		$data['dhalta'] = $data['dispatch_info']['dhalta'];
		$data['commission'] = $data['dispatch_info']['commission'];
		$data['expenses'] = $data['dispatch_info']['expenses'];
		
		$data['all_boxes'] = $this->DM->get_despatched_boxes($dr_number, 'Dispatched', $dispatch_id);
		$data['content_view'] = 'dispatch/dispatch_dr/estimate_dr_v';
		$data['estimate_by'] = $this->load->view('dispatch_dr/ajax_estimate_by_box_v', $data, true);
		$data['page_title'] = 'Estimate DR No. '.$dr_number;
		$this->template->set('stylesheet', array(base_url('assets/plugins/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')));
		$this->template->set('scriptsrc', array(base_url('assets/plugins/moment/min/moment-with-locales.min.js'),
												base_url('assets/plugins/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'),
												base_url('assets/modules/reports/jQuery.print.min.js'),
												base_url('assets/modules/reports/jquery.table2excel.min.js'),
												site_url('dispatch/assets/js/dispatch_common.js'),
												site_url('dispatch/assets/js/estimate_dr.js')
												));
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($data);
	}
	
	public function update_box_number(){
		$result = $this->db->get_where(PRODUCT_DISPATCH_BOX, array('dr_number' => 8))->result_array();
		foreach($result as $box){
			//$sql = 'UPDATE '.PRODUCT_DISPATCH_BOX.' SET `box_number` = "0'.$box['box_number'].'" WHERE `ID` = "'.$box['ID'].'"';
			$this->db->where('ID', $box['ID'])->update(PRODUCT_DISPATCH_BOX, array('box_number' => "0".$box['box_number'], 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true))); //8
		}
	}
	
	public function ajax_estimate_by(){
		$response = array('status'=>'', 'message'=>'', 'data'=>'');
		$post_data = $this->input->post();
		$estimate_by = $post_data['estimate_by'];
		$dispatch_id = $post_data['dispatch_id'];
		$dr_number = $post_data['dr_number'];
		$action_mode = $post_data['action_mode'];
		if($estimate_by == 'box'){
			$data['dispatch_id'] = $dispatch_id;
			$data['action_mode'] = $action_mode;
			$data['all_boxes'] = $this->DM->get_despatched_boxes($dr_number, 'Dispatched', $dispatch_id);
			$estimate_by_view = $this->load->view('dispatch_dr/ajax_estimate_by_box_v', $data, true);
			$response = array('status'=>'success', 'message'=>'Estimate by box.', 'data'=>$estimate_by_view);
		}else if($estimate_by == 'fish'){
			$data['dispatch_id'] = $dispatch_id;
			$data['action_mode'] = $action_mode;
			$data['fishes'] = $this->DM->get_estimate_by_fishes($dr_number, 'Dispatched', $dispatch_id);
			$estimate_by_view = $this->load->view('dispatch_dr/ajax_estimate_by_fish_v', $data, true);
			$response = array('status'=>'success', 'message'=>'Estimate by box.', 'data'=>$estimate_by_view);
		}
		$this->__return_json_output($response);
	}
	
	public function ajax_estimate_by_box(){
		$response_data = array('status' => 'failed', 'message' => 'Invalid Request.');	
		$form_valid = $this->__setFormRules('estimate_by_box');
		if($form_valid){
			$item_id 			= $this->input->post('item_id');
			$box_number 		= $this->input->post('box_number');
			$estimated_price 	= $this->input->post('estimated_price');
			$dr_number 			= $this->input->post('dr_number');
			
			//Estimate only when PRODUCT_DISPATCH_BOX status is Dispatched
			$dispatch_info = $this->db->get_where(PRODUCT_DISPATCH_BOX, array('dr_number'=>$dr_number, 'box_number'=>$box_number, 'status'=>'Dispatched'))->result_array();
			if(!empty($dispatch_info) && isset($dispatch_info[0]['ID']) && !empty($dispatch_info[0]['ID'])){
				//Estimate only when PRODUCT_BOX_ITEMS status is Dispatched
				//array('item_id' => $item_id, 'box_number'=>$box_number, 'status'=>'Dispatched')
				$box_item_info = $this->db->get_where(PRODUCT_BOX_ITEMS, array('item_id' => $item_id, 'box_number'=>$box_number))->result_array();
				if(!empty($box_item_info) && isset($box_item_info[0]['item_id']) && !empty($box_item_info[0]['item_id'])){
					$where = array('item_id' => $item_id, 'box_number' => $box_number);
					//$where = array('item_id' => $item_id, 'box_number' => $box_number, 'status'=>'Dispatched');
					$estimate_data = array('status'=>'Dispatched', 'estimated_price'=>$estimated_price, 'estimated_by'=>$this->userID, 'estimated_date'=>get_datetime('Y-m-d H:i:s'),'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true));
					$result = $this->db->where($where)->update(PRODUCT_BOX_ITEMS, $estimate_data);
					
					if($result){
						$response_data = array('status' => 'success', 
											   'message'=> 'Box item Estimated successfully.',
											   'data' 	=> ''
											   );
					}else{
						$response_data = array('status' => 'failed', 'message'=> 'Box item Estimation failed, Please try again.', 'data' => '');
					}
				}else{
					$response_data = array('status' => 'failed', 'message'=> 'Box is not in Estimate. You can not estimate price.', 'data' => '');
				}
			}else{
				$response_data = array('status' => 'failed', 'message'=> 'Box is not in dispatched box list. You can not estimate the price.', 'data' => '');
			}
		}else{
			$response_data['status'] = 'error';
			$response_data['message'] = validation_errors();
		}
		$this->__return_json_output($response_data);
	}
	
	public function ajax_estimate_by_fish(){
		$response_data = array('status' => 'failed', 'message' => 'Invalid Request.');
		$post_data = $this->input->post();
		
		$form_valid = $this->__setFormRules('estimate_by_fish');
		if($form_valid){
			$dispatch_id 		= $this->input->post('dispatch_id');
			$dr_number 			= $this->input->post('dr_number');
			$estimated_price 	= $this->input->post('estimated_price');
			$fish_code 			= $this->input->post('fish_code');
			$fish_qty			= $this->input->post('fish_qty');
			
			//Estimate only when PRODUCT_DISPATCH_BOX status is Dispatched
			$result_boxes = $this->db->select('box_number')->get_where(PRODUCT_DISPATCH_BOX, array('dr_number'=>$dr_number, 'status'=>'Dispatched'))->result_array();
			if(!empty($result_boxes)){
				$boxes_array = array_column($result_boxes, 'box_number');
				
				//Estimate only when PRODUCT_BOX_ITEMS status is Dispatched
				$where = array('fish_code' => $fish_code, 'fish_qty' => $fish_qty, 'status' => 'Dispatched');
				
				$estimate_data = array('estimated_price'=>$estimated_price, 'estimated_by'=>$this->userID, 'estimated_date'=>get_datetime('Y-m-d H:i:s'), 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true));
				$result = $this->db->where_in('box_number', $boxes_array)->where($where)->update(PRODUCT_BOX_ITEMS, $estimate_data);
				$affected_row = $this->db->affected_rows();
				if($result){
					$response_data = array('status' => 'success', 
										   'message'=> 'Estimated successfully.',
										   'data' 	=> $affected_row
										   );
				}else{
					$response_data = array('status' => 'failed', 'message'=> 'Item is not in Estimate. You can not estimate price.', 'data' => '');
				}
			}else{
				$response_data = array('status' => 'failed', 'message'=> 'Estimation Failed, Please reload page and try again.', 'data' => '');
			}
		}else{
			$response_data['status'] = 'error';
			$response_data['message'] = validation_errors();
		}
		$this->__return_json_output($response_data);
	}
	
	public function transfer_dr($action_mode = 'transfer', $dispatch_id = NULL){
		$this->actions = checkUserPermission('dispatch/dispatched_dr', $action_mode);
		if($dispatch_id){
			$data['dispatch_info'] = $this->DM->get_dispatch_info($dispatch_id, 'Dispatched');
			if(empty($data['dispatch_info'])){
				$this->messageci->set('Invalid request, Please try again.', 'error') ;
				redirect(site_url('dispatch/dispatched_dr'));
			}
			$dr_number = $data['dispatch_info']['dr_number'];
			$data['all_boxes'] = $this->DM->get_despatched_boxes($dr_number, 'Dispatched', $dispatch_id);
		}
		$data['go_back_url'] 	= site_url('dispatch/dispatched_dr');
		$data['action_mode'] 	= 'transfer';
		$data['page_title'] 	= 'Transfer DR';
		$data['datepicker'] 	= 'datetimepicker';
		$data['disabled_date'] 	= '';
		$data['all_depot'] 		= $this->DM->get_market_places(2);
		$data['outside_markets']= $this->DM->get_market_places(3);
		$data['content_view'] 	= 'dispatch/dispatch_dr/transfer_dr_v';
		
		$this->template->set('stylesheet', array(base_url('assets/plugins/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')));
		$this->template->set('scriptsrc', array(base_url('assets/plugins/moment/min/moment-with-locales.min.js'),
												base_url('assets/plugins/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'),
												base_url('assets/modules/reports/jQuery.print.min.js'),
												base_url('assets/modules/reports/jquery.table2excel.min.js'),
												site_url('dispatch/assets/js/dispatch_common.js'),
												site_url('dispatch/assets/js/transfer_dispatch.js')
											    ));
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($data);
	}
	
	public function ajax_save_transfer_info(){
		$response_data = array('status' => 'failed', 'message' => 'Invalid Request.');
		$post = $this->input->post();
		$form_valid = $this->__setFormRules('save_transfer_info');
		if($form_valid){
			$post 			= $this->input->post();
			$tr_boxes		= $post['boxes']; //this boxes are transferred, assign new dispatch id to this boxes
			$action_mode 	= $post['action_mode'];
			$dispatch_id 	= $post['dispatch_id'];
			$dr_number 	 	= $post['dr_number'];
			$dispatch_date 	= str_replace('/', '-', $post['dispatch_date']);
			$departure_time = str_replace('/', '-', $post['departure_time']);
			$arrival_time 	= str_replace('/', '-', $post['arrival_time']);
			
			$dispatch_info = array( 'ref_dispatch_id'	=> $dispatch_id,
									'dr_number'			=> $dr_number,
									'dispatch_date'		=> get_datetime('Y-m-d H:i:s', $dispatch_date),
									'dispatch_from'		=> $post['dispatch_from'],
									'dispatch_via'		=> $post['dispatch_via'], //Old Destination
									'dispatch_to'		=> $post['dispatch_to'],
									'departure_time' 	=> get_datetime('Y-m-d H:i:s', $departure_time),
									'arrival_time' 		=> get_datetime('Y-m-d H:i:s', $arrival_time),
									'vehicle_number' 	=> $post['vehicle_number'],
									'driver_name' 		=> $post['driver_name'],
									'driver_mobile_no' 	=> $post['driver_mobile_no'],
									'alter_mobile_no' 	=> $post['alter_mobile_no'],
									'total_freight' 	=> $post['total_freight'],
									'advance_freight' 	=> $post['advance_freight'],
									'remaining_freight' => $post['remaining_freight'],
									'remark' 			=> $post['remark'],
									'status' 			=> 'Dispatched',
									'added_by'			=> $this->userID,
									'added_date'		=> get_datetime('Y-m-d H:i:s'),
									'action_microtime'	=> microtime(true),
									'source' 			=> SOURCE
									);
			if($action_mode == 'transfer'){
				//Insert New Dispatch
				$dispatch_info['added_by'] = $this->userID;
				$dispatch_info['added_date'] = get_datetime('Y-m-d H:i:s');
				$this->db->insert(PRODUCT_DISPATCH, $dispatch_info);
				$new_dispatch_id = $this->db->insert_id();
				if($new_dispatch_id){
					//Transfer only those boxes whose box_number not found in SALE_ITEMS
					//So unset those boxes whose sale_id found in SALE_ITEMS
					$sold_boxes = $this->db->select('box_number')->where_in('box_number', $tr_boxes)->where('status','Active')->get(SALE_ITEMS)->result_array();
					if(!empty($sold_boxes)){
						foreach($sold_boxes as $sbox){
							//if box number found unset its key from $tr_boxes
							if (($key = array_search($sbox['box_number'], $tr_boxes)) !== false) {
								unset($tr_boxes[$key]);
							}
						}
					}
					//Assign new dispatch id to transferred boxes ($tr_boxes)
					$this->db->where_in('box_number', $tr_boxes)->update(PRODUCT_DISPATCH_BOX, array('dispatch_id'=>$new_dispatch_id, 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true)));
					$this->DM->update_dispatch_summary($new_dispatch_id, $dr_number);
					$this->DM->update_dispatch_summary($dispatch_id, $dr_number);
				}
			}
			
			if($new_dispatch_id){
				$response_data = array('status' 	=> 'success', 
									   'message'	=> count($tr_boxes).' boxes successfully transferred.',
								  	   'data' 		=> $new_dispatch_id,
									   'redirect_url' => site_url('dispatch/dispatched_dr')
								  	   );
			}else{
				$response_data = array('status' => 'failed', 'message'=> 'Operation failed!, Please try again.', 'data' => '');
			}
		}else{
			$response_data['status'] = 'error';
			$response_data['message'] = validation_errors();
		}
		$this->__return_json_output($response_data);
	}
	
	public function ajax_edit_trf_mode($dispatch_id = NULL){
		$data = array('status'=>'failed', 'message'=>'Invalid request, Please try again.', 'data'=>'');
		if($dispatch_id != NULL){
			$data['dispatch_info'] 	= $this->DM->get_dispatch_info($dispatch_id, 'Dispatched');
			$data['all_boxes'] 		= $this->DM->get_tr_boxes($dispatch_id, $data['dispatch_info']['ref_dispatch_id'], 'Dispatched');
			$data['go_back_url'] 	= site_url('dispatch/dispatched/view/'.$dispatch_id);
			$data['action_mode'] 	= 'transfer';
			$data['page_title'] 	= 'Update Transfer DR No. '.$data['dispatch_info']['dr_number'];
			$data['datepicker'] 	= 'datetimepicker';
			$data['disabled_date'] 	= '';
			$data['all_depot'] 		= $this->DM->get_market_places(2);
			$data['outside_markets']= $this->DM->get_market_places(3);
			$data['form_action']	= site_url('dispatch/update_transfer/');
			$data['stylesheet']		= array(base_url('assets/plugins/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css'));
			$data['scriptsrc']		= array(base_url('assets/plugins/moment/min/moment-with-locales.min.js'),
											base_url('assets/plugins/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'),
											base_url('assets/modules/reports/jQuery.print.min.js'),
											base_url('assets/modules/reports/jquery.table2excel.min.js'),
											site_url('dispatch/assets/js/dispatch_common.js'),
											site_url('dispatch/assets/js/transfer_dispatch.js')
											);
			$html = $this->load->view('dispatch_dr/ajax_edit_transfer_v', $data, true);
			$data = array('status'=>'success', 'data'=>$html);
		}
		$this->__return_json_output($data);
	}
	
	public function update_transfer(){
		$response_data = array('status'=>'failed', 'message'=>'Invalid request, Please try again.', 'data'=>'');
		$form_validation = $this->__setFormRules('save_transfer_info');
		if($form_validation){
			$post 			= $this->input->post();
			$tr_boxes 		= $post['boxes']; //this boxes are transferred, assign new dispatch id to this boxes
			$all_boxes 		= $post['all_boxes'];
			$action_mode 	= $post['action_mode'];
			$dispatch_id 	= $post['dispatch_id'];
			$ref_dispatch_id= $post['ref_dispatch_id'];
			$dr_number 	 	= $post['dr_number']; //Old DR Number
			$dispatch_date 	= str_replace('/', '-', $post['dispatch_date']);
			$departure_time = str_replace('/', '-', $post['departure_time']);
			$arrival_time 	= str_replace('/', '-', $post['arrival_time']);
			
			$dispatch_info = array( 'dispatch_date'		=> get_datetime('Y-m-d H:i:s', $dispatch_date),
									'dispatch_from'		=> $post['dispatch_from'],
									'dispatch_to'		=> $post['dispatch_to'],
									'departure_time' 	=> get_datetime('Y-m-d H:i:s', $departure_time),
									'arrival_time' 		=> get_datetime('Y-m-d H:i:s', $arrival_time),
									'vehicle_number' 	=> $post['vehicle_number'],
									'driver_name' 		=> $post['driver_name'],
									'driver_mobile_no' 	=> $post['driver_mobile_no'],
									'alter_mobile_no' 	=> $post['alter_mobile_no'],
									'total_freight' 	=> $post['total_freight'],
									'advance_freight' 	=> $post['advance_freight'],
									'remaining_freight' => $post['remaining_freight'],
									'updated_by' 		=> $this->userID,
									'updated_date'		=> get_datetime('Y-m-d H:i:s'),
									'action_microtime'	=> microtime(true)
									);
			$result = $this->db->where(array('dispatch_id'=>$dispatch_id))->update(PRODUCT_DISPATCH, $dispatch_info);
			if($result){
				//Assign current dispatch id to checked boxes
				$this->db->where_in('box_number', $tr_boxes)->update(PRODUCT_DISPATCH_BOX, array('dispatch_id'=>$dispatch_id, 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true)));
				$this->DM->update_dispatch_summary($dispatch_id, $dr_number);
				foreach($tr_boxes as $tr_box){
					//if box number found unset its key from $all_boxes
					if (($key = array_search($tr_box, $all_boxes)) !== false) {
						unset($all_boxes[$key]);
					}
				}
				if(!empty($all_boxes)){
					//Assign ref_dispatch_id to unchecked boxes
					$this->db->where_in('box_number', $all_boxes)->update(PRODUCT_DISPATCH_BOX, array('dispatch_id'=>$ref_dispatch_id, 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true)));
					$this->DM->update_dispatch_summary($ref_dispatch_id, $dr_number);
				}
				
				$response_data = array('status' 	=> 'success', 
									   'message'	=> count($tr_boxes).' boxes successfully transferred.',
								  	   'data' 		=> $dispatch_id,
									   'redirect_url' => site_url('dispatch/dispatched/view/'.$dispatch_id)
								  	   );
									   
			}else{
				$response_data = array('status' => 'failed', 'message'=> 'Operation failed!, Please try again.', 'data' => '');
			}
		}
		$this->__return_json_output($response_data);
	}
	
	public function ajax_save_dhalta(){
		$data = array('status'=>'danger', 'message'=>'Invalid method');
		$checkForm = $this->__setFormRules('dhalta');
		if($checkForm){
			$dispatch_id = $this->input->post('dispatch_id');
			$dr_number = $this->input->post('dr_number');
			$dhalta = $this->input->post('dhalta');
			$commission = $this->input->post('commission');
			$expenses = $this->input->post('expenses');
			$prepareData = array('dhalta'=>$dhalta, 'commission'=>$commission, 'expenses'=>$expenses);
			$result = $this->db->where(array('dispatch_id'=>$dispatch_id, 'dr_number'=>$dr_number))->update(PRODUCT_DISPATCH, $prepareData);
			if($result){
				$data = array('status'=>'success', 'message'=>'Dhalta, Commission and Expenses saved successfully!');
			}else{
				$data = array('status'=>'danger', 'message'=>'data saving failed!');
			}
		}else{
			$error_array = array_values($this->form_validation->error_array());
			$data = array('status'=>'danger', 'message'=>$error_array);
		}
		
		
		$this->__return_json_output($data);
	}
	
	//Dispatched DR functions end

	//Common Function
	public function __callbackDeleteActionButton($primary_key, $row){ 
		return site_url('bulk_action/action/mark_delete');
	}
	
	public function __callbackLockActionButton($primary_key, $row){ 
		return site_url('bulk_action/action/lock');
	}
	
	public function __callaback_display_datetime($value, $row){
		return get_datetime('d/m/Y', $value);
	}
	
	public function __return_json_output($data){
		//$this->output->set_content_type('application/json');
		//$this->output->set_output(json_encode($data));
		header("Content-type:application/json; charset=UTF8");
		echo json_encode($data);
		die;
	}
	
	//formvalidation callback
	public function checkPreparedBoxExistence($box_number){
		$dispatch_id 	= $this->input->post('dispatch_id');
		$dispatch_from 	= $this->input->post('dispatch_from');
		$source_depot 	= $this->input->post('source_depot');
		$data = FALSE;
		if(!empty($box_number)){
			//check box is availble in depot by validating box_number
			$box_number = trim(strtoupper($box_number));
			$result = $this->db->where(array('box_number'=>$box_number))->get(PRODUCT_BOX)->result_array();
			if(!empty($result)){
				$result = $this->db->where(array('box_number'=>$box_number, 'status'=>'Active'))->count_all_results(PRODUCT_DISPATCH_BOX);
				if($result == 0){
					$data = TRUE;
				}else{
					$this->form_validation->set_message('checkPreparedBoxExistence', 'Box number '.$box_number.' is already dispatched.');
				}
			}else{
				$this->form_validation->set_message('checkPreparedBoxExistence', 'Box number '.$box_number.' is not found.');
			}
		}else{
			$this->form_validation->set_message('checkPreparedBoxExistence', 'Please provide box number.');
		}
		return $data;
	}
	
	//formvalidation callback
	public function checkBoxExistence(){
		$data = FALSE;
		$box_id 	= $this->input->post('box_id');
		$box_number = strtoupper($this->input->post('box_number'));
		if(!empty($box_number)){
			if($box_id){
				$this->db->where('id !=', $box_id);
			}
			$result = $this->db->where('box_number', $box_number)->count_all_results(PRODUCT_BOX);
			if($result === 0){
				$data = TRUE;
			}else{
				$this->form_validation->set_message('checkBoxExistence', 'Box number '.$box_number.' is already exist.');
			}
		}else{
			$this->form_validation->set_message('checkBoxExistence', 'The Box number field  is required.');
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
				$this->form_validation->set_message('checkBoxWeight', 'Total Box weight must be less than or equals to total carret weight.');
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
	
	private function __setFormRules($setRulesFor = ''){
		switch($setRulesFor){
			case 'save_box':
				$this->form_validation->set_rules('depot_id', 'Depot', 'trim|required|integer|greater_than[0]');
				$this->form_validation->set_rules('market_id', 'Point', 'trim|required|integer|greater_than[0]');
				$this->form_validation->set_rules('packing_date', 'Date', 'trim|required');
				$this->form_validation->set_rules('package[fish_code][]', 'Fish', 'trim|required');
				$this->form_validation->set_rules('package[fish_qty][]', 'Quantity', 'trim|required|numeric|greater_than_equal_to[0]');
				$this->form_validation->set_rules('package[fish_wt][]', 'Carret Weight', 'trim|required|numeric|greater_than[0]');
				$this->form_validation->set_rules('package[box_wt][]', 'Box Weight', 'trim|required|numeric|greater_than[0]');
				$this->form_validation->set_rules('box_number', 'Box Number', 'trim|required|callback_checkBoxExistence');
				$this->form_validation->set_rules('box_weight', 'Total Box Weight', 'trim|required|numeric|callback_checkBoxWeight');
				$this->form_validation->set_rules('carret_weight', 'Total Carret Weight', 'trim|required|numeric|greater_than[0]');
				$this->form_validation->set_rules('fish_type', 'Fish Type', 'trim|required|in_list[Fresh,Rotten]', array('in_list'=>'Destroyed fish is not allowed.'));
			break;
			
			case 'update_box':
				$this->form_validation->set_rules('package[fish_code][]', 'Fish', 'trim|required');
				$this->form_validation->set_rules('package[fish_qty][]', 'Quantity', 'trim|required|numeric|greater_than_equal_to[0]');
				$this->form_validation->set_rules('package[fish_wt][]', 'Weight', 'trim|required|numeric|greater_than[0]');
				$this->form_validation->set_rules('box_number', 'Box Number', 'trim|required|callback_checkBoxExistence');
				$this->form_validation->set_rules('box_weight', 'Box Weight', 'trim|required|numeric|greater_than[0]|callback_checkBoxWeight');
				$this->form_validation->set_rules('carret_weight', 'Carret Weight', 'trim|required|numeric|greater_than[0]');
				$this->form_validation->set_rules('fish_type', 'Fish Type', 'trim|required|in_list[Fresh,Rotten]', array('in_list'=>'Destroyed fish is not allowed.'));
			break;
			
			case 'save_dispatch_info':
				$this->form_validation->set_rules('action_mode', 'Action Mode', 'trim|required|in_list[add,edit,transfer]');
				$this->form_validation->set_rules('dispatch_date', 'Dispatch Date', 'trim|required');
				$this->form_validation->set_rules('dispatch_from', 'Depot', 'trim|required|numeric|integer|greater_than[0]');
				$this->form_validation->set_rules('dispatch_to', 'Destination', 'trim|required|numeric|integer|greater_than[0]');
				$this->form_validation->set_rules('departure_time', 'Departure Time', 'trim|required');
				$this->form_validation->set_rules('arrival_time', 'Arrival Time', 'trim|required');
				$this->form_validation->set_rules('vehicle_number', 'Vehicle Number', 'trim|required|min_length[2]');
				$this->form_validation->set_rules('driver_name', 'Driver Name', 'trim|required|min_length[2]');
				$this->form_validation->set_rules('driver_mobile_no', 'Driver Mobile No.', 'trim|required|numeric|integer|min_length[10]|max_length[15]');
				$this->form_validation->set_rules('total_freight', 'Total Freight', 'trim|required|numeric');
				$this->form_validation->set_rules('advance_freight', 'Advance Freight', 'trim|required|numeric');
				$this->form_validation->set_rules('remaining_freight', 'Remaining Freight', 'trim|required|numeric');
			break;
			
			case 'add_dr_box':
				//$this->form_validation->set_rules('dispatch_from', 'Dispatch From', 'trim|required|numeric|integer|greater_than[0]');
				$this->form_validation->set_rules('dispatch_id', 'Dispatch Id', 'trim|required|min_length[1]|numeric|integer', array('required'=>'Please save dispatch info'));
				$this->form_validation->set_rules('box_number', 'Box Number', 'trim|required|callback_checkPreparedBoxExistence');
				$this->form_validation->set_rules('dr_number', 'DR Number', 'trim|required|min_length[1]');
			break;
			
			case 'delete_dr_box':
				$this->form_validation->set_rules('dr_number', 'DR Number', 'trim|required|min_length[1]');
				$this->form_validation->set_rules('box_number', 'Box Number', 'trim|required');
			break;
			
			case 'delete_product_box':
				$this->form_validation->set_rules('box_number', 'Box Number', 'trim|required');
			break;
			
			case 'estimate_by_box':
				$this->form_validation->set_rules('item_id', 'Box Item Id', 'trim|required|numeric|integer|greater_than[0]');
				$this->form_validation->set_rules('dr_number', 'DR Number', 'trim|required');
				$this->form_validation->set_rules('box_number', 'Box Number', 'trim|required');
				$this->form_validation->set_rules('estimated_price', 'Estimate Price', 'trim|required|numeric|greater_than[0]');
			break;
			
			case 'estimate_by_fish':
				$this->form_validation->set_rules('dispatch_id', 'Dispatch Id', 'trim|required|numeric|integer|greater_than[0]');
				$this->form_validation->set_rules('estimated_price', 'Estimate Price', 'trim|required|numeric|greater_than[0]');
				$this->form_validation->set_rules('fish_code', 'Fish Code', 'trim|required');
			break;
			
			case 'save_transfer_info':
				$this->form_validation->set_rules('action_mode', 'Action Mode', 'trim|required|in_list[add,edit,transfer]');
				$this->form_validation->set_rules('dispatch_date', 'Dispatch Date', 'trim|required');
				$this->form_validation->set_rules('dispatch_from', 'Depot', 'trim|required|numeric|integer|greater_than[0]');
				$this->form_validation->set_rules('dispatch_to', 'Destination', 'trim|required|numeric|integer|greater_than[0]');
				$this->form_validation->set_rules('departure_time', 'Departure Time', 'trim|required');
				$this->form_validation->set_rules('arrival_time', 'Arrival Time', 'trim|required');
				$this->form_validation->set_rules('vehicle_number', 'Vehicle Number', 'trim|required|min_length[2]');
				$this->form_validation->set_rules('driver_name', 'Driver Name', 'trim|required|min_length[2]');
				$this->form_validation->set_rules('driver_mobile_no', 'Driver Mobile No.', 'trim|required|numeric|integer|min_length[10]|max_length[15]');
				$this->form_validation->set_rules('total_freight', 'Total Freight', 'trim|required|numeric');
				$this->form_validation->set_rules('advance_freight', 'Advance Freight', 'trim|required|numeric');
				$this->form_validation->set_rules('remaining_freight', 'Remaining Freight', 'trim|required|numeric');
				$this->form_validation->set_rules('boxes[]', 'Boxes', 'trim|required', array('required'=>'Please select atleast one box.'));
			break;
			case 'dhalta':
				$this->form_validation->set_rules('dhalta', 'Dhalta', 'trim|required|numeric|less_than[100]');
				$this->form_validation->set_rules('commission', 'Commission', 'trim|required|numeric|less_than[100]');
				$this->form_validation->set_rules('expenses', 'Expenses', 'trim|required|numeric|less_than[100]');
			break;
		}
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert_msg margin-5 padding-5"><button data-dismiss="alert" class="close">×</button><i class="fa fa-times-circle"></i> ', '</div>');
		return $this->form_validation->run($this);
	}
}

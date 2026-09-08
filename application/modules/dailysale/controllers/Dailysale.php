<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dailysale extends MY_Controller {
	// url_encryptor("encrypt", 1);
	// url_encryptor("decrypt", 1);
	
	//$market_type : Local Market=>1, Outside Market=>2
	//market_category: Point=>1, Depot=>2, Outside=>3
		
	var $userID, $userGroup, $actions, $session_year;
	function __construct(){
		parent::__construct();
		$this->userID = checkUserLogin();
		$this->userGroup = loginUserInfo('group_id');
		$this->load->helper('dailysale');
		$this->load->model('dailysale_model', 'DSM');	
		$this->session_year = (defined('SESSION_YEAR') && !empty(constant('SESSION_YEAR'))) ? SESSION_YEAR : date('Y')."-".(date('y')+1);	
	}
	
	public function index(){
		redirect('dashboard');
	}
	
	function cash_deposit(){
		$this->actions = checkUserPermission('dailysale/cash_deposit', $this->uri->segment(3));
		
		$crud = new grocery_CRUD();
		$crud->set_theme('bootstrap');
		$crud->unset_jquery();
		$crud->unset_bootstrap();
		$crud->unset_common_search();					
		$crud->unset_delete();
		
		if(!in_array('add', $this->actions)){
			$crud->unset_add();
		}		
		if(!in_array('read', $this->actions)){
			$crud->unset_read();
		}
		if(!in_array('export', $this->actions)){
			$crud->unset_export();
		}
		if(!in_array('print', $this->actions)){
			$crud->unset_print();
		}
		
		if(!in_array('edit', $this->actions)){
			$crud->unset_edit();
		}else{
			$crud->add_bulk_action('Active', site_url('bulk_action/action/active'), '', 'fa fa-check', 'status');
			$crud->add_bulk_action('Inactive', site_url('bulk_action/action/inactive'), '', 'fa fa-ban', 'status');
		}
		
		if(in_array('delete', $this->actions)){
			//$crud->add_action('Delete', 'triggerBulkDelete text-danger', '', 'fa fa-trash', array($this, '__callbackDeleteActionButton'), 'dialogbox');
			//$crud->add_bulk_action('Delete', site_url('bulk_action/action/mark_delete'), ' text-danger', 'fa fa-trash', 'status');		
		}
		
		
		/*
		if(in_array('lock', $actions)){
			$crud->add_action('Lock Data', 'triggerBulkDelete text-danger','','fa fa-lock', array($this,'__callbackLockActionButton'), 'dialogbox');
			$crud->add_bulk_action('Lock Data', site_url('bulk_action/action/lock'), ' text-danger', 'fa fa-lock', 'editable');		
		}
		*/
		$data = array('page_title'=> 'Cash Deposit', 'content_view'=>'setup/setting');
		
		$crud->set_subject($data['page_title']);
		$crud->set_table(CASH_RECEIVED);
		$crud->set_relation('client_id', CLIENT, '{company_name} {contact_number}');
		$crud->where(array(CASH_RECEIVED.'.status'=>'Active', CASH_RECEIVED.'.editable'=>'Unlock'));
		$crud->order_by('added_date', 'DESC');
		if(in_array('view_all', $this->actions)){
			//For super admin user
			$crud->set_relation('added_by', ADMINISTRATOR, '{first_name} {last_name}');
			$crud->columns('client_id', 'amount', 'remition_by', 'payment_date', 'remark', 'status', 'added_by');
		}else{
			//For all other users
			$crud->where(array(CASH_RECEIVED.'.added_by'=>$this->userID));
			$crud->columns('client_id', 'amount', 'remition_by', 'payment_date', 'remark');
		}
		
		$crud->fields('client_id', 'invoice_number', 'amount', 'remition_by', 'payment_date', 'remark', 'added_by', 'updated_by', 'added_date', 'updated_date', 'action_microtime', 'source');
		$crud->required_fields('client_id', 'amount', 'remition_by', 'payment_date');
		$crud->display_as(array('client_id'=>'Customer'));
		$crud->unique_fields(array('invoice_number'));
		$crud->unset_texteditor('remark');
		
		$crud->field_type('added_by','hidden');
		$crud->field_type('added_date','hidden');
		$crud->field_type('updated_by','hidden');
		$crud->field_type('updated_date','hidden');
		$crud->field_type('action_microtime','hidden');
		$crud->field_type('source','hidden');
		
		$crud->callback_before_insert(array($this, '__callbackBeforeInsert'));
 		$crud->callback_before_update(array($this, '__callbackBeforeUpdate'));
		
		$crud->unset_read_fields('added_by', 'added_date', 'updated_by', 'updated_date', 'action_microtime');
		$output = $crud->render();
		
		$outputData = array_merge((array)$output, $data);
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($outputData);
	}
	
	function __callbackBeforeInsert($post_array){
		$post_array['added_by'] = $this->userID;
		$post_array['added_date'] = get_datetime('Y-m-d H:i:s');
		$post_array['action_microtime'] = microtime(true);
		$post_array['source'] = SOURCE;
		return $post_array;
	}
	
	function __callbackBeforeUpdate($post_array){
		$post_array['updated_by'] = $this->userID;
		$post_array['updated_date'] = get_datetime('Y-m-d H:i:s');
		$post_array['action_microtime'] = microtime(true);
		return $post_array;
	}
	
//==============================================================================
//Point SALE FUNCTIONS
	//Point sale listing
	public function point_sale(){
		$this->actions = checkUserPermission('dailysale/point_sale', $this->uri->segment(3));
		if(!empty($this->uri->segment(3)) && ($this->uri->segment(3) == 'add' || $this->uri->segment(3) == 'edit' || $this->uri->segment(3) == 'view')){redirect(site_url('dailysale/point_sale'));}
		$crud = new grocery_CRUD();
		$crud->set_theme('bootstrap');
		$crud->unset_jquery();
		$crud->unset_bootstrap();
		$crud->unset_common_search();
		//$crud->unset_add();		
		$crud->unset_delete();		
		
		if(!in_array('add', $this->actions)){
			$crud->unset_add();
		}else{
			$crud->set_add_url_path(site_url('dailysale/point/add'));
		}
		
		if(!in_array('edit', $this->actions)){
			$crud->unset_edit();
		}else{
			//$crud->set_edit_button_text('Sale');
			$crud->set_edit_url_path(site_url('dailysale/point/edit'));
		}
		
		if(in_array('view', $this->actions)){
			$crud->set_read_url_path(site_url('dailysale/point/view'));
		}else{
			$crud->unset_read();
		}
		
		/*if(in_array('delete', $this->actions)){			
			$crud->add_action('Delete', 'triggerBulkDelete text-danger', site_url('bulk_action/action/mark_delete'), 'fa fa-trash', array($this, '__callbackDeleteActionButton'), 'dialogbox');			
			$crud->add_bulk_action('Delete', site_url('bulk_action/action/mark_delete'), ' text-danger', 'fa fa-trash', 'status');		
		}*/
		
		if(in_array('cancel', $this->actions)){			
			$crud->add_action('Cancel', 'triggerBulkDelete text-danger', '', 'fa fa-times', array($this, '__callbackCancelActionButton'), 'dialogbox');			
			$crud->add_bulk_action('Cancel', site_url('bulk_action/action/mark_cancel'), ' text-danger', 'fa fa-trash', 'status');		
		}
		if(in_array('active', $this->actions)){
			$crud->add_action('Active', 'triggerBulkDelete', '', 'fa fa-check', array($this, '__callbackActiveActionButton'), 'dialogbox');
			$crud->add_bulk_action('Active', site_url('bulk_action/action/mark_active'), ' ', 'fa fa-check', 'status');
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
					if($value == 'sale_date'){
						$_POST['search_text'][$key] = get_date('Y-m-d', str_replace('/', '-', $postData['search_text'][$key]));
					}
				}
			}
		}
		
		$data = array('page_title'=> 'Point Sale', 'content_view'=>'setup/setting');
		
		$crud->set_subject($data['page_title']);
		$crud->set_table(SALE);
		//$crud->set_relation('client_id', CLIENT, 'company_name');
		$crud->set_relation('market_id', MARKET_PLACE, 'name');
		if(in_array('view_all', $this->actions)){
			//For super admin user
			$crud->set_relation('added_by', ADMINISTRATOR, '{first_name} {last_name}');
			$crud->columns('sale_date', 'invoice_number', 'market_id', 'client_name', 'total_wt', 'grand_total', 'received_amt', 'status', 'sms_sent', 'added_by');
		}else{
			//For all other users
			$crud->where(array(SALE.'.added_by'=>$this->userID));
			$crud->columns('sale_date', 'invoice_number', 'market_id', 'client_name', 'total_wt', 'grand_total', 'received_amt', 'status', 'sms_sent');
		}	

		$crud->display_summary('total_wt', 'grand_total', 'received_amt');		
		$crud->where(array(SALE.'.market_type'=>1, SALE.'.market_category'=>1));
		$crud->where(array(SALE.'.editable !='=>'Lock', SALE.'.status !='=>'Deleted', SALE.'.editable !='=>'Lock'));
		$crud->display_as(array('client_name'=>'Client', 'market_id'=>'Market'));
		$crud->callback_column('sale_date', array($this, '__callaback_display_datetime'));
		$output = $crud->render();
		
		$outputData = array_merge((array)$output, $data);	
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($outputData);	
	}
	
	//Add, Edit, view point sale
	public function point($action_mode = 'add', $id = NULL){
		$this->actions = checkUserPermission('dailysale/point_sale', $this->uri->segment(3));
		
		$data['go_back_url'] 		= site_url('dailysale/point_sale');
		$data['all_points'] 		= $this->DSM->get_market_places(1);
		$data['all_fishes'] 		= $this->DSM->get_all_fishes();
		$data['page_title'] 		= 'BILL/INVOICE MEMO '.$this->session_year;
		$data['content_view'] 		= 'dailysale/point_sale/point_sale_v';
		$data['disabled_input'] 	= '';
		$data['datepicker_class'] 	= 'datetimepicker';
		$data['action_mode'] 		= (!empty($action_mode) ? $action_mode : 'add');
		$data['sale_id'] 			= '';
		$data['extra_class'] 		= '';
		$data['sale_data'] = array('sale_id' 		=> '',
								   'mp_code' 		=> '',
								   'market_id' 		=> '',
								   'sale_date' 		=> '',
								   'invoice_number' => '',
								   'client_id' 		=> '',
								   'company_name' 	=> '',
								   'contact_number' => '',
								   'email' 			=> ''
								   );
		
		if(($action_mode == 'view' || $action_mode == 'edit') && $id != NULL){
			$data['action_mode'] 		= $action_mode;
			$data['sale_id'] 			= $id;
			$data['disabled_input'] 	= 'readonly="readonly"';
			$data['datepicker_class'] 	= '';
			$data['sale_data'] 			= $this->DSM->get_sale_data($id, $action_mode);
			if(!isset($data['sale_data']['sale_id']) || empty($data['sale_data']['sale_id'])){
				$this->messageci->set('Invalid request, Please try again.', 'error') ;
				redirect(site_url('dailysale/point_sale'));
			}
			$data['cash_received']	   	= $this->DSM->get_cash_received($data['sale_data']['invoice_number']); 
			$data['sale_item'] 			= $this->DSM->get_saleitem_data($id);
			$data['old_remaining_amt'] 	= $this->DSM->get_customer_old_remaing($data['sale_data']['client_id'], $data['sale_data']['sale_date'], $data['sale_data']['sale_id']);
		}
		if($action_mode == 'view'){$data['extra_class'] = 'disabled';}
		
		
		$this->template->set('stylesheet', array(base_url('assets/plugins/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css'),
												 base_url('assets/plugins/select2/dist/css/select2.min.css'),
												 site_url('dailysale/assets/css/dailysale_common.css')
							));
		$this->template->set('scriptsrc', array(base_url('assets/plugins/moment/min/moment-with-locales.min.js'),
												base_url('assets/plugins/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'),
												base_url('assets/plugins/select2/dist/js/select2.full.min.js'),
												base_url('assets/plugins/bootstrap-typeahead/bootstrap3-typeahead.min.js'),
												base_url('assets/modules/reports/jQuery.print.min.js'),
												base_url('assets/modules/reports/jquery.table2excel.min.js'), // work with ID
												site_url('dailysale/assets/js/dailysale_common.js'),
												site_url('dailysale/assets/js/point_sale.js')
							 ));
		$this->template->set('document_title', 'Point Sale');
		$this->template->layout($data);
	}
	
	public function ajax_point_sale_data(){
		$data = array('status' => 'failed', 'message' => 'No data posted.', 'data' => '');
		$formValidation = $this->__setFormRules('point_sale_data');
		if($formValidation){
			$post_data 			= $this->input->post();
			$action_mode 		= $this->input->post('action_mode');
			$sale_id 			= $this->input->post('sale_id');
			$market_id			= $this->input->post('market_id');
			$mp_code			= $this->input->post('mp_code');
			$sale_date 			= get_datetime('Y-m-d H:i:s', str_replace('/', '-', $this->input->post('sale_date')));
			$customer_type 		= $this->input->post('customer_type');
			$customer_code 		= $this->input->post('customer_code');
			$customer_name 		= $this->input->post('customer_name');
			$customer_mobile 	= $this->input->post('customer_mobile');
			$customer_email 	= $this->input->post('customer_email');
			$remark				= $this->input->post('remark');
			$customer_id		= $this->DSM->get_customer_id();
			
			//INSERT or UPDATE only if sale_date and customer_id is not already available
			$where 		= array('sale_date'=>$sale_date, 'client_id'=>$customer_id, 'status'=>'Active');
			$result 	= $this->db->select('sale_id')->where($where)->get(SALE)->result_array();
			if(!empty($result) && $result[0]['sale_id'] != $sale_id){
				//if record already exists
				$data['status'] = 'failed';
				$data['message'] = '<div class="alert alert-danger" style="padding: 5px !important;"> 
										<button class="close" data-dismiss="alert">×</button> 
										Error: There is already added sale data on selected date and time for this customer
										Click <a href="'.site_url('dailysale/point/view/'.$result[0]['sale_id']).'" class="btn btn-primary btn-xs">here</a> to view.
									</div>';
			}else{
				$data = $this->DSM->save_sale_data(1, 1, $customer_id);
			}
		}else{
			$data['status'] = 'failed';
			$data['message'] = validation_errors();
		}
		
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	public function ajax_save_point_item(){
		$post_data = $this->input->post();
		$data = array('status' => 'error', 'message' => 'No data posted.', 'data' => '');
		$valid_data = $this->__setFormRules('point_sale_item');
		if($valid_data){
			$post_data = $this->input->post();
			$total_amount = round($post_data['fish_weight'] * $post_data['fish_rate'], 2);
			
			$fish_code_rate = isset($post_data['fish_code']) ? explode('___', $post_data['fish_code']) : $post_data['fish_code'];
			$post_data['fish_code'] = is_array($fish_code_rate) ? $fish_code_rate[0] : $fish_code_rate;
			
			$saleitem_data = array(	'sale_id' 			=> $post_data['sale_id'],
									'client_id' 		=> $post_data['client_id'],
									'sale_date' 		=> get_datetime('Y-m-d H:i:s', str_replace('/', '-', $post_data['sale_date'])),
									'market_type' 		=> 1,
									'market_category' 	=> 1,
									'market_id' 		=> $post_data['market_id'],
									'fish_code' 		=> $post_data['fish_code'],									
									'fish_quantity' 	=> $post_data['fish_quantity'],
									'fish_weight' 		=> $post_data['fish_weight'],
									'fish_rate' 		=> $post_data['fish_rate'],
									'total_amount' 		=> $total_amount,
									'remark' 			=> $post_data['remark'],
									'added_by' 			=> $this->userID,
									'added_date'		=> get_datetime('Y-m-d H:i:s'),
									'action_microtime'	=> microtime(true),
									'status'			=> 'Active',
									'source'			=> SOURCE
								   );
			$this->db->insert(SALE_ITEMS, $saleitem_data);
			$saleitem_id = $this->db->insert_id();
			if($saleitem_id){
				$html_data = '';
				$html_data .= '<tr>';
                $html_data .= ' <td>'.($post_data['s_no_count']+1).'</td>';
                $html_data .= ' <td>'.$post_data['fish_name'].'</td>';
                $html_data .= ' <td><input type="text" readonly="readonly" value="'.number_format($post_data['fish_quantity'],'2','.','').'" class="form-control disabled input-sm si_qty" /></td>';
                $html_data .= ' <td><input type="text" readonly="readonly" value="'.number_format($post_data['fish_weight'],'2','.','').'" class="form-control disabled input-sm si_wt" /></td>';
                $html_data .= ' <td><input type="text" readonly="readonly" value="'.number_format($post_data['fish_rate'],'2','.','').'" class="form-control disabled input-sm si_rt" /></td>';
                $html_data .= ' <td><input type="text" readonly="readonly" value="'.number_format($total_amount,'2','.','').'" class="form-control disabled input-sm si_amt" /></td>';
				$html_data .= ' <td>'.$post_data['remark'].'</td>';
                $html_data .= ' <td><a href="javascript:void(0);" data-sale_id="'.$post_data['sale_id'].'" data-sale_item_id="'.$saleitem_id.'" class="btn btn-danger btn-sm delete_sale_item"><i class="fa fa-trash"></i> </a></td>';
                $html_data .= '</tr>';
				
				$data = array('status' => 'success', 'message' => 'Item added successfully', 'data' => $html_data);
			}else{
				$data = array('status' => 'error', 'message' => 'There is a problem in adding data, Please reload the page and try again.', 'data' => '');
			}
		}else{
			$data['status'] = 'error';
			$data['message'] = validation_errors();
		}
		
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	public function ajax_delete_point_item(){
		$data = array('status' => 'error', 'message' => 'No data posted.', 'data' => '');
		$valid_data = $this->__setFormRules('delete_sale_item');
		if($valid_data){
			$post_data = $this->input->post();
			$sale_id 		= $post_data['sale_id'];
			$sale_item_id 	= $post_data['sale_item_id'];
			
			$result = $this->db->delete(SALE_ITEMS, array('id'=>$sale_item_id, 'sale_id'=>$sale_id, 'added_by'=>$this->userID));
			if($result){
				$redirect_url = site_url('dailysale/point/edit/'.$sale_id);
				$data = array('status' => 'success', 'message' => 'Item deleted successfully', 'data' => $redirect_url);
			}else{
				$data = array('status' => 'error', 'message' => 'There is a problem in deleting data, Please reload the page and try again.', 'data' => '');
			}
		}else{
			$data['status'] = 'error';
			$data['message'] = validation_errors();
		}
		$this->__return_json_output($data);
	}
	
	public function ajax_save_point_summary(){
		$data = array('status' => 'error', 'message' => 'There is a problem in deleting data, Please reload the page and try again.', 'data' => '');

		$valid_data = $this->__setFormRules('save_point_summary');
		if($valid_data){
			$post_data 		= $this->input->post();
			$sale_id 		= $this->input->post('sale_id');
			$sale_history 	= $this->DSM->get_sale_data($sale_id);
			$send_sms		= $this->input->post('send_sms');
			$action_mode 	= $this->input->post('action_mode');
			if(!empty($sale_id) && $action_mode == 'edit'){
				$grand_total 		= $this->input->post('grand_total');
				$received_amt 		= $this->input->post('received_amt');
				$payment_mode 		= '';
				
				//received_amt is always either zero or greater than zero
				if($received_amt == 0){ //if received_amt is zero
					$payment_mode 	= 'Credit';
				}else if($received_amt > 0){ //if received_amt greater than zero
					if($received_amt < $grand_total){
						$payment_mode 		= 'Partial';
					}else if($received_amt >= $grand_total){
						$payment_mode 		= 'Cash';
					}
				}
				
				$sale_data = array(	'total_qty' 		=> $this->input->post('total_qty'),
									'total_wt' 			=> $this->input->post('total_wt'),
									'sub_total' 		=> $this->input->post('sub_total'),
									'ice_weight' 		=> $this->input->post('ice_weight'),
									'ice_rate' 			=> $this->input->post('ice_rate'),
									'ice_amount' 		=> $this->input->post('ice_amount'),
									'destroyed_weight' 	=> $this->input->post('destroyed_weight'),
									'destroyed_rate' 	=> $this->input->post('destroyed_rate'),
									'destroyed_amount' 	=> $this->input->post('destroyed_amount'),
									'discount_perc' 	=> $this->input->post('discount_perc'),
									'discount_amount' 	=> $this->input->post('discount_amount'),									
									'grand_total' 		=> $grand_total,
									'received_amt' 		=> $received_amt,									
									'payment_mode' 		=> $payment_mode,									
									'updated_by'		=> $this->userID,
									'updated_date'		=> get_datetime('Y-m-d H:i:s'),
									'action_microtime'	=> microtime(true)
								   );
						
				$result = $this->db->where(array('sale_id'=>$sale_id))->update(SALE, $sale_data);
								
				if($result){
					if($send_sms == "Yes" && !empty($sale_history['client_mobile'])){
						$invoice_number = $sale_history['invoice_number'];
						$party_name = $sale_history['client_name'];
						$tWt 		= $this->input->post('total_wt');
						$tAmt 		= $grand_total;
						$received 	= $received_amt;
						$balance 	= $this->input->post('remaining_amt');
						$prepare_sms_data = $party_name.', Total Wt: '.$tWt.', Total Amt: '.$tAmt.', Received Amt: '.$received.', Balance: '.$balance;
						$response = sendSMS($sale_history['client_mobile'], $prepare_sms_data);
						if($response['status'] == 'success'){
							$this->db->where(array('sale_id'=>$sale_id))->update(SALE, array('sms_sent'=>'Yes')); //ignore this udpate operation
						}
					}
					$redirect_url = site_url('dailysale/point_sale/');
					$data = array('status' => 'success', 'message' => 'Data saved successfully', 'data' => $redirect_url);
				}else{
					$data = array('status' => 'error', 'message' => 'There is some problem, Please reload the page and try again.', 'data' => '');
				}
			}
		}else{
			$data['status'] = 'error';
			$data['message'] = validation_errors();
		}
		$this->__return_json_output($data);
	}
	
	public function ajax_point_edit_mode($sale_id){
		$data['go_back_url'] = site_url('dailysale/point_sale');
		$data['action_mode'] = 'edit';
		$data['page_title'] = 'BILL/INVOICE MEMO '.$this->session_year;
		$data['sale_id'] = $sale_id;
		$data['all_points'] = $this->DSM->get_market_places(1);
		$data['sale_data'] = $this->DSM->get_sale_data($sale_id, $this->userID);
		$data['stylesheet'] = array(base_url('assets/plugins/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css'),
									base_url('assets/plugins/select2/dist/css/select2.min.css')
								    );
		$data['scriptsrc'] = array( base_url('assets/plugins/moment/min/moment-with-locales.min.js'),
									base_url('assets/plugins/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'),
									base_url('assets/plugins/select2/dist/js/select2.full.min.js')
							 	   );
		$setup_form = $this->load->view('dailysale/point_sale/ajax_psale_edit_mode_v', $data, true);
		$data = array('status'=>'success','data'=>$setup_form);
		$this->__return_json_output($data);
	}
	
//==============================================================================
//Depot SALE FUNCTIONS
	
	//Depot sale listing
	public function depot_sale(){
		$this->actions = checkUserPermission('dailysale/depot_sale', $this->uri->segment(3));
		if(!empty($this->uri->segment(3)) && ($this->uri->segment(3) == 'add' || $this->uri->segment(3) == 'edit' || $this->uri->segment(3) == 'view')){redirect(site_url('dailysale/depot_sale'));}
		$crud = new grocery_CRUD();
		$crud->set_theme('bootstrap');
		$crud->unset_jquery();
		$crud->unset_bootstrap();
		$crud->unset_common_search();
		//$crud->unset_add();		
		$crud->unset_delete();		
		
		if(!in_array('add', $this->actions)){
			$crud->unset_add();
		}else{
			$crud->set_add_url_path(site_url('dailysale/depot/add'));
		}
		
		if(!in_array('edit', $this->actions)){
			$crud->unset_edit();
		}else{
			//$crud->set_edit_button_text('Sale');
			$crud->set_edit_url_path(site_url('dailysale/depot/edit'));
		}
		
		if(in_array('view', $this->actions)){
			$crud->set_read_url_path(site_url('dailysale/depot/view'));
		}else{
			$crud->unset_read();
		}
		
		if(in_array('cancel', $this->actions)){			
			$crud->add_action('Cancel', 'triggerBulkDelete text-danger', '', 'fa fa-times', array($this, '__callbackCancelActionButton'), 'dialogbox');			
			$crud->add_bulk_action('Cancel', site_url('bulk_action/action/mark_cancel'), ' text-danger', 'fa fa-trash', 'status');		
		}
		if(in_array('active', $this->actions)){
			$crud->add_action('Active', 'triggerBulkDelete', '', 'fa fa-check', array($this, '__callbackActiveActionButton'), 'dialogbox');
			$crud->add_bulk_action('Active', site_url('bulk_action/action/mark_active'), ' ', 'fa fa-check', 'status');
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
					if($value == 'sale_date'){
						$_POST['search_text'][$key] = get_date('Y-m-d', str_replace('/', '-', $postData['search_text'][$key]));
					}
				}
			}
		}
		
		$data = array('page_title'=> 'Depot Sale', 'content_view'=>'setup/setting');
		
		$crud->set_subject($data['page_title']);
		$crud->set_table(SALE);
		//$crud->set_relation('client_id', CLIENT, 'company_name');
		$crud->set_relation('market_id', MARKET_PLACE, 'name');
		if(in_array('view_all', $this->actions)){
			//For super admin user
			$crud->set_relation('added_by', ADMINISTRATOR, '{first_name} {last_name}');
			$crud->columns('sale_date', 'invoice_number', 'market_id', 'client_name', 'total_wt', 'grand_total', 'received_amt', 'status', 'sms_sent', 'added_by');
		}else{
			//For all other users
			$crud->where(array(SALE.'.added_by'=>$this->userID));
			$crud->columns('sale_date', 'invoice_number', 'market_id', 'client_name', 'total_wt', 'grand_total', 'received_amt', 'status', 'sms_sent');
		}
		$crud->order_by('sale_date', 'DESC');
		$crud->display_summary('total_wt', 'grand_total', 'received_amt');
		$crud->where(array(SALE.'.market_type'=>1, SALE.'.market_category'=>2));
		$crud->where(array(SALE.'.editable !='=>'Lock', SALE.'.status !='=>'Deleted', SALE.'.editable !='=>'Lock'));
		$crud->display_as(array('client_name'=>'Client', 'market_id'=>'Market'));
		$crud->callback_column('sale_date', array($this, '__callaback_display_datetime'));
		$output = $crud->render();
		
		$outputData = array_merge((array)$output, $data);	
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($outputData);	
	}
	
	//Add, Edit, view depot sale
	public function depot($action_mode = 'add', $id = NULL){
		$this->actions = checkUserPermission('dailysale/depot_sale', $this->uri->segment(3));
		$data['go_back_url'] 		= site_url('dailysale/depot_sale');
		$data['market_type'] 		= $this->DSM->get_market_type();
		$data['all_depots'] 		= $this->DSM->get_market_places(2);
		$data['all_fishes'] 		= $this->DSM->get_all_fishes();
		$data['page_title'] 		= 'BILL/INVOICE MEMO '.$this->session_year;
		$data['content_view'] 		= 'dailysale/depot_sale/depot_sale_v';
		$data['disabled_input'] 	= '';
		$data['datepicker_class'] 	= 'datetimepicker';
		$data['action_mode'] 		= (!empty($action_mode) ? $action_mode : 'add');
		$data['sale_id'] 			= '';
		$data['extra_class'] 		= '';
		$data['sale_data'] = array('sale_id' 		=> '',
								   'mp_code' 		=> '',
								   'market_id' 		=> '',
								   'sale_date' 		=> '',
								   'invoice_number' => '',
								   'client_id' 		=> '',
								   'company_name' 	=> '',
								   'contact_number' => '',
								   'email' 			=> ''
								   );
		
		if(($action_mode == 'view' || $action_mode == 'edit') && $id != NULL){
			$data['action_mode'] 		= $action_mode;
			$data['sale_id'] 			= $id;
			$data['disabled_input'] 	= 'readonly="readonly"';
			$data['datepicker_class'] 	= '';
			$data['sale_data'] 			= $this->DSM->get_sale_data($id, $action_mode);
			if(!isset($data['sale_data']['sale_id']) || empty($data['sale_data']['sale_id'])){
				$this->messageci->set('Invalid request, Please try again.', 'error') ;
				redirect(site_url('dailysale/depot_sale'));
			}
			$data['cash_received']	   = $this->DSM->get_cash_received($data['sale_data']['invoice_number']); 
			$data['sale_item'] 		   = $this->DSM->get_saleitem_data($id);
			$data['old_remaining_amt'] = $this->DSM->get_customer_old_remaing($data['sale_data']['client_id'], $data['sale_data']['sale_date'], $data['sale_data']['sale_id']);
			//printr($data['cash_received']);
		}
		if($action_mode == 'view'){$data['extra_class'] = 'disabled';}
		
		$this->template->set('stylesheet', array(base_url('assets/plugins/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css'),
												 base_url('assets/plugins/select2/dist/css/select2.min.css'),
												 site_url('dailysale/assets/css/dailysale_common.css')
							));
		$this->template->set('scriptsrc', array(base_url('assets/plugins/moment/min/moment-with-locales.min.js'),
												base_url('assets/plugins/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'),
												base_url('assets/plugins/select2/dist/js/select2.full.min.js'),
												base_url('assets/plugins/bootstrap-typeahead/bootstrap3-typeahead.min.js'),
												base_url('assets/modules/reports/jQuery.print.min.js'),
												base_url('assets/modules/reports/jquery.table2excel.min.js'), // work with ID
												site_url('dailysale/assets/js/dailysale_common.js'),
												site_url('dailysale/assets/js/depot_sale.js')
							 ));
		$this->template->set('document_title', 'Depot');
		$this->template->layout($data);
	}
	
	public function ajax_depot_sale_data(){
		$data = array('status' => 'failed', 'message' => 'No data posted.', 'data' => '');
		$formValidation = $this->__setFormRules('depot_sale_data');
		if($formValidation){
			$post_data 			= $this->input->post();
			$action_mode 		= $this->input->post('action_mode');
			$sale_id 			= $this->input->post('sale_id');
			$market_id			= $this->input->post('market_id');
			$mp_code			= $this->input->post('mp_code');
			$sale_date 			= get_datetime('Y-m-d H:i:s', str_replace('/', '-', $this->input->post('sale_date')));
			$customer_type 		= $this->input->post('customer_type');
			$customer_code 		= $this->input->post('customer_code');
			$customer_name 		= $this->input->post('customer_name');
			$customer_mobile 	= $this->input->post('customer_mobile');
			$customer_email 	= $this->input->post('customer_email');
			$remark				= $this->input->post('remark');
			$customer_id		= $this->DSM->get_customer_id();
			
			//INSERT or UPDATE only if sale_date and customer_id is not already available
			$where 		= array('sale_date'=>$sale_date, 'client_id'=>$customer_id, 'status'=>'Active');
			$result 	= $this->db->select('sale_id')->where($where)->get(SALE)->result_array();
			if(!empty($result) && $result[0]['sale_id'] != $sale_id){
				//if record already exists
				$data['status'] = 'failed';
				$data['message'] = '<div class="alert alert-danger" style="padding: 5px !important;"> 
										<button class="close" data-dismiss="alert">×</button> 
										Error: There is already added sale data on selected date and time for this customer
										Click <a href="'.site_url('dailysale/point/view/'.$result[0]['sale_id']).'" class="btn btn-primary btn-xs">here</a> to view.
									</div>';
			}else{
				$data = $this->DSM->save_sale_data(1, 2, $customer_id);
			}
		}else{
			$data['status'] = 'failed';
			$data['message'] = validation_errors();
		}
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	public function ajax_save_depot_item(){
		$post_data = $this->input->post();
		$data = array('status' => 'error', 'message' => 'No data posted.', 'data' => '');
		$valid_data = $this->__setFormRules('depot_sale_item');
		if($valid_data){
			$post_data = $this->input->post();
			$total_amount = round($post_data['fish_weight'] * $post_data['fish_rate'], 2);
			
			$fish_code_rate = isset($post_data['fish_code']) ? explode('___', $post_data['fish_code']) : $post_data['fish_code'];
			$post_data['fish_code'] = is_array($fish_code_rate) ? $fish_code_rate[0] : $fish_code_rate;
			
			$saleitem_data = array(	'sale_id' 			=> $post_data['sale_id'],
									'client_id' 		=> $post_data['client_id'],
									'sale_date' 		=> get_datetime('Y-m-d H:i:s', str_replace('/', '-', $post_data['sale_date'])),
									'market_type' 		=> 1,
									'market_category' 	=> 2,
									'market_id' 		=> $post_data['market_id'],
									'fish_code' 		=> $post_data['fish_code'],									
									'fish_quantity' 	=> $post_data['fish_quantity'],
									'fish_weight' 		=> $post_data['fish_weight'],
									'fish_rate' 		=> $post_data['fish_rate'],
									'total_amount' 		=> $total_amount,
									'remark' 			=> $post_data['remark'],
									'stock_type'		=> $post_data['stock_type'],
									'added_by' 			=> $this->userID,
									'added_date'		=> get_datetime('Y-m-d H:i:s'),
									'action_microtime'	=> microtime(true),
									'status'			=> 'Active',
									'source'			=> SOURCE
								   );
			$this->db->insert(SALE_ITEMS, $saleitem_data);
			$saleitem_id = $this->db->insert_id();
			if($saleitem_id){
				$html_data = '';
				$html_data .= '<tr>';
                $html_data .= ' <td>'.($post_data['s_no_count']+1).'</td>';
                $html_data .= ' <td>0</td>';
				$html_data .= ' <td>'.$post_data['fish_name'].'</td>';
                $html_data .= ' <td><input type="text" readonly="readonly" value="'.number_format($post_data['fish_quantity'],'2','.','').'" class="form-control disabled input-sm si_qty" /></td>';
                $html_data .= ' <td><input type="text" readonly="readonly" value="'.number_format($post_data['fish_weight'],'2','.','').'" class="form-control disabled input-sm si_wt" /></td>';
                $html_data .= ' <td><input type="text" readonly="readonly" value="'.number_format($post_data['fish_rate'],'2','.','').'" class="form-control disabled input-sm si_rt" /></td>';
                $html_data .= ' <td><input type="text" readonly="readonly" value="'.number_format($total_amount,'2','.','').'" class="form-control disabled input-sm si_amt" /></td>';
				$html_data .= ' <td>'.$post_data['remark'].'</td>';
				$html_data .= ' <td>'.$post_data['stock_type'].'</td>';
                $html_data .= ' <td>
								 <a href="javascript:void(0);" data-sale_id="'.$post_data['sale_id'].'" data-sale_item_id="'.$saleitem_id.'" data-carret_number="" class="btn btn-primary btn-xs edit_sale_item"><i class="fa fa-pencil"></i> </a>
								 <a href="javascript:void(0);" data-sale_id="'.$post_data['sale_id'].'" data-sale_item_id="'.$saleitem_id.'" data-carret_number="" class="btn btn-danger btn-xs delete_sale_item"><i class="fa fa-trash"></i> </a>
								</td>';
                $html_data .= '</tr>';
				
				$data = array('status' => 'success', 'message' => 'Item added successfully', 'data' => $html_data);
			}else{
				$data = array('status' => 'error', 'message' => 'There is a problem in adding data, Please reload the page and try again.', 'data' => '');
			}
		}else{
			$data['status'] = 'error';
			$data['message'] = validation_errors();
		}
		
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	public function ajax_delete_depot_item(){
		$data = array('status' => 'error', 'message' => 'No data posted.', 'data' => '');
		$valid_data = $this->__setFormRules('delete_sale_item');
		if($valid_data){
			$post_data = $this->input->post();
			$sale_id 		= $post_data['sale_id'];
			$sale_item_id 	= $post_data['sale_item_id'];
			$carret_number 	= $post_data['carret_number'];
			
			if($carret_number > 0){
				$this->db->where(array('carret_number'=>$carret_number))->update(PRODUCTION_CARRET, array('client_id'=>0, 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true)));
				$this->db->where(array('carret_number'=>$carret_number))->update(PRODUCTION_CARRET_ITEMS, array('client_id'=>0, 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true)));
			}
			
			$result = $this->db->delete(SALE_ITEMS, array('id'=>$sale_item_id, 'sale_id'=>$sale_id, 'added_by'=>$this->userID));
			if($result){
				$redirect_url = site_url('dailysale/depot/edit/'.$sale_id);
				$data = array('status' => 'success', 'message' => 'Item deleted successfully', 'data' => $redirect_url);
			}else{
				$data = array('status' => 'error', 'message' => 'There is a problem in deleting data, Please reload the page and try again.', 'data' => '');
			}
		}else{
			$data['status'] = 'error';
			$data['message'] = validation_errors();
		}
		$this->__return_json_output($data);
	}
	
	public function ajax_save_depot_summary(){
		$data = array('status' => 'error', 'message' => 'There is a problem in deleting data, Please reload the page and try again.', 'data' => '');
		$valid_data = $this->__setFormRules('save_depot_summary');
		if($valid_data){
			$post_data = $this->input->post();			
			$sale_id = $this->input->post('sale_id');
			$sale_history 	= $this->DSM->get_sale_data($sale_id);
			$send_sms		= $this->input->post('send_sms');
			$action_mode = $this->input->post('action_mode');
			if(!empty($sale_id) && $action_mode == 'edit'){
				$grand_total 		= $this->input->post('grand_total');
				$received_amt 		= $this->input->post('received_amt');
				$payment_mode 		= '';
				
				//received_amt is always either zero or greater than zero
				if($received_amt == 0){ //if received_amt is zero
					$payment_mode 	= 'Credit';
				}else if($received_amt > 0){ //if received_amt greater than zero
					if($received_amt < $grand_total){
						$payment_mode 		= 'Partial';
					}else if($received_amt >= $grand_total){
						$payment_mode 		= 'Cash';
					}
				}
				
				$sale_data = array(	'total_qty' 		=> $this->input->post('total_qty'),
									'total_wt' 			=> $this->input->post('total_wt'),
									'sub_total' 		=> $this->input->post('sub_total'),
									'ice_weight' 		=> $this->input->post('ice_weight'),
									'ice_rate' 			=> $this->input->post('ice_rate'),
									'ice_amount' 		=> $this->input->post('ice_amount'),
									'destroyed_weight' 	=> $this->input->post('destroyed_weight'),
									'destroyed_rate' 	=> $this->input->post('destroyed_rate'),
									'destroyed_amount' 	=> $this->input->post('destroyed_amount'),
									'discount_perc' 	=> $this->input->post('discount_perc'),
									'discount_amount' 	=> $this->input->post('discount_amount'),
									'grand_total' 		=> $grand_total,
									'received_amt' 		=> $received_amt,
									'payment_mode' 		=> $payment_mode,
									'updated_by'		=> $this->userID, 
									'updated_date'		=> get_datetime('Y-m-d H:i:s'),
									'action_microtime'	=> microtime(true)
								   );
						
				$result = $this->db->where(array('sale_id'=>$sale_id))->update(SALE, $sale_data);
				if($result){
					if($send_sms == "Yes" && !empty($sale_history['client_mobile'])){
						$invoice_number = $sale_history['invoice_number'];
						$party_name = $sale_history['client_name'];
						$tWt 		= $this->input->post('total_wt');
						$tAmt 		= $grand_total;
						$received 	= $received_amt;
						$balance 	= $this->input->post('remaining_amt');
						$prepare_sms_data = $party_name.', Total Wt: '.$tWt.', Total Amt: '.$tAmt.', Received Amt: '.$received.', Balance: '.$balance;
						
						$response = sendSMS($sale_history['client_mobile'], $prepare_sms_data);
						if($response['status'] == 'success'){
							$this->db->where(array('sale_id'=>$sale_id))->update(SALE, array('sms_sent'=>'Yes'));
						}
					}
					$redirect_url = site_url('dailysale/depot_sale/');
					$data = array('status' => 'success', 'message' => 'Data saved successfully', 'data' => $redirect_url);
				}else{
					$data = array('status' => 'error', 'message' => 'There is some problem, Please reload the page and try again.', 'data' => '');
				}
			}
		}else{
			$data['status'] = 'error';
			$data['message'] = validation_errors();
		}
		$this->__return_json_output($data);
	}
	
	public function ajax_depot_edit_mode($sale_id){
		$data['go_back_url'] = site_url('dailysale/depot_sale');
		$data['action_mode'] = 'edit';
		$data['page_title'] = 'BILL/INVOICE MEMO';
		$data['sale_id'] 	= $sale_id;
		$data['all_depots'] = $this->DSM->get_market_places(2);
		$data['sale_data'] 	= $this->DSM->get_sale_data($sale_id, $this->userID);
		$data['stylesheet'] = array(base_url('assets/plugins/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css'),
									base_url('assets/plugins/select2/dist/css/select2.min.css')
								    );
		$data['scriptsrc'] = array( base_url('assets/plugins/moment/min/moment-with-locales.min.js'),
									base_url('assets/plugins/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'),
									base_url('assets/plugins/select2/dist/js/select2.full.min.js')
							 	   );
		$setup_form = $this->load->view('dailysale/depot_sale/ajax_dsale_edit_mode_v', $data, true);
		$data = array('status'=>'success','data'=>$setup_form);
		$this->__return_json_output($data);
	}
	
	public function update_rate($sale_id){
		$response_data = array('status' => 'success', 'page_title' => 'Update Rate', 'setup_form'=>'');
		$post = $this->input->post();
		if(!empty($post)){
			$response_data = array('status'=>'danger', 'message'=>'Failed to update');
			$new_rates = $post['new_rate'];
			$result = '';
			foreach($new_rates as $fish_code=>$fish_rate){
				if($fish_rate > 0){
					$result = $this->db->where(array('sale_id'=>$sale_id, 'fish_code'=>$fish_code))->update(SALE_ITEMS, array('fish_rate'=>$fish_rate, 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true)));
				}
			}
			if($result){
				$this->DSM->update_sale_detail($sale_id);
				$response_data = array('status'=>'success', 'message'=>'New rates updated');
			}
			$this->__return_json_output($response_data);
		}else{
			$this->db->select('f.name as fish_name, si.fish_code, si.fish_rate');
			$this->db->from(SALE_ITEMS.' si');
			$this->db->join(FISH.' f', 'f.code=si.fish_code', 'LEFT');
			$this->db->where('si.sale_id', $sale_id);
			$this->db->group_by('si.fish_code');
			$data['result'] = $this->db->get()->result_array();
			$data['sale_id']		= $sale_id;
			$setup_form 			= $this->load->view('dailysale/depot_sale/ajax_update_rate', $data, true);
			$response_data 			= array('status' => 'success', 'page_title' => 'Update Rate', 'setup_form'=>$setup_form);
			
			$this->__return_json_output($response_data);
		}
	}
	
	//depot item edit mode
	public function ajax_dp_item_edit_mode($sale_id, $sale_item_id, $carret_number = ''){
		$response_data = array('status' => 'success', 'page_title' => 'Edit Carret Number '.$carret_number, 'setup_form'=>'');
		
		$data['all_fishes'] 	= $this->DSM->get_all_fishes();
		$item_data 				= $this->DSM->get_saleitem_data($sale_id, $sale_item_id);
		$data['item_data']		= $item_data[0];
		$data['sale_id']		= $sale_id;
		$data['sale_item_id']	= $sale_item_id;
		$data['carret_number']	= $carret_number;
		$setup_form 			= $this->load->view('dailysale/depot_sale/ajax_sale_item_edit_mode_v', $data, true);
		$response_data 			= array('status' => 'success', 'page_title' => 'Edit Item', 'setup_form'=>$setup_form);
		
		$this->__return_json_output($response_data);
	}
	
	//update daily production item
	public function ajax_update_dp_item(){
		$data = array('status' => 'error', 'message' => 'No data posted.', 'data' => '');
		$valid_data = $this->__setFormRules('update_depot_sale_item');
		if($valid_data){
			$post_data = $this->input->post();
			
			$sale_id   		= $this->input->post('sale_id');
			$sale_item_id   = $this->input->post('sale_item_id');
			$fish_code 		= $this->input->post('fish_code');
			$fish_quantity 	= $this->input->post('fish_quantity');
			$fish_weight 	= $this->input->post('fish_weight');
			$fish_rate 		= $this->input->post('fish_rate');
			$total_amount 	= $this->input->post('total_amount');
			$remark 		= $this->input->post('remark');
			$stock_type 	= $this->input->post('stock_type');
			
			$saleitem_data = array(	'fish_code' 		=> $fish_code,									
									'fish_quantity' 	=> $fish_quantity,
									'fish_weight' 		=> $fish_weight,
									'fish_rate' 		=> $fish_rate,
									'total_amount' 		=> $total_amount,
									'remark' 			=> $remark,
									'stock_type'		=> $stock_type,
									'updated_by' 		=> $this->userID,
									'updated_date'		=> get_datetime('Y-m-d H:i:s'),
									'status'			=> 'Active', 
									'action_microtime'	=> microtime(true)
								   );
			$result = $this->db->where(array('id'=>$sale_item_id))->update(SALE_ITEMS, $saleitem_data);
			
			if($result){
				$redirect_url = site_url('dailysale/depot/edit/'.$sale_id);
				$data = array('status' => 'success', 'message' => 'Item updated successfully', 'data' => '', 'redirect_url'=>$redirect_url);
			}else{
				$data = array('status' => 'error', 'message' => 'There is a problem in adding data, Please reload the page and try again.', 'data' => '');
			}
		}else{
			$data['status'] = 'error';
			$data['message'] = validation_errors();
		}
		
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
//==============================================================================
//OUTSIDE SALE FUNCTIONS
	
	//Outside sale listing
	public function outside_sale(){
		$this->actions = checkUserPermission('dailysale/outside_sale', $this->uri->segment(3));
		if(!empty($this->uri->segment(3)) && ($this->uri->segment(3) == 'add' || $this->uri->segment(3) == 'edit' || $this->uri->segment(3) == 'view')){redirect(site_url('dailysale/outside_sale'));}
		$crud = new grocery_CRUD();
		$crud->set_theme('bootstrap');
		$crud->unset_jquery();
		$crud->unset_bootstrap();
		$crud->unset_common_search();
		//$crud->unset_add();		
		$crud->unset_delete();		
		$crud->unset_read();
		
		if(!in_array('add', $this->actions)){
			$crud->unset_add();
		}else{
			$crud->set_add_url_path(site_url('dailysale/outside/add'));
		}
		
		if(!in_array('edit', $this->actions)){
			$crud->unset_edit();
		}else{
			//$crud->set_edit_button_text('Sale');
			$crud->set_edit_url_path(site_url('dailysale/outside/edit'));
		}
		/*
		if(in_array('delete', $this->actions)){
			$crud->add_action('Delete', 'triggerBulkDelete text-danger', site_url('bulk_action/action/mark_delete'), 'fa fa-trash', array($this, '__callbackDeleteActionButton'), 'dialogbox');			
			$crud->add_bulk_action('Delete', site_url('bulk_action/action/mark_delete'), ' text-danger', 'fa fa-trash', 'status');		
		}
		*/
		if(in_array('cancel', $this->actions)){
			$crud->add_action('Cancel', 'triggerBulkDelete text-danger', '', 'fa fa-times', array($this, '__callbackCancelActionButton'), 'dialogbox');			
			$crud->add_bulk_action('Cancel', site_url('bulk_action/action/mark_cancel'), ' text-danger', 'fa fa-trash', 'status');		
		}
		if(in_array('active', $this->actions)){
			$crud->add_action('Active', 'triggerBulkDelete', '', 'fa fa-check', array($this, '__callbackActiveActionButton'), 'dialogbox');
			$crud->add_bulk_action('Active', site_url('bulk_action/action/mark_active'), ' ', 'fa fa-check', 'status');
		}
		
		if(!in_array('export', $this->actions)){
			$crud->unset_export();
		}
		if(!in_array('print', $this->actions)){
			$crud->unset_print();
		}
		
		if(in_array('expenditure', $this->actions)){
			$crud->add_action('Add Expenditure', '', 'dailysale/expenditure', 'fa fa-list-alt', '', '');
		}
		if(in_array('view', $this->actions)){			
			$crud->add_action('View Challan', '', 'dailysale/view_challan', 'fa fa-eye', '', '');
		}
		
		/*if(in_array('lock', $this->actions)){
			$crud->add_action('Lock Data', 'triggerBulkDelete text-danger','','fa fa-lock', array($this,'__callbackLockActionButton'), 'dialogbox');
			$crud->add_bulk_action('Lock Data', site_url('bulk_action/action/lock'), ' text-danger', 'fa fa-lock', 'editable');		
		}*/
		
		if($crud->getState() == 'ajax_list'){
			$postData = $this->input->post();
			if(isset($postData['search_field']) && !empty($postData['search_field'])){
				foreach($postData['search_field'] as $key=>$value){
					if($value == 'sale_date'){
						$_POST['search_text'][$key] = get_date('Y-m-d', str_replace('/', '-', $postData['search_text'][$key]));
					}
				}
			}
		}
		
		$data = array('page_title'=> 'Outside Sale', 'content_view'=>'setup/setting');
		
		$crud->set_subject($data['page_title']);
		$crud->set_table(SALE);
		$crud->set_relation('client_id', CLIENT, 'company_name');
		$crud->set_relation('market_id', MARKET_PLACE, 'name');
		$crud->where(array(SALE.'.market_type'=>2, SALE.'.market_category'=>3));
		$crud->where(array(SALE.'.editable !='=>'Lock', SALE.'.status !='=>'Deleted'));
		if(in_array('view_all', $this->actions)){
			//For super admin user
			$crud->set_relation('added_by', ADMINISTRATOR, '{first_name} {last_name}');
			$crud->columns('sale_date', 'dr_number', 'market_id', 'client_id', 'total_gross_wt', 'total_wt', 'grand_total', 'status', 'added_by');
		}else{
			//For all other users
			$crud->where(array(SALE.'.added_by'=>$this->userID));
			$crud->columns('sale_date', 'dr_number', 'market_id', 'client_id', 'total_gross_wt', 'total_wt', 'grand_total', 'status');
		}
		$crud->display_summary('total_gross_wt', 'total_wt', 'grand_total');
		$crud->display_as(array('client_id'=>'Client', 'market_id'=>'Market', 'dr_number'=>'DR Number', 
								'total_gross_wt'=>'Gross Wt', 'total_wt'=>'Net Wt', 'grand_total'=>'Amount'));
		$crud->callback_column('sale_date', array($this, '__callaback_display_datetime'));
		$output = $crud->render();
		$outputData = array_merge((array)$output, $data);	
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($outputData);	
	}
	
	public function __callbackExpenditureButton($primary_key, $row){
		return site_url('dailysale/expenditure/'.$primary_key);
	}
	
	//Add, Edit, view point sale
	public function outside($action_mode = 'add', $id = NULL){
		$this->actions = checkUserPermission('dailysale/outside_sale', $this->uri->segment(3));
		$data['go_back_url'] 		= site_url('dailysale/outside_sale');
		$data['outside_markets'] 	= array();//$this->DSM->get_market_places(3);
		$data['page_title'] 		= 'Outside Sale '.$this->session_year;
		$data['content_view'] 		= 'dailysale/outside_sale/outside_sale_v';
		$data['disabled_input'] 	= '';
		$data['datepicker_class'] 	= 'datetimepicker';
		$data['action_mode'] 		= (!empty($action_mode) ? $action_mode : 'add');
		$data['sale_id'] 			= '';
		$data['hidden_class'] 		= 'hidden';
		$data['sale_data'] = array('sale_id' 		=> '',
								   'market_id' 		=> '',
								   'sale_date' 		=> '',
								   'invoice_number' => '',
								   'client_id' 		=> '',
								   'company_name' 	=> '',
								   'contact_number' => '',
								   'email' 			=> '',
								   'commission' 	=> ''
								   );
		
		if(($action_mode == 'view' || $action_mode == 'edit') && $id != NULL){
			$data['action_mode'] = $action_mode;
			$data['sale_id'] = $id;
			$data['disabled_input'] = 'readonly="readonly"';
			$data['datepicker_class'] = '';
			$data['hidden_class'] = '';
			$data['sale_data'] = $this->DSM->get_sale_data($id, $action_mode);
			if(!isset($data['sale_data']['sale_id']) || empty($data['sale_data']['sale_id'])){
				$this->messageci->set('Invalid request, Please try again.', 'error') ;
				redirect(site_url('dailysale/outside_sale'));
			}
			$sale_date = $data['sale_data']['sale_date'];
			$dr_number = $data['sale_data']['dr_number'];
			$market_id = $data['sale_data']['market_id'];
			$client_id = $data['sale_data']['client_id'];
			$data['all_boxes'] = $this->DSM->get_boxes_outside_sale($dr_number, 'Dispatched', $market_id, $client_id,$id);
		}
		
		$this->template->set('stylesheet', array(base_url('assets/plugins/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css'),
												 base_url('assets/plugins/select2/dist/css/select2.min.css'),
												 base_url('assets/plugins/bootstrap-switch/static/stylesheets/bootstrap-switch.css')
							));
		$this->template->set('scriptsrc', array(base_url('assets/plugins/moment/min/moment-with-locales.min.js'),
												base_url('assets/plugins/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'),
												base_url('assets/plugins/select2/dist/js/select2.full.min.js'),
												base_url('assets/plugins/bootstrap-switch/static/js/bootstrap-switch.min.js'),
												base_url('assets/modules/reports/jQuery.print.min.js'),
												base_url('assets/modules/reports/jquery.table2excel.min.js'), // work with ID
												site_url('dailysale/assets/js/outside_sale.js')
							 ));
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($data);
	}
	
	public function ajax_outside_sale_data(){
		$data = array('status' => 'failed', 'message' => 'No data posted.', 'data' => '');
		$formValidation = $this->__setFormRules('outside_sale_data');
		if($formValidation){
			$post_data 		= $this->input->post();
			$action_mode 	= $post_data['action_mode'];
			$sale_id 		= $post_data['sale_id'];
			$sale_date 		= get_datetime('Y-m-d H:i:s', str_replace('/', '-', $post_data['sale_date']));
			$dr_number 		= $post_data['dr_number'];
			$market_id 		= $post_data['market_id'];
			$mp_code		= $post_data['mp_code'];
			$client_id 		= $post_data['client_id'];
			$commission 	= $post_data['commission'];
			
			$sale_date_ymd 	= get_datetime('Y-m-d', str_replace('/', '-', $post_data['sale_date']));			
			$where 	= array('DATE(sale_date)'=>$sale_date_ymd, 'client_id'=>$client_id, 'status'=>'Active');
			$result = $this->db->select('sale_id')->where($where)->get(SALE)->result_array();
			if(!empty($result) && $result[0]['sale_id'] != $sale_id){
				$data['status'] = 'failed';
				$data['message'] = '<div class="alert alert-danger" style="padding: 5px !important;"> 
										<button class="close" data-dismiss="alert">×</button> 
										Error: There is already added sale data on selected date for this customer
										Click <a href="'.site_url('dailysale/outside/view/'.$result[0]['sale_id']).'" class="btn btn-primary btn-xs">here</a> to view.
									</div>';
			}else{
				//prepare sale data
				$sale_data = array(	'market_type' 		=> 2,
									'market_category' 	=> 3,
									'market_id' 		=> $market_id,
									'mp_code' 			=> $mp_code,
									'sale_date' 		=> $sale_date, //Y-m-d h:i:s									
									'dr_number' 		=> $dr_number,
									'client_id' 		=> $client_id,
									'commission' 		=> $commission,
									'added_by' 			=> $this->userID,
									'added_date'		=> get_datetime('Y-m-d H:i:s'),
									'action_microtime'	=> microtime(true),
									'status'			=> 'Active',
									'source'			=> SOURCE
								   );
				if($action_mode == 'add'){
					$this->db->insert(SALE, $sale_data);
					$sale_id = $this->db->insert_id();
					
					if($sale_id){
						$data = array('status' => 'success', 'message' => 'Sale data saved successfully', 'data' => $sale_id);
					}else{
						$data = array('status' => 'failed', 'message' => 'Failed to save sale data, Please try again.', 'data' => '');
					}
				}else if($action_mode == 'edit'){
					unset($sale_data['added_date'], $sale_data['added_by'], $sale_data['action_microtime'], $sale_data['status']);
					$sale_data['updated_date'] = get_datetime('Y-m-d H:i:s');
					$sale_data['updated_by'] = $this->userID;
					$sale_data['action_microtime'] =  microtime(true);
					$result = $this->db->where('sale_id', $sale_id)->update(SALE, $sale_data);
					if($result){
						$result = $this->db->where('sale_id', $sale_id)->update(SALE_ITEMS, array('market_id'=>$market_id, 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true)));
						$data = array('status'=>'success', 'message'=>'Sale data updated successfully', 'data'=>$sale_id);
					}else{
						$data = array('status'=>'failed', 'message'=>'Sale data failed to update', 'data'=>'');
					}
				}
			}
		}else{
			$data['status'] = 'failed';
			$data['message'] = validation_errors();
		}
		
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	public function ajax_save_outside_sale_item(){
		$data = array('status' => 'error', 'message' => 'No data posted.', 'data' => '');
		$valid_data = $this->__setFormRules('outsite_sale_item_data');
		if($valid_data){
			$post_data 		= $this->input->post();
			$action_mode	= $this->input->post('action_mode');
			$sale_id		= $this->input->post('sale_id');
			$client_id		= $this->input->post('client_id');
			$sale_date 		= get_datetime('Y-m-d H:i:s', str_replace('/', '-', $post_data['sale_date']));
			$market_id		= $this->input->post('market_id');
			
			$box_number		= $this->input->post('box_number');
			$fish_code		= $this->input->post('fish_code');
			$fish_quantity	= $this->input->post('fish_quantity');
			$fish_weight	= $this->input->post('fish_weight');
			$gross_wt		= $this->input->post('gross_wt');
			$net_wt			= $this->input->post('net_wt');
			$rate			= $this->input->post('rate');
			$amount			= $this->input->post('amount');
			$sale_items		= array();
			foreach($box_number as $key => $value){
				//prepared to insert in db
				$sale_items[] = array('sale_id'			=> $sale_id,
									  'market_id'		=> $market_id,
									  'client_id'		=> $client_id,
									  'sale_date' 		=> $sale_date,
									  'market_type'		=> 2,
									  'market_category'	=> 3,
									  'market_id'		=> $market_id,
									  'box_number' 		=> $value,
									  'fish_code' 		=> $fish_code[$key],
									  'fish_quantity' 	=> $fish_quantity[$key],
									  'fish_weight' 	=> $fish_weight[$key],
									  'gross_wt' 		=> $gross_wt[$key],
									  'net_wt' 			=> $net_wt[$key],
									  'fish_rate' 		=> $rate[$key],
									  'total_amount' 	=> $amount[$key],
									  'added_by'		=> $this->userID,
									  'added_date'		=> get_datetime('Y-m-d H:i:s'),
									  'action_microtime'=> microtime(true),
									  'source'			=> SOURCE
									 );
			}
			
			if(!empty($sale_items)){
				$this->db->delete(SALE_ITEMS, array('sale_id' => $sale_id));
				$result = $this->db->insert_batch(SALE_ITEMS, $sale_items);
				if($result){
					$this->DSM->update_outside_summary($sale_id, $client_id);
					$data = array('status' => 'success', 'message' => 'Items Sold successfully', 'data' => '', 'redirect_url' => site_url('dailysale/outside_sale'));
				}else{
					$data = array('status' => 'failed', 'message' => 'Failed to sale items, Please try again.', 'data' => '');
				}
			}else{
				$data = array('status' => 'failed', 'message' => 'No sale item data found. Please try again.', 'data' => '');
			}
		}else{
			$data['status'] = 'error';
			$data['message'] = validation_errors();
		}
		
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	public function ajax_outside_edit_mode($sale_id){
		$data['go_back_url'] 		= site_url('dailysale/outside_sale');
		$data['datepicker_class'] 	= 'datetimepicker';
		$data['action_mode'] 		= 'edit';
		$data['page_title'] 		= 'Outside Sale '.$this->session_year;
		$data['sale_id'] 			= $sale_id;
		$data['outside_markets'] 	= $this->DSM->get_market_places(3);
		$data['sale_data'] 			= $this->DSM->get_sale_data($sale_id);
		$setup_form = $this->load->view('dailysale/outside_sale/ajax_outside_sale_edit_mode_v', $data, true);
		$data = array('status'=>'success','data'=>$setup_form);
		$this->__return_json_output($data);
	}
	
	public function ajax_update_sale_data(){
		$data = array('status' => 'failed', 'message' => 'No data posted.', 'data' => '');
		$formValidation = $this->__setFormRules('outside_sale_data');
		if($formValidation){
			$post_data 		= $this->input->post();
			$action_mode 	= $post_data['action_mode'];
			$sale_id 		= $post_data['sale_id'];
			$sale_date 		= get_datetime('Y-m-d H:i:s', str_replace('/', '-', $post_data['sale_date']));
			$dr_number 		= $post_data['dr_number'];
			$market_id 		= $post_data['market_id'];
			$mp_code		= $post_data['mp_code'];
			$client_id 		= $post_data['client_id'];
			$commission 	= $post_data['commission'];
			
			$sale_data = array(	'market_type' 		=> 2,
								'market_category' 	=> 3,
								'market_id' 		=> $market_id,
								'mp_code' 			=> $mp_code,
								'sale_date' 		=> $sale_date, //Y-m-d h:i:s									
								'dr_number' 		=> $dr_number,
								'client_id' 		=> $client_id,
								'commission' 		=> $commission,
								'updated_date'		=> get_datetime('Y-m-d H:i:s'),
								'updated_by'		=> $this->userID,
								'action_microtime'	=> microtime(true)
							   );
			
			$box_number		= $this->input->post('box_number');
			$fish_code		= $this->input->post('fish_code');
			$fish_quantity	= $this->input->post('fish_quantity');
			$fish_weight	= $this->input->post('fish_weight');
			$gross_wt		= $this->input->post('gross_wt');
			$net_wt			= $this->input->post('net_wt');
			$rate			= $this->input->post('rate');
			$amount			= $this->input->post('amount');
			$sale_items		= array();
			if(!empty($box_number)){

				foreach($box_number as $key => $value){
					//prepared to insert in db
					$sale_items[] = array('sale_id'			=> $sale_id,
										  'market_id'		=> $market_id,
										  'sale_date' 		=> $sale_date,
										  'market_type'		=> 2,
										  'market_category'	=> 3,
										  'market_id'		=> $market_id,
										  'box_number' 		=> $value,
										  'fish_code' 		=> $fish_code[$key],
										  'fish_quantity' 	=> $fish_quantity[$key],
										  'fish_weight' 	=> $fish_weight[$key],
										  'gross_wt' 		=> $gross_wt[$key],
										  'net_wt' 			=> $net_wt[$key],
										  'fish_rate' 		=> $rate[$key],
										  'total_amount' 	=> $amount[$key],
										  'added_by'		=> $this->userID,
										  'added_date'		=> get_datetime('Y-m-d H:i:s'),
										  'action_microtime'=> microtime(true),
										  'source'			=> SOURCE
										 );
				}
			}
			if(!empty($sale_items)){
				$this->db->delete(SALE_ITEMS, array('sale_id' => $sale_id));
				$result = $this->db->insert_batch(SALE_ITEMS, $sale_items);
				if($result){
					$this->DSM->update_outside_summary($sale_id, $client_id);
					$data = array('status' => 'success', 'message' => 'Items Sold successfully', 'data' => '', 'redirect_url' => site_url('dailysale/outside_sale'));
				}
			}
			
			$result = $this->db->where('sale_id', $sale_id)->update(SALE, $sale_data);
			if($result){
				$data = array('status'=>'success', 'message'=>'Sale data updated successfully', 'data'=>$sale_id);
			}else{
				$data = array('status'=>'failed', 'message'=>'Sale data failed to update', 'data'=>'');
			}
			
		}else{
			$data['status'] = 'failed';
			$data['message'] = validation_errors();
		}
		
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	public function expenditure($sale_id = NULL){
		if(!$sale_id){
			$this->messageci->set('This is an invalid request! Please select a sale first.', 'error');
			redirect(site_url('dailysale/dailysale/outside_sale'));
		}

		
		$formValidation = $this->__setFormRules('save_sale_challan');
		if($formValidation){
			$post_data 	= $this->input->post();
			$challan_data = array('sale_id' 			=> $post_data['sale_id'],
							'gross_sale' 		=> $post_data['gross_sale'],
							'total_expenses' 	=> $post_data['total_expenses'],
							'net_sale' 			=> $post_data['net_sale'],
							'prev_balance' 		=> $post_data['prev_balance'],
							'grand_total' 		=> $post_data['grand_total'],
							'total_remition' 	=> $post_data['total_remition'],
							'balance' 			=> $post_data['balance'],
							'detail' 			=> $post_data['detail'],
							'added_by' 			=> $this->userID,
							'added_date' 		=> get_datetime('Y-m-d H:i:s'),
							'action_microtime'	=> microtime(true),
							'source'			=> SOURCE
							);
							
			//check challan existance
			$checkExistance = $this->db->where('sale_id', $post_data['sale_id'])->count_all_results(SALE_CHALLAN_DETAIL);
			$result = '';
			if($checkExistance){
				unset($challan_data['added_by'], $challan_data['added_date']);
				$challan_data['updated_by'] = $this->userID;
				$challan_data['updated_date'] = get_datetime('Y-m-d H:i:s');
				$challan_data['action_microtime'] = microtime(true);
				$result = $this->db->where('sale_id', $post_data['sale_id'])->update(SALE_CHALLAN_DETAIL, $challan_data);
			}else{
				$result = $this->db->insert(SALE_CHALLAN_DETAIL, $challan_data);
			}
			if($result){
				$this->messageci->set('Challan successfully saved!','success');				
			}else{
				$this->messageci->set('Saving challan failed!','error');
			}
		}
				
		$data['sale_data'] = $this->DSM->get_sale_data($sale_id);
		if(empty($data['sale_data'])){
			$this->messageci->set('Invalid request, Please try again.', 'error') ;
			redirect(site_url('dailysale/outside_sale'));
		}
		
		$sale_date = $data['sale_data']['sale_date'];
		$dr_number = $data['sale_data']['dr_number'];
		$market_id = $data['sale_data']['market_id'];
		$client_id = $data['sale_data']['client_id'];
$data['all_boxes'] = $this->DSM->get_challan_boxes_outside_sale($dr_number, 'Dispatched', $market_id, $client_id, $sale_id);
				
		//$this->actions = checkUserPermission('dailysale/outside_sale', $this->uri->segment(3));
		$data['go_back_url'] 		= site_url('dailysale/outside_sale');
		$data['sale_id']			= $sale_id;
		$data['all_expenditure'] 	= $this->DSM->get_sale_expenditure($sale_id);
		$data['remition_data'] 		= $this->DSM->get_sale_remition_data($sale_id);
		$data['prev_balance'] 		= $this->DSM->get_challan_customer_old_remaing($client_id, $sale_date, $sale_id);
		$data['page_title'] 		= 'Details of Expenditure';
		$data['content_view'] 		= 'dailysale/outside_sale/expenditure_v';
		
		
		$data['stylesheet'] = array(base_url('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css'));
		
								   
		$this->template->set('scriptsrc', array(
			base_url('assets/modules/reports/jQuery.print.min.js'),
			base_url('assets/plugins/bootstrap-typeahead/bootstrap3-typeahead.min.js'),
			base_url('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js'),
			site_url('dailysale/assets/js/expenditure.js')
		));
												
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($data);
	}
	
	public function json_particulars_typehead(){
		$search_key = $this->input->post('search_key');
		$data = array();
		if($search_key != ''){
			$search_exploded = explode (" ", $search_key);		 
			$x = "";
			$construct = "";			
			foreach($search_exploded as $search_each){
				$x++;				
				if($x==1){
					$construct .= "particular LIKE '%$search_each%'";
				}else{
					$construct .= " OR particular LIKE '%$search_each%'";
				}
			}
			$construct = "(".$construct.") AND status='Active'";
			$sql = "SELECT id, particular FROM ".PARTICULARS." WHERE $construct";
			$result = $this->db->query($sql)->result_array();	
			if(!empty($result)){				
				foreach($result as $value){			 
					 $data[] = array('id'=>$value['id'], 'name'=>$value['particular']);					
				}
			}
		}		
		$this->output->set_content_type('application/json');
  		$this->output->set_output(json_encode($data));
	}
	
	public function ajax_save_expenditure_item(){
		$data = array('status' => 'danger', 'message'=>'Invalid request.', 'data'=>'');
		$formValidation = $this->__setFormRules('ajax_save_expenditure');
		if($formValidation){
			$post_data 	= $this->input->post();
			$particular = $post_data['particular'];
			$amount 	= $post_data['amount'];			
			$prep_data 	= array('sale_id' 		=> $post_data['sale_id'],
							    'particular' 	=> $post_data['particular'],
							    'amount'		=> $post_data['amount'],
								'added_by'		=> $this->userID,
								'added_date'	=> get_datetime('Y-m-d H:i:s'),
								'action_microtime'=> microtime(true),
								'source'		=> SOURCE
							    );
			$this->db->insert(SALE_EXPENDITURE, $prep_data);
			$item_id = $this->db->insert_id();
			if($item_id){
				$data = array('status' => 'success', 'message'=>'Particular saved successfully', 'item_id'=>$item_id, 'particular'=>$particular, 'amount'=>$amount);
			}else{
				$data = array('status' => 'danger', 'message'=>'Saving particular failed, Please try again.','item_id'=>$item_id, 'particular'=>$particular, 'amount'=>$amount);
			}
		}else{
			$data['status'] = 'danger';
			$data['message'] = validation_errors();
		}
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	public function ajax_delete_expenditure(){
		$data = array('status' => 'danger', 'message'=>'Invalid request.', 'data'=>'');
		$formValidation = $this->__setFormRules('ajax_delete_expenditure');
		if($formValidation){
			$post_data 	= $this->input->post();
			$where 	= array('sale_id' 	=> $post_data['sale_id'],
							'id' 		=> $post_data['pid']
							);
			$result = $this->db->delete(SALE_EXPENDITURE, $where);
			if($result){
				$data = array('status' => 'success', 'message'=>'Particular deleted successfully');
			}else{
				$data = array('status' => 'danger', 'message'=>'Deleting particular failed, Please try again.');
			}
		}else{
			$data['status'] = 'danger';
			$data['message'] = validation_errors();
		}
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	public function ajax_save_remition_item(){
		$data = array('status' => 'danger', 'message'=>'Invalid request.');
		$formValidation = $this->__setFormRules('ajax_save_remition');
		if($formValidation){
			$post_data 	= $this->input->post();
			$remition_by = $post_data['remition_by'] .' - '. $post_data['remition_date'];
			$amount 	= $post_data['amount'];
			$remark 	= $post_data['remark'];
			$remition_by = $remark ? $remition_by .= "<br>" . $remark : $remition_by; 
			$remition_date = get_date('Y-m-d', str_replace('/','-', $post_data['remition_date']));			
			$prep_data 	= array('sale_id' 		=> $post_data['sale_id'],
							    'client_id' 	=> $post_data['client_id'],
							    'remition_by'	=> $post_data['remition_by'],
								'amount'		=> $post_data['amount'],
								'payment_date'	=> $remition_date,
								'remark'		=> $remark,
								'added_by' 		=> $this->userID,
								'added_date' 	=> get_datetime('Y-m-d H:i:s'),
								'action_microtime' => microtime(true),
								'source'		=> SOURCE
							    );
			$this->db->insert(CASH_RECEIVED, $prep_data);
			$item_id = $this->db->insert_id();
			if($item_id){
				$data = array('status' => 'success', 'message'=>'Remition Item saved successfully', 'item_id'=>$item_id, 'remition_by'=>$remition_by, 'amount'=>$amount);
			}else{
				$data = array('status' => 'danger', 'message'=>'Saving Remition failed, Please try again.');
			}
		}else{
			$data['status'] = 'danger';
			$data['message'] = validation_errors();
		}
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	public function ajax_delete_remition_item(){
		$data = array('status' => 'danger', 'message'=>'Invalid request.');
		$formValidation = $this->__setFormRules('ajax_delete_remition');
		if($formValidation){
			$post_data 	= $this->input->post();
			$where 	= array('sale_id' => $post_data['sale_id'], 'ID' => $post_data['rid']);
			$result = $this->db->delete(CASH_RECEIVED, $where);
			if($result){
				$data = array('status' => 'success', 'message'=>'Remition deleted successfully');
			}else{
				$data = array('status' => 'danger', 'message'=>'Deleting Remition failed, Please try again.');
			}
		}else{
			$data['status'] = 'danger';
			$data['message'] = validation_errors();
		}
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	public function view_challan($sale_id){
		//$this->actions = checkUserPermission('dailysale/outside_sale', $this->uri->segment(3));
		
		if(!$sale_id){
			$this->messageci->set('This is invalid request!', 'error');
			redirect(site_url('dailysale/outside_sale'));
		}
						
		$data['sale_data'] = $this->DSM->get_sale_data($sale_id);
		if(empty($data['sale_data'])){
			$this->messageci->set('Invalid request, Please try again.', 'error') ;
			redirect(site_url('dailysale/outside_sale'));
		}
		$sale_date = $data['sale_data']['sale_date'];
		$dr_number = $data['sale_data']['dr_number'];
		$market_id = $data['sale_data']['market_id'];
		$client_id = $data['sale_data']['client_id'];
		$data['all_boxes'] = $this->DSM->get_challan_boxes_outside_sale($dr_number, 'Dispatched', $market_id, $client_id, $sale_id);
				
		$data['go_back_url'] 		= site_url('dailysale/outside_sale');
		$data['sale_id']			= $sale_id;
		$data['all_expenditure'] 	= $this->DSM->get_sale_expenditure($sale_id);
		$data['remition_data'] 		= $this->DSM->get_sale_remition_data($sale_id);
		$data['prev_balance'] 		= $this->DSM->get_challan_customer_old_remaing($client_id, $sale_date, $sale_id);
		
		$data['page_title'] 		= 'Sale Challan Detail';
		$data['content_view'] 		= 'dailysale/outside_sale/view_challan_v';
		
		$this->template->set('scriptsrc', array(
			base_url('assets/modules/reports/jQuery.print.min.js'),
			base_url('assets/plugins/bootstrap-typeahead/bootstrap3-typeahead.min.js'),
			base_url('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js'),
			site_url('dailysale/assets/js/expenditure.js')
		));
		
													
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($data);
	
	}
	
//==============================================================================
	//Free Stock FUNCTIONS
	//Free Stock listing
	public function free_sale(){
		$this->actions = checkUserPermission('dailysale/free_sale', $this->uri->segment(3));
		if(!empty($this->uri->segment(3)) && ($this->uri->segment(3) == "add" || $this->uri->segment(3) == "edit")){redirect(site_url('dailysale/free_sale'));}
		
		$crud = new grocery_CRUD();
		$crud->set_theme('bootstrap');
		$crud->unset_jquery();
		$crud->unset_bootstrap();
		$crud->unset_common_search();
		//$crud->unset_add();		
		$crud->unset_delete();		
		
		if(!in_array('add', $this->actions)){
			$crud->unset_add();
		}else{
			$crud->set_add_url_path(site_url('dailysale/free/add'));
		}
		
		if(!in_array('edit', $this->actions)){
			$crud->unset_edit();
		}else{
			//$crud->set_edit_button_text('Sale');
			$crud->set_edit_url_path(site_url('dailysale/free/edit'));
		}
		
		if(in_array('view', $this->actions)){
			$crud->set_read_url_path(site_url('dailysale/free/view'));
		}else{
			$crud->unset_read();
		}
		
		if(in_array('delete', $this->actions)){
			$crud->add_action('Delete', 'triggerBulkDelete text-danger', site_url('bulk_action/action/mark_delete'), 'fa fa-times', array($this, '__callbackDeleteActionButton'), 'dialogbox');			
			$crud->add_bulk_action('Delete', site_url('bulk_action/action/mark_delete'), ' text-danger', 'fa fa-times', 'status');		
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
					if($value == 'free_sale_date'){
						$_POST['search_text'][$key] = get_date('Y-m-d', str_replace('/', '-', $postData['search_text'][$key]));
					}
				}
			}
		}
		
		$data = array('page_title'=> 'Free Sale', 'content_view'=>'setup/setting');
		
		$crud->set_subject($data['page_title']);
		$crud->set_table(FREE_SALE);
		$crud->set_relation('client_id', CLIENT, 'company_name');
		$crud->set_relation('market_id', MARKET_PLACE, 'name');
		$crud->where(array(FREE_SALE.'.market_type'=>1));
		$crud->where(array(FREE_SALE.'.editable !='=>'Lock', FREE_SALE.'.status !='=>'Deleted'));
		if(in_array('view_all', $this->actions)){
			//For super admin user
			$crud->set_relation('added_by', ADMINISTRATOR, '{first_name} {last_name}');
			$crud->columns('free_sale_date', 'market_id', 'client_id', 'total_quantity', 'total_weight', 'added_by');
		}else{
			//For all other users
			$crud->where(array(FREE_SALE.'.added_by'=>$this->userID));
			$crud->columns('free_sale_date', 'market_id', 'client_id', 'total_quantity', 'total_weight');
		}
		$crud->display_summary('total_quantity', 'total_weight');
		$crud->display_as(array('free_sale_date'=>'Date', 'client_id'=>'Client', 'market_id'=>'Point/Depot'));
		$crud->callback_column('free_sale_date', array($this, '__callaback_display_datetime'));
		$output = $crud->render();
		
		$outputData = array_merge((array)$output, $data);	
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($outputData);	
	}	
	
	//Add, Edit, view free sale
	public function free($action_mode = 'add', $id = NULL){
		$this->actions = checkUserPermission('dailysale/free_sale', $this->uri->segment(3));
		$data['go_back_url'] = site_url('dailysale/free_sale');
		$data['market_category'] = $this->DSM->get_market_category(1);
		$data['all_fishes'] = $this->DSM->get_all_fishes();
		$data['page_title'] = 'FREE SALE '.$this->session_year;
		$data['content_view'] = 'dailysale/free_sale/free_sale_v';
		$data['disabled_input'] = '';
		$data['datepicker_class'] = 'datetimepicker';
		$data['action_mode'] = (!empty($action_mode) ? $action_mode : 'add');
		$data['free_sale_id'] = '';
		$data['sale_data'] = array('free_sale_id' 	=> '',
								   'market_id' 		=> '',
								   'market_id' 		=> '',
								   'free_sale_date' => '',
								   'invoice_number' => '',
								   'client_id' 		=> '',
								   'company_name' 	=> '',
								   'contact_number' => '',
								   'email' 			=> '',
								   'remark' 		=> '',
								   'total_quantity' => '',
								   'total_weight' 	=> ''
								   );
		
		if(($action_mode == 'view' || $action_mode == 'edit') && $id != NULL){
			$data['action_mode'] = $action_mode;
			$data['free_sale_id'] = $id;
			$data['disabled_input'] = 'readonly="readonly"';
			$data['datepicker_class'] = '';
			$data['sale_data'] = $this->DSM->get_free_sale_data($id, $action_mode);
			if(!isset($data['sale_data']['free_sale_id']) || empty($data['sale_data']['free_sale_id'])){
				$this->messageci->set('Invalid request, Please try again.', 'error') ;
				redirect(site_url('dailysale/free_sale'));
			}
			$data['sale_item'] = $this->DSM->get_free_saleitem_data($id);
		}
		
		$this->template->set('stylesheet', array(base_url('assets/plugins/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css'),
												 base_url('assets/plugins/select2/dist/css/select2.min.css')
							));
		$this->template->set('scriptsrc', array(base_url('assets/plugins/moment/min/moment-with-locales.min.js'),
												base_url('assets/plugins/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'),
												base_url('assets/plugins/select2/dist/js/select2.full.min.js'),
												base_url('assets/modules/reports/jQuery.print.min.js'),
												base_url('assets/modules/reports/jquery.table2excel.min.js'), // work with ID
												site_url('dailysale/assets/js/stock_common.js'),
												site_url('dailysale/assets/js/free_sale.js')
							 ));
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($data);
	}
	
	public function ajax_free_sale_data(){
		$data = array('status' => 'failed', 'message' => 'No data posted.', 'data' => '');
		$formValidation = $this->__setFormRules('free_sale_data');
		if($formValidation){
			$post_data = $this->input->post();
			$action_mode = $post_data['action_mode'];
			$free_sale_id = $post_data['free_sale_id'];
			
			//Register customer and get its id
			if($post_data['customer_type'] == 'new'){
				//register new customer
				$customer_data = array(	'market_type' 		=> 1,
										'company_name' 		=> $post_data['customer_name'],
										'email' 			=> $post_data['customer_email'],
										'contact_number'	=> $post_data['customer_mobile'],
										'added_by' 			=> $this->userID,
										'added_date' 		=> get_datetime('Y-m-d H:i:s'),
										'action_microtime'	=> microtime(true),
										'status' 			=> 'Active',
										'source'			=> SOURCE
										);
				
				$this->db->insert(CLIENT, $customer_data);
				$customer_id = $this->db->insert_id();
			}elseif($post_data['customer_type'] == 'registered'){
				$customer_id = $post_data['customer_id'];
			}
			
			if($customer_id){
				$free_sale_date 	= get_datetime('Y-m-d H:i:s', str_replace('/', '-', $post_data['free_sale_date']));
				//Preparing sale data for insert or update
				$sale_data = array(	'market_type' 		=> 1,
									'market_category' 	=> $post_data['market_category'],
									'market_id' 		=> $post_data['market_id'],
									'free_sale_date' 	=> $free_sale_date,									
									'client_id' 		=> $customer_id,
									'remark' 			=> $post_data['remark'],
									'added_by' 			=> $this->userID,
									'added_date'		=> get_datetime('Y-m-d H:i:s'),
									'action_microtime'	=> microtime(true),
									'status'			=> 'Active',
									'source'			=> SOURCE
								   );
				
				$result = array();
				if($action_mode == 'add'){
					$where 		= array('free_sale_date'=>get_date('Y-m-d', $free_sale_date), 'client_id'=>$customer_id, 'status'=>'Active');
					$result 	= $this->db->select('free_sale_id')->where($where)->get(FREE_SALE)->result_array();
				}
				
				if(!empty($result)){
					//if record already exists
					$data['status'] = 'failed';
					$data['message'] = '<div class="alert alert-danger" style="padding: 5px !important;"> 
											<button class="close" data-dismiss="alert">×</button> 
											Error: There is already added free sale data on selected date and time for this customer
											Click <a href="'.site_url('dailysale/free/view/'.$result[0]['free_sale_id']).'" class="btn btn-primary btn-xs">here</a> to view.
										</div>';
				}else{
					//if no record found, prepare sale data
					if($action_mode == 'add'){
						$this->db->insert(FREE_SALE, $sale_data);
						$free_sale_id = $this->db->insert_id();
						if($free_sale_id){
							$data = array('status'=>'success', 'message'=>'Sale data saved successfully', 'data'=>$free_sale_id);
						}else{
							$data = array('status'=>'failed', 'message'=>'Sale data failed to save', 'data'=>'');
						}
					}else if($action_mode == 'edit'){
						unset($sale_data['added_date'], $sale_data['added_by'], $sale_data['action_microtime'], $sale_data['status']);
						$sale_data['updated_date'] = get_datetime('Y-m-d H:i:s');
						$sale_data['updated_by'] = $this->userID;
						$sale_data['action_microtime'] =  microtime(true);
						$result = $this->db->where('free_sale_id', $post_data['free_sale_id'])->update(FREE_SALE, $sale_data);
						if($result){
							$item_upd = array('market_category' => $post_data['market_category'],
											  'market_id' 		=> $post_data['market_id'],
											  'free_sale_date' 	=> $free_sale_date,
											  'updated_by'		=> $this->userID, 
											  'updated_date' 	=> get_datetime('Y-m-d H:i:s'),
											  'action_microtime'=> microtime(true)
											  );
							$this->db->where('free_sale_id', $post_data['free_sale_id'])->update(FREE_SALE_ITEMS, $item_upd);
							$data = array('status'=>'success', 'message'=>'Sale data updated successfully', 'data'=>$free_sale_id);
						}else{
							$data = array('status'=>'failed', 'message'=>'Sale data failed to update', 'data'=>'');
						}
					}
				}
			}else{
				$data['status'] = 'failed';
				$data['message'] = 'Customer data not found';
			}
		}else{
			$data['status'] = 'failed';
			$data['message'] = validation_errors();
		}
		
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	public function ajax_fs_edit_mode($free_sale_id){
		$data['go_back_url'] 		= site_url('dailysale/free_sale');
		$data['market_category'] 	= $this->DSM->get_market_category(1);
		$data['action_mode'] 		= 'edit';
		$data['page_title'] 		= 'FREE SALE '.$this->session_year;
		$data['free_sale_id'] 		= $free_sale_id;
		$data['disabled_input'] 	= '';
		$data['datepicker_class'] 	= 'datetimepicker';
		$data['sale_data'] 			= $this->DSM->get_free_sale_data($free_sale_id);
		$data['market_places'] 		= $this->DSM->get_market_places($data['sale_data']['market_category']);
		$data['stylesheet'] = array( base_url('assets/plugins/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css'),
									 base_url('assets/plugins/select2/dist/css/select2.min.css')
								    );
		$data['scriptsrc'] = array( base_url('assets/plugins/moment/min/moment-with-locales.min.js'),
									base_url('assets/plugins/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'),
									base_url('assets/plugins/select2/dist/js/select2.full.min.js')
							 	   );
		$setup_form = $this->load->view('dailysale/free_sale/free_sale_edit_mode_v', $data, true);
		$data = array('status'=>'success','data'=>$setup_form);
		$this->__return_json_output($data);
	}
	
	public function ajax_save_free_sale_item(){
		$post_data = $this->input->post();
		$data = array('status' => 'error', 'message' => 'No data posted.', 'data' => '');
		$valid_data = $this->__setFormRules('free_sale_item');
		if($valid_data){
			$post_data = $this->input->post();			
			$saleitem_data = array(	'free_sale_id' 		=> $post_data['free_sale_id'],
									'free_sale_date' 	=> get_datetime('Y-m-d H:i:s', str_replace('/', '-', $post_data['free_sale_date'])),
									'market_category' 	=> $post_data['market_category'],
									'market_id' 		=> $post_data['market_id'],
									'fish_code' 		=> $post_data['fish_code'],									
									'fish_quantity' 	=> $post_data['fish_quantity'],
									'fish_weight' 		=> $post_data['fish_weight'],
									'stock_type' 		=> $post_data['stock_type'],
									'added_by' 			=> $this->userID,
									'added_date'		=> get_datetime('Y-m-d H:i:s'),
									'action_microtime'	=> microtime(true),
									'status'			=> 'Active',
									'source'			=> SOURCE
								   );
			$this->db->insert(FREE_SALE_ITEMS, $saleitem_data);
			$saleitem_id = $this->db->insert_id();
			if($saleitem_id){
				$this->DSM->update_free_sale_summary($post_data['free_sale_id']);
				$html_data = '';
				$html_data .= '<tr>';
                $html_data .= ' <td>'.($post_data['s_no_count']+1).'</td>';
                $html_data .= ' <td>'.$post_data['fish_name'].'</td>';
                $html_data .= ' <td><input type="text" readonly="readonly" value="'.number_format($post_data['fish_quantity'],'2','.','').'" class="form-control disabled input-sm si_qty" /></td>';
                $html_data .= ' <td><input type="text" readonly="readonly" value="'.number_format($post_data['fish_weight'],'2','.','').'" class="form-control disabled input-sm si_wt" /></td>';
				$html_data .= ' <td>'.$post_data['stock_type'].'</td>';
                $html_data .= ' <td><a href="javascript:void(0);" data-free_sale_id="'.$post_data['free_sale_id'].'" data-sale_item_id="'.$saleitem_id.'" class="btn btn-danger btn-sm delete_sale_item"><i class="fa fa-trash"></i> </a></td>';
                $html_data .= '</tr>';
				
				$data = array('status' => 'success', 'message' => 'Item added successfully', 'data' => $html_data);
			}else{
				$data = array('status' => 'error', 'message' => 'There is a problem in adding data, Please reload the page and try again.', 'data' => '');
			}
		}else{
			$data['status'] = 'error';
			$data['message'] = validation_errors();
		}
		
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	public function ajax_delete_free_sale_item(){
		$data = array('status' => 'error', 'message' => 'No data posted.', 'data' => '');
		$valid_data = $this->__setFormRules('delete_free_sale_item');
		if($valid_data){
			$post_data = $this->input->post();
			$free_sale_id 	= $post_data['free_sale_id'];
			$sale_item_id 	= $post_data['sale_item_id'];
			
			$result = $this->db->delete(FREE_SALE_ITEMS, array('id'=>$sale_item_id, 'free_sale_id'=>$free_sale_id));
			if($result){
				$this->DSM->update_free_sale_summary($free_sale_id);
				
				$redirect_url = site_url('dailysale/free/edit/'.$free_sale_id);
				$data = array('status' => 'success', 'message' => 'Item deleted successfully', 'data' => $redirect_url);
			}else{
				$data = array('status' => 'error', 'message' => 'There is a problem in deleting data, Please reload the page and try again.', 'data' => '');
			}
		}else{
			$data['status'] = 'error';
			$data['message'] = validation_errors();
		}
		$this->__return_json_output($data);
	}
	
//================================================================================
//common functions
	public function __return_json_output($data){
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	//Get customers for javascript select2 plugin
	//$market_type : Local Market = 1, Outside Market = 2
	public function get_customers($market_type=NULL){
		$data['result1'] = '';
		$data['result2'] = '';
		$q = $this->input->get('q');
		if(!empty($q)){
			$data = $this->DSM->get_clients_data($q, $offset = 0, $limit = 10, $market_type);
		}
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	public function typeahead_get_customers($market_type=NULL){
		$data['result1'] = '';
		$data['result2'] = '';
		$search_type = $this->input->post('search_type');
		$search_key = $this->input->post('search_key');
		if(!empty($search_key)){
			$data = $this->DSM->typeahead_get_clients_data($search_type, $search_key, $offset = 0, $limit = 10, $market_type);
			//printr($data);
		}
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	public function __callbackActiveActionButton($primary_key, $row){ 
		return site_url('bulk_action/action/mark_active');
	}
	
	public function __callbackCancelActionButton($primary_key, $row){ 
		return site_url('bulk_action/action/mark_cancel');
	}
	
	public function __callbackDeleteActionButton($primary_key, $row){ 
		return site_url('bulk_action/action/mark_delete');
	}
	
	public function __callbackLockActionButton($primary_key, $row){ 
		return site_url('bulk_action/action/lock');
	}
		
	//called by mulitple functions
	function ajax_dr_markets(){
		$dr_number = $this->input->post('dr_number');
		$market_places = $this->db->select('dhalta, dispatch_to')->get_where(PRODUCT_DISPATCH, array('dr_number'=>$dr_number, 'status'=>"Dispatched"))->result_array();
		
		$clients = $this->db->select('client_id')->get_where(SALE, array('dr_number'=>$dr_number, 'status'=>'Active'))->result_array();
		$data['market_options'] = ''; $market_category = 3;
		
		if(!empty($market_places)){
			$market_place_id = array_column($market_places, 'dispatch_to');
			$all_markets = $this->DSM->get_market_places($market_category, $market_place_id);
			if(!empty($all_markets)){
				$data['market_options'] .= '<option value="">Select Market</option>';
				foreach($all_markets as $row){
					$dhalta = 0;
					foreach($market_places as $mp){
						if($row['ID'] == $mp['dispatch_to']){
							$dhalta = $mp['dhalta'];
						}
					}
					$data['market_options'] .= '<option data-dhalta="'.$dhalta.'" value="'.$row['ID'].'">'.$row['name'].'</option>>';
				}
			}else{
				$data['market_options'] .= '<option value="">No Markets Found</option>';
			}
		}else{
			$data['market_options'] .= '<option value="">No Markets Found</option>';
		}
		
		$response = array('status'=>'success', 'msg'=>'Data found.', 'data'=>$data);
		
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($response));
	}
	
	function ajax_market_clients(){
		$market_id = $this->input->post('market_id');
		$data['client_options'] = '';
		if(!empty($market_id)){
			$all_clients = $this->DSM->get_all_clients('', $market_id);
			if(!empty($all_clients)){
				$data['client_options'] .= '<option value="">Select Client</option>';
				foreach($all_clients as $row){
					$data['client_options'] .= '<option value="'.$row['ID'].'">'.$row['company_name'].'</option>>';
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
	
	public function ajax_get_market_places(){
		$data = array('status'=>'danger', 'msg'=>'No data found', 'data'=>'<option value="">No data found</option>');
		$market_category = $this->input->post('market_category');
		if(!empty($market_category)){
			$result = $this->DSM->get_market_places($market_category);
			$option = '<option value="">Select Market</option>';
			if(!empty($result)){
				foreach($result as $res){
					$option .= '<option value="'.$res['ID'].'">'.$res['name'].'</option>>';
				}
				$data = array('status'=>'success', 'msg'=>'Data found.', 'data'=>$option);
			}
		}	
		$this->__return_json_output($data);
	}
	
	public function __callaback_display_datetime($value, $row){
		return get_datetime('d/m/Y', $value);
	}
	
	//Formvalidtion callback
	function checkMobileExist(){
		$data = TRUE;
		$customer_type 		= $this->input->post('customer_type');
		$customer_mobile 	= $this->input->post('customer_mobile');
		$customer_code 		= $this->input->post('customer_code');
		
		if(!empty($customer_mobile)){
			if($customer_type == 'regular' && empty($customer_code)){
				$result = $this->db->where('contact_number', $customer_mobile)->count_all_results(CLIENT);
				if($result > 0){
					$data = FALSE;
					$this->form_validation->set_message(array('checkMobileExist'=>'Customer Mobile is already used.'));
				}
			}
		}else{
			$data = FALSE;
			$this->form_validation->set_message(array('checkMobileExist'=>'Customer Mobile is required.'));
		}

		return $data;
	}
	
	//Formvalidtion callback
	function checkEmailExist(){
		$data = TRUE;
		$customer_type 		= $this->input->post('customer_type');
		$customer_email 	= $this->input->post('customer_email');
		$customer_code 		= $this->input->post('customer_code');
		if($customer_type == 'regular' && empty($customer_code)){
			if(!empty($customer_email)){
				$result = $this->db->where('email', $customer_email)->count_all_results(CLIENT);
				if($result > 0){
					$data = FALSE;
					$this->form_validation->set_message(array('checkEmailExist'=>'Customer Email is already used.'));
				}
			}
		}
		return $data;
	}
	
	//Formvalidtion callback
	
	function checkCustomerCodeExist(){
		$data = TRUE;
		$customer_type = $this->input->post('customer_type');
		$customer_code = $this->input->post('customer_code');
		if($customer_code){
			$result = $this->db->where('code', $customer_code)->count_all_results(CLIENT);
			if($result == 0){
				$data = FALSE;
				$this->form_validation->set_message('checkCustomerCodeExist', 'Invalid Customer Code.');
			}
		}
		return $data;
	}
	
	//Formvalidtion callback
	function checkBoxFishes(){
		$data = FALSE;
		$fish_code = $this->input->post('fish_code');
		$box_number = $this->input->post('box_number');
		$result = $this->db->where(array('box_number'=>$box_number, 'fish_code'=>$fish_code, 'status'=>'Dispatched'))->count_all_results(PRODUCT_BOX_ITEMS);
		if($result){
			$data = TRUE;
		}	
		return $data;
	}
	
	//Formvalidtion callback
	function checkBoxFishesQty(){
		$data = FALSE;
		$fish_code = $this->input->post('fish_code');
		$fish_quantity = $this->input->post('fish_quantity');
		$box_number = $this->input->post('box_number');
		
		$box_fish_qty = $this->db->select('IFNULL(SUM(fish_qty), 0) as box_fish_qty')->where(array('box_number'=>$box_number, 'fish_code'=>$fish_code, 'status'=>'Dispatched'))->get(PRODUCT_BOX_ITEMS)->result_array();
		$sold_fish_qty = $this->db->select('IFNULL(SUM(fish_quantity), 0) as sold_fish_qty')->where(array('box_number'=>$box_number, 'fish_code'=>$fish_code, 'status'=>'Active'))->get(SALE_ITEMS)->result_array();
		
		$box_total_fish_qty = $box_fish_qty[0]['box_fish_qty'];
		$box_sold_fish_qty = $sold_fish_qty[0]['sold_fish_qty'];
		$remaining_qty = $box_total_fish_qty - $box_sold_fish_qty;
		if($remaining_qty >= $fish_quantity){
			$data = TRUE;
		}
		return $data;
	}
	
	//Formvalidtion callback
	function checkDrExist(){
		$data = FALSE;
		$dr_number = $this->input->post('dr_number');
		$result = $this->db->where(array('dr_number'=>$dr_number))->get(PRODUCT_DISPATCH)->result_array();
		if(count($result) > 0){
			if($result[0]['status'] == "Dispatched"){
				$data = TRUE;
			}else{
				$this->form_validation->set_message('checkDrExist', 'DR No. '.$dr_number.' is not dispatched.');
			}
		}else{
			$this->form_validation->set_message('checkDrExist', 'Invalid DR Number.');
		}
		return $data;
	}
	
	//Formvalidation callabck
	function checkInvoiceExistence(){
		$data = FALSE;
		//$post_data = $this->input->post();
		$action_mode = $this->input->post('action_mode');
		$sale_id = $this->input->post('sale_id');
		$mp_code = $this->input->post('mp_code');
		$invoice_number = $this->input->post('invoice_number');
		if($invoice_number){
			if($action_mode == 'add'){
				$result = $this->db->where(array('invoice_number'=>$invoice_number, 'mp_code'=>$mp_code, 'status'=>'Active'))->count_all_results(SALE);
				if($result == 0){
					$data = TRUE;
				}else{
					$this->form_validation->set_message('checkInvoiceExistence', 'This invoce number is already used, Please provice different invoice number.');
				}
			}else if($action_mode == 'edit' && !empty($sale_id)){
				$result = $this->db->where(array('invoice_number'=>$invoice_number, 'mp_code'=>$mp_code, 'status'=>'Active'))->get(SALE)->result_array();
				$result_count = count($result);
				if($result_count == 0 || ($result_count == 1 && ($result[0]['sale_id'] == $sale_id))){
					$data = TRUE;
				}else if(count($result) == 1 && ($result[0]['sale_id'] != $sale_id)){
					$this->form_validation->set_message('checkInvoiceExistence', 'This invoce number is already used, Please provice unique invoice number.');
				}
			}
		}else{
			$this->form_validation->set_message(array('checkInvoiceExistence'=>'Invoice Number field is required.'));
		}
		return $data;
	}
	
	//Formvalidation callabck
	/*
	function checkReceivedAmount(){
		$data = FALSE;
		$grand_total 	= $this->input->post('grand_total');
		$received_amt 	= $this->input->post('received_amt');
		if($received_amt <= $grand_total){
			$data = TRUE;
		}else{
			$this->form_validation->set_message(array('checkReceivedAmount'=>'Recevied amount must be less than or equal to Grand Total.'));
		}
		return $data;
	}
	*/
	
	public function __setFormRules($setRulesFor = ''){
		switch($setRulesFor){
			case 'point_sale_data':
				$this->form_validation->set_rules('market_id', 'Point', 'trim|required|min_length[1]|integer');
				$this->form_validation->set_rules('mp_code', 'Code', 'trim|required|min_length[1]', array('required'=>'The Point field is required.'));
				//$this->form_validation->set_rules('invoice_number', 'Invoice Number', 'trim|required|min_length[1]|callback_checkInvoiceExistence');
				$this->form_validation->set_rules('sale_date', 'Date', 'trim|required');
				$this->form_validation->set_rules('action_mode', 'Action Mode', 'trim|required|in_list[add,edit]');
				$this->form_validation->set_rules('customer_type', 'Customer Type', 'trim|required|in_list[regular,onetime]');
				if($this->input->post('customer_type') == 'regular'){
					$this->form_validation->set_rules('customer_code', 'Customer Code', 'trim|callback_checkCustomerCodeExist');
					$this->form_validation->set_rules('customer_name', 'Customer Name', 'trim|required|min_length[2]|max_length[50]');
					$this->form_validation->set_rules('customer_mobile', 'Customer Mobile', 'trim|min_length[10]|required|max_length[30]|integer|callback_checkMobileExist');
					$this->form_validation->set_rules('customer_email', 'Customer Email', 'trim|valid_email|callback_checkEmailExist');
				}elseif($this->input->post('customer_type') == 'onetime'){
					$this->form_validation->set_rules('customer_name', 'Customer Name', 'trim|required|min_length[2]|max_length[50]');
				}
			break;
			
			case 'point_sale_item':
				$this->form_validation->set_rules('action_mode', 'Action Mode', 'trim|required|in_list[add,edit]');
				$this->form_validation->set_rules('sale_id', 'Sale id', 'trim|required|integer');
				$this->form_validation->set_rules('sale_date', 'Sale Date', 'trim|required');
				$this->form_validation->set_rules('market_id', 'Point', 'trim|required');
				$this->form_validation->set_rules('fish_code', 'Fish Code', 'trim|required|min_length[2]');
				$this->form_validation->set_rules('fish_quantity', 'Fish Quantity', 'trim|required|numeric');
				$this->form_validation->set_rules('fish_weight', 'Fish Weight', 'trim|required|numeric|greater_than[0]');
				$this->form_validation->set_rules('fish_rate', 'Fish Rate', 'trim|required|numeric');
				$this->form_validation->set_rules('stock_type', 'Stock', 'trim|required|in_list[Current]');
			break;
			
			case 'save_point_summary':
				$this->form_validation->set_rules('action_mode', 'Action Mode', 'trim|required|in_list[add,edit]');
				$this->form_validation->set_rules('sale_id', 'Sale id', 'trim|required|integer');
				$this->form_validation->set_rules('total_qty', 'Total Quantity', 'trim|numeric');
				$this->form_validation->set_rules('total_wt', 'Total Weight', 'trim|numeric');
				$this->form_validation->set_rules('sub_total', 'Sub Total', 'trim|numeric');
				$this->form_validation->set_rules('ice_weight', 'Ice Weight', 'trim|numeric');
				$this->form_validation->set_rules('ice_rate', 'Ice Rate', 'trim|numeric');
				$this->form_validation->set_rules('ice_amount', 'Ice Amount', 'trim|numeric');
				$this->form_validation->set_rules('destroyed_weight', 'Destroyed Weight', 'trim|numeric');
				$this->form_validation->set_rules('destroyed_rate', 'Destroyed Rate', 'trim|numeric');
				$this->form_validation->set_rules('destroyed_amount', 'Destroyed Amount', 'trim|numeric');
				$this->form_validation->set_rules('discount_perc', 'Discount Percent', 'trim|numeric');
				$this->form_validation->set_rules('discount_amount', 'Discount Amount', 'trim|numeric');
				$this->form_validation->set_rules('grand_total', 'Grand Total', 'trim|numeric');
				$this->form_validation->set_rules('received_amt', 'Received Amount', 'trim|numeric');
			break;
			
			case 'depot_sale_data':
				$this->form_validation->set_rules('market_id', 'Depot', 'trim|required|min_length[1]|integer');
				$this->form_validation->set_rules('mp_code', 'Code', 'trim|required|min_length[1]', array('required'=>'The Depot field is required.'));
				//$this->form_validation->set_rules('invoice_number', 'Invoice Number', 'trim|required|min_length[1]|callback_checkInvoiceExistence');
				$this->form_validation->set_rules('sale_date', 'Date', 'trim|required');
				$this->form_validation->set_rules('action_mode', 'Action Mode', 'trim|required|in_list[add,edit]');
				$this->form_validation->set_rules('customer_type', 'Customer Type', 'trim|required|in_list[regular,onetime]');
				if($this->input->post('customer_type') == 'regular'){
					$this->form_validation->set_rules('customer_code', 'Customer Code', 'trim|callback_checkCustomerCodeExist');
					$this->form_validation->set_rules('customer_name', 'Customer Name', 'trim|required|min_length[2]|max_length[50]');
					$this->form_validation->set_rules('customer_mobile', 'Customer Mobile', 'trim|required|min_length[10]|max_length[30]|integer|callback_checkMobileExist');
					$this->form_validation->set_rules('customer_email', 'Customer Email', 'trim|valid_email|callback_checkEmailExist');
				}elseif($this->input->post('customer_type') == 'onetime'){
					$this->form_validation->set_rules('customer_name', 'Customer Name', 'trim|required|min_length[2]|max_length[50]');
				}
			break;
			
			case 'depot_sale_item':
				$this->form_validation->set_rules('action_mode', 'Action Mode', 'trim|required|in_list[add,edit]');
				$this->form_validation->set_rules('sale_id', 'Sale id', 'trim|required|integer');
				$this->form_validation->set_rules('sale_date', 'Sale Date', 'trim|required');
				$this->form_validation->set_rules('market_id', 'Depot', 'trim|required');
				$this->form_validation->set_rules('fish_code', 'Fish Code', 'trim|required|min_length[2]');
				$this->form_validation->set_rules('fish_quantity', 'Fish Quantity', 'trim|required|numeric');
				$this->form_validation->set_rules('fish_weight', 'Fish Weight', 'trim|required|numeric|greater_than[0]');
				$this->form_validation->set_rules('fish_rate', 'Fish Rate', 'trim|required|numeric');
				$this->form_validation->set_rules('stock_type', 'Stock', 'trim|required|in_list[Bachat,Opening]');
			break;
			
			case 'update_depot_sale_item':
				$this->form_validation->set_rules('sale_id', 'Sale ID', 'trim|required|integer');
				$this->form_validation->set_rules('sale_item_id', 'Sale Item ID', 'trim|required');
				$this->form_validation->set_rules('fish_code', 'Fish Code', 'trim|required|min_length[2]');
				$this->form_validation->set_rules('fish_quantity', 'Fish Quantity', 'trim|required|numeric');
				$this->form_validation->set_rules('fish_weight', 'Fish Weight', 'trim|required|numeric|greater_than[0]');
				$this->form_validation->set_rules('fish_rate', 'Fish Rate', 'trim|required|numeric');
				$this->form_validation->set_rules('total_amount', 'Total Amount', 'trim|required|numeric');
			break;
			
			case 'save_depot_summary':
				$this->form_validation->set_rules('action_mode', 'Action Mode', 'trim|required|in_list[add,edit]');
				$this->form_validation->set_rules('sale_id', 'Sale id', 'trim|required|integer');
				$this->form_validation->set_rules('total_qty', 'Total Quantity', 'trim|numeric');
				$this->form_validation->set_rules('total_wt', 'Total Weight', 'trim|numeric');
				$this->form_validation->set_rules('sub_total', 'Sub Total', 'trim|numeric');
				$this->form_validation->set_rules('ice_weight', 'Ice Weight', 'trim|numeric');
				$this->form_validation->set_rules('ice_rate', 'Ice Rate', 'trim|numeric');
				$this->form_validation->set_rules('ice_amount', 'Ice Amount', 'trim|numeric');
				$this->form_validation->set_rules('destroyed_weight', 'Destroyed Weight', 'trim|numeric');
				$this->form_validation->set_rules('destroyed_rate', 'Destroyed Rate', 'trim|numeric');
				$this->form_validation->set_rules('destroyed_amount', 'Destroyed Amount', 'trim|numeric');
				$this->form_validation->set_rules('discount_perc', 'Discount Percent', 'trim|numeric');
				$this->form_validation->set_rules('discount_amount', 'Discount Amount', 'trim|numeric');
				$this->form_validation->set_rules('grand_total', 'Grand Total', 'trim|numeric');
				//$this->form_validation->set_rules('received_amt', 'Received Amount', 'trim|numeric|callback_checkReceivedAmount');
				$this->form_validation->set_rules('received_amt', 'Received Amount', 'trim|numeric');
				$this->form_validation->set_rules('remaining_amt', 'Remaining Amount', 'trim|numeric');
				//$this->form_validation->set_rules('old_received_amt', 'Old Received Amount', 'trim|numeric');
			break;
			
			case 'outside_sale_data':
				$this->form_validation->set_rules('action_mode', 'Action Mode', 'trim|required|in_list[add,edit]');
				$this->form_validation->set_rules('sale_date', 'Date', 'trim|required');
				$this->form_validation->set_rules('dr_number', 'DR Number', 'trim|required|integer|callback_checkDrExist');
				$this->form_validation->set_rules('client_id', 'Customer', 'trim|required');
				$this->form_validation->set_rules('commission', 'Commission', 'trim|required|numeric');
			break;
			
			case 'outsite_sale_item_data':
				$this->form_validation->set_rules('action_mode', 'Action Mode', 'trim|required|in_list[add,edit]');
				$this->form_validation->set_rules('sale_id', 'Sale id', 'trim|required|integer');
				$this->form_validation->set_rules('client_id', 'Customer id', 'trim|required|integer');
				$this->form_validation->set_rules('sale_date', 'Sale Date', 'trim|required');
				$this->form_validation->set_rules('market_id', 'Market Id', 'trim|required');
				$this->form_validation->set_rules('box_number[]', 'Box Number', 'trim|required');
				$this->form_validation->set_rules('gross_wt[]', 'Gross Weight', 'trim|required|numeric|greater_than_equal_to[0]');
				$this->form_validation->set_rules('net_wt[]', 'Net Weight', 'trim|numeric|greater_than_equal_to[0]');
				$this->form_validation->set_rules('rate[]', 'Rate', 'trim|required|numeric|greater_than[0]');
				$this->form_validation->set_rules('rate[]', 'Amount', 'trim|required|numeric|greater_than[0]');
			break;
			
			case 'delete_sale_item': //common validation for point, depot and outside
				$this->form_validation->set_rules('sale_item_id', 'Sale item id', 'trim|required|integer');
				$this->form_validation->set_rules('sale_id', 'Sale id', 'trim|required|integer');
			break;
		
			case 'free_sale_data':
				$this->form_validation->set_rules('market_category', 'Category', 'trim|required|min_length[1]|integer');
				$this->form_validation->set_rules('market_id', 'Market', 'trim|required|min_length[1]|integer');
				$this->form_validation->set_rules('free_sale_date', 'Date', 'trim|required');
				$this->form_validation->set_rules('action_mode', 'Action Mode', 'trim|required|in_list[add,edit]');
				$this->form_validation->set_rules('customer_type', 'Customer Type', 'trim|required|in_list[registered,new]');
				if($this->input->post('customer_type') == 'new'){
					$this->form_validation->set_rules('customer_name', 'Customer Name', 'trim|required');
					$this->form_validation->set_rules('customer_mobile', 'Customer Mobile', 'trim|required|min_length[10]|max_length[12]|integer|callback_checkMobileExist');
					$this->form_validation->set_rules('customer_email', 'Customer Email', 'trim|valid_email|callback_checkEmailExist');
				}elseif($this->input->post('customer_type') == 'registered'){
					$this->form_validation->set_rules('customer_id', 'Customer', 'trim|required');
				}
			break;
			
			case 'free_sale_item':
				$this->form_validation->set_rules('action_mode', 'Action Mode', 'trim|required|in_list[add,edit]');
				$this->form_validation->set_rules('free_sale_id', 'Sale id', 'trim|required|integer');
				$this->form_validation->set_rules('free_sale_date', 'Sale Date', 'trim|required');
				$this->form_validation->set_rules('market_category', 'Category', 'trim|required|min_length[1]|integer');
				$this->form_validation->set_rules('market_id', 'Market', 'trim|required');
				$this->form_validation->set_rules('fish_code', 'Fish Code', 'trim|required|min_length[2]');
				$this->form_validation->set_rules('fish_quantity', 'Fish Quantity', 'trim|required|numeric');
				$this->form_validation->set_rules('fish_weight', 'Fish Weight', 'trim|required|numeric|greater_than[0]');
				$this->form_validation->set_rules('stock_type', 'Stock', 'trim|required|in_list[Opening,Current,Bachat]');
			break;
			
			case 'delete_free_sale_item':
				$this->form_validation->set_rules('free_sale_id', 'Sale id', 'trim|required|integer');
				$this->form_validation->set_rules('sale_item_id', 'Sale item id', 'trim|required|integer');
			break;
			
			case 'ajax_save_expenditure':
				$this->form_validation->set_rules('sale_id', 'Sale id', 'trim|required|integer');
				$this->form_validation->set_rules('particular', 'Particular', 'trim|required|min_length[2]');
				$this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric|greater_than[0]');
			break;
						
			case 'ajax_delete_expenditure':
				$this->form_validation->set_rules('sale_id', 'Sale id', 'trim|required|integer');
				$this->form_validation->set_rules('pid', 'Particular ID', 'trim|required');
			break;
			
			case 'ajax_save_remition':
				$this->form_validation->set_rules('sale_id', 'Sale id', 'trim|required|integer');
				$this->form_validation->set_rules('client_id', 'Sale id', 'trim|required|integer');
				$this->form_validation->set_rules('remition_by', 'Remition by', 'trim|required');
				$this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric|greater_than[0]');
				$this->form_validation->set_rules('remition_date', 'Remition date', 'trim|required');
			break;
						
			case 'ajax_delete_remition':
				$this->form_validation->set_rules('sale_id', 'Sale id', 'trim|required|integer');
				$this->form_validation->set_rules('rid', 'Remition ID', 'trim|required|integer');
			break;
								
			case 'save_sale_challan':
				$this->form_validation->set_rules('sale_id', 'Sale id', 'trim|required|integer');
				$this->form_validation->set_rules('gross_sale', 'Gross Sale', 'trim|required|greater_than[0]');
				$this->form_validation->set_rules('total_expenses', 'Total expenses', 'trim|required|greater_than[0]');
				$this->form_validation->set_rules('net_sale', 'Net Sale', 'trim|required|greater_than[0]');
				//$this->form_validation->set_rules('prev_balance', 'Prev balance', 'trim|required|greater_than[0]');
				$this->form_validation->set_rules('grand_total', 'Grand Total', 'trim|required');
				$this->form_validation->set_rules('balance', 'Balance', 'trim|required');			
			break;
			
		}
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert_msg margin-5 padding-5"><button data-dismiss="alert" class="close">×</button><i class="fa fa-times-circle"></i> ', '</div>');
		return $this->form_validation->run($this);
	}
}
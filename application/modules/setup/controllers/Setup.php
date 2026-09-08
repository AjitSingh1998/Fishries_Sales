<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Setup extends MY_Controller {
	var $userID, $actions;
	//$crud->unique_fields(array('code'));
	public function __construct(){
		parent::__construct();
		$this->userID = checkUserLogin();
		$this->load->model('setup_model', 'SM');
    }
	
	function index(){
		redirect('setup/fish_category');
	}	
	
//Customer/Client
	//1. Customer/Client
	function client(){
		$this->actions = checkUserPermission('setup/client', $this->uri->segment(3));
		$actions = $this->actions;

		$crud = new grocery_CRUD();
		$crud->set_theme('bootstrap');
		$crud->unset_jquery();
		$crud->unset_bootstrap();
		$crud->unset_common_search();		
		$crud->unset_delete();
			
		if(!in_array('add', $actions)){
			$crud->unset_add();
		}else{
			$crud->set_add_url_path(site_url('setup/add_client'));
			$crud->set_add_button_class('btn btn-default loadActionForm');
		}
		
		if(!in_array('edit', $actions)){
			$crud->unset_edit();
		}else{			
			$crud->set_edit_url_path(site_url('setup/add_client'));
			$crud->set_edit_button_class('btn btn-default loadActionForm');
			
			$crud->add_bulk_action('Active', site_url('bulk_action/action/active'), '', 'fa fa-check', 'status');
			$crud->add_bulk_action('Inactive', site_url('bulk_action/action/inactive'), '', 'fa fa-ban', 'status');
		}
		
		if(in_array('view', $actions)){
			$crud->set_read_url_path(site_url('setup/view_client'));
			$crud->set_read_button_class('loadActionForm');
		}else{
			$crud->unset_read();
		}
		
		if(in_array('delete', $actions)){
			$crud->add_action('Delete', 'triggerBulkDelete text-danger', '', 'fa fa-trash', array($this, '__callbackDeleteActionButton'), 'dialogbox');
			$crud->add_bulk_action('Delete', site_url('bulk_action/action/mark_delete'), ' text-danger', 'fa fa-trash', 'status');		
		}
		
		if(!in_array('export', $actions)){
			$crud->unset_export();
		}
		
		if(!in_array('print', $actions)){
			$crud->unset_print();
		}
		/*
		if(in_array('lock', $actions)){
			$crud->add_action('Lock Data', 'triggerBulkDelete text-danger','','fa fa-lock', array($this,'__callbackLockActionButton'), 'dialogbox');
			$crud->add_bulk_action('Lock Data', site_url('bulk_action/action/lock'), ' text-danger', 'fa fa-lock', 'editable');		
		}
		*/			
		$crud->set_subject('Client');
		$crud->set_table(CLIENT);
		$crud->set_relation('market_type', MARKET_TYPE, 'name');
		$crud->set_relation('city', MARKET_PLACE, 'name');
		$crud->where(array(CLIENT.'.status !='=>'Deleted', CLIENT.'.editable'=>'Unlock'));
		
		$crud->order_by('added_date', 'DESC');
		$crud->columns('market_type', 'code', 'company_name', 'trademark_name', 'contact_number', 'city', 'opening_balance', 'status');
		$crud->display_as(array('company_name'=>'Client Name', 'city'=>'Market'));
		$output = $crud->render();
		$data = array('page_title'=> 'Client', 'content_view'=>'setup/setting');		  
		$outputData = array_merge((array)$output, $data);	
		
		$this->template->set('stylesheet', array(base_url('assets/plugins/select2/dist/css/select2.min.css')));
		$this->template->set('scriptsrc', array(base_url('assets/plugins/select2/dist/js/select2.full.min.js'),
												site_url('setup/assets/js/setup.js')
												));
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($outputData);
	}
	
	function add_client($id = NULL){
		$data = array('status' => 'error', 'msg' => 'Invalid Request.');
		$data['city']	= array();
		$data['dbdata'] = array(  'ID'				=>'',
								  'opening_balance' =>'',
								  'market_type'		=>'',
								  'code'			=>'',
								  'trademark_name'	=>'',
								  'company_name'	=>'',
								  'email'			=>'',
								  'contact_number'	=>'',
								  'contact_number2'	=>'',
								  'address'			=>'',
								  'city'			=>'',
								  'state'			=>'',
								  'country'			=>'',
								  'remark'			=>'',
								  'status'			=>''
								);
								
		$data['page_title'] = 'Add Client';		
		$data['action_mode'] = 'add';
		$data['form_action'] = site_url('setup/add_client');
		
		$form_valid = $this->__setFormRules('add_client');
		if($form_valid){
			$post = $this->input->post();
			$action_mode = $post['action_mode'];
			$prepData = array('code'			=>$post['code'],
							  'opening_balance' =>$post['opening_balance'],
							  'trademark_name'	=>$post['trademark_name'],
							  'company_name'	=>$post['company_name'],
							  'market_type'		=>$post['market_type'],
							  'email'			=>$post['email'],
							  'contact_number'	=>$post['contact_number'],
							  'contact_number2'	=>$post['contact_number2'],
							  'address'			=>$post['address'],
							  'city'			=>$post['city'],
							  'state'			=>$post['state'],
							  'country'			=>$post['country'],
							  'status'			=>$post['status'],
							  'added_by'		=>$this->userID,
							  'added_date'		=>get_datetime('Y-m-d H:i:s'),
							  'action_microtime'=>microtime(true), 'source'=>SOURCE
							  );
			if($id != NULL && $action_mode == 'edit'){
				//UPDATE				
				unset($prepData['added_date'], $prepData['added_by']);
				$prepData['updated_date'] = get_datetime('Y-m-d H:i:s');
				$prepData['updated_by'] = $this->userID;
				$result = $this->db->where('ID', $id)->update(CLIENT, $prepData);
				if($result){
					// Generate Json file with all client details
					//$this->__callbackGenerateClientJson();
					
					$data['action_mode'] = 'edit';
					$data['form_action'] = site_url('setup/add_client/'.$id);
					$data['status'] = 'success';
					$data['msg'] = 'Data updated successfully.';					
				}else{
					$data['status'] = 'error';
					$data['msg'] = 'Failed to update. Please try again.';
				}
			}elseif($action_mode == 'add'){
				//iNSERT
				$result = $this->db->insert(CLIENT, $prepData);
				$id = $this->db->insert_id(); //Primary fisher id
				if($id){
					//$this->__callbackGenerateClientJson();
					$data['action_mode'] = 'edit';
					$data['form_action'] = site_url('setup/add_client/'.$id);
					$data['status'] = 'success';
					$data['msg'] = 'Data inserted successfully.';
										
				}else{
					$data['status'] = 'error';
					$data['msg'] = 'Failed to insert. Please try again.';
				}
			}
		}else{
			$data['status'] = 'error';
			$data['msg'] = validation_errors();
		}
		
		if($id != NULL){
			$data['page_title'] 	= 'Edit Client';
			$data['action_mode'] 	= 'edit';
			$data['form_action'] 	= site_url('setup/add_client/'.$id);
			$client_data 			= $this->SM->get_client_data($id);
			$data['city'] 			= $this->SM->get_mp_data('', $client_data['market_type']);
			if(!empty($client_data)){
				$data['dbdata'] = $client_data;
			}
		}
		
		$data['market_types'] = $this->SM->get_market_types();
		$data['scriptsrc'] = array(site_url('setup/assets/js/client.js'));
		$data['setup_form'] = $this->load->view('setup/forms/add_client_v', $data, true);
		
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	function __callbackGenerateClientJson(){
		$result = $this->db->get(CLIENT)->result_array();
		$jsonData = '';
		if(!empty($result)){
			$jsonData = json_encode($result);
		}
		$fileName = FCPATH.'assets/data/clients.json';
		$writeSetting = file_put_contents($fileName, $jsonData);
	}
	
	//Ajax function when add and edit fisherman
	function view_client($id = NULL){
		$data = array('status' => 'danger', 'msg' => 'Invalid Request.');
		$data['page_title'] = 'Client Detail';
		if($id != NULL){
			$client_data = $this->SM->get_client_data($id);
			//printr($client_data);
			if(!empty($client_data)){
				$data['dbdata'] = $client_data;
			}
		}
		$data['disabled'] = 'disabled="disabled"';
		$data['setup_form'] = $this->load->view('setup/forms/view_client_v', $data, true);
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
	
	//Select fisherman ajax request
	function ajax_client(){
		$data['result1'] = '';
		$data['result2'] = '';
		$q = $this->input->get('q');
		$f_ids = $this->input->get('f_ids');
		if(!empty($q)){
			$data = $this->SM->get_fisherman($q, $f_ids, $offset = 0, $limit = 10);
		}
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
//Customer/Client	
	
	//2. Fish Category
	function fish_category(){
		$actions = checkUserPermission('setup/fish_category', $this->uri->segment(3));
		
		$crud = new grocery_CRUD();
		$crud->set_theme('bootstrap');
		$crud->unset_jquery();
		$crud->unset_bootstrap();
		$crud->unset_common_search();					
		$crud->unset_delete();
		
		
		if(!in_array('add', $actions)){
			$crud->unset_add();
		}		
		if(!in_array('read', $actions)){
			$crud->unset_read();
		}
		if(!in_array('export', $actions)){
			$crud->unset_export();
		}
		if(!in_array('print', $actions)){
			$crud->unset_print();
		}
		
		if(!in_array('edit', $actions)){
			$crud->unset_edit();
		}else{
			$crud->add_bulk_action('Active', site_url('bulk_action/action/active'), '', 'fa fa-check', 'status');
			$crud->add_bulk_action('Inactive', site_url('bulk_action/action/inactive'), '', 'fa fa-ban', 'status');
		}
		
		if(in_array('delete', $actions)){
			$crud->add_action('Delete', 'triggerBulkDelete text-danger', '', 'fa fa-trash', array($this, '__callbackDeleteActionButton'), 'dialogbox');
			$crud->add_bulk_action('Delete', site_url('bulk_action/action/mark_delete'), ' text-danger', 'fa fa-trash', 'status');		
		}
		/*
		if(in_array('lock', $actions)){
			$crud->add_action('Lock Data', 'triggerBulkDelete text-danger','','fa fa-lock', array($this,'__callbackLockActionButton'), 'dialogbox');
			$crud->add_bulk_action('Lock Data', site_url('bulk_action/action/lock'), ' text-danger', 'fa fa-lock', 'editable');		
		}
			*/
		
		$crud->set_subject('Fish Category');
		$crud->set_table(FISH_CATEGORY);
		$crud->where(array('status !='=>'Deleted', 'editable'=>'Unlock'));
		
		$crud->columns('name', 'code', 'status');	
		$crud->fields('name', 'code',  'status', 'added_by', 'added_date', 'updated_by', 'updated_date', 'action_microtime');
		$crud->required_fields('name', 'status');
		
		$crud->unique_fields(array('code'));
		
		$crud->field_type('added_by','invisible');
		$crud->field_type('added_date','invisible');
		$crud->field_type('updated_by','invisible');
		$crud->field_type('updated_date','invisible');
		$crud->field_type('action_microtime','invisible');
		
		$crud->callback_before_insert(array($this, '__callbackBeforeInsert'));
 		$crud->callback_before_update(array($this, '__callbackBeforeUpdate'));
		
		$crud->unset_read_fields('added_by', 'added_date', 'updated_by', 'updated_date', 'action_microtime');
		$output = $crud->render();
		$data = array('page_title'=> 'Fish Category', 'content_view'=>'setup/setting');
		$outputData = array_merge((array)$output, $data);
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($outputData);
	}
	
	//3. All Fishes
	function all_fishes(){
		$actions = checkUserPermission('setup/all_fishes', $this->uri->segment(3));
		
		$columns = array('code' =>FISH.'.code', 'name' =>FISH.'.name', 'status' =>FISH.'.status'); 
		$search_field = $this->input->post('search_field');
		
		if(!empty($search_field)){
			foreach($search_field as $key => $value){
				if(array_key_exists($value,$columns)){
					$search_field[$key] = $columns[$value];
				}
			}
			$_POST['search_field'] = $search_field;
		}
		
		$crud = new grocery_CRUD();
		$crud->set_theme('bootstrap');
		$crud->unset_jquery();
		$crud->unset_bootstrap();
		$crud->unset_common_search();					
		$crud->unset_delete();
		
		
		if(!in_array('add', $actions)){
			$crud->unset_add();
		}		
		if(!in_array('read', $actions)){
			$crud->unset_read();
		}
		if(!in_array('export', $actions)){
			$crud->unset_export();
		}
		if(!in_array('print', $actions)){
			$crud->unset_print();
		}
		
		if(!in_array('edit', $actions)){
			$crud->unset_edit();
		}else{
			$crud->add_bulk_action('Active', site_url('bulk_action/action/active'), '', 'fa fa-check', 'status');
			$crud->add_bulk_action('Inactive', site_url('bulk_action/action/inactive'), '', 'fa fa-ban', 'status');
		}
		
		if(in_array('delete', $actions)){
			$crud->add_action('Delete', 'triggerBulkDelete text-danger', '', 'fa fa-trash', array($this, '__callbackDeleteActionButton'), 'dialogbox');
			$crud->add_bulk_action('Delete', site_url('bulk_action/action/mark_delete'), ' text-danger', 'fa fa-trash', 'status');		
		}
		/*
		if(in_array('lock', $actions)){
			$crud->add_action('Lock Data', 'triggerBulkDelete text-danger','','fa fa-lock', array($this,'__callbackLockActionButton'), 'dialogbox');
			$crud->add_bulk_action('Lock Data', site_url('bulk_action/action/lock'), ' text-danger', 'fa fa-lock', 'editable');		
		}
		*/	
		
		$crud->set_subject('All Fishes');
		$crud->set_table(FISH);
		$crud->set_relation('category_id', FISH_CATEGORY, 'Name');
		$crud->where(array(FISH.'.status !='=>'Deleted', FISH.'.editable'=>'Unlock'));
		
		$crud->columns('type', 'name', 'code', 'category_id', 'fish_rate', 'dhalta', 'status');	
		$crud->fields('category_id', 'type', 'code', 'name', 'fish_rate', 'dhalta', 'status','added_by', 'added_date', 'updated_by', 'updated_date', 'action_microtime');
		$crud->required_fields('category_id', 'type', 'code', 'name', 'fish_rate', 'dhalta', 'status');
		
		$crud->display_as(array('category_id'=>'Category', 'dhalta'=>'Dhalta (in grams)'));
		$crud->field_type('added_by','invisible');
		$crud->field_type('added_date','invisible');
		$crud->field_type('updated_by','invisible');
		$crud->field_type('updated_date','invisible');
		$crud->field_type('action_microtime','invisible');
		$crud->unique_fields(array('code'));
		$crud->callback_before_insert(array($this, '__callbackBeforeInsert'));
 		$crud->callback_before_update(array($this, '__callbackBeforeUpdate'));
		
		$crud->callback_after_insert(array($this, '__callbackGenerateFishJson'));
 		$crud->callback_after_update(array($this, '__callbackGenerateFishJson'));
		
		$crud->unset_read_fields('added_by', 'added_date', 'updated_by', 'updated_date', 'action_microtime');
		$output = $crud->render();
		$data = array('page_title'=> 'All Fishes', 'content_view'=>'setup/setting');
		$outputData = array_merge((array)$output, $data);
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($outputData);
	}
	
	function __callbackGenerateFishJson(){
		$result = $this->db->get(FISH)->result_array();
		$jsonData = '';
		if(!empty($result)){
			$jsonData = json_encode($result);
		}
		$fileName = FCPATH.'assets/data/fish.json';
		$writeSetting = file_put_contents($fileName, $jsonData);
	}	
	
	//4. Particulars
	function particulars(){
		$actions = checkUserPermission('setup/particulars', $this->uri->segment(3));
		
		$crud = new grocery_CRUD();
		$crud->set_theme('bootstrap');
		$crud->unset_jquery();
		$crud->unset_bootstrap();
		$crud->unset_common_search();					
		$crud->unset_delete();
		
		if(!in_array('add', $actions)){
			$crud->unset_add();
		}		
		if(!in_array('read', $actions)){
			$crud->unset_read();
		}
		if(!in_array('export', $actions)){
			$crud->unset_export();
		}
		if(!in_array('print', $actions)){
			$crud->unset_print();
		}
		
		if(!in_array('edit', $actions)){
			$crud->unset_edit();
		}else{
			$crud->add_bulk_action('Active', site_url('bulk_action/action/active'), '', 'fa fa-check', 'status');
			$crud->add_bulk_action('Inactive', site_url('bulk_action/action/inactive'), '', 'fa fa-ban', 'status');
		}
		
		if(in_array('delete', $actions)){
			$crud->add_action('Delete', 'triggerBulkDelete text-danger', '', 'fa fa-trash', array($this, '__callbackDeleteActionButton'), 'dialogbox');
			$crud->add_bulk_action('Delete', site_url('bulk_action/action/mark_delete'), ' text-danger', 'fa fa-trash', 'status');		
		}
		
		/*
		if(in_array('lock', $actions)){
			$crud->add_action('Lock Data', 'triggerBulkDelete text-danger','','fa fa-lock', array($this,'__callbackLockActionButton'), 'dialogbox');
			$crud->add_bulk_action('Lock Data', site_url('bulk_action/action/lock'), ' text-danger', 'fa fa-lock', 'editable');		
		}
		*/
		
		$data = array('page_title'=> 'Market Type', 'content_view'=>'setup/setting');
		
		$crud->set_subject('Particulars');
		$crud->set_table(PARTICULARS);
		$crud->where(array('status !='=>'Deleted'));
		$crud->columns('particular', 'status');	
		$crud->fields('particular', 'status');
		$crud->required_fields('name', 'status');
		$output = $crud->render();
		
		$outputData = array_merge((array)$output, $data);
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($outputData);
	}
	
	//5. Market Type
	function market_type(){
		$actions = checkUserPermission('setup/market_type', $this->uri->segment(3));
		
		$crud = new grocery_CRUD();
		$crud->set_theme('bootstrap');
		$crud->unset_jquery();
		$crud->unset_bootstrap();
		$crud->unset_common_search();					
		$crud->unset_delete();
		
		
		if(!in_array('add', $actions)){
			$crud->unset_add();
		}		
		if(!in_array('read', $actions)){
			$crud->unset_read();
		}
		if(!in_array('export', $actions)){
			$crud->unset_export();
		}
		if(!in_array('print', $actions)){
			$crud->unset_print();
		}
		
		if(!in_array('edit', $actions)){
			$crud->unset_edit();
		}else{
			$crud->add_bulk_action('Active', site_url('bulk_action/action/active'), '', 'fa fa-check', 'status');
			$crud->add_bulk_action('Inactive', site_url('bulk_action/action/inactive'), '', 'fa fa-ban', 'status');
		}
		
		if(in_array('delete', $actions)){
			$crud->add_action('Delete', 'triggerBulkDelete text-danger', '', 'fa fa-trash', array($this, '__callbackDeleteActionButton'), 'dialogbox');
			$crud->add_bulk_action('Delete', site_url('bulk_action/action/mark_delete'), ' text-danger', 'fa fa-trash', 'status');		
		}
		/*
		if(in_array('lock', $actions)){
			$crud->add_action('Lock Data', 'triggerBulkDelete text-danger','','fa fa-lock', array($this,'__callbackLockActionButton'), 'dialogbox');
			$crud->add_bulk_action('Lock Data', site_url('bulk_action/action/lock'), ' text-danger', 'fa fa-lock', 'editable');		
		}
		*/
		$crud->set_subject('Market Type');
		$crud->set_table(MARKET_TYPE);
		$crud->where(array('status !='=>'Deleted', 'editable'=>'Unlock'));
		
		$crud->columns('code', 'name', 'status');	
		$crud->fields('code', 'name', 'status', 'added_by', 'added_date', 'updated_by', 'updated_date', 'action_microtime');
		$crud->required_fields('name', 'status');
		$crud->unique_fields(array('code'));
		$crud->field_type('added_by','invisible');
		$crud->field_type('added_date','invisible');
		$crud->field_type('updated_by','invisible');
		$crud->field_type('updated_date','invisible');
		$crud->field_type('action_microtime','invisible');
		
		$crud->callback_before_insert(array($this, '__callbackBeforeInsert'));
 		$crud->callback_before_update(array($this, '__callbackBeforeUpdate'));
		
		$crud->unset_read_fields('added_by', 'added_date', 'updated_by', 'updated_date', 'action_microtime');
		$output = $crud->render();
		$data = array('page_title'=> 'Market Type', 'content_view'=>'setup/setting');
		$outputData = array_merge((array)$output, $data);
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($outputData);
	}
	
	//6. Market Category
	function market_category(){
		$actions = checkUserPermission('setup/market_category', $this->uri->segment(3));
		
		$crud = new grocery_CRUD();
		$crud->set_theme('bootstrap');
		$crud->unset_jquery();
		$crud->unset_bootstrap();
		$crud->unset_common_search();					
		$crud->unset_delete();
		
		
		if(!in_array('add', $actions)){
			$crud->unset_add();
		}		
		if(!in_array('read', $actions)){
			$crud->unset_read();
		}
		if(!in_array('export', $actions)){
			$crud->unset_export();
		}
		if(!in_array('print', $actions)){
			$crud->unset_print();
		}
		
		if(!in_array('edit', $actions)){
			$crud->unset_edit();
		}else{
			$crud->add_bulk_action('Active', site_url('bulk_action/action/active'), '', 'fa fa-check', 'status');
			$crud->add_bulk_action('Inactive', site_url('bulk_action/action/inactive'), '', 'fa fa-ban', 'status');
		}
		
		if(in_array('delete', $actions)){
			$crud->add_action('Delete', 'triggerBulkDelete text-danger', '', 'fa fa-trash', array($this, '__callbackDeleteActionButton'), 'dialogbox');
			$crud->add_bulk_action('Delete', site_url('bulk_action/action/mark_delete'), ' text-danger', 'fa fa-trash', 'status');		
		}
		/*
		if(in_array('lock', $actions)){
			$crud->add_action('Lock Data', 'triggerBulkDelete text-danger','','fa fa-lock', array($this,'__callbackLockActionButton'), 'dialogbox');
			$crud->add_bulk_action('Lock Data', site_url('bulk_action/action/lock'), ' text-danger', 'fa fa-lock', 'editable');		
		}
		*/	
		
		$crud->set_subject('Market Category');
		$crud->set_table(MARKET_CATEGORY);
		$crud->set_relation('market_type', MARKET_TYPE, 'name');
		$crud->where(array(MARKET_CATEGORY.'.status !='=>'Deleted', MARKET_CATEGORY.'.editable'=>'Unlock'));
		
		$crud->columns('name', 'code', 'market_type', 'status');	
		$crud->fields('market_type', 'name', 'code', 'status', 'added_by', 'added_date', 'updated_by', 'updated_date', 'action_microtime');
		$crud->required_fields('market_type', 'name', 'status');
		$crud->unique_fields(array('code'));
		$crud->field_type('added_by','invisible');
		$crud->field_type('added_date','invisible');
		$crud->field_type('updated_by','invisible');
		$crud->field_type('updated_date','invisible');
		$crud->field_type('action_microtime','invisible');
		
		$crud->callback_before_insert(array($this, '__callbackBeforeInsert'));
 		$crud->callback_before_update(array($this, '__callbackBeforeUpdate'));
		
		$crud->unset_read_fields('added_by', 'added_date', 'updated_by', 'updated_date', 'action_microtime');
		$output = $crud->render();
		$data = array('page_title'=> 'Market Category', 'content_view'=>'setup/setting');
		$outputData = array_merge((array)$output, $data);
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($outputData);
	}
	
	//7. Market Place
	function market_place(){
		$actions = checkUserPermission('setup/market_place', $this->uri->segment(3));
		if(!empty($this->uri->segment(3)) && ($this->uri->segment(3) == 'add' || $this->uri->segment(3) == 'edit' || $this->uri->segment(3) == 'view')){redirect(site_url('setup/market_place'));}
		$crud = new grocery_CRUD();
		$crud->set_theme('bootstrap');
		$crud->unset_jquery();
		$crud->unset_bootstrap();
		$crud->unset_common_search();					
		$crud->unset_delete();
		$crud->unset_read();
		
		if(!in_array('add', $actions)){
			$crud->unset_add();
		}else{
			$crud->set_add_url_path(site_url('setup/m_places/add'));
			$crud->set_add_button_class('btn btn-primary loadActionForm');
		}
		
		if(!in_array('edit', $actions)){
			$crud->unset_edit();
		}else{
			$crud->set_edit_url_path(site_url('setup/m_places/edit'));
			$crud->set_edit_button_class('btn btn-default loadActionForm');
		}
		
		/*
		if(!in_array('read', $actions)){
			$crud->unset_read();
		}else{
			$crud->set_read_url_path(site_url('setup/view_m_places'));
			$crud->set_read_button_class('loadActionForm');
		}
		*/
		
		if(!in_array('export', $actions)){
			$crud->unset_export();
		}
		if(!in_array('print', $actions)){
			$crud->unset_print();
		}
		
		$crud->add_bulk_action('Active', site_url('bulk_action/action/active'), '', 'fa fa-check', 'status');
		$crud->add_bulk_action('Inactive', site_url('bulk_action/action/inactive'), '', 'fa fa-ban', 'status');
		
		if(in_array('delete', $actions)){
			$crud->add_action('Delete', 'triggerBulkDelete text-danger', '', 'fa fa-trash', array($this, '__callbackDeleteActionButton'), 'dialogbox');
			$crud->add_bulk_action('Delete', site_url('bulk_action/action/mark_delete'), ' text-danger', 'fa fa-trash', 'status');		
		}
		
		/*
		if(in_array('lock', $actions)){
			$crud->add_action('Lock Data', 'triggerBulkDelete text-danger','','fa fa-lock', array($this,'__callbackLockActionButton'), 'dialogbox');
			$crud->add_bulk_action('Lock Data', site_url('bulk_action/action/lock'), ' text-danger', 'fa fa-lock', 'editable');		
		}
		*/	
		
		$crud->set_subject('Market Place');
		$crud->set_table(MARKET_PLACE);
		$crud->set_relation('market_type', MARKET_TYPE, 'name');
		$crud->set_relation('market_category', MARKET_CATEGORY, 'name');
		$crud->where(array(MARKET_PLACE.'.status !='=>'Deleted', MARKET_PLACE.'.editable'=>'Unlock'));
		
		$crud->columns( 'name', 'code', 'market_type', 'market_category', 'status');	
		$crud->fields('market_type', 'market_category', 'name', 'code', 'status', 'added_by', 'added_date', 'updated_by', 'updated_date', 'action_microtime');
		$crud->required_fields('market_type', 'market_category', 'name', 'status');
		
		/*
		$crud->unique_fields(array('code'));
		$crud->field_type('added_by','invisible');
		$crud->field_type('added_date','invisible');
		$crud->field_type('updated_by','invisible');
		$crud->field_type('updated_date','invisible');
		$crud->field_type('action_microtime','invisible');
		$crud->callback_before_insert(array($this, '__callbackBeforeInsert'));
 		$crud->callback_before_update(array($this, '__callbackBeforeUpdate'));
		*/
		
		$crud->unset_read_fields('added_by', 'added_date', 'updated_by', 'updated_date', 'action_microtime');
		$output = $crud->render();
		$data = array('page_title'=> 'Sale Markets', 'content_view'=>'setup/setting');
		$outputData = array_merge((array)$output, $data);
		$this->template->set('document_title', $data['page_title']);
		$this->template->set('scriptsrc', array(site_url('setup/assets/js/setup.js')));
		$this->template->layout($outputData);
	}
	
	//Add, Edit, production
	public function m_places($action_mode = 'add', $id = NULL){
		$data['market_types'] = $this->SM->get_market_types();
		$data['market_category'] = $this->SM->get_market_category();
		$data['page_title'] = 'Add Market Place';
		$data['action_mode'] = $action_mode;
		$data['form_action'] = site_url('setup/m_places/'.$action_mode);
		$data['m_places_data'] = array( 'ID' 				=> '',
										'market_type' 		=> '',
										'market_category' 	=> '',
										'name'				=> '',
										'code' 				=> '',
										'status' 			=> '',
										);
		$form_valid = $this->__setFormRules('m_places_validation');
		if($form_valid){
			$post 			= $this->input->post();
			$action_mode 	= $post['action_mode'];
			$prepData = array('market_type' 		=> $post['market_type'],
							  'market_category' 	=> $post['market_category'],
							  'name'				=> $post['name'],
							  'code' 				=> strtoupper(trim($post['code'])),
							  'status' 				=> $post['status'],
							  'added_by'			=> $this->userID,
							  'added_date'			=> get_datetime('Y-m-d H:i:s'),
							  'action_microtime'	=> microtime(true), 'source'=>SOURCE
							  );
			
			if($id != NULL && $action_mode=='edit'){
				//UPDATE				
				unset($prepData['added_date'], $prepData['added_by']);
				$prepData['updated_date'] = get_datetime('Y-m-d H:i:s');
				$prepData['updated_by'] = $this->userID;
				$result = $this->db->where('ID', $id)->update(MARKET_PLACE, $prepData);
				if($result){
					$data['action_mode'] = 'edit';
					$data['form_action'] = site_url('setup/m_places/'.$action_mode.'/'.$id);
					$data['status'] = 'success';
					$data['msg'] = 'Data updated successfully.';
				}else{
					$data['status'] = 'danger';
					$data['msg'] = 'Failed to update. Please try again.';
				}
			}elseif($action_mode=='add'){
				//iNSERT
				$result = $this->db->insert(MARKET_PLACE, $prepData);
				$id = $this->db->insert_id(); //Primary fisher id
				if($id){
					$data['action_mode'] = 'edit';
					$data['form_action'] = site_url('setup/m_places/'.$action_mode.'/'.$id);
					$data['status'] = 'success';
					$data['msg'] = 'Data inserted successfully.';
				}else{
					$data['status'] = 'danger';
					$data['msg'] = 'Failed to insert. Please try again.';
				}
			}
		}else{
			$data['status'] = 'error';
			$data['msg'] = validation_errors();
		}
		
		if($id != NULL){
			$data['form_action'] = site_url('setup/m_places/'.$action_mode.'/'.$id);
			$data['page_title'] = 'Edit Market Place';
			$data['action_mode'] = 'edit';
			$m_places_data = $this->SM->get_mp_data($id);
			$data['m_places_data'] = $m_places_data[0];
		}
		$data['scriptsrc'] =  array(site_url('setup/assets/js/m_places_v.js'));
		$data['setup_form'] = $this->load->view('setup/add_m_places_v', $data, true);
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}
		
	function __callbackBeforeInsert($post_array){
		$post_array['added_by'] = $this->userID;
		$post_array['added_date'] = get_datetime('Y-m-d H:i:s');
		$post_array['action_microtime'] = microtime(true);
		return $post_array;
	}
	
	function __callbackBeforeUpdate($post_array){
		$post_array['updated_by'] = $this->userID;
		$post_array['updated_date'] = get_datetime('Y-m-d H:i:s');
		$post_array['action_microtime'] = microtime(true);
		return $post_array;
	}
	
	function __callbackDeleteActionButton($primary_key, $row){ 
		return site_url('bulk_action/action/mark_delete');
	}
	
	function __callbackLockActionButton($primary_key, $row){ 
		return site_url('bulk_action/action/lock');
	}
	
	public function ajax_get_market_category(){
		$data = array('status'=>'danger', 'msg'=>'No data found', 'data'=>'<option value="">No data found</option>');
		$market_type = $this->input->post('market_type');
		if(!empty($market_type)){
			$result = $this->SM->get_market_category($market_type);
			$option = '<option value="">Select Category</option>';
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
	
	public function ajax_get_market_places(){
		$data = array('status'=>'danger', 'msg'=>'No data found', 'data'=>'<option value="">No data found</option>');
		$market_type = $this->input->post('market_type');
		if(!empty($market_type)){
			$result = $this->SM->get_mp_data('', $market_type);
			//printr($result);
			$option = '<option value="">Select Market</option>';
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
	
	//Formvalidation callback
	function validateUniqueCode(){
		$data = FALSE;
		$code = $this->input->post('code');
		$client_id = $this->input->post('client_id');
		if(!empty($code)){
			$action_mode = $this->input->post('action_mode');
			if($action_mode == 'edit'){
				$this->db->where('ID !=', $client_id, false);			
			}
			$this->db->where('code', $code);
			$result = $this->db->count_all_results(CLIENT);
			if($result == 0){
				$data = TRUE;
			}else{
				$this->form_validation->set_message('validateUniqueCode', 'Code is already used, Please provide different code.');
			}
		}else{
			$data = TRUE;
		}
		return $data;
	}
	
	//Formvalidation callback
	function validateUniqueContNo(){
		$data = FALSE;
		$contact_number = $this->input->post('contact_number');
		$action_mode = $this->input->post('action_mode');
		if($contact_number){
			if($action_mode == 'edit'){
				$client_id = $this->input->post('client_id');
				$this->db->where('ID !=', $client_id, false);			
			}
			$this->db->where('contact_number', $contact_number);
			$result = $this->db->count_all_results(CLIENT);
			if($result == 0){
				$data = TRUE;
			}else{
				$this->form_validation->set_message('validateUniqueContNo', 'Contact Number is already used, Please provide different Contact Number.');
			}
		}else{
			$this->form_validation->set_message('validateUniqueContNo', 'Contact Number is required.');
		}
		return $data;
	}
	
	//Formvalidation callback
	function validateUniqueMPCode(){
		$data 		= FALSE;
		$code 		= $this->input->post('code');
		$market_id 	= $this->input->post('market_id');
		if($code){
			$action_mode = $this->input->post('action_mode');
			if($action_mode == 'edit'){
				$this->db->where('ID !=', $market_id, false);			
			}
			$this->db->where('code', $code);
			$result = $this->db->count_all_results(MARKET_PLACE);
			if($result == 0){
				$data = TRUE;
			}else{
				$this->form_validation->set_message('validateUniqueMPCode', 'Code is already used, Please provide different code.');
			}
		}else{
			$this->form_validation->set_message('validateUniqueMPCode', 'The Code field is required.');
		}
		return $data;
	}
	
	private function __setFormRules($setRulesFor = ''){
		switch($setRulesFor){			
			case'add_client':
				$this->form_validation->set_rules('code', 'Code', 'trim|callback_validateUniqueCode');
				$this->form_validation->set_rules('company_name', 'Customer Name', 'trim|required|min_length[2]');
				$this->form_validation->set_rules('market_type', 'Market Type', 'trim|required|integer|min_length[1]');
				//$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
				$this->form_validation->set_rules('contact_number', 'Contact Number', 'trim|required|integer|min_length[10]|max_length[12]|callback_validateUniqueContNo');
				$this->form_validation->set_rules('city', 'City', 'trim|required');
				//$this->form_validation->set_rules('state', 'State', 'trim|required');
				//$this->form_validation->set_rules('country', 'Country', 'trim|required');
				$this->form_validation->set_rules('status', 'Status', 'trim|required|min_length[2]|in_list[Active,Inactive]', array('in_list'=>'Invalid status field.'));
				$this->form_validation->set_rules('action_mode', 'Mode', 'trim|required|in_list[add,edit]', array('in_list'=>'Invalid Request. Please reload the page and try again.'));
			break;
			
			case'm_places_validation':
				$this->form_validation->set_rules('market_type', 'Market Type', 'trim|required|integer|greater_than[0]');
				$this->form_validation->set_rules('market_category', 'Market Category', 'trim|required|integer|greater_than[0]');
				$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]');	
				$this->form_validation->set_rules('code', 'Code', 'trim|required|alpha_numeric|min_length[2]|max_length[8]|callback_validateUniqueMPCode',array('alpha_numeric'=>'Blank Space is not allowed in code field.'));
				$this->form_validation->set_rules('status', 'Status', 'trim|required|in_list[Active,Inactive]');				
			break;
		}
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert_msg margin-5 padding-5"><button data-dismiss="alert" class="close">×</button><i class="fa fa-times-circle"></i> ', '</div>');
		return $this->form_validation->run($this);
	}
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Setup extends MY_Controller {
	var $userID, $actions;
	
	public function __construct(){
		parent::__construct();
		$this->userID = checkUserLogin();
		$this->load->model('setup_model', 'SM');
    }
	
	function index(){
		redirect('trash/setup/group_type');
	}
	
	function __callbackDeleteActionButton($primary_key, $row){ 
		return site_url('bulk_action/action/delete');
	}
	
	function __callbackRestoreActionButton($primary_key, $row){ 
		return site_url('bulk_action/action/active');
	}
	
	//Listing, Add, Edit Group Type
	function group_type(){
		$actions = checkUserPermission('trash/setup/group_type', $this->uri->segment(4));
		
		$crud = new grocery_CRUD();
		$crud->set_theme('bootstrap');
		$crud->unset_jquery();
		$crud->unset_bootstrap();
		$crud->unset_common_search();					
		
		$crud->unset_read();		
		$crud->unset_edit();		
		$crud->unset_add();		
		$crud->unset_delete();
		$crud->unset_export();
		$crud->unset_print();
		
		if(in_array('delete', $actions)){
			$crud->add_action('Delete', 'triggerBulkDelete text-danger', '', 'fa fa-times', array($this, '__callbackDeleteActionButton'), 'dialogbox');
			$crud->add_bulk_action('Delete', site_url('bulk_action/action/delete'), ' text-danger','fa fa-times', '');
		}
		if(in_array('restore', $actions)){
			$crud->add_action('Restore', 'triggerBulkDelete', '', 'fa fa-undo', array($this, '__callbackRestoreActionButton'), 'dialogbox');
			$crud->add_bulk_action('Restore', site_url('bulk_action/action/active'), '','fa fa-undo', 'status');
		}
		
		
		$crud->set_subject('Group Type');
		$crud->set_table(MAINGROUP_TYPE);		
		$crud->where(array(MAINGROUP_TYPE.'.status'=>'Deleted'));
		$crud->columns('type_name', 'status', 'editable');	
		$crud->fields('type_name', 'status', 'added_by', 'added_date', 'updated_by', 'updated_date', 'action_microtime');	
		$crud->required_fields('type_name', 'status');
		
		$crud->field_type('added_by','invisible');
		$crud->field_type('added_date','invisible');
		$crud->field_type('updated_by','invisible');
		$crud->field_type('updated_date','invisible');
		$crud->field_type('action_microtime','invisible');
		
		$crud->callback_before_insert(array($this,'__callbackBeforeInsert'));
 		$crud->callback_before_update(array($this,'__callbackBeforeUpdate'));
		
		$output = $crud->render();
		$data = array('page_title'=> 'Group Type', 'content_view'=>'setup/setting');
		$outputData = array_merge((array)$output, $data);
		$this->template->set('document_title', 'Group Type');
		$this->template->layout($outputData);
	}
}
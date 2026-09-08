<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->userID = checkUserLogin();
		$this->load->language('dashboard', $this->language);		
	}
	
	function index()
	{
		$data['page_title'] = lang('page_title');
		$data['breadcrumb'] = lang('page_title');
		$data['content_view'] = 'dashboard/dashboard_v';
		
		$this->template->set('document_title', lang('document_title'));
		$this->template->layout($data);
	}
}

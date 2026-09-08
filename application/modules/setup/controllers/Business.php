<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Business extends MY_Controller {
	var $userID;
	/**
	 * Function		:	__construct
	 * Author		: 	Tarun Malviya
	 * Author Email	: 	tarun.malviya@techlect.com
	 * Params		: 	NULL
	 * Return		: 	NULL
	 * Description	: 	This function loads model, helpers and libraries inside the whole controller.
 	**/
	
	public function __construct(){
		parent::__construct();		
		$this->userID = checkUserLogin();		
		$this->load->helper('location');		
		$this->load->model('business_model','BM');
		$this->load->model('location_staff_model','LSM');
		$this->load->model('services_model','SM');
		// Common language file 
		$this->load->language('setup/common', $this->language);
    } 
		
	public function index(){
		$this->details();
	} 
	
	public function details(){	
				
		$userGroup = loginUserInfo('group_name');
		
		if($userGroup == 'Teacher'){
			$this->load->language('setup/business_details_teacher', $this->language ); 
		} else {
			$this->load->language('setup/business_details', $this->language ); 
		}

		$validation = $this->__setFormRules('updateBusinessDetails');
		if($validation){
			$post = $this->input->post();				
			$updResp = $this->BM->update_details( $post , $_FILES );
			if($updResp){
				$this->messageci->set( lang('update_success_message'), 'success') ;
				redirect(site_url('setup/business/details'));
			} else {
				$this->messageci->set( lang('update_error_message'), 'error') ;
				redirect(site_url('setup/business/details'));
			}
		}
		
		$this->breadcrumbs->push(lang('page_title'), 'setup/business/details');
		$data['page_title'] = $this->breadcrumbs->show();
		
		$data['breadcrumb'] = '<div class="breadcrumb breadcrumb-button">
								<button type="button" class="btn btn-success padding-dr" onclick="$(\'form#bussiness_details\').submit();">'
								.lang('save_button').'</button>
							   </div>';	
		
		$data['content_view'] = 'setup/business/business_details_v';
		// COACHINGPREFIX	
		$customeID  = loginCompanyInfo( 'business_id' );	
		
		$data['details'] = $this->BM->get_business_details($customeID);				
		$data['allcourses'] = $this->BM->get_all_courses();
		$data['business_courses'] = $this->BM->get_business_courses(loginCompanyInfo('business_id'));			

		$scriptSrc = array(
							base_url('assets/plugins/jquery-validation/dist/jquery.validate.js'),
							base_url('assets/plugins/select2/dist/js/select2.min.js')
						  );
		$styleSrc = array(	base_url('assets/modules/business/css/business_details.css'), base_url('assets/plugins/select2/dist/css/select2.min.css'));
		$script ='
				<script>
					jQuery(document).ready(function(){						
						$(".select2").select2({placeholder:"Select course"});
					});
				</script>
				';
		$this->template->set('stylesheet', $styleSrc);
		$this->template->set('scriptsrc', $scriptSrc);
		$this->template->set('scripts', $script);
		
		$this->template->set('document_title', lang('document_title'));
		$this->template->layout($data);
	}
	
	public function remove_logo(){
		$filename = $this->input->get('logo');
		$delResp = $this->BM->remove_business_logo($filename);
		if($delResp == 'success'){
			$this->messageci->set( lang('remove_success'), 'success') ;
			redirect(site_url('setup/business/details'));		
		} else {
			$this->messageci->set( lang('remove_error') , 'error') ;
			redirect(site_url('setup/business/details'));			
		}
	}
	
	public function batchs(){
		$crud = new grocery_CRUD();
		$crud->set_theme('bootstrap');
		$crud->unset_jquery();
		$crud->unset_bootstrap();		
		$crud->unset_print();
		$crud->unset_export();
		$crud->unset_common_search();
				
		$crud->set_add_url_path(site_url('setup/business/add_batch'));
		$crud->set_edit_url_path(site_url('setup/business/edit_batch'));		
			
		$crud->add_bulk_action('Active', site_url('setup/bulk_action/action/active'), '', 'clip-checkmark-circle', 'status');
		$crud->add_bulk_action('Inactive', site_url('setup/bulk_action/action/inactive'), '', 'clip-cancel-circle', 'status');
		
		$crud->set_subject('Batchs');
		$hourTable = WORKING_HOURS;				
	    $crud->set_table($hourTable);			
        $crud->columns( 'branch_id','course','break_name','day_name','from_time','to_time', 'created_date' );		
		$crud->set_relation('branch_id', BUSINESS_LOCATIONS, 'branch_name', array('business_id'=>loginCompanyInfo('business_id')));		
		
		$where = array($hourTable. '.business_id' => loginCompanyInfo('business_id'),$hourTable.'.day_status' => 'Break');		
		$crud->where($where);
		
		$crud->display_as('branch_id','location');		
		$crud->display_as('break_name','Batch');
		
		$output = $crud->render();		
		
		$data['page_title'] = 'Batchs';
		$data['content_view'] = 'setup/setting';
		$this->template->set('document_title', 'Batchs');
		$outputData = array_merge((array) $output , $data);
		$this->template->layout($outputData);		
	}
	
	public function add_batch(){
		$this->load->language('setup/business_locations', $this->language);
		
		$batchData = $this->input->post();
		if(!empty($batchData)){
			$batchresp = $this->BM->addBatchData($batchData,$this->timezone);
		}
			
		$data['page_title']   = 'Add Batch';
		$data['content_view'] = 'setup/business/batchs/add_batch_v';
		$this->template->set('document_title', 'Add Batch');
		$data['breadcrumb'] = '<div class="breadcrumb breadcrumb-button">
								<a href="'.site_url('setup/business/batchs').'" class="btn btn-default padding-dr">'.lang('cancel_button').'</a>
								<button type="button" class="btn btn-success padding-dr" onclick="$(\'form#addBatch\').submit();">'
								.lang('save_button').'</button>
					   		  </div>';	
		$json_course = $this->BM->get_business_courses(loginCompanyInfo('business_id'));		
		if(!empty($json_course)){
			$data['json_course']  = json_encode($json_course);
		} else {
			$data['json_course']  = json_encode(array());
		}
		$locations = $this->BM->get_business_locations(loginCompanyInfo('business_id'));
		if(!empty($locations)){
			$data['json_location']  = json_encode($locations);
		} else {
			$data['json_location']  = json_encode(array());
		}
		$data['courses'] = $json_course;
		$this->template->set('scriptsrc', array(base_url('assets/modules/setup/batch.js')));

		$this->template->layout($data);		
	}
	
	public function edit_batch(){
		$batch_id = $this->uri->segment(4);
		
		if(empty($batch_id)){
			$this->messageci->set('Select batch to edit.', 'error') ;
			redirect(site_url('setup/business/batchs'));
		}
		
		$editBatchData = $this->input->post();
		
		if(!empty($editBatchData)){			
			$batchresp = $this->BM->updateBatchData($editBatchData,$this->timezone);
			if($batchresp){
				$this->messageci->set(lang('update_success'), 'success') ;
				redirect(site_url('setup/business/batchs'));
			} else {
				$this->messageci->set(lang('update_error'), 'error') ;
				redirect(site_url('setup/business/edit_batch/'.$batch_id));
			}
		}
		
		$this->load->language('setup/business_locations', $this->language);
		$data['page_title']   = 'Edit Batch';
		$data['content_view'] = 'setup/business/batchs/edit_batch_v';
		$this->template->set('document_title', 'Edit Batch');
		$data['breadcrumb'] = '<div class="breadcrumb breadcrumb-button">
								<a href="'.site_url('setup/business/batchs').'" class="btn btn-default padding-dr">'.lang('cancel_button').'</a>
								<button type="button" class="btn btn-success padding-dr" onclick="$(\'form#editBatch\').submit();">'
								.lang('save_button').'</button>
					   		  </div>';	
		$data['batch_id'] = $batch_id;
		$data['batch_data'] = $this->BM->getBatchData(loginCompanyInfo('business_id'),$batch_id);
		$json_course = $this->BM->get_business_courses(loginCompanyInfo('business_id'));		
		if(!empty($json_course)){
			$data['json_course']  = json_encode($json_course);
		} else {
			$data['json_course']  = json_encode(array());
		}
		$locations = $this->BM->get_business_locations(loginCompanyInfo('business_id'));
		if(!empty($locations)){
			$data['json_location']  = json_encode($locations);
		} else {
			$data['json_location']  = json_encode(array());
		}
		$data['courses'] = $json_course;
		$data['locations'] = $locations;
		$data['locationHourData'] = $this->db->get_where(WORKING_HOURS, array('business_id'=>loginCompanyInfo('business_id'),'hour_id'=>$batch_id))->result_array();
		
		
		$this->template->set('scriptsrc', array(base_url('assets/modules/setup/batch.js')));
		
		$this->template->layout($data);
	}
		
	public function online_booking(){
		$this->load->language('setup/business_online_booking', $this->language);
		$formValidation = $this->__setFormRules('online_booking');
		if($formValidation){
			$postData = $this->input->post();			
			$updateQuery = $this->BM->updateOnlineBooking($postData);			
			if($updateQuery){
				$message = "Settings saved successfully";
				$class = "success";
				log_message('debug', 'Booking setting have been updated successfully by user '.loginUserInfo('business_id'));
			}else{
				$message = "Some error while save setting";
				$class = "error";
				log_message('error', 'Booking setting failed to update by user '.loginUserInfo('business_id'));
			}
			$this->messageci->set($message, $class);
		}
		
		$onlineSettingData = $this->db->get_where($this->db->dbprefix.'appointment_online_booking_settings', array('business_id'=>loginUserInfo('business_id')))->result_array();
		$data['onlineSettingData'] = !empty($onlineSettingData) ? $onlineSettingData[0] : array();
		
		
		$this->breadcrumbs->push(lang('page_title'), 'setup/business/online_booking');
		$data['page_title'] = $this->breadcrumbs->show();
		
		$data['breadcrumb'] = '<div class="breadcrumb breadcrumb-button">
					<button type="button" class="btn btn-success padding-dr" onclick="$(\'form#booking_settings\').submit();">Save</button>
								</div>';
		
		$data['content_view'] = 'setup/business/online_booking_v';
		
		$this->template->set('stylesheet', array(base_url('assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css'),
												base_url('assets/modules/business/css/business_details.css')));		
		
		$this->template->set('scriptsrc', array(base_url('assets/plugins/jquery-inputlimiter/jquery.inputlimiter.1.3.1.min.js'),
												base_url('assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js'),
												base_url('assets/modules/business/js/online_booking.js')));	
												
		$this->template->set('document_title', lang('document_title'));
		$this->template->layout($data);
	}
	
	public function calendar_settings(){
		
		$this->breadcrumbs->push('Calendar settings', 'setup/business/calendar_settings');
		$data['page_title'] = $this->breadcrumbs->show();
		
		$data['breadcrumb'] = '<div class="breadcrumb breadcrumb-button">
					<button type="button" class="btn btn-success padding-dr" onclick="$(\'form#booking_settings\').submit();">Save</button>
								</div>';
								
		$data['content_view'] = 'setup/business/calendar_settings';		
		$this->template->set('document_title', lang('document_title'));
		$this->template->layout($data);
	}
		
	public function locations(){
		$this->load->language('setup/business_locations', $this->language);
		
		$this->db->where(array('business_id'=>loginUserInfo('business_id')));
		$data['locationData'] = $this->db->get(BUSINESS_LOCATIONS)->result_array();
		
		$this->breadcrumbs->push(lang('page_title'), 'setup/business/locations');
		$data['page_title'] = $this->breadcrumbs->show();
		
		$data['breadcrumb'] = '<div class="breadcrumb breadcrumb-button">
								<a href="'.site_url('setup/business/add_location').'" class="btn btn-success padding-dr"> Add location </a>
							   </div>';	
		
		$data['content_view'] = 'setup/business/locations_v';
		$this->template->set('document_title', lang('document_title'));
		$this->template->layout($data);
	}
	
	public function add_location(){
		// specific 
		$this->load->language('setup/business_locations', $this->language);

		$formValidation = $this->__setFormRules('add_location');
		if($formValidation){
			//$this->LSM->addLocation();
			$business_id  = loginCompanyInfo('business_id');
			$prepareData = array();
			$postData = $this->input->post();
			$prepareLocationData = array(
									 'business_id'			=> $business_id,
									 'branch_name'			=> $postData['branch_name'],						 
									 'phone_number'			=> implode(',',$postData['telephone']), 
									 'address'				=> $postData['address_line1'],									
									 'pincode'				=> $postData['pincode'],									
									 'city'					=> $postData['city'],									
									 'state'				=> $postData['state'],									
									 'created_date'			=> date('Y-m-d H:i:s'),									 
									 'status'				=> 'Active'
									 );
			
			$this->db->insert(BUSINESS_LOCATIONS, $prepareLocationData);
			$location_id = $this->db->insert_id();
			
			if($location_id){
				$weekdays = weekdays();
				for($w=0; $w<count($weekdays); $w++){
					$dayname = $weekdays[$w];
					
					if(isset($postData[$dayname.'_active'])){
						$day = $postData[$dayname];						
						$breakArray = array();
						if(!empty($day)){
							$from_time 	= $day['from_hour'].':'.$day['from_min'].' '.$day['from_ampm'];
							$to_time 	= $day['to_hour'].':'.$day['to_min'].' '.$day['to_ampm'];
							
							//Prepare day data start
							$prepareData[] = array('business_id'	=> $business_id,
												   'branch_id'		=> $location_id,
												   'staff_id'		=> '3',
												   'break_name'		=> '',
												   'course'			=> '',
												   'day_name'		=> $dayname,
												   'from_time'		=> date('H:i:s',strtotime($from_time)),
												   'to_time'		=> date('H:i:s',strtotime($to_time)),
												   'day_status'		=> 'Available',
												   'created_date'	=> date('Y-m-d H:i:s'),
												   'updated_date'	=> date('Y-m-d H:i:s'),
												   'time_zone'		=> $this->timezone,
												   );
							
							//Prepare day data end
							
							//Prepare break data start
							if(isset($day['break'])){
								$break = $day['break'];
								//printr($break);
								for($i=0; $i<count($break['name']); $i++){
									$break_from = $break['from_hour'][$i].':'.$break['from_min'][$i].' '.$break['from_ampm'][$i];
									$break_to 	= $break['to_hour'][$i].':'.$break['to_min'][$i].' '.$break['to_ampm'][$i];
									$course = $break['course'][$i];
									$prepareData[] = array('business_id'	=> $business_id,
												   		   'branch_id'		=> $location_id,
														   'staff_id'		=> '3',
														   'break_name'		=> $break['name'][$i],
														   'course'			=> $course,
														   'day_name'		=> $dayname,
														   'from_time'		=> date('H:i:s',strtotime($break_from)),
														   'to_time'		=> date('H:i:s',strtotime($break_to)),
														   'day_status'		=> 'Break',
														   'created_date'	=> date('Y-m-d H:i:s'),
														   'updated_date'	=> date('Y-m-d H:i:s'),
														   'time_zone'		=> $this->timezone,
														   );
									
									
								}
							}
							//Prepare break data end
						}
					}else{
						$prepareData[] = array('business_id'	=> $business_id,
											   'branch_id'		=> $location_id,
											   'staff_id'		=> '3',
											   'break_name'		=> '',
											   'course'			=> '',
											   'day_name'		=> $dayname,
											   'from_time'		=> '',
											   'to_time'		=> '',
											   'day_status'		=> 'Busy',
											   'created_date'	=> date('Y-m-d H:i:s'),
											   'updated_date'	=> date('Y-m-d H:i:s'),
											   'time_zone'		=> $this->timezone,
											  );
					}
				}
				
				if(!empty($prepareData)){
					//no error in days breaks
					$this->db->insert_batch(WORKING_HOURS, $prepareData);					
					$this->messageci->set(lang('add_success'), 'success') ;
					redirect(site_url('setup/business/locations'));
				}else{
					//error in days breaks
					$this->db->delete(BUSINESS_LOCATIONS, array('location_id' => $location_id));
					//print_r($isFormOK);print_r($isBreakOk);
					//redirect(site_url('setup/business/locations'));
					$this->messageci->set(lang('add_error'), 'error') ;
				}
			}
		
		}
				
		$this->breadcrumbs->push(lang('page_title'), 'setup/business/locations');
		$this->breadcrumbs->push('Add location', 'setup/business/add_location');
		$data['page_title'] = $this->breadcrumbs->show();
		
		$data['breadcrumb'] = '<div class="breadcrumb breadcrumb-button">
								<a href="'.site_url('setup/business/locations').'" class="btn btn-default padding-dr">'.lang('cancel_button').'</a>
							    <button type="button" class="btn btn-success padding-dr" onclick="$(\'form#Locations_step_first\').submit();">'
								.lang('save_button').'</button>
							   </div>';	
		
		$data['content_view'] = 'setup/business/location_add_v';
		$json_course = $this->BM->get_business_courses(loginCompanyInfo('business_id'));
				
		if(!empty($json_course)){
			$data['json_course']  = json_encode($json_course);
		} else {
			$data['json_course']  = json_encode(array());
		}
		$this->template->set('scriptsrc', array(base_url('assets/modules/setup/location.js')));
		$this->template->set('document_title', lang('document_title'));
		$this->template->layout($data);
	}
	
	public function edit_location($location_id=NULL){
		
		// specific 
		$this->load->language('setup/business_locations', $this->language);
		
		if($location_id==NULL){
			redirect('setup/business/locations');
		}
		$formValidation = $this->__setFormRules('edit_location');
		if($formValidation){			
			//$this->LSM->updateLocation();
			
			$business_id  = loginCompanyInfo('business_id');
			$prepareData = array();
			$postData = $this->input->post();
			
			$prepareLocationData = array(
									 'business_id'			=> $business_id,
									 'branch_name'			=> $postData['branch_name'],						 
									 'phone_number'			=> implode(',',$postData['telephone']), 
									 'address'				=> $postData['address_line1'],									
									 'pincode'				=> $postData['pincode'],									
									 'city'					=> $postData['city'],									
									 'state'				=> $postData['state'],									
									 'created_date'			=> date('Y-m-d H:i:s'),									 
									 'status'				=> 'Active'
									 );
			$updateLocation = $this->db->where(array('business_id'=>$business_id,'branch_id'=>$location_id))->update(BUSINESS_LOCATIONS, $prepareLocationData);
			
			if($updateLocation){
				$weekdays = weekdays();
				$this->db->delete(WORKING_HOURS, array('business_id'=>$business_id,'branch_id'=>$location_id));
				for($w=0; $w<count($weekdays); $w++){
					$day_name = $weekdays[$w];
					if(isset($postData[$day_name.'_active'])){
						$day = $postData[$day_name];
						$breakArray = array();
						if(!empty($day)){
							$from_time 	= $day['from_hour'].':'.$day['from_min'].' '.$day['from_ampm']; //hh:mm AM/PM (12 hour format)
							$to_time 	= $day['to_hour'].':'.$day['to_min'].' '.$day['to_ampm']; //hh:mm AM/PM (12 hour format)
							
							//Prepare day data start
							$prepareData[] = array('business_id'	=> $business_id,
												   'branch_id'		=> $location_id,
												   'staff_id'		=> '3',
												   'break_name'		=> '',
												   'course'			=> '',
												   'day_name'		=> $day_name,
												   'from_time'		=> date('H:i:s',strtotime($from_time)),
												   'to_time'		=> date('H:i:s',strtotime($to_time)),
												   'day_status'		=> 'Available',
												   'created_date'	=> date('Y-m-d H:i:s'),
												   'updated_date'	=> date('Y-m-d H:i:s'),
												   'time_zone'		=> $this->timezone,
												   );
							//Prepare day data start
							
							//Prepare break data start
							if(isset($day['break'])){
								$break = $day['break'];
								for($i=0; $i<count($break['name']); $i++){
									$break_from = $break['from_hour'][$i].':'.$break['from_min'][$i].' '.$break['from_ampm'][$i];
									$break_to 	= $break['to_hour'][$i].':'.$break['to_min'][$i].' '.$break['to_ampm'][$i];
									$course = $break['course'][$i];
									
									$prepareData[] = array('business_id'	=> $business_id,
														   'branch_id'		=> $location_id,
														   'staff_id'		=> '3',
														   'break_name'		=> $break['name'][$i],
														   'course'			=> $course,
														   'day_name'		=> $day_name,
														   'from_time'		=> date('H:i:s',strtotime($break_from)),
														   'to_time'		=> date('H:i:s',strtotime($break_to)),
														   'day_status'		=> 'Break',
														   'created_date'	=> date('Y-m-d H:i:s'),
														   'updated_date'	=> date('Y-m-d H:i:s'),
														   'time_zone'		=> $this->timezone,
														   );
									
								}
							}
							//Prepare break data end
						}
					}else{
						$prepareData[] = array('business_id'	=> $business_id,
											   'branch_id'		=> $location_id,
											   'staff_id'		=> '3',
											   'break_name'		=> '',
											   'course'			=> '',
											   'day_name'		=> $day_name,
											   'from_time'		=> '',
											   'to_time'		=> '',
											   'day_status'		=> 'Busy',
											   'created_date'	=> date('Y-m-d H:i:s'),
											   'updated_date'	=> date('Y-m-d H:i:s'),
											   'time_zone'		=> $this->timezone,
											  );
					}
				}
				
				$insertLocationHours = $this->db->insert_batch(WORKING_HOURS, $prepareData);

				if($insertLocationHours){
					$this->messageci->set(lang('update_success'), 'success') ;
				}else{
					$this->messageci->set(lang('update_error'), 'error') ;
				}
			}else{
				$this->messageci->set(lang('update_error'), 'error') ;
			}
			redirect(site_url('setup/business/locations'));
			
		}	
		
		$json_course = $this->BM->get_business_courses(loginCompanyInfo('business_id'));		
		if(!empty($json_course)){
			$data['json_course']  = json_encode($json_course);
		} else {
			$data['json_course']  = json_encode(array());
		}
		
		$data['data_course'] = $json_course;

		$data['locationData'] = array();
		$data['locationHourData'] = array();
		
		$locationData = $this->db->get_where(BUSINESS_LOCATIONS, array('business_id'=>loginCompanyInfo('business_id'),'branch_id'=>$location_id))->result_array();
		if(!empty($locationData)){
			$data['locationData'] = $locationData[0];
			$data['locationHourData'] = $this->db->get_where(WORKING_HOURS, array('business_id'=>loginCompanyInfo('business_id'),'branch_id'=>$location_id))->result_array();
		}
				
		$this->breadcrumbs->push(lang('page_title'), 'setup/business/locations');
		$this->breadcrumbs->push('Edit location', 'setup/business/edit_location');
		$data['page_title'] = $this->breadcrumbs->show();
		
		$data['breadcrumb'] = '<div class="breadcrumb breadcrumb-button">
								<a href="'.site_url('setup/business/locations').'" class="btn btn-default padding-dr">'.lang('cancel_button').'</a>
							    <button type="button" class="btn btn-success padding-dr" onclick="$(\'form#Locations_step_first\').submit();">'
								.lang('save_button').'</button>
							   </div>';	
		
		$data['content_view'] = 'setup/business/location_edit_v';
		
		$this->template->set('scriptsrc', array(base_url('assets/modules/setup/location.js')));
		$this->template->set('document_title', lang('document_title'));
		$this->template->layout($data);
	}
	
	public function delete_location($location_id=NULL){
		if($location_id==NULL){
			redirect('setup/business/locations');
		}
		$delete_bh = $this->db->delete(BUSINESS_LOCATIONS, array('business_id'=>loginUserInfo('business_id'),'location_id' => $location_id));
		$delete_bl = $this->db->delete(WORKING_HOURS, array('business_id'=>loginUserInfo('business_id'),'location_id' => $location_id));
		redirect(site_url('setup/business/locations'));
	}
	
	public function staff(){		
				
		$crud = new grocery_CRUD();
		$crud->set_theme('bootstrap');
		$crud->unset_jquery();
		$crud->unset_bootstrap();		
		$crud->unset_print();
		$crud->unset_export();
		$crud->unset_edit();
		$crud->unset_read();
		$crud->unset_delete();
		$crud->unset_common_search();
		
		$crud->set_add_url_path(site_url('setup/business/add_staff'));
	//	$crud->set_edit_url_path(site_url('setup/business/edit_staff'));		
			
		$crud->add_bulk_action('Active', site_url('setup/bulk_action/action/active'), '', 'clip-checkmark-circle', 'status');
		$crud->add_bulk_action('Inactive', site_url('setup/bulk_action/action/inactive'), '', 'clip-cancel-circle', 'status');
		
		$crud->set_subject('Staff Details');		
	  
	    $crud->set_table(ASSOCIATIONS);		
		
		$crud->set_model('grocery_crud_custom_query_model');
		$customQuery = "SELECT a.*, u.firstName, u.lastName, u.city, u.email, u.registerDate, u.phone_number FROM ".ASSOCIATIONS." a LEFT JOIN ".USERS." u ON a.association_to = u.user_id WHERE a.association_from = '".loginCompanyInfo('business_id')."' AND a.group_id = 7";						
		$crud->basic_model->set_custom_query($customQuery);		  		   
		$crud->order_by('a.association_id', 'DESC');		
	    $crud->columns( 'firstName','lastName','phone_number', 'city', 'status', 'registerDate' );		
		
		$crud->display_as('firstName','First Name');		
		$crud->display_as('lastName','Last Name');
		$crud->callback_column('registerDate', function($value,$row){		
			return date( 'M, d Y H:i:s', strtotime( $value ) );			
		});
		
		$crud->add_action('Edit', '', '','fa fa-pencil',array($this,'__changeEditUrl'));
		
		$output = $crud->render();		
        
		$data['content_view'] = 'setup/setting';
		$data['page_title'] = 'Staff Details';
		$data['breadcrumb'] = '<div class="breadcrumb breadcrumb-button"><a href="'.site_url('setup/business/staff').'" class="btn btn-primary padding-dr">Add staff</a></div>';
        $outputData = array_merge((array) $output , $data);
		$this->template->set('document_title', 'Staff Details');
		$this->template->layout($outputData);
	}
			
	public function add_staff(){
		$this->load->language('setup/business_staff', $this->language);
		$business_id  = loginCompanyInfo('business_id');
		$formValidation = $this->__setFormRules('add_staff');
		if($formValidation){
			$postData = $this->input->post();
			$dataResp  = $this->BM->addStaffData($postData);
			if(!empty($dataResp)){				
				$this->messageci->set(lang('add_success') , 'success' ) ;
				redirect(site_url('setup/business/staff'));
			}else{				
				$this->messageci->set( lang('add_error') , 'error' ) ;
     			redirect(site_url('setup/business/add_staff'));
			}
		}
		
		$this->breadcrumbs->push('Staff', 'setup/business/staff');
		$this->breadcrumbs->push('Add staff', 'setup/business/add_staff');
		$data['page_title'] = $this->breadcrumbs->show();
		
		$data['breadcrumb'] = '<div class="breadcrumb breadcrumb-button">
								<a href="'.site_url('setup/business/staff').'" class="btn btn-default padding-dr">'.lang('cancel_button').'</a>
							    <button type="button" class="btn btn-primary padding-dr" onclick="$(\'form#add_staff\').submit();">'
								.lang('save_button').'</button>
							   </div>';		
		
		$data['content_view'] = 'setup/business/staffs/add_staff_v';
		
        $this->template->set('stylesheet', array(base_url('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.css')));
		$this->template->set('scriptsrc', array(base_url('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.js')));
		$this->template->set('document_title', lang('document_title_add_staff'));
		$this->template->layout($data);
	}
	
	public function edit_staff(){
		$this->load->language('setup/business_staff', $this->language);
		$business_id  = loginCompanyInfo('business_id');
		$staff_id = $this->uri->segment(4);	

		$formValidation = $this->__setFormRules('edit_staff');
		if($formValidation){
			$postData = $this->input->post();
			$dataResp  = $this->BM->editStaffData($postData);
			if(!empty($dataResp)){				
				$this->messageci->set( lang('update_success'), 'success' ) ;
				redirect(site_url('setup/business/staff'));
			}else{				
				$this->messageci->set( lang('update_error') , 'error' ) ;
     			redirect(site_url('setup/business/edit_staff/'.$staff_id));
			}
		}
		
		$this->breadcrumbs->push('Staff', 'setup/business/staff');
		$this->breadcrumbs->push('Edit staff details', 'setup/business/edit_staff');
		$data['page_title'] = $this->breadcrumbs->show();
		$data['staff_details'] = $this->BM->get_staff_details($business_id, $staff_id );
		$data['staff_id'] = $staff_id;
		
		$data['breadcrumb'] = '<div class="breadcrumb breadcrumb-button">
								<a href="'.site_url('setup/business/staff').'" class="btn btn-default padding-dr">'.lang('cancel_button').'</a>
							    <button type="button" class="btn btn-primary padding-dr" onclick="$(\'form#add_staff\').submit();">'
								.lang('save_button').'</button>
							   </div>';
		
		$data['content_view'] = 'setup/business/staffs/edit_staff_v';
		
        $this->template->set('stylesheet', array(base_url('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.css')));
		$this->template->set('scriptsrc', array(base_url('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.js')));
		$this->template->set('document_title', 'Edit Staff');
		$this->template->layout($data);
	}
	
	public function staffrole(){		
		$crud = new grocery_CRUD();
		$crud->set_theme('bootstrap');
		$crud->unset_jquery();
		$crud->unset_bootstrap();		
		$crud->unset_print();
		$crud->unset_export();
		$crud->unset_common_search();	
	
		$crud->add_bulk_action('Active', site_url('setup/bulk_action/action/active'), '', 'clip-checkmark-circle', 'status');
		$crud->add_bulk_action('Inactive', site_url('setup/bulk_action/action/inactive'), '', 'clip-cancel-circle', 'status');
		
		$crud->set_subject('Staff Role');		
	    $crud->set_table(STAFF_GROUPS);
		$crud->where('business_id',loginCompanyInfo('business_id'));		
        $crud->columns( 'group_name', 'status', 'created_date' );
		
		$crud->unset_fields('manageable','created_date');
		$crud->field_type('business_id', 'hidden', loginCompanyInfo('business_id'));
		$crud->required_fields('group_name', 'status');
		
		$output = $crud->render();		
        
		$data['content_view'] = 'setup/setting';
		$data['page_title'] = 'Staff Group/Role';
		$data['breadcrumb'] = '<div class="breadcrumb breadcrumb-button"><a href="'.site_url('setup/business/add_staff').'" class="btn btn-primary padding-dr">Add staff</a></div>';
        $outputData = array_merge((array) $output , $data);
		$this->template->set('document_title', 'Staff Group/Role');
		$this->template->layout($outputData);
	}
	
	public function add_package(){
		$this->load->language('setup/business_staff', $this->language);
		$business_id  = loginCompanyInfo('business_id');
		$formValidation = $this->__setFormRules('add_staff');
		if($formValidation){
			$postData = $this->input->post();
			$dataResp  = $this->BM->addStaffData($postData);
			if(!empty($dataResp)){
				$this->messageci->set( lang('add_success'), 'success' ) ;
				redirect(site_url('setup/business/staff'));
			}else{
				$this->messageci->set( lang('add_error'), 'error' ) ;
     			redirect(site_url('setup/business/add_staff'));
			}
		}
		
		$this->breadcrumbs->push('Staff', 'setup/business/staff');
		$this->breadcrumbs->push('Add staff', 'setup/business/add_staff');
		$data['page_title'] = $this->breadcrumbs->show();
		
		$data['breadcrumb'] = '<div class="breadcrumb breadcrumb-button">
								<a href="'.site_url('setup/business/staff').'" class="btn btn-default padding-dr">'.lang('cancel_button').'</a>
							    <button type="button" class="btn btn-primary padding-dr" onclick="$(\'form#add_staff\').submit();">'
								.lang('save_button').'</button>
							   </div>';	
		$data['content_view'] = 'setup/business/staffs/add_staff_v';		
        $this->template->set('stylesheet', array(base_url('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.css')));
		$this->template->set('scriptsrc', array(base_url('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.js')));
		$this->template->set('document_title', lang('document_title_add_staff'));
		$this->template->layout($data);
	}
	
	public function media_category(){
		$crud = new grocery_CRUD();
		$crud->set_theme('bootstrap');
		$crud->unset_jquery();
		$crud->unset_bootstrap();		
		$crud->unset_print();
		$crud->unset_export();
		$crud->unset_common_search();	
	
		$crud->add_bulk_action('Active', site_url('setup/bulk_action/action/active'), '', 'clip-checkmark-circle', 'status');
		$crud->add_bulk_action('Inactive', site_url('setup/bulk_action/action/inactive'), '', 'clip-cancel-circle', 'status');
		
		$crud->set_subject('Media Category');		
	    $crud->set_table(MEDIA_CATEGORY);
		$crud->where('business_id',loginCompanyInfo('business_id'));
				
        $crud->columns('category_name', 'category_alias', 'status', 'created_date');
		
		$crud->field_type('business_id', 'hidden', loginCompanyInfo('business_id'));
		$crud->field_type('created_date', 'hidden');
		
		$crud->required_fields('category_name', 'category_for', 'status');
		
		$crud->callback_before_insert(array($this,'__callbackBeforeInsert'));
 		$crud->callback_before_update(array($this,'__callbackBeforeUpdate'));
		
		$output = $crud->render();		
        
		$data['content_view'] = 'setup/setting';
		$data['page_title'] = 'Media Category';
		
        $outputData = array_merge((array) $output , $data);
		$this->template->set('document_title', 'Media Category');
		$this->template->layout($outputData);
	}	
	
	public function photo_gallery(){
		$crud = new grocery_CRUD();
		$crud->set_theme('bootstrap');
		$crud->unset_jquery();
		$crud->unset_bootstrap();		
		$crud->unset_print();
		$crud->unset_export();
		$crud->unset_common_search();	
	
		$crud->add_bulk_action('Active', site_url('setup/bulk_action/action/active'), '', 'clip-checkmark-circle', 'status');
		$crud->add_bulk_action('Inactive', site_url('setup/bulk_action/action/inactive'), '', 'clip-cancel-circle', 'status');
		
		$crud->set_subject('Photo Gallery');
				
	    $crud->set_table(MEDIA_IMAGES);
		
		 $crud->set_relation('category_id', MEDIA_CATEGORY, 'category_name' ,array('business_id'=>loginCompanyInfo('business_id'),'category_for'=>'Photo'));
		
		$crud->where(MEDIA_IMAGES.'.business_id',loginCompanyInfo('business_id'));		
				
        $crud->columns( 'category_id', 'caption', 'image_path', 'status', 'created_date' );
		
		$crud->set_field_upload('image_path', 'assets/media/photo_gallery_images');
		
		$crud->field_type('created_date', 'hidden', date('Y-m-d H:i:s'));
		$crud->field_type('business_id', 'hidden', loginCompanyInfo('business_id'));

		$crud->required_fields('category_id', 'caption', 'status');
		
		$output = $crud->render();		
        
		$data['content_view'] = 'setup/setting';
		$data['page_title'] = 'Photo gallery';
		$data['breadcrumb'] = '<div class="breadcrumb breadcrumb-button"><a href="'.site_url('setup/business/photo_gallery').'" class="btn btn-primary padding-dr">Photo Gallery </a></div>';
        $outputData = array_merge((array) $output , $data);
		$this->template->set('document_title', 'Photo gallery');
		$this->template->layout($outputData);
	}
		
	public function video_gallery(){
		$crud = new grocery_CRUD();
		$crud->set_theme('bootstrap');
		$crud->unset_jquery();
		$crud->unset_bootstrap();		
		$crud->unset_print();
		$crud->unset_export();
		$crud->unset_common_search();	
	
		$crud->add_bulk_action('Active', site_url('setup/bulk_action/action/active'), '', 'clip-checkmark-circle', 'status');
		$crud->add_bulk_action('Inactive', site_url('setup/bulk_action/action/inactive'), '', 'clip-cancel-circle', 'status');
		
		$crud->set_subject('Video Gallery');				
	    $crud->set_table(MEDIA_VIDEOS);
				
		 $crud->set_relation('category_id', MEDIA_CATEGORY, 'category_name', array('business_id'=>loginCompanyInfo('business_id'),'category_for'=>'Video'));	
		 	
		$crud->where(MEDIA_VIDEOS.'.business_id',loginCompanyInfo('business_id'));		
				
        $crud->columns( 'category_id', 'caption', 'image_path', 'youtube_url', 'status', 'created_date' );
		
		$crud->callback_column('image_path', function($value,$row){
			return '<img src="'.$value.'" class="img img-responsive" alt="">';
		});
		$crud->fields('business_id', 'category_id', 'caption', 'youtube_url', 'status');
		$crud->field_type('business_id', 'hidden', loginCompanyInfo('business_id'));
		$crud->required_fields('category_id', 'youtube_url', 'status');
		$crud->callback_after_insert(array($this,'__callbackVideo'));
		$crud->callback_after_update(array($this,'__callbackVideo'));
		
		$output = $crud->render();		
        
		$data['content_view'] = 'setup/setting';
		$data['page_title'] = 'Video Gallery';
        $outputData = array_merge((array) $output , $data);
		$this->template->set('document_title', 'Video gallery');
		$this->template->layout($outputData);
	}
	
	public function addons(){
		$data['page_title'] = 'add ons';
		$data['breadcrumb'] = 'services';
		$data['content_view'] = 'setup/business/addons';		
		$this->template->set('document_title', lang('document_title'));
		$this->template->layout($data);
	}
	
	public function __callbackBeforeInsert($post_array){
		$category_alias = ($post_array['category_alias'] == '') ? $post_array['category_name'] : $post_array['category_alias'];
		$post_array['category_alias'] = slugify($category_alias); 
		$post_array['created_date'] = date('Y-m-d H:i:s'); 
		return $post_array;
	}
	
	public function __callbackBeforeUpdate($post_array){
		$category_alias = ($post_array['category_alias'] == '') ? $post_array['category_name'] : $post_array['category_alias'];
		$post_array['category_alias'] = slugify($category_alias); 	
		$post_array['created_date'] = date('Y-m-d H:i:s');
		return $post_array;
	}
	
	function __callbackVideo($post_array,$primary_key){
				$vid = getYoutubeVideoId($post_array['youtube_url']);				 
				$caption = $post_array['caption'];
				if(empty($caption)){
					$content = file_get_contents("http://youtube.com/get_video_info?video_id=".$vid);
					parse_str($content, $ytarr);
					$caption = $ytarr['title'];
				}
			    $dataSet=array('caption'=>$caption ,'image_path'=>'https://img.youtube.com/vi/'.$vid.'/1.jpg','created_date'=>date('Y-m-d H:i:s'));
				$this->db->where('video_id',$primary_key)->update(MEDIA_VIDEOS,$dataSet);
	}
	
	function __changeEditUrl($primary_key, $row){
		return site_url('setup/business/edit_staff/'.$row->association_to);	
	}
	
	/* services psy04112017 BGN */ 
	public function category(){
		$clientID = loginUserInfo('business_id');
		$responseData = array('status'=>'error', 'message'=>'', 'data'=>'');
		$validateCategory = $this->__setFormRules('category');
		
		if($validateCategory){		
			$timeZone = $this->timezone;
			$postData = $this->input->post();
			$action = $this->input->post('action');
			
			switch($action){
				case 'add':			
					$catResp = $this->SM->save_category($postData, $timeZone, $clientID);
					if(!empty($catResp)){						
						$responseData = array('status'=>'success', 'message'=>'Service category added successfully.', 'data'=>$catResp);
					} else {
						$responseData = array('status'=>'error', 'message'=>'Category not added.', 'data'=>$catResp);
					}	
				break;
				case 'edit':
					if(!empty($postData['cid']) && isset($postData['cid'])){
						$prepareData = array('category_name' => $postData['category_name']);		
						$this->db->where(array('category_id'=> $postData['cid'], 'business_id'=>$clientID));
						$catresp = $this->db->update(SERVICE_CATEGORIES, $prepareData);
						if($catresp){
							$responseData = array('status'=>'success', 'message'=>'Service category updated successfully.', 'data'=>'');
						} else {
							$responseData = array('status'=>'error', 'message'=>'Service category updation failed.', 'data'=>'');
						}
					}else{
						$responseData = array('status'=>'error', 'message'=>'Category ID is missing, please try again!', 'data'=>'');
					}
				break;
				case 'delete':
					if(!empty($postData['cid']) && isset($postData['cid'])){
						$this->db->where(array('category_id'=> $postData['cid'], 'business_id'=>$clientID));
						$catResp = $this->db->delete(SERVICE_CATEGORIES);
						if($catResp){						
							$responseData = array('status'=>'success', 'message'=>'Service category deleted successfully.', 'data'=>'');
						} else {
							$responseData = array('status'=>'error', 'message'=>'Service category deletion failed.', 'data'=>'');
						}
					}else{
						$responseData = array('status'=>'error', 'message'=>'Category ID is missing, please try again!', 'data'=>'');
					}
				break;			
			}
			
			$message = str_replace("'","\'", $responseData['message']);			
			
			$rsp = '<script language="javascript" type="text/javascript">window.top.window.stopFormSubmit(\''.$responseData['status'].'\', \''.$message.'\', \''.$action.'\');</script>';
			echo $rsp;
			die;
		}
		else if($validateCategory === false && isset($_POST) && !empty($_POST)){
			$message = $this->form_validation->error_array();
			$errors = '';
			foreach($message as $msg){
				$errors .= $msg;
			}			
			$rsp = '<script language="javascript" type="text/javascript">window.top.window.stopFormSubmit(\''.$responseData['status'].'\', \''.$errors.'\',\'\');</script>';
			echo $rsp;
			die;
		}
		
		$data['category_for']   = $this->input->get('for');
		$data['action']   = $this->input->get('act');		
		$data['category_id']   = $this->input->get('cid');
		$data['category_name']   = $this->input->get('cat');
		
		if(isset($data['category_id']) && !empty($data['category_id'])){
			$this->db->where(array('category_id'=> $data['category_id'], 'business_id'=>$clientID));
			$catResp = $this->db->get(SERVICE_CATEGORIES)->result_array();
			$data['category_name']   = !empty($catResp)? $catResp[0]['category_name'] : '';
		}		
		
		$data['page_title']     = strtoupper($data['action'].' Category');
		$data['content_view'] = 'setup/services/service_category';
		
		$this->template->set('scriptsrc', array(base_url('assets/modules/services/js/services-category.js')));
		$this->template->set('document_title', lang('document_title'));
		$this->template->layout($data);	
		
	}

	public function processcategory(){
		$timeZone = $this->timezone;
		$post = $this->input->post();
		if(isset($post) && !empty($post['category_name'])){
			$clientID = loginUserInfo('business_id');
			$catResp = $this->SM->save_category($post,$timeZone,$clientID );
			if($catResp == 'success'){
				$this->messageci->set( lang('save_success') ,'success') ;
				 redirect(site_url('setup/business/services'));
			} else {
				$this->messageci->set( lang('save_error') ,'error') ;
				redirect(site_url('setup/business/services'));
			}
		} else {
				redirect(site_url('setup/business/services'));
		}
	}
	
	public function services(){
		$cat = $this->input->get('cat');
		$categories = $this->SM->getCategories('Service');
		if(!isset($cat) && empty($cat)){
			if(!empty($categories)){
				$cat = $categories[0]['category_id'];
			}
		}
		
		$category_data = $this->SM->getCategoryDetail($cat);
		$service_data = $this->SM->getCategoryServices($cat);
		
		$this->breadcrumbs->push('Manage services', 'setup/business/details');
		$data['page_title'] = $this->breadcrumbs->show();
		
		$breadcrumb = '<div class="breadcrumb breadcrumb-button">
		<a href="'.site_url('setup/business/addservices').'" class="btn btn-success padding-dr">Add service</a>
		<a href="'.site_url('setup/business/addservicegroup').'" class="btn btn-success padding-dr">Add service group</a>
				</div>';
		
		$data = array('page_title' => $this->breadcrumbs->show(), 
					  'breadcrumb' => $breadcrumb,
					  'content_view' => 'setup/services/services',
					  'categories' => $categories,
					  'category_data' => $category_data,
					  'category_id' => $category_data['category_id'],
					  'category_name' => $category_data['category_name'],
					  'service_data' => $service_data);		
		$this->template->set('document_title', lang('document_title'));
		$this->template->layout($data);		
		
	}
	
	public function addservices(){	
		
		$this->load->language('setup/business_services', $this->language);
			
		$validateForm = $this->__setFormRules('service');
		if($validateForm){
			$postData = $this->input->post();
			$prepareData = array('business_id'		=>	loginUserInfo('business_id'),
								 'added_by'			=>	loginUserInfo('admin_id'),
								 'category_id'		=>	$postData['category_id'],
								 'service_name'		=>	$postData['service_name'],
								 'service_detail'	=>	$postData['service_detail'],
								 'service_type'		=>	$postData['service_type'],
								 'price_type'		=>	$postData['price_type'],
								 'price'			=>	$postData['price'],
								 'tax_id'			=>	$postData['tax_id'],
								 'include_tax'		=>  $postData['include_tax'],
								 'duration'			=>	$postData['duration'],
								 'service_color'	=>	$postData['service_color'],
								 'created_date'		=>	date('Y-m-d H:i:s'),
								 'timezone'			=>	$this->timezone,
								 'status'			=>	$postData['status']);
			$result = $this->db->insert(SERVICES, $prepareData);
			$service_id = $this->db->insert_id();
			if($result){
				$staffs = $this->input->post('staff');
				if(!empty($staffs)){					
					$dataSet = array();
					foreach($staffs as $staff){
						$dataSet[] = array('business_id'=>loginUserInfo('business_id'), 'service_id'=>$service_id, 'staff_id'=>$staff);
					}					
					if(!empty($dataSet)){
						$this->db->insert_batch(SERVICE_STAFF, $dataSet);
					}
				}
				$this->messageci->set( lang('add_success'),'success');
				redirect('setup/business/services');
			}else{
				$this->messageci->set( lang('add_error') , 'error');
			}			
		}
		
		
		$this->breadcrumbs->push('Services', 'setup/business/services');
		$this->breadcrumbs->push('Add services', 'setup/business/addservices');
		
		$data['page_title'] = $this->breadcrumbs->show();
		$data['businessStaffs'] = $this->BM->getBusinessStaffs();
		
		$data['breadcrumb'] = '<div class="breadcrumb breadcrumb-button">
								<a href="'.site_url('setup/business/services').'" class="btn btn-default padding-dr">'.lang('cancel_button').'</a>
							    <button type="button" class="btn btn-success padding-dr" onclick="$(\'form#add_services\').submit();">'
								.lang('save_button').'</button>
							   </div>';	
		
		$data['content_view'] = 'setup/services/addservices';
		
		$scriptSrc = array(
			base_url('assets/plugins/jquery-validation/dist/jquery.validate.js'), 
			base_url('assets/plugins/select2/dist/js/select2.min.js'), 
			base_url('assets/plugins/jquery-inputlimiter/jquery.inputlimiter.1.3.1.min.js'),  
			base_url('assets/modules/services/js/services.js')
		);
				  
		$this->template->set('scriptsrc', $scriptSrc);
				
		$this->template->set('document_title', lang('document_title'));
		$this->template->layout($data);
	}
	
	public function edit_service($service_id = NULL){		
		if($service_id == NULL){
			redirect('error_handler');
		}
		
		$this->load->language('setup/business_services', $this->language);
		
		$validateForm = $this->__setFormRules('service');
		if($validateForm){
			$postData = $this->input->post();
			$prepareData = array('business_id'		=>	loginUserInfo('business_id'),
								 'added_by'			=>	loginUserInfo('admin_id'),
								 'category_id'		=>	$postData['category_id'],
								 'service_name'		=>	$postData['service_name'],
								 'service_detail'	=>	$postData['service_detail'],
								 'service_type'		=>	$postData['service_type'],
								 'price_type'		=>	$postData['price_type'],
								 'price'			=>	$postData['price'],
								 'tax_id'			=>	$postData['tax_id'],
								 'include_tax'		=>  $postData['include_tax'],
								 'duration'			=>	$postData['duration'],
								 'service_color'	=>	$postData['service_color'],
								 'updated_date'		=>	date('Y-m-d H:i:s'),
								 'updated_date'		=>	$this->timezone,
								 'status'			=>	$postData['status']);
			$result = $this->db->where('service_id', $service_id)->update(SERVICES, $prepareData);
			if($result){
				$staffs = $this->input->post('staff');
				if(!empty($staffs)){
					$this->db->where(array('business_id'=>loginUserInfo('business_id'), 'service_id'=>$service_id))->delete(SERVICE_STAFF);
					$dataSet = array();
					foreach($staffs as $staff){
						$dataSet[] = array('business_id'=>loginUserInfo('business_id'), 'service_id'=>$service_id, 'staff_id'=>$staff);
					}					
					if(!empty($dataSet)){
						$this->db->insert_batch(SERVICE_STAFF, $dataSet);
					}
				}				
				$this->messageci->set( lang('update_success') ,'success');
				redirect('setup/business/services');
			}else{
				$this->messageci->set( lang('update_error') , 'error');
			}			
		}
		
		$data['service_id'] = $service_id;
		$data['service_data'] =  $this->SM->getServiceDetail($service_id);
		$data['service_staffs'] =  $this->SM->getServiceStaffs($service_id);
		$data['businessStaffs'] = $this->BM->getBusinessStaffs();
		
		$this->breadcrumbs->push('Services', 'setup/business/services');
		$this->breadcrumbs->push('Edit services', 'setup/business/edit_service');
		$data['page_title'] = $this->breadcrumbs->show();
		
		$data['breadcrumb'] = '<div class="breadcrumb breadcrumb-button">
								<a href="'.site_url('setup/business/services').'" class="btn btn-default padding-dr">'.lang('cancel_button').'</a>
							    <button type="button" class="btn btn-success padding-dr" onclick="$(\'form#edit_services\').submit();">'
								.lang('save_button').'</button>
							   </div>';
		$data['content_view'] = 'setup/services/edit_service';
				
		$scriptSrc = array(base_url('assets/plugins/jquery-validation/dist/jquery.validate.js'), 
						   base_url('assets/plugins/jquery-inputlimiter/jquery.inputlimiter.1.3.1.min.js'),  
						   base_url('assets/modules/services/js/services.js'));
		$this->template->set('scriptsrc', $scriptSrc);
		$this->template->set('document_title', lang('document_title'));
		$this->template->layout($data);
	} 

	public function addservicegroup(){			
		/* ADD SERVICE GROUP */
		$validation = $this->__setFormRules('add_service_group');
		if($validation){
		$timeZone = $this->timezone;
		$clientID = loginCompanyInfo('business_id'); 
		$post = $this->input->post();		
			$sergResp = $this->SM->save_servicegroup( $post , $timeZone , $clientID );
			if($sergResp == 'success' ){
				$this->messageci->set( lang('add_success') ,'success') ;
				redirect(site_url('setup/business/services'));
			} else {
				$this->messageci->set(lang('add_error') ,'error') ;
				redirect(site_url('setup/business/addservicegroup'));
			}
		}
		/* ADD SERVICE GROUP END */
		
		$this->breadcrumbs->push('Services', 'setup/business/services');
		$this->breadcrumbs->push('Add services group', 'setup/business/addservices');
		$data['page_title'] = $this->breadcrumbs->show();
		
		$data['breadcrumb'] = '<div class="breadcrumb breadcrumb-button">
								<a href="'.site_url('setup/business/services').'" class="btn btn-default padding-dr">'.lang('cancel_button').'</a>
							    <button type="button" class="btn btn-success padding-dr" onclick="$(\'form#edit_services\').submit();">'
								.lang('save_button').'</button>
							   </div>';
							   		
		$data['content_view'] = 'setup/services/addservicegroup';
		
		$scriptSrc = array(base_url('assets/plugins/jquery-validation/dist/jquery.validate.js'),
						   base_url('assets/plugins/jquery-inputlimiter/jquery.inputlimiter.1.3.1.min.js'),
						   base_url('assets/plugins/select2/dist/js/select2.min.js'),
						   base_url('assets/modules/services/js/services.js'));
						   
		$this->template->set('scriptsrc', $scriptSrc);
		$this->template->set('stylesheet', array(base_url('assets/plugins/select2/dist/css/select2.min.css')));
		$this->template->set('document_title', lang('document_title'));
		$this->template->layout($data);
	}

	public function edit_group( $group_id = NULL ){
		
		if($group_id == NULL){
			redirect('error_handler');
		}

		/* EDIT SERVICE GROUP */
		$validation = $this->__setFormRules('edit_service_group');
		if($validation){
			$post = $this->input->post();
			$updateSGResp = $this->SM->update_servicegroup( $post );
			if($updateSGResp){
				$this->messageci->set( lang('update_success') ,'success') ;
				redirect(site_url('setup/business/services'));
			} else {
				$this->messageci->set(  lang('update_error') ,'error') ;
				redirect(site_url('setup/business/edit_group/'.$group_id ));
			}
		}
		/* EDIT SERVICE GROUP */
			
		$data['service_id'] = $group_id;
		$data['service_data'] =  $this->SM->getServiceDetail($group_id);
		$data['group_services'] =  $this->SM->getGroupServices($group_id);

		$this->breadcrumbs->push('Services', 'setup/business/services');
		$this->breadcrumbs->push('Edit services group', 'setup/business/edit_group');
		$data['page_title'] = $this->breadcrumbs->show();
		
		$data['breadcrumb'] = '<div class="breadcrumb breadcrumb-button">
								<a href="'.site_url('setup/services/services').'" class="btn btn-default padding-dr">'.lang('cancel_button').'</a>
							    <button type="button" class="btn btn-success padding-dr" onclick="$(\'form#edit_services\').submit();">'
								.lang('save_button').'</button>
							   </div>';
							   
		$scriptSrc = array(base_url('assets/plugins/jquery-validation/dist/jquery.validate.js'),
						   base_url('assets/plugins/jquery-inputlimiter/jquery.inputlimiter.1.3.1.min.js'),
						   base_url('assets/plugins/select2/dist/js/select2.min.js'),
						   base_url('assets/modules/services/js/services.js'));
						   
		$this->template->set('scriptsrc', $scriptSrc);
		$this->template->set('stylesheet', array(base_url('assets/plugins/select2/dist/css/select2.min.css')));
	
		$data['content_view'] = 'setup/services/edit_service_group';		
		$this->template->set('document_title', lang('document_title'));
		$this->template->layout($data);
	}
		
	public function getServiceHtmlAjax(){
		$post = $this->input->get();
		$Trhtml = '';
		if(isset($post) && !empty($post) && !empty($post['service_id'])){			
			 $Trhtml = $this->SM->prepareServiceItemsHtml($post);
		} 
		echo $Trhtml;
		die();
	}
	
	public function getTabHtmlAjax(){
		$post = $this->input->post();
		$Tabhtml = '';
		if(isset($post) && !empty($post) && !empty($post['category_id'])){
			 $cat_id  = $post['category_id'];			
			 $Tabhtml = $this->SM->getCategoryTabServicesHtml($cat_id);
		} 
		echo $Tabhtml . '';
		die();
	}
	/* services psy04112017 END */ 
	function __setFormRules( $setRulesFor = '' ){
		switch($setRulesFor){
			case'updateBusinessDetails':
				$this->form_validation->set_rules('business_name',lang('business_name_label'), 'trim|required');
			break;
			case'online_booking':
				$this->form_validation->set_rules('initial_status', 'Initial Status', 'trim|required|integer|greater_than[0]|less_than[3]');
				$this->form_validation->set_rules('select_service', 'Select service', 'trim|max_length[250]');
				$this->form_validation->set_rules('select_staff', 'Select staff', 'trim|max_length[250]');
				$this->form_validation->set_rules('select_date_time', 'Select  date/time', 'trim|max_length[100]');
				$this->form_validation->set_rules('customer_details', 'Customer details', 'trim|max_length[250]');
				$this->form_validation->set_rules('appointment_confirmed', 'Appointment confirmed', 'trim|max_length[250]');
				$this->form_validation->set_rules('no_service_available', 'No service available', 'trim|max_length[250]');
			break;
			case'add_location':
				$this->form_validation->set_rules('branch_name', lang('location_name_label'), 'trim|required|min_length[5]');
			break;
			case'edit_location':
				$this->form_validation->set_rules('branch_name', lang('location_name_label'), 'trim|required|min_length[5]');
				$this->form_validation->set_rules('address_line1', lang('address_label'), 'trim|required|min_length[5]');
				$this->form_validation->set_rules('city', lang('city_label') , 'trim|required|min_length[5]');
			break;
			case'add_staff':
				$this->form_validation->set_rules('email', lang('email_label') , 'trim|required|valid_email|is_unique[fd_users_administrator.email]');
				$this->form_validation->set_rules('phone_number', lang('phone_label') , 'trim|required|is_unique[fd_users_administrator.phone_number]');
				$this->form_validation->set_rules('city',  lang('city_label') , 'trim|required|min_length[5]');
				$this->form_validation->set_rules('first_name', lang('firstname_label') , 'trim|required|min_length[5]');
			break;
			case'edit_staff':
				$this->form_validation->set_rules('city', lang('city_label')  , 'trim|required|min_length[5]');
				$this->form_validation->set_rules('first_name', lang('firstname_label') , 'trim|required|min_length[5]');
			break;
			/* services psy04112017 */ 
			case 'category':
				$this->form_validation->set_rules('category_name',lang('category_label'), 'required|min_length[5]|max_length[40]');
			break;
			case 'add_service_group':
				$this->form_validation->set_rules('service_name',lang('service_name_label'), 'required|min_length[5]|max_length[40]');
				$this->form_validation->set_rules('category_id', lang('category_label') , 'trim|required|numeric');
			break;		
			case 'edit_service_group':
				$this->form_validation->set_rules('service_name',lang('service_name_label'), 'required|min_length[5]|max_length[40]');
				$this->form_validation->set_rules('category_id', lang('category_label') , 'trim|required|numeric');
			break;		
			case 'service':				
				$this->form_validation->set_rules('service_name', lang('service_name_label') , 'trim|required|min_length[3]|max_length[60]');
				$this->form_validation->set_rules('category_id', lang('category_label') , 'trim|required|numeric');
				$this->form_validation->set_rules('service_detail', lang('description_label') , 'trim|required');
				$this->form_validation->set_rules('price_type', lang('tax_type_label') , 'trim|required');
				$this->form_validation->set_rules('status', lang('status_label') , 'trim|required');
			break;			
			/* services psy04112017 */ 
		}
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert_msg" style="padding: 5px; margin-bottom: 5px;"><button data-dismiss="alert" class="close">×</button><i class="fa fa-times-circle"></i> ', '</div>');
		return $this->form_validation->run($this);
	
	}
}
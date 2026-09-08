<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Excel_import extends MY_Controller {
	var $userID;
	public function __construct(){
		parent::__construct();
		$this->userID = checkUserLogin();
		$this->load->model('excel_import_model', 'EIM');
		$this->load->library('excel');
		$this->load->model('production/production_model', 'PM');
	}
	
	function index(){
		$data['page_title'] = 'Import Excel File Data';
		$data['content_view'] = 'excel_import/excel_import';
		
		$this->template->set('stylesheet', array(base_url('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.css')));
		$this->template->set('scriptsrc', array(base_url('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.js'),
												site_url('excel_import/assets/js/excel_import.js')));
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($data);
	}
	
	// Download sample excel files
	function download_sample($type = 'point_production'){
		$type = preg_replace('/[^a-zA-Z0-9_-]/', '', $type);
		$file = FCPATH . 'sample_excel_file/' . $type . '.xlsx';
		
		if(!file_exists($file)){
			if(strpos($type, 'production') !== false || $type == 'production'){
				$file = FCPATH . 'sample_excel_file/point_production.xlsx';
			}else if(strpos($type, 'sale') !== false){
				$file = FCPATH . 'sample_excel_file/local_sale.xlsx';
			}else if(strpos($type, 'dispatch') !== false || strpos($type, 'box') !== false){
				$file = FCPATH . 'sample_excel_file/dispatch_box.xlsx';
			}else if(strpos($type, 'stock') !== false){
				$file = FCPATH . 'sample_excel_file/closing_stock.xlsx';
			}else{
				$file = FCPATH . 'sample_excel_file/point_production.xlsx';
			}
		}
		
		if(file_exists($file)){
			$this->load->helper('download');
			force_download($file, NULL);
		}else{
			show_404();
		}
	}
	
	// start production data import
	function production($production_id = NULL){
		if($production_id === NULL || !$this->input->is_ajax_request()){
			$this->index();
			return;
		}
		$this->setting['stylesheet'] = array(base_url('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.css'));
		$this->setting['scriptsrc'] = array(base_url('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.js'),
												site_url('excel_import/assets/js/production_import.js'));		
		$data['production_id'] = $production_id;
		$data['page_title'] = 'Import production data through excel (.xls, .xlsx) file.';
		$data['setup_form'] = $this->load->view('excel_import/import_production_v', $data, true);
		$data['status'] = 'success';
		json_output($data);
	}
	
	function ajax_production_import(){
		$response_data = array('status' => 'danger', 'message' => 'Invalid request, Please try again to import data!');		
		$validateForm = $this->__setFormRules('production_import');		
		if($validateForm){
			$allFishCodeSql = $this->db->select('code')->get_where(FISH, array('status'=>'Active'))->result_array();
			$allFishCode = array();
			if(!empty($allFishCodeSql)){
				foreach($allFishCodeSql as $code){
					$allFishCode[] = $code['code'];
				}
			}
			
			$allClientCodeSql = $this->db->select('ID, company_name, contact_number, email')->get_where(CLIENT, array('status'=>'Active'))->result_array();
			$allClientCode = array();
			if(!empty($allClientCodeSql)){
				foreach($allClientCodeSql as $code){
					$allClientCode[] = $code['ID'];
					$allClientInfo[$code['ID']] = array('name'=>$code['company_name'], 'number'=>$code['contact_number'], 'email'=>$code['email']);
				}
				$allClientCode = array('code'=>$allClientCode, 'info'=>$allClientInfo);
			}			
			$production_id = $this->input->post('production_id');
			$action_mode = $this->input->post('action_mode');
			
			$productionInfo = $this->db->select(PRODUCTION.'.*, ' . MARKET_PLACE.'.code, IFNULL(max(number),0) as box_number')
										->join(MARKET_PLACE, 'ID = depot_id')
										->join(BOX_NUMBER, 'mp_code = code')
										->get_where(PRODUCTION, array('production_id' => $production_id))->result_array();										
			if(!empty($productionInfo)){
				$productionInfo = $productionInfo[0];
				switch($productionInfo['production_type']){
					case 'Point':
					case 'Stock':
					case 'Transfer':
					case 'Bachat':				
						if($action_mode == 'check'){
							$path = $_FILES["file"]["tmp_name"]; //FCPATH . 'carrets_detail.xlsx'; //
							$response_data = $this->__check_production_data($path, $productionInfo, $allFishCode, $allClientCode);
						}else if($action_mode == 'save'){
							$path = $this->input->post('file_path');
							$path = decrypt_data($path);
							if(file_exists($path) && is_file($path)){
								$response_data = $this->__save_production_data($path, $productionInfo, $allFishCode, $allClientCode);
							}else{
								$body_data = '<tr><td><div class="alert alert-danger">Uploaded excel file not found, please try again!</div></td></tr>';
								$response_data = array('status' =>'error', 'message' => $body_data);
							}
						}else{
						 $body_data = '<tr><td><div class="alert alert-danger">This is an invalid request, Please reload the page and try again!</div></td></tr>';
							$response_data = array('status' =>'error', 'message' => $body_data);
						}
					break;			
				}
			}else{
				$response_data = array('status' =>'danger', 'message' => 'This is an invalid request, Production info not found!');
			}
		}else{
			$error = $this->form_validation->error_array(); //validation_errors();
			$error = implode("\n", $error);
			$response_data = array('status' =>'danger', 'message' => $error);
		}
		json_output($response_data);		
	}
	
	function __check_production_data($path, $productionInfo, $allFishCode, $allClientCode){
		$allFishType = array('Fresh','Rotten','Destroyed');		
		$current_box_number = $productionInfo['box_number'] + 1;
		$mp_code = strtoupper(substr($productionInfo['code'], 0, 1));
		$header_data = $body_data = $footer_data = '';
		$object = PHPExcel_IOFactory::load($path);
		$response_data = array();
		$columns = array('serial_number', 'production_type', 'fish_type', 'quantity', 'fish_code', 'carret_weight', 'box_weight', 'box_number','client_code');
		$errors = array();
		
		foreach($object->getWorksheetIterator() as $worksheet){
			
			$highestRow = $worksheet->getHighestRow();
			$highestColumn = $worksheet->getHighestColumn();
			
			$header_data .= '<tr>';
			foreach($columns as $key => $name){
				$hname = $worksheet->getCellByColumnAndRow($key, 1)->getValue();
				if($hname !== $name){
					$errors[] = $hname.': Column name mismatched, there should be "'.$name.'"!';
				}
				$header_data .= '<th>'. ucfirst(str_replace('_',' ', $hname))  . '</th>';
			}
			$header_data .= '</tr>';
			
			
			$total_carret = $total_qty = $total_carret_wt = $total_box_wt = 0;
			for($row=2; $row<=$highestRow; $row++){
								
				$client = $box = $carret_weight = $box_weight = '';
				$body_data .= '<tr>';
				
				foreach($columns as $key => $name){
					$bdata = trim($worksheet->getCellByColumnAndRow($key, $row)->getValue());
					if($name == 'production_type'){
						if($bdata !== $productionInfo['production_type']){
							$errors[] = $bdata.' is not valid production type at line number '.$row.' there should be '.$productionInfo['production_type'];
						}			
					}else if($name == 'fish_type'){
						if(!in_array($bdata, $allFishType)){
							$errors[] = $bdata.' is not valid <strong>fish type</strong> at line number '.$row.' There should be one of : '.implode(', ',$allFishType);
						}
					}else if($name == 'quantity'){
						if (preg_match('/^[0-9]+$/', $bdata)) {
							$total_qty  += $bdata;
						}else{
							$errors[] = $bdata.' is not valid <strong>fish quantity</strong> at line number '.$row. ($bdata==''?' there should be 0 instead of blank':'');
						}						
					}else if($name == 'fish_code'){
						if(!in_array($bdata, $allFishCode)){
							$errors[] = $bdata.' is not valid <strong>fish code</strong> at line number '.$row;
						}
					}else if($name == 'carret_weight'){
						if(is_float($bdata) || is_numeric($bdata)){
							$total_carret_wt  += $bdata;
							$carret_weight = $bdata;
						}else{
							$errors[] = $bdata.' is not valid <strong>carret weight</strong> at line number '.$row;
						}						
					}else if($name == 'box_weight'){
						if($bdata != ''){
							if(is_float($bdata) || is_numeric($bdata)){
								$total_box_wt  += $bdata;
								$box_weight = $bdata;
							}else{
								$errors[] = $bdata.' is not valid <strong>box weight</strong> at line number '.$row;
							}
						}
					}else if($name == 'box_number'){
						if($bdata != ''){
							$box_number = $this->db->where(array('box_number'=>$bdata))->count_all_results(PRODUCT_BOX);
							if($box_number > 0){
								$errors[] = '<strong>'.$bdata.' Box Number</strong> is already exists, please enter different at line number '.$row;
							}
							$current_box_number++;
							$box = $bdata;
						}	
					}else if($name == 'client_code'){
						if($bdata != ''){
							if(!in_array($bdata, $allClientCode['code'])){
								$errors[] = $bdata.' is not valid <strong>client code</strong> at line number '.$row;
							}
							$client = $bdata;
						}
					}					
					$body_data .= '<td>'. $bdata  . '</td>';									
				}
				
				if($box != '' && $client != ''){
					$errors[] = 'You can not <strong>sale</strong> and <strong>make box</strong> to same carret, please either <strong>make box</strong> or <strong>sale</strong> at line number '.$row;
				}
				
				if(!empty($box_weight) && (($carret_weight < $box_weight) || ($carret_weight - $box_weight) > 1)){
					$errors[] = 'The <strong>Box weight('.$box_weight.')</strong> should always less <strong>0.5KG or 1KG</strong> than <strong>carret weight('.$carret_weight.')</strong> at line number '.$row;
				}
				
				$body_data .= '</tr>';
				
			}
			
			$footer_data .= '<tr>';
			$footer_data .= '<th>Total:</th>';
			$footer_data .= '<th>carret:</th>';
			$footer_data .= '<th>'.($highestRow-1).'</th>';
			$footer_data .= '<th>'.$total_qty.'</th>';
			$footer_data .= '<th></th>';
			$footer_data .= '<th>'.$total_carret_wt.'</th>';
			$footer_data .= '<th>'.$total_box_wt.'</th>';
			$footer_data .= '<th></th>';
			$footer_data .= '<th></th>';
			$footer_data .= '</tr>';
			$footer_data .= $header_data;
			break;
		}
		
		if(!empty($errors)){
			$body_data = '<tr><td><div class="alert alert-danger"><h3>'.count($errors).' Error found!</h3><p>'.implode("<br>", $errors).'</p></div></td></tr>';
			$response_data = array('status' =>'error', 'message' => $body_data);
		}else{
			$ext = pathinfo($path, PATHINFO_EXTENSION);
			$micro = md5(microtime(true)).'.'.$ext;
			$dst_path = FCPATH . 'excel_import_file/';
			if(!file_exists($dst_path) && !is_dir($dst_path)){
				@mkdir($dst_path, 0755, true);
			}
			$dst_path = $dst_path.$micro;
			@move_uploaded_file($path, $dst_path);
			$dst_path = encrypt_data($dst_path);
$response_data = array('status' =>'success', 'header_data'=> $header_data, 'body_data'=> $body_data, 'footer_data'=> $footer_data, 'file_path'=>$dst_path);
		}
		
		return $response_data;
	
	}
	
	function __save_production_data($excel_file_path, $productionInfo, $allFishCode, $allClientCode){
		//$current_box_number = $productionInfo['box_number'] + 1;
		//$mp_code = strtoupper(substr($productionInfo['code'], 0, 1));
				
		$object = PHPExcel_IOFactory::load($excel_file_path);
		$response_data = array();
		$columns = array('serial_number', 'production_type', 'fish_type', 'quantity', 'fish_code', 'carret_weight', 'box_weight', 'box_number','client_code');
		$errors = array();
		
		foreach($object->getWorksheetIterator() as $worksheet){
			
			$highestRow = $worksheet->getHighestRow();
			$highestColumn = $worksheet->getHighestColumn();		
			$prepare_data = array();
			
			$prepare_data['production_id'] 	= $productionInfo['production_id'];
			$prepare_data['depot_id'] 		= $productionInfo['depot_id'];
			$prepare_data['market_id'] 		= $productionInfo['market_id'];
			$prepare_data['production_type'] = $productionInfo['production_type'];
			$prepare_data['packing_date'] 	= $productionInfo['production_date'];
						
			$prepare_data['added_by'] 		= $this->userID;
			$prepare_data['added_date'] 	= get_datetime('Y-m-d H:i:s');
			$prepare_data['status'] 		= 'Active';
			$prepare_data['action_microtime'] = microtime(true);
							
			for($row=2; $row<=$highestRow; $row++){
				$box_number	= '';
				$row_data = array('fish_type'=>'', 'carret_quantity'=>'0', 'fish_code'=>'',
									'carret_weight'=>'0', 'box_weight'=>'0', 'box_number'=>'', 'client_id'=>'');
				foreach($columns as $key => $name){
					$bdata = trim($worksheet->getCellByColumnAndRow($key, $row)->getValue());
					
					if($name == 'fish_type'){
						$row_data['fish_type'] = $bdata;		
					}else if($name == 'quantity'){
						$row_data['carret_quantity'] = $bdata;									
					}else if($name == 'fish_code'){
						$row_data['fish_code'] = $bdata;
					}else if($name == 'carret_weight'){
						$row_data['carret_weight'] = $bdata;
					}else if($name == 'box_weight'){
						if($bdata != ''){
							$row_data['box_weight'] = $bdata;
						}
					}else if($name == 'box_number'){
						if($bdata != ''){
							$box_number = $this->db->where(array('box_number'=>$bdata))->count_all_results(PRODUCT_BOX);
							if($box_number > 0){
								$errors[] = '<strong>'.$bdata.' Box Number</strong> is already exists, please enter different at line number '.$row;
							}							
							$row_data['box_number'] = $bdata;
						}						
					}else if($name == 'client_code'){
						if($bdata != ''){
							$row_data['client_id'] = $bdata;
						}
					}								
				}
				
				if(empty($errors)){
					$prepare_data = array_merge($prepare_data, $row_data);
					$carret_number = $this->EIM->generate_carret($prepare_data);
					if($carret_number){
						if(isset($prepare_data['box_number']) && !empty($prepare_data['box_number'])){
							$box_id = $this->EIM->generate_box($prepare_data, $carret_number);
						}
						if(isset($prepare_data['client_id']) && !empty($prepare_data['client_id']) && $prepare_data['production_type'] != 'Transfer'){
							$this->EIM->generate_sale($prepare_data, $productionInfo['code'], $carret_number, $allClientCode);
						}
					}	
				}				
			}
			break;
		}
		
		if(!empty($errors)){
			$body_data = '<tr><td><div class="alert alert-danger"><h3>'.count($errors).' Error found!</h3><p>'.implode("<br>", $errors).'</p></div></td></tr>';
			$response_data = array('status' =>'error', 'message' => $body_data);
		}else{
			if(file_exists($excel_file_path) && is_file($excel_file_path)){
				@unlink($excel_file_path);
			}
			$this->PM->update_production_summary($productionInfo['production_id']);
			$response_data = array('status' =>'success', 'message'=> 'All data imported successfully!');
		}		
		return $response_data;
	
	}
	
	// start dispatch box data import
	function dispatch_box($dispatch_id = NULL){
		if($dispatch_id === NULL || !$this->input->is_ajax_request()){
			$this->index();
			return;
		}
		$this->setting['stylesheet'] = array(base_url('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.css'));
		$this->setting['scriptsrc'] = array(base_url('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.js'),
												site_url('excel_import/assets/js/dispatch_box.js'));		
		$data['dispatch_id'] = $dispatch_id;
		$data['page_title'] = 'Import Dispatch Box through excel (.xls, .xlsx) file.';
		$data['setup_form'] = $this->load->view('excel_import/import_dispatch_box_v', $data, true);
		$data['status'] = 'success';
		json_output($data);		
	}
	
	function ajax_dispatch_box(){
		
		$response_data = array('status' => 'danger', 'message' => 'Invalid request, Please try again to import data!');		
		$validateForm = $this->__setFormRules('dispatch_box');		
		
		if($validateForm){
			$dispatch_id = $this->input->post('dispatch_id');
			$action_mode = $this->input->post('action_mode');
			$dispatchInfo = $this->db->get_where(PRODUCT_DISPATCH, array('dispatch_id' => $dispatch_id))->result_array();										
			if(!empty($dispatchInfo)){
				$dispatchInfo = $dispatchInfo[0];
				if($action_mode == 'check'){
					$file_path = $_FILES["file"]["tmp_name"];
					$response_data = $this->__import_dispatch_box($file_path, $dispatchInfo, $action_mode);
				}else if($action_mode == 'save'){
					$file_path = $this->input->post('file_path');
					$file_path = decrypt_data($file_path);
					if(file_exists($file_path) && is_file($file_path)){						
						$response_data = $this->__import_dispatch_box($file_path, $dispatchInfo, $action_mode);
					}else{
						$body_data = '<tr><td><div class="alert alert-danger">Uploaded excel file not found, please try again!</div></td></tr>';
						$response_data = array('status' =>'error', 'message' => $body_data);
					}
				}else{
				 	$body_data = '<tr><td><div class="alert alert-danger">This is an invalid request, Please reload the page and try again!</div></td></tr>';
					$response_data = array('status' =>'error', 'message' => $body_data);
				}
			}else{
				$response_data = array('status' =>'danger', 'message' => 'This is an invalid request, Production info not found!');
			}
		}else{
			$error = $this->form_validation->error_array(); //validation_errors();
			$error = implode("\n", $error);
			$response_data = array('status' =>'danger', 'message' => $error);
		}
		json_output($response_data);		
	
	}
	
	function __import_dispatch_box($file_path, $dispatchInfo, $action_mode){
		$header_data = $body_data = $footer_data = '';
		$object = PHPExcel_IOFactory::load($file_path);
		$response_data = array();
		$errors = array();
		foreach($object->getWorksheetIterator() as $worksheet){
			$highestRow = $worksheet->getHighestRow();
			$highestColumn = $worksheet->getHighestColumn();
			if($action_mode == 'save'){
				$boxArray = array();
				for($row=2; $row<=$highestRow; $row++){
					$excel_box_number = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
					if($excel_box_number != ''){
						$checkBoxExist = $this->db->where(array('box_number'=>$excel_box_number, 'status !=' => 'Deleted'))->count_all_results(PRODUCT_BOX);
						if($checkBoxExist <= 0){
							$errors[] = $excel_box_number.':  <strong>Box number</strong> is not exists at line number '.$row;
						}
						$checkBoxDispatch = $this->db->where(array('box_number'=>$excel_box_number))->count_all_results(PRODUCT_DISPATCH_BOX);
						if($checkBoxDispatch > 0){
							$errors[] = $excel_box_number.':  <strong>Box number</strong> is already dispatched at line number '.$row;
						}
						
						$boxArray[] = array('dispatch_id'   => $dispatchInfo['dispatch_id'],
											'dr_number'     => $dispatchInfo['dr_number'],
											'box_number' 	=> $excel_box_number,
											'added_by' 	 	=> $this->userID,
											'added_by' 	 	=> $this->userID,
											'added_date' 	=> get_datetime('Y-m-d H:i:s'),
											'action_microtime'=> microtime(true),
											'status'		=> 'Active'
											);
					}
				}
				if(!empty($boxArray) && empty($errors)){
					$this->db->insert_batch(PRODUCT_DISPATCH_BOX, $boxArray);
					if(file_exists($file_path) && is_file($file_path)){
						@unlink($file_path);
					}
					$response_data = array('status' =>'success', 'message' => count($boxArray) .' Box uploaded to DR successfully! ');
				}else{
					$body_data = '<tr><td><div class="alert alert-danger"><h3>'.count($errors).' Error found!</h3><p>'.implode("<br>", $errors).'</p></div></td></tr>';
					$response_data = array('status' =>'error', 'message' => $body_data);
				}
				return $response_data;
				
			}else{
				$excel_column = $worksheet->getCellByColumnAndRow(0, 1)->getValue();
				if($excel_column !== 'box_number'){
					$errors[] = $excel_column.': Column name mismatched, there should be "'.$column.'"!';
				}
				$header_data .= '<tr><th>'. ucfirst(str_replace('_',' ', $excel_column))  . '</th></tr>';
				$footer_data .= '<tr><th>Total Box: '.($highestRow-1).'</th></tr>';
				for($row=2; $row<=$highestRow; $row++){
					$excel_box_number = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
					if($excel_box_number != ''){
						$checkBoxExist = $this->db->where(array('box_number'=>$excel_box_number, 'status !=' => 'Deleted'))->count_all_results(PRODUCT_BOX);
						if($checkBoxExist <= 0){
							$errors[] = $excel_box_number.':  <strong>Box number</strong> is not exists at line number '.$row;
						}
						$checkBoxDispatch = $this->db->where(array('box_number'=>$excel_box_number))->count_all_results(PRODUCT_DISPATCH_BOX);
						if($checkBoxDispatch > 0){
							$errors[] = $excel_box_number.':  <strong>Box number</strong> is already dispatched at line number '.$row;
						}
					}
					$body_data .= '<tr><td>'. $excel_box_number  . '</td></tr>';		
				}
			}			
			break;
		}
		
		if(!empty($errors)){
			$body_data = '<tr><td><div class="alert alert-danger"><h3>'.count($errors).' Error found!</h3><p>'.implode("<br>", $errors).'</p></div></td></tr>';
			$response_data = array('status' =>'error', 'message' => $body_data);
		}else{
			$ext = pathinfo($file_path, PATHINFO_EXTENSION);
			$micro = md5(microtime(true)).'.'.$ext;
			$dst_path = FCPATH . 'excel_import_file/';
			if(!file_exists($dst_path) && !is_dir($dst_path)){
				@mkdir($dst_path, 0755, true);
			}
			$dst_path = $dst_path.$micro;
			@move_uploaded_file($file_path, $dst_path);
			$dst_path = encrypt_data($dst_path);
$response_data = array('status' =>'success', 'header_data'=> $header_data, 'body_data'=> $body_data, 'footer_data'=> $footer_data, 'file_path'=>$dst_path);
		}
		return $response_data;
	}
	
	// start dispatch box data import
	function import($data_type = '', $sale_id = ''){
		if(empty($data_type) || !$this->input->is_ajax_request()){
			$this->index();
			return;
		}
		$this->setting['stylesheet'] = array(base_url('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.css'));
		$this->setting['scriptsrc'] = array(base_url('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.js'),
											site_url('excel_import/assets/js/import_data.js'));		
		$data['data_id'] = $sale_id;
		$data['data_type'] = $data_type;
		$data['page_title'] = 'Import '.ucfirst(str_replace('_',' ',$data_type)).' through excel (.xls, .xlsx) file.';
		$data['setup_form'] = $this->load->view('excel_import/import_data_v.php', $data, true);
		$data['status'] = 'success';
		json_output($data);
	}
	
	function ajax_import_data(){
		
		$response_data = array('status' => 'danger', 'message' => 'Invalid request, Please try again to import data!');		
		$validateForm = $this->__setFormRules('import_data');		
		if($validateForm){
			$data_type = $this->input->post('data_type');
			if(!empty($data_type)){
				switch($data_type){
					case 'point_sale':
					case 'depot_sale':	
						$response_data = $this->__local_sale();	
					break;			
				}
			}else{
				$response_data = array('status' =>'danger', 'message' => 'This is an invalid request, There is not define Data type!');
			}
		}else{
			$error = $this->form_validation->error_array(); //validation_errors();
			$error = implode("\n", $error);
			$response_data = array('status' =>'danger', 'message' => $error);
		}
		json_output($response_data);	
	}
	
	function __local_sale(){
		$action_mode = $this->input->post('action_mode');
		$sale_id = $this->input->post('data_id');
		$data_type = $this->input->post('data_type');
	
		$stock_type = ($data_type == 'depot_sale') ? array('Bachat','Opening') : array('Current');
		$sale_from = array('Point','Depot');		
		$allFishCode = $this->EIM->getAllFishCode();	
		$saleInfo = $this->EIM->getSaleInfo($sale_id);
		
		$file_path = '';
		if($action_mode == 'check'){
			$file_path = $_FILES["file"]["tmp_name"];			
		}else if($action_mode == 'save'){
			$file_path = $this->input->post('file_path');
			$file_path = decrypt_data($file_path);
			if(!file_exists($file_path) && !is_file($file_path)){
				$body_data = '<tr><td><div class="alert alert-danger">Uploaded excel file not found, please try again!</div></td></tr>';
				return $response_data = array('status' =>'error', 'message' => $body_data);
			}
		}else{
		 	$body_data = '<tr><td><div class="alert alert-danger">This is an invalid request, Please reload the page and try again!</div></td></tr>';
			return $response_data = array('status' =>'error', 'message' => $body_data);			
		}	
		
		$header_data = $body_data = $footer_data = '';
		$object = PHPExcel_IOFactory::load($file_path);
		$response_data = array();
											
		$columns = array('serial_number', 'sale_from', 'fish_code', 'quantity', 'weight', 'rate', 'amount','remark', 'stock_type');
		$errors = array();
		
		foreach($object->getWorksheetIterator() as $worksheet){
			
			$highestRow = $worksheet->getHighestRow();
			$highestColumn = $worksheet->getHighestColumn();
			
			$header_column = '';
			foreach($columns as $key => $name){
				$hname = $worksheet->getCellByColumnAndRow($key, 1)->getValue();
				if($hname !== $name){
					$errors[] = $hname.': Column name mismatched, there should be "'.$name.'"!';
				}
				$header_column .= '<th>'. ucfirst(str_replace('_',' ', $hname))  . '</th>';
			}
			$header_data .= '<tr>'.$header_column.'</tr>';
			
			$prepare_data = array();
			
			$prepare_data['sale_id'] = $sale_id;
			$prepare_data['client_id'] = $saleInfo['client_id'];
			$prepare_data['sale_date'] = $saleInfo['sale_date'];
			$prepare_data['market_type'] = $saleInfo['market_type'];
			$prepare_data['market_category'] = $saleInfo['market_category'];
			$prepare_data['market_id'] = $saleInfo['market_id'];
			
			$prepare_data['added_by'] = $this->userID;
			$prepare_data['added_date'] = get_datetime('Y-m-d H:i:s');
			$prepare_data['action_microtime'] = microtime(true);
			$prepare_data['status'] = 'Active';
			
			 
			$total_qty = $total_weight = $total_amount = 0;
			$prepare_data_array = array();			
			for($row=2; $row<=$highestRow; $row++){
								
				$tr_column = '';
				$row_data = array('fish_code'=>'', 'fish_quantity'=>'0', 'fish_weight'=>'0', 'fish_rate'=>'0','total_amount'=>'0','remark'=>'', 'stock_type' => 'Current');	
				foreach($columns as $key => $column_name){
					$bdata = trim($worksheet->getCellByColumnAndRow($key, $row)->getValue());
					if($column_name == 'sale_from'){
						if(!in_array($bdata, $sale_from)){
							$errors[] = $bdata.' is not valid <strong>Sale From</strong> at line number '.$row.' There should be one of : '.implode(', ',$sale_from);
						}
					}else if($column_name == 'fish_code'){
						if(!in_array($bdata, $allFishCode)){
							$errors[] = $bdata.' is not valid <strong>fish code</strong> at line number '.$row;
						}
						$row_data['fish_code'] = $bdata;
						
					}else if($column_name == 'quantity'){
						if (preg_match('/^[0-9]+$/', $bdata)) {
							$total_qty  += $bdata;
							$row_data['fish_quantity'] = $bdata;
						}else{
							$errors[] = $bdata.' is not valid <strong>fish quantity</strong> at line number '.$row. ($bdata==''?' there should be 0 instead of blank':'');
						}		
					}else if($column_name == 'weight'){
						if(is_float($bdata) || is_numeric($bdata)){
							$total_weight  += $bdata;
							$row_data['fish_weight'] = $bdata;
						}else{
							$errors[] = $bdata.' is not valid <strong>Weight</strong> at line number '.$row;
						}
					}else if($column_name == 'rate'){
						if((is_float($bdata) || is_numeric($bdata)) && !empty($bdata)){
							$row_data['fish_rate'] = $bdata;
						}else{
							$errors[] = $bdata.' is not valid <strong>Fish Rate</strong> at line number '.$row;
						}						
					}else if($column_name == 'amount'){
						if((is_float($bdata) || is_numeric($bdata)) && !empty($bdata)){
							$total_amount  += $bdata;
							$row_data['total_amount'] = $bdata;
						}else{
							$errors[] = $bdata.' is not valid <strong>Amount</strong> at line number '.$row;
						}						
					}else if($column_name == 'remark'){
						if(!empty($bdata)){
							$row_data['remark'] = $bdata;
						}						
					}else if($column_name == 'stock_type'){
						if(!empty($bdata)){
							if(in_array($bdata, $stock_type)){
								$row_data['stock_type'] = $bdata;
							}else{
								$errors[] = $bdata.' is not valid <strong>Stock Type</strong> at line number '.$row. 
												' There should be one of: '.implode(',', $stock_type);
							}							
						}else{
							$errors[] = $bdata.' is not valid <strong>Stock Type</strong> at line number '.$row;
						}						
					}														
					$tr_column .= '<td>'. $bdata  . '</td>';									
				}
				
				$body_data .= '<tr>'.$tr_column.'</tr>';
				
				if($action_mode == 'save' && empty($errors)){
					$prepare_date_array[] = array_merge($prepare_data, $row_data);
				}	
			}
			
			$footer_data .= '<tr>';
			$footer_data .= '<th>Total:</th>';
			$footer_data .= '<th>&nbsp;</th>';
			$footer_data .= '<th>&nbsp;</th>';
			$footer_data .= '<th>'.$total_qty.'</th>';
			$footer_data .= '<th>'.$total_weight.'</th>';
			$footer_data .= '<th></th>';
			$footer_data .= '<th>'.$total_amount.'</th>';
			$footer_data .= '<th>&nbsp;</th>';
			$footer_data .= '</tr>';
			$footer_data .= $header_data;
			break;
		}
						
		if(!empty($errors)){
			$body_data = '<tr><td><div class="alert alert-danger"><h3>'.count($errors).' Error found!</h3><p>'.implode("<br>", $errors).'</p></div></td></tr>';
			$response_data = array('status' =>'error', 'message' => $body_data);
		}else if($action_mode == 'check'){
			
			$ext = pathinfo($file_path, PATHINFO_EXTENSION);
			$micro = md5(microtime(true)).'.'.$ext;
			$dst_path = FCPATH . 'excel_import_file/';
			if(!file_exists($dst_path) && !is_dir($dst_path)){
				@mkdir($dst_path, 0755, true);
			}
			$dst_path = $dst_path.$micro;
			@move_uploaded_file($file_path, $dst_path);
			$dst_path = encrypt_data($dst_path);
			$response_data = array('status' =>'success', 'header_data'=> $header_data, 'body_data'=> $body_data, 'footer_data'=> $footer_data, 
								 'file_path'=>$dst_path);
		
		}else if($action_mode == 'save' && !empty($prepare_date_array)){
			$this->db->insert_batch(SALE_ITEMS, $prepare_date_array);
			if(file_exists($file_path) && is_file($file_path)){
				@unlink($file_path);
			}
			$response_data = array('status' =>'success', 'message' => count($prepare_date_array) .' Items uploaded to Sale successfully! ');			
		}
		
		return $response_data;
				
	}
	
	function file_check($str){
        $allowed_ext = array('xls','xlsx');
		$file_name = @$_FILES['file']['name'];
		$ext = pathinfo($file_name, PATHINFO_EXTENSION);
		if(isset($file_name) && $file_name !=""){
            if(in_array($ext, $allowed_ext)){
                return TRUE;
            }else{
                $this->form_validation->set_message('file_check', 'Please select only xls/xlsx.');
                return false;
            }
        }else{
            $this->form_validation->set_message('file_check', 'Please choose a file to import.');
            return false;
        }
    }
	
	function __setFormRules($setRulesFor = ''){
		switch($setRulesFor){
			case'production_import':
				$this->form_validation->set_rules('production_id', 'Production id', 'trim|required|integer');
				$this->form_validation->set_rules('action_mode', 'Action mode', 'trim|required|in_list[check,save]');
				$action_mode = $this->input->post('action_mode');
				if($action_mode == 'check'){
					$this->form_validation->set_rules('file', 'Import File', 'trim|callback_file_check');
				}else{
					$this->form_validation->set_rules('file_path', 'Excel File Path', 'trim|required');
				}
			break;
			case'dispatch_box':
				$this->form_validation->set_rules('dispatch_id', 'Dispatch id', 'trim|required|integer');
				$this->form_validation->set_rules('action_mode', 'Action mode', 'trim|required|in_list[check,save]');
				$action_mode = $this->input->post('action_mode');
				if($action_mode == 'check'){
					$this->form_validation->set_rules('file', 'Import File', 'trim|callback_file_check');
				}else{
					$this->form_validation->set_rules('file_path', 'Excel File Path', 'trim|required');
				}
			break;
			case'import_data':
				$this->form_validation->set_rules('data_id', 'Data id', 'trim|required|integer');
				$this->form_validation->set_rules('data_type', 'Data Type', 'trim|required');
				$this->form_validation->set_rules('action_mode', 'Action mode', 'trim|required|in_list[check,save]');
				$action_mode = $this->input->post('action_mode');
				if($action_mode == 'check'){
					$this->form_validation->set_rules('file', 'Import File', 'trim|callback_file_check');
				}else{
					$this->form_validation->set_rules('file_path', 'Excel File Path', 'trim|required');
				}
			break;
			
			
		}
		
		if (!$this->input->is_ajax_request()) {
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button><i class="fa fa-times-circle"></i> ', '</div>');
        }
		
		return $this->form_validation->run($this);
	}
}

?>
<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Bulk_action extends MY_Controller {
	var $userID, $userGroup, $userInfo, $datetime;
    public function __construct() {
        parent::__construct();
		$this->userID = checkUserLogin();
		$this->userInfo = loginUserInfo();
		$this->datetime = get_datetime('l, d M, Y h:i A');
		$this->userGroup = loginUserInfo('group_id');
    }
	
	public function action(){
		$postData = $this->input->post();						
		$action = $this->uri->segment(3);
		$table_name = $this->input->post("table_name", TRUE);
		$field_name = $this->input->post("column_name", TRUE);
		$primary_key = $this->input->post("primary_key", TRUE);	
		$id_array = $this->input->post("items", TRUE);
		//$items = rtrim($this->input->post("items", TRUE), '|');
		//$id_array = ($items) ? explode("|", $items) : '';
		
		if($id_array != '' && $table_name !='' && $primary_key !=''){
			switch($action){
				case 'delete':
					$this->db->where_in($primary_key, $id_array);
					$this->db->delete($table_name);					
					$result = array('success'=>'true', 'success_message'=> count($id_array).' Items successfully deleted!');
				break;
				case 'publish':
					$this->db->where_in($primary_key, $id_array);
					$this->db->update($table_name, array($field_name => 'Publish', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true))); //1					
					$result = array('success'=>'true', 'success_message'=>  count($id_array).' Items successfully published!');
				break;
				case 'unpublish':
					$this->db->where_in($primary_key, $id_array);
					$this->db->update($table_name, array($field_name => 'Unpublish', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true))); //2
					$result = array('success'=>'true', 'success_message'=>  count($id_array).' Items successfully unpublished!');
				break;
				case 'active':
					$this->db->where_in($primary_key, $id_array);
					$this->db->update($table_name, array($field_name => 'Active', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true))); //3
					
					if($table_name == PRODUCT_BOX){
						$this->db->where_in('dispatch_id', $id_array);
						$this->db->update(PRODUCT_BOX_ITEMS, array('status' => 'Active', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true))); //4
					}
					
					$result = array('success'=>'true', 'success_message'=>  count($id_array).' Items successfully Activated!');
				break;
				case 'move_to_prepare':
					//Update all PRODUCT_DISPATCH status to "Active"
					$result = $this->db->where_in($primary_key, $id_array)->update(PRODUCT_DISPATCH, array($field_name => 'Active', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true))); //5
					if(!empty($result)){
						$dispatch_data = $this->db->select('dispatch_id, dr_number')->where_in('dispatch_id', $id_array)->get($table_name)->result_array();
						foreach($dispatch_data as $dispatch){
							$dispatch_id 	= $dispatch['dispatch_id'];
							$dr_number 		= $dispatch['dr_number'];
							$all_boxes = $this->db->select('box_number')->where(array('dispatch_id'=>$dispatch_id, 'dr_number'=>$dr_number))->get(PRODUCT_DISPATCH_BOX)->result_array();
							$all_boxes_array = array_column($all_boxes, 'box_number');
							if(!empty($all_boxes_array) && count($all_boxes_array) > 0){
								//Update all PRODUCT_BOX and PRODUCT_BOX_ITEMS status to "Prepared"
								$this->db->where_in('box_number', $all_boxes_array)->update(PRODUCT_BOX, array('status' => 'Prepared', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true))); //6
								$this->db->where_in('box_number', $all_boxes_array)->update(PRODUCT_BOX_ITEMS, array('status' => 'Prepared', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true))); //7
								
								//Update all PRODUCT_DISPATCH_BOX status to "Active"
								$this->db->where(array('dispatch_id'=>$dispatch_id, 'dr_number'=>$dr_number))->update(PRODUCT_DISPATCH_BOX, array('status'=>'Active', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true))); //8
							}
						}
					}
					$result = array('success'=>'true', 'success_message'=>  count($id_array).' Items successfully Active!');
				break;
				case 'move_to_dispatch':
					//Update all PRODUCT_DISPATCH status to "Dispatched"
					$result = $this->db->where_in($primary_key, $id_array)->update(PRODUCT_DISPATCH, array($field_name => 'Dispatched', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true))); //9
					if(!empty($result)){
						$dispatch_data = $this->db->select('dispatch_id, dr_number')->where_in('dispatch_id', $id_array)->get($table_name)->result_array();
						foreach($dispatch_data as $dispatch){
							$dispatch_id 	= $dispatch['dispatch_id'];
							$dr_number 		= $dispatch['dr_number'];
							$all_boxes = $this->db->select('box_number')->where(array('dispatch_id'=>$dispatch_id, 'dr_number'=>$dr_number))->get(PRODUCT_DISPATCH_BOX)->result_array();
							$all_boxes_array = array_column($all_boxes, 'box_number');
							$this->update_dispatch_summary($all_boxes_array, $dispatch_id, $dr_number);
							if(!empty($all_boxes_array) && count($all_boxes_array) > 0){
								//Update all PRODUCT_BOX and PRODUCT_BOX_ITEMS status to "Dispatched"
								$this->db->where_in('box_number', $all_boxes_array)->update(PRODUCT_BOX, array('status' => 'Dispatched', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true))); //10
								$this->db->where_in('box_number', $all_boxes_array)->update(PRODUCT_BOX_ITEMS, array('status' => 'Dispatched', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true))); //11
								
								//Update all PRODUCT_DISPATCH_BOX status to "Dispatched"
								$this->db->where(array('dispatch_id'=>$dispatch_id, 'dr_number'=>$dr_number))->update(PRODUCT_DISPATCH_BOX, array('status'=>'Dispatched', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true))); //12
							}
						}
					}
					$result = array('success'=>'true', 'success_message'=>  count($id_array).' Items successfully Dispatched!');
				break;
				case 'inactive':
					$this->db->where_in($primary_key, $id_array);
					$this->db->update($table_name, array($field_name => 'Inactive', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true))); //13				
					$result = array('success'=>'true', 'success_message'=>  count($id_array).' Items successfully Inactivated!');
				break;
				case 'activate':
					$this->db->where_in($primary_key, $id_array);
					$this->db->update($table_name, array($field_name => 'Activate', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true))); //14
					$result = array('success'=>'true', 'success_message'=> count($id_array).' Items successfully Activated!');
				break;
				case 'deactivate':
					$this->db->where_in($primary_key, $id_array);
					$this->db->update($table_name, array($field_name => 'Deactivate', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true))); //15
					$result = array('success'=>'true', 'success_message'=> count($id_array).' Items successfully Deactivated!');
				break;
				case 'mark_delete':
					if($this->userGroup != 1){
						$this->db->where('added_by', $this->userID);
					}
					$this->db->where_in($primary_key, $id_array);
					$this->db->update($table_name, array($field_name => 'Deleted', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true))); //16
					/*
					//Note: Mark delete is replaced with mark cancel for SALE table
					if($table_name == SALE){
						$this->db->where_in('sale_id', $id_array)->update(SALE_ITEMS, array('status' => 'Deleted'));
						foreach($id_array as $key => $value){
							$userActivityLogArray = array(
								'activity_by'			=> $this->userID,
								'activity' 				=> 'Delete',
								'table_name'			=> 'SALE,SALE_ITEMS',
								'data_id'				=> $value,
								'description' 			=> $this->userInfo['first_name'].' '.$this->userInfo['last_name'].' deleted sale data on '.$this->datetime,
								'activity_url'			=> 'bulk_action/action/mark_delete',
								'activity_date'			=> get_datetime('Y-m-d H:i:s')
							 );
							$this->db->insert(USERS_ACTIVITY_LOG, $userActivityLogArray);
						}
					}
					*/
					if($table_name == FREE_SALE){
						if($this->userGroup != 1){
							$this->db->where('added_by', $this->userID);
						}
						$this->db->where_in('free_sale_id', $id_array)->update(FREE_SALE_ITEMS, array('status' => 'Deleted', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true))); //17
						
						foreach($id_array as $key => $value){
							$userActivityLogArray = array(
								'activity_by'			=> $this->userID,
								'activity' 				=> 'Delete',
								'table_name'			=> 'FREE_SALE,FREE_SALE_ITEMS',
								'data_id'				=> $value,
								'description' 			=> $this->userInfo['first_name'].' '.$this->userInfo['last_name'].' deleted free sale data on '.$this->datetime,
								'activity_url'			=> 'bulk_action/action/mark_delete',
								'activity_date'			=> get_datetime('Y-m-d H:i:s')
							 );
							$this->db->insert(USERS_ACTIVITY_LOG, $userActivityLogArray);
						}
					}
					$result = array('success'=>'true', 'success_message'=> count($id_array).' Items successfully Deleted!');
				break;
				case 'mark_cancel':
					if($this->userGroup != 1){$this->db->where('added_by', $this->userID);}
					$this->db->where_in($primary_key, $id_array);
					$this->db->update($table_name, array($field_name => 'Cancelled', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true))); //18
					if($table_name == SALE){						
						foreach($id_array as $sale_id){
							$carret_number = $this->db->select('carret_number')->group_by('carret_number')->get_where(SALE_ITEMS, array('sale_id'=>$sale_id))->result_array();
							if(!empty($carret_number)){
								$carret_number = array_column($carret_number, 'carret_number');
								if($this->userGroup != 1){$this->db->where('added_by', $this->userID);}
								$this->db->where_in('carret_number', $carret_number)->update(PRODUCTION_CARRET_ITEMS, array('client_id'=>'0', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true))); //19
								if($this->userGroup != 1){$this->db->where('added_by', $this->userID);}
								$this->db->where_in('carret_number', $carret_number)->update(PRODUCTION_CARRET, array('client_id'=>'0', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true))); //20
							}
						}
						if($this->userGroup != 1){$this->db->where('added_by', $this->userID);}
						$this->db->where_in('sale_id', $id_array)->delete(SALE_ITEMS);
						
						foreach($id_array as $key => $value){
							$userActivityLogArray = array(
								'activity_by'			=> $this->userID,
								'activity' 				=> 'Cancelled',
								'table_name'			=> 'SALE',
								'data_id'				=> $value,
								'description' 			=> $this->userInfo['first_name'].' '.$this->userInfo['last_name'].' cancelled sale data on '.$this->datetime,
								'activity_url'			=> 'bulk_action/action/mark_cancel',
								'activity_date'			=> get_datetime('Y-m-d H:i:s')
							 );
							$this->db->insert(USERS_ACTIVITY_LOG, $userActivityLogArray);
						}
					}					
					$result = array('success'=>'true', 'success_message'=> count($id_array).' Items successfully cancelled!');
				break;
				
				case 'mark_active':
					$this->db->where_in($primary_key, $id_array);
					$this->db->update($table_name, array($field_name => 'Active', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true))); //21
					if($table_name == SALE){
						foreach($id_array as $key => $value){
							$userActivityLogArray = array(
								'activity_by'			=> $this->userID,
								'activity' 				=> 'Active',
								'table_name'			=> 'SALE',
								'data_id'				=> $value,
								'description' 			=> $this->userInfo['first_name'].' '.$this->userInfo['last_name'].' activated sale data on '.$this->datetime,
								'activity_url'			=> 'bulk_action/action/mark_active',
								'activity_date'			=> get_datetime('Y-m-d H:i:s')
							 );
							$this->db->insert(USERS_ACTIVITY_LOG, $userActivityLogArray);
						}
					}
					$result = array('success'=>'true', 'success_message'=> count($id_array).' Items successfully activated!');
				break;
				
				case 'delete_dispatch':
					foreach($id_array as $dispatch_id){
						$dr_data = $this->db->where(array('dispatch_id'=>$dispatch_id, 'status'=>'Active', 'added_by'=>$this->userID))->get(PRODUCT_DISPATCH)->result_array();
						if(!empty($dr_data) && isset($dr_data[0]['dispatch_id']) && !empty($dr_data[0]['dispatch_id'])){
							//Delete only when this dr_number is not found in SALE table.
							$dr_count = $this->db->where('dr_number', $dr_data[0]['dr_number'])->count_all_results(SALE);
							
							if($dr_count == 0){
								//Update all PRODUCT_DISPATCH and PRODUCT_DISPATCH_BOX status to "Deleted"
								$result = $this->db->where('dispatch_id', $dispatch_id)->update(PRODUCT_DISPATCH, array('status' => 'Deleted', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true))); //22				
								$this->db->where('dispatch_id', $dispatch_id)->update(PRODUCT_DISPATCH_BOX, array('status' => 'Deleted', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true))); //23
								
								$box_numbers = $this->db->select('box_number')->where_in('dispatch_id', $dispatch_id)->get(PRODUCT_DISPATCH_BOX)->result_array();
								$box_numbers_array = array_column($box_numbers, 'box_number');
								if(!empty($box_numbers_array) && count($box_numbers_array) > 0){
									//Update all PRODUCT_BOX and PRODUCT_BOX_ITEMS status to "Active"
									$this->db->where_in('box_number', $box_numbers_array)->update(PRODUCT_BOX, array('status' => 'Active', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true))); //24
									$this->db->where_in('box_number', $box_numbers_array)->update(PRODUCT_BOX_ITEMS, array('status' => 'Active', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true))); //25
								}
								
								$userActivityLogArray = array(
											'activity_by'			=> $this->userID,
											'activity' 				=> 'Delete',
											'table_name'			=> 'PRODUCT_DISPATCH,PRODUCT_DISPATCH_BOX',
											'data_id'				=> $dispatch_id,
											'description' 			=> $this->userInfo['first_name'].' '.$this->userInfo['last_name'].' deleted prepared dispatch data on '.$this->datetime,
											'activity_url'			=> 'bulk_action/action/mark_delete',
											'activity_date'			=> get_datetime('Y-m-d H:i:s')
										 );
								$this->db->insert(USERS_ACTIVITY_LOG, $userActivityLogArray);
							}
						}
					}
					$result = array('success'=>'true', 'success_message'=> count($id_array).' Items successfully Deleted!');
				break;
				case 'lock':
					$data = array($field_name => 'Lock', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true));
					$this->db->where_in($primary_key, $id_array)->update($table_name, $data); //26
					$result = array('success'=>'true', 'success_message'=> count($id_array).' Data successfully locked!');
				break;
				case 'unlock':
					$data = array($field_name => 'Unlock', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true));
					$this->db->where_in($primary_key, $id_array)->update($table_name, $data); //27
					$result = array('success'=>'true', 'success_message'=> count($id_array).' Data successfully unlocked!');
				break;
				case 'cancel':
					$this->db->where_in($primary_key, $id_array);
					$this->db->update($table_name, array($field_name => 'Cancelled', 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true)));	//28
					$result = array('success'=>'true', 'success_message'=>  count($id_array).' Items successfully cancelled!');
				break;
			}
			echo json_encode($result);
		}else{
		   echo 'Kindly Select Atleast One Item!';
		}
	   die();
	}
	
	public function update_dispatch_summary($all_boxes_array, $dispatch_id, $dr_number){
		$summary = array('total_box' 		=> 0,
						 'total_box_qty' 	=> 0,
						 'total_carret_wt'  => 0,
						 'total_box_wt' 	=> 0, 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true)
						 );
		
		$this->db->select('IFNULL(SUM(fish_qty),0) as total_box_qty,
						   IFNULL(SUM(fish_wt),0) as total_carret_wt,
						   IFNULL(SUM(box_wt),0) as total_box_wt
						  ');		
		$this->db->where_in('box_number',$all_boxes_array);
		$result = $this->db->get(PRODUCT_BOX_ITEMS)->result_array();
		if(!empty($result)){
			$summary = array('total_box' 		=> count($all_boxes_array),
							 'total_box_qty'  	=> $result[0]['total_box_qty'],
							 'total_carret_wt'  => $result[0]['total_carret_wt'],
							 'total_box_wt' 	=> $result[0]['total_box_wt'], 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true)
							 );
		}
		$this->db->where(array('dispatch_id'=>$dispatch_id, 'dr_number'=>$dr_number))->update(PRODUCT_DISPATCH, $summary); //29
	}
}
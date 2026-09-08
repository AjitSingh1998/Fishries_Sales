<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sync_data extends MY_Controller {
	var $client;
	var $last_sync_time;
	function __construct(){
		parent::__construct();
		$this->userID = checkUserLogin();
		$this->load->model('Sync_data_model', 'SDM');
		$this->load->library('guzzle/guzzle');
		//echo SALES_API_URL , SALES_API_KEY; die;
		$this->client = new GuzzleHttp\Client(array('base_uri' =>SALES_API_URL, 'headers'=>array('X-API-KEY'=>SALES_API_KEY . 'manoj'), 'timeout' => 2.0, 'connect_timeout' => 2.0));
		$this->last_sync_time = date('2018-08-17');
	}
	
	function generate_key(){
		try{		
			$response = $this->client->get('api/key/new_key');
			echo $result = $response->getBody();	
			
		} catch (GuzzleHttp\Exception\BadResponseException $e) {
			$response = $e->getResponse();
			$responseBodyAsString = $response->getBody();
			printr($responseBodyAsString);
			$response_data = json_decode($responseBodyAsString, true);
			echo $tablesInfo = json_decode($response_data, true);
		}
	}
		
	function index(){
		redirect('sync_data/local');
	}
	
	function local(){
		$data['content_view'] = 'sync_data/sync_data_v';
		$data['page_title'] = 'Sync Local data to live';
		$data['table_url'] = 'sync_data/ajax_local_data';
		$data['sync_url'] = 'sync_data/export';
		$data['tablesInfo'] = $this->SDM->getTableInfo();	
		
		$data['tablesInfo'] = $this->load->view('sync_data/sync_table_list_v', $data, true);
		
		$this->template->set('scriptsrc', 
			array(base_url('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js'), site_url('sync_data/assets/js/sync_data.js'))
		);
		
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($data);
	}
	
	function ajax_local_data(){
		$data_type = $this->input->get('data_type');
		$sync_date = $this->input->get('sync_date');
		$data['sync_url'] = 'sync_data/export';
		$data['tablesInfo'] = $this->SDM->getTableInfo($data_type, $sync_date);
		$output = $this->load->view('sync_data/sync_table_list_v', $data, true);		
		$response_data = array('data' => $output);
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($response_data)); 
	}	
	
	function export($table_name, $last_sync){	
		$response_data = array('status' => 'error', 'message' => 'Request failed: please try again!');
		try {
			$data_type = $this->input->get('data_type');
			$sync_date = $this->input->get('sync_date');
			$page = $this->input->get('page');
			$synced_rows = $this->input->get('srows')? $this->input->get('srows') : 0;
			$limit = $this->input->get('limit') ? $this->input->get('limit') : 200;
			$offset = (($page - 1) * $limit);// + $synced_rows;
			
			if($sync_date != ''){
				$sync_date = str_replace('/','-',$sync_date);
				$last_sync = strtotime($sync_date);
			}
			
			$keys = $this->db->query("SHOW KEYS FROM ".$table_name." WHERE Key_name = 'PRIMARY'")->result_array();
			$primary_key = '';
			if(!empty($keys)){
				$primary_key = $keys[0]['Column_name'];
			}
			$total_records = $this->db->where(array('action_microtime >' => $last_sync))->count_all_results($table_name);			
			
			//$total_records = $this->db->count_all_results($table_name);			
			//$result = $this->db->limit($limit, $offset)->get_where($table_name, array('action_microtime >' => $last_sync));
			/*if($table_name == 'psac_product_dispatch_box'){
				$this->db->where('ID <', '201');
			}*/
			
			$this->db->where(array('action_microtime >' => $last_sync));
			$this->db->limit($limit, $offset);
			$this->db->order_by($primary_key, 'ASC');
			$result = $this->db->get($table_name);
			$rows = (int)$result->num_rows();
			$response_data = array();			
			if($rows > 0){
				$row_data = $result->result_array();
				$tname = str_replace($this->db->dbprefix, '', $table_name);
				$response = $this->client->post(
					'sync_data/import', 
					array('json' => array('data'=>$row_data, 'table_name'=>$tname, 'primary_key'=>$primary_key, 'data_type' => $data_type))
				);
				$result = $response->getBody();
				$result =json_decode($result, true);		
				if($result){
					$process = (($offset+$rows) == $total_records) ? 'completed' : 'continue';
					$history = array('synced_time'=> date('Y-m-d H:i:s'),'synced_rows' =>($offset+$rows), 'sync_type' => 'Local','table_name' => $table_name);
					
					if($process == 'completed'){
						if($this->db->where(array('sync_type' => $data_type, 'table_name' => $table_name))->count_all_results(SYNC_TABLE_HISTORY) > 0){
							$this->db->where(array('sync_type' => $data_type, 'table_name' => $table_name))->update(SYNC_TABLE_HISTORY, $history);
						}else{
							$this->db->insert(SYNC_TABLE_HISTORY, $history);
						}
						$sncData = array('last_sync_date'=> date('Y-m-d H:i:s'), 'total_rows' => ($offset+$rows), 'sync_status' => 'Complete', 'action_microtime' => microtime(true));
						$this->db->where('table_name', $table_name)->update(SYNC_TABLE_INFO, $sncData);
					}else{
						
						if($this->db->where(array('sync_type' => $data_type, 'table_name' => $table_name))->count_all_results(SYNC_TABLE_HISTORY) > 0){
							$this->db->where(array('sync_type' => $data_type, 'table_name' => $table_name))->update(SYNC_TABLE_HISTORY, $history);
						}else{
							$this->db->insert(SYNC_TABLE_HISTORY, $history);
						}
					}
					
					$response_data = array('status' => 'success', 'process' => $process, 'next_page' => ($page+1), 'message' => $offset+$rows . ' data synced out of '.$total_records, 'server_response' => $result);
					
				}else{
					$sncData = array('sync_status' => 'Incomplete', 'action_microtime' => microtime(true));
					$this->db->where('table_name', $table_name)->update(SYNC_TABLE_INFO, $sncData);
					$response_data = array('status' => 'error', 'message' => 'Live server not responding', 'server_response' => $result);
				}
			}else{
				$response_data = array('status' => 'success', 'process' => 'completed', 'next_page' => 0, 'message' => 'Data not found to sync');
			}
			
					
		} catch (GuzzleHttp\Exception\BadResponseException $e) {
			$response = $e->getResponse();
			$responseBodyAsString = $response->getBody();
			$response_data = json_decode($responseBodyAsString, true);
		}
		
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($response_data)); 
	}
	
	function live(){
		
		/*$this->db->group_start();
		$this->db->or_where(array('client_key' => 1));
		$this->db->or_where(array('client_key' => '0'));
		$this->db->group_end();
		$this->db->where(array('action_microtime >=' => 'sasas'));
		$total_records = $this->db->count_all_results(CLIENT);
		echo $this->db->last_query();
		die;*/
		
		$tablesInfo = array();
		try{
			$response = $this->client->get('sync_data/tables');
			$result = $response->getBody();			
			$tablesInfo = json_decode($result, true);
		}catch (Exception $e) {
			$tablesInfo = array();
		}
		if(!is_array($tablesInfo)){
			$tablesInfo = array();
		}
		$data['content_view'] = 'sync_data/sync_data_v';
		$data['page_title'] = 'Sync Live data to Local';
		$data['table_url'] = 'sync_data/ajax_live_data';	
		$data['sync_url'] = 'sync_data/import';
		
		$data['tablesInfo'] = $tablesInfo;
		$data['tablesInfo'] = $this->load->view('sync_data/sync_table_list_v', $data, true);
		
		$this->template->set('scriptsrc', 
			array(base_url('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js'), site_url('sync_data/assets/js/sync_data.js'))
		);
		
		$this->template->set('document_title', $data['page_title']);
		$this->template->layout($data);
	}
	
	function ajax_live_data(){
		$data_type = $this->input->get('data_type');
		$sync_date = $this->input->get('sync_date');
		
		$tablesInfo = array();
		try{
			$response = $this->client->get('sync_data/tables?dtype='.$data_type.'&sdate='.$sync_date);
			$result = $response->getBody();			
			$tablesInfo = json_decode($result, true);
		}catch (Exception $e) {
			$tablesInfo = array();
		}
		if(!is_array($tablesInfo)){
			$tablesInfo = array();
		}
		$data['sync_url'] = 'sync_data/import';
		$data['tablesInfo'] = $tablesInfo;
		$output = $this->load->view('sync_data/sync_table_list_v', $data, true);
				
		$response_data = array('data' => $output);
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($response_data)); 
	}
		
	function import($table_name){
		$response_data = array('status' => 'error', 'message' => 'Request failed: please try again!');
		$result = $this->___export_data_from_live($table_name);
		if($result){
			
			$total_records = $result['total_records'];
			$process = $result['process'];
			$rows = $result['rows'];
			$row_data = $result['data'];
			$page = $this->input->get('page');
			$data_type = $this->input->get('data_type');
			
			$status = array('status'=>'success'); //$this->___import_live_data($table_name, $row_data);
			if(isset($status) && $status['status'] == 'success' ){			
				$history = array('synced_time'=> date('Y-m-d H:i:s'), 'synced_rows' =>($offset+$rows), 'sync_type' => $data_type, 'table_name' => $table_name);
				if($process == 'completed'){
					if($this->db->where(array('sync_type' => $data_type, 'table_name' => $table_name))->count_all_results(SYNC_TABLE_HISTORY) > 0){
						$this->db->where(array('sync_type' => $data_type, 'table_name' => $table_name))->update(SYNC_TABLE_HISTORY, $history);
					}else{
						$this->db->insert(SYNC_TABLE_HISTORY, $history);
					}
					$sncData = array('last_sync_date'=> date('Y-m-d H:i:s'), 'total_rows' => ($offset+$rows), 'sync_status' => 'Complete', 'action_microtime' => microtime(true));
					$this->db->where('table_name', $table_name)->update(SYNC_TABLE_INFO, $sncData);
				}else{
					
					if($this->db->where('table_name', $table_name)->count_all_results(SYNC_TABLE_HISTORY) > 0){
						$this->db->where('table_name', $table_name)->update(SYNC_TABLE_HISTORY, $history);
					}else{
						$this->db->insert(SYNC_TABLE_HISTORY, $history);
					}
				}

				
				
				
				$history = array('synced_time'=> date('Y-m-d H:i:s'),'synced_rows' =>($offset+$rows), 'sync_type' => 'Live', 'table_name' => $table_name);
				$response_data = array('query'=>$result['query'], 'status' => 'success', 'process' => $process, 'next_page' => ($page+1), 'message' => $rows . ' data synced out of '.$total_records);
			}else{
				$sncData = array('sync_status' => 'Incomplete', 'action_microtime' => microtime(true));
				$this->db->where('table_name', $table_name)->update(SYNC_TABLE_INFO, $sncData);
				$response_data = array('status' => 'error', 'message' => 'Live to local sync process failed!', 'server_response' => $result);
			}
			
		}else{
			$response_data = array('status' => 'success', 'process' => 'completed', 'next_page' => 0, 'message' => 'Live server not responding', 'server_response' => $result);
		}		
		
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($response_data));
	}
	
	function ___export_data_from_live($table_name){
		
		try {			
			$page = $this->input->get('page');
			$synced_rows = $this->input->get('srows')? $this->input->get('srows') : 0;
			$limit = $this->input->get('limit') ? $this->input->get('limit') : 100;
			$offset = (($page - 1) * $limit) + $synced_rows;
			
			$sync_date = $this->input->get('sync_date');
			if($sync_date != ''){
				$last_sync = strtotime($sync_date) * 1000;
			}
			
			$params = array('tname'=>$table_name, 'sdate' => $last_sync, 'offset' => $offset, 'limit' => $limit);				
			$response = $this->client->get('sync_data/export?'.http_build_query($params));
			$response_status = $response->getStatusCode(); // "200"
			$response_header =  $response->getHeader('content-type'); // 'application/json; charset=utf8'
			$response_data = $response->getBody(); // {"type":"User"...'}			
			return $result =json_decode($response_data, true);
		
		}catch(GuzzleHttp\Exception\BadResponseException $e){
			$response = $e->getResponse();
			$responseBodyAsString = $response->getBody();
			return $response_data = json_decode($responseBodyAsString, true);
		}
	}
	
	function ___import_live_data($table_name, $row_data){
		
		$result =  array('status' => 'error', 'Message' => ' No aperation performed!');
		
		$keys = $this->db->query("SHOW KEYS FROM ".$table_name." WHERE Key_name = 'PRIMARY'")->result_array();
		$primary_key = '';
		if(!empty($keys)){
			$primary_key = $keys[0]['Column_name'];
		}
		
		$data_type = $this->input->get('data_type');
		if($data_type == 'Master'){
			$result = $this->SDM->master_data($row_data, $table_name, $primary_key);
		}else{
			$tname = str_replace($this->db->dbprefix, '', $table_name);
			if(method_exists($this->SDM, $tname)){
				$result = $this->SDM->$tname($row_data, $table_name, $primary_key);
			}else{
				$result =  array('status' => 'error', 'Message' => $table_name. ' Table method not exists!');
			}
		}
		return $result;	
	}
}

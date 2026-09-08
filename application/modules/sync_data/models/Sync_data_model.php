<?php
class Sync_data_model extends CI_Model {
	
	public function __construct(){
		parent::__construct();
    }
		
	function master_data($data, $table_name, $primary_key){
		$table_name = $this->db->dbprefix.$table_name;
		if(!empty($data)){
			
			$local_primary_data = array();
			foreach($data as $v){
				$local_primary_data[] = $v[$primary_key];
			}
			// check local data existance in live database and get primary data of the table
			$existance = $this->db->select($primary_key)->where_in($primary_key, $local_primary_data)->get($table_name)->result_array();
			$live_primary_data = array();
			if(!empty($existance)){				
				foreach($existance as $pd){
					$live_primary_data[] = $pd[$primary_key];
				}
			}
			// prepare local data to insert into live database	
			$insertData = array();
			$updateData = array();		
			foreach($data as $v){
				$v['transaction_id'] = 	$v[$primary_key];
				$v['client_key'] = 	$this->clientID;
				if(in_array($v[$primary_key], $live_primary_data)){
					$updateData[] = $v;
				}else{
					$insertData[] = $v;
				}
			}
			$affected_rows = 0;
			if(!empty($updateData)){
				$this->db->update_batch($table_name, $updateData, $primary_key);
				$affected_rows += $this->db->affected_rows();
				if(empty($insertData)){
					return  count($updateData) . ' Data transfered successfully!';
				}
			}
			
			if(!empty($insertData)){
				$result = $this->db->insert_batch($table_name, $insertData);
				$affected_rows += $this->db->affected_rows();		
				return  $affected_rows . ' Data transfered successfully!';
			}
			
		}
		return false;
		
	}
	
	function sale($data, $table_name, $primary_key){
		$table_name = $this->db->dbprefix.$table_name;
		if(!empty($data)){
			$local_primary_data = array();
			$local_dr_number = array();
			foreach($data as $v){
				$local_primary_data[] = $v[$primary_key];				
				if($v['dr_number'] != '' && $v['dr_number'] != 0 ){
					$local_dr_number[] = $v['dr_number'];
				}				
			}
			$local_dr_number = array_unique($local_dr_number);
			
			// check local data existance in live database and get primary data of the table
			$live_primary_data = get_live_primary_data($table_name, $primary_key, $local_primary_data, $this->clientID);
			$live_dr_number = array();
			if(!empty($local_dr_number)){
				$live_dr_number = get_live_primary_data($this->db->dbprefix.'product_dr', 'dr_number', $local_dr_number, $this->clientID);			
			}
			
			// prepare local data to insert into live database			
			$insertData = array();
			$updateData = array();		
			foreach($data as $v){							
				if(isset($live_dr_number[$v['dr_number']]) && !empty($live_dr_number[$v['dr_number']])){
					$v['dr_number'] = 	$live_dr_number[$v['dr_number']];
				}				
				$v['transaction_id'] = 	$v[$primary_key];
				$v['client_key'] = 	$this->clientID;
				$v['source'] = 'Local';
				$pid = $v[$primary_key];
				unset($v[$primary_key]);
				if(array_key_exists($pid, $live_primary_data)){
					$v[$primary_key] = $live_primary_data[$pid];
					$updateData[] = $v;
				}else{
					$insertData[] = $v;
				}
			}		
					
			$affected_rows = 0;
			if(!empty($updateData)){
				$this->db->update_batch($table_name, $updateData, $primary_key);
				$affected_rows += $this->db->affected_rows();
				if(empty($insertData)){
					return  count($updateData) . ' Data transfered successfully!';
				}
			}
			
			if(!empty($insertData)){
				$result = $this->db->insert_batch($table_name, $insertData);
				$affected_rows += $this->db->affected_rows();		
				return  $affected_rows . ' Data transfered successfully!';
			}
			
		}
		return false;
		
	}
	
	function sale_items($data, $table_name, $primary_key){
		$table_name = $this->db->dbprefix.$table_name;
		if(!empty($data)){
			$local_sale_id = array();
			$local_carret_number = array();
			foreach($data as $v){
				$local_sale_id[] = $v['sale_id'];
				if($v['carret_number'] !== 0){
					$local_carret_number[] = $v['carret_number'];
				}
				
			}
			$local_sale_id = array_unique($local_sale_id);
			$local_carret_number = array_unique($local_carret_number);
			
			// check local data existance in live database and get primary data of the table
			$live_carret_number = array();
			if(!empty($local_carret_number)){
				$live_carret_number = get_live_primary_data($this->db->dbprefix.'production_carret', 'carret_number', $local_carret_number, $this->clientID);
			}
			$sales = $this->db->select('sale_id, transaction_id')->where('client_key', $this->clientID)->where_in('transaction_id', $local_sale_id)->get($this->db->dbprefix.'sale')->result_array();
						
			$live_sale_data = array();
			if(!empty($sales)){
				$live_sale_id = array();
				foreach($sales as $id){
					$live_sale_id[] = $id['sale_id'];
					$live_sale_data[$id['transaction_id']] = $id['sale_id'];			
				}
				if(!empty($live_sale_id)){
					$this->db->where_in('sale_id', $live_sale_id)->delete($table_name);	
				}
			}
			
			
			// prepare local data to insert into live database	
			$insertData = array();
			foreach($data as $v){
				if(isset($live_carret_number[$v['carret_number']]) && !empty($live_carret_number[$v['carret_number']])){
					$v['carret_number'] = 	$live_carret_number[$v['carret_number']];
				}
				$v['sale_id'] = 	$live_sale_data[$v['sale_id']];
				$v['transaction_id'] = 	$v[$primary_key];
				$v['client_key'] = 	$this->clientID;
				$v['source'] = 	'Local';
				unset($v[$primary_key]);
				$insertData[] = $v;				
			}
						
			if(!empty($insertData)){
				$result = $this->db->insert_batch($table_name, $insertData);				
				return $this->db->affected_rows() . ' Data transfered successfully!';
			}
			
		}
		return false;
		
	}
	
	function sale_expenditure($data, $table_name, $primary_key){
		$table_name = $this->db->dbprefix.$table_name;
		if(!empty($data)){
			$local_sale_id = array();
			foreach($data as $v){
				$local_sale_id[] = $v['sale_id'];
			}
			$local_sale_id = array_unique($local_sale_id);
			// check local data existance in live database and get primary data of the table
			$sales = $this->db->select('sale_id, transaction_id')->where_in('transaction_id', $local_sale_id)->get($this->db->dbprefix.'sale')->result_array();
			$live_sale_data = array();
			if(!empty($sales)){
				$live_sale_id = array();
				foreach($sales as $id){
					$live_sale_id[] = $id['sale_id'];
					$live_sale_data[$id['transaction_id']] = $id['sale_id'];			
				}
				if(!empty($live_sale_id)){
					$this->db->where_in('sale_id', $live_sale_id)->delete($table_name);	
				}
			}
			
			// prepare local data to insert into live database	
			$insertData = array();
			foreach($data as $v){
				$v['sale_id'] = 	$live_sale_data[$v['sale_id']];
				$v['transaction_id'] = 	$v[$primary_key];
				$v['client_key'] = 	$this->clientID;
				$v['source'] = 	'Local';
				unset($v[$primary_key]);
				$insertData[] = $v;				
			}
						
			if(!empty($insertData)){
				$result = $this->db->insert_batch($table_name, $insertData);				
				return $this->db->affected_rows() . ' Data transfered successfully!';
			}
			
		}
		return false;
		
	}
	
	function sale_challan_detail($data, $table_name, $primary_key){
		$table_name = $this->db->dbprefix.$table_name;
		if(!empty($data)){
			$local_sale_id = array();
			foreach($data as $v){
				$local_sale_id[] = $v['sale_id'];
			}
			$local_sale_id = array_unique($local_sale_id);
			// check local data existance in live database and get primary data of the table
			$sales = $this->db->select('sale_id, transaction_id')->where_in('transaction_id', $local_sale_id)->get($this->db->dbprefix.'sale')->result_array();
			$live_sale_data = array();
			if(!empty($sales)){
				$live_sale_id = array();
				foreach($sales as $id){
					$live_sale_id[] = $id['sale_id'];

					$live_sale_data[$id['transaction_id']] = $id['sale_id'];			
				}
				if(!empty($live_sale_id)){
					$this->db->where_in('sale_id', $live_sale_id)->delete($table_name);	
				}
			}
			
			// prepare local data to insert into live database	
			$insertData = array();
			foreach($data as $v){
				$v['sale_id'] = 	$live_sale_data[$v['sale_id']];
				$v['transaction_id'] = 	$v[$primary_key];
				$v['client_key'] = 	$this->clientID;
				$v['source'] = 	'Local';
				unset($v[$primary_key]);
				$insertData[] = $v;				
			}
						
			if(!empty($insertData)){
				$result = $this->db->insert_batch($table_name, $insertData);				
				return $this->db->affected_rows() . ' Data transfered successfully!';
			}
			
		}
		return false;
		
	}
	
	function free_sale($data, $table_name, $primary_key){
		$table_name = $this->db->dbprefix.$table_name;
		if(!empty($data)){
			$local_primary_data = array();
			foreach($data as $v){
				$local_primary_data[] = $v[$primary_key];
			}
			
			// check local data existance in live database and get primary data of the table
			$live_primary_data = get_live_primary_data($table_name, $primary_key, $local_primary_data, $this->clientID);
			
			// prepare local data to insert into live database	
			$insertData = array();
			$updateData = array();
			foreach($data as $v){
				$v['transaction_id'] = 	$v[$primary_key];
				$v['client_key'] = 	$this->clientID;
				$v['source'] = 'Local';
				$pid = $v[$primary_key];
				unset($v[$primary_key]);
				if(array_key_exists($pid, $live_primary_data)){
					$v[$primary_key] = $live_primary_data[$pid];
					$updateData[] = $v;
				}else{
					$insertData[] = $v;
				}
			}
						
			$affected_rows = 0;
			if(!empty($updateData)){
				$this->db->update_batch($table_name, $updateData, $primary_key);
				$affected_rows += $this->db->affected_rows();
				if(empty($insertData)){
					return  count($updateData) . ' Data transfered successfully!';
				}
			}
			
			if(!empty($insertData)){
				$result = $this->db->insert_batch($table_name, $insertData);
				$affected_rows += $this->db->affected_rows();		
				return  $affected_rows . ' Data transfered successfully!';
			}
			
		}
		return false;
		
	}
	
	function free_sale_items($data, $table_name, $primary_key){
		$table_name = $this->db->dbprefix.$table_name;
		if(!empty($data)){
			$local_sale_id = array();
			foreach($data as $v){
				$local_sale_id[] = $v['free_sale_id'];
			}
			$local_sale_id = array_unique($local_sale_id);
			// check local data existance in live database and get primary data of the table
			$sales = $this->db->select('free_sale_id, transaction_id')->where('client_key', $this->clientID)->where_in('transaction_id', $local_sale_id)->get($this->db->dbprefix.'free_sale')->result_array();
			$live_sale_data = array();
			if(!empty($sales)){
				$live_sale_id = array();
				foreach($sales as $id){
					$live_sale_id[] = $id['free_sale_id'];
					$live_sale_data[$id['transaction_id']] = $id['free_sale_id'];			
				}
				if(!empty($live_sale_id)){
					$this->db->where_in('free_sale_id', $live_sale_id)->delete($table_name);	
				}
			}
			
			// prepare local data to insert into live database	
			$insertData = array();
			foreach($data as $v){
				$v['free_sale_id'] = 	$live_sale_data[$v['free_sale_id']];
				$v['transaction_id'] = 	$v[$primary_key];
				$v['client_key'] = 	$this->clientID;
				$v['source'] = 	'Local';
				unset($v[$primary_key]);
				$insertData[] = $v;				
			}
						
			if(!empty($insertData)){
				$result = $this->db->insert_batch($table_name, $insertData);				
				return $this->db->affected_rows() . ' Data transfered successfully!';
			}
			
		}
		return false;
		
	}
	
	/***************************************************************
	*    Start code to export data
	**************************************************************************/
	
	function getTableInfo($data_type = 'Local', $sync_date = ''){
		/*$tables = $this->db->query("SELECT TABLE_NAME as table_name, TABLE_ROWS as total_rows FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'sales_punasa'")->result_array();
		//printr($tables);
		foreach($tables as $t){
			$data = array('table_name' => $t['table_name']);
			$this->db->select('added_by, updated_by, added_date, updated_date, action_microtime, source, transaction_id, client_key')->get($t['table_name'])->result_array();
			
		}
		die('Hello');*/
		
		
		$this->db->select('sti.*, IFNULL(sth.synced_rows, 0) as synced_rows');
		$this->db->from(SYNC_TABLE_INFO . ' sti');
		$this->db->join(SYNC_TABLE_HISTORY . ' sth', 'sth.sync_type = "'.$data_type.'" AND sth.table_name = sti.table_name AND sth.synced_time > sti.last_sync_date', 'LEFT');
		if($data_type !== ''){
			$this->db->where('data_type', $data_type);
		}
		$this->db->where(array('table_status' => 'Active'));
		$this->db->order_by('table_order', 'ASC');
		$tablesInfo = $this->db->get()->result_array();
		$data = array();
		if(isset($tablesInfo) && !empty($tablesInfo)){
			foreach($tablesInfo as $table){
				//echo $table['table_name'] = "TRUNCATE TABLE ".$table['table_name'].";" ."<br>";
				
				$last_sync = '';
				if($sync_date != ''){
					$last_sync = strtotime($last_sync);
				}else if($table['last_sync_date'] != "0000-00-00 00:00:00"){
					$last_sync = strtotime($table['last_sync_date']);
				}else{
					$last_sync = '';
				}
				$table['last_sync'] = $last_sync ? $last_sync : strtotime('-1000 days');
				if(!empty($last_sync) && $this->db->field_exists('action_microtime', $table['table_name'])){
					$this->db->where('action_microtime >', $last_sync);
				}			
				
				if($table['data_type'] == 'Local' && $this->db->field_exists('source', $table['table_name'])){
					$this->db->group_start();
					$this->db->or_where(array('source' => $table['data_type']));
					$this->db->or_where(array('source' => ''));
					$this->db->group_end();
				}
				//$this->db->where('source', 'Local');
				$table['rows'] = $this->db->count_all_results($table['table_name']);
				
				$data[] = $table;	
			}
		}
		//die;
		return $data;
	}
}

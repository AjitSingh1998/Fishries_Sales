<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dailysale_model extends CI_Model {
	//market_type: 1=>Local Market, 2=>Outside Market
	//market_category: Point=>1, Depot=>2, Outside=>3
	
	var $userID, $userGroup;
	function __construct(){
		parent::__construct();
		$this->userID = checkUserLogin();
		$this->userGroup = loginUserInfo('group_id');
	}
	
	function get_all_fishes($fish_code = ''){
		if(!empty($fish_code)){
			$this->db->where('code', $fish_code);
		}
		$result = $this->db->where(array('status'=>'Active'))->select('ID, name, code, fish_rate')->order_by('name', 'ASC')->get(FISH)->result_array();
		$data = array();
		if(!empty($result)){
			$data = $result;
		}
		return $data;		
	}
	
	function get_market_category($market_type = '', $ID = ''){
		if(!empty($ID)){
			$this->db->where('ID', $ID);
		}
		if(!empty($market_type)){
			$this->db->where('market_type', $market_type);
		}
		$result = $this->db->where(array('status'=>'Active'))->select('ID, name, code')->order_by('name', 'ASC')->get(MARKET_CATEGORY)->result_array();
		$data = array();
		if(!empty($result)){
			$data = $result;
		}
		return $data;		
	}
	
	function get_market_places($market_category, $id = '', $market_type = ''){
		if(!empty($id)){
			if(is_array($id)){
				$this->db->where_in('ID', $id);
			}else{
				$this->db->where(array('ID'=>$id));
			}
		}
		if(!empty($market_type)){
			$this->db->where(array('market_type'=>$market_type));
		}
		
		$this->db->where(array('market_category'=>$market_category, 'use_in_report'=>'Yes'));
		$result = $this->db->where(array('status'=>'Active'))->select('ID, name, code')->order_by('name', 'ASC')->get(MARKET_PLACE)->result_array();
		$data = array();
		if(!empty($result)){
			$data = $result;
		}
		return $data;		
	}
	
	function get_all_clients($client_id = '', $market_id = ''){		
		if(!empty($client_id)){
			if(is_array($client_id)){
				$this->db->where_in('ID',$client_id);
			}else{
				$this->db->where('ID',$client_id);
			}
		}
		if($market_id){
			$this->db->where(array('city'=>$market_id));
		}
		$result = $this->db->get(CLIENT)->result_array();
		$data = array();
		if(!empty($result) && count($result)>0){
			$data = $result;
		}
		return $data;		
	}
	
	function get_market_type($id = ''){
		if(!empty($id)){
			$this->db->where('ID', $id);
		}
		$result = $this->db->where(array('status'=>'Active'))->select('ID, name, code')->order_by('name', 'ASC')->get(MARKET_TYPE)->result_array();
		$data = array();
		if(!empty($result)){
			$data = $result;
		}
		return $data;		
	}
	
	//javascript select2 plugin customers data
	function get_clients_data($q, $offset = 0, $limit = 10, $market_type){
		$data['result1'] = '';
		$data['result2'] = '';
		
		$mt_condition = '';
		if($market_type){
			$mt_condition = ' AND market_type='.$market_type;
		}
		
		$query = "SELECT
					ID,
					company_name,
					email,
					code,
					contact_number
				  FROM ".CLIENT."
				  WHERE (company_name like '%".$q."%' OR code like '%".$q."%') AND status = 'Active' ".$mt_condition."
				  ORDER BY company_name ASC
				  LIMIT ".$offset.", ".$limit;
		$result = $this->db->query($query)->result_array();
		if(!empty($result)){
			$new_data = array();
			foreach($result as $res){
				$new_data['result1'][] = array('id'=>$res['ID'], 'text'=>$res['company_name'].' ('.$res['contact_number'].')');
				$new_data['result2'][$res['ID']] = array('ID' 				=> $res['ID'],
														 'company_name' 	=> $res['company_name'],
														 'email' 			=> $res['email'],
														 'code' 			=> $res['code'],
														 'contact_number'	=> $res['contact_number']
														 );
			}
			$data = $new_data;
		}
		return $data;	
	}
	
	//javascript select2 plugin customers data
	function typeahead_get_clients_data($search_type, $search_key, $offset = 0, $limit = 10, $market_type = 1){
		$data['result'] = '';
		
		$this->db->select('ID, company_name, email, code, contact_number');
		$this->db->from(CLIENT);
		
		if($market_type){
			$this->db->where('market_type', $market_type);
		}
		
		if($search_type == 'customer_code'){
			$this->db->like('code', $search_key);
		}else if($search_type == 'customer_name'){
			$this->db->like('company_name', $search_key);
		}else if($search_type == 'customer_mobile'){
			$this->db->like('contact_number', $search_key);
		}
		$this->db->where('status', 'Active');
		$this->db->order_by('company_name', 'ASC');
		$result = $this->db->get()->result_array();
		if(!empty($result)){
			$new_data = array();
			foreach($result as $res){
				$new_data['result'][] = array('ID' 				=> $res['ID'],
											 'company_name' 	=> $res['company_name'],
											 'email' 			=> $res['email'],
											 'code' 			=> $res['code'],
											 'contact_number'	=> $res['contact_number'],
											 'name'				=> $res['code'].' - '.$res['company_name'].' ('.$res['contact_number'].')'
											 );
			}
			$data = $new_data;
		}
		return $data;	
	}
	
	/*
	function generate_invoice_number($market_id){
		//First Check unused invoice_number, if exist then get its invoice_number else insert new invoice_number
		$result = $this->db->select('invoice_number')->get_where(SALE_INVOICE, array('market_id'=>$market_id, 'sale_id'=>0))->result_array();
		
		if(!empty($result) && isset($result[0]['invoice_number']) && $result[0]['invoice_number'] > 0){
			$invoice_number = $result[0]['invoice_number'];
		}else{
			$result = $this->db->select('IFNULL(MAX(invoice_number),0) as invoice_number')->where(array('market_id'=>$market_id, 'sale_id >'=>0))->get(SALE_INVOICE)->result_array();
			$temp_invoice_number = (1+$result[0]['invoice_number']);
			
			$this->db->insert(SALE_INVOICE, array('market_id' =>$market_id, 'invoice_number'=>$temp_invoice_number));
			$id = $this->db->insert_id();
			if($id){$invoice_number = $temp_invoice_number;}
		}
		return $invoice_number;
		
	}*/
	
	function get_customer_id(){
		$post				= $this->input->post();
		$customer_type 		= $this->input->post('customer_type');
		$customer_id 		= $this->input->post('customer_code');
		$customer_name 		= $this->input->post('customer_name');
		$customer_mobile 	= $this->input->post('customer_mobile');
		$customer_email 	= $this->input->post('customer_email');
		$market_id 			= $this->input->post('market_id');
				
		//Register customer and get its id
		if($customer_type == 'regular' && empty($customer_id)){
			//register new customer
			$customer_data = array(	'market_type' 		=> 1,
									'company_name' 		=> $customer_name,
									'email' 			=> $customer_email,
									'contact_number'	=> $customer_mobile,
									'city'				=> $market_id,
									'added_by' 			=> $this->userID,
									'added_date' 		=> get_datetime('Y-m-d H:i:s'),
									'action_microtime'	=> microtime(true),
									'status' 			=> 'Active',
									'source' 			=> SOURCE
									);
			
			$this->db->insert(CLIENT, $customer_data);
			$customer_id = $this->db->insert_id();
			if($customer_id){
				$this->db->where('ID', $customer_id)->update(CLIENT, array('code' => $customer_id)); //ignore update operation
			}
		}elseif($customer_type == 'onetime'){
			$customer_id = 0;
		}
		return $customer_id;
	}
	
	//$market_type : Local Market=>1, Outside Market=>2
	//market_category: Point=>1, Depot=>2, Outside=>3
	function save_sale_data($market_type, $market_category, $customer_id){
		
		$action_mode 		= $this->input->post('action_mode');
		$sale_id 			= $this->input->post('sale_id');
		$market_id			= $this->input->post('market_id');
		$mp_code			= $this->input->post('mp_code');
		$sale_date 			= get_datetime('Y-m-d H:i:s', str_replace('/', '-', $this->input->post('sale_date')));
		$customer_name 		= $this->input->post('customer_name');
		$customer_mobile 	= $this->input->post('customer_mobile');
		$customer_email 	= $this->input->post('customer_email');
		$remark				= $this->input->post('remark');
		
		//if no record found, prepare sale data
		//Preparing sale data for insert or update
		$sale_data = array(	'market_type' 		=> $market_type,
							'market_category' 	=> $market_category,
							'market_id' 		=> $market_id,
							'mp_code'			=> $mp_code,
							'sale_date' 		=> $sale_date,									
							'client_id' 		=> $customer_id,
							'client_name' 		=> $customer_name,
							'client_mobile' 	=> $customer_mobile,
							'client_email' 		=> $customer_email,
							'remark' 			=> $remark,
							'added_by' 			=> $this->userID,
							'added_date'		=> get_datetime('Y-m-d H:i:s'),
							'action_microtime'	=> microtime(true),
							'status'			=> 'Active',
							'source'			=> SOURCE
						   );
		if($action_mode == 'add'){
			$invoice_number = generate_invoice_number($market_id);	
			if($invoice_number){
				$sale_data['invoice_number'] = $mp_code.$invoice_number;
				$sale_data['inv_number'] = $invoice_number;
				$this->db->insert(SALE, $sale_data);
				$sale_id = $this->db->insert_id();
				if($sale_id){
					$data = array('status'=>'success', 'message'=>'Sale data saved successfully', 'data'=>$sale_id);
				}else{
					$data = array('status'=>'failed', 'message'=>'Sale data failed to save', 'data'=>'');
				}
			}else{
				$data = array('status'=>'failed', 'message'=>'Sale data failed to save', 'data'=>'');
			}
		}else if($action_mode == 'edit'){
			
			$mp_code_old = $this->input->post('mp_code_old');
			if($mp_code != $mp_code_old){
				$invoice_number = generate_invoice_number($market_id);
				$sale_data['invoice_number'] = $mp_code.$invoice_number;
				$sale_data['inv_number'] = $invoice_number;
			}
			
			unset($sale_data['added_date'], $sale_data['added_by'], $sale_data['action_microtime'], $sale_data['status']);
			$sale_data['updated_date'] = get_datetime('Y-m-d H:i:s');
			$sale_data['updated_by'] = $this->userID;
			$sale_data['action_microtime'] =  microtime(true);
			$result = $this->db->where('sale_id', $sale_id)->update(SALE, $sale_data);
			if($result){
				$item_upd = array('market_id' 		=> $market_id,
								  'sale_date' 		=> $sale_date,
								  'updated_by'		=> $this->userID, 
								  'updated_by'		=>get_datetime('Y-m-d H:i:s'), 
								  'action_microtime'=>microtime(true)
								  );
				$this->db->where('sale_id', $sale_id)->update(SALE_ITEMS, $item_upd);
				
				$data = array('status'=>'success', 'message'=>'Sale data updated successfully', 'data'=>$sale_id);
			}else{
				$data = array('status'=>'failed', 'message'=>'Sale data failed to update', 'data'=>'');
			}
		}
		return $data;
	}
	
	function get_sale_data($sale_id, $action_mode = 'view'){
		if($this->userGroup != 1 && $action_mode != "view"){
			//$this->db->where(array('sale.added_by'=>$this->userID));
		}
		$this->db->select('sale.*, sale.remark as sale_remark, 
						   client.ID as client_id, client.company_name, CONCAT(IFNULL(client.contact_number,""),", ", IFNULL(client.contact_number2,"")) as contact_number, client.email, client.code as client_code,
						   client.trademark_name as trade_mark,
						   client.address as client_address,
						   mt.name as market_type_name, 
						   mp.name as market_name,
						   pd.dispatch_to, pd.vehicle_number, pd.remark, 
						   scd.gross_sale, scd.total_expenses, scd.net_sale, scd.prev_balance, scd.grand_total, scd.balance, scd.details as detail
						  ', FALSE);
		$this->db->from(SALE.' sale');
		$this->db->join(CLIENT.' client', 'client.ID=sale.client_id', 'LEFT');
		$this->db->join(MARKET_TYPE.' mt', 'mt.ID=sale.market_type', 'LEFT');
		$this->db->join(MARKET_PLACE.' mp', 'mp.ID=sale.market_id', 'LEFT');
		$this->db->join(PRODUCT_DISPATCH.' pd', 'pd.dr_number=sale.dr_number AND pd.status="Dispatched"', 'LEFT');
		$this->db->join(SALE_CHALLAN_DETAIL.' scd', 'scd.sale_id=sale.sale_id', 'LEFT');
		
		$this->db->where(array('sale.sale_id'=>$sale_id, 'sale.status'=>'Active', 'sale.editable'=>'Unlock'));
		$result = $this->db->get()->result_array();
		$data = array();
		if(!empty($result) && count($result)>0){
			$data = $result[0];
		}
		return $data;
	}
	
	function get_saleitem_data($sale_id, $sale_item_id = ''){
		if($sale_id){
			$this->db->select('si.*, f.name as fish_name');
			$this->db->from(SALE_ITEMS.' si');
			$this->db->join(FISH.' f', 'f.code=si.fish_code', 'LEFT');
			$this->db->where(array('si.sale_id'=>$sale_id, 'si.status'=>'Active', 'si.editable'=>'Unlock'));
			if($sale_item_id){
				$this->db->where(array('si.id'=>$sale_item_id));
			}
			$this->db->order_by('si.id', 'ASC');
			$result = $this->db->get()->result_array();
		}
		$data = array();
		if(!empty($result) && count($result)>0){
			$data = $result;
		}
		return $data;
	}
	
	function get_customer_old_remaing($client_id, $sale_datetime, $sale_id = NULL){
		//SALE Data
		$this->db->select('IFNULL(SUM(grand_total), 0) as grand_total,IFNULL(SUM(received_amt), 0) as received_amt');
		if($sale_id){
			$this->db->where(array('sale_id !='=>$sale_id));
		}
		$this->db->where(array('client_id'=>$client_id, 'sale_date <='=>$sale_datetime, 'status'=>'Active'));
		$sale_result = $this->db->get(SALE)->result_array();
		$op_balance = $this->db->select('IFNULL(SUM(opening_balance),0) as opening_balance')->get_where(CLIENT, array('ID'=>$client_id))->result_array();
		//CASH_RECEIVED Data
		$this->db->select('IFNULL(SUM(amount), 0) as cash_received_amount');
		$this->db->where(array('client_id'=>$client_id, 'payment_date <='=>$sale_datetime, 'sale_id !='=>$sale_id, 'status'=>'Active'));
		$cash_received_result = $this->db->get(CASH_RECEIVED)->result_array();
		
		$old_remaing = ($sale_result[0]['grand_total']+$op_balance[0]['opening_balance']) - ($sale_result[0]['received_amt']+$cash_received_result[0]['cash_received_amount']);
		return $old_remaing;
	}
	
	function get_challan_customer_old_remaing($client_id, $sale_datetime, $sale_id = NULL){
		if($sale_id){
			$this->db->where(array('sale_id !=' => $sale_id));
		}
		$this->db->where_in('sale_id', 'SELECT sale_id FROM '.SALE.' WHERE client_id='.$client_id.' AND status="Active" AND sale_date <="'.$sale_datetime.'"', false);
		$total_challan = $this->db->get(SALE_CHALLAN_DETAIL)->num_rows();
		
		
		
		$this->db->select('IFNULL(SUM(balance), 0) as prev_balance');		
		if($sale_id){
			$this->db->where(array('sale_id !=' => $sale_id));
		}
		
		$this->db->where_in('sale_id', 'SELECT sale_id FROM '.SALE.' WHERE client_id='.$client_id.' AND status="Active" AND sale_date <="'.$sale_datetime.'"', false);
		$challan_result = $this->db->get(SALE_CHALLAN_DETAIL)->result_array();
		
		//printr($challan_result);
		
		$old_remaing = 0;
		
		if(isset($challan_result[0]['prev_balance']) && $total_challan > 0){
			$old_remaing = $challan_result[0]['prev_balance'];
		}else{
			$opening = $this->db->select('opening_balance')->get_where(CLIENT, array('ID'=>$client_id))->result_array();
			if(!empty($opening)){
				$old_remaing = $opening[0]['opening_balance'];
			}
		}		
		return $old_remaing;
	}
	
	function get_sale_boxes($dispatch_id){
		$this->db->select('pdb.box_number, (SELECT GROUP_CONCAT(CONCAT( name, " (", code, ")", "/",  sale_qty, "/", sale_wt, "/", item_id , "/", sale_price , "/", sale_remark , "/", client_id) SEPARATOR "|" ) FROM '.PRODUCT_BOX_ITEMS.' pbi LEFT JOIN '.FISH.' fc ON fc.code=pbi.fish_code WHERE pbi.box_number = pdb.box_number) as box_items', false);
		$this->db->from(PRODUCT_DISPATCH_BOX .' pdb');
		$this->db->where('pdb.dispatch_id', $dispatch_id);
		$result = $this->db->get()->result_array();
		
		$data = array();
		if(!empty($result) && count($result) > 0){
			$data = $result;
		}
		
		return $data;	
	}
	
	function get_dr_detail($dr_number, $status = 'Dispatched'){
		$data = array();
		if($dr_number){
			$result = $this->db->get_where(PRODUCT_DISPATCH, array('dr_number'=>$dr_number, 'status'=>$status))->result_array();
			if(!empty($result) && count($result)>0){
				$data = $result[0];
			}
		}
		return $data;
	}
	
	function get_boxes_outside_sale_old($dr_number, $status = 'Dispatched', $market_id, $client_id, $sale_id = ''){
		
		$this->db->select('box_number');
		$this->db->where_in('sale_id', 'SELECT sale_id FROM '.SALE.' WHERE dr_number="'.$dr_number.'" AND market_id="'.$market_id.'" AND sale_id != "'.$sale_id.'"', false);
		$boxes_sold = $this->db->get(SALE_ITEMS)->result_array();
		//echo $this->db->last_query();
		//printr($boxes_sold);
		
		$sold_boxes = array();
		$all_sold_boxes = '';
		if(!empty($boxes_sold) ){
			foreach($boxes_sold as $box){
				$sold_boxes[] = $box['box_number'];
				$all_sold_boxes .= '"'.$box['box_number'].'", ';
			}			
		}
		$all_sold_boxes = rtrim($all_sold_boxes, ', ');
		//printr($sold_boxes);		
		//$this->db->select('pd.*, GROUP_CONCAT(pdb.box_number) as all_boxes');
		//$this->db->from(PRODUCT_DISPATCH.' pd');
		//$this->db->join(PRODUCT_DISPATCH_BOX.' pdb', 'pdb.dispatch_id=pd.dispatch_id', 'LEFT');
		//$this->db->where(array('pd.dr_number'=>$dr_number, 'pd.dispatch_to'=>$market_id, 'pd.status'=>$status));
		//$dispatch_data = array('asassa'); $this->db->get()->result_array();
		
		/*if(!empty($dispatch_data)){
			$this->db->select('box_number');
			$this->db->where('dispatch_id', '(SELECT dispatch_id FROM '.PRODUCT_DISPATCH.' WHERE dr_number="'.$dr_number.'" AND dispatch_to="'.$market_id.'" AND status="'.$status.'")', false);			
			$this->db->where_not_in('box_number', $sold_boxes);
			$dispatch_boxes = $this->db->get(PRODUCT_DISPATCH_BOX)->result_array();
			echo $this->db->last_query();
			die;
			$all_boxes = array();
			if(!empty($dispatch_boxes)){
				foreach($dispatch_boxes as $boxes){
					$all_boxes[] = $boxes['box_number'];
				}				
			}
		}*/
		//printr($all_boxes);
		$data = array();
		//if(!empty($dispatch_data)){
			//$dispatch_id = $dispatch_data[0]['dispatch_id'];
			$extra_sold_query = '';
			if(!empty($all_sold_boxes)){
				$extra_sold_query = ' AND pdb.box_number NOT IN('.$all_sold_boxes.')';
			}
				$this->db->select('pbi.box_number, pbi.fish_code, f.name as fish_name, pbi.fish_qty, pbi.fish_wt, pbi.box_wt,
								   si.id as sale_item_id, si.gross_wt, si.net_wt, si.fish_rate, si.total_amount,
								  ');
				$this->db->from(PRODUCT_BOX_ITEMS.' pbi');
				$this->db->join(SALE_ITEMS.' si', 'si.box_number=pbi.box_number AND si.fish_code=pbi.fish_code', 'LEFT');
				$this->db->join(FISH.' f', 'f.code=pbi.fish_code', 'LEFT');
				$this->db->where_in('pbi.box_number',					
					'SELECT pdb.box_number FROM '.PRODUCT_DISPATCH_BOX.' pdb WHERE pdb.dispatch_id = (SELECT dispatch_id FROM '.PRODUCT_DISPATCH.' WHERE dr_number="'.$dr_number.'" AND dispatch_to="'.$market_id.'" AND status="'.$status.'")' . $extra_sold_query, false);
					
				$this->db->order_by('cast(pbi.box_number as unsigned)', 'ASC');
				$result = $this->db->get()->result_array();
				$boxes = array();
				if(!empty($result)){
					foreach($result as $res){
						$boxes[$res['box_number']][] = $res;
					}
					$data = $boxes;
				}
			//}
		//}
		return $data;	
	}
	
	function get_boxes_outside_sale($dr_number, $status = 'Dispatched', $market_id, $client_id, $sale_id = ''){
		
		$this->db->select('box_number');
		$this->db->where_in('sale_id', 'SELECT sale_id FROM '.SALE.' WHERE dr_number="'.$dr_number.'" AND market_id="'.$market_id.'" AND sale_id != "'.$sale_id.'"', false);
		$boxes_sold = $this->db->get(SALE_ITEMS)->result_array();
		$sold_boxes = array();
		$all_sold_boxes = '';
		if(!empty($boxes_sold) ){
			foreach($boxes_sold as $box){
				$sold_boxes[] = $box['box_number'];
				$all_sold_boxes .= '"'.$box['box_number'].'", ';
			}			
		}
		$all_sold_boxes = rtrim($all_sold_boxes, ', ');
		
		$extra_sold_query = '';
		if(!empty($all_sold_boxes)){
			$extra_sold_query = ' AND pdb.box_number NOT IN('.$all_sold_boxes.')';
		}
		$this->db->select('pbi.box_number, pbi.fish_code, f.name as fish_name, pbi.fish_qty, pbi.fish_wt, pbi.box_wt,
						   si.id as sale_item_id, si.gross_wt, si.net_wt, si.fish_rate, si.total_amount,
						  ');
		$this->db->from(PRODUCT_BOX_ITEMS.' pbi');
		$this->db->join(SALE_ITEMS.' si', 'si.box_number=pbi.box_number AND si.fish_code=pbi.fish_code', 'LEFT');
		$this->db->join(FISH.' f', 'f.code=pbi.fish_code', 'LEFT');
		$this->db->where_in('pbi.box_number',					
			'SELECT pdb.box_number FROM '.PRODUCT_DISPATCH_BOX.' pdb WHERE pdb.dispatch_id = (SELECT dispatch_id FROM '.PRODUCT_DISPATCH.' WHERE dr_number="'.$dr_number.'" AND dispatch_to="'.$market_id.'" AND status="'.$status.'")' . $extra_sold_query, false);
			
		$this->db->order_by('cast(pbi.box_number as unsigned)', 'ASC');
		$result = $this->db->get()->result_array();
		$boxes = array();
		if(!empty($result)){
			foreach($result as $res){
				$boxes[$res['box_number']][] = $res;
			}
		}	
		return $boxes;	
	}
	
	function get_challan_boxes_outside_sale($dr_number, $status = 'Dispatched', $market_id, $client_id, $sale_id = ''){
		$boxes = array();
		$this->db->select('box_number');
		$this->db->where_in('sale_id', 'SELECT sale_id FROM '.SALE.' WHERE dr_number="'.$dr_number.'" AND market_id="'.$market_id.'" AND sale_id="'.$sale_id.'"', false);
		$boxes_sold = $this->db->get(SALE_ITEMS)->result_array();
		$sold_boxes = array();
		if(!empty($boxes_sold) ){
			foreach($boxes_sold as $box){
				$sold_boxes[] = $box['box_number'];
			}			
		}		
			
		if(!empty($sold_boxes)){
			$this->db->select('pbi.box_number, pbi.fish_code, f.name as fish_name, pbi.fish_qty, pbi.fish_wt, pbi.box_wt,
							   si.id as sale_item_id, si.gross_wt, si.net_wt, si.fish_rate, si.total_amount,
							  ', FALSE);
			$this->db->from(PRODUCT_BOX_ITEMS.' pbi');
			$this->db->join(SALE_ITEMS.' si', 'si.box_number=pbi.box_number AND si.fish_code=pbi.fish_code', 'LEFT');
			$this->db->join(FISH.' f', 'f.code=pbi.fish_code', 'LEFT');
			$this->db->where_in('pbi.box_number', $sold_boxes);
			$this->db->order_by('cast(pbi.box_number as unsigned)', 'ASC');
			$result = $this->db->get()->result_array();
			if(!empty($result)){
				foreach($result as $res){
					$boxes[$res['box_number']][] = $res;
				}
			}
		}
	
		return $boxes;	
	}
		
	function update_outside_summary($sale_id, $client_id){
		$summary = array('total_wt' 		=> 0,
						 'total_qty' 		=> 0,
						 'total_gross_wt' 	=> 0,
						 'total_net_wt' 	=> 0,
						 'grand_total' 		=> 0,
						 'updated_by'		=> $this->userID, 
						 'updated_date'		=>get_datetime('Y-m-d H:i:s'),
						 'action_microtime'	=>microtime(true)
						 );
		$this->db->select('IFNULL(SUM(fish_weight),0) as total_wt, 
						   IFNULL(SUM(fish_quantity),0) as total_qty,
						   IFNULL(SUM(gross_wt),0) as total_gross_wt, 
						   IFNULL(SUM(net_wt),0) as total_net_wt, 
						   IFNULL(SUM(total_amount),0) as grand_total
						  ');		
		$this->db->where(array('sale_id'=>$sale_id, 'status'=>'Active'));
		$result = $this->db->get(SALE_ITEMS)->result_array();
		
		if(!empty($result)){
			$summary = array('total_wt' 		=> $result[0]['total_wt'],
							 'total_qty' 		=> $result[0]['total_qty'],
							 'total_gross_wt' 	=> $result[0]['total_gross_wt'],
							 'total_net_wt' 	=> $result[0]['total_net_wt'], 
							 'grand_total' 		=> $result[0]['grand_total'],
							 'updated_by'		=> $this->userID,
							 'updated_date'		=>get_datetime('Y-m-d H:i:s'),
							 'action_microtime'	=>microtime(true)
							 );
		}
		
		$this->db->where(array('sale_id'=>$sale_id, 'client_id'=>$client_id))->update(SALE, $summary);
	}
	
	//Free sale funcitons
	function get_free_sale_data($sale_id, $action_mode = 'view'){
		if($this->userGroup != 1 && $action_mode != "view"){
			$this->db->where(array('sale.added_by'=>$this->userID));
		}
		$this->db->select('sale.*,  
						   client.ID as client_id, client.company_name, client.contact_number, client.email, 
						   mcat.name as market_category_name, 
						   mp.name as market_name
						  ');
		$this->db->from(FREE_SALE.' sale');
		$this->db->join(CLIENT.' client', 'client.ID=sale.client_id', 'LEFT');
		$this->db->join(MARKET_CATEGORY.' mcat', 'mcat.ID=sale.market_category', 'LEFT');
		$this->db->join(MARKET_PLACE.' mp', 'mp.ID=sale.market_id', 'LEFT');
		$this->db->where(array('sale.free_sale_id'=>$sale_id, 'sale.status'=>'Active', 'sale.editable'=>'Unlock'));
		$result = $this->db->get()->result_array();
		$data = array();
		if(!empty($result) && count($result)>0){
			$data = $result[0];
		}
		return $data;
	}
	
	function get_free_saleitem_data($sale_id){
		if($sale_id){
			$this->db->select('si.*, f.name as fish_name');
			$this->db->from(FREE_SALE_ITEMS.' si');
			$this->db->join(FISH.' f', 'f.code=si.fish_code', 'LEFT');
			$this->db->where(array('si.free_sale_id'=>$sale_id, 'si.status'=>'Active', 'si.editable'=>'Unlock'));
			$this->db->order_by('si.id', 'ASC');
			$result = $this->db->get()->result_array();
		}
		$data = array();
		if(!empty($result) && count($result)>0){
			$data = $result;
		}
		return $data;
	}
	
	function update_free_sale_summary($free_sale_id){
		$this->db->select('IFNULL(SUM(fish_quantity),0) as total_quantity, IFNULL(SUM(fish_weight),0) as total_weight');
		$this->db->where('free_sale_id', $free_sale_id);
		$result = $this->db->get(FREE_SALE_ITEMS)->result_array();
		if(!empty($result)){
			$udp_data = array('total_quantity' => $result[0]['total_quantity'], 'total_weight' => $result[0]['total_weight'], 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true));
			$this->db->where(array('free_sale_id'=>$free_sale_id))->update(FREE_SALE, $udp_data);
		}
	}
	
	function get_sale_expenditure($sale_id){
		$result = $this->db->where(array('sale_id'=>$sale_id))->get(SALE_EXPENDITURE)->result_array();
		$data = array();
		if(!empty($result)){
			$data = $result;
		}
		return $data;		
	}
	
	function get_sale_remition_data($sale_id){
		$result = $this->db->where(array('sale_id'=>$sale_id))->get(CASH_RECEIVED)->result_array();
		return $result;
	}
	
	function update_sale_detail($sale_id){
		$sale_data = $this->db->select()->get_where(SALE, array('sale_id'=>$sale_id))->result_array();
		if(!empty($sale_data)){
			$sale_data = $sale_data[0];
			$discount_perc = $sale_data['discount_perc'];
			$result = $this->db->select('id, fish_weight, fish_rate')->get_where(SALE_ITEMS)->resulT_array();
			if(!empty($result)){
				foreach($result as $row){
					$total_amount = $row['fish_weight'] * $row['fish_rate'];
					$this->db->where(array('id'=>$row['id'], 'sale_id'=>$sale_id))->update(SALE_ITEMS, array('total_amount'=>$total_amount, 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true)));
				}
				
				$result = $this->db->select('SUM(total_amount) as sub_total')->get_where(SALE_ITEMS, array('sale_id'=>$sale_id))->result_array();
				$sub_total = $result[0]['sub_total'];
				$discount_amount = ($discount_perc * $sub_total)/100;
				$grand_total = $sub_total - $discount_amount;
				$this->db->where(array('sale_id'=>$sale_id))->update(SALE, array('sub_total'=>$sub_total, 'discount_amount'=>$discount_amount, 'grand_total'=>$grand_total, 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true)));
			}
		}
	}
	
	function get_cash_received($invoice_number){
		$cash_received = array();
		if(!empty($invoice_number)){
			$cash_received = $this->db->get_where(CASH_RECEIVED, array('invoice_number'=>$invoice_number, 'status'=>'Active'))->result_array();
		}
		return $cash_received;
	}
}
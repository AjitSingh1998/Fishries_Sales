<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Production_model extends CI_Model {
	var $userID, $userGroup;
	function __construct(){
		parent::__construct();
		$this->userGroup = loginUserInfo('group_id');
		$this->userID = checkUserLogin();
	}
	
	function get_all_fishes($fish_code = ''){
		if(!empty($fish_code)){
			$this->db->where('code', $fish_code);
		}
		$result = $this->db->where(array('status'=>'Active'))->select('ID, type, name, code, fish_rate, dhalta')->order_by('name', 'ASC')->get(FISH)->result_array();
		$data = array();
		if(!empty($result)){
			$data = $result;
		}
		return $data;		
	}
		
	function get_market_places($market_category = '', $market_type = '', $id = '', $production_type = ''){
		if(!empty($id)){
			$this->db->where(array('ID'=>$id));
		}
		
		if(!empty($market_type)){
			$this->db->where(array('market_type'=>$market_type));
		}
		
		if(!empty($market_category)){
			$this->db->where(array('market_category'=>$market_category));
		}
		
		if($production_type == "stock" || $production_type == "bachat"){
			$this->db->where(array('status'=>'Active', 'use_in_report'=>'No'));
			$result = $this->db->select('ID, name, code')->order_by('name', 'ASC')->get(MARKET_PLACE)->result_array();
			if(!empty($result)){
				return $result;
			}
			// If no specific non-report points configured, fallback to all active points
			if(!empty($market_category)){
				$this->db->where(array('market_category'=>$market_category));
			}
			$result = $this->db->where(array('status'=>'Active'))->select('ID, name, code')->order_by('name', 'ASC')->get(MARKET_PLACE)->result_array();
			return !empty($result) ? $result : array();
		}
		
		$use_in_report = 'Yes';
		$result = $this->db->where(array('status'=>'Active', 'use_in_report'=>$use_in_report))->select('ID, name, code')->order_by('name', 'ASC')->get(MARKET_PLACE)->result_array();
		$data = array();
		if(!empty($result)){
			$data = $result;
		}
		return $data;		
	}
	
	function check_production_available($market_id, $production_date, $production_type, $depot_id){
		$production_id = '';
		$this->db->where('depot_id', $depot_id);
		$this->db->where('market_id', $market_id);
		$this->db->where('production_date', $production_date);
		$this->db->where('LOWER(production_type)', strtolower($production_type));
		$this->db->where('status', 'Active');
		$result = $this->db->get(PRODUCTION)->result_array();
		if(!empty($result)){
			$production_id = !empty($result[0]['production_id']) ? $result[0]['production_id'] : $result[0]['id'];
		}
		return $production_id;
	}
	
	function get_production_data($production_id){
		if($this->userGroup != 1){
			$this->db->where('dp.added_by',$this->userID);
		}
		$this->db->select('dp.*, mp2.name as depot_name, mp2.code as depot_code, mp.name as market_name, mp.code as mp_code')->from(PRODUCTION.' dp');
		$this->db->join(MARKET_PLACE.' mp','mp.ID=dp.market_id', 'LEFT');
		$this->db->join(MARKET_PLACE.' mp2','mp2.ID=dp.depot_id', 'LEFT');
		if(is_numeric($production_id)){
			$this->db->group_start()->where('dp.id', $production_id)->or_where('dp.production_id', $production_id)->group_end();
		}else{
			$this->db->where('dp.production_id', $production_id);
		}
		$this->db->where('dp.status !=', 'Deleted');
		$result = $this->db->get()->result_array();
		$data = array();
		if(!empty($result)){
			$data = $result[0];
			if(empty($data['production_id'])){
				$data['production_id'] = $data['id'];
			}
		}
		return $data;
	}
	
	function get_production_items($production_id = '', $carret_number = ''){
		$sql = "SELECT pc.carret_number, pc.fish_type, pc.carret_quantity, pc.carret_weight, 
				pb.box_number, IFNULL(pb.box_weight, 0) as box_weight, pb.status as box_status,
				cl.ID as client_id, cl.company_name as client_name, 
				si.sale_id,
				(SELECT GROUP_CONCAT(CONCAT(IFNULL(fc.code, pci.fish_code), '/', IFNULL(fc.name, pci.fish_code), '/', pci.fish_qty, '/', pci.fish_wt, '/', pci.box_wt, '/', IFNULL(fc.fish_rate, 0)) SEPARATOR '|') 
				 FROM psac_production_carret_items pci 
				 LEFT JOIN psac_fish fc ON (fc.code = pci.fish_code OR fc.code = CONCAT('FISH-', pci.fish_code)) 
				 WHERE (pci.carret_id = pc.id OR (pci.production_id = pc.production_id AND pci.carret_number = pc.carret_number))
				) as carret_items
				FROM psac_production_carret pc
				LEFT JOIN psac_product_box pb ON pb.carret_number = pc.carret_number
				LEFT JOIN psac_sale_items si ON si.carret_number = pc.carret_number
				LEFT JOIN psac_client cl ON cl.ID = pc.client_id
				WHERE pc.status = 'Active'";
		
		$params = array();
		if(!empty($production_id)){
			$sql .= " AND pc.production_id = ?";
			$params[] = $production_id;
		}
		if(!empty($carret_number)){
			$sql .= " AND pc.carret_number = ?";
			$params[] = $carret_number;
		}
		$sql .= " ORDER BY pc.carret_number ASC";
		
		$result = $this->db->query($sql, $params)->result_array();
		$data = array();
		if(!empty($result)) {$data = $result;}
		return $data;
	}
	
	function update_production_summary($production_id){
		// Find the production record by numeric id or string production_id
		$this->db->select('id, production_id, point_wt, forfeiture_wt, forfeiture_rt_wt, total_pt_wt, production_type');
		if(is_numeric($production_id)){
			$this->db->group_start()->where('id', $production_id)->or_where('production_id', $production_id)->group_end();
		}else{
			$this->db->where('production_id', $production_id);
		}
		$prod_row = $this->db->get(PRODUCTION)->row_array();
		if(empty($prod_row)){
			return;
		}
		$numeric_id = $prod_row['id'];
		$string_id  = $prod_row['production_id'];
		
		// Query carrets associated with either numeric or string id
		$this->db->select('fish_type, IFNULL(SUM(carret_weight),0) as carret_weight, IFNULL(SUM(carret_quantity),0) as carret_quantity, COUNT(*) as carret_count');
		$this->db->group_start()->where('production_id', $numeric_id);
		if(!empty($string_id)){
			$this->db->or_where('production_id', $string_id);
		}
		$this->db->group_end();
		$this->db->where('status', 'Active');
		$this->db->group_by('fish_type');
		$result = $this->db->get(PRODUCTION_CARRET)->result_array();
		
		$fresh_wt = $rotten_wt = $destroyed_wt = $depot_wt = $total_carret_qty = $total_carret_count = 0;
		if(!empty($result)){
			foreach($result as $res){
				$c_wt = floatval($res['carret_weight']);
				$total_carret_qty += intval($res['carret_quantity']);
				$total_carret_count += intval($res['carret_count']);
				if($res['fish_type'] == "Fresh"){ $fresh_wt += $c_wt; }
				if($res['fish_type'] == "Rotten"){ $rotten_wt += $c_wt; }
				if($res['fish_type'] == "Destroyed"){ $destroyed_wt += $c_wt; }
			}
			$depot_wt = $fresh_wt + $rotten_wt + $destroyed_wt;
		}
		
		$total_pt_wt = floatval($prod_row['total_pt_wt']);
		if($total_pt_wt == 0){
			$total_pt_wt = floatval($prod_row['point_wt']);
		}
		$surplus_wt = $depot_wt - $total_pt_wt;
		
		$summary = array(
			'fresh_wt'        => $fresh_wt,
			'rotten_wt'       => $rotten_wt,
			'destroyed_wt'    => $destroyed_wt,
			'depot_wt'        => $depot_wt,
			'total_wt'        => $depot_wt,
			'surplus_wt'      => $surplus_wt,
			'carret_quantity' => $total_carret_qty,
			'carret_weight'   => $depot_wt,
			'carret_count'    => $total_carret_count,
			'updated_by'      => $this->userID,
			'updated_date'    => get_datetime('Y-m-d H:i:s'),
			'action_microtime'=> microtime(true)
		);
		
		$this->db->where('id', $numeric_id)->update(PRODUCTION, $summary);
	}
	
	function checkBoxExistance(){
		$data = 'exist';
		$carret_number 	= $this->input->post('carret_number');
		$box_number 	= $this->input->post('box_number');
		if(!empty($box_number)){
			if($carret_number){
				$this->db->where(array('carret_number !='=>$carret_number));
			}
			$result = $this->db->where(array('box_number'=>$box_number))->count_all_results(PRODUCT_BOX);
			if($result == 0){
				$data = 'not_exist';
			}
		}
		return $data;
	}
	
	function insert_box_detail( $prep_box_data, $carret_data, $box_number ){
		//$carret_box_number = generate_box_number($prep_box_data['mp_code']);		
		$box_number = strtoupper($box_number);
		
		$prep_box = array('carret_number' 	=> $prep_box_data['carret_number'],
						  'box_number' 		=> $box_number,
						  'depot_id' 		=> $prep_box_data['depot_id'],
						  'market_id' 		=> $prep_box_data['market_id'],
						  'packing_date' 	=> $prep_box_data['packing_date'],
						  'fish_type' 		=> $carret_data['fish_type'],
						  'box_quantity' 	=> $carret_data['carret_quantity'],
						  'carret_weight' 	=> $carret_data['carret_weight'],
						  'box_weight' 		=> $prep_box_data['box_weight'],
						  'added_by'		=> $this->userID,
						  'added_date'		=> get_datetime('Y-m-d H:i:s'),
						  'action_microtime'=> microtime(true),
						  'source'			=> SOURCE
						 );
		
		$this->db->insert(PRODUCT_BOX, $prep_box);
		$box_id = $this->db->insert_id();
		
		//$this->db->where(array('box_id'=>$box_number))->update(PRODUCT_BOX, array('box_number'=>$box_number));
		$this->db->where(array('carret_number'=>$prep_box_data['carret_number']))->update(PRODUCTION_CARRET, array('updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true)));
		$this->db->where(array('carret_number'=>$prep_box_data['carret_number']))->update(PRODUCTION_CARRET_ITEMS, array('box_number'=>$box_number, 'updated_by'=>$this->userID, 'updated_date'=>get_datetime('Y-m-d H:i:s'), 'action_microtime'=>microtime(true)));		
		/*
		$box_info = array('mp_code'=>strtoupper($mp_code), 'number' => $carret_box_number, 'box_number' => $box_number, 
					'added_by' => $this->userID, 'added_date' => get_datetime('Y-m-d H:i:s'));
		$this->db->insert(BOX_NUMBER, $box_info);
		*/
		
		return $box_number;
	}
	
	function insert_box_items( $prep_box_data, $prep_carret_items, $box_number ){
		$prep_box_items = array();
		foreach($prep_carret_items as $item){
			$prep_box_items[] = array('box_number' 		=> $box_number,
									  'depot_id'		=> $prep_box_data['depot_id'],
									  'market_id'		=> $prep_box_data['market_id'],
									  'packing_date' 	=> $prep_box_data['packing_date'],
									  'carret_number'	=> $prep_box_data['carret_number'],
									  'fish_type'		=> $item['fish_type'],
									  'fish_code'		=> $item['fish_code'],
									  'fish_qty' 		=> $item['fish_qty'],
									  'fish_wt' 		=> $item['fish_wt'],
									  'box_wt' 			=> $item['box_wt'],
									  'added_by'		=> $item['added_by'],
									  'added_date'		=> get_datetime('Y-m-d H:i:s'),
									  'action_microtime'=> $item['action_microtime'],
									  'source'			=> SOURCE
									 );
		}
		if(!empty($prep_box_items)){
			$this->db->insert_batch(PRODUCT_BOX_ITEMS, $prep_box_items);
		}
	}
	
	function insert_sale_items( $sale_item_d, $prep_carret_items ){
		$this->db->delete(SALE_ITEMS, array('carret_number'=> $sale_item_d['carret_number']));
		if(!empty($prep_carret_items)){
			foreach($prep_carret_items as $item){
				$rate = isset($item['fish_rate']) ? floatval($item['fish_rate']) : 0;
				$wt   = isset($item['fish_wt']) ? floatval($item['fish_wt']) : 0;
				$qty  = isset($item['fish_qty']) ? floatval($item['fish_qty']) : 0;
				$amt  = number_format($wt * $rate, 2, '.', '');
				
				$saleitem_data = array(	'sale_id' 			=> $sale_item_d['sale_id'],
										'carret_number' 	=> $sale_item_d['carret_number'],
										'client_id' 		=> $sale_item_d['client_id'],
										'sale_date' 		=> $item['packing_date'],
										'market_type' 		=> 1,
										'market_category' 	=> 2,
										'market_id' 		=> $item['depot_id'],
										'fish_code' 		=> $item['fish_code'],									
										'fish_quantity' 	=> $qty,
										'fish_weight' 		=> $wt,
										'fish_rate' 		=> $rate,
										'amount'			=> $amt,
										'total_amount' 		=> $amt,
										'stock_type' 		=> isset($item['stock_type']) ? $item['stock_type'] : 'Current',
										'status'			=> 'Active'
									   );
				$this->db->insert(SALE_ITEMS, $saleitem_data);
			}
		}
	}
	
	function get_stock($till_date){
		$stock_fresh_wt = $stock_rotten_wt = $stock_destroyed_wt = $total_stock = 0;
		$this->db->select('IFNULL(SUM(fresh_wt),0) as fresh_wt, 
						   IFNULL(SUM(rotten_wt),0) as rotten_wt, 
						   IFNULL(SUM(destroyed_wt),0) as destroyed_wt,
						   IFNULL(SUM(depot_wt),0) as total_stock');		
		$this->db->where(array('production_date <='=>$till_date, 'status'=>'Active'));
		$result = $this->db->get(PRODUCTION)->result_array();
		if(!empty($result)){
			$stock_fresh_wt 	= $result[0]['fresh_wt'];
			$stock_rotten_wt 	= $result[0]['rotten_wt'];
			$stock_destroyed_wt = $result[0]['destroyed_wt'];
			$total_stock 		= $result[0]['total_stock'];;
		}
		
		
		$dispatched_fresh_wt = $dispatch_rotten_wt = $total_dispatched_wt = 0;
		$this->db->select('fish_type, IFNULL(SUM(carret_weight),0) as dispatched_wt');
		$this->db->from(PRODUCT_BOX);
		$this->db->where(array('packing_date <='=>$till_date, 'status !='=>'Deleted'));
		$this->db->group_by('fish_type');
		$result = $this->db->get()->result_array();
		if(!empty($result)){
			$result2 = array_column($result,'dispatched_wt','fish_type');
			$dispatched_fresh_wt = isset($result2['Fresh']) ? $result2['Fresh'] : 0;
			$dispatch_rotten_wt = isset($result2['Rotten']) ? $result2['Rotten'] : 0;
			$total_dispatched_wt = $dispatched_fresh_wt + $dispatch_rotten_wt;
		}
		
		$closing_stock 	= $total_stock - $total_dispatched_wt;
		$fresh_wt		= $stock_fresh_wt - $dispatched_fresh_wt;
		$rotten_wt		= $stock_rotten_wt - $dispatch_rotten_wt;
		return $summary = array( 'closing_stock' => $closing_stock,
								 'fresh_wt' 	 => $fresh_wt,
								 'rotten_wt' 	 => $rotten_wt,
								 'destroyed_wt'  => $stock_destroyed_wt
							    );
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
	
	//javascript typeahead plugin customers data
	function typeahead_get_clients_data($search_key, $offset = 0, $limit = 10, $market_type = 1){
		$data['result'] = '';
		
		$this->db->select('ID, company_name, email, code, contact_number');
		$this->db->from(CLIENT);
		$this->db->where(array('market_type'=>$market_type, 'status'=>'Active'));
		$this->db->group_start();
		$this->db->like('contact_number', $search_key);
		$this->db->or_like('code', $search_key);
		$this->db->or_like('company_name', $search_key);
		$this->db->group_end();
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
											  'name'			=> $res['code'].' - '.$res['company_name'].' ('.$res['contact_number'].')'
											 );
			}
			$data = $new_data;
		}
		return $data;	
	}
	
	function get_all_clients($market_type){
		$this->db->select('ID as client_id, company_name as client_name, contact_number as client_mobile, email as client_email');
		$this->db->from(CLIENT);
		$this->db->where(array('market_type'=>$market_type, 'status'=>'Active'));
		$this->db->order_by('client_name', 'ASC');
		$result = $this->db->get()->result_array();
		
		$data = array();
		if(!empty($result)){
			$data = $result;
		}
		return $data;
	}
	
	function get_closing_stock($closing_date, $market_id){
				
		$this->db->select('f.ID, f.code, f.name as fish_name, fc.name as category_name,
						   
						   IFNULL(pci.fish_qty, 0) as production_qty, 
						   IFNULL(pci.fish_wt,0) as production_wt,
						   						   
						   IFNULL(tci_in.market_id, 0) as tranfer_in_from_depot_id,
						   IFNULL(tci_in.depot_id, 0) as tranfer_in_to_depot_id, 						   
						   IFNULL(tci_in.fish_qty, 0) as tranfer_in_qty, 
						   IFNULL(tci_in.fish_wt,0) as tranfer_in_wt,
						   
						   IFNULL(tci_out.market_id, 0) as tranfer_out_from_depot_id,
						   IFNULL(tci_out.depot_id, 0) as tranfer_out_to_depot_id, 						   
						   IFNULL(tci_out.fish_qty, 0) as tranfer_out_qty, 
						   IFNULL(tci_out.fish_wt,0) as tranfer_out_wt,
						   
						   IFNULL(pbid.fish_qty,0) as dispatched_qty, 
						   IFNULL(pbid.fish_wt,0) as dispatched_wt,
						   
						   IFNULL(pbip.fish_qty,0) as prepared_box_qty, 
						   IFNULL(pbip.fish_wt,0) as prepared_box_wt,
						   						   
						   IFNULL(si.fish_qty, 0) as sale_qty, 
						   IFNULL(si.fish_wt, 0) as sale_wt,
						   
						   IFNULL(fsi.fish_quantity,0) as free_sale_qty, 
						   IFNULL(fsi.fish_weight,0) as free_sale_wt,
						   
						   IFNULL(bachat.fish_qty,0) as bachat_fish_qty, 
						   IFNULL(bachat.fish_wt,0) as bachat_fish_wt,
						   
						   IFNULL(bsort.fish_qty,0) as bsort_fish_qty,
						   IFNULL(bsort.fish_wt,0) as bsort_fish_wt,
						   
						   IFNULL(opening.pb_qty,0) as opening_pb_qty,
						   IFNULL(opening.pb_wt,0) as opening_pb_wt,
						   
						   IFNULL(opening.cs_qty,0) as opening_fish_qty,
						   IFNULL(opening.cs_wt,0) as opening_fish_wt,
						   
						   (IFNULL(stock.fish_qty,0) + IFNULL(opening_si.fish_qty,0) + IFNULL(opening_fsi.fish_qty,0) ) as opening_sorting_fish_qty,
						   (IFNULL(stock.fish_wt,0) + IFNULL(opening_si.fish_wt,0) + IFNULL(opening_fsi.fish_wt,0) ) as opening_sorting_fish_wt,						   
						   						   						   
						   IFNULL(dstry.fish_qty,0) as destroyed_fish_qty,
						   IFNULL(dstry.fish_wt,0) as destroyed_fish_wt				   
						   
						   ');
						   
		$this->db->from(FISH . ' f');		
		$this->db->join(FISH_CATEGORY. ' fc', 'fc.ID = f.category_id', 'LEFT');
		// Total production as current stock
		$this->db->join('(SELECT fish_code, SUM(fish_qty) as fish_qty, SUM(fish_wt) as fish_wt FROM '.PRODUCTION_CARRET_ITEMS.' 
				WHERE production_type = "Point" AND market_id NOT IN(SELECT ID FROM '.MARKET_PLACE.' WHERE use_in_report = "No") AND depot_id = "'.$market_id.'" AND packing_date = "'.$closing_date.'" GROUP BY fish_code)  pci', 'pci.fish_code = f.code', 'LEFT');
		
		// Total Transfer IN production as Transfer stock
		$this->db->join('(SELECT depot_id, market_id, fish_code, SUM(fish_qty) as fish_qty, SUM(fish_wt) as fish_wt FROM '.PRODUCTION_CARRET_ITEMS.' 
				WHERE production_type = "Transfer" AND market_id NOT IN(SELECT ID FROM '.MARKET_PLACE.' WHERE use_in_report = "No") AND (depot_id = "'.$market_id.'") AND packing_date = "'.$closing_date.'" GROUP BY fish_code)  tci_in', 'tci_in.fish_code = f.code', 'LEFT');
				
		// Total Transfer OUT production as Transfer stock
		$this->db->join('(SELECT depot_id, market_id, fish_code, SUM(fish_qty) as fish_qty, SUM(fish_wt) as fish_wt FROM '.PRODUCTION_CARRET_ITEMS.' 
				WHERE production_type = "Transfer" AND market_id NOT IN(SELECT ID FROM '.MARKET_PLACE.' WHERE use_in_report = "No") AND (market_id = "'.$market_id.'") AND packing_date = "'.$closing_date.'"  GROUP BY fish_code)  tci_out', 'tci_out.fish_code = f.code', 'LEFT');
				
		// Total dispatched from current stock
		/*$this->db->join('(SELECT fish_code, SUM(fish_qty) as fish_qty, SUM(fish_wt) as fish_wt FROM '.PRODUCTION_CARRET_ITEMS.' 
				WHERE box_number != "" AND production_type = "Point" AND market_id NOT IN(SELECT ID FROM '.MARKET_PLACE.' WHERE use_in_report = "No") AND depot_id = "'.$market_id.'" AND packing_date = "'.$closing_date.'" AND fish_type != "Destroyed" GROUP BY fish_code)  pbi', 'pbi.fish_code = f.code', 'LEFT');		
		
		// total paid sale from current stock
		$this->db->join('(SELECT fish_code, SUM(fish_qty) as fish_qty, SUM(fish_wt) as fish_wt FROM '.PRODUCTION_CARRET_ITEMS.' 
				WHERE client_id > "0" AND production_type = "Point" AND market_id NOT IN(SELECT ID FROM '.MARKET_PLACE.' WHERE use_in_report = "No") AND depot_id = "'.$market_id.'" AND packing_date = "'.$closing_date.'" AND fish_type != "Destroyed" GROUP BY fish_code) si', 'si.fish_code = f.code', 'LEFT');		
				
		
		// total free sale from current tock
		$this->db->join('(SELECT fish_code, SUM(fish_quantity) as fish_quantity, SUM(fish_weight) as fish_weight FROM '.FREE_SALE_ITEMS.' 
				WHERE stock_type = "Current" AND market_id = "'.$market_id.'" AND DATE(free_sale_date) = "'.$closing_date.'" GROUP BY fish_code)  fsi', 'fsi.fish_code = f.code', 'LEFT');*/
		
		// Total dispatched box weight
		/*$this->db->join('(SELECT fish_code, SUM(fish_qty) as fish_qty, SUM(fish_wt) as fish_wt FROM '.PRODUCT_BOX_ITEMS.' 
				WHERE (status="Prepared" OR status="Dispatched") AND depot_id = "'.$market_id.'" AND packing_date = "'.$closing_date.'" AND fish_type != "Destroyed" GROUP BY fish_code) pbid', 'pbid.fish_code = f.code', 'LEFT');*/
		
		$this->db->join('(SELECT fish_code, SUM(fish_qty) as fish_qty, SUM(fish_wt) as fish_wt FROM '.PRODUCT_BOX_ITEMS.' 
				WHERE box_number IN(SELECT box_number FROM '.PRODUCT_DISPATCH_BOX.' WHERE dispatch_id IN(SELECT dispatch_id FROM '.PRODUCT_DISPATCH.' WHERE DATE(dispatch_date) = "'.$closing_date.'" AND dispatch_from = "'.$market_id.'" AND status != "Deleted")) AND (status="Prepared" OR status="Dispatched") AND depot_id = "'.$market_id.'" AND fish_type != "Destroyed" GROUP BY fish_code) pbid', 'pbid.fish_code = f.code', 'LEFT');
					
		
		// Total prepared box not dispatched weight
		$this->db->join('(SELECT fish_code, SUM(fish_qty) as fish_qty, SUM(fish_wt) as fish_wt FROM '.PRODUCT_BOX_ITEMS.' 
				WHERE box_number NOT IN(SELECT box_number FROM '.PRODUCT_DISPATCH_BOX.' WHERE dispatch_id IN(SELECT dispatch_id FROM '.PRODUCT_DISPATCH.' WHERE DATE(dispatch_date) = "'.$closing_date.'" AND dispatch_from = "'.$market_id.'" AND status != "Deleted")) AND depot_id = "'.$market_id.'" AND packing_date = "'.$closing_date.'" AND fish_type != "Destroyed" GROUP BY fish_code) pbip', 'pbip.fish_code = f.code', 'LEFT');
				
		
		$this->db->join('(SELECT fish_code, SUM(fish_quantity) as fish_qty, SUM(fish_weight) as fish_wt FROM '.SALE_ITEMS.' 
				WHERE market_type = 1 AND market_category = 2 AND market_id = "'.$market_id.'" AND DATE(sale_date) = "'.$closing_date.'" GROUP BY fish_code)  si', 'si.fish_code = f.code', 'LEFT');
		
		$this->db->join('(SELECT fish_code, SUM(fish_quantity) as fish_quantity, SUM(fish_weight) as fish_weight FROM '.FREE_SALE_ITEMS.' 
				WHERE market_id = "'.$market_id.'" AND DATE(free_sale_date) = "'.$closing_date.'" GROUP BY fish_code)  fsi', 'fsi.fish_code = f.code', 'LEFT');
				
		//Total bachat stock after dispatched and local sale 
		$this->db->join('(SELECT fish_code, SUM(fish_qty) as fish_qty, SUM(fish_wt) as fish_wt FROM '.PRODUCTION_CARRET_ITEMS.' 
				WHERE client_id ="0" AND box_number="" AND (production_type = "Point" OR production_type = "Transfer") AND market_id NOT IN(SELECT ID FROM '.MARKET_PLACE.' WHERE use_in_report = "No") AND depot_id = "'.$market_id.'" AND packing_date = "'.$closing_date.'" AND fish_type != "Destroyed" GROUP BY fish_code)  bachat', 'bachat.fish_code = f.code', 'LEFT');
		
		// total bachat production from bachat stock
		$this->db->join('(SELECT fish_code, SUM(fish_qty) as fish_qty, SUM(fish_wt) as fish_wt FROM '.PRODUCTION_CARRET_ITEMS.' 
				WHERE production_type="Bachat" AND depot_id = "'.$market_id.'" AND packing_date = "'.$closing_date.'" GROUP BY fish_code) bsort', 'bsort.fish_code = f.code', 'LEFT');
				
		/*// total paid sale from bachat stock
		$this->db->join('(SELECT fish_code, SUM(fish_quantity) as sold_fish_qty, SUM(fish_weight) as sold_fish_wt FROM '.SALE_ITEMS.' 
				WHERE stock_type = "Bachat" AND market_type = 1 AND market_id = "'.$market_id.'" AND DATE(sale_date) = "'.$closing_date.'" GROUP BY fish_code)  bachat_si', 'bachat_si.fish_code = f.code', 'LEFT');
		
		// total free sale from bachat tock
		$this->db->join('(SELECT fish_code, SUM(fish_quantity) as fish_quantity, SUM(fish_weight) as fish_weight FROM '.FREE_SALE_ITEMS.' 
				WHERE stock_type = "Bachat" AND market_id = "'.$market_id.'" AND DATE(free_sale_date) = "'.$closing_date.'" GROUP BY fish_code)  bachat_fsi', 'bachat_fsi.fish_code = f.code', 'LEFT');*/
						
		// opening stock production and shortage
		$opening_date = get_date('Y-m-d', strtotime($closing_date .' -1 day'));		
		$this->db->join(DAILY_CLOSING_STOCK.' opening', 'opening.fish_code = f.code AND opening.depot_id = "'.$market_id.'" AND opening.production_date = "'.$opening_date.'"', 'LEFT');
		
		$this->db->join('(SELECT fish_code, SUM(fish_qty) as fish_qty, SUM(fish_wt) as fish_wt FROM '.PRODUCTION_CARRET_ITEMS.' 
				WHERE production_type="Stock" AND depot_id = "'.$market_id.'" AND packing_date = "'.$closing_date.'" GROUP BY fish_code) stock', 'stock.fish_code = f.code', 'LEFT');
		
		$this->db->join('(SELECT fish_code, SUM(fish_quantity) as fish_qty, SUM(fish_weight) as fish_wt FROM '.SALE_ITEMS.' 
				WHERE carret_number = "0" AND stock_type="Opening" AND market_id = "'.$market_id.'" AND DATE(sale_date) = "'.$closing_date.'" GROUP BY fish_code)  opening_si', 'opening_si.fish_code = f.code', 'LEFT');
		
		$this->db->join('(SELECT fish_code, SUM(fish_quantity) as fish_qty, SUM(fish_weight) as fish_wt FROM '.FREE_SALE_ITEMS.' 
				WHERE stock_type="Opening" AND market_id = "'.$market_id.'" AND DATE(free_sale_date) = "'.$closing_date.'" GROUP BY fish_code) opening_fsi', 'opening_fsi.fish_code = f.code', 'LEFT');
		
		$this->db->join('(SELECT fish_code, SUM(fish_qty) as fish_qty, SUM(fish_wt) as fish_wt FROM '.PRODUCTION_CARRET_ITEMS.' WHERE depot_id = "'.$market_id.'" AND packing_date = "'.$closing_date.'"  AND fish_type = "Destroyed" GROUP BY fish_code) dstry', 'dstry.fish_code = f.code', 'LEFT');
			
		$this->db->where('f.status', 'Active');
		//$this->db->where('pci.fish_wt > ', '0');
		
		$this->db->order_by('fish_name', 'ASC');
		$result = $this->db->get()->result_array();
		//echo $this->db->last_query();
		//die;
		//printr($result);
		return $result;	
		
	}
	
	function get_all_prepared_and_stocked_box($closing_date, $market_id){
		
		$where = array('depot_id'=>$market_id, 'packing_date' => $closing_date, 'status' => "Active");
		$active_box_result = $this->db->select('box_number')->get_where(PRODUCT_BOX, $where)->result_array();
		
		$day_before = strtotime("yesterday", strtotime($closing_date));
		$opening_date = date('Y-m-d', $day_before);

		$stock_where = array('depot_id'=>$market_id, 'closing_date' => $opening_date);
		$stock_box_result = $this->db->select('box_number')->get_where(DAILY_CLOSING_STOCK_BOX, $stock_where)->result_array();
		if(!empty($stock_box_result)){
			$active_box_result = array_merge($active_box_result, $stock_box_result);
		}
		return $active_box_result;
	}
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dispatch_model extends CI_Model {
	var $userID, $userGroup;
	//$market_type : Local Market=>1, Outside Market=>2
	//market_category: Point=>1, Depot=>2, Outside=>3
	function __construct(){
		parent::__construct();
		$this->userID = checkUserLogin();
		$this->userGroup = loginUserInfo('group_id');
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
	
	function get_market_places($market_category = '', $id = '', $market_type = ''){
		if(!empty($id)){
			$this->db->where(array('ID'=>$id));
		}
		if(!empty($market_type)){
			$this->db->where(array('market_type'=>$market_type));
		}
		if(!empty($market_category)){
			$this->db->where(array('market_category'=>$market_category));
		}
		
		$result = $this->db->where(array('status'=>'Active'))->select('ID, name, code')->order_by('name', 'ASC')->get(MARKET_PLACE)->result_array();
		$data = array();
		if(!empty($result)){
			$data = $result;
		}
		if(!empty($id) && !empty($result)){
			$data = $result[0];
		}
		return $data;		
	}
	
	//1. Prepare box functions
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
	
	function get_box_info($date, $market_id, $depot_id){
		if($this->userID && $this->userGroup != 1){
			$this->db->where('pb.added_by',$this->userID);
		}
		$this->db->select('pb.box_number');
		$this->db->from(PRODUCT_BOX .' pb');
		$this->db->where(array('DATE(pb.packing_date)'=>$date, 'market_id'=>$market_id, 'depot_id'=>$depot_id));
		$result = $this->db->get()->result_array();
		return $result;	
	}
	
	function get_all_boxes($date, $market_id, $depot_id){
		$this->db->select('pb.box_number, pb.carret_number, pb.box_quantity, pb.carret_weight, pb.box_weight, pb.status as box_status, 
						  (SELECT GROUP_CONCAT(CONCAT(code,"/",name,"/",fish_qty,"/",fish_wt,"/",box_wt) SEPARATOR "|" ) 
						  	FROM '.PRODUCT_BOX_ITEMS.' pbi 
						  	LEFT JOIN '.FISH.' fc ON fc.code=pbi.fish_code 
						  	WHERE DATE(pbi.packing_date) = pb.packing_date AND pbi.box_number = pb.box_number
						   ) as box_items', false);
		$this->db->from(PRODUCT_BOX .' pb');
		$this->db->where(array('DATE(pb.packing_date)'=>$date, 'market_id'=>$market_id, 'depot_id' => $depot_id));
		$result = $this->db->get()->result_array();
		return $result;		
	}
	
	function get_box_items($box_number){
		$this->db->select('pb.id as box_id, pb.fish_type, pb.box_quantity, pb.carret_weight, pb.box_number, pb.status as box_status,
						  IFNULL(pb.box_weight, 0) as box_weight,
						  (SELECT GROUP_CONCAT(CONCAT(code,"/",name,"/",fish_qty,"/",fish_wt,"/",box_wt) SEPARATOR "|" ) 
						  	  FROM '.PRODUCT_BOX_ITEMS.' pbi 
							  LEFT JOIN '.FISH.' fc ON fc.code=pbi.fish_code 
							  WHERE pbi.box_number = pb.box_number
						   ) as box_items', false);
		$this->db->from(PRODUCT_BOX.' pb');
		$this->db->where(array('pb.box_number'=>$box_number, 'pb.status'=>'Active'));
		$result = $this->db->get()->result_array();
		$data = array();
		if(!empty($result)) {$data = $result[0];}
		return $data;
	}
	
	function insert_box_items($carret_number, $box_number, $box_data, $prep_box_items){
		$box_items = array();
		foreach($prep_box_items as $item){
			$box_items[] = array( 'box_number' 		=> $box_number,
								  'depot_id'		=> $item['depot_id'],
								  'market_id'		=> $box_data['market_id'],
								  'packing_date' 	=> $box_data['packing_date'],
								  'carret_number'	=> $carret_number,
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
		if(!empty($box_items)){
			$this->db->insert_batch(PRODUCT_BOX_ITEMS, $box_items);
		}
	}
	
	function generate_dr_number(){
		$year = date('Y');
		$result = $this->db->query("SELECT dr_number FROM psac_product_dispatch WHERE dr_number REGEXP '^DR-[0-9]{4}-[0-9]+$' ORDER BY CAST(SUBSTRING(dr_number, 9) AS UNSIGNED) DESC LIMIT 1")->row_array();
		$next_num = 1;
		if(!empty($result['dr_number']) && preg_match('/DR-\d+-(\d+)/', $result['dr_number'], $m)){
			$next_num = intval($m[1]) + 1;
		}else{
			$count = $this->db->count_all_results(PRODUCT_DISPATCH);
			$next_num = $count + 1;
		}
		$dr_number = sprintf("DR-%s-%03d", $year, $next_num);
		
		// Insert into PRODUCT_DR if not exists
		$dr_check = $this->db->get_where(PRODUCT_DR, array('dr_number' => $dr_number))->row_array();
		if(empty($dr_check)){
			$this->db->insert(PRODUCT_DR, array(
				'dr_number' => $dr_number,
				'dr_date'   => date('Y-m-d'),
				'status'    => 'Active',
				'editable'  => 'Unlock'
			));
		}
		return $dr_number;
	}
	
	function get_dispatch_info($dispatch_id, $status = '', $user_id = ''){
		$data = array();
		if($dispatch_id){
			$this->db->select('pd.*, mp.name as source, mp2.name as destination');
			$this->db->from(PRODUCT_DISPATCH.' pd');
			$this->db->join(MARKET_PLACE.' mp', 'mp.ID=pd.dispatch_from', 'LEFT');
			$this->db->join(MARKET_PLACE.' mp2', 'mp2.ID=pd.dispatch_to', 'LEFT');
			/*if($this->userGroup != 1){
				$this->db->where('pd.added_by',$this->userID);
			}*/
			if($status){
				$this->db->where(array('pd.status'=>$status));
			}
			$this->db->where(array('pd.dispatch_id'=>$dispatch_id));
			$result = $this->db->get()->result_array();
			if(!empty($result) && count($result)>0){
				$data = $result[0];
			}
		}
		return $data;
	}
	
	function get_despatched_boxes($dr_number, $status = 'Dispatched', $dispatch_id){
		//,"/",estimated_price		
		$this->db->select('pdb.box_number,  pdb.status as dispatch_status, pdb.dispatch_id as ref_dispatch_id,
						   pb.carret_number, pb.box_quantity, pb.carret_weight, pb.box_weight, pb.status as box_status,
						   (SELECT IFNULL(si.sale_id, 0)
						   		FROM '.SALE_ITEMS.' si 
								WHERE si.box_number=pdb.box_number AND si.status="Active"
								GROUP BY si.box_number
						   ) as sale_id,						   
						   (SELECT GROUP_CONCAT(CONCAT(code,"/",name,"/",fish_qty,"/",fish_wt,"/",box_wt,"/",item_id,"/",estimated_price) SEPARATOR "|" ) 
						   		FROM '.PRODUCT_BOX_ITEMS.' pbi 
								LEFT JOIN '.FISH.' fc ON fc.code=pbi.fish_code 
								WHERE pbi.box_number = pdb.box_number
						   ) as box_items', false);
		$this->db->from(PRODUCT_DISPATCH_BOX .' pdb');
		$this->db->join(PRODUCT_BOX .' pb', 'pb.box_number=pdb.box_number', 'LEFT');
		//$this->db->join(SALE_ITEMS.' si', 'si.box_number=pdb.box_number AND si.status="Active"', 'LEFT');
		$this->db->where(array('pdb.dr_number'=>$dr_number, 'pdb.status'=>$status, 'pdb.dispatch_id'=>$dispatch_id));
		$this->db->order_by('cast(pdb.box_number as unsigned)', 'ASC');
		//$this->db->group_by('si.box_number');
		$result = $this->db->get()->result_array();
		
		$data = array();
		if(!empty($result) && count($result) > 0){
			$data = $result;
		}
		return $data;	
	}
	
	function get_box_detail($box_number){
		$this->db->select('pb.box_number, pb.carret_number, pb.box_quantity, pb.carret_weight, pb.box_weight, 
						  (SELECT GROUP_CONCAT(CONCAT(code,"/",name,"/",fish_qty,"/",fish_wt,"/",box_wt) SEPARATOR "|" ) 
						  	FROM '.PRODUCT_BOX_ITEMS.' pbi 
						  	LEFT JOIN '.FISH.' fc ON fc.code = pbi.fish_code 
							WHERE pbi.box_number = pb.box_number
						  ) as box_items', false);
		$this->db->from(PRODUCT_BOX .' pb');
		//$this->db->join(PRODUCT_DISPATCH_BOX .' pdb', 'pdb.box_number=pb.box_number', 'LEFT');
		$this->db->where('pb.box_number', trim(strtoupper($box_number)));
		$result = $this->db->get()->result_array();
		$data = array();
		if(!empty($result) && count($result) > 0){
			$data = $result[0];
		}
		return $data;	
	}
	
	//3.Dispatched DR Funcitons	
	function get_estimate_by_fishes($dr_number, $status = 'Dispatched', $dispatch_id){
		$result = $this->db->select('box_number')->where(array('dr_number'=>$dr_number, 'status'=>$status, 'dispatch_id'=>$dispatch_id))->get(PRODUCT_DISPATCH_BOX)->result_array();
		$all_boxes_array = array();
		if(!empty($result)){
			$all_boxes_array = array_column($result, 'box_number');
		}
		$data = array();
		if(!empty($all_boxes_array)){
			//Get fishes of boxes
			$this->db->select('CONCAT(f.name," (",pbi.fish_code, ")") as fish_nc, pbi.fish_qty, pbi.fish_code, pbi.estimated_price');
			$this->db->from(PRODUCT_BOX_ITEMS.' pbi');
			$this->db->join(FISH.' f','f.code=pbi.fish_code', 'LEFT');
			$this->db->where(array('pbi.status'=>$status));
			$this->db->where_in('pbi.box_number', $all_boxes_array);
			$this->db->group_by(array('pbi.fish_code','pbi.fish_qty'));
			$this->db->order_by('pbi.fish_code', 'ASC');
			$result = $this->db->get()->result_array();
			if(!empty($result)){
				$data = $result;	
			}
		}
		return $data;
	}
	
	function get_tr_boxes($dispatch_id, $ref_dispatch_id, $status = 'Dispatched'){
		$this->db->select('pdb.box_number,  pdb.status as dispatch_status,
						   pb.carret_number, pb.box_quantity, pb.carret_weight, pb.box_weight, pb.status as box_status,
						   pdb.dispatch_id as box_dispatch_id,
						   (SELECT IFNULL(si.sale_id, 0)
						   		FROM '.SALE_ITEMS.' si 
								WHERE si.box_number=pdb.box_number AND si.status="Active"
								GROUP BY si.box_number
						   ) as sale_id,
						   (SELECT GROUP_CONCAT(CONCAT(code,"/",name,"/",fish_qty,"/",fish_wt,"/",box_wt,"/",item_id,"/",estimated_price) SEPARATOR "|" ) 
						   		FROM '.PRODUCT_BOX_ITEMS.' pbi 
								LEFT JOIN '.FISH.' fc ON fc.code=pbi.fish_code 
								WHERE pbi.box_number = pdb.box_number
						    ) as box_items', false);
		$this->db->from(PRODUCT_DISPATCH_BOX .' pdb');
		$this->db->join(PRODUCT_BOX .' pb', 'pb.box_number=pdb.box_number', 'LEFT');
		//$this->db->join(SALE_ITEMS.' si', 'si.box_number=pdb.box_number AND si.status="Active"', 'LEFT');
		//$this->db->group_by('si.box_number');
		$this->db->where(array('pdb.status'=>$status));
		
		$this->db->group_start();
			$this->db->where('pdb.dispatch_id',$dispatch_id);
			$this->db->or_where('pdb.dispatch_id',$ref_dispatch_id);
		$this->db->group_end();
		
		$this->db->order_by('cast(pdb.box_number as unsigned)', 'ASC');
		$result = $this->db->get()->result_array();
		
		$data = array();
		if(!empty($result) && count($result) > 0){
			$data = $result;
		}
		return $data;	
	}
	
	public function update_dispatch_summary($dispatch_id, $dr_number){
		if(!empty($dispatch_id) && !empty($dr_number)){
			$summary = array('total_box' 		=> 0,
							 'total_box_qty' 	=> 0,
							 'total_carret_wt'  => 0,
							 'total_box_wt' 	=> 0,
							 'updated_by'		=> $this->userID,
							 'updated_date'		=> get_datetime('Y-m-d H:i:s'),
							 'action_microtime'	=> microtime(true)
							 );
			
			$all_boxes = $this->db->select('box_number')->where(array('dispatch_id'=>$dispatch_id, 'dr_number'=>$dr_number))->get(PRODUCT_DISPATCH_BOX)->result_array();
			$all_boxes_array = array_column($all_boxes, 'box_number');
			
			if(!empty($all_boxes_array)){
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
									 'total_box_wt' 	=> $result[0]['total_box_wt'],
									 'updated_by'		=> $this->userID,
									 'updated_date'		=> get_datetime('Y-m-d H:i:s'),
									 'action_microtime'	=> microtime(true)
									 );
				}
			}
			$this->db->where(array('dispatch_id'=>$dispatch_id, 'dr_number'=>$dr_number))->update(PRODUCT_DISPATCH, $summary);
		}
	}
	
}
<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_model extends CI_Model {
	//	market_type: 1=>Local Market, 2=>Outside Market
	//  market_category: 1=>Point, 2=>Depot, 3=>Outside
//Common Function Start

	function get_all_clients( $market_type = '', $client_id = '', $market_id = '' ){
		$this->db->select('id as ID, id, company_name, market_type, city');
		if(!empty($client_id)){
			if(is_array($client_id)){
				$this->db->where_in('id',$client_id);
			}else{
				$this->db->where('id',$client_id);
			}
		}
		if($market_type){
			$this->db->where(array('market_type'=>$market_type));
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
	
	function get_all_fishes($fish_code = ''){
		if(!empty($fish_code)){
			$this->db->where('code', $fish_code);
		}
		$result = $this->db->where(array('status'=>'Active'))->select('name, code')->order_by('name', 'ASC')->get(FISH_CATEGORY)->result_array();
		$data = array();
		if(!empty($result)){
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
		
	function vehicle_numbers($q, $limit = 20){
		$data['results'] = '';
		$this->db->select('vehicle_number as id, vehicle_number as text');
		$this->db->like('vehicle_number', $q);
		$this->db->group_by('vehicle_number');
		$this->db->limit($limit);
		$result = $this->db->get(PRODUCT_DISPATCH)->result_array();
		if(!empty($result)){
			$data['results'] = $result;
		}
		return $data;
	}
	
	function dr_numbers($q = '', $limit = 20){
		$data['results'] = array();
		$this->db->select('dr_number as id, dr_number as text');
		if(!empty($q)){
			$this->db->like('dr_number', $q);
		}
		$this->db->group_start();
			$this->db->where('status', 'Active');
			$this->db->or_where('status', 'Dispatched');
		$this->db->group_end();
		$this->db->group_by('dr_number');
		$this->db->order_by('dispatch_id', 'DESC');
		$this->db->limit($limit);
		$result = $this->db->get(PRODUCT_DISPATCH)->result_array();
		if(!empty($result)){
			$data['results'] = $result;
		}
		return $data;
	}

//Common Function End
	
	//1. Daily Production Report [reports/production_report]	
	function get_production_data($production_date, $market_id = ''){
		$this->db->select('mp.ID as market_id, mp.name as point_name, 
						   IFNULL(dp.point_wt, 0) as point_wt , 
						   IFNULL(dp.forfeiture_wt, 0) as forfeiture_wt, 
						   IFNULL(dp.forfeiture_rt_wt, 0) as forfeiture_rt_wt, 
						   IFNULL(dp.total_pt_wt, 0) as total_pt_wt, 
						   IFNULL(dp.destroyed_wt, 0) as destroyed_wt,
						   IFNULL(dp.rotten_wt, 0) as rotten_wt,
						   IFNULL(dp.depot_wt, 0) as depot_wt,
						   IFNULL(si.paid_sale_wt ,0) as paid_sale_wt,
						   IFNULL(fs.free_sale_wt ,0) as free_sale_wt,
						   IFNULL(dp.jhinga_point_wt, 0) as jhinga_point_wt, 
						   IFNULL(dp.jhinga_depot_wt, 0) as jhinga_depot_wt,
						   ');
		$this->db->from(MARKET_PLACE.' mp');
		$this->db->join(PRODUCTION.' dp','dp.market_id=mp.ID AND dp.production_date="'.$production_date.'" AND dp.status="Active"', 'LEFT');
		
		$this->db->join('(SELECT market_id, SUM(total_wt) as paid_sale_wt				 		
						 FROM '.SALE.' WHERE DATE(sale_date)="'.$production_date.'" AND status="Active" GROUP BY market_id) si','si.market_id=mp.ID', 'LEFT');
		
		$this->db->join('(SELECT market_id, SUM(total_weight) as free_sale_wt				 		
						 FROM '.FREE_SALE.' WHERE DATE(free_sale_date)="'.$production_date.'" AND status="Active" GROUP BY market_id) fs','fs.market_id=mp.ID', 'LEFT');
		
		if($market_id){
			$this->db->where(array('mp.ID'=>$market_id));
		}
		$this->db->where(array('mp.market_category'=>1, 'use_in_report'=>'Yes','mp.status'=>'Active'));
		$result = $this->db->get()->result_array();
		
		return $result;
	}
	
	//2. Fish Category Wise Detail Report [reports/fcw_detail_report]
	function get_fcw_detail_report($from_date, $to_date, $fish_code = '', $market_id = ''){
		$this->db->select('pbi.box_number,
						   pbi.fish_code,
						   pbi.fish_qty,
						   pbi.fish_wt
						  ');
		$this->db->from(PRODUCT_BOX_ITEMS. ' pbi');
		$this->db->where(array('pbi.packing_date >='=>$from_date, 'pbi.packing_date <='=>$to_date));
		$this->db->where('(pbi.status = "Active" OR pbi.status = "Dispatched")');
		if(!empty($fish_code)){
			$this->db->where(array('pbi.fish_code'=>$fish_code));	
		}
		if(!empty($market_id)){
			$this->db->where(array('pbi.market_id'=>$market_id));	
		}
		$this->db->order_by('pbi.box_number');
		$result = $this->db->get()->result_array();
		
		$new_data = array();
		if(!empty($result)){
			foreach($result as $res){
				$new_data[$res['fish_code']][] = array('box_number' => $res['box_number'],
													   'fish_code'  => $res['fish_code'],
													   'fish_qty'   => $res['fish_qty'],
													   'fish_wt'    => $res['fish_wt']
													   );
			}
		}
		return $new_data;
	}
	
	//3. DR For Driver [reports/dr_for_driver]
	function get_dr_for_driver_report($dispatch_date, $vehicle_number, $dispatch_from){
		$this->db->select('pd.*, CONCAT(pd.driver_mobile_no,", ", pd.alter_mobile_no) as driver_mobile_no, mp.name as dispatch_from, mp2.name as dispatch_to, mp.name as depot_name, GROUP_CONCAT(pdb.box_number) as boxes', FALSE);
		$this->db->from(PRODUCT_DISPATCH.' pd');
		$this->db->join(PRODUCT_DISPATCH_BOX.' pdb', 'pdb.dispatch_id=pd.dispatch_id', 'LEFT'); 
		$this->db->join(MARKET_PLACE.' mp', 'mp.ID=pd.dispatch_from', 'LEFT');
		$this->db->join(MARKET_PLACE.' mp2', 'mp2.ID=pd.dispatch_to', 'LEFT');
		$this->db->where(array('DATE(pd.dispatch_date)'=>$dispatch_date, 'pd.vehicle_number'=>$vehicle_number, 'pd.status'=>'Dispatched'));
		//$this->db->where(array('pd.dispatch_from'=>$dispatch_from, 'pd.vehicle_number'=>$vehicle_number, 'pd.status'=>'Dispatched'));
		$result = $this->db->get()->result_array();
		$data = array();
		
		if(!empty($result) && !empty($result[0]['dispatch_id'])){
			$data = $result[0];
			$dispatch_id = $result[0]['dispatch_id'];
			$boxes = $data['boxes'];
			$boxes_array = explode(',', $boxes);
			//printr($data);
			$this->db->select('pbi.box_number, pbi.fish_code, 
							   SUM(pbi.fish_qty) AS quantity, 
							   SUM(pbi.box_wt) AS weight, 
							   f.name, 
							   count(box_number) as box_items'
							  );
			$this->db->from(PRODUCT_BOX_ITEMS.' pbi');
			$this->db->join(FISH.' f', 'f.code=pbi.fish_code', 'LEFT');
			$this->db->where_in('pbi.box_number', '(SELECT box_number FROM '.PRODUCT_DISPATCH_BOX.' WHERE dispatch_id = "'.$dispatch_id.'")', false);
			$this->db->having('box_items > 1'); 
			$this->db->group_by(array('pbi.box_number', 'pbi.fish_code'));
			$mixed_result = $this->db->get()->result_array();
			$data['mix_data'] = $mixed_result;
			
			if(!empty($mixed_result)){
				foreach($mixed_result as $mb){
					$key = array_search($mb['box_number'], $boxes_array);
					unset($boxes_array[$key]);
				}
			}
			
			$this->db->select('pbi.box_number, pbi.fish_code, 
							   SUM(pbi.fish_qty) AS quantity, 
							   SUM(pbi.box_wt) AS weight, 
							   f.name, 
							   count(box_number) as total_box'
							   );
			$this->db->from(PRODUCT_BOX_ITEMS.' pbi');
			$this->db->join(FISH.' f', 'f.code=pbi.fish_code', 'LEFT');
			$this->db->where_in('pbi.box_number', '(SELECT box_number FROM '.PRODUCT_DISPATCH_BOX.' WHERE dispatch_id = "'.$dispatch_id.'")', false);
			$this->db->group_by('pbi.fish_code');
			$fish_data = $this->db->get()->result_array();
			
			$data['fish_data'] = $fish_data;
		}
		
		return $data;
	}
	
	//4. Xbox Serialwise DR Detail report [reports/xbox_report]
	function get_xbox_report($dispatch_date, $dr_number, $dispatch_from){
		$where = array('DATE(pd.dispatch_date)'=>$dispatch_date,
					   'pd.dispatch_from'=>$dispatch_from, 
					   'pd.dr_number'=>$dr_number, 
					   'pd.status'=>'Dispatched');
		
		$this->db->select('pd.*, mp.name as destination, mp.name as depot_name, COUNT(pdb.box_number) as total_boxes', FALSE);
		$this->db->from(PRODUCT_DISPATCH.' pd');
		$this->db->join(PRODUCT_DISPATCH_BOX.' pdb', 'pdb.dispatch_id=pd.dispatch_id', 'LEFT');
		$this->db->join(MARKET_PLACE.' mp', 'mp.ID=pd.dispatch_from', 'LEFT');
		$this->db->where($where);
		$result = $this->db->get()->result_array();
		$data = array();
		$data['box_items'] 		= array();
		if(!empty($result) && !empty($result[0]['dispatch_id'])){
			$dispatch_id = $result[0]['dispatch_id'];
			$data = $result[0];
			$this->db->select('pbi.box_number, pbi.fish_code, pbi.fish_qty, pbi.box_wt');
			$this->db->from(PRODUCT_BOX_ITEMS.' pbi');
			$this->db->where_in('pbi.box_number', '(SELECT box_number FROM '.PRODUCT_DISPATCH_BOX.' WHERE dispatch_id = "'.$dispatch_id.'")', false);
			//$this->db->order_by('cast(pbi.box_number as unsigned)', 'ASC');
			$this->db->order_by('pbi.item_id', 'ASC');
			$box_data = $this->db->get()->result_array();
			$data['box_items'] = $box_data;
		}
		
		return $data;
	}
	
	function get_opening_stock($date, $depot_id){
		$result = $this->db->select('SUM(pb_wt) as total_box_opening,  SUM(cs_wt) as total_carret_opening')->get_where(DAILY_CLOSING_STOCK, array('production_date'=> $date, 'depot_id' => $depot_id))->result_array();
		$stock = array('carret_opening_stock' => 0, 'box_opening_stock' => 0);
		if(!empty($result)){
			$stock['carret_opening_stock'] = $result[0]['total_carret_opening'];
			$stock['box_opening_stock'] = $result[0]['total_box_opening'];
		}
		return $stock;	
	}
	
	//$stock_type = [ 'Current', 'Opening', 'Bachat',] 
	//$sale_from = ['Production', 'Stock', 'All']
	function get_total_sale($date, $market_id, $stock_type = 'Opening', $sale_from = 'All'){
		$total_sale = 0;
		
		$this->db->select('IFNULL(SUM(fish_weight), 0) as total_paid_sale');
		$this->db->where(array('status'=>'Active', 'market_id'=>$market_id, 'DATE(sale_date)'=>$date, 'stock_type'=>$stock_type));
		
		if($sale_from == 'Production'){
			$this->db->where(array('carret_number !=' => "0"));
		}
		
		if($sale_from == 'Stock'){
			$this->db->where("carret_number", "0");
		}	
		
		$paid_sale = $this->db->get(SALE_ITEMS)->result_array();
		if(!empty($paid_sale)){
			$total_sale += $paid_sale[0]['total_paid_sale'];
		}
		
		
		$this->db->select('IFNULL(SUM(fish_weight), 0) as total_free_sale');
		$this->db->where(array('status'=>'Active', 'market_id'=>$market_id, 'DATE(free_sale_date)'=>$date, 'stock_type'=>$stock_type));
		$free_sale = $this->db->get(FREE_SALE_ITEMS)->result_array();
					
		if(!empty($free_sale)){
			$total_sale += $free_sale[0]['total_free_sale'];
		}
		return $total_sale;	
	}
	
	function get_total_production($date, $depot_id, $production_type = 'Point', $use_in_report = ""){						
		/*$this->db->select('depot_wt as total_depot_wt, 
						   (SELECT IFNULL(SUM(s.total_wt),0) FROM '.SALE.' s WHERE s.market_id = p.market_id AND s.status = "Active" AND DATE(s.sale_date) = "'.$date.'" ) as total_paid_sale,
						   (SELECT IFNULL(SUM(fs.total_weight),0) FROM '.FREE_SALE.' fs WHERE fs.market_id = p.market_id AND fs.status = "Active" AND DATE(fs.free_sale_date) = "'.$date.'" ) as total_free_sale,');*/
		if($use_in_report == 'inward_current_stock'){
			$this->db->select('IFNULL(SUM(depot_wt),0) as depot_wt, 
			(SELECT IFNULL(SUM(total_wt), 0) as total_sale_wt from '.SALE.' WHERE market_category = 1 AND DATE(sale_date) = "'.$date.'" AND status="Active") as total_sale_wt,
			(SELECT IFNULL(SUM(total_weight), 0) from '.FREE_SALE.' WHERE market_category = 1 AND DATE(free_sale_date) = "'.$date.'" AND status="Active") as total_free_sale_wt
			
			');
		}else if($production_type == 'Bachateee'){
			$this->db->select('(IFNULL(SUM(depot_wt),0) - IFNULL(SUM(destroyed_wt),0)) as depot_wt');
		}else{
			$this->db->select('IFNULL(SUM(depot_wt),0) as depot_wt');
		}
		
		$this->db->from(PRODUCTION . ' p');
		
		/*if($use_in_report == 'inward_current_stock'){
			$this->db->join('(SELECT IFNULL(SUM(sub_total), 0) as total_sale_wt from '.SALE.' WHERE market_category = 1 AND DATE(sale_date) = "'.$date.'" AND status="Active" GROUP BY market_id) sale', 'sale.market_id = p.market_id', 'LEFT');
		}*/		
		
		//$this->db->join(MARKET_PLACE . ' mp', 'mp.ID = p.market_id', 'LEFT');
		
		//$this->db->where('mp.use_in_report', 'Yes');
		$this->db->where('p.depot_id', $depot_id);
		$this->db->where('p.production_date', $date);
		$this->db->where('p.production_type', $production_type);		
		$result = $this->db->get()->result_array();
		//echo $this->db->last_query();
		//printr($result);
		$total_production = 0;
		if(!empty($result)){
			foreach($result as $val){
				if($use_in_report == 'inward_current_stock'){
					$total_production += ($val['depot_wt'] + $val['total_sale_wt'] + $val['total_free_sale_wt']);
				}else{
					$total_production += $val['depot_wt'];
				}
			}			
		}
		return $total_production;	
	}
	
	function get_outward_data($date, $depot_id){
		// get all dispatched data
		$sql = 'SELECT CONCAT("DR No", dr_number) as invoice_number,
					   CONCAT(mp.name, " (Bx.Wt. ", total_box_wt, ")") as client_name, 
					   total_carret_wt as total_wt FROM '.PRODUCT_DISPATCH.' pd LEFT JOIN '.MARKET_PLACE.' mp ON mp.ID = pd.dispatch_to 
					WHERE pd.dispatch_from = "'.$depot_id.'" AND DATE(pd.dispatch_date) = "'.$date.'"';
			
		// get all Depot paid sale data from Current Stock
		$sql .= ' UNION ALL
				
				SELECT GROUP_CONCAT(DISTINCT CONCAT(" ", s.invoice_number)) as invoice_number,						
						CONCAT(s.client_name, "(Current+Bachat Stock)") as client_name,
						IFNULL(SUM(si.fish_weight), 0) as total_wt FROM '.SALE_ITEMS.' si
						LEFT JOIN '.SALE.' s ON s.sale_id = si.sale_id
						LEFT JOIN '.MARKET_PLACE.' mp ON mp.ID = s.market_id					
					WHERE si.status = "Active" AND  (si.stock_type = "Current" OR si.stock_type = "Bachat") AND s.market_id = "'.$depot_id.'" AND DATE(s.sale_date) = "'.$date.'" GROUP BY s.client_id';
		
		// get all Depot paid sale data from Opening Stock
		$sql .= ' UNION ALL
				
				SELECT GROUP_CONCAT(DISTINCT CONCAT(" ", s.invoice_number)) as invoice_number,						
						CONCAT(s.client_name, "(Opening Stock)") as client_name,
						IFNULL(SUM(si.fish_weight), 0) as total_wt FROM '.SALE_ITEMS.' si
						LEFT JOIN '.SALE.' s ON s.sale_id = si.sale_id	
						
						LEFT JOIN '.MARKET_PLACE.' mp ON mp.ID = s.market_id					
					WHERE si.status = "Active" AND si.stock_type = "Opening" AND s.market_id = "'.$depot_id.'" AND DATE(s.sale_date) = "'.$date.'" GROUP BY s.client_id';
								
		// get all Point paid sale data
		$sql .= ' UNION ALL
				
				SELECT GROUP_CONCAT(CONCAT(" ", s.invoice_number)) as invoice_number,
						mp.name as client_name, 
						IFNULL(SUM(total_wt), 0) as total_wt FROM '.SALE.' s LEFT JOIN '.MARKET_PLACE.' mp ON mp.ID = s.market_id
					WHERE s.status = "Active" AND  s.market_category = 1 AND DATE(s.sale_date) = "'.$date.'" GROUP BY s.market_id';
		
		
		// get all left box to dispatch data pb.status = "Active" AND		
		$sql .= ' UNION ALL
				
				SELECT "" as invoice_number,
					    "Total Left Box To Dispatch From Current" as client_name, 
					   IFNULL(SUM(pb.carret_weight),0) as total_wt FROM '.PRODUCT_BOX.' pb
					WHERE pb.depot_id = "'.$depot_id.'" AND pb.packing_date = "'.$date.'" AND pb.status = "Active"';
					
		// get all left box to dispatch data pb.status = "Active" AND		
		$day_before = strtotime("yesterday", strtotime($date));
		$opening_date = date('Y-m-d', $day_before);
		$sql .= ' UNION ALL
				
				SELECT "" as invoice_number,
					    "Total Left Box To Dispatch From Opening " as client_name, 
					   IFNULL(SUM(pb.carret_weight),0) as total_wt FROM '.PRODUCT_BOX.' pb
					WHERE pb.box_number IN(SELECT dcsb.box_number FROM '.DAILY_CLOSING_STOCK_BOX.' dcsb WHERE dcsb.depot_id = "'.$depot_id.'" AND dcsb.closing_date = "'.$opening_date.'") AND pb.status = "Active"';
		
		// get all transfered production to other depot
		$sql .= ' UNION ALL
				
				SELECT "" as invoice_number,
					    CONCAT("Total Transfer Production To ", IFNULL(mp.name, "Depot")) as client_name, 
					   IFNULL(SUM(pci.fish_wt),0) as total_wt FROM '.PRODUCTION_CARRET_ITEMS.' pci LEFT JOIN '.MARKET_PLACE.' mp ON mp.ID = pci.depot_id
				WHERE pci.production_type = "Transfer" AND pci.market_id = "'.$depot_id.'" AND pci.packing_date = "'.$date.'" AND pci.fish_type != "Destroyed"';
				
		
		// get all Depot free sale data	
		$sql .= ' UNION ALL
				
				SELECT "" as invoice_number,
						CONCAT(mp.name, " (Free Sale)") as client_name, 
						IFNULL(SUM(fs.total_weight), 0) as total_wt FROM '.FREE_SALE.' fs LEFT JOIN '.MARKET_PLACE.' mp ON mp.ID = fs.market_id
					WHERE fs.status = "Active" AND  fs.market_id = "'.$depot_id.'" AND DATE(fs.free_sale_date) = "'.$date.'" GROUP BY fs.market_id';
		
		// get all Point free sale data				
		$sql .= ' UNION ALL
				
				SELECT "" as invoice_number,
						"All Point Free Sale" as client_name, 
						IFNULL(SUM(fs.total_weight), 0) as total_wt FROM '.FREE_SALE.' fs
					WHERE fs.status = "Active" AND fs.market_category = 1 AND market_id NOT IN(SELECT ID FROM '.MARKET_PLACE.' WHERE use_in_report = "No") AND DATE(fs.free_sale_date) = "'.$date.'" GROUP BY fs.market_category';
		
		// get all Depot Destroyed sale data				
		$sql .= ' UNION ALL
				
				SELECT "" as invoice_number,
						CONCAT("Destroyed Sale from ", mp.name) as client_name, 
						IFNULL(SUM(destroyed_weight), 0) as total_wt FROM '.SALE.' s LEFT JOIN '.MARKET_PLACE.' mp ON mp.ID = s.market_id
					WHERE s.status = "Active" AND  s.market_id = "'.$depot_id.'" AND DATE(s.sale_date) = "'.$date.'" GROUP BY s.market_id';
		
		// get all Depot Destroyed Feka data	UNION ALL	
		$sql .= ' UNION ALL
				
				SELECT 
					 "" as invoice_number,
					 CONCAT("Destroyed Feka ", IFNULL(dst.destroyed_items, " From Depot") ) as client_name, 
					 (dst.total_carret_weight - IFNULL(SUM(s.destroyed_weight),0) ) as total_wt 
					FROM (SELECT 
						depot_id, 
						GROUP_CONCAT(CONCAT(tmp.production_type, "-", tmp.carret_weight) SEPARATOR "|") as destroyed_items, 
								 IFNULL(SUM(carret_weight),0) as total_carret_weight 
							   FROM (SELECT 
								  depot_id, 
										production_type, 
										IFNULL(SUM(carret_weight),0) as carret_weight 
									 FROM '.PRODUCTION_CARRET.' 
									 WHERE status = "Active" AND 
										   fish_type = "Destroyed" AND 
										   depot_id = "'.$depot_id.'" AND 
										   packing_date = "'.$date.'" 
									 GROUP BY production_type
									 ) tmp
							   ) dst
					LEFT JOIN ( SELECT market_id, IFNULL(SUM(destroyed_weight),0) as destroyed_weight 
					   FROM '.SALE.' 
								WHERE   status = "Active" AND 
										market_id = "'.$depot_id.'" AND 
										DATE(sale_date) = "'.$date.'" 
								GROUP BY market_id
							   ) s ON s.market_id = dst.depot_id';
		
		$result = $this->db->query($sql)->result_array();
		
		$total_point_bachat = $this->get_total_point_bachat_data($date, $depot_id);
		$total_bachat_production = $this->get_total_production($date, $depot_id, 'Bachat');		
		//printr($total_bachat_production);
		$bachat_sortage = ($total_bachat_production != 0) ? ($total_point_bachat - $total_bachat_production) : 0;		
		$bachat_data = array(array('invoice_number'=>'', 'client_name'=>'Bachat Shortage from sorting', 'total_wt'=>$bachat_sortage));		
		$data = array_merge($result, $bachat_data);		
		return $data;	
	}
	
	function get_total_point_bachat_data($bachat_date, $depot_id){
						
		$this->db->select('IFNULL(SUM(pci.fish_wt), 0) as production_wt');
		$this->db->from(PRODUCTION_CARRET_ITEMS . ' pci');
						
		$this->db->where('pci.depot_id', $depot_id);
		$this->db->where('pci.packing_date', $bachat_date);
		$this->db->where('pci.fish_type !=', 'Destroyed');
		$this->db->where('pci.client_id', '0');
		$this->db->where('pci.box_number', "");
		$this->db->where_in('pci.market_id', "(SELECT ID FROM ".MARKET_PLACE." WHERE use_in_report = 'Yes')", false);
		$this->db->group_by(array('pci.depot_id'));
		$result = $this->db->get()->result_array();	
		//echo $this->db->last_query();	
		//printr($result);
		$production = 0;
		if(!empty($result)){
			foreach($result as $value){
				$production += $value['production_wt'];
			}
		}
		return $production;		
	
	}
	
	function get_free_sale($sale_date, $market_id, $market_type = '1', $market_category ='2'){	
		$this->db->select('fsi.id, fsi.fish_code, 					
						   IFNULL(SUM(fsi.fish_quantity), 0) as fish_quantity, 
						   IFNULL(SUM(fsi.fish_weight), 0) as fish_weight,  
						   fs.remark, c.company_name as client_name, f.name as fish_name, fc.name as fish_category_name,
						   mp.name as market_name');
		$this->db->from(FREE_SALE_ITEMS . ' fsi');		
		$this->db->join(FREE_SALE. ' fs', 		'fs.free_sale_id = fsi.free_sale_id', 'LEFT');
		$this->db->join(CLIENT. ' c', 			'c.ID = fs.client_id', 'LEFT');
		$this->db->join(FISH. ' f', 			'f.code = fsi.fish_code', 'LEFT');
		$this->db->join(FISH_CATEGORY. ' fc', 	'fc.ID = f.category_id', 'LEFT');
		$this->db->join(MARKET_PLACE. ' mp', 	'mp.ID = fsi.market_id', 'LEFT'); 
		
		$this->db->where('DATE(fsi.free_sale_date)', $sale_date);
		//$this->db->where('fsi.market_type', $market_type);
		$this->db->where('fsi.market_category', $market_category);
		$this->db->where('fsi.market_id', $market_id);
		$this->db->group_by(array('fsi.fish_code', 'fs.client_id'));
		$this->db->order_by('client_name', 'ASC');
		$result = $this->db->get()->result_array();
		return $result;
	}
	
	function get_local_sale($from_date, $to_date, $market_category, $market_place){
		//si.sale_id, si.fish_code, si.fish_quantity, si.fish_weight, si.fish_rate, si.remark, si.total_amount,
		$this->db->select('s.*, s.id as sale_id, mp.name as point_name, IFNULL(mp.code, "") as mp_code');
		$this->db->from(SALE . ' s');
		$this->db->join(CLIENT . ' c', ' c.ID = s.client_id', 'LEFT');
		$this->db->join(MARKET_PLACE . ' mp', ' mp.ID = s.market_id', 'LEFT');
		
		$this->db->where('DATE(s.sale_date) >=', $from_date);
		$this->db->where('DATE(s.sale_date) <=', $to_date);
		if($market_category){
			$this->db->where('s.market_category', $market_category);
		}
		if($market_place){
			$this->db->where('s.market_id', $market_place);
		}
		$this->db->where('s.market_type', 1);
		$this->db->order_by('s.invoice_number', 'ASC');
		$this->db->order_by('DATE(s.sale_date)', 'ASC');
		$result = $this->db->get()->result_array();
		return $result;		
	}
	
	function get_local_sale_detail($sale_id){
		
		$this->db->select('s.*, s.client_mobile as contact_number, c.contact_number2,
							si.sale_id, si.fish_code, 
							IFNULL(SUM(si.fish_quantity),0) as fish_quantity, 
							IFNULL(SUM(si.fish_weight),0) fish_weight, 
							si.fish_rate, si.remark, 
							IFNULL(SUM(si.total_amount),0) as total_amount,
							mp.name as point_name,
						     f.name as fish_name, fc.name as fish_category_name');
		$this->db->from(SALE_ITEMS . ' si');
		$this->db->join(SALE . ' s', ' s.sale_id = si.sale_id', 'LEFT');
		$this->db->join(CLIENT . ' c', ' c.ID = s.client_id', 'LEFT');
		$this->db->join(MARKET_PLACE . ' mp', ' mp.ID = s.market_id', 'LEFT');
		$this->db->join(FISH . ' f', ' f.code = si.fish_code', 'LEFT');
		$this->db->join(FISH_CATEGORY . ' fc', ' fc.ID = f.category_id', 'LEFT');
		$this->db->where('si.sale_id', $sale_id);
		$this->db->group_by(array('si.fish_code', 'si.fish_rate'));
		$this->db->order_by('si.fish_code', 'ASC');
		$result = $this->db->get()->result_array();
		return $result;		
	}
	
	function get_customer_old_remaing($client_id, $sale_datetime){
		//SALE Data
		$this->db->select('IFNULL(SUM(grand_total), 0) as grand_total,IFNULL(SUM(received_amt), 0) as received_amt');
		$this->db->where(array('client_id'=>$client_id, 'DATE(sale_date) <= '=>$sale_datetime, 'status'=>'Active'));
		$sale_result = $this->db->get(SALE)->result_array();
		
		//CASH_RECEIVED Data
		$this->db->select('IFNULL(SUM(amount), 0) as cash_received_amount');
		$this->db->where(array('client_id'=>$client_id, 'payment_date <'=>$sale_datetime, 'status'=>'Active'));
		$cash_received_result = $this->db->get(CASH_RECEIVED)->result_array();
		
		$old_remaing = $sale_result[0]['grand_total'] - ($sale_result[0]['received_amt']+$cash_received_result[0]['cash_received_amount']);
		return $old_remaing;
	}
	
	//Changes manish
	//6. Comparison Report [reports/comparison_report]
	function get_comparison_report($dr_number,  $market_id='', $client_id=''){
		$boxes = array();
		if(!empty($market_id) && $market_id > 0){
			$this->db->where('dispatch_to',$market_id);
		}
		$dispatch_info = $this->db->select('dispatch_id, dispatch_date, vehicle_number')
			->order_by('dispatch_id', 'ASC')
			->where('dr_number', $dr_number)
			->where_in('status', array('Active', 'Dispatched'))
			->get(PRODUCT_DISPATCH)
			->result_array();
		
		$veh = !empty($dispatch_info[0]['vehicle_number']) ? $dispatch_info[0]['vehicle_number'] : '';
		$ddate = !empty($dispatch_info[0]['dispatch_date']) ? get_date('d-m-Y', $dispatch_info[0]['dispatch_date']) : '';
		$response['dr_info'] = 'DR NO. '.$dr_number.' '.$veh.' ( '.$ddate.' )';
		
		if($client_id){
			$this->db->select('si.box_number');
			$this->db->from(SALE.' s');
			$this->db->join(SALE_ITEMS.' si', 'si.sale_id=s.sale_id', 'LEFT');
			$this->db->where(array('s.dr_number'=>$dr_number, 's.client_id'=>$client_id));
			$this->db->where_in('s.status', array('Active', 'Dispatched'));
			$this->db->group_by('si.box_number');
			$this->db->order_by('cast(si.box_number as unsigned)', 'ASC');
			$result = $this->db->get()->result_array();
			if(!empty($result)){
				$boxes = array_column($result, 'box_number');
			}
		}else{
			$dispatch_id_array = !empty($dispatch_info) ? array_unique(array_map(function($elem){return $elem['dispatch_id'];}, $dispatch_info)) : array();
			if(!empty($dispatch_id_array)){
				$this->db->select('box_number');
				$this->db->from(PRODUCT_DISPATCH_BOX);
				$this->db->where_in('dispatch_id', $dispatch_id_array);
				$this->db->where('dr_number', $dr_number);
				$this->db->where_in('status', array('Active', 'Dispatched'));
				$this->db->group_by('box_number');
				$this->db->order_by('cast(box_number as unsigned)', 'ASC');
				$result = $this->db->get()->result_array();
				if(!empty($result)){
					$boxes = array_column($result, 'box_number');
				}
			}
		}
		
		$response['total_boxes'] = count($boxes);
		
		$response['boxes'] = array();
		if(!empty($boxes)){
			$this->db->select('pbi.box_number, pbi.estimated_price, pbi.fish_code, pbi.fish_qty, pbi.box_wt,
							   si.sale_id, si.net_wt, si.fish_rate, si.total_amount,
							   s.commission,
							   cl.company_name as client_name, pdb.dispatch_id, 
							   pd.dhalta as est_dhalta, 
							   pd.commission as est_commission, 
							   pd.expenses as est_expenses
							  ');
			$this->db->from(PRODUCT_BOX_ITEMS.' pbi');
			$this->db->join(SALE_ITEMS.' si', 'si.box_number=pbi.box_number AND si.fish_code=pbi.fish_code', 'LEFT');
			$this->db->join(SALE.' s', 's.sale_id=si.sale_id', 'LEFT');
			$this->db->join(CLIENT.' cl', 'cl.ID=si.client_id', 'LEFT');
			
			$this->db->join(PRODUCT_DISPATCH_BOX.' pdb', 'pdb.box_number=pbi.box_number', 'LEFT');
			$this->db->join(PRODUCT_DISPATCH.' pd', 'pd.dispatch_id=pdb.dispatch_id', 'LEFT');
			
			$this->db->where_in('pbi.box_number', $boxes);
			$this->db->order_by('cast(pbi.box_number as unsigned)', 'ASC');
			$boxes_data = $this->db->get()->result_array();
			
			$sale_id_array = array();
			if(!empty($boxes_data)){
				$sale_id_array = array_unique(array_map(function($elem){return $elem['sale_id'];}, $boxes_data));
				$amount = $this->db->select('IFNULL(SUM(amount), 0) as expenses')->where_in('sale_id', $sale_id_array)->get(SALE_EXPENDITURE)->result_array();
				$response['expenses'] = $amount[0]['expenses'];
				$response['boxes'] = $boxes_data;
			}
		}
		
		return $response;
	}
	
	//6. Comparison Report [reports/comparison_report]
	function get_dr_summary($from_date, $to_date, $market_id){
		if($market_id){
			$this->db->where('pd.dispatch_from',$market_id);
		}
		$this->db->select('pd.dr_number,
						  (SELECT GROUP_CONCAT(box_number) 
						       FROM '.PRODUCT_DISPATCH_BOX.' pdb 
							   WHERE pdb.dispatch_id = pd.dispatch_id
							   ORDER BY CAST(pdb.box_number AS UNSIGNED) ASC
						   ) as boxes
						 ', FALSE);
		$this->db->from(PRODUCT_DISPATCH.' pd');
		$this->db->where(array('DATE(pd.dispatch_date) >='=>$from_date, 'DATE(pd.dispatch_date) <='=>$to_date, 'pd.status'=>'Dispatched'));
		$result = $this->db->get()->result_array();
		
		$response = array();
		if(!empty($result)){
			$dr_numbers = array_column($result, 'dr_number');
			
			$this->db->select('s.sale_id,
							   pd.dispatch_date, pd.vehicle_number, pd.total_box_qty, pd.dr_number, pd.total_box,
							   mp.name as market_name, s.sale_date, cl.company_name as client_name, s.total_gross_wt, s.total_net_wt,
							  (SELECT COUNT(DISTINCT box_number) 
								   FROM '.SALE_ITEMS.' si 
								   WHERE si.sale_id=s.sale_id
							   ) as boxes_sold
							  ', FALSE);
			$this->db->from(SALE.' s');
			$this->db->join(CLIENT.' cl', 'cl.ID=s.client_id', 'LEFT');
			$this->db->join(MARKET_PLACE.' mp', 'mp.ID=s.market_id', 'LEFT');
			$this->db->join(PRODUCT_DISPATCH.' pd', 'pd.dr_number=s.dr_number', 'LEFT');
			$this->db->where_in('s.dr_number', $dr_numbers);
			$this->db->where(array('s.status'=>'Active'));
			$sale_data = $this->db->get()->result_array();
			
			
			if(!empty($sale_data)){
				foreach($sale_data as $sale){
					$response[$sale['dr_number']][] = $sale;
				}
			}
		}
		return $response;
	}
		
	function get_all_boxes_report($production_date, $depot_id = '', $box_status = '' ){
		$this->db->select('mp.name as depot_name, mp2.name as point_name, pb.box_number, pb.status');
		if($depot_id){
			$this->db->where('depot_id', $depot_id);
		}
		$this->db->from(PRODUCT_BOX.' pb');
		$this->db->join(MARKET_PLACE.' mp', 'mp.ID=pb.depot_id', 'LEFT');
		$this->db->join(MARKET_PLACE.' mp2', 'mp2.ID=pb.market_id', 'LEFT');
		$whereArr = array('pb.packing_date'=>$production_date);
		if(!empty($box_status)){
			$whereArr['pb.status'] = $box_status;
		}
		$this->db->where($whereArr);
		$this->db->order_by('pb.box_number', 'ASC');
		$result = $this->db->get()->result_array();
		return $result;
	}
}
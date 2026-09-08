<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_report_model extends CI_Model {
	//  market_category: 1=>Point, 2=>Depot, 3=>Outside
	//	market_type: 1=>Local Market, 2=>Outside Market
	//Common Function Start
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
			$this->db->where(array('ID'=>$id));
		}
		if(!empty($market_type)){
			$this->db->where(array('market_type'=>$market_type));
		}
		$this->db->where(array('market_category'=>$market_category));
		$result = $this->db->where(array('status'=>'Active'))->select('ID, name, code')->order_by('name', 'ASC')->get(MARKET_PLACE)->result_array();
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
	
	function get_destroyed_stock($from_date, $to_date, $market_id){
						
		$this->db->select('f.ID, f.code, f.name as fish_name, fc.name as category_name, 
						   IFNULL(pci.fish_qty, 0) as production_qty, 
						   IFNULL(pci.fish_wt,0) as production_wt,
						   pci.market_name');
		$this->db->from(FISH . ' f');
		
		$this->db->join(FISH_CATEGORY. ' fc', 'fc.ID = f.category_id', 'LEFT');
		
		$this->db->join('(SELECT pci2.fish_code, SUM(pci2.fish_qty) as fish_qty, SUM(pci2.fish_wt) as fish_wt, mp.name as market_name FROM '.PRODUCTION_CARRET_ITEMS.' pci2 LEFT JOIN '.MARKET_PLACE.' mp ON mp.ID = pci2.market_id
				WHERE depot_id = "'.$market_id.'" AND packing_date = "'.$from_date.'"  AND fish_type = "Destroyed" GROUP BY fish_code, pci2.market_id)  pci', 'pci.fish_code = f.code', 'LEFT');
		
		$this->db->where('f.status', 'Active');
		$this->db->order_by('pci.market_name', 'ASC');
		$this->db->order_by('fish_name', 'ASC');
		
		$result = $this->db->get()->result_array();
		//echo $this->db->last_query();
		//die;
		return $result;		
	
	}
	
	function get_free_sale_data($from_date, $to_date, $market_category){
		
		$this->db->select('fsi.id, fsi.fish_code, 					
						   IFNULL(SUM(fsi.fish_quantity), 0) as fish_quantity, 
						   IFNULL(SUM(fsi.fish_weight), 0) as fish_weight, fs.remark, 
						   c.company_name as client_name, f.name as fish_name, fc.name as fish_category_name,
						   CONCAT(a.first_name, " ", a.last_name) as added_by_name,
						   mp.name as market_name', FALSE);
		$this->db->from(FREE_SALE_ITEMS . ' fsi');		
		$this->db->join(FREE_SALE. ' fs', 		'fs.free_sale_id = fsi.free_sale_id', 'LEFT');
		$this->db->join(CLIENT. ' c', 			'c.ID = fs.client_id', 'LEFT');
		$this->db->join(FISH. ' f', 			'f.code = fsi.fish_code', 'LEFT');
		$this->db->join(FISH_CATEGORY. ' fc', 	'fc.ID = f.category_id', 'LEFT');
		$this->db->join(ADMINISTRATOR. ' a', 	'a.admin_id = fsi.added_by', 'LEFT');
		$this->db->join(MARKET_PLACE. ' mp', 	'mp.ID = fsi.market_id', 'LEFT'); 
		
		$this->db->where('fsi.status', 'Active');
		$this->db->where('DATE(fsi.free_sale_date) >= ', $from_date);
		$this->db->where('DATE(fsi.free_sale_date) <= ', $to_date);
		$this->db->where('fsi.market_category', $market_category);
		
		$this->db->group_by(array('fsi.fish_code', 'fs.client_id'));
		$this->db->order_by('client_name', 'ASC');
		$result = $this->db->get()->result_array();
		$this->db->last_query();
		return $result;		
	
	}
	
	function get_point_bachat_data($bachat_date, $depot_id){
						
		$this->db->select('pci.market_id, pci.client_id, pci.box_number, pci.fish_code, f.name as fish_name, fc.name as category_name, 
						   IFNULL(SUM(pci.fish_qty), 0) as production_qty, 
						   IFNULL(SUM(pci.fish_wt), 0) as production_wt,
						   mp.name as market_name', false);
		$this->db->from(PRODUCTION_CARRET_ITEMS . ' pci');
		$this->db->join(MARKET_PLACE . ' mp', 'mp.ID = pci.market_id', 'LEFT');
		$this->db->join(FISH. ' f', 'f.code = pci.fish_code', 'LEFT');
		$this->db->join(FISH_CATEGORY. ' fc', 'fc.ID = f.category_id', 'LEFT');
						
		$this->db->where('pci.depot_id', $depot_id);
		$this->db->where('pci.packing_date', $bachat_date);
		$this->db->where('pci.fish_type !=', 'Destroyed');
		$this->db->where('pci.client_id', '0');
		$this->db->where('pci.box_number', "");
		$this->db->where_in('pci.market_id', "(SELECT ID FROM ".MARKET_PLACE." WHERE use_in_report = 'Yes')", false);
		$this->db->group_by(array('pci.fish_code', 'pci.market_id'));
		$this->db->order_by('market_name', 'ASC');
		$result = $this->db->get()->result_array();	
		//echo $this->db->last_query();	
		//printr($result);
		return $result;		
	
	}
	
}
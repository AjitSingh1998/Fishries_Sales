<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup_model extends CI_Model {
	
	function get_market_types($id = ''){
		$data = array();
		if($id){
			$this->db->where(array('ID' => $id));
		}
		$this->db->where(array('status' => 'Active'));
		$result = $this->db->get(MARKET_TYPE)->result_array();
		return $result;
	}
	
	function get_market_category($market_type = '', $ID = ''){
		if(!empty($ID)){
			$this->db->where('ID', $ID);
		}
		if(!empty($market_type)){
			$this->db->where('market_type', $market_type);
		}
		$this->db->where(array('status'=>'Active'));
		$this->db->select('ID, market_type, name, code');
		$this->db->order_by('name', 'ASC');
		$result = $this->db->get(MARKET_CATEGORY)->result_array();
		$data = array();
		if(!empty($result)){
			$data = $result;
		}
		return $data;		
	}
	
	function get_client_data($id){
		$this->db->select('c.*, mt.name as market_type_name, mp.name as city_name');
		$this->db->from(CLIENT.' c');
		$this->db->join(MARKET_TYPE.' mt', 'mt.ID = c.market_type', 'LEFT');
		$this->db->join(MARKET_PLACE.' mp', 'mp.ID = c.city', 'LEFT');
		$this->db->where('c.ID', $id);
		$result = $this->db->get()->result_array();
		$data = array();
		if(!empty($result)){
			$data = $result[0];
		}
		return $data;
	}
	
	function get_mp_data($ID = '', $market_type = ''){
		$data = array();
		if(!empty($ID)){
			$this->db->where('ID', $ID);
		}
		if(!empty($market_type)){
			$this->db->where('market_type', $market_type);
		}
		$this->db->select('ID, market_type, market_category, name, code, status');
		$this->db->order_by('name', 'ASC');
		$result = $this->db->get(MARKET_PLACE)->result_array();
		$data = array();
		if(!empty($result)){
			$data = $result;
		}

		return $data;
	}
}



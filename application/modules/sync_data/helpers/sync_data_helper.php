<?php
/**
 * @author   Natan Felles <natanfelles@gmail.com>
 */
defined('BASEPATH') OR exit('No direct script access allowed');

// check local data existance in live database and get primary data of the table
function get_live_primary_data($table_name, $primary_key, $local_primary_data, $clientID){
	$CI = & get_instance();
	
	$CI->db->select($primary_key .', transaction_id');
	$CI->db->where('client_key', $clientID);
	$CI->db->where_in('transaction_id', $local_primary_data);
	$existance = $CI->db->get($table_name)->result_array();
	$live_primary_data = array();
	if(!empty($existance)){				
		foreach($existance as $pd){
			$live_primary_data[$pd['transaction_id']] = $pd[$primary_key];
		}
	}
	return $live_primary_data;
}
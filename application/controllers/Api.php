<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller {
	
	function __construct(){
		$this->load->library('rest');
	}
	
	
	public function index()
	{
		// Pull in an array of tweets
		$users = ['id' => 100, 'name' => 'John', 'email' => 'john@example.com', 'fact' => 'Loves coding'];
            
		
		$tweets = $this->rest->get('api/example/users', $users);
		printr($tweets);
	}
		
}

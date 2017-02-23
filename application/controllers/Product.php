<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		
		die("hi");
	}
	
	public function product_list(){
		$this->load->view('product_list'); 
	}
}
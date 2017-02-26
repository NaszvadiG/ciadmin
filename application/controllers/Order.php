<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Order extends CI_Controller {
		
		public $data = array();
		public $loggedin_method_arr = array();
		
		public function __construct(){
			parent::__construct();
			
			$this->load->model('orderdata');
			
			$this->data = $this->defaultdata->getFrontendDefaultData();
			
			if(in_array($this->data['tot_segments'][2], $this->loggedin_method_arr))
			{
				if($this->defaultdata->is_session_active() == 0)
				{
					redirect(base_url());
				}
			}
		}
		
		public function order_list()
		{
			$like = array();
			parse_str($_SERVER['QUERY_STRING'], $like);
			unset($like['page']);
			
			$search_key = $this->input->get('name');
			if(isset($search_key) && $search_key){
				$this->data['search_key'] = $search_key;
			}else{
				$this->data['search_key'] = '';
			}
			
			$cond["status"] = '1';
			$order_data = $this->orderdata->grab_total_order($cond, array(), $like); 
			
			//pagination settings
			$config['base_url'] = site_url('order-list');
			$config['total_rows'] = count($order_data);
			
			$pagination = $this->config->item('pagination');
			
			$pagination = array_merge($config, $pagination);

			$this->pagination->initialize($pagination);
			$this->data['page'] = ($this->input->get('page')) ? $this->input->get('page') : 0;		

			$this->data['pagination'] = $this->pagination->create_links();
			
			$order_paginated_data = $this->orderdata->grab_order($cond, array(PAGINATION_PER_PAGE, $this->data['page']), $like);
			
			$this->data['order_list'] = $order_paginated_data;
			
			$this->load->view('order_list', $this->data);
		}
		
		public function order_delete($order_id){			
			$cond['order_id'] = $order_id;
			
			if($this->orderdata->delete_order($cond)){
				redirect($this->agent->referrer());		
			}
		}
	}
?>
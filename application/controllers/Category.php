<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends CI_Controller{
	
	public $data = array();
	public $loggedin_method_arr = array('dashboard', 'profile', 'settings', 'category-list');
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model('categorydata');
			
		$this->data = $this->defaultdata->getFrontendDefaultData();
		
		if(in_array($this->data['tot_segments'][2], $this->loggedin_method_arr))
		{
			if($this->defaultdata->is_session_active() == 0)
			{
				redirect(base_url());
			}
		}
	}
	
	public function category_list(){
		$like = array();
		parse_str($_SERVER['QUERY_STRING'], $like);
		unset($like['page']);
		
		$category_data = $this->categorydata->grab_category(array(), $like, array());
		echo '<br>'.count($category_data).'<br>';
		
		//pagination settings
		$config['base_url'] = site_url('category-list');
		$config['total_rows'] = count($category_data);
		
		$pagination = $this->config->item('pagination');
		
		$pagination = array_merge($config, $pagination);

		$this->pagination->initialize($pagination);
		$page = ($this->input->get('page')) ? $this->input->get('page') : 0;		

		$this->data['pagination'] = $this->pagination->create_links();
		
		$category_paginated_data = $this->categorydata->grab_category(array(), $like, array(PAGINATION_PER_PAGE, $this->data['page']));
		$this->data['category_details'] = $category_paginated_data;
		
		$this->load->view('category_list', $this->data); 
	}
}
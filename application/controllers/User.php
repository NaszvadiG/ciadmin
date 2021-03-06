<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class User extends CI_Controller {
		
		public $data = array();
		public $loggedin_method_arr = array('dashboard', 'profile', 'settings');
		
		public function __construct(){
			parent::__construct();
			
			$this->load->model('userdata');
			
			$this->data = $this->defaultdata->getFrontendDefaultData();
			
			if(in_array($this->data['tot_segments'][2], $this->loggedin_method_arr))
			{
				if($this->defaultdata->is_session_active() == 0)
				{
					redirect(base_url());
				}
			}
		}
		
		public function index()
		{			
			$this->load->view('login', $this->data); 
		}
		
		public function process_login(){
			$post_data = $this->input->post();
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules('username', 'Username', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'trim|required');
			
			if($this->form_validation->run() == FALSE)
			{	
				$this->session->set_userdata('login_error', 'true');
				redirect(base_url());
			}
			else
			{
				$username = $post_data['username'];
				$password = $post_data['password'];
				
				$encrypted_password = base64_encode(hash("sha256", $password, True));
				
				$data = array(
					"username" => $username,
					"password" => $encrypted_password
				);
				
				$user_data = $this->userdata->grab_user_details($data);
				if(count($user_data) > 0){
					$this->defaultdata->setLoginSession($user_data[0]);
					
					redirect(base_url('dashboard'));					
				}else{
					$this->session->set_userdata('login_error', 'true');
					redirect(base_url());					
				}
			}
		}
		
		public function dashboard(){
			$this->load->view('dashboard', $this->data);
		}
		
		public function profile(){
			$data = array();
			$data['user_id'] = $this->session->usrid;
			$user_data = $this->userdata->grab_user_details($data);
			
			if($this->session->userdata('has_error')){
				$this->data['profile_data'] = (object)$this->session->userdata;
			}else{
				$this->data['profile_data'] = $user_data[0];
			}
			
			$this->load->view('profile', $this->data);
		}
		
		public function process_profile(){
			$post_data = $this->input->post();
			
			$username = $post_data['username'];
			$old_username = $post_data['old_username'];
			$password = $post_data['password'];
			$old_password = $post_data['old_password'];
			$email = $post_data['email'];
			$old_email = $post_data['old_email'];
			$is_active = $post_data['is_active'];
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('username', 'Username', 'trim|required');
			if($username != $old_username){
				$this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique['.TABLE_USER.'.username]');
			}
			if($password){
				$this->form_validation->set_rules('password', 'Password', 'trim|min_length[6]|max_length[20]');
			}else{
				$password = $old_password;
			}
			if($email != $old_email){
				$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique['.TABLE_USER.'.email]');
			}
			
			$this->session->unset_userdata($post_data);
			if($this->form_validation->run() == FALSE)
			{	
				$this->session->set_userdata($post_data);
				$this->session->set_userdata('has_error', true);
				$this->session->set_userdata('profile_notification', validation_errors());
			}else{				
				$encrypted_password = base64_encode(hash("sha256", $password, True));
				
				$cond = array("user_id" => $this->session->usrid);
				$data = array(
					"username" => $username,
					"password" => $encrypted_password,
					"original_password" => $password,
					"email" => $email,
					"is_active" => $is_active,
					"date_added" => time()
				);
				
				$this->userdata->update_user_details($cond, $data);
				
				$this->session->set_userdata('has_error', false);
				$this->session->set_userdata('profile_notification', 'Profile changes have been saved successfully.');
			}
			
			redirect($this->agent->referrer());
		}
		
		public function settings(){		
			if($this->session->userdata('has_error')){
				$this->data['settings_data'] = (object)$this->session->userdata;
			}else{
				$this->data['settings_data'] = $this->defaultdata->grabSettingData();
			}
			
			$this->load->view('settings', $this->data);
		}
		
		public function process_setings(){
			
			$post_data = $this->input->post();
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('sitename', 'Sitename', 'trim|required');
			$this->form_validation->set_rules('siteaddress', 'Siteaddress', 'trim|required');
			
			$this->session->unset_userdata($post_data);
			if($this->form_validation->run() == FALSE)
			{	
				$this->session->set_userdata($post_data);
				$this->session->set_userdata('has_error', true);
				$this->session->set_userdata('settings_notification', validation_errors());
			}else{
				$data = array(
					"sitename" => $post_data['sitename'],
					"siteaddress" => $post_data['siteaddress'],
					"date_added" => time()
				);
				
				if(isset($_FILES) && $_FILES['logo']['error'] == 0){
					$filename = $_FILES['logo']['name'];
					$file_ext = pathinfo($filename, PATHINFO_EXTENSION);
					$file_name = 'logo'.'.'.$file_ext;
					
					array_map('unlink', glob(UPLOAD_LOGO_PATH."*"));
					if(move_uploaded_file($_FILES['logo']['tmp_name'], UPLOAD_LOGO_PATH.$file_name)){
						$data['logoname'] = $file_name;
						$data['logopathname'] = UPLOAD_LOGO_PATH.$file_name;
					}
				}
				
				$cond = array("settings_id" => $post_data['settings_id']);
				
				$this->userdata->update_settings_details($cond, $data);
				
				$this->session->set_userdata('has_error', false);
				$this->session->set_userdata('settings_notification', 'Settings changes have been saved successfully.');
			}
			
			redirect($this->agent->referrer());			
		}
		
		public function user_list()
		{
			$like = array();
			parse_str($_SERVER['QUERY_STRING'], $like);
			unset($like['page']);
			
			$search_key = $this->input->get('username');
			if(isset($search_key) && $search_key){
				$this->data['search_key'] = $search_key;
			}else{
				$this->data['search_key'] = '';
			}
			
			$cond["role"] = '1';
			$user_data = $this->userdata->grab_user_details($cond, array(), $like); 
			
			//pagination settings
			$config['base_url'] = site_url('user-list');
			$config['total_rows'] = count($user_data);
			
			$pagination = $this->config->item('pagination');
			
			$pagination = array_merge($config, $pagination);

			$this->pagination->initialize($pagination);
			$this->data['page'] = ($this->input->get('page')) ? $this->input->get('page') : 0;		

			$this->data['pagination'] = $this->pagination->create_links();
			
			$user_paginated_data = $this->userdata->grab_user_details($cond, array(PAGINATION_PER_PAGE, $this->data['page']), $like);			
			
			$this->data['user_details'] = $user_paginated_data;
			
			$this->load->view('user_list', $this->data);
		}
		
		public function user_add(){				
			if($this->session->userdata('has_error')){
				$this->data['user_details'] = (object)$this->session->userdata;
			}
			
			$this->load->view('user_add', $this->data);
		}
		
		public function add_user(){
			$post_data = $this->input->post();
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique['.TABLE_USER.'.username]');
			$this->form_validation->set_rules('password', 'Password', 'trim|required');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique['.TABLE_USER.'.email]');
			
			$this->session->unset_userdata($post_data);
			if($this->form_validation->run() == FALSE)
			{	
				$this->session->set_userdata($post_data);
				$this->session->set_userdata('has_error', true);
				$this->session->set_userdata('useradd_notification', validation_errors());
				
				redirect($this->agent->referrer());
			}else{
				$data = array(
					"username" => $post_data['username'],
					"password" => base64_encode(hash("sha256", $post_data['password'], True)),
					"original_password" => $post_data['password'],
					"email" => $post_data['email'],
					"user_discount" => $post_data['user_discount'],
					"is_active" => $post_data['is_active'],
					"date_added" => time()
				);
				$this->userdata->insert_user($data);
				
				$this->session->set_userdata('has_error', false);
				
				redirect(base_url('user-list'));
			}
		}
		
		public function user_edit($username){
			if(!$this->session->userdata('has_error')){
				$cond['username'] = $username;
				$user_data = $this->userdata->grab_user_details($cond);
				
				$this->data['user_details'] = $user_data[0];
			}else{
				$this->data['user_details'] = (object)$this->session->userdata;
			}
			
			$this->load->view('user_edit', $this->data);
		}
		
		public function hasSamePassword($pass){
			$user_data = $this->userdata->grab_user_details(array("original_password" => $pass));
			if(count($user_data) > 0){
				$this->form_validation->set_message('hasSamePassword', 'Same password given');
				return false;
			}else{
				return true;
			}
		}
		
		public function edit_user(){
			$post_data = $this->input->post();
			
			$this->load->library('form_validation');
			
			if($post_data['username'] != $post_data['old_username']){
				$is_unique =  '|is_unique['.TABLE_USER.'.username]';
			}else{
				$is_unique =  '';
			}			
			$this->form_validation->set_rules('username', 'Username', 'trim|required'.$is_unique);
			
			$this->form_validation->set_rules('reset_password', 'Repeat Password', 'trim|callback_hasSamePassword');
			
			if($post_data['email'] != $post_data['old_email']){	
				$is_unique =  '|is_unique['.TABLE_USER.'.email]';	
			}else{
				$is_unique =  '';
			}
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email'.$is_unique);			
			
			$this->session->unset_userdata($post_data);
			if($this->form_validation->run() == FALSE)
			{	
				$this->session->set_userdata($post_data);
				
				$this->session->set_userdata('has_error', true);
				$this->session->set_userdata('useredit_notification', validation_errors());
				
				redirect($this->agent->referrer());
			}else{
				if($post_data['reset_password']){
					$password = $post_data['reset_password'];
				}else{
					$password = $post_data['password'];
				}
				$cond['user_id'] = $post_data['user_id'];
				$data = array(
					"username" => $post_data['username'],
					"password" => base64_encode(hash("sha256", $password, True)),
					"original_password" => $password,
					"email" => $post_data['email'],
					"user_discount" => $post_data['user_discount'],
					"is_active" => $post_data['is_active'],
					"date_added" => time()
				);
				$this->userdata->update_user_details($cond, $data);
				
				redirect(base_url('user-list'));
			}
		}
		
		public function user_delete($username){			
			$cond['username'] = $username;
			
			if($this->userdata->delete_user($cond)){
				redirect($this->agent->referrer());		
			}
		}
		
		public function logout()
		{
			$this->defaultdata->unsetLoginSession();
			redirect(base_url());
		}
	}
?>
<?php
class Defaultdata extends CI_Model {

	private $data = array();
	private $mydata = array();
	private $headerdata = array();
	
	function __construct()
	{
		parent::__construct();
	}
	public function getFrontendDefaultData()
	{
		$this->mydata["tot_segments"] = $this->getUrlSegments();
		$this->mydata['general_settings'] = $this->grabSettingData();
		$this->mydata["sidebar"] = $this->load->view('partials/sidebar', null, true);
		$this->data = $this->mydata;
		$this->headerdata = $this->mydata;
		$this->data["header"] = $this->load->view('partials/header', $this->headerdata, true);		
		$this->data["head"] = $this->load->view('partials/head', null, true);
		$this->data["footer"] = $this->load->view('partials/footer', null, true);
		
		return $this->data;
	}
	public function is_session_active()
	{
		$sess_id = $this->session->userdata('usrid');
		if (isset($sess_id)==true && $sess_id!="")
			return 1;
		else
			return 0;
	}
	public function CheckFilename($page_filename)
	{
		$page_filename=str_replace(" ","-",$page_filename); //blank space is converted into blank
		$special_char=array("/",".htm",".","!","@","#","$","^","&","*","(",")","=","+","|","\\","{","}",":",";","'","<",">",",",".","?","\"","%");
		$page_filename=str_replace($special_char,"",$page_filename); // dot is converted into blank
		return strtolower($page_filename);      
	}
	public function getUrlSegments()
	{
		$all_segment=$this->uri->segment_array();
		if(sizeof($all_segment)==0)
		{
			$all_segment[1]=$this->router->class;
		}
		if(sizeof($all_segment)==1)
		{
			$all_segment[2]=$this->router->method;
		}
		return $all_segment;
	}
	
	public function returnPartString($string,$length)
	{
		$string = strip_tags($string);
		$s_length=strlen($string);
		if($s_length > $length)
		{
			if(strpos($string," ",$length) !== false)
			{
				$string=substr($string,0,strpos($string," ",$length));
			}
			else
			{
				$string=substr($string,0,$length);
			}
		} 
		else
		{
			$string=$string;
		}
		return stripslashes($string);
	}
	public function grabSettingData(){
		$query = $this->db->get(TABLE_SETTINGS);
		
		return $query->row();
	}
	public function secureInput($data)
	{
		$return_data = array();
		foreach($data as $field => $inp_data)
		{
			$return_data[$field] = $this->security->xss_clean(trim($inp_data));
		}
		return $return_data;
	}
	public function setLoginSession($user_data = array())
	{
		if(count($user_data) > 0)
		{
			$this->session->set_userdata('usrid', $user_data->user_id);
			$this->session->set_userdata('usrname', $user_data->username);
			$this->session->set_userdata('usremail', $user_data->email);
		}
	}
	public function unsetLoginSession()
	{
		$this->session->unset_userdata('usrid');
		$this->session->unset_userdata('usrname');
		$this->session->unset_userdata('usremail');	
	}	
	public function getGeneratedPassword( $length = 6 ) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
		$password = substr( str_shuffle( $chars ), 0, $length );	
		return $password;
	}
	public function generatedRandString( $length = 6 ) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$strgen = substr( str_shuffle( $chars ), 0, $length );	
		return $strgen;
	}
	public function slugify($text)
	{
		// replace non letter or digits by -
		$text = preg_replace('~[^\pL\d]+~u', '-', $text);

		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);

		// trim
		$text = trim($text, '-');

		// remove duplicate -
		$text = preg_replace('~-+~', '-', $text);

		// lowercase
		$text = strtolower($text);

		if (empty($text)) {
			return 'n-a';
		}

		return $text;
	}
}
?>
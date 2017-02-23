<?php
class Userdata extends CI_Model {
	
	public function grab_user_details($cond = array(), $limit = array(), $like = array()){
		
		if(!empty($limit)){
			$this->db->limit($limit[0], $limit[1]);
		}
		
		$this->db->order_by('date_added','desc');
		$this->db->where($cond);
		
		if(!empty($like)){
			$this->db->like($like);
		}
		$query = $this->db->get(TABLE_USER);
		
		return $query->result();
	}
	
	public function insert_user($data = array()){

		$this->db->insert(TABLE_USER, $data); 
		
		return true;
	}
	
	public function update_user_details($cond = array(), $data = array()){

		$this->db->where($cond);
		$this->db->update(TABLE_USER, $data); 
		
		return true;
	}
	
	public function update_settings_details($cond = array(), $data = array()){

		$this->db->where($cond);
		$this->db->update(TABLE_SETTINGS, $data); 
		
		return true;
	}
	
	public function delete_user($cond = array()){
		$this->db->where($cond);
		
		$this->db->delete(TABLE_USER);
		
		return true;
	}
}
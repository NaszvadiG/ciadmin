<?php
class Categorydata extends CI_Model {
	
	public function grab_category($cond = array(), $like = array(), $limit = array()){
		if(!empty($cond)){
			$this->db->where($cond);
		}		
		if(!empty($like)){
			$this->db->like($like);
		}
		if(!empty($limit)){
			$this->db->limit($limit[0], $limit[1]);
		}
		$this->db->order_by('date_added','desc');
		
		$query = $this->db->get(TABLE_CATEGORY);
		echo $this->db->last_query();
		
		return $query->result();
	}
	
	public function insert_category($data = array()){

		$this->db->insert(TABLE_CATEGORY, $data); 
		
		return true;
	}
	
	public function update_category($cond = array(), $data = array()){

		$this->db->where($cond);
		$this->db->update(TABLE_CATEGORY, $data); 
		
		return true;
	}
	
	public function delete_category($cond = array()){
		$this->db->where($cond);
		
		$this->db->delete(TABLE_CATEGORY);
		
		return true;
	}
}
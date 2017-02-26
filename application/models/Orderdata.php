<?php
class Orderdata extends CI_Model {
	
	public function grab_order($cond = array(), $limit = array(), $like = array()){
		
		if(!empty($limit)){
			$per_page = $limit[0];
			$offset = $limit[1];
			$start = max(0, ( $offset -1 ) * $per_page);
		}
		
		$sql = "SELECT mailing_dates.mailing_date_id, mailing_dates.item, mailing_dates.quantity, mailing_dates.proof_pdf, mailing_dates.proofapproved_date, mailing_dates.proofsent_date, mailing_dates.total, mailing_dates.date, mailing_dates.status, orders.orderid, orders.email, orders.first_name, orders.last_name, orders.date_added FROM mailing_dates LEFT JOIN orders ON mailing_dates.order_id = orders.order_id WHERE orders.status='".$cond['status']."' ORDER BY orders.date_added DESC LIMIT ".$start.", ".$per_page;
		
		$query = $this->db->query($sql);
		
		return $query->result();
	}
	
	public function grab_total_order($cond = array(), $like = array()){
		
		$sql = "SELECT mailing_dates.mailing_date_id, mailing_dates.item, mailing_dates.quantity, mailing_dates.proof_pdf, mailing_dates.proofapproved_date, mailing_dates.proofsent_date, mailing_dates.total, mailing_dates.date, mailing_dates.status, orders.orderid, orders.email, orders.first_name, orders.last_name, orders.date_added FROM mailing_dates LEFT JOIN orders ON mailing_dates.order_id = orders.order_id WHERE orders.status='".$cond['status']."' ORDER BY orders.date_added DESC";
		
		$query = $this->db->query($sql);
		
		return $query->result();
	}
	
	public function insert_category($data = array()){

		$this->db->insert(TABLE_ORDER, $data); 
		
		return true;
	}
	
	public function update_category($cond = array(), $data = array()){

		$this->db->where($cond);
		$this->db->update(TABLE_ORDER, $data); 
		
		return true;
	}
	
	public function delete_category($cond = array()){
		$this->db->where($cond);
		
		$this->db->delete(TABLE_ORDER);
		
		return true;
	}
}
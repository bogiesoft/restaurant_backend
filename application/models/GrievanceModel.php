<?php
class GrievanceModel extends CI_Model{
	private $table_name ="tbl_dtl_customer_grievance A, tbl_mast_customer B, tbl_mast_order C";

	public function __construct()
	{
		$this->load->database('krazytable');
	}

	public function get_data($data)
	{
		$this->db->select('A.customer_id_fk, B.first_name, B.middle_name, B.surname, B.profile_image_url, B.mobile_number_uk, B.email_id, A.order_id_fk, C.table_no_fk, A.complaint_date, A.concern, A.grievance_id');
		$this->db->from('tbl_dtl_customer_grievance A');
		$this->db->join('tbl_mast_customer B', 'A.customer_id_fk = B.customer_id_pk', 'inner');
		$this->db->join('tbl_mast_order C', 'A.order_id_fk = C.order_id_pk', 'inner');
		$this->db->where('C.restaurant_id_fk', $data['restaurant_id']);
		$this->db->order_by('A.complaint_date desc');

		$query = $this->db->get();

		return $query->result_array();
	}

	public function add_data($array_object)
	{
		$this->db->trans_start();
		$this->db->insert($this->table_name, $array_object);
		$id = $this->db->insert_id();
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
		{
			log_message("error", "Error in file" . __FILE__ . "at line" . __LINE__ .  print_r($array_object, true) . " due to " . print_r($this->db->error(), true));
			throw new Exception("Error in file" . __FILE__ . "at line" . __LINE__, EXIT_DATABASE);
		}
		return $id;
	}

	public function replace_data($array_object, $primary_key)
	{
		$this->db->trans_start();
		$this->db->where($primary_key);
		$this->db->update($this->table_name, $array_object);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
		{
			log_message("error", "Error in file" . __FILE__ . "at line" . __LINE__   . print_r($array_object, true) . " due to ". print_r($this->db->error(), true));
			throw new Exception("Error in file" . __FILE__ . "at line" . __LINE__, EXIT_DATABASE);
		}
	}
}

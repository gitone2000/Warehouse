<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelCustomer extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	
	function dataCustomer($limit,$start,$search=''){
		$this->db->select(array("ap_customer.id_customer","ap_customer.nama","ap_customer.kontak","ap_customer.tanggal_lahir","ap_customer.diskon","ap_customer.tanggal_gabung","ap_customer.point","ap_customer.alamat","ap_customer.id_provinsi","ap_customer.id_kabupaten","ap_customer.id_kecamatan","ap_customer.kategori"));
		$this->db->from("ap_customer");

		if(!empty($search)){
			$this->db->like("ap_customer.nama",$search);
			$this->db->or_like("ap_customer.kontak",$search);
		}

		$this->db->limit($limit,$start);
		$this->db->order_by("ap_customer.nama","DESC");
		return $this->db->get();		
	}

	function hapus_customer($id){
		$this->db->delete("ap_customer",array("id_customer" => $id));
	}

	function editCustomer($idCustomer,$data_customer){
		$this->db->where("id_customer",$idCustomer);
		$this->db->update("ap_customer",$data_customer);
		$affect = $this->db->affected_rows();
		return $affect;
	}

	function addCustomer($data_customer){
		$this->db->insert("ap_customer",$data_customer);
		$affect = $this->db->affected_rows();
		return $affect;
	}

	function hapusGroup($id){
		$this->db->delete("ap_customer_group",array("id_group" => $id));
	}

	function addCustomerGroup($data_grup){
		$this->db->insert("ap_customer_group",$data_grup);
	}

	function updateGroup($id,$dataUpdate){
		$this->db->where("id_group",$id);
		$this->db->update("ap_customer_group",$dataUpdate);
	}
	
	function totalCustomer(){
		$this->db->from("ap_customer");
		return $this->db->count_all_results();
	}

}
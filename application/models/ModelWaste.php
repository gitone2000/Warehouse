<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelWaste extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function cekCartWaste($idProduk,$idUser){
		$this->db->from("cc_cartwaste");
		$this->db->where("idProduk",$idProduk);
		$this->db->where("idUser",$idUser);
		return $this->db->count_all_results();
	}

	function getIdCart($idProduk,$idUser){
		$this->db->select("id");
		$this->db->from("cc_cartwaste");
		$this->db->where("idUser",$idUser);
		$this->db->where("idProduk",$idProduk);
		$query = $this->db->get()->row();
		return $query->id;
	}

	function viewCartWaste($idUser){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","ap_produk.satuan","cc_cartwaste.qty","cc_cartwaste.id","ap_produk.hpp"));
		$this->db->from("cc_cartwaste");
		$this->db->join("ap_produk","ap_produk.id_produk = cc_cartwaste.idProduk");
		$this->db->where("cc_cartwaste.idUser",$idUser);
		$this->db->order_by("cc_cartwaste.id","DESC");
		return $this->db->get()->result();
	}

	function currentStokWarehouse($sku){
		$this->db->select("stok");
		$this->db->from("ap_produk");
		$this->db->where("id_produk",$sku);
		$query = $this->db->get()->row();
		return $query->stok;
	}

	function cek_tanggal_waste($tanggal_waste){
		$this->db->from("waste");
		$this->db->where("tanggal_waste",$tanggal_waste);
		return $this->db->count_all_results();
	}

	function insertWaste($data_waste){
		$this->db->insert("waste",$data_waste);
	}

	function insertWasteItemBatch($data_item){
		$this->db->insert_batch("waste_item",$data_item);
	}

	function updateStokBatch($data_update){
		$this->db->update_batch("ap_produk",$data_update,"id_produk");
	}

	function hapusCartWaste($idUser){
		$this->db->delete("cc_cartwaste",array("idUser" => $idUser));
	}

	function insertCartWaste($dataArray){
		$this->db->insert("cc_cartwaste",$dataArray);
	}

	function updateQtyCartWaste($idProduk,$idUser,$dataUpdate){
		$this->db->where("idProduk",$idProduk);
		$this->db->where("idUser",$idUser);
		$this->db->update("cc_cartwaste",$dataUpdate);
	}

	function hapusCartId($id){
		$this->db->delete("cc_cartwaste",array("id" => $id));
	}
}
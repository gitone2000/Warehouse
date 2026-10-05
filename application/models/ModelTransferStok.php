<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelTransferStok extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function viewCart($idUser,$idStore){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","cc_cartransfer.qty","cc_cartransfer.id"));
		$this->db->from("cc_cartransfer");
		$this->db->join("ap_produk","ap_produk.id_produk = cc_cartransfer.idProduk");
		$this->db->where("cc_cartransfer.idUser",$idUser);
		$this->db->where("cc_cartransfer.idStore",$idStore);
		$this->db->order_by("cc_cartransfer.id","DESC");
		return $this->db->get()->result();
	}

	function stokToko($idProduk,$idStore){
		$this->db->select("stok");
		$this->db->from("stok_store");
		$this->db->where("id_produk",$idProduk);
		$this->db->where("id_store",$idStore);
		$query = $this->db->get()->row();

		return $query->stok;
	}

	function stokCart($idProduk,$idUser,$idStore){
		$this->db->select("qty");
		$this->db->from("cc_cartransfer");
		$this->db->where("idProduk",$idProduk);
		$this->db->where("idUser",$idUser);
		$this->db->where("idStore",$idStore);
		$query = $this->db->get()->row();
		return $query->qty;
	}

	function cekCartExist($idProduk,$idUser,$idStore){
		$this->db->from("cc_cartransfer");
		$this->db->where("idProduk",$idProduk);
		$this->db->where("idUser",$idUser);
		$this->db->where("idStore",$idStore);
		return $this->db->count_all_results();
	}

	function noUrutTransfer($idUser,$today){
		$this->db->from("transferstoknumber");
		$this->db->where("DATE(tanggal)",$today);
		$this->db->where("idUser",$idUser);
		return $this->db->count_all_results();
	}

	function itemTransfer($idUser,$idToko){
		$this->db->select(array("cc_cartransfer.idProduk","cc_cartransfer.qty"));
		$this->db->from("cc_cartransfer");
		$this->db->where("idUser",$idUser);
		$this->db->where("idStore",$idToko);
		return $this->db->get()->result();	
	}

	function stokTokoAsal($idProduk,$idStore){
		$this->db->select("stok");
		$this->db->from("stok_store");
		$this->db->where("id_produk",$idProduk);
		$this->db->where("id_store",$idStore);
		$query = $this->db->get()->row();
		return $query->stok;
	}

	function stokTokoTujuan($idProduk,$tokoTujuan){
		$this->db->select("stok");
		$this->db->from("stok_store");
		$this->db->where("id_produk",$idProduk);
		$this->db->where("id_store",$tokoTujuan);
		$query = $this->db->get()->row();
		return $query->stok;
	}

	function cekProdukToko($idProduk,$idToko){
		$this->db->from("stok_store");
		$this->db->where("id_produk",$idProduk);
		$this->db->where("id_store",$idToko);
		return $this->db->count_all_results();
	}

	function infoTransfer($noTransfer){
		$this->db->select(array("transferstoknumber.noTransfer","transferstoknumber.tanggal","transferstoknumber.transferFrom","transferstoknumber.transferTo","transferstoknumber.keterangan"));
		$this->db->from("transferstoknumber");
		$this->db->where("noTransfer",$noTransfer);
		return $this->db->get()->row();
	}

	function itemTransferView($noTransfer){
		$this->db->select(array("ap_produk.id_produk","ap_produk.nama_produk","transferstokitem.qty"));
		$this->db->from('transferstokitem');
		$this->db->join("ap_produk","ap_produk.id_produk = transferstokitem.idProduk");
		$this->db->where("noTransfer",$noTransfer);
		return $this->db->get()->result();
	}

	function getIdCart($idProduk,$idUser){
		$this->db->select("id");
		$this->db->from("cc_cartransfer");
		$this->db->where("idUser",$idUser);
		$this->db->where("idProduk",$idProduk);
		$query = $this->db->get()->row();
		return $query->id;
	}

	function insertCartTransfer($dataInsert){
		$this->db->insert("cc_cartransfer",$dataInsert);
	}

	function updateCart($idProduk,$idUser,$idStore,$dataUpdate){
		$this->db->where("idProduk",$idProduk);
		$this->db->where("idUser",$idUser);
		$this->db->where("idStore",$idStore);
		$this->db->update("cc_cartransfer",$dataUpdate);
	}

	function hapusCart($idProduk,$idUser,$idStore){
		$this->db->delete("cc_cartransfer",array("idProduk" => $idProduk, "idUser" => $idUser, "idStore" => $idStore));
	}

	function insertTransferStokNumber($dataTransfer){
		$this->db->insert("transferstoknumber",$dataTransfer);
	}

	function hapusCartTransfer($idUser){
		$this->db->delete("cc_cartransfer",array("idUser" => $idUser));
	}

	function inputItemTransfer($dataInsert){
		$this->db->insert("transferstokitem",$dataInsert);
	}

	function updateStokAsalToko($idProduk,$tokoAsal,$updateStokAsal){
		$this->db->where('id_produk',$idProduk);
		$this->db->where("id_store",$tokoAsal);
		$this->db->update("stok_store",$updateStokAsal);
	}

	function updateStokTokoTujuan($idProduk,$idToko,$dataUpdate){
		$this->db->where("id_produk",$idProduk);
		$this->db->where("id_store",$idToko);
		$this->db->update("stok_store",$dataUpdate);
	}

	function insertStokTokoTujuan($dataInsert){
		$this->db->insert("stok_store",$dataInsert);
	}
}
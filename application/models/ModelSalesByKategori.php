<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class ModelSalesByKategori extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function salesPerkategori($start,$end,$id_kategori){
		$this->db->select("SUM(ap_invoice_item.harga_jual*ap_invoice_item.qty) as totalPenjualan");
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left outer");
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		$this->db->where("ap_produk.id_kategori",$id_kategori);
		$this->db->group_by("ap_kategori.id_kategori");
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->totalPenjualan;
		}
	}

	function salesPerkategori2($start,$end,$id_kategori,$id){
		$this->db->select("SUM(ap_invoice_item.harga_jual*ap_invoice_item.qty) as totalPenjualan");
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left outer");
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		$this->db->where("ap_produk.id_kategori",$id_kategori);
		$this->db->where("ap_produk.id_subkategori",$id);
		$this->db->group_by("ap_kategori.id_kategori");
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->totalPenjualan;
		}
	}

	function salesPerkategori3($start,$end,$id_kategori,$id,$id2){
		$this->db->select("SUM(ap_invoice_item.harga_jual*ap_invoice_item.qty) as totalPenjualan");
		$this->db->from("ap_invoice_item");
		$this->db->join("ap_produk","ap_produk.id_produk = ap_invoice_item.id_produk","left");
		$this->db->join("ap_kategori","ap_kategori.id_kategori = ap_produk.id_kategori","left outer");
		$this->db->where("ap_invoice_item.tanggal BETWEEN '$start' AND '$end'");
		$this->db->where("ap_produk.id_kategori",$id_kategori);
		$this->db->where("ap_produk.id_subkategori",$id);
		$this->db->where("ap_produk.id_subkategori_2",$id2);
		$this->db->group_by("ap_kategori.id_kategori");
		$query = $this->db->get()->result();

		foreach($query as $row){
			return $row->totalPenjualan;
		}
	}

}
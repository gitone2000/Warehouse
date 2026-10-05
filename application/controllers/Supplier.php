<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Supplier extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model("model1");
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],2,3);
	}

	function index(){
		$this->global['pageTitle'] = "POS - Supplier";
		$this->loadViews("supplier/body_supplier",$this->global,NULL,"supplier/footerSupplier");
	}

	function data_supplier(){
		$data['supplier'] = $this->db->get("supplier");
		$this->load->view("supplier/data_supplier",$data);
	}

	function add_supplier_sql(){
		$nama_supplier 		= $_POST['nama_supplier'];
		$kontak_supplier	= $_POST['kontak_supplier'];
		$email_supplier 	= $_POST['email_supplier'];
		$alamat_supplier 	= $_POST['alamat_supplier'];
		$no_rekening 		= $_POST['no_rekening'];
		$bank 				= $_POST['bank'];
		$atas_nama 			= $_POST['atas_nama'];

		$data_insert = array(
								"supplier"		=> $nama_supplier,
								"alamat"		=> $alamat_supplier,
								"kontak"		=> $kontak_supplier,
								"email"			=> $email_supplier,
								"no_rekening" 	=> $no_rekening,
								"bank"			=> $bank,
								"atas_nama"		=> $atas_nama
							);

		$this->db->insert("supplier",$data_insert);
		$affect = $this->db->affected_rows();
		echo $affect;
	}

	function form_edit_supplier(){
		$id = $_POST['id'];
		$data['supplier'] = $this->db->get_where("supplier",array("id_supplier" => $id));
		$this->load->view("supplier/form_edit_supplier",$data);
	}

	function edit_supplier_sql(){
		$nama 			= $_POST['nama'];
		$kontak 		= $_POST['kontak'];
		$alamat 		= $_POST['alamat'];
		$id 			= $_POST['id'];
		$no_rekening 	= $_POST['no_rekening'];
		$bank 			= $_POST['bank'];
		$atas_nama 		= $_POST['atas_nama'];
		$email 			= $_POST['email'];

		$data_update = array(
								"supplier"		=> $nama,
								"alamat"		=> $alamat,
								"kontak"		=> $kontak,
								"no_rekening" 	=> $no_rekening,
								"bank"			=> $bank,
								"atas_nama"		=> $atas_nama,
								"email"			=> $email
							);

		$this->db->where("id_supplier",$id);
		$this->db->update("supplier",$data_update);
		$affect = $this->db->affected_rows();
		echo $affect;
	}

	function hapus_supplier(){
		$id = $_POST['id'];
		$this->db->delete("supplier",array("id_supplier" => $id));
	}
}
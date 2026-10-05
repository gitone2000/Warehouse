<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Customer extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model(array("model1","modelCustomer"));
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],2,40);
	}

	function index(){
		$this->global['pageTitle'] = "POS - Data Customer";
		$this->loadViews("customer/body_customer",$this->global,NULL,"customer/footerCustomer");
	}

	function datatablesCustomer(){
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$total 			 			= $this->modelCustomer->totalCustomer();
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelCustomer->dataCustomer($length,$start,$search);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelCustomer->dataCustomer($length,$start,$search);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {

			$output['data'][]=array($nomor_urut,$dt['nama'],$dt['kontak'],$dt['tanggal_lahir'],$dt['tanggal_gabung'],$dt['alamat'],$dt['kategori'],$dt['diskon'],$dt['point'],"<a href='".base_url('customer/edit_customer?id='.$dt['id_customer'])."'><i class='fa fa-pencil'></i></a> | <a onclick='return confirm('Yakin Hapus Data?')' href='".base_url('customer/hapus_customer?id='.$dt['id_customer'])."'><i class='fa fa-trash'></i></a>");
		$nomor_urut++;
		}

		echo json_encode($output);
	}


	function add_customer(){
		$data['group_customer'] = $this->db->get("ap_customer_group");
		$data['provinsi'] = $this->db->get("ae_provinsi");
		$this->global['pageTitle'] = "POS - Tambah Customer";
		$this->loadViews("customer/body_add_customer",$this->global,$data,"footer");
	}

	function hapus_customer(){
		$id = $this->input->get("id");

		$this->modelCustomer->hapus_customer($id);

		redirect("customer");
	}

	function edit_customer(){
		$data['group_customer'] = $this->db->get("ap_customer_group");
		$data['provinsi'] = $this->db->get("ae_provinsi");

		$id_customer 	= $this->input->get('id');
		$data['customer'] = $this->db->get_where("ap_customer",array("id_customer" => $id_customer));
		$this->global['pageTitle'] = "POS - Edit Customer";
		$this->loadViews("customer/body_edit_customer",$this->global,$data,"footer");
	}

	function edit_customer_sql(){
		$nama 	 	= $_POST['nama'];
		$kontak 	= $_POST['kontak'];
		$alamat 	= $_POST['alamat'];
		$provinsi 	= $_POST['provinsi'];
		$kabupaten 	= $_POST['kabupaten'];
		$kecamatan 	= $_POST['kecamatan'];
		$group 		= $_POST['group'];
		$diskon 	= $_POST['diskon'];
		$idCustomer = $_POST['id_customer'];

	
		$data_customer = array(
								"nama"				=> $nama,
								"kontak"			=> $kontak,
								"tanggal_gabung"	=> date('Y-m-d'),
								"alamat"			=> $alamat,
								"id_provinsi"		=> $provinsi,
								"id_kabupaten"		=> $kabupaten,
								"id_kecamatan"		=> $kecamatan,
								"kategori"			=> $group,
								"diskon"			=> $diskon
							  );
		
		
		$affect = $this->modelCustomer->editCustomer($idCustomer,$data_customer);

		if($affect > 0){
			$this->session->set_flashdata('message', 'Data Berhasil Diubah !');
		} else {
			$this->session->set_flashdata('message2', 'Data Gagal Diubah !');
		}

		redirect("customer");	
	}

	function add_customer_sql(){
		$nama 	 		= $_POST['nama'];
		$kontak 		= $_POST['kontak'];
		$alamat 		= $_POST['alamat'];
		$provinsi 		= $_POST['provinsi'];
		$kabupaten 		= $_POST['kabupaten'];
		$kecamatan 		= $_POST['kecamatan'];
		$group 			= $_POST['group'];
		$diskon 		= $_POST['diskon'];
		$tanggal_lahir	= $_POST['tanggal_lahir'];

		$data_customer = array(
								"nama"				=> $nama,
								"kontak"			=> $kontak,
								"tanggal_lahir"		=> $tanggal_lahir,
								"tanggal_gabung"	=> date('Y-m-d'),
								"alamat"			=> $alamat,
								"id_provinsi"		=> $provinsi,
								"id_kabupaten"		=> $kabupaten,
								"id_kecamatan"		=> $kecamatan,
								"kategori"			=> $group,
								"diskon"			=> $diskon
							  );
		

		$affect = $this->modelCustomer->addCustomer($data_customer);

		if($affect > 0){
			$this->session->set_flashdata('message', 'Data Berhasil Ditambahkan !');
		} else {
			$this->session->set_flashdata('message', 'Data Gagal Ditambahkan !');
		}

		redirect("customer");
	}

	function list_kabupaten(){
		$id = $_POST['id'];

		$kabupaten = $this->db->get_where("ae_kabupaten", array("id_provinsi" => $id));

		foreach($kabupaten->result() as $dt){
			echo "<option value='".$dt->kabupaten_id."'>".$dt->nama_kabupaten."</option>";
		}
	}

	function list_kecamatan(){
		$id  = $_POST['id'];

		$kecamatan = $this->db->get_where("ae_kecamatan",array("kabupaten_id" => $id));

		foreach($kecamatan->result() as $dt){
			echo "<option value='".$dt->id_kecamatan."'>".$dt->kecamatan."</option>";
		}
	}
}
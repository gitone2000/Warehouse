<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_request extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model("model1");
		$this->load->database();

		//cek login
		$username = $this->session->userdata("username");
		$password = $this->session->userdata("password");
		$id_user  = $this->session->userdata("id_user");

		$cek_auth = $this->model1->cek_auth($username,$password);

		if($cek_auth > 0){
			//cek hak navigasi
			$access = 17;
			$cek_status = $this->model1->cek_status_navigasi($id_user,$access);
			
			if($cek_status=='0'){
				redirect("access_denied");
			} else {
				//do nothing
			}

		} else {
			redirect("login");
		}
	}

	function index(){
		$this->load->view("navigation");
		$this->load->view("body_purchase_request");
		$this->load->view("footer");
	}

	function select2_dropdown_goods(){
		$sku_barang 	= $_POST['sku_barang'];
		$data_barang 	= $this->model1->get_bahan_baku_filter($sku_barang);

		foreach($data_barang->result() as $row){
			echo "<option value='".$row->sku."'>".$row->nama_bahan."</option>";
		}
	}

	function satuan_barang(){
		$sku 	= $_POST['sku'];
		$satuan = $this->model1->satuan_barang($sku);

		echo "<input type='text' value='".$satuan."' disabled class='form-control'>";
	}

	function harga_terakhir_beli(){
		$sku = $_POST['sku'];

		$harga_terakhir = $this->model1->harga_terakhir_beli($sku);

		echo "<input type='text' value='".$harga_terakhir."' disabled class='form-control'>";

	}

	function data_form(){
		$data['no'] = $_GET['no'];
		$this->load->view("data_form_pr",$data);
	}

	function insert_data_request(){
		$sku 			= $_POST['sku_barang'];
		$qty 			= $_POST['jumlah_beli'];
		$status 		= 0;
		$cek_no_request = $this->model1->cek_no_request();
		$no_request 	= "RQ-".date('y').date('m').sprintf('%04d',$cek_no_request+1);

		$request_number = array(
									"purchase_no"		=> $no_request,
									"id_pic"			=> $this->session->userdata("id_user"),
									"tanggal_request" 	=> date('Y-m-d H:i:s'),
									"sku"				=> $sku,
									"status"			=> $status,
									"qty"			=> $qty
							   );

		$this->db->insert("rq_purchase_no",$request_number);

		$count = count($_POST['harga_request']);

		for($i=0;$i<$count;$i++){
			$harga 	 	= $_POST['harga_request'][$i];
			$supplier 	= $_POST['supplier'][$i];
			$remark		= $_POST['remark'][$i];

			$data_item = array(
									"purchase_no"	=> $no_request,
									"sku"			=> $sku,
									"id_supplier"	=> $supplier,
									"harga"			=> $harga,
									"remark"		=> $remark
							  );

			$this->db->insert("rq_purchase_item",$data_item);
		}

		redirect("Purchase_request/daftar_request");
	}

	function daftar_request(){
		$this->load->view("navigation");
		$this->load->view("body_daftar_request");
		$this->load->view("footer");
	}

	function wait_approve_list(){
		$data['wait_approve'] = $this->model1->wait_approve_list();
		$this->load->view("body_wait_approve_list",$data);
	}

	function spinner(){
		echo "<img src='".base_url('assets/loading.gif')."'/>";
	}


	function daftar_request_approved(){
		$data['approved_request'] = $this->model1->daftar_request_approved();
		$this->load->view("daftar_request_approved",$data);
	}

	function approved_item(){
		$no_request 	= $_POST['id_request'];

		$data['no_request']   = $no_request;
		$data['data_request'] = $this->model1->data_request($no_request);
		$data['item_request'] = $this->model1->item_request($no_request); 
		$this->load->view("approved_item",$data);
	}

	function supplierAjax(){
		$q 	= $_GET['term'];

		$supplier = $this->model1->supplierAjax($q);

		$data_array = array();

		foreach($supplier->result() as $row){
			$data_array[] = array(
									"id" 	=> $row->id_supplier,
									"text"	=> $row->supplier
								 );
		}

		echo json_encode($data_array);
	}

	function daftar_request_ditolak(){
		$this->load->model("modelPurchaseApproval");
		$data['approved_request'] = $this->modelPurchaseApproval->daftar_request_ditolak();
		$this->load->view("purchaseApproval/daftar_request_ditolak",$data);
	}

	


}
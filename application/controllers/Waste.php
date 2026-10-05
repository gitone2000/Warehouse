<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Waste extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model(array("model1","modelWaste"));
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],2,12);
	}

	function index(){
		$data['keterangan_waste'] = $this->db->get("keterangan_waste");
		$this->global['pageTitle'] = "POS - Waste";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("waste/body_waste",$this->global,$data,"waste/footer_waste");
	}

	function ajax_produk(){
		$q 	= $_GET['term'];

		
		$customer = $this->model1->produk_search_all($q);

		$data_array = array();

		foreach($customer->result() as $row){
			$data_array[] = array(
									"id" 	=> $row->id_produk,
									"text"	=> $row->id_produk." / ".$row->nama_produk
								 );
		}

		echo json_encode($data_array);
	}

	function insertWaste(){
		$id_user 			= sprintf("%03d",$this->global['idUser']);
		$tanggal_waste 		= date('Y-m-d');
		$id_keterangan	 	= $_POST['idWaste'];
		$keterangan 		= $_POST['keterangan'];

		$cek_tanggal 		= $this->modelWaste->cek_tanggal_waste($tanggal_waste);

		$create_date 	= date_create($tanggal_waste);
		$convert_date   = date_format($create_date,'y').date_format($create_date,'m').date_format($create_date,'d');

		$no_inv = "WS-".$convert_date.$id_user.sprintf("%03d",$cek_tanggal+1);


		$data_waste = array(
								"no_waste" 			=> $no_inv,
								"tanggal_waste"  	=> $tanggal_waste,
								"id_pic"			=> $this->global['idUser'],
								"id_keterangan" 	=> $id_keterangan,
								"keterangan" 		=> $keterangan
							);

		$this->modelWaste->insertWaste($data_waste);
		$itemWaste = $this->modelWaste->viewCartWaste($this->global['idUser']);

		foreach($itemWaste as $row){
			$sku 			= $row->id_produk;
			$jumlah_waste 	= $row->qty;
			$hpp 			= $row->hpp;

			$data_item[] = array(
								"no_waste"	=> $no_inv,
								"sku"		=> $sku,
								"qty"		=> $jumlah_waste,
								"harga"		=> $hpp,
								"tanggal"	=> $tanggal_waste,
								"id_keterangan" => $id_keterangan
							  );

			$stok_lama = $this->model1->cek_stok_lama($sku);

			$data_update[] = array(
									"id_produk" => $sku,
									"stok"	=> $stok_lama-$jumlah_waste
								);			
		}

		$this->modelWaste->updateStokBatch($data_update);
		$this->modelWaste->insertWasteItemBatch($data_item);
		$this->modelWaste->hapusCartWaste($this->global['idUser']);
		echo $no_inv;

	}

	function invoice_waste(){
		$data['header'] = $this->db->get("ap_receipt");

		$no_waste = $_GET['no_waste'];

		$data['info_waste'] = $this->model1->info_waste($no_waste);
		$data['item_waste'] = $this->model1->item_waste($no_waste);;

		$this->global['pageTitle'] = "POS - Invoice Waste";
		$this->loadViews("waste/body_invoice_waste",$this->global,$data,"footer_empty");
	}

	function insertCartWaste(){
		$sku 		= $_POST['sku'];
		$idUser 	= $this->global['idUser'];

		//cek if data exist or not

		$cekCart = $this->modelWaste->cekCartWaste($sku,$idUser);

		if($cekCart < 1){
			$dataArray = array(
									"idProduk"		=> $sku,
									"qty"			=> 1,
									"idUser"		=> $idUser
							  );

			$this->modelWaste->insertCartWaste($dataArray);
			echo 0;
		} else {
			$id = $this->modelWaste->getIdCart($sku,$idUser);
			echo $id;
		}
	}

	function getDataProdukWarehouse(){
		$this->load->model("model_penjualan");
		$sku 		= $_POST['sku'];
		$dataProduk = $this->model_penjualan->oldStokWarehouse($sku);

		echo $dataProduk;
	}

	function viewCartWaste(){
		$idUser = $this->global['idUser'];
		$data['viewCart'] = $this->modelWaste->viewCartWaste($idUser);
		$this->load->view("waste/viewCartWaste",$data);	
	}

	function updateQtyCart(){
		$qty = $_POST['qty'];
		$idProduk = $_POST['idProduk'];
		$idUser = $this->global['idUser'];

		$cekStokGudang = $this->modelWaste->currentStokWarehouse($idProduk);

		if($qty > $cekStokGudang){
			//melebihi stok
			echo 0;
		} else {
			$dataUpdate = array(
									"qty"	=> $qty
							   );

			$this->modelWaste->updateQtyCartWaste($idProduk,$idUser,$dataUpdate);
			echo 1;
		}
	}

	function qtyOnCart(){
		$id = $_POST['id'];

		$qty = $this->db->get_where("cc_cartwaste",array("id" => $id))->row();
		echo $qty->qty;
	}

	function hapusCart(){
		$id = $_POST['id'];

		$this->modelWaste->hapusCartId($id);
	}
}
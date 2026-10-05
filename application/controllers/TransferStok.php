<?php
defined('BASEPATH') OR exit('No direct script access allowed');	

require APPPATH . '/libraries/BaseController.php';

class TransferStok extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model(array("model1","modelTransferStok","model_penjualan"));
		$this->load->database();
		
		$this->isLoggedIn($this->global['idUser'],2,14);
	}

	function index(){
		$data['store'] = $this->db->get("ap_store")->result();
		$this->global['pageTitle'] = "POS - Transfer Stok Barang";
		$this->loadViews("transfer_stok/bodyTransferStok",$this->global,$data,"transfer_stok/footerTransferStok");
	}

	function formTransfer(){
		$idStore = $this->input->get("idStore");
		$data['namaStore'] = $this->model1->namaStore($idStore);
		$data['store'] = $this->db->get("ap_store")->result();
		$this->global['pageTitle'] = "POS - Form Transfer Barang";
		$this->loadViews("transfer_stok/formTransfer",$this->global,$data,"transfer_stok/footerTransferStok");
	}

	function ajax_produk(){
		$q 	= $_GET['term'];
		$id_store = $_GET['idStore'];
		
		
		$customer = $this->model_penjualan->produk_search($q,$id_store);

		$data_array = array();

		foreach($customer->result() as $row){
			$data_array[] = array(
									"id" 	=> $row->id_produk,
									"text"	=> $row->id_produk." / ".$row->nama_produk,
								 );
		}

		echo json_encode($data_array);
	}

	function insertCart(){
		$idProduk 	= $_POST['idProduk'];
		$idStore 	= $_POST['idStore'];
		$idUser 	= $this->global['idUser'];

		//cek stok toko
		$stokToko = $this->modelTransferStok->stokToko($idProduk,$idStore);

		if($stokToko > 0){
			$cekCart = $this->modelTransferStok->cekCartExist($idProduk,$idUser,$idStore);
			if($cekCart < 1){
				$dataInsert = array(
										"idProduk"		=> $idProduk,
										"qty"			=> 1,
										"idUser" 		=> $idUser,
										"idStore" 		=> $idStore
								   );

				$this->modelTransferStok->insertCartTransfer($dataInsert);
			} else {
				$id = $this->modelTransferStok->getIdCart($idProduk,$idUser);
				echo $id;
			}
		} else {
			echo "NotEnoughStock";
		}
	}

	function viewCart(){
		$idUser = $this->global['idUser'];
		$idStore = $_POST['idStore'];
		$data['viewCart'] = $this->modelTransferStok->viewCart($idUser,$idStore);
		$this->load->view("transfer_stok/viewCart",$data);
	}

	function updateCart(){
		$idProduk 	= $_POST['idProduk'];
		$qty 		= $_POST['qty'];
		$idUser 	= $this->global['idUser'];
		$idStore 	= $_POST['idStore'];

		$stokToko = $this->modelTransferStok->stokToko($idProduk,$idStore);

		if($qty > $stokToko){
			echo 0;
		} else {
			$dataUpdate = array(
									"qty"	=> $qty
							   );

			$this->modelTransferStok->updateCart($idProduk,$idUser,$idStore,$dataUpdate);
			echo 1;
		}
	}

	function hapusCart(){
		$idProduk 	= $_POST['idProduk'];
		$idUser 	= $this->global['idUser'];
		$idStore 	= $_POST['idStore'];

		$this->modelTransferStok->hapusCart($idProduk,$idUser,$idStore);
	}

	function doTransfer(){
		$tokoTujuan 	= $_POST['tokoTujuan'];
		$keterangan 	= $_POST['keterangan'];
		$transferFrom 	= $_POST['transferFrom'];
		$idUser 		= $this->global['idUser'];
		$today 			= date('Y-m-d');

		//cek no urut transfer
		$noUrutTransfer = $this->modelTransferStok->noUrutTransfer($idUser,$today);
		$noTransfer = "TS-".date('y').date('m').date('d').sprintf('%03d',$idUser).sprintf('%04d',$noUrutTransfer+1);

		$dataTransfer = array(
								"noTransfer"		=> $noTransfer,
								"tanggal"			=> date('Y-m-d H:i:s'),
								"idUser"			=> $idUser,
								"transferFrom"		=> $transferFrom,
								"transferTo"		=> $tokoTujuan,
								"keterangan"		=> $keterangan
							 );

		$this->modelTransferStok->insertTransferStokNumber($dataTransfer);

		//ambil item transfer
		$itemTransfer = $this->modelTransferStok->itemTransfer($idUser,$transferFrom);

		foreach($itemTransfer as $row){
			$idProduk = $row->idProduk;
			$qty 	  = $row->qty;

			//input item transfer 
			$this->inputItemTransfer($idProduk,$qty,$noTransfer);

			//kurangi stok lama toko asal transfer
			$stokLamaAsalToko = $this->modelTransferStok->stokTokoAsal($idProduk,$transferFrom);
			$this->updateStokAsalToko($stokLamaAsalToko,$qty,$transferFrom,$idProduk);

			//tambah stok pada tujuan toko baru
			$this->updateStokTokoTujuan($qty,$tokoTujuan,$idProduk);
			
		}
		echo $noTransfer;
		$this->modelTransferStok->hapusCartTransfer($idUser);
	}

	function inputItemTransfer($idProduk,$qty,$noTransfer){
		$dataInsert = array(
								"noTransfer"  => $noTransfer,
								"idProduk"	  => $idProduk,
								"qty"		  => $qty
						   );

		$this->modelTransferStok->inputItemTransfer($dataInsert);
	}

	function updateStokAsalToko($stokLama,$qty,$tokoAsal,$idProduk){
		$updateStokAsal = array(
									"stok" 		=> $stokLama-$qty
							    );

		$this->modelTransferStok->updateStokAsalToko($idProduk,$tokoAsal,$updateStokAsal);
	}

	function updateStokTokoTujuan($qty,$idToko,$idProduk){
		//cek if id barang exist on store
		$cekProdukToko = $this->modelTransferStok->cekProdukToko($idProduk,$idToko);

		if($cekProdukToko > 0){
			$stokLama = $this->modelTransferStok->stokTokoTujuan($idProduk,$idToko);

			$dataUpdate = array(
									"stok" 	=> $stokLama+$qty
							   );

			$this->modelTransferStok->updateStokTokoTujuan($idProduk,$idToko,$dataUpdate);
		} else {
			$dataInsert = array(
									"id_produk" => $idProduk,
									"stok" => $qty,
									"id_store" => $idToko
							    );

			$this->modelTransferStok->insertStokTokoTujuan($dataInsert);
		}
	}

	function invoiceTransfer(){
		$noTransfer = $this->input->get("noTransfer");

		$data['header'] = $this->db->get("ap_receipt")->row();
		$data['infoTransfer'] = $this->modelTransferStok->infoTransfer($noTransfer);
		$data['itemTransfer'] = $this->modelTransferStok->itemTransferView($noTransfer);

		$this->global['pageTitle'] = "POS - Invoice Transfer Barang";
		$this->loadViews("transfer_stok/bodyInvoiceTransfer",$this->global,$data,"footer_empty");
	}
}
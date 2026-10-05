<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Purchase_order extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library(array('session','encryption'));
		$this->load->model(array("model1","modelPurchaseOrder"));
		$this->load->database();
		
		$this->isLoggedIn($this->global['idUser'],2,9);
	}

	function index(){
		$this->global['pageTitle'] = "POS - Purchase Order";
		$this->global['navigation'] = $this->model1->callNavigation();
		$data['supplier'] = $this->db->get("supplier");
		$this->loadViews("purchase_order/bodyPurchaseOrder",$this->global,$data,"bahan_masuk/footer_barang_masuk");
	}

	function ajax_produk(){
		$q 			= $_GET['term'];

		$get_bahan_baku_select2 = $this->modelPurchaseOrder->produkAjax($q);

		$data_array = array();

		foreach($get_bahan_baku_select2->result() as $row){
			$data_array[] = array(
									"id" 	=> $row->id_produk,
									"text"	=> $row->id_produk." / ".$row->nama_produk
								 );
		}

		echo json_encode($data_array);
	}

	function insertPO(){
		$id_user  		= sprintf("%03d",$this->global['idUser']);
		$tanggal_po 	= date('Y-m-d');
		$tanggal_kirim 	= $_POST['tanggalKirim'];
		$jatuh_tempo 	= $_POST['jatuhTempo'];
		$keterangan 	= $_POST['keterangan'];
		$supplier  		= $_POST['supplier'];
		$alamat 		= $_POST['alamatPengiriman'];

		$cek_tanggal 	= $this->model1->cek_tanggal_terima($tanggal_po);

		$create_date 	= date_create($tanggal_po);
		$convert_date   = date_format($create_date,'y').date_format($create_date,'m').date_format($create_date,'d');

		$no_inv = 'PO'.$convert_date.$id_user.sprintf("%04d",$cek_tanggal+1);

		$data_masuk = array(
								"no_po" 			=> $no_inv,
								"tanggal_po" 		=> $tanggal_po,
								"tanggal_kirim"		=> $tanggal_kirim,
								"jatuh_tempo"		=> $jatuh_tempo,
								"alamat_pengiriman"	=> $alamat,
								"id_supplier"		=> $supplier,
								"keterangan"		=> $keterangan,
								"id_pic"			=> $this->global['idUser'],
								"status"			=> 0
							);
		
		$this->modelPurchaseOrder->insertPONumber($data_masuk);

		$viewDataPO = $this->modelPurchaseOrder->viewCartPO($this->global['idUser']);

		foreach($viewDataPO->result() as $row){
			$sku 			= $row->id_produk;
			$jumlah_beli	= $row->qty;
			$harga 			= $row->harga;

			$data_bahan[]     = array(
										"no_po"			=> $no_inv,
										"sku"			=> $sku,
										"qty"			=> $jumlah_beli,
										"harga"			=> $harga,
										"tanggal"		=> $tanggal_po,
								   );
		}

		$this->modelPurchaseOrder->insertPOItem($data_bahan);
		$this->modelPurchaseOrder->deleteCartPO($this->global['idUser']);
		echo $no_inv;

		//redirect("purchase_order/form_po?no_po=".$no_inv);
	}

	function daftar_po(){
		$this->global['pageTitle'] = "POS - Daftar PO";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("purchase_order/body_daftar_po",$this->global,NULL,"purchase_order/footerDaftarPO");
	}

	function spinner(){
		echo "<img src='".base_url('assets/loading.gif')."'/>";
	}

	function form_po(){
		$no_po = $_GET['no_po'];
		$data['header'] = $this->db->get("ap_receipt");
		$data['purchase_item'] = $this->modelPurchaseOrder->purchase_item($no_po);
		$info_po = $this->modelPurchaseOrder->infoPurchase($no_po);

		$data['tanggal_po'] 		= $info_po->tanggal_po;
		$data['keterangan'] 		= $info_po->keterangan;
		$data['supplier'] 			= $info_po->supplier;
		$data['alamat_sp'] 			= $info_po->alamat;
		$data['kontak_sp'] 			= $info_po->kontak;
		$data['ppn']				= $info_po->ppn;
		$data['nilai_ppn']			= $info_po->nilai_ppn;
		$data['alamat_pengiriman'] 	= $info_po->alamat_pengiriman;
		$data['tanggal_kirim']		= $info_po->tanggal_kirim;
		$data['idSupplier'] 		= $info_po->id_supplier;

		$this->global['pageTitle'] = "POS - Form Purchase Order";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("bahan_masuk/body_form_po",$this->global,$data,"bahan_masuk/footer_barang_masuk");
	}

	function datatablesPO(){
		$this->load->model("modelBahanMasukMaterial");
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$total 			 			= $this->modelBahanMasukMaterial->totalPOProduk();
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelBahanMasukMaterial->viewPOProduk($length,$start,$search);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelBahanMasukMaterial->viewPOProduk($length,$start,$search);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			$status = $dt['status'];

			if($status==0){
				$button = '<span class="label label-primary">Menunggu Approve</span>';
			} elseif($status==1){
				$button = '<span class="label label-success">Diterima</span>';
			} elseif($status==2){
				$button = '<span class="label label-danger">Ditolak</span>';
			} elseif($status==3){
				$button = '<span class="label label-info">Selesai</span>';
			}

			$output['data'][]=array($nomor_urut,"<a href='".base_url('purchase_order/form_po?no_po='.$dt['no_po'])."'>".$dt['no_po']."</a>",$dt['tanggal_po'],$dt['tanggal_kirim'],$dt['supplier'],$dt['first_name'],$button);
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function invoice_receive(){
		$this->load->view("navigation");
		$data['header'] = $this->db->get("ap_receipt");
		$no_receive = $_GET['no_receive'];
		$data['dataReceive'] = $this->model1->dataReceive($no_receive);
		$data['receive_item'] = $this->modelPurchaseOrder->received_item($no_receive);
		$this->load->view("body_invoice_receive",$data);
		$this->load->view("footer");
	}	

	function sendEmailPOSupplier(){
		$no_po 	= $_POST['noPo'];
		$data['header'] = $this->db->get("ap_receipt");
		$data['purchase_item'] = $this->modelPurchaseOrder->purchase_item($no_po);
		$info_po = $this->modelPurchaseOrder->infoPurchase($no_po);
		$data['noPO'] = $no_po;

		$data['tanggal_po'] 		= $info_po->tanggal_po;
		$data['keterangan'] 		= $info_po->keterangan;
		$data['supplier'] 			= $info_po->supplier;
		$data['alamat_sp'] 			= $info_po->alamat;
		$data['kontak_sp'] 			= $info_po->kontak;
		$data['ppn']				= $info_po->ppn;
		$data['nilai_ppn']			= $info_po->nilai_ppn;
		$data['alamat_pengiriman'] 	= $info_po->alamat_pengiriman;
		$data['tanggal_kirim']		= $info_po->tanggal_kirim;
		

		$mesg = $this->load->view("emailFormPO",$data,TRUE);

		$this->load->library("email");

		//get data email
		$dataEmail  = $this->db->get("settingemail")->row();

		$SMTPHost 	= $dataEmail->SMTPHost;
		$SMTPPort 	= $dataEmail->SMTPPort;
		$SMTPUser 	= $dataEmail->SMTPUser;
		$SMTPPass 	= $dataEmail->SMTPPas;
		$SenderName = $dataEmail->UserName;
		

		$config['protocol'] = 'smtp';
		$config['smtp_host'] = $SMTPHost;
		$config['smtp_port'] = $SMTPPort;
		$config['smtp_user'] = $SMTPUser;
		$config['smtp_pass'] = $SMTPPass;
		$config['mailtype'] = 'html';
		$config['charset'] = 'utf-8';
		$config['wordwrap'] = TRUE;
		$config['newline'] = "\r\n";
		$config['crlf'] = "\r\n";
		
		//get email supplier
		$email = $this->modelPurchaseOrder->emailSupplier($_POST['idSupplier']);	

		$this->email->initialize($config);

		$this->email->from($SMTPUser, $SenderName);
		$this->email->to($email);

		$this->email->subject('Purchase Order | '.$no_po);
		$this->email->message($mesg);

		if($this->email->send()){
			echo "1";
		} else {
			echo "0";
		}
	}

	function cekEmailSupplier(){
		$idSupplier = $_POST['idSupplier'];

		//cek email
		$cekEmailIfExist = $this->modelPurchaseOrder->cekEmailIfExist($idSupplier);

		if($cekEmailIfExist == 1){
			echo 1;
		} else {
			echo 0;
		}
	}

	function insertCartPO(){
		$idProduk 		= $_POST['idProduk'];
		$idUser = $this->global['idUser'];
		$hargaProduk = $this->modelPurchaseOrder->hargaBeliProduk($idProduk);

		//cek on cart if exist
		$cekCart = $this->modelPurchaseOrder->cekCartPO($idProduk,$idUser);

		if($cekCart < 1){
			$dataCart = array(
								"idProduk"		=> $idProduk,
								"qty"			=> 1,
								"idUser" 		=> $idUser,
								"harga"			=> $hargaProduk
						     );

			$this->modelPurchaseOrder->insertCartPO($dataCart);
			echo 0;
		} else {			
			$id = $this->modelPurchaseOrder->getIdCart($idProduk,$idUser);
			echo $id;
		}
	}

	function cartPO(){
		$idUser = $this->global['idUser'];
		$data['viewCartPO'] = $this->modelPurchaseOrder->viewCartPO($idUser);
		$this->load->view("purchase_order/cartPO",$data);
	}

	function updateQtyCart(){
		$idProduk = $_POST['idProduk'];
		$idUser   = $this->global['idUser'];
		$qty = $_POST['qty'];

		$dataUpdate = array(
								"qty"		=> $qty
					       );
		
		$this->modelPurchaseOrder->updateQtyCart($idProduk,$idUser,$dataUpdate);
		//get total peritem
		$totalPeritem = $this->modelPurchaseOrder->totalPeritem($idUser,$idProduk);

		echo number_format($totalPeritem,'0',',','.');
	}

	function totalCart(){
		$idUser   = $this->global['idUser'];
		$totalCart = $this->modelPurchaseOrder->totalCartPeruser($idUser);

		if($totalCart){
			echo number_format($totalCart,'0',',','.');
		} else {
			echo 0;
		}
	}

	function updateHargaCart(){
		$idProduk = $_POST['idProduk'];
		$idUser   = $this->global['idUser'];
		$harga = $_POST['harga'];

		$dataUpdate = array(
								"harga"		=> $harga
					       );

		$this->modelPurchaseOrder->updateHargaCart($idProduk,$idUser,$dataUpdate);
	
		//get total peritem
		$totalPeritem = $this->modelPurchaseOrder->totalPeritem($idUser,$idProduk);

		echo number_format($totalPeritem,'0',',','.');
	}

	function hapusCart(){
		$idProduk 	= $_POST['idProduk'];
		$idUser = $this->global['idUser'];

		$this->modelPurchaseOrder->hapusCart($idProduk,$idUser);
	}

}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Laporan extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->library('session');
		$this->load->model(array("model1","modelLaporan","model_penjualan","modelDashboard"));
		$this->load->database();

		$this->isLoggedIn($this->global['idUser'],1,6);
	}

	function pembelian(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,20);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->global['pageTitle'] = "POS - Laporan Pembelian";
				$this->loadViews("laporan/bodyLaporanPembelian",$this->global,NULL,"footer_empty");
			}
		}
	}



	function waste(){
		$this->global['pageTitle'] = "POS - Laporan Waste";
		$this->loadViews("laporan/body_laporan_waste",$this->global,NULL,"laporan/footerWaste");
	}

	function viewReportWaste(){
		$dateStart 	 	= $_POST['dateStart'];
		$dateEnd 		= $_POST['dateEnd'];
		$idProduk 		= $_POST['idProduk'];

		$data['viewReport'] = $this->modelLaporan->viewReportWaste($dateStart,$dateEnd,$idProduk);
		$this->load->view("laporan/viewReportWaste",$data);
	}

	function stock_opname(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,24);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$total_rows = $this->model1->total_so();
				$this->load->library("pagination");
				$config['base_url'] 			= base_url('laporan/stock_opname/');
				$config['total_rows']			= $total_rows;
				$config["per_page"]				= $per_page = 25;
				$config["uri_segment"]			= 3;
				$config["full_tag_open"] 		= '<ul class="pagination">';
				$config["full_tag_close"] 		= '</ul>';
				$config["first_link"] 			= "&laquo;";
				$config["first_tag_open"] 		= "<li>";
				$config["first_tag_close"] 		= "</li>";
				$config["last_link"] 			= "&raquo;";
				$config["last_tag_open"] 		= "<li>";
				$config["last_tag_close"] 		= "</li>";
				$config['next_link'] 			= '&gt;';
				$config['next_tag_open'] 		= '<li>';
				$config['next_tag_close'] 		= '<li>';
				$config['prev_link'] 			= '&lt;';
				$config['prev_tag_open'] 		= '<li>';
				$config['prev_tag_close'] 		= '<li>';
				$config['cur_tag_open'] 		= '<li class="active"><a href="#">';
				$config['cur_tag_close'] 		= '</a></li>';
				$config['num_tag_open'] 		= '<li>';
				$config['num_tag_close'] 		= '</li>';

				$this->pagination->initialize($config);

				$data['paging'] = $this->pagination->create_links();
				$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

				if(empty($_GET['query'])){
					$data['data_so'] = $this->model1->data_so_all($per_page,$page);
				} else {
					$query = $_GET['query'];
					$data['data_so'] = $this->model1->data_so_sort($per_page,$page,$query);
				}

				$this->global['pageTitle'] = "POS - Laporan Stock Opname";
				$this->global['navigation'] = $this->model1->callNavigation();
				$this->loadViews("laporan/body_laporan_stock_opname_detail",$this->global,$data,"footer_empty");
			}
		}
	}

	function stock_opname_report(){
		$data['header'] = $this->db->get("ap_receipt");

		$no_so = $_GET['no_so'];

		$data['header_so'] = $this->db->get_where("stock_opname_info",array("no_so" => $no_so))->row();

		$type = $this->model1->header_type($no_so);
		$data['type'] = $type;

		$data['item_so'] = $this->model1->item_so($no_so,$type);

		$this->global['pageTitle'] = "POS - Laporan Stock Opname";
		$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("stock_opname/body_stock_opname_report",$this->global,$data,"footer_empty");
	}

	function launch_detail_barang(){
		$no_po = $_POST['id'];

		$data['purchase_item'] = $this->model1->purchase_item($no_po);
		$data['no_po'] = $no_po;
		$this->load->view("laporan/detail_barang_modal",$data);
	}


	function hutang_po(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,20);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['header'] = $this->db->get("ap_receipt")->row();
				$data['supplier'] = $this->db->get("supplier")->result();
				$this->global['pageTitle'] = "POS - Laporan Hutang Pembelian";
				$this->loadViews("laporan/body_hutang_po",$this->global,$data,"laporan/footerHutangPO");
			}
		}
	}

	function dataHutangPO(){
		$data['tagihan_hutang'] = $this->modelLaporan->hutang_ditagih();
		$this->load->view("laporan/dataHutangPO",$data);
	}

	function dataHutangPOFilter(){
		$supplier = $_POST['supplier'];
		$tanggalPO = $_POST['tanggalPO'];
		$jatuhTempo = $_POST['jatuhTempo'];

		$data['tagihan_hutang'] = $this->modelLaporan->hutang_ditagih_filter($supplier,$tanggalPO,$jatuhTempo);
		$this->load->view("laporan/dataHutangPO",$data);
	}

	function hutang_jatuh_tempo(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,20);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->global['pageTitle'] = "POS - Laporan Hutang Jatuh Tempo";
				$data['header'] = $this->db->get("ap_receipt")->row();
				$data['supplier'] = $this->db->get("supplier")->result();
				$this->loadViews("laporan/body_hutang_jatuh_tempo",$this->global,$data,"laporan/footerHutangJatuhTempo");
			}
		}
	}

	function dataHutangJatuhTempo(){
		$data['hutang_jatuh_tempo'] = $this->modelLaporan->hutangJatuhTempo();
		$this->load->view("laporan/dataHutangJatuhTempo",$data);
	}

	function dataHutangJatuhTempoFilter(){
		$supplier = $_POST['supplier'];
		$data['hutang_jatuh_tempo'] = $this->modelLaporan->hutangJatuhTempoFilter($supplier);
		$this->load->view("laporan/dataHutangJatuhTempo",$data);
	}

	function hutang_terbayar(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,20);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->global['pageTitle'] = "POS - Laporan Hutang Terbayar";
				$data['supplier'] = $this->db->get("supplier")->result();
				$data['tipeBayar'] = $this->db->get("payment_type_debt")->result();
				$this->loadViews("laporan/body_hutang_terbayar",$this->global,$data,"laporan/footerHutangTerbayar");
			}
		}
	}

	function dataHutangTerbayar(){
		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		$supplier = $_POST['supplier'];
		$tipeBayar = $_POST['tipeBayar'];
		$noPO = $_POST['noPO'];
		$noPayment = $_POST['noPayment'];

		$data['hutang_terbayar'] = $this->modelLaporan->laporanHutangTerbayar($dateStart,$dateEnd,$supplier,$tipeBayar,$noPO,$noPayment);
		$this->load->view("laporan/dataHutangTerbayar",$data);
	}


	function analisa_umur_hutang(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,20);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->global['pageTitle'] = "POS - Analisa Umur Hutang";
				$data['supplier'] = $this->db->get("supplier")->result();
				$this->loadViews("laporan/body_analisa_umur_hutang",$this->global,$data,"laporan/footerAnalisaUmurHutang");
			}
		}
	}

	function dataAnalisaUmurHutang(){

		if(!empty($_POST['supplier'])){
			$supplier = $_POST['supplier'];
		} else {
			$supplier = '';
		}

		$data['hutang_ditagih'] = $this->modelLaporan->hutang_ditagih($supplier);
		$this->load->view("laporan/dataAnalisaUmurHutang",$data);
	}


	function purchaseOrder(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,20);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['supplier'] = $this->db->get("supplier")->result();
				$this->global['pageTitle'] = "POS - Laporan Pembelian";
				$this->global['navigation'] = $this->model1->callNavigation();
				$this->loadViews("laporan/bodyLaporanPurchaseOrder",$this->global,$data,"laporan/footerPurchaseOrder");
			}
		}
	}

	function viewReportPurchaseOrder(){
		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		$supplier = $_POST['supplier'];
		$status = $_POST['status'];

		$data['viewReport'] = $this->modelLaporan->viewReportPurchaseOrder($dateStart,$dateEnd,$supplier,$status);
		$data['ap_receipt'] = $this->db->get("ap_receipt")->row();
		$data['dateStart'] = date_format(date_create($dateStart),'d M Y');
		$data['dateEnd'] = date_format(date_create($dateEnd),'d M Y');
		$this->load->view("laporan/viewReportPurchaseOrder",$data);
	}

	function penjualan(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->global['pageTitle'] = "POS - Laporan Penjualan";
				$this->global['navigation'] = $this->model1->callNavigation();
				$this->loadViews("laporan/body_laporan_penjualan",$this->global,NULL,"footer_empty");
			}
		}
	}

	function penjualan_perbarang(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['pageTitle'] = "POS - Penjualan Perbarang";
				$data['navigation'] = $this->model1->callNavigation();
				$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
				$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
				$this->load->view("navigation",$data);
				$data['store'] = $this->db->get("ap_store")->result();
				$data['idUser'] = $this->session->userdata("id_user");
				$this->load->view("laporan_penjualan/body_penjualan_perbarang",$data);
				$this->load->view("laporan_penjualan/jsInclude");
				$this->load->view("laporan_penjualan/js/jsPenjualanPerbarang");
				$this->load->view("laporan_penjualan/closeTag");
			}
		}
	}

	function penjualanPerbarangReport(){
		$data['produk'] = $this->db->get("ap_produk")->result();

		$date_start  = $_POST['dateStart'];
		$date_end 	 = $_POST['dateEnd'];
		$id_produk   = $_POST['idProduk'];
		$idToko 	 = $_POST['idToko'];

		//check user ini punya akses tidak
		$data['penjualan_perbarang'] = $this->modelLaporan->penjualanPerbarang($date_start,$date_end,$id_produk,$idToko);
		$data['info_produk'] = $this->model1->info_produk($id_produk);
		$data['dateStart'] = $date_start;
		$data['dateEnd'] = $date_end;
		$this->load->view("laporan_penjualan/bodyPenjualanPerbarangReport",$data);
	}

	function cekUserAccess(){
		$idToko = $_POST['idToko'];
		$cekUserAccess = $this->modelLaporan->cekAksesPertoko($idToko,$this->session->userdata("id_user"));

		echo $cekUserAccess;
	}

	function sub_account(){
		$id 	= $_POST['id'];

		$query = $this->db->get_where("ap_payment_account",array("id_payment_type" => $id));

		$data['sub_account'] = $query;

		$rows = $query->num_rows();

		if($rows > 0 ){
			$this->load->view("sub_account",$data);
		} else {
			echo "<input type='hidden' id='subAccount' value=''/>";
		}
	}

	function akumulasiPenjualanProdukPerkriteria(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['navigation'] = $this->model1->callNavigation();
				$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
				$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
				$data['pageTitle'] = "POS - Akumulasi Penjualan Berdasarkan Produk";
				$this->load->view("navigation",$data);
				$data['listKasir'] 	= $this->modelLaporan->list_kasir();
				$data['toko'] = $this->db->get("ap_store")->result();
				$data['payment_type'] = $this->db->get("ap_payment_type")->result();
				$data['customer'] = $this->db->get("ap_customer")->result();
				$data['tempat'] = $this->db->get("ap_stand")->result();
				$data['kategori'] = $this->db->get("ap_kategori")->result();
				$data['idUser'] = $this->session->userdata('id_user');
				$this->load->view("laporan_penjualan/bodyPenjualanPerkriteriaProduk",$data);
				$this->load->view("laporan_penjualan/jsInclude");
				$this->load->view("laporan_penjualan/js/jsAkumulasiKriteriaProduk");
				$this->load->view("laporan_penjualan/closeTag");
			}
		}
	}

	function akumulasiPenjualanPerkriteria(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['navigation'] = $this->model1->callNavigation();
				$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
				$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
				$data['pageTitle'] = "POS - Akumulasi Penjualan Berdasarkan Invoice";
	
				$this->load->view("navigation",$data);
				$data['listKasir'] 	= $this->modelLaporan->list_kasir();
				$data['toko'] = $this->db->get("ap_store")->result();
				$data['payment_type'] = $this->db->get("ap_payment_type")->result();
				$data['customer'] = $this->db->get("ap_customer")->result();
				$data['idUser'] = $this->session->userdata("id_user");
				$this->load->view("laporan_penjualan/bodyPenjualanPerkriteria",$data);
				$this->load->view("laporan_penjualan/jsInclude");
				$this->load->view("laporan_penjualan/js/jsAkumulasiKriteria");
				$this->load->view("laporan_penjualan/closeTag");
			}
		}
	}

	function laporanPenjualanPerkriteria(){
		$dateStart 			= $_POST['dateStart'];
		$dateEnd 			= $_POST['dateEnd'];
		$idKasir 			= $_POST['idKasir'];
		$toko 				= $_POST['toko'];
		$idCustomer 		= $_POST['idCustomer'];
		$typeBayar 			= $_POST['typeBayar'];
		$subAccount 		= $_POST['subAccount'];

		$data['dateStart'] 	= $_POST['dateStart'];
		$data['dateEnd'] 	= $_POST['dateEnd'];
		$data['idKasir'] 	= $_POST['idKasir'];
		$data['toko'] 		= $_POST['toko'];
		$data['idCustomer'] = $_POST['idCustomer'];
		$data['typeBayar'] 	= $_POST['typeBayar'];
		$data['subAccount'] = $_POST['subAccount'];

		$data['laporan'] = $this->modelLaporan->laporanPenjualanPerkriteria($dateStart, $dateEnd,$idKasir,$toko,$idCustomer,$typeBayar,$subAccount);
		$this->load->view("laporan_penjualan/laporanPenjualanPerkriteria",$data);
	}

	function laporanPenjualanPerkriteriaProduk(){
 		$dateStart 		= $_POST['dateStart'];
 		$dateEnd 		= $_POST['dateEnd'];
 		$toko 			= $_POST['toko'];
 		$tempat 		= $_POST['tempat'];
 		$customer 		= $_POST['customer'];
 		$kategori 		= $_POST['kategori'];
 		$subkategori 	= $_POST['subkategori'];
 		$subkategori2 	= $_POST['subkategori2'];

 		$data['dateStart'] 		= $_POST['dateStart'];
 		$data['dateEnd'] 		= $_POST['dateEnd'];
 		$data['toko'] 			= $_POST['toko'];
 		$data['tempat'] 		= $_POST['tempat'];
 		$data['customer'] 		= $_POST['customer'];
 		$data['kategori'] 		= $_POST['kategori'];
 		$data['subkategori'] 	= $_POST['subkategori'];
 		$data['subkategori2'] 	= $_POST['subkategori2'];

 		$data['laporan'] = $this->modelLaporan->penjualanPerkriteriaProduk($dateStart, $dateEnd,$toko,$tempat,$customer,$kategori,$subkategori,$subkategori2);
		$this->load->view("laporan_penjualan/laporanPenjualanPerkriteriaProduk",$data);
	}

	function exportExcelLaporanPenjualanPerkriteriaProduk(){
		$this->load->library("excel/PHPExcel");

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									  ->setCellValue('B1','SKU')
									  ->setCellValue('C1','Nama Produk')
									  ->setCellValue('D1','Satuan')
									  ->setCellValue('E1','Harga Beli')	
									  ->setCellValue('F1','Harga Jual')
									  ->setCellValue('G1','QTY Terjual')
									  ->setCellValue('H1','Total HPP')
									  ->setCellValue('I1','Total Terjual')
									  ->setCellValue('J1','Profit');
		
		$dateStart 		= $_GET['dateStart'];
 		$dateEnd 		= $_GET['dateEnd'];
 		$toko 			= $_GET['toko'];
 		$tempat 		= $_GET['tempat'];
 		$customer 		= $_GET['customer'];
 		$kategori 		= $_GET['kategori'];
 		$subkategori 	= $_GET['subkategori'];
 		$subkategori2 	= $_GET['subkategori2'];

		$laporan = $this->modelLaporan->penjualanPerkriteriaProduk($dateStart, $dateEnd,$toko,$tempat,$customer,$kategori,$subkategori,$subkategori2);

		$i=2;
		foreach($laporan->result() as $row){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$i-1)
									  ->setCellValue('B'.$i,$row->id_produk)
									  ->setCellValue('C'.$i,$row->nama_produk)
									  ->setCellValue('D'.$i,$row->satuan)
									  ->setCellValue('E'.$i,$row->harga_beli)
									  ->setCellValue('F'.$i,$row->harga_jual)
									  ->setCellValue('G'.$i,$row->qty_terjual)
									  ->setCellValue('H'.$i,$row->harga_beli*$row->qty_terjual)
									  ->setCellValue('I'.$i,$row->harga_jual*$row->qty_terjual)
									  ->setCellValue('J'.$i,($row->harga_jual*$row->qty_terjual)-($row->harga_beli*$row->qty_terjual));
		$i++; }

		
		//set title pada sheet (me rename nama sheet)
	  	$objPHPExcel->getActiveSheet()->setTitle('Sheet 1');

	    // Set document properties
		$objPHPExcel->getProperties()->setCreator("Rifal")
								->setLastModifiedBy("Rifal")
								->setTitle("TukuWebsite | Simple PSB System")
								->setSubject("TukuWebsite | Simple PSB System")
								->setDescription("Export Data")
								->setKeywords("office 2007 openxml php")
								->setCategory("Data SO");
	 
	     //mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5          
	    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	 
	   	//sesuaikan headernya 
	    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	   	header("Cache-Control: no-store, no-cache, must-revalidate");
	    header("Cache-Control: post-check=0, pre-check=0", false);
	    header("Pragma: no-cache");
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    //ubah nama file saat diunduh
	    header('Content-Disposition: attachment;filename=Laporan Penjualan.xlsx');
	    //unduh file
	    $objWriter->save("php://output");
	} 

	function exportExcelLaporanPenjualanPerkriteria(){
		$this->load->library("excel/PHPExcel");

		$objPHPExcel = new PHPExcel();

		$objPHPExcel->getActiveSheet()->setCellValue('A1','No')
									  ->setCellValue('B1','No Invoice')
									  ->setCellValue('C1','Toko')
									  ->setCellValue('D1','Tanggal')
									  ->setCellValue('E1','Tipe Bayar')	
									  ->setCellValue('F1','Subtotal')
									  ->setCellValue('G1','Ongkir')
									  ->setCellValue('H1','Diskon Member')
									  ->setCellValue('I1','Diskon')
									  ->setCellValue('J1','Pon Reimburs')
									  ->setCellValue('K1','Diskon Peritem')
									  ->setCellValue('L1','Total');
		
		$dateStart 			= $_GET['dateStart'];
		$dateEnd 			= $_GET['dateEnd'];
		$idKasir 			= $_GET['idKasir'];
		$toko 				= $_GET['toko'];
		$idCustomer 		= $_GET['idCustomer'];
		$typeBayar 			= $_GET['typeBayar'];
		$subAccount 		= $_GET['subAccount'];

		$laporan = $this->modelLaporan->laporanPenjualanPerkriteria($dateStart, $dateEnd,$idKasir,$toko,$idCustomer,$typeBayar,$subAccount);

		$i=2;
		foreach($laporan->result() as $row){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$i-1)
									  ->setCellValue('B'.$i,$row->no_invoice)
									  ->setCellValue('C'.$i,$row->store)
									  ->setCellValue('D'.$i,$row->tanggal)
									  ->setCellValue('E'.$i,$row->payment_type." ".$row->account)
									  ->setCellValue('F'.$i,$row->total)
									  ->setCellValue('G'.$i,$row->ongkir)
									  ->setCellValue('H'.$i,$row->diskon)
									  ->setCellValue('I'.$i,$row->diskon_free)
									  ->setCellValue('J'.$i,$row->poin_value)
									  ->setCellValue('K'.$i,$row->diskon_otomatis)
									  ->setCellValue('L'.$i,($row->total+$row->ongkir)-($row->diskon+$row->diskon_free+$row->poin_value+$row->diskon_otomatis));
		$i++; }

		
		//set title pada sheet (me rename nama sheet)
	  	$objPHPExcel->getActiveSheet()->setTitle('Sheet 1');

	    // Set document properties
		$objPHPExcel->getProperties()->setCreator("Rifal")
								->setLastModifiedBy("Rifal")
								->setTitle("TukuWebsite | Simple PSB System")
								->setSubject("TukuWebsite | Simple PSB System")
								->setDescription("Export Data")
								->setKeywords("office 2007 openxml php")
								->setCategory("Data SO");
	 
	     //mulai menyimpan excel format xlsx, kalau ingin xls ganti Excel2007 menjadi Excel5          
	    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	 
	   	//sesuaikan headernya 
	    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	   	header("Cache-Control: no-store, no-cache, must-revalidate");
	    header("Cache-Control: post-check=0, pre-check=0", false);
	    header("Pragma: no-cache");
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    //ubah nama file saat diunduh
	    header('Content-Disposition: attachment;filename=Laporan Penjualan Produk.xlsx');
	    //unduh file
	    $objWriter->save("php://output");
	}

	function akumulasiPenjualanReport(){
		$dateStart  = $_POST['dateStart'];
		$dateEnd 	= $_POST['dateEnd'];

		$data['akumulasi_penjualan'] = $this->modelLaporan->akumulasiPenjualan($dateStart,$dateEnd);
		$this->load->view("laporan_penjualan/bodyAkumulasiReport",$data);
	}

	function dateReport(){
		$dateStart 		= $_POST['dateStart'];
			$dateEnd 		= $_POST['dateEnd'];

		echo "<h2>Laporan Akumulasi Penjualan</h2>";
		echo "<h2>Periode</h2>";
		echo "<h4>".date_format(date_create($dateStart),"d M Y")."-".date_format(date_create($dateEnd),"d M Y")."</h4>";
	}

	function ajax_customer(){
		$q 	= $_GET['term'];

		$customer = $this->model1->customer_search($q);

		$data_array = array();

		foreach($customer->result() as $row){
			$data_array[] = array(
									"id" 	=> $row->id_customer,
									"text"	=> $row->nama." / ".$row->id_customer
								 );
		}

		echo json_encode($data_array);
	}

	function penjualan_perkategori_customer(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['navigation'] = $this->model1->callNavigation();
				$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
				$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
				$data['pageTitle'] = "POS - Penjualan Perkategori Customer";
		
				$this->load->view("navigation",$data);
				$data['kategori'] = $this->db->get("ap_customer_group")->result();
				$data['toko'] = $this->db->get("ap_store")->result();
				$data['idUser'] = $this->session->userdata('id_user');
		 		$this->load->view("laporan_penjualan/body_penjualan_perkategori_custumer",$data);
				$this->load->view("laporan_penjualan/jsInclude");
				$this->load->view("laporan_penjualan/js/jsPenjualanPerkategoriCustomer");
				$this->load->view("laporan_penjualan/closeTag");
			}
		}
	}

	function penjualanPerkategoriCustomerReport(){
		$start 			= $_POST['dateStart'];
		$end 			= $_POST['dateEnd'];
		$kategori 		= $_POST['kategoriCustomer'];
		$idToko 		= $_POST['idToko'];

		$data['nama_kategori'] = $this->model1->nama_kategori($kategori);
		$data['penjualan_perkategori_customer'] = $this->modelLaporan->penjualan_perkategori_customer($start,$end,$kategori,$idToko);
		$this->load->view("laporan_penjualan/bodyPenjualanPerkategoriCustomerReport",$data);
	}


	function penjualan_percustomer(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['pageTitle'] = "POS - Penjualan Percustomer";
				$data['navigation'] = $this->model1->callNavigation();
				$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
				$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
				$this->load->view("navigation",$data);
				$this->load->view("body_penjualan_percustomer");
				$this->load->view("laporan_penjualan/jsInclude");
				$this->load->view("laporan_penjualan/js/jsPenjualanPercustomer");
				$this->load->view("laporan_penjualan/closeTag");
			}
		}
	}

	function penjualanPercustomerReport(){
		$data['customer'] = $this->db->get("ap_customer")->result();

		$start 			= $_POST['dateStart'];
		$end 			= $_POST['dateEnd'];
		$id_customer 	= $_POST['idCustomer'];


		$data['penjualan_percustomer'] = $this->modelLaporan->penjualan_percustomer($start,$end,$id_customer);

		$nama_customer = $this->db->get_where("ap_customer",array("id_customer" => $id_customer))->result();

		foreach($nama_customer as $cs){
			$data['nama']	= $cs->nama;
		}	

		$data['start'] = $start;
		$data['end'] = $end;
		$this->load->view("laporan_penjualan/bodyPenjualanPercustomerReport",$data);
	}

	function penjualan_perkategori_produk(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['navigation'] = $this->model1->callNavigation();
				$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
				$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
				$data['pageTitle'] = "POS - Penjualan Perkategori Produk";
				$data['navigation'] = $this->model1->callNavigation();
				$this->load->view("navigation",$data);
				$this->load->view("body_penjualan_perkategori_produk");
				$this->load->view("laporan_penjualan/jsInclude");
				$this->load->view("laporan_penjualan/js/jsPerkategoriProduk");
				$this->load->view("laporan_penjualan/closeTag");
			}
		}
	}

	function penjualanPerkategoriProdukReport(){
		$start 			= $_POST['dateStart'];
		$end 			= $_POST['dateEnd'];

		$idKategori = $_POST['idKategori'];
	
		$data['sales_perkategori'] = $this->modelLaporan->salesPerkategori($start,$end,$idKategori);		
		$data['start'] = $start;
		$data['end'] = $end;
		$this->load->view("laporan_penjualan/penjualanPerkategoriProdukReport",$data);
	}

	function penjualan_pertoko(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['navigation'] = $this->model1->callNavigation();
				$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
				$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
				$data['pageTitle'] = "POS - Penjualan Pertoko";

				$this->load->view("navigation",$data);
				$data['store'] = $this->db->get("ap_store")->result();
				$data['idUser'] = $this->session->userdata('id_user');
				$this->load->view("laporan_penjualan/penjualanPertoko",$data);
				$this->load->view("laporan_penjualan/jsInclude");
				$this->load->view("laporan_penjualan/js/jsPenjualanPertoko");
				$this->load->view("laporan_penjualan/closeTag");
			}
		}
	}

	function penjualanPertokoReport(){
		$start 			= $_POST['dateStart'];
		$end 			= $_POST['dateEnd'];
		$idToko 		= $_POST['idToko'];

		$data['start'] 	= $_POST['dateStart'];
		$data['end'] 	= $_POST['dateEnd'];
		$data['idToko'] = $_POST['idToko'];
		$data['laporanPertoko'] = $this->modelLaporan->penjualanPertoko($start,$end,$idToko);
		$data['storeName'] 	 	= $this->modelLaporan->storeName($idToko);
		$this->load->view("laporan_penjualan/penjualanPertokoReport",$data);
	}

	function penjualan_perkasir(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['navigation'] = $this->model1->callNavigation();
				$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
				$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
				$data['pageTitle'] = "POS - Penjualan Perkasir";
		
				$this->load->view("navigation",$data);
				$data['listKasir'] 	= $this->modelLaporan->list_kasir();
				$this->load->view("laporan_penjualan/penjualanPerkasir",$data);
				$this->load->view("laporan_penjualan/jsInclude");
				$this->load->view("laporan_penjualan/js/jsPenjualanPerkasir");
				$this->load->view("laporan_penjualan/closeTag");
			}
		}
	}

	function penjualanPerkasirReport(){
		$start 			= $_POST['dateStart'];
		$end 			= $_POST['dateEnd'];
		$idKasir 		= $_POST['idKasir'];

		$data['start'] 		= $_POST['dateStart'];
		$data['end'] 		= $_POST['dateEnd'];
		$data['idKasir'] 	= $_POST['idKasir'];

		$data['nama_kasir'] = $this->model1->nama_kasir($idKasir);
		$data['laporanPerkasir'] = $this->modelLaporan->penjualanPerkasir($start,$end,$idKasir);
		$this->load->view("laporan_penjualan/penjualanPerkasirReport",$data);
	}

	function akumulasi_penjualan_produk(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['navigation'] = $this->model1->callNavigation();
				$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
				$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
				$data['pageTitle'] = "POS - Akumulasi Penjualan Produk";
			
				$this->load->view("navigation",$data);
				$this->load->view("laporan_penjualan/akumulasiPenjualanProduk");
				$this->load->view("laporan_penjualan/jsInclude");
				$this->load->view("laporan_penjualan/js/jsAkumulasiPenjualanProduk");
				$this->load->view("laporan_penjualan/closeTag");
			}
		}
	}

	function akumulasiPenjualanProdukReport(){
		$start 			= $_POST['dateStart'];
		$end 			= $_POST['dateEnd'];

		$data['start'] = $start;
		$data['end'] = $end;
		$data['laporanAkumulasiProduk'] = $this->modelLaporan->akumulasiPenjualanProduk($start,$end);
		$this->load->view("laporan_penjualan/akumulasiPenjualanProdukReport",$data);
	}

	function get_subkategori(){
		$id_kategori = $_POST['id_kategori'];

		$data['show_sub'] = $this->db->get_where("ap_kategori_1",array("id_kategori" => $id_kategori));

		$count = 0;

		foreach($data['show_sub']->result() as $row){
			$count = $count+$row->id;
		}

		if(!empty($_POST['id_kategori']) && $count > 0){
			$this->load->view("laporan_penjualan/show_sub",$data);
		} else {
			echo "<input type='hidden' id='subkategori2' value=''/>";
		}
	}

	function get_subkategori_2(){
		$id_kategori_2 = $_POST['id'];

		$data['show_sub'] = $this->db->get_where("ap_kategori_2",array("id_kategori_1" => $id_kategori_2));
		$count = 0;

		foreach($data['show_sub']->result() as $row){
			$count = $count+$row->id;
		}

		if(!empty($_POST['id']) && $count > 0){
			$this->load->view("laporan_penjualan/show_sub2",$data);
		} else {
			echo "<input type='hidden' id='subkategori_3' value=''/>";
		}
	}

	function top_customer(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['navigation'] = $this->model1->callNavigation();
				$data['permitAccess'] = $this->model1->permitAccess($this->global['idUser']);
				$data['permitAccessSub'] = $this->model1->permitAccessSub($this->global['idUser']);
				$data['pageTitle'] = "POS - Top Customer";
		
				$this->load->view("navigation",$data);

				$total_rows = $this->db->get("ap_customer")->num_rows();
				$this->load->library('pagination');
				$config['base_url'] 			= base_url('laporan/top_customer');
				$config['total_rows']			= $total_rows;
				$config["per_page"]				= $per_page = 50;
				$config["uri_segment"]			= 3;
				$config["full_tag_open"] 		= '<ul class="pagination">';
				$config["full_tag_close"] 		= '</ul>';
				$config["first_link"] 			= "&laquo;";
				$config["first_tag_open"] 		= "<li>";
				$config["first_tag_close"] 		= "</li>";
				$config["last_link"] 			= "&raquo;";
				$config["last_tag_open"] 		= "<li>";
				$config["last_tag_close"] 		= "</li>";
				$config['next_link'] 			= '&gt;';
				$config['next_tag_open'] 		= '<li>';
				$config['next_tag_close'] 		= '<li>';
				$config['prev_link'] 			= '&lt;';
				$config['prev_tag_open'] 		= '<li>';
				$config['prev_tag_close'] 		= '<li>';
				$config['cur_tag_open'] 		= '<li class="active"><a href="#">';
				$config['cur_tag_close'] 		= '</a></li>';
				$config['num_tag_open'] 		= '<li>';
				$config['num_tag_close'] 		= '</li>';

				$this->pagination->initialize($config);

				$data['paging'] = $this->pagination->create_links();
				$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

				$data['top_customer'] = $this->model1->top_customer($per_page,$page);
				$this->load->view("body_top_customer",$data);
				$this->load->view("footer_empty");
			}
		}
	}
	
	function customer_by_group(){

		if($_POST['id']!=''){
			$customer = $this->db->get_where("ap_customer",array("kategori" => $_POST['id']))->result();
		} else {
			$customer = $this->db->get("ap_customer")->result();
		}

		foreach($customer as $cs){
			echo "<option value='".$cs->id_customer."'>".$cs->nama."</option>";
		}

	}

	function detailPenjualan(){
		$noInvoice 			= $_POST['noInvoice'];

		$data['invoiceDetail'] 	= $this->modelLaporan->invoiceDetail($noInvoice);
		$data['invoiceItem'] 	= $this->modelLaporan->invoiceItem($noInvoice);
		$this->load->view("laporan_penjualan/detailPenjualan",$data);
	}

	function ajax_produk(){
		$q 	= $_GET['term'];
				
		$customer = $this->modelLaporan->ajaxProduk($q);

		$data_array = array();

		foreach($customer->result() as $row){
			$data_array[] = array(
									"id" 	=> $row->id_produk,
									"text"	=> $row->id_produk." / ".$row->nama_produk,
								 );
		}

		echo json_encode($data_array);
	}

	function mutasiBarang(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,22);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['toko'] = $this->db->get("ap_store")->result();
				$this->global['pageTitle'] = "POS - Laporan Barang Keluar / Mutasi";
				$this->global['navigation'] = $this->model1->callNavigation();
				$this->loadViews("laporan/bodyMutasiBarang",$this->global,$data,"laporan/footerMutasiBarang");
			}
		}
	}

	function viewReportMutasi(){
		$data['dateStart'] 	= $_POST['dateStart'];
		$data['dateEnd'] = $_POST['dateEnd'];
		$data['idStore'] = $_POST['idStore'];
		$this->load->view("laporan/viewReportMutasi",$data);
	}

	function datatableMutasi(){
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		$idStore = $_POST['idStore'];

		$total 			 			= $this->modelLaporan->rowDataMutasi($dateStart,$dateEnd,$idStore);
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelLaporan->viewMutasi($length,$start,$search,$dateStart,$dateEnd,$idStore);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelLaporan->viewMutasi($length,$start,$search,$dateStart,$dateEnd,$idStore);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			$output['data'][]=array($nomor_urut,"<a target='_blank' href='".base_url('laporan/invoice_pengeluaran_barang?no_keluaran='.$dt['no_bahan_keluar'])."'>".$dt['no_bahan_keluar']."</a>",date_format(date_create($dt['tanggal_keluar']),'d M Y H:i'),$dt['store'],$dt['nama_penerima']);
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function invoice_pengeluaran_barang(){
		$data['header'] = $this->db->get("ap_receipt");
		$no_keluaran = $_GET['no_keluaran'];
		$data['info'] = $this->model1->info_pengeluaran($no_keluaran);
		$data['spending_item'] = $this->model1->spending_item($no_keluaran);
		$this->load->model("modelProduk");
		$this->global['pageTitle'] = "POS - Invoice Pengeluaran Barang";
		//$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("barang_keluar/body_invoice_pengeluaran_barang",$this->global,$data,"footer_empty");
	}

	function mutasiPeritem(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,22);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['toko'] = $this->db->get("ap_store")->result();
				$this->global['pageTitle'] = "POS - Mutasi Peritem";
				$this->loadViews("laporan/bodyMutasiBarangPeritem",$this->global,$data,"laporan/footerMutasiBarangPeritem");
			}
		}
	}

	function viewReportMutasiPeritem(){
		$data['dateStart'] 	= $_POST['dateStart'];
		$data['dateEnd'] = $_POST['dateEnd'];
		$data['idStore'] = $_POST['idStore'];
		$data['idProduk'] = $_POST['idProduk'];
		$this->load->view("laporan/viewReportMutasiPeritem",$data);
	}

	function datatableMutasiPeritem(){
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		$idStore = $_POST['idStore'];
		$idProduk = $_POST['idProduk'];

		$total 			 			= $this->modelLaporan->rowDataMutasiPeritem($dateStart,$dateEnd,$idStore,$idProduk);
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelLaporan->viewMutasiPeritem($length,$start,$search,$dateStart,$dateEnd,$idStore,$idProduk);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelLaporan->viewMutasiPeritem($length,$start,$search,$dateStart,$dateEnd,$idStore,$idProduk);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			$output['data'][]=array($nomor_urut,"<a target='_blank' href='".base_url('laporan/invoice_pengeluaran_barang?no_keluaran='.$dt['no_bahan_keluar'])."'>".$dt['no_bahan_keluar']."</a>",date_format(date_create($dt['tanggal_keluar']),'d M Y H:i'),$dt['id_produk'],$dt['nama_produk'],$dt['store'],$dt['qty']);
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function totalQTYMutasi(){
		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		$idStore = $_POST['idStore'];
		$idProduk = $_POST['idProduk'];

		$totalQTY = $this->modelLaporan->totalQTYMutasi($dateStart,$dateEnd,$idStore,$idProduk);
		echo $totalQTY;
	}

	function transferStok(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,23);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['toko'] = $this->db->get("ap_store")->result();
				$this->global['pageTitle'] = "POS - Laporan Transfer Stok";
				$this->global['navigation'] = $this->model1->callNavigation();
				$this->loadViews("laporan/bodyTransferStok",$this->global,$data,"laporan/footerTransferStok");
			}
		}
	}

	function viewReportTransfer(){
		$data['dateStart'] 	= $_POST['dateStart'];
		$data['dateEnd'] = $_POST['dateEnd'];
		$data['transferFrom'] = $_POST['transferFrom'];
		$data['transferTo'] = $_POST['transferTo'];
		$this->load->view("laporan/viewReportTransferStok",$data);
	}

	function viewReportTransferPeritem(){
		$data['dateStart'] 	= $_POST['dateStart'];
		$data['dateEnd'] = $_POST['dateEnd'];
		$data['transferFrom'] = $_POST['transferFrom'];
		$data['transferTo'] = $_POST['transferTo'];
		$data['idProduk'] = $_POST['idProduk'];
		$this->load->view("laporan/viewReportTransferStokPeritem",$data);
	}

	function datatableTransferStok(){
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		$transferFrom = $_POST['transferFrom'];
		$transferTo = $_POST['transferTo'];

		$total 			 			= $this->modelLaporan->rowDataTransferStok($dateStart,$dateEnd,$transferFrom,$transferTo);
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelLaporan->viewTransferStok($length,$start,$search,$dateStart,$dateEnd,$transferFrom,$transferTo);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelLaporan->viewTransferStok($length,$start,$search,$dateStart,$dateEnd,$transferFrom,$transferTo);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			$output['data'][]=array($nomor_urut,"<a target='_blank' href='".base_url('laporan/invoiceTransfer?noTransfer='.$dt['noTransfer'])."'>".$dt['noTransfer']."</a>",date_format(date_create($dt['tanggal']),'d M Y H:i'),$this->model1->namaStore($dt['transferFrom']),$this->model1->namaStore($dt['transferTo']));
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function datatableTransferStokPeritem(){
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		$transferFrom = $_POST['transferFrom'];
		$transferTo = $_POST['transferTo'];
		$idProduk = $_POST['idProduk'];

		$total 			 			= $this->modelLaporan->rowDataTransferStokPeritem($dateStart,$dateEnd,$transferFrom,$transferTo,$idProduk);
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelLaporan->viewTransferStokPeritem($length,$start,$search,$dateStart,$dateEnd,$transferFrom,$transferTo,$idProduk);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelLaporan->viewTransferStokPeritem($length,$start,$search,$dateStart,$dateEnd,$transferFrom,$transferTo,$idProduk);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {
			$output['data'][]=array($nomor_urut,"<a target='_blank' href='".base_url('laporan/invoiceTransfer?noTransfer='.$dt['noTransfer'])."'>".$dt['noTransfer']."</a>",date_format(date_create($dt['tanggal']),'d/m/y'),$dt['id_produk'],$dt['nama_produk'],$this->model1->namaStore($dt['transferFrom']),$this->model1->namaStore($dt['transferTo']),$dt['qty']);
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function totalQtyTransferStok(){
		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		$transferFrom = $_POST['transferFrom'];
		$transferTo = $_POST['transferTo'];
		$idProduk = $_POST['idProduk'];
		$qty = $this->modelLaporan->totalQtyTransferStok($dateStart,$dateEnd,$transferFrom,$transferTo,$idProduk);
		echo $qty;
	}

	function invoiceTransfer(){
		$noTransfer = $this->input->get("noTransfer");

		$this->load->model("modelTransferStok");
		$data['header'] = $this->db->get("ap_receipt")->row();
		$data['infoTransfer'] = $this->modelTransferStok->infoTransfer($noTransfer);
		$data['itemTransfer'] = $this->modelTransferStok->itemTransferView($noTransfer);
		$this->global['pageTitle'] = "POS - Data Customer";
		//$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("transfer_stok/bodyInvoiceTransfer",$this->global,$data,"footer_empty");
	}

	function transferStokPeritem(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,23);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['toko'] = $this->db->get('ap_store')->result();
				$this->global['pageTitle'] = "POS - Transfer Stok Peritem";
				$this->global['navigation'] = $this->model1->callNavigation();
				$this->loadViews("laporan/bodyTransferStokPeritem",$this->global,$data,"laporan/footerTransferStokPeritem");
			}
		}
	}


	function invoice_penjualan(){
		$data['pageTitle'] = "POS - Invoice Penjualan";
		$no_invoice = $_GET['no_invoice'];
		$id_store = $this->model1->id_store_invoice($no_invoice);
		$data['receipt'] = $this->db->get_where("ap_store",array("id_store" => $id_store));
		$data['no_invoice'] = $this->model1->invoice_ket($no_invoice);
		$data['invoice_item'] = $this->model1->invoice_item($no_invoice);

		$idKasir = $this->model1->getIdKasir($no_invoice);

		$data['nama_kasir'] = $this->model1->nama_kasir($idKasir);
		$data['item_barang'] = $this->model1->item_barang_struk($no_invoice);
		$data['qty_barang'] = $this->model1->qty_barang_struk($no_invoice);
		$data['tipe_bayar'] = $this->model1->tipe_bayar_struk($no_invoice);

		$this->load->library('ciqrcode');

		/**$qr['data'] 	= $no_invoice;
		$qr['level']	= 'H';
		$qr['size']		= '10';
		$qr['savename']	= FCPATH."qr/".$no_invoice.".png";
		$this->ciqrcode->generate($qr);**/


		$this->global['pageTitle'] = "POS - Invoice Penjualan";
		$this->loadViews("penjualan/body_invoice_penjualan",$this->global,$data,"penjualan/footerInvoicePenjualan");
	}

	function invoiceA4(){
		$noInvoice = $this->input->get('no_invoice');
		$idStore = $this->model_penjualan->getIdStore($noInvoice);
		$data['header'] = $this->db->get_where("ap_store",array("id_store" => $idStore))->row();
		$data['invoiceInfo'] = $this->model_penjualan->invoiceInfo($noInvoice);
		$data['invoiceItem'] = $this->model1->invoice_item($noInvoice);
		$data['qty_barang'] = $this->model1->qty_barang_struk($noInvoice);
		$data['item_barang'] = $this->model1->item_barang_struk($noInvoice);

		$this->global['pageTitle'] = "POS - Invoice Penjualan";
		$this->loadViews("penjualan/invoiceA4",$this->global,$data,"footer_empty");
	}

	function suratJalan(){
		$data['pageTitle'] = "POS - Surat Jalan";
		$noInvoice = $this->input->get('no_invoice');
		$idStore = $this->model_penjualan->getIdStore($noInvoice);
		$data['header'] = $this->db->get_where("ap_store",array("id_store" => $idStore))->row();
		$data['invoiceInfo'] = $this->model_penjualan->invoiceInfo($noInvoice);
		$data['invoiceItem'] = $this->model1->invoice_item($noInvoice);

		$this->global['pageTitle'] = "POS - Surat Jalan";
		$this->loadViews("penjualan/suratJalan",$this->global,$data,"footer_empty");
	}

	function shippingLabel(){
		$noInvoice = $this->input->get('no_invoice');
		$idStore = $this->model_penjualan->getIdStore($noInvoice);
		$data['header'] = $this->db->get_where("ap_store",array("id_store" => $idStore))->row();
		$data['invoiceInfo'] = $this->model_penjualan->invoiceInfo($noInvoice);
		$data['invoiceItem'] = $this->model1->invoice_item($noInvoice);

		$this->load->library('ciqrcode');

		$qr['data'] 	= $noInvoice;
		$qr['level']	= 'H';
		$qr['size']		= '4';
		$qr['savename']	= FCPATH."qr/".$noInvoice.".png";
		$this->ciqrcode->generate($qr);


		$this->global['pageTitle'] = "POS - Shipping Label";
		$this->loadViews("penjualan/shippingLabel",$this->global,$data,"footer_empty");
	}

	function penerimaanBarang(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,24);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['toko'] = $this->db->get("ap_store")->result();
				$data['supplier'] = $this->db->get("supplier")->result();
				$this->global['pageTitle'] = "POS - Laporan Penerimaan Barang";
				$this->global['navigation'] = $this->model1->callNavigation();
				$this->loadViews("laporan/bodyPenerimaanBarang",$this->global,$data,"laporan/footerPenerimaanBarang");
			}
		}
	}

	function penerimaanBarangPeritem(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,24);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['toko'] = $this->db->get("ap_store")->result();
				$data['supplier'] = $this->db->get("supplier")->result();
				$this->global['pageTitle'] = "POS - Penerimaan Barang Peritem";
				$this->global['navigation'] = $this->model1->callNavigation();
				$this->loadViews("laporan/bodyPenerimaanBarangPeritem",$this->global,$data,"laporan/footerPenerimaanBarangPeritem");
			}
		}
	}

	function viewReportPenerimaanBarang(){
		$data['dateStart'] = $_POST['dateStart'];
		$data['dateEnd'] = $_POST['dateEnd'];
		$data['tempatPenerimaan'] = $_POST['tempatPenerimaan'];
		$data['supplier'] = $_POST['supplier'];
		$this->load->view("laporan/viewReportPenerimaanBarang",$data);
	}

	function viewReportPenerimaanBarangPeritem(){
		$data['dateStart'] = $_POST['dateStart'];
		$data['dateEnd'] = $_POST['dateEnd'];
		$data['tempatPenerimaan'] = $_POST['tempatPenerimaan'];
		$data['supplier'] = $_POST['supplier'];
		$data['idProduk'] = $_POST['idProduk'];
		$this->load->view("laporan/viewReportPenerimaanBarangPeritem",$data);
	}

	function convertDate($date){
		return date_format(date_create($date),'d/m/Y');
	}

	function invoice_receive(){
		$this->load->model("modelBahanMasukMaterial");
		$data['header'] = $this->db->get("ap_receipt");
		$no_receive = $_GET['no_receive'];
		$data['dataReceive'] = $this->model1->dataReceive($no_receive);
		$data['receive_item'] = $this->modelBahanMasukMaterial->received_item($no_receive);

		$this->global['pageTitle'] = "POS - Data Customer";
		//$this->global['navigation'] = $this->model1->callNavigation();
		$this->loadViews("bahan_masuk/body_invoice_receive",$this->global,$data,"footer_empty");
	}

	function datatablePenerimaanBarang(){
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		$tempatPenerimaan = $_POST['tempatPenerimaan'];
		$supplier = $_POST['supplier'];

		$total 			 			= $this->modelLaporan->rowPenerimaanBarang($dateStart,$dateEnd,$tempatPenerimaan,$supplier);
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelLaporan->viewPenerimaanBarang($length,$start,$search,$dateStart,$dateEnd,$tempatPenerimaan,$supplier);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelLaporan->viewPenerimaanBarang($length,$start,$search,$dateStart,$dateEnd,$tempatPenerimaan,$supplier);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {

			if(empty($dt['store'])){
				$place = "Gudang";
			} else {
				$place = $dt['store'];
			}

			$output['data'][]=array($nomor_urut,"<a target='_blank' href='".base_url('laporan/invoice_receive?no_receive='.$dt['no_receive'])."'>".$dt['no_receive']."</a>",$dt['no_po'],$this->convertDate($dt['tanggal_terima']),$place,$dt['penerima'],$dt['pemeriksa'],$dt['supplier']);
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function qtyPeritemPenerimaan(){
		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		$tempatPenerimaan = $_POST['tempatPenerimaan'];
		$supplier = $_POST['supplier'];
		$idProduk = $_POST['idProduk'];

		$qty = $this->modelLaporan->qtyPeritemPenerimaan($dateStart,$dateEnd,$tempatPenerimaan,$supplier,$idProduk);
		echo $qty;
	}

	function datatablePenerimaanBarangPeritem(){
		$draw 		= $_REQUEST['draw'];
		$length 	= $_REQUEST['length'];
		$start 		= $_REQUEST['start'];
		$search 	= $_REQUEST['search']["value"];

		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		$tempatPenerimaan = $_POST['tempatPenerimaan'];
		$supplier = $_POST['supplier'];
		$idProduk = $_POST['idProduk'];

		$total 			 			= $this->modelLaporan->rowPenerimaanBarangPeritem($dateStart,$dateEnd,$tempatPenerimaan,$supplier,$idProduk);
		$output 					= array();
		$output['draw']	 			= $draw;
		$output['recordsTotal'] 	= $output['recordsFiltered']=$total;
		$output['data'] 			= array();

		if($search!=""){
			$query = $this->modelLaporan->viewPenerimaanBarangPeritem($length,$start,$search,$dateStart,$dateEnd,$tempatPenerimaan,$supplier,$idProduk);
			$output['recordsTotal'] = $output['recordsFiltered'] = $query->num_rows();
		} else {
			$query = $this->modelLaporan->viewPenerimaanBarangPeritem($length,$start,$search,$dateStart,$dateEnd,$tempatPenerimaan,$supplier,$idProduk);
		}

		$nomor_urut=$start+1;
		foreach ($query->result_array() as $dt) {

			if(empty($dt['store'])){
				$place = "Gudang";
			} else {
				$place = $dt['store'];
			}

			$output['data'][]=array($nomor_urut,"<a target='_blank' href='".base_url('laporan/invoice_receive?no_receive='.$dt['no_receive'])."'>".$dt['no_receive']."</a>",$this->convertDate($dt['tanggal']),$place,$dt['supplier'],$dt['id_produk'],$dt['nama_produk'],$dt['qty']);
			$nomor_urut++;
		}

		echo json_encode($output);
	}

	function returPembelian(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,47);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->global['pageTitle'] = "POS - Laporan Retur Pembelian";
				$data['supplier'] = $this->db->get("supplier")->result();
				$this->loadViews("laporan/returPembelian",$this->global,$data,"laporan/footerReturPembelian");
			}
		}
	}

	function viewReportReturPembelian(){
		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		$supplier = $_POST['supplier'];

		$data['viewReport'] = $this->modelLaporan->viewReportReturPembelian($dateStart,$dateEnd,$supplier);
		$data['ap_receipt'] = $this->db->get("ap_receipt")->row();
		$data['dateStart'] = date_format(date_create($dateStart),'d M Y');
		$data['dateEnd'] = date_format(date_create($dateEnd),'d M Y');
		$this->load->view("laporan/viewReportReturPembelian",$data);
	}

	function returPenjualan(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->global['pageTitle'] = "POS - Laporan Retur Penjualan";
				$this->loadViews("laporan/returPenjualan",$this->global,NULL,"laporan/footerReturPenjualan");
			}
		}
	}

	function labaRugi(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->global['pageTitle'] = "POS - Laporan Laba Rugi";
				$data['toko'] = $this->db->get("ap_store")->result();
				$this->loadViews("laporan/bodyLabaRugi",$this->global,$data,"laporan/footerReturLabaRugi");
			}
		}
	}

	function viewReportLabaRugi(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->load->model("modelFinance");

				$start 		= $_POST['dateStart'];
				$end 		= $_POST['dateEnd'];
				$toko 		= $_POST['toko'];

				$data['start'] = $start;
				$data['end']   = $end;

				if(empty($toko)){
					$data['title'] = "Akumulasi";
				} else {
					$data['title'] = $this->model1->namaStore($toko);
				}

				$data['salesPerkategori'] = $this->modelDashboard->salesPerkategoriRange($start,$end,$toko);
				$data['cogs'] 			  = $this->modelDashboard->bebanPokokPenjualanRange($start,$end,$toko);
				$data['totalSales'] 	  = $this->modelDashboard->totalSalesRange($start,$end,$toko);
				$data['retur'] 			  = $this->modelDashboard->dataReturRange($start,$end,$toko);

				$data['diskonMember'] 	  = $this->modelFinance->diskonMemberRange($start,$end,$toko);
				$data['diskonGlobal'] 	  = $this->modelFinance->diskonGlobalRange($start,$end,$toko);
				$data['diskonPeritem'] 	  = $this->modelFinance->diskonPeritemRange($start,$end,$toko);
				$data['poinReimburs'] 	  = $this->modelFinance->poinReimbursRange($start,$end,$toko);

				$this->load->view("laporan_penjualan/laporanLabaRugi",$data);
			}
		}
	}

	function viewReportReturPenjualan(){
		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		$idProduk = $_POST['idProduk'];
		$noInvoice = $_POST['noInvoice'];
		
		$data['viewReport'] = $this->modelLaporan->viewReportPenjualanPeritem($dateStart,$dateEnd,$idProduk,$noInvoice);
		$this->load->view("laporan/viewReportPenjualanPeritem",$data);
	}

	function penjualanPerkategori(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,21);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->load->model("ModelSalesByKategori");
				$this->global['pageTitle'] = "POS - Laporan Penjualan Perkategori";
				$data['store'] = $this->db->get("ap_store")->result();
				$this->loadViews("laporan/penjualanPerkategori",$this->global,$data,"laporan/footerPenjualanPerkategori");
			}
		}
	}

	function viewReportSalesPerkategori(){
		$this->load->model("ModelSalesByKategori");
		$start 		= $_POST['dateStart'];
		$end 		= $_POST['dateEnd'];
		$idStore 	= $_POST['store'];

		$data['periode'] = date_format(date_create($start),"d M Y")." ".date_format(date_create($end),"d M Y");
		$data['start']   = $start;
		$data['end'] 	 = $end;
		$data['idStore'] = $idStore;
		$this->load->view("laporan/viewSalesByKategori",$data);
	}
}
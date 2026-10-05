<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Dashboard extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->database();
		$this->load->model(array("model1","model_dashboard"));
		$this->load->library("session");
	
		$this->isLoggedIn($this->global['idUser'],1,1);
	}

	function index(){
		$this->load->model("model_dashboard");
		$this->global['pageTitle'] = "POS - Dashboard";
		$this->loadViews("staff/body",$this->global,NULL,"staff/footer_dashboard");
	}

	function dayFilter(){
		$this->load->view("staff/dayFilter");
	}

	function bulanFilter(){
		$this->load->view("staff/bulanFilter");
	}

	function tahunFilter(){
		$this->load->view("staff/tahunFilter");
	}

	function dataPenjualan(){
		if(!empty($_POST['tanggal'])){
			$tanggal = $_POST['tanggal'];
		} else {
			$tanggal = date('Y-m-d');
		}

		$totalSales = $this->model_dashboard->total_sales($tanggal);
		$transaction = $this->model_dashboard->transaction($tanggal);
		$totalItemTerjual = $this->model_dashboard->totalItemTerjual($tanggal);

		if($transaction > 0){
			$basketSize = number_format($totalSales/$transaction,'0','','');
		} else {
			$basketSize = 0;	
		}
		if($totalSales > 0){
			$totalSales = number_format($totalSales,'0','','');
		} else {
			$totalSales = 0;
		}
		if($totalItemTerjual > 0){
			$totalItemTerjual = number_format($totalItemTerjual,'0','','');
		} else {
			$totalItemTerjual = 0;
		}

		$dataArray[] = array(
								"totalSales" => $totalSales, 
								"transaction" => $transaction,
								"basketSize" => $basketSize,
								"totalItemTerjual" => $totalItemTerjual
							);

		echo json_encode($dataArray);
	}

	function dataPenjualanPerbulan(){
		if(!empty($_POST['tanggal'])){
			$tanggal = $_POST['tanggal'];
			$bulan = substr($tanggal, 5,2);
        	$tahun = substr($tanggal, 0,4);
		} else {
			$bulan = date('m');
			$tahun = date('Y');
		}

		$totalSales = $this->model_dashboard->total_sales_perbulan($bulan,$tahun);
		$transaction = $this->model_dashboard->transactionPerbulan($bulan,$tahun);
		$totalItemTerjual = $this->model_dashboard->totalItemTerjualPerbulan($bulan,$tahun);

		if($transaction > 0){
			$basketSize = number_format($totalSales/$transaction,'0','','');
		} else {
			$basketSize = 0;	
		}

		$dataArray[] = array(
								"totalSales" => $totalSales, 
								"transaction" => $transaction,
								"basketSize" => $basketSize,
								"totalItemTerjual" => $totalItemTerjual
							);

		echo json_encode($dataArray);
	}

	function dataPenjualanPertahun(){
		if(!empty($_POST['tanggal'])){
			$tahun = $_POST['tanggal'];
		} else {
			$tahun = date('Y');
		}

		$totalSales = $this->model_dashboard->total_sales_pertahun($tahun);
		$transaction = $this->model_dashboard->transactionPertahun($tahun);
		$totalItemTerjual = $this->model_dashboard->totalItemTerjualPertahun($tahun);

		if($transaction > 0){
			$basketSize = number_format($totalSales/$transaction,'0','','');
		} else {
			$basketSize = 0;	
		}

		$dataArray[] = array(
								"totalSales" => $totalSales, 
								"transaction" => $transaction,
								"basketSize" => $basketSize,
								"totalItemTerjual" => $totalItemTerjual
							);

		echo json_encode($dataArray);
	}

	function salesPerHour(){
		if(!empty($_POST['tanggal'])){
			$tanggal = $_POST['tanggal'];
		} else{
			$tanggal = date('Y-m-d');
		}

		$salesByHour = $this->model_dashboard->salesByHour($tanggal);

		$numRows = $salesByHour->num_rows();

		if($numRows > 0){
			foreach($salesByHour->result() as $dt){
				$date[] = $dt->tanggal;
				$sales[] = $dt->total;
			}

			$data['tanggal'] = json_encode($date);
			$data['sales'] = json_encode($sales);
			$this->load->view('staff/salesByHour',$data);
		} else {
			$this->load->view("staff/noData");
		}
	}

	function salesPerHourMonth(){
		if(!empty($_POST['tanggal'])){
			$tanggal = $_POST['tanggal'];
			$bulan = substr($tanggal, 5,2);
        	$tahun = substr($tanggal, 0,4);
		} else{
			$bulan = date('m');
			$tahun = date('Y');
		}

		$salesByHour = $this->model_dashboard->salesByHourMonth($bulan,$tahun);

		$numRows = $salesByHour->num_rows();

		if($numRows > 0){
			foreach($salesByHour->result() as $dt){
				$date[] = $dt->tanggal;
				$sales[] = $dt->total;
			}

			$data['tanggal'] = json_encode($date);
			$data['sales'] = json_encode($sales);
			$this->load->view('staff/salesByHour',$data);
		} else {
			$this->load->view("staff/noData");
		}
	}

	function salesPerHourYear(){
		if(!empty($_POST['tanggal'])){
			$tahun = $_POST['tanggal'];
		} else{
			$tahun = date('Y');
		}

		$salesByHour = $this->model_dashboard->salesByHourYear($tahun);

		$numRows = $salesByHour->num_rows();

		if($numRows > 0){
			foreach($salesByHour->result() as $dt){
				$date[] = $dt->tanggal;
				$sales[] = $dt->total;
			}

			$data['tanggal'] = json_encode($date);
			$data['sales'] = json_encode($sales);
			$this->load->view('staff/salesByHour',$data);
		} else {
			$this->load->view("staff/noData");
		}
	}

	function perkategoriSales(){
		if(empty($_POST['tanggal'])){
			$date = date('Y-m-d');
		} else {
			$date = $_POST['tanggal'];
		}

		$salesPerkategori = $this->model_dashboard->salesPerkategori($date);

		$numRows = $salesPerkategori->num_rows();

		if($numRows > 0){

			foreach($salesPerkategori->result() as $row){
				$kategori[] = $row->kategori;
				$sales[] = $row->totalPenjualan;
			}

			$data['kategori'] = json_encode($kategori);
			$data['sales'] = json_encode($sales);

			$this->load->view("staff/perkategoriSales",$data);

		} else {
			$this->load->view("staff/noData");
		}
	}

	function perkategoriSalesMonth(){
		if(empty($_POST['tanggal'])){
			$bulan = date('m');
			$tahun = date('Y');
		} else {
			$date = $_POST['tanggal'];
			$bulan = substr($date, 5,2);
        	$tahun = substr($date, 0,4);
		}

		$salesPerkategori = $this->model_dashboard->salesPerkategoriMonth($bulan,$tahun);

		$numRows = $salesPerkategori->num_rows();

		if($numRows > 0){

			foreach($salesPerkategori->result() as $row){
				$kategori[] = $row->kategori;
				$sales[] = $row->totalPenjualan;
			}

			$data['kategori'] = json_encode($kategori);
			$data['sales'] = json_encode($sales);

			$this->load->view("staff/perkategoriSales",$data);

		} else {
			$this->load->view("staff/noData");
		}
	}

	function perkategoriSalesYear(){
		if(empty($_POST['tanggal'])){
			$tahun = date('Y');
		} else {
			$tahun = $_POST['tanggal'];
		}

		$salesPerkategori = $this->model_dashboard->salesPerkategoriYear($tahun);

		$numRows = $salesPerkategori->num_rows();

		if($numRows > 0){

			foreach($salesPerkategori->result() as $row){
				$kategori[] = $row->kategori;
				$sales[] = $row->totalPenjualan;
			}

			$data['kategori'] = json_encode($kategori);
			$data['sales'] = json_encode($sales);

			$this->load->view("staff/perkategoriSales",$data);

		} else {
			$this->load->view("staff/noData");
		}
	}

	function salesPerKasir(){
		if(empty($_POST['tanggal'])){
			$date = date('Y-m-d');
		} else {
			$date = $_POST['tanggal'];
		}

		$salesPerkasir = $this->model_dashboard->salesPerkasir($date);

		$numRows = $salesPerkasir->num_rows();

		if($numRows > 0){
			foreach($salesPerkasir->result() as $row){
				$kasir[] = $row->first_name;
				$sales[] = $row->total;
			}

			$data['kasir'] = json_encode($kasir);
			$data['sales'] = json_encode($sales);

			$this->load->view("staff/salesByKasir",$data);
		} else {
			$this->load->view("staff/noData");
		}
	}

	function salesPerKasirMonth(){
		if(empty($_POST['tanggal'])){
			$bulan = date('m');
			$tahun = date('Y');
		} else {
			$date = $_POST['tanggal'];
			$bulan = substr($date, 5,2);
        	$tahun = substr($date, 0,4);
		}

		$salesPerkasir = $this->model_dashboard->salesPerkasirMonth($bulan,$tahun);

		$numRows = $salesPerkasir->num_rows();

		if($numRows > 0){
			foreach($salesPerkasir->result() as $row){
				$kasir[] = $row->first_name;
				$sales[] = $row->total;
			}

			$data['kasir'] = json_encode($kasir);
			$data['sales'] = json_encode($sales);

			$this->load->view("staff/salesByKasir",$data);
		} else {
			$this->load->view("staff/noData");
		}
	}

	function salesPerKasirYear(){
		if(empty($_POST['tanggal'])){
			$tahun = date('Y');
		} else {
			$tahun = $_POST['tanggal'];
		}

		$salesPerkasir = $this->model_dashboard->salesPerkasirYear($tahun);

		$numRows = $salesPerkasir->num_rows();

		if($numRows > 0){
			foreach($salesPerkasir->result() as $row){
				$kasir[] = $row->first_name;
				$sales[] = $row->total;
			}

			$data['kasir'] = json_encode($kasir);
			$data['sales'] = json_encode($sales);

			$this->load->view("staff/salesByKasir",$data);
		} else {
			$this->load->view("staff/noData");
		}
	}

	function hariLiniMasa(){
		$this->load->view("staff/hariLiniMasa");
	}

	function bulanLiniMasa(){
		$this->load->view("staff/bulanLiniMasa");
	}

	function tahunLiniMasa(){
		$this->load->view("staff/tahunLiniMasa");
	}

	function loading(){
		echo "<center><img src='".base_url('assets/loading.gif')."'/></center>";
	}

	function convertDateMonth($tanggal){
		$month = substr($tanggal, 0,2);
    	$year  = substr($tanggal, 3,4);
    		
    		if($month=='01'){
    			$var =  "Januari"." ".$year;
    		} elseif($month=='02'){
    			$var =   "Februari"." ".$year;
    		} elseif($month=='03'){
    			$var =   "Maret"." ".$year;
    		} elseif($month=='04'){
    			$var =   "April"." ".$year;
    		} elseif($month=='05'){
    			$var =   "Mei"." ".$year;
    		} elseif($month=='06'){
    			$var =   "Juni"." ".$year;
    		} elseif($month=='07'){
    			$var =   "Juli"." ".$year;
    		} elseif($month=='08'){
    			$var =   "Agustus"." ".$year;
    		} elseif($month=='09'){
    			$var =   "September"." ".$year;
    		} elseif($month=='10'){
    			$var =   "Oktober"." ".$year;
    		} elseif($month=='11'){
    			$var =   "Nopember"." ".$year;
    		} elseif($month=='12'){
    			$var =   "Desember"." ".$year;
    		}

    		return $var;
	}

	function liniMasa(){
		$this->load->model("modelLiniMasa");

		$start 		 = $_POST['dateStart'];
		$end 		 = $_POST['dateEnd'];
		$type 		 = $_POST['type'];

		if($type=='day'){
			$data['periode'] 	= date_format(date_create($start),"d M Y")." - ".date_format(date_create($end),"d M Y");
			$akumulasiPendapatan = $this->modelLiniMasa->akumulasiPendapatan($start,$end);
			$potongan 			 = $this->modelLiniMasa->potongan($start,$end);
		} elseif($type=='month'){
			$data['periode'] 	= $this->convertDateMonth($start)." - ".$this->convertDateMonth($end);
			$akumulasiPendapatan = $this->modelLiniMasa->akumulasiPendapatanPermonth($start,$end);
			$potongan 			 = $this->modelLiniMasa->potonganPermonth($start,$end);
		} elseif($type=='year'){
			$data['periode'] 	= $start." - ".$end;
			$akumulasiPendapatan = $this->modelLiniMasa->akumulasiPendapatanPeryear($start,$end);
			$potongan 			 = $this->modelLiniMasa->potonganPeryear($start,$end);
		}

		$numRows = $akumulasiPendapatan->num_rows();

		foreach($akumulasiPendapatan->result() as $row){
			if($type=='day'){
				$dateFormat = date_format(date_create($row->tanggal),"d M Y");
			} elseif($type=='month'){
				$dateFormat = date_format(date_create($row->tanggal),"M Y");
			} elseif($type=='year'){
				$dateFormat = date_format(date_create($row->tanggal),"Y");
			}

			$dataTitle[] = $dateFormat;
			$dataVal[] 	 = $row->total; 
		}

		foreach($potongan as $dt){
			$dataPotongan[] = $dt->total;
		}

		if($numRows > 0){
			$data['title'] 				= json_encode($dataTitle);
			$data['value']  			= json_encode($dataVal);
			$data['potongan'] 			= json_encode($dataPotongan);
			$data['akumulasiIncome'] 	= $akumulasiPendapatan;
			$data['type'] 				= $type;
			$data['start']  			= $start;
			$data['end'] 				= $end;
			$this->load->view("staff/akumulasiPendapatan",$data);
		} else {
			$this->load->view("staff/test");
		}
	}
}
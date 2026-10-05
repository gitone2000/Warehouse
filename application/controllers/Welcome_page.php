<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Welcome_page extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->helper("url");
		$this->load->model("model1");
	}

	function index(){
		$this->global['pageTitle'] = "POS";
		$data['navigation'] = $this->model1->callNavigation();
		$this->loadViews("bodyWelcome",$this->global,NULL,"footer_empty");
	}

}
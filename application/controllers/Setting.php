<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Setting extends BaseController{
	function __construct(){
		parent::__construct();
		$this->load->library("ion_auth");
		$this->load->helper("url");
		$this->load->model(array("model1","modelPublic"));

		$this->isLoggedIn($this->global['idUser'],1,11);
	}

	function info_perusahaan(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,42);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$data['apReceipt'] = $this->db->get("ap_receipt")->result();
				$this->global['pageTitle'] = "POS - Setting Info Perusahaan";
				$this->loadViews("setting/bodyInfoPerusahaan",$this->global,$data,"footer_empty");
			}
		}
	}

	function updateInfoPerusahaanSQL(){
		$companyName  = $this->input->post("companyName");
		$kontak 	  = $this->input->post("kontak");
		$alamat 	  = $this->input->post("address");

		$dataUpdate = array(
								"alamat"			=> $alamat,
								"kontak"			=> $kontak,
								"nama_perusahaan" 	=> $companyName
						   );

		$affect = $this->modelPublic->updateInfoPerusahaanSQL($dataUpdate);

		if($affect > 0){
			$message = "<div class='alert alert-success alert-dismissable'>";
            $message .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
            $message .= "Data Berhasil Diubah";
            $message .= "</div>";

			$this->session->set_flashdata("message",$message);
		} else {
			$message = "<div class='alert alert-danger alert-dismissable'>";
            $message .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
            $message .= "Data Gagal Diubah";
            $message .= "</div>";

			$this->session->set_flashdata("message",$message);
		}
		redirect("setting/info_perusahaan");
	}

	function email(){
		$data['viewEmailSetting'] = $this->db->get("settingemail")->result();
		$this->global['pageTitle'] = "POS - Setting Email";
		$this->loadViews("setting/bodyEmailSetting",$this->global,$data,"footer_empty");
	}

	function updateEmailSetting(){
		$SMTPHost 		= $this->input->post("SMTPHost");
		$SMTPPort 		= $this->input->post("SMTPPort");
		$SMTPUser 		= $this->input->post("SMTPUser");
		$SMTPPassword 	= $this->input->post("SMTPPassword");
		$senderName 	= $this->input->post("SenderName");

		$dataUpdate 	= array(
									"SMTPHost"		=> $SMTPHost,
									"SMTPPort"		=> $SMTPPort,
									"SMTPUser"		=> $SMTPUser,
									"SMTPPas" 		=> $SMTPPassword,
									"UserName"		=> $senderName
							   );

		$affect = $this->modelPublic->updateEmailSetting($dataUpdate);

		if($affect > 0){
			$message = "<div class='alert alert-success alert-dismissable'>";
            $message .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
            $message .= "Data Berhasil Diubah";
            $message .= "</div>";

			$this->session->set_flashdata("message",$message);
		} else {
			$message = "<div class='alert alert-danger alert-dismissable'>";
            $message .= "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
            $message .= "Data Gagal Diubah";
            $message .= "</div>";

			$this->session->set_flashdata("message",$message);
		}

		redirect("setting/email");
	}

	function user(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,46);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->global['pageTitle'] = "POS - User";
				$data['user'] = $this->db->get("users");
				$this->loadViews("user/body_user_management",$this->global,$data,"footer_empty");
			}
		}
	}

	function tambah_user(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,46);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->global['pageTitle'] = "POS - Tambah User";
				$data['user'] = $this->db->get("users");
				$data['store'] = $this->db->get("ap_store")->result();
				$this->loadViews("user/body_tambah_user",$this->global,$data,"user/footerTambahUser");
			}
		}
	}

	function tambah_user_sql(){
		$namaDepan 		= $_POST['namaDepan'];
		$namaBelakang 	= $_POST['namaBelakang'];
		$noHP 			= $_POST['noHP'];
		$email 			= $_POST['email'];	
		$username 		= $_POST['username'];
		$password		= $_POST['password'];
		$group 			= "";
		$menu 			= $_POST['menu'];
		$submenu 		= $_POST['submenu'];
		$toko 			= $_POST['toko'];

		$additionalData = array(
								"first_name"	=> $namaDepan,
								"last_name"		=> $namaBelakang,
								"phone"			=> $noHP,
								"menu" 			=> $menu,
								"sub_menu" 		=> $submenu,
								"toko"			=> $toko						
								);

		$this->ion_auth->register($username,$password,$email,$additionalData,$group);
	}

	function editUserSQL(){
		$namaDepan 		= $_POST['namaDepan'];
		$namaBelakang 	= $_POST['namaBelakang'];
		$noHP 			= $_POST['noHP'];
		$email 			= $_POST['email'];	
		$username 		= $_POST['username'];
		$password		= $_POST['password'];
		$group 			= "";
		$menu 			= $_POST['menu'];
		$submenu 		= $_POST['submenu'];
		$status 		= $_POST['status'];
		$idUser 		= $_POST['idUser'];
		$toko 			= $_POST['toko'];

		if(!empty($password)){

			$dataUpdate = array(
									"first_name"	=> $namaDepan,
									"last_name"		=> $namaBelakang,
									"phone"			=> $noHP,
									"menu" 			=> $menu,
									"sub_menu" 		=> $submenu,
									"username"		=> $username,
									"password"		=> $password,
									"active" 		=> $status,
									"email"			=> $email,
									"toko"			=> $toko						
								);
		} else {
			$dataUpdate = array(
									"first_name"	=> $namaDepan,
									"last_name"		=> $namaBelakang,
									"phone"			=> $noHP,
									"menu" 			=> $menu,
									"sub_menu" 		=> $submenu,
									"username"		=> $username,
									"active" 		=> $status,
									"email"			=> $email,
									"toko"			=> $toko						
								);
		}

		 $this->ion_auth->update($idUser,$dataUpdate);
	}

	function editUser(){
		if (!$this->ion_auth->logged_in()){	
			redirect("login");
		} else {
			$cekMyAccess = $this->model1->cekMyAccess($this->global['idUser'],2,46);
			if($cekMyAccess < 1){
				$this->accessDenied();
			} else {
				$this->global['pageTitle'] = "POS - Edit User";
				$data['store'] = $this->db->get("ap_store")->result();
				$data['user'] = $this->db->get_where("users",array("id" => $this->input->get("id_user")))->row();
				$this->loadViews("user/bodyEditUser",$this->global,$data,"user/footerEditUser");
			}
		}
	}

	function checkEmailIfExist(){
		$email = $_POST['email'];
		$cekEmail = $this->modelPublic->checkEmailIfExist($email);

		if($cekEmail > 0){
			echo "1";
		} 
	}

	function checkUsernameIfExist(){
		$username = $_POST['username'];

		$cekUsername = $this->modelPublic->checkUsername($username);

		if($cekUsername > 0){
			echo "1";
		}
	}

}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Allone extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('commn');
    }
	public function all_roles(){
		$table = "role";
		$Commn = new Commn();
		$select = 'id, r_name';
        $response= $Commn->where_selectAll($table,array('status'=> 1),$select);

        $data = array('message' => 'successfully get role','code'=>200,'role'=> $response);

        echo json_encode($data);
	}
}
?>
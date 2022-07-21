<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

class Bank extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('commn');
    }
    
    public function ifsc_code_checker(){
        
        $ifsc_code = $this->input->post('ifsc');
        
        if($ifsc_code == ''){
            $response = array('message'=> 'IFSC Code field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        
        if(isset($ifsc_code)) {
            $ifsc = $ifsc_code;
            $json = @file_get_contents(
                "https://ifsc.razorpay.com/".$ifsc);
            $arr = json_decode($json);
      
            if(empty($arr)){
                $response = array('message'=> 'Invalid IFSC Code','code'=> 400);
                echo json_encode($response);
                return false;   
            }
            
            if(isset($arr)){
                
                // echo "<pre>";print_r($arr);
                $bank_arr = array(
                    'bank_name' => $arr->BANK,
                    'branch_name' => $arr->BRANCH,
                    'bank_code' => $arr->BANKCODE,
                    'bank_ifsc_code' => $arr->IFSC,
                    'bank_city' => $arr->CITY
                );
                $response = array('message'=> 'successfully get Bank details','code'=> 200,'bank'=> $bank_arr);
                echo json_encode($response);
            }
        }
    }
}
?>
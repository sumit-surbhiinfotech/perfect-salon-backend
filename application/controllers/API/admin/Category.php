<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

class Category extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('commn');
        $this->load->model('Dashboard_Model');
    }
    
    public function view_category(){
        $commn = new commn();
        $categories = $commn->selectAll('salon_type');
        if(isset($categories) && !empty($categories)){
            $response = array('message'=> 'Get Categories','code'=> 200,'lists' => $categories);
            echo json_encode($response);            
        }else{
            $response = array('message'=> 'Categories not found','code'=> 400);
            echo json_encode($response);
        }
    }
    
    public function add_category(){
        $commn = new commn();
        $name = $this->input->post('name');
        if($name == ''){
            $response = array('message'=> 'Name is required','code'=> 400);
            echo json_encode($response);    
            return false;
        }
        $get_cate = $commn->get_row_data('salon_type', array('type_name' => $name));
        if(isset($get_cate) && !empty($get_cate)){
            $response = array('message'=> 'Already added category','code'=> 400);
            echo json_encode($response);
            return false;
        }else{
            $data = array('type_name' => $name);
            $add_cate = $commn->insert_data('salon_type',$data);
            if($add_cate == 1){
                $response = array('message'=> 'Added Category','code'=> 200);
                echo json_encode($response);  
            }else{
                $response = array('message'=> 'Smothing wrong','code'=> 400);
                 echo json_encode($response);   
                 return false;
            }
        }
    }
    
    public function delete_category(){
        $commn = new commn();
        $id = $this->input->post('id');
        if($id == ''){
            $response = array('message'=> 'id is required','code'=> 400);
            echo json_encode($response);    
            return false;
        }
        $status = $commn->delete_data('salon_type',array('id' => $id));
       
            if($status == 1){
                $response = array('message'=> 'Category Deleted Successfully','code'=> 200);
                echo json_encode($response);  
            }else{
                $response = array('message'=> 'Smothing wrong','code'=> 400);
                 echo json_encode($response);   
                 return false;
            }
      
    }
    
}

?>
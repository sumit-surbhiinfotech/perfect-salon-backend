<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

class Queries extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('commn');
    }
    public function queries_list(){
        $Common = new commn();
            
        $Contact_list_arr = array();
        $Contact_lists =  $Common->selectAll('contact_us');
        
        if(isset($Contact_lists) && !empty($Contact_lists)){
            foreach($Contact_lists as $Contact_list){
                $Contact = array();
                $Contact['image'] = $Common->select_get_row_data('users', array('id' => $Contact_list->user_id),'image');
                
                if(!empty($Contact['image']) && isset($Contact['image'])){
                    $image = base_url().'assets/profile_pic/seller_user/'.$Contact['image'];
                }else{
                    $image = base_url().'assets/profile_pic/default_pro_pic.png';
                }
                
                $Contact['id'] = $Contact_list->id;
                $Contact['name'] = $Common->select_get_row_data('users', array('id' => $Contact_list->id),'name');
            
                $Contact['image'] = $image;
                $Contact['email'] = $Contact_list->email;
                $Contact['subject'] = $Contact_list->subject;
                $Contact['content'] = $Contact_list->content;
                $Contact['date_time'] = date('dd F Y', strtotime($Contact_list->created_at)).' at '.date('h a', strtotime($Contact_list->created_at));
                
                array_push($Contact_list_arr, $Contact);
            }
            
            if(isset($Contact_list_arr) && !empty($Contact_list_arr)){
                $response = array('message'=> 'Contacts lists','code'=> 200,'lists' => $Contact_list_arr);
                echo json_encode($response);   
            }else{
                $response = array('message'=> 'Contacts not found','code'=> 400);
                echo json_encode($response);   
            }
        }else{
            $response = array('message'=> 'Contacts not found','code'=> 400);
            echo json_encode($response);
        }
    }
}

?>
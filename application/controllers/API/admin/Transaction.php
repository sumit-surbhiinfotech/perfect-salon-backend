<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

class Transaction extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('commn');
    }
    public function transaction_list(){
        $Common = new commn();
            
        $transaction_list_arr = array();
        $transaction_lists =  $Common->selectAll('salon_booking');
        
        if(isset($transaction_lists) && !empty($transaction_lists)){
            foreach($transaction_lists as $transaction_list){
                $transaction = array();
                 $transaction['image'] = $Common->select_get_row_data('users',array('id' => $Common->select_get_row_data('salon-list',array('id' => $transaction_list->user_id),'user_id')),'image');
                if(!empty($new_partner_list->image) && isset($new_partner_list->image)){
                    $image = base_url().'assets/profile_pic/seller_user/'.$new_partner_list->image;
                }else{
                    $image = base_url().'assets/profile_pic/default_pro_pic.png';
                }
                
                $transaction['id'] = $transaction_list->id;
                $transaction['image'] = $image;
                          $transaction['name'] = $Common->select_get_row_data('users',array('id' => $Common->select_get_row_data('salon-list',array('id' => $transaction_list->user_id),'user_id')),'name');
                $transaction['salon_name'] = $Common->select_get_row_data('salon-list',array('id' => $transaction_list->id),'salon_name');
    
                $transaction['commission'] = "3%";
                $transaction['transaction_amount'] = $transaction_list->total_pay;
                $transaction['month'] = date('F-y', strtotime($transaction_list->created_at));
                
                array_push($transaction_list_arr, $transaction);
            }
            
            if(isset($transaction_list_arr) && !empty($transaction_list_arr)){
                $response = array('message'=> 'Transactions lists','code'=> 200,'lists' => $transaction_list_arr);
                echo json_encode($response);   
            }else{
                $response = array('message'=> 'Transactions not found','code'=> 400);
                echo json_encode($response);   
            }
        }else{
            $response = array('message'=> 'Transactions not found','code'=> 400);
            echo json_encode($response);
        }
    }
   
}

?>
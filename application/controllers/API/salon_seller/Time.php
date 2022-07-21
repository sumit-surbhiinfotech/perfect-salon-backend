<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
class Time extends CI_Controller {
	public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('commn');
    }
    public function get_time_slot(){
        $minutes = 30;
        $start = new \DateTime('07:00');
        $end = new \DateTime('23:00');
        $interval = new DateInterval("PT".$minutes."M");
        $dateRange = new DatePeriod($start, $interval, $end);
        $start_time_arr = array();
        foreach ($dateRange as $date) {
            array_push($start_time_arr, $date->format("h:ia"));
        }
        if(isset($start_time_arr) && !empty($start_time_arr)){
            $response = array('message'=> 'Get time slot','code'=> 200,'time_slot' => array("start_time" =>$start_time_arr, 'end_time' => $start_time_arr));
            echo json_encode($response); 
        }
    }
    
    public function update_time_slot(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        $time_slot = $this->input->post('time_slot');
        
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        $services_arr = array();
        if(isset($user_data) & !empty($user_data)){
          $seller_id =  $Commn->select_get_row_data('salon-list',array('user_id'=> $user_data->id),'id');
          $time_slot = json_decode($time_slot,TRUE);
          $time_slot = serialize($time_slot);
          
          
          $min_array = array();
          $max_array = array();
           foreach(unserialize($time_slot) as $time){
            //   echo "<pre>";print_r($time);
               if($time['status'] == 1){
                    $min_time = $time['start_time'];
                    $max_time = $time['end_time'];
                    array_push($min_array, strtok($min_time, " "));
                    array_push($max_array, strtok($max_time, " "));
               }

           }
           $min_time = min($min_array);
           $max_time = max($max_array);
           
          $update_time_slot =  $Commn->update_data('salon-list',array('salon_time_slot' => $time_slot ,'start_time' => $min_time, 'end_time' => $max_time),array('id' => $seller_id));
          if($update_time_slot == 1){
                    $step_data =  $Commn->get_row_data('seller_steps',array('user_id'=> $seller_user_id));
                    if(isset($step_data) && !empty($step_data)){
                        if($step_data->current_step == 3){
                            $next_step = 4;
                        }else{
                             $next_step = $step_data->current_step;
                        }
                    }else{
                        $next_step = 6;
                    }
                    $step_update_data = array(
                        'steps' => '1=>general_settings:status=>1,2=>services_settings:status=>1,3=>date/time_settings:status=>1,4=>image_settings:status=>0,5=>descripation_settings:status=>0,1=>home_screen:status=>0',
                        'current_step' => $next_step,
                    );
                    $step_where = array('user_id' => $seller_user_id);
                    $update_step =  $Commn->update_data('seller_steps',$step_update_data,$step_where);
                    if($update_step == 1){    
                        $current_step = $Commn->select_get_row_data('seller_steps',array('user_id'=> $seller_user_id),'current_step');
    			        if($current_step == "" && $current_step == null){ $current_step = "6";}else{ $current_step = ($current_step); }
                            $response = array('message'=> 'successfully update time slot','code'=> 200, "current_step" => $current_step);
                            echo json_encode($response);
                    }else{
                     $response = array('message'=> 'Something else','code'=> 400);
                     echo json_encode($response);   
                    }                 

          }else{
                $response = array('message'=> 'somthing wrong','code'=> 400);
                echo json_encode($response);  
          }
        }else{
            $response = array('message'=> 'user not found','code'=> 400);
            echo json_encode($response);            
        }
    }
    
    public function salon_time_slot(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        $time_slot = $this->input->post('time_slot');
        
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        $services_arr = array();
        if(isset($user_data) & !empty($user_data)){
          $seller_id =  $Commn->select_get_row_data('salon-list',array('user_id'=> $user_data->id),'id');
          $salon_time_slot =  $Commn->select_get_row_data('salon-list',array('id'=> $seller_id),'salon_time_slot');
          if(isset($salon_time_slot) & !empty($salon_time_slot)){
            $response = array('message'=> 'get time slot','code'=> 200,'time_slot' => unserialize($salon_time_slot));
            echo json_encode($response);      
          }else{
               $response = array('message'=> 'time slot not found','code'=> 400);
                echo json_encode($response); 
          }
        }else{
            $response = array('message'=> 'user not found','code'=> 400);
            echo json_encode($response);            
        }
    }
}
?>
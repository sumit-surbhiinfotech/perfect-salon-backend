<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
class Review extends CI_Controller {
	public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('commn');
    }
    
    public function add_review(){
        $Commn = new Commn();
        $star = $this->input->post('star');
        $user_id = $this->input->post('user_id');
        $salon_id = $this->input->post('salon_id');
        $booking_id = $this->input->post('booking_id');
        if($star == ''){
            $response = array('message'=> 'star field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($user_id == ''){
            $response = array('message'=> 'user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($salon_id == ''){
            $response = array('message'=> 'salon id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($booking_id == ''){
            $response = array('message'=> 'booking id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $user_id, 'role' => 1));
        if(empty($user_data)){
             $response = array('message'=> 'user not found','code'=> 400);
             echo json_encode($response);
             return false;
        }
        $salon =  $Commn->get_row_data('salon-list',array('id'=> $salon_id));
        if(empty($salon)){
             $response = array('message'=> 'salon id wrong','code'=> 400);
             echo json_encode($response);
             return false;
        }
        $booking =  $Commn->get_row_data('salon_booking',array('id'=> $booking_id));
        if(empty($salon)){
             $response = array('message'=> 'booking id wrong','code'=> 400);
             echo json_encode($response);
             return false;
        }
        // $already_review =  $Commn->get_row_data('salon-review',array('user_id' => $user_id, 'salon_id' => $salon_id));
        $already_review =  $Commn->get_row_data('salon-review',array('booking_id' => $booking_id));
        if(isset($already_review) && !empty($already_review)){
             $response = array('message'=> 'already added review','code'=> 400);
             echo json_encode($response);
             return false;    
        }
        $review = array('star_review' => $star, 'user_id' => $user_id, 'salon_id' => $salon_id, 'status' => 1,'booking_id' => $booking_id);
        $added_review = $Commn->insert_data('salon-review', $review);
        if($added_review == 1){
             $update_status_review = $Commn->update_data('salon_booking', array('ratting_status' => 1,'ratting' => $star), array('id'=> $booking_id));
            $response = array('message'=> 'added review','code'=> 200);
            echo json_encode($response);    
        }else{
            $response = array('message'=> 'somthing wrong','code'=> 400);
            echo json_encode($response);    
        }
    }
}
?>
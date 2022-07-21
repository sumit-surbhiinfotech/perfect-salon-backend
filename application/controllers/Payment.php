<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-Type: application/json; charset=utf-8');
class Payment extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('commn');
    }
    
    
    public function payment_success(){
        $Commn = new Commn();
        
        $user_id =  $this->input->post('user_id');
        $salon_id =  $this->input->post('salon_id');
        $booking_id =  $this->input->post('booking_id');
        $payment_id =  $this->input->post('payment_id');
        $os =  $this->input->post('os');
        $payment_timestrap =  $this->input->post('payment_timestrap');
        $amount =  $this->input->post('amount');
        $date =  date('Y-m-d H:i:s', $payment_timestrap);
        $payment_mode =  $this->input->post('payment_mode');
        
        if($user_id == ''){
            $response = array('message'=> 'User_id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($salon_id == ''){
            $response = array('message'=> 'Salon id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($booking_id == ''){
            $response = array('message'=> 'Booking ID field is required','code'=> 400);
            echo json_encode($response);
            return false;   
        }
        if($payment_id == ''){
            $response = array('message'=> 'Payment ID field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }        
        if($os == ''){
            $response = array('message'=> 'OS field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($payment_timestrap == ''){
            $response = array('message'=> 'Payment Timestrap field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($amount == ''){
            $response = array('message'=> 'Amount field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($payment_mode == ''){
            $response = array('message'=> 'Payment Mode field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user =  $Commn->get_row_data('users', array('id'=> $user_id,'role' => 1));
        if(empty($user)){
            $response = array('message'=> 'User ID is Wrong','code'=> 400);
            echo json_encode($response);
            return false;            
        }
        $salon =  $Commn->get_row_data('salon-list', array('id'=> $salon_id));
        if(empty($salon)){
            $response = array('message'=> 'Salon ID is Wrong','code'=> 400);
            echo json_encode($response);
            return false;            
        }
        $booking =  $Commn->get_row_data('salon_booking', array('id'=> $booking_id));
        if(empty($booking)){
            $response = array('message'=> 'Booking ID is Wrong','code'=> 400);
            echo json_encode($response);
            return false;            
        }
        
        $payment = array(
        	'user_id' => $user_id,
        	'salon_id' => $salon_id,
        	'booking_id' => $booking_id,
        	'payment_id' => $payment_id,
        	'os' => $os,
        	'payment_timestrap' => $payment_timestrap,
        	'amount' => $amount,
        	'payment_msg' => "Payment is Success",
        );
        $payment_success =  $Commn->insert_data('payment_transaction', $payment);
        if($payment_success == 1){
            $update_payment_status =  $Commn->update_data('salon_booking', array('payment_mode' => $payment_mode,'is_payment' => 1),array('id' => $booking_id));
            // echo $this->db->last_query();
            if($update_payment_status == 1){
                $response = array('message'=> 'Successfully Add payment history','code'=> 200);
                echo json_encode($response);
                return false;    
            }
        }else{
            $response = array('message'=> 'Somthing Wrong','code'=> 400);
            echo json_encode($response);
            return false;     
        }

    }
    public function payment_fail(){
        $Commn = new Commn();
        
        $user_id =  $this->input->post('user_id');
        $salon_id =  $this->input->post('salon_id');
        $key =  $this->input->post('key');
        $local_payment_id =  $this->input->post('local_payment_id');
        $payment_id =  $this->input->post('payment_id');
        $os =  $this->input->post('os');
        $local_order_id =  $this->input->post('local_order_id');
        $payment_timestrap =  $this->input->post('payment_timestrap');
        $amount =  $this->input->post('amount');
        $currency =  $this->input->post('currency');
        $booking_id =  $this->input->post('booking_id');
        $date =  date('Y-m-d H:i:s', $payment_timestrap);
        
        if($user_id == ''){
            $response = array('message'=> 'User_id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($salon_id == ''){
            $response = array('message'=> 'Salon id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($booking_id == ''){
            $response = array('message'=> 'Booking ID field is required','code'=> 400);
            echo json_encode($response);
            return false;   
        }
        if($key == ''){
            $response = array('message'=> 'Key field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($local_payment_id == ''){
            $response = array('message'=> 'Local Payment ID field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($payment_id == ''){
            $response = array('message'=> 'Payment ID field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }        
        if($os == ''){
            $response = array('message'=> 'OS field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($local_order_id == ''){
            $response = array('message'=> 'Local Order ID field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($payment_timestrap == ''){
            $response = array('message'=> 'Payment Timestrap field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($amount == ''){
            $response = array('message'=> 'Amount field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($currency == ''){
            $response = array('message'=> 'Currency field is required','code'=> 400);
            echo json_encode($response);
            return false;   
        }
        $user =  $Commn->get_row_data('users', array('id'=> $user_id,'role' => 1));
        if(empty($user)){
            $response = array('message'=> 'User ID is Wrong','code'=> 400);
            echo json_encode($response);
            return false;            
        }
        $salon =  $Commn->get_row_data('salon-list', array('id'=> $salon_id));
        if(empty($salon)){
            $response = array('message'=> 'Salon ID is Wrong','code'=> 400);
            echo json_encode($response);
            return false;            
        }
        $booking =  $Commn->get_row_data('salon_booking', array('id'=> $booking_id));
        if(empty($booking)){
            $response = array('message'=> 'Booking ID is Wrong','code'=> 400);
            echo json_encode($response);
            return false;            
        }
        
        $payment = array(
        	'user_id' => $user_id,
        	'salon_id' => $salon_id,
        	'booking_id' => $booking_id,
        	'key' => $key,
        	'local_payment_id' => $local_payment_id,
        	'payment_id' => $payment_id,
        	'os' => $os,
        	'local_order_id' => $local_order_id,
        	'payment_timestrap' => $payment_timestrap,
        	'amount' => $amount,
        	'currency' => $currency,
        	'payment_msg' => "Payment is Success",
        	'date' => $date
        );
        $payment_success =  $Commn->insert_data('payment_transaction', $payment);
        if($payment_success == 1){
            $response = array('message'=> 'Successfully Add payment history','code'=> 200);
            echo json_encode($response);
            return false;
        }else{
            $response = array('message'=> 'Somthing Wrong','code'=> 400);
            echo json_encode($response);
            return false;     
        }

    }
}
?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-Type: application/json; charset=utf-8');
// date_default_timezone_set('Asia/Kolkata');
class Booking extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('commn');
    }
    
    public function get_time_zone(){
        echo date_default_timezone_get();
        
        echo date("Y-m-d H:i:s");
    }
    public function booking(){
        // date_default_timezone_set("Asia/Kolkata");
        $user_id =  $this->input->post('user_id');
        $salon_id =  $this->input->post('salon_id');
        $service_id =  $this->input->post('service_id');
        $booking_date =  $this->input->post('booking_date');
        
        $booking_time =  $this->input->post('booking_time');
        $total_item =  $this->input->post('total_item');
        $total_amount =  $this->input->post('total_amount');
        $cgst =  $this->input->post('cgst');
        $sgst =  $this->input->post('sgst');
        $total_pay =  $this->input->post('total_pay');
        
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
       if($service_id == ''){
            $response = array('message'=> 'Service ID field is required','code'=> 400);
            echo json_encode($response);
            return false;
       }
       if($booking_date == ''){
            $response = array('message'=> 'Booking Date field is required','code'=> 400);
            echo json_encode($response);
            return false;
       }
       if($booking_time == ''){
            $response = array('message'=> 'Booking Time field is required','code'=> 400);
            echo json_encode($response);
            return false;
       }
       if($total_item == ''){
            $response = array('message'=> 'Total Item field is required','code'=> 400);
            echo json_encode($response);
            return false;
       }
       if($total_amount == ''){
            $response = array('message'=> 'Total amount field is required','code'=> 400);
            echo json_encode($response);
            return false;
       }
       if($cgst == ''){
            $response = array('message'=> 'CGST field is required','code'=> 400);
            echo json_encode($response);
            return false;
       }
       if($sgst == ''){
            $response = array('message'=> 'SGST field is required','code'=> 400);
            echo json_encode($response);
            return false;
       }
       if($total_pay == ''){
            $response = array('message'=> 'Total pay field is required','code'=> 400);
            echo json_encode($response);
            return false;
       }
       $Commn = new Commn();
       $get_booking =  $Commn->get_row_data('salon_booking', array('booking_date'=> date('Y-m-d', strtotime($booking_date)),'booking_time'=> $booking_time,'booking_status' => 1));
       
    //   if(isset($get_booking) && !empty($get_booking)){
    //         $response = array('message'=> 'Already Booked Date','code'=> 400);
    //         echo json_encode($response);
    //         return false;  
    //   }
      
      $data = array(
        'user_id' => $user_id,
        'salon_id' => $salon_id,
        'service_id' => $service_id,
        'booking_date' => date('Y-m-d', strtotime($booking_date)),
        'booking_time' => $booking_time,
        'total_item' => $total_item,
        'total_amount' => $total_amount,
        'cgst' => $cgst,
        'sgst' => $sgst,
        'booking_status' => "1",
        'total_pay' => $total_pay,
        'created_at' => date('Y-m-d H:i:s')
      ); 
    //   print_r($data);
    //     die;
      $booking =  $Commn->insert_data('salon_booking', $data);
      
      if($booking == 1){
        $salon_booking_user =  $Commn->get_row_data('salon_booking_user', array('user_id'=> $user_id, 'salon_id' => $salon_id));
       
       if(isset($salon_booking_user) && !empty($salon_booking_user)){
           
       }else{
           $salon_booking_user_data = array('user_id' => $user_id, 'status' => 1, 'salon_id' => $salon_id);
           $Commn->insert_data('salon_booking_user', $salon_booking_user_data);
       }  
        $response = array('message'=> 'Booking request send to salon','code'=> 200);
        echo json_encode($response);  
      }
        
    }
    
    public function my_booking(){
        
        $user_id =  $this->input->post('user_id');
        
        if($user_id == ''){
            $response = array('message'=> 'User_id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        
        $Commn = new Commn();
        
        $get_my_bookings =  $Commn->get_my_booking($user_id);
        $my_booking_arr = array();
        if(isset($get_my_bookings) && !empty($get_my_bookings)){
            foreach($get_my_bookings as $get_my_booking){
                $booking_status = '';
                if($get_my_booking->booking_status == 1){
                    $booking_status = 'Request send to Salon';
                }
                if($get_my_booking->booking_status == 2){
                    $booking_status = 'Booking Accept';
                }
                if($get_my_booking->booking_status == 3){
                    $booking_status = 'Completed';
                }
                if($get_my_booking->booking_status == 4){
                    $booking_status = 'Rejected';
                }
                if($get_my_booking->booking_status == 5){
                    $booking_status = 'Cancelled';
                }
                $salon_user_id = $Commn->select_get_row_data('salon-list',array('id'=> $get_my_booking->salon_id),'user_id');
                $salon_token = $Commn->select_get_row_data('notification_token',array('user_id'=>$salon_user_id),'Token');
                if($get_my_booking->ratting == null || $get_my_booking->ratting == ""){
                    $get_my_booking->ratting = 0;
                }
                // echo '<pre>'.$get_my_booking->id;
                
                 $order_details = $Commn->get_row_data('order_no_tbl',array('booking_id' => $get_my_booking->id));
                $order_no = '';
                if(isset($order_details) && !empty($order_details)) {
                    $order_no = $order_details->order_no;  
                }
                // echo $get_my_booking->booking_is_payment;
                if($get_my_booking->booking_is_payment == 0){
                    $get_my_booking->booking_is_payment = 0;
                    $invoice = "";
                }else{ 
                    $get_my_booking->booking_is_payment = 1;
                    $invoice = "https://my-salon-app.surbhiinfotech.com/assets/PDF/INV_25.pdf";
                }
                
                 $invoice_details = $Commn->get_row_data('invoice',array('booking_id' => $get_my_booking->id));
                    // print_r($invoice_details);
                //  die;
                    $invoice_no = '';
                    if(isset($invoice_details) && !empty($invoice_details)) {
                        $invoice_no = $invoice_details->order_no;  
                    }
                $salon_name = $Commn->select_get_row_data('salon-list',array('id'=> $get_my_booking->salon_id),'salon_name');                    
                 $files = scandir('assets/usercreatePDF/'.$salon_name.'/'.$get_my_booking->booking_date);
                // echo $
                $get_file1 = '';
                if(isset($files) && !empty($files)){
                    foreach ($files as $file) {
                        // print_r($file);
                        // echo "INV_user_".$invoice_no.".pdf";
                        if (trim("INV_".$invoice_no.".pdf") == trim($file)) {
                            $get_file1 = base_url().'assets/usercreatePDF/'.$salon_name.'/'.$get_my_booking->booking_date.'/INV_'.$invoice_no.'.pdf';
                            // echo $get_file1;
                        }
                    }
                }
                    // die;
                $booking_arr = array( 
                    'id' => $get_my_booking->id,
                    'salon_id' => $get_my_booking->salon_id,
                    'user_id' => $get_my_booking->user_id,
                    'salon_name' => $get_my_booking->salon_name,
                    'booking_status' => $booking_status,
                    'booking_id' => 'INV_'.$invoice_no,
                    'booking_date' => date('l d, Y',strtotime($get_my_booking->booking_date)),
                    'booking_time' => $get_my_booking->booking_time,
                    'total_pay' => $get_my_booking->total_amount,
                    'ratting_status' =>  $get_my_booking->ratting_status,
                    'ratting' => $get_my_booking->ratting,
                    'is_payment' => $get_my_booking->booking_is_payment,
                    'invoice' => $get_file1,
                    'token' => $salon_token,
                    'order_no' => $order_no
                );
                //  sort($booking_arr);
                array_push($my_booking_arr, $booking_arr);
            }
        }
        // die;
       
        if(isset($my_booking_arr) && !empty($my_booking_arr)){
            $response = array('message'=> 'successfully get booking','code'=> 200,'salon'=> $my_booking_arr);
            echo json_encode($response);
        }else{
            $response = array('message'=> 'booking not found','code'=> 400);
            echo json_encode($response);
        }
       
    }
    
    public function cancel_booking(){
         $booking_id =  $this->input->post('booking_id');
         if($booking_id == ''){
            $response = array('message'=> 'booking_id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $Commn = new Commn();
        $get_booking =  $Commn->get_row_data('salon_booking', array('id'=> $booking_id));
        
        if(isset($get_booking) && !empty($get_booking)){  
            if($get_booking->booking_status == 5){
                $response = array('message'=> 'Already cancelled booking','code'=> 200);
                echo json_encode($response);
                return false;
            }
            if($get_booking->booking_status == 1 || $get_booking->booking_status == 2){
                $cancel_booking =  $Commn->update_data("salon_booking",array('booking_status' => 5),array('id'=> $booking_id));
                if($cancel_booking == 1){
                    $response = array('message'=> 'Successfully cancel booking','code'=> 200);
                    echo json_encode($response);
                    return false;  
                }else{
                    $response = array('message'=> 'please try again','code'=> 400);
                    echo json_encode($response);
                    return false; 
                }
            }
            
        }
    }
}
?>
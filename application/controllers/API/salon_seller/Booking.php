<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

date_default_timezone_set('Asia/Kolkata');

class Booking extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('commn');
    }
    public function new_booking_list(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');    
        
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        $booking_arr = array();
        if(isset($user_data) & !empty($user_data)){
            $new_bookings = $Commn->get_seller_booking($user_data->id);  
            
            $seller_id =  $Commn->select_get_row_data('salon-list',array('user_id'=> $user_data->id),'id');
               foreach($new_bookings as $booking){
                    $status = '';
                    if($booking->booking_status == 1){
                        $status = "Pending";
                    }
                    if($booking->booking_status == 2){
                        $status = "Accepted";
                    }
                    if($booking->booking_status == 3){
                        $status = "Completed";
                    }
                    if($booking->booking_status == 4){
                        $status = "Rejected";
                    }
                    if($booking->booking_status == 5){
                        $status = "Cancelled";
                    }
                    
                    $user_token = $Commn->select_get_row_data('notification_token',array('user_id'=> $booking->c_id),'Token');
			        $token =  isset($user_token) ? $user_token : '';
			       
                    $data = array(
                        'booking_id' => $booking->id,
                        'user_name' => $Commn->select_get_row_data('users', array('id' => $booking->c_id),'name'),
                        'status' => $status,
                        'total_amount' => $booking->total_amount,
                        'book_date' => date('M d, Y', strtotime($booking->booking_date)),
                        'booking_time' => $booking->booking_time,
                        'booking_date' => date("Y-m-d",strtotime($booking->created_at)),
                        'user_id' => $booking->c_id,
                        'salon_id' => $booking->salon_id,
                        'is_payment' =>$booking->is_payment,
                        'token' => $token
                    );    
                
                array_push($booking_arr, $data);  
               }
               if(isset($booking_arr) && !empty($booking_arr)){
                    $response = array('message'=> 'Get Booking List','code'=> 200,'booking' => $booking_arr);
                    echo json_encode($response);   
               }else{
                    $response = array('message'=> 'New Booking not found','code'=> 400);
                    echo json_encode($response);                   
               }
        }else{
                $response = array('message'=> 'Booking not found','code'=> 400);
                echo json_encode($response);
           }
    }
    public function booking_list(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        if(isset($user_data)){
           $seller_id =  $Commn->select_get_row_data('salon-list',array('user_id'=> $user_data->id),'id');
           $booking_list = $Commn->order_where_selectAll('salon_booking',array('salon_id' => $seller_id),'','id');
           
           $booking_arr = array();
           if(isset($booking_list) && !empty($booking_list)){
               foreach($booking_list as $booking){
                    $status = '';
                    if($booking->booking_status == 1){
                        $status = "Pending";
                    }
                    if($booking->booking_status == 2){
                        $status = "Accepted";
                    }
                    if($booking->booking_status == 3){
                        $status = "Completed";
                    }
                    if($booking->booking_status == 4){
                        $status = "Rejected";
                    }
                    if($booking->booking_status == 5){
                        $status = "Cancelled";
                    }
                    $order_details = $Commn->get_row_data('order_no_tbl',array('booking_id' => $booking->id));
                    $order_no = '';
                    if(isset($order_details) && !empty($order_details)) {
                        $order_no = $order_details->order_no;  
                    }
                    $files = scandir('assets/PDF/');
                    $get_file1 = '';
                    foreach ($files as $file) {
                        if (trim("INV_".$order_no.".pdf") == trim($file)) {
                            $get_file1 = base_url()."assets/PDF/INV_".$order_no.".pdf";
                            // echo $get_file1;
                        }
                    }
                    $user_token = $Commn->select_get_row_data('notification_token',array('user_id'=> $booking->user_id),'Token');
			        $token =  isset($user_token) ? $user_token : '';
                    $data = array(
                        'booking_id' => $booking->id,
                        'user_name' => $Commn->select_get_row_data('users', array('id' => $booking->user_id),'name'),
                        'status' => $status,
                        'total_amount' => $booking->total_amount,
                        'book_date' => date('M d, Y', strtotime($booking->booking_date)),
                        'booking_time' => $booking->booking_time,
                        'booking_date' => date("Y-m-d",strtotime($booking->created_at)),
                        'payment_mode' => $booking->payment_mode,
                        'user_id' => $booking->user_id,
                        'salon_id' => $booking->salon_id,
                        'is_payment' =>$booking->is_payment,
                        'token' => $token,
                        'order_no' => $order_no,
                        'invoice' => $get_file1
                    );    
                array_push($booking_arr, $data);  
               }
               if(isset($booking_arr) && !empty($booking_arr)){
                $response = array('message'=> 'Get Booking List','code'=> 200,'booking' => $booking_arr);
                echo json_encode($response);   
               }
           }else{
                $response = array('message'=> 'Booking not found','code'=> 400);
                echo json_encode($response);
           }
        }
    }
    
     public function booking_list_filter(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        $sort_key = $this->input->post('sort_key');
    
        $key = "";
        if(empty($sort_key)){
            $key = "today";
        }
         
        if($sort_key == "today"){
            $key = "today";
        }
        if($sort_key == "yesterday"){
            $key = "yesterday";
        }
        if($sort_key == "week"){
            $key = "this_week";
        }
        if($sort_key == "month"){
            $key = "this_month";
        }
        if($sort_key == "year"){
            $key = "this_year";
        }
        $from_date = '';
        $to_date = '';
        if($sort_key == "custom_date"){
            $key = "custom_date";
            $from_date = date('Y-m-d', strtotime($this->input->post('custom_from_date')));
            
            $to_date = date('Y-m-d', strtotime($this->input->post('custom_to_date')));
        }
      
    
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
         
        if(isset($user_data)){
           $seller_id =  $Commn->select_get_row_data('salon-list',array('user_id'=> $user_data->id),'id');
        //   $booking_list = $Commn->order_where_selectAll('salon_booking',array('salon_id' => $seller_id),'','id');
           
           $this->db->select('*');
           $this->db->where('salon_id',$seller_id);
            if($key == 'today'){
                $this->db->where('DATE(booking_date) =',date('Y-m-d'));
             }
             if($key == 'yesterday'){
                $this->db->where('DATE(booking_date) =',date('Y-m-d',strtotime("-1 days")));
             }
             if($key == 'this_week'){
                 
                $date_start = strtotime('-' . date('w') . ' days');
                $date_start = date('Y-m-d', $date_start);
                $day = date('w');
                // $week_start = date('Y-m-d', strtotime('-'.$day.' days'));
                $week_start = date('Y-m-d');
                $week_end = date('Y-m-d', strtotime('-'.$day.' days')); 
                $this->db->where('DATE(booking_date) >=', $week_end);
                $this->db->where('DATE(booking_date) <=', $week_start);
             }
             if($key == 'this_month'){
                $first=  date('Y-m-01');
                
                $last = date("Y-m-d");
          
                $this->db->where('DATE(booking_date) >=', $first);
                $this->db->where('DATE(booking_date) <=', $last);
             }
             if($key == 'this_year'){
                $first = date("Y-01-01");
                $last = date("Y-12-31");
            
                $this->db->where('DATE(booking_date) >=', $first);
                $this->db->where('DATE(booking_date) <=', $last);
             }
             if($key == 'custom_date'){
                $this->db->where('DATE(booking_date) >=', $from_date);
                $this->db->where('DATE(booking_date) <=', $to_date);
             }
             $data =  $this->db->get('salon_booking')->result();
         
           $booking_arr = array();
           if(!empty($data)){
               foreach($data as $booking){
                    $status = '';
                    if($booking->booking_status == 1){
                        $status = "Pending";
                    }
                    if($booking->booking_status == 2){
                        $status = "Accepted";
                    }
                    if($booking->booking_status == 3){
                        $status = "Completed";
                    }
                    if($booking->booking_status == 4){
                        $status = "Rejected";
                    }
                    if($booking->booking_status == 5){
                        $status = "Cancelled";
                    }
                    $order_details = $Commn->get_row_data('order_no_tbl',array('booking_id' => $booking->id));
                    $order_no = '';
                    if(isset($order_details) && !empty($order_details)) {
                        $order_no = $order_details->order_no;  
                    }
                    $files = scandir('assets/PDF/');
                    $get_file1 = '';
                    foreach ($files as $file) {
                        if (trim("INV_".$order_no.".pdf") == trim($file)) {
                            $get_file1 = base_url()."assets/PDF/INV_".$order_no.".pdf";
                            // echo $get_file1;
                        }
                    }
                    $user_token = $Commn->select_get_row_data('notification_token',array('user_id'=> $booking->user_id),'Token');
			        $token =  isset($user_token) ? $user_token : '';
                    $data = array(
                        'booking_id' => $booking->id,
                        'user_name' => $Commn->select_get_row_data('users', array('id' => $booking->user_id),'name'),
                        'status' => $status,
                        'total_amount' => $booking->total_amount,
                        'book_date' => date('M d, Y', strtotime($booking->booking_date)),
                        'booking_time' => $booking->booking_time,
                        'booking_date' => date("Y-m-d",strtotime($booking->created_at)),
                        'payment_mode' => $booking->payment_mode,
                        'user_id' => $booking->user_id,
                        'salon_id' => $booking->salon_id,
                        'is_payment' =>$booking->is_payment,
                        'token' => $token,
                        'order_no' => $order_no,
                        'invoice' => $get_file1
                    );    
                array_push($booking_arr, $data);  
               }
           
               if(isset($booking_arr) && !empty($booking_arr)){
                $response = array('message'=> 'Get Booking List','code'=> 200,'booking' => $booking_arr);
                echo json_encode($response);   
               }
           }else{
                $response = array('message'=> 'Booking not found','code'=> 400);
                echo json_encode($response);
           }
        }
    }
    
    public function booking_accepted(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        if(isset($user_data)){
           $seller_id =  $Commn->select_get_row_data('salon-list',array('user_id'=> $user_data->id),'id');
        //   $booking_list = $Commn->where_selectAll('salon_booking',array('salon_id' => $seller_id ,'booking_status' => 2),'');
           $booking_list = $Commn->order_where_selectAll('salon_booking',array('salon_id' => $seller_id,'booking_status' => 2),'','id');
           
           $booking_arr = array();
           if(isset($booking_list) && !empty($booking_list)){
               foreach($booking_list as $booking){
                    $status = '';
                    if($booking->booking_status == 1){
                        $status = "Pending";
                    }
                    if($booking->booking_status == 2){
                        $status = "Accepted";
                    }
                    if($booking->booking_status == 3){
                        $status = "Completed";
                    }
                    if($booking->booking_status == 4){
                        $status = "Rejected";
                    }
                    if($booking->booking_status == 5){
                        $status = "Cancelled";
                    }
                    $order_details = $Commn->get_row_data('order_no_tbl',array('booking_id' => $booking->id));
                    $order_no = '';
                    if(isset($order_details) && !empty($order_details)) {
                        $order_no = $order_details->order_no;  
                    }
                    $user_token = $Commn->select_get_row_data('notification_token',array('user_id'=> $booking->user_id),'Token');
			        $token =  isset($user_token) ? $user_token : '';
                    $data = array(
                        'booking_id' => $booking->id,
                        'user_name' => $Commn->select_get_row_data('users', array('id' => $booking->user_id),'name'),
                        'status' => $status,
                        'total_amount' => $booking->total_amount,
                        'book_date' => date('M d, Y', strtotime($booking->booking_date)),
                        'booking_time' => $booking->booking_time,
                        'booking_date' => date("Y-m-d",strtotime($booking->created_at)),
                        'user_id' => $booking->user_id,
                        'salon_id' => $booking->salon_id,
                        'is_payment' =>$booking->is_payment,
                        'token' => $token,
                        'order_no' => $order_no
                    );    
                array_push($booking_arr, $data);  
               }
               if(isset($booking_arr) && !empty($booking_arr)){
                $response = array('message'=> 'Get Accepeted Booking List','code'=> 200,'booking' => $booking_arr);
                echo json_encode($response);   
               }
           }else{
                $response = array('message'=> 'Booking not found','code'=> 400);
                echo json_encode($response);
           }
        }
    }
    
    public function booking_completed(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        if(isset($user_data)){
           $seller_id =  $Commn->select_get_row_data('salon-list',array('user_id'=> $user_data->id),'id');
        //   $booking_list = $Commn->where_selectAll('salon_booking',array('salon_id' => $seller_id ,'booking_status' => 3),'');
        $booking_list = $Commn->order_where_selectAll('salon_booking',array('salon_id' => $seller_id,'booking_status' => 3),'','id');
           $booking_arr = array();
           if(isset($booking_list) && !empty($booking_list)){
               foreach($booking_list as $booking){
                    $status = '';
                    // echo base_url()."assets/PDF/INV_".$booking->id.".pdf";
                    if($booking->booking_status == 1){
                        $status = "Pending";
                    }
                    if($booking->booking_status == 2){
                        $status = "Accepted";
                    }
                    if($booking->booking_status == 3){
                        $status = "Completed";
                    }
                    if($booking->booking_status == 4){
                        $status = "Rejected";
                    }
                    if($booking->booking_status == 5){
                        $status = "Cancelled";
                    }
                    $salon_name = $Commn->select_get_row_data('salon-list',array('id'=> $booking->salon_id),'salon_name');
                    
                     $get_invoice = $Commn->get_row_data('invoice',array('booking_id'=>$booking->id));
                    $order_details = $Commn->get_row_data('order_no_tbl',array('booking_id' => $booking->id));
                   
                    $order_no = '';
                    if(isset($order_details) && !empty($order_details)) {
                        $order_no = $order_details->order_no;  
                    }
                    
                    // echo $order_no . ' -- ' . $booking->id .'<br>';
                    $invoice_details = $Commn->get_row_data('invoice',array('booking_id' => $booking->id));
                    
                    // echo "<pre>";print_r($invoice_details);
                    
                    $invoice_no = '';
                    if(isset($invoice_details) && !empty($invoice_details)) {
                        $invoice_no = $invoice_details->order_no;  
                    }
                    // echo "invoice_no => ".$invoice_no.'  ==> booking_id ==> '.$booking->id.'\n';
                 $files = scandir('assets/PDF/'.$salon_name.'/'.$booking->booking_date);
                
                    $get_file1 = '';
                if(isset($files) && !empty($files)){
                    foreach ($files as $file) {
                       
                        if (trim("INV_".$invoice_no.".pdf") == trim($file)) {
                            $get_file1 = base_url()."assets/PDF/".$salon_name."/".$booking->booking_date ."/INV_".$invoice_no.".pdf";
                            // echo $get_file1;
                        }
                    }
                }
              
                $user_token = $Commn->select_get_row_data('notification_token',array('user_id'=> $booking->user_id),'Token');
			        $token =  isset($user_token) ? $user_token : '';
                    $data = array(
                        'booking_id' => $booking->id,
                        'user_name' => $Commn->select_get_row_data('users', array('id' => $booking->user_id),'name'),
                        'status' => $status,
                        'total_amount' => $booking->total_amount,
                        'book_date' => date('M d, Y', strtotime($booking->booking_date)),
                        'booking_time' => $booking->booking_time,
                        'booking_date' => date("Y-m-d",strtotime($booking->created_at)),
                        'user_id' => $booking->user_id,
                        'salon_id' => $booking->salon_id,
                        'invoice' => $get_file1,
                        'is_payment' =>$booking->is_payment,
                        'token' => $token,
                        'order_no' => $order_no
                    );  
                  
                array_push($booking_arr, $data);  
               }
               if(isset($booking_arr) && !empty($booking_arr)){
                $response = array('message'=> 'Get Completed Booking List','code'=> 200,'booking' => $booking_arr);
                echo json_encode($response);   
               }
           }else{
                $response = array('message'=> 'Booking not found','code'=> 400);
                echo json_encode($response);
           }
        }
    }
    
    public function booking_completed_filter(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        $sort_key = $this->input->post('sort_key');
        
        $key = "";
        if(empty($sort_key)){
            $key = "today";
        }
         
        if($sort_key == "today"){
            $key = "today";
        }
        if($sort_key == "yesterday"){
            $key = "yesterday";
        }
        if($sort_key == "week"){
            $key = "this_week";
        }
        if($sort_key == "month"){
            $key = "this_month";
        }
        if($sort_key == "year"){
            $key = "this_year";
        }
        if($sort_key == "custom_date"){
            $key = "custom_date";
        }
      
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        
        if(isset($user_data)){
           $seller_id =  $Commn->select_get_row_data('salon-list',array('user_id'=> $user_data->id),'id');
           $this->db->select('*');
           $this->db->where('salon_id',$seller_id);
            if($key == 'today'){
                $this->db->where('DATE(booking_date) =',date('Y-m-d'));
             }
             if($key == 'yesterday'){
                $this->db->where('DATE(booking_date) =',date('Y-m-d',strtotime("-1 days")));
             }
             if($key == 'this_week'){
                 
                $date_start = strtotime('-' . date('w') . ' days');
                $date_start = date('Y-m-d', $date_start);
                $day = date('w');
                // $week_start = date('Y-m-d', strtotime('-'.$day.' days'));
                $week_start = date('Y-m-d');
                $week_end = date('Y-m-d', strtotime('-'.$day.' days')); 
                $this->db->where('DATE(booking_date) >=', $week_end);
                $this->db->where('DATE(booking_date) <=', $week_start);
             }
             if($key == 'this_month'){
                $first=  date('Y-m-01');
                
                $last = date("Y-m-d");
          
                $this->db->where('DATE(booking_date) >=', $first);
                $this->db->where('DATE(booking_date) <=', $last);
             }
             if($key == 'this_year'){
                $first = date("Y-01-01");
                $last = date("Y-12-31");
            
                $this->db->where('DATE(booking_date) >=', $first);
                $this->db->where('DATE(booking_date) <=', $last);
             }
             if($key == 'custom_date'){
                $this->db->where('DATE(booking_date) >=', $this->input->post('custom_from_date'));
                $this->db->where('DATE(booking_date) <=', $this->input->post('custom_to_date'));
             }
             $this->db->where('booking_status', 3);
             $booking_list =  $this->db->get('salon_booking')->result();
            // echo $this->db->last_query();
            //  die;
        //   $booking_list = $Commn->where_selectAll('salon_booking',array('salon_id' => $seller_id ,'booking_status' => 3),'');
     //   $booking_list = $Commn->order_where_selectAll('salon_booking',array('salon_id' => $seller_id,'booking_status' => 3),'','id');
           $booking_arr = array();
           if(isset($booking_list) && !empty($booking_list)){
               foreach($booking_list as $booking){
                     
                    $status = '';
                    // echo base_url()."assets/PDF/INV_".$booking->id.".pdf";
                    if($booking->booking_status == 1){
                        $status = "Pending";
                    }
                    if($booking->booking_status == 2){
                        $status = "Accepted";
                    }
                    if($booking->booking_status == 3){
                        $status = "Completed";
                    }
                    if($booking->booking_status == 4){
                        $status = "Rejected";
                    }
                    if($booking->booking_status == 5){
                        $status = "Cancelled";
                    }
                    $order_details = $Commn->get_row_data('order_no_tbl',array('booking_id' => $booking->id));
                    $order_no = '';
                    if(isset($order_details) && !empty($order_details)) {
                        $order_no = $order_details->order_no;  
                    }
                    
                    $files = scandir('assets/PDF/');
                    
                    $get_invoice = $Commn->get_row_data('invoice',array('booking_id'=>$booking->id));
                    // print_r($get_invoice->order_no);
                    // echo $this->db->last_query();
                    $order_no = '';
                    if(isset($get_invoice) && !empty($get_invoice)){
                        $order_no = $get_invoice->id;     
                    }else{
                        $order_no = '';
                    }
                    // print_r($files);
                    // die;
                    $get_file1 = '';
                    foreach ($files as $file) {
                        if (trim("INV_".$order_no.".pdf") == trim($file)) {
                            $get_file1 = base_url()."assets/PDF/INV_".$order_no.".pdf";
                            // echo $get_file1;
                        }
                    }
                    
                    
                $user_token = $Commn->select_get_row_data('notification_token',array('user_id'=> $booking->user_id),'Token');
			        $token =  isset($user_token) ? $user_token : '';
                    $data = array(
                        'booking_id' => $booking->id,
                        'user_name' => $Commn->select_get_row_data('users', array('id' => $booking->user_id),'name'),
                        'status' => $status,
                        'total_amount' => $booking->total_amount,
                        'book_date' => date('M d, Y', strtotime($booking->booking_date)),
                        'booking_time' => $booking->booking_time,
                        'booking_date' => date("Y-m-d",strtotime($booking->created_at)),
                        'user_id' => $booking->user_id,
                        'salon_id' => $booking->salon_id,
                        'invoice' => $get_file1,
                        'is_payment' =>$booking->is_payment,
                        'token' => $token,
                        'order_no' => $order_no
                    );    
                array_push($booking_arr, $data);  
               }
                //   die;
               if(isset($booking_arr) && !empty($booking_arr)){
                $response = array('message'=> 'Get Completed Booking List','code'=> 200,'booking' => $booking_arr);
                echo json_encode($response);   
               }
           }else{
                $response = array('message'=> 'Booking not found','code'=> 400);
                echo json_encode($response);
           }
        }
    }
    
    public function booking_rejected(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        if(isset($user_data)){
           $seller_id =  $Commn->select_get_row_data('salon-list',array('user_id'=> $user_data->id),'id');
        //   $booking_list = $Commn->where_selectAll('salon_booking',array('salon_id' => $seller_id ,'booking_status' => 4),'');
           $booking_list = $Commn->order_where_selectAll('salon_booking',array('salon_id' => $seller_id,'booking_status' => 4),'','id');
           $booking_arr = array();
           if(isset($booking_list) && !empty($booking_list)){
               foreach($booking_list as $booking){
                    $status = '';
                    if($booking->booking_status == 1){
                        $status = "Pending";
                    }
                    if($booking->booking_status == 2){
                        $status = "Accepted";
                    }
                    if($booking->booking_status == 3){
                        $status = "Completed";
                    }
                    if($booking->booking_status == 4){
                        $status = "Rejected";
                    }
                    if($booking->booking_status == 5){
                        $status = "Cancelled";
                    }
                    $user_token = $Commn->select_get_row_data('notification_token',array('user_id'=> $booking->user_id),'Token');
			        $token =  isset($user_token) ? $user_token : '';
			        
			         $get_invoice = $Commn->get_row_data('invoice',array('booking_id'=>$booking->id));
                    $order_details = $Commn->get_row_data('order_no_tbl',array('booking_id' => $booking->id));
                    $order_no = '';
                    if(isset($order_details) && !empty($order_details)) {
                        $order_no = $order_details->order_no; 
                    }
                    // $order_no = '';
                    // if(isset($get_invoice) && !empty($get_invoice)){
                    //     $order_no = $get_invoice->id;     
                    // }else{
                    //     $order_no = '';
                    // }
                    $data = array(
                        'booking_id' => $booking->id,
                        'user_name' => $Commn->select_get_row_data('users', array('id' => $booking->user_id),'name'),
                        'status' => $status,
                        'total_amount' => $booking->total_amount,
                        'book_date' => date('M d, Y', strtotime($booking->booking_date)),
                        'booking_time' => $booking->booking_time,
                        'booking_date' => date("Y-m-d",strtotime($booking->created_at)),
                        'user_id' => $booking->user_id,
                        'salon_id' => $booking->salon_id,
                        'is_payment' =>$booking->is_payment,
                        'token' => $token,
                        'order_no' => $order_no
                    );    
                array_push($booking_arr, $data);  
               }
            //   die;
               if(isset($booking_arr) && !empty($booking_arr)){
                $response = array('message'=> 'Get Rejected Booking List','code'=> 200,'booking' => $booking_arr);
                echo json_encode($response);   
               }
           }else{
                $response = array('message'=> 'Booking not found','code'=> 400);
                echo json_encode($response);
           }
        }
    }
    
    public function booking_rejected_filter(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        $sort_key = $this->input->post('sort_key');
      
        $key = "";
        if(empty($sort_key)){
            $key = "today";
        }
         
        if($sort_key == "today"){
            $key = "today";
        }
        if($sort_key == "yesterday"){
            $key = "yesterday";
        }
        if($sort_key == "week"){
            $key = "this_week";
        }
        if($sort_key == "month"){
            $key = "this_month";
        }
        if($sort_key == "year"){
            $key = "this_year";
        }
        if($sort_key == "custom_date"){
            $key = "custom_date";
        }
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        if(isset($user_data)){
           $seller_id =  $Commn->select_get_row_data('salon-list',array('user_id'=> $user_data->id),'id');
           $this->db->select('*');
           $this->db->where('salon_id',$seller_id);
            if($key == 'today'){
                $this->db->where('DATE(booking_date) =',date('Y-m-d'));
             }
             if($key == 'yesterday'){
                $this->db->where('DATE(booking_date) =',date('Y-m-d',strtotime("-1 days")));
             }
             if($key == 'this_week'){
                 
                $date_start = strtotime('-' . date('w') . ' days');
                $date_start = date('Y-m-d', $date_start);
                $day = date('w');
                // $week_start = date('Y-m-d', strtotime('-'.$day.' days'));
                $week_start = date('Y-m-d');
                $week_end = date('Y-m-d', strtotime('-'.$day.' days')); 
                $this->db->where('DATE(booking_date) >=', $week_end);
                $this->db->where('DATE(booking_date) <=', $week_start);
             }
             if($key == 'this_month'){
                $first=  date('Y-m-01');
                
                $last = date("Y-m-d");
          
                $this->db->where('DATE(booking_date) >=', $first);
                $this->db->where('DATE(booking_date) <=', $last);
             }
             if($key == 'this_year'){
                $first = date("Y-01-01");
                $last = date("Y-12-31");
            
                $this->db->where('DATE(booking_date) >=', $first);
                $this->db->where('DATE(booking_date) <=', $last);
             }
             if($key == 'custom_date'){
                $this->db->where('DATE(booking_date) >=', $this->input->post('custom_from_date'));
                $this->db->where('DATE(booking_date) <=', $this->input->post('custom_to_date'));
             }
             $this->db->where('booking_status', 4);
             $booking_list =  $this->db->get('salon_booking')->result();
             
            
        //   $booking_list = $Commn->where_selectAll('salon_booking',array('salon_id' => $seller_id ,'booking_status' => 4),'');
         //  $booking_list = $Commn->order_where_selectAll('salon_booking',array('salon_id' => $seller_id,'booking_status' => 4),'','id');
           $booking_arr = array();
           if(isset($booking_list) && !empty($booking_list)){
               foreach($booking_list as $booking){
                    $status = '';
                    if($booking->booking_status == 1){
                        $status = "Pending";
                    }
                    if($booking->booking_status == 2){
                        $status = "Accepted";
                    }
                    if($booking->booking_status == 3){
                        $status = "Completed";
                    }
                    if($booking->booking_status == 4){
                        $status = "Rejected";
                    }
                    if($booking->booking_status == 5){
                        $status = "Cancelled";
                    }
                    $order_details = $Commn->get_row_data('order_no_tbl',array('booking_id' => $booking->id));
                    $order_no = '';
                    if(isset($order_details) && !empty($order_details)) {
                        $order_no = $order_details->order_no;  
                    }
                    $user_token = $Commn->select_get_row_data('notification_token',array('user_id'=> $booking->user_id),'Token');
			        $token =  isset($user_token) ? $user_token : '';
                    $data = array(
                        'booking_id' => $booking->id,
                        'user_name' => $Commn->select_get_row_data('users', array('id' => $booking->user_id),'name'),
                        'status' => $status,
                        'total_amount' => $booking->total_amount,
                        'book_date' => date('M d, Y', strtotime($booking->booking_date)),
                        'booking_time' => $booking->booking_time,
                        'booking_date' => date("Y-m-d",strtotime($booking->created_at)),
                        'user_id' => $booking->user_id,
                        'salon_id' => $booking->salon_id,
                        'is_payment' =>$booking->is_payment,
                        'token' => $token,
                        'order_no' => $order_no
                    );    
                array_push($booking_arr, $data);  
               }
               if(isset($booking_arr) && !empty($booking_arr)){
                $response = array('message'=> 'Get Rejected Booking List','code'=> 200,'booking' => $booking_arr);
                echo json_encode($response);   
               }
           }else{
                $response = array('message'=> 'Booking not found','code'=> 400);
                echo json_encode($response);
           }
        }
    }
    
    public function booking_cancel(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        if(isset($user_data)){
           $seller_id =  $Commn->select_get_row_data('salon-list',array('user_id'=> $user_data->id),'id');
        //   $booking_list = $Commn->where_selectAll('salon_booking',array('salon_id' => $seller_id ,'booking_status' => 5),'');
           $booking_list = $Commn->order_where_selectAll('salon_booking',array('salon_id' => $seller_id,'booking_status' => 5),'','id');
           $booking_arr = array();
           if(isset($booking_list) && !empty($booking_list)){
               foreach($booking_list as $booking){
                    $status = '';
                    if($booking->booking_status == 1){
                        $status = "Pending";
                    }
                    if($booking->booking_status == 2){
                        $status = "Accepted";
                    }
                    if($booking->booking_status == 3){
                        $status = "Completed";
                    }
                    if($booking->booking_status == 4){
                        $status = "Rejected";
                    }
                    if($booking->booking_status == 5){
                        $status = "Cancelled";
                    }
                    $order_details = $Commn->get_row_data('order_no_tbl',array('booking_id' => $booking->id));
                    $order_no = '';
                    if(isset($order_details) && !empty($order_details)) {
                        $order_no = $order_details->order_no;  
                    }
                     $user_token = $Commn->select_get_row_data('notification_token',array('user_id'=> $booking->user_id),'Token');
			        $token =  isset($user_token) ? $user_token : '';
                    $data = array(
                        'booking_id' => $booking->id,
                        'user_name' => $Commn->select_get_row_data('users', array('id' => $booking->user_id),'name'),
                        'status' => $status,
                        'total_amount' => $booking->total_amount,
                        'book_date' => date('M d, Y', strtotime($booking->booking_date)),
                        'booking_time' => $booking->booking_time,
                        'booking_date' => date("Y-m-d",strtotime($booking->created_at)),
                        'user_id' => $booking->user_id,
                        'salon_id' => $booking->salon_id,
                        'is_payment' =>$booking->is_payment,
                        'token' => $token,
                        'order_no' => $order_no
                    );    
                array_push($booking_arr, $data);  
               }
               if(isset($booking_arr) && !empty($booking_arr)){
                $response = array('message'=> 'Get Cancel Booking List','code'=> 200,'booking' => $booking_arr);
                echo json_encode($response);   
               }
           }else{
                $response = array('message'=> 'Booking not found','code'=> 400);
                echo json_encode($response);
           }
        }
    }
    
    public function booking_cancel_filter(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        $sort_key = $this->input->post('sort_key');
      
        $key = "";
        if(empty($sort_key)){
            $key = "today";
        }
         
        if($sort_key == "today"){
            $key = "today";
        }
        if($sort_key == "yesterday"){
            $key = "yesterday";
        }
        if($sort_key == "week"){
            $key = "this_week";
        }
        if($sort_key == "month"){
            $key = "this_month";
        }
        if($sort_key == "year"){
            $key = "this_year";
        }
        if($sort_key == "custom_date"){
            $key = "custom_date";
        }
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        if(isset($user_data)){
           $seller_id =  $Commn->select_get_row_data('salon-list',array('user_id'=> $user_data->id),'id');
           $this->db->select('*');
           $this->db->where('salon_id',$seller_id);
            if($key == 'today'){
                $this->db->where('DATE(booking_date) =',date('Y-m-d'));
             }
             if($key == 'yesterday'){
                $this->db->where('DATE(booking_date) =',date('Y-m-d',strtotime("-1 days")));
             }
             if($key == 'this_week'){
                 
                $date_start = strtotime('-' . date('w') . ' days');
                $date_start = date('Y-m-d', $date_start);
                $day = date('w');
                // $week_start = date('Y-m-d', strtotime('-'.$day.' days'));
                $week_start = date('Y-m-d');
                $week_end = date('Y-m-d', strtotime('-'.$day.' days')); 
                $this->db->where('DATE(booking_date) >=', $week_end);
                $this->db->where('DATE(booking_date) <=', $week_start);
             }
             if($key == 'this_month'){
                $first=  date('Y-m-01');
                
                $last = date("Y-m-d");
          
                $this->db->where('DATE(booking_date) >=', $first);
                $this->db->where('DATE(booking_date) <=', $last);
             }
             if($key == 'this_year'){
                $first = date("Y-01-01");
                $last = date("Y-12-31");
            
                $this->db->where('DATE(booking_date) >=', $first);
                $this->db->where('DATE(booking_date) <=', $last);
             }
             if($key == 'custom_date'){
                $this->db->where('DATE(booking_date) >=', $this->input->post('custom_from_date'));
                $this->db->where('DATE(booking_date) <=', $this->input->post('custom_to_date'));
             }
             $this->db->where('booking_status', 5);
             $booking_list =  $this->db->get('salon_booking')->result();
        //   $booking_list = $Commn->where_selectAll('salon_booking',array('salon_id' => $seller_id ,'booking_status' => 5),'');
        //   $booking_list = $Commn->order_where_selectAll('salon_booking',array('salon_id' => $seller_id,'booking_status' => 5),'','id');
           $booking_arr = array();
           if(isset($booking_list) && !empty($booking_list)){
               foreach($booking_list as $booking){
                    $status = '';
                    if($booking->booking_status == 1){
                        $status = "Pending";
                    }
                    if($booking->booking_status == 2){
                        $status = "Accepted";
                    }
                    if($booking->booking_status == 3){
                        $status = "Completed";
                    }
                    if($booking->booking_status == 4){
                        $status = "Rejected";
                    }
                    if($booking->booking_status == 5){
                        $status = "Cancelled";
                    }
                    $order_details = $Commn->get_row_data('order_no_tbl',array('booking_id' => $booking->id));
                    $order_no = '';
                    if(isset($order_details) && !empty($order_details)) {
                        $order_no = $order_details->order_no;  
                    }
                     $user_token = $Commn->select_get_row_data('notification_token',array('user_id'=> $booking->user_id),'Token');
			        $token =  isset($user_token) ? $user_token : '';
                    $data = array(
                        'booking_id' => $booking->id,
                        'user_name' => $Commn->select_get_row_data('users', array('id' => $booking->user_id),'name'),
                        'status' => $status,
                        'total_amount' => $booking->total_amount,
                        'book_date' => date('M d, Y', strtotime($booking->booking_date)),
                        'booking_time' => $booking->booking_time,
                        'booking_date' => date("Y-m-d",strtotime($booking->created_at)),
                        'user_id' => $booking->user_id,
                        'salon_id' => $booking->salon_id,
                        'is_payment' =>$booking->is_payment,
                        'token' => $token,
                        'order_no' => $order_no
                    );    
                array_push($booking_arr, $data);  
               }
               if(isset($booking_arr) && !empty($booking_arr)){
                $response = array('message'=> 'Get Cancel Booking List','code'=> 200,'booking' => $booking_arr);
                echo json_encode($response);   
               }
           }else{
                $response = array('message'=> 'Booking not found','code'=> 400);
                echo json_encode($response);
           }
        }
    }
    
    public function change_booking_status(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        $booking_id = $this->input->post('booking_id');
        $booking_status = $this->input->post('booking_status');
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($booking_id == ''){
            $response = array('message'=> 'booking id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($booking_status == ''){
            $response = array('message'=> 'booking status field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($booking_status != 1 && $booking_status != 2 && $booking_status != 3 && $booking_status != 4 && $booking_status != 5){
            $response = array('message'=> 'booking status is wrong','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        if(isset($user_data)){
           $booking =  $Commn->get_row_data('salon_booking',array('id'=> $booking_id));
           if(isset($booking) && !empty($booking)){
                $update_status =  $Commn->update_data('salon_booking',array('booking_status'=>$booking_status),array('id'=> $booking_id));
                if($update_status == 1){
                    $booking =  $Commn->get_row_data('salon_booking',array('id'=> $booking_id));
                    if($booking->booking_status == 3){
                         $salon_id = $seller_user_id;
                            $invoice_tbl_data = $Commn->where_selectAll('invoice',array('salon_id'=> $salon_id),'');
                            if(isset($invoice_tbl_data) && !empty($invoice_tbl_data)){
                                $last_record = end($invoice_tbl_data);
                                
                                $data = array('salon_id'=>$salon_id,'order_no'=>(++$last_record->order_no),'booking_id' => $booking_id);
                                $result = $Commn->insert_data_new('invoice',$data); 
                        $this->invoice($booking_id,$result);
                        $this->invoice1($booking_id,$result);
                            }else {
                            $data = array('salon_id'=>$salon_id,'order_no'=>1,'booking_id' => $booking_id);
                            $result = $Commn->insert_data_new('invoice',$data);
                            $this->invoice($booking_id,$result);
                            $this->invoice1($booking_id,$result);
                          
                        }
                    }
                    $response = array('message'=> 'successfully change status','code'=> 200);
                    echo json_encode($response);
                }else{
                    $response = array('message'=> 'somthing wrong','code'=> 400);
                    echo json_encode($response);     
                }
           }else{
                $response = array('message'=> 'booking id is wrong','code'=> 400);
                echo json_encode($response);    
           }
        }else{
            $response = array('message'=> 'user not found','code'=> 400);
            echo json_encode($response);    
        } 
    }
    
    public function change_booking_status_new(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        $booking_id = $this->input->post('booking_id');
        
        $booking_status = $this->input->post('booking_status');
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($booking_id == ''){
            $response = array('message'=> 'booking id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($booking_status == ''){
            $response = array('message'=> 'booking status field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($booking_status != 1 && $booking_status != 2 && $booking_status != 3 && $booking_status != 4 && $booking_status != 5){
            $response = array('message'=> 'booking status is wrong','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
    
        if(isset($user_data)){
           $booking =  $Commn->get_row_data('salon_booking',array('id'=> $booking_id));
           
              $salon_name = $Commn->select_get_row_data('salon-list',array('id'=> $booking->salon_id),'salon_name');
            $filename = $salon_name.'/'.$booking->booking_date;
            // $date = str_replace( ':', '', $date);
            if (!is_dir('././assets/PDF/'.$filename)) {
            mkdir('././assets/PDF/' . $filename, 0777, TRUE);
            }
            if (!is_dir('././assets/usercreatePDF/'.$filename)) {
            mkdir('././assets/usercreatePDF/' . $filename, 0777, TRUE);
            }
           if(isset($booking) && !empty($booking)){
                $update_status =  $Commn->update_data('salon_booking',array('booking_status'=>$booking_status),array('id'=> $booking_id));
                if($update_status == 1){
                    $booking =  $Commn->get_row_data('salon_booking',array('id'=> $booking_id));
                    if($booking->booking_status == 2){
                        $salon_id = $seller_user_id;
                         $order_no_tbl_data = $Commn->where_selectAll('order_no_tbl',array('salon_id'=> $salon_id),'');
                         if(isset($order_no_tbl_data) && !empty($order_no_tbl_data)){
                            $last_record = end($order_no_tbl_data);
                            $data = array('salon_id'=>$salon_id,'order_no'=>(++$last_record->order_no),'booking_id' => $booking_id);
                            $result = $Commn->insert_data_new('order_no_tbl',$data); 
                         }else{
                            $data = array('salon_id'=>$salon_id,'order_no'=>1,'booking_id' => $booking_id);
                            $result = $Commn->insert_data_new('order_no_tbl',$data);
                          
                         }
                    }
                    if($booking->booking_status == 3){
                        $salon_id = $seller_user_id;
                        $invoice_tbl_data = $Commn->where_selectAll('invoice',array('salon_id'=> $salon_id),'');
                        if(isset($invoice_tbl_data) && !empty($invoice_tbl_data)){
                            $last_record = end($invoice_tbl_data);
                            
                            $data = array('salon_id'=>$salon_id,'order_no'=>(++$last_record->order_no),'booking_id' => $booking_id);
                            $result = $Commn->insert_data_new('invoice',$data); 
                           $order_no =  $Commn->get_row_data('order_no_tbl',array('salon_id'=>$salon_id,'booking_id'=> $booking_id));
                            //  echo $this->db->last_query();die;
                            // echo '<pre>';print_r($order_no);die;
                            // $last_insert_record = $Commn->get_row_data('invoice',array('id'=>$result));
                        // print_r($last_insert_record->order_no);
                        // die;
                        
                            $this->invoice($booking_id,$order_no->order_no);
                            $this->invoice1($booking_id,$order_no->order_no);
                        }else {
                            $data = array('salon_id'=>$salon_id,'order_no'=>1,'booking_id' => $booking_id);
                            $result = $Commn->insert_data_new('invoice',$data);
                            $order_no =  $Commn->get_row_data('invoice',array('id'=>$result));
                            $this->invoice($booking_id,$order_no->order_no);
                            $this->invoice1($booking_id,$order_no->order_no);
                          
                        }
                    }
                    $response = array('message'=> 'successfully change status','code'=> 200);
                    echo json_encode($response);    
                }else{
                    $response = array('message'=> 'somthing wrong','code'=> 400);
                    echo json_encode($response);     
                }
           }else{
                $response = array('message'=> 'booking id is wrong','code'=> 400);
                echo json_encode($response);    
           }
        }else{
            $response = array('message'=> 'user not found','code'=> 400);
            echo json_encode($response);    
        } 
    }
    
    public function view_booking(){
    $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        $booking_id = $this->input->post('booking_id');
        $booking_status = $this->input->post('booking_status');
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($booking_id == ''){
            $response = array('message'=> 'booking id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        if(isset($user_data)){
            $booking =  $Commn->get_row_data('salon_booking',array('id'=> $booking_id));
            if(isset($booking) && !empty($booking)){
                
            }
        }else{
            $response = array('message'=> 'user not found','code'=> 400);
            echo json_encode($response);    
        } 
    }    
    
    public function booking_all_customer(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        if(isset($user_data)){
            $seller_id =  $Commn->select_get_row_data('salon-list',array('user_id'=> $user_data->id),'id');
            // $all_users =  $Commn->where_selectAll('salon_booking',array('salon_id'=> $seller_id),'user_id');
             $all_users = $Commn->where_selectAll('salon_booking_user',array('salon_id'=> $seller_id),'');
            // $all_users =  array_map("unserialize", array_unique(array_map("serialize", $all_users)));
            $all_users_arr = array();
            if(isset($all_users)){
                foreach($all_users as $user){
                   $user_status = $Commn->select_get_row_data('salon_booking_user',array('user_id'=> $user->user_id,'salon_id'=>$seller_id),'status');
                   if(isset($user_status) && $user_status == 1){
                       $user_status = "active";
                   }else{$user_status = "Blocked";}
                   $data = array(
                        'id' => $user->user_id,
                        'name' => $Commn->select_get_row_data('users',array('id'=> $user->user_id),'name'),
                        'email' => $Commn->select_get_row_data('users',array('id'=> $user->user_id),'email'),
                        'phone' => $Commn->select_get_row_data('users',array('id'=> $user->user_id),'phone'),
                        'status' =>$user_status,
                        'total_booking' => count($Commn->where_selectAll('salon_booking',array('user_id'=> $user->user_id, 'salon_id'=> $seller_id),'')),
                        'completed_booking' => count($Commn->where_selectAll('salon_booking',array('user_id'=> $user->user_id,'booking_status'=> 3,'salon_id'=> $seller_id),'')),
                        'rejected_booking' =>  count($Commn->where_selectAll('salon_booking',array('user_id'=> $user->user_id,'booking_status'=> 4,'salon_id'=> $seller_id),'')),
                   );
                   array_push($all_users_arr, $data);
                }
                if(isset($all_users_arr) && !empty($all_users_arr)){
                    $response = array('message'=> 'Get all booking Customers','code'=> 200, 'customer'=> $all_users_arr);
                    echo json_encode($response);    
                }else{
                    $response = array('message'=> 'customer record not found','code'=> 400);
                    echo json_encode($response); 
                }
                 
            }else{
              $response = array('message'=> 'customer record not found','code'=> 400);
                echo json_encode($response);   
            }
            
        }else{
            $response = array('message'=> 'user not found','code'=> 400);
            echo json_encode($response);    
        }        
    }
    
    public function booking_active_customer(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        if(isset($user_data)){
            
            $seller_id =  $Commn->select_get_row_data('salon-list',array('user_id'=> $user_data->id),'id');
            $all_users = $Commn->where_selectAll('salon_booking_user',array('salon_id'=> $seller_id,'status'=> 1),'user_id');
            $all_users_arr = array();
            if(isset($all_users) && !empty($all_users)){
                foreach($all_users as $user){
                  $user_status = $Commn->select_get_row_data('salon_booking_user',array('user_id'=> $user->user_id,'salon_id'=>$seller_id),'status');
                  if(isset($user_status) && $user_status == 1){
                      $user_status = "active";
                  }else{$user_status = "Blocked";}
                  $data = array(
                        'id' => $user->user_id,
                        'name' => $Commn->select_get_row_data('users',array('id'=> $user->user_id),'name'),
                        'email' => $Commn->select_get_row_data('users',array('id'=> $user->user_id),'email'),
                        'phone' => $Commn->select_get_row_data('users',array('id'=> $user->user_id),'phone'),
                        'status' =>$user_status,
                        'total_booking' => count($Commn->where_selectAll('salon_booking',array('user_id'=> $user->user_id, 'salon_id'=> $seller_id),'')),
                        'completed_booking' => count($Commn->where_selectAll('salon_booking',array('user_id'=> $user->user_id,'booking_status'=> 3,'salon_id'=> $seller_id),'')),
                        'rejected_booking' =>  count($Commn->where_selectAll('salon_booking',array('user_id'=> $user->user_id,'booking_status'=> 4,'salon_id'=> $seller_id),'')),
                  );
                  array_push($all_users_arr, $data);
                }
                 $response = array('message'=> 'Get active booking Customers','code'=> 200, 'customer'=> $all_users_arr);
                echo json_encode($response); 
            }else{
                $response = array('message'=> 'Active booking Customers not found','code'=> 400);
                echo json_encode($response);
            }
            
        }else{
            $response = array('message'=> 'user not found','code'=> 400);
            echo json_encode($response);    
        }        
    }
    
    public function booking_blocked_customer(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        if(isset($user_data)){
            
            $seller_id =  $Commn->select_get_row_data('salon-list',array('user_id'=> $user_data->id),'id');
            $all_users = $Commn->where_selectAll('salon_booking_user',array('salon_id'=> $seller_id,'status'=> 0),'user_id');
            $all_users_arr = array();
            if(isset($all_users) && !empty($all_users)){
                foreach($all_users as $user){
                  $user_status = $Commn->select_get_row_data('salon_booking_user',array('user_id'=> $user->user_id,'salon_id'=>$seller_id),'status');
                  if(isset($user_status) && $user_status == 1){
                      $user_status = "active";
                  }else{$user_status = "Blocked";}
                  $data = array(
                        'id' => $user->user_id,
                        'name' => $Commn->select_get_row_data('users',array('id'=> $user->user_id),'name'),
                        'email' => $Commn->select_get_row_data('users',array('id'=> $user->user_id),'email'),
                        'phone' => $Commn->select_get_row_data('users',array('id'=> $user->user_id),'phone'),
                        'status' =>$user_status,
                        'total_booking' => count($Commn->where_selectAll('salon_booking',array('user_id'=> $user->user_id, 'salon_id'=> $seller_id),'')),
                        'completed_booking' => count($Commn->where_selectAll('salon_booking',array('user_id'=> $user->user_id,'booking_status'=> 3,'salon_id'=> $seller_id),'')),
                        'rejected_booking' =>  count($Commn->where_selectAll('salon_booking',array('user_id'=> $user->user_id,'booking_status'=> 4,'salon_id'=> $seller_id),'')),
                  );
                  array_push($all_users_arr, $data);
                }
                 $response = array('message'=> 'Get Blocked booking Customers','code'=> 200, 'customer'=> $all_users_arr);
                echo json_encode($response); 
            }else{
                $response = array('message'=> 'Blocked booking Customers not found','code'=> 400);
                echo json_encode($response); 
            }
            
        }else{
            $response = array('message'=> 'user not found','code'=> 400);
            echo json_encode($response);    
        }        
    }
    
    public function booking_customer_change_status(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        $user_id = $this->input->post('user_id');
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($user_id == ''){
            $response = array('message'=> 'user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        if(isset($user_data)){
            
            $seller_id =  $Commn->select_get_row_data('salon-list',array('user_id'=> $user_data->id),'id');
            $get_user_status = $Commn->get_row_data('salon_booking_user',array('salon_id'=> $seller_id,'user_id' => $user_id));
            
            if(isset($get_user_status) && !empty($get_user_status)){
                
                if($get_user_status->status == 1){
                     $update_status =  $Commn->update_data('salon_booking_user',array('status'=>0),array('id'=> $get_user_status->id));
                     if($update_status == 1){
                        $response = array('message'=> 'User is blocked','code'=> 200);
                        echo json_encode($response);  
                     }
                }else{
                    $update_status =  $Commn->update_data('salon_booking_user',array('status'=>1),array('id'=> $get_user_status->id));
                     if($update_status == 1){
                        $response = array('message'=> 'User is Active','code'=> 200);
                        echo json_encode($response);  
                     }    
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
    
    public function invoice($booking_id,$invoce_id){
        
        // echo $invoce_id;
        // die;
      
        $this->load->library('pdf');
        $Commn = new Commn();
        // $booking_id = $this->input->get('booking_id');
        if($booking_id == ''){
            $response = array('message'=> 'booking id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        
        $booking =  $Commn->get_row_data('salon_booking',array('id'=> $booking_id));
        
        if(isset($booking) && !empty($booking)){
            if($booking->booking_status != 3){
                $response = array('message'=> 'Booking is not completed','code'=> 400);
                echo json_encode($response); 
                return false;
            }
            if($booking->booking_status == 3){
                
                $salon_id =  $Commn->select_get_row_data('salon-list',array('id'=> $booking->salon_id),'id');
                $salon =  $Commn->get_row_data('salon-list',array('id'=> $salon_id));
                $salon_name =  $Commn->select_get_row_data('salon-list',array('id'=> $booking->salon_id),'salon_name');
                $address =  $Commn->select_get_row_data('salon-list',array('id'=> $booking->salon_id),'address');
                $email =  $Commn->select_get_row_data('users',array('id'=> $salon->user_id),'email');
                $phone=  $Commn->select_get_row_data('users',array('id'=> $salon->user_id),'phone');
                $booking_date =  $booking->booking_date;
                $service_satus = "Completed";
                if($booking->payment_mode == "offline"){
                    $payment_mode = "Cash Payment";
                }else{
                    $payment_mode = "Online Payment";
                }
                $user_name = $Commn->select_get_row_data('users',array('id'=> $booking->user_id),'name');
                $user_phone = $Commn->select_get_row_data('users',array('id'=> $booking->user_id),'phone');
                
                $booking_services = explode(',', $booking->service_id);
                $booking_arr = array();
                foreach($booking_services as $booking_service){
                    $data = array(
                        'service_name' => $Commn->select_get_row_data('salon-services',array('id'=> $booking_service),'title'),
                        'service_price' => $Commn->select_get_row_data('salon-services',array('id'=> $booking_service),'price'),
                        'booking_time' => $booking->booking_time
                    );
                    array_push($booking_arr, $data);
                }
                 $order_no = $Commn->get_row_data('order_no_tbl',array('booking_id'=>$booking_id ));
                $order_no = $order_no->order_no; 
                
                $pdf_data = array(
                    'invoice_id' => 'INV_'. $invoce_id,
                    'salon_name' => $salon_name,
                    'address' => $address,
                    'email' => $email,
                    'phone' => $phone,
                    'booking_date' => $booking_date,
                    'service_satus' => "Completed",
                    'payment_mode' => $payment_mode,
                    'user_name' => $user_name,
                    'user_phone' => $user_phone,
                    'services' => $booking_arr,
                    'order_no' => $order_no
                );
                // print_r($pdf_data);
                // die;
                // $this->load->view('GeneratePdfView',$pdf_data);
            $html = $this->load->view('GeneratePdfView', $pdf_data, true);
            
            $file_name = $salon_name.'/'.$booking->booking_date.'/'.'INV_'. $invoce_id;
            $this->pdf->createPDF($html, $file_name,false);  
            
            }
        }else{
            $response = array('message'=> 'Booking id wrong','code'=> 400);
            echo json_encode($response); 
        }
        
    }
    
    public function invoice1($booking_id,$invoce_id){
        $this->load->library('pdf');
        $Commn = new Commn();
        // $booking_id = $this->input->get('booking_id');
        if($booking_id == ''){
            $response = array('message'=> 'booking id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $booking =  $Commn->get_row_data('salon_booking',array('id'=> $booking_id));
        
        if(isset($booking) && !empty($booking)){
            if($booking->booking_status != 3){
                $response = array('message'=> 'Booking is not completed','code'=> 400);
                echo json_encode($response); 
                return false;
            }
            if($booking->booking_status == 3){
                $salon_id =  $Commn->select_get_row_data('salon-list',array('id'=> $booking->salon_id),'id');
                $salon =  $Commn->get_row_data('salon-list',array('id'=> $salon_id));
                $salon_name =  $Commn->select_get_row_data('salon-list',array('id'=> $booking->salon_id),'salon_name');
                $address =  $Commn->select_get_row_data('salon-list',array('id'=> $booking->salon_id),'address');
                $email =  $Commn->select_get_row_data('users',array('id'=> $salon->user_id),'email');
                $phone=  $Commn->select_get_row_data('users',array('id'=> $salon->user_id),'phone');
                $booking_date =  $booking->booking_date;
                $service_satus = "Completed";
                if($booking->payment_mode == "offline"){
                    $payment_mode = "Cash Payment";
                }else{
                    $payment_mode = "Online Payment";
                }
                $user_name = $Commn->select_get_row_data('users',array('id'=> $booking->user_id),'name');
                $user_phone = $Commn->select_get_row_data('users',array('id'=> $booking->user_id),'phone');
                
                $booking_services = explode(',', $booking->service_id);
                $booking_arr = array();
                foreach($booking_services as $booking_service){
                    $data = array(
                        'service_name' => $Commn->select_get_row_data('salon-services',array('id'=> $booking_service),'title'),
                        'service_price' => $Commn->select_get_row_data('salon-services',array('id'=> $booking_service),'price'),
                        'booking_time' => $booking->booking_time
                    );
                    array_push($booking_arr, $data);
                }
                $order_no = $Commn->get_row_data('order_no_tbl',array('booking_id'=>$booking_id ));
                $order_no = $order_no->order_no; 
                $pdf_data = array(
                    'invoice_id' => 'INV_'. $invoce_id,
                    'salon_name' => $salon_name,
                    'address' => $address,
                    'email' => $email,
                    'phone' => $phone,
                    'booking_date' => $booking_date,
                    'service_satus' => "Completed",
                    'payment_mode' => $payment_mode,
                    'user_name' => $user_name,
                    'user_phone' => $user_phone,
                    'services' => $booking_arr,
                    'order_no' => $order_no
                );
                // $this->load->view('GeneratePdfView',$pdf_data);
                 $file_name = $salon_name.'/'.$booking->booking_date.'/'.'INV_'. $invoce_id;
            $usercreatePDFhtml = $this->load->view('usercreatePDF', $pdf_data, true);
            $this->pdf->usercreatePDF($usercreatePDFhtml, $file_name, false);
            
            }
        }else{
            $response = array('message'=> 'Booking id wrong','code'=> 400);
            echo json_encode($response); 
        }
        
    }
}
?>
<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

class BookingCron extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('commn');
    }
    
    public function booking_cancels(){
        $commn = new commn();
        $all_bookings = $commn->selectAll_new('salon_booking','');
        
        
     if(isset($all_bookings) && !empty($all_bookings)){
         foreach($all_bookings as $all_booking){
             if($all_booking->booking_date < date('Y-m-d')){
                 if($all_booking->booking_status == 1 || $all_booking->booking_status == 2){
                     $update_status =  $commn->update_data('salon_booking',array('booking_status'=> '5'),array('id'=> $all_booking->id));
                 }
             }
         }
     }
    }
}
?>
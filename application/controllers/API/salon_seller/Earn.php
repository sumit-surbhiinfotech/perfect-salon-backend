<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

class Earn extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('commn');
    }
    
    public function earn_report(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        if(isset($user_data)){
            
            // $earn_data['online'] =  ((int)$Commn->get_earn_data($seller_user_id,'online') - (int)((int)$Commn->get_earn_data($seller_user_id,'online') * 4 / 100));
            $earn_data['online'] =  (int)$Commn->get_earn_data($seller_user_id,'online');
            // $earn_data['offline'] = ((int)$Commn->get_earn_data($seller_user_id,'offline') - (int)((int)$Commn->get_earn_data($seller_user_id,'offline') * 4 / 100));
            $earn_data['offline'] = (int)$Commn->get_earn_data($seller_user_id,'offline');
            $all_datas =  $Commn->get_earn_all_data($user_data->id,$start_date,$end_date);
            // $earn_data['total_amount'] = ((int)$Commn->get_earn_data($seller_user_id,'')  -(int)((int)$Commn->get_earn_data($seller_user_id,'') * 4 / 100));
            $earn_data['total_amount'] = (int)($earn_data['online']  + $earn_data['offline']);
            
            // $earn_data['all_data'] = '';
            $total_commission_charges = 0;
            $table_arr = array();
            if(isset($all_datas) && !empty($all_datas)){
                foreach($all_datas as $all_data){
                    $total_commission_charges += (int)($all_data->total_amount * 4 / 100);
                    $data = array(
                        'id' => $all_data->id,
                        'user_name' => $Commn->select_get_row_data('users',array('id' => $all_data->user_id),'name'),
                        'amount' => (int)($all_data->total_amount - (int)($all_data->total_amount * 4 / 100)),
                        'payment_type' => $all_data->payment_mode,
                        'date' => $all_data->booking_date .' '. $all_data->booking_time,
                        'commission_charges' => (int)($all_data->total_amount * 4 / 100),
                    );
                    array_push($table_arr, $data);
                }
                
            }
            // echo "<pre>";print_r($all_data);die;
            // $earn_data['commission_charges'] = $total_commission_charges;
            $earn_data['commission_charges'] = (int)($earn_data['total_amount'] * 4 / 100);
            $earn_data['receivable_amount'] = (int)($earn_data['total_amount'] - (int)($earn_data['total_amount'] * 4 / 100));
            
            $earn_data['all_data'] = $table_arr;
            
            if(isset($earn_data) && !empty($earn_data)){
                $pdf = $this->record_pdf($earn_data,$start_date,$end_date,$seller_user_id);
                $pdf = 'https://my-salon-app.surbhiinfotech.com/assets/Booking_PDF/'.$pdf.'.pdf';
                $earn_data['filter_pdf'] = $pdf;
                $response = array('message'=> 'successfully get earn report','code'=> 200,'earn_report' => $earn_data);
                echo json_encode($response);
            }
        }else{
            $response = array('message'=> 'user not found','code'=> 400);
            echo json_encode($response);
        }
    }
    
    public function earn_report_filter(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        $sort_key = $this->input->post('sort_key');
        $key = "";
        $start_date = '';
        $end_date = '';
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
            $start_date = $this->input->post('custom_from_date');
            $end_date = $this->input->post('custom_to_date');
        }
        
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
       
        if(isset($user_data)){
            
            // $earn_data['online'] =  ((int)$Commn->get_earn_data($seller_user_id,'online') - (int)((int)$Commn->get_earn_data($seller_user_id,'online') * 4 / 100));
            $earn_data['online'] =  (int)$Commn->get_earn_data_filter($seller_user_id,'online',$key,$start_date,$end_date);
          
            // $earn_data['offline'] = ((int)$Commn->get_earn_data($seller_user_id,'offline') - (int)((int)$Commn->get_earn_data($seller_user_id,'offline') * 4 / 100));
            $earn_data['offline'] = (int)$Commn->get_earn_data_filter($seller_user_id,'offline',$key,$start_date,$end_date);
            //  die;
            $all_datas =  $Commn->get_earn_all_data_filter($user_data->id,$key,$start_date,$end_date);
            // $earn_data['total_amount'] = ((int)$Commn->get_earn_data($seller_user_id,'')  -(int)((int)$Commn->get_earn_data($seller_user_id,'') * 4 / 100));
            $earn_data['total_amount'] = (int)($earn_data['online']  + $earn_data['offline']);
            
            // $earn_data['all_data'] = '';
            $total_commission_charges = 0;
            $table_arr = array();
            if(isset($all_datas) && !empty($all_datas)){
                foreach($all_datas as $all_data){
                    $total_commission_charges += (int)($all_data->total_amount * 4 / 100);
                    $data = array(
                        'id' => $all_data->id,
                        'user_name' => $Commn->select_get_row_data('users',array('id' => $all_data->user_id),'name'),
                        'amount' => (int)($all_data->total_amount - (int)($all_data->total_amount * 4 / 100)),
                        'payment_type' => $all_data->payment_mode,
                        'date' => $all_data->booking_date .' '. $all_data->booking_time,
                        'commission_charges' => (int)($all_data->total_amount * 4 / 100),
                    );
                    array_push($table_arr, $data);
                }
                
            }
            // echo "<pre>";print_r($all_data);die;
            // $earn_data['commission_charges'] = $total_commission_charges;
            $earn_data['commission_charges'] = (int)($earn_data['total_amount'] * 4 / 100);
            $earn_data['receivable_amount'] = (int)($earn_data['total_amount'] - (int)($earn_data['total_amount'] * 4 / 100));
            
            $earn_data['all_data'] = $table_arr;
            
            if(isset($earn_data) && !empty($earn_data)){
                $pdf = $this->record_pdf($earn_data,$start_date,$end_date,$seller_user_id);
                $pdf = 'https://my-salon-app.surbhiinfotech.com/assets/Booking_PDF/'.$pdf.'.pdf';
                $earn_data['filter_pdf'] = $pdf;
                $response = array('message'=> 'successfully get earn report','code'=> 200,'earn_report' => $earn_data);
                echo json_encode($response);
            }
        }else{
            $response = array('message'=> 'user not found','code'=> 400);
            echo json_encode($response);
        }
    }
    
    public function record_pdf($record,$start_date,$end_date,$seller_user_id){
        
        $Commn = new Commn();
        $salon =  $Commn->get_row_data('salon-list',array('user_id'=> $seller_user_id));
        $user =  $Commn->get_row_data('users',array('id'=> $seller_user_id));
        if($start_date != '' && $end_date != ''){
            $file_name = trim($salon->salon_name).'_'.$start_date.'-'.$end_date;
        }else
        {
            $start_date = date('Y').date('m').'01';
            $end_date = date('Y').date('m').'30';
            $file_name = trim($salon->salon_name).'_'.date('M').'_list';
            
        }
        $record['records'] = $record;
        $record['records']['start_date'] = $start_date;
        $record['records']['end_date'] = $end_date;
        $record['records']['salon_name'] = $salon->salon_name;
        $record['records']['salon_address'] = $salon->address;
        $record['records']['salon_email'] = $user->email;
        $record['records']['salon_phone'] = $user->phone;
        
        $this->load->library('pdf');
        $html = $this->load->view('recordspdf', $record, true);
        $this->pdf->bookinglistPDF($html, $file_name, false);
        return $file_name;
    }
}
?>
<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

class Users extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('commn');
        $this->load->model('Dashboard_Model');
    }
    
    public function new_users_list(){
        $Model = new Dashboard_Model();
        $Common = new commn();
         
        $new_partner_list_arr = array();
        $new_partner_lists =  $Model->new_users('created_at BETWEEN DATE_SUB(NOW(), INTERVAL 15 DAY) AND NOW()', array('role' => 1), 'users');
        
        if(isset($new_partner_lists) && !empty($new_partner_lists)){
           foreach($new_partner_lists as $new_partner_list){
                $new_partner = array();
                
                if(!empty($new_partner_list->image) && isset($new_partner_list->image)){
                    $image = base_url().'assets/profile_pic/users/'.$new_partner_list->image;
                }else{
                    $image = base_url().'assets/profile_pic/default_pro_pic.png';
                }
                $new_partner['id'] = $new_partner_list->id;
                $new_partner['image'] = $image;
                $new_partner['name'] = $new_partner_list->name;
                $new_partner['phone_number'] = $new_partner_list->phone;
                $new_partner['register_date'] = date('Y-m-d', strtotime($new_partner_list->created_at));
                $new_partner['city'] = $Common->select_get_row_data('cities',array('id' => $new_partner_list->city),'name');
                $new_partner['state'] = $Common->select_get_row_data('states',array('id' => $new_partner_list->state),'name');
                $new_partner['pincode'] = $new_partner_list->pincode;
                if($new_partner_list->status == 1){
                    $status = 'Active';
                }
                if($new_partner_list->status == 0){
                    $status = 'Inactive';
                }
                $new_partner['status'] = $status;
                
                array_push($new_partner_list_arr, $new_partner);
           }
           
           if(isset($new_partner_list_arr) && !empty($new_partner_list_arr)){
                $response = array('message'=> 'New Users lists','code'=> 200,'lists' => $new_partner_list_arr);
                echo json_encode($response);   
           }else{
                $response = array('message'=> 'New Users not found','code'=> 400);
                echo json_encode($response);   
           }
        }else{
           $response = array('message'=> 'New Users not found','code'=> 400);
           echo json_encode($response);
        }        
    }
    
    public function existing_users_list(){
        $Model = new Dashboard_Model();
        $Common = new commn();
         
        $existing_partner_list_arr = array();
        $existing_partner_lists =  $Common->where_selectAll('users',array('role' => 1),'');
        
        if(isset($existing_partner_lists) && !empty($existing_partner_lists)){
           foreach($existing_partner_lists as $existing_partner_list){
                $existing_partner = array();
                
                if(!empty($new_partner_list->image) && isset($new_partner_list->image)){
                    $image = base_url().'assets/profile_pic/seller_user/'.$new_partner_list->image;
                }else{
                    $image = base_url().'assets/profile_pic/default_pro_pic.png';
                }
                
                $existing_partner['id'] = $existing_partner_list->id;
                $existing_partner['image'] = $image;
                $existing_partner['name'] = $existing_partner_list->name;
                $existing_partner['phone_number'] = $existing_partner_list->phone;
                $existing_partner['register_date'] = date('Y-m-d', strtotime($existing_partner_list->created_at));
                $existing_partner['city'] = $Common->select_get_row_data('cities',array('id' => $existing_partner_list->city),'name');
                $existing_partner['state'] = $Common->select_get_row_data('states',array('id' => $existing_partner_list->state),'name');
                $existing_partner['pincode'] = $existing_partner_list->pincode;
                if($existing_partner_list->status == 1){
                    $status = 'Active';
                }
                if($existing_partner_list->status == 0){
                    $status = 'Inactive';
                }
                $existing_partner['status'] = $status;
                
                array_push($existing_partner_list_arr, $existing_partner);
           }
           
           if(isset($existing_partner_list_arr) && !empty($existing_partner_list_arr)){
                $response = array('message'=> 'Existing Users lists','code'=> 200,'lists' => $existing_partner_list_arr);
                echo json_encode($response);   
           }else{
                $response = array('message'=> 'Existing Users not found','code'=> 400);
                echo json_encode($response);   
           }
        }else{
           $response = array('message'=> 'Existing Users not found','code'=> 400);
           echo json_encode($response);
        }

        
        
    }    
    
    public function new_users_view(){
        $Model = new Dashboard_Model();
        $Common = new commn();
        
        $id = $this->input->post('id');
        
        if($id == ''){
           $response = array('message'=> 'id is required','code'=> 400);
           echo json_encode($response);
           return false;
        }
        $check_users =  $Common->get_row_data('users',array('role' => 1,'id' => $id));
        
        if(isset($check_users) && !empty($check_users)){
            $id = $check_users->id;
            $bank_details =  $Common->get_row_data('seller_bank_detail',array('seller_user_id' => $id));
            $salon_id =  $Common->select_get_row_data('salon-list',array('user_id' => $check_users->id),'id');
            
            $new_users_view_arr = array();
            if($check_users->status == 1){
                    $status = 'Approve';
            }
            if($check_users->status == 0){
                $status = 'Blocked';
            }
            if($check_users->status == 2){
                $status = 'pending';
            }
            $new_users_view_arr['id'] = $check_users->id;
            $new_users_view_arr['name'] = $check_users->name;
            $new_users_view_arr['email'] = $check_users->email;
            $new_users_view_arr['address'] = $check_users->address;
            $new_users_view_arr['phone'] = $check_users->phone;
            $new_users_view_arr['city'] =  $Common->select_get_row_data('cities',array('id' => $check_users->city),'name');
            $new_users_view_arr['pincode'] = $check_users->pincode;
            $new_users_view_arr['state'] = $Common->select_get_row_data('states',array('id' => $check_users->state),'name');
            $new_users_view_arr['status'] = $status;
            $new_users_view_arr['register_date'] = date('Y-m-d', strtotime($check_users->created_at));
            
            $response_arr = array('user_detail' => $new_users_view_arr);
            if(isset($response_arr) && !empty($response_arr)){
                $response = array('message'=> 'New Users View','code'=> 200,'view' => $response_arr);
                echo json_encode($response);   
            }else{
                $response = array('message'=> 'User Details not found','code'=> 400);
                echo json_encode($response);   
            }
        }else{
           $response = array('message'=> 'id is wrong','code'=> 400);
           echo json_encode($response);
           return false;
        }        
    }
    
    public function existing_users_view(){
        $Model = new Dashboard_Model();
        $Common = new commn();
        
        $id = $this->input->post('id');
        
        if($id == ''){
           $response = array('message'=> 'id is required','code'=> 400);
           echo json_encode($response);
           return false;
        }
        $check_users =  $Common->get_row_data('users',array('role' => 1,'id' => $id));
        
        if(isset($check_users) && !empty($check_users)){
            $id = $check_users->id;
            $bank_details =  $Common->get_row_data('seller_bank_detail',array('seller_user_id' => $id));
            $salon_id =  $Common->select_get_row_data('salon-list',array('user_id' => $check_users->id),'id');
            
            $new_users_view_arr = array();
            if($check_users->status == 1){
                    $status = 'Approve';
            }
            if($check_users->status == 0){
                $status = 'Blocked';
            }
            if($check_users->status == 2){
                $status = 'pending';
            }
            $new_users_view_arr['id'] = $check_users->id;
            $new_users_view_arr['name'] = $check_users->name;
            $new_users_view_arr['email'] = $check_users->email;
            $new_users_view_arr['address'] = $check_users->address;
            $new_users_view_arr['phone'] = $check_users->phone;
            $new_users_view_arr['city'] =  $Common->select_get_row_data('cities',array('id' => $check_users->city),'name');
            $new_users_view_arr['pincode'] = $check_users->pincode;
            $new_users_view_arr['state'] = $Common->select_get_row_data('states',array('id' => $check_users->state),'name');
            $new_users_view_arr['status'] = $status;
            $new_users_view_arr['register_date'] = date('Y-m-d', strtotime($check_users->created_at));
            
            $get_my_bookings =  $Common->get_my_booking($check_users->id);
            $my_bookings_arr = array();
            if(isset($get_my_bookings) && !empty($get_my_bookings)){
                foreach($get_my_bookings as $get_my_booking){
                    $my_bookings = array(
                        'image' => '',
                        'salon_name' => $Common->select_get_row_data('salon-list',array('id' => $get_my_booking->salon_id),'salon_name'),
                        'service' => $Common->select_get_row_data('salon-services', array('id' => $get_my_booking->service_id),'title'),
                        'book_date' => date('M d, Y', strtotime($get_my_booking->booking_date)),
                        'booking_time' => $get_my_booking->booking_time,
                        'total_amount' => $get_my_booking->total_amount,
                        'invoice_no' => $get_my_booking->id,    
                    );
                    array_push($my_bookings_arr,$my_bookings);
                }
            }
            $response_arr = array('user_detail' => $new_users_view_arr,"bookings" => $my_bookings_arr);
            if(isset($response_arr) && !empty($response_arr)){
                $response = array('message'=> 'Existing User View','code'=> 200,'view' => $response_arr);
                echo json_encode($response);   
            }else{
                $response = array('message'=> 'User Details not found','code'=> 400);
                echo json_encode($response);   
            }
        }else{
           $response = array('message'=> 'id is wrong','code'=> 400);
           echo json_encode($response);
           return false;
        }        
    }
    
    public function change_user_status(){
        $Model = new Dashboard_Model();
        $Common = new commn();
        
        $id = $this->input->post('id');
        
        if($id == ''){
           $response = array('message'=> 'id is required','code'=> 400);
           echo json_encode($response);
           return false;
        }
        $check_users =  $Common->get_row_data('users',array('role' => 1,'id' => $id));
        
        
        if(isset($check_users) && !empty($check_users)){
            if($check_users->status == 1){
                $status = 0;
            }else if($check_users->status == 0){
                $status = 1;
            }
            $update_status =  $Common->update_data('users',array('status' =>$status),array('id' => $check_users->id));
            if($update_status == 1){
                $get_user =  $Common->get_row_data('users',array('id' => $id));
                if($get_user->status == 1){
                    $response = array('message'=> 'User is unblock','code'=> 200);
                    echo json_encode($response);    
                }else if($get_user->status == 0){
                    $response = array('message'=> 'User is Block','code'=> 200);
                    echo json_encode($response);                    
                }  
            }else{
                $response = array('message'=> 'Somthing wrong','code'=> 400);
                echo json_encode($response);
                return false;  
            }
        }else{
           $response = array('message'=> 'id is wrong','code'=> 400);
           echo json_encode($response);
           return false;            
        }
    }
}
?>
<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

class Partners extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('commn');
        $this->load->model('Dashboard_Model');
    }
    
    public function new_partner_list(){
        $Model = new Dashboard_Model();
        $Common = new commn();
         
        $new_partner_list_arr = array();
        $new_partner_lists =  $Model->new_users('created_at BETWEEN DATE_SUB(NOW(), INTERVAL 15 DAY) AND NOW()', array('role' => 2), 'users');
       
        if(isset($new_partner_lists) && !empty($new_partner_lists)){
           foreach($new_partner_lists as $new_partner_list){
                $new_partner = array();
                
                if(!empty($new_partner_list->image) && isset($new_partner_list->image)){
                    $image = base_url().'assets/profile_pic/seller_user/'.$new_partner_list->image;
                }else{
                    $image = base_url().'assets/profile_pic/default_pro_pic.png';
                }
                $new_partner['id'] = $new_partner_list->id;
                $new_partner['image'] = $image;
                $new_partner['name'] = $new_partner_list->name;
                $new_partner['salon_name'] = $Common->select_get_row_data('salon-list',array('id' => $new_partner_list->id),'salon_name');
                $new_partner['phone_number'] = $new_partner_list->phone;
                $new_partner['register_date'] = date('Y-m-d', strtotime($new_partner_list->created_at));
                $new_partner['city'] = $Common->select_get_row_data('cities',array('id' => $new_partner_list->city),'name');
                $new_partner['state'] = $Common->select_get_row_data('states',array('id' => $new_partner_list->state),'name');
                $new_partner['pincode'] = $new_partner_list->pincode;
                $salon = $Common->get_row_data('salon-list',array('user_id' => $new_partner_list->id));
               
                if($salon->is_approve == 1){
                    $status = 'Approve';
                }
                if($salon->is_approve == 0){
                    $status = 'Rejected';
                }
                if($salon->is_approve == 2){
                    $status = 'pending';
                }
                $new_partner['status'] = $status;
                
                array_push($new_partner_list_arr, $new_partner);
           }
           
           if(isset($new_partner_list_arr) && !empty($new_partner_list_arr)){
                $response = array('message'=> 'New Partner lists','code'=> 200,'lists' => $new_partner_list_arr);
                echo json_encode($response);   
           }else{
                $response = array('message'=> 'New Partner Users not found','code'=> 400);
                echo json_encode($response);   
           }
        }else{
           $response = array('message'=> 'New Partner Users not found','code'=> 400);
           echo json_encode($response);
        }

        
        
    }
    
    public function existing_partner_list(){
        $Model = new Dashboard_Model();
        $Common = new commn();
         
        $existing_partner_list_arr = array();
        $existing_partner_lists =  $Common->where_selectAll('users',array('role' => 2),'');
        
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
                $existing_partner['salon_name'] = $Common->select_get_row_data('salon-list',array('id' => $existing_partner_list->id),'salon_name');
                $existing_partner['phone_number'] = $existing_partner_list->phone;
                $existing_partner['register_date'] = date('Y-m-d', strtotime($existing_partner_list->created_at));
                $existing_partner['city'] = $Common->select_get_row_data('cities',array('id' => $existing_partner_list->city),'name');
                $existing_partner['state'] = $Common->select_get_row_data('states',array('id' => $existing_partner_list->state),'name');
                $existing_partner['pincode'] = $existing_partner_list->pincode;
                if($existing_partner_list->status == 1){
                    $status = 'Approve';
                }
                if($existing_partner_list->status == 0){
                    $status = 'Rejected';
                }
                if($existing_partner_list->status == 2){
                    $status = 'pending';
                }
                $existing_partner['status'] = $status;
                
                array_push($existing_partner_list_arr, $existing_partner);
           }
           
           if(isset($existing_partner_list_arr) && !empty($existing_partner_list_arr)){
                $response = array('message'=> 'Existing partner lists','code'=> 200,'lists' => $existing_partner_list_arr);
                echo json_encode($response);   
           }else{
                $response = array('message'=> 'Existing partner Users not found','code'=> 400);
                echo json_encode($response);   
           }
        }else{
           $response = array('message'=> 'Existing partner Users not found','code'=> 400);
           echo json_encode($response);
        }

        
        
    }
    
    public function block_partner_list(){
        $Model = new Dashboard_Model();
        $Common = new commn();
         
        $block_partner_list_arr = array();
        $block_partner_lists =  $Common->where_selectAll('users',array('role' => 2,'status' => 0),'');
        
        if(isset($block_partner_lists) && !empty($block_partner_lists)){
           foreach($block_partner_lists as $block_partner_list){
                $block_partner = array();
                
                if(!empty($block_partner_list->image) && isset($block_partner_list->image)){
                    $image = base_url().'assets/profile_pic/seller_user/'.$block_partner_list->image;
                }else{
                    $image = base_url().'assets/profile_pic/default_pro_pic.png';
                }
                
                $block_partner['id'] = $block_partner_list->id;
                $block_partner['image'] = $image;
                $block_partner['name'] = $block_partner_list->name;
                $block_partner['salon_name'] = $Common->select_get_row_data('salon-list',array('id' => $block_partner_list->id),'salon_name');
                $block_partner['phone_number'] = $block_partner_list->phone;
                $block_partner['register_date'] = date('Y-m-d', strtotime($block_partner_list->created_at));
                $block_partner['city'] = $Common->select_get_row_data('cities',array('id' => $block_partner_list->city),'name');
                $block_partner['state'] = $Common->select_get_row_data('states',array('id' => $block_partner_list->state),'name');
                $block_partner['pincode'] = $block_partner_list->pincode;
                if($block_partner_list->status == 1){
                    $status = 'Approve';
                }
                if($block_partner_list->status == 0){
                    $status = 'Blocked';
                }
                if($block_partner_list->status == 2){
                    $status = 'pending';
                }
                $block_partner['status'] = $status;
                
                array_push($block_partner_list_arr, $block_partner);
           }
           
           if(isset($block_partner_list_arr) && !empty($block_partner_list_arr)){
                $response = array('message'=> 'Block partner lists','code'=> 200,'lists' => $block_partner_list_arr);
                echo json_encode($response);   
           }else{
                $response = array('message'=> 'Block partner Users not found','code'=> 400);
                echo json_encode($response);   
           }
        }else{
           $response = array('message'=> 'Block partner Users not found','code'=> 400);
           echo json_encode($response);
        }

        
        
    }
    
    public function new_partner_view(){
        $Model = new Dashboard_Model();
        $Common = new commn();
        
        $id = $this->input->post('id');
        
        if($id == ''){
           $response = array('message'=> 'id is required','code'=> 400);
           echo json_encode($response);
           return false;
        }
        $check_users =  $Common->get_row_data('users',array('role' => 2,'id' => $id));
        
        if(isset($check_users) && !empty($check_users)){
            $id = $check_users->id;
            $bank_details =  $Common->get_row_data('seller_bank_detail',array('seller_user_id' => $id));
            $salon_id =  $Common->select_get_row_data('salon-list',array('user_id' => $check_users->id),'id');
            
            $new_partner_view_arr = array();
            if($check_users->status == 1){
                    $status = 'Approve';
            }
            if($check_users->status == 0){
                $status = 'Blocked';
            }
            if($check_users->status == 2){
                $status = 'pending';
            }
            $new_partner_view_arr['id'] = $check_users->id;
            $new_partner_view_arr['name'] = $check_users->name;
            $new_partner_view_arr['address'] = $check_users->address;
            $new_partner_view_arr['salon_name'] = $Common->select_get_row_data('salon-list',array('user_id' => $check_users->id),'salon_name');
            $new_partner_view_arr['phone'] = $check_users->phone;
            $new_partner_view_arr['email'] = $check_users->email;
            $new_partner_view_arr['pan'] = $Common->select_get_row_data('seller_bank_detail',array('seller_user_id' => $check_users->id),'pan_card');
            $new_partner_view_arr['account_number'] = $Common->select_get_row_data('seller_bank_detail',array('seller_user_id' => $check_users->id),'account_number');
            $new_partner_view_arr['upi_id'] = $Common->select_get_row_data('seller_bank_detail',array('seller_user_id' => $check_users->id),'upi_id');
            $new_partner_view_arr['account_holder_name'] = $Common->select_get_row_data('seller_bank_detail',array('seller_user_id' => $check_users->id),'account_holder_name');
            $new_partner_view_arr['adhar_card'] = $Common->select_get_row_data('seller_bank_detail',array('seller_user_id' => $check_users->id),'adhar_card');
            $new_partner_view_arr['ifsc_number'] = $Common->select_get_row_data('seller_bank_detail',array('seller_user_id' => $check_users->id),'ifsc_number');
            $new_partner_view_arr['city'] =  $Common->select_get_row_data('cities',array('id' => $check_users->city),'name');
            $new_partner_view_arr['pincode'] = $check_users->pincode;
            $new_partner_view_arr['status'] = $status;
            $new_partner_view_arr['state'] = $Common->select_get_row_data('states',array('id' => $check_users->state),'name');
            $new_partner_view_arr['register_date'] = date('Y-m-d', strtotime($check_users->created_at));
            
            $banner_images =  $Common->where_selectAll('salon_banner_image',array('salon_id' => $salon_id),'');
            $banner_images_arr = array();
            if(isset($banner_images) && !empty($banner_images)){
                foreach($banner_images as $banner_image){
                    $banner_images = array();
                    $banner_images['id'] = $banner_image->id;
                    $banner_images['image'] = base_url().'assets/images/salon/banner/'.$banner_image->image;
                    array_push($banner_images_arr, $banner_images);
                }
            }
            $our_gallery_images =  $Common->where_selectAll('salon-portfolio',array('salon_id' => $salon_id),'');
            $our_gallery_images_arr = array();
            if(isset($our_gallery_images) && !empty($our_gallery_images)){
                foreach($our_gallery_images as $our_gallery_image){
                    $our_gallery_images = array();
                    $our_gallery_images['id'] = $our_gallery_image->id;
                    $our_gallery_images['image'] = base_url().'assets/images/salon/portfolio/'.$our_gallery_image->image;
                    array_push($our_gallery_images_arr, $our_gallery_images);
                }
            }
            
            $response_arr = array('user_detail' => $new_partner_view_arr, 'banner_images' => $banner_images_arr, 'our_gallery' => $our_gallery_images_arr);
            if(isset($response_arr) && !empty($response_arr)){
                $response = array('message'=> 'Partner View','code'=> 200,'view' => $response_arr);
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
    
    public function reject_status_new_partner(){
        $Model = new Dashboard_Model();
        $Common = new commn();
        
        $id = $this->input->post('id');
        
        if($id == ''){
           $response = array('message'=> 'id is required','code'=> 400);
           echo json_encode($response);
           return false;
        }
        $check_users =  $Common->get_row_data('users',array('role' => 2,'id' => $id));
        
        $salon =  $Common->get_row_data('salon-list',array('user_id' => $check_users->id));
        if(isset($salon) && !empty($salon)){
            if($salon->is_approve == 0){
               $response = array('message'=> 'Already Rejected user','code'=> 400);
               echo json_encode($response);
               return false;  
            }
            $update_status =  $Common->update_data('salon-list',array('is_approve' => 0),array('id' => $salon->id));
            if($update_status == 1){
               $response = array('message'=> 'Successfully Update Status','code'=> 200);
               echo json_encode($response);                
            }else{
               $response = array('message'=> 'Somthing wrong','code'=> 400);
               echo json_encode($response);                     
            }
        }else{
           $response = array('message'=> 'id is wrong','code'=> 400);
           echo json_encode($response);
           return false;   
        }
    }
    
    public function approve_status_new_partner(){
        $Model = new Dashboard_Model();
        $Common = new commn();
        
        $id = $this->input->post('id');
        
        if($id == ''){
           $response = array('message'=> 'id is required','code'=> 400);
           echo json_encode($response);
           return false;
        }
        $check_users =  $Common->get_row_data('users',array('role' => 2,'id' => $id));
        $salon =  $Common->get_row_data('salon-list',array('user_id' => $check_users->id));
        if(isset($salon) && !empty($salon)){
            if($salon->is_approve == 1){
               $response = array('message'=> 'Already Approve user','code'=> 400);
               echo json_encode($response);
               return false;  
            }
            $update_status =  $Common->update_data('salon-list',array('is_approve' => 1),array('id' => $salon->id));
            if($update_status == 1){
               $response = array('message'=> 'Successfully Update Status','code'=> 200);
               echo json_encode($response);                
            }else{
               $response = array('message'=> 'Somthing wrong','code'=> 400);
               echo json_encode($response);                     
            }
        }else{
           $response = array('message'=> 'id is wrong','code'=> 400);
           echo json_encode($response);
           return false;   
        }        
    }
    
    public function upload_banner_image(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('id');
        $banner_image = $this->input->post('banner_image');
        // $banner_image_file =  $_FILES['banner_image'];
        
        // echo "<pre>";print_r($banner_image_file);die;
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if(empty($_FILES['banner_image']['name'])){
            $response = array('message'=> 'banner image is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        $banner_gallery_arr = array();
        $upload_status =0;
        if(isset($user_data) && !empty($user_data)){
            $seller_id =  $Commn->select_get_row_data('salon-list',array('user_id'=> $user_data->id),'id');
            $banner_image_arr = array();
            $banner_image_arr['salon_id'] = $seller_id;
            $upload_path="assets/images/salon/banner";
            $config = array(
            'upload_path' => $upload_path,
            'allowed_types' => "gif|jpg|png|jpeg",
            'allowed_types' => '*',
            'overwrite' => TRUE,
            'max_size' => "2048000"
            );
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload('banner_image'))
            { 
            $data['imageError'] =  $this->upload->display_errors();
            
            if(isset($data['imageError']) && !empty($data['imageError'])){
                $response = array('message'=> 'gallery image not upload','code'=> 400,'error'=> $data['imageError']);
                echo json_encode($response);
                return false;
            }
            
            }else{
                $imageDetailArray = $this->upload->data();
                $image =  $imageDetailArray['file_name'];
                $banner_image_arr['image'] = $image;
                $banner_image_arr['status'] = 1;
                // $update = $Commn->update_data('users',array('image' => $image), array('id' => $user_id));
            }
             $new_banner_image =  $Commn->insert_data('salon_banner_image', $banner_image_arr);
            if($new_banner_image == 1){
                $response = array('message'=> 'Successfully uploaded','code'=> 200);
                echo json_encode($response);
            }else{
                $response = array('message'=> 'somthing wrong','code'=> 400);
                echo json_encode($response);    
            }
            
        }else{
            $response = array('message'=> 'Id is weong','code'=> 400);
            echo json_encode($response);            
        }
    }
    
    public function upload_gallery_image(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('id');
        $gallery_image = $this->input->post('gallery_image');
        // $gallery_image_file =  $_FILES['gallery_image'];
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if(empty($_FILES['gallery_image']['name'])){
            $response = array('message'=> 'gallery image is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        $get_gallery_arr = array();
        if(isset($user_data) && !empty($user_data)){
            $seller_id =  $Commn->select_get_row_data('salon-list',array('user_id'=> $user_data->id),'id');
            $gallery_image_arr = array();
            $gallery_image_arr['salon_id'] = $seller_id;
            $upload_path="assets/images/salon/portfolio";
            $config = array(
            'upload_path' => $upload_path,
            'allowed_types' => "gif|jpg|png|jpeg",
            'allowed_types' => '*',
            'overwrite' => TRUE,
            'max_size' => "2048000"
            );
            $this->load->library('upload', $config);
            if(!$this->upload->do_upload('gallery_image'))
            { 
            $data['imageError'] =  $this->upload->display_errors();
            
            if(isset($data['imageError']) && !empty($data['imageError'])){
                $response = array('message'=> 'gallery image not upload','code'=> 400,'error'=> $data['imageError']);
                echo json_encode($response);
                return false;
            }
            
            }else{
                $imageDetailArray = $this->upload->data();
                $image =  $imageDetailArray['file_name'];
                $gallery_image_arr['image'] = $image;
                $gallery_image_arr['status'] = 1;
                // $update = $Commn->update_data('users',array('image' => $image), array('id' => $user_id));
            }
            $new_gallery_image =  $Commn->insert_data('salon-portfolio', $gallery_image_arr);
            if($new_gallery_image == 1){
                $response = array('message'=> 'Successfully uploaded','code'=> 200);
                echo json_encode($response);    
            }else{
                $response = array('message'=> 'somthing wrong','code'=> 400);
                echo json_encode($response);    
            }
            
        }else{
            $response = array('message'=> 'Id is wrong','code'=> 400);
            echo json_encode($response);            
        }        
    }
    
    
    // existing partner API'S
    
    public function existing_partner_view(){
        $Model = new Dashboard_Model();
        $Common = new commn();
        
        $id = $this->input->post('id');
        
        if($id == ''){
           $response = array('message'=> 'id is required','code'=> 400);
           echo json_encode($response);
           return false;
        }
        $check_users =  $Common->get_row_data('users',array('role' => 2,'id' => $id));
        
        if(isset($check_users) && !empty($check_users)){
            $id = $check_users->id;
            $bank_details =  $Common->get_row_data('seller_bank_detail',array('seller_user_id' => $id));
            $salon_id =  $Common->select_get_row_data('salon-list',array('user_id' => $check_users->id),'id');
            
            $new_partner_view_arr = array();
            if($check_users->status == 1){
                    $status = 'Active';
            }
            if($check_users->status == 0){
                $status = 'Blocked';
            }
            if($check_users->status == 2){
                $status = 'pending';
            }
            $new_partner_view_arr['id'] = $check_users->id;
            $new_partner_view_arr['name'] = $check_users->name;
            $new_partner_view_arr['address'] = $check_users->address;
            $new_partner_view_arr['salon_name'] = $Common->select_get_row_data('salon-list',array('user_id' => $check_users->id),'salon_name');
            $new_partner_view_arr['phone'] = $check_users->phone;
            $new_partner_view_arr['email'] = $check_users->email;
            $new_partner_view_arr['pan'] = $Common->select_get_row_data('seller_bank_detail',array('seller_user_id' => $check_users->id),'pan_card');
            $new_partner_view_arr['account_number'] = $Common->select_get_row_data('seller_bank_detail',array('seller_user_id' => $check_users->id),'account_number');
            $new_partner_view_arr['upi_id'] = $Common->select_get_row_data('seller_bank_detail',array('seller_user_id' => $check_users->id),'upi_id');
            $new_partner_view_arr['account_holder_name'] = $Common->select_get_row_data('seller_bank_detail',array('seller_user_id' => $check_users->id),'account_holder_name');
            $new_partner_view_arr['adhar_card'] = $Common->select_get_row_data('seller_bank_detail',array('seller_user_id' => $check_users->id),'adhar_card');
            $new_partner_view_arr['ifsc_number'] = $Common->select_get_row_data('seller_bank_detail',array('seller_user_id' => $check_users->id),'ifsc_number');
            $new_partner_view_arr['city'] =  $Common->select_get_row_data('cities',array('id' => $check_users->city),'name');
            $new_partner_view_arr['pincode'] = $check_users->pincode;
            $new_partner_view_arr['status'] = $status;
            $new_partner_view_arr['state'] = $Common->select_get_row_data('states',array('id' => $check_users->state),'name');
            $new_partner_view_arr['register_date'] = date('Y-m-d', strtotime($check_users->created_at));
            
         
           $booking_list = $Common->where_selectAll('salon_booking',array('salon_id' => $salon_id),'');
           
           $booking_arr = array();
           if(isset($booking_list) && !empty($booking_list)){
               foreach($booking_list as $booking){
                    $status = '';
                    if($booking->booking_status == 1){
                        $status = "Booked";
                    }
                    if($booking->booking_status == 2){
                        $status = "Booking Accept";
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
                    
                    $user_token = $Common->select_get_row_data('notification_token',array('user_id'=> $booking->user_id),'Token');
			        $token =  isset($user_token) ? $user_token : '';
			        
			        $image = $Common->select_get_row_data('users', array('id' => $booking->user_id),'image');
			        if($image == ''){
			             $image = base_url().'assets/profile_pic/default_pro_pic.png';
			        }else{
			            $image = base_url().'assets/profile_pic/users/'.$image;
			        }
                    $data = array(
                        'image' => $image,
                        'user_name' => $Common->select_get_row_data('users', array('id' => $booking->user_id),'name'),
                        'service' => $Common->select_get_row_data('salon-services', array('id' => $booking->service_id),'title'),
                        'book_date' => date('M d, Y', strtotime($booking->booking_date)),
                        'booking_time' => $booking->booking_time,
                        'total_amount' => $booking->total_amount,
                        'invoice_no' => $booking->id,
                    );    
                array_push($booking_arr, $data);  
               }
           }
            
            $banner_images =  $Common->where_selectAll('salon_banner_image',array('salon_id' => $salon_id),'');
            $banner_images_arr = array();
            if(isset($banner_images) && !empty($banner_images)){
                foreach($banner_images as $banner_image){
                    $banner_images = array();
                    $banner_images['id'] = $banner_image->id;
                    $banner_images['image'] = base_url().'assets/images/salon/banner/'.$banner_image->image;
                    array_push($banner_images_arr, $banner_images);
                }
            }
            $our_gallery_images =  $Common->where_selectAll('salon-portfolio',array('salon_id' => $salon_id),'');
            $our_gallery_images_arr = array();
            if(isset($our_gallery_images) && !empty($our_gallery_images)){
                foreach($our_gallery_images as $our_gallery_image){
                    $our_gallery_images = array();
                    $our_gallery_images['id'] = $our_gallery_image->id;
                    $our_gallery_images['image'] = base_url().'assets/images/salon/portfolio/'.$our_gallery_image->image;
                    array_push($our_gallery_images_arr, $our_gallery_images);
                }
            }
            
            $response_arr = array('user_detail' => $new_partner_view_arr, 'bookings' => $booking_arr, 'banner_images' => $banner_images_arr, 'our_gallery' => $our_gallery_images_arr);
            if(isset($response_arr) && !empty($response_arr)){
                $response = array('message'=> 'Partner View','code'=> 200,'view' => $response_arr);
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
    
    public function change_status(){
        $Model = new Dashboard_Model();
        $Common = new commn();
        
        $id = $this->input->post('id');
        
        if($id == ''){
           $response = array('message'=> 'id is required','code'=> 400);
           echo json_encode($response);
           return false;
        }
        $check_users =  $Common->get_row_data('users',array('role' => 2,'id' => $id));
        
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
    
    // Blocked partner API'S
    
    public function block_partner_view(){
        $Model = new Dashboard_Model();
        $Common = new commn();
        
        $id = $this->input->post('id');
        
        if($id == ''){
           $response = array('message'=> 'id is required','code'=> 400);
           echo json_encode($response);
           return false;
        }
        $check_users =  $Common->get_row_data('users',array('role' => 2,'id' => $id));
        
        if(isset($check_users) && !empty($check_users)){
            $id = $check_users->id;
            $bank_details =  $Common->get_row_data('seller_bank_detail',array('seller_user_id' => $id));
            $salon_id =  $Common->select_get_row_data('salon-list',array('user_id' => $check_users->id),'id');
            
            $new_partner_view_arr = array();
            if($check_users->status == 1){
                    $status = 'Active';
            }
            if($check_users->status == 0){
                $status = 'Blocked';
            }
            if($check_users->status == 2){
                $status = 'pending';
            }
            $new_partner_view_arr['id'] = $check_users->id;
            $new_partner_view_arr['name'] = $check_users->name;
            $new_partner_view_arr['address'] = $check_users->address;
            $new_partner_view_arr['salon_name'] = $Common->select_get_row_data('salon-list',array('user_id' => $check_users->id),'salon_name');
            $new_partner_view_arr['phone'] = $check_users->phone;
            $new_partner_view_arr['email'] = $check_users->email;
            $new_partner_view_arr['pan'] = $Common->select_get_row_data('seller_bank_detail',array('seller_user_id' => $check_users->id),'pan_card');
            $new_partner_view_arr['account_number'] = $Common->select_get_row_data('seller_bank_detail',array('seller_user_id' => $check_users->id),'account_number');
            $new_partner_view_arr['upi_id'] = $Common->select_get_row_data('seller_bank_detail',array('seller_user_id' => $check_users->id),'upi_id');
            $new_partner_view_arr['account_holder_name'] = $Common->select_get_row_data('seller_bank_detail',array('seller_user_id' => $check_users->id),'account_holder_name');
            $new_partner_view_arr['adhar_card'] = $Common->select_get_row_data('seller_bank_detail',array('seller_user_id' => $check_users->id),'adhar_card');
            $new_partner_view_arr['ifsc_number'] = $Common->select_get_row_data('seller_bank_detail',array('seller_user_id' => $check_users->id),'ifsc_number');
            $new_partner_view_arr['city'] =  $Common->select_get_row_data('cities',array('id' => $check_users->city),'name');
            $new_partner_view_arr['pincode'] = $check_users->pincode;
            $new_partner_view_arr['status'] = $status;
            $new_partner_view_arr['state'] = $Common->select_get_row_data('states',array('id' => $check_users->state),'name');
            $new_partner_view_arr['register_date'] = date('Y-m-d', strtotime($check_users->created_at));
            
         
           $booking_list = $Common->where_selectAll('salon_booking',array('salon_id' => $salon_id),'');
           
           $booking_arr = array();
           if(isset($booking_list) && !empty($booking_list)){
               foreach($booking_list as $booking){
                    $status = '';
                    if($booking->booking_status == 1){
                        $status = "Booked";
                    }
                    if($booking->booking_status == 2){
                        $status = "Booking Accept";
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
                    
                    $user_token = $Common->select_get_row_data('notification_token',array('user_id'=> $booking->user_id),'Token');
			        $token =  isset($user_token) ? $user_token : '';
			        
			        $image = $Common->select_get_row_data('users', array('id' => $booking->user_id),'image');
			        if($image == ''){
			             $image = base_url().'assets/profile_pic/default_pro_pic.png';
			        }else{
			            $image = base_url().'assets/profile_pic/users/'.$image;
			        }
                    $data = array(
                        'image' => $image,
                        'user_name' => $Common->select_get_row_data('users', array('id' => $booking->user_id),'name'),
                        'service' => $Common->select_get_row_data('salon-services', array('id' => $booking->service_id),'title'),
                        'book_date' => date('M d, Y', strtotime($booking->booking_date)),
                        'booking_time' => $booking->booking_time,
                        'total_amount' => $booking->total_amount,
                        'invoice_no' => $booking->id,
                    );    
                array_push($booking_arr, $data);  
               }
           }
            
            $banner_images =  $Common->where_selectAll('salon_banner_image',array('salon_id' => $salon_id),'');
            $banner_images_arr = array();
            if(isset($banner_images) && !empty($banner_images)){
                foreach($banner_images as $banner_image){
                    $banner_images = array();
                    $banner_images['id'] = $banner_image->id;
                    $banner_images['image'] = base_url().'assets/images/salon/banner/'.$banner_image->image;
                    array_push($banner_images_arr, $banner_images);
                }
            }
            $our_gallery_images =  $Common->where_selectAll('salon-portfolio',array('salon_id' => $salon_id),'');
            $our_gallery_images_arr = array();
            if(isset($our_gallery_images) && !empty($our_gallery_images)){
                foreach($our_gallery_images as $our_gallery_image){
                    $our_gallery_images = array();
                    $our_gallery_images['id'] = $our_gallery_image->id;
                    $our_gallery_images['image'] = base_url().'assets/images/salon/portfolio/'.$our_gallery_image->image;
                    array_push($our_gallery_images_arr, $our_gallery_images);
                }
            }
            
            $response_arr = array('user_detail' => $new_partner_view_arr, 'bookings' => $booking_arr, 'banner_images' => $banner_images_arr, 'our_gallery' => $our_gallery_images_arr);
            if(isset($response_arr) && !empty($response_arr)){
                $response = array('message'=> 'Partner View','code'=> 200,'view' => $response_arr);
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
    
    public function remove_banner(){
        $Common = new commn();
        $id = $this->input->post('id');
        $banner_images =  $Common->delete_data('salon_banner_image',array('id' => $id),'');
       if($banner_images == 1){
            $response = array('message'=> 'Image Deleted Successfully','code'=> 200);
               echo json_encode($response);   
           
       }else{
               $response = array('message'=> 'Image not found','code'=> 400);
               echo json_encode($response);   
           }
    }
       public function remove_gallery_image(){
        $Common = new commn();
        $id = $this->input->post('id');
        $banner_images =  $Common->delete_data('salon-portfolio',array('id' => $id),'');
        if($banner_images == 1){
            $response = array('message'=> 'Image Deleted Successfully','code'=> 200);
               echo json_encode($response);   
           
       }else{
               $response = array('message'=> 'Image not found','code'=> 400);
               echo json_encode($response);   
        }
       
    }
}
?>
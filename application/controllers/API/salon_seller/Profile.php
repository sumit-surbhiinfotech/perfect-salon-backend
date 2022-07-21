<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
class Profile extends CI_Controller {
	public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('commn');
    }
    
    
    public function get_profile(){
        $Commn = new Commn();
        $id = $this->input->post('seller_user_id');
        if($id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $id, 'role' => 2));
        
        if(isset($user_data)){
            
            $adharcard =  $Commn->select_get_row_data('seller_bank_detail',array('seller_user_id'=> $id),'adhar_card');
            if($adharcard != null && $adharcard != ''){
               $user_data->adharcard = $adharcard;
            }else{
                 $user_data->adharcard = '';
            }
            $pan_card =  $Commn->select_get_row_data('seller_bank_detail',array('seller_user_id'=> $id),'pan_card');
            if($pan_card != null && $pan_card != ''){
               $user_data->pan_card = $pan_card;
            }else{
                 $user_data->pan_card = '';
            }
            if($user_data->image == '' ||  $user_data->image == null){
                $user_data->image =  base_url().'assets/profile_pic/default_pro_pic.png'; 
            }else{
                $user_data->image =  base_url().'assets/profile_pic/seller_user/'.$user_data->image;
            }
            $user_data->state =  $Commn->select_get_row_data('states',array('id'=> $user_data->state),'name');
            $user_data->city =  $Commn->select_get_row_data('cities',array('id'=> $user_data->city),'name');
            if($user_data->register_date == '' && $user_data->register_date == null){
                $user_data->register_date =  date('Y-m-d', strtotime($user_data->created_at));
            }
            $user_data->register_date =  date('Y-m-d', strtotime($user_data->register_date));
            $user_data->current_step = $Commn->select_get_row_data('seller_steps',array('user_id'=> $user_data->id),'current_step');
            if($user_data->current_step == "" && $user_data->current_step == null){ $user_data->current_step = "6";}
            $response = array('message'=> 'successfully get Profile','code'=> 200,'user'=> $user_data);
            echo json_encode($response);
        }else{
            $response = array('message'=> 'user not found','code'=> 400);
            echo json_encode($response);
        } 
        
    }
    
    public function update_seller_profile(){

       $Commn = new Commn();
       $seller_user_id = $this->input->post('seller_user_id');
       $username = $this->input->post('username');
       $gender = $this->input->post('gender');
       $pan_card = $this->input->post('pan_card');
       $adhar_card = $this->input->post('adhar_card');
       $state = $this->input->post('state');
       $city = $this->input->post('city');
       $pincode = $this->input->post('pincode');
       $address = $this->input->post('address');
       
       $register_status =0;

       
       if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
       }
        
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        if(isset($user_data) & !empty($user_data)){
            $banks =  $Commn->get_row_data('seller_bank_detail',array('seller_user_id'=> $seller_user_id));
                $b = array(
                        'pan_card' => $pan_card,
                        'adhar_card' => $adhar_card,
                        'seller_user_id' => $seller_user_id
                );
            if(isset($banks) & !empty($banks)){
                $b_update =  $Commn->update_data('seller_bank_detail', $b,array('id' => $banks->id));
            }else{
                $b_add =  $Commn->insert_data('seller_bank_detail', $b);
            }
            $user = array(
                'username' => $username,
                'gender' => $gender,
                'state' => $state,
                'city' => $city,
                'pincode' => $pincode,
                'address' => $address
            );
            $res =  $Commn->update_data('users', $user,array('id' => $seller_user_id));
            if($res == 1){
                $response = array('message'=> 'successfully Update','code'=> 200);
                echo json_encode($response);
            }                
            
        }else{
           $response = array('message'=> 'Seller user not found','code'=> 400);
           echo json_encode($response); 
        }
    }
    
    public function update_bank_details(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        $ifsc_code = $this->input->post('ifsc_code');
        $bank_name = $this->input->post('bank_name');
        $branch_address = $this->input->post('branch_address');
        $bank_account_holder_name = $this->input->post('bank_account_holder_name');
        $account_number = $this->input->post('account_number');
        $confirm_account_number = $this->input->post('confirm_account_number');
        $upi_number = $this->input->post('upi_number');
        
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
            
        if(empty($user_data)){
            $response = array('message'=> 'Seller user not found','code'=> 400);
            echo json_encode($response); 
            return false;
        }
       
        if($ifsc_code != ''){
             $suceess_update = 0;
            if($bank_name == ''){
                $response = array('message'=> 'Bank name field is required','code'=> 400);
                echo json_encode($response);
                return false; 
            }
            if($branch_address == ''){
                $response = array('message'=> 'Branch address field is required','code'=> 400);
                echo json_encode($response);
                return false; 
            }
            if($bank_account_holder_name == ''){
                $response = array('message'=> 'Bank Account Holder name field is required','code'=> 400);
                echo json_encode($response);
                return false; 
            }
            if($account_number == ''){
                $response = array('message'=> 'Bank Account Number field is required','code'=> 400);
                echo json_encode($response);
                return false; 
            }
            if($confirm_account_number == ''){
                $response = array('message'=> 'Confirm Bank Account Number field is required','code'=> 400);
                echo json_encode($response);
                return false; 
            }
            if($confirm_account_number != $account_number){
                $response = array('message'=> 'Bank Account Number and Confirm Bank Account Number not match','code'=> 400);
                echo json_encode($response);
                return false; 
            }
            
            $bank_details = array(
                'bank_name' => $bank_name,
                'branch_name' => $branch_address, 
                'account_holder_name' => $bank_account_holder_name,
                'account_number' => $account_number,
                'ifsc_number' => $ifsc_code,
                'upi_id' => $upi_number
            );
            // $seller_user_id
            $user_bank =  $Commn->get_row_data('seller_bank_detail',array('seller_user_id'=> $seller_user_id));
            if(isset($user_bank) & !empty($user_bank)){
                 $b_update =  $Commn->update_data('seller_bank_detail', $bank_details,array('id' => $user_bank->id));
                 if($b_update == 1){
                    $suceess_update = 1;
                 }
            }else{
                $b_add =  $Commn->insert_data('seller_bank_detail', $bank_details);
                if($b_add == 1){
                    $suceess_update = 1;
                }
            }
            
            if($suceess_update == 1){
              $response = array('message'=> 'successfully Update','code'=> 200);
              echo json_encode($response);
            }else{
              $response = array('message'=> 'Somthing wrong','code'=> 400);
              echo json_encode($response);  
            }
            
        }else{
            $response = array('message'=> 'successfully Update','code'=> 200);
            echo json_encode($response);
        }
        
    }
    
    public function get_bank_detail(){
        $Commn = new Commn();
        $id = $this->input->post('seller_user_id');
        if($id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $id, 'role' => 2));
        
        if(isset($user_data)){
            
            $seller_bank_detail =  $Commn->get_row_data('seller_bank_detail',array('seller_user_id'=> $id));
            if(!empty($seller_bank_detail) && isset($seller_bank_detail)){
                $response_data = array(
                    'ifsc_number' => $seller_bank_detail->ifsc_number,
                    'bank_name' => $seller_bank_detail->bank_name,
                    'branch_address' => $seller_bank_detail->branch_name,
                    'account_holder_name' => $seller_bank_detail->account_holder_name,
                    'account_number' => $seller_bank_detail->account_number,
                    'upi_id' => $seller_bank_detail->upi_id,
                    'seller_user_id' => $seller_bank_detail->seller_user_id
                );
                $response = array('message'=> 'successfully get bank detail','code'=> 200,'bank'=> $response_data);
                echo json_encode($response); 
            }else{
                $response = array('message'=> 'bank detail not found','code'=> 400);
                echo json_encode($response); 
            }
            
        }else{
            $response = array('message'=> 'user not found','code'=> 400);
            echo json_encode($response);
        }     
    }
    
    public function update_password(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        $old_password = $this->input->post('old_password');
        $new_password = $this->input->post('new_password');
        $confirm_new_password = $this->input->post('confirm_new_password');
        
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        
        if($old_password == ''){
            $response = array('message'=> 'Old Password field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($new_password == ''){
            $response = array('message'=> 'New Password field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($confirm_new_password == ''){
            $response = array('message'=> 'Confirn New Password field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($confirm_new_password!== $new_password){
            $response = array('message'=> 'New Password and Confirn New Password not match','code'=> 400);
            echo json_encode($response);
            return false;
        }
            
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
   
        if(isset($user_data) & !empty($user_data)){
           
            if($user_data->password != md5($old_password))
            {
                $response = array('message'=> 'Old password is wrong','code'=> 400);
                echo json_encode($response);
                return false;
            }else{
                
                $update_pass =  array('password'=> md5($new_password));
                $new_update_pass =  $Commn->update_data('users', $update_pass,array('id' => $user_data->id));
                if($new_update_pass == 1){
                    $response = array('message'=> 'successfully Update password','code'=> 200);
                    echo json_encode($response);        
                }else{
                     $response = array('message'=> 'somthing wrong','code'=> 400);
                    echo json_encode($response);    
                }
            }
        }else{
            $response = array('message'=> 'Seller user not found','code'=> 400);
            echo json_encode($response);    
        }
        
        
    }
    
    
    public function update_status(){
       $Commn = new Commn();
       $seller_user_id = $this->input->post('seller_user_id');    
       if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
       }
       $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        if(isset($user_data) & !empty($user_data)){
            if($user_data->status == 1){
                $update_status_val = 0;
            }else{
                $update_status_val = 1;
            }
            if(isset($update_status_val)){
                $status_update =  $Commn->update_data('users', array('status' => $update_status_val),array('id' => $user_data->id));
                if($status_update == 1){
                    $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
                    if($user_data->status == 1){
                        $response = array('message'=> 'User is active','code'=> 200);
                        echo json_encode($response);  
                    }else{
                         $response = array('message'=> 'User is deactive','code'=> 200);
                        echo json_encode($response); 
                    }  
                }else{
                    $response = array('message'=> 'somthing wrong','code'=> 400);
                    echo json_encode($response);    
                }
            }
        }else{
           $response = array('message'=> 'Seller user not found','code'=> 400);
           echo json_encode($response);            
        }
    }
    
    public function update_profile_pic(){
        $Commn = new Commn();
        $user_id = $this->input->post('user_id');
        $image_file = $this->input->post('image_file');
        $upload_path="assets/profile_pic/seller_user";
         //creare seperate folder for each user 
        // $upPath=upload_path."/".$uid;
        // if(!file_exists($upPath)) 
        // {
        //           mkdir($upPath, 0777, true);
        // }
        $config = array(
        'upload_path' => $upload_path,
        // 'allowed_types' => "gif|jpg|png|jpeg",
         'allowed_types' => '*',
        'overwrite' => TRUE,
        'max_size' => "2048000", 
        // 'max_height' => "768",
        // 'max_width' => "1024"
        );
        $this->load->library('upload', $config);
        if(!$this->upload->do_upload('image_file'))
        { 
            $data['imageError'] =  $this->upload->display_errors();
            
            if(isset($data['imageError']) && !empty($data['imageError'])){
                $response = array('message'=> 'Profile pic not upload','code'=> 400,'error'=> $data['imageError']);
                echo json_encode($response);
                return false;
            }

        }
        else
        {
            $imageDetailArray = $this->upload->data();
            $image =  $imageDetailArray['file_name'];
            $update = $Commn->update_data('users',array('image' => $image), array('id' => $user_id));
            if($update == 1){
                $user_data =  $Commn->get_row_data('users',array('id'=> $user_id));
                if($user_data->image == '' ||  $user_data->image == null){
                    $user_data->image =  base_url().'assets/profile_pic/default_pro_pic.png'; 
                }else{
                    $user_data->image =  base_url().'assets/profile_pic/seller_user/'.$user_data->image;
                }
                $response = array('message'=> 'successfully update profile Picture','code'=> 200,'user'=> $user_data->image);
                echo json_encode($response);
                return false;
            }
        }
    }
}
?>
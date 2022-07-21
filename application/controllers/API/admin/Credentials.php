<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

class Credentials extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('commn');
    }
    
    
    public function register(){
        
        $Commn = new commn();
        
        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $phone = $this->input->post('phone');        
        $password = $this->input->post('password');
        $conform_password = $this->input->post('confirm_password');
        
        if($name == ''){
            $response = array('message'=> 'Name is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($email == ''){
            $response = array('message'=> 'Email is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($phone == ''){
            $response = array('message'=> 'Phone is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($password == ''){
            $response = array('message'=> 'Password is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($conform_password == ''){
            $response = array('message'=> 'Confirm Password is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($password != $conform_password){
            $response = array('message'=> 'Password and Conform Password is not match','code'=> 400);
            echo json_encode($response);
            return false;
        }
        
        $where =  array('email' => $email);
        $exist_email = $Commn->get_row_data('admin', $where);
        if(isset($exist_email) && !empty($exist_email)){
            $response = array('message'=> 'Email Address already exist','code'=> 400);
            echo json_encode($response);
            return false;    
        }
        $admin_data = array(
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => md5($password),
        );
        $admin_register = $Commn->insert_data('admin', $admin_data);
        if($admin_register == 1){
            $response = array('message'=> 'Successfully register admin','code'=> 200);
            echo json_encode($response);    
        }else{
            $response = array('message'=> 'Somthing wrong','code'=> 400);
            echo json_encode($response);    
        }
    }
    
    public function login(){
        $Commn = new commn();
        
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        
        if($email == ''){
            $response = array('message'=> 'Email is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($password == ''){
            $response = array('message'=> 'Password is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        
        $where =  array('email' => $email);
        $get_admin = $Commn->get_row_data('admin', $where);
        $login_status = 0;
        if(isset($get_admin) && !empty($get_admin)){
            if($get_admin->password != md5($password)){
                $response = array('message'=> 'Password is wrong','code'=> 400);
                echo json_encode($response);
                return false;    
            }else{
                if($get_admin->status == 0){
                    $response = array('message'=> 'You are blocked','code'=> 400);
                    echo json_encode($response);
                    return false;    
                }else{
                    $login_status = 1;     
                }
            }
            
            if($login_status == 1){
                $response = array('message'=> 'Successfully login','code'=> 200,'response' => $get_admin);
                echo json_encode($response);    
            }else{
                $response = array('message'=> 'Somthing Wrong','code'=> 400);
                echo json_encode($response);    
            }
            
        }else{
          $response = array('message'=> 'Email and Password is wrong','code'=> 404);
          echo json_encode($response);
          return false;
        }
    }
    
    public function forgot_password(){
        $Commn = new commn();
        
        $email = $this->input->post('email');  
        if($email == ''){
            $response = array('message'=> 'Email is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $where =  array('email' => $email);
        $get_admin = $Commn->get_row_data('admin', $where);
        $token = md5($get_admin->email).'__'.$get_admin->id;
         $digits = 4;
        $otp = rand(pow(10, $digits-1), pow(10, $digits)-1);
        if(isset($get_admin) && !empty($get_admin)){
            
             $this->load->library('email'); 
   
             $this->email->from('sumit.surbhiinfotech@gmail.com', $get_admin->name); 
             $this->email->to($get_admin->email);
             $this->email->subject('Forgot Password'); 
             $this->email->message('<p>Your otp is: '.$otp.'</p>'); 
       
             //Send mail 
             if($this->email->send()){
                    $response = array('message'=> 'Successfully sent Mail','code'=> 200,'otp'=> $otp,'token'=>$token);
                    echo json_encode($response);
             }else{ 
                    $response = array('message'=> 'Somthing Wrong','code'=> 400);
                    echo json_encode($response);
             }
            
        }else{
              $response = array('message'=> 'Email is wrong','code'=> 404);
              echo json_encode($response);
              return false;            
        }
    }
    
    public function change_password(){
        $Commn = new commn();
        
        $token = $this->input->post('token');
        $password = $this->input->post('password');
        $confirm_password = $this->input->post('confirm_password');
        if($token == ''){
            $response = array('message'=> 'Token is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($password == ''){
            $response = array('message'=> 'Password is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($confirm_password == ''){
            $response = array('message'=> 'Confirm Password is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($password != $confirm_password){
            $response = array('message'=> 'Password and Confirm Password not match','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $token = explode('__', $token);
        $where =  array('id' => isset($token[1]) ? $token[1] : '');
        $get_admin = $Commn->get_row_data('admin', $where);
        $forgot_status = 0;
        if(isset($get_admin) && !empty($get_admin)){
            $md5_email =  isset($token[0]) ? $token[0] : '';
            if(md5($get_admin->email) != $md5_email){
                $response = array('message'=> 'Token is invalid','code'=> 400);
                echo json_encode($response);
                return false;    
            }else{
                $forgot_status = 1;
            }
        }else{
            $response = array('message'=> 'Token is invalid','code'=> 400);
            echo json_encode($response);
            return false;    
        }
        

        if($forgot_status == 1){
            $change_password_data = array('password' => md5($password));
            $where = array('id' => $get_admin->id);
            $update_admin_password = $Commn->update_data('admin', $change_password_data, $where);
            if($update_admin_password == 1){
                $response = array('message'=> 'Successfully Update Password','code'=> 200);
                echo json_encode($response);                
            }else{
                $response = array('message'=> 'Somthing Wrong','code'=> 400);
                echo json_encode($response);                
            }
        }else{
              $response = array('message'=> 'Somthing Wrong','code'=> 400);
              echo json_encode($response);                
        }
    }
}

?>
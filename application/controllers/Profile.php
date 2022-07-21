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

    public function profile(){
        $Commn = new Commn();
        $id = $this->input->get('user_id');
        if($id == ''){
            $response = array('message'=> 'User_id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $id));
        if($user_data->image == '' ||  $user_data->image == null){
           $user_data->image =  base_url().'assets/profile_pic/default_pro_pic.png'; 
        }else{
            $user_data->image =  base_url().'assets/profile_pic/users/'.$user_data->image;
        }
        $user_data->state =  $Commn->select_get_row_data('states',array('id'=> $user_data->state),'name');
        $user_data->city =  $Commn->select_get_row_data('cities',array('id'=> $user_data->city),'name');
        if($user_data->register_date == '' && $user_data->register_date == null){
            $user_data->register_date =  date('Y-m-d', strtotime($user_data->created_at));
        }
        $user_data->register_date =  date('Y-m-d', strtotime($user_data->register_date));
        if(isset($user_data)){
            $response = array('message'=> 'successfully get Profile','code'=> 200,'user'=> $user_data);
            echo json_encode($response);
        }else{
            $response = array('message'=> 'user not found','code'=> 400);
            echo json_encode($response);
        }   
    }

    public function update_profile(){

       $Commn = new Commn();
       $user_id = $this->input->post('user_id');
       $name = $this->input->post('name');
       $email = $this->input->post('email');
       $address = $this->input->post('address');
       $state = $this->input->post('state');
       $city = $this->input->post('city');
       $pincode = $this->input->post('pincode');
       $alt_phone = $this->input->post('alt_phone');
       
       $register_status =0;

       
       if($user_id == ''){
            $response = array('message'=> 'User_id field is required','code'=> 400);
            echo json_encode($response);
            return false;
       }
        
        $user_data =  $Commn->get_row_data('users',array('id'=> $user_id));
        if(isset($user_data) & !empty($user_data)){
       
        if(trim($user_data->email) != trim($email)){
            $email_exist =  $Commn->get_row('users',array('email'=> $email));
            if($email_exist == 1){
                $response = array('response'=> 'already resgister email','code'=> 400);
                echo json_encode($response);
                return false;
            }else{
                $register_status = 1;
            }
        }else{
            
            $register_status = 1;
            
        }
        }else{
                $response = array('response'=> 'User Id is wrong','code'=> 400);
                echo json_encode($response);    
        }
        
        if($register_status == 1){
            $user = array(
                'email' => $email,
                'name' => $name,
                'address' => $address,
                'state' => $state,
                'city' => $city,
                'pincode' => $pincode,
                'alt_phone' => $alt_phone
            );
            $res =  $Commn->update_data('users', $user,array('id' => $user_id));
            if($res == 1){
                $response = array('message'=> 'successfully Update','code'=> 200);
                echo json_encode($response);
            }
        }
    }
    
    public function update_profile_pic(){
        $Commn = new Commn();
        $user_id = $this->input->post('user_id');
        $image_file = $this->input->post('image_file');
        $upload_path="assets/profile_pic/users";
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
                    $user_data->image =  base_url().'assets/profile_pic/users/'.$user_data->image;
                }
                $response = array('message'=> 'successfully update profile Picture','code'=> 200,'user'=> $user_data->image);
                echo json_encode($response);
                return false;
            }
        }
    }
}
?>
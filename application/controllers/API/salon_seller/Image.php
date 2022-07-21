<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
class Image extends CI_Controller {
	public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('commn');
    }
    
    public function get_gallery_image(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        $get_gallery_arr = array();
        if(isset($user_data) && !empty($user_data)){
            $seller_id =  $Commn->select_get_row_data('salon-list',array('user_id'=> $user_data->id),'id');
            $get_gallerys =  $Commn->where_selectAll('salon-portfolio',array('salon_id'=> $seller_id),'');
            
            if(isset($get_gallerys) && !empty($get_gallerys)){
                foreach($get_gallerys as $get_gallery){
                    $image = array('id'=>$get_gallery->id,'image'=> base_url().'assets/images/salon/portfolio/'.$get_gallery->image);
                    array_push($get_gallery_arr, $image);
                }
                $response = array('message'=> 'Successfully Gallery Image','code'=> 200,'gallery_image' => $get_gallery_arr);
                echo json_encode($response); 
            }else{
                $response = array('message'=> 'Gallery Image not found','code'=> 400);
                echo json_encode($response);    
            }
        }else{
            $response = array('message'=> 'user not found','code'=> 400);
            echo json_encode($response);            
        }
    }
    
    public function delete_gallery_image(){
        $Commn = new Commn();
        $gallery_image_id = $this->input->post('gallery_image_id');
        if($gallery_image_id == ''){
            $response = array('message'=> 'gallery image id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $gallery_image =  $Commn->get_row_data('salon-portfolio',array('id'=> $gallery_image_id));
        if(isset($gallery_image) && !empty($gallery_image)){
              $delete_image =  $Commn->delete_data('salon-portfolio',array('id'=> $gallery_image_id));
              if($delete_image == 1){
                $response = array('message'=> 'successfully delete image','code'=> 200);
                echo json_encode($response);               
              }else{
                  $response = array('message'=> 'somthing wrong','code'=> 400);
                  echo json_encode($response);
              }
        }else{
            $response = array('message'=> 'Image id is wrong','code'=> 400);
            echo json_encode($response);             
        }
    }
    
    public function add_gallery_image(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        $gallery_image = $this->input->post('gallery_image');
        $gallery_image_file =  $_FILES['gallery_image'];
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if(empty($gallery_image_file['name'])){
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
                $response = array('message'=> 'successfully add new gallery image','code'=> 200);
                echo json_encode($response);    
            }else{
                $response = array('message'=> 'somthing wrong','code'=> 400);
                echo json_encode($response);    
            }
            
        }else{
            $response = array('message'=> 'user not found','code'=> 400);
            echo json_encode($response);            
        }
    }
    
    public function add_banner_image(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        $banner_image = $this->input->post('banner_image');
        $banner_image_file =  $_FILES['banner_image'];
        
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
                    $step_data =  $Commn->get_row_data('seller_steps',array('user_id'=> $seller_user_id));
                    if(isset($step_data) && !empty($step_data)){
                        if($step_data->current_step == 4){
                            $next_step = 5;
                        }else{
                             $next_step = $step_data->current_step;
                        }
                    }else{
                        $next_step = 6;
                    }
                    $step_update_data = array(
                        'steps' => '1=>general_settings:status=>1,2=>services_settings:status=>1,3=>date/time_settings:status=>1,4=>image_settings:status=>1,5=>descripation_settings:status=>0,1=>home_screen:status=>0',
                        'current_step' => $next_step,
                    );
                    $step_where = array('user_id' => $seller_user_id);
                    $update_step =  $Commn->update_data('seller_steps',$step_update_data,$step_where);
                    if($update_step == 1){    
                        $current_step = $Commn->select_get_row_data('seller_steps',array('user_id'=> $seller_user_id),'current_step');
    			        if($current_step == "" && $current_step == null){ $current_step = "6";}else{ $current_step = ($current_step); }
                        $response = array('message'=> 'successfully add Banner gallery image','code'=> 200 ,"current_step" => $current_step);
                        echo json_encode($response); 
                    }else{
                     $response = array('message'=> 'Something else','code'=> 400);
                     echo json_encode($response);   
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
    
    public function get_banner_image(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        $get_banner_arr = array();
        if(isset($user_data) && !empty($user_data)){
            $seller_id =  $Commn->select_get_row_data('salon-list',array('user_id'=> $user_data->id),'id');
            $banner_images =  $Commn->where_selectAll('salon_banner_image',array('salon_id'=> $seller_id),'');
            
            if(isset($banner_images) && !empty($banner_images)){
                foreach($banner_images as $banner_image){
                    $image = array('id'=>$banner_image->id,'image'=> base_url().'assets/images/salon/banner/'.$banner_image->image,'status' => $banner_image->status);
                    array_push($get_banner_arr, $image);
                }
                $response = array('message'=> 'Successfully Banner Image','code'=> 200,'banner_image' => $get_banner_arr);
                echo json_encode($response); 
            }else{
                $response = array('message'=> 'Banner Image not found','code'=> 400);
                echo json_encode($response);    
            }
        }else{
            $response = array('message'=> 'user not found','code'=> 400);
            echo json_encode($response);            
        }
    }
    
    public function delete_banner_image(){
        $Commn = new Commn();
        $banner_image_id = $this->input->post('banner_image_id');
        if($banner_image_id == ''){
            $response = array('message'=> 'banner image id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $banner_image =  $Commn->get_row_data('salon_banner_image',array('id'=> $banner_image_id));
        if(isset($banner_image) && !empty($banner_image)){
              $delete_image =  $Commn->delete_data('salon_banner_image',array('id'=> $banner_image_id));
              if($delete_image == 1){
                $response = array('message'=> 'successfully delete image','code'=> 200);
                echo json_encode($response);               
              }else{
                  $response = array('message'=> 'somthing wrong','code'=> 400);
                  echo json_encode($response);
              }
        }else{
            $response = array('message'=> 'Image id is wrong','code'=> 400);
            echo json_encode($response);             
        }
    }
    
    public function banner_change_status(){
        $Commn = new Commn();
        $banner_image_id = $this->input->post('banner_image_id');
        if($banner_image_id == ''){
            $response = array('message'=> 'banner image id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $banner_image =  $Commn->get_row_data('salon_banner_image',array('id'=> $banner_image_id));
        if(isset($banner_image) && !empty($banner_image)){
            $update_status = 0;
             if($banner_image->status == 1){
                $update_status = 0;     
             }else{
                 $update_status = 1;
             }
          $status_update = $Commn->update_data('salon_banner_image',array('status' => $update_status),array('id' => $banner_image->id));
          if($status_update == 1){
               $banner_image =  $Commn->get_row_data('salon_banner_image',array('id'=> $banner_image_id));
                if($banner_image->status == 1){
                    $response = array('message'=> 'banner image is active','code'=> 200);
                    echo json_encode($response);    
                }else{
                    $response = array('message'=> 'banner image is deactive','code'=> 200);
                    echo json_encode($response);    
                }
           }else{
                    $response = array('message'=> 'somthing wrong','code'=> 400);
                    echo json_encode($response);
           }
        }else{
            $response = array('message'=> 'Image id is wrong','code'=> 400);
            echo json_encode($response);     
        }
    }
}
?>
<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
date_default_timezone_set('Asia/Kolkata');

class Notification extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('commn');
    }
    
    
    public function push_token(){
        $_uv_Token = $this->input->post('token');
        $user_id = $this->input->post('user_id');
        $Commn = new Commn();
        if($_uv_Token == ''){
            $response = array('message'=> 'token field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
         if($user_id == ''){
            $response = array('message'=> 'user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $get_user_data =  $Commn->get_row_data('users',array('id'=> $user_id));
        if(isset($get_user_data) && !empty($get_user_data)){
            $user_data =  $Commn->get_row_data('notification_token',array('user_id'=> $user_id));
            
            

            if(isset($user_data) && !empty($user_data)){
                 $un_like = $Commn->update_data('notification_token',array('Token' => $_uv_Token),array('user_id'=> $user_id));
                 $response = array('message'=> 'update token','code'=> 200);
                  echo json_encode($response);
            }else{
                $result = $this->db->query("INSERT INTO notification_token (Token,user_id) VALUES ( '$_uv_Token','$user_id') "." ON DUPLICATE KEY UPDATE Token = '$_uv_Token';");
            
                if($result){
                    $response = array('message'=> 'successfully added token','code'=> 200);
                    echo json_encode($response);
         
                }else{
                    $response = array('message'=> 'something wrong','code'=> 400);
                    echo json_encode($response);    
                }
            }    
        }else{
            $response = array('message'=> 'user id is wrong','code'=> 400);
            echo json_encode($response);             
        }
        
        
    }
    
    
    public function send_notification(){
	    
	    $tokens =  $this->input->post('token');
	    $message =  $this->input->post('message');
		$url = 'https://fcm.googleapis.com/fcm/send';
		if($tokens == ''){
            $response = array('message'=> 'token field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($message == ''){
            $response = array('message'=> 'message field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
// 		$fields = array(
// 			 'registration_ids' => array($tokens),
// 			 'data' =>  array("message" => $message)
// 			);

// 		$headers = array(
// // 			'Authorization:key = AAAATQOk-nc:APA91bGrPFRan3RSvAtamiYDXd5p0OFsu04m6XNt3eJx9zhXPH5eW0h7oVEAMELWFd5ELw322LSFNe_yIZfCej9XKFUcooTalEPhjut4KKwcRVwSxjUuCLECqnlXwBYHwarecfQL7YO4',
//             'Authorization:key = AAAA2pva1uo:APA91bH7mBfSBnfeAGPuRg8LKgb2DRpObrCcgz3V1btOjzM7Q6suc2m_60Gw-Py3JzFGKKmcHsGBqZuia7hGie5CdEoz4ar2xI7v-_u3Rm_G0OpdPELsT2CaibPklv8-2L_8318DqpoJ',
// 			'Content-Type: application/json'
// 			);

        // 
        // 
        // 
        // 
        $headers = [
         'Authorization: key=AAAA2pva1uo:APA91bH7mBfSBnfeAGPuRg8LKgb2DRpObrCcgz3V1btOjzM7Q6suc2m_60Gw-Py3JzFGKKmcHsGBqZuia7hGie5CdEoz4ar2xI7v-_u3Rm_G0OpdPELsT2CaibPklv8-2L_8318DqpoJ',
         'Content-Type: application/json'
        ];
        
        $notification = [
          'title' => '',
          'body' => $message
        ];
        
        $request = [
          'notification' => $notification,
          'registration_ids' => array($tokens),
          'priority' => 'high',
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        // curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
        
        $res = curl_exec($ch);
        curl_close($ch);
        
        
        
	   //$ch = curl_init();
    //   curl_setopt($ch, CURLOPT_URL, $url);
    //   curl_setopt($ch, CURLOPT_POST, true);
    //   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //   curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
    //   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    //   $result = curl_exec($ch);           
    //   if ($result === FALSE) {
    //       die('Curl failed: ' . curl_error($ch));
    //   }
    //   curl_close($ch);
       echo $res;
	}
	
	public function get_token(){
	    $Commn = new Commn();
        $salon_id = $this->input->post('salon_id');
        $user_id = $this->input->post('user_id');
        if($salon_id == ''){
            $response = array('message'=> 'salon id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
         if($user_id == ''){
            $response = array('message'=> 'user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $user_id));
        $salon_data =  $Commn->get_row_data('salon-list',array('id'=> $salon_id));
        if(empty($user_data)){
            $response = array('message'=> 'user not found','code'=> 400);
            echo json_encode($response);     
            return false;
        //   $seller_id =  $Commn->select_get_row_data('salon-list',array('id'=> $salon_id),'id');
        }
        if(empty($salon_data)){
            $response = array('message'=> 'salon not found','code'=> 400);
            echo json_encode($response);     
            return false;
        //   $seller_id =  $Commn->select_get_row_data('salon-list',array('id'=> $salon_id),'id');
        }
        if(!empty($salon_data)){
            $seller_user_id =  $Commn->select_get_row_data('salon-list',array('id'=> $salon_id),'user_id');
            $salon_token = $Commn->select_get_row_data('notification_token',array('user_id'=> $seller_user_id),'Token');
        }
        if(!empty($user_data)){
            $user_token = $Commn->select_get_row_data('notification_token',array('user_id'=> $user_data->id),'Token');
        }
        
        $response = array('message'=> 'get token','code'=> 200,'token' => array("salon_token" => $salon_token, 'user_token' => $user_token));
        echo json_encode($response);  
        
        
	}
	
	public function save_notification(){
        $user_id = $this->input->post('user_id');
        $salon_id = $this->input->post('salon_id');
        $message = $this->input->post('message');
        $when_user = $this->input->post('when_user');
        $Commn = new Commn();
        if($user_id == ''){
            $response = array('message'=> 'User Id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($salon_id == ''){
            $response = array('message'=> 'Salon id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($message == ''){
            $response = array('message'=> 'message field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($when_user == ''){
            $response = array('message'=> 'when user field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
         $seller_user_id =  $Commn->select_get_row_data('salon-list',array('id'=> $salon_id),'user_id');
         if(empty($seller_user_id)){
            $response = array('message'=> 'salon id is wrong','code'=> 400);
            echo json_encode($response);
            return false;     
         }
         $user_data =  $Commn->get_row_data('users',array('id'=> $user_id, 'role' => 1));
         if(empty($user_data)){
            $response = array('message'=> 'user id is wrong','code'=> 400);
            echo json_encode($response);
            return false;     
         }
         $notification_data = array('user_id' => $user_id, 'salon_id' => $salon_id, 'seller_id' => $seller_user_id,'message' => $message, 'when_user' => $when_user,'created_at' => date('Y-m-d H:i:s'));
         $notification =  $Commn->insert_data('notification_list',$notification_data);
         if($notification == 1){
            $response = array('message'=> 'Successfully added notification','code'=> 200);
            echo json_encode($response);
         }else{
             $response = array('message'=> 'Somthing Wrong','code'=> 400);
             echo json_encode($response);
         }
         
	}
	
	public function get_notification(){
	   $user_id = $this->input->post('id');
	   $Commn = new Commn();
        if($user_id == ''){
            $response = array('message'=> 'ID field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $user_id));
     
        if(isset($user_data) && !empty($user_data)){
            $when_user = '';
            if($user_data->role == 1){
                $when_user = 2;
            }else{
                $when_user = 1;
            }
            if($when_user == 2){
                $notifications = $Commn->order_where_selectAll('notification_list',array('when_user'=> $when_user,'user_id' => $user_data->id),'','id');
                  
            }else{
                $notifications = $Commn->order_where_selectAll('notification_list',array('when_user'=> $when_user,'seller_id' => $user_data->id),'','id');
            }
            $notification_user_arr = array();
            $notification_seller_arr = array();
            foreach($notifications as $notification){
                if($notification->when_user == 2){
                    $image = $Commn->where_selectAll('salon_banner_image',array('salon_id'=> $notification->salon_id),'');
                    if(isset($image[0]) && !empty($image[0])){
                        $image = base_url().'assets/images/salon/banner/'.$image[0]->image;
                    }else{
                        $image = base_url().'assets/images/salon/default.jpg';
                    }
                    
                    $noti_data  = array(
                        'salon_name' =>  $Commn->select_get_row_data('salon-list',array('id'=> $notification->salon_id),'salon_name'),
                        'salon_image' => $image,
                        'message' => $notification->message,
                        'open_status' => $notification->open_status,
                        'time' => $this->time_since(time() - strtotime($notification->created_at))
                    );
                    array_push($notification_user_arr, $noti_data);
                }
                if($notification->when_user == 1){
                    $user = $Commn->get_row_data('users',array('id'=> $notification->user_id));
                    if(!empty($user->image)){
                        $image = base_url().'assets/profile_pic/users/'.$user->image;
                    }else{
                        $image = base_url().'assets/profile_pic/users/default.png';    
                    }
                    if(!empty($user->name)){ $name = $user->name;}
                    if(empty($name)){ $name = $user->username; }
                    if(empty($name)){ $name = 'user'; }
                    
                    $noti_data  = array(
                        'user_name' =>  $name,
                        'user_image' => $image,
                        'message' => $notification->message,
                        'open_status' => $notification->open_status,
                        'time' => $this->time_since(time() - strtotime($notification->created_at))
                    );
                     array_push($notification_seller_arr, $noti_data);
                }
            }
            
            if($when_user == 2){
                if(isset($notification_user_arr) && !empty($notification_user_arr)){
                    $response = array('message'=> 'Get Notification','code'=> 200,'notification' => $notification_user_arr);
                    echo json_encode($response);
                }else{
                    $response = array('message'=> 'Notification not found','code'=> 400);
                    echo json_encode($response);    
                }
            }
            if($when_user == 1){
                if(isset($notification_seller_arr) && !empty($notification_seller_arr)){
                    $response = array('message'=> 'Get Notification','code'=> 200,'notification' => $notification_seller_arr);
                    echo json_encode($response);
                }else{
                    $response = array('message'=> 'Notification not found','code'=> 400);
                    echo json_encode($response);    
                }
            }
        }else{
            $response = array('message'=> 'user id is wrong','code'=> 400);
            echo json_encode($response);
            return false;
        }
	}
	
    public function time_since($since) {
        $chunks = array(
            array(60 * 60 * 24 * 365 , 'year'),
            array(60 * 60 * 24 * 30 , 'month'),
            array(60 * 60 * 24 * 7, 'week'),
            array(60 * 60 * 24 , 'day'),
            array(60 * 60 , 'hour'),
            array(60 , 'minute'),
            array(1 , 'second')
        );
    
        for ($i = 0, $j = count($chunks); $i < $j; $i++) {
            $seconds = $chunks[$i][0];
            $name = $chunks[$i][1];
            if (($count = floor($since / $seconds)) != 0) {
                break;
            }
        }
    
        $print = ($count == 1) ? '1 '.$name : "$count {$name}s ago";
        return $print;
    }
}
?>
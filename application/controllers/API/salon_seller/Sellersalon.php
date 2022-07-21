<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
class Sellersalon extends CI_Controller {
	public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('commn');
    }
    
    public function salon_layer(){
        $Commn = new Commn();
        $chair_seat =  $Commn->selectAll_new('chair_seat','seat');
        $ac_type =  $Commn->selectAll_new('ac_type','type');
        $is_payment =  $Commn->selectAll_new('is_payment','is_payment');
        $salon_type =  $Commn->selectAll_new('salon_type','type_name');
        
        
        $seat_arr = array();
        if(isset($chair_seat)){
            foreach($chair_seat as $seat){
                array_push($seat_arr,$seat->seat);
            }
        }
        $type_arr = array();
        if(isset($ac_type)){
            foreach($ac_type as $type){
                array_push($type_arr,$type->type);
            }
        }
        $is_payment_arr = array();
        if(isset($is_payment)){
            foreach($is_payment as $payment){
                array_push($is_payment_arr,$payment->is_payment);
            }
        }
        $s_type_arr = array();
        if(isset($salon_type)){
            foreach($salon_type as $s_type){
                array_push($s_type_arr,$s_type->type_name);
            }
        }
        $arr = array(
            'chair_seat' =>  isset($seat_arr) ? $seat_arr : '',
            'ac_type' =>  isset($type_arr) ? $type_arr : '',
            'is_payment' =>  isset($is_payment_arr) ? $is_payment_arr : '',
            'salon_type' =>  isset($s_type_arr) ? $s_type_arr : '',
        );
        
        
        if(isset($arr) && !empty($arr)){
            $response = array('response'=> 'Get Salon Layers','code'=> 200, 'salon_layer' => $arr);
            echo json_encode($response);
            return false;
        }
        
    }
    
    public function login(){
		header('Content-type: application/json');
		$Commn = new Commn();
		$phone = $this->input->post('phone');
		$password = $this->input->post('password');
		$response = array();
		if($phone == ''){
			$response = array('message'=> 'Phone field is required','code'=> 400);
			echo json_encode($response);
			return false;
		}
		if($password == ''){
			$response = array('message'=> 'Password field is required','code'=> 400);
			echo json_encode($response);
			return false;
		}
// 		$login_exist =  $Commn->get_row_data('users',array('phone'=> $phone));
		$login_exist =  $Commn->get_row_data('users',array('phone'=> $phone, 'role' => 2));
		
		if(isset($login_exist)){
		    if($login_exist->status == 1){
		        $seller_id =  $Commn->select_get_row_data('salon-list',array('user_id'=> $login_exist->id),'id');
		        if(!empty($seller_id)){
		            $seller_id = $seller_id;
		        }else{ $seller_id =  '';}
    			if($login_exist->password != md5($password)){
    				$response = array('message'=> 'password is wrong','code'=> 400);
    			}else{
    			    $login_exist->salon_id = $seller_id;
    			    $login_exist->current_step = $Commn->select_get_row_data('seller_steps',array('user_id'=> $login_exist->id),'current_step');
    			    if($login_exist->current_step == "" && $login_exist->current_step == null){ $login_exist->current_step = "6";}
    			    if(isset($login_exist->image)) {
    			       $login_exist->image = base_url().'assets/profile_pic/seller_user/'.$login_exist->image;
    			    }
    				$response = array('message'=> 'Successfully login','code'=> 200,'user' => $login_exist);
    			}
		    }else{
		        $response = array('message'=> 'Your account is deactive','code'=> 400);
		    }
		}else{
			$response = array('message'=> 'User is not fond','code'=> 404);
		}
		echo json_encode($response);
	}
    
    public function regsiter(){
        $name = $this->input->post('name');
        $phone = $this->input->post('phone');
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $confirm_password = $this->input->post('confirm_password');
        
        if($name == ''){
			$response = array('message'=> 'Name field is required','code'=> 400);
			echo json_encode($response);
			return false;
		}
		if($phone == ''){
			$response = array('message'=> 'Phone field is required','code'=> 400);
			echo json_encode($response);
			return false;
		}
		if($email == ''){
			$response = array('message'=> 'Email field is required','code'=> 400);
			echo json_encode($response);
			return false;
		}
		if($password == ''){
			$response = array('message'=> 'Password field is required','code'=> 400);
			echo json_encode($response);
			return false;
		}
		if($confirm_password == ''){
			$response = array('message'=> 'Confirm Password field is required','code'=> 400);
			echo json_encode($response);
			return false;
		}
		if($password != $confirm_password){
			$response = array('message'=> 'Password and Confirm Password does not match','code'=> 400);
			echo json_encode($response);
			return false;
		}
		$Commn = new Commn();
        $email_exist =  $Commn->get_row('users',array('email'=> $email));
        if($email_exist == 1){
            $response = array('message'=> 'already resgister email','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $phone_exist =  $Commn->select_get_row_data('users',array('phone'=> $phone),'phone');
        
        
        if($phone_exist == $phone){
            $response = array('message'=> 'already resgister phone','code'=> 400);
            echo json_encode($response);
            return false;
        }
		$arr =  array(
		  'name' => $name,
		  'phone' => $phone,
		  'email' => $email,
	      'password' => md5($password),
		  'role' => 2,
		  'status' => 1,
		  'register_date' => date('Y-m-d')
		);
		$user =  $Commn->insert_data('users',$arr);
		$register = false;
		if($user == 1){
		    $user_id = $this->db->insert_id();
        	$salon_arr =  array(
        	  'salon_name' => '',
        	  'chair_seat' => '',
        	  'ac_type' => '',
              'is_payment' => '',
        	  'salon_type' => '',
        	  'address' => '',
        	  'min_price' => '',
        	  'max_price' => '',
        	  'salon_time_slot' => '',
        	  'start_time' => '',
        	  'end_time' => '',
        	  'review' =>'',
        	  'city' => '',
        	  'pincode' => '',
        	  'about' => '',
        	  'location' => '',
        	  'image' => '',
        	  'user_id' => $user_id
        	);
        	$salon =  $Commn->new_insert_data('salon-list',$salon_arr);
        	if($salon == 1){
        	   $register = true;
        	}
        	$steps_arr =  array(
        	  'steps' => '1=>general_settings:status=>0,2=>services_settings:status=>0,3=>date/time_settings:status=>0,4=>image_settings:status=>0,5=>descripation_settings:status=>0,1=>home_screen:status=>0',
        	  'current_step' => '1',
        	  'user_id' => $user_id,
        	);
        	$steps= $this->db->insert('seller_steps',$steps_arr);
        	$steps = 1;
        // 	$steps =  $Commn->new_insert_data('seller_steps',$steps_arr);
        	if($steps == 1){
        	   $register = true;
        	}
		}
		
		if($register == true){
		    $users = $Commn->get_row_data('users',array('email'=> $email));
		    $users->current_step = $Commn->select_get_row_data('seller_steps',array('user_id'=> $users->id),'current_step');
		    $seller_id =  $Commn->select_get_row_data('salon-list',array('user_id'=> $users->id),'id');
		        if(!empty($seller_id)){
		            $seller_id = $seller_id;
		        }else{ $seller_id =  '';}
		    $users->salon_id = $seller_id;
		    $response = array('message'=> 'Salon Register Successfully','code'=> 200,'user'=> $users);
            echo json_encode($response);
            return false;  
		}else{
		     $response = array('message'=> 'Something wrong','code'=> 400);
            echo json_encode($response);
            return false;  
		}
        
    }
    
    public function seller_dashboard(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');    
        
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        
        $dashboard_arr = array();
        if(isset($user_data) & !empty($user_data)){
            
          $booking = $Commn->get_seller_booking($user_data->id);  
          $seller_id =  $Commn->select_get_row_data('salon-list',array('user_id'=> $user_data->id),'id');
          $bookings =  $Commn->where_selectAll('salon_booking',array('salon_id'=> $seller_id,'booking_status'=>3),'');
          $recevice_bookings =  $Commn->where_selectAll('salon_booking',array('salon_id'=> $seller_id,'booking_status'=>2),'');
          
        //   echo "<pre>";print_r($bookings);
          $commission = 4;
          $total_earn = 0;
            if(isset($bookings) && !empty($bookings)){
              foreach($bookings as $bookingx){
                  $ern1 = ($bookingx->total_amount * $commission);
                  $ern = ($ern1 / 100);
                  $total_earn += ($bookingx->total_amount - $ern);
                  
              }
            }
            $recevice_total_earn = 0;
            if(isset($recevice_bookings) && !empty($recevice_bookings)){
              foreach($recevice_bookings as $recevice_booking){
                  $ern1 = ($recevice_booking->total_amount * $commission);
                  $ern = ($ern1 / 100);
                  $recevice_total_earn += ($recevice_booking->total_amount - $ern);
                  
              }
            }
           $dashboard_arr['name'] = isset($user_data->name) ? $user_data->name : '';
           $dashboard_arr['total_earn'] =  (int)$total_earn;
           $dashboard_arr['receive_amount'] =  (int)$recevice_total_earn;
           $dashboard_arr['new_booking'] =  count($booking);
           $dashboard_arr['accpeted_request'] =  count($Commn->where_selectAll('salon_booking',array('salon_id'=> $seller_id,'booking_status'=> 2),''));
           $dashboard_arr['completed_booking'] = count($Commn->where_selectAll('salon_booking',array('salon_id'=> $seller_id,'booking_status'=> 3),''));
           $dashboard_arr['rejected_booking'] = count($Commn->where_selectAll('salon_booking',array('salon_id'=> $seller_id,'booking_status'=> 4),''));
           $dashboard_arr['total_users'] = count($Commn->where_selectAll('salon_booking_user',array('salon_id'=> $seller_id),''));
           $dashboard_arr['active_users'] = count($Commn->where_selectAll('salon_booking_user',array('salon_id'=> $seller_id,'status'=> 1),''));
           $dashboard_arr['blocked_users'] = count($Commn->where_selectAll('salon_booking_user',array('salon_id'=> $seller_id,'status'=> 0),''));;
          $response = array('message'=> 'get Dashboard data','code'=> 200,'dashboard'=> $dashboard_arr);
          echo json_encode($response);
        }else{
           $response = array('message'=> 'Seller user not found','code'=> 400);
           echo json_encode($response);            
        }
    }
    
    public function get_general_setting(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');    
        
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        if(isset($user_data) & !empty($user_data)){
           $salon =  $Commn->get_row_data('salon-list',array('user_id'=> $user_data->id));
           if(isset($salon) && !empty($salon)){
              $response_salon = array(
                'id' => $salon->id,
                'shop_name' => $salon->salon_name,
                'seats' => $salon->chair_seat,
                'ac_type' => $salon->ac_type,
                'is_payment' => $salon->is_payment,
                'salon_type' => $salon->salon_type,
                'location' => $salon->location,
                'state_id' => $Commn->select_get_row_data('states',array('name'=> $salon->state),'id'),
                'state' => $salon->state,
                'city' => $salon->city
              ); 
              $response = array('message'=> 'get general setting','code'=> 200,'general_setting' => $response_salon);
              echo json_encode($response);
           }
        }
    }
    
    public function update_general_setting(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        $shop_name = $this->input->post('shop_name');
        $seats = $this->input->post('seats');
        $ac_type = $this->input->post('ac_type');
        $is_payment = $this->input->post('is_payment');
        $salon_type = $this->input->post('salon_type');
        $location = $this->input->post('location');
        $address = $this->input->post('address');
        $state = $this->input->post('state');
        $city = $this->input->post('city');
        
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($shop_name == ''){
            $response = array('message'=> 'shop name field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($seats == ''){
            $response = array('message'=> 'seats field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($ac_type == ''){
            $response = array('message'=> 'ac type field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($is_payment == ''){
            $response = array('message'=> 'is payment field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }if($salon_type == ''){
            $response = array('message'=> 'salon type field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($location == ''){
            $response = array('message'=> 'location field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($address == ''){
            $response = array('message'=> 'address field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($state == ''){
            $response = array('message'=> 'state field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($city == ''){
            $response = array('message'=> 'city field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }        
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        if(isset($user_data) & !empty($user_data)){
           $salon =  $Commn->get_row_data('salon-list',array('user_id'=> $user_data->id));  
         
           $update_data = array();
           $update_data['salon_name'] = $shop_name;
           $update_data['chair_seat'] = $seats;
           $update_data['ac_type'] = $ac_type;
           $update_data['is_payment'] = $is_payment;
           $update_data['salon_type'] = $salon_type;
           $update_data['location'] = $location;
           $update_data['address'] = $address;
           $update_data['state'] = $state;
           $update_data['city'] = $city;
           $where = array('id' => $salon->id);
           $update_setting =  $Commn->update_data('salon-list',$update_data,$where);
           if($update_setting == 1){
                $step_data =  $Commn->get_row_data('seller_steps',array('user_id'=> $seller_user_id));
                if(isset($step_data) && !empty($step_data)){
                    if($step_data->current_step == 1){
                        $next_step = 2;
                    }else{
                        $next_step = $step_data->current_step;
                    }
                }else{
                    $next_step = 6;
                }
                $step_update_data = array(
                    'steps' => '1=>general_settings:status=>1,2=>services_settings:status=>0,3=>date/time_settings:status=>0,4=>image_settings:status=>0,5=>descripation_settings:status=>0,1=>home_screen:status=>0',
                    'current_step' => $next_step,
                );
                $step_where = array('user_id' => $seller_user_id);
                $update_step =  $Commn->update_data('seller_steps',$step_update_data,$step_where);
                if($update_step == 1){
                    $current_step = $Commn->select_get_row_data('seller_steps',array('user_id'=> $seller_user_id),'current_step');
    			    if($current_step == "" && $current_step == null){ $current_step = "6";}else{ $current_step = ($current_step); }
                    $response = array('message'=> 'Successfully update settings','code'=> 200,'current_step' => $current_step);
                    echo json_encode($response);
                }else{
                 $response = array('message'=> 'Something else','code'=> 400);
                 echo json_encode($response);   
                }
           }else{
               $response = array('message'=> 'Something else','code'=> 400);
               echo json_encode($response);  
           }
        }else{
                $response = array('message'=> 'user not found','code'=> 400);
                echo json_encode($response);     
        }
    }
    
    public function get_services(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');    
        
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        $services_arr = array();
        if(isset($user_data) & !empty($user_data)){
           $salon =  $Commn->get_row_data('salon-list',array('user_id'=> $user_data->id)); 
           $salon_services =  $Commn->where_selectAll('salon-services',array('salon_id'=> $salon->id),'');
          
           if(isset($salon_services) && !empty($salon_services)){
              foreach($salon_services as $salon_service){
                $service_data = array();
                $service_data['id'] = $salon_service->id;
                $service_data['image'] = base_url().'assets/images/salon/services/'.$salon_service->image;
                $service_data['title'] = $salon_service->title;
                $service_data['descripation'] = $salon_service->desc;
                $service_data['price'] = $salon_service->price;
                
                array_push($services_arr, $service_data);
              }
              
              if(isset($services_arr) && !empty($services_arr)){
                 $response = array('message'=> 'Successfully get services','code'=> 200,'services'=> $services_arr);
                 echo json_encode($response); 
              }
           }else{
                $response = array('message'=> 'Services not found','code'=> 400);
                 echo json_encode($response);    
           }
        }else{
            $response = array('message'=> 'user not found','code'=> 400);
            echo json_encode($response);
        }
    }
    
    public function add_service(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        $service_name = $this->input->post('service_name');
        $service_price = $this->input->post('service_price');
        $service_descriaption = $this->input->post('service_descriaption');
        $service_image = $_FILES['service_image'];
        
        // echo "<pre>";print_r($_FILES);
        // die;
        
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($service_name == ''){
            $response = array('message'=> 'service name field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($service_price == ''){
            $response = array('message'=> 'service price field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($service_descriaption == ''){
            $response = array('message'=> 'service descripation field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if(empty($service_image['name'])){
            $response = array('message'=> 'service image field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        $services_arr = array();
        if(isset($user_data) & !empty($user_data)){
            $salon =  $Commn->get_row_data('salon-list',array('user_id'=> $user_data->id));
            $get_service = $Commn->get_row_data('salon-services',array('title'=> $service_name, 'salon_id' => $salon->id));
            
            if(isset($get_service) && !empty($get_service)){
                $response = array('message'=> 'already added service','code'=> 400);
                echo json_encode($response);    
                return false;
            }else{
                $services_arr['title'] = $service_name;
                $services_arr['price'] = $service_price;
                $services_arr['desc'] = $service_descriaption;
                
                $services_arr['salon_id'] = $salon->id;
                $upload_path="assets/images/salon/services";
                $config = array(
                'upload_path' => $upload_path,
                'allowed_types' => "gif|jpg|png|jpeg",
                'allowed_types' => '*',
                'overwrite' => TRUE,
                'max_size' => "2048000"
                );
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('service_image'))
                { 
                $data['imageError'] =  $this->upload->display_errors();
                
                if(isset($data['imageError']) && !empty($data['imageError'])){
                    $response = array('message'=> 'service picture not upload','code'=> 400,'error'=> $data['imageError']);
                    echo json_encode($response);
                    return false;
                }
                
                }else{
                    $imageDetailArray = $this->upload->data();
                    $image =  $imageDetailArray['file_name'];
                    $services_arr['image'] = $image;
                    // $update = $Commn->update_data('users',array('image' => $image), array('id' => $user_id));
                }
                
                $new_service =  $Commn->insert_data('salon-services', $services_arr);
                // $new_service = 1;
                if($new_service == 1){
                    $step_data =  $Commn->get_row_data('seller_steps',array('user_id'=> $seller_user_id));
                    if(isset($step_data) && !empty($step_data)){
                        if($step_data->current_step == 2){
                            $next_step = 3;
                        }else{
                             $next_step = $step_data->current_step;
                        }
                    }else{
                        $next_step = 6;
                    }
                    $step_update_data = array(
                        'steps' => '1=>general_settings:status=>1,2=>services_settings:status=>1,3=>date/time_settings:status=>0,4=>image_settings:status=>0,5=>descripation_settings:status=>0,1=>home_screen:status=>0',
                        'current_step' => $next_step,
                    );
                    $step_where = array('user_id' => $seller_user_id);
                    $update_step =  $Commn->update_data('seller_steps',$step_update_data,$step_where);
                    if($update_step == 1){    
                        $current_step = $Commn->select_get_row_data('seller_steps',array('user_id'=> $seller_user_id),'current_step');
    			        if($current_step == "" && $current_step == null){ $current_step = "6";}else{ $current_step = ($current_step); }
    			        
    			        $all_services =  $Commn->where_selectAll('salon-services',array('salon_id'=> $salon->id),'');
    			      $min_array = array();
                      $max_array = array();
                       foreach($all_services as $service){
                           $min_price = $service->price;
                           $max_price = $service->price;
                           array_push($min_array, strtok($min_price, " "));
                           array_push($max_array, strtok($max_price, " "));
                       }
                      
                       $min_price = min($min_array);
                       $max_price = max($max_array);
                         $update_price =  $Commn->update_data('salon-list',array('min_price' => $min_price,'max_price' => $max_price),array('id' => $salon->id));
                        $response = array('message'=> 'successfully add new service','code'=> 200,"current_step" => $current_step);
                        echo json_encode($response);
                    }else{
                     $response = array('message'=> 'Something else','code'=> 400);
                     echo json_encode($response);   
                    }    
                }    
            }
            
            
        }else{
            $response = array('message'=> 'user not found','code'=> 400);
            echo json_encode($response);            
        }
    }
    
    public function update_service(){
    
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        $service_id = $this->input->post('service_id');
        $service_name = $this->input->post('service_name');
        $service_price = $this->input->post('service_price');
        $service_descriaption = $this->input->post('service_descriaption');
        $service_image = (!empty($_FILES['service_image']));
        
        // echo "<pre>";print_r($_FILES);
        // die;
        
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
         if($service_id == ''){
            $response = array('message'=> 'service id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($service_name == ''){
            $response = array('message'=> 'service name field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($service_price == ''){
            $response = array('message'=> 'service price field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($service_descriaption == ''){
            $response = array('message'=> 'service descripation field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        // if(empty($service_image['name'])){
        //     $response = array('message'=> 'service image field is required','code'=> 400);
        //     echo json_encode($response);
        //     return false;
        // }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        $services_arr = array();
         $update_status = 0;
        if(isset($user_data) & !empty($user_data)){
            $salon =  $Commn->get_row_data('salon-list',array('user_id'=> $user_data->id));
            $get_service = $Commn->get_row_data('salon-services',array('title'=> $service_name, 'salon_id' => $salon->id));
            
            if(isset($get_service) && !empty($get_service)){
                if($get_service->title == $service_name){
                    if($get_service->id != $service_id){
                        $response = array('message'=> 'already added service','code'=> 400);
                        echo json_encode($response);    
                        return false;        
                    }else{
                        $update_status = 1;
                    }
                }else{
                     $update_status = 1;   
                }
            if($update_status == 1){
                 $services_arr['title'] = $service_name;
                $services_arr['price'] = $service_price;
                $services_arr['desc'] = $service_descriaption;
                
                $services_arr['salon_id'] = $salon->id;
             if(isset($service_image['name']) &&  !empty($service_image['name'])){    
                $unlink_path = 'assets/images/salon/services/'.$get_service->image;
               
                $upload_path="assets/images/salon/services";
                $config = array(
                'upload_path' => $upload_path,
                'allowed_types' => "gif|jpg|png|jpeg",
                'allowed_types' => '*',
                'overwrite' => TRUE,
                'max_size' => "2048000"
                );
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('service_image'))
                { 
                $data['imageError'] =  $this->upload->display_errors();
                
                if(isset($data['imageError']) && !empty($data['imageError'])){
                    $response = array('message'=> 'service picture not upload','code'=> 400,'error'=> $data['imageError']);
                    echo json_encode($response);
                    return false;
                }
                
                }else{
                    // 
                    $imageDetailArray = $this->upload->data();
                    $image =  $imageDetailArray['file_name'];
                    $services_arr['image'] = $image;
                    unlink($unlink_path); 
                }
            }
                $update_service =  $Commn->update_data('salon-services', $services_arr,array('id' => $service_id));
                if($update_service == 1){
                    $response = array('message'=> 'successfully Update service','code'=> 200);
                    echo json_encode($response);    
                } 
            }else{
                    $response = array('message'=> 'somthing else','code'=> 400);
                    echo json_encode($response);    
            }    
            }else{
                    $response = array('message'=> 'service not found','code'=> 400);
                    echo json_encode($response);
            }
        }else{
            $response = array('message'=> 'user not found','code'=> 400);
            echo json_encode($response);            
        }
        
    }
    
    public function delete_service(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        $service_id = $this->input->post('service_id');  
        
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
         if($service_id == ''){
            $response = array('message'=> 'service id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        if(isset($user_data) & !empty($user_data)){
            $salon =  $Commn->get_row_data('salon-list',array('user_id'=> $user_data->id));
            $get_service = $Commn->get_row_data('salon-services',array('id'=> $service_id, 'salon_id' => $salon->id));
            if(isset($get_service) && !empty($get_service)){
                $delete_service = $Commn->delete_data('salon-services', array('id' => $get_service->id));
                if($delete_service == 1){
                    $response = array('message'=> 'Successfully delete service','code'=> 200);
                    echo json_encode($response);
                }else{
                    $response = array('message'=> 'Somthing wrong','code'=> 400);
                    echo json_encode($response);    
                }
            }else{
                $response = array('message'=> 'Services not found','code'=> 404);
                echo json_encode($response);
            }
        }else{
            $response = array('message'=> 'User not found','code'=> 404);
            echo json_encode($response);
        }
    }
    
    public function get_descripation(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        $description = $this->input->post('description');
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        $get_gallery_arr = array();
        if(isset($user_data) && !empty($user_data)){
            $seller_id =  $Commn->select_get_row_data('salon-list',array('user_id'=> $user_data->id),'id');
            $about =  $Commn->select_get_row_data('salon-list',array('user_id'=> $user_data->id),'about');
            if($about){
                $response = array('message'=> 'successfully get descripation','code'=> 200,'descripation' => $about);
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
    
    public function update_salon_descripation(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        $description = $this->input->post('description');
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        if($description == ''){
            $response = array('message'=> 'Descripation field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        $get_gallery_arr = array();
        if(isset($user_data) && !empty($user_data)){
            $seller_id =  $Commn->select_get_row_data('salon-list',array('user_id'=> $user_data->id),'id');
            $update_description = $Commn->update_data('salon-list',array('about' => $description),array('id' => $seller_id));
            if($update_description == 1){
                    $step_data =  $Commn->get_row_data('seller_steps',array('user_id'=> $seller_user_id));
                    if(isset($step_data) && !empty($step_data)){
                        if($step_data->current_step == 5){
                            $next_step = 6;
                        }else{
                            $next_step = $step_data->current_step;
                        }
                    }else{
                        $next_step = 6;
                    }
                    $step_update_data = array(
                        'steps' => '1=>general_settings:status=>1,2=>services_settings:status=>1,3=>date/time_settings:status=>1,4=>image_settings:status=>1,5=>descripation_settings:status=>1,1=>home_screen:status=>0',
                        'current_step' => $next_step,
                    );
                    $step_where = array('user_id' => $seller_user_id);
                    $update_step =  $Commn->update_data('seller_steps',$step_update_data,$step_where);
                    if($update_step == 1){    
                            $current_step = $Commn->select_get_row_data('seller_steps',array('user_id'=> $seller_user_id),'current_step');
    			            if($current_step == "" && $current_step == null){ $current_step = "6";}else{ $current_step = ($current_step); }
                            $response = array('message'=> 'successfully update descripation','code'=> 200,"current_step" => $current_step);
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
    
    public function get_inquiry_list(){
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
            $inquiry_list =  $Commn->order_where_selectAll('contact_us',array('salon_id'=> $seller_id),'','id');
            $inquiry_arr = array();
            if(isset($inquiry_list) && !empty($inquiry_list)){
             foreach($inquiry_list as $inquiry){
                  
                  $d = array(
                      "name" =>  $Commn->select_get_row_data('users',array('id'=> $inquiry->user_id),'name'),
                      "phone" =>  $Commn->select_get_row_data('users',array('id'=> $inquiry->user_id),'phone'),
                      "email" =>  $Commn->select_get_row_data('users',array('id'=> $inquiry->user_id),'email'),
                      "subject" =>  $inquiry->subject,
                      "content" =>  $inquiry->content,
                      "created_at" =>  date('d-m-Y H:i:s', strtotime($inquiry->date)),
                  );
                  array_push($inquiry_arr, $d);
             }   
            }
            if(isset($inquiry_arr) && !empty($inquiry_arr)){
                $response = array('message'=> 'successfully get descripation','code'=> 200,'inquiry_list' => $inquiry_arr);
                echo json_encode($response);    
            }else{
                $response = array('message'=> 'inquiry list not found','code'=> 400);
                echo json_encode($response);
            }
        }else{
            $response = array('message'=> 'user not found','code'=> 400);
            echo json_encode($response);            
        }
    }
    
    public function deactive_account(){
        $Commn = new Commn();
        $seller_user_id = $this->input->post('seller_user_id');
        $description = $this->input->post('description');
        if($seller_user_id == ''){
            $response = array('message'=> 'seller user id field is required','code'=> 400);
            echo json_encode($response);
            return false;
        }
        $user_data =  $Commn->get_row_data('users',array('id'=> $seller_user_id, 'role' => 2));
        if(isset($user_data) && !empty($user_data)){
            $accoount_status = $Commn->update_data('users',array('status' => 0),array('id' => $user_data->id));
            if($accoount_status == 1){
                $response = array('message'=> 'successfully deactive your account','code'=> 200);
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
    
}
?>
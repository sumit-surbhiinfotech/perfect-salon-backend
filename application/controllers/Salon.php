<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-Type: application/json; charset=utf-8');
// ob_start(); 
class Salon extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
    //$this->load->library('form_validation');
        $this->load->model('commn');
    }
    public function salon(){
		$Commn = new Commn();
		$id = $this->input->get('salon_id');
        $user_id = $this->input->get('user_id');
		if(empty($id)){
			$error = array('message'=> 'enter salon Id','code'=> 400);
            echo json_encode($error);
            return false;
		}
		$salon=  $Commn->get_row_data('salon-list',array('id'=>$id,'is_approve'=>1));
		if(!empty($salon) && isset($salon)){

			$salon_arr = array();
			$salon_arr['id'] =  $salon->id;
			// get reviews
			$reviews  = $Commn->where_selectAll('salon-review',array('salon_id'=> $salon->id),'');
			$salon_arr['salon_name'] =  trim($salon->salon_name, ' ');
			$salon_arr['salon_type'] =  $salon->salon_type;
			$salon_arr['address'] =  $salon->address;
			$salon_arr['city'] =  $Commn->select_get_row_data('cities',array('id' => $salon->city),'name'); //$salon->city; //$Commn->select_get_row_data('cities',array('id' =>  $Commn->select_get_row_data('users',array('id' =>  $Commn->select_get_row_data('salon-list',array('id' =>  $salon->id),'user_id')),'city')),'name');
			$salon_arr['total_review'] =  isset($reviews) ? count($reviews) : '';
			$total_plus_ratting;
			if(isset($reviews) && !empty($reviews)){
				$total_plus_ratting =0;
				foreach ($reviews as $key => $review) {
					$total_plus_ratting += (int)$review->star_review;
				}
			}else{ $reviews = array();}
			$salon_arr['avg_review'] =  isset($total_plus_ratting) ? ($total_plus_ratting/count($reviews)) : 0;
			$salon_arr['ac_type'] =  $salon->ac_type;
			$salon_arr['min_price'] =  $salon->min_price;
			$salon_arr['max_price'] =  $salon->max_price;
			$salon_arr['start_time'] =  $salon->start_time;
			$salon_arr['end_time'] =  $salon->end_time;
			$images =  explode(',', $salon->image);
			if(!empty($user_id)){
			    $fav =  $Commn->get_row_data('salon_favioute', array('salon_id' =>  $salon->id, 'user_id' => $user_id));
			    if(isset($fav) && !empty($fav)){
    			    if(!empty($fav->is_like) && $fav->is_like != null){
    			        $salon_arr['is_favorite'] = $fav->is_like;
    			    }else{
    			        $salon_arr['is_favorite'] = $fav->is_like;
    			    }
			    }else{
			        $salon_arr['is_favorite'] = 0;
			    }
			}else{
			    $salon_arr['is_favorite'] = 0;
			}
			$img_arr = array();
			// images
			$banner_images  = $Commn->where_selectAll('salon_banner_image',array('salon_id'=> $salon->id,'status'=>1),'image');
		
			if(isset($banner_images) && !empty($banner_images)){
				foreach ($banner_images as $key => $banner_image) {
					$img = base_url().'assets/images/salon/banner/'.$banner_image->image;
					array_push($img_arr,$img);
				}
			}
			
			
			$salon_arr['images'] = $img_arr;
			// salon-portfolio
			$salon_portfolio_arr = array();
			$salon_portfolios  = $Commn->where_selectAll('salon-portfolio',array('salon_id'=> $salon->id),'');
			if(isset($salon_portfolios) && !empty($salon_portfolios)){
				foreach ($salon_portfolios as $key => $salon_portfolio) {
					$img = base_url().'assets/images/salon/portfolio/'.$salon_portfolio->image;
					array_push($salon_portfolio_arr,$img);
				}
			}
			$salon_arr['our_work'] =  isset($salon_portfolio_arr) ? $salon_portfolio_arr : '';
			// services
			$services  = $Commn->where_selectAll('salon-services',array('salon_id'=> $salon->id),'');
			$services_arr = array();
			if(isset($services) && !empty($services)){
				foreach ($services as $key => $service) {
					$srv = 
						array(
							'id' => $service->id,
							'image' => base_url().'assets/images/salon/services/'.$service->image,
							'title' => $service->title,
							'desc' => $service->desc,
							'price' => $service->price,
						);
					array_push($services_arr,$srv);
				}
			}
			$salon_arr['services'] =  isset($services_arr) ? $services_arr : '';
			// $salon_arr['salon_time_slot'] =  explode(',', $salon->salon_time_slot);
			$salon_arr['about'] =  $salon->about;
			$location = explode(',', $salon->location);
			$salon_arr['location'] = array('lat' => isset($location[0]) ? $location[0] : '','lng' => isset($location[1]) ? $location[1] : '');
			$salon_token = $Commn->select_get_row_data('notification_token',array('user_id'=> $salon->user_id),'Token');
			$salon_arr['token'] =  isset($salon_token) ? $salon_token : '';
			if(isset($salon_arr) && !empty($salon_arr)){
				$response = array('message'=> 'successfully get salon','code'=> 200,'salon'=> $salon_arr);
            	echo json_encode($response);
			}
		}else{
		 	$response = array('message'=> 'salon not found','code'=> 400);
            echo json_encode($response);
		}  
	}

	public function favorite(){
	    $user_id=  $this->input->post('user_id');
	    $salon_id=  $this->input->post('salon_id');
	    if($user_id == ''){
            $response = array('message'=> 'User_id field is required','code'=> 400);
            echo json_encode($response);
            return false;
       }
       if($salon_id == ''){
            $response = array('message'=> 'salon_id field is required','code'=> 400);
            echo json_encode($response);
            return false;
       }
       $Commn = new Commn();
       $result =  $Commn->get_row_data('salon_favioute', array('salon_id' => $salon_id, 'user_id' => $user_id));
       
       if(isset($result) &&  !empty($result)){
           if(!empty($result->is_like) && $result->is_like == 1){
                $un_like = $Commn->update_data('salon_favioute',array('is_like' => 0),array('id'=> $result->id));
                if($un_like == 1){
                    $response = array('message'=> 'successfully Un favorite','code'=> 200);
                    echo json_encode($response);
                }else{
                    $response = array('message'=> 'please try again','code'=> 400);
                    echo json_encode($response);
                }
           }else{
              $like = $Commn->update_data('salon_favioute',array('is_like' => 1),array('id'=> $result->id));
                if($like == 1){
                    $response = array('message'=> 'successfully favorite like','code'=> 200);
                    echo json_encode($response);
                }else{
                    $response = array('message'=> 'please try again','code'=> 400);
                    echo json_encode($response);
                } 
           }
       }else{
           $result = $Commn->insert_data('salon_favioute',array('salon_id' => $salon_id, 'user_id' => $user_id, 'is_like' => 1));
           if($result == 1){
               $response = array('message'=> 'successfully favorite like','code'=> 200);
               echo json_encode($response);
           }else{
               $response = array('message'=> 'please try again','code'=> 400);
               echo json_encode($response);
           }
       }
       
	}
	public function get_favorite(){
	    $user_id=  $this->input->get('user_id');
	    if($user_id == ''){
            $response = array('message'=> 'User_id field is required','code'=> 400);
            echo json_encode($response);
            return false;
       }
       $Commn = new Commn();
       $favorites = $Commn->get_favorite($user_id);
       
    //   echo "<pre>";print_r($favorites);
       $salon_arr = array();
        $img_arr = array();
        // images
        
       foreach($favorites as $favorite){
           $banner_images  = $Commn->where_selectAll('salon_banner_image',array('salon_id'=>  $favorite->salon_id,'status'=>1),'image');
           if(isset($banner_images[0]->image) && !empty($banner_images[0]->image)){
             $img = base_url().'assets/images/salon/banner/'.$banner_images[0]->image;
           }else {
               $img = base_url().'assets/images/salon/default.jpg';
           }
        
           if($favorite->is_like == 1){
               	$images = explode(',', $favorite->image);
    			$sal_arr = array(
    				'id' => $favorite->id,
    				'salon_id' => $favorite->salon_id,
    				'image' => $img,
    				'review' => '',
    				'city' => $favorite->city, //$Commn->select_get_row_data('cities',array('id' =>  $Commn->select_get_row_data('users',array('id' =>  $Commn->select_get_row_data('salon-list',array('id' =>  $favorite->salon_id),'user_id')),'city')),'name'),
    				'salon_type' => $favorite->salon_type,
    				'title' => $favorite->salon_name,
    				'address' => $favorite->address,
    				'ac_type' => $favorite->ac_type,
    				'start_time' => $favorite->start_time,
    				'end_time' => $favorite->end_time,
    			);
    			array_push($salon_arr,$sal_arr);
           }
       }
       if(!empty($salon_arr)){
            $response = array('message'=> 'successfully get favorite salon list','code'=> 200,'salon'=> $salon_arr);
            echo json_encode($response);
        }else{
            $response = array('message'=> 'salon not found','code'=> 400);
            echo json_encode($response);
        } 
	}
	public function time_slot(){
	    date_default_timezone_set("Asia/Kolkata");
	    $Commn = new Commn();
	    $salon_id = $this->input->post('salon_id');
	    $service_date = $this->input->post('service_date');
	    if($salon_id == ''){
	         $error = array('message'=> 'enter salon Id','code'=> 400);
             echo json_encode($error);
             return false;  
	    }
	    if($service_date == ''){
	         $error = array('message'=> 'enter service date','code'=> 400);
             echo json_encode($error);
             return false;  
	    }
	    $salon=  $Commn->select_get_row_data('salon-list',array('id'=>$salon_id),'salon_time_slot');
        $chair_seat=  $Commn->select_get_row_data('salon-list',array('id'=>$salon_id),'chair_seat');
        if($chair_seat == null && $chair_seat == ''){
            $chair_seat = '';
        }
        // echo $chair_seat;
	   // salon_time_slot
	   $slot_arr = array();
	   if(isset($salon) && !empty($salon)){
	        $full_service_date =  date('l', strtotime($service_date));
	        $service_day = $full_service_date;
	        $salon = unserialize($salon);
	       // echo ucfirst($service_day);
	       
	       
	       //echo "<pre>";print_r($salon);die;
	       
	        if(isset($salon[ucfirst($service_day)]) && !empty($salon[ucfirst($service_day)])){
	               
	            if($salon[ucfirst($service_day)]['status'] == 1){
	                
	                $minutes = 30;
                    $start = new \DateTime($salon[ucfirst($service_day)]['start_time']);
                    $end = new \DateTime($salon[ucfirst($service_day)]['end_time']);
                    $interval = new DateInterval("PT".$minutes."M");
                    $dateRange = new DatePeriod($start, $interval, $end);
                    $start_time_arr = array();
                    // echo "<pre>";print_r($salon[ucfirst($service_day)]['start_time']);
                    // echo "<pre>";print_r($salon[ucfirst($service_day)]['end_time']);
                    foreach ($dateRange as $date) {
                        // echo $date->format("h:ia");
                        $booking = $Commn->where_selectAll('salon_booking', array('booking_date'=> date('Y-m-d', strtotime($service_date)),'booking_time'=> $date->format("h:ia"),'booking_status' => 2,'salon_id' => $salon_id),'');
                        
                        // echo "<br>".$this->db->last_query() .'<br/>';
                        
                        // echo $date->format("h:ia") ." Booking count : ". count($booking) .'</br>';
                        // echo $date->format("h:ia") ." chair_seat count : ". $chair_seat .'</br>';
                        
                        
                        $booking_status = 0;
                        if(isset($booking) && !empty($booking)){
                            
                            if(count($booking) >= (int)$chair_seat){
                                $booking_status = 1;    
                            }else{
                                $booking_status = 0;
                            }
                        }
                        // echo $chair_seat .' - ' . $date->format("h:ia") .' - '.count($booking) . ' --- '.$booking_status ."\n";
                        // echo $date->format("h:ia") ."Status  : ". $booking_status .'</br>';
                        array_push($start_time_arr, array("time" => $date->format("h:ia"), 'booking_status' => $booking_status));
                        
                    }
                    if(isset($start_time_arr) && !empty($start_time_arr)){
                        $response = array('message'=> 'Get time slot','code'=> 200,'time_slot' => $start_time_arr);
                        echo json_encode($response); 
                    }else{
                        $response = array('message'=> 'time slot not avalible','code'=> 400);
        	            echo json_encode($response);    
                    }
	            }else{
	                $response = array('message'=> 'this day time slot block','code'=> 400);
        	        echo json_encode($response);
	            }
	        }else{
	            $response = array('message'=> 'time slot not avalible','code'=> 400);
        	    echo json_encode($response);
	        }
	       //$time =  explode(',', $salon);
	       //$response = array('message'=> 'successfully get salon Time','code'=> 200,'salon'=> $time);
        // 	    echo json_encode($response);
	   }
	   // echo "<pre>";print_r($slot_arr);
	}
	public function popular_salon(){
		$Commn = new Commn();
		$hight_rate  = $Commn->hightest_val();
		$main_sal_arr = array();
		if(isset($hight_rate) && !empty($hight_rate)){
		  //  print_r($hight_rate);
		  //  die;
		    foreach($hight_rate as $rate){
		    $salons  = $Commn->get_row_data('salon-list',array('id'=> $rate['salon_id']),'');
		    if($salons->is_approve ==1){
		    
		    $img_arr = array();
			// images
			$banner_images  = $Commn->where_selectAll('salon_banner_image',array('salon_id'=> $rate['salon_id'],'status'=>1),'image');
		
			if(isset($banner_images) && !empty($banner_images)){
				foreach ($banner_images as $key => $banner_image) {
					$img = base_url().'assets/images/salon/banner/'.$banner_image->image;
					array_push($img_arr,$img);
				}
			}
// 			print_r($salons);
		
			
		    $images = explode(',', $salons->image);
		    	$sal_arr = array(
					'id' => $salons->id,
					'image' => isset($img_arr[0]) ? $img_arr[0] : '',
					'review' => '',
					'city' => $Commn->select_get_row_data('cities',array('id' => $salons->city),'name'),
					'salon_type' => $salons->salon_type,
					'title' => $salons->salon_name,
					'address' => $salons->address,
					'ac_type' => $salons->ac_type,
					'start_time' => $salons->start_time,
					'end_time' => $salons->end_time,
				);
				array_unique($sal_arr);
					// get reviews
				$reviews  = $Commn->where_selectAll('salon-review',array('salon_id'=> $salons->id),'');
		      //  print_r($reviews);
				
				$total_plus_ratting;
				if(isset($reviews) && !empty($reviews)){
					$total_plus_ratting =0;
					foreach ($reviews as $key => $review) {
						$total_plus_ratting += (int)$review->star_review;
					}
				// 	echo " total => ".$total_plus_ratting;
					$sal_arr['review'] = isset($total_plus_ratting) ? (string)($total_plus_ratting/count($reviews)) : "0";
				}
				array_push($main_sal_arr, $sal_arr);
		    }
		    
		    }
		  //  	die;
		    if(isset($main_sal_arr) && !empty($main_sal_arr)){
    		    $response = array('message'=> 'successfully get salon','code'=> 200,'salon'=> $main_sal_arr);
        	    echo json_encode($response);
			}else{
    		    $response = array('message'=> 'salon not found','code'=> 400);
                echo json_encode($response);
    		}
		}else{
		    $response = array('message'=> 'salon not found','code'=> 400);
            echo json_encode($response);
		}
	}
	public function search(){
	   $search = $this->input->post('search');
	   if($search == ''){
           $response = array('message'=> 'search field is required','code'=> 400);
           echo json_encode($response);
           return false;
	   }
	   $Commn = new Commn();
	   $get_city_id =  $Commn->get_city($search);
       $city =  $Commn->search_where('salon-list', 'city', $get_city_id->id);
       
       $pincode =  $Commn->search_where('salon-list', 'pincode', $search);
       $salon_arr = array();
       if(isset($city) && !empty($city)){
		foreach ($city as $key => $cty) {

				$images = explode(',', $cty->image);
				$banner_images  = $Commn->where_selectAll('salon_banner_image',array('salon_id'=> $cty->id,'status'=>1),'image');
		        $image = '';
		        if(isset($banner_images) && !empty($banner_images)){
		            if(!empty($banner_images[0]->image)){
		                $image = base_url().'assets/images/salon/banner/'.$banner_images[0]->image;
		            }else{
		                $image = isset($images[0]) ? base_url().'assets/images/salon/'.$images[0] : base_url().'assets/images/default.jpg';
		            }
		        }else{ if(empty($images[0])){$images[0]= 'default.jpg';} $image = isset($images[0]) ? base_url().'assets/images/salon/'.$images[0] : 'default.jpg';}
				$sal_arr = array(
					'id' => $cty->id,
					'image' => $image,
					'review' => '',
					'city' =>   $Commn->select_get_row_data('cities',array('id' => $cty->city),'name'),
					'salon_type' => $cty->salon_type,
					'title' => $cty->salon_name,
					'address' => $cty->address,
					'ac_type' => $cty->ac_type,
					'start_time' => $cty->start_time,
					'end_time' => $cty->end_time,
				);
				// get reviews
				$reviews  = $Commn->where_selectAll('salon-review',array('salon_id'=> $cty->id),'');
		
				
				$total_plus_ratting;
				if(isset($reviews) && !empty($reviews)){
					$total_plus_ratting =0;
					foreach ($reviews as $key => $review) {
						$total_plus_ratting += (int)$review->star_review;
					}
					$sal_arr['review'] = isset($total_plus_ratting) ? (string)($total_plus_ratting/count($reviews)) : "0";
				}
				 array_push($salon_arr,$sal_arr);
		}
       }
    
       if(isset($pincode) && !empty($pincode)){
		foreach ($pincode as $key => $pinc) {

				$images = explode(',', $pinc->image);
				$banner_images  = $Commn->where_selectAll('salon_banner_image',array('salon_id'=> $pinc->id,'status'=>1),'image');
		        $image = '';
		        if(isset($banner_images) && !empty($banner_images)){
		            if(!empty($banner_images[0]->image)){
		                $image = base_url().'assets/images/salon/banner/'.$banner_images[0]->image;
		            }else{
		                $image = isset($images[0]) ? base_url().'assets/images/salon/'.$images[0] : base_url().'assets/images/default.jpg';
		            }
		        }else{ if(empty($images[0])){$images[0]= 'default.jpg';} $image = isset($images[0]) ? base_url().'assets/images/salon/'.$images[0] : 'default.jpg';}
				$sal_arr = array(
					'id' => $pinc->id,
					'image' => $image,
					'review' => '',
					'city' => $pinc->city,
					'salon_type' => $pinc->salon_type,
					'title' => $pinc->salon_name,
					'address' => $pinc->address,
					'ac_type' => $pinc->ac_type,
					'start_time' => $pinc->start_time,
					'end_time' => $pinc->end_time,
				);
				// get reviews
				$reviews  = $Commn->where_selectAll('salon-review',array('salon_id'=> $pinc->id),'');
				$total_plus_ratting;
				if(isset($reviews) && !empty($reviews)){
					$total_plus_ratting =0;
					foreach ($reviews as $key => $review) {
						$total_plus_ratting += (int)$review->star_review;
					}
					$sal_arr['review'] = isset($total_plus_ratting) ? ($total_plus_ratting/count($reviews)) : 0;
				}
				 array_push($salon_arr,$sal_arr);
		}
       }
       if(isset($salon_arr) && !empty($salon_arr)){
            $response = array('message'=> 'successfully get search','code'=> 200, 'search' => $salon_arr);
            echo json_encode($response);
       }else{
            $response = array('message'=> 'record is not found','code'=> 400);
           echo json_encode($response);
       }
	}
	
	
	public function salon_search(){
	    $search = $this->input->post('search');
	   if($search == ''){
           $response = array('message'=> 'search field is required','code'=> 400);
           echo json_encode($response);
           return false;
	   }
	   $Commn = new Commn();
       $salons =  $Commn->search_where('salon-list', 'salon_name', $search);
    //   echo $this->db->last_query();
    //   die;
       $salon_arr = array();
       if(isset($salons) && !empty($salons)){
		foreach ($salons as $key => $salon) {

				$images = explode(',', $salon->image);
				$banner_images  = $Commn->where_selectAll('salon_banner_image',array('salon_id'=> $salon->id,'status'=>1),'image');
		        $image = '';
		        if(isset($banner_images) && !empty($banner_images)){
		            if(!empty($banner_images[0]->image)){
		                $image = base_url().'assets/images/salon/banner/'.$banner_images[0]->image;
		            }else{
		                $image = isset($images[0]) ? base_url().'assets/images/salon/'.$images[0] : base_url().'assets/images/default.jpg';
		            }
		        }else{ if(empty($images[0])){$images[0]= 'default.jpg';} $image = isset($images[0]) ? base_url().'assets/images/salon/'.$images[0] : 'default.jpg';}
				$sal_arr = array(
					'id' => $salon->id,
					'image' => $image,
					'review' => '',
					'city' => $Commn->select_get_row_data('cities',array('id' => $salon->city),'name'),
					'salon_type' => $salon->salon_type,
					'title' => $salon->salon_name,
					'address' => $salon->address,
					'ac_type' => $salon->ac_type,
					'start_time' => $salon->start_time,
					'end_time' => $salon->end_time,
				);
				// get reviews
				$reviews  = $Commn->where_selectAll('salon-review',array('salon_id'=> $salon->id),'');
				$total_plus_ratting;
				if(isset($reviews) && !empty($reviews)){
					$total_plus_ratting =0;
					foreach ($reviews as $key => $review) {
						$total_plus_ratting += (int)$review->star_review;
					}
					$review =isset($total_plus_ratting) ? ($total_plus_ratting/count($reviews)) : "0";
					$sal_arr['review'] = (string)$review;
				}
				 array_push($salon_arr,$sal_arr);
		}
       }
       if(isset($salon_arr) && !empty($salon_arr)){
            $response = array('message'=> 'successfully get search','code'=> 200, 'search' => $salon_arr);
            echo json_encode($response);
       }else{
            $response = array('message'=> 'record is not found','code'=> 400);
           echo json_encode($response);
       }
	}
	
	public function cate_filter(){
	   $search = $this->input->post('salon_cate');
	   if($search == ''){
           $response = array('message'=> 'Salon Cate field is required','code'=> 400);
           echo json_encode($response);
           return false;
	   }
	   $Commn = new Commn();
	   if($search == "Mens Parlour"){
	       $search = array('Mens Parlour','Unisex','Mens Parlour + SPA','Unisex + SPA');
	   }
	   if($search == "Ladies Parlour"){
	      $search = array('Ladies Parlour','Unisex','Ladies Parlour + SPA','Unisex + SPA'); 
	   }
	   if($search == "SPA"){
	       $search = array('Unisex + SPA','Mens Parlour + SPA','Ladies Parlour +SPA');
	   }
	   
       $salon_cate =  $Commn->cate_search_where('salon-list', 'salon_type', $search);
       
       $salon_arr = array();
       if(isset($salon_cate) && !empty($salon_cate)){
		foreach ($salon_cate as $key => $cty) {
                
				$images = explode(',', $cty->image);
				$banner_images  = $Commn->where_selectAll('salon_banner_image',array('salon_id'=> $cty->id,'status'=>1),'image');
		        $image = '';
		        if(isset($banner_images) && !empty($banner_images)){
		            if(!empty($banner_images[0]->image)){
		                $image = base_url().'assets/images/salon/banner/'.$banner_images[0]->image;
		            }else{
		                $image = isset($images[0]) ? base_url().'assets/images/salon/'.$images[0] : base_url().'assets/images/default.jpg';
		            }
		        }else{ if(empty($images[0])){$images[0]= 'default.jpg';} $image = isset($images[0]) ? base_url().'assets/images/salon/'.$images[0] : 'default.jpg';}
				$sal_arr = array(
					'id' => $cty->id,
					'image' => $image,
					'review' => '0',
					'city' => $Commn->select_get_row_data('cities',array('id' => $cty->city),'name'),
					'salon_type' => $cty->salon_type,
					'title' => $cty->salon_name,
					'address' => $cty->address,
					'ac_type' => $cty->ac_type,
					'start_time' => $cty->start_time,
					'end_time' => $cty->end_time,
				);
				// get reviews
				$reviews  = $Commn->where_selectAll('salon-review',array('salon_id'=> $cty->id),'');
				$total_plus_ratting;
				if(isset($reviews) && !empty($reviews)){
					$total_plus_ratting =0;
					foreach ($reviews as $key => $review) {
						$total_plus_ratting += (int)$review->star_review;
					}
					$review = isset($total_plus_ratting) ? ($total_plus_ratting/count($reviews)) : "0";
					$sal_arr['review'] = (string)$review;
				}else {
				    	$sal_arr['review'] = "0";
				}
				 array_push($salon_arr,$sal_arr);
		}
       }
       if(isset($salon_arr) && !empty($salon_arr)){
            $response = array('message'=> 'successfully get search','code'=> 200, 'search' => $salon_arr);
            echo json_encode($response);
       }else{
            $response = array('message'=> 'record is not found','code'=> 400);
           echo json_encode($response);
       }
	}
}
?>
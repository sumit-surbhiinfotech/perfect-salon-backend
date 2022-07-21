<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-Type: application/json; charset=utf-8');
class Home extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('commn');
    }
	public function salon(){
		$Commn = new Commn();
		$salon_lists =  $Commn->where_selectAll('salon-list',array('is_approve'=>1),'*');
		$user_id = $this->input->get('user_id');
		$salon_arr = array();
		
		foreach ($salon_lists as $key => $salon_list) {
		  //  echo  $Commn->select_get_row_data('salon-list',array('id'=> $salon_list->id),'user_id');
		        $salon_user_status  = $Commn->select_get_row_data('users',array('id'=> $Commn->select_get_row_data('salon-list',array('id'=> $salon_list->id),'user_id')),'status');
		        if(empty($user_id)){
		              //  $salon_user_status = '';
		                 $salon_user_status  = $Commn->select_get_row_data('users',array('id'=> $Commn->select_get_row_data('salon-list',array('id'=> $salon_list->id),'user_id')),'status');
		        }else{
                        $salon_user_status  = $Commn->select_get_row_data('salon_booking_user',array('salon_id'=> $salon_list->id, 'user_id'=> $user_id),'status');
		        }
                // echo $salon_user_status;
                if($salon_user_status == 1 || $salon_user_status == ''){
                    
				$images = explode(',', $salon_list->image);
				$banner_images  = $Commn->where_selectAll('salon_banner_image',array('salon_id'=> $salon_list->id,'status'=>1),'image');
		        $image = '';
		        if(isset($banner_images) && !empty($banner_images)){
		            if(!empty($banner_images[0]->image)){
		                $image = base_url().'assets/images/salon/banner/'.$banner_images[0]->image;
		            }else{
		                $image = isset($images[0]) ? base_url().'assets/images/salon/'.$images[0] : base_url().'assets/images/default.jpg';
		            }
		        }else{ if(empty($images[0])){$images[0]= 'default.jpg';} $image = isset($images[0]) ? base_url().'assets/images/salon/'.$images[0] : 'default.jpg';}
				$sal_arr = array(
					'id' => $salon_list->id,
					'review' => "0",
					'image' => $image,
				// 	'city' =>  $salon_list->city,//$Commn->select_get_row_data('cities',array('id' =>  $Commn->select_get_row_data('users',array('id' =>  $Commn->select_get_row_data('salon-list',array('id' =>  $salon_list->id),'user_id')),'city')),'name'),
				// 	'city' =>  $Commn->select_get_row_data('cities',array('id' =>  $Commn->select_get_row_data('users',array('id' =>  $Commn->select_get_row_data('salon-list',array('id' =>  $salon_list->id),'user_id')),'city')),'name'),
				    'city' =>  $Commn->select_get_row_data('cities',array('id' => $salon_list->city),'name'),
					'salon_type' => $salon_list->salon_type,
					'title' => $salon_list->salon_name,
					'address' => $salon_list->address,
					'ac_type' => $salon_list->ac_type,
					'start_time' => $salon_list->start_time,
					'end_time' => $salon_list->end_time,
				);
				
				if(!empty($user_id)){
			    $fav =  $Commn->get_row_data('salon_favioute', array('salon_id' =>  $salon_list->id, 'user_id' => $user_id));
			      if(isset($fav) && !empty($fav)){
			         if(!empty($fav->is_like) && $fav->is_like != null){
    			        $sal_arr['is_favorite'] = (string)$fav->is_like;
    			    }else{
    			        $sal_arr['is_favorite'] = (string)$fav->is_like;
    			    }
			      }else{
			            $sal_arr['is_favorite'] = "0";
			      }
    			   
    			}else{
    			    $sal_arr['is_favorite'] = "0";
    			}
 
				// get reviews
				$reviews  = $Commn->where_selectAll('salon-review',array('salon_id'=> $salon_list->id),'');
				$total_plus_ratting;
				if(isset($reviews) && !empty($reviews)){
					$total_plus_ratting =0;
					foreach ($reviews as $key => $review) {
						$total_plus_ratting += (int)$review->star_review;
					}
					$review = isset($total_plus_ratting) ? ($total_plus_ratting/count($reviews)) : "0";
					$sal_arr['review'] = isset($review) ? (string)$review : "0";
				}
			 array_push($salon_arr,$sal_arr);
          }
		}
		if(!empty($salon_arr)){
            $response = array('message'=> 'successfully get salon list','code'=> 200,'salon'=> $salon_arr);
            echo json_encode($response);
        }else{
            $response = array('message'=> 'salon not found','code'=> 400);
            echo json_encode($response); 
        }   
	}
	
	public function country(){
	    $Commn = new Commn();
	    $country =  $Commn->selectAll('countries');
	    if(!empty($country)){
            $response = array('message'=> 'successfully get country list','code'=> 200,'country'=> $country);
            echo json_encode($response);
        }else{
            $response = array('message'=> 'country not found','code'=> 400);
            echo json_encode($response); 
        } 
	}
	
	public function state(){
	    $Commn = new Commn();
	    $country_id = $this->input->post('country_id');
	    $state =  $Commn->where_selectAll('states',array('country_id' => $country_id),'');
	    if(!empty($state)){
            $response = array('message'=> 'successfully get state list','code'=> 200,'state'=> $state);
            echo json_encode($response);
        }else{
            $response = array('message'=> 'state not found','code'=> 400);
            echo json_encode($response); 
        } 
	}
	
	public function city(){
	    $Commn = new Commn();
	    $state_id = $this->input->post('state_id');
	    $cities =  $Commn->where_selectAll('cities',array('state_id' => $state_id),'');
	    if(!empty($cities)){
            $response = array('message'=> 'successfully get city list','code'=> 200,'state'=> $cities);
            echo json_encode($response);
        }else{
            $response = array('message'=> 'city not found','code'=> 400);
            echo json_encode($response); 
        } 
	}
	
	public function slaon_categories(){
	    $Commn = new Commn();
	    $slaon_categories =  $Commn->selectAll('salon_type');
	    $cate_arr = array();
	    if(isset($slaon_categories)){
	        foreach($slaon_categories as $slaon_categorie){
	            $cate=  array('name' => $slaon_categorie->type_name , 'icon' => base_url().'assets/category/'.$slaon_categorie->icon);
	            array_push($cate_arr,$cate);
	        }
	    }
	    if(!empty($cate_arr)){
            $response = array('message'=> 'successfully get slaon categories','code'=> 200,'slaon_categories'=> $cate_arr);
            echo json_encode($response);
        }else{
            $response = array('message'=> 'slaon categories not found','code'=> 400);
            echo json_encode($response); 
        }     
	}
	
}
?>
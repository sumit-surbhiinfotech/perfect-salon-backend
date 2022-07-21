<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

class Dashboard extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('commn');
        $this->load->model('Dashboard_Model');
    }
    
    public function dashboard_old(){
        $Dashboard = new Dashboard_Model();
        
        $key = $this->input->post('key');
        if($key == ''){
            $key = 'today';
        }
        
        $first = '';
        $last = '';
        
        if($key == 'today'){
            $first = '';
            $last = date('Y-m-d');
        }
        if($key == 'yesterday'){
            $first = '';
            $last = date('Y-m-d', strtotime());
        }
        if($key == 'week'){
            $date_start = strtotime('-' . date('w') . ' days');
            $date_start = date('Y-m-d', $date_start);
            $day = date('w');
            $first = date('Y-m-d');
            $last = date('Y-m-d', strtotime('-'.$day.' days')); 
        }
        if($key == 'month'){
            $first=  date('Y-m-01');
            $last = date("Y-m-d");
        }
        if($key == 'year'){
            $first = date("Y-01-01");
            $last = date("Y-12-31");
        }
        if($key == "custom_date"){
            $first = date('Y-m-d', strtotime($this->input->post('custom_from_date')));
            $last = date('Y-m-d', strtotime($this->input->post('custom_to_date')));
        }
        
        // seller
        $seller_new_users =  $Dashboard->new_users('created_at BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND NOW()', array('role' => 2,'status'=>2), 'users');
        $count_seller = count($seller_new_users);
        
        // $total_seller_users =  $Dashboard->total_users(array('role' => 2), 'users');
        // $total_seller_users = count($total_seller_users);
        
        // $rasion_of_seller_users = ((int)$count_seller * 100);
        // $rasion_of_seller_users = ($rasion_of_seller_users / (int)$total_seller_users);
        
        // $rasion_of_seller_users = round($rasion_of_seller_users);
        
        // users
        $new_users =  $Dashboard->new_users('created_at BETWEEN DATE_SUB(NOW(), INTERVAL 15 DAY) AND NOW()', array('role' => 1,'status'=>0), 'users');
        $count_users = count($new_users);
        
        // $total_users =  $Dashboard->total_users(array('role' => 1), 'users');
        // $total_users = count($total_users);
        
        // $rasion_of_users = ((int)$count_users * 100);
        // $rasion_of_users = ($rasion_of_users / (int)$total_users);
        
        // $rasion_of_users = round($rasion_of_users);
        
        
        // active seller
        $total_active_seller =  $Dashboard->total_users(array('role' => 2,'status' => 1), 'users');
        $total_active_seller = count($total_active_seller);
        
        // $rasion_of_seller = ((int)$total_active_seller * 100);
        // $rasion_of_seller = ($rasion_of_seller / (int)$total_seller_users);
        
        // $rasion_of_active_seller = round($rasion_of_seller);
        

        // active users
        $total_active_users =  $Dashboard->total_users(array('role' => 1,'status' => 1), 'users');
        $total_active_users = count($total_active_users);
        
        // $rasion_of_active_users = ((int)$total_active_users * 100);
        // $rasion_of_active_users = ($rasion_of_active_users / (int)$total_users);
        
        // $rasion_of_active_users = round($rasion_of_active_users);
        
        
        
        // blocked seller
        $total_blocked_users =  $Dashboard->total_users(array('role' => 2,'status' => 0), 'users');
        $total_blocked_users = count($total_blocked_users); 
        
        // $rasion_of_blocked_users = ((int)$total_blocked_users * 100);
        // $rasion_of_blocked_users = ($rasion_of_blocked_users / (int)$total_seller_users);
        
        // $rasion_of_blocked_users = round($rasion_of_blocked_users);
        
        // $i =28;
        // $i++;
        // $day = '';
        // $week = (60/7);
        // if($i == "29"){
        //     $day = ($week - $i);
        // }
        // echo $day;
        
        $data = array(
            'users_counts' => array(
                'new_partners' => (string)$count_seller,
                'new_users' => (string)$count_users,
                'active_partners' => (string)$total_active_seller,
                'active_users' => (string)$total_active_users,
                'blocked_partners' => (string)$total_blocked_users,
                'total_transction_amount' =>  "₹ ".$Dashboard->sum_quantity_of_stacks(),
            ),
            'transcation' => array(),
            'users' => array(),
            'partners' => array(),
            'category' => array(),
            'top_services' => array()
        ); 
        $response = array('message'=> 'Successfully Get Dashboard','code'=> 200,'dashboard' => $data);
        echo json_encode($response);
        
    }
    
    public function dashboard(){
        $Dashboard = new Dashboard_Model();
        $key = $this->input->post('key');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        
     
        if($key == ''){
            $key = 'month';
        }
        
      
        
        if($key == 'custom_date'){
            
            $from_date = $from_date;
            $to_date = $to_date;
            
        }else{
                $from_date = '';
                $to_date = '';
        }
        $get_seller = $Dashboard->get_seller($key,$from_date, $to_date,2);
        $total_count_seller = count($get_seller);
        
        
        $get_user = $Dashboard->get_seller($key,$from_date, $to_date,1);
        $total_count_user = count($get_user);
        
        // active seller
        $total_active_seller =  $Dashboard->total_users(array('role' => 2,'status' => 1), 'users',$key,$from_date, $to_date);
        $total_active_seller = count($total_active_seller);
        
        // active users
        $total_active_users =  $Dashboard->total_users(array('role' => 1,'status' => 1), 'users',$key,$from_date, $to_date);
        $total_active_users = count($total_active_users);
        
        // blocked seller
        $total_blocked_users =  $Dashboard->total_users(array('role' => 2,'status' => 0), 'users',$key,$from_date, $to_date);
        $total_blocked_users = count($total_blocked_users); 
        
        
        if($key == 'today' || $key == 'yesterday' || $key == 'week'){
               
                $users_history = $Dashboard->dashboard_user_history($key,$from_date,$to_date,1);
                $day_history  = array('Sunday' => array('total_users' => "0", 'day' => 'Sunday'), 'Monday' => array('total_users' => "0", 'day' => 'Monday'), 'Tuesday' => array('total_users' => "0", 'day' => 'Tuesday'),'Wednesday' => array('total_users' => "0", 'day' => 'Wednesday'), 'Thursday' => array('total_users' => "0", 'day' => 'Thursday'), 'Friday' => array('total_users' => "0", 'day' => 'Friday'), 'Saturday' => array('total_users' => "0", 'day' => 'Saturday'));    
                if(!empty($users_history)){
                            
                            foreach($users_history as $user){
                                $day_history[$user->day] = array('total_users' => $user->total_users, 'day' => $user->day);
                            }
                            
                    }
                $users_history = $day_history;
        }
        
        
        $order_users_month = [];
        if($key == 'month' ||  $key == 'custom_date'){
                
                if($key == 'month'){
                    $current_date = date('Y-m-d');
                    $first_date = date('Y-m-01');    
                }else{
                    $first_date = $from_date;
                    $current_date = $to_date;
                    
                }
            
                
                $date_diff_count = $Dashboard->dateDifference($first_date,$current_date);
                $user_his_month_arr =array();
                    if($date_diff_count <= 7 ){
                        $users_history_month = $Dashboard->dashboard_user_month_history_less_days($key,$first_date,$current_date,1);
                           
                         foreach($users_history_month as $key1 => $user_month){
                            array_push($user_his_month_arr,array('total_users' => $user_month->total_users,'day' => $user_month->day));        
                        }
                    }else {
                        $users_history_month = $Dashboard->dashboard_user_month_history_new($key,$first_date,$current_date,1);
                        foreach($users_history_month as $key1 => $user_month){
                            array_push($user_his_month_arr,array('total_users' => $user_month->total_users,'day' => 'Week '.($key1+1)));        
                        }
                    }
                    $users_history = $user_his_month_arr;
                    
        }
        
        if($key == 'year'){
                $users_history_year = $Dashboard->dashboard_user_year_history($key,$from_date,$to_date,1);
                $year = array("1" => array('total_users' => "0", 'day'=>'January'),"2" => array('total_users' => "0", 'day'=>'February'),"3" => array('total_users' => "0",'day'=>'March'),"4" => array('total_users' => "0", 'day'=>'April'),"5" => array('total_users' => "0",'day'=>'May'),"6" => array('total_users' => "0",'day'=>'June'),"7" => array('total_users' => "0",'day'=>'July'),"8" => array('total_users' => "0",'day'=>'August'),"9" => array('total_users' => "0", 'day'=>'September'),"10" => array('total_users' => "0",'day'=>'October'),"11" => array('total_users' => "0", 'day'=>'November'),"12" => array('total_users' => "0", 'day'=>'December'));
                $user_his_year_arr =array();
                if(!empty($users_history_year)){
                    foreach($users_history_year as $i => $user_year){
                         $year[$user_year->day] = array('total_users' => $user_year->total_users, 'day' => $year[$user_year->day]['day']);
                    }
                    $users_history = $year;
                }
        }
        $users_history = array_values($users_history);
        
        // partner chart
        
        if($key == 'today' || $key == 'yesterday' || $key == 'week'){
               
                $salons_history = $Dashboard->dashboard_user_history($key,$from_date,$to_date,2);
                $day_history  = array('Sunday' => array('total_users' => "0", 'day' => 'Sunday'), 'Monday' => array('total_users' => "0", 'day' => 'Monday'), 'Tuesday' => array('total_users' => "0", 'day' => 'Tuesday'),'Wednesday' => array('total_users' => "0", 'day' => 'Wednesday'), 'Thursday' => array('total_users' => "0", 'day' => 'Thursday'), 'Friday' => array('total_users' => "0", 'day' => 'Friday'), 'Saturday' => array('total_users' => "0", 'day' => 'Saturday'));    
                if(!empty($salons_history)){
                            
                            foreach($salons_history as $user){
                                $day_history[$user->day] = array('total_users' => $user->total_users, 'day' => $user->day);
                            }
                            
                    }
                $salons_history = $day_history;
        }
        
        
        $order_users_month = [];
        if($key == 'month' ||  $key == 'custom_date'){
                
                if($key == 'month'){
                    $current_date = date('Y-m-d');
                    $first_date = date('Y-m-01');    
                }else{
                    $first_date = $from_date;
                    $current_date = $to_date;
                    
                }
            
                
                $date_diff_count = $Dashboard->dateDifference($first_date,$current_date);
                $salon_his_month_arr =array();
                    if($date_diff_count <= 7 ){
                        $salon_history_month = $Dashboard->dashboard_user_month_history_less_days($key,$first_date,$current_date,2);
                           
                         foreach($salon_history_month as $key1 => $user_month){
                            array_push($salon_his_month_arr,array('total_users' => $user_month->total_users,'day' => $user_month->day));        
                        }
                    }else {
                        $salon_history_month = $Dashboard->dashboard_user_month_history_new($key,$first_date,$current_date,2);
                        foreach($salon_history_month as $key1 => $user_month){
                            array_push($salon_his_month_arr,array('total_users' => $user_month->total_users,'day' => 'Week '.($key1+1)));        
                        }
                    }
                    $salons_history = $salon_his_month_arr;
                    
        }
        
        if($key == 'year'){
                $salon_history_year = $Dashboard->dashboard_user_year_history($key,$from_date,$to_date,2);
                $year = array("1" => array('total_users' => "0", 'day'=>'January'),"2" => array('total_users' => "0", 'day'=>'February'),"3" => array('total_users' => "0",'day'=>'March'),"4" => array('total_users' => "0", 'day'=>'April'),"5" => array('total_users' => "0",'day'=>'May'),"6" => array('total_users' => "0",'day'=>'June'),"7" => array('total_users' => "0",'day'=>'July'),"8" => array('total_users' => "0",'day'=>'August'),"9" => array('total_users' => "0", 'day'=>'September'),"10" => array('total_users' => "0",'day'=>'October'),"11" => array('total_users' => "0", 'day'=>'November'),"12" => array('total_users' => "0", 'day'=>'December'));
                $user_his_year_arr =array();
                if(!empty($salon_history_year)){
                    foreach($salon_history_year as $i => $user_year){
                         $year[$user_year->day] = array('total_users' => $user_year->total_users, 'day' => $year[$user_year->day]['day']);
                    }
                    $salons_history = $year;
                }
        }
        $salons_history = array_values($salons_history);
        
        // transaction 
        $transaction = array();
        // print_r($key); 
        // die;
        if($key == 'today' || $key == 'yesterday' || $key == 'week'){
               
                $transaction = $Dashboard->dashboard_user_transaction($key,$from_date,$to_date,1);
                // print_r($this->db->last_query());
                // die;
                $day_history  = array('Sunday' => array('total_amount' => "0", 'day' => 'Sunday'), 'Monday' => array('total_amount' => "0", 'day' => 'Monday'), 'Tuesday' => array('total_amount' => "0", 'day' => 'Tuesday'),'Wednesday' => array('total_amount' => "0", 'day' => 'Wednesday'), 'Thursday' => array('total_amount' => "0", 'day' => 'Thursday'), 'Friday' => array('total_amount' => "0", 'day' => 'Friday'), 'Saturday' => array('total_amount' => "0", 'day' => 'Saturday'));    
                if(!empty($transaction)){
                            foreach($transaction as $user){
                                
                                $day_history[$user->day] = array('total_amount' => $user->total_amount, 'day' => $user->day);
                            }
                            
                    }
                $transaction = $day_history;
        }
        
         $transction_month = [];
        if($key == 'month' ||  $key == 'custom_date'){
                
                if($key == 'month'){
                    $current_date = date('Y-m-d');
                    $first_date = date('Y-m-01');    
                }else{
                    $first_date = $from_date;
                    $current_date = $to_date;
                    
                }
            
                
                $date_diff_count = $Dashboard->dateDifference($first_date,$current_date);
                $salon_tra_month_arr =array();
                    if($date_diff_count <= 7 ){
                        $salon_tra_month = $Dashboard->dashboard_tra_month_history_less_days($key,$first_date,$current_date,2);   
                        foreach($salon_tra_month as $key1 => $tra_month){
                            array_push($salon_tra_month_arr,array('total_amount' => $tra_month->total_amount,'day' => $tra_month->day));        
                        }
                    }else {
                        $salon_tra_month = $Dashboard->dashboard_tra_month_history_new($key,$first_date,$current_date,2);
                        foreach($salon_tra_month as $key1 => $tra_month){
                            array_push($salon_tra_month_arr,array('total_amount' => $tra_month->total_amount,'day' => 'Week '.($key1+1)));        
                        }
                    }
                    $transaction = $salon_tra_month_arr;
                    
        }
        
        if($key == 'year'){
                $tra_history_year = $Dashboard->dashboard_tra_year_history($key,$from_date,$to_date,2);
                $year = array("1" => array('total_amount' => "0", 'day'=>'January'),"2" => array('total_amount' => "0", 'day'=>'February'),"3" => array('total_amount' => "0",'day'=>'March'),"4" => array('total_amount' => "0", 'day'=>'April'),"5" => array('total_amount' => "0",'day'=>'May'),"6" => array('total_amount' => "0",'day'=>'June'),"7" => array('total_amount' => "0",'day'=>'July'),"8" => array('total_amount' => "0",'day'=>'August'),"9" => array('total_amount' => "0", 'day'=>'September'),"10" => array('total_amount' => "0",'day'=>'October'),"11" => array('total_amount' => "0", 'day'=>'November'),"12" => array('total_amount' => "0", 'day'=>'December'));
                $tra_his_year_arr =array();
                if(!empty($tra_history_year)){
                    foreach($tra_history_year as $i => $tra_year){
                         $year[$tra_year->day] = array('total_amount' => $tra_year->total_amount, 'day' => $year[$tra_year->day]['day']);
                    }
                    $tra_history = $year;
                }
        }
        $transaction = array_values($tra_history);
        // category chart
        
        $dashboard_category = $Dashboard->dashboard_category($key,$from_date,$to_date);
        
        // services chart 
        
        $dashboard_services = $Dashboard->dashboard_services($key,$from_date,$to_date);
        
        $data = array(
            'users_counts' => array(
                'new_partners' => (string)$total_count_seller,
                'new_users' => (string)$total_count_user,
                'active_partners' => (string)$total_active_seller,
                'active_users' => (string)$total_active_users,
                'blocked_partners' => (string)$total_blocked_users,
                'total_transction_amount' =>  "₹ ".$Dashboard->sum_quantity_of_stacks($key,$from_date, $to_date),
            ),
            'transcation' => $transaction,
            'users' => $users_history,
            'partners' => $salons_history,
            'category' => $dashboard_category,
            'top_services' => $dashboard_services
        ); 
        $response = array('message'=> 'Successfully Get Dashboard','code'=> 200,'dashboard' => $data);
        echo json_encode($response);
    }
}

?>
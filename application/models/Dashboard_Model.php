<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Dashboard_Model extends CI_Model
{
    public $Modal;

    public function __construct()
    {
        parent::__construct();
    }
    
    public function new_users($where1, $where2, $table){
        
        $this->db->select('*');
        $this->db->where($where1);
        $this->db->where($where2);
        $result = $this->db->get($table)->result();
        return $result;
    }
    
    public function total_users($where,$table,$key,$from_date,$to_date){
        // $this->db->select('*');
        $this->db->select('*');
        if($key == 'today'){
          $this->db->where('DATE(register_date) =',date('Y-m-d'));
        }
        if($key == 'yesterday'){
            $this->db->where('DATE(register_date) =',date('Y-m-d',strtotime("-1 days"))); 
        }
        if($key == 'week'){
            $date_start = strtotime('-' . date('w') . ' days');
            $date_start = date('d-m-Y', $date_start);
            $day = date('w');
            $week_start = date('Y-m-d', strtotime('-'.$day.' days'));
            $week_end = date('Y-m-d', strtotime('+'.(6-$day).' days'));
            
            $this->db->where('DATE(register_date) >=', $week_start);
            $this->db->where('DATE(register_date) <=', $week_end);
            
        }
        if($key == 'month'){
            $first = date('Y-m-01'); // hard-coded '01' for first day
            $last  = date('Y-m-d');
            
            $this->db->where('DATE(register_date) >=', $first);
            $this->db->where('DATE(register_date) <=', $last);
        }
        if($key == 'year'){
            $year = date('Y');
            $start = mktime(0, 0, 0, 1, 1, $year);
            $end = mktime(0, 0, 0, 12, 31, $year);
            
            $first = date('Y-m-d', $start);
            $last = date('Y-m-d', $end);
            
            $this->db->where('DATE(register_date) >=', $first);
            $this->db->where('DATE(register_date) <=', $last);
        }
        if($key == 'custom_date'){
            $this->db->where('DATE(register_date) >=', $from_date);
            $this->db->where('DATE(register_date) <=', $to_date);
        }
        if(!empty($where)){
            $this->db->where($where);            
        }
        $result = $this->db->get($table)->result();
        return $result;    
    }
    public function sum_quantity_of_stacks($key,$from_date,$to_date){
        $this->db->select_sum('total_amount','total');
        if($key == 'today'){
          $this->db->where('DATE(booking_date) =',date('Y-m-d'));
        }
        if($key == 'yesterday'){
            $this->db->where('DATE(booking_date) =',date('Y-m-d',strtotime("-1 days"))); 
        }
        if($key == 'week'){
            $date_start = strtotime('-' . date('w') . ' days');
            $date_start = date('d-m-Y', $date_start);
            $day = date('w');
            $week_start = date('Y-m-d', strtotime('-'.$day.' days'));
            $week_end = date('Y-m-d', strtotime('+'.(6-$day).' days'));
            
            $this->db->where('DATE(booking_date) >=', $week_start);
            $this->db->where('DATE(booking_date) <=', $week_end);
            
        }
        if($key == 'month'){
            $first = date('Y-m-01'); // hard-coded '01' for first day
            $last  = date('Y-m-d');
            
            $this->db->where('DATE(booking_date) >=', $first);
            $this->db->where('DATE(booking_date) <=', $last);
        }
        if($key == 'year'){
            $year = date('Y');
            $start = mktime(0, 0, 0, 1, 1, $year);
            $end = mktime(0, 0, 0, 12, 31, $year);
            
            $first = date('Y-m-d', $start);
            $last = date('Y-m-d', $end);
            
            $this->db->where('DATE(booking_date) >=', $first);
            $this->db->where('DATE(booking_date) <=', $last);
        }
        if($key == 'custom_date'){
            $this->db->where('DATE(booking_date) >=', $from_date);
            $this->db->where('DATE(booking_date) <=', $to_date);
        }
        $query = $this->db->get('salon_booking');
        $res = $query->row();
        return $res->total;
    }
    
    public function get_seller($key, $from_date, $to_date,$role){
        $this->db->select('*');
        if($key == 'today'){
          $this->db->where('DATE(register_date) =',date('Y-m-d'));
        }
        if($key == 'yesterday'){
            $this->db->where('DATE(register_date) =',date('Y-m-d',strtotime("-1 days"))); 
        }
        if($key == 'week'){
            $date_start = strtotime('-' . date('w') . ' days');
            $date_start = date('d-m-Y', $date_start);
            $day = date('w');
            $week_start = date('Y-m-d', strtotime('-'.$day.' days'));
            $week_end = date('Y-m-d', strtotime('+'.(6-$day).' days'));
            
            $this->db->where('DATE(register_date) >=', $week_start);
            $this->db->where('DATE(register_date) <=', $week_end);
            
        }
        if($key == 'month'){
            $first = date('Y-m-01'); // hard-coded '01' for first day
            $last  = date('Y-m-d');
            
            $this->db->where('DATE(register_date) >=', $first);
            $this->db->where('DATE(register_date) <=', $last);
        }
        if($key == 'year'){
            $year = date('Y');
            $start = mktime(0, 0, 0, 1, 1, $year);
            $end = mktime(0, 0, 0, 12, 31, $year);
            
            $first = date('Y-m-d', $start);
            $last = date('Y-m-d', $end);
            
            $this->db->where('DATE(register_date) >=', $first);
            $this->db->where('DATE(register_date) <=', $last);
        }
        if($key == 'custom_date'){
            $this->db->where('DATE(register_date) >=', $from_date);
            $this->db->where('DATE(register_date) <=', $to_date);
        }
        $this->db->where('role', $role);
        $this->db->where('status', 2);
        return $this->db->get('users')->result();
    }
    
    public function dashboard_user_history($key,$from_date,$to_date,$role){
        
         $this->db->select('COUNT(*) AS total_users, DAYNAME(register_date) as day');
         if($key == 'today'){
            $this->db->where('DATE(register_date) =',date('Y-m-d'));
         }
         if($key == 'yesterday'){
            $this->db->where('DATE(register_date) =',date('Y-m-d',strtotime("-1 days")));
         }
         if($key == 'week'){
             
            $date_start = strtotime('-' . date('w') . ' days');
            $date_start = date('Y-m-d', $date_start);
            $day = date('w');
            $week_start = date('Y-m-d', strtotime('-'.$day.' days'));
            $week_end = date('Y-m-d', strtotime('+'.(6-$day).' days')); 
            $this->db->where('DATE(register_date) >=', $week_start);
            $this->db->where('DATE(register_date) <=', $week_end);
         }
         if($key == 'custom_date'){
            $this->db->where('DATE(register_date) >=', $from_date);
            $this->db->where('DATE(register_date) <=', $to_date);
         }
        $this->db->from('users');
        $this->db->where('role',$role);
        
       
        $this->db->group_by('DAYNAME(register_date)');
        // $this->db->order_by('DAYNAME(register_date)');
        return $this->db->get()->result();
    }
    
    public function dashboard_user_transaction($key,$from_date,$to_date,$role){
        
         $this->db->select_sum('total_amount');
         $this->db->select('total_amount, DAYNAME(booking_date) as day');
         if($key == 'today'){
            $this->db->where('DATE(booking_date) =',date('Y-m-d'));
         }
         if($key == 'yesterday'){
            $this->db->where('DATE(booking_date) =',date('Y-m-d',strtotime("-1 days")));
         }
         if($key == 'week'){
             
            $date_start = strtotime('-' . date('w') . ' days');
            $date_start = date('Y-m-d', $date_start);
            $day = date('w');
            $week_start = date('Y-m-d', strtotime('-'.$day.' days'));
            $week_end = date('Y-m-d', strtotime('+'.(6-$day).' days')); 
            $this->db->where('DATE(booking_date) >=', $week_start);
            $this->db->where('DATE(booking_date) <=', $week_end);
         }
         if($key == 'custom_date'){
            $this->db->where('DATE(booking_date) >=', $from_date);
            $this->db->where('DATE(booking_date) <=', $to_date);
         }
        $this->db->from('salon_booking');
        // $this->db->where('role',$role);
        
       
        $this->db->group_by('DAYNAME(booking_date)');
        // $this->db->order_by('DAYNAME(register_date)');
        return $this->db->get()->result();
    }
    
    public function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);
       
        $interval = date_diff($datetime1, $datetime2);
       
        return $interval->format($differenceFormat);
       
    }
    
    public function dashboard_user_month_history_less_days($key,$from_date,$to_date,$role){
       $this->db->select('COUNT(*) AS total_users, DATE(register_date) as day');
         if($key == 'month'){
            // $first = date("Y-m-d", strtotime("first day of this month"));
            // $last = date("Y-m-d", strtotime("last day of this month"));
            
            $first = date("Y-m-d", mktime(0, 0, 0, date("m"), 1, date("Y")));
            $last = date("Y-m-d", mktime(0, 0, 0, date("m"), date("t"), date("Y")));
        
         }else{
              $first = $from_date;
              $last = $to_date;
         }
          $this->db->where('DATE(register_date) >=', $first);
            $this->db->where('DATE(register_date) <=', $last);
        $this->db->from('users');
        $this->db->where('role', $role);
        
       
        $this->db->group_by('DATE(register_date)');
        // $this->db->order_by('DAYNAME(order_date)');
        return $this->db->get()->result();
   }
   
    public function dashboard_tra_month_history_less_days($key,$from_date,$to_date,$role){
    //   $this->db->select('COUNT(*) AS total_users, DATE(register_date) as day');
         
        //   $this->db->select_sum('total_amount');
         $this->db->select('SUM(total_amount) AS total_amount, DATE(booking_date) as day');
         if($key == 'month'){
            // $first = date("Y-m-d", strtotime("first day of this month"));
            // $last = date("Y-m-d", strtotime("last day of this month"));
            
            $first = date("Y-m-d", mktime(0, 0, 0, date("m"), 1, date("Y")));
            $last = date("Y-m-d", mktime(0, 0, 0, date("m"), date("t"), date("Y")));
        
         }else{
              $first = $from_date;
              $last = $to_date;
         }
        $this->db->where('DATE(booking_date) >=', $first);
        $this->db->where('DATE(booking_date) <=', $last);
        $this->db->where('booking_status', 3);
        $this->db->from('salon_booking');
        $this->db->group_by('DATE(booking_date)');
        return $this->db->get()->result();
    }
   
   
    public function dashboard_tra_month_history_new($key,$from_date,$to_date,$role){
        //  $this->db->select_sum('total_amount');
         $this->db->select('SUM(total_amount) AS total_amount, WEEK(booking_date) as day');
         if($key == 'month'){
           $first = date('Y-m-01');
            $last = date("Y-m-d");
        
         }else{
              $first = $from_date;
              $last = $to_date;
         }
        $this->db->where('DATE(booking_date) >=', $first);
        $this->db->where('DATE(booking_date) <=', $last);
        $this->db->where('booking_status', 3);
        $this->db->from('salon_booking');
        $this->db->group_by('WEEK(booking_date)');
        return $this->db->get()->result();
    }
    
    public function dashboard_user_month_history_new($key,$from_date,$to_date,$role){
        
         $this->db->select('COUNT(*) AS total_users, WEEK(register_date) as day');
         if($key == 'this_month'){
            $first = date('Y-m-01');
            $last = date("Y-m-d");

         }else{
             $first = $from_date;
            $last = $to_date;
         }
            $this->db->where('DATE(register_date) >=', $first);
            $this->db->where('DATE(register_date) <=', $last);
        $this->db->from('users');
       $this->db->where('role', $role);
        $this->db->group_by('WEEK(register_date)');
        return $this->db->get()->result();
    }
    
    public function dashboard_user_year_history($key,$from_date,$to_date,$role){
        
         $this->db->select('COUNT(*) AS total_users, MONTH(register_date) as day');
         if($key == 'this_year'){
            // $first = date("Y-m-d", strtotime("first day of this month"));
            // $last = date("Y-m-d", strtotime("last day of this month"));
            
             $first = date("Y-m-d", strtotime('first day of january this year'));
            $last = date("Y-m-d", strtotime('last day of december this year'));
            $this->db->where('DATE(register_date) >=', $first);
            $this->db->where('DATE(register_date) <=', $last);
         }
        $this->db->from('users');
        $this->db->where('role', $role);
        
       
        $this->db->group_by('MONTH(register_date)');
        // $this->db->order_by('DAYNAME(order_date)');
        return $this->db->get()->result();
    }
    public function dashboard_tra_year_history($key,$from_date,$to_date,$role){
        
         $this->db->select('SUM(total_amount) AS total_amount, MONTH(booking_date) as day');
         if($key == 'year'){
            // $first = date("Y-m-d", strtotime("first day of this month"));
            // $last = date("Y-m-d", strtotime("last day of this month"));
            
             $first = date("Y-m-d", strtotime('first day of january this year'));
            $last = date("Y-m-d", strtotime('last day of december this year'));
            $this->db->where('DATE(booking_date) >=', $first);
            $this->db->where('DATE(booking_date) <=', $last);
         }
        $this->db->where('booking_status', 3);
        $this->db->from('salon_booking');
        $this->db->group_by('MONTH(booking_date)');
        return $this->db->get()->result();
    }
    
    public function dashboard_category($key,$from_date,$to_date){
        $this->db->select('COUNT(*) AS count, salon_type.type_name as type');
        $this->db->from('salon_type');
        $this->db->join('salon-list','salon_type.type_name = salon-list.salon_type');
        $this->db->join('users','salon-list.user_id = users.id');
        if($key == 'today'){
          $this->db->where('DATE(users.register_date) =',date('Y-m-d'));
        }
        if($key == 'yesterday'){
            $this->db->where('DATE(users.register_date) =',date('Y-m-d',strtotime("-1 days"))); 
        }
        if($key == 'week'){
            $date_start = strtotime('-' . date('w') . ' days');
            $date_start = date('d-m-Y', $date_start);
            $day = date('w');
            $week_start = date('Y-m-d', strtotime('-'.$day.' days'));
            $week_end = date('Y-m-d', strtotime('+'.(6-$day).' days'));
            $this->db->where('DATE(users.register_date) >=', $week_start);
            $this->db->where('DATE(users.register_date) <=', $week_end);
            
        }
        if($key == 'month'){
            $first = date('Y-m-01'); // hard-coded '01' for first day
            $last  = date('Y-m-d');
            $this->db->where('DATE(users.register_date) >=', $first);
            $this->db->where('DATE(users.register_date) <=', $last);
        }
        if($key == 'year'){
            $year = date('Y');
            $start = mktime(0, 0, 0, 1, 1, $year);
            $end = mktime(0, 0, 0, 12, 31, $year);
            $first = date('Y-m-d', $start);
            $last = date('Y-m-d', $end);
            $this->db->where('DATE(users.register_date) >=', $first);
            $this->db->where('DATE(users.register_date) <=', $last);
        }
        if($key == 'custom_date'){
            $this->db->where('DATE(users.register_date) >=', $from_date);
            $this->db->where('DATE(users.register_date) <=', $to_date);
        }
        $this->db->where('users.role',2);
        $this->db->group_by('salon_type.type_name');
        return $this->db->get()->result();
    }
    public function dashboard_services($key,$from_date,$to_date){
        $this->db->select('COUNT(*) AS count, salon-services.title as name');
        $this->db->from('salon-services');
        $this->db->join('salon_booking',' salon-services.id = salon_booking.service_id');
        if($key == 'today'){
          $this->db->where('DATE(salon_booking.booking_date) =',date('Y-m-d'));
        }
        if($key == 'yesterday'){
            $this->db->where('DATE(salon_booking.booking_date) =',date('Y-m-d',strtotime("-1 days"))); 
        }
        if($key == 'week'){
            $date_start = strtotime('-' . date('w') . ' days');
            $date_start = date('d-m-Y', $date_start);
            $day = date('w');
            $week_start = date('Y-m-d', strtotime('-'.$day.' days'));
            $week_end = date('Y-m-d', strtotime('+'.(6-$day).' days'));
            $this->db->where('DATE(salon_booking.booking_date) >=', $week_start);
            $this->db->where('DATE(salon_booking.booking_date) <=', $week_end);
            
        }
        if($key == 'month'){
            $first = date('Y-m-01'); // hard-coded '01' for first day
            $last  = date('Y-m-d');
            $this->db->where('DATE(salon_booking.booking_date) >=', $first);
            $this->db->where('DATE(salon_booking.booking_date) <=', $last);
        }
        if($key == 'year'){
            $year = date('Y');
            $start = mktime(0, 0, 0, 1, 1, $year);
            $end = mktime(0, 0, 0, 12, 31, $year);
            $first = date('Y-m-d', $start);
            $last = date('Y-m-d', $end);
            $this->db->where('DATE(salon_booking.booking_date) >=', $first);
            $this->db->where('DATE(salon_booking.booking_date) <=', $last);
        }
        if($key == 'custom_date'){
            $this->db->where('DATE(salon_booking.booking_date) >=', $from_date);
            $this->db->where('DATE(salon_booking.booking_date) <=', $to_date);
        }
        // $this->db->where('users.role',2);
        $this->db->group_by('salon-services.id');
        return $this->db->get()->result();
    }
    
}
?>
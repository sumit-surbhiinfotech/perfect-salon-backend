<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Commn extends CI_Model
{
    public $Modal;

    public function __construct()
    {
        parent::__construct();
    }

    function where_selectAll($table,$where,$select)
    {
        if($select != ''){
            $this->db->select($select);
        }else{
            $this->db->select('*');   
        }
        $this->db->where($where);
        $this->db->from($table);
        $query = $this->db->get();
        $res = $query->result();
        return $res;
    }
    function order_where_selectAll($table,$where,$select,$order_by)
    {
        if($select != ''){
            $this->db->select($select);
        }else{
            $this->db->select('*');   
        }
        $this->db->where($where);
        $this->db->from($table);
        $this->db->order_by($order_by, "desc");
        $query = $this->db->get();
        $res = $query->result();
        return $res;
    }
    function selectAll($table)
    {
        $this->db->select('*');
        $this->db->from($table);
        $query = $this->db->get();
        $res = $query->result();
        return $res;
    }
    function selectAll_new($table,$select)
    {
        if($select != ''){
            $this->db->select($select);
        }else{
            $this->db->select('*');
        }
        $this->db->from($table);
        $query = $this->db->get();
        $res = $query->result();
        return $res;
    }

    public function get_row_data($table,$where){
        $this->db->where($where);
        $result = $this->db->get($table);
      
        $num_row = $result->num_rows();
        
        if($num_row == 1){
            $result = $result->row();  
            return $result; 
        }
    }
    public function select_get_row_data($table,$where,$select){
        $this->db->select($select);
        $this->db->where($where);
        $result = $this->db->get($table);
        $num_row = $result->num_rows();
        if($num_row == 1){
            $result = $result->row()->$select;  
            return $result; 
        }
    }
    public function get_row($table,$where){
        $this->db->where($where);
        $result = $this->db->get($table);
        $num_row = $result->num_rows();
        return $num_row;
        // if($num_row == 1){
        //  $result = $result->row();  
        //  return $result; 
        // }
    }
    public function insert_data($table,$data){
        $this->db->insert($table, $data);
        return 1;
    }
    public function insert_data_new($table,$data){
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }
    
    public function new_insert_data($table,$data){
        $this->db->insert($table, $data);
        return 1;
    }
    public function update_data($table,$data,$where){
        $this->db->where($where);
        $this->db->update($table, $data);
        return 1;
    }
    public function delete_data($table, $where){
        $this->db->where($where);
        $this->db->delete($table);
        return 1;
    }
    public function hightest_val(){
        $this->db->select('id,salon_id');
        $this->db->select('AVG(star_review) avg_rating', FALSE);
        $this->db->from('salon-review');
        $this->db->group_by('salon_id');
        $this->db->order_by('avg_rating', 'desc');
        $q = $this->db->get();
        
        return $q->result_array();
    }
    
    public function search($table, $column, $keyword){
        $this->db->select('*');
        $this->db->from($table);
        // $this->db->where_in($column,$keyword);
        $this->db->like($column, $keyword);
        return $this->db->get()->result();    
    }
      public function search_where($table, $column, $keyword){
        $this->db->select('*');
        $this->db->where(array('is_approve' => 1));
        $this->db->from($table);
        
        // $this->db->where_in($column,$keyword);
        $this->db->like($column, $keyword);
        return $this->db->get()->result();    
    }
    public function cate_search($table, $column, $keyword){
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where_in($column,$keyword);
        // $this->db->like($column, $keyword);
        return $this->db->get()->result();    
    }
    
    public function cate_search_where($table, $column, $keyword){
        $this->db->select('*');
        $this->db->where(array('is_approve' => 1));
        $this->db->from($table);
        $this->db->where_in($column,$keyword);
        // $this->db->like($column, $keyword);
        return $this->db->get()->result();    
    }
    
    
    public function get_favorite($user_id){
        $this->db->select('*,salon_favioute.id as id, salon-list.id as salon_id');
        $this->db->from('salon_favioute');
        $this->db->where('salon_favioute.user_id',$user_id);
        $this->db->where(array('salon-list.is_approve'=>1));
        $this->db->join('salon-list', 'salon_favioute.salon_id = salon-list.id');
        $query = $this->db->get();
        return $query->result();
    }
    
    public function get_my_booking($user_id){
        $this->db->select('*,salon_booking.id as id, salon_booking.is_payment as booking_is_payment');
        $this->db->from('salon_booking');
        $this->db->where('salon_booking.user_id',$user_id);
        $this->db->join('salon-list', 'salon_booking.salon_id = salon-list.id');
        $this->db->order_by('salon_booking.id',"desc");
        $query = $this->db->get();
        return $query->result();
    }
    
    public function get_seller_booking($user_id){
        $this->db->select('*,salon_booking.id as id, salon_booking.user_id as c_id, salon_booking.is_payment as is_payment');
        $this->db->from('salon_booking');
        $this->db->where(array('salon-list.user_id'=> $user_id,'salon_booking.booking_status' => 1));
        $this->db->join('salon-list', 'salon_booking.salon_id = salon-list.id');
        $query = $this->db->get();
        return $query->result();    
    }
    
    public function get_earn_data($user_id, $payment_type){
         $this->db->select('id');
         $this->db->from('salon-list');
         $this->db->where('user_id', $user_id);
         $query = $this->db->get();
         $salon = $query->row(); 
       
         if(isset($salon->id)){
            // $this->db->select_sum('total_pay');
            $this->db->from('salon_booking');
            if($payment_type == ''){
                $this->db->where(array('salon_id' => $salon->id));
            }else{
                $this->db->where(array('salon_id' => $salon->id, 'payment_mode' => $payment_type, 'booking_status'=> 3));
            }
            // $this->db->order_by('total_pay desc');
            // $this->db->limit(3);
            $this->db->where('MONTH(booking_date)', date('m'));
            $amount = $this->db->get()->result(); 
            //  echo $this->db->last_query();

            if(isset($amount) && !empty($amount)){
                $total_pay = 0;
                foreach($amount as $rec){
                    $total_pay += $rec->total_amount;
                }
                return $total_pay;
            }else{ return 0;}
         }
         
    }
    
    public function get_earn_data_filter($user_id, $payment_type,$key,$start_date,$end_date){
         $this->db->select('id');
         $this->db->from('salon-list');
         $this->db->where('user_id', $user_id);
         $query = $this->db->get();
         $salon = $query->row(); 
       
         if(isset($salon->id)){
            // $this->db->select_sum('total_pay');
            $this->db->from('salon_booking');
                if($key == 'today'){
                $this->db->where('DATE(booking_date) =',date('Y-m-d'));
             }
             if($key == 'yesterday'){
                $this->db->where('DATE(booking_date) =',date('Y-m-d',strtotime("-1 days")));
             }
             if($key == 'this_week'){
                 
                $date_start = strtotime('-' . date('w') . ' days');
                $date_start = date('Y-m-d', $date_start);
                $day = date('w');
                // $week_start = date('Y-m-d', strtotime('-'.$day.' days'));
                $week_start = date('Y-m-d');
                $week_end = date('Y-m-d', strtotime('-'.$day.' days')); 
                $this->db->where('DATE(booking_date) >=', $week_end);
                $this->db->where('DATE(booking_date) <=', $week_start);
             }
             if($key == 'this_month'){
                $first=  date('Y-m-01');
                
                $last = date("Y-m-d");
          
                $this->db->where('DATE(booking_date) >=', $first);
                $this->db->where('DATE(booking_date) <=', $last);
             }
             if($key == 'this_year'){
                $first = date("Y-01-01");
                $last = date("Y-12-31");
            
                $this->db->where('DATE(booking_date) >=', $first);
                $this->db->where('DATE(booking_date) <=', $last);
             }
             if($key == 'custom_date'){
                $this->db->where('DATE(booking_date) >=', $start_date);
                $this->db->where('DATE(booking_date) <=', $end_date);
             }
            if($payment_type == ''){
                $this->db->where(array('salon_id' => $salon->id));
            }else{
                $this->db->where(array('salon_id' => $salon->id, 'payment_mode' => $payment_type, 'booking_status'=> 3));
            }
            // $this->db->order_by('total_pay desc');
            // $this->db->limit(3);
            $this->db->where('MONTH(booking_date)', date('m'));
            $amount = $this->db->get()->result(); 
            // echo $this->db->last_query();
            // print_r($amount);
            
            if(isset($amount) && !empty($amount)){
                $total_pay = 0;
                foreach($amount as $rec){
                    $total_pay += $rec->total_amount;
                    // echo "$total_pay => ".$total_pay ."/n";
                }
                return $total_pay;
            }else{ return 0;}
         }
         
    }
    
     public function get_earn_all_data($user_id,$start_date,$end_date){
         $this->db->select('id');
         $this->db->from('salon-list');
         $this->db->where('user_id', $user_id);
         $query = $this->db->get();
         $salon = $query->row(); 
         
         if(isset($salon->id)){
            $this->db->select('*');
            $this->db->from('salon_booking');
            $this->db->where(array('salon_id'=> $salon->id, 'booking_status' => 3));
            // $this->db->where('MONTH(booking_date)', date('m'));
            if($start_date != '' && $end_date != ''){
                
            $this->db->where('booking_date BETWEEN "'. date('Y-m-d', strtotime($start_date)). '" and "'. date('Y-m-d', strtotime($end_date)).'"');
            }else{
                $this->db->where('MONTH(booking_date)', date('m'));
            }
            $result = $this->db->get()->result();
            return $result;
         }
         
    }
    
    public function get_earn_all_data_filter($user_id,$key,$start_date,$end_date){
         $this->db->select('id');
         $this->db->from('salon-list');
         $this->db->where('user_id', $user_id);
         $query = $this->db->get();
         $salon = $query->row(); 
         
         if(isset($salon->id)){
            $this->db->select('*');
            $this->db->from('salon_booking');
            $this->db->where(array('salon_id'=> $salon->id, 'booking_status' => 3));
            // $this->db->where('MONTH(booking_date)', date('m'));
            
            if($key == 'today'){
                $this->db->where('DATE(booking_date) =',date('Y-m-d'));
             }
             if($key == 'yesterday'){
                $this->db->where('DATE(booking_date) =',date('Y-m-d',strtotime("-1 days")));
             }
             if($key == 'this_week'){
                 
                $date_start = strtotime('-' . date('w') . ' days');
                $date_start = date('Y-m-d', $date_start);
                $day = date('w');
                // $week_start = date('Y-m-d', strtotime('-'.$day.' days'));
                $week_start = date('Y-m-d');
                $week_end = date('Y-m-d', strtotime('-'.$day.' days')); 
                $this->db->where('DATE(booking_date) >=', $week_start);
                $this->db->where('DATE(booking_date) <=', $week_end);
             }
             if($key == 'this_month'){
                $first=  date('Y-m-01');
                
                $last = date("Y-m-d");
          
                $this->db->where('DATE(booking_date) >=', $first);
                $this->db->where('DATE(booking_date) <=', $last);
             }
             if($key == 'this_year'){
                $first = date("Y-01-01");
                $last = date("Y-12-31");
            
                $this->db->where('DATE(booking_date) >=', $first);
                $this->db->where('DATE(booking_date) <=', $last);
             }
             if($key == 'custom_date'){
                $this->db->where('DATE(booking_date) >=', $start_date);
                $this->db->where('DATE(booking_date) <=', $end_date);
             }
            $result = $this->db->get()->result();
            return $result;
         }
         
    }
    
    public function get_city($search){
        $this->db->select('*');
        $this->db->from('cities');
        $this->db->like('name', $search);
        return $this->db->get()->row();
    }
}
?>
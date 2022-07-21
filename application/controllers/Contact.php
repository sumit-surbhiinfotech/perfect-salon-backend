<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

date_default_timezone_set("Asia/Kolkata");
class Contact extends CI_Controller {
	public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('commn');
    }
    
    
    public function contact(){
        
        $user_id = $this->input->post('user_id');
        $salon_id = $this->input->post('salon_id');
        $email = $this->input->post('email');
        $subject = $this->input->post('subject');
        $content = $this->input->post('content');
        
        if($user_id == ''){
            $response = array('message'=> 'User_id field is required','code'=> 400);
            echo json_encode($response);
            return false;
       }
    //   if($salon_id == ''){
    //         $response = array('message'=> 'salon_id field is required','code'=> 400);
    //         echo json_encode($response);
    //         return false;
    //   }
       if($email == ''){
            $response = array('message'=> 'Email field is required','code'=> 400);
            echo json_encode($response);
            return false;
       }
       if($subject == ''){
            $response = array('message'=> 'Subject field is required','code'=> 400);
            echo json_encode($response);
            return false;
       }
       if($content == ''){
            $response = array('message'=> 'Content field is required','code'=> 400);
            echo json_encode($response);
            return false;
       }       
       
    	$from = $email;
    	
    	$data = array(
    	    'email' => $email,
    	    'subject' => $subject,
    	    'content' => $content,
    	    'user_id' => $user_id,
    	    'salon_id' => $salon_id,
    	    'date' => date('Y-m-d H:i:s'),
    	);
    	$Commn = new Commn();
    	$mail =  $Commn->insert_data('contact_us', $data);
    	if($mail == 1){
            $response = array('message'=> 'successfully Inquiry sent','code'=> 200);
            echo json_encode($response);  
    	}
    	
    	$this->load->library('email');
    
    	//SMTP & mail configuration
    	$config = array(
    	    'protocol'  => 'smtp',
    	    'smtp_host' => 'ssl://smtp.googlemail.com',
    	    'smtp_port' => 465,
    	    'smtp_user' => 'sumit.surbhiinfotech@gmail.com',
    	    'smtp_pass' => 'Sumit@2022',
    	    'mailtype'  => 'html',
    	    'charset'   => 'utf-8'
    	);
    	$this->email->initialize($config);
    	$this->email->set_mailtype("html");
    	$this->email->set_newline("\r\n");
    
    	//Email content
    	$htmlContent = '<h1>Inquiry</h1>';
    	$htmlContent .= '<p>User ID : '. $user_id.'</p>';
    	$htmlContent .= '<p>Email : '. $email.'</p>';
    	$htmlContent .= '<p>Content : '. $content.'</p>';
    	
    
    	$this->email->to('sumit.surbhiinfotech@gmail.com','Contact Us');
    	$this->email->from($from);
    	$this->email->subject($subject);
    	$this->email->message($htmlContent);
    
    	//Send email
    	$this->email->send();
     	if($this->email->send()){
     		echo "mail sent";
     	}else{
     		//echo $this->email->print_debugger();
     	}
    }
}
?>
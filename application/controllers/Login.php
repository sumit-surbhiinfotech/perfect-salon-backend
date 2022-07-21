<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
class Login extends CI_Controller {
	public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('commn');
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
		$login_exist =  $Commn->get_row_data('users',array('phone'=> $phone, 'role' => 1));
		if(isset($login_exist)){
		    $login_exist->image = base_url().'assets/profile_pic/users/'.$login_exist->image;
		}
		if(isset($login_exist)){
			if($login_exist->password != md5($password)){
				$response = array('message'=> 'password is wrong','code'=> 400);
			}else{
				$response = array('message'=> 'successfully login','code'=> 200,'user' => $login_exist);
			}
		}else{
			$response = array('message'=> 'user is not fond','code'=> 404);
		}
		echo json_encode($response);
	}

	public function register(){
		header('Content-type: application/json');
		$role =  $this->input->post('role');
		$username = $this->input->post('username');
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$phone = $this->input->post('phone');
		$gender = $this->input->post('gender');

		$register_status =0;
		if($role == ''){
			$response = array('message'=> 'Role field is required','code'=> 400);
			echo json_encode($response);
			return false;
		}
		if($username == ''){
			$response = array('message'=> 'Username field is required','code'=> 400);
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
		if($phone == ''){
			$response = array('message'=> 'Phone field is required','code'=> 400);
			echo json_encode($response);
			return false;
		}
		if($gender == ''){
			$response = array('message'=> 'Gender field is required','code'=> 400);
			echo json_encode($response);
			return false;
		}

		  $Commn = new Commn();
		  $role_res =  $Commn->get_row('role',array('id'=> $role));
		  if($role_res == 0){
		  	$response = array('message'=> 'role not found','code'=> 404);
			echo json_encode($response);
			return false;
		  }
		  $email_exist =  $Commn->get_row('users',array('phone'=> $phone));
		  if($email_exist == 1){
		  	$response = array('message'=> 'already resgister phone','code'=> 400);
			echo json_encode($response);
			return false;
		  }
// 		  $username_exist =  $Commn->get_row('users',array('username'=> $username));
// 		  if($username_exist == 1){
// 		  	$response = array('response'=> 'already resgister username','code'=> 400);
// 			echo json_encode($response);
// 			return false;
// 		  }else{
// 		  	$register_status = 1;
// 		  }
        $register_status = 1;
		  if($register_status == 1){
		  	 $user = array(
		  	 	'email' => $email,
		  	 	'name' => $username,
		  	 	'password' => md5($password),
		  	 	'role' => $role,
		  	 	'phone' => $phone,
		  	 	'gender' => $gender,
		  	 );
		  	$res =  $Commn->insert_data('users', $user);
		  	if($res == 1){
		  		$response = array('message'=> 'successfully Register','code'=> 200);
		  		echo json_encode($response);
		  	}
		  }
		
	}

	public function forgot_password(){
		$email = $this->input->post('email');
		if($email == ''){
			$response = array('message'=> 'Email field is required','code'=> 400);
			echo json_encode($response);
			return false;
		}
		$Commn = new Commn();
		$email_res =  $Commn->get_row('users',array('email'=> $email));
		
// 		echo "<pre>";print_r($email_res);die;
		$email_data =  $Commn->get_row_data('users',array('email'=> $email));

		//$token = base64_encode($email_data->email);
		
		if($email_res == 0){
		  	$response = array('response'=> 'email is wrong','code'=> 404);
			echo json_encode($response);
			return false;
		}
		if($email_res == 1){
		    $digits = 4;
            $otp = rand(pow(10, $digits-1), pow(10, $digits)-1);
            $update_opt =  $Commn->update_data('users',array('otp' => $otp),array('id' => $email_data->id));
             $this->load->library('phpmailer_lib');
            $mail = $this->phpmailer_lib->load();
            
            $mail->isSMTP();
            // $mail->SMTPDebug = 2;
            $mail->charSet = "iso-8859-1";
            // $mail->Host     = 'smtp.gmail.com';
            $mail->Host     = 'smtp.gmail.com';
            
            $mail->SMTPAuth = true;
            $mail->Username = 'perfectsalon22@gmail.com';
            $mail->Password =  'wcuhrpekjnqpzzlb';
            $mail->Priority = 1;
            $mail->SMTPSecure = 'tls';
            $mail->Port     = 587;
            $mail->SMTPAutoTLS = false;
            // 
            // $mail->AuthType = 'LOGIN';
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            // $mail->ConfirmReadingTo = "sumitkachariya03@gmail.com";
            // $mail->AddCustomHeader( "Disposition-Notification-To:<sumitkachariya03@gmail.com>");
            // $mail->SingleTo = true; 
            // $mail->SMTPKeepAlive = true;
            
            $mail->AddReplyTo('perfectsalon22@gmail.com', 'perfectsalon22@gmail.com');
            $mail->Subject = "Forgot Password";
            $mail->setFrom('perfectsalon22@gmail.com', 'perfectsalon');
            $mail->Sender = 'perfectsalon22@gmail.com';
            $mail->addAddress($email,$email);
            $mail->isHTML(true);
            
            // Email body content
            $mailContent = "<h1>Forgot Password</h1>";
            $mailContent .= "<p>Your OTP is : '. $otp .'</p>";
            
            // $mail->Body = $mailContent;
            $mail->MsgHTML($mailContent);
           
            
            if(!$mail->send()){
                    
                $response = array('message'=> 'somthing wrong','code'=> 400);
                    echo json_encode($response);
                echo "Mailer Error: " . $mail->ErrorInfo;
            }else{
                $response = array('message'=> 'successfully sent otp','code'=> 200,'user_id' => $email_data->id);
                echo json_encode($response);
            }
//             $to = $email;
//             $subject = "Forgot Password";
//             $htmlContent = '<h1>Forgot Password</h1>';
// 			$htmlContent .= '<p>Your OTP is : '. $otp .'</p>';
//             $headers = "From: perfectsalon22@gmail.com" . "\r\n" .
//             "CC: perfectsalon22@gmail.com";
            
//             if(mail($to,$subject,$htmlContent,$headers)){
//                 $response = array('message'=> 'successfully sent otp','code'=> 200,'user_id' => $email_data->id);
//                     echo json_encode($response);
//             }else{
//                 $response = array('message'=> 'somthing wrong','code'=> 400);
//                     echo json_encode($response);
//             }
// 			$to = $email;
// 			$this->load->library('email');

// 			//SMTP & mail configuration
// 			$config = array(
// 			    'protocol'  => 'smtp',
// 			    'smtp_host' => 'ssl://smtp.googlemail.com',
// 			    'smtp_port' => 465,
// 			    'smtp_user' => 'sumitkachariya03@gmail.com',
// 			    'smtp_pass' => '@Sumit049',
// 			    'mailtype'  => 'html',
// 			    'charset'   => 'utf-8'
// 			);
// 			$this->email->initialize($config);
// 			$this->email->set_mailtype("html");
// 			$this->email->set_newline("\r\n");

// 			//Email content
// 			echo "token :" . $token;
// 			$htmlContent = '<h1>Forgot Password</h1>';
// 			$htmlContent .= '<p>You can click below button and change your password</p>';
// 			$htmlContent .= '<p><a href="'.base_url().'change_password?token='.$token.'">Change Password</a></p>';

// 			$this->email->to($to);
// 			$this->email->from('sumit.surbhiinfotech@gmail.com','MyWebsite');
// 			$this->email->subject('Forgot Password');
// 			$this->email->message($htmlContent);

// 			//Send email
// 			$this->email->send();
// 		 	if($this->email->send()){
// 		 		echo "mail sent";
// 		 	}else{
// 		 		echo $this->email->print_debugger();
// 		 	}
		}
	}
    
    public function otp_verification(){
        $Commn = new Commn();
        $otp = $this->input->post('otp');
        $user_id = $this->input->post('user_id');
        if($otp == ''){
			$response = array('message'=> 'OTP field is required','code'=> 400);
			echo json_encode($response);
			return false;
		}
		if($user_id == ''){
			$response = array('message'=> 'userid field is required','code'=> 400);
			echo json_encode($response);
			return false;
		}
        $email_data =  $Commn->get_row_data('users',array('id'=> $user_id));
        
        if(isset($email_data) && !empty($email_data)){
            if($email_data->otp != '' && $email_data->otp != null){
                if($email_data->otp == $otp){
                    $update_opt =  $Commn->update_data('users',array('otp' => null),array('id' => $email_data->id));
                   $response = array('message'=> 'successfully match OTP','code'=> 200,'user_id' => $email_data->id);
                    echo json_encode($response); 
                }else{
                   $response = array('message'=> 'OTP is wrong','code'=> 400);
                    echo json_encode($response); 
                }
            }else{
                $response = array('message'=> 'please resend OTP','code'=> 400);
                    echo json_encode($response);
            }
        }else{
            $response = array('message'=> 'somthing wrong','code'=> 400);
            echo json_encode($response);
        }
    }
	public function change_password(){
		$Commn = new Commn();
		$user_id = $this->input->post('user_id');
		$new_password = $this->input->post('new_password');
		$confirm_password = $this->input->post('confirm_password');
		if($user_id == ''){
			$response = array('message'=> 'user id field is required','code'=> 400);
			echo json_encode($response);
			return false;
		}
		if($new_password == ''){
			$response = array('message'=> 'New Password field is required','code'=> 400);
			echo json_encode($response);
			return false;
		}
		if($confirm_password == ''){
			$response = array('message'=> 'Confirm Password field is required','code'=> 400);
			echo json_encode($response);
			return false;
		}
		if($new_password != $confirm_password){
			$response = array('message'=> 'Password and Confirm Password not match','code'=> 400);
			echo json_encode($response);
			return false;
		}
		$update_password =  $Commn->update_data('users',array('password' => md5($new_password)),array('id' => $user_id));
		
		if($update_password == 1){
		   $response = array('message'=> 'successfully Update password','code'=> 200);
           echo json_encode($response);  
		}else{
		   $response = array('message'=> 'somthing wrong','code'=> 400);
           echo json_encode($response);   
		}
	}
}
?>
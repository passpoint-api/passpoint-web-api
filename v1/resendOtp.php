<?php


include('middleware/cors.php');
include('database/connection.php');
include('database/queries.php');
 
 

$json = file_get_contents('php://input');
$data = json_decode($json);





if(  !isset($data->email) ||  empty($data->email)   ){
	
	 header( 'Content-Type: application/json; charset=utf-8');
	 header('HTTP/1.0  400 Bad Request');
	 
	 $set=array('responseStatus'=>"40", 'message' => "Please supply the required fields",'status'=>0);
	 
	 $msg = json_encode($set);
	 echo $msg;
	 
}else{
	
	//retive all the entry from front-end


	
       
      
        $email = mysqli_real_escape_string($mysqli, $data->email);
        

	
  	  
		$exist = 2;

		  //if num of rows is 0 user not found
		if ( CheckUserEmailQuery($mysqli, $email, $exist)){

			$result = LoginUser($mysqli, $email);

			$row =  $result['user_data'];


			//generate and send OTP

			$otp = mt_rand(111111,999999);
			$firstName =$row['firstname'];
					
			
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://api.postmarkapp.com/email/withTemplate',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS =>'{
				"From": "contact@mypasspoint.com",
				"To": "'.$email.'",
				"TemplateId": 30373432,
				"TemplateModel": {
					"firstName": "'.$firstName.'",
					"otp":"'.$otp.'"
				}
			}',
		CURLOPT_HTTPHEADER => array(
			'X-Postmark-Server-Token: '.$_SERVER['X_Postmark_Server_Token'],
			'Content-Type: application/json'
		),
		));

		$response = curl_exec($curl);

		curl_close($curl);


		//update otp field.



				
			if(ForgotPasswordOtpQuery($mysqli, $otp, $email)){

				header( 'Content-Type: application/json; charset=utf-8');
					header('HTTP/1.0 200 Success');
					$set=array('responseStatus'=>"00", 'message' => "OTP Resent Successfully to Email Address. Please Retry New OTP.", "email"=>$email, 'code'=>200,'status'=>1);
		 
					$msg = json_encode($set);
					echo $msg;
					exit;



			}else{



				header( 'Content-Type: application/json; charset=utf-8');
					header('HTTP/1.0  400 Bad Request');
					$set=array('responseStatus'=>"40", 'message' => "Error Provisioning OTp for Account.", "email"=>$email, 'code'=>400,'status'=>1);
		 
					$msg = json_encode($set);
					echo $msg;
					exit;

			}
	


			
		}else{
				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 400 Bad Request');
	 
				$set=array('responseStatus'=>"40", 'message' => "User Email Cannot be found.", 'code'=>400, 'status'=>0);
		
				$msg = json_encode($set);
				echo $msg;
				exit;
			
		}


		  
}










?>
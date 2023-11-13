<?php

//  error_reporting(E_ALL);
//  ini_set('display_errors', 'On');

include('middleware/cors.php');
include('database/connection.php');
include('database/queries.php');
 



$json = file_get_contents('php://input');
$data = json_decode($json);




if( !isset($data->email) || empty($data->firstName) || empty($data->lastName) || empty($data->phone) || empty($data->password)  ){
	
	 header( 'Content-Type: application/json; charset=utf-8');
	 header('HTTP/1.0 400 Bad Request');
	 
	 $set=array('responseStatus'=>"40", 'message'=>"Please supply the required fields", 'status'=>0);
	 
	 $msg = json_encode($set);
	 echo $msg;
	 
}else{
	
	//retive all the entry from front-end


       
        $email = mysqli_real_escape_string($mysqli, $data->email);


        $firstName = mysqli_real_escape_string($mysqli, $data->firstName);
        $lastName = mysqli_real_escape_string($mysqli, $data->lastName);

		$password = mysqli_real_escape_string($mysqli, $data->password);

		$phone = mysqli_real_escape_string($mysqli, $data->phone);
	
	
		

		function validatePassword($password) {
			$uppercase = preg_match('@[A-Z]@', $password);
			$number    = preg_match('@[0-9]@', $password);
			$length    = strlen($password) >= 8;
		
			if (!$uppercase || !$number || !$length) {
				return false;
			}
		
			return true;
		}

		
		function isValidEmail($email)
		{
			// Use filter_var with FILTER_VALIDATE_EMAIL
			return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
		}


		function isPhoneNumberNumeric($phoneNumber) {
			// Remove any non-numeric characters (e.g., spaces, dashes, parentheses)
			$numericPhoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
		
			// Check if the resulting string is numeric and has a length between 9 and 13 characters (inclusive)
			return is_numeric($numericPhoneNumber) && strlen($numericPhoneNumber) >= 9 && strlen($numericPhoneNumber) <= 13;
		}
	



	  
		$exist = 1;

		  //if num of rows is 0 user not found
		if ( CheckUserEmailQuery($mysqli, $email, $exist)){


			if ( isValidEmail($email)){

				
				//check that phone number is number
				if(isPhoneNumberNumeric($phone)){


				if ( validatePassword($password)){

	
    
		
				$otp = mt_rand(111111,999999);


				$hashpassword = password_hash($password, PASSWORD_DEFAULT);

				$reference ="";
				$userType ="1"; // individual as user type
				$regStage = "1";// step one




				if(CreateIndividualPersonalInfoQuery($mysqli, $firstName, $lastName,  $phone, $email, $hashpassword,  $otp, $regStage, $userType)){


					
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
						"TemplateId": 30364123,
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






				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 200 Success');
				$set=array('responseStatus'=>"00", 'message' => "Personal Information Updated and Verification Code Sent to  ".$email.", Please proceed to fill your business information.",'code'=>200, "email"=>$email, 'status'=>1);
	 
				$msg = json_encode($set);
				echo $msg;
				exit;



				}else{


				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 400 Bad Request');
				$set=array('responseStatus'=>"40", 'message' => "Error Updating Personal Information ", 'code'=>200, "email"=>$email, 'status'=>1);
	 
				$msg = json_encode($set);
				echo $msg;
				exit;




				}





		}else{


			header( 'Content-Type: application/json; charset=utf-8');
			header('HTTP/1.0 400 Bad Request');
			$set=array('responseStatus'=>"40", 'message' =>"Password Strength is low, Please ensure password contains at least one number, one uppercase letter, and is a minimum of 8 characters",'code'=>400,'status'=>0);
 
			$msg = json_encode($set);
			echo $msg;
			exit;



		}




	}else{
		header( 'Content-Type: application/json; charset=utf-8');
		header('HTTP/1.0 400 Bad Request');

		$set=array('responseStatus'=>"40", 'message' => "Phone Number is not valid",'code'=>400,'status'=>0);

		$msg = json_encode($set);
		echo $msg;
		exit;
	
}






			}else{
				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 400 Bad Request');
		
				$set=array('message' => "Email Provided is not a valid email.", 'code'=>400,'responseStatus'=>"50");
		
				$msg = json_encode($set);
				echo $msg;
				exit;
			}

		


 
	
			
		}else{
				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 400 Bad Request');
	 
				$set=array('responseStatus'=>"40", 'message' => "User Email has Already been Onboarded",'code'=>400,'status'=>0);
		
				$msg = json_encode($set);
				echo $msg;
				exit;
			
		}


		  
}











?>
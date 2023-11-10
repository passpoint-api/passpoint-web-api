<?php

include('middleware/cors.php');
include('database/connection.php');
include('database/queries.php');
 



$json = file_get_contents('php://input');
$data = json_decode($json);




if( !isset($data->email) || empty($data->firstName) || empty($data->lastName) || empty($data->phone) ){
	
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

		$phone = mysqli_real_escape_string($mysqli, $data->phone);






		function isPhoneNumberNumeric($phoneNumber) {
			// Remove any non-numeric characters (e.g., spaces, dashes, parentheses)
			$numericPhoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
		
			// Check if the resulting string is numeric and has a length between 9 and 13 characters (inclusive)
			return is_numeric($numericPhoneNumber) && strlen($numericPhoneNumber) >= 9 && strlen($numericPhoneNumber) <= 13;
		}
	
	
		


		$exist = 2;

		  //if num of rows is 0 user not found
		if ( CheckUserEmailQuery($mysqli, $email, $exist)){


			//check that phone number is number
			if(isPhoneNumberNumeric($phone)){


	

    
				$regStage = "2"; // step two
				$otp = mt_rand(111111,999999);



				if(CreateUserPersonalInfoQuery($mysqli, $firstName, $lastName, $phone, $regStage, $otp, $email) ){





						
			
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
				$set=array('responseStatus'=>"00", 'message' => "Personal Information Updated and Verification Code Sent to  ".$email.", Please proceed to fill your business address.",'code'=>200, "email"=>$email, 'status'=>1);
	 
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
	 
				$set=array('responseStatus'=>"40", 'message' => "Phone Number is not valid",'code'=>400,'status'=>0);
		
				$msg = json_encode($set);
				echo $msg;
				exit;
			
		}




 
	
			
		}else{
				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 400 Bad Request');
	 
				$set=array('responseStatus'=>"40", 'message' => "User Email Does Not Exist, Please Re-Confirm",'code'=>400,'status'=>0);
		
				$msg = json_encode($set);
				echo $msg;
				exit;
			
		}


		  
}











?>
<?php


include('middleware/cors.php');
include('database/connection.php');
include('database/queries.php');
 
 

$json = file_get_contents('php://input');
$data = json_decode($json);





if(  !isset($data->otp) || !isset($data->email) ||  empty($data->otp)   ){
	
	 header( 'Content-Type: application/json; charset=utf-8');
	 header('HTTP/1.0 400 Bad Request');
	 
	 $set=array('responseStatus'=>"40", 'message' => "Please supply the required fields",'status'=>0);
	 
	 $msg = json_encode($set);
	 echo $msg;
	 
}else{
	
	//retive all the entry from front-end


	
       
        $otp = mysqli_real_escape_string($mysqli, $data->otp);
        $email = mysqli_real_escape_string($mysqli, $data->email);
		$otpType = mysqli_real_escape_string($mysqli, $data->otpType);
        


		if($otpType =="accountVerification" or $otpType =="passwordReset" ){

			if(is_numeric($otp)){

			

	
  	  
		$exist = 2;

		  //if num of rows is 0 user not found
		if (CheckUserEmailQuery($mysqli, $email, $exist)){



			if ( ValidateOtpQuery($mysqli, $email, $otp)){




				function generateApiKey()
				{
					// Define character sets for each section of the API key
					$charset1 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
					$charset2 = '0123456789';
					$charset3 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
				
					// Initialize the API key sections
					$section1 = '';
					$section2 = '';
					$section3 = '';
				
					// Generate the first section (PPG22)
					for ($i = 0; $i < 5; $i++) {
						$section1 .= $charset1[rand(0, strlen($charset1) - 1)];
					}
				
					// Generate the second section (9XY77)
					for ($i = 0; $i < 5; $i++) {
						$section2 .= $charset2[rand(0, strlen($charset2) - 1)];
					}
				
					// Generate the third section (KPK6)
					for ($i = 0; $i < 4; $i++) {
						$section3 .= $charset3[rand(0, strlen($charset3) - 1)];
					}
				
					// Concatenate the sections to form the API key
					$apiKey = $section1 . '-' . $section2 . '-' . $section3;
				
					return $apiKey;
				}


				$apiKey = generateApiKey();
				//creat User


				$result = LoginUser($mysqli, $email);
			
	
				$row =  $result['user_data'];

				$curl = curl_init();

				curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://client-sandbox.mypasspoint.com/passpoint-usr/v1/merchant-app/create-merchant',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS =>'{
					"name":"'.$row['bussinesName'].'",
					"email":"'.$row['email'].'",
					"address":"'.$row['address'].'",
					"countryCode":"NG",
					"firstName":"'.$row['firstname'].'",
					"lastName":"'.$row['lastname'].'",
					"phoneNumber":"'.$row['phone'].'",
					"password":"'.$apiKey.'"
				}',
				CURLOPT_HTTPHEADER => array(
					'Content-Type: application/json'
				),
				));

				$response = curl_exec($curl);

				curl_close($curl);

				$response = json_decode($response, true);	

				$merchantId =$response['data']['merchantId'];
	



				if(VerifyOtpUpdateStatusQuery($mysqli, $merchantId, $apiKey, $email, $otpType)){


					header( 'Content-Type: application/json; charset=utf-8');
					header('HTTP/1.0 200 Success');
					$set=array('responseStatus'=>"00", 'message' => "User Account Verified Successfully. Please Proceed to login", "email"=>$email, 'code'=>200,'status'=>1);
		 
					$msg = json_encode($set);
					echo $msg;
					exit;

				}else{


					header( 'Content-Type: application/json; charset=utf-8');
					header('HTTP/1.0 400 Bad Request');
					$set=array('responseStatus'=>"40", 'message' => "Error Verifing User Account", "email"=>$email, 'code'=>200,'status'=>1);
		 
					$msg = json_encode($set);
					echo $msg;
					exit;


				}





			
		}else{
			header( 'Content-Type: application/json; charset=utf-8');
			header('HTTP/1.0 400 Bad Request');
 
			$set=array('responseStatus'=>"40", 'message' => "Invalid Otp Supplied", 'code'=>400, 'status'=>0);
	
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




	}else{
		header( 'Content-Type: application/json; charset=utf-8');
		header('HTTP/1.0 400 Bad Request');

		$set=array('responseStatus'=>"40", 'message' => "OTP is not in right formart.", 'code'=>400, 'status'=>0);

		$msg = json_encode($set);
		echo $msg;
		exit;
	
}





	}else{
		header( 'Content-Type: application/json; charset=utf-8');
		header('HTTP/1.0 400 Bad Request');

		$set=array('responseStatus'=>"40", 'message' => "Invalid OTP Type.", 'code'=>400, 'status'=>0);

		$msg = json_encode($set);
		echo $msg;
		exit;
	
}


		  
}










?>
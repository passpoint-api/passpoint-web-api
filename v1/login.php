
<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 'On');


include('middleware/cors.php');
include('database/connection.php');
include('database/queries.php');
include('middleware/constants.php');


require_once 'firebase-php-jwt/src/BeforeValidException.php';
require_once 'firebase-php-jwt/src/ExpiredException.php';
require_once 'firebase-php-jwt/src/SignatureInvalidException.php';
require_once 'firebase-php-jwt/src/JWT.php';


use \Firebase\JWT\JWT;

	//retive all the entry from front-end

	



	

	$json = file_get_contents('php://input');
	$data = json_decode($json);



if( !isset( $data->password) || !isset($data->email) ){
	
	 header( 'Content-Type: application/json; charset=utf-8');
	 header('HTTP/1.0  400 Bad Request');
	 
	 $set=array('message' => "Please supply the required fields", 'responseStatus'=>"40");
	 
	 $msg = json_encode($set);
	 echo $msg;

	 
}else{

	//$crypt = new PasswordLib\PasswordLib;
	//retive all the entry from front-end

	$password = $data->password;
	$email = $data->email;

	$key = $_SERVER['JWT_KEY'];



	function isValidEmail($email)
	{
		// Use filter_var with FILTER_VALIDATE_EMAIL
		return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
	}

	function validatePassword($password) {
		$uppercase = preg_match('@[A-Z]@', $password);
		$number    = preg_match('@[0-9]@', $password);
		$length    = strlen($password) >= 8;
	
		if (!$uppercase || !$number || !$length) {
			return false;
		}
	
		return true;
	}

	
	if ( isValidEmail($email)){

		if ( validatePassword($password)){

	//since password is encryted want to check if user exist first
		

	$exist = 2;

	$result = LoginUser($mysqli, $email);

	//var_dump($result);
	//if num of rows is 0 user not found
  if ( $result['user_exists'] ){

			
	
			$row =  $result['user_data'];
			//compare use entry with encryted password
			//password_verify($password, $row['password']) 
			// $crypt->verifyPasswordHash($password, $row['password'])

		if($row['userType'] ==INDIVIDUAL_USER or $row['userType'] ==CO_OPERATE_USER){



			if(password_verify($password, $row['password'])){

				

						//log the users info & status
						if($row['status'] ==1){ $is_active = true;}else{ $is_active = false; }


						//public profile account
						$profileid = getPublicProfileId($mysqli , $row['id']);

						if(is_numeric($profileid)){ $hasPublicProfile = true; }else{ $hasPublicProfile = false; }

						//kyc status

					// 	$kyc = getKycDetails($mysqli, $id);


					// if($result['p_exists']){ 
						
					
					//1=approved, 2=Rejected, 0=pending, 
					if($row['kycStatus'] ==1){ $kycStatus = "Approved";}elseif($row['kycStatus'] ==2){ $kycStatus = "Rejected"; }elseif($row['kycStatus'] ==0 and !getKycDetails($mysqli, $row['id'])['p_exists']){ $kycStatus = "Pending"; }elseif($row['kycStatus'] ==0 and getKycDetails($mysqli, $row['id'])['p_exists']){ $kycStatus = "inReview"; }
					
					// }else{ $kycStatus = false; }	

						
						$title = "Recent sign in to your account";
						$userid = $row['id'];
						createActivity($mysqli, $userid, $title);


						if($row['2fa'] ==1){ 
							

							function encryptData($data, $key, $method = 'aes-256-cbc') {
								$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
								$encrypted = openssl_encrypt($data, $method, $key, 0, $iv);
								return base64_encode($iv . $encrypted);
							}
							
							
							$is_2fa = true;

							$encrptyData = $row['email']."::".$row['id'];

							header( 'Content-Type: application/json; charset=utf-8');
							header('HTTP/1.0 200 Success');

						
							$set=array(

								'responseStatus'=>"00",

								'mesg'=>"Proceed to validate otp.",

								'token' =>encryptData($encrptyData, $key),

							);
				 
							$msg = json_encode($set);
							echo $msg;
							exit;
						
						}else{ 
								
								
								$is_2fa = false; 
							
							
						

					
						
							$payload=[
								'id' => $row['id'], 
								'firstName'=>$row['firstname'], 
								'lastName'=>$row['lastname'], 
								'email'=> $row['email'], 
								'phoneNumber'=>$row['phone'], 
								'businessName'=>$row['bussinesName'], 
								'rcNumber'=>$row['rcNumber'], 

								'businessType'=>$row['businessType'], 
								'businessIndustry'=>$row['businessIndustry'], 
								'address'=>$row['address'], 

								'merchantId'=>$row['merchant_id'], 
								'apiKey'=>$row['api_key'], 

								'dateOnboarded'=>$row['date_created'], 
							
							
								'userType' => $row['userType'],
								'regStage' => $row['regStage'],
								'hasPublicProfile' => $hasPublicProfile,
							
								'isActive' => $is_active, 

								'kycStatus' => $kycStatus,

								'is2faEnable' => $is_2fa,
							

								"refresh_token"=> null,
								"refresh_token_expires"=> null,
								'created_at'=>  date('Y-m-d')."T".date('H:i.s')."000z",
								'modified_at'=> date('Y-m-d')."T".date('H:i.s')."000z",


								'iat'=> time(),
								'exp'=> time()+ 60 * 60 * 24,
							];
							

					
							header( 'Content-Type: application/json; charset=utf-8');
							header('HTTP/1.0 200 Success');

						
							$jwt = JWT::encode($payload, $key, 'HS256');

						
		 
							$set=array(

								'responseStatus'=>"00",

								'data'=>[
									
									'firstName'=>$row['firstname'], 
									'lastName'=>$row['lastname'], 
									'email'=> $row['email'], 
									'phoneNumber'=>$row['phone'], 
									'businessName'=>$row['bussinesName'], 
									'rcNumber'=>$row['rcNumber'], 
	
									'businessType'=>$row['businessType'], 
									'businessIndustry'=>$row['businessIndustry'], 
									'state'=>$row['state'], 
									'lga'=>$row['lga'], 
									'country'=>$row['country'], 
									
									'address'=>$row['address'], 

									'merchantId'=>$row['merchant_id'], 
	
									'dateOnboarded'=>$row['date_created'], 
								
									'userType' => $row['userType'],

									'regStage' => $row['regStage'],

									'kycStatus' => $kycStatus,

									'hasPublicProfile' => $hasPublicProfile,
								
									'isActive' => $is_active, 
									
									'is2faEnable' => $is_2fa,
								
									'profileImg'=>null, 
								
						
								
								],

								'token' =>$jwt

							);
				 
							$msg = json_encode($set);
							echo $msg;
							exit;


						}







			}else{

				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 400 Bad Request');
				$set=array('message' => "Wrong Password. Please Check your Password", 'code'=>400, 'responseStatus'=>"40");
	 
				$msg = json_encode($set);
				echo $msg;
				exit;
			}



		}else{
			// throw error, since user is not an admin

			header( 'Content-Type: application/json; charset=utf-8');
			header('HTTP/1.0 400 Bad Request');
 
			$set=array('message' => "Permission Denied. You do not have access", 'code'=>400,'responseStatus'=>"50");
	
			$msg = json_encode($set);
			echo $msg;



		}
			
					
	

			
		}else{
				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 400 Bad Request');
	 
				$set=array('message' => "Invaild Login Credentials", 'code'=>400,'responseStatus'=>"50");
		
				$msg = json_encode($set);
				echo $msg;
			
		}


	}else{
		header( 'Content-Type: application/json; charset=utf-8');
		header('HTTP/1.0 400 Bad Request');

		$set=array('message' => "Invaild Login Credentials", 'code'=>400,'responseStatus'=>"50");

		$msg = json_encode($set);
		echo $msg;
		exit;
	
}





	}else{
		header( 'Content-Type: application/json; charset=utf-8');
		header('HTTP/1.0 400 Bad Request');

		//Email Provided is not a valid email.

		$set=array('message' => "Invaild Login Credentials", 'code'=>400,'responseStatus'=>"50");

		$msg = json_encode($set);
		echo $msg;
		exit;
}








		  
}


mysqli_close($mysqli);



?>
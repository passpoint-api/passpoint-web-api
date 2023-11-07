
<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');


include('middleware/cors.php');
include('database/connection.php');
include('database/queries.php');
 


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


			if(password_verify($password, $row['password'])){

				

						//log the users info & status
						if($row['status'] ==1){ $is_active = true;}else{ $is_active = false; }


						//public profile account
						$profileid = getPublicProfileId($mysqli , $row['id']);

						if(is_numeric($profileid)){ $hasPublicProfile = true; }else{ $hasPublicProfile = false; }

						//kyc status

						$kyc = getKycDetails($mysqli, $id);


					if($result['p_exists']){ 
						
						$kycStatus = true; 
					
					}else{ $kycStatus = false; }	

						
						$title = "Recent sign in to your account";
						$userid = $row['id'];
						createActivity($mysqli, $userid, $title);

					
						
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

								'kycStatus' => $row['kycStatus'],
							

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

									'kycStatus' => $row['kycStatus'],

									'hasPublicProfile' => $hasPublicProfile,
								
									'isActive' => $is_active, 
								
									'profileImg'=>null, 
								
						
								
								],

								'token' =>$jwt

							);
				 
							$msg = json_encode($set);
							echo $msg;
							exit;


			}else{

				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 400 Bad Request');
				$set=array('message' => "Wrong Password. Please Check your Password", 'code'=>400, 'responseStatus'=>"40");
	 
				$msg = json_encode($set);
				echo $msg;
				exit;
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
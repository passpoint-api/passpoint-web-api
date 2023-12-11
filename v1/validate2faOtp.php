<?php


include('middleware/cors.php');
include('database/connection.php');
include('database/queries.php');



 

		//retive all the entry from front-end

		require_once 'firebase-php-jwt/src/BeforeValidException.php';
		require_once 'firebase-php-jwt/src/ExpiredException.php';
		require_once 'firebase-php-jwt/src/SignatureInvalidException.php';
		require_once 'firebase-php-jwt/src/JWT.php';
		
		
		use \Firebase\JWT\JWT;



	   $json = file_get_contents('php://input');
	   $data = json_decode($json);



	
	

	   if( !isset( $data->token) || !isset($data->code) || empty($data->code) ){
	
		header( 'Content-Type: application/json; charset=utf-8');
		header('HTTP/1.0  400 Bad Request');
		
		$set=array('message' => "Please supply the required fields", 'responseStatus'=>"40");
		
		$msg = json_encode($set);
		echo $msg;
   
		
   }else{
		   
	$token =$data->token;
     
		try{

			function decryptData($encryptedData, $key, $method = 'aes-256-cbc') {
				$data = base64_decode($encryptedData);
				$ivLength = openssl_cipher_iv_length($method);
				$iv = substr($data, 0, $ivLength);
				$encrypted = substr($data, $ivLength);
				return openssl_decrypt($encrypted, $method, $key, 0, $iv);
			}

			$key = $_SERVER['JWT_KEY'];
			$newToken = decryptData($token, $key);

			$mainArry = explode("::", $newToken);

			$id = $mainArry[1];


					//now generate 2 factor autherication code for this user

						$row = GetUserDetails($mysqli, $id)['user_data'];

												//check that 2fa is not enabled.
						require_once("google_authenticator/index.php"); 

						$g = new \Google\Authenticator\GoogleAuthenticator();

						$secret=$row['2faSecret'];
						$check_this_code = $data->code;



						if ($g->checkCode($secret, $check_this_code)) {

							//1=approved, 2=Rejected, 0=pending, 
					if($row['kycStatus'] ==1){ $kycStatus = "Approved";}elseif($row['kycStatus'] ==2){ $kycStatus = "Rejected"; }elseif($row['kycStatus'] ==0 and !getKycDetails($mysqli, $row['id'])['p_exists']){ $kycStatus = "Pending"; }elseif($row['kycStatus'] ==0 and getKycDetails($mysqli, $row['id'])['p_exists']){ $kycStatus = "inReview"; }
					
					// }else{ $kycStatus = false; }	


						//log the users info & status
						if($row['status'] ==1){ $is_active = true;}else{ $is_active = false; }


						//public profile account
						$profileid = getPublicProfileId($mysqli , $row['id']);

						if(is_numeric($profileid)){ $hasPublicProfile = true; }else{ $hasPublicProfile = false; }


						
						$title = "Validated 2fa Code";
						$userid = $row['id'];
						createActivity($mysqli, $userid, $title);


						
						$is_2fa = true;

						
						
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

					

					}else{
						//throw error

						header( 'Content-Type: application/json; charset=utf-8');
						header('HTTP/1.0 400 Bad Request');
						$set=array('msg' => 'Invalid Code Supplied. Please Re-Check Code', 'responseStatus'=>"40", 'status'=>0);
			
						$msg = json_encode($set);
						echo $msg;
						exit;


					}

					

		

	}catch (Exception $e) {

		

		header('Content-Type: application/json; charset=utf-8');
		header('HTTP/1.0 400 Bad Request');
		$set = ['message' => "Error Approving Kyc info. ".$e->getMessage(), 'code' => 400, 'responseStatus' => '40', 'status' => 0];
		$msg = json_encode($set);
		echo $msg;
		exit;
	}


		  


   }





?>
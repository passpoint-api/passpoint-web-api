<?php


include('middleware/cors.php');
include('database/connection.php');
include('middleware/auth.php');
include('database/queries.php');



 

		//retive all the entry from front-end





	   $json = file_get_contents('php://input');
	   $data = json_decode($json);



	
	   $id =$USER_DATA['id'];



		   
		   
     
		try{

					//now generate 2 factor autherication code for this user

						$result = GetUserDetails($mysqli, $id)['user_data'];

												//check that 2fa is not enabled.
						require_once("google_authenticator/index.php"); 

						$g = new \Google\Authenticator\GoogleAuthenticator();

						$secret=$result['2faSecret'];
						$check_this_code = $data->code;



						if ($g->checkCode($secret, $check_this_code)) {
						


						
									header( 'Content-Type: application/json; charset=utf-8');
								header('HTTP/1.0 200 Success');
								$set=array('msg' => 'Validation Successfull, Code is Valid', 'responseStatus'=>"00", 'status'=>1);
					
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


		  








?>
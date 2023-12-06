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
						
					require_once("google_authenticator/index.php"); 

					$g = new \Google\Authenticator\GoogleAuthenticator();

					$secret = $g->generateSecret();


						$result = GetUserDetails($mysqli, $id)['user_data'];

						//check that 2fa is not enabled.
						if($result['2fa'] != 1){

						

						if($result['2faSecret'] =="" or empty($result['2faSecret'])){
							//update the user with new secret key

							$email = $result['email'];
							$firstName = $result['firstname'];
	
	
							$two_fa_link = $g->getURL($email, 'mypasspoint.com', $secret);
							
								

								if(Update2faDetails($mysqli, $id, $secret, $two_fa_link )){

									header( 'Content-Type: application/json; charset=utf-8');
								header('HTTP/1.0 200 Success');
								$set=array('msg' => '2fa Details ', 'secret'=>$secret, "2FaQrCodeLink"=>$two_fa_link ,'responseStatus'=>"00", 'status'=>1);
					
								$msg = json_encode($set);
								echo $msg;
								exit;

								}else{

									header( 'Content-Type: application/json; charset=utf-8');
									header('HTTP/1.0 400 Bad Request');
								$set=array('msg' => 'Error getting 2fa details ', 'responseStatus'=>"40", 'status'=>1);
					
								$msg = json_encode($set);
								echo $msg;
								exit;

								}
	
	

						}else{

							//pick from already store DB credentials


							$dBsecret = $result['2faSecret'];
							$dB2faLink = $result['2faLink'];


							header( 'Content-Type: application/json; charset=utf-8');
							header('HTTP/1.0 200 Success');
							$set=array('msg' => '2fa Details ', 'secret'=>$dBsecret, "2FaQrCodeLink"=>$dB2faLink ,'responseStatus'=>"00", 'status'=>1);
				
							$msg = json_encode($set);
							echo $msg;
							exit;


						}
					

					}else{
						//throw error

						header( 'Content-Type: application/json; charset=utf-8');
						header('HTTP/1.0 400 Bad Request');
						$set=array('msg' => 'Please disable 2fa in order to get Details', 'responseStatus'=>"40", 'status'=>0);
			
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
<?php


include('middleware/cors.php');
include('database/connection.php');
include('middleware/auth.php');
include('database/queries.php');



 

		//retive all the entry from front-end





	   $json = file_get_contents('php://input');
	   $data = json_decode($json);




	   if( !isset( $data->otp) || !isset($data->action) || empty($data->otp) ){
	
		header( 'Content-Type: application/json; charset=utf-8');
		header('HTTP/1.0  400 Bad Request');
		
		$set=array('message' => "Please supply the required fields", 'responseStatus'=>"40");
		
		$msg = json_encode($set);
		echo $msg;
   
		
   }else{
	
	   $id =$USER_DATA['id'];

	   //use this to validate the user otp first.
	   $otp = $data->otp;

	   $action = $data->action; //ENABLE or DISABLE




     
		try{



			$result = GetUserDetails($mysqli, $id)['user_data'];

							//check that 2fa is not enabled.
				require_once("google_authenticator/index.php"); 

				$g = new \Google\Authenticator\GoogleAuthenticator();

				$secret=$result['2faSecret'];



		if ($g->checkCode($secret, $otp)) {



			if($action =="ENABLE" ){

				$status = 1;
				if(Update2faStatus($mysqli, $id, $status )){

					header( 'Content-Type: application/json; charset=utf-8');
					header('HTTP/1.0 200 Success');
					$set=array('msg' => '2fa has been enabled ', 'responseStatus'=>"00", 'status'=>1);
		
					$msg = json_encode($set);
					echo $msg;
					exit;

				}





			}elseif($action =="DISABLE"){


				$status = 0;
				if(Update2faStatus($mysqli, $id, $status)){

					header( 'Content-Type: application/json; charset=utf-8');
					header('HTTP/1.0 200 Success');
					$set=array('msg' => '2fa has been disabled ','responseStatus'=>"00", 'status'=>1);
		
					$msg = json_encode($set);
					echo $msg;
					exit;
				}



			}else{

				header('Content-Type: application/json; charset=utf-8');
		header('HTTP/1.0 400 Bad Request');
		$set = ['message' => "Invalid Action Type. Please input a valid action. ", 'code' => 400, 'responseStatus' => '40', 'status' => 0];
		$msg = json_encode($set);
		echo $msg;
		exit;

			}


		}else{


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
		$set = ['message' => "Error Updating 2fa Status. ".$e->getMessage(), 'code' => 400, 'responseStatus' => '40', 'status' => 0];
		$msg = json_encode($set);
		echo $msg;
		exit;
	}


		  
   }







?>
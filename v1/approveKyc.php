<?php


include('middleware/cors.php');
include('database/connection.php');

include('database/queries.php');



 

		//retive all the entry from front-end





	   $json = file_get_contents('php://input');
	   $data = json_decode($json);



		$userId = $data->userId;



     
		try{


			


			if(updateKycStatus($mysqli, $userId)){




				



							header( 'Content-Type: application/json; charset=utf-8');
							header('HTTP/1.0 200 Success');
							$set=array('msg' => 'KYC Details Approved', 'code'=>200, 'responseStatus'=>"00", 'status'=>1);
				
							$msg = json_encode($set);
							echo $msg;
							exit;


			}else{



				//$date = date('d')."-".date('m')."-".date('Y');


				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 400 Bad Request');
				$set = ['message' => "Error Approving Kyc Info. ".$e->getMessage(), 'code' => 400, 'responseStatus' => '40', 'status' => 0];

				$msg = json_encode($set);
				echo $msg;
				exit;




			}

			
			

        	


		

		

	}catch (Exception $e) {

		

		header('Content-Type: application/json; charset=utf-8');
		header('HTTP/1.0 400 Bad Request');
		$set = ['message' => "Error Getting Kyc info. ".$e->getMessage(), 'code' => 400, 'responseStatus' => '40', 'status' => 0];
		$msg = json_encode($set);
		echo $msg;
		exit;
	}


		  








?>
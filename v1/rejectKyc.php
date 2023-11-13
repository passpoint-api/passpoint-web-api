<?php


include('middleware/cors.php');
include('database/connection.php');

include('database/queries.php');



 

		//retive all the entry from front-end





	   $json = file_get_contents('php://input');
	   $data = json_decode($json);



		$userId = $data->userId;



     
		try{


			
            $kycStatus =2;

			if(updateKycStatus($mysqli, $userId, $kycStatus)){


				$result = GetUserDetails($mysqli, $userId)['user_data'];

				$email = $result['email'];
				$firstName = $result['firstname'];




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
						"TemplateId": 33799917,
						"TemplateModel": {
							"firstName": "'.$firstName.'"
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
							$set=array('msg' => 'KYC Details Rejected', 'code'=>200, 'responseStatus'=>"00", 'status'=>1);
				
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
		$set = ['message' => "Error Approving Kyc info. ".$e->getMessage(), 'code' => 400, 'responseStatus' => '40', 'status' => 0];
		$msg = json_encode($set);
		echo $msg;
		exit;
	}


		  








?>
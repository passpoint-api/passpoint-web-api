<?php


include('middleware/cors.php');
include('database/connection.php');

include('database/queries.php');



 

		//retive all the entry from front-end


   


		$jsonObj = array();
     
		try{


			$result = getUnapprovedUsers($mysqli) ;


			if($result['p_exists']){

				$row = $result['p_data'];


				
		


					foreach ($row as $user) {
						
					
						if($user['kycStatus'] ==1){ $kycStatus = true;}else{ $kycStatus = false; }
					

							$info['userId'] = $user['id'];
							$info['firstName'] = $user['firstname'];
							$info['lastName'] = $user['lastname'];

							$info['email'] = $user['email'];
							$info['phoneNumber'] = $user['phone'];

							$info['businessName']=$user['bussinesName'];
							$info['rcNumber']=$user['rcNumber'];

							$info['businessType']=$user['businessType']; 
							$info['businessIndustry']=$user['businessIndustry']; 
							$info['address']=$user['address'];

							$info['merchantId']=$user['merchant_id'];
							$info['apiKey']=$user['api_key'];

							$info['dateOnboarded']=$user['date_created'];


							$info['kycStatus'] = $kycStatus;
							

							// Add the current descList to the desc array
							array_push($jsonObj, $info);
						
						
					
						 
					}


				
			





							header( 'Content-Type: application/json; charset=utf-8');
							header('HTTP/1.0 200 Success');
							$set=array('data' => $jsonObj, 'code'=>200, 'responseStatus'=>"00", 'status'=>1);
				
							$msg = json_encode($set);
							echo $msg;
							exit;


			}else{



				//$date = date('d')."-".date('m')."-".date('Y');


				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 400 Bad Request');
				$set = ['message' => "Error Retriving Users. ".$e->getMessage(), 'code' => 400, 'responseStatus' => '40', 'status' => 0];

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
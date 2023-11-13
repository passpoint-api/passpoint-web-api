<?php


include('middleware/cors.php');
include('database/connection.php');
include('middleware/auth.php');
include('database/queries.php');



 

		//retive all the entry from front-end


       

	   $identity = array();
	   $ownership = array();



		$id =$USER_DATA['id'];



     
		try{


			$result = GetUserDetails($mysqli, $id);



			if($result['user_exists']){

				$row = $result['user_data'];



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
								
									'profileImg'=>null, 
								
						
								
								],


							);
				 
							$msg = json_encode($set);
							echo $msg;
							exit;


			

			}else{



				//$date = date('d')."-".date('m')."-".date('Y');


				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 400 Bad Request');
				$set = ['message' => "Error Getting user Info.", 'code' => 400, 'responseStatus' => '40', 'status' => 0];

				$msg = json_encode($set);
				echo $msg;
				exit;




			}

		
		

		

	}catch (Exception $e) {

		

		header('Content-Type: application/json; charset=utf-8');
		header('HTTP/1.0 400 Bad Request');
		$set = ['message' => "Error Getting User info. ".$e->getMessage(), 'code' => 400, 'responseStatus' => '40', 'status' => 0];
		$msg = json_encode($set);
		echo $msg;
		exit;
	}


		  








?>
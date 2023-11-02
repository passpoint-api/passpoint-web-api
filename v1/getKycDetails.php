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


			$result = getKycDetails($mysqli, $id);


			if($result['p_exists']){

				$row = $result['p_data'];


				//initailize stage to zero
				$kycStage =0;
				$isCompleted = false;




				$info['kycId'] = $row['id'];
				if(empty($row['websiteURL'])){
					$info['kycType'] = "individual";
					//$info['businessInfo'] =["websiteURL"=>$row['websiteURL'] ]; 
				}else{
					$info['kycType'] = "cooperate";
					$info['businessInfo'] =["websiteURL"=>$row['websiteURL'] ]; 
				}


			
		



					$descData = getKycDocs($mysqli, $row['id'])['p_data'];

					foreach ($descData as $descItem) {
						$identityList = []; // Initialize the descList array for each iteration
						$OwnerList = []; // Initialize the descList array for each iteration

						if($descItem['docType'] =="IDENTITY"){
							$identityList['id'] = $descItem['id'];
							$identityList['docName'] = $descItem['docName'];
							$identityList['docFile'] = $descItem['docFile'];

							$identity[] = $identityList;
						}elseif($descItem['docType'] =="OWNERSHIP"){

							$ownerList['id'] = $descItem['id'];
							$ownerList['docName'] = $descItem['docName'];
							$ownerList['docFile'] = $descItem['docFile'];

							// Add the current descList to the desc array
						$ownership[] =$ownerList;
						}
						
					
						 
					}


				
			


				if($info['kycType'] =="individual"){

					$info['proofIdentity'] = ['identityDocumentType' =>$row['identityDocumentType'],
												'identityDocumentNumber' =>$row['identityDocumentFile'],
											];

				}elseif($info['kycType'] =="cooperate"){

					$info['proofIdentity'] = $identity;

				}
				



				$info['proofAddress'] = ['addressDocumentType' =>$row['addressDocumentType'],
										'addressDocumentFile' =>$row['addressDocumentFile']
									];


				if($info['kycType'] =="individual"){


				}elseif($info['kycType'] =="cooperate"){

					$info['proofOwnership'] = $ownership;

				}

				



				//if identity is uploaded increment by 1
				if(count($identity) >0){
					
					if($info['kycType'] =="individual"){
						$kycStage=1;
					
					}elseif($info['kycType'] =="cooperate"){
						$kycStage=1;
					}
				
				}


				if($row['addressDocumentType'] != ""){
					

					if($info['kycType'] =="individual"){
						$kycStage=2;
						$isCompleted = true;
					}elseif($info['kycType'] =="cooperate"){
						$kycStage=3;
					}

				}

				//if ownership is uploaded increment by 1
				if(count($ownership) >0){
					//first step
					if($info['kycType'] =="cooperate"){
						$kycStage=4;
						$isCompleted = true;
					}
					
				
				}

		

				//get public profile stage
				$info['isCompleted'] =$isCompleted;
				$info['KycStage'] =$kycStage;


	



							header( 'Content-Type: application/json; charset=utf-8');
							header('HTTP/1.0 200 Success');
							$set=array('data' => $info, 'code'=>200, 'responseStatus'=>"00", 'status'=>1);
				
							$msg = json_encode($set);
							echo $msg;
							exit;


			}else{



				//$date = date('d')."-".date('m')."-".date('Y');


				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 400 Bad Request');
				$set = ['message' => "Error Retriving Kyc Info. ".$e->getMessage(), 'code' => 400, 'responseStatus' => '40', 'status' => 0];

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
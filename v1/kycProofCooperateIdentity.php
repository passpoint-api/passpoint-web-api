<?php


include('middleware/cors.php');
include('database/connection.php');
include('middleware/auth.php');
include('database/queries.php');



 





		if( !isset($data->documents)  and  !is_array($data->documents) and !isset($data->submitType)  ){
			
			header( 'Content-Type: application/json; charset=utf-8');
			header('HTTP/1.0 400 Bad Request');
			
			$set=array('responseStatus'=>"40",'message' => "Please supply the required fields",'status'=>0);
			
			$msg = json_encode($set);
			echo $msg;
			
		}else{
			


		//retive all the entry from front-end


	

		$id =$USER_DATA['id'];
		$email = $USER_DATA['email'];
		$firstName = $USER_DATA['firstName'];

		$submitType = $data->submitType;

     
		try{


				//detemine the submission type

				if($submitType =="NEW"){



			foreach($data->documents as $documents){



						$documentType = $documents->documentType;
						$documentFile = $documents->documentFile;
				
							
					//if zero does not exist proceed to register
					uploadKycProofCooperateIdentity($mysqli, $documentType, $documentFile, $id);

					//$dae = date('d')."-".date('m')."-".date('Y');


			}

			$title = "Submitted Kyc Information";
			$userid = $id;
			createActivity($mysqli, $userid, $title);


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
					"TemplateId": 33380425,
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
			$set=array('message' => "Kyc Identity Uploaded Successfully", 'code'=>200, 'responseStatus'=>"00", 'status'=>1);

			$msg = json_encode($set);
			echo $msg;
			exit;


		}elseif($submitType =="EDIT"){


			$count=0;
			foreach($data->documents as $documents){


				$documentType = $documents->documentType;
				$documentFile = $documents->documentFile;
		

					
				//if zero does not exist proceed to register
				editKycProofCooperateIdentity($mysqli, $documentType, $documentFile, $id, $count);

				//$dae = date('d')."-".date('m')."-".date('Y');
				$count++;

			}


		



			header( 'Content-Type: application/json; charset=utf-8');
			header('HTTP/1.0 200 Success');
			$set=array('message' => "Kyc Identity Uploaded Successfully", 'code'=>200, 'responseStatus'=>"00", 'status'=>1);

			$msg = json_encode($set);
			echo $msg;
			exit;

		}else{


			header( 'Content-Type: application/json; charset=utf-8');
			header('HTTP/1.0 400 Bad Request');

			$set=array('message' => "Please Specify a valid submission type.",'code'=>400, 'responseStatus'=>"40", 'status'=>0);
	
			$msg = json_encode($set);
			echo $msg;
			exit;

		}






	}catch (Exception $e) {

		header('Content-Type: application/json; charset=utf-8');
		header('HTTP/1.0 400 Bad Request');
		$set = ['message' => "Error Uploading KYC Documents.". $e->getMessage(), 'code' => 400, 'responseStatus' => '40', 'status' => 0];
		$msg = json_encode($set);
		echo $msg;
		exit;
	}


		  
}





mysqli_close($mysqli);


?>
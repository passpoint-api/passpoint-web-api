<?php


include('middleware/cors.php');
include('database/connection.php');
include('middleware/auth.php');
include('database/queries.php');
 


$json = file_get_contents('php://input');
$data = json_decode($json);





if(  !isset($data->bvn) ){
	
	 header( 'Content-Type: application/json; charset=utf-8');
	 header('HTTP/1.0 400 Bad Request');
	 
	 $set=array('responseStatus'=>"40", 'message' => "Please supply the required fields",'status'=>0);
	 
	 $msg = json_encode($set);
	 echo $msg;
	 
}else{
	
	//retive all the entry from front-end

	$id =$USER_DATA['id'];
	$email =$USER_DATA['email'];

       
        $bvn = mysqli_real_escape_string($mysqli, $data->bvn);
		$dateBirth = "1997-05-16";

		$apiKey = $USER_DATA['apiKey'];
		$merchantId = $USER_DATA['apiKey'];
       


			if(is_numeric($bvn)){

			

				$curl = curl_init();

				curl_setopt_array($curl, array(
				  CURLOPT_URL => 'https://client-sandbox.mypasspoint.com/passpoint-usr/v1/kyc-app/verify-id',
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => '',
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 0,
				  CURLOPT_FOLLOWLOCATION => true,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => 'POST',
				  CURLOPT_POSTFIELDS =>'{
					"id":"'.$bvn.'",
					"kycType":"1",
					"otherInfo": {
						"dob":"'.$dateBirth.'",
						"verificationType":"1"
					}
				}',
				  CURLOPT_HTTPHEADER => array(
					'x-channel-id: 2',
					'x-channel-code: passpoint-go-customer',
					'x-merchant-id: '.$merchantId,
					'apikey: '.$apiKey,
					'Content-Type: application/json',
					'Authorization: Basic '.$_SERVER['BAISC_AUTH']
				  ),
				));
				
				$response = curl_exec($curl);
				
				curl_close($curl);

				$response = json_decode($response, true);
  	  
		

	

	



				if($response['responseCode'] == "00"){


					header( 'Content-Type: application/json; charset=utf-8');
					header('HTTP/1.0 200 Success');
					$set=array('responseStatus'=>"00", 'message' => "BVN Data Retrived", "data"=>$response['data'], 'code'=>200,'status'=>1);
		 
					$msg = json_encode($set);
					echo $msg;
					exit;

				}else{


					header( 'Content-Type: application/json; charset=utf-8');
					header('HTTP/1.0 400 Bad Request');
					$set=array('responseStatus'=>"40", 'message' => "Error Verifing Bvn ",  'code'=>200,'status'=>1);
		 
					$msg = json_encode($set);
					echo $msg;
					exit;


				}









			




	}else{
		header( 'Content-Type: application/json; charset=utf-8');
		header('HTTP/1.0 400 Bad Request');

		$set=array('responseStatus'=>"40", 'message' => "OTP is not in right formart.", 'code'=>400, 'status'=>0);

		$msg = json_encode($set);
		echo $msg;
		exit;
	
}







		  
}










?>
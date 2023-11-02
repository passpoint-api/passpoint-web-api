<?php

include('middleware/cors.php');
include('database/connection.php');
include('database/queries.php');
 

$json = file_get_contents('php://input');
$data = json_decode($json);





if(  !isset($data->apikey) || empty($data->apikey)  ){
	
	 header( 'Content-Type: application/json; charset=utf-8');
	 header('HTTP/1.0 400 Bad Request');
	 
	 $set=array('responseStatus'=>"40", 'message' => "Please Input a Valid Api Key",);
	 
	 $msg = json_encode($set);
	 echo $msg;
	 
}else{
	
	//retive all the entry from front-end
       
        $apikey = mysqli_real_escape_string($mysqli, $data->apikey);

        

		
	//
    //check if email exist aready
   

    //if zero does not exist proceed to register
	$result = CheckApiKey($mysqli, $apikey);
    if( $result['api_exists']){

		$row =  $result['user_data'];


        $date = date('d')."-".date('m')."-".date('Y');


			  //print $response;


				$firstname = $row["firstname"];
				$lastname = $row["lastname"];
				$email = $row['email'];
				$phone = $row['phone'];
				$merchantId= $row['merchant_id'];


				


				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 200 Success');
				$set=array('responseStatus'=>"00", 'message' => "Valid API Key ", "data" =>[ "email"=>$email, "firstname"=>$firstname , "lastname"=>$lastname, "phone"=>$phone, "countryCode"=>"","cryptoEnabled"=>false,  "merchantId"=>$merchantId   ]);
	 
				$msg = json_encode($set);
				echo $msg;
				exit;


			
		}else{
				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 400 Bad Request');
	 
				$set=array('responseStatus'=>"40", 'message' => "Invalid API KEY. Wrong Credentials ");
		
				$msg = json_encode($set);
				echo $msg;
				exit;
			
		}


		  
}








mysqli_close($mysqli);


?>
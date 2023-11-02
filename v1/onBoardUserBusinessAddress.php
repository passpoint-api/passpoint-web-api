<?php

include('middleware/cors.php');
include('database/connection.php');
include('database/queries.php');
 



$json = file_get_contents('php://input');
$data = json_decode($json);




if( !isset($data->email) || empty($data->address) || empty($data->state) || empty($data->lga) || empty($data->country) ){
	
	 header( 'Content-Type: application/json; charset=utf-8');
	 header('HTTP/1.0 400 Bad Request');
	 
	 $set=array('responseStatus'=>"40", 'message'=>"Please supply the required fields", 'status'=>0);
	 
	 $msg = json_encode($set);
	 echo $msg;
	 
}else{
	
	//retive all the entry from front-end


       
        $email = mysqli_real_escape_string($mysqli, $data->email);

        $address = mysqli_real_escape_string($mysqli, $data->address);
        $state = mysqli_real_escape_string($mysqli, $data->state);

		$lga = mysqli_real_escape_string($mysqli, $data->lga);
		$country = mysqli_real_escape_string($mysqli, $data->country);
	
	
		

		



	  
		$exist = 2;

		  //if num of rows is 0 user not found
		if ( CheckUserEmailQuery($mysqli, $email, $exist)){

	

    
				$regStage = "3"; //step three



				if(CreateUserBusinessAddressQuery($mysqli, $address, $state, $lga, $country, $regStage, $email) ){


				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 200 Success');
				$set=array('responseStatus'=>"00", 'message' => "Business Address Updated for ".$email.", Please proceed to verify your account.",'code'=>200, "email"=>$email, 'status'=>1);
	 
				$msg = json_encode($set);
				echo $msg;
				exit;



				}else{


				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 400 Bad Request');
				$set=array('responseStatus'=>"40", 'message' => "Error Updating Personal Information ", 'code'=>200, "email"=>$email, 'status'=>1);
	 
				$msg = json_encode($set);
				echo $msg;
				exit;




				}

 
	
			
		}else{
				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 400 Bad Request');
	 
				$set=array('responseStatus'=>"40", 'message' => "User Email Does Not Exist, Please Re-Confirm",'code'=>400,'status'=>0);
		
				$msg = json_encode($set);
				echo $msg;
				exit;
			
		}
		  
}











?>
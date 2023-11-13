<?php

include('middleware/cors.php');
include('database/connection.php');
include('database/queries.php');
 



$json = file_get_contents('php://input');
$data = json_decode($json);




if( !isset($data->email) || empty($data->businessIndustry) ||  !isset($data->businessName ) ){
	
	 header( 'Content-Type: application/json; charset=utf-8');
	 header('HTTP/1.0 400 Bad Request');
	 
	 $set=array('responseStatus'=>"40", 'message'=>"Please supply the required fields", 'status'=>0);
	 
	 $msg = json_encode($set);
	 echo $msg;
	 exit;
	 
}else{
	
	//retive all the entry from front-end


       
        $email = mysqli_real_escape_string($mysqli, $data->email);
      
        $businessIndustry = mysqli_real_escape_string($mysqli, $data->businessIndustry);

		$businessName = mysqli_real_escape_string($mysqli, $data->businessName);
	
	 

		function isValidEmail($email)
		{
			// Use filter_var with FILTER_VALIDATE_EMAIL
			return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
		}



	  $exist = 2;

		  //if num of rows is 0 user not found
		if(CheckUserEmailQuery($mysqli, $email, $exist)){


			if(isValidEmail($email)){
		

		

				$reference ="";
			
				$regStage = "2";// step one



				if(CreateIndividualBussinesInfoQuery($mysqli, $businessName, $businessIndustry, $regStage, $email)){


					header( 'Content-Type: application/json; charset=utf-8');
					header('HTTP/1.0 200 Success');
					$set=array('responseStatus'=>"00", 'message' => "Business Information Updated  for ".$email.", Please proceed to fill your Address.",'code'=>200, "email"=>$email, 'status'=>1);
		 
					$msg = json_encode($set);
					echo $msg;
					exit;


				}else{


					header( 'Content-Type: application/json; charset=utf-8');
					header('HTTP/1.0 400 Bad Request');
					$set=array('responseStatus'=>"40", 'message' => "Error Updating Business Info",'code'=>400, "email"=>$email, 'status'=>1);
		 
					$msg = json_encode($set);
					echo $msg;
					exit;



				}


				
				



 
			










			}else{
				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 400 Bad Request');

				$set=array('message' => "Email Provided is not a valid email.", 'code'=>400,'responseStatus'=>"50");

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
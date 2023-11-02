<?php


include('middleware/cors.php');
include('database/connection.php');
include('database/queries.php');
 
 

$json = file_get_contents('php://input');
$data = json_decode($json);





if(   !isset($data->email)   ){
	
	 header( 'Content-Type: application/json; charset=utf-8');
	 header('HTTP/1.0 400 Bad Request');
	 
	 $set=array('responseStatus'=>"40", 'message' => "Please supply the required fields",'status'=>0);
	 
	 $msg = json_encode($set);
	 echo $msg;
	 
}else{
	
	//retive all the entry from front-end


	
       
       
        $email = mysqli_real_escape_string($mysqli, $data->email);
        

		

  	  
		$exist = 2;

		  //if num of rows is 0 user not found
		if ( CheckUserEmailQuery($mysqli, $email, $exist)){



			



				$otp = GetOtp($mysqli, $email)['user_data']; 


					header( 'Content-Type: application/json; charset=utf-8');
					header('HTTP/1.0 200 Success');
					
					$set=array('responseStatus'=>"00", 'otp' => $otp, "email"=>$email, 'code'=>200,'status'=>1);
		 
					$msg = json_encode($set);
					echo $msg;
					exit;

			





			
		}else{
				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 400 Bad Request');
	 
				$set=array('responseStatus'=>"40", 'message' => "User Email Cannot be found.", 'code'=>400, 'status'=>0);
		
				$msg = json_encode($set);
				echo $msg;
				exit;
			
		}


		  
}










?>
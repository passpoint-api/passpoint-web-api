<?php

include('middleware/cors.php');
include('database/connection.php');
include('database/queries.php');
 



$json = file_get_contents('php://input');
$data = json_decode($json);




if( !isset($data->email) || empty($data->businessType) || empty($data->businessIndustry) || empty($data->password)  ||  !isset($data->businessName )  ||  !isset($data->rcNumber)   || empty($data->rcNumber) ){
	
	 header( 'Content-Type: application/json; charset=utf-8');
	 header('HTTP/1.0 200 Success');
	 
	 $set=array('responseStatus'=>"40", 'message'=>"Please supply the required fields", 'status'=>0);
	 
	 $msg = json_encode($set);
	 echo $msg;
	 
}else{
	
	//retive all the entry from front-end


       
        $email = mysqli_real_escape_string($mysqli, $data->email);
        $businessType = mysqli_real_escape_string($mysqli, $data->businessType);
        $businessIndustry = mysqli_real_escape_string($mysqli, $data->businessIndustry);

		$businessName = mysqli_real_escape_string($mysqli, $data->businessName);
	
		$password = mysqli_real_escape_string($mysqli, $data->password);
		
		$rcNumber = mysqli_real_escape_string($mysqli, $data->rcNumber);


		function isValidEmail($email)
		{
			// Use filter_var with FILTER_VALIDATE_EMAIL
			return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
		}



	  $exist = 1;

		  //if num of rows is 0 user not found
		if(CheckUserEmailQuery($mysqli, $email, $exist)){


			if(isValidEmail($email)){
		

		//check password strength
		function validatePassword($password) {
			$uppercase = preg_match('@[A-Z]@', $password);
			$number    = preg_match('@[0-9]@', $password);
			$length    = strlen($password) >= 8;
		
			if (!$uppercase || !$number || !$length) {
				return false;
			}
		
			return true;
		}
		
	
		if (validatePassword($password)) {


        $date = date('d')."-".date('m')."-".date('Y');


    



		


				$hashpassword = password_hash($password, PASSWORD_DEFAULT);

				$reference ="";
				$userType ="2"; //co-operate
				$regStage = "1";// step one


				$create = CreateUserBussinesInfoQuery($mysqli,  $businessType, $businessIndustry, $email, $hashpassword, $businessName, $rcNumber, $date, $userType, $regStage);




				
				



 
				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 200 Success');
				$set=array('responseStatus'=>"00", 'message' => "Account Created for ".$email.", Please proceed to fill your personal details",'code'=>200, "email"=>$email, 'status'=>1);
	 
				$msg = json_encode($set);
				echo $msg;
				exit;





		}else{


			header( 'Content-Type: application/json; charset=utf-8');
			header('HTTP/1.0 400 Bad Request');
			$set=array('responseStatus'=>"40", 'message' =>"Password Strength is low, Please ensure password contains at least one number, one uppercase letter, and is a minimum of 8 characters",'code'=>400,'status'=>0);
 
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
	 
				$set=array('responseStatus'=>"40", 'message' => "User Email has Already been Onboarded",'code'=>400,'status'=>0);
		
				$msg = json_encode($set);
				echo $msg;
				exit;
			
			
		}


		  
}











?>
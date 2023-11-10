
<?php


include('middleware/cors.php');
include('database/connection.php');
include('database/queries.php');
 




	$json = file_get_contents('php://input');
	$data = json_decode($json);



if( !isset( $data->password) || !isset( $data->confirm) || !isset($data->email) ){
	
	 header( 'Content-Type: application/json; charset=utf-8');
	 header('HTTP/1.0 400 Bad Request');
	 
	 $set=array('message' => "Please supply the required fields", 'responseStatus'=>"40");
	 
	 $msg = json_encode($set);
	 echo $msg;

	 
}else{

	//$crypt = new PasswordLib\PasswordLib;
	//retive all the entry from front-end

	$password = $data->password;
	$confirm = $data->confirm;
	$email = $data->email;

	



	function isValidEmail($email)
	{
		// Use filter_var with FILTER_VALIDATE_EMAIL
		return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
	}

	function validatePassword($password) {
		$uppercase = preg_match('@[A-Z]@', $password);
		$number    = preg_match('@[0-9]@', $password);
		$length    = strlen($password) >= 8;
	
		if (!$uppercase || !$number || !$length) {
			return false;
		}
	
		return true;
	}

	
	if ( isValidEmail($email)){

		if ( validatePassword($password) and validatePassword($confirm)  ){ 

	//since password is encryted want to check if user exist first
		
	$exist = 2;

	//if num of rows is 0 user not found
  if ( CheckUserEmailQuery($mysqli, $email, $exist)){

			
	$result = LoginUser($mysqli, $email);

	$row =  $result['user_data'];
	

	if($row['otpStatus']==1){

		
			//compare use entry with encryted password
			//password_verify($password, $row['password']) 
			// $crypt->verifyPasswordHash($password, $row['password'])


			if($password === $confirm){

			
				

						//log the users info
						$hashpassword = password_hash($password, PASSWORD_DEFAULT);

						
		
						if(ResetPasswordOtpQuery($mysqli, $hashpassword, $email)){

							header( 'Content-Type: application/json; charset=utf-8');
							header('HTTP/1.0 200 Success');

				
						
							$set=array('message' => "Password Reset Successful, Please Proceed to login", 'code'=>400, 'responseStatus'=>"00");
				 
							$msg = json_encode($set);
							echo $msg;
							exit;


						}else{


							header( 'Content-Type: application/json; charset=utf-8');
							header('HTTP/1.0 400 Bad Request');

				
						
							$set=array('message' => "Erro Resetting password, Please Retry.", 'code'=>400, 'responseStatus'=>"40");
				 
							$msg = json_encode($set);
							echo $msg;
							exit;
						}

					
						

			}else{

				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 400 Bad Request');
				$set=array('message' => "Password do not match. Please Check your Password", 'code'=>400, 'responseStatus'=>"40");
	 
				$msg = json_encode($set);
				echo $msg;
				exit;
			}





		}else{

			header( 'Content-Type: application/json; charset=utf-8');
			header('HTTP/1.0 400 Bad Request');
			$set=array('message' => "Please Ensure Reset Password Otp is validated before proceding.", 'code'=>400, 'responseStatus'=>"40");
 
			$msg = json_encode($set);
			echo $msg;
			exit;
		}


					
	

			
		}else{
				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 400 Bad Request');
	 
				$set=array('message' => "User has not been OnBoarded, Or Email Address is Invalid", 'code'=>400,'responseStatus'=>"50");
		
				$msg = json_encode($set);
				echo $msg;
			
		}



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








		  
}


mysqli_close($mysqli);



?>
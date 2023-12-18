
<?php



include('middleware/cors.php');
include('database/connection.php');
include('middleware/auth.php');
include('database/queries.php');
 





if( !isset( $data->password) || !isset( $data->confirm)  ){
	
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

	$id =$USER_DATA['id'];
	$email =$USER_DATA['email'];




	function validatePassword($password) {
		$uppercase = preg_match('@[A-Z]@', $password);
		$number    = preg_match('@[0-9]@', $password);
		$length    = strlen($password) >= 8;
	
		if (!$uppercase || !$number || !$length) {
			return false;
		}
	
		return true;
	}

	


		if ( validatePassword($password) and validatePassword($confirm)  ){ 

			//since password is encryted want to check if user exist first
			$exist = 2;

			//if num of rows is 0 user not found
		if ( CheckUserEmailQuery($mysqli, $email, $exist)){

			$result = LoginUser($mysqli, $email);

			$row =  $result['user_data'];

			
			//compare use entry with encryted password
			//password_verify($password, $row['password']) 
			// $crypt->verifyPasswordHash($password, $row['password'])


			if($password === $confirm){

		
						//log the users info
						$hashpassword = password_hash($password, PASSWORD_DEFAULT);

						
						if(ResetPasswordOtpQuery($mysqli, $hashpassword, $email)){

							//update passwordChangeStatus
							$status=1;

							updatePasswordChangeStatus($mysqli, $status, $id);


							$title = "Change Account Password ";
							$userid = $id;
							createActivity($mysqli, $userid, $title);


							header( 'Content-Type: application/json; charset=utf-8');
							header('HTTP/1.0 200 Success');

				
							$set=array('message' => "Password Changed Successfully.", 'code'=>400, 'responseStatus'=>"00");
				 
							$msg = json_encode($set);
							echo $msg;
							exit;


						}else{


							header( 'Content-Type: application/json; charset=utf-8');
							header('HTTP/1.0 400 Bad Request');

				
						
							$set=array('message' => "Erro Changing password, Please Retry.", 'code'=>400, 'responseStatus'=>"40");
				 
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
	 
				$set=array('message' => "User has not been OnBoarded, Or Email Address is Invalid", 'code'=>400,'responseStatus'=>"50");
		
				$msg = json_encode($set);
				echo $msg;
				exit;
			
		}



	}else{


		header( 'Content-Type: application/json; charset=utf-8');
		header('HTTP/1.0 400 Bad Request');
		$set=array('responseStatus'=>"40", 'message' =>"Password Strenght is low, Please ensure password contains at least 1 number one uppercase letter and is minimum of 8 characters.",'code'=>400,'status'=>0);

		$msg = json_encode($set);
		echo $msg;
		exit;



	}














		  
}


mysqli_close($mysqli);



?>
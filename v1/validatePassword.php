
<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 'On');


include('middleware/cors.php');
include('database/connection.php');
include('middleware/auth.php');
include('database/queries.php');
 





if( !isset( $data->password) ){
	
	 header( 'Content-Type: application/json; charset=utf-8');
	 header('HTTP/1.0  400 Bad Request');
	 
	 $set=array('message' => "Please supply the required fields", 'responseStatus'=>"40");
	 
	 $msg = json_encode($set);
	 echo $msg;

	 
}else{

	//$crypt = new PasswordLib\PasswordLib;
	//retive all the entry from front-end

	$password = $data->password;
	$id =$USER_DATA['id'];


	function validatePassword($password) {
		$uppercase = preg_match('@[A-Z]@', $password);
		$number    = preg_match('@[0-9]@', $password);
		$length    = strlen($password) >= 8;
	
		if (!$uppercase || !$number || !$length) {
			return false;
		}
	
		return true;
	}

	


		if ( validatePassword($password)){

	//since password is encryted want to check if user exist first
		

	$exist = 2;

	$result = GetUserDetails($mysqli, $id);

	//var_dump($result);
	//if num of rows is 0 user not found
  if ( $result['user_exists'] ){

			
	
			$row =  $result['user_data'];
			//compare use entry with encryted password
			//password_verify($password, $row['password']) 
			// $crypt->verifyPasswordHash($password, $row['password'])


			if(password_verify($password, $row['password'])){

	
							header( 'Content-Type: application/json; charset=utf-8');
							header('HTTP/1.0 200 Success');

				
		 
							$set=array(

								'responseStatus'=>"00",
								'message' => "Password Validated Successful.",
							
							);
				 
							$msg = json_encode($set);
							echo $msg;
							exit;

			}else{

				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 400 Bad Request');
				$set=array('message' => "Wrong Password. Please Check your Password", 'code'=>400, 'responseStatus'=>"40");
	 
				$msg = json_encode($set);
				echo $msg;
				exit;
			}


					
	

			
		}else{
				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 400 Bad Request');
	 
				$set=array('message' => "Invaild Account Credentials", 'code'=>400,'responseStatus'=>"50");
		
				$msg = json_encode($set);
				echo $msg;
			
		}


	}else{
		header( 'Content-Type: application/json; charset=utf-8');
		header('HTTP/1.0 400 Bad Request');

		$set=array('message' => "Invaild Account Credentials", 'code'=>400,'responseStatus'=>"50");

		$msg = json_encode($set);
		echo $msg;
		exit;
	
}











		  
}


mysqli_close($mysqli);



?>
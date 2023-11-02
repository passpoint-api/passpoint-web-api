<?php


include('middleware/cors.php');
include('database/connection.php');
include('middleware/auth.php');
include('database/queries.php');



 





		if( !isset($data->socials) and !is_array($data->socials) and COUNT($data->socials)<0   and  !isset($data->submitType) ){
			
			header( 'Content-Type: application/json; charset=utf-8');
			header('HTTP/1.0 400 Bad Request');
			
			$set=array('responseStatus'=>"40",'message' => "Please supply the required fields",'status'=>0);
			
			$msg = json_encode($set);
			echo $msg;
			
		}else{
			


		//retive all the entry from front-end


       
       



		$id =$USER_DATA['id'];




     
		try{



			function isValidEmail($email)
			{
				// Use filter_var with FILTER_VALIDATE_EMAIL
				return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
			}


			
			$submitType = $data->submitType;

			if($submitType =="NEW"){

			
			


			$companyEmail = $data->companyEmail;
			$companyPhone = $data->companyPhone;
			$companyAddress = $data->companyAddress;

			$openingDay = $data->openingDay;
			$closingDay = $data->closingDay;

			$openingHour = $data->openingHour;
			$closingHour = $data->closingHour;

			$hasWebContact = $data->hasWebContact;


			if ( isValidEmail($companyEmail)){


			if(updateProfileContact($mysqli, $companyEmail, $companyPhone, $companyAddress, $openingDay, $closingDay, $openingHour, $closingHour, $hasWebContact, $id )){

	
			
			foreach ($data->socials as $socials) {

		
				$socialName = $socials->name;
				$socialUrl = $socials->url;

				addProfileSocial($mysqli , $socialName, $socialUrl, $id );

		
			}






        	//$date = date('d')."-".date('m')."-".date('Y');


				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 200 Success');
				$set=array('message' => "Public Profile Contact Details Added Successfully", 'code'=>200, 'responseStatus'=>"00", 'status'=>1);
	 
				$msg = json_encode($set);
				echo $msg;
				exit;


		}else{



			header('Content-Type: application/json; charset=utf-8');
			header('HTTP/1.0 400 Bad Request');
			$set = ['message' => "Error Adding Profile Contacts. ", 'code' => 400, 'responseStatus' => '40', 'status' => 0];
			$msg = json_encode($set);
			echo $msg;
			exit;


		}

		

		}else{
			header( 'Content-Type: application/json; charset=utf-8');
			header('HTTP/1.0 400 Bad Request');

			//Email Provided is not a valid email.

			$set=array('message' => "Invaild Email Provided", 'code'=>400,'responseStatus'=>"50");

			$msg = json_encode($set);
			echo $msg;
			exit;
		}








		}elseif($submitType =="EDIT"){








			$companyEmail = $data->companyEmail;
			$companyPhone = $data->companyPhone;
			$companyAddress = $data->companyAddress;

			$openingDay = $data->openingDay;
			$closingDay = $data->closingDay;

			$openingHour = $data->openingHour;
			$closingHour = $data->closingHour;

			$hasWebContact = $data->hasWebContact;


			if (isValidEmail($companyEmail)){


			if(updateProfileContact($mysqli, $companyEmail, $companyPhone, $companyAddress, $openingDay, $closingDay, $openingHour, $closingHour, $hasWebContact, $id )){

	
				$count =0;
			foreach ($data->socials as $socials) {

				$social_id = getSocialId($mysqli, $count, $id );

				$socialName = $socials->name;
				$socialUrl = $socials->url;

				updateProfileSocial($mysqli , $socialName, $socialUrl, $social_id );

				$count++;
			}



			$title = "Update Business Profile Contact Details";
			$userid = $id;
			createActivity($mysqli, $userid, $title);



        	//$date = date('d')."-".date('m')."-".date('Y');


				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 200 Success');
				$set=array('message' => "Public Profile Contact Details Updated Successfully", 'code'=>200, 'responseStatus'=>"00", 'status'=>1);
	 
				$msg = json_encode($set);
				echo $msg;
				exit;


		}else{



			header('Content-Type: application/json; charset=utf-8');
			header('HTTP/1.0 400 Bad Request');
			$set = ['message' => "Error Adding Profile Contacts. ", 'code' => 400, 'responseStatus' => '40', 'status' => 0];
			$msg = json_encode($set);
			echo $msg;
			exit;


		}

		

		}else{
			header( 'Content-Type: application/json; charset=utf-8');
			header('HTTP/1.0 400 Bad Request');

			//Email Provided is not a valid email.

			$set=array('message' => "Invaild Email Provided", 'code'=>400,'responseStatus'=>"50");

			$msg = json_encode($set);
			echo $msg;
			exit;
		}









						


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
		$set = ['message' => "Error Adding Profile Contacts. ".$e->getMessage(), 'code' => 400, 'responseStatus' => '40', 'status' => 0];
		$msg = json_encode($set);
		echo $msg;
		exit;
	}


		  
}





mysqli_close($mysqli);


?>
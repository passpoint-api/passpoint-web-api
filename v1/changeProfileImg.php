<?php


include('middleware/cors.php');
include('database/connection.php');
include('middleware/auth.php');
include('database/queries.php');



 





		if(  !isset($data->image)  ){
			
			header( 'Content-Type: application/json; charset=utf-8');
			header('HTTP/1.0 400 Bad Request');
			
			$set=array('responseStatus'=>"40",'message' => "Please supply the required fields",'status'=>0);
			
			$msg = json_encode($set);
			echo $msg;
			
		}else{
			


		//retive all the entry from front-end


       
        $image =  $data->image;



		$id =$USER_DATA['id'];


     
		try{


    //if zero does not exist proceed to register
    if(uploadProfileImage($mysqli, $id, $image)){



        	//$date = date('d')."-".date('m')."-".date('Y');


				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 200 Success');
				$set=array('message' => "Profile Image Uploaded Successfully", 'code'=>200, 'responseStatus'=>"00", 'status'=>1);
	 
				$msg = json_encode($set);
				echo $msg;
				exit;



		
	
			
		}else{
				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 400 Bad Request');
	 
				$set=array('message' => "Error Uploading Logo.",'code'=>400, 'responseStatus'=>"40", 'status'=>0);
		
				$msg = json_encode($set);
				echo $msg;
				exit;
			
		}


	}catch (Exception $e) {

		

		header('Content-Type: application/json; charset=utf-8');
		header('HTTP/1.0 400 Bad Request');
		$set = ['message' => "Error Updating Profile Image Already Uploaded.", 'code' => 400, 'responseStatus' => '40', 'status' => 0];
		$msg = json_encode($set);
		echo $msg;
		exit;
	}


		  
}





mysqli_close($mysqli);


?>
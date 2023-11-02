<?php
 

include('middleware/cors.php');
include('database/connection.php');
include('middleware/auth.php');
include('database/queries.php');





		if( !isset($data->socialId) ){
			
			header( 'Content-Type: application/json; charset=utf-8');
			header('HTTP/1.0 400 Bad Request');
			
			$set=array('responseStatus'=>"40",'message' => "Please suppply the required fields",'status'=>0);
			
			$msg = json_encode($set);
			echo $msg;
			exit;
			
		}else{
			


		//retive all the entry from front-end


       


		$id =$USER_DATA['id'];




     
		try{



			$socialId = $data->socialId;



				if(DeleteSocial($mysqli, $socialId)){


						// $date = date('d')."-".date('m')."-".date('Y');


					header( 'Content-Type: application/json; charset=utf-8');
					header('HTTP/1.0 200 Success');
					$set=array('message' => "Social URL Deleted Successfully", 'code'=>200, 'responseStatus'=>"00", 'status'=>1);
		
					$msg = json_encode($set);
					echo $msg;
					exit;

					
				}else{





					header( 'Content-Type: application/json; charset=utf-8');
					header('HTTP/1.0 400 Success');
					$set=array('message' => "Error Deleting Social URL.", 'code'=>400, 'responseStatus'=>"40", 'status'=>1);
		
					$msg = json_encode($set);
					echo $msg;
					exit;





				}



		

	}catch (Exception $e) {

		

		header('Content-Type: application/json; charset=utf-8');
		header('HTTP/1.0 400 Bad Request');
		$set = ['message' => "Error Deleting Social .".$e->getMessage(), 'code' => 400, 'responseStatus' => '40', 'status' => 0];
		$msg = json_encode($set);
		echo $msg;
		exit;
	}


		  
}




?>
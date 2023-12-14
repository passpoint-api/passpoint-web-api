<?php
 

include('middleware/cors.php');
include('database/connection.php');
include('middleware/auth.php');
include('database/queries.php');





		if( !isset($data->roleTitle) and !is_array($data->permission)  and  !isset($data->roleDesc)  ){
			
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



			$roleTitle = $data->roleTitle;
			$roleDesc = $data->roleDesc;
			$permission = $data->permission;


		

				if(createRole($mysqli, $roleTitle, $roleDesc, $permission, $id)){

			

					header( 'Content-Type: application/json; charset=utf-8');
					header('HTTP/1.0 200 Success');
					$set=array('message' => "New Role Created Successfully", 'code'=>200, 'responseStatus'=>"00", 'status'=>1);
		
					$msg = json_encode($set);
					echo $msg;
					exit;


			}else{


	

				header('Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 400 Bad Request');
				$set = ['message' => "Error Creating Role.", 'code' => 400, 'responseStatus' => '40', 'status' => 0];
				$msg = json_encode($set);
				echo $msg;
				exit;




			}



	
		

	}catch (Exception $e) {

		

		header('Content-Type: application/json; charset=utf-8');
		header('HTTP/1.0 400 Bad Request');
		$set = ['message' => "Error Creating Role .".$e->getMessage(), 'code' => 400, 'responseStatus' => '40', 'status' => 0];
		$msg = json_encode($set);
		echo $msg;
		exit;
	}


		  
}







?>
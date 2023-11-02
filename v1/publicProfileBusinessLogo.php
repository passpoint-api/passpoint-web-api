<?php


include('middleware/cors.php');
include('database/connection.php');
include('middleware/auth.php');
include('database/queries.php');



 





		if(  !isset($data->logo) and !isset($data->submitType) ){
			
			header( 'Content-Type: application/json; charset=utf-8');
			header('HTTP/1.0 400 Bad Request');
			
			$set=array('responseStatus'=>"40",'message' => "Please supply the required fields",'status'=>0);
			
			$msg = json_encode($set);
			echo $msg;
			
		}else{
			


		//retive all the entry from front-end

       
        $logo =  $data->logo;
		$submitType = $data->submitType;



		$id =$USER_DATA['id'];


     
		try{


				//detemine the submission type

			if($submitType =="NEW"){


					//if zero does not exist proceed to register
					if(uploadBusinessLogo($mysqli,$id, $logo)){



						//$date = date('d')."-".date('m')."-".date('Y');


							header( 'Content-Type: application/json; charset=utf-8');
							header('HTTP/1.0 200 Success');
							$set=array('message' => " Uploaded Successfully", 'code'=>200, 'responseStatus'=>"00", 'status'=>1);
				
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


			}elseif($submitType =="EDIT"){

				//ensure the needed Id is returned

				//if zero does not exist proceed to register
				if(editBusinessLogo($mysqli, $id, $logo)){



					//$date = date('d')."-".date('m')."-".date('Y');


						header( 'Content-Type: application/json; charset=utf-8');
						header('HTTP/1.0 200 Success');
						$set=array('message' => " Uploaded Successfully", 'code'=>200, 'responseStatus'=>"00", 'status'=>1);
			
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
		$set = ['message' => "Business Logo Already Uploaded.", 'code' => 400, 'responseStatus' => '40', 'status' => 0];
		$msg = json_encode($set);
		echo $msg;
		exit;
	}


		  
}





mysqli_close($mysqli);


?>
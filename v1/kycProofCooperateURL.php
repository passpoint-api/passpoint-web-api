<?php


include('middleware/cors.php');
include('database/connection.php');
include('middleware/auth.php');
include('database/queries.php');



 





		if( !isset($data->url) ){
			
			header( 'Content-Type: application/json; charset=utf-8');
			header('HTTP/1.0 400 Bad Request');
			
			$set=array('responseStatus'=>"40",'message' => "Please supply the required fields",'status'=>0);
			
			$msg = json_encode($set);
			echo $msg;
			exit;
			
		}else{
			


		//retive all the entry from front-end


		$url = $data->url;




		$id =$USER_DATA['id'];


     
		try{


					//if zero does not exist proceed to register
					if(uploadKycURL($mysqli, $url, $id)){



							//$dae = date('d')."-".date('m')."-".date('Y');

								header( 'Content-Type: application/json; charset=utf-8');
								header('HTTP/1.0 200 Success');
								$set=array('message' => "Kyc Website Url Updated Successfully", 'code'=>200, 'responseStatus'=>"00", 'status'=>1);
					
								$msg = json_encode($set);
								echo $msg;
								exit;


							
						}else{
								header( 'Content-Type: application/json; charset=utf-8');
								header('HTTP/1.0 400 Bad Request');
					
								$set=array('message' => "Error Adding Url.",'code'=>400, 'responseStatus'=>"40", 'status'=>0);
						
								$msg = json_encode($set);
								echo $msg;
								exit;
							
						}


					}catch (Exception $e) {

						
						header('Content-Type: application/json; charset=utf-8');
						header('HTTP/1.0 400 Bad Request');
						$set = ['message' => "Error Adding KYC Upload.", 'code' => 400, 'responseStatus' => '40', 'status' => 0];
						$msg = json_encode($set);
						echo $msg;
						exit;

					}


		  
}



?>
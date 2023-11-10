<?php


include('middleware/cors.php');
include('database/connection.php');
include('middleware/auth.php');
include('database/queries.php');



 





		if( !isset($data->documentType)  and !isset($data->documentNumber)  ){
			
			header( 'Content-Type: application/json; charset=utf-8');
			header('HTTP/1.0 400 Bad Request');
			
			$set=array('responseStatus'=>"40",'message' => "Please supply the required fields",'status'=>0);
			
			$msg = json_encode($set);
			echo $msg;
			
		}else{
			

		//retive all the entry from front-end


		$documentType = $data->documentType;
        $documentNumber = $data->documentNumber;



		$id =$USER_DATA['id'];


     
		try{


    //if zero does not exist proceed to register
    if(uploadKycProofIdividualIdentity($mysqli, $documentType, $documentNumber, $id)){



        	//$dae = date('d')."-".date('m')."-".date('Y');


				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 200 Success');
				$set=array('message' => "Kyc Identity Uploaded Successfully", 'code'=>200, 'responseStatus'=>"00", 'status'=>1);
	 
				$msg = json_encode($set);
				echo $msg;
				exit;



		
	
			
		}else{
				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 400 Bad Request');
	 
				$set=array('message' => "Error Uploading File.",'code'=>400, 'responseStatus'=>"40", 'status'=>0);
		
				$msg = json_encode($set);
				echo $msg;
				exit;
			
		}


	}catch (Exception $e) {

		

		header('Content-Type: application/json; charset=utf-8');
		header('HTTP/1.0 400 Bad Request');
		$set = ['message' => "Error Uploading Information.", 'code' => 400, 'responseStatus' => '40', 'status' => 0];
		$msg = json_encode($set);
		echo $msg;
		exit;
	}


		  
}





mysqli_close($mysqli);


?>
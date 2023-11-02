<?php


include('middleware/cors.php');
include('database/connection.php');
include('middleware/auth.php');
include('database/queries.php');



 





		if( !isset($data->desc) and !is_array($data->desc) and COUNT($data->desc)<0  and  !isset($data->submitType)  ){
			
			header( 'Content-Type: application/json; charset=utf-8');
			header('HTTP/1.0 400 Bad Request');
			
			$set=array('responseStatus'=>"40",'message' => "Please supply the required fields",'status'=>0);
			
			$msg = json_encode($set);
			echo $msg;
			
		}else{
			


		//retive all the entry from front-end


       
       



		$id =$USER_DATA['id'];




     
		try{



			$submitType = $data->submitType;

			if($submitType =="NEW"){


			$aboutBusiness = $data->aboutBusiness;


			$updateAboutBusiness = addAboutBusiness($mysqli, $aboutBusiness, $id );

			
			foreach ($data->desc as $desc) {
				$businessHeadLine = $desc->businessHeadLine;
				$businessDesc = $desc->businessDesc;

				addProfileDesc($mysqli , $businessDesc, $businessHeadLine, $id );

			

			}



			$title = "Business Profile Updated";
			$userid = $id;
			createActivity($mysqli, $userid, $title);



        	//$date = date('d')."-".date('m')."-".date('Y');


				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 200 Success');
				$set=array('message' => "Public Profile Desc Added Successfully", 'code'=>200, 'responseStatus'=>"00", 'status'=>1);
	 
				$msg = json_encode($set);
				echo $msg;
				exit;



			}elseif($submitType =="EDIT"){

				$aboutBusiness = $data->aboutBusiness;

				$updateAboutBusiness = addAboutBusiness($mysqli, $aboutBusiness, $id );

				$count =0;
				foreach ($data->desc as $desc) {
				

					$des_id = getDescId($mysqli, $count, $id );

					$businessHeadLine = $desc->businessHeadLine;
					$businessDesc = $desc->businessDesc;
	
					updateProfileDesc($mysqli , $businessDesc, $businessHeadLine, $des_id );
	
				
					$count++;
				}





				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 200 Success');
				$set=array('message' => "Public Profile Desc Updated Successfully", 'code'=>200, 'responseStatus'=>"00", 'status'=>1);
	 
				$msg = json_encode($set);
				echo $msg;
				exit;



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
		$set = ['message' => "Error Uploading Business Desc.".$e->getMessage(), 'code' => 400, 'responseStatus' => '40', 'status' => 0];
		$msg = json_encode($set);
		echo $msg;
		exit;
	}


		  
}





mysqli_close($mysqli);


?>
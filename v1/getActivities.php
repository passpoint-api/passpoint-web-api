<?php


include('middleware/cors.php');
include('database/connection.php');
include('middleware/auth.php');
include('database/queries.php');




			
				$id =$USER_DATA['id'];


				$list = getActivities($mysqli, $id)['p_data'];

		

				$jsonObj = array(); // Initialize an empty array to store JSON data

				foreach ($list as $info) { // Loop through the $list array
					$row = array(); // Initialize an empty array for each row of data
					
					// Assign values to the keys in $row array based on your requirements
					$row['title'] = $info['title']; 
					$row['date'] = $info['formattted_date']; 
			
					// Push the $row array into the $jsonObj array
					array_push($jsonObj, $row);
				}
			



				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 200 Success');
				$set=array('responseStatus'=>"00", 'message' => "All Activity",  "data"=> $jsonObj);
	 
				$msg = json_encode($set);
				echo $msg;



		
	
			
	

		  










mysqli_close($mysqli);


?>
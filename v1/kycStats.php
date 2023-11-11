<?php



include('middleware/cors.php');
include('database/connection.php');

include('database/queries.php');
 




				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 200 Success');


				$set=array("metrics" =>["approvedUsers"=>getApprovedUsers($mysqli), "pendingUsers"=>getPendingUsers($mysqli), "rejectedUsers"=>getRejectUsers($mysqli), "totalUsers"=>getTotalUsers($mysqli)],  'responseStatus'=>"00", 'status'=>1);
	 
				$msg = json_encode($set);
				echo $msg;



		
	

mysqli_close($mysqli);


?>
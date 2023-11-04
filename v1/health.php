
<?php

include('database/connection.php');
	
	 header( 'Content-Type: application/json; charset=utf-8');
	 header('HTTP/1.0  200 success');
	 
	 $set=array('message' => "Health Check", 'responseStatus'=>"00");
	 
	 $msg = json_encode($set);
	 echo $msg;



?>
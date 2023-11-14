<?php



include('middleware/cors.php');
include('database/connection.php');
include('middleware/auth.php');
include('database/queries.php');
 




$userid = $USER_DATA['id'];


				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 200 Success');
				$set=array("metrics" =>["totalRevenue"=>0, "bookingConversion"=>0 , "completedBookings"=>0, "totalVists"=>0], "monthlyRevenue"=>["totalMonthlyRevenue"=>0, "percentageGrowth"=>0, "RevenueList"=>["Jan"=>0, "Feb"=>0, "Mar"=>0, "Apr"=>0, "May"=>0, "June"=>0, "July"=>0, "Aug"=>0, "Sept"=>0, "Oct"=>0, "Nov"=>0, "Dec"=>0]],

				"customerGrowth"=>["totalCustomer"=>0, "percentageGrowth"=>0, "growthList"=>["Jan"=>0, "Feb"=>0, "Mar"=>0, "Apr"=>0, "May"=>0, "June"=>0, "July"=>0, "Aug"=>0, "Sept"=>0, "Oct"=>0, "Nov"=>0, "Dec"=>0]],

				
				'responseStatus'=>"00", 'status'=>1);
	 
				$msg = json_encode($set);
				echo $msg;



		
	
	



mysqli_close($mysqli);


?>
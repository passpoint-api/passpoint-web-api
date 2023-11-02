<?php



include('middleware/cors.php');
include('database/connection.php');
include('middleware/auth.php');
include('database/queries.php');
 




$userid = $USER_DATA['id'];


				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 200 Success');
				$set=array("metrics" =>["totalRevenue"=>100, "bookingConversion"=>24 , "completedBookings"=>58, "totalVists"=>2489], "monthlyRevenue"=>["totalMonthlyRevenue"=>100, "percentageGrowth"=>15.5, "RevenueList"=>["Jan"=>300, "Feb"=>100, "Mar"=>0, "Apr"=>250, "May"=>150, "June"=>240, "July"=>0, "Aug"=>140, "Sept"=>450, "Oct"=>280, "Nov"=>180, "Dec"=>0]],

				"customerGrowth"=>["totalCustomer"=>1300, "percentageGrowth"=>33.2, "growthList"=>["Jan"=>100, "Feb"=>350, "Mar"=>0, "Apr"=>170, "May"=>100, "June"=>100, "July"=>0, "Aug"=>130, "Sept"=>290, "Oct"=>120, "Nov"=>90, "Dec"=>260]],

				
				'responseStatus'=>"00", 'status'=>1);
	 
				$msg = json_encode($set);
				echo $msg;



		
	
	



mysqli_close($mysqli);


?>
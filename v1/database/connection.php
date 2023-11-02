<?php




require_once '../vendor/autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();






if( $_ENV['APPLICATION_STATE']==="staging")
		{	
			//local 

			//local live 
			DEFINE ('DB_USER', $_ENV['STAGING_DB_USER']);
			DEFINE ('DB_PASSWORD', $_ENV['STAGING_DB_PASSWORD']);
			DEFINE ('DB_HOST',  $_ENV['STAGING_DB_HOST']); //host name depends on server
			DEFINE ('DB_NAME', $_ENV['STAGING_DB_NAME']);
			DEFINE ('DB_PORT',  $_ENV['STAGING_DB_PORT']);


	
		
				
		}
		elseif( $_ENV['APPLICATION_STATE']==="live")
		{
		
 				//local live 
				 DEFINE ('DB_USER', $_ENV['LIVE_DB_USER']);
				 DEFINE ('DB_PASSWORD', $_ENV['LIVE_DB_PASSWORD']);
				 DEFINE ('DB_HOST',  $_ENV['LIVE_DB_HOST']); //host name depends on server
				 DEFINE ('DB_NAME', $_ENV['LIVE_DB_NAME']);
				 DEFINE ('DB_PORT',  $_ENV['LIVE_DB_PORT']);
			
		
		}

	

	$mysqli =mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME,DB_PORT);


	mysqli_query($mysqli,"SET NAMES 'utf8'");





/*

	try {
		$server = '54.72.9.173';
		$database = 'passpointdashboard';
		$username = 'passpoint';
		$password = 'Pm7WE3#93dMd@12';
	
		$connection = new PDO("sqlsrv:server=$server;Database=$database", $username, $password);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		echo "Connection ".$connection;
	} catch (PDOException $e) {
		echo 'Connection failed: ' . $e->getMessage();
	}

	*/











			
			




	
	?>
<?php




require_once '../vendor/autoload.php';


// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
// $dotenv->load();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ );
//$dotenv->load();
$dotenv->safeLoad();





if( $_SERVER['APPLICATION_STATE']==="staging")
		{	
			//local 

			//local live 
			DEFINE ('DB_USER', $_SERVER['STAGING_DB_USER']);
			DEFINE ('DB_PASSWORD', $_SERVER['STAGING_DB_PASSWORD']);
			DEFINE ('DB_HOST',  $_SERVER['STAGING_DB_HOST']); //host name depends on server
			DEFINE ('DB_NAME', $_SERVER['STAGING_DB_NAME']);
			DEFINE ('DB_PORT',  $_SERVER['STAGING_DB_PORT']);


	
		
				
		}
		elseif( $_SERVER['APPLICATION_STATE']==="live")
		{
		
 				//local live 
				 DEFINE ('DB_USER', $_SERVER['LIVE_DB_USER']);
				 DEFINE ('DB_PASSWORD', $_SERVER['LIVE_DB_PASSWORD']);
				 DEFINE ('DB_HOST',  $_SERVER['LIVE_DB_HOST']); //host name depends on server
				 DEFINE ('DB_NAME', $_SERVER['LIVE_DB_NAME']);
				 DEFINE ('DB_PORT',  $_SERVER['LIVE_DB_PORT']);
			
		
		}

	

	$mysqli =mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME,DB_PORT);


	mysqli_query($mysqli,"SET NAMES 'utf8'");











	
	?>
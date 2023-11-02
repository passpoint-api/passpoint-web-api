<?php
 

include('middleware/cors.php');
include('database/connection.php');
include('middleware/auth.php');
include('database/queries.php');





		if( !isset($data->services) and !is_array($data->services)  and  !isset($data->submitType)  ){
			
			header( 'Content-Type: application/json; charset=utf-8');
			header('HTTP/1.0 400 Bad Request');
			
			$set=array('responseStatus'=>"40",'message' => "Please suppply the required fields",'status'=>0);
			
			$msg = json_encode($set);
			echo $msg;
			exit;
			
		}else{
			


		//retive all the entry from front-end


       


		$id =$USER_DATA['id'];




     
		try{



			$submitType = $data->submitType;

			if($submitType =="NEW"){



			foreach($data->services as $services){

				

				$serviceType = $services->serviceType;
				$serviceName = $services->serviceName;
				$serviceDesc = $services->serviceDesc;
				$featuredService = $services->featuredService;
				$serviceBanner = $services->serviceBanner;
				$servicePriceModel = $services->servicePriceModel;
				$serviceCurrency = $services->serviceCurrency; 

				//if PriceModel is commission get commission type , can b amountBased or percentBased
				$serviceCommissionType = $services->serviceCommissionType;
				
				
				
				$pricingType = $services->pricingType;
				$addVat = $services->addVat;
				

				$result = AddServices($mysqli, $serviceType, $serviceName, $serviceDesc, $featuredService, $serviceBanner, $servicePriceModel, $serviceCurrency, $pricingType, $addVat, $id );

				if($result['added']){

					$serviceId = $result['service_data']; 
				
					

					if(is_array($services->servicePrice)){
						//arrary of prices


						foreach($services->servicePrice as $prices){

							$priceName = $prices->cateoryName;
							$price = $prices->price;
					
	
							AddServicePricing($mysqli,$serviceId, $priceName, $price );

						}


					}elseif(is_numeric($services->servicePrice)){
						//single price to be given.

						$priceName = $servicePriceModel;
						$price = $services->servicePrice;

						AddServicePricing($mysqli, $serviceId, $priceName, $price );
					

					}else{


						header( 'Content-Type: application/json; charset=utf-8');
						header('HTTP/1.0 400 Bad Request');
						$set=array('message' => "Service Price Valid", 'code'=>400, 'responseStatus'=>"40", 'status'=>1);
			 
						$msg = json_encode($set);
						echo $msg;
						exit;

					}


				}else{


					header('Content-Type: application/json; charset=utf-8');
					header('HTTP/1.0 400 Bad Request');
					$set = ['message' => "Error Adding Services .".$e->getMessage(), 'code' => 400, 'responseStatus' => '40', 'status' => 0];
					$msg = json_encode($set);
					echo $msg;
					exit;



				}


				

			}








        	//$date = date('d')."-".date('m')."-".date('Y');


			header( 'Content-Type: application/json; charset=utf-8');
			header('HTTP/1.0 200 Success');
			$set=array('message' => "Services Added Successfully", 'code'=>200, 'responseStatus'=>"00", 'status'=>1);
 
			$msg = json_encode($set);
			echo $msg;
			exit;







		}elseif($submitType =="EDIT"){




			$count=0;
			foreach($data->services as $services){

				

				$serviceType = $services->serviceType;
				$serviceName = $services->serviceName;
				$serviceDesc = $services->serviceDesc;
				$featuredService = $services->featuredService;
				$serviceBanner = $services->serviceBanner;
				$servicePriceModel = $services->servicePriceModel;
				$serviceCurrency = $services->serviceCurrency; 
				
				$pricingType = $services->pricingType;
				$addVat = $services->addVat;
				
				$service_id = getServiceId($mysqli, $count, $id );

				$result = UpdateServices($mysqli, $serviceType, $serviceName, $serviceDesc, $featuredService, $serviceBanner, $servicePriceModel, $serviceCurrency, $pricingType, $addVat, $service_id );

				if($result['added']){

					$serviceId = $service_id; 
				
					

					if(is_array($services->servicePrice)){
						//arrary of prices


						$count=0;
						foreach($services->servicePrice as $prices){

							$priceName = $prices->cateoryName;
							$price = $prices->price;
					
							$servicePricingId =getServicePricingId($mysqli, $serviceId, $count);

							updateServicePricing($mysqli, $servicePricingId, $priceName, $price);

							$count++;

						}


					}elseif(is_numeric($services->servicePrice)){
						//single price to be given.

						$priceName = "Standard";
						$price = $services->servicePrice;
						$count ="0";
						$servicePricingId =getServicePricingId($mysqli, $serviceId, $count);


						updateServicePricing($mysqli, $servicePricingId, $priceName, $price );
					

					}else{


						header( 'Content-Type: application/json; charset=utf-8');
						header('HTTP/1.0 400 Bad Request');
						$set=array('message' => "Service Price Valid", 'code'=>400, 'responseStatus'=>"40", 'status'=>1);
			 
						$msg = json_encode($set);
						echo $msg;
						exit;

					}


				}else{


					header('Content-Type: application/json; charset=utf-8');
					header('HTTP/1.0 400 Bad Request');
					$set = ['message' => "Error Updating Services .", 'code' => 400, 'responseStatus' => '40', 'status' => 0];
					$msg = json_encode($set);
					echo $msg;
					exit;



				}


				$count++;

			}








        	//$date = date('d')."-".date('m')."-".date('Y');


			header( 'Content-Type: application/json; charset=utf-8');
			header('HTTP/1.0 200 Success');
			$set=array('message' => "Services Updated Successfully", 'code'=>200, 'responseStatus'=>"00", 'status'=>1);
 
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
		$set = ['message' => "Error Adding Services .".$e->getMessage(), 'code' => 400, 'responseStatus' => '40', 'status' => 0];
		$msg = json_encode($set);
		echo $msg;
		exit;
	}


		  
}







?>
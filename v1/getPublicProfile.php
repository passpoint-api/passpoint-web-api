<?php


include('middleware/cors.php');
include('database/connection.php');
include('middleware/auth.php');
include('database/queries.php');



 





	


		//retive all the entry from front-end


       

	   $desc = array();



		$id =$USER_DATA['id'];



     
		try{

			$result = getPublicProfileDetials($mysqli, $id);


			if($result['p_exists']){

				$row = $result['p_data'];


				
				

				//initailize stage to zero
				$profileStage =0;
				$isCompleted = false;


				//if logo is uploaded increament by 1
				if(!empty($row['logo'])){
					//first step
					$profileStage=1;
				}


				//if aboutBusiness is uploaded increment by 1
				if(!empty($row['aboutBusiness'])){
					//first step
					$profileStage=2;
				}



				//create business Identity Caterogy

				$info['businessIdentity'] = ["businessName"=>$USER_DATA['businessName'], 'logo' => $row['logo']];





					$descData = getPublicProfileDesc($mysqli, $id)['p_data'];

					foreach ($descData as $descItem) {
						$descList = []; // Initialize the descList array for each iteration

						$descList['businessDescId'] = $descItem['id'];
						$descList['businessHeadLine'] = $descItem['headline'];
						$descList['businessDesc'] = $descItem['service_description'];

						$desc[] = $descList; // Add the current descList to the desc array
					}


				

				$info["aboutBusiness"]=["aboutBusiness" => $row['aboutBusiness'], "desc"=>$desc]; 



				

					$socialData = getPublicProfileSocials($mysqli, $id)['p_data'];

					foreach ($socialData as $socialItem) {
						$soialList = []; // Initialize the descList array for each iteration

						$soialList['socialId'] = $socialItem['id'];
						$soialList['name'] = $socialItem['social_name'];
						$soialList['url'] = $socialItem['social_url'];

						$social[] = $soialList; // Add the current descList to the desc array
					}


			



				$info['contactInfo'] = ['companyEmail' =>$row['companyEmail'],
										'companyPhone' =>$row['companyPhone'],
										'companyAddress' =>$row['companyAddress'],
										'openingDay' =>$row['openingDay'],
										'closingDay' =>$row['closingDay'],
										'openingHour' =>$row['openingHour'],
										'closingHour' =>$row['closingHour'],
										'hasWebContact' =>$row['hasWebContact'],
										"socials" => $social
									];



				$serviceData = getPublicProfileServices($mysqli, $id)['p_data'];
				$services =[]; // Initialize the serviceList array for each service

				foreach ($serviceData as $serviceItem) {
					$serviceList = []; // Initialize the serviceList array for each service iteration
					$servicePrices = []; // Initialize the servicePrices array for each service iteration
				
					$serviceList['serviceId'] = $serviceItem['id'];
					$serviceList['serviceType'] = $serviceItem['serviceType'];
					$serviceList['serviceName'] = $serviceItem['service_name'];
					$serviceList['serviceDesc'] = $serviceItem['service_description'];
					$serviceList['featuredService'] = $serviceItem['isFeatured'];

					$serviceList['serviceBanner'] = $serviceItem['service_banner_url'];
					$serviceList['servicePriceModel'] = $serviceItem['priceModel'];
					$serviceList['serviceCurrency'] = $serviceItem['serviceCurrency'];


					$service_id = $serviceItem['id'];
					$servicePriceData =  getPublicServicePricing($mysqli, $service_id)['p_data'];
					foreach ($servicePriceData as $servicePriceItem) {
						$servicePrice = []; // Initialize the descList array for each iteration

						$servicePrice['servicePriceId'] = $servicePriceItem['id'];
						$servicePrice['cateoryName'] = $servicePriceItem['priceName'];
						$servicePrice['price'] = $servicePriceItem['price'];

						$servicePrices[] = $servicePrice; // Add the current descList to the desc array
					}

					if(count($servicePrices) ==1 and$servicePrices[0]['cateoryName']=="Standard"){
						$serviceList['servicePrice'] = $servicePrices[0]['price'];
					}else{
						$serviceList['servicePrice'] = $servicePrices;
					}
					
					$serviceList['addVat'] = $serviceItem['addVat'];
					$serviceList['pricingType'] = $serviceItem['priceType'];
					

					

					$services[] = $serviceList; // Add the current descList to the desc array
				}


				$info['services'] = $services;




				//if companyEmail is uploaded increment by 1
				if(count($services) >0){
					//first step
					$profileStage=3;
				
				}



				



				//if companyEmail is uploaded increment by 1
				if(!empty($row['companyEmail'])){
					//first step
					$profileStage=4;
					$isCompleted =true;
				}


			

				//get public profile stage
				$info['isCompleted'] =$isCompleted;
				$info['profileStage'] =$profileStage;




	



							header( 'Content-Type: application/json; charset=utf-8');
							header('HTTP/1.0 200 Success');
							$set=array('data' => $info, 'code'=>200, 'responseStatus'=>"00", 'status'=>1);
				
							$msg = json_encode($set);
							echo $msg;
							exit;


			}else{



				//$date = date('d')."-".date('m')."-".date('Y');


				header( 'Content-Type: application/json; charset=utf-8');
				header('HTTP/1.0 400 Bad Request');
				$set = ['message' => "Error Retriving Public Profile. ".$e->getMessage(), 'code' => 400, 'responseStatus' => '40', 'status' => 0];

				$msg = json_encode($set);
				echo $msg;
				exit;




			}

			
			

        	


		

		

	}catch (Exception $e) {

		

		header('Content-Type: application/json; charset=utf-8');
		header('HTTP/1.0 400 Bad Request');
		$set = ['message' => "Error Adding Profile Contacts. ".$e->getMessage(), 'code' => 400, 'responseStatus' => '40', 'status' => 0];
		$msg = json_encode($set);
		echo $msg;
		exit;
	}


		  








?>
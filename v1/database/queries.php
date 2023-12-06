<?php

//all Queries

function CheckUserEmailQuery($mysqli, $email, $exist){

	$stmt = $mysqli->prepare("SELECT id  FROM `users` WHERE email=? ");
	$stmt->bind_param("s", $email);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();

	if($exist ==1){
		//exist 1, user not existing

		if($result->num_rows < 1 ){
			return true;
		}else{
			return false;
		}

	}else{
		//exist 2, user is existing

		if($result->num_rows == 1 ){
			return true;
		}else{
			return false;
		}


	}
	

}





function GetOtp($mysqli, $email){

	$stmt = $mysqli->prepare("SELECT otp  FROM `users` WHERE email=? ");
	$stmt->bind_param("s", $email);
	$stmt->execute();
	$result = $stmt->get_result();
	
            
	// Fetch the user data as an associative array
	$user_data = $result->fetch_assoc();

	
	$stmt->close();
            if ($user_data) {
                // User exists
                return [
                    'user_exists' => true,
                    'user_data' => $user_data,
                ];
            } else {
                // User not found
                return [
                    'user_exists' => false,
                    'user_data' => null,
                    'error' => 'User not found in the database.'
                ];
            }
	

}



function LoginUser($mysqli, $email)
{
    $query = "SELECT * FROM `users` WHERE email=?";
    
    // Prepare the statement
    $stmt = $mysqli->prepare($query);
    
    if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("s", $email);

        // Execute the statement
        if ($stmt->execute()) {
            // Get the result set as an associative array
            $result = $stmt->get_result();
            
            // Fetch the user data as an associative array
            $user_data = $result->fetch_assoc();

            $stmt->close();
            
            if ($user_data) {
                // User exists
                return [
                    'user_exists' => true,
                    'user_data' => $user_data,
                ];
            } else {
                // User not found
                return [
                    'user_exists' => false,
                    'user_data' => null,
                    'error' => 'User not found in the database.'
                ];
            }
        } else {
            // Query execution failed
            return [
                'user_exists' => false,
                'user_data' => null,
                'error' => 'Query execution failed: ' . mysqli_error($mysqli)
            ];
        }
    } else {
        // Statement preparation failed
        return [
            'user_exists' => false,
            'user_data' => null,
            'error' => 'Statement preparation failed: ' . mysqli_error($mysqli)
        ];
    }
}





function CreateUserBussinesInfoQuery($mysqli,  $businessType, $businessIndustry, $email, $hashpassword, $businessName, $rcNumber,$date, $userType, $regStage){

	$query = "INSERT INTO `users`(`businessType`, `businessIndustry`, `email`, `password`, `bussinesName`, `rcNumber`, `date_created`, `userType`, `regStage`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Prepare the statement
$stmt = $mysqli->prepare($query);

if ($stmt) {
	
    // Bind the parameters
    $stmt->bind_param("sssssssss",  $businessType, $businessIndustry, $email, $hashpassword, $businessName, $rcNumber, $date, $userType, $regStage);

    // Execute the statement
    if ($stmt->execute()) {
		$stmt->close();
        // Insertion was successful
        // You can add your success handling here
		return true;
    } else { 
		$stmt->close();
        // Insertion failed
        // You can add your error handling here
		return false;
    }

    // Close the statement
   
} else {
	$stmt->close();
    // Statement preparation failed
    // You can add your error handling here
	return false;
}
	
	
}




function ValidateOtpQuery($mysqli, $email, $otp) {
    $query = "SELECT otp FROM `users` WHERE email=?";
    
    // Prepare the statement
    $stmt = $mysqli->prepare($query);
    
    if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("s", $email);

		$db_otp = null;

        // Execute the statement
        if ($stmt->execute()) {
            // Bind the result column to a variable
            $stmt->bind_result($db_otp);
            
            // Fetch the result
            if ($stmt->fetch()) {
                // OTP exists, compare it with the provided OTP
                if ($db_otp === $otp) {
                    $stmt->close();
                    return true; // OTP is valid
                } else {
                    $stmt->close();
                    return false; // OTP is not valid
                }
            } else {
                $stmt->close();
                return false; // User not found or OTP not set
            }
        } else {
            $stmt->close();
            // Query execution failed
            // You can add your error handling here
            return false;
        }
    } else {
        // Statement preparation failed
        // You can add your error handling here
        return false;
    }
}





function CreateUserPersonalInfoQuery($mysqli, $firstName, $lastName,  $phone, $regStage, $otp, $email){


	$query = "UPDATE `users` SET `firstname`=?, `lastname`=?, `phone`=?, `regStage`=?, otp=?  WHERE `email`=? ";

	// Prepare the statement
	$stmt = $mysqli->prepare($query);
	
	if ($stmt) {
		
		// Bind the parameters
		$stmt->bind_param("ssssss",  $firstName, $lastName, $phone, $regStage, $otp, $email);
	
		// Execute the statement
		if ($stmt->execute()) {
			
			$mysqli->commit();

			 // echo "Error: " . $stmt->error;

			$stmt->close();
			// update was successful
			// You can add your success handling here
			return true;
		} else { 

			echo "Error: " . $stmt->error;

			$stmt->close();
			// Insertion failed
			// You can add your error handling here
			return false;
		}
	
		// Close the statement
	   
	} else {

		echo "Error: " . $stmt->error;


		$stmt->close();
		// Statement preparation failed
		// You can add your error handling here
		return false;
	}
		




}



function CreateUserBusinessAddressQuery($mysqli, $address, $state, $lga, $country, $regStage, $email){

	$query = "UPDATE `users` SET `address`=?, `state`=?, `lga`=?, `country`=?, `regStage`=?  WHERE `email`=? ";

	// Prepare the statement
	$stmt = $mysqli->prepare($query);
	
	if ($stmt) {
		
		// Bind the parameters
		$stmt->bind_param("ssssss",  $address, $state, $lga, $country, $regStage, $email);
	
		// Execute the statement
		if ($stmt->execute()) {
			
			$mysqli->commit();

			 // echo "Error: " . $stmt->error;

			$stmt->close();
			// update was successful
			// You can add your success handling here
			return true;
		} else { 

		

			$stmt->close();
			// Insertion failed
			// You can add your error handling here
			return false;
		}
	
		// Close the statement
	   
	} else {




		$stmt->close();
		// Statement preparation failed
		// You can add your error handling here
		return false;
	}
		



}






function VerifyOtpUpdateStatusQuery($mysqli, $merchantId, $apiKey,  $email, $otpType){

	if($otpType =="accountVerification"){


	$query = "UPDATE `users` SET `status`=?, `merchant_id`=?, `otp`=?, `api_key`=?  WHERE `email`=? ";

	// Prepare the statement
	$stmt = $mysqli->prepare($query);
	
	if ($stmt) {

		$status = 1;
		$otp = "";
		
		// Bind the parameters
		$stmt->bind_param("sssss",  $status, $merchantId, $otp, $apiKey, $email);
	
		// Execute the statement
		if ($stmt->execute()) {
			
			$mysqli->commit();

			 // echo "Error: " . $stmt->error;

			$stmt->close();
			// update was successful
			// You can add your success handling here
			return true;
		} else { 

		

			$stmt->close();
			// Insertion failed
			// You can add your error handling here
			return false;
		}
	
		// Close the statement
	   
	} else {




		$stmt->close();
		// Statement preparation failed
		// You can add your error handling here
		return false;
	}

}elseif($otpType =="passwordReset" ){

//password reser

	$query = "UPDATE `users` SET `otp`=?, `otpStatus`=?  WHERE `email`=? ";

	// Prepare the statement
	$stmt = $mysqli->prepare($query);
	
	if ($stmt) {

		$status = 1;
		$otp = "";
		
		// Bind the parameters
		$stmt->bind_param("sss",  $otp, $status, $email);
	
		// Execute the statement
		if ($stmt->execute()) {
			
			$mysqli->commit();

			 // echo "Error: " . $stmt->error;

			$stmt->close();
			// update was successful
			// You can add your success handling here
			return true;
		} else { 

		

			$stmt->close();
			// Insertion failed
			// You can add your error handling here
			return false;
		}
	
		// Close the statement
	   
	} else {




		$stmt->close();
		// Statement preparation failed
		// You can add your error handling here
		return false;
	}



}

		



}





function ForgotPasswordOtpQuery($mysqli, $otp, $email){


	$query = "UPDATE `users` SET  otp=?  WHERE `email`=? ";

	// Prepare the statement
	$stmt = $mysqli->prepare($query);
	
	if ($stmt) {
		
		// Bind the parameters
		$stmt->bind_param("ss",  $otp, $email);
	
		// Execute the statement
		if ($stmt->execute()) {
			
			$mysqli->commit();

			 // echo "Error: " . $stmt->error;

			$stmt->close();
			// update was successful
			// You can add your success handling here
			return true;
		} else { 

			echo "Error: " . $stmt->error;

			$stmt->close();
			// Insertion failed
			// You can add your error handling here
			return false;
		}
	
		// Close the statement
	   
	} else {

		echo "Error: " . $stmt->error;


		$stmt->close();
		// Statement preparation failed
		// You can add your error handling here
		return false;
	}
		




}










function ResetPasswordOtpQuery($mysqli, $password, $email){


	$query = "UPDATE `users` SET  `password`=?  WHERE `email`=? ";

	// Prepare the statement
	$stmt = $mysqli->prepare($query);
	
	if ($stmt) {
		
		// Bind the parameters
		$stmt->bind_param("ss",  $password, $email);
	
		// Execute the statement
		if ($stmt->execute()) {
			
			$mysqli->commit();

			 // echo "Error: " . $stmt->error;

			$stmt->close();
			// update was successful
			// You can add your success handling here
			return true;
		} else { 

			echo "Error: " . $stmt->error;

			$stmt->close();
			// Insertion failed
			// You can add your error handling here
			return false;
		}
	
		// Close the statement
	   
	} else {

		echo "Error: " . $stmt->error;


		$stmt->close();
		// Statement preparation failed
		// You can add your error handling here
		return false;
	}
		




}








function CreateIndividualPersonalInfoQuery($mysqli, $firstName, $lastName,  $phone, $email, $password,  $otp, $regStage, $userType){


	$query = "INSERT INTO `users`(`firstname`, `lastname`, `phone`, `email`, `password`, `date_created`, `otp`, `regStage`, `userType`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";


	// Prepare the statement
	$stmt = $mysqli->prepare($query);
	
	if ($stmt) {

		$date = date('d')."-".date('m')."-".date('Y');
		
		// Bind the parameters
		$stmt->bind_param("sssssssss",  $firstName, $lastName, $phone, $email, $password, $date, $otp, $regStage, $userType);
	
		// Execute the statement
		if ($stmt->execute()) {
			
			$mysqli->commit();

			 // echo "Error: " . $stmt->error;

			$stmt->close();
			// update was successful
			// You can add your success handling here
			return true;
		} else { 

			echo "Error: " . $stmt->error;

			$stmt->close();
			// Insertion failed
			// You can add your error handling here
			return false;
		}
	
		// Close the statement
	   
	} else {

		echo "Error: " . $stmt->error;


		$stmt->close();
		// Statement preparation failed
		// You can add your error handling here
		return false;
	}
		




}










function CreateIndividualBussinesInfoQuery($mysqli, $businessName, $businessIndustry, $regStage, $email){

	$query = "UPDATE `users` SET `bussinesName`=?, `businessIndustry`=?, `regStage`=?    WHERE `email`=?";

// Prepare the statement
$stmt = $mysqli->prepare($query);

if ($stmt) {
	
    // Bind the parameters
    $stmt->bind_param("ssss",  $businessName, $businessIndustry, $regStage, $email);

    // Execute the statement
    if ($stmt->execute()) {
		$stmt->close();
        // Insertion was successful
        // You can add your success handling here
		return true;
    } else { 
		$stmt->close();
        // Insertion failed
        // You can add your error handling here
		return false;
    }

    // Close the statement
   
} else {
	$stmt->close();
    // Statement preparation failed
    // You can add your error handling here
	return false;
}
	
	
}





function getServices($mysqli){

	$query = "SELECT * FROM MainServices  WHERE `status`=1";

// Prepare the statement
$stmt = $mysqli->prepare($query);

if ($stmt) {
	
    // Bind the parameters

    // Execute the statement
    if ($stmt->execute()) {
		$result = $stmt->get_result();

		// Create an array to store the results
		$services = array();

		// Fetch the results
		while ($row = $result->fetch_assoc()) {
			// Add each row to the results array
			$services[] = $row;
		}

		$stmt->close();
		
		// Return the array of services
		return $services;
    } else { 
		$stmt->close();
        // Insertion failed
        // You can add your error handling here
		return false;
    }

    // Close the statement
   
} else {
	$stmt->close();
    // Statement preparation failed
    // You can add your error handling here
	return false;
}
	
	

}



function uploadBusinessLogo($mysqli,$id, $logo){


	$query = "INSERT INTO `publicProfile`(`userid`, `logo`) VALUES(?, ?)";

// Prepare the statement
$stmt = $mysqli->prepare($query);

if ($stmt) {
	
    // Bind the parameters
    $stmt->bind_param("ss",  $id, $logo);

    // Execute the statement
    if ($stmt->execute()) {
		$stmt->close();
        // Insertion was successful
        // You can add your success handling here
		return true;
    } else { 
		$stmt->close();
        // Insertion failed
        // You can add your error handling here
		return false;
    }

    // Close the statement
   
} else {
	$stmt->close();
    // Statement preparation failed
    // You can add your error handling here
	return false;
}


}



function getPublicProfileId($mysqli, $userid) {
    // Prepare the statement
    $stmt = $mysqli->prepare("SELECT id FROM `publicProfile` WHERE userid=? ");
    
    if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("s", $userid);

        // Execute the statement
        if ($stmt->execute()) {
            // Get the result set
            $result = $stmt->get_result();

            // Fetch the result
            $id = null;
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $id = $row['id'];
            }

            $stmt->close();
            
            return $id; // Return the profileId
        } else {
            $stmt->close();
            // Query execution failed
            // You can add your error handling here
            return false;
        }
    } else {
        // Statement preparation failed
        // You can add your error handling here
        return false;
    }
}




function addProfileDesc($mysqli , $businessDesc, $businessHeadLine, $id ){

	$profileid = getPublicProfileId($mysqli , $id);



	$query = "INSERT INTO `profileDesc`(`profile_id`, `service_description`, `headline`) VALUES(?, ?, ?)";

// Prepare the statement
$stmt = $mysqli->prepare($query);

if ($stmt) {
	
    // Bind the parameters
    $stmt->bind_param("sss",  $profileid, $businessDesc, $businessHeadLine);

    // Execute the statement
    if ($stmt->execute()) {
		$stmt->close();
        // Insertion was successful
        // You can add your success handling here
		return true;
    } else { 
		$stmt->close();
        // Insertion failed
        // You can add your error handling here
		return false;
    }

    // Close the statement
   
} else {
	$stmt->close();
    // Statement preparation failed
    // You can add your error handling here
	return false;
}


	
}




function CheckApiKey($mysqli, $apikey){

	$query = "SELECT * FROM `users` WHERE api_key=?";
    
    // Prepare the statement
    $stmt = $mysqli->prepare($query);
    
    if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("s", $apikey);

        // Execute the statement
        if ($stmt->execute()) {
            // Get the result set as an associative array
            $result = $stmt->get_result();
            
            // Fetch the user data as an associative array
            $user_data = $result->fetch_assoc();

            $stmt->close();
            
            if ($user_data) {
                // User exists
                return [
                    'api_exists' => true,
                    'user_data' => $user_data,
                ];
            } else {
                // User not found
                return [
                    'api_exists' => false,
                    'user_data' => null,
                    'error' => 'User not found in the database.'
                ];
            }
        } else {
            // Query execution failed
            return [
                'api_exists' => false,
                'user_data' => null,
                'error' => 'Query execution failed: ' . mysqli_error($mysqli)
            ];
        }
    } else {
        // Statement preparation failed
        return [
            'api_exists' => false,
            'user_data' => null,
            'error' => 'Statement preparation failed: ' . mysqli_error($mysqli)
        ];
    }

}







function uploadProfileImage($mysqli, $id, $logo) {
    // Check if the record with the given userid already exists
    $checkQuery = "SELECT id FROM `profileimg` WHERE `userid` = ?";
    $checkStmt = $mysqli->prepare($checkQuery);
    $checkStmt->bind_param("s", $id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    $checkStmt->close();

    if ($checkResult->num_rows > 0) {
        // If the record exists, update it
        $updateQuery = "UPDATE `profileimg` SET `logo` = ? WHERE `userid` = ?";
        $updateStmt = $mysqli->prepare($updateQuery);
        $updateStmt->bind_param("ss", $logo, $id);

        if ($updateStmt->execute()) {
            $updateStmt->close();
            return true; // Update successful
        } else {
            $updateStmt->close();
            return false; // Update failed
        }
    } else {
        // If the record doesn't exist, insert a new one
        $insertQuery = "INSERT INTO `profileimg`(`userid`, `logo`) VALUES (?, ?)";
        $insertStmt = $mysqli->prepare($insertQuery);
        $insertStmt->bind_param("ss", $id, $logo);

        if ($insertStmt->execute()) {
            $insertStmt->close();
            return true; // Insertion successful
        } else {
            $insertStmt->close();
            return false; // Insertion failed
        }
    }
}






function addAboutBusiness($mysqli, $aboutBusiness, $id ){


	$profileid = getPublicProfileId($mysqli , $id);


	$query = "UPDATE `publicProfile` SET `aboutBusiness`=? WHERE id=? ";

// Prepare the statement
$stmt = $mysqli->prepare($query);

if ($stmt) {
	
    // Bind the parameters
    $stmt->bind_param("ss",  $aboutBusiness, $profileid);

    // Execute the statement
    if ($stmt->execute()) {
		$stmt->close();
        // Insertion was successful
        // You can add your success handling here
		return true;
    } else { 
		$stmt->close();
        // Insertion failed
        // You can add your error handling here
		return false;
    }

    // Close the statement
   
} else {
	$stmt->close();
    // Statement preparation failed
    // You can add your error handling here
	return false;
}





}





function updateProfileContact($mysqli, $companyEmail, $companyPhone, $companyAddress, $openingDay, $closingDay, $openingHour, $closingHour, $hasWebContact , $id){



	$profileid = getPublicProfileId($mysqli , $id);


	$query = "UPDATE `publicProfile` SET companyEmail=?, companyPhone=?, companyAddress=?, openingDay=?, closingDay=?, openingHour=?, closingHour=?, hasWebContact=? WHERE id=? ";

// Prepare the statement
$stmt = $mysqli->prepare($query);

if ($stmt) {
	
    // Bind the parameters
    $stmt->bind_param("sssssssss", $companyEmail, $companyPhone, $companyAddress, $openingDay, $closingDay, $openingHour, $closingHour, $hasWebContact , $profileid);

    // Execute the statement
    if ($stmt->execute()) {
		$stmt->close();
        // Insertion was successful
        // You can add your success handling here
		return true;
    } else { 
		$stmt->close();
        // Insertion failed
        // You can add your error handling here
		return false;
    }

    // Close the statement
   
} else {
	$stmt->close();
    // Statement preparation failed
    // You can add your error handling here
	return false;
}






}






function addProfileSocial($mysqli , $socialName, $socialUrl, $id ){


	$profileid = getPublicProfileId($mysqli , $id);


	$query = "INSERT INTO `profileContact`(`profile_id`, `social_url`, `social_name`) VALUES(?, ?, ?)";

// Prepare the statement
$stmt = $mysqli->prepare($query);

if ($stmt) {
	
    // Bind the parameters
    $stmt->bind_param("sss",  $profileid, $socialUrl, $socialName);

    // Execute the statement
    if ($stmt->execute()) {
		$stmt->close();
        // Insertion was successful
        // You can add your success handling here
		return true;
    } else { 
		$stmt->close();
        // Insertion failed
        // You can add your error handling here
		return false;
    }

    // Close the statement
   
} else {
	$stmt->close();
    // Statement preparation failed
    // You can add your error handling here
	return false;
}





}





function AddServices($mysqli, $serviceType, $serviceName, $serviceDesc, $featuredService, $serviceBanner, $servicePriceModel, $serviceCurrency, $pricingType, $addVat, $id) {

    $profileid = getPublicProfileId($mysqli, $id);

    $query = "INSERT INTO `services`(`profile_id`, `service_name`, `service_description`, `service_banner_url`, `isFeatured`, `priceType`, `addVat`, `priceModel`, `serviceType`, `serviceCurrency`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $mysqli->prepare($query);

    if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("ssssssssss", $profileid, $serviceName, $serviceDesc, $serviceBanner, $featuredService, $pricingType, $addVat, $servicePriceModel, $serviceType, $serviceCurrency);

        // Execute the statement
        if ($stmt->execute()) {
            $serviceId = $mysqli->insert_id; // Get the ID of the last inserted row
            $stmt->close();

            // Insertion was successful, return the ID of the inserted service
            return [
                'added' => true,
                'service_data' =>  $serviceId,
                'error' => null
            ];
        } else {
            $stmt->close();

            // Insertion failed
            return [
                'added' => false,
                'service_data' => null,
                'error' => 'Insertion failed: ' . mysqli_error($mysqli)
            ];
        }
    } else {
        // Statement preparation failed
        return [
            'added' => false,
            'service_data' => null,
            'error' => 'Statement preparation failed: ' . mysqli_error($mysqli)
        ];
    }
}






function AddServicePricing($mysqli,$serviceId, $priceName, $price ){


	$query = "INSERT INTO `servicePricing`(`service_id`, `priceName`, `price`) VALUES(?, ?, ?)";

	// Prepare the statement
	$stmt = $mysqli->prepare($query);

	if ($stmt) {
		
		// Bind the parameters
		$stmt->bind_param("sss",  $serviceId, $priceName, $price);

		// Execute the statement
		if ($stmt->execute()) {
			$stmt->close();
			// Insertion was successful
			// You can add your success handling here
			return true;
		} else { 
			$stmt->close();
			// Insertion failed
			// You can add your error handling here
			return false;
		}

		// Close the statement
	
	} else {
		$stmt->close();
		// Statement preparation failed
		// You can add your error handling here
		return false;
	}




}






function uploadKycProofIdividualIdentity($mysqli, $documentType, $documentFile, $userid) {
    // Check if the record with the given userid exists
    $checkQuery = "SELECT * FROM `kyc` WHERE `userid` = ?";
    $checkStmt = $mysqli->prepare($checkQuery);

    if ($checkStmt) {
        $checkStmt->bind_param("s", $userid);
        $checkStmt->execute();
        $checkStmt->store_result();

        // If the record exists, update it; otherwise, insert a new record
        if ($checkStmt->num_rows > 0) {
            $updateQuery = "UPDATE `kyc` SET `identityDocumentType` = ?, `identityDocumentFile` = ? WHERE `userid` = ?";
            $updateStmt = $mysqli->prepare($updateQuery);
            $updateStmt->bind_param("sss", $documentType, $documentFile, $userid);
            
            if ($updateStmt->execute()) {
                $updateStmt->close();
                $checkStmt->close();
                return true; // Update successful
            } else {
                $updateStmt->close();
                $checkStmt->close();
                return false; // Update failed
            }
        } else {
            $checkStmt->close();

            $insertQuery = "INSERT INTO `kyc` (`identityDocumentType`, `identityDocumentFile`, `userid`) VALUES (?, ?, ?)";
            $insertStmt = $mysqli->prepare($insertQuery);
            $insertStmt->bind_param("sss", $documentType, $documentFile, $userid);

            if ($insertStmt->execute()) {
                $insertStmt->close();
                return true; // Insertion successful
            } else {
                $insertStmt->close();
                return false; // Insertion failed
            }
        }
    } else {
        return false; // Statement preparation failed
    }
}





function uploadKycProofIdividualAddress($mysqli, $documentType, $documentFile, $userid){



	$query = "UPDATE `kyc` SET `addressDocumentType`=?, `addressDocumentFile`=?  WHERE `userid`=? ";

    // Prepare the statement
    $stmt = $mysqli->prepare($query);

    if ($stmt) {
        
        // Bind the parameters
        $stmt->bind_param("sss",  $documentType, $documentFile, $userid);

        // Execute the statement
        if ($stmt->execute()) {
            $stmt->close();
            // Insertion was successful
            // You can add your success handling here
            return true;
        } else { 
            $stmt->close();
            // Insertion failed
            // You can add your error handling here
            return false;
        }

        // Close the statement
    
    }else{
        $stmt->close();
        // Statement preparation failed
        // You can add your error handling here
        return false;
    }





}








function editBusinessLogo($mysqli,$id, $logo){


	$query = "UPDATE `publicProfile` SET `logo`=? WHERE `userid`=? ";

// Prepare the statement
$stmt = $mysqli->prepare($query);

if ($stmt) {
	
    // Bind the parameters
    $stmt->bind_param("ss",  $logo, $id);

    // Execute the statement
    if ($stmt->execute()) {
		$stmt->close();
        // Insertion was successful
        // You can add your success handling here
		return true;
    } else { 
		$stmt->close();
        // Insertion failed
        // You can add your error handling here
		return false;
    }

    // Close the statement
   
} else {
	$stmt->close();
    // Statement preparation failed
    // You can add your error handling here
	return false;
}


}








function getPublicProfileDetials($mysqli, $userid)
{
    $query = "SELECT * FROM `publicProfile` WHERE userid=?";
    
    // Prepare the statement
    $stmt = $mysqli->prepare($query);
    
    if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("s", $userid);

        // Execute the statement
        if ($stmt->execute()) {
            // Get the result set as an associative array
            $result = $stmt->get_result();
            
            // Fetch the user data as an associative array
            $user_data = $result->fetch_assoc();

            $stmt->close();
            
            if ($user_data) {
                // User exists
                return [
                    'p_exists' => true,
                    'p_data' => $user_data,
                ];
            } else {
                // User not found
                return [
                    'p_exists' => false,
                    'p_data' => null,
                    'error' => 'User not found in the database.'
                ];
            }
        } else {
            // Query execution failed
            return [
                'p_exists' => false,
                'p_data' => null,
                'error' => 'Query execution failed: ' . mysqli_error($mysqli)
            ];
        }
    } else {
        // Statement preparation failed
        return [
            'p_exists' => false,
            'p_data' => null,
            'error' => 'Statement preparation failed: ' . mysqli_error($mysqli)
        ];
    }
}






function getPublicProfileDesc($mysqli, $userid) {
    $profileid = getPublicProfileId($mysqli, $userid);
    $query = "SELECT * FROM `profileDesc` WHERE profile_id=?";
    
    // Prepare the statement
    $stmt = $mysqli->prepare($query);
    
    if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("s", $profileid);

        // Execute the statement
        if ($stmt->execute()) {
            // Get the result set
            $result = $stmt->get_result();
            
            // Fetch the rows and store them in an array
            $profileData = [];
            while ($row = $result->fetch_assoc()) {
                $profileData[] = $row;
            }

            $stmt->close();

            if (!empty($profileData)) {
                // User exists, return the profile data array
                return [
                    'p_exists' => true,
                    'p_data' => $profileData,
                ];
            } else {
                // User not found
                return [
                    'p_exists' => false,
                    'p_data' => null,
                    'error' => 'User not found in the database.'
                ];
            }
        } else {
            // Query execution failed
            return [
                'p_exists' => false,
                'p_data' => null,
                'error' => 'Query execution failed: ' . mysqli_error($mysqli)
            ];
        }
    } else {
        // Statement preparation failed
        return [
            'p_exists' => false,
            'p_data' => null,
            'error' => 'Statement preparation failed: ' . mysqli_error($mysqli)
        ];
    }

}








function getPublicProfileSocials($mysqli, $userid){


    $profileid = getPublicProfileId($mysqli, $userid);
    $query = "SELECT * FROM `profileContact` WHERE profile_id=?";
    
    // Prepare the statement
    $stmt = $mysqli->prepare($query);
    
    if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("s", $profileid);

        // Execute the statement
        if ($stmt->execute()) {
            // Get the result set
            $result = $stmt->get_result();
            
            // Fetch the rows and store them in an array
            $profileData = [];
            while ($row = $result->fetch_assoc()) {
                $profileData[] = $row;
            }

            $stmt->close();

            if (!empty($profileData)) {
                // User exists, return the profile data array
                return [
                    'p_exists' => true,
                    'p_data' => $profileData,
                ];
            } else {
                // User not found
                return [
                    'p_exists' => false,
                    'p_data' => null,
                    'error' => 'User not found in the database.'
                ];
            }
        } else {
            // Query execution failed
            return [
                'p_exists' => false,
                'p_data' => null,
                'error' => 'Query execution failed: ' . mysqli_error($mysqli)
            ];
        }
    } else {
        // Statement preparation failed
        return [
            'p_exists' => false,
            'p_data' => null,
            'error' => 'Statement preparation failed: ' . mysqli_error($mysqli)
        ];
    }



}







function getPublicProfileServices($mysqli, $userid){


    $profileid = getPublicProfileId($mysqli, $userid);

    $query = "SELECT * FROM `services` WHERE profile_id=?";
    
    // Prepare the statement
    $stmt = $mysqli->prepare($query);
    
    if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("s", $profileid);

        // Execute the statement
        if ($stmt->execute()) {
            // Get the result set
            $result = $stmt->get_result();
            
            // Fetch the rows and store them in an array
            $profileData = [];
            while ($row = $result->fetch_assoc()) {
                $profileData[] = $row;
            }

            $stmt->close();

            if (!empty($profileData)) {
                // User exists, return the profile data array
                return [
                    'p_exists' => true,
                    'p_data' => $profileData,
                ];
            } else {
                // User not found
                return [
                    'p_exists' => false,
                    'p_data' => null,
                    'error' => 'User not found in the database.'
                ];
            }
        } else {
            // Query execution failed
            return [
                'p_exists' => false,
                'p_data' => null,
                'error' => 'Query execution failed: ' . mysqli_error($mysqli)
            ];
        }
    } else {
        // Statement preparation failed
        return [
            'p_exists' => false,
            'p_data' => null,
            'error' => 'Statement preparation failed: ' . mysqli_error($mysqli)
        ];
    }




}





function getPublicServicePricing($mysqli, $service_id){

    $query = "SELECT * FROM `servicePricing` WHERE service_id=?";
    
    // Prepare the statement
    $stmt = $mysqli->prepare($query);
    
    if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("s", $service_id);

        // Execute the statement
        if ($stmt->execute()) {
            // Get the result set
            $result = $stmt->get_result();
            
            // Fetch the rows and store them in an array
            $profileData = [];
            while ($row = $result->fetch_assoc()) {
                $profileData[] = $row;
            }

            $stmt->close();

            if (!empty($profileData)) {
                // User exists, return the profile data array
                return [
                    'p_exists' => true,
                    'p_data' => $profileData,
                ];
            } else {
                // User not found
                return [
                    'p_exists' => false,
                    'p_data' => null,
                    'error' => 'User not found in the database.'
                ];
            }
        } else {
            // Query execution failed
            return [
                'p_exists' => false,
                'p_data' => null,
                'error' => 'Query execution failed: ' . mysqli_error($mysqli)
            ];
        }
    } else {
        // Statement preparation failed
        return [
            'p_exists' => false,
            'p_data' => null,
            'error' => 'Statement preparation failed: ' . mysqli_error($mysqli)
        ];
    }




}












function updateProfileDesc($mysqli , $businessDesc, $businessHeadLine, $desc_id ){


	$query = "UPDATE`profileDesc` SET `service_description`=?, `headline`=? WHERE id=?";

// Prepare the statement
$stmt = $mysqli->prepare($query);

if ($stmt) {
	
    // Bind the parameters
    $stmt->bind_param("sss", $businessDesc, $businessHeadLine, $desc_id );

    // Execute the statement
    if ($stmt->execute()) {
		$stmt->close();
        // Insertion was successful
        // You can add your success handling here
		return true;
    } else { 
		$stmt->close();
        // Insertion failed
        // You can add your error handling here
		return false;
    }

    // Close the statement
   
} else {
	$stmt->close();
    // Statement preparation failed
    // You can add your error handling here
	return false;
}




}




function  getDescId($mysqli, $count, $id ){

    $profileid = getPublicProfileId($mysqli, $id);


  // Prepare the statement
  $stmt = $mysqli->prepare("SELECT id FROM `profileDesc` WHERE profile_id=? LIMIT $count ,1");
    
  if ($stmt) {
      // Bind the parameters
      $stmt->bind_param("s", $profileid);

      // Execute the statement
      if ($stmt->execute()) {
          // Get the result set
          $result = $stmt->get_result();

          // Fetch the result
          $id = null;
          if ($result->num_rows > 0) {
              $row = $result->fetch_assoc();
              $id = $row['id'];
          }

          $stmt->close();
          
          return $id; // Return the profileId
      } else {
          $stmt->close();
          // Query execution failed
          // You can add your error handling here
          return false;
      }
  } else {
      // Statement preparation failed
      // You can add your error handling here
      return false;
  }


}





function getSocialId($mysqli, $count, $id ){


    $profileid = getPublicProfileId($mysqli, $id);


  // Prepare the statement
  $stmt = $mysqli->prepare("SELECT id FROM `profileContact` WHERE profile_id=? LIMIT $count ,1");
    
  if ($stmt) {
      // Bind the parameters
      $stmt->bind_param("s", $profileid);

      // Execute the statement
      if ($stmt->execute()) {
          // Get the result set
          $result = $stmt->get_result();

          // Fetch the result
          $id = null;
          if ($result->num_rows > 0) {
              $row = $result->fetch_assoc();
              $id = $row['id'];
          }

          $stmt->close();
          
          return $id; // Return the profileId
      } else {
          $stmt->close();
          // Query execution failed
          // You can add your error handling here
          return false;
      }
  } else {
      // Statement preparation failed
      // You can add your error handling here
      return false;
  }


}





function updateProfileSocial($mysqli , $socialName, $socialUrl, $social_id ){



	$query = "UPDATE `profileContact` SET  `social_url`=?, `social_name`=? WHERE id=?";

            // Prepare the statement
            $stmt = $mysqli->prepare($query);

            if ($stmt) {
                
                // Bind the parameters
                $stmt->bind_param("sss",  $socialUrl, $socialName, $social_id );

                // Execute the statement
                if ($stmt->execute()) {
                    $stmt->close();
                    // Insertion was successful
                    // You can add your success handling here
                    return true;
                } else { 
                    $stmt->close();
                    // Insertion failed
                    // You can add your error handling here
                    return false;
                }

                // Close the statement
            
            } else {
                $stmt->close();
                // Statement preparation failed
                // You can add your error handling here
                return false;
            }






}






function getServiceId($mysqli, $count, $id ){


    $profileid = getPublicProfileId($mysqli, $id);


  // Prepare the statement
  $stmt = $mysqli->prepare("SELECT id FROM `services` WHERE profile_id=? LIMIT $count ,1");
    
  if ($stmt) {
      // Bind the parameters
      $stmt->bind_param("s", $profileid);

      // Execute the statement
      if ($stmt->execute()) {
          // Get the result set
          $result = $stmt->get_result();

          // Fetch the result
          $id = null;
          if ($result->num_rows > 0) {
              $row = $result->fetch_assoc();
              $id = $row['id'];
          }

          $stmt->close();
          
          return $id; // Return the profileId
      } else {
          $stmt->close();
          // Query execution failed
          // You can add your error handling here
          return false;
      }
  } else {
      // Statement preparation failed
      // You can add your error handling here
      return false;
  }


}




function getServicePricingId($mysqli, $serviceId, $count){




  // Prepare the statement
  $stmt = $mysqli->prepare("SELECT id FROM `servicePricing` WHERE service_id=? LIMIT $count ,1");
    
  if ($stmt) {
      // Bind the parameters
      $stmt->bind_param("s", $serviceId);

      // Execute the statement
      if ($stmt->execute()) {
          // Get the result set
          $result = $stmt->get_result();

          // Fetch the result
          $id = null;
          if ($result->num_rows > 0) {
              $row = $result->fetch_assoc();
              $id = $row['id'];
          }

          $stmt->close();
          
          return $id; // Return the profileId
      } else {
          $stmt->close();
          // Query execution failed
          // You can add your error handling here
          return false;
      }
  } else {
      // Statement preparation failed
      // You can add your error handling here
      return false;
  }





}




function  UpdateServices($mysqli, $serviceType, $serviceName, $serviceDesc, $featuredService, $serviceBanner, $servicePriceModel, $serviceCurrency, $pricingType, $addVat, $service_id ){
    
 

    $query = "UPDATE `services` SET `service_name`=?, `service_description`=?, `service_banner_url`=?, `isFeatured`=?, `priceType`=?, `addVat`=?, `priceModel`=?, `serviceType`=?, `serviceCurrency`=? WHERE id=?";

    // Prepare the statement
    $stmt = $mysqli->prepare($query);

    if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("ssssssssss",  $serviceName, $serviceDesc, $serviceBanner, $featuredService, $pricingType, $addVat, $servicePriceModel, $serviceType, $serviceCurrency, $service_id);

        // Execute the statement
        if ($stmt->execute()) {
           
            $stmt->close();

            // Insertion was successful, return the ID of the inserted service
            return [
                'added' => true,
                'service_data' =>  null,
                'error' => null
            ];
        } else {
            $stmt->close();

            // Insertion failed
            return [
                'added' => false,
                'service_data' => null,
                'error' => 'Insertion failed: ' . mysqli_error($mysqli)
            ];
        }
    } else {
        // Statement preparation failed
        return [
            'added' => false,
            'service_data' => null,
            'error' => 'Statement preparation failed: ' . mysqli_error($mysqli)
        ];
    }

}






function updateServicePricing($mysqli, $serviceId, $priceName, $price){

	$query = "UPDATE `servicePricing` SET `priceName`=?, `price`=? WHERE id=?";

	// Prepare the statement
	$stmt = $mysqli->prepare($query);

	if ($stmt) {
		
		// Bind the parameters
		$stmt->bind_param("ssi",  $priceName, $price, $serviceId );

		// Execute the statement
		if ($stmt->execute()) {
			$stmt->close();
			// Insertion was successful
			// You can add your success handling here
			return true;
		} else { 
			$stmt->close();
			// Insertion failed
			// You can add your error handling here
			return false;
		}

		// Close the statement
	
	} else {
		$stmt->close();
		// Statement preparation failed
		// You can add your error handling here
		return false;
	}


}






function DeleteService($mysqli, $serviceId) {
    // Delete from servicePricing table
    $deleteServicePricingQuery = "DELETE FROM servicePricing WHERE service_id = ?";
    
    // Prepare the statement
    $stmtServicePricing = $mysqli->prepare($deleteServicePricingQuery);
    
    if ($stmtServicePricing) {
        // Bind the parameters
        $stmtServicePricing->bind_param("s", $serviceId);
        
        // Execute the statement
        if ($stmtServicePricing->execute()) {
            $stmtServicePricing->close();
            
            // Delete from services table
            $deleteServiceQuery = "DELETE FROM services WHERE id = ?";
            
            // Prepare the statement
            $stmtService = $mysqli->prepare($deleteServiceQuery);
            
            if ($stmtService) {
                // Bind the parameters
                $stmtService->bind_param("s", $serviceId);
                
                // Execute the statement
                if ($stmtService->execute()) {
                    $stmtService->close();
                    // Deletion was successful
                    return true;
                } else {
                    $stmtService->close();
                    // Deletion from services table failed
                    // You can add your error handling here
                    return false;
                }
            } else {
                $stmtService->close();
                // Statement preparation failed for services table
                // You can add your error handling here
                return false;
            }
        } else {
            $stmtServicePricing->close();
            // Deletion from servicePricing table failed
            // You can add your error handling here
            return false;
        }
    } else {
        $stmtServicePricing->close();
        // Statement preparation failed for servicePricing table
        // You can add your error handling here
        return false;
    }
}






function DeleteServicePricing($mysqli, $servicePriceId) {
    // Delete from servicePricing table
    $deleteServicePricingQuery = "DELETE FROM servicePricing WHERE id = ?";
    
    // Prepare the statement
    $stmtServicePricing = $mysqli->prepare($deleteServicePricingQuery);
    
    if ($stmtServicePricing) {
        // Bind the parameters
        $stmtServicePricing->bind_param("s", $servicePriceId);
        
        // Execute the statement
        if ($stmtServicePricing->execute()) {
            $stmtServicePricing->close();
            
            
            return true;
           
        } else {
            $stmtServicePricing->close();
            // Deletion from servicePricing table failed
            // You can add your error handling here
            return false;
        }
    } else {
        $stmtServicePricing->close();
        // Statement preparation failed for servicePricing table
        // You can add your error handling here
        return false;
    }
}


function getKycId($mysqli, $userid) {
    // Prepare the statement
    $stmt = $mysqli->prepare("SELECT id FROM `kyc` WHERE userid=? ");
    
    if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("s", $userid);

        // Execute the statement
        if ($stmt->execute()) {
            // Get the result set
            $result = $stmt->get_result();

            // Fetch the result
            $id = null;
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $id = $row['id'];
            }

            $stmt->close();
            
            return $id; // Return the profileId
        } else {
            $stmt->close();
            // Query execution failed
            // You can add your error handling here
            return false;
        }
    } else {
        // Statement preparation failed
        // You can add your error handling here
        return false;
    }
}






function uploadKycProofCooperateIdentity($mysqli, $documentType, $documentFile, $id){

    $kycid = getKycId($mysqli, $id);

    $docType = "IDENTITY";

    $query = "INSERT INTO  `kycDocs` (`docName`, `docFile`, `kycId`, `docType`) VALUES(?,?,?,?) ";

    // Prepare the statement
    $stmt = $mysqli->prepare($query);

    if ($stmt) {
        
        // Bind the parameters
        $stmt->bind_param("ssss",  $documentType, $documentFile, $kycid, $docType);

        // Execute the statement
        if ($stmt->execute()) {
            $stmt->close();
            // Insertion was successful
            // You can add your success handling here
            return true;
        } else { 
            $stmt->close();
            // Insertion failed
            // You can add your error handling here
            return false;
        }

        // Close the statement
    
    } else {
        $stmt->close();
        // Statement preparation failed
        // You can add your error handling here
        return false;
    }



}







function DeleteSocial($mysqli, $socialId) {
    // Delete from servicePricing table
    $deleteBusinessQuery = "DELETE FROM `profileContact` WHERE id = ?";
    
    // Prepare the statement
    $stmtServicePricing = $mysqli->prepare($deleteBusinessQuery);
    
    if ($stmtServicePricing) {
        // Bind the parameters
        $stmtServicePricing->bind_param("s", $socialId);
        
        // Execute the statement
        if ($stmtServicePricing->execute()) {
            $stmtServicePricing->close();
            
            return true;
           
        } else {
            $stmtServicePricing->close();
            // Deletion from servicePricing table failed
            // You can add your error handling here
            return false;
        }
    } else {
        $stmtServicePricing->close();
        // Statement preparation failed for servicePricing table
        // You can add your error handling here
        return false;
    }

}





function DeleteBusinessDesc($mysqli, $businessDescId) {
    // Delete from servicePricing table
    $deleteBusinessQuery = "DELETE FROM `profileDesc` WHERE id = ?";
    
    // Prepare the statement
    $stmtServicePricing = $mysqli->prepare($deleteBusinessQuery);
    
    if ($stmtServicePricing) {
        // Bind the parameters
        $stmtServicePricing->bind_param("i", $businessDescId);
        
        // Execute the statement
        if ($stmtServicePricing->execute()) {
            $stmtServicePricing->close();
            
            return true;
           
        } else {
            $stmtServicePricing->close();
            // Deletion from servicePricing table failed
            // You can add your error handling here
            return false;
        }
    } else {
        $stmtServicePricing->close();
        // Statement preparation failed for servicePricing table
        // You can add your error handling here
        return false;
    }

}




function uploadKycURL($mysqli, $URL, $id) {
    // Check if the record with the given userid exists
    $checkQuery = "SELECT * FROM `kyc` WHERE `userid` = ?";
    $checkStmt = $mysqli->prepare($checkQuery);

    if ($checkStmt) {
        $checkStmt->bind_param("s", $id);
        $checkStmt->execute();
        $checkStmt->store_result();

        // If the record exists, update it; otherwise, insert a new record
        if ($checkStmt->num_rows > 0) {
            $updateQuery = "UPDATE `kyc` SET `websiteURL` = ? WHERE `userid` = ?";
            $updateStmt = $mysqli->prepare($updateQuery);
            $updateStmt->bind_param("ss", $URL, $id);
            
            if ($updateStmt->execute()) {
                $updateStmt->close();
                return true; // Update successful
            } else {
                $updateStmt->close();
                return false; // Update failed
            }
        } else {
            $checkStmt->close();

            $insertQuery = "INSERT INTO `kyc` (`websiteURL`, `userid`) VALUES (?, ?)";
            $insertStmt = $mysqli->prepare($insertQuery);
            $insertStmt->bind_param("ss", $URL, $id);

            if ($insertStmt->execute()) {
                $insertStmt->close();
                return true; // Insertion successful
            } else {
                $insertStmt->close();
                return false; // Insertion failed
            }
        }
    } else {
        return false; // Statement preparation failed
    }
}






function uploadKycProofCooperateOwnership($mysqli, $documentType, $documentFile, $id){

    $kycid = getKycId($mysqli, $id);

    $docType = "OWNERSHIP";

    $query = "INSERT INTO  `kycDocs` (`docName`, `docFile`, `kycId`, `docType`) VALUES(?,?,?,?) ";

    // Prepare the statement
    $stmt = $mysqli->prepare($query);

    if ($stmt) {
        
        // Bind the parameters
        $stmt->bind_param("ssss",  $documentType, $documentFile, $kycid, $docType);

        // Execute the statement
        if ($stmt->execute()) {
            $stmt->close();
            // Insertion was successful
            // You can add your success handling here
            return true;
        } else { 
            $stmt->close();
            // Insertion failed
            // You can add your error handling here
            return false;
        }

        // Close the statement
    
    } else {
        $stmt->close();
        // Statement preparation failed
        // You can add your error handling here
        return false;
    }



}





function getKycDetails($mysqli, $userid){

    $query = "SELECT * FROM `kyc` WHERE userid=?";
    
    // Prepare the statement
    $stmt = $mysqli->prepare($query);
    
    if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("s", $userid);

        // Execute the statement
        if ($stmt->execute()) {
            // Get the result set as an associative array
            $result = $stmt->get_result();
            
            // Fetch the user data as an associative array
            $user_data = $result->fetch_assoc();

            $stmt->close();
            
            if ($user_data) {
                // User exists
                return [
                    'p_exists' => true,
                    'p_data' => $user_data,
                ];
            } else {
                // User not found
                return [
                    'p_exists' => false,
                    'p_data' => null,
                    'error' => 'User not found in the database.'
                ];
            }
        } else {
            // Query execution failed
            return [
                'p_exists' => false,
                'p_data' => null,
                'error' => 'Query execution failed: ' . mysqli_error($mysqli)
            ];
        }
    } else {
        // Statement preparation failed
        return [
            'p_exists' => false,
            'p_data' => null,
            'error' => 'Statement preparation failed: ' . mysqli_error($mysqli)
        ];
    }


}








function getKycDocs($mysqli, $kycid){


    $profileid = getPublicProfileId($mysqli, $kycid);
    $query = "SELECT * FROM `kycDocs` WHERE kycid=?";
    
    // Prepare the statement
    $stmt = $mysqli->prepare($query);
    
    if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("s", $kycid);

        // Execute the statement
        if ($stmt->execute()) {
            // Get the result set
            $result = $stmt->get_result();
            
            // Fetch the rows and store them in an array
            $profileData = [];
            while ($row = $result->fetch_assoc()) {
                $profileData[] = $row;
            }

            $stmt->close();

            if (!empty($profileData)) {
                // User exists, return the profile data array
                return [
                    'p_exists' => true,
                    'p_data' => $profileData,
                ];
            } else {
                // User not found
                return [
                    'p_exists' => false,
                    'p_data' => null,
                    'error' => 'User not found in the database.'
                ];
            }
        } else {
            // Query execution failed
            return [
                'p_exists' => false,
                'p_data' => null,
                'error' => 'Query execution failed: ' . mysqli_error($mysqli)
            ];
        }
    } else {
        // Statement preparation failed
        return [
            'p_exists' => false,
            'p_data' => null,
            'error' => 'Statement preparation failed: ' . mysqli_error($mysqli)
        ];
    }



}






function editKycProofCooperateIdentity($mysqli, $documentType, $documentFile, $id, $count) {
    $kycid = getKycId($mysqli, $id);
    $docType = "IDENTITY";

    // Fetch the specific row to update
    $selectQuery = "SELECT `id` FROM `kycDocs` WHERE `kycId`=? and `docType`=? LIMIT ?,1";
    $stmtSelect = $mysqli->prepare($selectQuery);

    $docId = null;
    
    if ($stmtSelect) {
        $stmtSelect->bind_param("isi", $kycid, $docType, $count);
        $stmtSelect->execute();
        $stmtSelect->bind_result($docId);
        
        if ($stmtSelect->fetch()) {
            // Row found, update the record
            $stmtSelect->close();
            
            $updateQuery = "UPDATE `kycDocs` SET `docName`=?, `docFile`=? WHERE `id`=?";
            $stmtUpdate = $mysqli->prepare($updateQuery);
            
            if ($stmtUpdate) {
                $stmtUpdate->bind_param("ssi", $documentType, $documentFile, $docId);
                
                if ($stmtUpdate->execute()) {
                    $stmtUpdate->close();
                    return true;
                } else {
                    $stmtUpdate->close();
                    return false;
                }
            } else {
                return false;
            }
        } else {
            // Row not found, handle this case accordingly (return false or throw an error)
            $stmtSelect->close();
            return false;
        }
    } else {
        return false;
    }
}





function getActivities($mysqli, $id){

    $query = "SELECT * FROM `activity` WHERE userid=? ORDER BY id DESC";
    
    // Prepare the statement
    $stmt = $mysqli->prepare($query);
    
    if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("s", $id);

        // Execute the statement
        if ($stmt->execute()) {
            // Get the result set
            $result = $stmt->get_result();
            
            // Fetch the rows and store them in an array
            $profileData = [];
            while ($row = $result->fetch_assoc()) {
                $profileData[] = $row;
            }

            $stmt->close();

            if (!empty($profileData)) {
                // User exists, return the profile data array
                return [
                    'p_exists' => true,
                    'p_data' => $profileData,
                ];
            } else {
                // User not found
                return [
                    'p_exists' => false,
                    'p_data' => null,
                    'error' => 'User not found in the database.'
                ];
            }
        } else {
            // Query execution failed
            return [
                'p_exists' => false,
                'p_data' => null,
                'error' => 'Query execution failed: ' . mysqli_error($mysqli)
            ];
        }
    } else {
        // Statement preparation failed
        return [
            'p_exists' => false,
            'p_data' => null,
            'error' => 'Statement preparation failed: ' . mysqli_error($mysqli)
        ];
    }



}





function createActivity($mysqli, $userid, $title){

	$query = "INSERT INTO `activity`(`title`, `formattted_date`, `userid`) VALUES(?, ?, ?)";


	// Prepare the statement
	$stmt = $mysqli->prepare($query);
	
	if ($stmt) {

		$date = date('d')."-".date('m')."-".date('Y').", ".date('h').":".date('i').date("a");;
		
		// Bind the parameters
		$stmt->bind_param("sss",  $title, $date, $userid);
	
		// Execute the statement
		if ($stmt->execute()) {
			
			$mysqli->commit();

			 // echo "Error: " . $stmt->error;

			$stmt->close();
			// update was successful
			// You can add your success handling here
			return true;
		} else { 

			echo "Error: " . $stmt->error;

			$stmt->close();
			// Insertion failed
			// You can add your error handling here
			return false;
		}
	
		// Close the statement
	   
	} else {

		echo "Error: " . $stmt->error;


		$stmt->close();
		// Statement preparation failed
		// You can add your error handling here
		return false;
	}
		



}














function getUnapprovedUsers($mysqli) {
   
    $query = "SELECT * FROM `users` WHERE kycStatus=?";
    
    // Prepare the statement
    $stmt = $mysqli->prepare($query);
    
    if ($stmt) {
        $kycStatus = 0;
        // Bind the parameters
        $stmt->bind_param("s", $kycStatus);

        // Execute the statement
        if ($stmt->execute()) {
            // Get the result set
            $result = $stmt->get_result();
            
            // Fetch the rows and store them in an array
            $profileData = [];
            while ($row = $result->fetch_assoc()) {
                $profileData[] = $row;
            }

            $stmt->close();

            if (!empty($profileData)) {
                // User exists, return the profile data array
                return [
                    'p_exists' => true,
                    'p_data' => $profileData,
                ];
            } else {
                // User not found
                return [
                    'p_exists' => false,
                    'p_data' => null,
                    'error' => 'User not found in the database.'
                ];
            }
        } else {
            // Query execution failed
            return [
                'p_exists' => false,
                'p_data' => null,
                'error' => 'Query execution failed: ' . mysqli_error($mysqli)
            ];
        }
    } else {
        // Statement preparation failed
        return [
            'p_exists' => false,
            'p_data' => null,
            'error' => 'Statement preparation failed: ' . mysqli_error($mysqli)
        ];
    }

}







function updateKycStatus($mysqli, $userId, $kycStatus) {

    $query = "UPDATE `users` SET `kycStatus`=? WHERE id=?";

	// Prepare the statement
	$stmt = $mysqli->prepare($query);

	if ($stmt) {
		// Bind the parameters
		$stmt->bind_param("ss",  $kycStatus, $userId);

		// Execute the statement
		if ($stmt->execute()){
			$stmt->close();
			// Insertion was successful
			// You can add your success handling here
			return true;
		} else { 
			$stmt->close();
			// Insertion failed
			// You can add your error handling here
			return false;
		}
		// Close the statement
	
	} else {
		$stmt->close();
		// Statement preparation failed
		// You can add your error handling here
		return false;
	}


}







function getTotalUsers($mysqli) {
    $query = "SELECT COUNT(*) FROM `users`";

    // Prepare the statement
    $stmt = $mysqli->prepare($query);

    if ($stmt) {
        // Execute the statement
      

        if ($stmt->execute()) {
            $userCount = null;
            // Bind the result variable
            $stmt->bind_result($userCount);

            // Fetch the result
            $stmt->fetch();

            // Close the statement
            $stmt->close();

            // Return the count of users
            return $userCount;
        } else {
            $stmt->close();
            // Execution failed
            // You can add your error handling here
            return false;
        }
    } else {
        // Statement preparation failed
        // You can add your error handling here
        return false;
    }
}




function getApprovedUsers($mysqli) {
    $query = "SELECT COUNT(*) FROM `users` WHERE kycStatus=?";

    // Prepare the statement
    $stmt = $mysqli->prepare($query);

    if ($stmt) {
        // Execute the statement
        $kycStatus =1;
        $stmt->bind_param("s",  $kycStatus);
        if ($stmt->execute()) {
            $userCount = null;
            // Bind the result variable
            $stmt->bind_result($userCount);

            // Fetch the result
            $stmt->fetch();

            // Close the statement
            $stmt->close();

            // Return the count of users
            return $userCount;
        } else {
            $stmt->close();
            // Execution failed
            // You can add your error handling here
            return false;
        }
    } else {
        // Statement preparation failed
        // You can add your error handling here
        return false;
    }
}





function getPendingUsers($mysqli) {
    $query = "SELECT COUNT(*) FROM `users` WHERE kycStatus=?";

    // Prepare the statement
    $stmt = $mysqli->prepare($query);

    if ($stmt) {
        // Execute the statement
        $kycStatus =0;
        $stmt->bind_param("s",  $kycStatus);
        if ($stmt->execute()) {
            $userCount = null;
            // Bind the result variable
            $stmt->bind_result($userCount);

            // Fetch the result
            $stmt->fetch();

            // Close the statement
            $stmt->close();

            // Return the count of users
            return $userCount;
        } else {
            $stmt->close();
            // Execution failed
            // You can add your error handling here
            return false;
        }
    } else {
        // Statement preparation failed
        // You can add your error handling here
        return false;
    }
}







function getRejectUsers($mysqli) {
    $query = "SELECT COUNT(*) FROM `users` WHERE kycStatus=?";

    // Prepare the statement
    $stmt = $mysqli->prepare($query);

    if ($stmt) {
        // Execute the statement
        $kycStatus =2;
        $stmt->bind_param("s",  $kycStatus);
        if ($stmt->execute()) {
            $userCount = null;
            // Bind the result variable
            $stmt->bind_result($userCount);

            // Fetch the result
            $stmt->fetch();

            // Close the statement
            $stmt->close();

            // Return the count of users
            return $userCount;
        } else {
            $stmt->close();
            // Execution failed
            // You can add your error handling here
            return false;
        }
    } else {
        // Statement preparation failed
        // You can add your error handling here
        return false;
    }
}




function checkBussinesName($mysqli, $bussinessName) {
    $query = "SELECT COUNT(*) FROM `users` WHERE bussinesName=?";

    // Prepare the statement
    $stmt = $mysqli->prepare($query);

    if ($stmt) {
        // Execute the statement
        $stmt->bind_param("s", $bussinessName);
        if ($stmt->execute()) {
            $userCount = null;

            // Bind the result variable
            $stmt->bind_result($userCount);

            // Fetch the result
            $stmt->fetch();

            // Close the statement
            $stmt->close();

            // Return true if the business name count is zero, otherwise false
            return $userCount === 0;
        } else {
            $stmt->close();
            // Execution failed
            // You can add your error handling here
            return false;
        }
    } else {
        // Statement preparation failed
        // You can add your error handling here
        return false;
    }
}




function checkBussinesRegNumber($mysqli, $rcNumber){
    $query = "SELECT COUNT(*) FROM `users` WHERE rcNumber=?";

    // Prepare the statement
    $stmt = $mysqli->prepare($query);

    if ($stmt) {
        // Execute the statement
        $stmt->bind_param("s", $rcNumber);
        if ($stmt->execute()) {
            $userCount = null;

            // Bind the result variable
            $stmt->bind_result($userCount);

            // Fetch the result
            $stmt->fetch();

            // Close the statement
            $stmt->close();

            // Return true if the business name count is zero, otherwise false
            return $userCount === 0;
        } else {
            $stmt->close();
            // Execution failed
            // You can add your error handling here
            return false;
        }
    } else {
        // Statement preparation failed
        // You can add your error handling here
        return false;
    }

}


function checkPhoneNumber($mysqli, $phone){
    $query = "SELECT COUNT(*) FROM `users` WHERE phone=?";

    // Prepare the statement
    $stmt = $mysqli->prepare($query);

    if ($stmt) {
        // Execute the statement
        $stmt->bind_param("s", $phone);
        if ($stmt->execute()) {
            $userCount = null;

            // Bind the result variable
            $stmt->bind_result($userCount);

            // Fetch the result
            $stmt->fetch();

            // Close the statement
            $stmt->close();

            // Return true if the business name count is zero, otherwise false
            return $userCount === 0;
        } else {
            $stmt->close();
            // Execution failed
            // You can add your error handling here
            return false;
        }
    } else {
        // Statement preparation failed
        // You can add your error handling here
        return false;
    }

}





function GetUserDetails($mysqli, $id)
{
    $query = "SELECT * FROM `users` WHERE id=?";
    
    // Prepare the statement
    $stmt = $mysqli->prepare($query);
    
    if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("s", $id);

        // Execute the statement
        if ($stmt->execute()) {
            // Get the result set as an associative array
            $result = $stmt->get_result();
            
            // Fetch the user data as an associative array
            $user_data = $result->fetch_assoc();

            $stmt->close();
            
            if ($user_data) {
                // User exists
                return [
                    'user_exists' => true,
                    'user_data' => $user_data,
                ];
            } else {
                // User not found
                return [
                    'user_exists' => false,
                    'user_data' => null,
                    'error' => 'User not found in the database.'
                ];
            }
        } else {
            // Query execution failed
            return [
                'user_exists' => false,
                'user_data' => null,
                'error' => 'Query execution failed: ' . mysqli_error($mysqli)
            ];
        }
    } else {
        // Statement preparation failed
        return [
            'user_exists' => false,
            'user_data' => null,
            'error' => 'Statement preparation failed: ' . mysqli_error($mysqli)
        ];
    }
}



function Update2faDetails($mysqli, $id, $secret, $two_fa_link ){


    $query = "UPDATE `users` SET `2faSecret`=?, `2faLink`=? WHERE id=?";

	// Prepare the statement
	$stmt = $mysqli->prepare($query);

	if ($stmt) {
		// Bind the parameters
		$stmt->bind_param("sss",  $secret, $two_fa_link, $id);

		// Execute the statement
		if ($stmt->execute()){
			$stmt->close();
			// Insertion was successful
			// You can add your success handling here
			return true;
		} else { 
			$stmt->close();
			// Insertion failed
			// You can add your error handling here
			return false;
		}
		// Close the statement
	
	} else {
		$stmt->close();
		// Statement preparation failed
		// You can add your error handling here
		return false;
	}


}


function Update2faStatus($mysqli, $id, $status ){


    $query = "UPDATE `users` SET `2fa`=? WHERE id=?";

	// Prepare the statement
	$stmt = $mysqli->prepare($query);

	if ($stmt) {
		// Bind the parameters
		$stmt->bind_param("ss",  $status, $id);

		// Execute the statement
		if ($stmt->execute()){
			$stmt->close();
			// Insertion was successful
			// You can add your success handling here
			return true;
		} else { 
			$stmt->close();
			// Insertion failed
			// You can add your error handling here
			return false;
		}
		// Close the statement
	
	} else {
		$stmt->close();
		// Statement preparation failed
		// You can add your error handling here
		return false;
	}


}


	
?>
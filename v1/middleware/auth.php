<?php


require_once 'firebase-php-jwt/src/BeforeValidException.php';
require_once 'firebase-php-jwt/src/Key.php';
require_once 'firebase-php-jwt/src/ExpiredException.php';
require_once 'firebase-php-jwt/src/SignatureInvalidException.php';
require_once 'firebase-php-jwt/src/JWT.php';

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

$headers = getallheaders();

if(isset($headers['Authorization']) || isset($headers['authorization'])) {
    $bearer_token = isset($headers['Authorization']) ? $headers['Authorization'] : $headers['authorization'];

    if (preg_match("/^Bearer\s/", $bearer_token)) {
        $bearer_token = str_replace("Bearer ", "", $bearer_token);

        $key = $_ENV['JWT_KEY'];

        $json = file_get_contents('php://input');
        $data = json_decode($json);

        try {
            $decoded = JWT::decode($bearer_token, new Key($key, 'HS256'));

            $decoded = (array) $decoded;

               // Check if the token has expired
               $currentTimestamp = time();
               $expirationTimestamp = isset($decoded['exp']) ? $decoded['exp'] : null;
   
               if ($expirationTimestamp && $currentTimestamp > $expirationTimestamp) {
                   header('Content-Type: application/json; charset=utf-8');
                   header('HTTP/1.0 401 Unauthorized');
                   $set = ['message' => 'Token has expired', 'code' => 401, 'responseStatus' => '40', 'status' => 0];
                   $msg = json_encode($set);
                   echo $msg;
                   exit;
               }

               
               
            
            // Assuming you have a user object in the decoded data, you can return it.
            $userData = isset($decoded['id']) ? $decoded['id'] : null;

            if ($userData) {

                // You can return the user data as a JSON response.
                header('Content-Type: application/json; charset=utf-8');
             
                 $USER_DATA = $decoded;

                 global $USER_DATA;


            } else {

                global $USER_DATA;


                header('Content-Type: application/json; charset=utf-8');
                header('HTTP/1.0 401 Unauthorized');
                $set = ['message' => 'User data not found', 'code' => 401, 'responseStatus' => '40', 'status' => 0];
                $msg = json_encode($set);
                echo $msg;
                exit;
            }

        } catch (Exception $e) {

            global $USER_DATA;

            header('Content-Type: application/json; charset=utf-8');
            header('HTTP/1.0 401 Unauthorized ');
            $set = ['message' => "Invalid Token Supplied. Error Validating Token", 'code' => 401, 'responseStatus' => '40', 'status' => 0];
            $msg = json_encode($set);
            echo $msg;
            exit;
        }

    } else {

        global $USER_DATA;


        header('Content-Type: application/json; charset=utf-8');
        header('HTTP/1.0 401 Unauthorized ');
        $set = ['message' => 'Error Validating Access', 'responseStatus' => '40', 'code' => 401, 'status' => 0];
        $msg = json_encode($set);
        echo $msg;
        exit;
    }
} else {

    global $USER_DATA;


    header('Content-Type: application/json; charset=utf-8');
    header('HTTP/1.0 401 Unauthorized');
    $set = ['message' => 'Error Validating Access', 'responseStatus' => '40', 'code' => 401, 'status' => 0];
    $msg = json_encode($set);
    echo $msg;
    exit;
}








	
?>
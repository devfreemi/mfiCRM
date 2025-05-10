<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;

class ApiController extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        //
        $url = 'https://uat.risewithprotean.io/v1/oauth/token';

        // Your API credentials
        $apiKey = "PGI9xIw9g0ADlUODfTjNG9wA8bOjmi40Vjr7ePZKdhyrS0A1";       // Username
        $secretKey = "iQ2JY50gbNWY80TLw2AJAllJw7dVhRXkhmf87ts0vIuQKDnDdOFStna0FmTz4OIp"; // Password

        // Base64 encode for Basic Auth
        $auth = base64_encode("$apiKey:$secretKey");

        // Payload
        $data = http_build_query([
            'grant_type' => 'client_credentials'
        ]);
        // cURL setup
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Basic $auth",
            "Content-Type: application/x-www-form-urlencoded"
        ]);

        // Execute request
        $response = curl_exec($ch);

        // Error handling
        // if (curl_errno($ch)) {
        //     echo 'Error: ' . curl_error($ch);
        // } else {
        //     echo "Response:\n";
        //     echo $response;
        // }
        $error = curl_error($ch);

        curl_close($ch);

        if ($error) {
            return $this->respond(['error' => 'Internal Exception!' . $error], 502);
        } else {
            $response = json_decode($response, true);
            $accessToken = $response['access_token'];
            echo "Access Token:\n";
            echo $accessToken;
            echo "\n";
            // return $this->respond(['success' => 'Success! ' . $response], 200);
            $dataApi = array(
                'vehicleNumber'              => 'WB24BA4308',

            );
            $data_json = json_encode($dataApi);

            $curlData = curl_init();

            curl_setopt_array($curlData, array(
                CURLOPT_URL => "https://uat.risewithprotean.io/api/v1/protean/vehicle-detailed-advanced",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $data_json,
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/json",
                    "Authorization: Bearer " . $accessToken,
                    "apikey: " . $apiKey,
                ),
            ));


            $responseFinal = curl_exec($curlData);
            // Error handling
            if (curl_errno($curlData)) {
                echo 'Error: ' . curl_error($curlData);
            } else {
                echo "Response Final API:\n";
                echo $responseFinal;
            }
            curl_close($curlData);
        }
    }
}

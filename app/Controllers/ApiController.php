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
        $url = 'https://api.risewithprotean.io/v1/oauth/token';
        $username = 'PGI9xIw9g0ADlUODfTjNG9wA8bOjmi40Vjr7ePZKdhyrS0A1';
        $password = 'iQ2JY50gbNWY80TLw2AJAllJw7dVhRXkhmf87ts0vIuQKDnDdOFStna0FmTz4OIp';

        $curl = curl_init();

        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // // Basic Authentication
        // curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");

        // // Optional: set method to POST or GET
        // curl_setopt($ch, CURLOPT_POST, true);

        // // Optional: set content type if needed
        // curl_setopt($ch, CURLOPT_HTTPHEADER, [
        //     'Content-Type: application/x-www-form-urlencoded'
        // ]);

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD => "$username:$password",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            // CURLOPT_POSTFIELDS => $data_json,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
            ),
        ));



        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);


        if (curl_errno($curl)) {
            // echo 'Curl error: ' . curl_error($curl);
            // echo "HTTP Status Code: $httpCode\n";
            // echo $err;
        } else {
            // echo 'Response: ' . $response;
            print_r($response);
            // echo "HTTP Status Code: $httpCode\n";
            // echo $err;
        }

        curl_close($curl);
    }
}

<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;

class IpCheckController extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        //
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.ipify.org?format=json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        // echo "Your server's outgoing IP is: ";
        // echo $response;
        return $this->respond(['panVerify' => $response], 200);
    }
}

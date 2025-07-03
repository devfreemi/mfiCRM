<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;

class GeoController extends BaseController
{
    use ResponseTrait;
    public function reverse()
    {
        $lat = $this->request->getGet('lat');
        $lon = $this->request->getGet('lon');

        $apiKey = 'd548c5ed24604be6a9dd0d989631f783';
        $url = "https://api.geoapify.com/v1/geocode/reverse?lat=$lat&lon=$lon&format=json&apiKey=$apiKey";

        $client = \Config\Services::curlrequest();
        $response = $client->get($url);
        $body = json_decode($response->getBody(), true);

        $placeName = $body['results'][0]['county'] ?? null;
        $cityName = $body['results'][0]['city'] ?? null;
        $pinCode = $body['results'][0]['postcode'] ?? null;
        $final = $placeName . ' ' . $cityName . ' ' . $pinCode;
        return $this->response->setJSON(['place_name' => $final]);
    }
}

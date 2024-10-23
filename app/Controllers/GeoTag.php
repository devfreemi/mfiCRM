<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;

class GeoTag extends BaseController
{
    use ResponseTrait;
    public function getData()
    {
        //
        $lat =  $this->request->getVar('latitude');
        $long = $this->request->getVar('longitude');
        $data = [$lat, $long];
        return $this->respond(['tag' => $data], 200);
    }
}

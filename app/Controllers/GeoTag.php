<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\GeoTagModel;
use CodeIgniter\API\ResponseTrait;

class GeoTag extends BaseController
{
    use ResponseTrait;
    public function getData()
    {
        //

        $model = new GeoTagModel();
        $data = [
            'agent'             => $this->request->getVar('eName'),
            'agent_id'              => $this->request->getVar('employeeId'),
            'latitude'       => $this->request->getVar('latitude'),
            'longitude'        => $this->request->getVar('longitude'),
            'location'                 => $this->request->getVar('address'),
            'city'                 => $this->request->getVar('city'),
            'pincode'                 => $this->request->getVar('pin'),
            'state'                 => $this->request->getVar('state'),
            'country'                 => $this->request->getVar('country'),
            'reference'                 => $this->request->getVar('reference'),
            'created_at'           => date('Y-m-d H:i:s'),
        ];
        $query = $model->save($data);
        if (!$query) {
            return $this->respond(['error' => 'Invalid Request.' . $query], 401);
        } else {
            # code...
            return $this->respond(['tag' => $data], 200);
        }
    }
}

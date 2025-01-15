<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\GeoTagModel;
use App\Models\AgentAttendenceModel;
use CodeIgniter\API\ResponseTrait;

class GeoTag extends BaseController
{
    use ResponseTrait;
    public function getData()
    {
        //
        date_default_timezone_set('Asia/Kolkata');
        $employee = $this->request->getVar('employeeId');
        $signDate = date('Y-m-d');
        $date = date('Y-m-d H:i:s');
        $signIn = date('H:i:s');
        $model = new GeoTagModel();
        $atten = new AgentAttendenceModel();
        $data = [
            'agent'             => $this->request->getVar('eName'),
            'agent_id'          => $this->request->getVar('employeeId'),
            'latitude'          => $this->request->getVar('latitude'),
            'longitude'         => $this->request->getVar('longitude'),
            'location'          => $this->request->getVar('address'),
            'city'              => $this->request->getVar('city'),
            'pincode'           => $this->request->getVar('pin'),
            'state'             => $this->request->getVar('state'),
            'country'           => $this->request->getVar('country'),
            'reference'         => $this->request->getVar('downloadURLP1'),
            'date'              => $signDate,
            'created_at'        => $date,
            'sign_in_time'      => $signIn,
        ];
        $query = $model->save($data);

        // $lastRecord = $atten
        //     ->where('agent_id', $employee) // Your WHERE condition
        //     ->orderBy('created_at', 'DESC') // Order by created_at in descending order
        //     ->limit(1)
        //     ->first();
        // if ($lastRecord != Null) {
        //     # code...
        //     return $this->respond(['tag' => $lastRecord], 200);
        // } else {
        //     # code...
        //     return $this->respond(['error' => 'Invalid Request.' . $lastRecord], 401);
        // }

        // if (!$query) {
        //     return $this->respond(['error' => 'Invalid Request.' . $query], 401);
        // } else {
        //     # code...
        //     return $this->respond(['tag' => $data], 200);
        // }
    }

    public function listData()
    {
        //

        $model = new GeoTagModel();
        $eIdTag = $this->request->getVar('eIdTag');

        $tag_list = $model->where('agent_id', $eIdTag)->findAll();

        if (is_null($tag_list)) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        } else {
            # code...
            return $this->respond($tag_list, 200);
        }
    }
}

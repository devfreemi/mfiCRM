<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\GroupModel;
use CodeIgniter\API\ResponseTrait;

class GroupApi extends BaseController
{
    use ResponseTrait;
    public function add_group()
    {
        //
        date_default_timezone_set('Asia/Kolkata');
        $model = new GroupModel();
        $agentId = $this->request->getVar('employeeID');

        $data = [
            'g_name'        => $this->request->getVar('groupName'),
            'g_id'          => rand(100, 999),
            'branch'        => $this->request->getVar('branch'),
            'location'      => $this->request->getVar('location'),
            'pincode'       => $this->request->getVar('pincode'),
            'group_type'    => $this->request->getVar('groupType'),
            'agent'         => $this->request->getVar('employeeID'),
            'group_leader'  => $this->request->getVar('employeeName'),
            'date'          => date('Y-m-d'),
            'created_at'    => date('Y-m-d H:i:s')
        ];

        // $query = $model->insert($data);
        $query = $model->save($data);
        if (!$query) {
            return $this->respond(['error' => 'Invalid Request.' . $query], 401);
        } else {
            # code...
            return $this->respond(['groups' => $data], 200);
        }
    }
}

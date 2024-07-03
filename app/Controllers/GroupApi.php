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
        $model = new GroupModel();

        $data = [
            'g_name'    => $this->request->getVar('groupName'),
            'g_id'      => rand(100, 999),
            'branch'    => $this->request->getVar('branch'),
            'location'  => $this->request->getVar('location'),
            'pincode'   => $this->request->getVar('pincode'),
            'group_type'  => $this->request->getVar('groupType'),
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

<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\GroupModel;
use CodeIgniter\API\ResponseTrait;

class GroupListApi extends BaseController
{
    use ResponseTrait;
    public function group_list_api()
    {
        //
        $model = new GroupModel();
        $agentID   = $this->request->getVar('employeeID');

        $group = $model->where('agent', $agentID)->findAll();

        if (is_null($group)) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        }

        return $this->respond($group, 200);
    }
    public function total_group()
    {
        //
        $model = new GroupModel();
        $employeeIDG = $this->request->getVar('employeeIDG');

        $total_group = $model->where('agent', $employeeIDG)->countAllResults();

        if (is_null($total_group)) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        }

        return $this->respond($total_group, 200);
    }
}

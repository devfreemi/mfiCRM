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


        $group = $model->findAll();

        if (is_null($group)) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        }

        return $this->respond($group, 200);
    }
}

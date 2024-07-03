<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\BranchModel;
use CodeIgniter\API\ResponseTrait;

class BranchListAPI extends BaseController
{
    use ResponseTrait;
    public function barnch_list_api()
    {
        //
        $model = new BranchModel();


        $branch = $model->findAll();

        if (is_null($branch)) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        }

        return $this->respond($branch, 200);
    }
}

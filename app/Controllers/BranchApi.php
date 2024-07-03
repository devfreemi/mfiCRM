<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\BranchModel;
use CodeIgniter\API\ResponseTrait;

class BranchApi extends BaseController
{
    use ResponseTrait;
    public function barnch_api()
    {
        //
        $model = new BranchModel();
        $branchID = $this->request->getVar('branchID');

        $branch = $model->where('id', $branchID)->first();

        if (is_null($branch)) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        }

        return $this->respond(['branches' => $branch], 200);
    }
}

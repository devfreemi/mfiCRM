<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\GroupModel;
// use App\Models\BranchModel;
use CodeIgniter\API\ResponseTrait;

class GroupDetailsApi extends BaseController
{
    use ResponseTrait;
    public function group_details()
    {
        //
        $model = new GroupModel();
        $groupID = $this->request->getVar('groupID');

        $group = $model->join('branches', 'branches.id = groups.branch')
            ->where('g_id', $groupID)->first();

        if (is_null($group)) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        }

        return $this->respond(['groups' => $group], 200);
    }
}

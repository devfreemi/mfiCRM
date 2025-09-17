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
        $db = db_connect();
        $model = new GroupModel();
        // $agentID   = $this->request->getVar('employeeID');

        // Fetch all groups
        $groups = $model->findAll();

        if (empty($groups)) {
            return $this->respond(['error' => 'No groups found.'], 404);
        }

        // Add member count to each group
        foreach ($groups as &$group) {
            $builder = $db->table('members');
            $builder->where('groupId', $group['g_id']); // assuming PK is "id"
            $memberCount = $builder->countAllResults();

            $group['member_count'] = $memberCount; // append count to response
        }

        return $this->respond($groups, 200);
    }
    public function total_group()
    {
        //
        $model = new GroupModel();
        // $employeeIDG = $this->request->getVar('employeeIDG');

        $total_group = $model->countAllResults();

        if (is_null($total_group)) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        }

        return $this->respond($total_group, 200);
    }
}

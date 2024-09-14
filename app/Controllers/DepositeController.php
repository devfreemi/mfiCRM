<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DepositeModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;

class DepositeController extends BaseController
{
    use ResponseTrait;
    public function add_deposite()
    {
        //
        $model = new DepositeModel();
        $data = [
            'member_id'             => $this->request->getVar('memberId'),
            'group_id'              => $this->request->getVar('groupId'),
            'collected_amount'       => $this->request->getVar('collectionAmt'),
            'collection_date'        => $this->request->getVar('collectionDateApi'),
            'agent'                 => $this->request->getVar('employeeId'),
            'created_at'           => date('Y-m-d'),

        ];

        // $query = $model->insert($data);
        $query = $model->save($data);
        if (!$query) {
            return $this->respond(['error' => 'Invalid Request.' . $query], 401);
        } else {
            # code...
            return $this->respond(['deposite' => $data], 200);
        }
    }

    public function temp_data()
    {
        $model = new DepositeModel();
        $agentID   = $this->request->getVar('employeeIDT');
        $temp = $model->where('agent', $agentID)
            ->where('created_at', date('Y-m-d'))
            ->where('submitted', 'N')->findAll();

        if (!$temp) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        } else {
            return $this->respond($temp, 200);
        }
    }
}

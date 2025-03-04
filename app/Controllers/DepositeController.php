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

        $uniqId = $this->request->getVar('employeeId') . "-" . $this->request->getVar('collectionDateApi');
        $uniqId = str_replace(' ', '', $uniqId);
        $uniqId = str_replace('-', '', $uniqId);
        $uniqId = str_replace(':', '', $uniqId);
        $model = new DepositeModel();
        $data = [
            'member_id'             => $this->request->getVar('memberId'),
            'group_id'              => $this->request->getVar('groupId'),
            'collected_amount'       => $this->request->getVar('collectionAmt'),
            'collection_date'        => $this->request->getVar('collectionDateApi'),
            'uniqid'                 => $uniqId,
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
    public function deposite_verification_total()
    {
        $model = new DepositeModel();
        $agentID   = $this->request->getVar('employeeIdValid');

        // COUNT FOR CURRENT DATE
        $saveDataCount = $model->where('created_at', date('Y-m-d'))
            ->where('agent', $agentID)
            ->countAllResults();

        if ($saveDataCount > 0) {
            # code...
            $tempTotal = $model->selectSum('collected_amount')
                ->where('agent', $agentID)
                ->where('created_at', date('Y-m-d'))
                ->where('submitted', 'N')->get();

            foreach ($tempTotal->getResult() as $rowSum) {
                return $this->respond(
                    $rowSum,
                    200
                );
            }
        } else {
            # code...
            return $this->respond(['error' => 'Invalid Request.'], 401);
        }
    }
}

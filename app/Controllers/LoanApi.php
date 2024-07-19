<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\LoanModel;
use CodeIgniter\API\ResponseTrait;

class LoanApi extends BaseController
{
    use ResponseTrait;
    public function applied_loan()
    {
        //
        $model = new LoanModel();
        $data = [

            'groupId'      => $this->request->getVar('groupID'),
            'member_id'    => $this->request->getVar('memberID'),
            'loan_amount'  => $this->request->getVar('loanAmount'),
            'loan_type'   => $this->request->getVar('loanType'),
            'employee_id'  => $this->request->getVar('employeeID'),
            'applicationID'  => rand(10000, 99999) . $this->request->getVar('memberID'),

        ];
        // $query = $model->insert($data);
        $query = $model->save($data);
        if (!$query) {
            return $this->respond(['error' => 'Invalid Request.' . $query], 401);
        } else {
            # code...
            return $this->respond(['loan' => $data], 200);
        }
    }
    public function list_of_loan()
    {
        $model = new LoanModel();
        $member_id   = $this->request->getVar('memberID');

        $loan = $model->where('member_id', $member_id)->findAll();

        if (!$loan) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        }

        return $this->respond($loan, 200);
    }
}

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
    public function details_of_loan()
    {
        $model = new LoanModel();
        $loanID   = $this->request->getVar('loanID');

        $loan = $model->where('applicationID', $loanID)->first();

        if (!$loan) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        }

        return $this->respond($loan, 200);
    }
    public function update_of_loan()
    {
        // $model = new LoanModel();
        $db = db_connect();
        $builder = $db->table('loans');
        $applicationid   = $this->request->getPost('applicationid');
        $data = [

            'loan_status'      => $this->request->getPost('status'),
        ];

        $builder->where('applicationID', $applicationid);
        $query = $builder->update($data);

        $session = session();
        $session->setFlashdata('msg', 'Loan Status Updated!');
        return redirect()->to(base_url() . 'loan');
    }
}

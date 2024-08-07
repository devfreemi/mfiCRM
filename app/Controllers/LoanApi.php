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

            'groupId'       => $this->request->getVar('groupID'),
            'member_id'     => $this->request->getVar('memberID'),
            'loan_amount'   => $this->request->getVar('loanAmount'),
            'loan_tenure'        => $this->request->getVar('tenure'),
            'loan_type'     => $this->request->getVar('loanType'),
            'employee_id'   => $this->request->getVar('employeeID'),
            'applicationID' => rand(10000, 99999) . $this->request->getVar('memberID'),

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
        $model = new LoanModel();
        $db = db_connect();
        $builder = $db->table('loans');
        $applicationid   = $this->request->getPost('applicationid');
        $table = "tab_" . $applicationid;
        $status = $this->request->getPost('status');
        $loan_amount = $this->request->getPost('lona_amount');
        $tenure = $this->request->getPost('tenure');
        $roi = "12";
        if ($status == "Approved") {
            # code...
            $model->create($table);
            $r = ($roi / 100 / 12);
            $x = pow(1 + $r, $tenure);
            $emi = round(($loan_amount * $x * $r) / ($x - 1));
            $due = round($emi * $tenure);
            $data = [

                'loan_status'       => "Approved",
                'loan_tenure'       => $tenure,
                'emi'               =>  $emi,
                'pending_emi'       =>  $tenure,
                'loan_due'          => $due,
            ];

            $builder->where('applicationID', $applicationid);
            $query = $builder->update($data);

            $session = session();
            $session->setFlashdata('msg', 'Loan Status Updated!');
            return redirect()->to(base_url() . 'loan');
        } elseif ($status == "Disbursed") {
            # code...
            $today = date('Y-m-d');
            $r = ($roi / 100 / 12);
            $x = pow(1 + $r, $tenure);
            $emi = round(($loan_amount * $x * $r) / ($x - 1));
            $due = round($emi * $tenure);
            for ($y = 1; $y <= $tenure; $y++) {
                $repeat = strtotime("+1 month", strtotime($today));
                $today = date('Y-m-d', $repeat);
                $todayStamp = date('d-M-y D', $repeat);
                $builder_app = $db->table($table);
                $data = [

                    'emi'               => $emi,
                    'valueDate'         => $today,
                    'valueDateStamp'    => $todayStamp,
                    'balance'           => $due,
                ];

                $query = $builder_app->insert($data);
            }
            $data_loan = [

                'loan_status'      => $this->request->getPost('status'),
            ];
            $builder->where('applicationID', $applicationid);
            $builder->update($data_loan);
            $session = session();
            $session->setFlashdata('msg', 'Loan Status Updated!');
            return redirect()->to(base_url() . 'loan');
        } else {
            # code...
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

    public function status_count_of_loan()
    {
        $model = new LoanModel();
        $groupID = $this->request->getVar('groupID');

        $loanCapplied = $model->where('groupId', $groupID)->where('loan_status', 'Applied')->countAllResults();
        $loanCapproved = $model->where('groupId', $groupID)->where('loan_status', 'Approved')->countAllResults();
        $loanCdis = $model->where('groupId', $groupID)->where('loan_status', 'Disbursed')->countAllResults();

        if (!$groupID) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        }

        return $this->respond(
            [
                'totalApplied' => $loanCapplied,
                'totalApproved' => $loanCapproved,
                'totalDisbursed' => $loanCdis
            ],
            200
        );
    }
    public function loan_emi()
    {
        $loanID = $this->request->getVar('loanAC');
        $table = "tab_" . $loanID;
        $db = db_connect();

        if (!$loanID || !$db->tableExists($table)) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        } else {

            $builder_emi = $db->table($table);

            $query = $builder_emi->where('reference', 'N')->get();
            foreach ($query->getResult() as $row) {

                $response[] = array(
                    "emi_number" =>  $row->Id,
                    "emi" => $row->emi,
                    "valueDate" => $row->valueDate,
                    "valueDateStamp" => $row->valueDateStamp,
                    "statusCode" => 200,


                );
            }
            return $this->respond(
                $response,
                200
            );
        }
    }

    public function loan_emi_payment_status()
    {
        $loanID = $this->request->getVar('loanAC');
        $table = "tab_" . $loanID;
        $db = db_connect();

        if (!$loanID || !$db->tableExists($table)) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        } else {

            $builder_emi = $db->table($table);

            $queryCount = $builder_emi->where('reference', 'Y')->countAllResults();

            if ($queryCount > 0) {
                # code...
                $query = $builder_emi->where('reference', 'Y')->limit(3)->get();
                foreach ($query->getResult() as $row) {

                    $response[] = array(
                        "emi_number" =>  $row->Id,
                        "emi" => $row->emi,
                        "valueDate" => $row->valueDate,
                        "transactionDate" => $row->transactionDate,
                        "transactionId" => $row->transactionId,
                        "comments" => $row->comments,


                    );
                }
                return $this->respond(
                    $response,
                    200
                );
            } else {
                # code...
                return $this->respond(['error' => 'Invalid Request.'], 401);
            }
        }
    }

    public function disbursement_status()
    {
        $model = new LoanModel();

        $loanCapproved = $model->where('loan_status', 'Approved')->countAllResults();

        if ($loanCapproved > 0) {

            $loanGroupList = $model->join('groups', 'groups.g_id = loans.groupId')
                ->where('loan_status', 'Approved')->findAll();

            return $this->respond($loanGroupList, 200);
        } else {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        }
    }
}

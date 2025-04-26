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
        $loan_amount = $this->request->getPost('loan_amount');
        $tenure = $this->request->getPost('tenure');
        // Exact Date calculation
        $start = new \DateTime();
        $end = (clone $start)->modify("+$tenure months");
        $diff = $start->diff($end);
        $day_tenure =  $diff->days;


        // $day_tenure = $tenure * 30;
        $roi = $this->request->getVar('roi');
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
                'roi'               => $roi,
                'total_amount'      => $due,
            ];

            $builder->where('applicationID', $applicationid);
            $query = $builder->update($data);

            $session = session();
            $session->setFlashdata('msg', 'Loan Status Updated!');
            return redirect()->to(base_url() . 'loan');
        } elseif ($status == "Disbursed") {
            # code...
            $builder_app = $db->table($table);
            $count = $builder_app->countAll();
            if ($count < $day_tenure) {
                $today = date('Y-m-d');
                $r = ($roi / 100 / 12);
                $x = pow(1 + $r, $tenure);
                $emi = round(($loan_amount * $x * $r) / ($x - 1));

                $due = round($emi * $tenure);
                $final_emi = round($due / $day_tenure);
                $repeat = strtotime("+1 day", strtotime($today));
                $today = date('Y-m-d', $repeat);
                $todayStamp = date('d-M-y D', $repeat);
                $dataFirst = [

                    'emi'               => $final_emi,
                    'valueDate'         => $today,
                    'valueDateStamp'    => $todayStamp,
                    'balance'           => $due,
                    'reference'         => 'Due'
                ];
                $builder_app_f = $db->table($table);
                $builder_app_f->insert($dataFirst);
                # code...
                for ($y = 2; $y <= $day_tenure; $y++) {
                    $repeat = strtotime("+1 day", strtotime($today));
                    $today = date('Y-m-d', $repeat);
                    $todayStamp = date('d-M-y D', $repeat);

                    $data = [

                        'emi'               => $final_emi,
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
            }

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
        $employeeIDlos = $this->request->getVar('employeeIDlos');
        $loanCapplied = $model->where('groupId', $groupID)->where('loan_status', 'Applied')
            ->where('employee_id', $employeeIDlos)->countAllResults();
        $loanCapproved = $model->where('groupId', $groupID)->where('loan_status', 'Approved')
            ->where('employee_id', $employeeIDlos)->countAllResults();
        $loanCdis = $model->where('groupId', $groupID)->where('loan_status', 'Disbursed')
            ->where('employee_id', $employeeIDlos)->countAllResults();

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

            $query = $builder_emi->where('reference', 'N')->orWhere('reference', 'Due')->get();
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
        $agentID   = $this->request->getVar('employeeID');

        $loanCapproved = $model->where('loan_status', 'Approved')
            ->where('employee_id', $agentID)
            ->countAllResults();

        if ($loanCapproved > 0) {

            $loanGroupList = $model->join('groups', 'groups.g_id = loans.groupId')
                ->where('loan_status', 'Approved')
                ->where('employee_id', $agentID)->findAll();


            return $this->respond($loanGroupList, 200);
        } else {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        }
    }
    public function disbursement_status_member()
    {
        $model = new LoanModel();
        $groupID = $this->request->getVar('groupID');

        $loanCapproved = $model
            ->where('loans.groupId', $groupID)
            ->where('loan_status', 'Approved')->countAllResults();

        if ($loanCapproved > 0) {

            $loanMemberList = $model->join('members', 'members.member_id = loans.member_id')
                ->where('loan_status', 'Approved')->where('loans.groupId', $groupID)->findAll();

            return $this->respond($loanMemberList, 200);
        } else {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        }
    }
    public function disbursement_details_member()
    {
        $model = new LoanModel();
        $memberID = $this->request->getVar('memberID');

        if ($memberID) {

            $loanMemberDetail = $model->join('members', 'members.member_id = loans.member_id')
                ->where('members.member_id', $memberID)->first();

            return $this->respond($loanMemberDetail, 200);
        } else {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        }
    }
    public function disbursement_verification()
    {
        $model = new LoanModel();
        // $memberID = $this->request->getVar('memberID');
        $loanID = $this->request->getVar('loanIDV');
        $otpVerified = $this->request->getVar('otpS');
        $db = db_connect();
        $builder = $db->table('loans');
        if ($otpVerified === 'Y') {
            $data = [
                'loan_status' => 'Disbursed Verified',
                'otpVerify'    => $otpVerified,
            ];
            $builder->where('applicationID', $loanID)->update($data);

            return $this->respond(['status' => 'Disbursed Verified.'], 200);
        } else {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        }
    }

    // Collection
    public function collection_status()
    {
        $model = new LoanModel();
        $agentID   = $this->request->getVar('employeeID');

        $loanCdisbursed = $model->where('loan_status', 'Disbursed')
            ->where('employee_id', $agentID)
            ->countAllResults();

        if ($loanCdisbursed > 0) {

            $loanMemberList = $model->join('members', 'members.member_id = loans.member_id')
                ->where('loans.loan_status', 'Disbursed')
                ->where('employee_id', $agentID)->findAll();

            return $this->respond($loanMemberList, 200);
        } else {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        }
    }
    public function collection_details_member()
    {
        $model = new LoanModel();
        $memberID = $this->request->getVar('memberID');

        if ($memberID) {

            $loanMemberDetail = $model->join('members', 'members.member_id = loans.member_id')
                ->where('members.member_id', $memberID)->first();

            return $this->respond($loanMemberDetail, 200);
        } else {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        }
    }

    public function collection_details_submit()
    {
        // $model = new LoanModel();
        // $memberID = $this->request->getVar('memberID');
        $loanID = $this->request->getVar('loanIDV');
        $emiPending = $this->request->getVar('pendingEmi');
        $db = db_connect();
        $builder = $db->table('tab_' . $loanID);
        $data = [
            'credit'            => $this->request->getVar('paymentReceive'),
            'balance'           => $this->request->getVar('loanDueApi'),
            'reference'         => $this->request->getVar('ref'),
            'comments'          => $this->request->getVar('selectRadio'),
            'transactionId'     => uniqid() . $this->request->getVar('memberID'),
            'transactionDate'   => date('Y-m-d H:i:s'),
            'updated_on'        => date('Y-m-d H:i:s')

        ];
        $q = $builder->where('reference', 'Due')->update($data);
        $dataFirst = [

            'reference'         => 'Due'
        ];
        $p = $builder->where('reference', 'N')->limit(1)->update($dataFirst);

        for ($y = 2; $y <= $emiPending; $y++) {
            $dataLoop = [
                'balance'           => $this->request->getVar('loanDueApi'),
            ];

            $query = $builder->update($dataLoop);
        }
        // Data Set For Loan Master Table
        $builderMaster = $db->table('loans');
        $dataMaster = [

            'loan_due'           => $this->request->getVar('loanDueApi'),
            'pending_emi'         => round($emiPending - 1),
            'updated_at'        => date('Y-m-d H:i:s')

        ];
        $builderMaster->where('applicationID', $loanID)->update($dataMaster);


        return $this->respond(['status' => 'Collection Status Updated.'], 200);
    }

    public function total_gr_disbursed()
    {
        $model = new LoanModel();
        $groupID = $this->request->getVar('groupID');
        $employeeIDis = $this->request->getVar('employeeIDis');
        if (!$groupID) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        } else {
            $totalDisbursed = $model->selectSum('loan_amount')->where('groupId', $groupID)->where('employee_id', $employeeIDis)->where('loan_status', 'Disbursed')->get();
            foreach ($totalDisbursed->getResult() as $rowD) {
                return $this->respond(
                    $rowD,
                    200
                );
            }
        }
    }
    public function total_gr_outstanding()
    {
        $model = new LoanModel();
        $groupID = $this->request->getVar('groupID');
        $employeeIOut = $this->request->getVar('employeeIOut');
        if (!$groupID) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        } else {
            $totalDue = $model->selectSum('loan_due')->where('groupId', $groupID)->where('employee_id', $employeeIOut)->where('loan_status', 'Disbursed')->get();
            foreach ($totalDue->getResult() as $rowDue) {
                return $this->respond(
                    $rowDue,
                    200
                );
            }
        }
    }
    public function diposite_details_member()
    {
        $model = new LoanModel();
        $memberID = $this->request->getVar('memberID');

        if ($memberID) {

            $loanMemberDetail = $model->join('members', 'members.member_id = loans.member_id')
                ->where('members.member_id', $memberID)->first();

            return $this->respond($loanMemberDetail, 200);
        } else {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        }
    }

    public function total_outstanding()
    {
        $model = new LoanModel();
        $empID = $this->request->getVar('employeeID');
        if (!$empID) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        } else {
            $totalDue = $model->selectSum('loan_due')->where('employee_id', $empID)->where('loan_status', 'Disbursed')->get();
            foreach ($totalDue->getResult() as $rowDue) {
                return $this->respond(
                    $rowDue,
                    200
                );
            }
        }
    }
    public function total_disbursed()
    {
        $model = new LoanModel();
        $empID = $this->request->getVar('employeeID');
        if (!$empID) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        } else {
            $totalDisbursed = $model->selectSum('total_amount')->where('employee_id', $empID)->where('loan_status', 'Disbursed')->get();
            foreach ($totalDisbursed->getResult() as $rowD) {
                return $this->respond(
                    $rowD,
                    200
                );
            }
        }
    }
    public function total_outstanding_month()
    {
        $model = new LoanModel();
        $empID = $this->request->getVar('employeeID');
        if (!$empID) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        } else {
            $totalDue = $model->selectSum('loan_due')->where('employee_id', $empID)
                ->where('loan_status', 'Disbursed')
                ->where('MONTH(created_at)', date('m'))
                ->get();
            foreach ($totalDue->getResult() as $rowDue) {
                return $this->respond(
                    $rowDue,
                    200
                );
            }
        }
    }
    public function total_disbursed_month()
    {
        $model = new LoanModel();
        $empID = $this->request->getVar('employeeID');
        if (!$empID) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        } else {
            $totalDisbursed = $model->selectSum('total_amount')->where('employee_id', $empID)
                ->where('loan_status', 'Disbursed')
                ->where('MONTH(created_at)', date('m'))
                ->get();
            foreach ($totalDisbursed->getResult() as $rowD) {
                return $this->respond(
                    $rowD,
                    200
                );
            }
        }
    }
}

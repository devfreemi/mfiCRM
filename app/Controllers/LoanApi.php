<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\LoanModel;
use CodeIgniter\API\ResponseTrait;

date_default_timezone_set('Asia/Kolkata');
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
    /**
     * Create a new loan application.
     *
     * 
     */
    // This function is used to create a new loan application.
    public function loan_create()
    {
        //
        $model = new LoanModel();
        $data = [

            'groupId'       => $this->request->getPost('groupID'),
            'member_id'     => $this->request->getPost('memberID'),
            'loan_amount'   => $this->request->getPost('loan_amount'),
            'loan_tenure'   => $this->request->getPost('tenure'),
            'roi'           => $this->request->getPost('roi'),
            'employee_id'   => $this->request->getPost('employeeID'),
            'loan_status'     => $this->request->getPost('status'),
            'applicationID' => date('ym') . str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT) . $this->request->getPost('mobile'),

        ];
        // $query = $model->insert($data);
        $query = $model->save($data);
        if (!$query) {
            return $this->respond(['error' => 'Invalid Request.' . $query], 401);
        } else {
            # code...
            $session = session();
            $session->setFlashdata('msg', 'Loan Status Updated!');
            return redirect()->to(base_url() . 'loan');
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
        $roi = $this->request->getPost('roi');
        if ($status == "Approved") {
            # code...
            $model->create($table);

            $r = ($roi / 100 / 12);

            // Total interest (Flat): (P × R × N years)
            $interest = ($loan_amount * $roi * ($tenure / 12)) / 100;

            // Total amount payable
            $due = round($loan_amount + $interest);

            // Flat EMI: Total payable / total months
            $emi = round($due / $tenure, 2);
            $disbursable = round($loan_amount - ($loan_amount * 0.04));
            $chargesandinsurance = round($loan_amount * 0.04);
            $data = [

                'loan_status'       => "Approved",
                'loan_amount'      => $loan_amount,
                'loan_tenure'       => $tenure,
                'emi'               =>  $emi,
                'interest'          =>  $interest,
                'pending_emi'       =>  $day_tenure,
                'loan_due'          => $due,
                'emi_day'          => round($due / $day_tenure, 2),
                'roi'               => $roi,
                'total_amount'      => $due,
                'disbursable_amount' => $disbursable,
                'chargesandinsurance' => $chargesandinsurance,
                'updated_at'      => date('Y-m-d H:i:s'),

            ];

            $builder->where('applicationID', $applicationid);
            $query = $builder->update($data);

            $session = session();
            $session->setFlashdata('msg', 'Loan Status Updated!');
            return redirect()->to(base_url() . 'loan');
        } elseif ($status == "FI Initiated") {
            # code...
            $model->create($table);

            $r = ($roi / 100 / 12);

            // Total interest (Flat): (P × R × N years)
            $interest = ($loan_amount * $roi * ($tenure / 12)) / 100;

            // Total amount payable
            $due = round($loan_amount + $interest);

            // Flat EMI: Total payable / total months
            $emi = round($due / $tenure);
            $disbursable = round($loan_amount - ($loan_amount * 0.04));
            $chargesandinsurance = round($loan_amount * 0.04);
            $data = [

                'loan_status'       => "FI Initiated",
                'loan_amount'      => $loan_amount,
                'loan_tenure'       => $tenure,

                'updated_at'      => date('Y-m-d H:i:s'),

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
                // $r = ($roi / 100 / 12);
                // $x = pow(1 + $r, $tenure);
                // $emi = round(($loan_amount * $x * $r) / ($x - 1));
                // $due = round($emi * $tenure);
                // Monthly interest rate is not needed in flat interest, but keeping variable structure
                $r = ($roi / 100 / 12);

                // Total interest (Flat): (P × R × N years)
                $interest = ($loan_amount * $roi * ($tenure / 12)) / 100;

                // Total amount payable
                $due = round($loan_amount + $interest);

                // Flat EMI: Total payable / total months
                $emi = round($due / $tenure);
                $final_emi = round($due / $day_tenure, 2);
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
                    'updated_at'      => date('Y-m-d H:i:s'),
                ];
                $builder->where('applicationID', $applicationid);
                $builder->update($data_loan);
            }
            // Update in disbursement table
            // Save the file
            $file = $this->request->getFile('bank_receipt');
            if ($file->isValid() && !$file->hasMoved()) {
                $fileName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/bank_receipts', $fileName);
                $bankReceipt = 'uploads/bank_receipts/' . $fileName;
            }
            $data_dis = [
                'bank_receipt' => $bankReceipt,
                'disbursed_amount' => $this->request->getPost('disbursed_amount'),
                'applicationID' => $applicationid,
                'retailer_id'   => $this->request->getPost('member_id'),
                'transaction_id' => $this->request->getPost('transaction_id'),
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $builder_dis = $db->table('disbursement');
            $builder_dis->insert($data_dis);
            // cURL Code for Send SMS to member
            $dataSms = array(
                "name" => $this->request->getPost('member_name'),
                "phone" => $this->request->getPost('member_phone'),
                "amount" => $this->request->getPost('loan_amount'),
                "account_last4" => substr($this->request->getPost('member_bank_account'), -4),

            );
            $dataSmsJson = json_encode($dataSms);
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://otp.retailpe.in/api/sms-disbursement/send",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $dataSmsJson,
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                log_message('error', 'cURL Error: ' . $err);
            } else {
                log_message('info', 'SMS sent successfully: ' . $response);
            }
            // cURL Code END
            // Mail Code Start
            // Initialize and configure email
            $message = '
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <title>Loan Disbursal Confirmation - Retail Pe</title>
                    <style>
                        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
                        .container { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
                        .header { background: #6819e6; color: white; padding: 20px; text-align: center; }
                        .header img { height: 50px; }
                        .banner { display: flex; background: #dcd3ff9e; padding: 8px 20px; align-items: center; gap: 20px; }
                        .icon-circle {
                            background: linear-gradient(145deg, #e0d4fb, #ffffff);
                            border-radius: 50%;
                            padding: 18px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            box-shadow: 0 2px 8px rgba(104, 25, 230, 0.2);
                        }
                        .icon-circle img {
                            height: 55px;
                            width: 55px;
                        }
                        h2 {
                                color: #6819e6;
                                margin: 0;
                                font-size: 18px;
                                text-align: justify;
                                padding: 32px;
                        }
                        .content { padding: 30px; color: #333; }
                        .content h2 { color: #6819e6; margin: 0; font-size: 22px; }
                        .content p { font-size: 15px; line-height: 1.6; }
                        .highlight { font-weight: bold; color: #6819e6; }
                        .footer { background: #6819e6; color: #fff; padding: 20px; text-align: center; font-size: 13px; line-height: 1.5; }
                        a { color: #007bff; text-decoration: none; }
                        .button { text-align: center; margin: 30px 0; }
                        .button a { background-color: #6819e6; color: #ffffff; padding: 12px 25px; border-radius: 6px; text-decoration: none; font-size: 16px; font-weight: bold; }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <div class="header">
                            <img src="https://crm.retailpe.in/assets/img/icons/brands/RetailPeMail.png" alt="Retail Pe">
                        </div>
                        <div class="banner">
                            <div class="icon-circle">
                                <img src="https://crm.retailpe.in/assets/img/icons/brands/rupee.png" alt="Money Icon">
                            </div>
                            <h2>Cash Loan Disbursed</h2>
                        </div>
                        <div class="content">
                            <p><strong>Dear ' . esc($this->request->getPost('member_name')) . ',</strong></p>
                            <p>We are excited to inform you that your Retail Loan of ₹' . number_format($this->request->getPost('loan_amount')) . ' with <span class="highlight">Retail Pe</span> has been successfully disbursed.</p>

                            <p><strong>Loan Account Number:</strong> ' . esc($this->request->getPost('applicationid')) . '<br>
                            <strong>Total Loan Amount:</strong> ₹' . number_format($this->request->getPost('loan_amount')) . '<br>
                            <strong>Start Date:</strong> ' . esc($this->request->getPost('first_dei_date')) . '</p>

                            <p>If you require any assistance regarding your Retail Loan, please <a href="mailto:support@retailpe.in">click here</a> to contact our customer support team.</p>

                            <p>Please ensure timely Installment payments to maintain a healthy credit record and continue enjoying financial services from <strong>Retail Pe</strong>.</p>
                            <p>We appreciate your trust and look forward to supporting your business growth.</p>

                            <div class="button">
                                <a href="https://retailpe.in">Visit Our Site</a>
                            </div>

                            <p>Warm regards,<br>
                            <strong><span class="highlight">Retail Pe</span> Team</strong></p>
                        </div>
                        <div class="footer">
                            Ntactus Financial Services Private Limited<br>
                            Mani Casadona,<br>
                            Street Number 372, Action Area I,<br>
                            IIF Newtown, Kolkata - 700156
                        </div>
                    </div>
                </body>
                </html>';
            $email = \Config\Services::email();
            $email->initialize([
                'protocol'   => 'smtp',
                'SMTPHost'   => 'smtp.hostinger.com',
                'SMTPUser'   => 'noreply@retailpe.in',
                'SMTPPass'   => 'Noreply@2025#', // Use Gmail App Password
                'SMTPPort'   => 587,
                'SMTPCrypto' => 'tls',
                'mailType'   => 'html',
                'charset'    => 'utf-8',
                'newline'    => "\r\n",
                'crlf'       => "\r\n"
            ]);

            // Set email params
            $email->setFrom('noreply@retailpe.in', 'Retail Pe');
            $email->setTo($this->request->getPost('customer_email')); // Change recipient as needed
            $email->setCC('kousik@retailpe.in');
            $email->setSubject("Congratulations! Your Loan of ₹ " . number_format($this->request->getPost('loan_amount'), 2) . " Has Been Disbursed");
            $email->setMessage($message);

            // Send and respond
            if ($email->send()) {
                log_message('info', 'Mail Sent Successfully: ' . $this->request->getPost('customer_email'));
            } else {
                log_message('error', 'Failed to send email: ' . $email->printDebugger(['headers']));
            }
            $session = session();
            $session->setFlashdata('success', 'Loan Status Updated!');
            return redirect()->to(base_url() . 'disbursement/details/' . $applicationid);
        } else {
            # code...
            $data = [

                'loan_status'      => $this->request->getPost('status'),
                'updated_at'      => date('Y-m-d H:i:s'),
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
            log_message('error', 'Invalid Request: Loan ID or table does not exist.');
        } else {

            $builder_emi = $db->table($table);

            // $query = $builder_emi->where('reference', 'N')->orWhere('reference', 'Due')->get();
            $query = $builder_emi->get();
            foreach ($query->getResult() as $row) {

                $response[] = array(
                    "emi_number" =>  $row->Id,
                    "emi" => $row->emi,
                    "valueDate" => $row->valueDate,
                    "reference" => $row->reference,
                    "valueDateStamp" => $row->valueDateStamp,
                    "statusCode" => 200,


                );
            }
            return $this->respond(
                $response,
                200
            );
            log_message('info', 'Success.');
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
        $member_id = $this->request->getVar('memberID');

        if ($member_id) {
            $loanMemberDetail = $model->join('members', 'members.member_id = loans.member_id')
                ->where('members.member_id', $member_id)->first();
            return $this->respond($loanMemberDetail, 200);
        } else {
            return $this->respond(['error' => 'Invalid Request Member Id.'], 401);
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
            $totalDisbursed = $model->selectSum('loan_amount', 'total_amount')->where('employee_id', $empID)->where('loan_status', 'Disbursed')->get();
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

    public function disbursement_details($applicationID)
    {
        // $session = session();
        $model = new LoanModel();
        $data['disbursement'] = $model->where('applicationID', $applicationID)->first();


        return view('disbursement_details', $data);
        // print_r($data);
        // echo $memberID;
    }
}

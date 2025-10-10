<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;


date_default_timezone_set('Asia/Kolkata');
class PaymentController extends BaseController
{
    use ResponseTrait;

    // GET TODAYS EMI PAYMENT
    public function get_today_emi()
    {
        $memberID = $this->request->getVar('memberID');
        if (empty($memberID)) {
            return $this->respond(['error' => 'Member ID is required'], 400);
        }

        $db = db_connect();
        $today = date('Y-m-d');

        // Fetch loans with member info
        $builder = $db->table('loans');
        $builder->select('loans.*, members.name as member_name,members.mobile as member_mobile');
        $builder->join('members', 'members.member_id = loans.member_id');
        $builder->where('loans.member_id', $memberID);

        $query = $builder->get();
        $loans = $query->getResultArray();

        if (empty($loans)) {
            return $this->failNotFound('No loans found for this member.');
        }

        $loanID = null;
        $memberName = '';
        $memberMobile = '';
        $data['total_emi'] = 0;

        foreach ($loans as &$loan) {
            $loanID = $loan['applicationID'] ?? $loan['loan_id'] ?? null;
            $memberName = $loan['member_name'] ?? 'Unknown Member';
            $memberMobile = $loan['member_mobile'] ?? 'Unknown Number';

            if (!$loanID) {
                continue;
            }

            $tableName = 'tab_' . esc($loanID);

            try {

                // // --- PART 1: Unpaid EMIs before today (add 10% penalty)
                // $builderLoanPast = $db->table($tableName);
                // $builderLoanPast->selectSum('emi', 'past_emi');
                // $builderLoanPast->whereIn('reference', ['Due', 'N']);
                // $builderLoanPast->where('valueDate <', $today);
                // $queryPast = $builderLoanPast->get();
                // $rowPast = $queryPast->getRow();

                // $pastEMI = $rowPast && $rowPast->past_emi !== null ? (float)$rowPast->past_emi : 0;

                // $penalty = round($pastEMI * 0.10, 2);
                // // $penalty = 0;
                // $pastTotal = $pastEMI + $penalty;
                // --- PART 1: Unpaid EMIs before today (daily 10% penalty)
                $builderLoanPast = $db->table($tableName);
                $builderLoanPast->select('emi, valueDate');
                $builderLoanPast->whereIn('reference', ['Due', 'N']);
                $builderLoanPast->where('valueDate <', $today);
                $queryPast = $builderLoanPast->get();
                $pastRows = $queryPast->getResultArray();

                $pastEMI = 0;
                $penalty = 0;

                foreach ($pastRows as $row) {
                    $emiAmount = (float)$row['emi'];
                    $emiDate   = $row['valueDate'];

                    $pastEMI += $emiAmount;

                    // Calculate overdue days
                    $overdueDays = (strtotime($today) - strtotime($emiDate)) / (60 * 60 * 24);

                    if ($overdueDays > 0) {
                        // Add penalty = emi * 10% * overdue days
                        $penalty += round($emiAmount * 0.10 * $overdueDays, 2);
                    }
                }

                $pastTotal = $pastEMI + $penalty;
                // --- PART 2: Today's EMI
                $builderLoanToday = $db->table($tableName);
                $builderLoanToday->selectSum('emi', 'today_emi');
                $builderLoanToday->whereIn('reference', ['Due', 'N']);
                $builderLoanToday->where('valueDate', $today);
                $queryToday = $builderLoanToday->get();
                $rowToday = $queryToday->getRow();

                $todayEMI = $rowToday && $rowToday->today_emi !== null ? (float)$rowToday->today_emi : 0;

                // --- FINAL: Total EMI = today's + past with penalty
                $totalWithPenalty = $todayEMI + $pastTotal;
                $data['total_emi'] += $totalWithPenalty;
            } catch (\Exception $e) {
                // If table error or missing, skip
                continue;
            }
        }

        unset($loan); // prevent accidental reference

        // return $this->respond([
        //     'loanIDs' => $loanID,
        //     'member_name' => $memberName,
        //     'member_id' => $memberID,
        //     'member_mobile' => '+91' . $memberMobile,
        //     'total_emi' => $data['total_emi']
        // ], 200);
        return $this->respond([
            'loanIDs' => $loanID,
            'member_name' => $memberName,
            'member_id' => $memberID,
            'member_mobile' => '+91' . $memberMobile,
            'total_emi' => $data['total_emi']
        ], 200);
    }

    // Generate Order and initiate payment
    // This function will create an order with Cashfree and return the payment link
    // It will also return the order ID and other details needed for the payment

    public function generate_order()
    {
        $db = db_connect();
        // This function can be used to display a payment form or redirect to payment initiation
        $amount = $this->request->getVar('total_emi');
        $member_name = $this->request->getVar('member_name') ?? 'Unknown Member';
        $member_id = $this->request->getVar('member_id') ?? 'Unknown Member';
        $member_mobile = $this->request->getVar('member_mobile') ?? 'Unknown Member';
        $loanID = $this->request->getVar('loanIDs');
        log_message('info', "Generate Order called for LoanID: $loanID, MemberID: $member_id, Amount: $amount");
        $dataApi = array(
            'amount'              => round($amount * 100),
            'currency'          => "INR",
            'receipt'           => $loanID . date('YmdHis'),
            'notes' => array(
                'customer_id'          => $member_id,
                'customer_name'          => $member_name,
                'customer_phone'      => $member_mobile,
            ),
            // 'order_meta' => array(
            //     'return_url'          => base_url() . 'payment/conformation',
            //     'payment_methods'       => "nb,dc,upi"
            // ),
        );

        $data_json = json_encode($dataApi);
        log_message('info', 'Order Request: ' . $data_json);
        // print_r($data_json);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.razorpay.com/v1/orders",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data_json,
            // CURLOPT_USERPWD => "rzp_live_RAdEZU6v2cdsZY:6YfsReB4zIaUGIO7ykcAG6yb",
            CURLOPT_USERPWD => "rzp_test_R79rNPBPhEZBrk:cOMqa78NIijH0FboT9WoRrer",
            CURLOPT_HTTPHEADER => array(

                'Content-Type: application/json',
                'Accept: application/json',
            ),
        ));

        $response = curl_exec($curl);
        $response_decode = json_decode($response, true);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        log_message('info', 'Order Response: ' . $response);

        if ($httpcode === 200) {
            # code...
            $builderEmi = $db->table('tab_' . esc($loanID));
            $data = [
                'paymentSession'            => $response_decode['receipt'],
                'orderId'                   => $response_decode['id'],
                'paymentStatus'             => $response_decode['status'],
                'updated_on'                => date('Y-m-d H:i:s')

            ];
            $builderEmi->where('reference', 'Due')->update($data);
            return $this->respond(['order_created' => $response_decode], 200);
        } else {
            # code...
            // Generic failure response
            $errors = $response_decode['message'] ?? 'Failed to create order. Please try again.';
            // $this->respond($errors, 400);
            return $this->respond(['error' => $errors], $httpcode);
        }



        curl_close($curl);
        // UPDATE EMI TABLE AFTER PAYMENT

    }

    // Get payment response from Cashfree
    // This function will be called by Cashfree after payment is completed
    public function conformation()
    {

        $orderId = $this->request->getVar('order_id_success');
        $loanID = $this->request->getVar('loan_id');
        $paymentAmount = $this->request->getVar('order_amount_success');
        $payment_signature = $this->request->getVar('payment_signature');
        $payment_id = $this->request->getVar('payment_id');

        // if (!$loanID || !$orderId) {
        //     return redirect()->to('/')->with('error', 'Missing required payment details.');
        // }
        log_message('info', 'Payment Confirmation called for LoanID: ' . $loanID . ', OrderID: ' . $orderId . ', Amount: '
            . $paymentAmount . ', Payment Signature: ' . $payment_signature);
        $generateSignature = hash_hmac('sha256', $orderId . "|" . $payment_id, '6YfsReB4zIaUGIO7ykcAG6yb');

        log_message('debug', 'Razorpay API Signature: ' . $generateSignature);

        $status = 'FAILED';
        $paymentData = [];

        if ($generateSignature == $payment_signature) {
            $status = 'SUCCESS';
            // $paymentData = $response_decode[0];
            // Build payment data array
            $paymentData = [
                'order_id'        => $orderId,
                'loan_id'         => $loanID,
                'payment_amount'  => $paymentAmount,
                'payment_status'  => $status,
                'payment_signature' => $payment_signature,
                'cf_payment_id'   => $payment_id,
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ];
            if ($status === 'SUCCESS') {
                $db = \Config\Database::connect();
                $builder = $db->table('transaction_master');

                try {
                    $inserted = $builder->insert($paymentData);
                    log_message('info', 'DB Insert Success: ' . $inserted);
                    if (!$inserted) {
                        // Log DB error but don’t stop the process
                        log_message('error', 'Transaction insert failed for order_id: ' . $orderId);
                    }
                } catch (\Exception $e) {
                    // Log exception but don’t stop the process
                    log_message('error', 'DB Insert Exception: ' . $e->getMessage());
                }

                // Continue with EMI Payment even if insert failed
                // $this->processEMIPayment($paymentData, $loanID, $orderId);
                $this->processEMIPayment($paymentData, $loanID, $orderId);
            }
        }
        log_message('info', 'Payment Success: LoanID=' . $loanID .
            ', OrderID=' . $orderId .
            ', Status=' . $status .
            ', Amount=' . $paymentAmount .
            ', PaymentData=' . json_encode($paymentData));

        return $this->respond(['payment' => [
            'status'        => $status,
            // 'paymentData'   => $paymentData,
            'loanId'        => $loanID,
            'orderID'       => $orderId,
            'order_amount'  => $paymentAmount
        ]], 200);
    }




    public function initiate_payment()
    {
        //
        $session = session();
        $amount = $this->request->getVar('amount');
        $loan_id = $this->request->getVar('loan_id');
        $db = db_connect();
        $builder = $db->table('loans');
        $builder->select('*');
        $builder->where('applicationID', $loan_id);
        $builder->join('members', 'members.member_id = loans.member_id');
        $query = $builder->get();
        foreach ($query->getResult() as $row) {
            $customer_name = $row->name;

            // $amount = $this->request->getVar('amount');
            // $dataApi = array(
            //     'order_amount'              => $amount,
            //     'order_currency'          => "INR",
            //     'customer_details' => array(
            //         'customer_id'          => $row->member_id,
            //         'customer_name'          => $customer_name,
            //         'customer_phone'      => $row->mobile,
            //     ),
            //     'order_meta' => array(
            //         'return_url'          => base_url() . 'payment/details?id=' . $loan_id,
            //         'payment_methods_filters'          => array(
            //             'method'          => array(
            //                 'action'          => "ALLOW",
            //                 'values'          =>  array(
            //                     "debit_card",
            //                     "netbanking",
            //                     "upi"
            //                 ),
            //             ),
            //         ),
            //     ),
            // );
            $dataApi = array(
                'order_amount'              => number_format($amount),
                'order_currency'          => "INR",
                'customer_details' => array(
                    'customer_id'          => $row->member_id,
                    'customer_name'          => $customer_name,
                    'customer_phone'      => $row->mobile,
                ),
                'order_meta' => array(
                    'return_url'          => base_url() . 'payment/conformation',
                    'payment_methods'       => "nb,dc,upi"
                ),
            );
        }
        $data_json = json_encode($dataApi);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.cashfree.com/pg/orders",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data_json,
            CURLOPT_HTTPHEADER => array(

                'x-api-version: 2025-01-01',
                'Content-Type: application/json',
                'Accept: application/json',
            ),
        ));

        $response = curl_exec($curl);
        $response_decode = json_decode($response, true);
        $err = curl_error($curl);
        // print_r($response);



        curl_close($curl);
        if ($err) {
            // echo "cURL Error #:" . $err;
            return $this->respond(['error' => 'Invalid Request.' . $err], 401);
        } else {
            // echo $response;
            return $this->respond(['payment_response' => $response_decode], 200);
        }
    }

    public function details()
    {
        $loanId = $this->request->getGet('id'); // Get from query string
        return view('payment_details', ['loanID' => $loanId]);
    }
    public function pay_conf()
    {
        return view('payment_conf');
    }

    // Redirect from app of retailer to payment page
    public function app_payment_collection()
    {
        $loanId = $this->request->getGet('loanID'); // Get from query string
        $name = $this->request->getGet('name');
        $amount = $this->request->getGet('amount');
        $orderID = $this->request->getGet('orderID');
        $paymentSessionId = $this->request->getGet('paymentSessionId');
        return view('payment_redirect', [
            'loanID' => $loanId,
            'orderID' => $orderID,
            'name' => $name,
            'amount' => $amount,
            'paymentSessionId' => $paymentSessionId
        ]);
    }
    public function app_pay_conf()
    {
        $loanId = $this->request->getGet('loanId');
        $orderID = $this->request->getGet('orderID');
        $order_amount = $this->request->getGet('order_amount');

        if (!$loanId || !$orderID) {
            return redirect()->to('/')->with('error', 'Missing required payment details.');
        }


        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.cashfree.com/pg/orders/{$orderID}/payments",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [

                "x-api-version: 2025-01-01",
                "Content-Type: application/json",
                "Accept: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $response_decode = json_decode($response, true);
        log_message('debug', 'Cashfree API Response: ' . print_r($response_decode, true));

        $status = 'FAILED';
        $paymentData = [];

        if ($httpcode === 200 && isset($response_decode[0]['payment_status'])) {
            $status = $response_decode[0]['payment_status'];
            $paymentData = $response_decode[0];

            if ($status === 'SUCCESS') {
                $this->processEMIPayment($paymentData, $loanId, $orderID);
            }
        }

        return view('payment_conf_app', [
            'status'        => $status,
            'paymentData'   => $paymentData,
            'loanId'        => $loanId,
            'orderID'       => $orderID,
            'order_amount'  => $order_amount
        ]);
    }

    private function processEMIPayment($paymentInfo, $loanId, $orderId)
    {
        $db = db_connect();
        $emiTable = 'tab_' . esc($loanId);
        $paymentAmount = (float) $paymentInfo['payment_amount'];
        $paymentStatus = $paymentInfo['payment_status'];
        $txnID = 'TXN_' . $paymentInfo['cf_payment_id'];
        $now = date('Y-m-d H:i:s');

        log_message('info', "[START] Processing payment for LoanID: {$loanId}, Payment Amount: {$paymentAmount}, Status: {$paymentStatus}");

        // 🔹 Step 0: Fetch Loan
        $loan = $db->table('loans')->where('applicationID', $loanId)->get()->getRow();
        if (!$loan) {
            log_message('error', "[ERROR] Loan not found for applicationID: {$loanId}");
            return;
        }

        $emiPending = (int) $loan->pending_emi;
        $loanDue    = (float) $loan->loan_due;

        $totalEmiPaid = 0;   // ✅ Track actual paid amount
        $emiCountPaid = 0;

        log_message('info', "[LOAN INFO] LoanID {$loanId}, Current Loan Due: {$loanDue}, Pending EMI: {$emiPending}");

        // 🔹 Step 1: Pay Due EMIs
        log_message('info', "[STEP 1] Checking Due EMIs for LoanID {$loanId}");
        $emiRows = $db->table($emiTable)->where('reference', 'Due')->orderBy('Id', 'ASC')->get()->getResult();

        foreach ($emiRows as $emi) {
            $emiValue = round($emi->emi, 2);
            $today = date('Y-m-d');

            // --- Penalty Calculation ---
            $builderLoanPast = $db->table($emiTable);
            $builderLoanPast->selectSum('emi', 'past_emi');
            $builderLoanPast->whereIn('reference', ['Due', 'N']);
            $builderLoanPast->where('valueDate <', $today);
            $rowPast = $builderLoanPast->get()->getRow();

            $pastEMI = $rowPast && $rowPast->past_emi !== null ? (float) $rowPast->past_emi : 0;
            $penalty = round($pastEMI * 0.10, 2);

            log_message('info', "[PENALTY] LoanID {$loanId}, Past EMI: {$pastEMI}, Penalty: {$penalty}");

            if ($penalty > 0) {
                $db->table($emiTable)->where('Id', $emi->Id)->update(['credit' => $penalty]);
                log_message('info', "[PENALTY APPLIED] LoanID {$loanId}, EMI ID {$emi->Id}, Penalty Deducted: {$penalty}");
                $paymentAmount -= $penalty;

                $paymentAmount = round($paymentAmount, 2);
            }

            // --- EMI Settlement ---
            if ($paymentAmount >= $emiValue) {
                $db->table($emiTable)->where('Id', $emi->Id)->update([
                    'reference'       => 'Y',
                    'paymentStatus'   => $paymentStatus,
                    'transactionDate' => $now,
                    'updated_on'      => $now,
                    'orderId'         => $orderId,
                    'transactionId'   => $txnID . "_" . date('YmdHis'),
                ]);
                $paymentAmount -= $emiValue;
                $totalEmiPaid += $emiValue;
                $emiCountPaid += 1;

                log_message('info', "[EMI CLEARED] LoanID {$loanId}, EMI ID {$emi->Id}, Paid: {$emiValue}, Remaining Payment: {$paymentAmount}");
            } else {
                if ($paymentAmount > 0) {
                    $db->table($emiTable)->where('Id', $emi->Id)->update([
                        'paymentStatus'   => $paymentStatus,
                        'transactionDate' => $now,
                        'updated_on'      => $now,
                        'transactionId'   => $txnID . "_" . date('YmdHis'),
                    ]);
                    log_message('info', "[PARTIAL EMI PAYMENT] LoanID {$loanId}, EMI ID {$emi->Id}, Paid: {$paymentAmount}");
                    $totalEmiPaid += $paymentAmount;
                    $paymentAmount = 0;
                }
                break;
            }
        }

        // 🔹 Step 2: Pay Upcoming EMIs (if money still left)

        $paymentAmount = round($paymentAmount, 2);
        if ($paymentAmount > 0) {
            log_message('info', "[STEP 2] Processing Upcoming EMIs for LoanID {$loanId}, Remaining Payment: {$paymentAmount}");

            $upcomingEMIs = $db->table($emiTable)->where('reference', 'N')->orderBy('Id', 'ASC')->get()->getResult();
            foreach ($upcomingEMIs as $emi) {
                $emiValue = round($emiValue, 2);
                if ($paymentAmount >= $emiValue) {
                    $db->table($emiTable)->where('Id', $emi->Id)->update([
                        'reference'       => 'Y',
                        'paymentStatus'   => $paymentStatus,
                        'transactionDate' => $now,
                        'updated_on'      => $now,
                        'orderId'         => $orderId,
                        'transactionId'   => $txnID . "_" . date('YmdHis'),
                    ]);
                    // $paymentAmount -= $emiValue;
                    $paymentAmount = round($paymentAmount - $emiValue, 2);
                    $totalEmiPaid += $emiValue;
                    $emiCountPaid += 1;

                    log_message('info', "[UPCOMING EMI CLEARED] LoanID {$loanId}, EMI ID {$emi->Id}, Paid: {$emiValue}, Remaining Payment: {$paymentAmount}");
                } else {
                    if ($paymentAmount > 0) {
                        $db->table($emiTable)->where('Id', $emi->Id)->update([
                            'reference'       => 'Y',
                            'emi'             => $emiValue - $paymentAmount, // Ensure EMI value remains unchanged
                            'paymentStatus'   => $paymentStatus,
                            'transactionDate' => $now,
                            'updated_on'      => $now,
                            'transactionId'   => $txnID . "_" . date('YmdHis'),
                        ]);
                        log_message('info', "[PARTIAL UPCOMING EMI] LoanID {$loanId}, EMI ID {$emi->Id}, Paid: {$paymentAmount}");
                        $totalEmiPaid += $paymentAmount;
                        $paymentAmount = 0;
                    }
                    break;
                }
            }
        }

        // 🔹 Step 3: Mark next EMI as Due
        $nextEmi = $db->table($emiTable)->where('reference', 'N')->orderBy('Id', 'ASC')->limit(1)->get()->getRow();
        if ($nextEmi) {
            $db->table($emiTable)->where('Id', $nextEmi->Id)->update(['reference' => 'Due']);
            log_message('info', "[STEP 3] Next EMI marked as Due. LoanID {$loanId}, EMI ID {$nextEmi->Id}");
        } else {
            log_message('info', "[STEP 3] No Upcoming EMI found to mark as Due. LoanID {$loanId}");
        }

        // 🔹 Step 4: Update loan record
        $db->table('loans')->where('applicationID', $loanId)->update([
            'loan_due'    => $loanDue - $totalEmiPaid,
            'pending_emi' => $emiPending - $emiCountPaid,
            'updated_at'  => $now,
        ]);

        log_message('info', "[STEP 4] Loan master updated. LoanID {$loanId}, Loan Due Updated To: " . ($loanDue - $totalEmiPaid) . ", Pending EMI: " . ($emiPending - $emiCountPaid));

        // 🔹 Step 5: Final Log
        $balanceDue    = $loanDue - $totalEmiPaid;
        $emiPendingLog = $emiPending - $emiCountPaid;

        log_message('info', "[END] Loan {$loanId} Payment Processed. 
        EMIs Paid: {$emiCountPaid}, 
        Total Paid: {$totalEmiPaid}, 
        Amount Due Left: {$balanceDue}, 
        EMI pending: {$emiPendingLog}, 
        Remaining Unused Payment: {$paymentAmount}");
    }


    public function createPaymentLink()
    {
        $db = db_connect();

        // Input from request
        $amount = $this->request->getVar('total_emi');
        $member_name = $this->request->getVar('member_name') ?? 'Unknown Member';
        $member_id = $this->request->getVar('member_id') ?? 'Unknown';
        $member_mobile = $this->request->getVar('member_mobile') ?? 'Unknown';

        $loanID = $this->request->getVar('loanIDs');

        log_message('info', "Generate Payment Link called for LoanID: $loanID, MemberID: $member_id, Amount: $amount");

        // Payment Link data
        $dataApi = [
            "upi_link"     => true,
            "amount"       => round($amount * 100), // Razorpay expects paise
            "currency"     => "INR",
            "accept_partial" => false,
            "expire_by"    => strtotime("+1 day"), // link expires in 1 day
            "reference_id" => "LN_" . $loanID . "_" . time(),
            "description"  => "EMI Payment for Loan ID: $loanID",
            "customer"     => [
                "name"    => $member_name,
                "contact" => $member_mobile,
                // "email"   => $member_email,
            ],
            "notify"       => [
                "sms"   => true,
                "email" => false,
            ],
            "reminder_enable" => true,
            "notes" => [
                "loan_id"        => $loanID,
                "customer_id"    => $member_id,
                "customer_phone" => $member_mobile,
            ],
            "callback_url"    => base_url() . 'payment/conformation',
            "callback_method" => "get",
        ];

        $data_json = json_encode($dataApi);
        log_message('info', 'Payment Link Request: ' . $data_json);

        // cURL call
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.razorpay.com/v1/payment_links/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data_json,
            CURLOPT_USERPWD => "rzp_live_RAdEZU6v2cdsZY:6YfsReB4zIaUGIO7ykcAG6yb", // replace with your credentials
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
        ]);

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $response_decode = json_decode($response, true);
        curl_close($curl);

        log_message('info', 'Payment Link Response: ' . $response);

        if ($httpcode === 200) {
            // Save payment link in loan table
            $builderEmi = $db->table('tab_' . esc($loanID));
            $data = [
                'paymentSession'  => $response_decode['id'],
                'comments'     => $response_decode['short_url'] ?? null,
                'paymentStatus'   => $response_decode['status'],
                'updated_on'      => date('Y-m-d H:i:s')
            ];
            $builderEmi->where('reference', 'Due')->update($data);

            return $this->respond(['payment_link_created' => $response_decode], 200);
        } else {
            $errors = $response_decode['error']['description'] ?? 'Failed to create payment link. Please try again.';
            return $this->respond(['error' => $errors], $httpcode);
        }
    }
}

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

                // --- PART 1: Unpaid EMIs before today (add 10% penalty)
                $builderLoanPast = $db->table($tableName);
                $builderLoanPast->selectSum('emi', 'past_emi');
                $builderLoanPast->whereIn('reference', ['Due', 'N']);
                $builderLoanPast->where('valueDate <', $today);
                $queryPast = $builderLoanPast->get();
                $rowPast = $queryPast->getRow();

                $pastEMI = $rowPast && $rowPast->past_emi !== null ? (float)$rowPast->past_emi : 0;
                $penalty = round($pastEMI * 0.10, 2);
                // $penalty = 0;
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
        $amount = $this->request->getVar('total_emi') ?? 0;
        $member_name = $this->request->getVar('member_name') ?? 'Unknown Member';
        $member_id = $this->request->getVar('member_id') ?? 'Unknown Member';
        $member_mobile = $this->request->getVar('member_mobile') ?? 'Unknown Member';
        $loanID = $this->request->getVar('loanIDs');
        $dataApi = array(
            'order_amount'              => $amount,
            'order_currency'          => "INR",
            'customer_details' => array(
                'customer_id'          => $member_id,
                'customer_name'          => $member_name,
                'customer_phone'      => $member_mobile,
            ),
            'order_meta' => array(
                'return_url'          => base_url() . 'payment/conformation',
                'payment_methods'       => "nb,dc,upi"
            ),
        );

        $data_json = json_encode($dataApi);
        // print_r($data_json);
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
                'X-Client-Secret: ..',
                'X-Client-Id: ..',
                'x-api-version: 2025-01-01',
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
                'paymentSession'            => $response_decode['payment_session_id'],
                'orderId'                   => $response_decode['order_id'],
                'paymentStatus'             => $response_decode['order_status'],
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

        $orderId = $this->request->getVar('order_id');
        $loanID = $this->request->getVar('loan_id');
        $paymentAmount = $this->request->getVar('order_amount');
        $payment_session_id = $this->request->getPost('payment_session_id');


        if (!$loanID || !$orderId) {
            return redirect()->to('/')->with('error', 'Missing required payment details.');
        }


        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.cashfree.com/pg/orders/{$orderId}/payments",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                'X-Client-Secret: ..',
                'X-Client-Id: ..',
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
                $this->processEMIPayment($paymentData, $loanID, $orderId);
            }
        }
        return $this->respond(['payment_success' => [
            'status'        => $status,
            'paymentData'   => $paymentData,
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
                'X-Client-Secret: ..',
                'X-Client-Id: ..',
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
                'X-Client-Secret: ..',
                'X-Client-Id: ..',
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

        $loan = $db->table('loans')->where('applicationID', $loanId)->get()->getRow();
        if (!$loan) {
            log_message('error', "Loan not found for applicationID: {$loanId}");
            return;
        }

        $emiPending = (int) $loan->pending_emi;
        $loanDue = (float) $loan->loan_due;
        $totalEmiPaid = 0;
        $emiCountPaid = 0;

        // Step 1: Pay Due EMIs
        $emiRows = $db->table($emiTable)->where('reference', 'Due')->orderBy('Id', 'ASC')->get()->getResult();

        foreach ($emiRows as $emi) {
            $today = date('Y-m-d');
            // --- Check if penalty exists and remove it ---
            $builderLoanPast = $db->table($emiTable);
            $builderLoanPast->selectSum('emi', 'past_emi');
            $builderLoanPast->whereIn('reference', ['Due', 'N']);
            $builderLoanPast->where('valueDate <', $today);
            $queryPast = $builderLoanPast->get();
            $rowPast = $queryPast->getRow();

            $pastEMI = $rowPast && $rowPast->past_emi !== null ? (float)$rowPast->past_emi : 0;
            $penalty = round($pastEMI * 0.10, 2);


            // 🔢 Get number of rows matching the same condition
            $countQuery = $db->table($emiTable);
            $countQuery->selectCount('*', 'emi_count');
            $countQuery->whereIn('reference', ['Due', 'N']);
            $countQuery->where('valueDate <', $today);
            $countResult = $countQuery->get()->getRow();
            $emiRowCount = $countResult && $countResult->emi_count !== null ? (int)$countResult->emi_count : 0;

            if ($penalty > 0) {
                // $originalEmi = $emi->emi - (float) $penalty;

                // Update the EMI record to remove penalty
                $db->table($emiTable)->where('Id', $emi->Id)->update([
                    'credit' => $penalty
                ]);

                $paymentAmount = $paymentAmount - (float) $penalty; // Use corrected EMI
            }

            if ($paymentAmount >= $emi->emi) {
                $db->table($emiTable)->where('Id', $emi->Id)->update([
                    'reference'       => 'Y',
                    'paymentStatus'   => $paymentStatus,
                    'transactionDate' => $now,
                    'updated_on'      => $now,
                    'orderId'         => $orderId,
                    'transactionId'   => $txnID . "_" . date('YmdHis'),
                    // 'balance'         => 0,
                ]);
                $paymentAmount -= $emi->emi;
                $totalEmiPaid =     $paymentAmount;
                $emiCountPaid += 1;
            } else {
                if ($paymentAmount > 0) {
                    $balance = $emi->balance - $paymentAmount;
                    $db->table($emiTable)->where('Id', $emi->Id)->update([
                        'paymentStatus'   => $paymentStatus,
                        'transactionDate' => $now,
                        'updated_on'      => $now,
                        'transactionId'   => $txnID . "_" . date('YmdHis'),
                        // 'balance'         => $balance,
                    ]);
                    $totalEmiPaid =     $paymentAmount;
                    $paymentAmount = 0;
                }
                break;
            }
        }

        // Step 2: Pay Upcoming EMIs
        if ($paymentAmount > 0) {
            $upcomingEMIs = $db->table($emiTable)->where('reference', 'N')->orderBy('Id', 'ASC')->get()->getResult();
            foreach ($upcomingEMIs as $emi) {
                if ($paymentAmount >= $emi->emi) {
                    $db->table($emiTable)->where('Id', $emi->Id)->update([
                        'reference'       => 'Y',
                        'paymentStatus'   => $paymentStatus,
                        'transactionDate' => $now,
                        'updated_on'      => $now,
                        'orderId'         => $orderId,
                        'transactionId'   => $txnID . "_" . date('YmdHis'),
                        // 'balance'         => 0,
                    ]);
                    $paymentAmount -= $emi->emi;
                    $totalEmiPaid =     $paymentAmount;
                    $emiCountPaid += 1;
                } else {
                    if ($paymentAmount > 0) {
                        $balance = $emi->balance - $paymentAmount;
                        $db->table($emiTable)->where('Id', $emi->Id)->update([
                            'reference'       => 'Due',
                            'paymentStatus'   => $paymentStatus,
                            'transactionDate' => $now,
                            'updated_on'      => $now,
                            'transactionId'   => $txnID . "_" . date('YmdHis'),
                            // 'balance'         => $balance,
                        ]);
                        $totalEmiPaid =     $paymentAmount;
                        $paymentAmount = 0;
                    }
                    break;
                }
            }
        }

        // Step 3: Mark next EMI as Due
        $nextEmi = $db->table($emiTable)->where('reference', 'N')->orderBy('Id', 'ASC')->limit(1)->get()->getRow();
        if ($nextEmi) {
            $db->table($emiTable)->where('Id', $nextEmi->Id)->update(['reference' => 'Due']);
        }

        // Step 4: Update loan record
        $db->table('loans')->where('applicationID', $loanId)->update([
            'loan_due'    => $loanDue - $totalEmiPaid,
            'pending_emi' => $emiPending - $emiCountPaid,
            'updated_at'  => $now,
        ]);

        log_message('info', "Loan {$loanId} updated after payment. EMIs Paid: {$emiCountPaid}, Amount: {$totalEmiPaid}");
    }
}

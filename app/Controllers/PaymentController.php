<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;

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

        foreach ($loans as &$loan) {
            $loanID = $loan['applicationID'] ?? $loan['loan_id'] ?? null;
            $memberName = $loan['member_name'] ?? 'Unknown Member';
            $memberMobile = $loan['member_mobile'] ?? 'Unknown Number';
            if (!$loanID) {
                $loan['total_emi'] = 0;
                continue;
            }

            $tableName = 'tab_' . esc($loanID);

            try {
                // Sum of EMI where reference is 'Due' or 'N' and valueDate <= today
                $builderLoan = $db->table($tableName);
                $builderLoan->selectSum('emi', 'total_emi');
                $builderLoan->whereIn('reference', ['Due', 'N']);
                $builderLoan->where('valueDate <=', $today);

                $queryLoan = $builderLoan->get();
                $row = $queryLoan->getRow();

                $loan['total_emi'] = $row && $row->total_emi !== null ? (float)$row->total_emi : 0;
                $data['total_emi'] = $row && $row->total_emi !== null ? (float)$row->total_emi : 0;
            } catch (\Exception $e) {
                // If the dynamic table doesn't exist or other error
                $data['total_emi'] = 0;
            }
        }

        unset($loan); // prevent accidental reference

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
            'order_amount'              => number_format($amount),
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
            CURLOPT_URL => "https://sandbox.cashfree.com/pg/orders",
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
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);


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
            return $this->respond(['error' => $response_decode], $httpcode);
        }



        curl_close($curl);
        // UPDATE EMI TABLE AFTER PAYMENT

    }

    // Get payment response from Cashfree
    // This function will be called by Cashfree after payment is completed
    public function conformation()
    {

        $orderId = $this->request->getPost('order_id');
        $loanID = $this->request->getPost('loan_id');
        $paymentAmount  = $this->request->getPost('order_amount');
        $payment_session_id = $this->request->getPost('payment_session_id');



        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sandbox.cashfree.com/pg/orders/" . $orderId . "/payments",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(

                'x-api-version: 2025-01-01',
                'Content-Type: application/json',
                'Accept: application/json',
            ),
        ));

        $response = curl_exec($curl);
        $response_decode = json_decode($response, true);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        // print_r($err);
        // var_dump($response_decode);
        // echo $response_decode['order_id'];
        // echo $response_decode[0]['payment_status'];
        // return $this->respond(['payment_created' => $response_decode], $httpcode);
        curl_close($curl);

        if ($response_decode[0]['payment_status'] === 'SUCCESS') {
            # code...
            $db = db_connect();
            $loanID = $loanID; // From context
            $emiTable = 'tab_' . esc($loanID);


            $paymentAmount = (float) $response_decode[0]['payment_amount'];
            $paymentStatus = $response_decode[0]['payment_status'];
            $txnID = 'TXN_' . $response_decode[0]['cf_payment_id'];
            $now = date('Y-m-d H:i:s');

            // Get loan details
            $loan = $db->table('loans')->where('applicationID', $loanID)->get()->getRow();
            if (!$loan) {
                return $this->failNotFound('Loan not found');
            }

            $emiPending = (int) $loan->pending_emi;
            $loanDue = (float) $loan->loan_due;

            $totalEmiPaid = 0;
            $emiCountPaid = 0;

            // Step 1: Pay active EMIs ("Due")
            $emiRows = $db->table($emiTable)
                ->where('reference', 'Due')
                ->orderBy('Id', 'ASC')
                ->get()
                ->getResult();

            foreach ($emiRows as $emi) {
                if ($paymentAmount >= $emi->emi) {
                    $db->table($emiTable)->where('Id', $emi->Id)->update([
                        'reference'       => 'Y',
                        'paymentStatus'   => $paymentStatus,
                        'transactionDate' => $now,
                        'updated_on'      => $now,
                        'transactionId'   => $txnID,
                        'balance'         => 0,
                    ]);
                    $paymentAmount -= $emi->emi;
                    $totalEmiPaid += $emi->emi;
                    $emiCountPaid += 1;
                } else {
                    // Partial payment
                    if ($paymentAmount > 0) {
                        $balance = $emi->balance - $paymentAmount;

                        $db->table($emiTable)->where('Id', $emi->Id)->update([
                            'paymentStatus'   => $paymentStatus,
                            'transactionDate' => $now,
                            'updated_on'      => $now,
                            'transactionId'   => $txnID,
                            'balance'         => $balance,
                        ]);

                        $totalEmiPaid += $paymentAmount;
                        $paymentAmount = 0;
                    }
                    break;
                }
            }

            // Step 2: If still amount left, pay future EMIs ("N")
            if ($paymentAmount > 0) {
                $upcomingEMIs = $db->table($emiTable)
                    ->where('reference', 'N')
                    ->orderBy('Id', 'ASC')
                    ->get()
                    ->getResult();

                foreach ($upcomingEMIs as $emi) {
                    if ($paymentAmount >= $emi->emi) {
                        $db->table($emiTable)->where('Id', $emi->Id)->update([
                            'reference'       => 'Y',
                            'paymentStatus'   => $paymentStatus,
                            'transactionDate' => $now,
                            'updated_on'      => $now,
                            'transactionId'   => $txnID,
                            'balance'         => 0,
                        ]);
                        $paymentAmount -= $emi->emi;
                        $totalEmiPaid += $emi->emi;
                        $emiCountPaid += 1;
                    } else {
                        if ($paymentAmount > 0) {
                            $balance = $emi->balance - $paymentAmount;

                            $db->table($emiTable)->where('Id', $emi->Id)->update([
                                'reference'       => 'Due', // this becomes next active EMI
                                'paymentStatus'   => $paymentStatus,
                                'transactionDate' => $now,
                                'updated_on'      => $now,
                                'transactionId'   => $txnID,
                                'balance'         => $balance,
                            ]);

                            $totalEmiPaid += $paymentAmount;
                            $paymentAmount = 0;
                        }
                        break;
                    }
                }
            }

            // Step 3: Set next EMI as "Due"
            $nextEmi = $db->table($emiTable)
                ->where('reference', 'N')
                ->orderBy('Id', 'ASC')
                ->limit(1)
                ->get()
                ->getRow();

            if ($nextEmi) {
                $db->table($emiTable)
                    ->where('Id', $nextEmi->Id)
                    ->update(['reference' => 'Due']);
            }

            // Step 4: Update loan master table
            $db->table('loans')->where('applicationID', $loanID)->update([
                'loan_due'    => $loanDue - $totalEmiPaid,
                'pending_emi' => $emiPending - $emiCountPaid,
                'updated_at'  => $now,
            ]);

            return $this->respond([
                'message'        => 'Payment applied successfully.',
                'emis_paid'      => $emiCountPaid,
                'total_emi_paid' => $totalEmiPaid,
            ]);
        } else {
            # code...
        }
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
            CURLOPT_URL => "https://sandbox.cashfree.com/pg/orders",
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
}

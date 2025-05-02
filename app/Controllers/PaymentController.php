<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;

class PaymentController extends BaseController
{
    use ResponseTrait;
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
            $dataApi = array(
                'order_amount'              => $amount,
                'order_currency'          => "INR",
                'customer_details' => array(
                    'customer_id'          => $row->member_id,
                    'customer_name'          => $customer_name,
                    'customer_phone'      => $row->mobile,
                ),
                'order_meta' => array(
                    'return_url'          => base_url() . 'payment/details?id=' . $loan_id,
                    'payment_methods_filters'          => array(
                        'method'          => array(
                            'action'          => "ALLOW",
                            'values'          =>  array(
                                "debit_card",
                                "netbanking",
                                "upi"
                            ),
                        ),
                    ),
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
            CURLOPT_HTTPHEADER => array(),
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

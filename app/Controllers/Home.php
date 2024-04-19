<?php

namespace App\Controllers;

use App\Models\User_login_model;

class Home extends BaseController
{
    public function index(): string
    {
        return view('login');
    }
    public function panel()
    {
        return view('login');
    }
    public function terms()
    {
        return view('termsandcondition');
    }
    // USER login
    public function user_login()
    {
        $session = session();
        $model = new User_login_model();
        $userId = $this->request->getVar('userid');
        $userPass       = $this->request->getVar('pass');
        $password = sha1($userPass);
        $data = $model->where('userid', $userId)->first();
        if ($data) {
            $pass = $data['password'];
            // $verify_pass = password_verify($password, $pass);
            if ($pass == $password) {

                $login_data = [
                    'userId'       => $data['userId'],
                    'name'         => $data['UserName'],
                    'uType'         => $data['userType'],
                    'logged_in'    => TRUE
                ];
                // $jsonLogin = json_encode($login_data);
                // $_SESSION['crmUser'] = $jsonLogin;
                $session->set($login_data);
                return redirect()->to(base_url() . 'tax/dashboard');
            } else {
                $session = session();
                $session->setFlashdata('loginmsg', 'Wrong Password. Please Try Again.');

                // echo "Wrong Password";
                return redirect()->to(base_url() . 'tax');
            }
        } else {
            $session = session();
            $session->setFlashdata('loginmsg', 'User ID not Found. Please Create Your Account.');
            return redirect()->to(base_url() . 'tax');
            // print_r($ses_data);
            // echo "Test3";
        }
    }
    public function insert_data()
    {
        $db = db_connect();
        $builder = $db->table('servicesDetails');
        $data = [
            'address'   => $this->request->getPost('address'),
            'state'     => $this->request->getPost('state'),
            'pin'       => $this->request->getPost('zipCode'),
            'status'       => $this->request->getPost('status'),
            'price'     => $this->request->getPost('price'),
        ];
        $uniqID = $this->request->getPost('uniqID');
        $builder->where('uniqid', $uniqID);
        $builder->update($data);
        if ($this->request->getPost('status') == "Pending Payment") {
            # code...
            // PAyment
            date_default_timezone_set('Asia/Kolkata');
            $builderPayment = $db->table('payment');
            // PAYMENT INTEGRATION
            $arrayOrder = array(
                'receipt' => 'Receipt#' . $uniqID,
                'amount' => $this->request->getPost('price'),
                'currency' => 'INR',
                'notes' => array(
                    'customerReference' => $this->request->getPost('customerID'),
                    'CustomerMobile' => $this->request->getPost('phoneNumber')
                )

            );
            $data_string_order_api = json_encode($arrayOrder); //LOGIN IN FREEMI

            $curlO = curl_init();
            $loginOrderURL = "https://api.razorpay.com/v1/orders";
            $header_js_Order = array('Accept: application/json', 'Content-Type: application/json');
            print_r($header_js_Order);
            //set cURL options
            curl_setopt($curlO, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curlO, CURLOPT_URL, $loginOrderURL);
            curl_setopt($curlO, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curlO, CURLOPT_POST, 1);
            curl_setopt($curlO, CURLOPT_POSTFIELDS, $data_string_order_api);
            curl_setopt($curlO, CURLOPT_USERPWD, "rzp_test_nM0gkKKYwEqjex:5Tj6UnAptHc2oB8lNJvdeIf7");
            curl_setopt($curlO, CURLOPT_HTTPHEADER, $header_js_Order);

            //Execute cURL
            $curl_response_order = curl_exec($curlO);
            $httpCode = curl_getinfo($curlO, CURLINFO_HTTP_CODE);
            if ($httpCode == 200) {
                echo $curl_response_order;
                $orderResponseDecode = json_decode($curl_response_order, true);
                // $order_id =  $orderResponseDecode['id'];
                $dataPayment = [
                    'jpbID'             => $this->request->getPost('uniqID'),
                    'p_id'             => $this->request->getPost('productID'),
                    'orderID'           => $orderResponseDecode['id'],
                    'customerID'        => $this->request->getPost('customerID'),
                    'paymentStatus'     =>  $orderResponseDecode['status'],
                    'amount'            => $orderResponseDecode['amount_due'],
                    'receipt'           => $orderResponseDecode['receipt'],
                    'name'           => $this->request->getPost('customerName'),
                    'email'           => $this->request->getPost('email'),
                    'mobile'           => $this->request->getPost('phoneNumber'),
                    'date'              => date('Y-m-d'),
                    'time'              => date('h:i:s'),
                ];
                $builderPayment->upsert($dataPayment);
            } else {
                $session = session();
                $session->setFlashdata('update', 'Internal Error.');
            }
            curl_close($curlO);
            // END PAYMENT
        } else {
            # code...
            $builderPayment = $db->table('payment');
            // $order_id =  $orderResponseDecode['id'];
            $dataPayment = [
                'jpbID'             => $this->request->getPost('uniqID'),
                'p_id'             => $this->request->getPost('productID'),
                'orderID'           => "Null",
                'customerID'        => $this->request->getPost('customerID'),
                'paymentStatus'     =>  "Null",
                'amount'            => "Null",
                'receipt'           => "Null",
                'date'              => date('Y-m-d'),
                'time'              => date('h:i:s'),
            ];
            $builderPayment->upsert($dataPayment);
        }

        $session = session();
        $session->setFlashdata('update', 'Profile successfully updated.');
        return redirect()->to(base_url() . 'tax/dashboard');
    }
    // User Login
    // api
    public function api_v1_login()
    {
        return view('api/api_login');
    }
    public function api_v1_login_mobile()
    {
        return view('api/api_login_mobile');
    }
    public function api_v1_mobile()
    {
        return view('api/api_mobile');
    }
    public function api_v1_service()
    {
        return view('api/api_service');
    }
    public function api_v1_service_gst()
    {
        return view('api/api_service_gst');
    }
    public function api_v1_dashboard()
    {
        return view('api/api_dashboard');
    }
    public function api_v1_dashboard_ser_list()
    {
        return view('api/api_dashboard_ser_list');
    }
    public function api_v1_update_mobile()
    {
        return view('api/api_mobile_update');
    }
    public function api_v1_profile()
    {
        return view('api/api_profile');
    }
    public function api_v1_payment()
    {
        return view('api/api_payment');
    }
    public function api_v1_service_dsc()
    {
        return view('api/api_service_dsc');
    }
    public function api_v1_service_company()
    {
        return view('api/api_service_incorporation');
    }
    public function api_v1_service_compliance()
    {
        return view('api/api_service_compliance');
    }
    public function api_v1_license_gst()
    {
        return view('api/api_license_gst_license');
    }
    public function api_v1_license_trade()
    {
        return view('api/api_license_trade');
    }
    public function api_v1_license_trademark()
    {
        return view('api/api_license_trademark');
    }
    public function api_v1_payment_response()
    {
        return view('api/api_payment_response');
    }
    public function api_v1_mobile_count()
    {
        return view('api/api_mobile_count');
    }
}

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
    // User Login
    // api
    public function api_v1_login()
    {
        return view('api/api_login');
    }
    public function api_v1_service()
    {
        return view('api/api_service');
    }
    public function api_v1_dashboard()
    {
        return view('api/api_dashboard');
    }
    public function api_v1_dashboard_ser_list()
    {
        return view('api/api_dashboard_ser_list');
    }
}
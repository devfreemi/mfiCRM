<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserLogin;

class Auth extends BaseController
{
    public function __construct()
    {
        helper(['url', 'form']);
    }
    public function index()
    {
        //
        // $ipAddress = $this->request->getIPAddress();
        // if ($ipAddress == '2401:4900:882b:1b10:c351:94a9:a573:8e9f') {
        //     # code...
        return view('login');
        // } else {
        //     $this->response->setStatusCode(403);
        //     return view('errors/cli/error_404');
        // }

    }
    public function signup()
    {
        return view('register');
    }
    public function signup_validation()
    {
        if ($this->request->getMethod() == "post") {
            # code...
            $rules = [
                'name' => 'required',
                'userId' => 'required|is_unique[userBackOffice.userId]',
                'password' => 'required|min_length[8]|max_length[8]',
            ];
            if (!$this->validate($rules)) {
                # code...
                return view("register", [
                    "validation" => $this->validator
                ]);
            } else {
                $name = $this->request->getVar('name');
                $userId = $this->request->getVar('userId');
                $password = $this->request->getVar('password');
                $val = [
                    'name' => $name,
                    'userId' => $userId,
                    'password_plain' => $password,
                    'password' => sha1($password),
                    'userBrowser' => $this->request->getUserAgent(),
                    'ip' =>  $this->request->getIPAddress(),
                ];

                $userLoginModel = new UserLogin();
                $query = $userLoginModel->insert($val);
                if (!$query) {
                    # code...
                    return redirect()->back()->with('fail', 'Internal Exception');
                } else {
                    # code...
                    return redirect()->to(base_url());
                }
            }
        }
    }
    public function login_validation()
    {
        $session = session();
        if ($this->request->getMethod() == "post") {
            # code...
            $rules = [
                'userId' => 'required|is_not_unique[userBackOffice.userId]',
                'password' => 'required|min_length[8]|max_length[8]',
            ];
            if (!$this->validate($rules)) {
                # code...
                return view("login", [
                    "validation" => $this->validator
                ]);
            } else {
                $userId = $this->request->getVar('userId');
                $password = $this->request->getVar('password');
                $passwordEn = sha1($password);
                $model = new UserLogin();
                $data = $model->where('userId', $userId)->first();
                if (!$data) {
                    # code...
                    return redirect()->back()->with('fail', 'Internal Exception');
                } else {
                    # code...
                    $pass = $data['password'];

                    if ($pass == $passwordEn) {

                        $login_data = [
                            'userId'       => $data['userId'],
                            'name'         => $data['name'],
                            // 'uType'         => $data['userType'],
                            'logged_in'    => TRUE
                        ];

                        $session->set('login_data', $login_data);
                        $session->set($login_data);
                        return redirect()->to(base_url() . 'dashboard');
                    } else {
                        $session = session();
                        $session->setFlashdata('loginmsg', 'Wrong Password. Please Try Again.');
                        return redirect()->to(base_url());
                    }
                }
            }
        }
    }
    public function logout()
    {
        $session = session();
        // $session->remove('login_data');
        $session->destroy();
        return redirect()->to(base_url());
    }
}

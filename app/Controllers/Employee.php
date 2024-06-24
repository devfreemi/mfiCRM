<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;
use App\Models\EmployeeModel;
use \Firebase\JWT\JWT;

class Employee extends BaseController
{
    use ResponseTrait;

    public function api_login()
    {
        //
        $employeeModel = new EmployeeModel();

        $mobile = $this->request->getVar('mobile');

        $user = $employeeModel->where('mobile', $mobile)->first();

        if (is_null($user)) {
            return $this->respond(['error' => 'Invalid Mobile Number.'], 401);
        }



        $key = getenv('JWT_SECRET');
        $iat = time(); // current timestamp value
        $exp = $iat + 3600;

        $payload = array(
            "iss" => "truetechnologies.in",
            "aud" => "truetechnologies.in",
            "sub" => "API Security",
            "iat" => $iat, //Time the JWT issued at
            "exp" => $exp, // Expiration time of token
            "email" => $user['email'],
        );

        $token = JWT::encode($payload, $key, 'HS256');

        $response = [
            'message' => 'Login Succesful',
            'token' => $token
        ];

        return $this->respond($response, 200);
    }

    public function api_register()
    {
        $rules = [
            'email' => ['rules' => 'required|min_length[4]|max_length[255]|valid_email|is_unique[users.email]'],
            'password' => ['rules' => 'required|min_length[8]|max_length[255]'],
            'confirm_password'  => ['label' => 'confirm password', 'rules' => 'matches[password]']
        ];


        if ($this->validate($rules)) {
            $model = new EmployeeModel();
            $data = [
                'email'    => $this->request->getVar('email'),
                'mobile' => $this->request->getVar('password'),
                'name' => $this->request->getVar('name'),
                'employeeID' => $this->request->getVar('employeeID'),
            ];
            $model->save($data);

            return $this->respond(['message' => 'Registered Successfully'], 200);
        } else {
            $response = [
                'errors' => $this->validator->getErrors(),
                'message' => 'Invalid Inputs'
            ];
            return $this->fail($response, 409);
        }
    }
}

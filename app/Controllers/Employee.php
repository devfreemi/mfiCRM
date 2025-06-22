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

        $employeeID = $this->request->getVar('employeeID');

        $user = $employeeModel->where('employeeID', $employeeID)->first();

        if (is_null($user)) {
            return $this->respond(['error' => 'Invalid Employee ID.'], 401);
        }



        $key = getenv('JWT_SECRET');
        $iat = time(); // current timestamp value
        $exp = $iat + 3600 * 24;

        $payload = array(
            "iss" => "retailpe.in",
            "aud" => "crm.retailpe.in",
            "sub" => "API Security",
            "iat" => $iat, //Time the JWT issued at
            "exp" => $exp, // Expiration time of token
            // "mobile" => $user['mobile'],
        );

        $token = JWT::encode($payload, $key, 'HS256');

        $response = [
            'message' => 'Login Succesful',
            'token' => $token
        ];

        return $this->respond($response, 200);
    }

    public function add_employee()
    {
        $rules = [
            'mobile' => ['rules' => 'required|min_length[10]|max_length[10]|is_unique[employees.mobile]'],
            'name' => ['rules' => 'required|min_length[5]|max_length[64]'],
        ];


        if ($this->validate($rules)) {
            $model = new EmployeeModel();
            $data = [
                'email'    => $this->request->getVar('email'),
                'mobile' => $this->request->getVar('mobile'),
                'name' => $this->request->getVar('name'),
                'employeeID' => $this->request->getVar('employeeID'),
                'designation' => $this->request->getVar('designation'),
            ];
            // $model->save($data);

            $query = $model->insert($data);
            if (!$query) {
                # code...
                return redirect()->back()->with('fail', 'Internal Exception');
            } else {
                # code...
                return redirect()->to(base_url() . 'employee');
            }
        } else {
            return redirect()->back()->with('fail', 'Invalid Inputs');
        }
    }
}

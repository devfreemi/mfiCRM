<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MemberModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;

class MemberLoginController extends BaseController
{
    use ResponseTrait;
    public function member_application_login()
    {
        //
        $model = new MemberModel();
        $mobileNo = $this->request->getVar('mobile');

        $member = $model->where('mobile', $mobileNo)->first();

        if (is_null($member)) {
            return $this->respond(['error' => 'Invalid Retailer Mobile Number!'], 401);
        }



        $key = getenv('JWT_SECRET');
        $iat = time(); // current timestamp value
        $exp = $iat + (3600 * 24 * 30); // jwt valid for 30 days

        $payload = array(
            "iss" => "retailpe.in",
            "aud" => "Retail Pe Application",
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
        log_message('info', 'Existing Retailer Login by OTP: ' . $mobileNo);

        return $this->respond($response, 200);
    }
    // Member Register
    public function member_application_register()
    {
        //
        $model = new MemberModel();
        $mobileNo = $this->request->getVar('mobile');
        $data = [
            'mobile'         =>  $mobileNo,
            'groupId'        =>  002,
        ];
        // $member = $model->where('mobile', $mobileNo)->first();
        $query = $model->insert($data);

        if (is_null($query)) {
            return $this->respond(['error' => 'Invalid Retailer Mobile Number!'], 401);
        }



        $key = getenv('JWT_SECRET');
        $iat = time(); // current timestamp value
        $exp = $iat + (3600 * 24 * 30);

        $payload = array(
            "iss" => "NFSPL",
            "aud" => "Retail Pe Application",
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
        log_message('info', 'New Retailer Register by OTP: ' . $mobileNo);
        return $this->respond($response, 200);
    }
    public function member_details()
    {
        //
        $model = new MemberModel();
        $mobileNo = $this->request->getVar('mobile');

        $member = $model->where('mobile', $mobileNo)->first();

        if (is_null($member)) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        }

        return $this->respond(['Retailer' => $member], 200);
    }
}

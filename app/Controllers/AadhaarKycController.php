<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;

date_default_timezone_set('Asia/Kolkata');

class AadhaarKycController extends BaseController
{
    use ResponseTrait;

    public function send_otp()
    {
        //
        $employeeIDkyc = $this->request->getVar('employeeIDkyc');
        $aadhaarNo = $this->request->getVar('aadhaarNo');
        $aadhaarFour = substr($aadhaarNo, -4);
        $caseId = $aadhaarFour . rand(10, 99);
        $consent = "Y";

        // $query = $model->save($data);
        // $data = [
        //     'aadhaarNo'        => $aadhaarNo,
        //     'caseId'          => $caseId,
        //     'consent'        => $consent,
        //     'keyDoneBy'      => $employeeIDkyc,
        //     'timestamp'       => date('Y-m-d H:i:s'),
        // ];
        $dataApi = array(
            'aadhaarNo'        => $aadhaarNo,
            'consent'        => $consent,
            'clientData' => array(
                'caseId'          => $caseId,
            ),
        );
        $data_json = json_encode($dataApi);

        if ($aadhaarNo != '') {
            # code...
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://uat-hub.perfios.com/api/kyc/v3/aadhaar-xml/otp",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $data_json,
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/json",
                    "x-auth-key: jTk670PBaHVP5kD5"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            $response_decode = json_decode($response, true);

            curl_close($curl);

            if ($err) {
                // echo "cURL Error #:" . $err;
                return $this->respond(['error' => 'Invalid Request.' . $err], 401);
            } else {
                // echo $response;
                return $this->respond(['kyc' => $response_decode], 200);
            }
        } else {
            # code...
            return $this->respond(['error' => 'Server Error.'], 502);
        }

        // if (!$aadhaarNo) {
        //     return $this->respond(['error' => 'Invalid Request.'], 401);
        // } else {
        //     # code...
        //     return $this->respond($dataApi, 200);
        // }
    }
}

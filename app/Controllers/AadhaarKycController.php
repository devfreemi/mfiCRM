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
        //Data Input
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
    public function verify_otp()
    {
        // Data Input
        $otpAadhaar = $this->request->getVar('otp');
        $employeeIDkycVer = $this->request->getVar('employeeIDkycVer');
        $aadhaarNo = $this->request->getVar('aadhaarNo');
        $caseId = $this->request->getVar('caseId');
        $requestId = $this->request->getVar('requestId');
        $consent = "Y";

        $dataApi = array(
            'otp'              => $otpAadhaar,
            'aadhaarNo'        => $aadhaarNo,
            'requestId'        => $requestId,
            'consent'          => $consent,
            'shareCode'        => $caseId,
            'clientData' => array(
                'caseId'          => $caseId,
            ),
        );
        $data_json = json_encode($dataApi);

        if ($otpAadhaar != '') {
            # code...
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://uat-hub.perfios.com/api/kyc/v3/aadhaar-xml/file",
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
                // return $response;
                return $this->respond(['aadhaar' => $response_decode], 200);
            }
        } else {
            # code...
            return $this->respond(['error' => 'Internal Error'], 502);
        }
    }
    public function verify_pan()
    {
        // Data Input
        $panNumber = $this->request->getVar('pan');
        $employeeIDkycVer = $this->request->getVar('employeeIDPan');
        $name = $this->request->getVar('userName');
        $name = strtoupper($name);
        $caseId = $this->request->getVar('caseId');
        $consent = "Y";

        $dataApi = array(
            'pan'              => $panNumber,
            'consent'          => $consent,
            'clientData' => array(
                'caseId'          => $caseId,
            ),
        );
        $data_json = json_encode($dataApi);

        if ($panNumber != '') {
            # code...
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://uat-hub.perfios.com/api/kyc/v3/pan-profile-detailed",
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
                return $this->respond(['error' => 'Internal Exception!' . $err], 502);
            } else {
                // return $response;
                $statusCode = $response_decode['statusCode'];
                if ($statusCode === 101) {
                    $nameResponse = $response_decode['result']['name'];
                    # code...
                    if ($name === $nameResponse) {
                        # code...
                        return $this->respond(['panVerify' => 'PAN Verified'], 200);
                    } else {
                        # code...
                        return $this->respond(['panDetails' => $nameResponse], 403);
                    }
                } else {
                    # code...
                    return $this->respond(['panVerify' => 'Invalid PAN Number'], 406);
                }
            }
        } else {
            # code...
            return $this->respond(['error' => 'Internal Error'], 502);
        }
    }
}

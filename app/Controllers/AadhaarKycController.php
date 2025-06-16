<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;
use App\Models\PanMasterModel;
use CodeIgniter\I18n\Time;

date_default_timezone_set('Asia/Kolkata');

class AadhaarKycController extends BaseController
{
    use ResponseTrait;

    public function kyc()
    {
        return view('check_user_kyc');
    }

    public function send_otp_page()
    {
        //Data Input
        $employeeIDkyc = $this->request->getVar('employeeIDkyc');
        $aadhaarNo = $this->request->getVar('aadhaarNo');
        $aadhaarSix = substr($aadhaarNo, -6);
        $caseId = $aadhaarSix;
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

            "ipAddress"  =>      "192.168.1.27",
            "name"    =>      "Subhajit Paul",
            "consentTime"   => time(),
            "consentText"   => "I hereby give my consent to Retail Pe to use my Aadhaar number for KYC verification.",
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
                CURLOPT_URL => "https://hub.perfios.com/api/kyc/v3/aadhaar-consent",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $data_json,
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/json",
                    "x-auth-key: KzKQbi9Tw8OokmY"
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
                echo $data_json;
                echo "<br>";
                echo $response;
                echo "<br>";
                echo "OTP DATA: <br>";
                // return $this->respond(['kyc' => $response_decode], 200);
                // $session = session();
                // $session->setFlashdata('msg', $response_decode['requestId']);
                // return redirect()->to(base_url() . 'member/kyc');
                // return view('check_user_kyc', ['kyc' => $response_decode]);
                // SEND OTP

                $dataApi_otp = array(
                    'aadhaarNo'    => $aadhaarNo,
                    "accessKey" => $response_decode['result']['accessKey'],
                    'consent'        => $consent,
                    'clientData' => array(
                        'caseId'          => $caseId,
                    ),
                );
                $data_json_otp = json_encode($dataApi_otp);
                $curl_otp = curl_init();

                curl_setopt_array($curl_otp, array(
                    CURLOPT_URL => "https://hub.perfios.com/api/kyc/v3/get-aadhaar-otp",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $data_json_otp,
                    CURLOPT_HTTPHEADER => array(
                        "content-type: application/json",
                        "x-auth-key: KzKQbi9Tw8OokmY"
                    ),
                ));

                $response_otp = curl_exec($curl_otp);
                $err_otp = curl_error($curl_otp);
                $response_decode_otp = json_decode($response_otp, true);

                curl_close($curl_otp);
                if ($err_otp) {
                    // echo "cURL Error #:" . $err;
                    return $this->respond(['error' => 'Invalid Request.' . $err_otp], 401);
                } else {
                    var_dump($response_decode_otp);
                    // return $this->respond(['kyc' => $response_decode], 200);
                    $session = session();
                    $session->setFlashdata('msg', $response_decode_otp['result']['accessKey']);
                    return redirect()->to(base_url() . 'member/kyc');
                    return view('check_user_kyc', ['kyc' => $response_decode]);
                }
            }
        } else {
            # code...
            return $this->respond(['error' => 'Server Error.'], 502);
        }
    }

    public function verify_otp_page()
    {
        // Data Input
        $otpAadhaar = $this->request->getVar('otp');
        $employeeIDkycVer = $this->request->getVar('employeeIDkycVer');
        $aadhaarNo = $this->request->getVar('aadhaarNo');
        $aadhaarSix = substr($aadhaarNo, -6);
        $shareCode = rand(1000, 9999);
        $caseId = $aadhaarSix;
        $requestId = $this->request->getVar('requestId');
        $consent = "Y";

        $dataApi = array(
            'otp'                       => $otpAadhaar,
            'aadhaarNo'                 => $aadhaarNo,
            "aadhaarUpdateHistory"      => "Y",
            'accessKey'                 => $requestId,
            'consent'                   => $consent,
            'shareCode'                 => $shareCode,
            'clientData'                => array(
                'caseId'          => $caseId,
            ),
        );
        $data_json = json_encode($dataApi);
        // print_r($data_json);
        if ($otpAadhaar != '') {
            # code...
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://hub.perfios.com/api/kyc/v3/get-aadhaar-file",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $data_json,
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/json",
                    "x-auth-key: KzKQbi9Tw8OokmY"
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
                echo $data_json;
                echo "<br>";
                echo $response;
                // return $response;
                // return $this->respond(['aadhaar' => $response_decode], 200);
            }
        } else {
            # code...
            return $this->respond(['error' => 'Internal Error'], 502);
        }
    }
















    public function send_otp()
    {
        //Data Input
        $employeeIDkyc = $this->request->getVar('employeeIDkyc');
        $aadhaarNo = $this->request->getVar('aadhaarNo');
        $aadhaarFour = substr($aadhaarNo, -6);
        $caseId = $aadhaarFour;
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
                CURLOPT_URL => "https://hub.perfios.com/api/kyc/v3/aadhaar-xml/otp",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $data_json,
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/json",
                    "x-auth-key: KzKQbi9Tw8OokmY"
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
    }
    public function verify_otp()
    {
        // Data Input
        $otpAadhaar = $this->request->getVar('otp');
        $employeeIDkycVer = $this->request->getVar('employeeIDkycVer');
        $aadhaarNo = $this->request->getVar('aadhaarNo');

        $shareCode = rand(1000, 9999);
        $aadhaarFour = substr($aadhaarNo, -6);
        $caseId = $aadhaarFour;
        $requestId = $this->request->getVar('requestId');
        $consent = "Y";

        $dataApi = array(
            'otp'              => $otpAadhaar,
            'aadhaarNo'        => $aadhaarNo,
            'requestId'        => $requestId,
            'consent'          => $consent,
            'shareCode'        => $shareCode,
            'clientData' => array(
                'caseId'          => $caseId,
            ),
        );
        $data_json = json_encode($dataApi);

        if ($otpAadhaar != '') {
            # code...
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://hub.perfios.com/api/kyc/v3/aadhaar-xml/file",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $data_json,
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/json",
                    "x-auth-key: KzKQbi9Tw8OokmY"
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
    // --------------------------------------------
    // --------------------------------------------
    // GET PAN FROM NAME AND MOBILE NUMBER
    public function get_pan()
    {
        // Data Input
        $name = $this->request->getVar('member_name');
        $mobile = $this->request->getVar('member_mobile');


        $dataApi = array(
            'name'              => strtoupper($name),
            'mobile_no'            => $mobile,

        );
        $data_json = json_encode($dataApi);

        if ($name != '' && $mobile != '') {
            # code...
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://sandbox.surepass.io/api/v1/pan/mobile-to-pan',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data_json,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . getenv('SUREPASS_API_KEY'),
                    // 'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJmcmVzaCI6ZmFsc2UsImlhdCI6MTc0ODM0MzIxOCwianRpIjoiYzAxY2ZmNDItZTBkYi00YjdhLWFkZWMtZmJmNmE1M2JmZDQwIiwidHlwZSI6ImFjY2VzcyIsImlkZW50aXR5IjoiZGV2Lm5hbWFub2poYTdAc3VyZXBhc3MuaW8iLCJuYmYiOjE3NDgzNDMyMTgsImV4cCI6MTc1MDkzNTIxOCwiZW1haWwiOiJuYW1hbm9qaGE3QHN1cmVwYXNzLmlvIiwidGVuYW50X2lkIjoibWFpbiIsInVzZXJfY2xhaW1zIjp7InNjb3BlcyI6WyJ1c2VyIl19fQ.kyAlKocj2wsHG5vc34NMdKUPa7d4jKBMHlLzuJoUUpY',
                    'Content-Type: application/json'
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
                // return $this->respond(['pan' => $response_decode], 200);
                if ($response_decode['status_code'] === 200) {
                    # code...
                    $dataApiPan = array(
                        'id_number'              => $response_decode['data']['pan_number'],
                        'get_address'            => true,

                    );
                    $data_json_pan = json_encode($dataApiPan);
                    $curlInter = curl_init();

                    curl_setopt_array($curlInter, array(
                        CURLOPT_URL => 'https://sandbox.surepass.io/api/v1/pan/pan-comprehensive',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => $data_json_pan,
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: Bearer ' . getenv('SUREPASS_API_KEY'),
                            // 'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJmcmVzaCI6ZmFsc2UsImlhdCI6MTc0ODM0MzIxOCwianRpIjoiYzAxY2ZmNDItZTBkYi00YjdhLWFkZWMtZmJmNmE1M2JmZDQwIiwidHlwZSI6ImFjY2VzcyIsImlkZW50aXR5IjoiZGV2Lm5hbWFub2poYTdAc3VyZXBhc3MuaW8iLCJuYmYiOjE3NDgzNDMyMTgsImV4cCI6MTc1MDkzNTIxOCwiZW1haWwiOiJuYW1hbm9qaGE3QHN1cmVwYXNzLmlvIiwidGVuYW50X2lkIjoibWFpbiIsInVzZXJfY2xhaW1zIjp7InNjb3BlcyI6WyJ1c2VyIl19fQ.kyAlKocj2wsHG5vc34NMdKUPa7d4jKBMHlLzuJoUUpY',
                            'Content-Type: application/json'
                        ),
                    ));

                    $responsePan = curl_exec($curlInter);


                    $err = curl_error($curlInter);
                    $response_decode_pan = json_decode($responsePan, true);

                    curl_close($curlInter);
                    if ($err) {
                        // echo "cURL Error #:" . $err;
                        return $this->respond(['error' => 'Internal Exception!' . $err], 502);
                    } else {
                        // return $responsePan;
                        // return $this->respond(['pan' => $response_decode_pan], 200);
                        if ($response_decode_pan['status_code'] === 200) {
                            # code... get gst from pan
                            $dataApiGst = array(
                                'id_number'              => $response_decode_pan['data']['pan_number'],

                            );
                            $data_json_gst = json_encode($dataApiGst);
                            $curlGst = curl_init();
                            curl_setopt_array($curlGst, array(
                                CURLOPT_URL => 'https://sandbox.surepass.io/api/v1/corporate/gstin-by-pan',
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 0,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'POST',
                                CURLOPT_POSTFIELDS => $data_json_gst,
                                CURLOPT_HTTPHEADER => array(
                                    'Authorization: Bearer ' . getenv('SUREPASS_API_KEY'),
                                    'Content-Type: application/json'
                                ),
                            ));
                            $response_gst = curl_exec($curlGst);
                            $err_gst = curl_error($curlGst);
                            curl_close($curlGst);
                            if ($err_gst) {
                                // echo "cURL Error #:" . $err;
                                return $this->respond(['error' => 'Internal Exception!' . $err_gst], 502);
                            } else {

                                $response_decode_gst = json_decode($response_gst, true);
                                // return $this->respond(['pan' => $response_decode_pan], 200);
                                return $this->respond([
                                    'pan' => $response_decode_pan,
                                    'gst' => $response_decode_gst
                                ], 200);
                            }
                        } else {
                            # code...
                            return $this->respond(['error' => 'Server Error'], 500);
                        }
                    }
                } else {
                    # code...
                    return $this->respond(['pan' => $err], 401);
                }
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


        $dataApi = array(
            'id_number'              => $panNumber,
            'get_address'            => true,
        );
        $data_json = json_encode($dataApi);

        if ($panNumber != '') {
            # code...
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://sandbox.surepass.io/api/v1/pan/pan-comprehensive',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data_json,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . getenv('SUREPASS_API_KEY'),
                    // 'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJmcmVzaCI6ZmFsc2UsImlhdCI6MTc0ODM0MzIxOCwianRpIjoiYzAxY2ZmNDItZTBkYi00YjdhLWFkZWMtZmJmNmE1M2JmZDQwIiwidHlwZSI6ImFjY2VzcyIsImlkZW50aXR5IjoiZGV2Lm5hbWFub2poYTdAc3VyZXBhc3MuaW8iLCJuYmYiOjE3NDgzNDMyMTgsImV4cCI6MTc1MDkzNTIxOCwiZW1haWwiOiJuYW1hbm9qaGE3QHN1cmVwYXNzLmlvIiwidGVuYW50X2lkIjoibWFpbiIsInVzZXJfY2xhaW1zIjp7InNjb3BlcyI6WyJ1c2VyIl19fQ.kyAlKocj2wsHG5vc34NMdKUPa7d4jKBMHlLzuJoUUpY',
                    'Content-Type: application/json'
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

                if ($response_decode['status_code'] === 200) {
                    # code... get gst from pan
                    $dataApiGst = array(
                        'id_number'              => $response_decode['data']['pan_number'],

                    );
                    $data_json_gst = json_encode($dataApiGst);
                    $curlGst = curl_init();
                    curl_setopt_array($curlGst, array(
                        CURLOPT_URL => 'https://sandbox.surepass.io/api/v1/corporate/gstin-by-pan',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => $data_json_gst,
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: Bearer ' . getenv('SUREPASS_API_KEY'),
                            'Content-Type: application/json'
                        ),
                    ));
                    $response_gst = curl_exec($curlGst);
                    $err_gst = curl_error($curlGst);
                    curl_close($curlGst);
                    if ($err_gst) {
                        // echo "cURL Error #:" . $err;
                        return $this->respond(['error' => 'Internal Exception!' . $err_gst], 502);
                    } else {
                        // return $response_gst;
                        // return $this->respond(['gst' => $response_decode_gst], 200);
                        $response_decode_gst = json_decode($response_gst, true);
                        // return $this->respond(['panVerify' => $response_decode], 200);
                        return $this->respond([
                            'panVerify' => $response_decode,
                            'gst' => $response_decode_gst
                        ], 200);
                    }
                } else {
                    # code...
                    return $this->respond(['error' => 'Server Error'], 500);
                }
            }
        } else {
            # code...
            return $this->respond(['error' => 'Internal Error'], 502);
        }
    }
    public function verify_gst()
    {
        $gstNumber = $this->request->getVar('gstin');

        $dataApi = array(
            'id_number'              => $gstNumber,

        );
        $data_json = json_encode($dataApi);

        if ($gstNumber != '') {
            # code...
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://sandbox.surepass.io/api/v1/corporate/gstin-advanced",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data_json,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . getenv('SUREPASS_API_KEY'),
                    'Content-Type: application/json'
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

                return $this->respond(['gst' => $response_decode], 200);
            }
        }
    }

    // --------------------------------------------

    // --------------------------------------------

    // PAN VERIFICATION FOR CUSTOMER APPLICATION
    public function verify_pan_user()
    {
        // Data Input
        $panNumber = $this->request->getVar('pan');
        $memberID = $this->request->getVar('memberID');
        $caseId = rand(100000, 999999);
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
                CURLOPT_URL => "https://hub.perfios.com/api/kyc/v3/pan-profile-detailed",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $data_json,
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/json",
                    "x-auth-key: KzKQbi9Tw8OokmY"
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

                    // GST Number Search  
                    $dataApiGst = array(
                        'pan'              => $panNumber,
                        'consent'          => $consent,
                        "stateCode"         => '19',
                        'clientData'        => array(
                            'caseId'          => $caseId,
                        ),
                    );
                    $data_json_gst = json_encode($dataApiGst);

                    $curlGst = curl_init();

                    curl_setopt_array($curlGst, array(
                        CURLOPT_URL => "https://hub.perfios.com/api/gst/v2/gst-advanced",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $data_json_gst,
                        CURLOPT_HTTPHEADER => array(
                            "content-type: application/json",
                            "x-auth-key: KzKQbi9Tw8OokmY"
                        ),
                    ));

                    $response_gst = curl_exec($curlGst);
                    $err_gst = curl_error($curlGst);
                    $response_decode_gst = json_decode($response_gst, true);

                    curl_close($curlGst);
                    if ($err_gst) {
                        # code...
                        // $gst_ref = "GST Searching Failed!";
                        return $this->respond(['error' => 'Internal Exception!'], 502);
                    } else {
                        # code...
                        $statusCodeGst = $response_decode_gst['statusCode'];
                        if ($statusCodeGst === 101) {
                            # code...
                            $gst_ref = "GST Found!";
                            $gst = $response_decode_gst['result'][0]['gstinId'];
                            // $result = json_decode($result[0], true);
                            // $gst = "N/A";

                        } else {
                            # code...
                            $gst_ref = "GST Not Found for this PAN!";
                            $gst = "N/A";
                        }
                    }

                    $nameResponse = $response_decode['result']['name'];
                    $userDOB = $response_decode['result']['dob'];

                    // End GST Number Search
                    # code...
                    $db = db_connect();
                    $builderMaster = $db->table('members');

                    # code...
                    $dataOutput = array(
                        'pan'               => $panNumber,
                        'gst'               => $gst,
                        'msgGst'            => $gst_ref,
                        'name'              => $nameResponse,
                        'userDOB'           => $userDOB,
                        'msgPan'            => 'PAN Verified',
                        'authenticateGST'     => 'N',
                    );
                    $dataDB = [
                        'pan'               => $panNumber,
                        'authenticatePAN'   => 'Y',
                        'gst'               => $gst,
                        'userDOB'           => $userDOB,
                        'panName'        => $nameResponse,
                        'name'            => $nameResponse,
                        'gstValidation'     => 'N',

                    ];
                    $builderMaster->where('member_id', $memberID)->update($dataDB);
                    return $this->respond(['panVerify' => $dataOutput], 200);
                } else {
                    # code...
                    return $this->respond(['panVerify' => 'Invalid PAN Number'], 406);
                }
            }
        } else {
            # code...
            return $this->respond(['error' => 'Enter Valid PAN Number'], 401);
        }
    }

    public function verify_gst_user()
    {
        $gstNumber = $this->request->getVar('gstin');
        $memberID = $this->request->getVar('memberIDgst');
        $caseId = rand(1000, 9999);
        $consent = "Y";
        $dataApi = array(
            'gstin'              => $gstNumber,
            'consent'          => $consent,
            'clientData' => array(
                'caseId'          => $caseId,
            ),
        );
        $data_json = json_encode($dataApi);

        if ($gstNumber != '') {
            # code...
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://hub.perfios.com/api/gst/v2/gstdetailed-additional",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $data_json,
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/json",
                    "x-auth-key: KzKQbi9Tw8OokmY"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            $response_decode = json_decode($response, true);


            $db = db_connect();
            $builderUpdate = $db->table('members');


            curl_close($curl);
            if ($err) {
                // echo "cURL Error #:" . $err;
                return $this->respond(['error' => 'Invalid Request.' . $err], 401);
            } else {
                // echo $response;
                $dataDB = [

                    'gstValidation'   => 'Y',
                    'gst'   => $response_decode['result']['gstin'],
                    'businessName'  => $response_decode['result']['tradeNam'],

                ];
                $builderUpdate->where('member_id', $memberID)->update($dataDB);
                $dataInsert = [
                    'status'     => $response_decode['result']['sts'],
                    'memberID'               => $memberID,
                    'gst'         => $response_decode['result']['gstin'],
                    'gstType'      => $response_decode['result']['dty'],
                    'holderEmail'   => $response_decode['result']['contacted']['email'],
                    'holderMobile'  => $response_decode['result']['contacted']['mobNum'],
                    'holderName'    => $response_decode['result']['contacted']['name'],
                    'tradeName'   => $response_decode['result']['tradeNam'],
                    'regDate'       => $response_decode['result']['rgdt'],
                    'businessAddress' => $response_decode['result']['pradr']['adr'],
                    'turnOver' => $response_decode['result']['aggreTurnOver'],
                    'turnOveryear' => $response_decode['result']['aggreTurnOverFY'],
                ];
                $builderMaster = $db->table('gstmaster');
                $builderMaster->upsert($dataInsert);

                // return $this->respond(['gst' => $response_decode], 200);
                return $this->respond($dataInsert, 200);
            }
        }
    }
}

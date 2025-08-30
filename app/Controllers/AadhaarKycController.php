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
















    public function digi_status()
    {
        //Data Input

        $clientIDAdh = $this->request->getVar('clientIDAdh');



        if ($clientIDAdh != '') {
            # code...
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://kyc-api.surepass.app/api/v1/digilocker/list-documents/' . $clientIDAdh,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/json",
                    'Authorization: Bearer ' . getenv('SUREPASS_API_KEY_PROD'),
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            $response_decode = json_decode($response, true);
            log_message('info', 'Digi Locker Verification API List Documents called. Payload: ' . json_encode($response_decode));
            $aadhaarFileId = null;

            foreach ($response_decode['data']['documents'] as $doc) {
                if ($doc['doc_type'] === 'ADHAR') {
                    $aadhaarFileId = $doc['file_id'];
                    break;
                }
            }
            curl_close($curl);

            if ($err) {
                // echo "cURL Error #:" . $err;
                log_message('error', 'Digi Locker Verification API called & Error By Retailer. Payload: ' . $err);
                return $this->respond(['error' => 'Invalid Request.' . $err], 401);
            } else {
                // Get the response of file download
                $curlD = curl_init();

                curl_setopt_array($curlD, array(
                    CURLOPT_URL => 'https://kyc-api.surepass.app/api/v1/digilocker/download-document/' . $clientIDAdh . '/' . $aadhaarFileId,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        "content-type: application/json",
                        'Authorization: Bearer ' . getenv('SUREPASS_API_KEY_PROD'),
                    ),
                ));

                $responseD = curl_exec($curlD);
                $errD = curl_error($curlD);
                $response_decode_d = json_decode($responseD, true);

                curl_close($curlD);
                // Download the PDF file
                $pdfUrl = $response_decode_d['data']['download_url'];
                $fileType = $response_decode_d['data']['mime_type'];
                $ext_fileType = str_replace("application/", "", $fileType);
                // Generate file name and destination path
                $fileName = $clientIDAdh . 'aadhaar.' . $ext_fileType;
                $storagePath = FCPATH . 'uploads/customer_doc/aadhaar/' . $clientIDAdh . '/';
                $fullFilePath = $storagePath . $fileName;

                // Create directory if it does not exist
                if (!is_dir($storagePath)) {
                    mkdir($storagePath, 0777, true);
                }

                // Use file_get_contents and file_put_contents to download and save
                $pdfContent = file_get_contents($pdfUrl);
                if ($pdfContent === false) {
                    // echo "❌ Failed to download PDF.";
                    die("❌ Failed to download file. Check php.ini 'allow_url_fopen' and the URL itself.");
                } else {
                    file_put_contents($fullFilePath, $pdfContent);
                    $uploadPath = 'uploads/customer_doc/aadhaar/' . $clientIDAdh . '/' . $fileName;
                    // Append upload path in response
                    $response_decode_d['aadhaar_file_path'] = 'https://crm.retailpe.in/' . $uploadPath;
                }
                // echo $response;
                log_message('info', 'Aadhaar upload in server. Payload: ' . $uploadPath);
                log_message('info', 'Digi Locker Verification Download Aadhaar. Payload: ' . json_encode($response_decode_d));
                return $this->respond(['kyc' => $response_decode_d], 200);
            }
        } else {
            # code...
            return $this->respond(['error' => 'Server Error.'], 502);
        }
    }
    public function verify_otp()
    {
        // Data Input
        $name = $this->request->getVar('nameAdh');
        $mobile = $this->request->getVar('mobileNumber');



        $dataApi = array(
            "data" => array(
                "prefill_options" => array(
                    "full_name"     => $name,
                    "mobile_number" => $mobile,

                ),
                "expiry_minutes" => 10,
                "send_sms"       => true,
                "send_email"     => false,
                "verify_phone"   => true,
                "verify_email"   => false,
                "signup_flow"    => true,
                "logo_url"       =>   "https://www.retailpe.in/assets/img/Logo/Retail%20Pe.webp",
                "redirect_url"   => "https://crm.retailpe.in/digi-success",
                "state"          => "RetailPeLOS"
            )
        );
        $data_json = json_encode($dataApi);
        log_message('info', 'Digi Locker API called. Payload: ' . $data_json);

        if ($mobile != '') {
            # code...
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://kyc-api.surepass.app/api/v1/digilocker/initialize',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data_json,
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/json",
                    'Authorization: Bearer ' . getenv('SUREPASS_API_KEY_PROD'),
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            $response_decode = json_decode($response, true);

            curl_close($curl);

            if ($err) {
                // echo "cURL Error #:" . $err;
                log_message('error', 'Digi Locker API Error. Payload: ' . $err);
                return $this->respond(['error' => 'Invalid Request.' . $err], 401);
            } else {
                // return $response;
                log_message('info', 'Digi Locker API called & Success. Payload: ' . $response);
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
        log_message('info', 'PAN Verification API called. Payload: ' . $data_json);


        if ($name != '' && $mobile != '') {
            # code...
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://kyc-api.surepass.app/api/v1/pan/mobile-to-pan',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data_json,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . getenv('SUREPASS_API_KEY_PROD'),
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
                        CURLOPT_URL => 'https://kyc-api.surepass.app/api/v1/pan/pan-comprehensive',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => $data_json_pan,
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: Bearer ' . getenv('SUREPASS_API_KEY_PROD'),
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
                                CURLOPT_URL => 'https://kyc-api.surepass.app/api/v1/corporate/gstin-by-pan',
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 0,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'POST',
                                CURLOPT_POSTFIELDS => $data_json_gst,
                                CURLOPT_HTTPHEADER => array(
                                    'Authorization: Bearer ' . getenv('SUREPASS_API_KEY_PROD'),
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
        log_message('info', 'PAN Verification API called. Payload: ' . $data_json);

        if ($panNumber != '') {
            # code...
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://kyc-api.surepass.app/api/v1/pan/pan-comprehensive',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data_json,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . getenv('SUREPASS_API_KEY_PROD'),
                    // 'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJmcmVzaCI6ZmFsc2UsImlhdCI6MTc0ODM0MzIxOCwianRpIjoiYzAxY2ZmNDItZTBkYi00YjdhLWFkZWMtZmJmNmE1M2JmZDQwIiwidHlwZSI6ImFjY2VzcyIsImlkZW50aXR5IjoiZGV2Lm5hbWFub2poYTdAc3VyZXBhc3MuaW8iLCJuYmYiOjE3NDgzNDMyMTgsImV4cCI6MTc1MDkzNTIxOCwiZW1haWwiOiJuYW1hbm9qaGE3QHN1cmVwYXNzLmlvIiwidGVuYW50X2lkIjoibWFpbiIsInVzZXJfY2xhaW1zIjp7InNjb3BlcyI6WyJ1c2VyIl19fQ.kyAlKocj2wsHG5vc34NMdKUPa7d4jKBMHlLzuJoUUpY',
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            $response_decode = json_decode($response, true);

            curl_close($curl);
            log_message('info', 'PAN Verification API cURL Called. Payload: ' . $data_json);
            log_message('info', 'PAN Verification API cURL Response : ' . $response ?? $err);
            if ($err) {
                // echo "cURL Error #:" . $err;
                return $this->respond(['error' => 'Internal Exception!' . $err], 502);
                log_message('error', 'PAN Verification API Failed : ' . json_encode($err));
            } else {
                // return $response;
                log_message('info', 'PAN Verification API Success. Payload: ' . $response);
                if ($response_decode['status_code'] === 200) {
                    # code... get gst from pan
                    $dataApiGst = array(
                        'id_number'              => $response_decode['data']['pan_number'],

                    );
                    $data_json_gst = json_encode($dataApiGst);
                    $curlGst = curl_init();
                    curl_setopt_array($curlGst, array(
                        CURLOPT_URL => 'https://kyc-api.surepass.app/api/v1/corporate/gstin-by-pan',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => $data_json_gst,
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: Bearer ' . getenv('SUREPASS_API_KEY_PROD'),
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
                        log_message('info', 'PAN Verification API & GST Search by PAN API called & Success. Payload: ' . $response_gst);
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
                    log_message('error', 'PAN Verification API & GST Search by PAN API called & Failed. API Down');
                    return $this->respond(['error' => 'Server Error'], 500);
                }
            }
        } else {
            # code...
            log_message('error', 'PAN Verification API & GST Search by PAN API called & Failed. PAN number not send!');
            return $this->respond(['error' => 'Internal Error'], 502);
        }
    }
    public function verify_gst()
    {
        $gstNumber = $this->request->getVar('gstin');
        $mobile = $this->request->getVar('mobile');
        $mobileFour = substr($mobile, -4);

        $dataApi = array(
            'id_number'              => $gstNumber,

        );
        $data_json = json_encode($dataApi);
        log_message('info', 'GST Validation API called. Payload: ' . $data_json);

        if ($gstNumber != '') {
            # code...
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://kyc-api.surepass.app/api/v1/corporate/gstin-advanced",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data_json,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . getenv('SUREPASS_API_KEY_PROD'),
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
                $name_str = strtoupper($response_decode['data']['business_name']);
                $name_str = str_replace(" ", "", $name_str);
                $name_str = substr($name_str, 0, 4);
                $name_str = str_replace(['.', '/'], '', $name_str);
                $db = db_connect();
                $dataInsert = [
                    'status'            => $response_decode['data']['gstin_status'],
                    'memberID'          => $name_str . $mobileFour,
                    'gst'               => $gstNumber,
                    'gstType'           => $response_decode['data']['taxpayer_type'],
                    'holderEmail'       => $response_decode['data']['contact_details']['principal']['email'],
                    'holderMobile'      => $response_decode['data']['contact_details']['principal']['mobile'],
                    'holderName'        => $response_decode['data']['promoters'][0],
                    'tradeName'         => $response_decode['data']['business_name'],
                    'regDate'           => $response_decode['data']['date_of_registration'],
                    'businessAddress'   => $response_decode['data']['contact_details']['principal']['address'],
                    'turnOver'          => $response_decode['data']['annual_turnover'],
                    'turnOveryear'      => $response_decode['data']['annual_turnover_fy'],
                ];
                $builderMaster = $db->table('gstmaster');
                $builderMaster->upsert($dataInsert);
                log_message('info', 'GST Validation API called & Success & Data Updated. Response: ' . $response);

                return $this->respond(['gst' => $response_decode], 200);
            }
        }
    }
    // Voter ID
    public function verify_voter_id()
    {
        // Data Input
        $voterNumber = $this->request->getVar('voterNumber');




        $dataApi = array(

            "id_number" => $voterNumber,

        );
        $data_json = json_encode($dataApi);
        log_message('info', 'Voter ID API called. Payload: ' . $data_json);

        if ($voterNumber != '') {
            # code...
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://kyc-api.surepass.app/api/v1/voter-id/voter-id',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data_json,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . getenv('SUREPASS_API_KEY_PROD'),
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);
            $respone_decode_voter = json_decode($response, true);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                // echo "cURL Error #:" . $err;
                return $this->respond(['error' => 'Invalid Request.' . $err], 401);
            } else {
                // return $response;
                log_message('info', 'Voter ID API called & Success. Payload: ' . $response);
                return $this->respond(['voter_id' => $respone_decode_voter], 200);
            }
        } else {
            # code...
            log_message('error', 'Voter ID API called Voter ID number not send!');
            return $this->respond(['error' => 'Internal Error'], 502);
        }
    }
    // --------------------------------------------

    // --------------------------------------------

    // PAN VERIFICATION FOR CUSTOMER APPLICATION
    public function verify_pan_user()
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
                CURLOPT_URL => 'https://kyc-api.surepass.app/api/v1/pan/pan-comprehensive',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data_json,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . getenv('SUREPASS_API_KEY_PROD'),
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
                        CURLOPT_URL => 'https://kyc-api.surepass.app/api/v1/corporate/gstin-by-pan',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => $data_json_gst,
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: Bearer ' . getenv('SUREPASS_API_KEY_PROD'),
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
                        log_message('info', 'PAN Verification for User API & GST Search by PAN API called & Success. Payload: ' . $response_gst);
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

    public function verify_gst_user()
    {

        $gstNumber = $this->request->getVar('gstin');
        $mobile = $this->request->getVar('mobile');
        $mobileFour = substr($mobile, -4);

        $dataApi = array(
            'id_number'              => $gstNumber,

        );
        $data_json = json_encode($dataApi);

        if ($gstNumber != '') {
            # code...
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://kyc-api.surepass.app/api/v1/corporate/gstin-advanced",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data_json,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . getenv('SUREPASS_API_KEY_PROD'),
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
                $name_str = strtoupper($response_decode['data']['business_name']);
                $name_str = str_replace(" ", "", $name_str);
                $name_str = substr($name_str, 0, 4);
                $db = db_connect();
                $dataInsert = [
                    'status'            => $response_decode['data']['gstin_status'],
                    'memberID'          => $name_str . $mobileFour,
                    'gst'               => $gstNumber,
                    'gstType'           => $response_decode['data']['taxpayer_type'],
                    'holderEmail'       => $response_decode['data']['contact_details']['principal']['email'],
                    'holderMobile'      => $response_decode['data']['contact_details']['principal']['mobile'],
                    'holderName'        => $response_decode['data']['promoters'][0],
                    'tradeName'         => $response_decode['data']['business_name'],
                    'regDate'           => $response_decode['data']['date_of_registration'],
                    'businessAddress'   => $response_decode['data']['contact_details']['principal']['address'],
                    'turnOver'          => $response_decode['data']['annual_turnover'],
                    'turnOveryear'      => $response_decode['data']['annual_turnover_fy'],
                ];
                $builderMaster = $db->table('gstmaster');
                $builderMaster->upsert($dataInsert);
                return $this->respond(['gst' => $response_decode], 200);
            }
        }
    }
}

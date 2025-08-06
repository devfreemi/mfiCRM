<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\BankModel;
use CodeIgniter\API\ResponseTrait;

class BankController extends BaseController
{
    use ResponseTrait;
    public function add_bank()
    {
        //
        $rules = [
            'acc_no' => ['rules' => 'required|min_length[5]|max_length[64]|is_unique[banks.acc_no]'],
            'ifsc' => ['rules' => 'required|min_length[11]|max_length[11]'],

        ];


        if ($this->validate($rules)) {
            $model = new BankModel();

            $data = [
                'acc_no'         => $this->request->getVar('acc_no'),
                'ifsc'           => $this->request->getVar('ifsc'),
                'bankName'       => $this->request->getVar('bankName'),
                'branch'         => $this->request->getVar('branch'),
                'bankCity'       => $this->request->getVar('bankCity'),
                'bankAddress'    => $this->request->getVar('bankAddress'),

            ];

            $query = $model->insert($data);
            if (!$query) {
                # code...
                $session = session();
                $session->setFlashdata('msg', 'Invalid Inputs');
                return redirect()->to(base_url() . 'bank');
            } else {
                # code...
                $session = session();
                $session->setFlashdata('success', 'Bank Details Updated!');
                return redirect()->to(base_url() . 'bank');
            }
        } else {
            $session = session();
            $session->setFlashdata('msg', 'Internal Exception!');
            return redirect()->to(base_url() . 'bank');
        }
    }


    public function bank_list_api()
    {
        //
        $model = new BankModel();


        $bank = $model->findAll();

        if (is_null($bank)) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        }

        return $this->respond($bank, 200);
    }

    public function bank_verification()
    {
        $meber_id = $this->request->getVar('memberId_bank');
        $dataApi = array(
            'id_number'                 => $this->request->getVar('acc_no'),
            'ifsc'                      => $this->request->getVar('ifsc_code'),
            "ifsc_details"              => true

        );
        $data_json = json_encode($dataApi);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://kyc-api.surepass.app/api/v1/bank-verification/',
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

        $responseDown = curl_exec($curl);
        // $responseDown = curl_exec($curlDown);
        $error_an = curl_error($curl);
        $response_decode_an = json_decode($responseDown, true);
        $data = [
            'bankName' => $response_decode_an['data']['ifsc_details']['bank_name'],
            'ifsc' => $response_decode_an['data']['ifsc_details']['ifsc'],
            'bankBranch' => $response_decode_an['data']['ifsc_details']['branch'],
            'bankCity' => $response_decode_an['data']['ifsc_details']['city'],
            'bankState' => $response_decode_an['data']['ifsc_details']['state'],

            'bankAddress' => $response_decode_an['data']['ifsc_details']['address'],
            'bankAccount' => $this->request->getVar('acc_no'),
            // 'code' => $response_decode_an['status_code'],
        ];
        curl_close($curl);
        if ($error_an) {
            # code...
            return $this->respond(['error' => 'Internal Exception!' . $error_an], 502);
        } else {
            # code...
            $db = db_connect();
            $builder = $db->table('members');
            $builder->where('member_id', $meber_id);
            $builder->update($data);
            return $this->respond([
                'bank_verified' => $response_decode_an
            ], 200);
        }
    }
    // Bank Verification for RM app
    public function bank_verification_rm_app()
    {

        $dataApi = array(
            'id_number'                 => $this->request->getVar('acc_no'),
            'ifsc'                      => $this->request->getVar('ifsc_code'),
            "ifsc_details"              => true

        );
        $data_json = json_encode($dataApi);
        log_message('info', 'Bank Verification Initiated. Payload: ' . $data_json);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://kyc-api.surepass.app/api/v1/bank-verification/',
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

        $responseDown = curl_exec($curl);
        // $responseDown = curl_exec($curlDown);
        $error_an = curl_error($curl);
        $response_decode_an = json_decode($responseDown, true);

        curl_close($curl);
        if ($error_an) {
            # code...
            log_message('error', 'Bank Verification Failed. Error: ' . $error_an);
            return $this->respond(['error' => 'Internal Exception!' . $error_an], 502);
        } else {
            # code...
            log_message('info', 'Bank Verification Success. Payload: ' . $responseDown);
            return $this->respond([
                'bank_verified' => $response_decode_an
            ], 200);
        }
    }
}

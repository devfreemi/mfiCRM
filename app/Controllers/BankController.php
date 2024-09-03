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
}

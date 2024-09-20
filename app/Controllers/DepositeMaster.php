<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DepositeMasterModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;


class DepositeMaster extends BaseController
{
    use ResponseTrait;
    public function submit_deposite_master()
    {
        //
        $model = new DepositeMasterModel();
        $data = [

            'agent'                         => $this->request->getVar('employeeUpload'),
            'deposited_amount'              => $this->request->getVar('totalDeposite'),
            'bank_name'                     => $this->request->getVar('bank'),
            'bank_account_number'           => $this->request->getVar('bank'),
            'deposited_date'                => $this->request->getVar('depositeDateApi'),
            'receipt_url'                   => $this->request->getVar('downloadURLP1'),

        ];


        $query = $model->save($data);
        if (!$query) {
            return $this->respond(['error' => 'Invalid Request.' . $query], 401);
        } else {
            # code...
            return $this->respond(['deposite' => $data], 200);
        }
    }
}

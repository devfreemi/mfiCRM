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
            'deposited_amount'              => $this->request->getVar('collectionAmt'),
            'deposited_date'                => $this->request->getVar('collectionDateApi'),
            'bank_name'                     => $this->request->getVar('collectionDateApi'),
            'bank_account_number'           => $this->request->getVar('collectionDateApi'),
            'receipt_url'                   => $this->request->getVar('employeeId'),
            'agent'                         => $this->request->getVar('employeeId'),

        ];

        // $query = $model->insert($data);
        $query = $model->save($data);
        if (!$query) {
            return $this->respond(['error' => 'Invalid Request.' . $query], 401);
        } else {
            # code...
            return $this->respond(['deposite' => $data], 200);
        }
    }
}

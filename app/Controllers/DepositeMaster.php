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

        $agentID = $this->request->getVar('employeeUpload');
        $uniqId = $this->request->getVar('employeeUpload') . "" . $this->request->getVar('depositeDateApi');
        $uniqId = str_replace(' ', '', $uniqId);
        $uniqId = str_replace('/', '', $uniqId);
        $uniqId = str_replace(':', '', $uniqId);
        $uniqId = str_replace(',', '', $uniqId);

        $data = [

            'agent'                         => $this->request->getVar('employeeUpload'),
            'deposited_amount'              => $this->request->getVar('totalDeposite'),
            'bank_name'                     => $this->request->getVar('bank'),
            'bank_account_number'           => $this->request->getVar('bank'),
            'deposited_date'                => $this->request->getVar('depositeDateApi'),
            'receipt_url'                   => $this->request->getVar('downloadURLP1'),
            'uniqId'                        => $uniqId

        ];


        $query = $model->save($data);

        // Deposite Table
        $db = db_connect();
        $builder = $db->table('bank_deposites');
        $dataUpdate = [
            'submitted' => 'Y'
        ];
        $p = $builder->where('agent', $agentID)
            ->where('created_at', date('Y-m-d'))
            ->where('submitted', 'N')->update($dataUpdate);

        if (!$query) {
            return $this->respond(['error' => 'Invalid Request.' . $query], 401);
        } else {
            # code...
            return $this->respond(['deposite' => $data], 200);
        }
    }
}

<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;
use App\Models\RetailerDocumentsModel;


class RetailerDocumentsController extends BaseController
{
    use ResponseTrait;
    public function add_doc()
    {
        //
        date_default_timezone_set('Asia/Kolkata');
        $model = new RetailerDocumentsModel();


        $data = [
            'member_id'         => $this->request->getVar('member_id'),
            'document_path'     => $this->request->getVar('document_path'),
            'document_type'     => $this->request->getVar('doc_type'),
            'created_at'        => date('Y-m-d H:i:s')
        ];

        // $query = $model->save($data);
        $db = db_connect();
        $builder = $db->table('retailerdocuments');
        // $builder->where('member_id', $this->request->getVar('member_id'));
        $builder->insert($data);
        $query = $builder->get();
        if (!$query) {
            return $this->respond(['error' => 'Invalid Request.' . $query], 401);
        } else {
            # code...
            return $this->respond(['documents' => $data], 200);
        }
    }
}

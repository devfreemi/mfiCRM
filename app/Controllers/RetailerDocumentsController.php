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
        // date_default_timezone_set('Asia/Kolkata');
        // $model = new RetailerDocumentsModel();


        // $data = [
        //     'member_id'         => $this->request->getVar('member_id'),
        //     'document_path'     => $this->request->getVar('document_path'),
        //     'document_type'     => $this->request->getVar('doc_type'),
        //     'created_at'        => date('Y-m-d H:i:s')
        // ];

        // // $query = $model->save($data);
        // $db = db_connect();
        // $builder = $db->table('retailerdocuments');
        // // $builder->where('member_id', $this->request->getVar('member_id'));
        // $builder->insert($data);
        // $query = $builder->get();
        // if (!$query) {
        //     return $this->respond(['error' => 'Invalid Request.' . $query], 401);
        // } else {
        //     # code...
        //     return $this->respond(['documents' => $data], 200);
        // }
        date_default_timezone_set('Asia/Kolkata');
        $model = new RetailerDocumentsModel();

        $memberId = $this->request->getVar('member_id');
        $docType = $this->request->getVar('doc_type');
        $docPath = $this->request->getVar('document_path');

        $db = db_connect();
        $builder = $db->table('retailerdocuments');

        // 🔍 Count existing uploads for this doc type and member
        $countQuery = $builder->selectCount('id')
            ->where('member_id', $memberId)
            ->where('document_type', $docType)
            ->get()
            ->getRow();

        $existingCount = $countQuery->id ?? 0;

        // 🛑 Limit logic
        if (($docType === 'Shop_Image' && $existingCount >= 3) ||
            ($docType !== 'Shop_Image' && $existingCount >= 1)
        ) {
            return $this->respond([
                'status' => false,
                'message' => 'Upload limit reached for this document type.'
            ], 406);
        }

        // ✅ Insert if within limits
        $data = [
            'member_id'     => $memberId,
            'document_path' => $docPath,
            'document_type' => $docType,
            'created_at'    => date('Y-m-d H:i:s')
        ];

        $builder->insert($data);

        return $this->respond([
            'status' => true,
            'message' => 'Document uploaded successfully.',
            'documents' => $data
        ], 200);
    }

    // Check if the shop image has been uploaded 3 times
    public function check_shop_image_status()
    {
        $memberId = $this->request->getVar('member_id');

        if (!$memberId) {
            return $this->respond([
                'status' => false,
                'message' => 'member_id is required.'
            ], 400);
        }

        $db = db_connect();
        $builder = $db->table('retailerdocuments');

        // Count Shop_Image uploads for this member
        $count = $builder->where('member_id', $memberId)
            ->where('document_type', 'Shop_Image')
            ->countAllResults();

        if ($count >= 3) {
            return $this->respond([
                'status' => true,
                'message' => 'image captured'
            ], 201);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'not captured'
            ], 200);
        }
    }

    // Check PAN AND Voter ID
    public function check_pan_voter_status()
    {
        $memberId = $this->request->getVar('member_id');

        if (!$memberId) {
            return $this->respond([
                'status' => false,
                'message' => 'member_id is required.'
            ], 400);
        }

        $db = db_connect();
        $builder = $db->table('retailerdocuments');

        // Check Pan_Card
        $panExists = $builder->where('member_id', $memberId)
            ->where('document_type', 'PAN_ID')
            ->countAllResults();

        // Reset builder for next query
        $builder = $db->table('retailerdocuments');

        // Check Voter_ID
        $voterExists = $builder->where('member_id', $memberId)
            ->where('document_type', 'VOTER_ID')
            ->countAllResults();

        if ($panExists > 0 && $voterExists > 0) {
            return $this->respond([
                'status' => true,
                'message' => 'captured'
            ], 201);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'not captured'
            ], 200);
        }
    }
}

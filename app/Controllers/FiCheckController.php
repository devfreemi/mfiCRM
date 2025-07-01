<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\FiCheckModel;

class FiCheckController extends BaseController
{
    public function submit()
    {
        helper(['form']);

        try {
            // Check for duplicate FI entry (optional safety)
            $memberId = $this->request->getPost('member_id');
            $model = new \App\Models\FiCheckModel();

            if ($model->where('member_id', $memberId)->first()) {
                throw new \Exception("FI already submitted for Member ID: $memberId");
            }

            $data = [
                'member_id' => $this->request->getPost('member_id'),
                'fiInspector_name' => $this->request->getPost('fiInspector_name'),

                'retailer_present' => $this->request->getPost('retailer_present'),
                'retailer_behavior_professional' => $this->request->getPost('retailer_behavior_professional'),
                'retailer_aware_products' => $this->request->getPost('retailer_aware_products'),
                'retailer_needs_training' => $this->request->getPost('retailer_needs_training'),

                'shop_clean' => $this->request->getPost('shop_clean'),
                'products_displayed' => $this->request->getPost('products_displayed'),
                'stock_available' => $this->request->getPost('stock_available'),
                'promo_materials_visible' => $this->request->getPost('promo_materials_visible'),
                'location_accessible' => $this->request->getPost('location_accessible'),

                'payment_behavior' => $this->request->getPost('payment_behavior'),
                'documents_received' => implode(', ', $this->request->getPost('documents_received') ?? []),
                'verified_fields' => implode(', ', $this->request->getPost('verified_fields') ?? []),
                'shop_ownership' => $this->request->getPost('shop_ownership'),
                'house_ownership' => $this->request->getPost('house_ownership'),
                'documents_verified' => $this->request->getPost('documents_verified'),
                'inspector_comments' => $this->request->getPost('inspector_comments'),
            ];

            $model->insert($data);
            // return redirect()->to('/feedback/success');

            $session = session();
            $session->setFlashdata('msg', 'Saved!');
            return redirect()->to('fi-report');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('server_error', $e->getMessage());
        }
    }
    public function report()
    {
        $model = new FiCheckModel();
        $data['feedbacks'] = $model->orderBy('created_at', 'DESC')->findAll();
        return view('feedback_report', $data);
    }
}

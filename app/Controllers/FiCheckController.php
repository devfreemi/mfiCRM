<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\FiCheckModel;
use App\Models\LoanModel;

class FiCheckController extends BaseController
{
    public function submit()
    {
        helper(['form']);

        try {
            $memberId = $this->request->getPost('member_id');
            $model = new \App\Models\FiCheckModel();

            // Prevent duplicate FI
            if ($model->where('member_id', $memberId)->first()) {
                throw new \Exception("FI already submitted for Member ID: $memberId");
            }


            // Handle location fields
            $latitude = $this->request->getPost('latitude');
            $longitude = $this->request->getPost('longitude');
            $placeName = $this->request->getPost('place_name');

            // File uploads
            $shopPhoto = $this->request->getFile('shop_photo');
            $selfiePhoto = $this->request->getFile('selfie_with_owner');

            $shopPhotoPath = '';
            $selfiePhotoPath = '';

            if ($shopPhoto && $shopPhoto->isValid() && !$shopPhoto->hasMoved()) {
                $shopPhotoName = $shopPhoto->getRandomName();
                $shopPhoto->move(FCPATH . 'uploads/shop_photos', $shopPhotoName);
                $shopPhotoPath = 'uploads/shop_photos/' . $shopPhotoName;
            }

            if ($selfiePhoto && $selfiePhoto->isValid() && !$selfiePhoto->hasMoved()) {
                $selfiePhotoName = $selfiePhoto->getRandomName();
                $selfiePhoto->move(FCPATH . 'uploads/selfies', $selfiePhotoName);
                $selfiePhotoPath = 'uploads/selfies/' . $selfiePhotoName;
            }

            // Expected keys
            $expectedFields = [
                'name',
                'mobile',
                'address',
                'business_type',
                'daily_sales',
                'stock_value',
                'monthly_purchase'
            ];

            $verifiedFieldsInput = $this->request->getPost('verified_fields') ?? [];
            $correctedFields = $this->request->getPost('corrected_fields') ?? [];

            // Count mismatches
            $notVerified = [];
            foreach ($expectedFields as $field) {
                if (!isset($verifiedFieldsInput[$field]) || strtolower($verifiedFieldsInput[$field]) !== 'yes') {
                    $notVerified[] = $field;
                }
            }

            $totalFields = count($expectedFields);
            $unverifiedCount = count($notVerified);
            if ($totalFields === 0) {
                throw new \Exception("No verifiable fields submitted.");
            }

            // Deviation logic
            // $deviation = ($unverifiedCount / $totalFields) * 100;
            // if ($deviation >= 70) {
            //     $fiStatus = '❌ Field Investigation failed. Major mismatches: ' . implode(', ', $notVerified);
            //     $fiResult = 'FI failed';
            // } elseif ($deviation >= 40) {
            //     $fiStatus = 'Review needed. Mismatches in: ' . implode(', ', $notVerified);
            //     $fiResult = 'FI Needs Review';
            // } elseif ($deviation > 0) {

            //     $fiStatus = 'Minor issues: ' . implode(', ', $notVerified);
            //     $fiResult = 'FI Success';
            // } else {
            //     $fiResult = 'FI Success';
            //     $fiStatus = 'FI passed. All data matched.';
            // }
            $deviation = ($unverifiedCount / $totalFields) * 100;

            // Extra qualitative checks
            $fiChecks = [
                'retailer_present',
                'retailer_behavior_professional',
                'retailer_aware_products',
                'shop_clean',
                'products_displayed',
                'stock_available',
                'promo_materials_visible',
                'location_accessible',
                'payment_behavior'
            ];

            // Count how many are "No" or empty
            $fiFailCount = 0;
            foreach ($fiChecks as $field) {
                $value = strtolower(trim($this->request->getPost($field)));
                if (in_array($value, ['no', '0', 'false', ''])) {
                    $fiFailCount++;
                }
            }

            $totalFiChecks = count($fiChecks);
            $fiFailPercent = ($fiFailCount / $totalFiChecks) * 100;

            // Combine both (business mismatch + field failures)
            if ($deviation >= 70 || $fiFailPercent >= 50) {
                $fiStatus = '❌ FI Failed due to high mismatch and/or poor shop conditions.';
                $fiResult = 'FI failed';
                $fi_final = 'N';
                $application_stage = 'Rejected';
            } elseif ($deviation >= 40 || $fiFailPercent >= 30) {
                $fiStatus = '⚠️ FI Needs Review - Some mismatches and inspection concerns.';
                $fiResult = 'FI Needs Review';
                $fi_final = 'W';
                $application_stage = 'approval_pending';
            } else {
                $fiStatus = ($unverifiedCount > 0 ? 'Minor mismatches: ' . implode(', ', $notVerified) : 'FI passed. All matched.');
                $fiResult = 'FI Success';
                $fi_final = 'Y';
                $application_stage = 'approved';
            }
            // Inspector overrides (if mismatch)
            $inspectedSales = in_array('daily_sales', $notVerified) ? $correctedFields['daily_sales'] ?? null : $correctedFields['daily_sales'];
            $inspectedStock = in_array('stock_value', $notVerified) ? $correctedFields['stock_value'] ?? null : $correctedFields['stock_value'];
            $inspectedPurchase = in_array('monthly_purchase', $notVerified) ? $correctedFields['monthly_purchase'] ?? null : $correctedFields['monthly_purchase'];

            // Save data
            $data = [
                'member_id' => $memberId,
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
                'verified_fields' => implode(', ', array_keys(array_filter($verifiedFieldsInput, fn($v) => strtolower($v) === 'yes'))),
                'shop_ownership' => $this->request->getPost('shop_ownership'),
                'house_ownership' => $this->request->getPost('house_ownership'),
                'documents_verified' => $this->request->getPost('documents_verified'),
                'inspector_comments' => $this->request->getPost('inspector_comments'),
                'inspector_daily_sales' => $inspectedSales,
                'inspector_stock_value' => $inspectedStock,
                'inspector_month_purchase' => $inspectedPurchase,
                'fi_status' => $fiStatus,
                'fi_final'  => $fi_final,

                // New location fields
                'latitude' => $latitude,
                'longitude' => $longitude,
                'place_name' => $placeName,

                // Image paths
                'shop_photo' => $shopPhotoPath,
                'selfie_with_owner' => $selfiePhotoPath,
            ];

            $model->insert($data);

            $model = new LoanModel();
            $data_loan = [

                'groupId'       => $this->request->getPost('groupId'),
                'member_id'     => $this->request->getPost('member_id'),
                'loan_amount'   => $this->request->getPost('eligible_amount'),
                'loan_tenure'   => $this->request->getPost('tenure'),
                'roi'           => $this->request->getPost('roi'),
                'employee_id'   => $this->request->getPost('agent'),
                'loan_status'     => $fiResult,
                'application_stage' => $application_stage,
                'applicationID' => date('ym') . str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT) . $this->request->getPost('mobile'),

            ];
            // $query = $model->insert($data);
            $query = $model->save($data_loan);

            // Update the member's details status
            $data_update = [

                'stock'             =>  $inspectedStock,
                'dailySales'        => $inspectedSales,

                'month_purchase'    => $inspectedPurchase,

            ];
            $db = db_connect();
            $builder = $db->table('members');
            $builder->where('member_id', $this->request->getPost('member_id'));
            $query = $builder->update($data_update);
            // Normal flow
            session()->setFlashdata('msg', $fi_final);
            return view('fi-success');
        } catch (\Throwable $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => false,
                    'error' => $e->getMessage()
                ]);
            }

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

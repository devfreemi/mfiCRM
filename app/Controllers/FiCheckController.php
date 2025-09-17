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
                'month_purchase'
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
                $fiResult = 'Re initiate FI';
                $fi_final = 'W';
                $application_stage = 'approval_pending';
            } else {
                $fiStatus = ($unverifiedCount > 0 ? 'Minor mismatches: ' . implode(', ', $notVerified) : 'FI passed. All matched.');
                $fiResult = 'Approved';
                $fi_final = 'Y';
                $application_stage = 'FI Success';
            }
            // Inspector overrides (if mismatch)
            $inspectedSales = in_array('daily_sales', $notVerified) ? $correctedFields['daily_sales'] ?? null : $correctedFields['daily_sales'];
            $inspectedStock = in_array('stock_value', $notVerified) ? $correctedFields['stock_value'] ?? null : $correctedFields['stock_value'];
            $inspectedPurchase = in_array('month_purchase', $notVerified) ? $correctedFields['month_purchase'] ?? null : $correctedFields['month_purchase'];

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
            $db = db_connect();
            // $model = new LoanModel();

            $loan_amount = $this->request->getVar('eligible_amount');
            $tenure = $this->request->getVar('tenure');
            // Exact Date calculation
            $start = new \DateTime();
            $end = (clone $start)->modify("+$tenure months");
            $diff = $start->diff($end);
            $day_tenure =  $diff->days;


            // $day_tenure = $tenure * 30;
            $roi = $this->request->getVar('roi');
            $r = ($roi / 100 / 12);

            // Total interest (Flat): (P × R × N years)
            $interest = ($loan_amount * $roi * ($tenure / 12)) / 100;

            // Total amount payable
            $due = round($loan_amount + $interest);

            // Flat EMI: Total payable / total months
            $emi = round($due / $tenure, 2);
            $disbursable = round($loan_amount - (($loan_amount * 0.04) + 2643.2), 2);
            $chargesandinsurance = round(($loan_amount * 0.04) + 2643.20, 2);

            $data_loan = [

                'groupId'       => $this->request->getVar('groupId'),
                'member_id'     => $this->request->getVar('member_id'),
                'loan_amount'   => $this->request->getVar('eligible_amount'),
                'loan_tenure'   => $this->request->getVar('tenure'),
                'roi'           => $this->request->getVar('roi'),
                'emi'               =>  $emi,
                'pending_emi'       =>  $day_tenure,
                'emi_day'          => round($due / $day_tenure, 2),
                'total_amount'      => $due,
                'disbursable_amount' => $disbursable,
                'chargesandinsurance' => $chargesandinsurance,
                'insurance_fee'      =>  2643.20,
                'interest'          =>  $interest,
                'employee_id'   => $this->request->getVar('agent'),
                'loan_status'     => $fiResult,
                'application_stage' => $application_stage,
                'updated_at' => date('Y-m-d H:i:s'),

            ];
            // $query = $model->insert($data);
            // $query = $model->save($data_loan);
            $builderLoan = $db->table('loans');
            $builderLoan->where('member_id', $this->request->getVar('member_id'));
            $builderLoan->update($data_loan);
            // Update the member's details status
            $data_update = [

                'stock'             =>  $inspectedStock,
                'dailySales'        => $inspectedSales,

                'month_purchase'    => $inspectedPurchase,

            ];

            $builder = $db->table('members');
            $builder->where('member_id', $this->request->getPost('member_id'));
            $query = $builder->update($data_update);
            // Normal flow
            session()->setFlashdata('msg', $fi_final);
            return view('fi-success', ['member_id' => $this->request->getPost('member_id')]);
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

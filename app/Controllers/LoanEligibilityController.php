<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\LoanEligibilityModel;
use CodeIgniter\API\ResponseTrait;

date_default_timezone_set('Asia/Kolkata');

class LoanEligibilityController extends BaseController
{
    use ResponseTrait;
    public function checkEligibility()
    {
        $request = service('request');
        if ($this->request->getVar('remarks') === 'Reject') {
            $data_update = [
                'location'          => $this->request->getVar('memberLocation'),
                'pincode'           => $this->request->getVar('groupPin'),
                'mobile'            => $this->request->getVar('mobile'),
                'pan'               => $this->request->getVar('pan'),
                'gst'               => $this->request->getVar('gst'),
                'adhar'             => $this->request->getVar('adhar'),
                'footFall'          => $this->request->getVar('footFall'),
                'stock'             => $this->request->getVar('stock'),
                'outstanding'       => $this->request->getVar('previous_emi'),
                'estab'             => $this->request->getVar('business_time'),
                'dailySales'        => $this->request->getVar('daily_sales'),
                'name'              => $this->request->getVar('name'),
                'businessName'              => $this->request->getVar('businessName'),
                'eli_run'           => "Y",
                'month_purchase'    => $this->request->getVar('month_purchase'),
                'remarks'    => $this->request->getVar('remarks'),
                'comments'    => $this->request->getVar('comments')
            ];
            $db = db_connect();
            $builder = $db->table('members');
            $builder->where('member_id', $request->getVar('memberID'));
            $query = $builder->update($data_update);
            return view('members');
        } else {

            $name = $this->request->getVar('name');
            $mobile = $this->request->getVar('mobile');
            $panNumber = $this->request->getVar('pan');
            $dataApi = array(
                'name'              => $name,
                'consent'           => "Y",
                "mobile"            => $mobile,
                "pan"               => $panNumber
            );
            $data_json = json_encode($dataApi);


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://kyc-api.surepass.app/api/v1/credit-report-experian/fetch-report',
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
                    // 'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJmcmVzaCI6ZmFsc2UsImlhdCI6MTc0ODM0MzIxOCwianRpIjoiYzAxY2ZmNDItZTBkYi00YjdhLWFkZWMtZmJmNmE1M2JmZDQwIiwidHlwZSI6ImFjY2VzcyIsImlkZW50aXR5IjoiZGV2Lm5hbWFub2poYTdAc3VyZXBhc3MuaW8iLCJuYmYiOjE3NDgzNDMyMTgsImV4cCI6MTc1MDkzNTIxOCwiZW1haWwiOiJuYW1hbm9qaGE3QHN1cmVwYXNzLmlvIiwidGVuYW50X2lkIjoibWFpbiIsInVzZXJfY2xhaW1zIjp7InNjb3BlcyI6WyJ1c2VyIl19fQ.kyAlKocj2wsHG5vc34NMdKUPa7d4jKBMHlLzuJoUUpY',
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            $response_decode = json_decode($response, true);
            log_message('info', 'Experian CIBIL Check API Called and Success: ' . $response);

            $cibil = $response_decode['data']['credit_score'];
            $cibilReport    = $response;

            curl_close($curl);
            // Get input data from the form
            if ($request->getVar('previous_emi') === "") {
                # code...
                $previous_emi = 0;
            } else {
                # code...
                $previous_emi = $request->getVar('previous_emi');
            }
            if ($request->getVar('business_time') === "") {
                # code...
                $business_time = 0;
            } else {
                # code...
                $current_year = date('Y');
                $business_time = round($current_year - $request->getVar('business_time'));
            }
            log_message('info', 'Rule Engine API response: ' . $this->request->getVar('previous_emi'));

            $data = [
                'stock' => $request->getVar('stock'),
                'daily_sales' => $request->getVar('daily_sales'),
                'cibil_score' => $cibil,
                'business_time' => $business_time,
                'location' => $request->getVar('location'),
                'business_type' => $request->getVar('business_type'),
                'previous_emi' => $previous_emi,
                'memberId' => $request->getVar('memberID'),
                'image_profile' => $request->getVar('image_profile')
            ];

            // Load the LoanEligibilityModel and pass input data
            $loanModel = new LoanEligibilityModel();
            $loanModel->setData($data);

            $result = $loanModel->calculateLoanEligibility();
            // Merge input data with result for passing to view
            $data['result'] = $result;
            // echo "<pre>";
            // print_r($data);
            // echo "<br>";
            // member data update
            $data_update = [
                'location'          => $this->request->getVar('memberLocation'),
                'pincode'           => $this->request->getVar('groupPin'),
                'mobile'            => $this->request->getVar('mobile'),
                'pan'               => $this->request->getVar('pan'),
                'gst'               => $this->request->getVar('gst'),
                'adhar'             => $this->request->getVar('adhar'),
                'footFall'          => $this->request->getVar('footFall'),
                'stock'             => $this->request->getVar('stock'),
                'outstanding'       => $this->request->getVar('previous_emi'),
                'estab'             => $this->request->getVar('business_time'),
                'dailySales'        => $this->request->getVar('daily_sales'),
                'name'              => $this->request->getVar('name'),
                'businessName'              => $this->request->getVar('businessName'),
                'eli_run'           => "Y",
                'month_purchase'    => $this->request->getVar('month_purchase'),
                'remarks'    => $this->request->getVar('remarks'),
                'comments'    => $this->request->getVar('comments')
            ];
            $db = db_connect();
            $builder = $db->table('members');
            $builder->where('member_id', $request->getVar('memberID'));
            $query = $builder->update($data_update);

            // member data update
            $data_eli_run = [
                'cibil' => $cibil,
                'member_id' => $request->getVar('memberID'),
                'first_date' => date('Y-m-d'),
                'loan_amount' => $result['LoanAmount'],
                'roi' => $result['FixedROI'],
                'tenure' => $result['Tenure'],
                'emi' => $result['EMI'],
                'score' => $result['Score'],
                'eligibility' => $result['Eligibility'],
                'reason' => $result['Reason'],
                'cibilReport' => $cibilReport,
            ];
            $db = db_connect();
            $builder = $db->table('initial_eli_run');
            $builder->upsert($data_eli_run);

            return view('eli-page', $data);
        }
    }


    // API
    public function checkEligibilityAPI()
    {
        log_message('info', '✅ checkEligibilityAPI called');

        // default/placeholder cibil (keep as before)
        $cibil = 680;

        // sanitize inputs
        $memberIdApi = $this->request->getVar('memberId_api') ?? $this->request->getVar('memberId') ?? null;
        $previous_emi = $this->request->getVar('previous_emi') === "" ? 0 : (float) $this->request->getVar('previous_emi');
        if ($this->request->getVar('business_time') === "" || is_null($this->request->getVar('business_time'))) {
            $business_time = 0;
        } else {
            $current_year = date('Y');
            // If client passed an establishment year, convert to age; otherwise if they passed years, this may need adjustment.
            $raw = $this->request->getVar('business_time');
            $business_time = (int) ($raw > 1900 ? round($current_year - (int)$raw) : (int)$raw);
        }

        $daily_sales = (float) ($this->request->getVar('daily_sales') ?? 0);
        $purchase_monthly_input = $this->request->getVar('purchase_monthly');
        $purchase_monthly = is_null($purchase_monthly_input) || $purchase_monthly_input === '' ? null : (float)$purchase_monthly_input;
        $stock = (float) ($this->request->getVar('stock') ?? 0);
        $location = $this->request->getVar('location') ?? 'rural';
        $business_type = $this->request->getVar('business_type') ?? '';
        $memberId = $memberIdApi;

        // Fetch bank_statement_reports CAMS JSON
        $db = db_connect();
        $builder = $db->table('bank_statement_reports');
        $builder->select('*');
        $builder->where('member_id', $memberId);
        $row = $builder->get()->getRow();

        $consolidated = [];
        $scorecard_summary = [];
        if ($row) {
            $report_data = json_decode($row->report_json, true);

            // try to locate CAM sheets & scorecard safely
            $camSheets = $report_data['data']['statement']['Bankstatement 1']['CAM Sheet'] ?? [];
            $scorecard = $report_data['data']['statement']['Bankstatement 1']['Summary - Scorecard'] ?? [];

            // find consolidated row (case-insensitive)
            $consolidated = null;
            foreach ($camSheets as $sheet) {
                if (isset($sheet['Month']) && strtolower($sheet['Month']) === 'consolidated') {
                    $consolidated = $sheet;
                    break;
                }
            }
            if (!$consolidated && !empty($camSheets)) {
                // Fallback: sometimes consolidated might be last row or key named differently — try to find a row containing many expected keys
                foreach ($camSheets as $sheet) {
                    if (isset($sheet['Total debit transactions sum']) || isset($sheet['Total of EMI Amount'])) {
                        $consolidated = $sheet;
                        break;
                    }
                }
            }
            $consolidated = $consolidated ?? [];

            // Build a small scorecard summary
            foreach ($scorecard as $item) {
                $itemName = $item['Item'] ?? '';
                $itemDetail = $item['Details'] ?? null;

                if (in_array($itemName, ['Monthly Average Balance', 'Monthly Average Surplus'])) {
                    $scorecard_summary[$itemName] = $itemDetail;
                }
            }
        } else {
            log_message('warning', "No bank_statement_reports found for member {$memberId}");
        }

        // Parse consolidated CAM values safely
        $totalDebit = isset($consolidated['Total debit transactions sum']) ? (float) $consolidated['Total debit transactions sum'] : 0;
        $totalOutwardUPI = isset($consolidated['Total of Outward UPI Amount']) ? (float) $consolidated['Total of Outward UPI Amount'] : 0;
        $totalEMI = isset($consolidated['Total of EMI Amount']) ? (float) $consolidated['Total of EMI Amount'] : 0;
        $investment = isset($consolidated['Investment Made Amount']) ? (float) $consolidated['Investment Made Amount'] : 0;
        $upiInward = isset($consolidated['Total of Inward UPI Amount']) ? (float) $consolidated['Total of Inward UPI Amount'] : 0;

        // finalValue calculation (as you specified)
        $finalValue = $totalDebit - ($totalOutwardUPI + $totalEMI + $investment);

        // monthly sales (from provided daily_sales) — controller should compute and pass it
        $monthly_sales = $daily_sales * 30;

        // Build unified data object to pass to model
        $data = [
            'stock' => $stock,
            'daily_sales' => $daily_sales,
            'monthly_sales' => $monthly_sales,
            'purchase_monthly' => $purchase_monthly,
            'cibil_score' => $cibil,
            'business_time' => $business_time,
            'location' => $location,
            'business_type' => $business_type,
            'previous_emi' => $previous_emi,
            'memberId' => $memberId,
            // CAMS data / consolidated
            'cam_consolidated' => $consolidated,
            'scorecard_summary' => $scorecard_summary,
            'totalDebit' => $totalDebit,
            'totalOutwardUPI' => $totalOutwardUPI,
            'totalEMI' => $totalEMI,
            'investment' => $investment,
            'finalValue' => $finalValue,
            'upiInward' => $upiInward,
        ];

        // send to model
        $loanModel = new LoanEligibilityModel();
        $loanModel->setData($data);
        $result = $loanModel->calculateLoanEligibility();

        // store/record summary in initial_eli_run (keep previous behaviour)
        $data_eli_run = [
            'cibil' => $cibil,
            'member_id' => $memberId,
            'cibilReport' => null,
            'first_date' => date('Y-m-d'),
            'loan_amount' => $result['LoanAmount'] ?? 0,
            'roi' => $result['FixedROI'] ?? 0,
            'tenure' => $result['Tenure'] ?? 0,
            'emi' => $result['EMI'] ?? 0,
            'score' => $result['Score'] ?? 0,
            'eligibility' => $result['Eligibility'] ?? 'Not Eligible',
            'reason' => $result['Reason'] ?? '',
        ];
        $builder = $db->table('initial_eli_run');
        // use replace or insert/update compatible with your CI4 version
        if (method_exists($builder, 'upsert')) {
            $builder->upsert($data_eli_run);
        } else {
            // fallback: try replace by member_id
            $existing = $builder->where('member_id', $memberId)->get()->getRow();
            if ($existing) {
                $builder->where('member_id', $memberId)->update($data_eli_run);
            } else {
                $builder->insert($data_eli_run);
            }
        }

        log_message('info', 'Rule Engine API response: ' . json_encode($data_eli_run));

        // Return the model's structured result
        return $this->respond(['member' => $data, 'result' => $result], 200);
    }

    public function get_approval()
    {

        $memberId = $this->request->getVar('memberEliID');
        $loanModel = new LoanEligibilityModel();
        $data = $loanModel->where('member_id', $memberId)->first();

        if (is_null($data)) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        }

        return $this->respond(['data_loan' => $data], 200);
    }
    public function approved_retailer()
    {

        $empID = $this->request->getVar('employeeID');
        $loanModel = new LoanEligibilityModel();
        $data = $loanModel->join('members', 'members.member_id  = initial_eli_run.member_id')
            // ->join('loans', 'loans.member_id = initial_eli_run.member_id', 'left')
            ->where('initial_eli_run.eligibility', 'Eligible')
            ->where('agent', $empID)->findAll();


        if (is_null($data)) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        } else {
            # code...
            log_message('info', 'Approved Retailers List API called. Employee ID: ' . $empID);
            return $this->respond($data, 200);
        }
    }
    public function approved_retailer_data()
    {

        $memID = $this->request->getVar('memberID');
        $loanModel = new LoanEligibilityModel();
        $data = $loanModel->join('members', 'members.member_id  = initial_eli_run.member_id')
            ->where('initial_eli_run.member_id ', $memID)->first();


        if (is_null($data)) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        } else {
            # code...
            log_message('info', 'Approved Retailers List API called. Employee ID: ' . json_encode($data));
            return $this->respond($data, 200);
        }
    }
    public function cibil_report($memberId)
    {
        $model = new LoanEligibilityModel();
        $record = $model->where('member_id', $memberId)
            ->orderBy('id', 'DESC')
            ->first();

        if (!$record) {
            return "No reports available.";
        }

        $reportData = json_decode($record['cibilReport'], true);

        // print_r($reportData);
        return view('cibil-report', ['data' => $reportData]);
        // return view('cibil-report', ['data' => $data]);
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class LoanEligibilityModel extends Model
{
    protected $table            = 'initial_eli_run';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['member_id', 'first_date', 'second_date', 'eligibility', 'loan_amount', 'roi', 'tenure', 'score', 'eligibilityV2', 'loan_amountV2', 'roiV2', 'tenureV2', 'scoreV2', 'reasonV2', 'reason', 'cibil', 'cibilReport', 'created_at', 'updated_at'];

    /* --------------------------
     * State (input) fields
     * -------------------------- */
    protected $stock = 0;
    protected $daily_sales = 0;
    protected $dailyUPI = 0;
    protected $monthly_sales = 0;
    protected $purchase_monthly = null;
    protected $cibil_score = 0;
    protected $cibil_report = null;
    protected $business_time = 0;
    protected $location = 'rural';
    protected $business_type = '';
    protected $previous_emi = 0;
    protected $memberId = null;
    protected $totalEMIAmountFromCibil = 0;
    // CAMS fields
    protected $cam_consolidated = [];
    protected $scorecard_summary = [];
    protected $totalDebit = 0;
    protected $totalOutwardUPI = 0;
    protected $totalEMI = 0;
    protected $investment = 0;
    protected $finalValue = null;
    protected $upiInward = 0;

    /* --------------------------
     * setData - populate model inputs
     * -------------------------- */
    public function setData(array $data)
    {
        log_message('info', 'LoanEligibilityModel::setData called with ' . json_encode(array_keys($data)));

        $this->memberId = $data['memberId'] ?? null;
        $this->stock = isset($data['stock']) ? (float)$data['stock'] : 0;
        $this->daily_sales = isset($data['daily_sales']) ? (float)$data['daily_sales'] : 0;
        $this->dailyUPI = isset($data['dailyUPI']) ? (float)$data['dailyUPI'] : 0;
        $this->monthly_sales = isset($data['monthly_sales']) ? (float)$data['monthly_sales'] : ($this->daily_sales * 30);
        $this->purchase_monthly = array_key_exists('purchase_monthly', $data) ? ($data['purchase_monthly'] === null ? null : (float)$data['purchase_monthly']) : null;
        $this->cibil_score = isset($data['cibil_score']) ? (float)$data['cibil_score'] : 0;
        $this->cibil_report = $data['cibil_report'] ?? null;
        $this->business_time = isset($data['business_time']) ? (int)$data['business_time'] : 0;
        $this->location = $data['location'] ?? 'rural';
        $this->business_type = $data['business_type'] ?? '';
        $this->previous_emi = isset($data['previous_emi']) ? (float)$data['previous_emi'] : 0;

        $this->cam_consolidated = $data['cam_consolidated'] ?? [];
        $this->scorecard_summary = $data['scorecard_summary'] ?? [];
        $this->totalDebit = isset($data['totalDebit']) ? (float)$data['totalDebit'] : (isset($this->cam_consolidated['Total debit transactions sum']) ? (float)$this->cam_consolidated['Total debit transactions sum'] : 0);
        $this->totalOutwardUPI = isset($data['totalOutwardUPI']) ? (float)$data['totalOutwardUPI'] : (isset($this->cam_consolidated['Total of Outward UPI Amount']) ? (float)$this->cam_consolidated['Total of Outward UPI Amount'] : 0);
        $this->totalEMI = isset($data['totalEMI']) ? (float)$data['totalEMI'] : (isset($this->cam_consolidated['Total of EMI Amount']) ? (float)$this->cam_consolidated['Total of EMI Amount'] : 0);
        $this->investment = isset($data['investment']) ? (float)$data['investment'] : (isset($this->cam_consolidated['Investment Made Amount']) ? (float)$this->cam_consolidated['Investment Made Amount'] : 0);
        $this->finalValue = isset($data['finalValue']) ? (float)$data['finalValue'] : null;
        $this->upiInward = isset($data['upiInward']) ? (float)$data['upiInward'] : (isset($this->cam_consolidated['Total of Inward UPI Amount']) ? (float)$this->cam_consolidated['Total of Inward UPI Amount'] : 0);

        log_message('info', "Model inputs set: memberId={$this->memberId}, daily_sales={$this->daily_sales}, monthly_sales={$this->monthly_sales}, cibil={$this->cibil_score}, dailyUPI = {$this->dailyUPI}");
    }

    /* --------------------------
     * Microservice-style check methods
     * Each method returns ['status' => bool, 'reason' => string|null, 'meta' => array]
     * and logs input/result.
     * -------------------------- */

    // public function serviceFOIR()
    // {
    //     log_message('info', "serviceFOIR: computing FOIR for daily_sales={$this->daily_sales}, previous_emi={$this->previous_emi}, business_type={$this->business_type}");

    //     $foirResult = $this->calculateFOIREligibleEMI($this->daily_sales, $this->business_type, $this->previous_emi);
    //     log_message('info', 'serviceFOIR: result ' . json_encode($foirResult));

    //     return ['status' => true, 'reason' => null, 'meta' => ['foir' => $foirResult]];
    // }

    // public function serviceFOIRCapacityCheck()
    // {
    //     $foir = $this->calculateFOIREligibleEMI($this->daily_sales, $this->business_type, $this->previous_emi);
    //     $eligible_emi = $foir['EligibleEMI'] ?? 0;
    //     log_message('info', "serviceFOIRCapacityCheck: eligible_emi={$eligible_emi}");

    //     if ($eligible_emi <= 0) {
    //         log_message('error', "serviceFOIRCapacityCheck: failed - no EMI capacity");
    //         return ['status' => false, 'reason' => 'Customer has no EMI capacity after existing obligations.', 'meta' => ['foir' => $foir]];
    //     }

    //     log_message('info', "serviceFOIRCapacityCheck: passed");
    //     return ['status' => true, 'reason' => null, 'meta' => ['foir' => $foir]];
    // }



    public function serviceFOIRCapacityCheck()
    {
        log_message('info', "serviceFOIRCheck: computing FOIR for daily_sales={$this->daily_sales}, previous_emi={$this->previous_emi}, business_type={$this->business_type}");

        $foirResult = $this->calculateFOIREligibleEMI($this->daily_sales, $this->business_type, $this->previous_emi);
        log_message('info', 'serviceFOIRCheck: FOIR result ' . json_encode($foirResult));

        $eligible_emi = $foirResult['EligibleEMI'] ?? 0;
        log_message('info', "serviceFOIRCheck: eligible_emi={$eligible_emi}");

        if ($eligible_emi <= 0) {
            log_message('error', "serviceFOIRCheck: failed - no EMI capacity");
            return [
                'status' => false,
                'reason' => 'Customer has no EMI capacity after existing obligations.',
                'meta'   => ['foir' => $foirResult]
            ];
        }

        log_message('info', "serviceFOIRCheck: passed");
        return [
            'status' => true,
            'reason' => null,
            'meta'   => ['foir' => $foirResult]
        ];
    }

    // FOIR based Risk o meter
    public function serviceFOIRUsageProfile($foir_meta)
    {
        log_message('info', "serviceFOIRUsageProfile: FOIRLIMIT={$foir_meta['FOIRLIMIT']}, ExistingEMI={$foir_meta['ExistingEMI']}, EligibleEMI={$foir_meta['EligibleEMI']}");

        // Safety check
        if ($foir_meta['FOIRLIMIT'] <= 0) {
            log_message('error', "serviceFOIRUsageProfile: FOIR limit invalid");
            return [
                'status' => false,
                'reason' => 'Invalid FOIR limit',
                'meta'   => $foir_meta
            ];
        }

        // FOIR usage %
        $usagePercent = ($foir_meta['ExistingEMI'] / $foir_meta['FOIRLIMIT']) * 100;

        // Defaults
        $score = 0;
        $roiDelta = 0;
        $risk = '';


        if ($usagePercent <= 25) {
            $risk = 'Very Low Risk';
            $score = 5;
            $roiDelta = -2;
        } elseif ($usagePercent <= 40) {
            $risk = 'Moderately Low Risk';
            $score = 4;
            $roiDelta = -1.5;
        } elseif ($usagePercent <= 55) {
            $risk = 'Low Risk';
            $score = 3;
            $roiDelta = -1;
        } elseif ($usagePercent <= 70) {
            $risk = 'Moderate Risk';
            $score = 2;
            $roiDelta = 1;
        } elseif ($usagePercent <= 85) {
            $risk = 'Moderately High Risk';
            $score = 1;
            $roiDelta = 1.5;
        } elseif ($usagePercent <= 100) {
            $risk = 'High Risk';
            $score = -1.5;
            $roiDelta = 2;
        } else {
            $risk = 'Very High Risk';
            $score = -3;
            $roiDelta = 3;
        }

        log_message('info', "serviceFOIRUsageProfile: usagePercent={$usagePercent}, risk={$risk}, score={$score}, roiDelta={$roiDelta}");

        return [
            'status' => ($risk !== 'Very High Risk'),
            'reason' => ($risk === 'Very High Risk') ? 'FOIR usage too high' : null,
            'meta'   => [
                'usagePercent' => round($usagePercent, 2),
                'risk' => $risk,
                'score' => $score,
                'roiDelta' => $roiDelta,
                'foirDetails' => $foir_meta
            ]
        ];
    }
    public function serviceCibil()
    {
        // log_message('info', "serviceCibil: checking cibil_score={$this->cibil_score}");

        // if ($this->cibil_score >= 800) {
        //     log_message('info', "serviceCibil: excellent");
        //     return ['status' => true, 'reason' => null, 'meta' => ['scoreDelta' => 3]];
        // } elseif ($this->cibil_score >= 750) {
        //     log_message('info', "serviceCibil: very good");
        //     return ['status' => true, 'reason' => null, 'meta' => ['scoreDelta' => 2]];
        // } elseif ($this->cibil_score >= 700) {
        //     log_message('info', "serviceCibil: good");
        //     return ['status' => true, 'reason' => null, 'meta' => ['scoreDelta' => 1.5]];
        // } elseif ($this->cibil_score >= 670) {
        //     log_message('info', "serviceCibil: acceptable");
        //     return ['status' => true, 'reason' => null, 'meta' => ['scoreDelta' => 1]];
        // } elseif ($this->cibil_score > 0) {
        //     log_message('error', "serviceCibil: failed - low score {$this->cibil_score}");
        //     return ['status' => false, 'reason' => 'Low CIBIL score', 'meta' => ['cibil' => $this->cibil_score]];
        // } else {
        //     log_message('warning', "serviceCibil: no cibil score");
        //     return ['status' => true, 'reason' => null, 'meta' => ['scoreDelta' => -1, 'note' => 'No CIBIL score']];
        // }
        // Decode CIBIL report if available
        log_message('info', "serviceCibil: checking cibil_score={$this->cibil_score}");
        // log_message('info', "serviceCibil: checking cibil_report={$this->cibil_report}");
        // Decode CIBIL report JSON
        $report = [];
        if (!empty($this->cibil_report)) {
            $report = json_decode($this->cibil_report, true);
        }

        // Extract key values
        $totalOutstanding = (float) ($report['data']['credit_report']['CAIS_Account']['CAIS_Summary']['Total_Outstanding_Balance']['Outstanding_Balance_All'] ?? 0);
        $totalLoanAccounts = (float) ($report['data']['credit_report']['CAIS_Account']['CAIS_Summary']['Credit_Account']['CreditAccountTotal'] ?? 0);

        $totalActiveLoanAmount = 0;
        $totalEMIAmount = 0; // new
        $activeLoanWiseEMI = [];

        if (!empty($report['data']['credit_report']['CAIS_Account']['CAIS_Account_DETAILS'])) {
            foreach ($report['data']['credit_report']['CAIS_Account']['CAIS_Account_DETAILS'] as $account) {
                if (
                    !empty($account['Portfolio_Type']) &&
                    $account['Portfolio_Type'] === 'I' &&
                    isset($account['Account_Status']) &&
                    $account['Account_Status'] == '11'
                ) {
                    $currBal = (float) ($account['Current_Balance'] ?? 0);
                    $totalActiveLoanAmount += $currBal;

                    // Find EMI Amount
                    $emiAmount = 0;
                    if (!empty($account['Account_Review_Data'])) {
                        foreach ($account['Account_Review_Data'] as $review) {
                            if (!empty($review['EMI_Amount'])) {
                                $emiAmount = (float) $review['EMI_Amount'];
                                $totalEMIAmount += $emiAmount; // add to total
                                break;
                            }
                        }
                    }

                    $activeLoanWiseEMI[] = [
                        'account_number'  => $account['Account_Number'] ?? '',
                        'current_balance' => $currBal,
                        'emi_amount'      => $emiAmount
                    ];
                }
            }
        }
        $this->totalEMIAmountFromCibil = $totalEMIAmount;
        // ===== Log CIBIL summary =====
        $summary = [
            'CIBIL Score'           => $this->cibil_score,
            'Total Outstanding'     => $totalOutstanding,
            'Total Loan Accounts'   => $totalLoanAccounts,
            'Active Loan Amount'    => $totalActiveLoanAmount,
            'Total EMI Amount'      => $totalEMIAmount,
            'Active Loan EMI Count' => count($activeLoanWiseEMI)
        ];


        log_message('info', 'CIBIL Summary: ' . json_encode($summary));

        foreach ($activeLoanWiseEMI as $loan) {
            log_message('info', "Loan: Account={$loan['account_number']}, Balance={$loan['current_balance']}, EMI={$loan['emi_amount']}");
        }
        // ===== Negative History Check =====
        $hasNegativeHistory = false;
        $negativeAccounts = [];
        if (!empty($report['data']['credit_report']['CAIS_Account']['CAIS_Account_DETAILS'])) {
            foreach ($report['data']['credit_report']['CAIS_Account']['CAIS_Account_DETAILS'] as $account) {
                $status = $account['Account_Status'] ?? '';
                $settleCode = $account['Written_off_Settled_Status'] ?? '';
                $dpd = (int)($account['Days_Past_Due'] ?? 0);

                $negativeStatusCodes = ['71', '78', '89', '93', '97']; // loss, settled, suit filed, wilful default, written-off
                $negativeSettlementCodes = ['03', '02', '04', '08', '13', '15', '16', '17']; // settled, written-off settled, other
                if (in_array($status, $negativeStatusCodes) || in_array($settleCode, $negativeSettlementCodes) || $dpd > 0) {
                    $hasNegativeHistory = true;
                    $negativeAccounts[] = [
                        'account_number' => $account['Account_Number'] ?? '',
                        'status' => $status,
                        'settlement_status' => $settleCode,
                        'dpd' => $dpd
                    ];
                }
            }
        }

        if ($hasNegativeHistory) {
            log_message('error', 'Negative loan history found: ' . json_encode($negativeAccounts));
            return [
                'status' => true,
                'reason' => 'Loan settled / overdue / default found in credit history',
                'meta' => [
                    'totalOutstanding' => $totalOutstanding,
                    'totalLoanAccounts' => $totalLoanAccounts,
                    'totalActiveLoanAmount' => $totalActiveLoanAmount,
                    'totalEMIAmount' => $totalEMIAmount,
                    'activeLoanWiseEMI' => $activeLoanWiseEMI,
                    'negativeAccounts' => $negativeAccounts
                ]
            ];
        }
        // ===== STRICT LOGIC =====
        // Example rules:

        // if ($totalOutstanding > 500000) { // ₹5L+ total outstanding
        //     return ['status' => false, 'reason' => 'High total outstanding balance', 'meta' => compact('totalOutstanding', 'totalLoanAccounts', 'totalActiveLoanAmount', 'activeLoanWiseEMI')];
        // }
        // if ($totalActiveLoanAmount > 300000) { // ₹3L+ active loan amount
        //     return ['status' => false, 'reason' => 'High active loan exposure', 'meta' => compact('totalOutstanding', 'totalLoanAccounts', 'totalActiveLoanAmount', 'activeLoanWiseEMI')];
        // }

        // Keep your old CIBIL score slab logic if needed
        if ($this->cibil_score >= 800) {
            return ['status' => true, 'reason' => null, 'meta' => ['scoreDelta' => 3]];
        } elseif ($this->cibil_score >= 750) {
            return ['status' => true, 'reason' => null, 'meta' => ['scoreDelta' => 2]];
        } elseif ($this->cibil_score >= 700) {
            return ['status' => true, 'reason' => null, 'meta' => ['scoreDelta' => 1.5]];
        } elseif ($this->cibil_score >= 670) {
            return ['status' => true, 'reason' => null, 'meta' => ['scoreDelta' => 1]];
        } elseif ($this->cibil_score > 0) {
            return ['status' => false, 'reason' => 'Low CIBIL score', 'meta' => compact('totalOutstanding', 'totalLoanAccounts', 'totalActiveLoanAmount', 'activeLoanWiseEMI')];
        } else {
            return ['status' => true, 'reason' => null, 'meta' => ['scoreDelta' => -1]];
        }
    }

    public function serviceUPIInwardCheck()
    {
        $monthly = $this->monthly_sales ?: ($this->daily_sales * 30);
        $daily_UPI = $this->dailyUPI ?: ($this->upiInward / 60);
        $dailySales = $this->daily_sales ?: 0; // avoid division by zero
        $required = 0.3 * $dailySales;
        $deviation = 100; // allowed deviation
        log_message('info', "serviceUPIInwardCheck: upiInward={$this->upiInward}, monthly_sales={$monthly}, daily_UPI={$daily_UPI}, required={$required}");

        if ($dailySales > 0 && $daily_UPI < ($required - $deviation)) {
            log_message('info', "serviceUPIInwardCheck: not matched get higher ROI - upiInward < required");
            return ['status' => false, 'reason' => 'UPI inward is less than 30% of daily sales.', 'meta' => ['upiInward' => $daily_UPI, 'required' => $required, 'scoreDelta' => -0.5, 'roiDelta' => 0.50]];
        }

        log_message('info', "serviceUPIInwardCheck: passed");
        return ['status' => true, 'reason' => null, 'meta' => ['upiInward' => $this->upiInward, 'required' => $required]];
    }

    public function serviceFinalValueCalculation()
    {
        $finalValue = is_null($this->finalValue) ? ($this->totalDebit - ($this->totalOutwardUPI + $this->totalEMI + $this->investment)) : $this->finalValue;
        log_message('info', "serviceFinalValueCalculation: totalDebit={$this->totalDebit}, totalOutwardUPI={$this->totalOutwardUPI}, totalEMI={$this->totalEMI}, investment={$this->investment}, finalValue={$finalValue}");

        return ['status' => true, 'reason' => null, 'meta' => ['finalValue' => $finalValue]];
    }

    public function servicePurchaseDeviationCheck()
    {
        $fvRes = $this->serviceFinalValueCalculation();
        $finalValue = $fvRes['meta']['finalValue'] ?? 0;


        if ($finalValue <= 0) {
            log_message('error', "servicePurchaseDeviationCheck: failed - outward transactions amount is invalid (calculated={$finalValue})");
            return [
                'status' => false,
                'reason' => 'Calculated outward transactions amount is zero or negative — inconsistent CAMS data.',
                'meta'   => ['calculated_outward' => $finalValue]
            ];
        }

        if (is_null($this->purchase_monthly)) {
            log_message('info', "servicePurchaseDeviationCheck: customer’s declared monthly outward payment not provided - skipping check");
            return [
                'status' => false,
                'reason' => 'Customer’s monthly outward payment not available for comparison.',
                'meta'   => ['calculated_outward' => $finalValue, 'skipped' => true]
            ];
        }

        $allowedDeviation = 0.15 * $finalValue;
        log_message(
            'info',
            "servicePurchaseDeviationCheck: declared_outward={$this->purchase_monthly}, calculated_outward={$finalValue}, allowedDeviation={$allowedDeviation}"
        );

        if (abs($this->purchase_monthly - $finalValue) > $allowedDeviation) {
            log_message(
                'error',
                "servicePurchaseDeviationCheck: failed - customer’s declared outward payments indicate higher dues/overdues to distributor/dealer (deviation beyond 5%)"
            );
            $score = 0;
            $roiDelta = 2.5;
            $notes = '';
            return ['status' => true, 'score' => $score, 'roiDelta' => $roiDelta, 'notes' => 'High Purchase and Debit Transaction deviation'];
        }

        log_message('info', "servicePurchaseDeviationCheck: passed - customer’s declared outward payment is consistent");
        return [
            'status' => true,
            'reason' => null,
            'meta'   => [
                'calculated_outward' => $finalValue,
                'allowedDeviation'   => $allowedDeviation
            ]
        ];
    }
    // Business Growth Check
    public function serviceBusinessGrowthCheck()
    {
        // Calculate net stock change
        $net_stock_change = ($this->stock + $this->purchase_monthly) - ($this->daily_sales * 30);

        $score = 0;
        $roiDelta = 0;
        $notes = '';

        // Calculate 10% tolerance
        $tolerance = $this->stock * 0.10;

        // Determine growth status with tolerance
        if ($net_stock_change >= ($this->stock - $tolerance)) {
            $growthStatus = 'Growth';
            $stockChange = 'Stock Within 10% Range';
            $score = 2;
            $roiDelta = -1;
        } elseif ($net_stock_change < ($this->stock - $tolerance)) {
            $growthStatus = 'De-growth';
            $stockChange = 'Stock Decreased';
            $score = -2;
            $roiDelta = 1;
        } else {
            $growthStatus = 'Stable';
            $stockChange = 'Stock Within 10% Range';
            $score = 1;
            $roiDelta = -0.5;
        }

        $notes = "Business Status: {$growthStatus}, {$stockChange}";

        // Logs
        log_message('info', "serviceBusinessGrowthCheck: net_stock_change={$net_stock_change}, tolerance={$tolerance}");
        log_message('info', "serviceBusinessGrowthCheck: score={$score}, roiDelta={$roiDelta}, notes={$notes}");

        // Return result
        return [
            'score' => $score,
            'roiDelta' => $roiDelta,
            'notes' => $notes
        ];
    }
    /* --------------------------
     * Scoring microservices: business age, sales, stock, location, business type
     * Each returns ['score' => float, 'roiDelta' => float, 'notes' => string|null]
     * -------------------------- */

    public function serviceBusinessAgeScore()
    {
        log_message('info', "serviceBusinessAgeScore: business_time={$this->business_time}");
        $score = 0;
        $roiDelta = 0;
        $notes = '';

        if ($this->business_time >= 5) {
            $score = 2;
            $roiDelta = -1.5;
        } elseif ($this->business_time >= 2) {
            $score = 1;
            $roiDelta = -0.5;
        } else {
            $score = 0;
            $roiDelta = 2;
            $notes = 'Low business age.';
        }

        log_message('info', "serviceBusinessAgeScore: score={$score}, roiDelta={$roiDelta}");
        return ['score' => $score, 'roiDelta' => $roiDelta, 'notes' => $notes];
    }

    public function serviceDailySalesScore()
    {
        log_message('info', "serviceDailySalesScore: daily_sales={$this->daily_sales}");
        $score = 0;
        $roiDelta = 0;
        $notes = '';
        if ($this->daily_sales == 0) {
            log_message('error', 'serviceDailySalesScore: Zero sales');
            return ['status' => false, 'reason' => 'Zero sales', 'meta' => null];
        }
        if ($this->daily_sales >= 10000) {
            $score = 3;
            $roiDelta = -1.5;
        } elseif ($this->daily_sales >= 5000) {
            $score = 2;
            $roiDelta = -0.5;
        } elseif ($this->daily_sales >= 2000) {
            $score = 1.5;
        } else {
            $score = 0;
            $roiDelta = 2;
            $notes = 'Low sales.';
        }

        log_message('info', "serviceDailySalesScore: score={$score}, roiDelta={$roiDelta}");
        return ['score' => $score, 'roiDelta' => $roiDelta, 'notes' => $notes];
    }

    public function serviceStockScore()
    {
        log_message('info', "serviceStockScore: stock={$this->stock}");
        $score = 0;
        $roiDelta = 0;
        $notes = '';

        if ($this->stock >= 1000000) {
            $score = 4;
            $roiDelta = -2;
        } elseif ($this->stock >= 500000) {
            $score = 3;
            $roiDelta = -1.5;
        } elseif ($this->stock >= 100000) {
            $score = 2;
            $roiDelta = 1;
        } elseif ($this->stock >= 50000) {
            $score = 1;
            $roiDelta = 2;
        } else {
            $score = 0;
            $roiDelta = 2.5;
            $notes = 'Low stock.';
        }

        log_message('info', "serviceStockScore: score={$score}, roiDelta={$roiDelta}");
        return ['score' => $score, 'roiDelta' => $roiDelta, 'notes' => $notes];
    }

    public function serviceLocationScore()
    {
        $loc = strtolower($this->location);
        $map = ['metro' => 2, 'urban' => 1.5, 'semi-urban' => 1, 'suburban' => 0.5, 'rural' => 0];
        $score = $map[$loc] ?? 0;
        $roiDelta = - ($map[$loc] ?? 0);

        log_message('info', "serviceLocationScore: location={$this->location}, score={$score}, roiDelta={$roiDelta}");
        return ['score' => $score, 'roiDelta' => $roiDelta, 'notes' => null];
    }

    public function serviceBusinessTypeScore()
    {
        log_message('info', "serviceBusinessTypeScore: business_type={$this->business_type}");
        $essential = ['Grocery', 'Pharmacy', 'Stationery'];
        $service = ['Pet Shop', 'Food stall', 'Sweet Shop'];
        $retail = ['Furniture', 'Electrical', 'Electronics and Appliances'];

        $score = 0;
        $roiDelta = 0;
        $notes = null;
        if (in_array($this->business_type, $essential)) {
            $score = 2;
            $roiDelta = -1;
        } elseif (in_array($this->business_type, $service)) {
            $score = 1;
            $roiDelta = -0.5;
        } elseif (in_array($this->business_type, $retail)) {
            $score = 0.5;
            $roiDelta = 1;
        }

        log_message('info', "serviceBusinessTypeScore: score={$score}, roiDelta={$roiDelta}");
        return ['score' => $score, 'roiDelta' => $roiDelta, 'notes' => $notes];
    }

    /* --------------------------
     * EMI calculation helper (flat interest)
     * -------------------------- */
    public function serviceCalculateEMI($principal, $annual_rate, $tenure_months)
    {
        log_message('info', "serviceCalculateEMI: principal={$principal}, annual_rate={$annual_rate}, tenure_months={$tenure_months}");
        if ($tenure_months == 0) return 0;
        $interest = ($principal * $annual_rate * ($tenure_months / 12)) / 100;
        $total_payable = $principal + $interest;
        $emi = $total_payable / $tenure_months;
        log_message('info', "serviceCalculateEMI: emi={$emi}");
        return $emi;
    }

    /* --------------------------
     * Aggregator - orchestrates checks and scoring, returns final structured output
     * -------------------------- */
    public function calculateLoanEligibility()
    {
        log_message('info', 'calculateLoanEligibility: start for member ' . ($this->memberId ?? 'N/A'));

        // 1. FOIR capacity check
        $foirCheck = $this->serviceFOIRCapacityCheck();
        if (!$foirCheck['status']) {
            log_message('error', 'calculateLoanEligibility: rejected by FOIR capacity');
            return [
                "Eligibility" => "Not Eligible",
                "Reason" => $foirCheck['reason'],
                "LoanAmount" => 0,
                "ROI" => 0,
                "FixedROI" => 0,
                "Tenure" => 0,
                "Score" => 0,
                "EMI" => 0,
                "FOIR" => $foirCheck['meta']['foir'] ?? $this->calculateFOIREligibleEMI($this->daily_sales, $this->business_type, $this->previous_emi),
            ];
        }
        $foir_meta = $foirCheck['meta']['foir'];
        // 2. FOIR usage profile scoring
        $foirCheck = $this->serviceFOIRUsageProfile($foir_meta);
        if (!$foirCheck['status']) {
            log_message('error', 'calculateLoanEligibility: rejected by FOIR usage check');
            return [
                "Eligibility" => "Not Eligible",
                "Reason" => $foirCheck['reason'],
                "LoanAmount" => 0,
                "ROI" => 0,
                "FixedROI" => 0,
                "Tenure" => 0,
                "Score" => 0,
                "EMI" => 0,
                "FOIR" => $foirCheck['meta'],
            ];
        }

        // 2. CAMS checks: UPI inward
        $upiCheck = $this->serviceUPIInwardCheck();
        if (!$upiCheck['status']) {
            log_message('error', 'calculateLoanEligibility: rejected by UPI inward check');
            return [
                "Eligibility" => "Not Eligible",
                "Reason" => $upiCheck['reason'],
                "LoanAmount" => 0,
                "ROI" => 0,
                "FixedROI" => 0,
                "Tenure" => 0,
                "Score" => 0,
                "EMI" => 0,
                "FOIR" => $foir_meta,
            ];
        }

        // 3. CAMS checks: purchase deviation & final value
        $purchaseCheck = $this->servicePurchaseDeviationCheck();
        if (!$purchaseCheck['status']) {
            log_message('error', 'calculateLoanEligibility: rejected by purchase deviation check');
            return [
                "Eligibility" => "Not Eligible",
                "Reason" => $purchaseCheck['reason'],
                "LoanAmount" => 0,
                "ROI" => 0,
                "FixedROI" => 0,
                "Tenure" => 0,
                "Score" => 0,
                "EMI" => 0,
                "FOIR" => $foir_meta,
            ];
        }
        $finalValue = $purchaseCheck['meta']['finalValue'] ?? ($this->totalDebit - ($this->totalOutwardUPI + $this->totalEMI + $this->investment));

        // 4. CIBIL check
        $cibilCheck = $this->serviceCibil();

        if (!$cibilCheck['status']) {
            log_message('error', 'calculateLoanEligibility: rejected by CIBIL check - ' . $cibilCheck['reason']);
            return [
                "Eligibility" => "Not Eligible",
                "Reason" => $cibilCheck['reason'],
                "LoanAmount" => 0,
                "ROI" => 0,
                "FixedROI" => 0,
                "Tenure" => 0,
                "Score" => $this->cibil_score,
                "EMI" => 0,
                "FOIR" => $foir_meta,
                "NegativeAccounts" => $cibilCheck['meta']['negativeAccounts'] ?? []
            ];
        }
        // 5. Aggregate scoring microservices
        $score = 0;
        $roi = 28; // base ROI
        $reasonNotes = '';

        // CIBIL adjustments from cibilCheck meta (if any)
        $cibilDelta = $cibilCheck['meta']['scoreDelta'] ?? 0;
        if ($cibilDelta) {
            $score += $cibilDelta;
            $roi -= $cibilDelta; // approximate inverse relation like earlier logic (keeps previous behavior)
            log_message('info', "calculateLoanEligibility: applied cibilDelta {$cibilDelta}");
        } elseif (!isset($cibilCheck['meta']['scoreDelta'])) {
            // no meta delta - if service returned notes (no cibil) apply as before
            if (isset($cibilCheck['meta']['note']) && $cibilCheck['meta']['note'] === 'No CIBIL score') {
                $score -= 1;
                $roi += 2;
                $reasonNotes .= 'No CIBIL score. ';
            }
        }
        // 🔹 NEW: Special case if CIBIL = -1 → Fixed loan scheme
        if (($cibilCheck['meta']['scoreDelta'] ?? null) === -1) {
            log_message('info', 'calculateLoanEligibility: special case - CIBIL -1, fixed loan scheme applied');

            $loanAmount = 50000;
            $roi = 24;
            $tenure = 6;

            // Calculate EMI for fixed scheme
            $emi = $this->serviceCalculateEMI($loanAmount, $roi, $tenure);

            return [
                "Eligibility" => "Eligible",
                "Reason" => "Special scheme applied for CIBIL -1",
                "LoanAmount" => $loanAmount,
                "ROI" => $roi,
                "FixedROI" => $roi,
                "Tenure" => $tenure,
                "Score" => $this->cibil_score,
                "EMI" => round($emi, 2),
                "FOIR" => $foir_meta,
                "NegativeAccounts" => $cibilCheck['meta']['negativeAccounts'] ?? []
            ];
        }
        // Business age
        $bAge = $this->serviceBusinessAgeScore();
        $score += $bAge['score'];
        $roi += $bAge['roiDelta'];
        if (!empty($bAge['notes'])) $reasonNotes .= $bAge['notes'] . ' ';

        // Daily sales
        $dailySalesScore = $this->serviceDailySalesScore();
        if (isset($dailySalesScore['status']) && $dailySalesScore['status'] === false) {
            log_message('error', 'calculateLoanEligibility: rejected due to zero sales');
            return [
                "Eligibility" => "Not Eligible",
                "Reason" => $dailySalesScore['reason'],
                "LoanAmount" => 0,
                "ROI" => 0,
                "FixedROI" => 0,
                "Tenure" => 0,
                "Score" => round($score, 2),
                "EMI" => 0,
                "FOIR" => $foir_meta,
            ];
        } else {
            $score += $dailySalesScore['score'];
            $roi += $dailySalesScore['roiDelta'];
            if (!empty($dailySalesScore['notes'])) $reasonNotes .= $dailySalesScore['notes'] . ' ';
        }

        // Stock
        $stockScore = $this->serviceStockScore();
        $score += $stockScore['score'];
        $roi += $stockScore['roiDelta'];
        if (!empty($stockScore['notes'])) $reasonNotes .= $stockScore['notes'] . ' ';

        // 4. Business Growth Check (NEW)
        $growthCheck = $this->serviceBusinessGrowthCheck();
        $score += $growthCheck['score'];
        $roi += $growthCheck['roiDelta'];
        $reasonNotes .= $growthCheck['notes'] . ' ';

        // Location
        $locScore = $this->serviceLocationScore();
        $score += $locScore['score'];
        $roi += $locScore['roiDelta'];

        // Business Type
        $btScore = $this->serviceBusinessTypeScore();
        $score += $btScore['score'];
        $roi += $btScore['roiDelta'];
        if (!empty($btScore['notes'])) $reasonNotes .= $btScore['notes'] . ' ';

        log_message('info', "calculateLoanEligibility: aggregated score={$score}, roi(before fixed)={$roi}, reasonNotes={$reasonNotes}");

        // Loan amount calculation (same formula you had)
        $base_loan = $score * 500;
        $stock_contribution = $this->stock * 0.5;
        $daily_sales_contribution = $this->daily_sales * 10;
        $additional_loan = min($stock_contribution, $daily_sales_contribution);
        $eligibility_amount = $base_loan + $additional_loan;
        $eligibility_amount = max(50000, min($eligibility_amount, 110000)); // cap at 1.1L before policy check
        log_message('info', "eligibility_amount: As Per RuleEngine {$eligibility_amount}");
        if ($eligibility_amount > 110000) {
            log_message('info', "loanIsHigher: As Per internal policy, loan requests above ₹1.1 Lakhs require manual review.{$eligibility_amount}");
            // On Hold case
            return [
                "Eligibility" => "On Hold",
                "Reason" => "As Per internal policy, loan requests above ₹1.1 Lakhs require manual review.",
                "LoanAmount" => 0,
                "ROI" => $roi,
                "FixedROI" => null,
                "Tenure" => null,
                "Score" => round($score, 2),
                "EMI" => 0,
                "FOIR" => $foir_meta,
            ];
        }

        // Cap amount between 50k and 2.5L
        $eligibility_amount = max(50000, min($eligibility_amount, 110000));

        // if ($eligibility_amount >= 250000) {
        //     $fixed_roi = 18.8;
        //     $tenure = 21;
        // } elseif ($eligibility_amount >= 220000) {
        //     $fixed_roi = 18.8;
        //     $tenure = 21;
        // } elseif ($eligibility_amount >= 190000) {
        //     $fixed_roi = 19.6;
        //     $tenure = 21;
        // } elseif ($eligibility_amount >= 160000) {
        //     $fixed_roi = 20.8;
        //     $tenure = 18;
        // } elseif ($eligibility_amount >= 130000) {
        //     $fixed_roi = 22.0;
        //     $tenure = 15;
        // } 
        if ($eligibility_amount >= 110000) {
            $fixed_roi = 22.0;
            $tenure = 12;
        } elseif ($eligibility_amount >= 100000) {
            $fixed_roi = 23.0;
            $tenure = 12;
        } elseif ($eligibility_amount >= 75000) {
            $fixed_roi = 23.5;
            $tenure = 9;
        } elseif ($eligibility_amount >= 50000) {
            $fixed_roi = 24.0;
            $tenure = 6;
        } else {
            // below 50k not allowed
            $fixed_roi = null;
            $tenure = null;
        }

        // final ROI (ensure within limits)
        $final_roi = min(max($roi, $fixed_roi), 26);

        // score threshold
        if ($score < 6) {
            log_message('error', 'calculateLoanEligibility: failed - low score ' . $score);
            return [
                "Eligibility" => "Not Eligible",
                "Reason" => "Low score",
                "LoanAmount" => 0,
                "ROI" => $roi,
                "FixedROI" => $fixed_roi,
                "Tenure" => 0,
                "Score" => round($score, 2),
                "EMI" => 0,
                "FOIR" => $foir_meta,
            ];
        }

        // affordability minimum
        $min_loan = 50000;
        $max_roi = 26;
        $min_tenure = 6;
        $min_required_emi = $this->serviceCalculateEMI($min_loan, $max_roi, $min_tenure);
        $loan_amount = $eligibility_amount;
        $calculated_emi = $this->serviceCalculateEMI($eligibility_amount, $final_roi, $tenure);

        log_message('info', "calculateLoanEligibility: min_required_emi=" . $min_required_emi . ", eligible_emi=" . (isset($foir_meta['EligibleEMI']) ? $foir_meta['EligibleEMI'] : 0));
        // // ----- NEW: Surplus & Balance Adjustment -----
        // $monthlySurplus = $this->scorecard_summary['Monthly Average Surplus'] ?? 0;
        // $monthlyBalance = $this->scorecard_summary['Monthly Average Balance'] ?? 0;
        // $affordabilityCap = max($monthlySurplus, $monthlyBalance);

        // if ($calculated_emi > $affordabilityCap) {
        //     log_message('warning', "calculateLoanEligibility: EMI {$calculated_emi} > AffordabilityCap {$affordabilityCap}. Adjusting loan amount...");
        //     $adjustedEMI = $affordabilityCap;
        //     $loan_amount = $this->calculateLoanAmountFromEMI($adjustedEMI, $final_roi, $tenure);
        //     $calculated_emi = $this->serviceCalculateEMI($loan_amount, $final_roi, $tenure);
        //     log_message('info', "calculateLoanEligibility: Adjusted LoanAmount={$loan_amount}, EMI={$calculated_emi}");
        // }

        // // ----- Final Output -----
        // return [
        //     "Eligibility" => "Eligible",
        //     "LoanAmount" => round($loan_amount),
        //     "calculatedLoanAmount" => round($eligibility_amount),
        //     "ROI" => round($roi, 2),
        //     "FixedROI" => round($final_roi, 2),
        //     "Tenure" => $tenure,
        //     "Score" => round($score, 2),
        //     "EMI" => round($calculated_emi),
        //     "Reason" => trim($reasonNotes),
        //     "FOIR" => $foir_meta,
        //     "Surplus" => $monthlySurplus,
        //     "AverageBalance" => $monthlyBalance,
        // ];
        if (($foir_meta['EligibleEMI'] ?? 0) < $min_required_emi) {
            log_message('error', 'calculateLoanEligibility: failed - cannot afford minimum plan');
            return [
                "Eligibility" => "Not Eligible",
                "LoanAmount" => 0,
                "ROI" => 0,
                "FixedROI" => 0,
                "Tenure" => 0,
                "Score" => round($score, 2),
                "EMI" => 0,
                "Reason" => "Rejected — cannot afford minimum ₹50K loan.",
                "FOIR" => $foir_meta,
            ];
        }
        // Always calculate loan amount based on EligibleEMI (if provided)
        $eligibleEmi = $foir_meta['EligibleEMI'] ?? 0;

        if ($eligibleEmi > 0) {
            // Calculate loan amount from EligibleEMI
            $loan_amount = $this->calculateLoanAmountFromEMI($eligibleEmi, $final_roi, $tenure);
            // ✅ Cap loan amount between 1,00,000
            if ($loan_amount > 100000) {
                log_message('info', "loanCapApplied: LoanAmount {$loan_amount} exceeded policy limit. Capped at ₹1,00,000.");
                $loan_amount = 100000;
            }

            // Recalculate EMI for consistency
            $calculated_emi = $this->serviceCalculateEMI($loan_amount, $final_roi, $tenure);

            log_message('info', "calculateLoanEligibility: LoanAmount recalculated from EligibleEMI={$eligibleEmi} → LoanAmount={$loan_amount}, EMI={$calculated_emi}");
        }
        // Eligible - final result
        log_message('info', 'calculateLoanEligibility: Eligible. Preparing final result.');

        return [
            "Eligibility" => "Eligible",
            "LoanAmount" => round($loan_amount),
            "calculatedLoanAmount" => round($eligibility_amount),
            "ROI" => round($roi, 2),
            "FixedROI" => round($final_roi, 2),
            "Tenure" => $tenure,
            "Score" => round($score, 2),
            "EMI" => round($calculated_emi),
            "Reason" => trim($reasonNotes),
            "FOIR" => $foir_meta,
        ];
    }

    // Simple Flat Loan Amount calculation from EMI
    private function calculateLoanAmountFromEMI($emi, $roi = null, $tenure = null)
    {
        // use the same ROI & Tenure as in eligibility calculation
        $roi    = $roi ?? $this->fixed_roi ?? 24; // fallback 24% yearly
        $tenure = $tenure ?? $this->fixed_tenure ?? 12; // fallback 12 months

        // Flat method: Loan Amount = (EMI × Tenure) / (1 + (ROI × Tenure / 1200))
        // ROI in % yearly → convert to per year fraction
        $loanAmount = ($emi * $tenure) / (1 + (($roi * $tenure) / 1200));

        return round($loanAmount, 2);
    }
    /* --------------------------
     * Existing helper kept (FOIR calc)
     * -------------------------- */

    public function calculateFOIREligibleEMI($daily_sales, $business_type, $existing_emi = 0)
    {

        // Ensure CIBIL is parsed before calculation
        if (!isset($this->totalEMIAmountFromCibil) || $this->totalEMIAmountFromCibil === 0) {
            $this->serviceCibil();
        }
        log_message('info', "calculateFOIREligibleEMI: totalEMIFromCibil={$this->totalEMIAmountFromCibil}, totalEMIFromBank={$this->totalEMI}");
        $margin_table = [
            'Grocery' => [[0, 10000, 0.12], [10000, 25000, 0.12], [25000, 35000, 0.10], [35000, 50000, 0.08], [50000, 75000, 0.07], [75000, INF, 0.07]],
            'Stationary' => [[0, 10000, 0.25], [10000, 25000, 0.24], [25000, 35000, 0.23], [35000, 50000, 0.22], [50000, 75000, 0.21], [75000, INF, 0.20]],
            'Pharmacy' => [[0, 10000, 0.16], [10000, 25000, 0.17], [25000, 35000, 0.18], [35000, 50000, 0.19], [50000, 75000, 0.20], [75000, INF, 0.20]],
            'Electrical' => [[0, 10000, 0.20], [10000, 25000, 0.19], [25000, 35000, 0.18], [35000, 50000, 0.17], [50000, 75000, 0.16], [75000, INF, 0.15]],
            'Elctronic' => [[0, 10000, 0.20], [10000, 25000, 0.20], [25000, 35000, 0.19], [35000, 50000, 0.19], [50000, 75000, 0.18], [75000, INF, 0.15]],
            'Pet Shop' => [[0, 10000, 0.16], [10000, 25000, 0.17], [25000, 35000, 0.18], [35000, 50000, 0.19], [50000, 75000, 0.20], [75000, INF, 0.20]],
            'Sweet Shop' => [[0, 10000, 0.20], [10000, 25000, 0.19], [25000, 35000, 0.18], [35000, 50000, 0.17], [50000, 75000, 0.16], [75000, INF, 0.15]],
            'Food & Beverage' => [[0, 10000, 0.20], [10000, 25000, 0.19], [25000, 35000, 0.18], [35000, 50000, 0.17], [50000, 75000, 0.16], [75000, INF, 0.15]],
            'Hardware' =>  [[0, 10000, 0.19], [10000, 25000, 0.18], [25000, 35000, 0.17], [35000, 50000, 0.16], [50000, 75000, 0.15], [75000, INF, 0.15]],
            'Furniture' => [[0, 10000, 0.19], [10000, 25000, 0.18], [25000, 35000, 0.17], [35000, 50000, 0.16], [50000, 75000, 0.15], [75000, INF, 0.15]]
        ];

        $margin = 0.12; // default
        if (isset($margin_table[$business_type])) {
            foreach ($margin_table[$business_type] as [$min, $max, $m]) {
                if ($daily_sales >= $min && $daily_sales < $max) {
                    $margin = $m;
                    break;
                }
            }
        }

        $monthly_sales = $daily_sales * 30;
        $gross_income = $monthly_sales * $margin;
        $foir_limit = $gross_income * 0.6;
        log_message('error', 'Check EMI from CIBIL loaded ' . $this->totalEMIAmountFromCibil);
        // Pick higher EMI
        // $existing_emi = max($this->totalEMI, $this->previous_emi);
        $existing_emi = max($this->totalEMIAmountFromCibil, $this->previous_emi, $this->totalEMI);
        // $existing_emi = max($this->totalEMIAmountFromCibil, $this->previous_emi);
        log_message('info', 'checkCurrentEMI: from Bank = ' . $this->totalEMI . ' and from input field = ' . $this->previous_emi . ' and from Credit Report =' . $this->totalEMIAmountFromCibil . '. Take which ever is higher and that is = ' . $existing_emi);
        $net_affordable_emi = $foir_limit - $existing_emi;

        if ($gross_income >= 0 && $gross_income <= 33000) {
            $income_group = "LIG";
        } elseif ($gross_income > 33000 && $gross_income <= 73000) {
            $income_group = "MIG";
        } else {
            $income_group = "HIG";
        }

        return [
            "Margin" => $margin,
            "GrossIncome" => $gross_income,
            "IncomeGroup" => $income_group,
            "FOIRLIMIT" => $foir_limit,
            "ExistingEMI" => $existing_emi,
            "EligibleEMI" => max(0, $net_affordable_emi),
        ];
    }
}

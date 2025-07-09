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
    protected $allowedFields    = ['member_id', 'first_date', 'second_date', 'eligibility', 'loan_amount', 'roi', 'tenure', 'score', 'cibil', 'cibilReport', 'created_at', 'updated_at'];

    protected $stock;
    protected $daily_sales;
    protected $cibil_score;
    protected $business_time;
    protected $location;
    protected $business_type;
    protected $previous_emi;

    public function setData(array $data)
    {
        $this->stock = $data['stock'] ?? 0;
        $this->daily_sales = $data['daily_sales'] ?? 0;
        $this->cibil_score = $data['cibil_score'] ?? 0;
        $this->business_time = $data['business_time'] ?? 0;
        $this->location = $data['location'] ?? 'rural';
        $this->business_type = $data['business_type'] ?? '';
        $this->previous_emi = $data['previous_emi'] ?? 0;
    }

    public function calculateFOIREligibleEMI($daily_sales, $business_type, $existing_emi = 0)
    {
        $margin_table = [
            'Grocery' => [[0, 10000, 0.12], [10000, 25000, 0.12], [25000, 35000, 0.10], [35000, 50000, 0.08], [50000, 75000, 0.07], [75000, INF, 0.07]],
            'Stationary' => [[0, 10000, 0.12], [10000, 25000, 0.12], [25000, 35000, 0.10], [35000, 50000, 0.08], [50000, 75000, 0.07], [75000, INF, 0.07]],
            'Pharmacy' => [[0, 10000, 0.16], [10000, 25000, 0.17], [25000, 35000, 0.18], [35000, 50000, 0.19], [50000, 75000, 0.20], [75000, INF, 0.20]],
            'Electrical' => [[0, 10000, 0.20], [10000, 25000, 0.19], [25000, 35000, 0.18], [35000, 50000, 0.17], [50000, 75000, 0.16], [75000, INF, 0.15]],
            'Elctronic' => [[0, 10000, 0.20], [10000, 25000, 0.20], [25000, 35000, 0.19], [35000, 50000, 0.19], [50000, 75000, 0.18], [75000, INF, 0.15]],
            'Pet Shop' => [[0, 10000, 0.16], [10000, 25000, 0.17], [25000, 35000, 0.18], [35000, 50000, 0.19], [50000, 75000, 0.20], [75000, INF, 0.20]],
            'Sweet Shop' => [[0, 10000, 0.20], [10000, 25000, 0.19], [25000, 35000, 0.18], [35000, 50000, 0.17], [50000, 75000, 0.16], [75000, INF, 0.15]],
            'Food & Beverage' => [[0, 10000, 0.20], [10000, 25000, 0.19], [25000, 35000, 0.18], [35000, 50000, 0.17], [50000, 75000, 0.16], [75000, INF, 0.15]],
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
            "EligibleEMI" => max(0, $net_affordable_emi), // can't be negative

        ];
    }

    public function calculateEMI($principal, $annual_rate, $tenure_months)
    {
        if ($tenure_months == 0) return 0;

        // Flat interest calculation
        $interest = ($principal * $annual_rate * ($tenure_months / 12)) / 100;
        $total_payable = $principal + $interest;

        // EMI = Total Payable / Tenure
        return $total_payable / $tenure_months;
    }

    public function calculateLoanEligibility()
    {
        $foir = $this->calculateFOIREligibleEMI($this->daily_sales, $this->business_type, $this->previous_emi);
        $eligible_emi = $foir['EligibleEMI'];

        if ($eligible_emi <= 0) {
            return [
                "Eligibility" => "Not Eligible",
                "Reason" => "Customer has no EMI capacity after existing obligations.",
                "LoanAmount" => 0,
                "ROI" => 0,
                "FixedROI" => 0,
                "Tenure" => 0,
                "Score" => 0,
                "EMI" => 0,
                "FOIR" => $foir,
            ];
        }

        $score = 0;
        $roi = 28;
        $reason = '';
        $tenure = 24;

        // CIBIL
        if ($this->cibil_score >= 800) {
            $score += 3;
            $roi -= 3;
        } elseif ($this->cibil_score >= 750) {
            $score += 2;
            $roi -= 2;
        } elseif ($this->cibil_score >= 700) {
            $score += 1.5;
            $roi -= 1.5;
        } elseif ($this->cibil_score >= 670) {
            $score += 1;
            $roi += 1;
        } elseif ($this->cibil_score > 0) {
            return ["Eligibility" => "Not Eligible", "Reason" => "Low CIBIL score", "LoanAmount" => 0, "ROI" => 0, "FixedROI" => 0, "Tenure" => 0, "Score" => $score, "EMI" => 0, "FOIR" => $foir];
        } else {
            $score -= 1;
            $roi += 2;
            $reason .= "No CIBIL score. ";
        }

        // Business Age
        if ($this->business_time >= 5) {
            $score += 2;
            $roi -= 1.5;
        } elseif ($this->business_time >= 2) {
            $score += 1;
            $roi -= 0.5;
        } else {
            $roi += 2;
            $reason .= "Low business age. ";
        }

        // Daily Sales
        if ($this->daily_sales == 0) return ["Eligibility" => "Not Eligible", "Reason" => "Zero sales", "LoanAmount" => 0, "ROI" => 0, "FixedROI" => 0, "Tenure" => 0, "Score" => $score, "EMI" => 0, "FOIR" => $foir];
        elseif ($this->daily_sales >= 10000) {
            $score += 3;
            $roi -= 1.5;
        } elseif ($this->daily_sales >= 5000) {
            $score += 2;
            $roi -= 0.5;
        } elseif ($this->daily_sales >= 2000) {
            $score += 1.5;
        } else {
            $roi += 2;
            $reason .= "Low sales. ";
        }

        // Stock
        if ($this->stock >= 1000000) {
            $score += 4;
            $roi -= 2;
        } elseif ($this->stock >= 500000) {
            $score += 3;
            $roi -= 1.5;
        } elseif ($this->stock >= 100000) {
            $score += 2;
            $roi += 1;
        } elseif ($this->stock >= 50000) {
            $roi += 2;
            $score += 1;
        } else {
            $roi += 2.5;
            $reason .= "Low stock. ";
        }

        // Location
        $location_scores = ['metro' => 2, 'urban' => 1.5, 'semi-urban' => 1, 'suburban' => 0.5, 'rural' => 0];
        $score += $location_scores[$this->location] ?? 0;
        $roi -= $location_scores[$this->location] ?? 0;

        // Business Type
        $essential = ['Grocery', 'Pharmacy', 'Stationery'];
        $service = ['Pet Shop', 'Food stall', 'Sweet Shop'];
        $retail = ['Furniture', 'Electrical', 'Electronics and Appliances'];

        if (in_array($this->business_type, $essential)) {
            $score += 2;
            $roi -= 1;
        } elseif (in_array($this->business_type, $service)) {
            $score += 1;
            $roi -= 0.5;
        } elseif (in_array($this->business_type, $retail)) {
            $score += 0.5;
            $roi += 1;
        }

        // // Final adjustments
        // $fixed_roi = min(28, max(26, $roi));
        // New Loan Calculation Formula
        $base_loan = $score * 500;
        $stock_contribution = $this->stock * 0.5;
        $daily_sales_contribution = $this->daily_sales * 10;

        // Select the lower value between 50% of stock and 15 times daily sales
        $additional_loan = min($stock_contribution, $daily_sales_contribution);

        // Final loan amount calculation
        $eligibility_amount = $base_loan + $additional_loan;

        // Ensure the loan amount is within limits
        $eligibility_amount = max(50000, min($eligibility_amount, 250000));

        // Fixed ROI and Tenure based on Loan Eligibility Amount
        if ($eligibility_amount >= 200000) {
            $fixed_roi = 26.0;
            $tenure = 24;
        } elseif ($eligibility_amount >= 190000) {
            $fixed_roi = 26.2;
            $tenure = 24;
        } elseif ($eligibility_amount >= 180000) {
            $fixed_roi = 26.4;
            $tenure = 24;
        } elseif ($eligibility_amount >= 170000) {
            $fixed_roi = 26.6;
            $tenure = 24;
        } elseif ($eligibility_amount >= 160000) {
            $fixed_roi = 26.8;
            $tenure = 24;
        } elseif ($eligibility_amount >= 150000) {
            $fixed_roi = 27.0;
            $tenure = 24;
        } elseif ($eligibility_amount >= 140000) {
            $fixed_roi = 27.2;
            $tenure = 21;
        } elseif ($eligibility_amount >= 130000) {
            $fixed_roi = 27.4;
            $tenure = 21;
        } elseif ($eligibility_amount >= 120000) {
            $fixed_roi = 27.6;
            $tenure = 21;
        } elseif ($eligibility_amount >= 110000) {
            $fixed_roi = 27.8;
            $tenure = 21;
        } elseif ($eligibility_amount >= 100000) {
            $fixed_roi = 28.0;
            $tenure = 21;
        } elseif ($eligibility_amount >= 90000) {
            $fixed_roi = 28.2;
            $tenure = 18;
        } elseif ($eligibility_amount >= 80000) {
            $fixed_roi = 28.4;
            $tenure = 18;
        } elseif ($eligibility_amount >= 70000) {
            $fixed_roi = 28.6;
            $tenure = 18;
        } elseif ($eligibility_amount >= 60000) {
            $fixed_roi = 28.8;
            $tenure = 12;
        } elseif ($eligibility_amount >= 50000) {
            $fixed_roi = 29.0;
            $tenure = 12;
        } else {
            // Should not be eligible — fallback
            $fixed_roi = 30.0;
            $tenure = 12;
        }

        // Ensure ROI is the higher of the calculated ROI or the fixed ROI
        $final_roi = min(max($roi, $fixed_roi), 30);

        if ($score < 7) {
            return ["Eligibility" => "Not Eligible", "Reason" => "Low score", "LoanAmount" => 0, "ROI" => $roi, "FixedROI" => $fixed_roi, "Tenure" => 0, "Score" => $score, "EMI" => 0, "FOIR" => $foir];
        }


        $min_loan = 50000;
        $max_roi = 30;
        $min_tenure = 9;

        // Calculate EMI for ₹50K at 30% ROI for 9 months
        $min_required_emi = $this->calculateEMI($min_loan, $max_roi, $min_tenure);
        $loan_amount = $eligibility_amount;
        $calculated_emi = $this->calculateEMI($eligibility_amount, $final_roi, $tenure);
        // Reject if user cannot afford even the minimum plan
        if ($eligible_emi < $min_required_emi) {
            return [
                "Eligibility" => "Not Eligible",
                "LoanAmount" => 0,
                "ROI" => 0,
                "FixedROI" => 0,
                "Tenure" => 0,
                "Score" => round($score, 2),
                "EMI" => 0,
                "Reason" => "Rejected — cannot afford minimum ₹50K loan at 30% ROI for 9 months.",
                "FOIR" => $foir,
            ];
        } else {
            return [
                "Eligibility" => "Eligible",
                "LoanAmount" => round($loan_amount),
                "calculatedLoanAmount" => round($eligibility_amount),
                "ROI" => round($roi, 2),
                "FixedROI" => round($final_roi, 2),
                "Tenure" => $tenure,
                "Score" => round($score, 2),
                "EMI" => round($calculated_emi),
                "Reason" => trim($reason),
                "FOIR" => $foir,
            ];
        }
    }
}

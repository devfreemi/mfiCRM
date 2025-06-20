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
    protected $allowedFields    = ['member_id', 'first_date', 'second_date', 'eligibility', 'loan_amount', 'roi', 'tenure', 'score', 'cibil', 'created_at', 'updated_at'];

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
            'Elctronic And Electrical Shop' => [[0, 10000, 0.20], [10000, 25000, 0.20], [25000, 35000, 0.19], [35000, 50000, 0.19], [50000, 75000, 0.18], [75000, INF, 0.15]],
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
        $foir_limit = $gross_income * 0.5;
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
            "EligibleEMI" => max(0, $net_affordable_emi), // can't be negative
            "ExistingEMI" => $existing_emi,
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
        } elseif ($this->cibil_score >= 675) {
            $score += 1;
            $roi -= 1;
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
            $roi -= 2;
        } elseif ($this->business_time >= 2) {
            $score += 1;
            $roi -= 1;
        } else {
            $roi += 2;
            $reason .= "Low business age. ";
        }

        // Daily Sales
        if ($this->daily_sales == 0) return ["Eligibility" => "Not Eligible", "Reason" => "Zero sales", "LoanAmount" => 0, "ROI" => 0, "FixedROI" => 0, "Tenure" => 0, "Score" => $score, "EMI" => 0, "FOIR" => $foir];
        elseif ($this->daily_sales >= 10000) {
            $score += 3;
            $roi -= 2;
        } elseif ($this->daily_sales >= 5000) {
            $score += 2;
            $roi -= 1;
        } elseif ($this->daily_sales >= 2000) {
            $score += 1;
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
        } elseif ($this->stock >= 50000) {
            $score += 1;
        } else {
            $roi += 2;
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
            $roi += 0.5;
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
        if ($eligibility_amount > 200000) {
            $fixed_roi = 26;
            $tenure = 36;
        } elseif ($eligibility_amount > 50000) {
            $fixed_roi = 27;
            $tenure = 24;
        } else {
            $fixed_roi = 28;
            $tenure = 12;
        }

        // Ensure ROI is the higher of the calculated ROI or the fixed ROI
        $final_roi = min(max($roi, $fixed_roi), 28);

        if ($score < 7) {
            return ["Eligibility" => "Not Eligible", "Reason" => "Low score", "LoanAmount" => 0, "ROI" => $roi, "FixedROI" => $fixed_roi, "Tenure" => 0, "Score" => $score, "EMI" => 0, "FOIR" => $foir];
        }

        // $min_loan = 50000;
        // $max_roi = 30; // Maximum ROI for ₹50K plan
        // $max_tenure = 36;
        // // Calculate EMI for ₹50,000 at 30% ROI for 36 months
        // $min_emi = $this->calculateEMI($min_loan, $max_roi, $max_tenure);
        // $loan_amount = $eligibility_amount;
        // $calculated_emi = $this->calculateEMI($eligibility_amount, $final_roi, $tenure);

        // if ($calculated_emi > $eligible_emi) {
        //     // Convert to monthly flat rate
        //     $monthly_rate = $final_roi / (12 * 100);

        //     // Flat interest: Loan = (EMI × Tenure) / (1 + (Rate × Tenure in years))
        //     $total_interest_factor = ($final_roi * $tenure / 12) / 100;
        //     $adjusted_loan = ($eligible_emi * $tenure) / (1 + $total_interest_factor);

        //     // Update loan and EMI to FOIR-adjusted values
        //     $loan_amount = $adjusted_loan;
        //     $calculated_emi = $eligible_emi;
        //     $reason .= "Adjusted to match FOIR. ";
        // }
        // // If customer can afford that EMI, give ₹50k with higher ROI and tenure
        // if ($loan_amount < $min_loan) {
        //     return [
        //         "Eligibility" => "Eligible - High Risk",
        //         "LoanAmount" => $min_loan,
        //         "ROI" => $max_roi,
        //         "FixedROI" => $max_roi,
        //         "Tenure" => $max_tenure,
        //         "Score" => round($score, 2),
        //         "EMI" => round($min_emi, 2),
        //         "Reason" => "Upgraded to minimum ₹50K plan with higher ROI and longer tenure.",
        //         "FOIR" => $foir,
        //     ];
        // } 
        $min_loan = 10000; // Revised minimum loan for high-risk
        $high_risk_roi = 30; // High ROI for high-risk customers
        $min_tenure = 8; // Minimum 8 months

        $loan_amount = $eligibility_amount;
        $calculated_emi = $this->calculateEMI($loan_amount, $final_roi, $tenure);

        if ($calculated_emi > $eligible_emi) {
            // Mark as high-risk and downgrade to smaller loan, shorter tenure
            $calculated_emi = $this->calculateEMI($min_loan, $high_risk_roi, $min_tenure);

            return [
                "Eligibility" => "Eligible",
                "LoanAmount" => $min_loan,
                "ROI" => $high_risk_roi,
                "FixedROI" => $high_risk_roi,
                "Tenure" => $min_tenure,
                "Score" => round($score, 2),
                "EMI" => round($calculated_emi),
                "Reason" => "High FOIR risk. Downgraded to minimum ₹10K loan for 8 months at higher ROI.",
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

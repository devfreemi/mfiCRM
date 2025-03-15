<?php

namespace App\Models;

use CodeIgniter\Model;

class LoanEligibilityModel extends Model
{
    protected $stock;
    protected $daily_sales;
    protected $cibil_score;
    protected $business_time;
    protected $location;
    protected $business_type;
    protected $previous_emi;
    // protected $memberID;

    public function setData($data)
    {
        $this->stock = $data['stock'] ?? 0;
        $this->daily_sales = $data['daily_sales'] ?? 0;
        $this->cibil_score = $data['cibil_score'] ?? 0;
        $this->business_time = $data['business_time'] ?? 0;
        $this->location = $data['location'] ?? 'rural';
        $this->business_type = $data['business_type'] ?? '';
        $this->previous_emi = $data['previous_emi'] ?? 0;
        // $this->memberID = $data['memberID'] ?? '';
    }

    public function calculateLoanEligibility()
    {
        $score = 0;
        $roi = 28; // Starting max interest rate
        $eligibility_amount = 0;
        $reason = '';
        $fixed_roi = 0;
        $tenure = 0;

        // CIBIL Score Evaluation
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
        } elseif ($this->cibil_score <= 675 && $this->cibil_score > 0) {
            $score -= 4;
            $roi += 4;
            $reason .= "Low CIBIL score. ";
        } else {
            $score -= 1;
            $roi += 2;
            $reason .= "No CIBIL score. ";
        }

        // Business Age Evaluation
        if ($this->business_time >= 5) {
            $score += 2;
            $roi -= 2;
        } elseif ($this->business_time >= 2) {
            $score += 1;
            $roi -= 1;
        } else {
            $roi += 2;
            $reason .= "Business age is too low. ";
        }

        // Daily Sales Evaluation
        if ($this->daily_sales >= 10000) {
            $score += 3;
            $roi -= 2;
        } elseif ($this->daily_sales >= 5000) {
            $score += 2;
            $roi -= 1;
        } elseif ($this->daily_sales >= 2000) {
            $score += 1;
        } else {
            $roi += 2;
            $reason .= "Low daily sales. ";
        }

        // Stock Evaluation
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
            $reason .= "Low stock value. ";
        }

        // Location-based adjustment
        $location_factors = [
            "metro" => 2,
            "urban" => 1.5,
            "semi-urban" => 1,
            "suburban" => 0.5,
            "rural" => 0
        ];

        $score += $location_factors[$this->location] ?? 0;
        $roi -= ($location_factors[$this->location] ?? 0);

        // Previous EMI consideration
        if ($this->previous_emi == 0) {
            $score += 2;
            $roi -= 1;
        } elseif ($this->previous_emi <= 2) {
            $score += 1;
        } else {
            $roi += 2;
            $reason .= "High previous EMI burden. ";
        }

        // Business Type Rules
        $business_categories = [
            "essential" => ["Grocery", "Pharmacy"],
            "service" => ["Pet Shop", "Food stall", "Stationary"],
            "retail" => ["Furniture", "Electrical", "Elctronic And Appliance Shop"]
        ];

        foreach ($business_categories as $category => $types) {
            if (in_array($this->business_type, $types)) {
                if ($category == "essential") {
                    if ($this->business_time < 5 || $this->stock < 500000) {
                        // return [
                        //     "Eligibility" => "Not Eligible",
                        //     "ROI" => "N/A",
                        //     "Loan Amount" => 0,
                        //     "Score" => $score,
                        //     "Reason" => "Business does not meet the minimum criteria (5+ years and ₹5L+ stock)."
                        // ];
                        $score -= 3;
                        $roi += 3;
                    }
                    $score += 2;
                    $roi -= 1;
                } elseif ($category == "service") {
                    $score += 1;
                    $roi -= 0.5;
                } elseif ($category == "retail") {
                    $score += 0.5;
                    $roi += 0.5;
                }
            }
        }

        // New Loan Calculation Formula
        $base_loan = $score * 1000;
        $stock_contribution = $this->stock * 0.5;
        $daily_sales_contribution = $this->daily_sales * 15;

        // Select the lower value between 50% of stock and 15 times daily sales
        $additional_loan = min($stock_contribution, $daily_sales_contribution);

        // Final loan amount calculation
        $eligibility_amount = $base_loan + $additional_loan;

        // Ensure the loan amount is within limits
        $eligibility_amount = max(50000, min($eligibility_amount, 250000));

        // Fixed ROI and Tenure based on Loan Eligibility Amount
        if ($eligibility_amount > 200000) {
            $fixed_roi = 24;
            $tenure = 36;
        } elseif ($eligibility_amount > 50000) {
            $fixed_roi = 26;
            $tenure = 24;
        } else {
            $fixed_roi = 28;
            $tenure = 12;
        }

        // Ensure ROI is the higher of the calculated ROI or the fixed ROI
        $final_roi = min(max($roi, $fixed_roi), 28);

        // Determine final eligibility
        if ($score >= 5) {
            return [
                "Eligibility" => "Eligible",
                "ROI" => $final_roi,  // Final ROI applied
                "Fixed ROI" => $fixed_roi,
                "Tenure" => $tenure . " months",
                "Loan Amount" => $eligibility_amount,
                "Score" => $score,
                "Reason" => "Eligible for the loan.",
                "roi" => $roi
            ];
        } else {
            return [
                "roi" => $roi,
                "Eligibility" => "Not Eligible",
                "ROI" => "N/A",
                "Fixed ROI" =>  "N/A",
                "Tenure" =>  "N/A",
                "Loan Amount" => 0,
                "Score" => $score,
                "Reason" => trim($reason)
            ];
        }
    }
}

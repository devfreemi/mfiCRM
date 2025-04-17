<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\LoanEligibilityModel;
use CodeIgniter\API\ResponseTrait;

class LoanEligibilityController extends BaseController
{
    use ResponseTrait;
    public function checkEligibility()
    {
        $request = service('request');
        // $cibil = rand(0, 900);
        $cibil = $request->getVar('cibil');
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
            'roi' => $result['ROI'],
            'tenure' => $result['Tenure'],
            'score' => $result['Score'],
            'eligibility' => $result['Eligibility'],
            'reason' => $result['Reason'],
        ];
        $db = db_connect();
        $builder = $db->table('initial_eli_run');
        // $builder->where('member_id', $request->getVar('memberID'));
        $builder->upsert($data_eli_run);


        return view('eli-page', $data);
        // print_r($query);
        // echo ("<br>");
        // print_r($data);
    }


    // API
    public function checkEligibilityAPI()
    {
        // $request = service('request');
        // $cibil = rand(0, 900);
        $cibil = 680;
        // Get input data from the form
        if ($this->request->getVar('previous_emi') === "") {
            # code...
            $previous_emi = 0;
        } else {
            # code...
            $previous_emi =  $this->request->getVar('previous_emi');
        }
        if ($this->request->getVar('business_time') === "") {
            # code...
            $business_time = 0;
        } else {
            # code...
            $current_year = date('Y');
            $business_time = round($current_year -  $this->request->getVar('business_time'));
        }

        $data = [
            'stock' =>  $this->request->getVar('stock'),
            'daily_sales' =>  $this->request->getVar('daily_sales'),
            'cibil_score' => $cibil,
            'business_time' => $business_time,
            'location' =>  $this->request->getVar('location'),
            'business_type' =>  $this->request->getVar('business_type'),
            'previous_emi' => $previous_emi,
            'memberId' => $this->request->getVar('memberId_api'),
        ];

        // Load the LoanEligibilityModel and pass input data
        $loanModel = new LoanEligibilityModel();
        $loanModel->setData($data);

        $result = $loanModel->calculateLoanEligibility();
        // Merge input data with result for passing to view
        $data['result'] = $result;
        // member data update
        $data_eli_run = [
            'cibil' => $cibil,
            'member_id' => $this->request->getVar('memberId_api'),
            'first_date' => date('Y-m-d'),
            'loan_amount' => $result['LoanAmount'],
            'roi' => $result['ROI'],
            'tenure' => $result['Tenure'],
            'score' => $result['Score'],
            'eligibility' => $result['Eligibility'],
            'reason' => $result['Reason'],
        ];
        $db = db_connect();
        $builder = $db->table('initial_eli_run');
        $builder->upsert($data_eli_run);
        // return view('eli-page', $data);
        if (is_null($data)) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        }

        return $this->respond(['member' => $data], 200);
        // print_r($query);
        // echo ("<br>");
        // print_r($data);
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
            ->where('agent', $empID)->findAll();


        if (is_null($data)) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        } else {
            # code...
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
            return $this->respond($data, 200);
        }
    }
}

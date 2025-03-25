<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\LoanEligibilityModel;
use CodeIgniter\API\ResponseTrait;

class LoanEligibilityController extends BaseController
{
    public function checkEligibility()
    {
        $request = service('request');
        // $cibil = rand(0, 900);
        $cibil = 780;
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
        ];
        $db = db_connect();
        $builder = $db->table('members');
        $builder->where('member_id', $request->getVar('memberID'));
        $query = $builder->update($data_update);



        return view('eli-page', $data);
        // print_r($query);
        // echo ("<br>");
        // print_r($data);
    }
}

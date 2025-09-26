<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class InsuranceController extends BaseController
{
    public function index($member_id)
    {
        // Just pass member_id to the view
        return view('insurance', ['member_id' => $member_id]);
    }
    public function update_insurance()
    {
        $member_id = $this->request->getVar('member_id');
        $insurance_fee = $this->request->getVar('insurance_fee');

        $db = db_connect(); // connect to loan DB
        $builder = $db->table('loans'); // replace with your table name

        $builder->where('member_id', $member_id);
        $update = $builder->update(['insurance_fee' => $insurance_fee]);

        if ($update) {
            session()->setFlashdata('success', 'Insurance fee updated successfully!');
        } else {
            session()->setFlashdata('error', 'Failed to update insurance fee.');
        }

        // 👇 Instead of redirecting, directly return the same view
        return view('insurance');
    }
}

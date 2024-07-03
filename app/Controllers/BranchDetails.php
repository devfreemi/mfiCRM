<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\BranchModel;

class BranchDetails extends BaseController
{
    public function add_branch()
    {
        //
        $rules = [
            'b_name' => ['rules' => 'required|min_length[5]|max_length[64]|is_unique[branches.b_name]'],
            'location' => ['rules' => 'required|min_length[5]|max_length[64]'],
            'pincode' => ['rules' => 'required|min_length[6]|max_length[6]'],
        ];


        if ($this->validate($rules)) {
            $model = new BranchModel();

            $data = [
                'b_name'    => $this->request->getVar('b_name'),
                'location' => $this->request->getVar('location'),
                'pincode' => $this->request->getVar('pincode'),
            ];

            $query = $model->insert($data);
            if (!$query) {
                # code...
                $session = session();
                $session->setFlashdata('msg', 'Invalid Inputs');
                return redirect()->to(base_url() . 'branch');
            } else {
                # code...
                return redirect()->to(base_url() . 'branch');
            }
        } else {
            $session = session();
            $session->setFlashdata('msg', 'Internal Exception!');
            return redirect()->to(base_url() . 'branch');
        }
    }
}

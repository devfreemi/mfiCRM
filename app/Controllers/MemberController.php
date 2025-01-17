<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MemberModel;
use CodeIgniter\API\ResponseTrait;

class MemberController extends BaseController
{
    use ResponseTrait;
    public function add_member()
    {
        //
        $model = new MemberModel();

        $mobile = $this->request->getVar('mobile');
        $mobileFour = substr($mobile, -4);
        $name_str = strtoupper($this->request->getVar('businessName'));
        $name_str = substr($name_str, 0, 4);


        if ($this->request->getVar('pan') != '') {
            # code...
            $pan = $this->request->getVar('pan');
        } else {
            # code...
            $pan = $this->request->getVar('mobile') . "/NA";
        }
        if ($this->request->getVar('adhar') != '') {
            # code...
            $adhar = $this->request->getVar('adhar');
        } else {
            # code...
            $adhar = $this->request->getVar('mobile') . "/N";
        }


        $data = [
            'member_id'     =>  $name_str . $mobileFour,
            'groupName'     => $this->request->getVar('groupName'),
            'groupId'       => $this->request->getVar('groupId'),
            'mobile'        => $this->request->getVar('mobile'),
            'pan'           => $pan,
            'adhar'         => $adhar,
            'name'          => $this->request->getVar('name'),
            'location'      => $this->request->getVar('memberLocation'),
            'pincode'       => $this->request->getVar('groupPin'),
            'gender'        => $this->request->getVar('gender'),
            'marital'       => $this->request->getVar('marital'),
            'occupation'    => $this->request->getVar('occupation'),
            'businessType'  => $this->request->getVar('businessType'),
            'businessName'  => $this->request->getVar('businessName'),
            'footFall'      => $this->request->getVar('footFall'),
            'stock'         => $this->request->getVar('stock'),
            'outstanding'   => $this->request->getVar('outstanding'),
            'estab'         => $this->request->getVar('estab'),
            'dailySales'    => $this->request->getVar('dailySales'),
            'image'         => $this->request->getVar('downloadURLP1'),
            'bankAccount'   => $this->request->getVar('bankAccount'),
            'ifsc'          => $this->request->getVar('ifsc'),
            'bankName'      => $this->request->getVar('bankName'),
            'bankBranch'    => $this->request->getVar('bankBranch'),
            'bankCity'      => $this->request->getVar('bankCity'),
            'bankState'     => $this->request->getVar('bankState'),
            'bankAddress'   => $this->request->getVar('bankAddress'),
            'agent'         => $this->request->getVar('agent'),
        ];

        // $query = $model->insert($data);
        $query = $model->save($data);
        if (!$query) {
            return $this->respond(['error' => 'Invalid Request.' . $query], 401);
        } else {
            # code...
            return $this->respond(['members' => $data], 200);
        }
    }
    public function view_member()
    {
        $model = new MemberModel();
        $groupID = $this->request->getVar('groupID');

        $groupMembers = $model->where('groupId', $groupID)->countAllResults();

        if (is_null($groupMembers)) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        }

        return $this->respond(['totalMembers' => $groupMembers], 200);
    }

    public function member_list_api()
    {
        //
        $model = new MemberModel();
        $groupID = $this->request->getVar('groupID');

        $member = $model->where('groupId', $groupID)->findAll();

        if (is_null($member)) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        }

        return $this->respond($member, 200);
    }
    public function member_view_api()
    {
        //
        $model = new MemberModel();
        $memberID = $this->request->getVar('memberID');

        $member = $model->where('member_id', $memberID)->first();

        if (is_null($member)) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        }

        return $this->respond(['member' => $member], 200);
    }

    public function total_member()
    {
        $model = new MemberModel();
        $employeeIDM = $this->request->getVar('employeeIDM');
        $totalMember = $model->where('agent', $employeeIDM)->countAllResults();
        $totalMemberMale = $model->where('gender', 'Male')->countAllResults();
        $totalMemberFemale = $model->where('gender', 'Female')->countAllResults();

        if ($totalMember <= 0) {
            return $this->respond(['totalMembers' => 0, 'totalMembersMale' => 50, 'totalMemberFemale' => 50], 401);
            // echo "Empty";
        } else {
            return $this->respond(['totalMembers' => $totalMember, 'totalMembersMale' => $totalMemberMale, 'totalMemberFemale' => $totalMemberFemale], 200);
        }
    }
}

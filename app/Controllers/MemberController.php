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
        $adhar = $this->request->getVar('adhar');
        $adharFour = substr($adhar, -4);;
        $data = [
            'member_id'    => $mobileFour . $adharFour,
            'groupName'    => $this->request->getVar('groupName'),
            'groupId'      => $this->request->getVar('groupId'),
            'mobile'    => $this->request->getVar('mobile'),
            'pan'  => $this->request->getVar('pan'),
            'adhar'   => $this->request->getVar('adhar'),
            'name'  => $this->request->getVar('name'),
            'location'  => $this->request->getVar('memberLocation'),
            'pincode'  => $this->request->getVar('groupPin'),
            'gender'  => $this->request->getVar('gender'),
            'marital'  => $this->request->getVar('marital'),
            'occupation'  => $this->request->getVar('occupation'),
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

        $totalMember = $model->countAllResults();
        $totalMemberMale = $model->where('gender', 'Male')->countAllResults();
        $totalMemberFemale = $model->where('gender', 'Female')->countAllResults();

        if (is_null($totalMember)) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        }
        return $this->respond(['totalMembers' => $totalMember, 'totalMembersMale' => $totalMemberMale, 'totalMemberFemale' => $totalMemberFemale], 200);
    }
}

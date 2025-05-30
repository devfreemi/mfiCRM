<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MemberModel;
use App\Models\GeoTagModel;
use App\Models\AgentAttendenceModel;
use CodeIgniter\API\ResponseTrait;
use Google\Cloud\Storage\StorageClient;

class MemberController extends BaseController
{
    private $bucketName = 'microfinanceinstitution0.appspot.com'; // 🔁 Replace with your Firebase bucket
    private $serviceAccountPath = APPPATH . 'Config/firebase_service.json';

    use ResponseTrait;
    public function add_member()
    {
        //
        date_default_timezone_set('Asia/Kolkata');
        $model = new MemberModel();
        $modelGeo = new GeoTagModel();
        $atten = new AgentAttendenceModel();
        $mobile = $this->request->getVar('mobile');
        $mobileFour = substr($mobile, -4);
        $name_str = strtoupper($this->request->getVar('businessName'));
        $name_str = str_replace(" ", "", $name_str);
        $name_str = substr($name_str, 0, 4);
        $signDate = date('Y-m-d');
        $date = date('Y-m-d H:i:s');
        $signIn = date('H:i:s');
        $employee = $this->request->getVar('agent');

        if ($this->request->getVar('panNo') != '') {
            # code...
            $pan = $this->request->getVar('panNo');
        } elseif ($this->request->getVar('pan') != '') {
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
            'member_id'         =>  $name_str . $mobileFour,
            'groupName'         => $this->request->getVar('groupName'),
            'groupId'           => $this->request->getVar('groupId'),
            'mobile'            => $this->request->getVar('mobile'),
            'pan'               => $pan,
            'gst'               => $this->request->getVar('gstNo'),
            'adhar'             => $adhar,
            'name'              => $this->request->getVar('panName'),
            'location'          => $this->request->getVar('memberLocation'),
            'pincode'           => $this->request->getVar('groupPin'),
            'gender'            => $this->request->getVar('gender'),
            'marital'           => $this->request->getVar('marital'),
            'occupation'        => $this->request->getVar('occupation'),
            'businessType'      => $this->request->getVar('businessType'),
            'businessName'      => $this->request->getVar('businessName'),
            'footFall'          => $this->request->getVar('footFall'),
            'stock'             => $this->request->getVar('stock'),
            'outstanding'       => $this->request->getVar('outstanding'),
            'estab'             => $this->request->getVar('estab'),
            'dailySales'        => $this->request->getVar('dailySales'),
            'image'             => $this->request->getVar('downloadURLP1'),
            'bankAccount'       => $this->request->getVar('bankAccount'),
            'ifsc'              => $this->request->getVar('ifsc'),
            'bankName'          => $this->request->getVar('bankName'),
            'bankBranch'        => $this->request->getVar('bankBranch'),
            'bankCity'          => $this->request->getVar('bankCity'),
            'bankState'         => $this->request->getVar('bankState'),
            'bankAddress'       => $this->request->getVar('bankAddress'),
            'agent'             => $this->request->getVar('agent'),
            'aadhaarVerified'   => $this->request->getVar('authenticate'),
            'aadhaarData'       => $this->request->getVar('kycLocalValueSubmit'),
            'authenticatePAN'   => $this->request->getVar('authenticatePAN'),
            'panName'           => $this->request->getVar('panName'),
            'created_at'        => $date,
            'eli_run'           => "Y",
            'month_purchase'    => $this->request->getVar('month_purchase')
        ];

        // // // FOR ATTENDENCE
        // $dataAtten = [
        //     'agent_id'          => $this->request->getVar('agent'),
        //     'date'              => $signDate,
        //     'created_at'        => $date,
        //     'sign_in_time'      => $signIn,
        // ];
        // // Check Same date for attendence
        // $queryCheck = $modelGeo->where('agent_id', $employee)
        //     ->orderBy('created_at', 'DESC')
        //     ->limit(1)
        //     ->first();

        // if ($queryCheck != null) {
        //     # code...
        //     $dateCheck = $queryCheck['date'];
        //     if ($dateCheck == $signDate) {
        //         # code...
        //         $queryMem = $model->save($data);
        //         if (!$queryMem) {
        //             return $this->respond(['error' => 'Invalid Request.'], 401);
        //         } else {
        //             # code...
        //             // return $this->respond(['tag' => $data], 200);
        //             return $this->respond(['members' => $data], 200);
        //         }
        //     } else {
        //         # code...

        //         $queryAtten = $atten->save($dataAtten);
        //         $queryMem = $model->save($data);
        //         if (!$queryAtten && !$queryMem) {
        //             return $this->respond(['error' => 'Invalid Request.'], 401);
        //         } else {
        //             # code...
        //             // return $this->respond(['tag' => $data], 200);
        //             return $this->respond(['members' => $data], 200);
        //         }
        //     }
        // } else {
        //     # code...
        //     $queryMem = $model->save($data);
        //     $queryAtten = $atten->save($dataAtten);
        //     if (!$queryAtten && !$queryMem) {
        //         return $this->respond(['error' => 'Invalid Request.'], 401);
        //     } else {
        //         # code...
        //         // return $this->respond(['tag' => $data], 200);
        //         return $this->respond(['members' => $data], 200);
        //     }
        // }


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
        $employeeIDTotal = $this->request->getVar('employeeIDTotal');
        $groupMembers = $model->where('groupId', $groupID)->where('agent', $employeeIDTotal)->countAllResults();

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
        $employeeIDG = $this->request->getVar('employeeIDG');
        $member = $model->where('groupId', $groupID)
            ->where('agent', $employeeIDG)->findAll();

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

    public function retailer_profile($memberID)
    {
        // $session = session();
        $model = new MemberModel();
        $data['retailers'] = $model->where('member_id', $memberID)->first();


        return view('retailer_profile', $data);
        // print_r($data);
        // echo $memberID;
    }
    public function retailer_fi($memberID)
    {
        // $session = session();
        $model = new MemberModel();
        $data['retailers'] = $model->where('member_id', $memberID)->first();


        return view('field', $data);
        // print_r($data);
        // echo $memberID;
    }
}

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
        $name_str = str_replace(['.', '/'], '', $name_str);
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
        if ($this->request->getVar('gstNo') != '') {
            # code...
            $gst = $this->request->getVar('gstNo');
        } else {
            # code...
            $gst = $this->request->getVar('panNo') . "/NA";
        }
        if ($this->request->getVar('gender') === 'M') {
            # code...
            $gender = 'Male';
        } else {
            # code...
            $gender = 'Female';
        }



        $data = [
            'member_id'         =>  $name_str . $mobileFour,
            'groupName'         => $this->request->getVar('groupName'),
            'groupId'           => $this->request->getVar('groupId'),
            'mobile'            => $this->request->getVar('mobile'),
            'pan'               => $pan,
            'gst'               => $gst,
            'adhar'             => $adhar,
            'name'              => $this->request->getVar('name'),
            'location'          => $this->request->getVar('memberLocation'),
            'pincode'           => $this->request->getVar('groupPin'),
            'gender'            => $gender,
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
            'gstData'           => Null,
            'panName'           => $this->request->getVar('panName'),
            'created_at'        => $date,
            'month_purchase'    => $this->request->getVar('purchaseMonthly'),
            'eli_run'           => "N",
            'userDOB'           => $this->request->getVar('panDob'),
            'loanPurpose'       => $this->request->getVar('loanPurpose'),

        ];



        $query = $model->save($data);
        if (!$query) {
            log_message('error', 'Add New Retailer API Called and Failed ' . json_encode($query));

            return $this->respond(['error' => 'Invalid Request.' . $query], 401);
        } else {
            # code...
            log_message('info', 'Add New Retailer API Called and Data Inserted in DB: ' . json_encode($data));
            return $this->respond(['members' => $data], 200);
        }
    }
    // Update Member
    public function update_member()
    {
        //
        date_default_timezone_set('Asia/Kolkata');
        $model = new MemberModel();

        $mobile = $this->request->getVar('mobile');
        $mobileFour = substr($mobile, -4);
        $name_str = strtoupper($this->request->getVar('name'));
        $name_str = str_replace(" ", "", $name_str);
        $name_str = substr($name_str, 0, 4);
        $name_str = str_replace(['.', '/'], '', $name_str);
        $signDate = date('Y-m-d');
        $date = date('Y-m-d H:i:s');
        $signIn = date('H:i:s');
        $employee = $this->request->getVar('agent');

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
        if ($this->request->getVar('gstNo') != '') {
            # code...
            $gst = $this->request->getVar('gstNo');
        } else {
            # code...
            $gst = $this->request->getVar('pan') . "/NA";
        }
        if ($this->request->getVar('gender') === 'M') {
            # code...
            $gender = 'Male';
        } elseif ($this->request->getVar('gender') === 'F') {
            # code....
            $gender = 'Female';
        } else {
            # code...
            $gender = Null;
        }



        $data = [
            'member_id'         =>  $name_str . $mobileFour,
            'groupName'         => $this->request->getVar('groupName'),
            'groupId'           => $this->request->getVar('groupId'),
            'gst'               => $gst,
            'pan'               => $pan,
            'adhar'             => $adhar,
            'name'              => $this->request->getVar('name'),
            'location'          => $this->request->getVar('memberLocation'),
            'pincode'           => $this->request->getVar('groupPin'),
            'gender'            => $gender,
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
            'gstData'           => $this->request->getVar('gstLocalValueSubmit'),
            'panName'           => $this->request->getVar('panName'),
            'created_at'        => $date,
            'eli_run'           => "Y",
            'userDOB'           => $this->request->getVar('panDob'),
            'month_purchase'    => $this->request->getVar('month_purchase')
        ];



        $query = $model
            ->where('mobile', $mobile)

            ->set($data)
            ->update();

        if (!$query) {
            return $this->respond(['error' => 'Invalid Request.' . $query], 401);
        } else {
            # code...
            return $this->respond(['members' => $data], 200);
        }
    }
    // From CRM
    public function add_member_crm()
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
        if ($this->request->getVar('gstNo') != '') {
            # code...
            $gst = $this->request->getVar('gstNo');
        } else {
            # code...
            $gst = $this->request->getVar('panNo') . "/NA";
        }
        $imageFile = $this->request->getFile('image_profile');
        $imagePath = null;

        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $newName = $imageFile->getRandomName(); // Or use a unique ID
            $imageFile->move(ROOTPATH . 'public/uploads/', $newName);
            $imagePath = 'uploads/' . $newName;
        }


        $data = [
            'member_id'         =>  $name_str . $mobileFour,
            'groupName'         => $this->request->getVar('groupName'),
            'groupId'           => $this->request->getVar('groupId'),
            'mobile'            => $this->request->getVar('mobile'),
            'pan'               => $pan,
            'gst'               => $gst,
            'adhar'             => $adhar,
            'name'              => $this->request->getVar('name'),
            'location'          => $this->request->getVar('memberLocation'),
            'pincode'           => $this->request->getVar('groupPin'),


            'businessType'      => $this->request->getVar('businessType'),
            'businessName'      => $this->request->getVar('businessName'),
            'footFall'          => $this->request->getVar('footFall'),
            'stock'             => $this->request->getVar('stock'),
            'outstanding'       => $this->request->getVar('previous_emi'),
            'estab'             => $this->request->getVar('business_time'),
            'dailySales'        => $this->request->getVar('daily_sales'),
            'image'             => base_url() . $imagePath,

            'agent'             => $this->request->getVar('agent'),
            'aadhaarVerified'   => $this->request->getVar('authenticate'),
            'aadhaarData'       => $this->request->getVar('kycLocalValueSubmit'),
            // 'gstData'           => $this->request->getVar('gstLocalValueSubmit'),
            'panName'           => $this->request->getVar('panName'),
            'created_at'        => $date,
            'eli_run'           => "Y",
            'month_purchase'    => $this->request->getVar('month_purchase')
        ];

        $data_eli_run = [
            'cibil' =>  $this->request->getVar('cibil'),
            'member_id' => $name_str . $mobileFour,
            'first_date' => date('Y-m-d'),

        ];
        $db = db_connect();
        $builder = $db->table('initial_eli_run');
        $builder->insert($data_eli_run);

        $query = $model->save($data);
        if (!$query) {
            return $this->respond(['error' => 'Invalid Request.' . $query], 401);
        } else {
            # code...
            $session = session();
            $session->setFlashdata('success', 'Member Added Successfully');
            return redirect()->to(base_url() . 'members');
            // return $this->respond(['members' => $data], 200);
        }
    }
    // Update Member
    public function update_member_los()
    {
        //
        date_default_timezone_set('Asia/Kolkata');
        $model = new MemberModel();

        $mobile = $this->request->getVar('mobile');
        $mobileFour = substr($mobile, -4);
        $name_str = strtoupper($this->request->getVar('name'));
        $name_str = str_replace(" ", "", $name_str);
        $name_str = substr($name_str, 0, 4);
        $name_str = str_replace(['.', '/'], '', $name_str);
        $signDate = date('Y-m-d');
        $date = date('Y-m-d H:i:s');
        $signIn = date('H:i:s');
        $employee = $this->request->getVar('agent');

        if ($this->request->getVar('panNo') != '') {
            # code...
            $pan = $this->request->getVar('panNo');
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
        if ($this->request->getVar('gstNo') != '') {
            # code...
            $gst = $this->request->getVar('gstNo');
        } else {
            # code...
            $gst = $this->request->getVar('panNo') . "/NA";
        }
        if ($this->request->getVar('gender') === 'M') {
            # code...
            $gender = 'Male';
        } elseif ($this->request->getVar('gender') === 'F') {
            # code....
            $gender = 'Female';
        } else {
            # code...
            $gender = Null;
        }



        $data = [
            // 'member_id'         =>  $name_str . $mobileFour,
            'groupName'         => $this->request->getVar('groupName'),
            'groupId'           => $this->request->getVar('groupId'),
            'gst'               => $gst,
            'pan'               => $pan,
            'adhar'             => $adhar,
            'name'              => $this->request->getVar('name'),
            'location'          => $this->request->getVar('memberLocation'),
            'pincode'           => $this->request->getVar('groupPin'),
            'gender'            => $gender,
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
            'gstData'           => $this->request->getVar('gstLocalValueSubmit'),
            'panName'           => $this->request->getVar('panName'),
            'created_at'        => $date,
            'month_purchase'    => $this->request->getVar('purchaseMonthly'),
            'eli_run'           => "N",
            'userDOB'           => $this->request->getVar('panDob'),
            'loanPurpose'       => $this->request->getVar('loanPurpose'),
        ];



        $query = $model
            ->where('mobile', $mobile)

            ->set($data)
            ->update();

        if (!$query) {
            return $this->respond(['error' => 'Invalid Request.' . $query], 401);
        } else {
            # code...
            return $this->respond(['members' => $data], 200);
        }
    }
    // From CRM

    public function view_member()
    {
        $model = new MemberModel();
        $groupID = $this->request->getVar('groupID');
        $employeeIDTotal = $this->request->getVar('employeeIDTotal');
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
        $employeeIDG = $this->request->getVar('employeeIDG');
        $member = $model->where('groupId', $groupID)
            // ->where('agent', $employeeIDG)
            ->findAll();

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
    // Retailer Loan Doc

    public function retailer_loan_doc()
    {
        // $session = session();
        $memberID = $this->request->getVar('memberID');
        $db = db_connect();
        $builder = $db->table('retailer_loan_doc');
        $doc = $builder->where('member_id', $memberID)->where('eSign', 'Y')->get()->getRowArray();

        if (is_null($doc)) {
            return $this->respond(['error' => 'Invalid Request.'], 401);
        }

        return $this->respond(['doc' => $doc], 200);
    }
}

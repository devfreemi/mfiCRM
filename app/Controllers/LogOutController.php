<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\AgentAttendenceModel;
use CodeIgniter\API\ResponseTrait;

class LogOutController extends BaseController
{
    use ResponseTrait;

    public function logout_emp()
    {
        // date_default_timezone_set('Asia/Kolkata');
        $model = new AgentAttendenceModel();
        $agent =  $this->request->getVar('employeeID');
        $date = $this->request->getVar('date');
        $time = $this->request->getVar('time');
        $date_trime = substr($date, 0, 10);
        $time_trime = substr($time, 0, 8);
        $data = [
            'sign_out_time'             => $time_trime,
            // 'agent' => $agent,
        ];
        $db = db_connect();
        $builder = $db->table('agentattendences');

        $query = $builder->where('agent_id', $agent)
            ->update($data);

        if (!$query) {
            # code...
            return $this->respond(['error' => 'Invalid Request.' . $query], 401);
        } else {
            # code...
            return $this->respond(['data' => $data], 200);
        }
    }
}

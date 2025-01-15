<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\GeoTagModel;
use CodeIgniter\API\ResponseTrait;

class LogOutController extends BaseController
{
    use ResponseTrait;

    public function logout_emp()
    {
        // date_default_timezone_set('Asia/Kolkata');
        // $model = new GeoTagModel();
        $agent =  $this->request->getVar('employeeID');
        $date = $this->request->getVar('date');
        $time = $this->request->getVar('time');
        $date_trime = substr($date, 0, 10);
        $time_trime = substr($time, 0, 8);
        $data = [
            'log_out_date'              => $date_trime,
            'sign_out_time'             => $time_trime,
        ];
        return $this->respond(['data' => $data], 200);
    }
}

<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;
use App\Models\EmployeeModel;

class EmployeeDetails extends BaseController
{
    use ResponseTrait;
    public function get_employee()
    {
        //
        $employee = new EmployeeModel;
        $employeeID = $this->request->getVar('employeeID');

        $user = $employee->where('employeeID', $employeeID)->first();

        if (is_null($user)) {
            return $this->respond(['error' => 'Invalid Employee ID.'], 401);
        }

        return $this->respond(['users' => $user], 200);
    }
}

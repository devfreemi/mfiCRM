<?php

namespace App\Controllers;

use App\Models\User_login_model;

class Home extends BaseController
{
    public function dashboard()
    {
        return view('dashboard');
    }
    public function employee()
    {
        return view('employee');
    }
}

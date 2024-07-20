<?php

namespace App\Controllers;



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
    public function branch()
    {
        return view('branch');
    }
    public function group()
    {
        return view('group');
    }
    public function members()
    {
        return view('members');
    }
    public function loan()
    {
        return view('loan');
    }
    public function loan_view()
    {
        return view('loan_view');
    }
}

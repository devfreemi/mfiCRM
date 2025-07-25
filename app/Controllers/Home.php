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
    public function member_edit()
    {
        return view('member_edit');
    }
    public function member_add()
    {
        return view('member_add');
    }
    public function loan()
    {
        return view('loan');
    }
    public function loan_view()
    {
        return view('loan_view');
    }
    public function group_view()
    {
        return view('group_view');
    }
    public function loan_details()
    {
        return view('loan_details');
    }
    public function bank()
    {
        return view('bank');
    }
    public function bank_deposite()
    {
        return view('bank_deposite');
    }
    public function disbursement()
    {
        return view('disbursement');
    }
    public function geo_tag()
    {
        return view('geo_tag');
    }
    public function e_attendence()
    {
        return view('employee_attendence');
    }
    public function redirection()
    {
        return view('page-redirection');
    }
    public function digi_success()
    {
        return view('digilocker_success');
    }
}

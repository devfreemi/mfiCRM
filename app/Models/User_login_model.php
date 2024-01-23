<?php

namespace App\Models;

use CodeIgniter\Model;

class User_login_model extends Model
{
    // ...
    protected $table = 'user';
    protected $allowedFields = ['userId', 'password'];
}

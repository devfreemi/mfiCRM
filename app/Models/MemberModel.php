<?php

namespace App\Models;

use CodeIgniter\Model;

class MemberModel extends Model
{
    protected $table            = 'members';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'member_id',
        'groupName',
        'groupId',
        'mobile',
        'pan',
        'adhar',
        'name',
        'location',
        'pincode',
        'gender',
        'marital',
        'occupation',
        'bankAccount',
        'ifsc',
        'bankName',
        'bankBranch',
        'bankCity',
        'bankState',
        'bankAddress',
        'created_at',
        'businessType',
        'businessName',
        'footFall',
        'stock',
        'outstanding',
        'estab',
        'dailySales',
        'image',
        'agent',
        'aadhaarData',
        'aadhaarVerified',
        'panName',
        'authenticatePAN'

    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}

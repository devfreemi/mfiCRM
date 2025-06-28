<?php

namespace App\Models;

use CodeIgniter\Model;

class FiCheckModel extends Model
{
    protected $table            = 'field_inspection_feedback';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'member_id',
        'fiInspector_name',
        'retailer_present',
        'retailer_behavior_professional',
        'retailer_aware_products',
        'retailer_needs_training',
        'shop_clean',
        'products_displayed',
        'stock_available',
        'promo_materials_visible',
        'location_accessible',
        'payment_behavior',
        'shop_ownership',
        'house_ownership',
        'documents_verified',
        'documents_received',
        'inspector_comments'
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

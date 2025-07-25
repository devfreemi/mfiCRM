<?php

namespace App\Models;

use CodeIgniter\Model;

class LoanModel extends Model
{
    protected $table            = 'loans';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'groupId',
        'member_id',
        'loan_amount',
        'loan_type',
        'loan_status',
        'application_stage',
        'roi',
        'loan_tenure',
        'emi',
        'pending_emi',
        'loan_due',
        'employee_id',
        'applicationID',
        'created_at',
        'emi_day'
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

    function create($table)
    {
        $db = db_connect();

        if (!$db->tableExists($table)) {
            // some code...

            $sql = "CREATE TABLE $table (
            `Id` int(11) NOT NULL AUTO_INCREMENT,
          `transactionDate` timestamp NOT NULL DEFAULT current_timestamp(),
          `valueDate` varchar(28) NOT NULL,
          `valueDateStamp` varchar(28) NOT NULL,
          `reference` varchar(4) NOT NULL DEFAULT 'N',
          `emi` varchar(18) DEFAULT 0 NULL,
          `debit` varchar(18) DEFAULT 0 NULL,
          `credit` varchar(18) DEFAULT 0 NULL,
          `balance` varchar(18) NOT NULL,
          `orderId` varchar(64) NULL,
          `paymentSession` varchar(256) NULL,
          `paymentStatus` varchar(64) NULL,
          `transactionId` varchar(64) NULL,
          `comments` varchar(64) NULL,
          `updated_on` timestamp NOT NULL DEFAULT current_timestamp(),
          PRIMARY KEY (Id)
          );";

            $query = $this->db->query($sql);
            return $query;
        }
    }
}

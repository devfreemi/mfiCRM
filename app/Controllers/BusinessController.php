<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class BusinessController extends BaseController
{
    public function emiReport()
    {
        $db = db_connect();

        // ✅ 1. Get month/year from GET params, default to current
        $month = $this->request->getGet('month') ?? date('m');
        $year  = $this->request->getGet('year')  ?? date('Y');

        // 2. Get all tables starting with tab_
        $tables = $db->query("
            SELECT TABLE_NAME 
            FROM information_schema.tables 
            WHERE table_schema = DATABASE()
              AND table_name LIKE 'tab\_%'
        ")->getResultArray();

        $unionParts = [];

        // 3. Build SELECT for each table
        foreach ($tables as $t) {
            $table = $t['TABLE_NAME'];

            // Extract applicationID (remove "tab_")
            $applicationId = str_replace("tab_", "", $table);

            $unionParts[] = "
                SELECT '{$table}' AS table_name,
                       '{$applicationId}' AS application_id,
                       COALESCE(SUM(emi),0) AS total_emi
                FROM `{$table}`
                WHERE paymentStatus = 'SUCCESS'
                  AND MONTH(valueDate) = {$db->escape($month)}
                  AND YEAR(valueDate) = {$db->escape($year)}
            ";
        }

        if (empty($unionParts)) {
            $data['results'] = [];
            return view('emi_report', $data);
        }

        // 4. Combine into one UNION ALL query
        $sql = implode(" UNION ALL ", $unionParts);

        // 5. Add ALL_TABLES row (only keep tables with EMI > 0)
        $finalSql = "
            SELECT table_name, application_id, total_emi FROM (
                {$sql}
            ) t
            WHERE total_emi > 0
            UNION ALL
            SELECT 'ALL_TABLES' AS table_name, NULL AS application_id, SUM(total_emi) 
            FROM (
                {$sql}
            ) u
        ";

        // 6. Execute final query
        $query = $db->query($finalSql);
        $results = $query->getResultArray();

        // 7. Map application_id with loans table
        $appIds = array_filter(array_column($results, 'application_id'));
        $loans = [];

        if (!empty($appIds)) {
            $builder = $db->table('loans');
            $loans = $builder->select('applicationID, member_id, loan_amount, loan_due')
                ->whereIn('applicationID', $appIds)
                ->get()
                ->getResultArray();

            $loans = array_column($loans, null, 'applicationID');
        }

        // 8. Attach loan details
        foreach ($results as &$row) {
            if (!empty($row['application_id']) && isset($loans[$row['application_id']])) {
                $row['member_id']   = $loans[$row['application_id']]['member_id'];
                $row['loan_amount'] = $loans[$row['application_id']]['loan_amount'];
                $row['loan_due']    = $loans[$row['application_id']]['loan_due'];
            } else {
                $row['member_id']   = ($row['table_name'] === 'ALL_TABLES') ? '-' : 'Unknown';
                $row['loan_amount'] = ($row['table_name'] === 'ALL_TABLES') ? '-' : '0';
                $row['loan_due']    = ($row['table_name'] === 'ALL_TABLES') ? '-' : '0';
            }
        }

        // 9. Send to view with filter info
        $data['results'] = $results;
        $data['month']   = $month;
        $data['year']    = $year;

        return view('emi_report', $data);
    }
}

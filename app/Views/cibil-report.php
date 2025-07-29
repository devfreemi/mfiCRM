<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Credit Report - <?= esc($data['data']['name'] ?? 'N/A') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-top: 2rem;
            border-left: 5px solid #0d6efd;
            padding-left: 10px;
        }

        .info-card {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: .5rem;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }

        .table-sm th,
        .table-sm td {
            font-size: 0.875rem;
        }
    </style>
</head>

<body>

    <div class="container my-5">

        <div class="info-card">
            <h2 class="text-primary">Credit Report for <?= esc($data['data']['name'] ?? 'N/A') ?></h2>
            <p>
                <strong>PAN:</strong> <?= esc($data['data']['pan'] ?? 'N/A') ?> |
                <strong>Mobile:</strong> <?= esc($data['data']['mobile'] ?? 'N/A') ?> |
                <strong>Credit Score:</strong> <?= esc($data['data']['credit_score'] ?? 'N/A') ?>
            </p>
        </div>

        <!-- Report Metadata -->
        <div class="section-title">Report Metadata</div>
        <?php $header = $data['data']['credit_report']['CreditProfileHeader'] ?? []; ?>
        <div class="info-card">
            <ul class="mb-0">
                <li><strong>Report Date:</strong> <?= isset($header['ReportDate']) ? date('Y-m-d', strtotime($header['ReportDate'])) : 'N/A' ?></li>
                <li><strong>Version:</strong> <?= esc($header['Version'] ?? 'N/A') ?></li>
                <li><strong>Report No:</strong> <?= esc($header['ReportNumber'] ?? 'N/A') ?></li>
            </ul>
        </div>

        <!-- Credit Summary -->
        <div class="section-title">Credit Summary</div>
        <?php $summary = $data['data']['credit_report']['CAIS_Account']['CAIS_Summary']['Credit_Account'] ?? []; ?>
        <div class="info-card">
            <ul class="mb-0">
                <li><strong>Total Accounts:</strong> <?= esc($summary['CreditAccountTotal'] ?? 0) ?></li>
                <li><strong>Active Accounts:</strong> <?= esc($summary['CreditAccountActive'] ?? 0) ?></li>
                <li><strong>Closed Accounts:</strong> <?= esc($summary['CreditAccountClosed'] ?? 0) ?></li>
                <li><strong>Defaulted Accounts:</strong> <?= esc($summary['CreditAccountDefault'] ?? 0) ?></li>
            </ul>
        </div>

        <!-- Outstanding Balances -->
        <div class="section-title">Outstanding Balances</div>
        <?php $balance = $data['data']['credit_report']['CAIS_Account']['CAIS_Summary']['Total_Outstanding_Balance'] ?? []; ?>
        <div class="info-card">
            <ul class="mb-0">
                <li><strong>Secured:</strong> ₹<?= ($balance['Outstanding_Balance_Secured'] ?? 0) ?></li>
                <li><strong>Unsecured:</strong> ₹<?= ($balance['Outstanding_Balance_UnSecured'] ?? 0) ?></li>
                <li><strong>Total:</strong> ₹<?= ($balance['Outstanding_Balance_All'] ?? 0) ?></li>
            </ul>
        </div>

        <!-- Account Details -->
        <div class="section-title">Credit Account Details</div>
        <?php foreach ($data['data']['credit_report']['CAIS_Account']['CAIS_Account_DETAILS'] ?? [] as $account): ?>
            <div class="info-card">
                <h5><?= esc($account['Subscriber_Name'] ?? 'Unknown Subscriber') ?></h5>
                <p><strong>Account #:</strong> <?= esc($account['Account_Number'] ?? 'N/A') ?> |
                    <strong>Open Date:</strong> <?= esc($account['Open_Date'] ?? 'N/A') ?> |
                    <strong>Current Balance:</strong> ₹<?= ($account['Current_Balance'] ?? 0) ?> |
                    <strong>Loan Amount:</strong> ₹<?= ($account['Highest_Credit_or_Original_Loan_Amount'] ?? 0) ?>
                </p>

                <!-- Monthly Repayment Behavior -->
                <?php if (!empty($account['Account_Review_Data'])): ?>
                    <h6 class="text-secondary mt-3">Monthly Payment History</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Month</th>
                                    <th>Status</th>
                                    <th>EMI (₹)</th>
                                    <th>Balance (₹)</th>
                                    <th>Past Due (₹)</th>
                                    <th>Paid (₹)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($account['Account_Review_Data'] as $monthData):
                                    $monthNum = str_pad($monthData['Month'], 2, '0', STR_PAD_LEFT);
                                    $monthStr = $monthNum . '/' . $monthData['Year'];
                                    $statusCode = $monthData['Account_Status'];
                                    $statusMap = [
                                        '11' => 'Active',
                                        '13' => 'Closed'
                                    ];
                                    $statusLabel = $statusMap[$statusCode] ?? 'Status ' . $statusCode;
                                ?>
                                    <tr>
                                        <td><?= esc($monthStr) ?></td>
                                        <td><span class="badge bg-info"><?= esc($statusLabel) ?></span></td>
                                        <td><?= ($monthData['EMI_Amount'] ?? 0) ?></td>
                                        <td><?= ($monthData['Current_Balance'] ?? 0) ?></td>
                                        <td class="<?= ($monthData['Amount_Past_Due'] ?? 0) > 0 ? 'text-danger' : 'text-success' ?>">
                                            <?= ($monthData['Amount_Past_Due'] ?? 0) ?>
                                        </td>
                                        <td><?= ($monthData['Actual_Payment_Amount'] ?? 0) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

    </div>

</body>

</html>
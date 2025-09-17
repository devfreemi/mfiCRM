<?php include 'fragments/head.php'; ?>
<?php include 'fragments/sidebar.php'; ?>
<link href="https://cdn.datatables.net/2.1.0/css/dataTables.bootstrap5.css" rel="stylesheet">
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.0/js/dataTables.bootstrap5.js"></script>

<div class="main">

    <?php include 'fragments/nav.php'; ?>
    <main class="content">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3"><strong>Loan</strong> Collection</h1>

            <div class="row">
                <div class="col-4">
                    <?php
                    $db = db_connect();

                    // Today's date
                    $today = date('Y-m-d');

                    // Fetch today's successful transactions total and count
                    $query = $db->table('transaction_master')
                        ->select('COUNT(id) as total_count, SUM(payment_amount) as total_amount')
                        ->where('DATE(created_at)', $today)
                        ->where('payment_status', 'SUCCESS')
                        ->get()
                        ->getRow();

                    $totalAmount = $query->total_amount ?? 0;
                    $totalCount  = $query->total_count ?? 0;
                    ?>

                    <!-- Glassmorphism Card -->
                    <div class="glass-card glass-card-purple my-5 p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="fw-bold text-uppercase text-white-75 mb-1">Today's Collection</h6>
                                <h2 class="fw-bold text-white mb-0">₹ <?= number_format($totalAmount, 2); ?></h2>
                                <small class="text-white-75"><?= $totalCount; ?> Successful Transaction<?= $totalCount > 1 ? 's' : ''; ?></small>
                            </div>
                            <div class="icon-glass d-flex align-items-center justify-content-center">
                                <!-- <i class="bi bi-currency-rupee fs-3 text-white"></i> -->
                                <i class="fas fa-rupee-sign fs-3 text-white"></i>
                            </div>
                        </div>
                        <p class="mb-0 text-white-75">Collected on <?= date('d M Y'); ?></p>
                    </div>
                </div>
                <div class="col-4">
                    <?php
                    $db = db_connect();

                    // Fetch today's successful transactions total and count
                    $queryTotal = $db->table('transaction_master')
                        ->select('COUNT(id) as total_count, SUM(payment_amount) as total_amount')
                        // ->where('DATE(created_at)', $today)
                        ->where('payment_status', 'SUCCESS')
                        ->get()
                        ->getRow();

                    $totalAmountFull = $queryTotal->total_amount ?? 0;
                    $totalCountFull  = $queryTotal->total_count ?? 0;
                    ?>

                    <!-- Glassmorphism Card -->
                    <div class="glass-card glass-card-orange my-5 p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="fw-bold text-uppercase text-white-75 mb-1">Total Collection</h6>
                                <h2 class="fw-bold text-white mb-0">₹ <?= number_format($totalAmountFull, 2); ?></h2>
                                <small class="text-white-75"><?= $totalCountFull; ?> Successful Transaction<?= $totalCountFull > 1 ? 's' : ''; ?></small>
                            </div>
                            <div class="icon-glass d-flex align-items-center justify-content-center">
                                <i class="fas fa-wallet fs-3 text-white"></i>
                            </div>
                        </div>
                        <p class="mb-0 text-white-75">Collected till <?= date('d M Y'); ?></p>
                    </div>
                </div>
                <div class="col-xl-12 col-xxl-12 d-flex">

                    <div class="w-100">
                        <table id="branch" class="table table-striped">
                            <thead>
                                <tr class="table-dark text-center">
                                    <th>Sl</th>
                                    <th>Loan ID</th>
                                    <th>Order ID</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>CF Payment ID</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $db = db_connect();

                                $query = $db->table('transaction_master')
                                    ->orderBy('id', 'DESC')
                                    ->get();

                                foreach ($query->getResult() as $row) {
                                ?>
                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <td><?= $row->loan_id; ?></td>
                                        <td><?= $row->order_id; ?></td>
                                        <td>
                                            <?php if ($row->payment_status == 'SUCCESS') : ?>
                                                <span class="badge bg-success">Success</span>
                                            <?php elseif ($row->payment_status == 'FAILED') : ?>
                                                <span class="badge bg-danger">Failed</span>
                                            <?php else : ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $row->created_at; ?></td>
                                        <td><?= $row->payment_amount; ?></td>
                                        <td><?= $row->cf_payment_id; ?></td>
                                    </tr>
                                <?php } ?>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Sl</th>
                                    <th>Loan ID</th>
                                    <th>Order ID</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>CF Payment ID</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>


            </div>





        </div>
    </main>

    <?php include 'fragments/footer.php'; ?>
</div>
</div>
<script>
    new DataTable('#branch');
</script>
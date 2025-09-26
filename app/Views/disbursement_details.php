<?php include 'fragments/head.php'; ?>
<?php include 'fragments/sidebar.php'; ?>
<?php
$db = db_connect();
$builder = $db->table('members');
$builder->select('*');
$builder->where('member_id', $disbursement['member_id']);
$query = $builder->get();
$memberDetails = $query->getRowArray();
?>

<div class="main">

    <?php include 'fragments/nav.php'; ?>
    <main class="content">
        <div class="container-fluid p-0">


            <body class="bg-light">

                <div class="container my-5">
                    <div class="row">

                        <!-- Form -->
                        <div class="col-md-7">
                            <div class="card shadow p-4 mb-4">
                                <h4 class="mb-3">💼 Loan Disbursement Form</h4>

                                <?php if (session()->getFlashdata('errors')): ?>
                                    <div class="alert alert-danger">
                                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                            <div>⚠️ <?= esc($error) ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (session()->getFlashdata('success')): ?>
                                    <div class="alert alert-success">
                                        ✅ <?= session()->getFlashdata('success') ?>
                                    </div>
                                <?php endif; ?>

                                <form method="post" enctype="multipart/form-data" action="<?= base_url() ?>update-loan">
                                    <?= csrf_field() ?>

                                    <div class="mb-3">
                                        <label for="member_id" class="form-label">Member ID</label>
                                        <input type="text" class="form-control" name="member_id" id="member_id" value="<?= $disbursement['member_id'] ?>" required readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="business_name" class="form-label">Business Name</label>
                                        <input type="text" class="form-control" name="business_name" id="business_name" value="<?= esc($memberDetails['businessName']) ?>" required readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="customer_email" class="form-label">Customer Email</label>
                                        <input type="email" class="form-control" name="customer_email" id="customer_email" value="">
                                    </div>
                                    <div class="mb-3">
                                        <label for="loan_app_no" class="form-label">Loan Application Number</label>
                                        <input type="text" class="form-control" name="applicationid" id="loan_app_no" value="<?= $disbursement['applicationID'] ?>" readonly required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="disbursed_amount" class="form-label">Disbursed Amount (₹)</label>
                                        <input type="number" class="form-control" name="disbursed_amount" id="disbursed_amount" value="<?= $disbursement['disbursable_amount'] ?>" readonly required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="transaction_id" class="form-label">Transaction ID</label>
                                        <input type="text" class="form-control" name="transaction_id" id="transaction_id" value="" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="bank_receipt" class="form-label">Upload Bank Disbursement Receipt</label>
                                        <input type="file" class="form-control" name="bank_receipt" id="bank_receipt" accept=".pdf,.jpg,.jpeg,.png" required>
                                    </div>
                                    <input type="hidden" name="loan_amount" value="<?= $disbursement['loan_amount'] ?>">
                                    <input type="hidden" name="tenure" value="<?= $disbursement['loan_tenure'] ?>">
                                    <input type="hidden" name="roi" value="<?= $disbursement['roi'] ?>">
                                    <input type="hidden" name="emi_amount" value="<?= round($disbursement['total_amount'] / $disbursement['pending_emi']) ?>">
                                    <input type="hidden" name="first_dei_date" value="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                                    <input type="hidden" name="status" value="Disbursed">



                                    <input type="hidden" name="member_name" value="<?= $memberDetails['panName'] ?>">
                                    <input type="hidden" name="member_phone" value="<?= $memberDetails['mobile'] ?>">
                                    <input type="hidden" name="member_bank_account" value="<?= $memberDetails['bankAccount'] ?>">

                                    <button type="submit" class="btn btn-primary">Submit Disbursement</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="row">

                                <!-- Timeline Report -->
                                <div class="col-md-12">
                                    <div class="card shadow-sm p-3">
                                        <h5 class="text-secondary mb-3">📆 Timeline Report</h5>

                                        <ul class="list-group">
                                            <li class="list-group-item d-flex justify-content-between">
                                                <strong>Member Created At:</strong>
                                                <span><?= date('d M Y, h:i A', strtotime($memberDetails['created_at'])) ?></span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between">
                                                <strong>Loan Created At:</strong>
                                                <span><?= date('d M Y, h:i A', strtotime($disbursement['created_at'])) ?></span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between">
                                                <strong>Loan Approved At:</strong>
                                                <span><?= date('d M Y, h:i A', strtotime($disbursement['updated_at'])) ?></span>
                                            </li>
                                        </ul>

                                    </div>
                                </div>
                                <!-- Bank Report -->
                                <div class="col-md-12">
                                    <div class="card shadow-sm p-3">
                                        <h5 class="text-secondary mb-3">📄 Member Bank Details</h5>

                                        <ul class="list-group">
                                            <li class="list-group-item d-flex justify-content-between"><strong>Member ID:</strong> <span><?= esc($memberDetails['member_id']) ?></span></li>
                                            <li class="list-group-item d-flex justify-content-between"><strong>Account Holder:</strong> <span><?= esc($memberDetails['panName']) ?></span></li>
                                            <li class="list-group-item d-flex justify-content-between"><strong>Bank Name:</strong> <span><?= esc($memberDetails['bankName']) ?></span></li>
                                            <li class="list-group-item d-flex justify-content-between"><strong>Account Number:</strong> <span><?= esc($memberDetails['bankAccount']) ?></span></li>
                                            <li class="list-group-item d-flex justify-content-between"><strong>IFSC Code:</strong> <span><?= esc($memberDetails['ifsc']) ?></span></li>
                                            <li class="list-group-item d-flex justify-content-between"><strong>Bank Branch:</strong> <span><?= esc($memberDetails['bankBranch']) ?></span></li>
                                            <li class="list-group-item d-flex justify-content-between"><strong>Branch Address:</strong> <span><?= esc($memberDetails['bankAddress']) ?></span></li>
                                        </ul>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>



        </div>
    </main>



    <?php include 'fragments/footer.php'; ?>
</div>
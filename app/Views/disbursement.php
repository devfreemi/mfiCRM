<?php include 'fragments/head.php'; ?>
<?php include 'fragments/sidebar.php'; ?>
<link href="https://cdn.datatables.net/2.1.0/css/dataTables.bootstrap5.css" rel="stylesheet">
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.0/js/dataTables.bootstrap5.js"></script>
<script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
<div class="main">

    <?php include 'fragments/nav.php'; ?>
    <main class="content">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3"><strong>Pending Disbursement Applications</strong></h1>

            <div class="row">
                <?php if (session()->getFlashdata('msg')) : ?>
                    <div class="col-xl-12 col-xxl-12 mx-auto my-5">
                        <p class="text-center fw-bold text-success"><?= session()->getFlashdata('msg') ?></p>
                    </div>
                <?php endif; ?>
                <div class="col-xl-12 col-xxl-12 d-flex">
                    <div class="w-100">
                        <table id="branch" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Retailer Name</th>
                                    <th>Retailer Id</th>
                                    <th>Loan Amount</th>
                                    <th>Total Loan Amount</th>
                                    <th>Disbursable Amount</th>
                                    <th>Charges and Insurance</th>
                                    <th>Loan Status</th>
                                    <th>Application ID</th>

                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $db = db_connect();
                                $builder = $db->table('loans');
                                $builder->select('*');
                                $builder->join('members', 'members.member_id = loans.member_id');
                                $builder->where('loans.loan_status', 'Approved');
                                $query = $builder->get();
                                foreach ($query->getResult() as $row) {
                                    $e_id =  $row->employee_id;
                                    $builder_name = $db->table('employees');
                                    $builder_name->where('employeeID', $e_id);
                                    $query_name = $builder_name->get();
                                    foreach ($query_name->getResult() as $row_name) {
                                ?>
                                        <tr>
                                            <td><?php echo $row->id; ?></td>
                                            <td><?php echo $row->name; ?></td>
                                            <td><?php echo $row->member_id; ?></td>
                                            <td><?php echo $row->loan_amount; ?></td>
                                            <td><?php echo $row->total_amount; ?></td>
                                            <td class="text-success fw-bold"><?php echo $row->disbursable_amount; ?></td>
                                            <td><?php echo $row->chargesandinsurance; ?></td>
                                            <td><?php echo $row->loan_status; ?></td>
                                            <td><?php echo $row->applicationID; ?></td>


                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a type="button" class="btn btn-primary  view" href="<?= base_url('disbursement/details/' . $row->applicationID) ?>">
                                                        <i class="align-middle" data-feather="edit"></i>
                                                    </a>



                                                </div>
                                            </td>
                                        </tr>
                                <?php }
                                } ?>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Id</th>
                                    <th>Retailer Name</th>
                                    <th>Retailer Id</th>
                                    <th>Loan Amount</th>
                                    <th>Total Loan Amount</th>
                                    <th>Disbursable Amount</th>
                                    <th>Charges and Insurance</th>
                                    <th>Loan Status</th>
                                    <th>Application ID</th>

                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>

            <h1 class="h3 my-3"><strong>Disbursed Applications</strong></h1>

            <div class="row">

                <div class="col-xl-12 col-xxl-12 d-flex">
                    <div class="w-100">
                        <table id="branch" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Application ID</th>
                                    <th>Retailer Name</th>
                                    <th>Retailer Id</th>
                                    <th>Disbursed Amount</th>
                                    <th>Transaction ID</th>
                                    <th>Payment Receipt</th>

                                    <th>Date and Time</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $db = db_connect();
                                $i = 1;
                                $builderD = $db->table('disbursement');
                                $builderD->select('*');
                                $builderD->join('members', 'members.member_id = disbursement.retailer_id');
                                // $builder->where('loans.loan_status', 'Approved');
                                $queryD = $builderD->get();
                                foreach ($queryD->getResult() as $rowD) {

                                ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $rowD->applicationID; ?></td>
                                        <td><?php echo $rowD->name; ?></td>
                                        <td><?php echo $rowD->retailer_id; ?></td>
                                        <td><?php echo $rowD->disbursed_amount; ?></td>
                                        <td><?php echo $rowD->transaction_id; ?></td>
                                        <td>
                                            <a href="<?= base_url($rowD->bank_receipt) ?>" target="_blank" class="btn btn-primary btn-sm">View Receipt</a>
                                        </td>
                                        <td><?php echo $rowD->created_at; ?></td>


                                    </tr>
                                <?php
                                }
                                ?>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Id</th>
                                    <th>Application ID</th>
                                    <th>Retailer Name</th>
                                    <th>Retailer Id</th>
                                    <th>Disbursed Amount</th>
                                    <th>Transaction ID</th>
                                    <th>Payment Receipt</th>

                                    <th>Date and Time</th>

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
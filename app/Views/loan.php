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

            <h1 class="h3 mb-3">List of <strong>Applications</strong></h1>

            <div class="row">
                <div class="col-3">
                    <?php
                    $db = db_connect();

                    // Today's date
                    $today = date('Y-m-d');

                    // Fetch today's successful transactions total and count
                    $query = $db->table('loans')
                        ->select('COUNT(id) as total_count')
                        // ->where('DATE(created_at)', $today)
                        ->where('loan_status', 'Disbursed')
                        ->get()
                        ->getRow();

                    // $totalAmount = $query->total_amount ?? 0;
                    $totalCount  = $query->total_count ?? 0;
                    ?>

                    <!-- Glassmorphism Card -->
                    <div class="glass-card glass-card-green my-5 p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="fw-bold text-uppercase text-white-75 mb-1">Total number of active loans</h6>
                                <h2 class="fw-bold text-white mb-0"><?= number_format($totalCount); ?></h2>
                            </div>
                            <div class="icon-glass d-flex align-items-center justify-content-center">
                                <!-- <i class="bi bi-currency-rupee fs-3 text-white"></i> -->
                                <i class="fas fa-folder-open fs-3"></i>
                            </div>
                        </div>
                        <p class="mb-0 text-white-75">Date till <?= date('d M Y H:i A'); ?></p>
                    </div>
                </div>
                <div class="col-3">
                    <?php
                    $db = db_connect();

                    // Fetch today's successful transactions total and count
                    $queryTotal = $db->table('loans')
                        ->select('COUNT(id) as total_count')
                        // ->where('DATE(created_at)', $today)
                        ->where('loan_status', 'Completed')
                        ->get()
                        ->getRow();

                    $totalCountFull  = $queryTotal->total_count ?? 0;
                    ?>

                    <!-- Glassmorphism Card -->
                    <div class="glass-card glass-card-purple my-5 p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="fw-bold text-uppercase text-white-75 mb-1">Total Loan Completed</h6>
                                <h2 class="fw-bold text-white mb-0"><?= number_format($totalCountFull); ?></h2>
                            </div>
                            <div class="icon-glass d-flex align-items-center justify-content-center">
                                <i class="fas fa-file-signature fs-3 text-white"></i>
                            </div>
                        </div>
                        <p class="mb-0 text-white-75">Date till <?= date('d M Y'); ?></p>
                    </div>
                </div>
                <div class="col-3">
                    <?php
                    $db = db_connect();

                    // Fetch today's successful transactions total and count
                    $queryTotalH = $db->table('loans')
                        ->select('COUNT(id) as total_count')
                        // ->where('DATE(created_at)', $today)
                        ->where('loan_status', 'FI Initiated')
                        ->orWhere('loan_status', 'On Hold')
                        ->orWhere('loan_status', 'Approved')
                        ->orWhere('loan_status', 'Re initiate FI')
                        ->get()
                        ->getRow();

                    $totalCountH  = $queryTotalH->total_count ?? 0;
                    ?>

                    <!-- Glassmorphism Card -->
                    <div class="glass-card glass-card-orange my-5 p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="fw-bold text-uppercase text-white-75 mb-1">Total Applications on Hold</h6>
                                <h2 class="fw-bold text-white mb-0"><?= number_format($totalCountH); ?></h2>
                            </div>
                            <div class="icon-glass d-flex align-items-center justify-content-center">
                                <i class="fas fa-exclamation fs-3"></i>
                            </div>
                        </div>
                        <p class="mb-0 text-white-75">Date till <?= date('d M Y'); ?></p>
                    </div>
                </div>
                <div class="col-3">
                    <?php
                    $db = db_connect();

                    // Fetch today's successful transactions total and count
                    $queryTotalR = $db->table('loans')
                        ->select('COUNT(id) as total_count')
                        // ->where('DATE(created_at)', $today)
                        ->where('loan_status', 'Rejected')
                        ->get()
                        ->getRow();

                    $totalCountR  = $queryTotalR->total_count ?? 0;
                    ?>

                    <!-- Glassmorphism Card -->
                    <div class="glass-card glass-card-red my-5 p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="fw-bold text-uppercase text-white-75 mb-1">Total Loan Rejected</h6>
                                <h2 class="fw-bold text-white mb-0"><?= number_format($totalCountR); ?></h2>
                            </div>
                            <div class="icon-glass d-flex align-items-center justify-content-center">
                                <i class="fas fa-times-circle fs-3"></i>
                            </div>
                        </div>
                        <p class="mb-0 text-white-75">Date till <?= date('d M Y'); ?></p>
                    </div>
                </div>
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
                                    <th>Market Name</th>
                                    <th>Business Name</th>
                                    <th>Owner Name</th>
                                    <th>Members Id</th>
                                    <th>Loan Amount</th>
                                    <th>Loan Tenure</th>
                                    <th>Pending EMI</th>
                                    <th>Loan Type</th>
                                    <th>Loan Status</th>
                                    <th>Application ID</th>
                                    <th>Employee Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $db = db_connect();
                                $builder = $db->table('loans');
                                $builder->select('*');
                                $builder->join('members', 'members.member_id = loans.member_id');
                                $builder->where('members.remarks', 'Ok');

                                $query = $builder->get();
                                foreach ($query->getResult() as $row) {
                                    $e_id =  $row->employee_id;
                                    $builder_name = $db->table('employees');
                                    $builder_name->where('employeeID', $e_id);
                                    $query_name = $builder_name->get();
                                    foreach ($query_name->getResult() as $row_name) {
                                ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $row->groupName; ?></td>

                                            <td><?php echo $row->businessName; ?></td>
                                            <td><?php echo $row->name; ?></td>
                                            <td><?php echo $row->member_id; ?></td>
                                            <td><?php echo $row->loan_amount; ?></td>
                                            <td><?php echo $row->loan_tenure; ?></td>
                                            <td><?php echo $row->pending_emi; ?></td>
                                            <td><?php echo $row->loan_type; ?></td>
                                            <td>
                                                <?php
                                                $status = $row->loan_status;
                                                $color = "black"; // default

                                                if ($status == "Rejected") {
                                                    $color = "red";
                                                } elseif ($status == "On Hold" || $status == "FI Initiated" || $status == "Re initiate FI") {
                                                    $color = "orange";
                                                } elseif ($status == "Completed") {
                                                    $color = "blue";
                                                } elseif ($status == "Disbursed") {
                                                    $color = "green"; // Active
                                                }
                                                ?>
                                                <span style="color: <?= $color ?>; font-weight:bold;">
                                                    <?= $status ?>
                                                </span>
                                            </td>
                                            <td><?php echo $row->applicationID; ?></td>
                                            <td><?php echo $row_name->name; ?></td>

                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-primary  view" id="<?php echo $row->applicationID; ?>">
                                                        Edit <i class="align-middle" data-feather="edit"></i>
                                                    </button>
                                                    <?php
                                                    if ($row->loan_status === "Disbursed") { ?>
                                                        <button type="button" class="btn btn-danger details" id="<?php echo $row->applicationID; ?>">
                                                            TXN <i class="align-middle" data-feather="file-text"></i>
                                                        </button>
                                                    <?php } ?>
                                                    <a href="<?php echo base_url() . 'retailers/details/' . $row->member_id;
                                                                ?>" class="btn btn-success details" id="">
                                                        <!-- <i class="fas fa-eye"></i> -->
                                                        View <i class="align-middle" data-feather="user"></i>
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
                                    <th>Market Name</th>
                                    <th>Business Name</th>
                                    <th>Owner Name</th>
                                    <th>Members Id</th>
                                    <th>Loan Amount</th>
                                    <th>Loan Tenure</th>
                                    <th>Pending EMI</th>
                                    <th>Loan Type</th>
                                    <th>Loan Status</th>
                                    <th>Application ID</th>
                                    <th>Employee Name</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="modal fade" id="dataModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Application Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="application_detail">

                            </div>

                        </div>
                    </div>
                </div>

                <!-- Loan Details model -->
                <div class="modal fade" id="dataModalLoan">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Loan Details of : <strong></strong></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="loan_detail">

                            </div>

                        </div>
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
<script type="text/javascript">
    $(document).ready(function() {
        $("#branch").on("click", ".view", function() {
            var appli_id = $(this).attr("id");

            $.ajax({
                url: "<?php echo base_url(); ?>application-view",
                method: "POST",
                data: {
                    appli_id: appli_id
                },
                // beforeSend: function() {
                //     $('#dataModal').modal("show");
                // },
                success: function(data) {
                    $('#application_detail').html(data);
                    $('#dataModal').modal("show");

                    console.log(appli_id);
                    return false;
                }
            });

        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#branch").on("click", ".details", function() {
            var appli_id = $(this).attr("id");

            $.ajax({
                url: "<?php echo base_url(); ?>loan-details-view",
                method: "POST",
                data: {
                    appli_id: appli_id
                },
                // beforeSend: function() {
                //     $('#dataModal').modal("show");
                // },
                success: function(data) {
                    $('#loan_detail').html(data);
                    $('#dataModalLoan').modal("show");

                    console.log(appli_id);
                    return false;
                }
            });

        });
    });
</script>
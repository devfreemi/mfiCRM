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
                                    <th>Market ID</th>
                                    <th>Business Name</th>
                                    <th>Owner Name</th>
                                    <th>Members Id</th>
                                    <th>Loan Amount</th>
                                    <th>Loan Tenure</th>
                                    <th>Pending EMI</th>
                                    <th>Loan Type</th>
                                    <th>Loan Status</th>
                                    <th>Application ID</th>
                                    <th>Employee ID</th>
                                    <th>Employee Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
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
                                            <td><?php echo $row->id; ?></td>
                                            <td><?php echo $row->groupName; ?></td>
                                            <td><?php echo $row->groupId; ?></td>
                                            <td><?php echo $row->businessName; ?></td>
                                            <td><?php echo $row->name; ?></td>
                                            <td><?php echo $row->member_id; ?></td>
                                            <td><?php echo $row->loan_amount; ?></td>
                                            <td><?php echo $row->loan_tenure; ?></td>
                                            <td><?php echo $row->pending_emi; ?></td>
                                            <td><?php echo $row->loan_type; ?></td>
                                            <td><?php echo $row->loan_status; ?></td>
                                            <td><?php echo $row->applicationID; ?></td>
                                            <td><?php echo $row->employee_id; ?></td>
                                            <td><?php echo $row_name->name; ?></td>

                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-primary  view" id="<?php echo $row->applicationID; ?>">
                                                        <i class="align-middle" data-feather="edit"></i>
                                                    </button>
                                                    <?php
                                                    if ($row->loan_status === "Disbursed") { ?>
                                                        <button type="button" class="btn btn-danger details" id="<?php echo $row->applicationID; ?>">
                                                            <i class="align-middle" data-feather="file-text"></i>
                                                        </button>
                                                    <?php } ?>
                                                    <a href="<?php echo base_url() . 'retailers/details/' . $row->member_id;
                                                                ?>" class="btn btn-success details" id="">
                                                        <!-- <i class="fas fa-eye"></i> -->
                                                        <i class="align-middle" data-feather="user"></i>
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
                                    <th>Market ID</th>
                                    <th>Business Name</th>
                                    <th>Owner Name</th>
                                    <th>Members Id</th>
                                    <th>Loan Amount</th>
                                    <th>Loan Tenure</th>
                                    <th>Pending EMI</th>
                                    <th>Loan Type</th>
                                    <th>Loan Status</th>
                                    <th>Application ID</th>
                                    <th>Employee ID</th>
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
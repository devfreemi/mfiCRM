<?php include 'fragments/head.php'; ?>
<?php include 'fragments/sidebar.php'; ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/css/foundation.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/2.0.8/css/dataTables.foundation.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/js/foundation.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.foundation.js"></script>

<div class="main">

    <?php include 'fragments/nav.php'; ?>
    <main class="content">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3">List of <strong>Applications</strong></h1>

            <div class="row">

                <div class="col-xl-12 col-xxl-12 d-flex">
                    <div class="w-100">
                        <table id="branch" class="display hover">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Group Name</th>
                                    <th>Group ID</th>
                                    <th>Members Name</th>
                                    <th>Members Id</th>
                                    <th>Loan Amount</th>
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
                                $query = $builder->get();
                                foreach ($query->getResult() as $row) {
                                ?>
                                    <tr>
                                        <td><?php echo $row->id; ?></td>
                                        <td><?php echo $row->groupName; ?></td>
                                        <td><?php echo $row->groupId; ?></td>
                                        <td><?php echo $row->name; ?></td>
                                        <td><?php echo $row->member_id; ?></td>
                                        <td><?php echo $row->loan_amount; ?></td>
                                        <td><?php echo $row->loan_type; ?></td>
                                        <td><?php echo $row->loan_status; ?></td>
                                        <td><?php echo $row->applicationID; ?></td>
                                        <td><?php echo $row->employee_id; ?></td>
                                        <td><?php echo "E Name"; ?></td>

                                        <td>
                                            <button type="button" class="btn btn-primary view" id="<?php echo $row->applicationID; ?>">
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                <?php } ?>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Id</th>
                                    <th>Group Name</th>
                                    <th>Group ID</th>
                                    <th>Members Name</th>
                                    <th>Members Id</th>
                                    <th>Loan Amount</th>
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
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
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
        $('.view').click(function(e) {
            e.preventDefault();
            e.stopPropagation();
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
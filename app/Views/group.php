<?php include 'fragments/head.php'; ?>
<?php include 'fragments/sidebar.php'; ?>


<link href="https://cdn.datatables.net/2.1.0/css/dataTables.bootstrap5.css" rel="stylesheet">
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.0/js/dataTables.bootstrap5.js"></script>

<div class="main">

    <?php include 'fragments/nav.php'; ?>
    <main class="content">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3">List of <strong>Groups</strong></h1>

            <div class="row">

                <div class="col-xl-12 col-xxl-12 d-flex">
                    <div class="w-100">
                        <table id="branch" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Group Id</th>
                                    <th>Group Name</th>
                                    <th>Branch</th>
                                    <th>Location</th>
                                    <th>Pincode</th>
                                    <th>Group Type</th>
                                    <th>Agent</th>
                                    <th>Start date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $db = db_connect();
                                $builder = $db->table('groups');
                                $query = $builder->get();
                                foreach ($query->getResult() as $row) {
                                    $e_id =  $row->agent;
                                    $builder_name = $db->table('employees');
                                    $builder_name->where('employeeID', $e_id);
                                    $query_name = $builder_name->get();
                                    foreach ($query_name->getResult() as $row_name) {
                                ?>
                                        <tr>
                                            <td><?php echo $row->id; ?></td>
                                            <td><?php echo $row->g_id; ?></td>
                                            <td><?php echo $row->g_name; ?></td>
                                            <?php $builderB = $db->table('branches');
                                            $builderB->select('*');
                                            $builderB->where('id', $row->branch);
                                            $queryB = $builderB->get();
                                            foreach ($queryB->getResult() as $rowB) {
                                            ?>
                                                <td><?php echo $rowB->b_name; ?></td>
                                            <?php } ?>
                                            <td><?php echo $row->location; ?></td>
                                            <td><?php echo $row->pincode; ?></td>
                                            <td><?php echo $row->group_type; ?></td>
                                            <td><?php echo $row_name->name; ?></td>
                                            <td><?php echo $row->created_at; ?></td>
                                            <td>
                                                <button type="button" class="btn btn-primary view" id="<?php echo $row->g_id; ?>">
                                                    View
                                                </button>
                                            </td>
                                        </tr>
                                <?php }
                                } ?>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Id</th>
                                    <th>Group Id</th>
                                    <th>Group Name</th>
                                    <th>Branch</th>
                                    <th>Location</th>
                                    <th>Pincode</th>
                                    <th>Group Type</th>
                                    <th>Agent</th>
                                    <th>Start date</th>
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
                                <h5 class="modal-title" id="exampleModalLabel">Group Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="application_detail">

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
<script type="text/javascript">
    $(document).ready(function() {
        $("#branch").on("click", ".view", function() {
            var g_id = $(this).attr("id");

            $.ajax({
                url: "<?php echo base_url(); ?>group-view",
                method: "POST",
                data: {
                    g_id: g_id
                },
                // beforeSend: function() {
                //     $('#dataModal').modal("show");
                // },
                success: function(data) {
                    $('#application_detail').html(data);
                    $('#dataModal').modal("show");

                    console.log(g_id);
                    return false;
                }
            });

        });
    });
</script>
<script>
    new DataTable('#branch');
</script>
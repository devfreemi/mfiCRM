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

            <h1 class="h3 mb-3">List of <strong>Groups</strong></h1>

            <div class="row">

                <div class="col-xl-12 col-xxl-12 d-flex">
                    <div class="w-100">
                        <table id="branch" class="display hover">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Group Id</th>
                                    <th>Group Name</th>
                                    <th>Branch</th>
                                    <th>Location</th>
                                    <th>Pincode</th>
                                    <th>Group Type</th>
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
                                ?>
                                    <tr>
                                        <td><?php echo $row->id; ?></td>
                                        <td><?php echo $row->g_id; ?></td>
                                        <td><?php echo $row->g_name; ?></td>
                                        <td><?php echo $row->branch; ?></td>
                                        <td><?php echo $row->location; ?></td>
                                        <td><?php echo $row->pincode; ?></td>
                                        <td><?php echo $row->group_type; ?></td>
                                        <td><?php echo $row->created_at; ?></td>
                                        <td>
                                            <button type="button" class="btn btn-primary rounded">View</button>
                                        </td>
                                    </tr>
                                <?php } ?>

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
                                    <th>Start date</th>
                                    <th>Action</th>
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
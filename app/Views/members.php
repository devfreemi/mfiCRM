<?php include 'fragments/head.php'; ?>
<?php include 'fragments/sidebar.php'; ?>
<link href="https://cdn.datatables.net/2.1.0/css/dataTables.bootstrap5.css" rel="stylesheet">
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.0/js/dataTables.bootstrap5.js"></script>

<div class="main">

    <?php include 'fragments/nav.php'; ?>
    <main class="content">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3">List of <strong>Members</strong></h1>

            <div class="row">

                <div class="col-xl-12 col-xxl-12 d-flex">
                    <div class="w-100">
                        <table id="branch" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Members Id</th>
                                    <th>Group Name</th>
                                    <th>Group ID</th>
                                    <th>Mobile</th>
                                    <th>PAN Number</th>
                                    <th>Aadhaar Number</th>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>Pincode</th>
                                    <th>Gender</th>
                                    <th>Occupation</th>
                                    <th>Marital Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $db = db_connect();
                                $builder = $db->table('members');
                                $query = $builder->get();
                                foreach ($query->getResult() as $row) {
                                ?>
                                    <tr>
                                        <td><?php echo $row->id; ?></td>
                                        <td><?php echo $row->member_id; ?></td>
                                        <td><?php echo $row->groupName; ?></td>
                                        <td><?php echo $row->groupId; ?></td>
                                        <td><?php echo $row->mobile; ?></td>
                                        <td><?php echo $row->pan; ?></td>
                                        <td><?php echo $row->adhar; ?></td>
                                        <td><?php echo $row->name; ?></td>
                                        <td><?php echo $row->location; ?></td>
                                        <td><?php echo $row->pincode; ?></td>
                                        <td><?php echo $row->gender; ?></td>
                                        <td><?php echo $row->occupation; ?></td>
                                        <td><?php echo $row->marital; ?></td>
                                        <td>
                                            <button type="button" class="btn btn-primary rounded">View</button>
                                        </td>
                                    </tr>
                                <?php } ?>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Id</th>
                                    <th>Members Id</th>
                                    <th>Group Name</th>
                                    <th>Group ID</th>
                                    <th>Mobile</th>
                                    <th>PAN Number</th>
                                    <th>Aadhaar Number</th>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>Pincode</th>
                                    <th>Gender</th>
                                    <th>Occupation</th>
                                    <th>Marital Status</th>
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
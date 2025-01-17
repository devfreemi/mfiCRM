<?php include 'fragments/head.php'; ?>
<?php include 'fragments/sidebar.php'; ?>
<link href="https://cdn.datatables.net/2.1.0/css/dataTables.bootstrap5.css" rel="stylesheet">
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.0/js/dataTables.bootstrap5.js"></script>

<div class="main">

    <?php include 'fragments/nav.php'; ?>
    <main class="content">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3"><strong>Employees'</strong> Attendence</h1>

            <div class="row">
                <div class="col-xl-12 col-xxl-12 d-flex">
                    <div class="w-100">
                        <table id="branch" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Employee</th>
                                    <th>Status</th>
                                    <th>Sign In Date</th>
                                    <th>Sign In Time</th>
                                    <th>Sign Out Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $db = db_connect();
                                $builder = $db->table('agentattendences');
                                $query = $builder->join('employees', 'employees.employeeID = agentattendences.agent_id')->get();
                                foreach ($query->getResult() as $row) {
                                ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $row->name; ?></td>
                                        <td>Active</td>
                                        <td><?php echo $row->date; ?></td>
                                        <td><?php echo $row->sign_in_time; ?></td>
                                        <td><?php echo $row->sign_out_time; ?></td>
                                    </tr>
                                <?php } ?>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Sl</th>
                                    <th>Employee</th>
                                    <th>Status</th>
                                    <th>Sign In Date</th>
                                    <th>Sign In Time</th>
                                    <th>Sign Out Time</th>
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
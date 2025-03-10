<?php include 'fragments/head.php'; ?>
<?php include 'fragments/sidebar.php'; ?>
<link href="https://cdn.datatables.net/2.1.0/css/dataTables.bootstrap5.css" rel="stylesheet">
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.0/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.html5.min.js"></script>


<div class="main">

    <?php include 'fragments/nav.php'; ?>
    <main class="content">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3"><strong>Employees'</strong> Geo Tags</h1>

            <div class="row">
                <div class="col-xl-12 col-xxl-12 d-flex">
                    <div class="w-100">
                        <table id="branch" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Agent</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Location</th>
                                    <th>City</th>
                                    <th>Pincode</th>
                                    <th>Reference</th>
                                    <th>Status</th>
                                    <th>Sign In Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $db = db_connect();
                                $builder = $db->table('geotags')->orderBy('date', 'DESC');
                                $query = $builder->get();
                                foreach ($query->getResult() as $row) {
                                ?>
                                    <tr>
                                        <td><?php echo $row->id; ?></td>
                                        <td><?php echo $row->agent; ?></td>
                                        <td><?php echo $row->latitude; ?></td>
                                        <td><?php echo $row->longitude; ?></td>
                                        <td><?php echo $row->location; ?></td>
                                        <td><?php echo $row->city; ?></td>
                                        <td><?php echo $row->pincode; ?></td>
                                        <td>
                                            <a href="<?php echo $row->reference; ?>" target="_blank" rel="noopener noreferrer">
                                                <i class="far fa-eye"></i>
                                            </a>
                                        </td>
                                        <td><?php echo $row->status; ?></td>

                                        <td><?php echo $row->date; ?></td>

                                    </tr>
                                <?php } ?>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Id</th>
                                    <th>Agent</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Location</th>
                                    <th>City</th>
                                    <th>Pincode</th>
                                    <th>Reference</th>
                                    <th>Status</th>
                                    <th>Sign In Date</th>
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
    $('#branch').DataTable({
        responsive: true,
        layout: {
            topStart: {
                buttons: ['excelHtml5']
            }
        }
    });
</script>
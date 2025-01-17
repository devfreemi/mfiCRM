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

            <h1 class="h3 mb-3"><strong>Employee</strong> Details</h1>

            <div class="row">
                <div class="col-xl-12 col-xxl-12 d-flex flex-row-reverse my-5">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">Add New Employee</button>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Enter Employee Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="<?= base_url() ?>add-employee" method="post">
                                <div class="modal-body">
                                    <?= csrf_field('auth') ?>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Employee Id</label>
                                        <input type="text" lass="form-control" id="employeeID" name="employeeID" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Employee Name</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mobile" class="form-label">Mobile Number</label>
                                        <input type="tel" maxlength="10" class="form-control" id="mobile" name="mobile" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Id</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="desig" class="form-label">Employee Designation</label>
                                        <select class="form-select" required name="designation">
                                            <option selected value="" disabled>Select Designation</option>
                                            <option value="Back Office">Back Office</option>
                                            <option value="Field Agent">Field Agent</option>
                                            <option value="Relationship Manager">Relationship Manager</option>
                                            <!-- <option value="3">Three</option> -->
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 col-xxl-12 d-flex">
                    <div class="w-100">
                        <table id="example" class="display hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Employee ID</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Start date</th>
                                    <th>Position</th>
                                    <th>Activity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $db = db_connect();
                                $builder = $db->table('employees');
                                $query = $builder->get();
                                foreach ($query->getResult() as $row) {
                                ?>
                                    <tr>
                                        <td><?php echo $row->name; ?></td>
                                        <td><?php echo $row->employeeID; ?></td>
                                        <td><?php echo $row->mobile; ?></td>
                                        <td><?php echo $row->email; ?></td>
                                        <td><?php echo $row->created_at; ?></td>
                                        <td><?php echo $row->designation; ?></td>
                                        <td>Active</td>
                                    </tr>
                                <?php } ?>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Name</th>
                                    <th>Employee ID</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Start date</th>
                                    <th>Position</th>
                                    <th>Activity</th>
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
    new DataTable('#example');
</script>
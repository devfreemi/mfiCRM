<?php include 'fragments/head.php'; ?>
<?php include 'fragments/sidebar.php'; ?>
<link href="https://cdn.datatables.net/2.1.0/css/dataTables.bootstrap5.css" rel="stylesheet">
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.0/js/dataTables.bootstrap5.js"></script>

<div class="main">

    <?php include 'fragments/nav.php'; ?>
    <main class="content">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3"><strong>Company</strong> Bank Details</h1>
            <a href="losretailpe://check">Open MyApp</a>
            <div class="row">
                <?php if (session()->getFlashdata('msg')) : ?>
                    <div class="col-xl-12 col-xxl-12 my-5">
                        <p class="text-center text-danger"><?= session()->getFlashdata('msg') ?></p>
                    </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="col-xl-12 col-xxl-12 my-5">
                        <p class="text-center text-success fw-bold"><?= session()->getFlashdata('success') ?></p>
                    </div>
                <?php endif; ?>
                <div class="col-xl-12 col-xxl-12 d-flex flex-row-reverse my-5">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Bank</button>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Enter Bank Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <form class="row g-3" action="<?= base_url() ?>add-bank" method="post">
                                    <?= csrf_field('auth') ?>
                                    <div class="mb-3 col-6">
                                        <label for="name" class="form-label">Account Number</label>
                                        <input type="text" class="form-control" id="acc_no" name="acc_no" required>
                                    </div>

                                    <div class="mb-3 col-6">
                                        <label for="location" class="form-label">IFSC Code</label>
                                        <input type="text" class="form-control" id="ifsc" name="ifsc" maxlength="11" style="text-transform: uppercase;" required>
                                        <small id="invalidifsc" class="text-danger"></small>
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label for="pin" class="form-label">Bank Name</label>
                                        <input type="text" class="form-control" id="bankName" name="bankName" required>
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label for="pin" class="form-label">Branch</label>
                                        <input type="text" class="form-control" id="branch" name="branch" readonly required>
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label for="pin" class="form-label">City</label>
                                        <input type="text" class="form-control" id="bankCity" name="bankCity" readonly required>
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label for="pin" class="form-label">Address</label>
                                        <input type="text" class="form-control" id="bankAddress" name="bankAddress" readonly required>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="col-xl-12 col-xxl-12 d-flex">
                    <div class="w-100">
                        <table id="bank" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Bank Name</th>
                                    <th>Account Number</th>
                                    <th>IFSC Code</th>
                                    <th>Branch</th>
                                    <th>City</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $db = db_connect();
                                $builder = $db->table('banks');
                                $query = $builder->get();
                                foreach ($query->getResult() as $row) {
                                ?>
                                    <tr>
                                        <td><?php echo $row->id; ?></td>
                                        <td><?php echo $row->bankName; ?></td>
                                        <td><?php echo $row->acc_no; ?></td>
                                        <td><?php echo $row->ifsc; ?></td>
                                        <td><?php echo $row->branch; ?></td>
                                        <td><?php echo $row->bankCity; ?></td>
                                    </tr>
                                <?php } ?>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Id</th>
                                    <th>Bank Name</th>
                                    <th>Account Number</th>
                                    <th>IFSC Code</th>
                                    <th>Branch</th>
                                    <th>City</th>
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
    $(document).ready(function() {
        $("#ifsc").keyup(function() {
            var regex = new RegExp('^[a-zA-Z]{4}[0][0-9a-zA-Z]{6}$');
            //		console.log("IFSC search");
            var ifsc = $("#ifsc").val();

            if (regex.test(ifsc)) {
                $("#ifsc").css('border-left', '2px solid #43c253');
                $.get("https://ifsc.razorpay.com/" + ifsc, function(data, status) {
                        console.log(data.BANK);
                        //console.log(data.BRANCH);
                        //console.log(data.ADDRESS);

                        $("#bankCity").val(data.CITY);
                        $("#branch").val(data.BRANCH);
                        $("#bankAddress").val(data.ADDRESS);
                        $("#bankName").val(data.BANK);
                        $("#bankState").val(data.STATE);
                        $("#invalidifsc").text("");
                    })
                    .fail(function(data, status) {
                        //console.log("Error retrieving data");
                        //console.log(data);
                        //				console.log(status);
                        $("#ifsc").css('border-left', '2px solid #ff6a6a');
                        $("#invalidifsc").text("Invalid IFSC code");
                        $("#bankCity").val("");
                        $("#branch").val("");
                        $("#bankAddress").val("");
                        $("#bankName").val("");
                        $("#bankState").val("");
                    });
            } else {
                $("#ifsc").css('border-left', '2px solid #ff6a6a');
                $("#invalidifsc").text("Invalid IFSC code");
            }

        });
    });
</script>
<script>
    new DataTable('#bank');
</script>
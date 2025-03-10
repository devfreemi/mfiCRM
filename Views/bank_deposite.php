<?php include 'fragments/head.php'; ?>
<?php include 'fragments/sidebar.php'; ?>
<link href="https://cdn.datatables.net/2.1.0/css/dataTables.bootstrap5.css" rel="stylesheet">
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.0/js/dataTables.bootstrap5.js"></script>

<div class="main">

    <?php include 'fragments/nav.php'; ?>
    <main class="content">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-5"><strong>Bank Deposite</strong> Details</h1>
            <div class="row">
                <div class="col-xl-12 col-xxl-12 d-flex">
                    <div class="w-100">
                        <table id="bank" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Bank Name</th>
                                    <th>Account Number</th>
                                    <th>Deposited Amount</th>
                                    <th>Receipt</th>
                                    <th>Deposited Date</th>
                                    <th>Agent</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $db = db_connect();
                                $builder = $db->table('bank_deposites_master');
                                $builder->join('employees', 'employees.employeeID = bank_deposites_master.agent');
                                $builder->join('banks', 'banks.acc_no = bank_deposites_master.bank_name');
                                $query = $builder->get();
                                foreach ($query->getResult() as $row) {
                                ?>
                                    <tr>
                                        <td><?php echo $row->id; ?></td>
                                        <td><?php echo $row->bankName; ?></td>
                                        <td><?php echo $row->bank_name; ?></td>
                                        <td><?php echo $row->deposited_amount; ?></td>
                                        <td>
                                            <a type="button" href="<?php echo $row->receipt_url; ?>" target="_blank" class="btn btn-success btn-sm">
                                                View Receipt <i class="align-middle" data-feather="eye"></i>
                                            </a>
                                        </td>
                                        <td><?php echo $row->deposited_date; ?></td>
                                        <td><?php echo $row->name; ?> (<?php echo $row->agent; ?>)</td>
                                    </tr>
                                <?php } ?>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Id</th>
                                    <th>Bank Name</th>
                                    <th>Account Number</th>
                                    <th>Deposited Amount</th>
                                    <th>Receipt</th>
                                    <th>Deposited Date</th>
                                    <th>Agent</th>
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
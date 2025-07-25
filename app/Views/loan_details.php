<?php if (isset($_POST["appli_id"])) {
    $apli_id = $_POST["appli_id"];
?>

    <table class="table table-hover">
        <thead>
            <tr class="table-primary">

                <th>Installment No.</th>
                <th>EMI Date</th>
                <th>EMI</th>
                <!-- <th>Loan Balance</th> -->
                <th>Status</th>
                <th>Transaction Id</th>
                <th>Transaction Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $db = db_connect();
            $builderLoan = $db->table('tab_' . $_POST["appli_id"]);
            $builderLoan->select('*');
            // $builderLoan->where('reference', 'Due');
            // $builderLoan->orWhere('reference', 'Y');
            $builderLoan->orderBy('Id', 'ASC');
            $queryLoan = $builderLoan->get();
            foreach ($queryLoan->getResult() as $rowLoan) {
            ?>
                <tr>

                    <td><?php echo $rowLoan->Id; ?></td>
                    <td><?php echo $rowLoan->valueDateStamp; ?></td>
                    <td><?php echo number_format($rowLoan->emi); ?></td>
                    <td>
                        <?php
                        if ($rowLoan->reference === "Y") {
                            # code...
                            echo "<strong class='text-success'>Payment Success</strong>";
                        } else {
                            # code...
                            echo "<strong class='text-danger'>" . $rowLoan->reference . "</strong>";
                        }
                        ?>
                    </td>
                    <td><?php echo $rowLoan->transactionId; ?></td>
                    <td><?php echo $rowLoan->transactionDate; ?></td>
                    <td class="text-center">
                        <?php
                        if ($rowLoan->reference === "Y") {
                            # code...
                            echo "<strong class='text-success'>EMI Paid</strong>";
                        } else { ?>
                            <!-- <a href="<?php echo base_url() ?>payment/details?id=<?php echo $_POST["appli_id"]; ?>" class="btn btn-primary view">
                                Pay <i class="far fa-credit-card"></i>
                            </a> -->
                            <strong class='text-warning'>EMI Pending</strong>
                        <?php }
                        ?>

                        <!-- <a href="<?php //echo base_url() . 'retailers/details/' . $row->member_id;
                                        ?>" class="btn btn-success details" id="">
                                <i class="align-middle" data-feather="user"></i>
                            </a> -->

                    </td>
                </tr>
            <?php }
            ?>

        </tbody>
        <tfoot>
            <tr class="table-primary">

                <th>Installment No.</th>
                <th>EMI Date</th>
                <th>EMI</th>
                <!-- <th>Loan Balance</th> -->
                <th>Status</th>
                <th>Transaction Id</th>
                <th>Transaction Date</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>
    <script>
        $('#payData').click(function() {
            var amount = <?php echo number_format($rowLoan->emi); ?>;
            var appli_id = "<?php echo $apli_id; ?>";

            $.ajax({
                url: "<?php echo base_url(); ?>emi/payment", // Route to your CI4 controller
                type: "POST",
                data: {
                    amount: amount,
                    loan_id: appli_id
                },
                // dataType: "json",
                success: function(response) {
                    console.log("Response from server: " + response);
                    $('#responseContainer').html(response);
                },
                error: function(xhr) {
                    console.error("Error:", xhr.responseText);
                }
            });
        });
    </script>

<?php
}
?>
<?php if (isset($_POST["appli_id"])) {

?>
    <table class="table table-hover">
        <thead>
            <tr class="table-primary">
                <th>Loan Id</th>
                <th>Installment No.</th>
                <th>EMI Date</th>
                <th>EMI</th>
                <th>Loan Balance</th>
                <th>Status</th>
                <th>Transaction Id</th>
                <th>Transaction Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $db = db_connect();
            $builderLoan = $db->table('tab_' . $_POST["appli_id"]);
            $builderLoan->select('*');
            $builderLoan->where('reference', 'Due');
            $builderLoan->orWhere('reference', 'Y');
            $builderLoan->orderBy('Id', 'DESC');
            $queryLoan = $builderLoan->get();
            foreach ($queryLoan->getResult() as $rowLoan) {
            ?>
                <tr>
                    <td><?php echo $_POST["appli_id"]; ?></td>
                    <td><?php echo $rowLoan->Id; ?></td>
                    <td><?php echo $rowLoan->valueDateStamp; ?></td>
                    <td><?php echo number_format($rowLoan->emi); ?></td>
                    <td><?php echo number_format($rowLoan->balance); ?></td>
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
                </tr>
            <?php }
            ?>

        </tbody>
        <tfoot>
            <tr class="table-primary">
                <th>Loan Id</th>
                <th>Installment No.</th>
                <th>EMI Date</th>
                <th>EMI</th>
                <th>Loan Balance</th>
                <th>Status</th>
                <th>Transaction Id</th>
                <th>Transaction Date</th>
            </tr>
        </tfoot>
    </table>


<?php
}
?>
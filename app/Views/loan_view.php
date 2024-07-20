<?php if (isset($_POST["appli_id"])) {
    $db = db_connect();
    $builder = $db->table('loans');
    $builder->select('*');
    $builder->join('members', 'members.member_id = loans.member_id');
    $query = $builder->get();
    foreach ($query->getResult() as $row) {
        $e_id =  $row->employee_id;
        $builder_name = $db->table('employees');
        $builder_name->where('employeeID', $e_id);
        $query_name = $builder_name->get();
        foreach ($query_name->getResult() as $row_name) {

?>

            <div class="modal-body" id="application_detail">

                <form class="row g-3">
                    <div class="col-md-4">
                        <label for="loan" class="form-label">Application ID</label>
                        <input type="text" class="form-control" id="applicationid" readonly value="<?php echo $_POST["appli_id"]; ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="name" class="form-label">Member Name</label>
                        <input type="text" class="form-control" id="name" value="<?php echo $row->name; ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="name" class="form-label">Member Gender</label>
                        <input type="text" class="form-control" id="gender" value="<?php echo $row->gender; ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="name" class="form-label">Member Aadhaar No</label>
                        <input type="text" class="form-control" id="adhar" value="<?php echo $row->adhar; ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="name" class="form-label">Member PAN No</label>
                        <input type="text" class="form-control" id="pan" value="<?php echo $row->pan; ?>" readonly>
                    </div>
                    <div class="col-4">
                        <label for="inputAddress" class="form-label">Loan Amount</label>
                        <input type="text" class="form-control" id="lona_amount" value="<?php echo $row->loan_amount; ?>" readonly>
                    </div>
                    <div class=" col-4">
                        <label for="type" class="form-label">Loan Type</label>
                        <input type="text" class="form-control" id="loan_type" value="<?php echo $row->loan_type; ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="inputCity" class="form-label">City</label>
                        <input type="text" class="form-control" id="location" value="<?php echo $row->location; ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="inputCity" class="form-label">Pincode</label>
                        <input type="text" class="form-control" id="pin" value="<?php echo $row->pincode; ?>" readonly>
                    </div>

                    <div class="col-md-6">
                        <label for="inputZip" class="form-label">Agent</label>
                        <input type="text" class="form-control" id="agent" value="<?php echo $row_name->name; ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="inputState" class="form-label">Status</label>
                        <select id="inputState" class="form-select">
                            <option selected disabled>Choose</option>
                            <option value="New Customer" <?php if ($row->loan_status === 'Applied') echo 'selected="selected"'; ?>>Applied</option>
                            <option value="Work Approved" <?php if ($row->loan_status === 'Approved') echo 'selected="selected"'; ?>>Approved</option>
                            <option value="Pending Payment" <?php if ($row->loan_status === 'Disbursed') echo 'selected="selected"'; ?>>Disbursed</option>
                            <option value="Completed" <?php if ($row->loan_status === 'Completed') echo 'selected="selected"'; ?>>Completed</option>
                            <option value="Rejected" <?php if ($row->loan_status === 'Rejected') echo 'selected="selected"'; ?>>Rejected</option>
                        </select>
                    </div>

                </form>
            </div>

<?php }
    }
} ?>
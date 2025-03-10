<?php if (isset($_POST["member_id"])) {
    $db = db_connect();
    $builder = $db->table('members');
    $builder->select('*');
    $builder->where('member_id', $_POST["member_id"]);
    $query = $builder->get();
    foreach ($query->getResult() as $row) {
        $e_id =  $row->agent;
        $builder_name = $db->table('employees');
        $builder_name->where('employeeID', $e_id);
        $query_name = $builder_name->get();
        foreach ($query_name->getResult() as $row_name) {

?>

            <div class="modal-body" id="member_detail">

                <form class="row g-3" action="<?= base_url() ?>update-member" method="post">
                    <?= csrf_field('auth') ?>
                    <div class="col-md-6">
                        <label for="loan" class="form-label">Member ID</label>
                        <input type="text" class="form-control" id="applicationid" name="applicationid" readonly value="<?php echo $_POST["member_id"]; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="loan" class="form-label">Relationship Manager</label>
                        <input type="text" class="form-control" id="applicationid" name="applicationid" readonly value="<?php echo $row_name->name; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="loan" class="form-label">Member Name</label>
                        <input type="text" class="form-control" id="applicationid" name="applicationid" value="<?php echo $row->name; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="loan" class="form-label">Mobile</label>
                        <input type="text" class="form-control" id="applicationid" name="applicationid" value="<?php echo $row->mobile; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="loan" class="form-label">PAN Number</label>
                        <input type="text" class="form-control" id="applicationid" name="applicationid" value="<?php echo $row->pan; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="loan" class="form-label">AAdhaar Number</label>
                        <input type="text" class="form-control" id="applicationid" name="applicationid" value="<?php echo $row->adhar; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="loan" class="form-label">Business Name</label>
                        <input type="text" class="form-control" id="applicationid" name="applicationid" value="<?php echo $row->businessName; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="loan" class="form-label">Business Type</label>
                        <input type="text" class="form-control" id="applicationid" name="applicationid" value="<?php echo $row->businessType; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="loan" class="form-label">Market</label>
                        <input type="text" class="form-control" id="applicationid" name="applicationid" value="<?php echo $row->groupName; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="loan" class="form-label">Business Location</label>
                        <input type="text" class="form-control" id="applicationid" name="applicationid" value="<?php echo $row->location; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="loan" class="form-label">Pincode</label>
                        <input type="text" class="form-control" id="applicationid" name="applicationid" value="<?php echo $row->pincode; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="loan" class="form-label">Current Stock</label>
                        <input type="text" class="form-control" id="applicationid" name="applicationid" value="<?php echo $row->stock; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="loan" class="form-label">Daily Footfall</label>
                        <input type="text" class="form-control" id="applicationid" name="applicationid" value="<?php echo $row->footFall; ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="loan" class="form-label">Business Established Year</label>
                        <input type="text" class="form-control" id="applicationid" name="applicationid" value="<?php echo $row->estab; ?>">
                    </div>
                </form>
            </div>

<?php }
    }
} ?>
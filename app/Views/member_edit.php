<?php if (isset($_POST["member_id"])) {
    $db = db_connect();
    $builder = $db->table('members');
    $builder->select('*');
    $builder->where('member_id', $_POST["member_id"])->join('groups', 'groups.g_id  = members.groupId');
    $query = $builder->get();
    foreach ($query->getResult() as $row) {
        $e_id =  $row->agent;
        $builder_name = $db->table('employees');
        $builder_name->where('employeeID', $e_id);
        $query_name = $builder_name->get();
        foreach ($query_name->getResult() as $row_name) {

?>

            <div class="modal-body" id="member_detail">

                <form class="row g-3" action="<?= base_url() ?>loan/check" method="post">
                    <?= csrf_field('auth') ?>
                    <div class="col-md-4">
                        <label for="loan" class="form-label">Member ID</label>
                        <input type="text" class="form-control bg-model" id="applicationid" name="memberID" readonly value="<?php echo $_POST["member_id"]; ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="loan" class="form-label">Member Name</label>
                        <input type="text" class="form-control bg-model" id="applicationid" name="name" value="<?php echo $row->name; ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="loan" class="form-label">Mobile</label>
                        <input type="text" class="form-control bg-model" id="applicationid" name="mobile" value="<?php echo $row->mobile; ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="loan" class="form-label">PAN Number</label>
                        <input type="text" class="form-control bg-model" id="applicationid" name="pan" value="<?php echo $row->pan; ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="loan" class="form-label">Aadhaar Number</label>
                        <input type="text" class="form-control bg-model" id="applicationid" name="adhar" value="<?php echo $row->adhar; ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="loan" class="form-label">Business Name</label>
                        <input type="text" class="form-control bg-model" id="applicationid" readonly name="applicationid" value="<?php echo $row->businessName; ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="loan" class="form-label">Business Type</label>
                        <input type="text" class="form-control bg-model" id="applicationid" readonly name="business_type" value="<?php echo $row->businessType; ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="loan" class="form-label">Market</label>
                        <input type="text" class="form-control bg-model" id="applicationid" readonly name="applicationid" value="<?php echo $row->groupName; ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="loan" class="form-label">Market Type</label>
                        <input type="text" class="form-control bg-model" id="applicationid" readonly name="location" value="<?php echo $row->group_type; ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="loan" class="form-label">Business Location</label>
                        <input type="text" class="form-control bg-model" id="applicationid" name="memberLocation" value="<?php echo $row->location; ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="loan" class="form-label">Pincode</label>
                        <input type="text" class="form-control bg-model" id="applicationid" name="groupPin" value="<?php echo $row->pincode; ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="loan" class="form-label">Current Stock</label>
                        <input type="text" class="form-control bg-model" required id="applicationid" name="stock" value="<?php echo $row->stock; ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="loan" class="form-label">Daily Sales</label>
                        <input type="text" class="form-control bg-model" required id="applicationid" name="daily_sales" value="<?php echo $row->dailySales; ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="loan" class="form-label">Daily Footfall</label>
                        <input type="text" class="form-control bg-model" id="applicationid" name="footFall" value="<?php echo $row->footFall; ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="loan" class="form-label">Previous EMI</label>
                        <input type="text" class="form-control bg-model" required id="applicationid" value="0" name="previous_emi" value="<?php echo $row->outstanding; ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="loan" class="form-label">Business Established Year</label>
                        <input type="text" class="form-control bg-model " required id="applicationid" name="business_time" value="<?php echo $row->estab; ?>">
                    </div>
                    <input type="hidden" class="form-control bg-model " id="applicationid" name="image_profile" value="<?php echo $row->image; ?>">
                    <div class="col-md-4">
                        <img src="<?php echo $row->image; ?>" alt="shop image" class="input-image">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Check Eligibility</button>
                    </div>
                </form>
            </div>

<?php }
    }
} ?>
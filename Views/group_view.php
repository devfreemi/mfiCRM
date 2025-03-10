<?php if (isset($_POST["g_id"])) {
    $db = db_connect();
    $builder = $db->table('groups');
    $builder->select('*');
    $builder->where('g_id', $_POST["g_id"]);
    $query = $builder->get();
    foreach ($query->getResult() as $row) {

?>

        <div class="col-12">
            <div class="row mx-auto">
                <div class="col-6">
                    <p class="text-dark fw-bolder">Group Name</p>
                </div>
                <div class="col-6">
                    <p class="text-start text-primary fw-bolder"><?php echo $row->g_name; ?></p>
                </div>
                <div class="col-6">
                    <p class="text-dark fw-bolder">Group ID</p>
                </div>
                <div class="col-6">
                    <p class="text-start text-primary fw-bolder"><?php echo $row->g_id; ?></p>
                </div>
                <?php $builderB = $db->table('branches');
                $builderB->select('*');
                $builderB->where('id', $row->branch);
                $queryB = $builderB->get();
                foreach ($queryB->getResult() as $rowB) {
                ?>
                    <div class="col-6">
                        <p class="text-dark fw-bolder">Branch</p>
                    </div>
                    <div class="col-6">

                        <p class="text-start text-primary fw-bolder"><?php echo $rowB->b_name; ?></p>

                    </div>
                    <div class="col-6">
                        <p class="text-dark fw-bolder">Location</p>
                    </div>
                    <div class="col-6">
                        <p class="text-start text-primary fw-bolder"><?php echo $rowB->location; ?></p>
                    </div>
                    <div class="col-6">
                        <p class="text-dark fw-bolder">Pincode</p>
                    </div>
                    <div class="col-6">
                        <p class="text-start text-primary fw-bolder"><?php echo $rowB->pincode; ?></p>
                    </div>
                <?php } ?>
                <div class="col-6">
                    <p class="text-dark fw-bolder">Total Members</p>
                </div>
                <div class="col-6">
                    <?php $builderM = $db->table('members');
                    $builderM->where('groupId', $_POST["g_id"]);
                    $queryM = $builderM->countAllResults();
                    if ($queryM > 0) {
                        # code...
                        $bg = "bg-success";
                    } else {
                        # code...
                        $bg = "bg-danger";
                    }

                    ?>
                    <p class="badge <?php echo $bg; ?> text-wrap fw-bold"><?php echo $queryM; ?></p>

                </div>
                <div class="col-6">
                    <p class="text-dark fw-bolder">Created By</p>
                </div>
                <div class="col-6">
                    <?php
                    $e_id =  $row->agent;
                    $builder_name = $db->table('employees');
                    $builder_name->where('employeeID', $e_id);
                    $query_name = $builder_name->get();
                    foreach ($query_name->getResult() as $row_name) {
                    ?>
                        <p class="text-start text-primary fw-bolder"><?php echo $row_name->name; ?></p>
                    <?php } ?>
                </div>
            </div>
        </div>

<?php }
} ?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="<?php echo base_url(); ?>/assets/img//" data-template="vertical-menu-template-free">

<head>
    <?php include 'fragments/head.php'; ?>
</head>
<?php
$uri = service('uri');
//
$uniq_id = $uri->getSegment(2);

?>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php include 'fragments/sidebar.php'; ?>
            <!-- Layout container -->
            <div class="layout-page">
                <?php include 'fragments/nav.php' ?>
                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">
                        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Service Derails /</span> Service</h4>
                        <?php
                        $db = db_connect();
                        $builder = $db->table('customerDetails');
                        $builder->select('*');
                        $builder->join('servicesDetails', 'servicesDetails.customer_id = customerDetails.uniqid');
                        $builder->where('servicesDetails.uniqid', $uniq_id);
                        $query = $builder->get();
                        foreach ($query->getResult() as $row) {
                            $product_id = $row->product_id;
                            $builder_name = $db->table('product');
                            $builder_name->where('id', $product_id);
                            $query_name = $builder_name->get();
                            foreach ($query_name->getResult() as $row_name) {
                        ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card mb-4">
                                            <h4 class="card-header">Profile Details </h4>
                                            <!-- Account -->
                                            <div class="card-body">
                                                <div class="d-flex align-items-start align-items-sm-center gap-4">
                                                    <img src="<?php echo $row->photo; ?>" alt="user-avatar" class="d-block w-px-120 h-px-120 rounded" id="uploadedAvatar" />
                                                    <div class="button-wrapper">
                                                        <h3 class="fw-bold fs-3"><?php echo $row->name; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body pt-2 mt-1">
                                                <form id="formAccountSettings" action="<?php echo base_url() ?>tax/insert-data" method="POST">
                                                    <div class="row mt-2 gy-4">
                                                        <div class="col-md-6">
                                                            <div class="form-floating form-floating-outline">
                                                                <input class="form-control" type="text" id="email" name="email" value="<?php echo $row->email; ?>" readonly />
                                                                <label for="email">E-mail</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-floating form-floating-outline">
                                                                <input type="text" class="form-control" id="organization" name="organization" value="<?php echo $row_name->productName; ?>" readonly />
                                                                <label for="organization">Service Applied by Customer</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text">IND (+91)</span>
                                                                <div class="form-floating form-floating-outline">
                                                                    <input type="number" id="phoneNumber" name="phoneNumber" maxlength='10' class="form-control" placeholder="9051652308" />
                                                                    <label for="phoneNumber">Phone Number</label>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-floating form-floating-outline">
                                                                <input type="text" class="form-control" id="pan" name="pan" placeholder="Address" readonly value="<?php echo $row->customer_pan; ?>" />
                                                                <label for="address">PAN Number</label>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        if ($product_id == 1) {
                                                            # code... 
                                                        ?>
                                                            <div class="col-md-6">
                                                                <div class="form-floating form-floating-outline">
                                                                    <input type="text" class="form-control" id="employment" name="employment" readonly value="<?php
                                                                                                                                                                if ($row->employment_type == "SE") {
                                                                                                                                                                    echo "Self Employed";
                                                                                                                                                                } else if ($row->employment_type == "SR") {
                                                                                                                                                                    echo "Salaried";
                                                                                                                                                                } ?>" />
                                                                    <label for="address">Employment Type</label>
                                                                </div>
                                                            </div>
                                                        <?php

                                                        } else {
                                                        ?>
                                                            <div class="col-md-6">
                                                                <div class="form-floating form-floating-outline">
                                                                    <input type="text" class="form-control" id="prop" name="prop" readonly value="" />
                                                                    <label for="address">Type of Business</label>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                        <div class="col-md-6">
                                                            <div class="form-floating form-floating-outline">
                                                                <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="<?php echo $row->address; ?>" />
                                                                <label for="address">Address</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-floating form-floating-outline">
                                                                <input class="form-control" type="text" id="state" name="state" placeholder="California" value="<?php echo $row->state; ?>" />
                                                                <label for="state">State</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-floating form-floating-outline">
                                                                <input type="text" class="form-control" id="zipCode" name="zipCode" placeholder="231465" maxlength="6" value="<?php echo $row->pin; ?>" />
                                                                <label for="zipCode">Zip Code</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-floating form-floating-outline">
                                                                <select id="status" class="select2 form-select" name="status">
                                                                    <option value="Pending" <?php if ($row->status === 'Pending') echo 'selected="selected"'; ?>>Pending</option>
                                                                    <option value="Received" <?php if ($row->status === 'Received') echo 'selected="selected"'; ?>>Received</option>
                                                                    <option value="Approved" <?php if ($row->status === 'Approved') echo 'selected="selected"'; ?>>Approved</option>
                                                                    <option value="Pending Payment" <?php if ($row->status === 'Pending Payment') echo 'selected="selected"'; ?>>Pending Payment</option>
                                                                    <option value="Completed" <?php if ($row->status === 'Completed') echo 'selected="selected"'; ?>>Completed</option>
                                                                    <option value="Rejected" <?php if ($row->status === 'Rejected') echo 'selected="selected"'; ?>>Rejected</option>
                                                                </select>
                                                                <label for="Status">Status</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <h2 class="fs-5 mb-1">Documents</h2>
                                                            <ul>
                                                                <a href="<?php echo $row->customer_form16_p1; ?>" target="_blank">
                                                                    <li class="fw-bold">Form 16 <span class="mdi mdi-download-box"></span></li>
                                                                </a>
                                                                <a href="<?php echo $row->bank_statement; ?>" target="_blank">
                                                                    <li class="fw-bold">Bank Statement <span class="mdi mdi-download-box"></span></li>
                                                                </a>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="uniqID" name="uniqID" value="<?php echo $row->uniqid; ?>" />
                                                    <input type="hidden" id="paymentID" name="paymentID" value="DIGI<?php echo md5($row->uniqid); ?>" />
                                                    <input type="hidden" id="customerID" name="customerID" value="<?php echo $row->customer_id; ?>" />
                                                    <input type="hidden" id="paymentStatus" name="paymentStatus" value="Pending" />
                                                    <?php
                                                    $builder_price = $db->table('price');
                                                    $builder_price->where('pID', $product_id);
                                                    $queryP = $builder_price->get();
                                                    foreach ($queryP->getResult() as $rowP) {
                                                    ?>
                                                        <input type="hidden" id="paymentPrice" name="price" value="<?php echo $rowP->price; ?>" />
                                                    <?php } ?>
                                                    <div class="mt-4">
                                                        <button type="submit" class="btn btn-primary me-2">Save changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- /Account -->
                                        </div>

                                    </div>
                                </div>
                        <?php }
                        } ?>
                    </div>
                    <!-- / Content -->
                    <?php include 'fragments/footer.php' ?>

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="<?php echo base_url(); ?>/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="<?php echo base_url(); ?>/assets/vendor/libs/popper/popper.js"></script>
    <script src="<?php echo base_url(); ?>/assets/vendor/js/bootstrap.js"></script>
    <script src="<?php echo base_url(); ?>/assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="<?php echo base_url(); ?>/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="<?php echo base_url(); ?>/assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="<?php echo base_url(); ?>/assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="<?php echo base_url(); ?>/assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="<?php echo base_url(); ?>./assets/js/dashboards-analytics.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>
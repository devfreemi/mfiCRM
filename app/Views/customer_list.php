<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="<?php echo base_url(); ?>/assets/img//" data-template="vertical-menu-template-free">

<head>
    <?php include 'fragments/head.php'; ?>
</head>

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
                        <h4 class="py-3 mb-4"><span class="text-muted fw-light">Customer /</span> Customer Tables</h4>

                        <!-- Basic Bootstrap Table -->
                        <div class="card">
                            <h5 class="card-header">Customer List</h5>
                            <div class="table-responsive text-nowrap">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Customer Id</th>
                                            <th>Name</th>
                                            <th>Photo</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Login With</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        <?php
                                        $db = db_connect();
                                        $builder = $db->table('customerDetails');
                                        $query = $builder->get();
                                        foreach ($query->getResult() as $row) {
                                        ?>
                                            <tr>
                                                <td>
                                                    <i class="mdi mdi-account mdi-20px text-danger me-3"></i><span class="fw-medium"><?php echo $row->uniqid; ?></span>
                                                </td>
                                                <td><?php echo $row->name; ?></td>
                                                <td>
                                                    <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                                                        <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-md pull-up" title="<?php echo $row->name; ?>">
                                                            <img src="<?php echo $row->photo; ?>" alt="Avatar" class="rounded-circle" />
                                                        </li>

                                                    </ul>
                                                </td>
                                                <td><?php echo $row->email; ?></td>
                                                <td><?php echo $row->mobile; ?></td>
                                                <td><?php echo $row->loginWith; ?></td>
                                                <td class="text-center">
                                                    <div class="dropdown">
                                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                            <i class="mdi mdi-dots-vertical"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="javascript:void(0);"><i class="mdi mdi-trash-can-outline me-1"></i> Delete</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
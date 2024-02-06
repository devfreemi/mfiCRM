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
                        <div class="row gy-4">
                            <!-- Transactions -->
                            <div class="col-lg-12">
                                <?php if (session()->getFlashdata('update')) : ?>
                                    <p class="text-center text-success"><?= session()->getFlashdata('update') ?></p>
                                <?php endif;
                                ?>
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h5 class="card-title m-0 me-2">Transactions</h5>
                                            <div class="dropdown">
                                                <button class="btn p-0" type="button" id="transactionID" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="mdi mdi-dots-vertical mdi-24px"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
                                                    <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                                    <a class="dropdown-item" href="javascript:void(0);">Update</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-3 col-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar">
                                                        <div class="avatar-initial bg-primary rounded shadow">
                                                            <i class="mdi mdi-account-outline mdi-24px"></i>
                                                        </div>
                                                    </div>
                                                    <div class="ms-3">
                                                        <div class="small mb-1">Customers</div>
                                                        <?php
                                                        $db = db_connect();
                                                        $query1 = $db->query("SELECT COUNT(`id`) as total_customer FROM customerDetails");
                                                        foreach ($query1->getResult() as $row2) {
                                                            $totalCustomer = $row2->total_customer;
                                                        }
                                                        ?>
                                                        <h5 class="mb-0"><?php echo $totalCustomer; ?></h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar">
                                                        <div class="avatar-initial bg-success rounded shadow">
                                                            <i class="mdi mdi-trending-up mdi-24px"></i>
                                                        </div>
                                                    </div>
                                                    <div class="ms-3">
                                                        <div class="small mb-1">Jobs</div>
                                                        <?php $db = db_connect();
                                                        $builderSer = $db->table('servicesDetails');
                                                        $countSer = $builderSer->countAllResults();

                                                        ?>
                                                        <h5 class="mb-0"><?php echo $countSer; ?></h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar">
                                                        <div class="avatar-initial bg-warning rounded shadow">
                                                            <i class="mdi mdi-cellphone-link mdi-24px"></i>
                                                        </div>
                                                    </div>
                                                    <div class="ms-3">
                                                        <div class="small mb-1">Product</div>
                                                        <?php $db = db_connect();
                                                        $builder = $db->table('product');
                                                        $count = $builder->countAllResults();

                                                        ?>
                                                        <h5 class="mb-0"><?php echo $count; ?></h5>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar">
                                                        <div class="avatar-initial bg-info rounded shadow">
                                                            <i class="mdi mdi-currency-usd mdi-24px"></i>
                                                        </div>
                                                    </div>
                                                    <div class="ms-3">
                                                        <div class="small mb-1">Total Pending Payment</div>
                                                        <?php
                                                        $sql = $db->query('SELECT SUM(`price`) AS totalDue FROM `servicesDetails` WHERE `status` = "Pending PAyment"');
                                                        foreach ($sql->getResult() as $rows) {
                                                            $totalDue = $rows->totalDue;
                                                        }
                                                        ?>
                                                        <h5 class="mb-0">Rs. <?php echo number_format($totalDue); ?></h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/ Transactions -->
                            <!-- Data Tables -->
                            <div class="col-12">
                                <div class="card">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead class="table-light">
                                                <tr class="text-center">
                                                    <th class="text-truncate">Customer</th>
                                                    <th class="text-truncate">Email</th>
                                                    <th class="text-truncate">Job ID</th>
                                                    <th class="text-truncate">Service Name</th>
                                                    <th class="text-truncate">Status</th>
                                                    <th class="text-truncate">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $db = db_connect();
                                                $builder = $db->table('customerDetails');
                                                $builder->select('*');
                                                $builder->join('servicesDetails', 'servicesDetails.customer_id = customerDetails.uniqid');
                                                $query = $builder->get();
                                                foreach ($query->getResult() as $row) {
                                                    $product_id = $row->product_id;
                                                    $builder_name = $db->table('product');
                                                    $builder_name->where('id', $product_id);
                                                    $query_name = $builder_name->get();
                                                    foreach ($query_name->getResult() as $row_name) {
                                                        if ($row->status == "Approved" || $row->status == "Received") {
                                                            $badgeCL = "bg-label-primary";
                                                        } elseif ($row->status == "Pending") {
                                                            # code...
                                                            $badgeCL = "bg-label-warning";
                                                        } elseif ($row->status == "Pending Payment") {
                                                            # code...
                                                            $badgeCL = "bg-label-info";
                                                        } elseif ($row->status == "Completed") {
                                                            # code...
                                                            $badgeCL = "bg-label-success";
                                                        } elseif ($row->status == "Rejected") {
                                                            # code...
                                                            $badgeCL = "bg-label-danger";
                                                        }
                                                ?>
                                                        <tr class="text-center">
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="avatar avatar-sm me-3">
                                                                        <img src="<?php echo $row->photo; ?>" alt="<?php echo $row->givenName; ?>" class="rounded-circle" />
                                                                    </div>
                                                                    <div>
                                                                        <h6 class="mb-0 text-truncate"><?php echo $row->name; ?></h6>
                                                                        <small class="text-truncate"><?php echo $row->givenName; ?></small>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="text-truncate"><?php echo $row->email; ?></td>
                                                            <td class="text-truncate"><?php echo $row->uniqid; ?></td>
                                                            <td class="text-truncate">
                                                                <i class="mdi mdi-laptop mdi-24px text-danger me-1"></i> <?php echo $row_name->productName; ?>
                                                            </td>
                                                            <td><span class="badge <?php echo $badgeCL; ?> rounded-pill"><?php echo $row->status; ?></span></td>
                                                            <td class="text-truncate text-center">
                                                                <a type="button" href="<?php echo base_url(); ?>tax/customer/<?php echo $row->uniqid; ?>" class="btn rounded-pill btn-icon btn-outline-info waves-effect">
                                                                    <span class="tf-icons mdi mdi-account-edit text-info"></span>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                <?php }
                                                } ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!--/ Data Tables -->
                            <!-- Weekly Overview Chart -->
                            <div class="col-xl-4 col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="mb-1">Weekly Overview</h5>
                                            <div class="dropdown">
                                                <button class="btn p-0" type="button" id="weeklyOverviewDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="mdi mdi-dots-vertical mdi-24px"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="weeklyOverviewDropdown">
                                                    <a class="dropdown-item" href="javascript:void(0);" onClick="window.location.reload();">Refresh</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div id="weeklyOverviewChart"></div>
                                        <div class="mt-1 mt-md-3">
                                            <div class="d-flex align-items-center gap-3">

                                                <p class="mb-0">Weekly Job Receiving Details and Day Wise comparison.</p>
                                            </div>
                                            <div class="d-grid mt-3 mt-md-4">
                                                <a class="btn btn-primary text-white" type="button">Details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/ Weekly Overview Chart -->

                            <!-- Deposit / Withdraw -->
                            <div class="col-xl-8">
                                <div class="card h-100">
                                    <div class="card-body row g-2">
                                        <div class="col-12 col-md-6 card-separator pe-0 pe-md-3">
                                            <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
                                                <h5 class="m-0 me-2">Pending Payments</h5>
                                                <a class="fw-medium" href="javascript:void(0);">View all</a>
                                            </div>
                                            <div class="pt-2">
                                                <?php $builderSerC = $db->table('servicesDetails');
                                                $builderSerC->where('status', "Pending Payment");
                                                $countSerC = $builderSerC->countAllResults();
                                                if ($countSerC > 0) {
                                                ?>
                                                    <ul class="p-0 m-0">
                                                        <?php
                                                        $builder = $db->table('customerDetails');
                                                        $builder->select('*');
                                                        $builder->join('servicesDetails', 'servicesDetails.customer_id = customerDetails.uniqid');
                                                        $builder->where('status', "Pending Payment");
                                                        $query = $builder->get();
                                                        foreach ($query->getResult() as $row) {
                                                            $product_id = $row->product_id;
                                                            $builder_name = $db->table('product');
                                                            $builder_name->where('id', $product_id);
                                                            $query_name = $builder_name->get();
                                                            foreach ($query_name->getResult() as $row_name) {
                                                        ?>
                                                                <li class="d-flex mb-4 align-items-center pb-2">
                                                                    <div class="flex-shrink-0 me-3">
                                                                        <img src="<?php echo $row->photo; ?>" class="img-fluid" alt="<?php echo $row->name; ?>" height="30" width="30" />
                                                                    </div>
                                                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                                        <div class="me-2">
                                                                            <h6 class="mb-0"><?php echo $row->name; ?></h6>
                                                                            <small><?php echo $row_name->productName; ?></small>
                                                                        </div>
                                                                        <h6 class="text-success mb-0">Rs. <?php echo number_format($row->price); ?></h6>
                                                                    </div>
                                                                </li>
                                                        <?php }
                                                        } ?>
                                                    </ul>
                                                <?php } else { ?>
                                                    <p class="mb-0 text-danger fw-bold text-center my-5 py-5">No Pending Payment !</p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 ps-0 ps-md-3 mt-3 mt-md-2">
                                            <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
                                                <h5 class="m-0 me-2">Completed Jobs</h5>
                                                <a class="fw-medium" href="javascript:void(0);">View all</a>
                                            </div>
                                            <div class="pt-2">
                                                <ul class="p-0 m-0">
                                                    <li class="d-flex mb-4 align-items-center pb-2">
                                                        <div class="flex-shrink-0 me-3">
                                                            <img src="<?php echo base_url(); ?>/assets/img/icons/brands/google.png" class="img-fluid" alt="google" height="30" width="30" />
                                                        </div>
                                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                            <div class="me-2">
                                                                <h6 class="mb-0">Google Adsense</h6>
                                                                <small>Paypal deposit</small>
                                                            </div>
                                                            <h6 class="text-danger mb-0">-$145</h6>
                                                        </div>
                                                    </li>
                                                    <li class="d-flex mb-4 align-items-center pb-2">
                                                        <div class="flex-shrink-0 me-3">
                                                            <img src="<?php echo base_url(); ?>/assets/img/icons/brands/github.png" class="img-fluid" alt="github" height="30" width="30" />
                                                        </div>
                                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                            <div class="me-2">
                                                                <h6 class="mb-0">Github Enterprise</h6>
                                                                <small>Security &amp; compliance</small>
                                                            </div>
                                                            <h6 class="text-danger mb-0">-$1870</h6>
                                                        </div>
                                                    </li>
                                                    <li class="d-flex mb-4 align-items-center pb-2">
                                                        <div class="flex-shrink-0 me-3">
                                                            <img src="<?php echo base_url(); ?>/assets/img/icons/brands/slack.png" class="img-fluid" alt="slack" height="30" width="30" />
                                                        </div>
                                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                            <div class="me-2">
                                                                <h6 class="mb-0">Upgrade Slack Plan</h6>
                                                                <small>Debit card deposit</small>
                                                            </div>
                                                            <h6 class="text-danger mb-0">$450</h6>
                                                        </div>
                                                    </li>
                                                    <li class="d-flex mb-4 align-items-center pb-2">
                                                        <div class="flex-shrink-0 me-3">
                                                            <img src="<?php echo base_url(); ?>/assets/img/icons/payments/digital-ocean.png" class="img-fluid" alt="digital" height="30" width="30" />
                                                        </div>
                                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                            <div class="me-2">
                                                                <h6 class="mb-0">Digital Ocean</h6>
                                                                <small>Cloud Hosting</small>
                                                            </div>
                                                            <h6 class="text-danger mb-0">-$540</h6>
                                                        </div>
                                                    </li>
                                                    <li class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-3">
                                                            <img src="<?php echo base_url(); ?>/assets/img/icons/brands/aws.png" class="img-fluid" alt="aws" height="30" width="30" />
                                                        </div>
                                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                            <div class="me-2">
                                                                <h6 class="mb-0">AWS Account</h6>
                                                                <small>Choosing a Cloud Platform</small>
                                                            </div>
                                                            <h6 class="text-danger mb-0">-$21</h6>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Deposit / Withdraw -->


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
    <?php
    // Today
    $date = date('Y-m-d');
    $db = db_connect();
    $builderDate = $db->table('servicesDetails');
    $builderDate->where('date', $date);
    $countSer = $builderDate->countAllResults();
    // Previous 3 Days
    $date3 = date('d')  - 1;
    $builderDate3 = $db->table('servicesDetails');
    $builderDate3->where('date', $date3);
    $countSer3 = $builderDate3->countAllResults();
    $date2 = date('d')  - 2;
    $builderDate2 = $db->table('servicesDetails');
    $builderDate2->where('date', $date2);
    $countSer2 = $builderDate2->countAllResults();
    $date1 = date('d')  - 3;
    $builderDate1 = $db->table('servicesDetails');
    $builderDate1->where('date', $date1);
    $countSer1 = $builderDate1->countAllResults();
    // Next 3 Days
    $date5 = date('d')  + 1;
    $builderDate5 = $db->table('servicesDetails');
    $builderDate5->where('date', $date5);
    $countSer5 = $builderDate5->countAllResults();
    $date6 = date('d')  + 2;
    $builderDate6 = $db->table('servicesDetails');
    $builderDate6->where('date', $date6);
    $countSer6 = $builderDate6->countAllResults();
    $date7 = date('d') + 3;
    $builderDate7 = $db->table('servicesDetails');
    $builderDate7->where('date', $date7);
    $countSer7 = $builderDate7->countAllResults();
    ?>
    <script>
        (function() {
            let cardColor, labelColor, borderColor, chartBgColor, bodyColor;

            cardColor = config.colors.cardColor;
            labelColor = config.colors.textMuted;
            borderColor = config.colors.borderColor;
            chartBgColor = config.colors.chartBgColor;
            bodyColor = config.colors.bodyColor;

            // Weekly Overview Line Chart
            // --------------------------------------------------------------------
            const weeklyOverviewChartEl = document.querySelector("#weeklyOverviewChart"),
                weeklyOverviewChartConfig = {
                    chart: {
                        type: "bar",
                        height: 200,
                        offsetY: -9,
                        offsetX: -16,
                        parentHeightOffset: 0,
                        toolbar: {
                            show: false,
                        },
                    },
                    series: [{
                        name: "Jobs",
                        data: [<?php echo $countSer; ?>,
                            <?php echo $countSer3; ?>,
                            <?php echo $countSer2; ?>,
                            <?php echo $countSer1; ?>,
                            <?php echo $countSer5; ?>,
                            <?php echo $countSer6; ?>,
                            <?php echo $countSer7; ?>
                        ],
                    }, ],
                    colors: [chartBgColor],
                    plotOptions: {
                        bar: {
                            borderRadius: 8,
                            columnWidth: "30%",
                            endingShape: "rounded",
                            startingShape: "rounded",
                            colors: {
                                ranges: [{
                                        from: 11,
                                        to: 80,
                                        color: config.colors.primary,
                                    },
                                    {
                                        from: 0,
                                        to: 10,
                                        color: config.colors.danger,
                                    },
                                ],
                            },
                        },
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    legend: {
                        show: false,
                    },
                    grid: {
                        strokeDashArray: 8,
                        borderColor,
                        padding: {
                            bottom: -10,
                        },
                    },
                    xaxis: {
                        categories: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
                        tickPlacement: "on",
                        labels: {
                            show: false,
                        },
                        axisBorder: {
                            show: false,
                        },
                        axisTicks: {
                            show: false,
                        },
                    },
                    yaxis: {
                        min: 0,
                        max: 90,
                        show: true,
                        tickAmount: 3,
                        labels: {
                            formatter: function(val) {
                                return parseInt(val);
                            },
                            style: {
                                fontSize: "0.75rem",
                                fontFamily: "Inter",
                                colors: labelColor,
                            },
                        },
                    },
                    states: {
                        hover: {
                            filter: {
                                type: "none",
                            },
                        },
                        active: {
                            filter: {
                                type: "none",
                            },
                        },
                    },
                    responsive: [{
                            breakpoint: 1500,
                            options: {
                                plotOptions: {
                                    bar: {
                                        columnWidth: "40%",
                                    },
                                },
                            },
                        },
                        {
                            breakpoint: 1200,
                            options: {
                                plotOptions: {
                                    bar: {
                                        columnWidth: "30%",
                                    },
                                },
                            },
                        },
                        {
                            breakpoint: 815,
                            options: {
                                plotOptions: {
                                    bar: {
                                        borderRadius: 5,
                                    },
                                },
                            },
                        },
                        {
                            breakpoint: 768,
                            options: {
                                plotOptions: {
                                    bar: {
                                        borderRadius: 10,
                                        columnWidth: "20%",
                                    },
                                },
                            },
                        },
                        {
                            breakpoint: 568,
                            options: {
                                plotOptions: {
                                    bar: {
                                        borderRadius: 8,
                                        columnWidth: "30%",
                                    },
                                },
                            },
                        },
                        {
                            breakpoint: 410,
                            options: {
                                plotOptions: {
                                    bar: {
                                        columnWidth: "50%",
                                    },
                                },
                            },
                        },
                    ],
                };
            if (
                typeof weeklyOverviewChartEl !== undefined &&
                weeklyOverviewChartEl !== null
            ) {
                const weeklyOverviewChart = new ApexCharts(
                    weeklyOverviewChartEl,
                    weeklyOverviewChartConfig
                );
                weeklyOverviewChart.render();
            }
        })();
    </script>
</body>

</html>
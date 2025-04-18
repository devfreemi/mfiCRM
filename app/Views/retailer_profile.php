<?php include 'fragments/head.php'; ?>
<?php include 'fragments/sidebar.php'; ?>


<div class="main">

    <?php include 'fragments/nav.php'; ?>
    <main class="content">
        <div class="container-fluid p-0">
            <!-- Profile Details -->


            <!-- Shop Image (Full Image, Not Cropped) -->
            <div class="position-relative mb-2">
                <img src="<?= esc($retailers['image']) ?>" class="img-fluid w-100 rounded bg-shop" alt="Shop Image">

                <!-- Profile Picture Overlay -->
                <div class=" bottom-0 start-0 translate-middle-y ms-4 mb-2">
                    <img src="https://avatar.iran.liara.run/username?username=<?= esc($retailers['name']) ?>" class="rounded-circle border border-white border-3 shadow" alt="Profile" style="width: 120px; height: 120px;">
                </div>
            </div>

            <!-- Retailer Info Card -->
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <?php

                    if ($retailers['eli_run'] === "Y") {
                        $db = db_connect();
                        $builderB = $db->table('initial_eli_run');
                        $builderB->select('*');
                        $builderB->where('member_id ', $retailers['member_id']);
                        $queryB = $builderB->get();
                        // $countEli = $builderB->countAllResults();
                        foreach ($queryB->getResult() as $rowB) {
                            $eli = $rowB->eligibility;
                            if ($eli === 'Not Eligible') {
                                # code...

                                echo '<h3 class=" fw-bold">' . esc($retailers["name"]) . '</h3>
                                        <span class="badge bg-danger my-3">' . $eli . '</span>';
                            } else {
                                # code...

                                echo '<h3 class=" fw-bold">' . esc($retailers["name"]) . '</h3>
                                <span class="badge bg-success my-3">' . $eli . '</span>';
                            }
                        }
                    ?>

                    <?php
                    } else {
                        # code...
                    ?>
                        <h3 class="fw-bold"><?= esc($retailers['name']) ?></h3>
                        <span class="badge bg-warning my-3">Not Checked</span>


                    <?php  } ?>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <p class="mb-1"><strong>Shop Name:</strong> <?= esc($retailers['businessName']) ?></p>
                            <p class="mb-1"><strong>PAN Number:</strong> <?= esc($retailers['pan']) ?></p>
                            <p class="mb-1"><strong>Phone:</strong> <?= esc($retailers['mobile']) ?></p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <p class="mb-1"><strong>Address:</strong><?= esc($retailers['location']) ?>, Pin:<?= esc($retailers['pincode']) ?> </p>
                            <p class="mb-1"><strong>GSTIN:</strong> <?= esc($retailers['gst']) ?></p>
                            <p class="mb-1"><strong>Registered:</strong> <?= esc($retailers['estab']) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Section -->
            <h5 class="mb-3">Uploaded Documents</h5>
            <div class="row g-3">

                <!-- Example: Repeat this block up to 20 times -->
                <!-- You can use PHP to loop through documents -->

                <!-- Document Card (1) -->
                <div class="col-md-3 col-sm-6">
                    <div class="card h-100 shadow-sm">
                        <img src="<?= base_url() ?>assets/img/elements/dummy.png" class="card-img-top" alt="Document 1">
                        <div class="card-body text-center">
                            <h6 class="card-title">Document 1</h6>
                            <a href="<?= base_url() ?>assets/img/elements/dummy.png" target="_blank" class="btn btn-sm btn-outline-primary">View</a>
                        </div>
                    </div>
                </div>

                <!-- Document Card (2) -->
                <div class="col-md-3 col-sm-6">
                    <div class="card h-100 shadow-sm">
                        <img src="<?= base_url() ?>assets/img/elements/dummy.png" class="card-img-top" alt="Document 2">
                        <div class="card-body text-center">
                            <h6 class="card-title">Document 2</h6>
                            <a href="<?= base_url() ?>assets/img/elements/dummy.png" target="_blank" class="btn btn-sm btn-outline-primary">View</a>
                        </div>
                    </div>
                </div>

                <!-- Document Card (3) -->
                <div class="col-md-3 col-sm-6">
                    <div class="card h-100 shadow-sm">
                        <img src="<?= base_url() ?>assets/img/elements/dummy.png" class="card-img-top" alt="Document 3">
                        <div class="card-body text-center">
                            <h6 class="card-title">Document 3</h6>
                            <a href="<?= base_url() ?>assets/img/elements/dummy.png" target="_blank" class="btn btn-sm btn-outline-primary">View</a>
                        </div>
                    </div>
                </div>

                <!-- Document Card (3) -->
                <div class="col-md-3 col-sm-6">
                    <div class="card h-100 shadow-sm">
                        <img src="<?= base_url() ?>assets/img/elements/dummy.png" class="card-img-top" alt="Document 3">
                        <div class="card-body text-center">
                            <h6 class="card-title">Document 3</h6>
                            <a href="<?= base_url() ?>assets/img/elements/dummy.png" target="_blank" class="btn btn-sm btn-outline-primary">View</a>
                        </div>
                    </div>
                </div>
            </div>
    </main>

    <?php include 'fragments/footer.php'; ?>
</div>
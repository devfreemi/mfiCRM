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
                    <img src="https://ui-avatars.com/api/?background=random&&rounded=true&&name=<?= esc($retailers['name']) ?>" class="rounded-circle border border-white border-3 shadow" alt="Profile" style="width: 120px; height: 120px;">
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
                            $eligible_amount = $rowB->loan_amount;
                            $roi = $rowB->roi;
                            $emi = $rowB->emi;
                            $tenure = $rowB->tenure;
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
                        <div class="col-md-4 mb-2">
                            <p class="mb-1"><strong>Eligible Amount:</strong> <strong class="text-success fw-bold"><?= number_format($eligible_amount) ?>.00</strong> </p>
                            <p class="mb-1"><strong>ROI:</strong> <?= $roi ?> %</p>
                            <p class="mb-1"><strong>EMI:</strong> <?= number_format($emi) ?>.00</p>
                            <p class="mb-1"><strong>Tenure:</strong> <?= $tenure ?> Months</p>
                        </div>
                        <div class="col-md-4 mb-2">
                            <p class="mb-1"><strong>Shop Name:</strong> <?= esc($retailers['businessName']) ?></p>
                            <p class="mb-1"><strong>PAN Number:</strong> <?= esc($retailers['pan']) ?></p>
                            <p class="mb-1"><strong>Phone:</strong> <?= esc($retailers['mobile']) ?></p>
                        </div>
                        <div class="col-md-4 mb-2">
                            <p class="mb-1"><strong>Address:</strong><?= esc($retailers['location']) ?>, Pin:<?= esc($retailers['pincode']) ?> </p>
                            <p class="mb-1"><strong>GSTIN:</strong> <?= esc($retailers['gst']) ?></p>
                            <p class="mb-1"><strong>Registered:</strong> <?= esc($retailers['estab']) ?></p>
                        </div>


                    </div>
                </div>
            </div>

            <!-- Documents Section -->
            <h4 class="mb-3 fw-bold">Uploaded Documents</h4>
            <div class="row g-3">

                <!-- Example: Repeat this block up to 20 times -->
                <!-- You can use PHP to loop through documents -->

                <!-- Document Card (1) -->
                <?php
                $db = db_connect();
                $builder = $db->table('retailerdocuments');
                $builder->select('*');
                $builder->where('member_id ', $retailers['member_id']);
                $query = $builder->get();

                foreach ($query->getResult() as $row) {
                    $doc = $row->document_path;

                    // Decode it into associative array

                    // echo "- $url<br>";

                ?>
                    <div class="col-md-2 col-sm-6">
                        <div class="card h-100 shadow-sm">
                            <img src="
                                <?php if ($row->document_type === 'BANK_STATEMENT') {
                                    echo base_url() . "assets/img/elements/dummy.png";
                                } else {
                                    echo $doc;
                                } ?>
                            " class="card-img-top" alt="Document Image">
                            <div class="card-body text-center">
                                <h6 class="card-title"><?= $row->document_type ?></h6>
                                <a href="<?= $doc ?>" target="_blank" class="btn btn-sm btn-outline-primary">View</a>
                            </div>
                        </div>
                    </div>
                <?php
                } ?>
            </div>

            <div class="col-6 mx-auto text-center">
                <?php
                // Load the model inside the view
                $feedbackModel = new \App\Models\FiCheckModel();
                $existing = $feedbackModel->where('member_id', $retailers['member_id'])->first();
                ?>
                <?php if ($existing): ?>
                    <!-- FI Already Submitted -->
                    <div class="alert alert-warning mt-5">
                        <h4 class="alert-heading">⚠ FI Already Completed</h4>
                        <p>Field inspection has already been submitted for Member ID <strong><?= $retailers['member_id'] ?></strong>.</p>
                        <hr>
                        <a href="/" class="btn btn-primary">Go Back</a>
                    </div>
                <?php else: ?>
                    <?php

                    # code...
                    echo '<a href="' . base_url() . 'retailers/fi/' . $retailers['member_id'] . '" class="btn btn-primary mt-3">Initiate Field Inspection</a>';

                    ?>

                <?php endif; ?>
            </div>
    </main>

    <?php include 'fragments/footer.php'; ?>
</div>
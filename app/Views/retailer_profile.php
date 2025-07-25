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
                        <?php if ($retailers['eli_run'] === "Y") { ?>
                            <div class="col-md-4 mb-2">
                                <p class="mb-1"><strong>Eligible Amount:</strong> <strong class="text-success fw-bold"><?= number_format($eligible_amount) ?>.00</strong> </p>
                                <p class="mb-1"><strong>ROI:</strong> <?= $roi ?> %</p>
                                <p class="mb-1"><strong>EMI:</strong> <?= number_format($emi) ?>.00</p>
                                <p class="mb-1"><strong>Tenure:</strong> <?= $tenure ?> Months</p>
                            </div>
                        <?php }  ?>
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

            <?php if ($retailers['eli_run'] === "Y") { ?>
                <div class="col-12 mx-auto text-center">
                    <?php
                    // Load the model inside the view
                    $feedbackModel = new \App\Models\FiCheckModel();
                    $fiReport = $feedbackModel->where('member_id', $retailers['member_id'])->first();
                    ?>

                    <?php if ($fiReport): ?>


                        <div class="my-4">
                            <div class="card shadow-sm">
                                <div class="card-header bg-dark text-white">
                                    <div class="d-flex justify-content-between">
                                        <strong>FI Report: <?= esc($fiReport['member_id']) ?></strong>
                                        <span><?= date('d M Y, h:i A', strtotime($fiReport['created_at'])) ?></span>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="mb-3">
                                        <h6 class="text-muted mb-1 fw-bold">Inspector Name</h6>
                                        <h4 class="mb-0 fw-semibold"><?= esc($fiReport['fiInspector_name']) ?></h4>
                                    </div>

                                    <div class="mb-2">
                                        <div class="card shadow-sm border-0 mb-4">
                                            <div class="card-header bg-white border-bottom">
                                                <h5 class="mb-0">
                                                    <i class="bi bi-person-check text-primary me-2"></i>
                                                    Member's Details Verification
                                                </h5>
                                            </div>
                                            <div class="card-body px-4 py-3">
                                                <?php
                                                $verified = array_map('trim', explode(',', $fiReport['verified_fields'] ?? ''));
                                                function isVerified($key, $verified)
                                                {
                                                    return in_array($key, $verified)
                                                        ? '<span class="text-primary fw-bold">✔️</span>'
                                                        : '<span class="text-danger fw-bold">❌</span>';
                                                }
                                                ?>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6 class="text-primary fw-semibold mb-3">Personal Details</h6>
                                                        <ul class="list-group list-group-flush small">
                                                            <li class="list-group-item d-flex justify-content-between">
                                                                <span>Name:</span>
                                                                <span><strong><?= esc($retailers['name']) ?></strong> <?= isVerified('name', $verified) ?></span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between">
                                                                <span>Mobile:</span>
                                                                <span><strong><?= esc($retailers['mobile']) ?></strong> <?= isVerified('mobile', $verified) ?></span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between">
                                                                <span>Address:</span>
                                                                <span><strong><?= esc($retailers['location']) ?></strong> <?= isVerified('address', $verified) ?></span>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <h6 class="text-success fw-semibold mb-3">Business Details</h6>
                                                        <ul class="list-group list-group-flush small">
                                                            <li class="list-group-item d-flex justify-content-between">
                                                                <span>Business Type:</span>
                                                                <span><strong><?= esc($retailers['businessType']) ?></strong> <?= isVerified('business_type', $verified) ?></span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between">
                                                                <span>Daily Sales (Verified):</span>
                                                                <span><strong><strong>₹<?= number_format($retailers['dailySales']) ?></strong> (₹<?= number_format($fiReport['inspector_daily_sales']) ?>)</strong> <?= isVerified('daily_sales', $verified) ?></span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between">
                                                                <span>Stock Value (Verified):</span>
                                                                <span><strong><strong>₹<?= number_format($retailers['stock']) ?></strong> (₹<?= number_format($fiReport['inspector_stock_value']) ?>)</strong> <?= isVerified('stock_value', $verified) ?></span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between">
                                                                <span>Monthly Purchase (Verified):</span>
                                                                <span><strong>₹<?= number_format($fiReport['inspector_month_purchase']) ?></strong> <?= isVerified('monthly_purchase', $verified) ?></span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <?php
                                        function coloredYesNo($value)
                                        {
                                            $val = strtolower(trim($value));
                                            if ($val === 'yes') {
                                                return '<span class="text-success fw-semibold">Yes</span>';
                                            } elseif ($val === 'no') {
                                                return '<span class="text-danger fw-semibold">No</span>';
                                            } else {
                                                return '<span>' . esc($value) . '</span>';
                                            }
                                        }
                                        ?>

                                        <div class="col-md-6">
                                            <h6 class="text-primary">Retailer Interaction</h6>
                                            <ul class="list-group list-group-flush mb-3">
                                                <li class="list-group-item">Retailer Present: <strong><?= coloredYesNo($fiReport['retailer_present']) ?></strong></li>
                                                <li class="list-group-item">Behavior Professional: <strong><?= coloredYesNo($fiReport['retailer_behavior_professional']) ?></strong></li>
                                                <li class="list-group-item">Aware of Products: <strong><?= coloredYesNo($fiReport['retailer_aware_products']) ?></strong></li>
                                                <li class="list-group-item">Needs Training: <strong><?= coloredYesNo($fiReport['retailer_needs_training']) ?></strong></li>
                                            </ul>
                                        </div>

                                        <div class="col-md-6">
                                            <h6 class="text-primary">Shop Inspection</h6>
                                            <ul class="list-group list-group-flush mb-3">
                                                <li class="list-group-item">Shop Clean: <strong><?= coloredYesNo($fiReport['shop_clean']) ?></strong></li>
                                                <li class="list-group-item">Products Displayed: <strong><?= coloredYesNo($fiReport['products_displayed']) ?></strong></li>
                                                <li class="list-group-item">Stock Available: <strong><?= coloredYesNo($fiReport['stock_available']) ?></strong></li>
                                                <li class="list-group-item">Promo Materials Visible: <strong><?= coloredYesNo($fiReport['promo_materials_visible']) ?></strong></li>
                                                <li class="list-group-item">Shop Accessible: <strong><?= coloredYesNo($fiReport['location_accessible']) ?></strong></li>
                                            </ul>
                                        </div>

                                    </div>



                                    <hr>

                                    <?php
                                    $receivedDocs = array_map('trim', explode(',', $fiReport['documents_received'] ?? ''));
                                    function docStatus($docName, $receivedDocs)
                                    {
                                        return in_array($docName, $receivedDocs) ? '✅' : '❌';
                                    }
                                    ?>

                                    <div class="card mb-4">
                                        <div class="card-header bg-light">
                                            <strong>📄 Documents Received from Retailer</strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="text-primary">Personal Documents</h6>
                                                    <ul class="list-group list-group-flush">
                                                        <li class="list-group-item"><?= docStatus('PAN Card', $receivedDocs) ?> PAN Card</li>
                                                        <li class="list-group-item"><?= docStatus('Aadhaar Card', $receivedDocs) ?> Aadhaar Card</li>
                                                        <li class="list-group-item"><?= docStatus('Voter ID', $receivedDocs) ?> Voter ID</li>
                                                        <li class="list-group-item"><?= docStatus('Electricity Bill', $receivedDocs) ?> Electricity Bill</li>
                                                        <li class="list-group-item"><?= docStatus('Property Tax Receipt', $receivedDocs) ?> Property Tax Receipt</li>
                                                        <li class="list-group-item"><?= docStatus('Rent Agreement (House)', $receivedDocs) ?> Rent Agreement (House)</li>
                                                    </ul>
                                                    <div class="col-md-6 text-center mx-auto mt-3">
                                                        <h6 class="text-primary">Ownership</h6>
                                                        <p class="mb-1">Shop: <strong><?= esc($fiReport['shop_ownership']) ?></strong></p>
                                                        <p class="mb-0">House: <strong><?= esc($fiReport['house_ownership']) ?></strong></p>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <h6 class="text-success">Business Documents</h6>
                                                    <ul class="list-group list-group-flush">
                                                        <li class="list-group-item"><?= docStatus('Shop Electricity Bill', $receivedDocs) ?> Shop Electricity Bill</li>
                                                        <li class="list-group-item"><?= docStatus('Shop Rent Agreement', $receivedDocs) ?> Shop Rent Agreement</li>
                                                        <li class="list-group-item"><?= docStatus('GST Certificate', $receivedDocs) ?> GST Certificate</li>
                                                        <li class="list-group-item"><?= docStatus('Purchase Bills', $receivedDocs) ?> Purchase Bills</li>
                                                        <li class="list-group-item"><?= docStatus('Sale Bills', $receivedDocs) ?> Sale Bills</li>
                                                        <li class="list-group-item"><?= docStatus('Income Tax Return', $receivedDocs) ?> Income Tax Return</li>
                                                        <li class="list-group-item"><?= docStatus('Bank Statement', $receivedDocs) ?> Bank Statement</li>
                                                        <li class="list-group-item"><?= docStatus('Trade License', $receivedDocs) ?> Trade License</li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="mt-4">
                                                <h6 class="text-primary">Documents Seen & Verified</h6>
                                                <p class="fw-semibold"><?= esc($fiReport['documents_verified']) ?></p>
                                            </div>
                                            <div class="mb-3">
                                                <h6 class="text-primary">📍 Location Details</h6>
                                                <ul class="list-group list-group-flush small">
                                                    <li class="list-group-item d-flex justify-content-between">
                                                        <span>Place Name:</span>
                                                        <span><strong><?= esc($fiReport['place_name']) ?: 'N/A' ?></strong></span>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between">
                                                        <span>Latitude:</span>
                                                        <span><strong><?= esc($fiReport['latitude']) ?: 'N/A' ?></strong></span>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between">
                                                        <span>Longitude:</span>
                                                        <span><strong><?= esc($fiReport['longitude']) ?: 'N/A' ?></strong></span>
                                                    </li>
                                                </ul>
                                                <?php if (!empty($fiReport['latitude']) && !empty($fiReport['longitude'])): ?>
                                                    <div class="mt-3">
                                                        <h6 class="text-primary">📍 Map Location</h6>
                                                        <iframe
                                                            width="100%"
                                                            height="200"
                                                            style="border:0"
                                                            loading="lazy"
                                                            allowfullscreen
                                                            referrerpolicy="no-referrer-when-downgrade"
                                                            src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBL37wmv8b2ttTewyEFj3ri8sLIew5RN2E&center=<?= $fiReport['latitude'] ?>,<?= $fiReport['longitude'] ?>&q=<?= esc($fiReport['place_name']) ?: 'N/A' ?>&zoom=17&maptype=satellite">
                                                        </iframe>
                                                    </div>
                                                <?php else: ?>
                                                    <p class="text-muted">Location coordinates not available to show map.</p>
                                                <?php endif; ?>

                                            </div>
                                            <div class="mb-4">
                                                <h6 class="text-primary">📸 Captured Photos</h6>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <strong>Shop Photo</strong><br>
                                                        <?php if (!empty($fiReport['shop_photo'])): ?>
                                                            <img src="<?= base_url($fiReport['shop_photo']) ?>" class="img-fluid rounded border" style="max-height: 200px;">
                                                        <?php else: ?>
                                                            <p class="text-muted">Not available</p>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <strong>Selfie with Owner</strong><br>
                                                        <?php if (!empty($fiReport['selfie_with_owner'])): ?>
                                                            <img src="<?= base_url($fiReport['selfie_with_owner']) ?>" class="img-fluid rounded border" style="max-height: 200px;">
                                                        <?php else: ?>
                                                            <p class="text-muted">Not available</p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                        <p class="text-muted">Inspector's Comment: <span class="text-info fw-bold fs-4"><?= esc($fiReport['inspector_comments']) ?: 'No additional comments.' ?></span></p>
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <h6 class="text-primary">Payment Behavior</h6>
                                                <p class="badge bg-success fs-6"><?= esc($fiReport['payment_behavior']) ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="text-primary">FI Status</h6>
                                                <?php
                                                $fi_final = $fiReport['fi_final'];
                                                $badgeClass = match ($fi_final) {
                                                    'Y' => 'success',
                                                    'N' => 'danger',
                                                    'W' => 'warning',
                                                };
                                                ?>
                                                <span class="badge bg-<?= $badgeClass ?> fs-6"><?= strtoupper($fiReport['fi_status']) ?></span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="container my-4">
                            <?php

                            if ($fi_final == 'Y') {
                                # code...
                            ?>
                                <?php $db = db_connect();

                                $builder_doc_loan = $db->table('retailer_loan_doc');
                                $builder_doc_loan->where('member_id', $retailers['member_id']);
                                $builder_doc_loan->where('eSign', 'Y');
                                $row_loan_doc = $builder_doc_loan->get()->getRow();
                                // echo $row_loan_doc->eSign;
                                if ($row_loan_doc) {
                                    if ($row_loan_doc->eSign === 'Y') {
                                        echo "<p style='color: green;'>eSigned: Completed</p>";
                                    } else {
                                        echo "<a class='btn btn-primary' href='" . base_url() . "preview-pdf/" . $retailers['member_id'] . "'>Start eSigning</a>";
                                    }
                                } else {
                                    echo "<a class='btn btn-warning' href='" . base_url() . "preview-pdf/" . $retailers['member_id'] . "'>Start eSigning</a>";
                                } ?>

                            <?php
                            }
                            ?>

                        </div>
                    <?php else: ?>
                        <div class="container my-4">
                            <?php
                            if ($eli != 'Not Eligible') {
                                # code...
                            ?>
                                <a href="<?= base_url() ?>retailers/fi/<?= $retailers['member_id'] ?>" class="btn btn-primary ms-1">Initiate Filed Inspection</a>

                            <?php
                            }
                            ?>

                        </div>
                    <?php endif; ?>
                </div>
            <?php } ?>
        </div>
    </main>

    <?php include 'fragments/footer.php'; ?>
</div>
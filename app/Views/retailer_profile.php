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

                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-primary">Retailer Interaction</h6>
                                            <ul class="list-group list-group-flush mb-3">
                                                <li class="list-group-item">Retailer Present: <strong><?= esc($fiReport['retailer_present']) ?></strong></li>
                                                <li class="list-group-item">Behavior Professional: <strong><?= esc($fiReport['retailer_behavior_professional']) ?></strong></li>
                                                <li class="list-group-item">Aware of Products: <strong><?= esc($fiReport['retailer_aware_products']) ?></strong></li>
                                            </ul>
                                        </div>

                                        <div class="col-md-6">
                                            <h6 class="text-primary">Shop Inspection</h6>
                                            <ul class="list-group list-group-flush mb-3">
                                                <li class="list-group-item">Shop Clean: <strong><?= esc($fiReport['shop_clean']) ?></strong></li>
                                                <li class="list-group-item">Products Displayed: <strong><?= esc($fiReport['products_displayed']) ?></strong></li>
                                                <li class="list-group-item">Stock Available: <strong><?= esc($fiReport['stock_available']) ?></strong></li>
                                                <li class="list-group-item">Promo Materials Visible: <strong><?= esc($fiReport['promo_materials_visible']) ?></strong></li>
                                                <li class="list-group-item">Shop Accessible: <strong><?= esc($fiReport['location_accessible']) ?></strong></li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-primary">Payment Behavior</h6>
                                            <p class="fw-semibold"><?= esc($fiReport['payment_behavior']) ?></p>
                                        </div>

                                    </div>

                                    <hr>

                                    <div class="mb-2">

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
                                                    <!-- Personal Documents -->
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
                                                    <!-- Business Documents -->
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
                                                    <h6 class="text-primary mt-4">Verification & Comments</h6>
                                                    <p class="mb-1">Documents Verified: <strong><?= esc($fiReport['documents_verified']) ?></strong></p>

                                                </div>

                                            </div>
                                        </div>


                                        <p class="text-muted">Inspector's Comment: <span class="text-info fw-bold fs-4"><?= esc($fiReport['inspector_comments']) ?: 'No additional comments.' ?></span></p>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                            Update Loan Application
                                        </button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">Update Loan Application</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-start">
                                                        <?php
                                                        $db = db_connect();
                                                        $builderUrl = $db->table('loans');
                                                        $builderUrl->select('*');
                                                        $builderUrl->join('members', 'members.member_id = loans.member_id');
                                                        $builderUrl->where('members.member_id', $retailers['member_id']);
                                                        $query = $builderUrl->get();
                                                        $actonUrl = base_url() . "loan-create";

                                                        // ✅ If loans found, update the URL based on status
                                                        foreach ($query->getResult() as $rowUrl) {
                                                            if (in_array($rowUrl->loan_status, ['FI Success', 'Approved', 'Disbursed', 'Completed', 'Rejected', 'Disbursed Verified'])) {
                                                                $actonUrl = base_url() . "update-loan";
                                                                break; // no need to check further once one match found
                                                            }
                                                        }
                                                        ?>
                                                        <form class="row g-3" action="<?= $actonUrl ?>" method="post">
                                                            <?= csrf_field('auth') ?>
                                                            <?php if ($retailers['eli_run'] === "Y") { ?>

                                                                <div class="col-6">
                                                                    <label for="inputAddress" class="form-label">Loan Amount</label>
                                                                    <input type="text" class="form-control" name="loan_amount" id="loan_amount" value="<?= $eligible_amount ?>" readonly>
                                                                </div>
                                                                <div class="col-6">
                                                                    <label for="inputAddress" class="form-label">ROI</label>
                                                                    <input type="text" class="form-control" name="roi" id="roi" value="<?= $roi ?>">
                                                                </div>
                                                                <div class="col-6">
                                                                    <label for="inputAddress" class="form-label">Loan Tenure</label>
                                                                    <input type="text" class="form-control" name="tenure" id="tenure" value="<?= $tenure ?>">
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <label for="inputState" class="form-label">Status</label>
                                                                    <select id="inputState" class="form-select" name="status" required>
                                                                        <option selected disabled value="">Choose</option>
                                                                        <option value="FI Success">Field Inspection Success</option>
                                                                        <?php
                                                                        $db = db_connect();
                                                                        $builder = $db->table('loans');
                                                                        $builder->select('*');
                                                                        $builder->join('members', 'members.member_id = loans.member_id');
                                                                        $builder->where('members.member_id', $retailers['member_id']);
                                                                        $query = $builder->get();
                                                                        foreach ($query->getResult() as $row) {

                                                                        ?>
                                                                            <option value="Approved" <?php if ($row->loan_status === 'Approved') echo 'selected="selected"'; ?><?php if ($row->loan_status === 'Disbursed' || $row->loan_status === 'Completed' || $row->loan_status === 'Rejected' || $row->loan_status === 'Disbursed Verified') echo 'disabled'; ?>>Approved</option>
                                                                            <option value="Disbursed Verified" <?php if ($row->loan_status === 'Disbursed Verified') echo 'selected="selected"'; ?><?php if ($row->loan_status === 'Disbursed' || $row->loan_status === 'Completed' || $row->loan_status === 'Rejected' || $row->loan_status === 'FI Success') echo 'disabled'; ?>>Disbursed Verified</option>
                                                                            <option value="Disbursed" <?php if ($row->loan_status === 'Disbursed') echo 'selected="selected"'; ?><?php if ($row->loan_status === 'Completed' || $row->loan_status === 'Rejected' || $row->loan_status === 'FI Success') echo 'disabled'; ?>>Disbursed</option>
                                                                            <option value="Completed" <?php if ($row->loan_status === 'Completed') echo 'selected="selected"'; ?><?php if ($row->loan_status === 'Rejected' || $row->loan_status === 'FI Success') echo 'disabled'; ?>>Closed</option>
                                                                            <option value="Rejected" <?php if ($row->loan_status === 'Rejected') echo 'selected="selected"'; ?><?php if ($row->loan_status === 'Rejected' || $row->loan_status === 'Completed' || $row->loan_status === 'FI Success') echo 'disabled'; ?>>Rejected</option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>

                                                                <input type="hidden" value="<?= $retailers['member_id'] ?>" name="memberID">
                                                                <input type="hidden" value="<?= $retailers['groupId'] ?>" name="groupID">
                                                                <input type="hidden" value="<?= $retailers['mobile'] ?>" name="mobile">
                                                                <input type="hidden" value="<?= $retailers['agent'] ?>" name="employeeID">
                                                                <?php
                                                                $db = db_connect();
                                                                $builder = $db->table('loans');
                                                                $builder->select('*');
                                                                $builder->join('members', 'members.member_id = loans.member_id');
                                                                $builder->where('members.member_id', $retailers['member_id']);
                                                                $query = $builder->get();
                                                                foreach ($query->getResult() as $row) {

                                                                ?>
                                                                    <input type="hidden" value="<?= $row->applicationID ?>" name="applicationid">
                                                                <?php } ?>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                                </div>

                                                            <?php } else { ?>
                                                                <div class="col-md-12">
                                                                    <p class="text-danger">Please run the eligibility check first to update the loan application.</p>
                                                                </div>
                                                            <?php } ?>
                                                        </form>
                                                    </div>
                                                    <!-- <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary">Understood</button>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal -->
                                    </div>
                                </div>
                            </div>
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
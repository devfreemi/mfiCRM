<?php include 'fragments/head.php'; ?>
<?php include 'fragments/sidebar.php'; ?>


<div class="main">

    <?php include 'fragments/nav.php'; ?>
    <main class="content">
        <div class="container-fluid p-0">

            <div class="container mt-5">
                <?php
                // get member_id from URL segment
                // connect to DB
                $db = db_connect();
                $builder = $db->table('members'); // replace with your table name

                $builder->where('member_id', $member_id);
                $member = $builder->get()->getRow();

                ?>
                <div class="row justify-content-center">

                    <div class="col-md-10">
                        <div class="row">
                            <?php if (session()->getFlashdata('success')): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <?= session()->getFlashdata('success') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>

                            <?php if (session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?= session()->getFlashdata('error') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>
                            <div class="col-md-6">
                                <div class="card shadow-lg border-0 rounded-3 mt-4">
                                    <div class="card-header bg-dark text-light text-center">
                                        <h5 class="mb-0 text-light">Member Profile</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">

                                            <!-- Dummy Profile Image -->
                                            <div class="me-3">
                                                <img src="https://eu.ui-avatars.com/api/?name=<?= urlencode($member->panName) ?>&size=80&background=random"
                                                    alt="User Avatar"
                                                    class="rounded-circle border border-2 shadow-sm"
                                                    style="width:80px; height:80px; object-fit:cover;">
                                            </div>

                                            <div>
                                                <h5 class="mb-0"><?= esc($member->panName) ?></h5>
                                                <small class="text-muted">Member ID: <?= esc($member->member_id) ?></small>
                                            </div>
                                        </div>

                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex justify-content-between">
                                                <span><i class="bi bi-calendar3 me-2"></i> Date of Birth</span>
                                                <strong><?= esc($member->userDOB ?? '-') ?></strong>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between">
                                                <span><i class="bi bi-gender-ambiguous me-2"></i> Gender</span>
                                                <strong><?= esc($member->gender ?? '-') ?></strong>
                                            </li>
                                        </ul>
                                        <!-- 🔐 Security Disclaimer -->
                                        <p class="text-center mb-0 mt-3" style="font-size: 0.85rem; color:#555;">
                                            <i class="fas fa-shield-alt me-1"
                                                style="color:#ff4d4f; text-shadow: 0 0 4px rgba(255,77,79,0.7);"></i>
                                            <span style="font-weight:500;">Strictly confidential – unauthorized sharing is prohibited.</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card shadow-lg border-0 rounded-3 mt-4">
                                    <div class="card-header bg-primary text-white text-center">
                                        <h4 class="mb-0 text-white">Update Insurance Fee</h4>

                                    </div>
                                    <div class="card-body p-4">
                                        <form action="<?= base_url() ?>insurance-update" method="post">

                                            <!-- Member ID -->
                                            <div class="mb-3">
                                                <label for="member_id" class="form-label fw-bold">Member ID</label>
                                                <input type="text" class="form-control" id="member_id"
                                                    name="member_id" placeholder="Enter Member ID" value="<?= esc($member_id) ?>" readonly required>
                                            </div>

                                            <!-- Insurance Fee -->
                                            <div class="mb-3">
                                                <label for="insurance_fee" class="form-label fw-bold">Insurance Fee</label>
                                                <input type="number" step="0.01" class="form-control" id="insurance_fee"
                                                    name="insurance_fee" placeholder="Enter Insurance Fee" required>
                                            </div>

                                            <!-- Submit Button -->
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-success btn-lg">
                                                    <i class="bi bi-save"></i> Update Insurance
                                                </button>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
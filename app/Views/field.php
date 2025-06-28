<?php include 'fragments/head.php'; ?>
<?php include 'fragments/sidebar.php'; ?>


<link href="https://cdn.datatables.net/2.1.0/css/dataTables.bootstrap5.css" rel="stylesheet">
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.0/js/dataTables.bootstrap5.js"></script>

<div class="main">

    <?php include 'fragments/nav.php'; ?>
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-xl-12 col-xxl-12 d-flex">
                    <div class="w-100">
                        <div class="position-relative mb-2">
                            <img src="<?= esc($retailers['image']) ?>" class="img-fluid w-100 rounded bg-shop" alt="Shop Image">

                            <!-- Profile Picture Overlay -->
                            <div class=" bottom-0 start-0 translate-middle-y ms-4 mb-2">
                                <img src="https://ui-avatars.com/api/?background=random&&rounded=true&&name=<?= esc($retailers['name']) ?>" class="rounded-circle border border-white border-3 shadow" alt="Profile" style="width: 120px; height: 120px;">
                            </div>
                        </div>
                        <h2 class="mb-4">Field Inspection Feedback Form</h2>
                        <?php if ($serverError = session('server_error')): ?>
                            <div class="alert alert-danger">
                                <strong>⚠ Error:</strong> <?= esc($serverError) ?>
                            </div>
                        <?php endif; ?>
                        <form action="<?= base_url() ?>submit-fi" method="POST">

                            <!-- Inspector & Member Info -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <strong>Inspector & Member Details</strong>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="member_id" class="form-label">Member ID</label>
                                        <input type="text" class="form-control" name="member_id" id="member_id" value="<?= esc($retailers['member_id']) ?>" readonly required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="fiInspector_name" class="form-label">Field Inspector Name</label>
                                        <select class="form-control" name="fiInspector_name_select" id="fiInspector_name_select" required>
                                            <option selected value="" disabled>--FI Officer Name--</option>
                                            <?php
                                            $db = \Config\Database::connect();
                                            $builder2 = $db->table('employees');
                                            $builder2->select('*');

                                            $employees = $builder2->get()->getResult();
                                            ?>
                                            <?php foreach ($employees as $row2) : ?>
                                                <option value="<?= $row2->employeeID ?>"><?= $row2->name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <input type="hidden" name="fiInspector_name" id="fiInspector_name">
                                        <!-- <input type="text" class="form-control" name="fiInspector_name" id="fiInspector_name" required> -->
                                    </div>
                                </div>
                            </div>

                            <!-- Retailer Inspection -->
                            <div class="card mb-4">
                                <div class="card-header"><strong>Retailer Inspection</strong></div>
                                <div class="card-body">
                                    <!-- Retailer present -->
                                    <label class="form-label">Retailer present at the time of visit</label><br>
                                    <input class="form-check-input" type="radio" name="retailer_present" value="Yes" id="retailer_present_yes" required>
                                    <label for="retailer_present_yes">Yes</label>
                                    <input class="form-check-input" type="radio" name="retailer_present" value="No" id="retailer_present_no">
                                    <label for="retailer_present_no">No</label>

                                    <!-- Retailer behavior -->
                                    <br><label class="form-label mt-3">Retailer behavior was professional</label><br>
                                    <input class="form-check-input" type="radio" name="retailer_behavior_professional" value="Yes" id="retailer_behavior_yes" required>
                                    <label for="retailer_behavior_yes">Yes</label>
                                    <input class="form-check-input" type="radio" name="retailer_behavior_professional" value="No" id="retailer_behavior_no">
                                    <label for="retailer_behavior_no">No</label>

                                    <!-- Retailer awareness -->
                                    <br><label class="form-label mt-3">Retailer is aware of products and schemes</label><br>
                                    <input class="form-check-input" type="radio" name="retailer_aware_products" value="Yes" id="retailer_awareness_yes" required>
                                    <label for="retailer_awareness_yes">Yes</label>
                                    <input class="form-check-input" type="radio" name="retailer_aware_products" value="No" id="retailer_awareness_no">
                                    <label for="retailer_awareness_no">No</label>


                                </div>
                            </div>

                            <!-- Shop Inspection -->
                            <div class="card mb-4">
                                <div class="card-header"><strong>Retailer Shop Inspection</strong></div>
                                <div class="card-body">
                                    <!-- Shop clean -->
                                    <label class="form-label">Shop is clean and well maintained</label><br>
                                    <input class="form-check-input" type="radio" name="shop_clean" value="Yes" id="shop_clean_yes" required>
                                    <label for="shop_clean_yes">Yes</label>
                                    <input class="form-check-input" type="radio" name="shop_clean" value="No" id="shop_clean_no">
                                    <label for="shop_clean_no">No</label>

                                    <!-- Products displayed -->
                                    <br><label class="form-label mt-3">Products are properly displayed</label><br>
                                    <input class="form-check-input" type="radio" name="products_displayed" value="Yes" id="product_display_yes" required>
                                    <label for="product_display_yes">Yes</label>
                                    <input class="form-check-input" type="radio" name="products_displayed" value="No" id="product_display_no">
                                    <label for="product_display_no">No</label>

                                    <!-- Stock -->
                                    <br><label class="form-label mt-3">Adequate stock available</label><br>
                                    <input class="form-check-input" type="radio" name="stock_available" value="Yes" id="stock_yes" required>
                                    <label for="stock_yes">Yes</label>
                                    <input class="form-check-input" type="radio" name="stock_available" value="No" id="stock_no">
                                    <label for="stock_no">No</label>

                                    <!-- Promotional -->
                                    <br><label class="form-label mt-3">Promotional materials visible</label><br>
                                    <input class="form-check-input" type="radio" name="promo_materials_visible" value="Yes" id="promo_yes" required>
                                    <label for="promo_yes">Yes</label>
                                    <input class="form-check-input" type="radio" name="promo_materials_visible" value="No" id="promo_no">
                                    <label for="promo_no">No</label>

                                    <!-- Location -->
                                    <br><label class="form-label mt-3">Shop location is easily accessible</label><br>
                                    <input class="form-check-input" type="radio" name="location_accessible" value="Yes" id="shop_accessible_yes" required>
                                    <label for="shop_accessible_yes">Yes</label>
                                    <input class="form-check-input" type="radio" name="location_accessible" value="No" id="shop_accessible_no">
                                    <label for="shop_accessible_no">No</label>
                                </div>
                            </div>

                            <!-- Payment Behavior -->
                            <div class="card mb-4">
                                <div class="card-header"><strong>Retailer's Payment Behavior</strong></div>
                                <div class="card-body">
                                    <label class="form-label">Payment behavior</label><br>
                                    <input class="form-check-input" type="radio" name="payment_behavior" value="Pays on time" id="payment1" required>
                                    <label for="payment1">Pays on time</label>
                                    <input class="form-check-input" type="radio" name="payment_behavior" value="Delayed payments" id="payment2">
                                    <label for="payment2">Delayed payments</label>
                                    <input class="form-check-input" type="radio" name="payment_behavior" value="Requires follow-up" id="payment3">
                                    <label for="payment3">Requires follow-up</label>
                                    <input class="form-check-input" type="radio" name="payment_behavior" value="Makes advance payments" id="payment4">
                                    <label for="payment4">Makes advance payments</label>
                                </div>
                            </div>

                            <!-- Shop & House Ownership -->
                            <div class="card mb-4">
                                <div class="card-header"><strong>Shop & House Ownership</strong></div>
                                <div class="card-body">
                                    <!-- Shop Ownership -->
                                    <label class="form-label">Shop ownership</label><br>
                                    <input class="form-check-input" type="radio" name="shop_ownership" value="Owned" id="shop_own" required>
                                    <label for="shop_own">Owned</label>
                                    <input class="form-check-input" type="radio" name="shop_ownership" value="Rented" id="shop_rent">
                                    <label for="shop_rent">Rented</label>

                                    <!-- House Ownership -->
                                    <br><label class="form-label mt-3">House ownership</label><br>
                                    <input class="form-check-input" type="radio" name="house_ownership" value="Owned" id="house_own" required>
                                    <label for="house_own">Owned</label>
                                    <input class="form-check-input" type="radio" name="house_ownership" value="Rented" id="house_rent">
                                    <label for="house_rent">Rented</label>
                                </div>
                            </div>
                            <!-- Documents Received from Retailer -->
                            <!-- Documents Received from Retailer -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <strong>Documents Received from Retailer</strong>
                                </div>
                                <div class="card-body">
                                    <label class="form-label">Select all received and verified documents:</label><br>

                                    <div class="row">
                                        <!-- Personal Documents -->
                                        <div class="col-md-6">
                                            <h6 class="text-danger mb-2">Personal Documents</h6>
                                            <div class="form-check"><input class="form-check-input" type="checkbox" name="documents_received[]" value="PAN Card" id="doc_pan"><label class="form-check-label" for="doc_pan">PAN Card</label></div>
                                            <div class="form-check"><input class="form-check-input" type="checkbox" name="documents_received[]" value="Aadhaar Card" id="doc_aadhaar"><label class="form-check-label" for="doc_aadhaar">Aadhaar Card</label></div>
                                            <div class="form-check"><input class="form-check-input" type="checkbox" name="documents_received[]" value="Voter ID" id="doc_voter"><label class="form-check-label" for="doc_voter">Voter ID</label></div>
                                            <div class="form-check"><input class="form-check-input" type="checkbox" name="documents_received[]" value="Electricity Bill" id="doc_elec"><label class="form-check-label" for="doc_elec">Electricity Bill</label></div>
                                            <div class="form-check"><input class="form-check-input" type="checkbox" name="documents_received[]" value="Property Tax Receipt" id="doc_tax"><label class="form-check-label" for="doc_tax">Property Tax Receipt</label></div>
                                            <div class="form-check"><input class="form-check-input" type="checkbox" name="documents_received[]" value="Rent Agreement (House)" id="doc_rent1"><label class="form-check-label" for="doc_rent1">Rent Agreement (House)</label></div>
                                        </div>

                                        <!-- Business Documents -->
                                        <div class="col-md-6">
                                            <h6 class="text-success mb-2">Business Documents</h6>
                                            <div class="form-check"><input class="form-check-input" type="checkbox" name="documents_received[]" value="Trade License" id="doc_license"><label class="form-check-label" for="doc_license">Trade License</label></div>
                                            <div class="form-check"><input class="form-check-input" type="checkbox" name="documents_received[]" value="Shop Electricity Bill" id="doc_shop_elec"><label class="form-check-label" for="doc_shop_elec">Shop Electricity Bill</label></div>
                                            <div class="form-check"><input class="form-check-input" type="checkbox" name="documents_received[]" value="Shop Rent Agreement" id="doc_shop_rent"><label class="form-check-label" for="doc_shop_rent">Shop Rent Agreement</label></div>
                                            <div class="form-check"><input class="form-check-input" type="checkbox" name="documents_received[]" value="GST Certificate" id="doc_gst"><label class="form-check-label" for="doc_gst">GST Certificate</label></div>
                                            <div class="form-check"><input class="form-check-input" type="checkbox" name="documents_received[]" value="Purchase Bills" id="doc_purchase"><label class="form-check-label" for="doc_purchase">Purchase Bills</label></div>
                                            <div class="form-check"><input class="form-check-input" type="checkbox" name="documents_received[]" value="Sale Bills" id="doc_sale"><label class="form-check-label" for="doc_sale">Sale Bills</label></div>
                                            <div class="form-check"><input class="form-check-input" type="checkbox" name="documents_received[]" value="Income Tax Return" id="doc_itr"><label class="form-check-label" for="doc_itr">Income Tax Return</label></div>
                                            <div class="form-check"><input class="form-check-input" type="checkbox" name="documents_received[]" value="Bank Statement" id="doc_bank"><label class="form-check-label" for="doc_bank">Bank Statement</label></div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="card mb-4">
                                <div class="card-header">
                                    <strong>Document Verification</strong>
                                </div>
                                <div class="card-body">
                                    <label class="form-label">Are all documents verified with originals?</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="documents_verified" value="Yes" id="docs_yes" required>
                                        <label class="form-check-label" for="docs_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="documents_verified" value="No" id="docs_no">
                                        <label class="form-check-label" for="docs_no">No</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Inspector Comments -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <strong>Inspector Comments</strong>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="inspector_comments" class="form-label">Any comments or observations</label>
                                        <textarea class="form-control" name="inspector_comments" id="inspector_comments" rows="4" placeholder="Write here..."></textarea>
                                    </div>
                                </div>
                            </div>
                            <!-- Submit -->
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Submit Feedback</button>
                            </div>
                        </form>

                    </div>
                </div>


            </div>





        </div>
    </main>
    <script>
        document.getElementById('fiInspector_name_select').addEventListener('change', function() {
            const selectedText = this.options[this.selectedIndex].text;
            document.getElementById('fiInspector_name').value = selectedText;
        });
    </script>
    <?php include 'fragments/footer.php'; ?>
</div>
</div>
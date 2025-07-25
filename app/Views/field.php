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
                        <form action="<?= base_url() ?>submit-fi" method="POST" enctype="multipart/form-data">
                            <!-- Member's Details Verification -->

                            <input type="text" value="<?= esc($retailers['groupId']) ?>" name="groupId" hidden>
                            <input type="text" value="<?= esc($retailers['member_id']) ?>" name="member_id" hidden>
                            <input type="text" value="<?= esc($retailers['agent']) ?>" name="agent" hidden>
                            <input type="text" value="<?= esc($retailers['mobile']) ?>" name="mobile" hidden>
                            <?php
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
                            }
                            ?>
                            <input type="text" value="<?= $eligible_amount ?>" name="eligible_amount" hidden>
                            <input type="text" value="<?= $roi ?>" name="roi" hidden>
                            <input type="text" value="<?= $tenure ?>" name="tenure" hidden>
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
                            <div class="card mb-4">
                                <div class="card-header">
                                    <strong>Member's Details Verification</strong>
                                </div>
                                <div class="card-body">

                                    <!-- Example for Personal Details with radio buttons -->
                                    <div class="mb-3 row align-items-center">
                                        <div class="col-md-8">
                                            <label class="form-label">Name: <strong><?= esc($retailers['name']) ?></strong></label>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="verified_fields[name]" id="name_yes" value="Yes" required>
                                                <label class="form-check-label" for="name_yes">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="verified_fields[name]" id="name_no" value="No">
                                                <label class="form-check-label" for="name_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3 row align-items-center">
                                        <div class="col-md-8">
                                            <label class="form-label">Mobile: <strong><?= esc($retailers['mobile']) ?></strong></label>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="verified_fields[mobile]" id="mobile_yes" value="Yes" required>
                                                <label class="form-check-label" for="mobile_yes">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="verified_fields[mobile]" id="mobile_no" value="No">
                                                <label class="form-check-label" for="mobile_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3 row align-items-center">
                                        <div class="col-md-8">
                                            <label class="form-label">Address: <strong><?= esc($retailers['location']) ?></strong></label>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="verified_fields[address]" id="address_yes" value="Yes" required>
                                                <label class="form-check-label" for="address_yes">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="verified_fields[address]" id="address_no" value="No">
                                                <label class="form-check-label" for="address_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Business Type -->
                                    <div class="mb-3 row align-items-center">
                                        <div class="col-md-8">
                                            <label class="form-label">Business Type: <strong><?= esc($retailers['businessType']) ?></strong></label>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="verified_fields[business_type]" id="business_type_yes" value="Yes" required>
                                                <label class="form-check-label" for="business_type_yes">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="verified_fields[business_type]" id="business_type_no" value="No">
                                                <label class="form-check-label" for="business_type_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- BUSINESS DETAILS -->
                                    <h6 class="text-success mb-3 mt-4">Business Details</h6>

                                    <!-- DAILY SALES -->
                                    <div class="mb-3 row align-items-center">
                                        <div class="col-md-8">
                                            <label class="form-label">Daily Sales: <strong>₹<?= number_format($retailers['dailySales']) ?></strong></label>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="verified_fields[daily_sales]" id="daily_sales_yes" value="Yes" required>
                                                <label class="form-check-label" for="daily_sales_yes">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="verified_fields[daily_sales]" id="daily_sales_no" value="No">
                                                <label class="form-check-label" for="daily_sales_no">No</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" step="0.01" class="form-control form-control-sm d-none" value="<?= $retailers['dailySales'] ?>" name="corrected_fields[daily_sales]" id="corrected_daily_sales" placeholder="Enter corrected daily sales">
                                        </div>
                                    </div>

                                    <!-- STOCK VALUE -->
                                    <div class="mb-3 row align-items-center">
                                        <div class="col-md-8">
                                            <label class="form-label">Stock Value: <strong>₹<?= number_format($retailers['stock']) ?></strong></label>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="verified_fields[stock_value]" id="stock_value_yes" value="Yes" required>
                                                <label class="form-check-label" for="stock_value_yes">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="verified_fields[stock_value]" id="stock_value_no" value="No">
                                                <label class="form-check-label" for="stock_value_no">No</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" step="0.01" class="form-control form-control-sm d-none" value="<?= $retailers['stock'] ?>" name="corrected_fields[stock_value]" id="corrected_stock_value" placeholder="Enter corrected stock value">
                                        </div>
                                    </div>

                                    <!-- MONTHLY PURCHASE -->
                                    <div class="mb-3 row align-items-center">
                                        <div class="col-md-8">
                                            <label class="form-label">Monthly Purchase: <strong>₹<?= number_format($retailers['month_purchase']) ?></strong></label>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="verified_fields[month_purchase]" id="month_purchase_yes" value="Yes" required>
                                                <label class="form-check-label" for="month_purchase_yes">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="verified_fields[month_purchase]" id="month_purchase_no" value="No">
                                                <label class="form-check-label" for="month_purchase_no">No</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" step="0.01" class="form-control form-control-sm d-none" value="<?= $retailers['month_purchase'] ?>" name="corrected_fields[month_purchase]" id="corrected_month_purchase" placeholder="Enter corrected monthly purchase">
                                        </div>
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
                            <!-- GPS & Image Capture Section -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <strong>Location & Photo Capture</strong>
                                </div>
                                <div class="card-body">

                                    <!-- Location -->
                                    <div class="card mb-4">
                                        <div class="card-header"><strong>📍 GPS Location</strong></div>
                                        <div class="card-body">
                                            <input type="hidden" name="latitude" id="latitude">
                                            <input type="hidden" name="longitude" id="longitude">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <input type="text" class="form-control text-success fw-bold" readonly required name="place_name" id="place_name">
                                                    </div>

                                                    <button type="button" onclick="detectLocation()" class="btn btn-outline-primary col-6">Detect Location</button>
                                                </div>
                                            </div>
                                            <span id="loader" class="ms-2 text-muted" style="display: none;">⏳ Fetching location...</span>

                                        </div>
                                    </div>

                                    <!-- Image Capture -->
                                    <div class="mb-3">
                                        <label class="form-label">Shop Photo (Exterior / Interior)</label>
                                        <input type="file" name="shop_photo" accept="image/*" capture="environment" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Selfie with Retailer</label>
                                        <input type="file" name="selfie_with_owner" accept="image/*" capture="user" class="form-control" required>
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
    <script>
        const fields = ['daily_sales', 'stock_value', 'month_purchase'];

        fields.forEach(field => {
            const yes = document.getElementById(`${field}_yes`);
            const no = document.getElementById(`${field}_no`);
            const input = document.getElementById(`corrected_${field}`);

            yes.addEventListener('change', () => {
                input.classList.add('d-none');
                // input.value = '';
            });

            no.addEventListener('change', () => {
                input.classList.remove('d-none');
            });
        });
    </script>
    <!-- <script>
        function detectLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(async function(position) {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;

                    document.getElementById("latitude").value = lat;
                    document.getElementById("longitude").value = lon;

                    // Send lat/lon to your PHP endpoint
                    const response = await fetch(`/get-place-name?lat=${lat}&lon=${lon}`);
                    const data = await response.json();

                    const placeName = data.place_name || "Unknown";
                    document.getElementById("place_name").value = placeName;
                    document.getElementById("place_name_text").textContent = placeName;
                });
            }
        }
    </script> -->
    <script>
        function detectLocation() {
            const loader = $("#loader");
            const btn = $("#detectBtn");

            loader.show();
            // btn.prop("disabled", true); // Uncomment if you want to disable button

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;

                    $("#latitude").val(lat);
                    $("#longitude").val(lon);

                    $.ajax({
                        url: "/get-place-name",
                        type: "GET",
                        data: {
                            lat: lat,
                            lon: lon
                        },
                        success: function(data) {
                            const placeName = data.place_name || "Unknown";
                            $("#place_name").val(placeName);
                            $("#place_name_text").text(placeName);
                        },
                        error: function() {
                            alert("Error fetching location name.");
                        },
                        complete: function() {
                            loader.hide();
                            // btn.prop("disabled", false);
                        }
                    });

                }, function(error) {
                    alert("Geolocation error: " + error.message);
                    loader.hide();
                    // btn.prop("disabled", false);
                });
            } else {
                alert("Geolocation not supported.");
                loader.hide();
                // btn.prop("disabled", false);
            }
        }
    </script>



    <?php include 'fragments/footer.php'; ?>
</div>
</div>
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
                                <img src="https://avatar.iran.liara.run/username?username=<?= esc($retailers['name']) ?>" class="rounded-circle border border-white border-3 shadow" alt="Profile" style="width: 120px; height: 120px;">
                            </div>
                        </div>
                        <h2 class="mb-4">Field Inspection Feedback Form</h2>

                        <form action="/submit-feedback" method="POST">

                            <!-- Retailer Inspection -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <strong>Retailer Inspection</strong>
                                </div>
                                <div class="card-body">
                                    <label class="form-label">Retailer present at the time of visit</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="retailer_present" value="Yes" id="retailer_present_yes">
                                        <label class="form-check-label" for="retailer_present_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="retailer_present" value="No" id="retailer_present_no">
                                        <label class="form-check-label" for="retailer_present_no">No</label>
                                    </div>

                                    <br><label class="form-label mt-3">Retailer behavior was professional</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="retailer_behavior" value="Yes" id="retailer_behavior_yes">
                                        <label class="form-check-label" for="retailer_behavior_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="retailer_behavior" value="No" id="retailer_behavior_no">
                                        <label class="form-check-label" for="retailer_behavior_no">No</label>
                                    </div>

                                    <br><label class="form-label mt-3">Retailer is aware of products and schemes</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="retailer_awareness" value="Yes" id="retailer_awareness_yes">
                                        <label class="form-check-label" for="retailer_awareness_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="retailer_awareness" value="No" id="retailer_awareness_no">
                                        <label class="form-check-label" for="retailer_awareness_no">No</label>
                                    </div>

                                    <br><label class="form-label mt-3">Retailer needs training</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="retailer_training" value="Yes" id="retailer_training_yes">
                                        <label class="form-check-label" for="retailer_training_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="retailer_training" value="No" id="retailer_training_no">
                                        <label class="form-check-label" for="retailer_training_no">No</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Shop Inspection -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <strong>Retailer Shop Inspection</strong>
                                </div>
                                <div class="card-body">
                                    <label class="form-label">Shop is clean and well maintained</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="shop_clean" value="Yes" id="shop_clean_yes">
                                        <label class="form-check-label" for="shop_clean_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="shop_clean" value="No" id="shop_clean_no">
                                        <label class="form-check-label" for="shop_clean_no">No</label>
                                    </div>

                                    <br><label class="form-label mt-3">Products are properly displayed</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="product_display" value="Yes" id="product_display_yes">
                                        <label class="form-check-label" for="product_display_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="product_display" value="No" id="product_display_no">
                                        <label class="form-check-label" for="product_display_no">No</label>
                                    </div>

                                    <br><label class="form-label mt-3">Adequate stock available</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="stock_available" value="Yes" id="stock_yes">
                                        <label class="form-check-label" for="stock_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="stock_available" value="No" id="stock_no">
                                        <label class="form-check-label" for="stock_no">No</label>
                                    </div>

                                    <br><label class="form-label mt-3">Promotional materials visible</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="promo_visible" value="Yes" id="promo_yes">
                                        <label class="form-check-label" for="promo_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="promo_visible" value="No" id="promo_no">
                                        <label class="form-check-label" for="promo_no">No</label>
                                    </div>

                                    <br><label class="form-label mt-3">Shop location is easily accessible</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="shop_accessible" value="Yes" id="shop_accessible_yes">
                                        <label class="form-check-label" for="shop_accessible_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="shop_accessible" value="No" id="shop_accessible_no">
                                        <label class="form-check-label" for="shop_accessible_no">No</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Behavior -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <strong>Retailer's Payment Behavior</strong>
                                </div>
                                <div class="card-body">
                                    <label class="form-label">Payment behavior</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="payment_behavior" value="Pays on time" id="payment1">
                                        <label class="form-check-label" for="payment1">Pays on time</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="payment_behavior" value="Delayed payments" id="payment2">
                                        <label class="form-check-label" for="payment2">Delayed payments</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="payment_behavior" value="Requires follow-up" id="payment3">
                                        <label class="form-check-label" for="payment3">Requires follow-up</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="payment_behavior" value="Makes advance payments" id="payment4">
                                        <label class="form-check-label" for="payment4">Makes advance payments</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Shop Ownership -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <strong>Shop & House Ownership</strong>
                                </div>
                                <div class="card-body">
                                    <label class="form-label">Shop ownership</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="shop_ownership" value="Owned" id="shop_own">
                                        <label class="form-check-label" for="shop_own">Owned</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="shop_ownership" value="Rented" id="shop_rent">
                                        <label class="form-check-label" for="shop_rent">Rented</label>
                                    </div>

                                    <br><label class="form-label mt-3">House ownership</label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="house_ownership" value="Owned" id="house_own">
                                        <label class="form-check-label" for="house_own">Owned</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="house_ownership" value="Rented" id="house_rent">
                                        <label class="form-check-label" for="house_rent">Rented</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Submit Feedback</button>
                            </div>
                        </form>
                    </div>
                </div>


            </div>





        </div>
    </main>

    <?php include 'fragments/footer.php'; ?>
</div>
</div>
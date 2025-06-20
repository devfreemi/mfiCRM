<?php include 'fragments/head.php'; ?>
<?php include 'fragments/sidebar.php'; ?>
<link href="https://cdn.datatables.net/2.1.0/css/dataTables.bootstrap5.css" rel="stylesheet">
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.0/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.html5.min.js"></script>



<div class="main">

    <?php include 'fragments/nav.php'; ?>
    <main class="content">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3">Add New <strong>Retailers</strong></h1>

            <div class="row">
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="col-xl-12 col-xxl-12 my-5">
                        <p class="text-center text-success fw-bold"><?= session()->getFlashdata('success') ?></p>
                    </div>
                <?php endif; ?>

                <div class="col-xl-12 col-xxl-12 d-flex">
                    <form class="row g-3" action="<?= base_url() ?>add-member" method="post" enctype="multipart/form-data">
                        <?= csrf_field('auth') ?>
                        <div class="col-md-4">
                            <label for="loan" class="form-label">Market</label>
                            <select id="market" class="form-control bg-model" name="groupId" required>
                                <option selected disabled>--Select Market--</option>
                                <?php
                                $db = \Config\Database::connect();
                                $builder = $db->table('groups');
                                $builder->select('*');

                                $market = $builder->get()->getResult();
                                ?>
                                <?php foreach ($market as $row) : ?>
                                    <option value="<?= $row->g_id ?>"><?= $row->g_name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <input type="hidden" name="groupName" id="market_name">
                        <div class="col-md-4">
                            <label for="loan" class="form-label">RM Name</label>
                            <select class="form-control bg-model" name="agent" required>
                                <option selected disabled>--Select RM--</option>
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
                        </div>
                        <div class="col-md-4">
                            <label for="loan" class="form-label">Member Name</label>
                            <input type="text" class="form-control bg-model" id="applicationid" name="name" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="loan" class="form-label">Mobile</label>
                            <input type="text" class="form-control bg-model" id="applicationid" name="mobile" maxlength="10" pattern="\d*" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="loan" class="form-label">PAN Number</label>
                            <input type="text" class="form-control bg-model" id="applicationid" name="panNo" maxlength="10" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="loan" class="form-label">GST Number</label>
                            <input type="text" class="form-control bg-model" id="applicationid" name="gstNo" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="loan" class="form-label">CIBIL Score</label>

                            <input type="text" class="form-control bg-model" id="applicationid" name="cibil" maxlength="3" pattern="\d*" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="loan" class="form-label">Aadhaar Number</label>
                            <input type="text" class="form-control bg-model" id="applicationid" name="adhar" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="loan" class="form-label">Business Name</label>
                            <input type="text" class="form-control bg-model" id="applicationid" name="businessName" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="loan" class="form-label">Business Type</label>
                            <!-- <input type="text" class="form-control bg-model" id="applicationid" readonly name="" value=""> -->
                            <select class="form-control bg-model" name="businessType" required>
                                <option selected disabled>--Select Business Type--</option>
                                <option value="Grocery">Grocery Shop</option>
                                <option value="Stationary">Stationary Shop</option>
                                <option value="Pet_Shop">Pet Shop</option>
                                <option value="Sweet_Shop">Sweet Shop</option>
                                <option value="Pharmacy">Pharmacy</option>
                                <option value="Furniture">Furniture</option>
                                <option value="Elctronic And Appliance Shop">Electronics Shop</option>
                                <option value="Electrical">Electrical Shop</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="loan" class="form-label">Market Type</label>
                            <input type="text" class="form-control bg-model" id="applicationid" name="location" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="loan" class="form-label">Business Location</label>
                            <input type="text" class="form-control bg-model" id="applicationid" name="memberLocation" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="loan" class="form-label">Pincode</label>
                            <input type="text" class="form-control bg-model" id="applicationid" name="groupPin" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="loan" class="form-label">Current Stock</label>
                            <input type="text" class="form-control bg-model" required id="applicationid" name="stock" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="loan" class="form-label">Daily Sales</label>
                            <input type="text" class="form-control bg-model" required id="applicationid" name="daily_sales" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="loan" class="form-label">Monthly Purchase</label>
                            <input type="text" class="form-control bg-model" required id="applicationid" name="month_purchase" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="loan" class="form-label">Daily Footfall</label>
                            <input type="text" class="form-control bg-model" id="applicationid" name="footFall" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="loan" class="form-label">Current EMI</label>
                            <input type="text" class="form-control bg-model" required id="applicationid" name="previous_emi" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="loan" class="form-label">Business Established Year</label>
                            <input type="text" class="form-control bg-model " required id="applicationid" name="business_time" value="">
                        </div>


                        <div class="col-md-4">
                            <label for="loan" class="form-label">Shop Image</label>
                            <input type="file" class="form-control bg-model " accept="image/*" required id="applicationid" name="image_profile">
                        </div>
                        <!-- <input type="hidden" class="form-control bg-model " id="applicationid" name="image_profile" value=""> -->


                        <div class="text-center">
                            <button type="submit" class="btn btn-success col-md-2" id="actionBtn">Save</button>
                        </div>
                    </form>

                </div>


            </div>
        </div>
    </main>

    <?php include 'fragments/footer.php'; ?>
</div>
</div>

<script>
    document.getElementById('market').addEventListener('change', function() {
        const selectedText = this.options[this.selectedIndex].text;
        document.getElementById('market_name').value = selectedText;
    });
</script>
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

            <h1 class="h3 mb-3">List of <strong>Retailers</strong></h1>
            <div class="row">
                <div class="col-xl-12 col-xxl-12 my-5">
                    <p class="text-center text-success">Aadhaar KYC</p>
                </div>
            </div>
            <div class="row">
                <?php if (session()->getFlashdata('msg')) : ?>
                    <div class="col-xl-12 col-xxl-12 my-5">
                        <p class="text-center text-success"><?= session()->getFlashdata('msg') ?></p>
                    </div>
                <?php endif; ?>
                <div class="col-xl-12 col-xxl-12 d-flex">
                    <form action="<?php echo base_url(); ?>page/kyc-aadhaar-send-otp" method="post" class="w-100">
                        <div class="input-group mb-3">
                            <input type="number" id="aadhaarNo" name="aadhaarNo" value="" placeholder="Enter Aadhar Number" class="form-control" />
                        </div>
                        <button type="submit">Send Otp</button>
                    </form>
                </div>
                <h1>Verify OTP</h1>
                <div class="col-xl-12 col-xxl-12 d-flex">
                    <form action="<?php echo base_url(); ?>page/kyc-aadhaar-verify-otp" method="post" class="w-100">
                        <div class="input-group mb-3">
                            <input type="number" id="" name="aadhaarNo" value="" placeholder="Enter Aadhar Number" class="form-control" />
                        </div>
                        <div class="input-group mb-3">
                            <input type="number" id="" name="otp" value="" placeholder="Enter OTP " class="form-control" />
                        </div>
                        <input type="text" id="" name="requestId" value="<?= session()->getFlashdata('msg') ?>" />
                        <button type="submit">Verify Otp</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include 'fragments/footer.php'; ?>
</div>
</div>
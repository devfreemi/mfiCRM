<?php include 'fragments/head.php'; ?>
<?php include 'fragments/sidebar.php'; ?>


<div class="main">

    <?php include 'fragments/nav.php'; ?>
    <main class="content">
        <div class="container-fluid p-0">
            <!-- Loading Animation -->
            <div id="loading" class="text-center ">
                <img src="<?= base_url() ?>assets/img/illustrations/check.gif" alt="Processing Loan" class="img-fluid">
                <p class="text-center">Processing loan eligibility, please wait up to <strong>60 seconds</strong>...</p>
            </div>
            <div id="resultContainer" class="result-box">
                <h3>Input Data of Member : <strong> <?= $memberId ?></strong></h3>
                <p><strong>Stock Value:</strong> <?= esc($stock) ?></p>
                <p><strong>Daily Sales:</strong> <?= esc($daily_sales) ?></p>
                <p><strong>CIBIL Score:</strong> <?= esc($cibil_score) ?></p>
                <p><strong>Business Age:</strong> <?= esc($business_time) ?> years</p>
                <p><strong>Location:</strong> <?= esc($location) ?></p>
                <p><strong>Business Type:</strong> <?= esc($business_type) ?></p>
                <p><strong>Previous EMI:</strong> <?= esc($previous_emi) ?></p>
                <hr>
                <h3>Loan Eligibility Result</h3>
                <?php
                if (esc($result['Eligibility']) === "Eligible") { ?>

                    <p><strong>Eligibility:</strong> <?= esc($result['Eligibility']) ?></p>
                    <p><strong>Loan Amount:</strong> ₹<?= esc(number_format($result['Loan Amount'], 2)) ?></p>
                    <p><strong>Rate of Interest (ROI):</strong> <?= esc($result['ROI']) ?>%</p>
                    <p><strong>Score By NFSPL:</strong> <?= esc($result['Score']) ?></p>
                <?php } else { ?>
                    <p><strong>Eligibility:</strong> <?= esc($result['Eligibility']) ?></p>
                    <p class="text-danger"><strong>Reason:</strong> <?= esc($result['Reason']) ?></p>
                <?php  }
                ?>


                <br>
            </div>


        </div>
    </main>

    <?php include 'fragments/footer.php'; ?>
</div>
</div>
<script>
    // document.getElementById("resultContainer").style.display = "none";
    setTimeout(function() {
        document.getElementById("loading").style.display = "none";
        document.getElementById("resultContainer").style.display = "block";
    }, 15000); // Show result after 1 minute
</script>
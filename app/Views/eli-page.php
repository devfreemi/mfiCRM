<?php include 'fragments/head.php'; ?>
<?php include 'fragments/sidebar.php'; ?>


<div class="main">

    <?php include 'fragments/nav.php'; ?>
    <main class="content">
        <div class="container">
            <h2>Loan Eligibility Check</h2>
            <!-- Stepwise Loan Check -->
            <div class="step-container">
                <div id="step1" class="step">
                    <div class="step-circle">1</div>
                    <!-- <i class="fas fa-file-alt"></i> -->
                    <p>Gathering Financial Data</p>
                </div>
            </div>

            <div class="step-container">
                <div id="step2" class="step">
                    <div class="step-circle">2</div>
                    <!-- <i class="fas fa-clipboard-check"></i> -->
                    <p>Verifying Loan Criteria</p>
                </div>
            </div>

            <div class="step-container">
                <div id="step3" class="step">
                    <div class="step-circle">3</div>
                    <!-- <i class="fas fa-calculator"></i> -->
                    <p>Calculating Loan Amount</p>
                </div>
            </div>


            <!-- Loading Animation -->
            <div id="loading" class="loading-section">
                <img src="<?= base_url() ?>assets/img/illustrations/check.gif" alt="Loan Processing">

                <!-- Progress Bar -->
                <div class="progress-container">
                    <div id="progress-bar" class="progress-bar"></div>
                </div>
            </div>

            <!-- Loan Result Container -->
            <div id="resultContainer" class="result-box">
                <i id="resultIcon" class="result-icon"></i>
                <p id="resultText" class="result-text"></p>

                <!-- Loan Details -->
                <div class="details-grid" id="loanDetails">
                    <div class="details-box">
                        <i class="fas fa-rupee-sign"></i>
                        <p><strong>Loan Amount</strong></p>
                        <p id="loanAmount"></p>
                    </div>
                    <div class="details-box">
                        <i class="fas fa-percentage"></i>
                        <p><strong>Interest Rate</strong></p>
                        <p id="loanInterest"></p>
                    </div>
                    <div class="details-box">
                        <i class="fas fa-star"></i>
                        <p><strong>Score By NFSPL</strong></p>
                        <p id="score"></p>
                    </div>
                </div>

                <!-- View Details Button -->
                <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#loanDetailsModal">
                    View Details
                </button>

                <!-- Bootstrap Modal -->
                <div class="modal fade" id="loanDetailsModal" tabindex="-1" aria-labelledby="loanDetailsLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="fas fa-file-invoice-dollar"></i> Loan Eligibility Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="d-flex align-items-center">
                                    <!-- <i class="fas fa-user-circle fa-3x me-3 brand-color-text"></i> -->
                                    <img src="<?= esc($image_profile) ?>" alt="" height="45" width="45">
                                    <h5 class="px-3 mb-0 fs-4 fw-bold">User ID : <span class="brand-color-text"><?= $memberId ?></span></h5>
                                </div>
                                <ul class="list-group mt-3">
                                    <li class="list-group-item"><strong>Stock Value:</strong> ₹<span id="modal-stock"></span></li>
                                    <li class="list-group-item"><strong>Daily Sales:</strong> ₹<span id="modal-daily-sales"></span></li>
                                    <li class="list-group-item"><strong>CIBIL Score:</strong> <span id="modal-cibil"></span></li>
                                    <li class="list-group-item"><strong>Business Age:</strong> <span id="modal-business-time"></span> years</li>
                                    <li class="list-group-item"><strong>Business Type:</strong> <span id="modal-business-type"></span></li>
                                    <li class="list-group-item"><strong>Location:</strong> <span id="modal-location"></span></li>
                                </ul>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <?php include 'fragments/footer.php'; ?>
</div>
</div>
<script>
    $(document).ready(function() {
        let loanData = {
            stock: <?= esc($stock) ?>,
            daily_sales: <?= esc($daily_sales) ?>,
            cibil_score: <?= esc($cibil_score) ?>,
            business_time: <?= esc($business_time) ?>,
            business_type: "<?= esc($business_type) ?>",
            location: "<?= esc($location) ?>",
            eligibility: "<?php echo esc($result['Eligibility']); ?>"

        };

        // Populate Modal Data on Click
        $("#loanDetailsModal").on("show.bs.modal", function() {
            $("#modal-stock").text(loanData.stock);
            $("#modal-daily-sales").text(loanData.daily_sales);
            $("#modal-cibil").text(loanData.cibil_score);
            $("#modal-business-time").text(loanData.business_time);
            $("#modal-business-type").text(loanData.business_type);
            $("#modal-location").text(loanData.location);
            $("#modal-loan-amount").text(loanData.loan_amount);
            $("#modal-roi").text(loanData.roi);
            $("#modal-eligibility").text(loanData.eligibility);

            // Color Indicator for Eligibility Status
            if (loanData.eligibility === "Eligible") {
                $("#modal-eligibility").addClass("text-success").removeClass("text-danger");
            } else {
                $("#modal-eligibility").addClass("text-danger").removeClass("text-success");
            }
        });
        let progress = 0;

        function updateProgressBar() {
            $("#progress-bar").animate({
                width: "100%"
            }, 30000);
        }

        function showSteps() {
            setTimeout(() => {
                $("#step1").fadeIn().css("animation", "bounceIn 1s ease-in-out");
            }, 10);

            setTimeout(() => {
                $("#step1").fadeOut(500, function() {
                    $("#step2").fadeIn().css("animation", "bounceIn 1s ease-in-out");
                });
            }, 5000);

            setTimeout(() => {
                $("#step2").fadeOut(500, function() {
                    $("#step3").fadeIn().css("animation", "bounceIn 1s ease-in-out");
                });
            }, 10000);
        }



        function showResult() {
            let isEligible = "<?php echo esc($result['Eligibility']); ?>"; // Randomly decide eligibility for demo

            $("#loading").fadeOut(500, function() {
                $("#resultContainer").fadeIn(1000);

                if (isEligible === "Eligible") {
                    console.log(isEligible);

                    $("#resultIcon").addClass("fas fa-check-circle success");
                    $("#resultText").text("Retailer is eligible for a loan.");
                    $("#loanAmount").text("<?= esc(number_format($result['Loan Amount'], 2)) ?>");
                    $("#loanInterest").text("<?= esc($result['ROI']) ?>");
                    $("#score").text("<?= esc($result['Score']) ?>");
                    $("#loanDetails").show();
                    $(".step").hide();
                } else {
                    $("#resultIcon").addClass("fas fa-times-circle error");
                    $("#resultText").text("Sorry! <?php echo esc($result['Eligibility']); ?>.(<?= esc($result['Reason']) ?>)");
                    $("#loanDetails").hide();
                    $(".step").hide();
                }
            });
        }

        // Start animations
        updateProgressBar();
        showSteps();
        setTimeout(showResult, 30000);
    });
</script>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Retail Pe Payment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body class="bg-light">
    <main class="container my-5">
        <div class="row justify-content-center" id="receiptContent">
            <div class="col-md-8">

                <?php if ($status === 'SUCCESS') : ?>
                    <div class="bg-white p-4 rounded shadow-sm text-center">
                        <div class="icon-box bg-success text-white mb-3">
                            <i class="fas fa-check fa-2x"></i>
                        </div>
                        <h4 class="text-success">EMI Payment Successful</h4>
                        <p class="text-muted">Thank you for your payment</p>

                        <hr>
                        <h5 class="fw-semibold mb-3">Transaction Details</h5>
                        <div class="text-start px-md-4">
                            <p><strong>Payment ID:</strong> <?= esc($paymentData['cf_payment_id'] ?? 'N/A') ?></p>
                            <p><strong>Amount Paid:</strong> ₹<?= esc(number_format($paymentData['payment_amount'] ?? 0, 2)) ?></p>
                            <p><strong>Payment Date:</strong> <?= date('Y-m-d H:i:s') ?></p>
                            <p><strong>Payment Mode:</strong> <?= esc($paymentData['payment_method']['upi']['upi_instrument'] ?? 'N/A') ?> (<?= esc($paymentData['payment_method']['upi']['channel'] ?? 'N/A') ?>)</p>
                            <p><strong>Loan ID:</strong> <?= esc($loanId) ?></p>
                            <p><strong>Order ID:</strong> <?= esc($orderID) ?></p>
                        </div>

                        <div class="text-center mt-4">
                            <img src="<?= base_url('assets/img/icons/brands/RetailPeLogo.png') ?>" alt="Retail Pe" style="height: 35px;">
                            <p class="text-muted small mt-2">
                                <i class="bi bi-shield-check text-success me-1"></i>
                                Transactions are 100% secure and encrypted.
                            </p>
                            <button onclick="downloadPDF()" class="btn btn-outline-success mt-3">
                                <i class="fas fa-download me-2"></i>Download Receipt
                            </button>
                        </div>
                    </div>

                <?php elseif ($status === 'PENDING') : ?>
                    <div class="bg-white p-4 rounded shadow-sm text-center">
                        <div class="icon-box bg-warning text-white mb-3">
                            <i class="fas fa-hourglass-half fa-2x"></i>
                        </div>
                        <h4 class="text-warning">EMI Payment Pending</h4>
                        <p class="text-muted">Your payment is still being processed. Please refresh the page later.</p>
                        <a href="<?= current_url() ?>" class="btn btn-warning mt-3">Refresh</a>
                    </div>

                <?php else : ?>
                    <div class="bg-white p-4 rounded shadow-sm text-center">
                        <div class="icon-box bg-danger text-white mb-3">
                            <i class="fas fa-times-circle fa-2x"></i>
                        </div>
                        <h4 class="text-danger">Payment Failed</h4>
                        <p class="text-muted">Unfortunately, your payment could not be completed.</p>
                        <a href="/" class="btn btn-danger mt-3">Try Again</a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </main>

    <!-- JS to download PDF -->
    <script>
        function downloadPDF() {
            const element = document.getElementById('receiptContent');
            const opt = {
                margin: 0.5,
                filename: 'emi_receipt_<?= esc($orderID) ?>.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'in',
                    format: 'a4',
                    orientation: 'portrait'
                }
            };
            html2pdf().set(opt).from(element).save();
        }

        // Prevent form resubmission
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
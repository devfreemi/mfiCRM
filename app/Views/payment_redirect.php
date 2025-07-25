<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Retail Pe Payment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- JQ -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- Cashfree PG -->
    <script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>

</head>

<body class="bg-light">

    <div class="container d-flex flex-column justify-content-center align-items-center">
        <div class="w-100">

            <!-- Logo and Secure Label -->
            <div class="text-center mb-3">
                <img src="<?= base_url() ?>assets/img/icons/brands/RetailPeLogo.png" alt="Retail Pe Logo" class="img-fluid" style="max-height: 60px;">
                <p class="text-muted small mt-2">
                    <i class="bi bi-shield-check text-success me-1"></i>
                    Transactions are 100% secure and encrypted.
                </p>
            </div>

            <div class="p-3 bg-white shadow rounded-4 mx-2">


                <div>
                    <div class="container">
                        <!-- QR Code Section -->

                        <div class="mx-auto text-center mt-5">
                            <div class="row mx-auto">
                                <div class="col-2"></div>
                                <div class="col-8 px-0">
                                    <div class=" " style="width: 245px;">
                                        <div class="card">
                                            <div class="card-body pb-0">
                                                <h5 class="card-title">
                                                    QR for Rs. <?= esc($amount) ?>
                                                </h5>

                                                <div class="">
                                                    <div class="" bfor="qr">
                                                        <div id="qr" class="icon qrmount"></div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <p class="alert" id="paymentMessage">

                            </p>



                        </div>
                        <!-- QR -->
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label small text-muted">Full Name</label>
                        <input type="text" class="form-control form-control-lg rounded-3" id="name" value="<?= esc($name) ?>" required readonly>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label small text-muted">Loan Account Number</label>
                        <input type="text" class="form-control form-control-lg rounded-3" id="loanID" value="<?= esc($loanID) ?>" required readonly>
                    </div>
                    <div class="mb-3">
                        <label for="orderId" class="form-label small text-muted">Order ID</label>
                        <input type="text" class="form-control form-control-lg rounded-3" id="orderId" value="<?= esc($orderID) ?>" required readonly>
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label small text-muted">Amount (₹)</label>
                        <input type="number" class="form-control form-control-lg rounded-3" id="amount" value="<?= esc($amount) ?>" required readonly>
                    </div>

                    <input type="hidden" class="form-control form-control-lg rounded-3" id="paymentSessionId" value="<?= esc($paymentSessionId) ?>" required readonly>

                    <input type="hidden" id="returnUrl" value="<?= base_url() ?>payment/conformation/gateway?orderID=<?= esc($orderID) ?>&loanId=<?= esc($loanID) ?>" />
                    <div class="d-grid">
                        <button type="button" class="btn btn-primary btn-lg rounded-3" id="renderBtn">
                            <i class="bi bi-lock-fill me-1"></i> Generate QR Code
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>





    <script>
        const paymentBtn = document.getElementById("renderBtn");

        const cashfree = Cashfree({
            mode: "production"
        });
        const paymentMessage = document.getElementById("paymentMessage");
        let qr = cashfree.create("upiQr", {
            values: {
                size: "200px",
            }
        });
        qr.mount("#qr");
        qr.on('paymentrequested', function() {
            paymentBtn.disabled = true
        })
        paymentBtn.addEventListener("click", function(e) {

            paymentMessage.innerText = "";

            paymentMessage.classList.remove("alert-danger");
            paymentMessage.classList.remove("alert-success");
            cashfree.pay({
                paymentMethod: qr,
                paymentSessionId: document.getElementById("paymentSessionId").value,

                returnUrl: document.getElementById("returnUrl").value,
            }).then(function(data) {
                paymentBtn.disabled = false
                if (data.error) {
                    paymentMessage.innerHTML = data.error.message;
                    paymentMessage.classList.add("alert-danger");
                }
                if (data.paymentDetails) {
                    paymentMessage.innerHTML = data.paymentDetails.paymentMessage;
                    paymentMessage.classList.add("alert-success");
                }
                if (data.redirect) {
                    console.log("We are redirtion");
                }

            });
        })
        // const cashfree = Cashfree({
        //     mode: "sandbox",
        // });
        // document.getElementById("renderBtn").addEventListener("click", () => {
        //     let checkoutOptions = {
        //         paymentSessionId: "<?= esc($paymentSessionId) ?>",
        //         redirectTarget: "_self",
        //     };

        //     cashfree.checkout(checkoutOptions).then((result) => {
        //         if (result.error) {
        //             // This will be true whenever user clicks on close icon inside the modal or any error happens during the payment
        //             console.log("User has closed the popup or there is some payment error, Check for Payment Status");
        //             console.log(result.error);
        //         }
        //         if (result.redirect) {
        //             // This will be true when the payment redirection page couldnt be opened in the same window
        //             // This is an exceptional case only when the page is opened inside an inAppBrowser
        //             // In this case the customer will be redirected to return url once payment is completed
        //             console.log("Payment will be redirected");
        //         }
        //         if (result.paymentDetails) {
        //             // This will be called whenever the payment is completed irrespective of transaction status
        //             console.log("Payment has been completed, Check for Payment Status");
        //             console.log(result.paymentDetails.paymentMessage);
        //         }
        //     });
        // });
    </script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
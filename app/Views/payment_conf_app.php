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
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css" />
    <link href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

</head>

<body class="bg-light">
    <main class="container my-5">
        <div class="container-fluid p-0 my-5">

            <div class="row">

                <div class="col-12 col-md-12 mx-auto my-5">
                    <!-- Logo and Secure Label -->

                    <?php
                    $orderId = $orderID;
                    $loanId = $loanId;

                    $orderId = $orderId;
                    $loanID = $loanId;
                    $paymentAmount  = $order_amount;

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://sandbox.cashfree.com/pg/orders/" . $orderId . "/payments",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "GET",
                        CURLOPT_HTTPHEADER => array(),
                    ));

                    $response = curl_exec($curl);
                    $response_decode = json_decode($response, true);
                    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                    $err = curl_error($curl);
                    // return $this->respond(['payment_created' => $response_decode], $httpcode);
                    // print_r($err);
                    // var_dump($response_decode);
                    // echo $response_decode['order_id'];
                    // echo $response_decode[0]['payment_status'];
                    // return $this->respond(['payment_created' => $response_decode], $httpcode);

                    if ($httpcode === 200) {

                        if ($response_decode[0]['payment_status'] === 'SUCCESS') {
                            # code...
                            $db = db_connect();
                            $loanID = $loanID; // From context
                            $emiTable = 'tab_' . esc($loanID);


                            $paymentAmount = (float) $response_decode[0]['payment_amount'];
                            $paymentStatus = $response_decode[0]['payment_status'];
                            $txnID = 'TXN_' . $response_decode[0]['cf_payment_id'];
                            $now = date('Y-m-d H:i:s');

                            // Get loan details
                            $loan = $db->table('loans')->where('applicationID', $loanID)->get()->getRow();
                            if (!$loan) {
                                return $this->failNotFound('Loan not found');
                            }

                            $emiPending = (int) $loan->pending_emi;
                            $loanDue = (float) $loan->loan_due;

                            $totalEmiPaid = 0;
                            $emiCountPaid = 0;

                            // Step 1: Pay active EMIs ("Due")
                            $emiRows = $db->table($emiTable)
                                ->where('reference', 'Due')
                                ->orderBy('Id', 'ASC')
                                ->get()
                                ->getResult();

                            foreach ($emiRows as $emi) {
                                if ($paymentAmount >= $emi->emi) {
                                    $db->table($emiTable)->where('Id', $emi->Id)->update([
                                        'reference'       => 'Y',
                                        'paymentStatus'   => $paymentStatus,
                                        'transactionDate' => $now,
                                        'updated_on'      => $now,
                                        'orderId'         => $orderId,
                                        'transactionId'   => $txnID,
                                        'balance'         => 0,
                                    ]);
                                    $paymentAmount -= $emi->emi;
                                    $totalEmiPaid += $emi->emi;
                                    $emiCountPaid += 1;
                                } else {
                                    // Partial payment
                                    if ($paymentAmount > 0) {
                                        $balance = $emi->balance - $paymentAmount;

                                        $db->table($emiTable)->where('Id', $emi->Id)->update([
                                            'paymentStatus'   => $paymentStatus,
                                            'transactionDate' => $now,
                                            'updated_on'      => $now,
                                            'transactionId'   => $txnID,
                                            'balance'         => $balance,
                                        ]);

                                        $totalEmiPaid += $paymentAmount;
                                        $paymentAmount = 0;
                                    }
                                    break;
                                }
                            }

                            // Step 2: If still amount left, pay future EMIs ("N")
                            if ($paymentAmount > 0) {
                                $upcomingEMIs = $db->table($emiTable)
                                    ->where('reference', 'N')
                                    ->orderBy('Id', 'ASC')
                                    ->get()
                                    ->getResult();

                                foreach ($upcomingEMIs as $emi) {
                                    if ($paymentAmount >= $emi->emi) {
                                        $db->table($emiTable)->where('Id', $emi->Id)->update([
                                            'reference'       => 'Y',
                                            'paymentStatus'   => $paymentStatus,
                                            'transactionDate' => $now,
                                            'updated_on'      => $now,
                                            'orderId'         => $orderId,
                                            'transactionId'   => $txnID,
                                            'balance'         => 0,
                                        ]);
                                        $paymentAmount -= $emi->emi;
                                        $totalEmiPaid += $emi->emi;
                                        $emiCountPaid += 1;
                                    } else {
                                        if ($paymentAmount > 0) {
                                            $balance = $emi->balance - $paymentAmount;

                                            $db->table($emiTable)->where('Id', $emi->Id)->update([
                                                'reference'       => 'Due', // this becomes next active EMI
                                                'paymentStatus'   => $paymentStatus,
                                                'transactionDate' => $now,
                                                'updated_on'      => $now,
                                                'transactionId'   => $txnID,
                                                'balance'         => $balance,
                                            ]);

                                            $totalEmiPaid += $paymentAmount;
                                            $paymentAmount = 0;
                                        }
                                        break;
                                    }
                                }
                            }
                            curl_close($curl);
                            // Step 3: Set next EMI as "Due"
                            $nextEmi = $db->table($emiTable)
                                ->where('reference', 'N')
                                ->orderBy('Id', 'ASC')
                                ->limit(1)
                                ->get()
                                ->getRow();

                            if ($nextEmi) {
                                $db->table($emiTable)
                                    ->where('Id', $nextEmi->Id)
                                    ->update(['reference' => 'Due']);
                            }

                            // Step 4: Update loan master table
                            $db->table('loans')->where('applicationID', $loanID)->update([
                                'loan_due'    => $loanDue - $totalEmiPaid,
                                'pending_emi' => $emiPending - $emiCountPaid,
                                'updated_at'  => $now,
                            ]);

                            // return $this->respond([
                            //     'message'        => 'Payment Success',
                            //     'emis_paid'      => $emiCountPaid,
                            //     'total_emi_paid' => $paymentAmount,
                            //     'payment_method' => $response_decode[0]['payment_group']
                            // ]);
                        }
                        // return $this->respond(['payment_success' => ], 200);
                    }
                    ?>
                    <div class="row g-4 justify-content-center align-items-stretch" id="receiptContent">
                        <div class="col-md-6 col-12">
                            <?php
                            if ($response_decode[0]['payment_status'] == "SUCCESS") { ?>

                                <div class="col-12 col-md-8 offset-md-2">
                                    <div class="bg-white p-4 rounded shadow-sm text-center">

                                        <div class="icon-box bg-success text-white">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <!-- Header -->
                                        <div class="d-flex align-items-center justify-content-center mb-4">

                                            <div>
                                                <h5 class="text-success mb-0">EMI Payment Successful</h5>
                                                <small class="text-muted">Thank you for your payment</small>
                                            </div>
                                        </div>

                                        <hr>

                                        <!-- Order Details -->
                                        <div class="mb-2 text-center">
                                            <h5 class="fw-semibold mb-3">Order Details</h5>
                                        </div>

                                        <div class="d-flex flex-column gap-2 px-md-5" style="max-width: 100%;">
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-bold text-success">Payment ID:</span>
                                                <span class="text-dark fw-bold"><?= $response_decode[0]['cf_payment_id'] ?? 'N/A' ?></span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-bold text-success">Payment Amount:</span>
                                                <span class="text-dark fw-bold">₹<?= number_format($response_decode[0]['payment_amount']) ?>.00</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-bold text-success">Number Of EMI Paid:</span>
                                                <span class="text-dark fw-bold"><?= $emiCountPaid ?></span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-bold text-success">Payment Date:</span>
                                                <span class="text-dark fw-bold">
                                                    <?= date('Y-m-d H:i:s'); ?>
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-bold text-success">Payment Mode:</span>
                                                <span class="text-dark fw-bold"><?= $response_decode[0]['payment_method']['upi']['upi_instrument'] ?? 'N/A' ?>(<?= $response_decode[0]['payment_method']['upi']['channel'] ?? 'N/A' ?>)</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-bold text-success">Loan ID:</span>
                                                <span class="text-dark fw-bold"><?= $loanId ?></span>
                                            </div>
                                            <div class="text-center mb-3">
                                                <img src="<?= base_url() ?>assets/img/icons/brands/RetailPeLogo.png" alt="Retail Pe Logo" class="img-fluid" style="max-height: 35px;">
                                                <p class="text-muted small mt-2">
                                                    <i class="bi bi-shield-check text-success me-1"></i>
                                                    Transactions are 100% secure and encrypted.
                                                </p>
                                            </div>
                                        </div>
                                        <!-- Download Receipt Button -->
                                        <div class="mt-4">
                                            <button onclick="downloadPDF()" class="btn btn-outline-success">
                                                <i class="fas fa-download me-2"></i>Download Receipt
                                            </button>
                                        </div>
                                    </div>
                                </div>





                            <?php  } elseif ($response_decode[0]['payment_status'] == "PENDING") { ?>


                                <div class="col-12">
                                    <div class="status-card bg-white">
                                        <div class="icon-box bg-warning text-white">
                                            <i class="fas fa-hourglass-half"></i>
                                        </div>
                                        <h4 class="text-warning">EMI Payment Pending</h4>
                                        <p>Your EMI payment is still being processed. Please wait while we confirm the transaction.</p>
                                        <a href="/" class="btn bg-brand text-white mt-3">Refresh</a>
                                    </div>
                                </div>
                            <?php } else { ?>

                                <div class="col-12">
                                    <div class="status-card bg-white">
                                        <div class="icon-box bg-danger text-white">
                                            <i class="fas fa-times-circle"></i>
                                        </div>
                                        <h4 class="text-danger">Payment Failed</h4>
                                        <p>We couldn't complete your transaction. Please try again.</p>
                                        <a href="/" class="btn bg-brand text-white mt-3">Retry</a>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>





        </div>
    </main>
    <script>
        function downloadPDF() {
            const element = document.getElementById('receiptContent');
            const opt = {
                margin: 0.5,
                filename: 'emi_receipt.pdf',
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
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
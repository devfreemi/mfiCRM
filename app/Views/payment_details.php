<?php include 'fragments/head.php'; ?>
<?php include 'fragments/sidebar.php'; ?>


<link href="https://cdn.datatables.net/2.1.0/css/dataTables.bootstrap5.css" rel="stylesheet">
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.0/js/dataTables.bootstrap5.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>

<div class="main">

    <?php include 'fragments/nav.php'; ?>
    <main class="content">
        <div class="container-fluid p-0">
            <h1 class="h3 mb-3">Loan ID: <strong><?= esc($loanID) ?></strong></h1>

            <div class="row">

                <div class="col-12 col-md-12 mx-auto">

                    <?php
                    $session->set('loan_id', esc($loanID));
                    $db = db_connect();
                    $builderLoan = $db->table('tab_' . esc($loanID));
                    $builderLoan->select('*');
                    $builderLoan->where('reference', 'Due');
                    $builderLoan->orderBy('Id', 'DESC');
                    $queryLoan = $builderLoan->get();
                    $count = $builderLoan->countAllResults();
                    foreach ($queryLoan->getResult() as $rowLoan) {
                        $builder = $db->table('loans');
                        $builder->select('*');
                        $builder->where('applicationID', $loanID);
                        $builder->join('members', 'members.member_id = loans.member_id');
                        $query = $builder->get();
                        foreach ($query->getResult() as $row) {
                            $customer_name = $row->name;

                            // $amount = $this->request->getVar('amount');
                            $dataApi = array(
                                'order_amount'              => number_format($rowLoan->emi),
                                'order_currency'          => "INR",
                                'customer_details' => array(
                                    'customer_id'          => $row->member_id,
                                    'customer_name'          => $customer_name,
                                    'customer_phone'      => $row->mobile,
                                ),
                                'order_meta' => array(
                                    'return_url'          => base_url() . 'payment/conformation',
                                    'payment_methods'       => "nb,dc,upi"
                                ),
                            );
                        }
                        $data_json = json_encode($dataApi);
                        // print_r($data_json);
                        $curl = curl_init();

                        curl_setopt_array($curl, array(
                            CURLOPT_URL => "https://api.cashfree.com/pg/orders",
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => $data_json,
                            CURLOPT_HTTPHEADER => array(
                                'X-Client-Secret: ..',
                                'X-Client-Id: ..',
                                'x-api-version: 2025-01-01',
                                'Content-Type: application/json',
                                'Accept: application/json',
                            ),
                        ));

                        $response = curl_exec($curl);
                        $response_decode = json_decode($response, true);
                        $err = curl_error($curl);
                        // print_r($err);
                        // var_dump($response_decode);
                        // echo $response_decode['order_id'];
                        // echo $response_decode['payment_session_id'];
                        $session->set('payment_order_id', $response_decode['order_id']);
                        curl_close($curl);
                        // UPDATE EMI TABLE AFTER PAYMENT
                        $builderEmi = $db->table('tab_' . esc($loanID));
                        $data = [
                            'paymentSession'            => $response_decode['payment_session_id'],
                            'orderId'                   => $response_decode['order_id'],
                            'paymentStatus'             => $response_decode['order_status'],
                            'updated_on'                => date('Y-m-d H:i:s')

                        ];
                        $q = $builderEmi->where('reference', 'Due')->update($data);

                    ?>
                        <div class="row g-4 justify-content-center align-items-stretch">
                            <div class="col-md-6 col-12">
                                <div class="card details-card h-100">
                                    <div class="payment-header">
                                        <h4 class=" text-white fw-bold"><i class="bi bi-card-list me-2"></i>Loan Details</h4>
                                        <p class="mb-0">EMI Details</p>
                                    </div>
                                    <div class="card-body px-4 py-4">
                                        <div class="details-item">
                                            <div class="details-label">Loan Application No.</div>
                                            <div class="details-value"><?= esc($loanID) ?></div>
                                        </div>
                                        <div class="details-item">
                                            <div class="details-label">EMI Amount</div>
                                            <div class="details-value">₹ <?= number_format($rowLoan->emi) ?>.00</div>
                                        </div>
                                        <div class="details-item">
                                            <div class="details-label">EMI Date</div>
                                            <div class="details-value"><?= esc($rowLoan->valueDateStamp) ?></div>
                                        </div>
                                        <div class="details-item">
                                            <div class="details-label">Total Tenure</div>
                                            <div class="details-value"><?= esc($count) ?> Days </div>
                                        </div>

                                    </div>
                                    <div class="footer-text bg-light">
                                        &copy; <?= date('Y') ?> Retail Pe. All rights reserved.
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="card payment-card">
                                    <div class="payment-header">
                                        <h4 class=" text-white fw-bold"><i class="bi bi-shield-lock-fill me-2"></i>Secure Payment</h4>
                                        <p class="mb-0">Pay securely using encrypted gateway</p>
                                    </div>

                                    <div class="card-body px-4 py-4">
                                        <div class="mb-3">
                                            <label class="form-label">Full Name</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                                <input type="text" name="name" class="form-control" required readonly value="<?= esc($customer_name) ?>">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Customer ID</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                                                <input type="text" name="customer_id" class="form-control" required readonly value="<?= esc($row->member_id) ?>">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Phone</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                                                <input type="tel" name="phone" class="form-control" required readonly value="<?= esc($row->mobile) ?>">
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label">Amount (INR)</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-currency-rupee"></i></span>
                                                <input type="number" name="amount" class="form-control" readonly value="<?= number_format($rowLoan->emi) ?>" required>
                                            </div>
                                        </div>

                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-pay btn-lg text-white" id="renderBtn">
                                                <i class="bi bi-lock-fill me-2"></i>Proceed to Pay
                                            </button>
                                        </div>
                                        <div class="secure-note mt-4">
                                            <i class="bi bi-shield-check text-success me-1"></i>
                                            Transactions are 100% secure and encrypted.
                                        </div>
                                    </div>

                                    <div class="footer-text bg-light">
                                        &copy; <?= date('Y') ?> Retail Pe. All rights reserved.
                                    </div>
                                </div>
                            </div>


                        </div>


                    <?php } ?>

                </div>

                <div class="modal fade" id="dataModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Payment Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="application_detail">

                            </div>

                        </div>
                    </div>
                </div>
            </div>





        </div>
    </main>
    <script>
        const cashfree = Cashfree({
            mode: "sandbox",
        });
        document.getElementById("renderBtn").addEventListener("click", () => {
            let checkoutOptions = {
                paymentSessionId: "<?= $response_decode['payment_session_id'] ?>",
                redirectTarget: "_modal",
            };
            cashfree.checkout(checkoutOptions).then((result) => {
                if (result.error) {
                    // This will be true whenever user clicks on close icon inside the modal or any error happens during the payment
                    console.log("User has closed the popup or there is some payment error, Check for Payment Status");
                    console.log(result.error);
                }
                if (result.redirect) {
                    // This will be true when the payment redirection page couldnt be opened in the same window
                    // This is an exceptional case only when the page is opened inside an inAppBrowser
                    // In this case the customer will be redirected to return url once payment is completed
                    console.log("Payment will be redirected");
                }
                if (result.paymentDetails) {
                    // This will be called whenever the payment is completed irrespective of transaction status
                    console.log("Payment has been completed, Check for Payment Status");
                    console.log(result.paymentDetails.paymentMessage);
                    window.location.href = "<?= base_url() ?>payment/conformation";
                }
            });
        });
    </script>
    <?php include 'fragments/footer.php'; ?>
<?php include 'fragments/head.php'; ?>
<?php include 'fragments/sidebar.php'; ?>

<div class="main">

    <?php include 'fragments/nav.php'; ?>
    <main class="content">
        <div class="container-fluid p-0">

            <div class="row">

                <div class="col-12 col-md-12 mx-auto">

                    <?php
                    echo $orderId = session('payment_order_id');
                    echo $loanID = session('loan_id');
                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://sandbox.cashfree.com/pg/orders/" . $orderId,
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
                    $err = curl_error($curl);
                    // print_r($err);
                    // var_dump($response_decode);
                    // echo $response_decode['order_id'];
                    // echo $response_decode['payment_session_id'];

                    curl_close($curl);
                    // // UPDATE EMI TABLE AFTER PAYMENT
                    $db = db_connect();

                    // get loan details
                    $builderLoan = $db->table('loans');
                    $builderLoan->select('*');
                    $builderLoan->where('applicationID', $loanID);
                    $queryLoan = $builderLoan->get();
                    foreach ($queryLoan->getResult() as $rowLoan) {
                        $emiPending = $rowLoan->pending_emi;
                        $loanDue = $rowLoan->loan_due;
                    }
                    $builder = $db->table('tab_' . $loanID);
                    $data = [
                        'reference'         => 'Y',
                        'paymentStatus'             => $response_decode['order_status'],
                        'updated_on'                => date('Y-m-d H:i:s')

                    ];
                    $q = $builder->where('reference', 'Due')->update($data);
                    $dataFirst = [

                        'reference'         => 'Due'
                    ];
                    $p = $builder->where('reference', 'N')->limit(1)->update($dataFirst);

                    for ($y = 2; $y <= $emiPending; $y++) {
                        $dataLoop = [
                            'balance'           => round($loanDue - $response_decode['order_amount']),
                        ];

                        $query = $builder->update($dataLoop);
                    }
                    // Data Set For Loan Master Table
                    $builderMaster = $db->table('loans');
                    $dataMaster = [

                        'loan_due'           =>  round($loanDue - $response_decode['order_amount']),
                        'pending_emi'         => round($emiPending - 1),
                        'updated_at'        => date('Y-m-d H:i:s')

                    ];
                    $builderMaster->where('applicationID', $loanID)->update($dataMaster);

                    ?>
                    <div class="row g-4 justify-content-center align-items-stretch">
                        <div class="col-md-6 col-12">
                            <?php
                            if ($response_decode['order_status'] == "PAID") { ?>

                                <div class="col-12">
                                    <div class="status-card bg-white">
                                        <div class="icon-box bg-success text-white">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <h4 class="text-success">EMI Payment Successful</h4>
                                        <p>Your EMI payment has been received successfully.</p>
                                        <a href="/" class="btn bg-brand text-white mt-3">Continue</a>
                                    </div>
                                </div>

                            <?php  } elseif ($response_decode['order_status'] == "ACTIVE") { ?>


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

    <?php include 'fragments/footer.php'; ?>
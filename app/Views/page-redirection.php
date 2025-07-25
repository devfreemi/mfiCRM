<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Redirecting...</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            background-color: #f8f9fa;
        }

        .spinner-border {
            width: 4rem;
            height: 4rem;
            margin-bottom: 20px;
        }

        .text-warning-message {
            color: #dc3545;
            font-weight: 600;
            font-size: 1.1rem;
        }
    </style>
</head>

<body>

    <div class="text-center">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <?php
        $session = session(); // CI4 session object

        if ($session->has('client_id') && $session->has('member_id') && $session->has('member_name')) {
            $clientId  = $session->get('client_id');
            $member_id = $session->get('member_id');
            $name      = $session->get('member_name');
            $applicationid = $session->get('applicationid');
            $loan_amount = $session->get('loan_amount');
            $roi = $session->get('roi');
            $tenure = $session->get('tenure');

            $curlGetPdf = curl_init();
            curl_setopt_array($curlGetPdf, array(
                CURLOPT_URL => 'https://kyc-api.surepass.io/api/v1/esign/get-signed-document/' . $clientId,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . getenv('SUREPASS_API_KEY_PROD'),
                    'Content-Type: application/json'
                ),
            ));
            $responseGetPdf = curl_exec($curlGetPdf);
            $responseGetPdf_json = json_decode($responseGetPdf, true);
            $errGetPdf = curl_error($curlGetPdf);
            curl_close($curlGetPdf);
            if ($errGetPdf) {
                print_r($errGetPdf);
            } else {
                $pdfUrl = $responseGetPdf_json['data']['url'];

                // Generate file name and destination path
                $memberFolder = $member_id;
                $fileName = 'LoanPDF_' . $member_id . '_eSigned.pdf';
                $storagePath = FCPATH . 'uploads/loan_doc/eSigned/' . $memberFolder . '/';
                $fullFilePath = $storagePath . $fileName;

                // Create directory if it does not exist
                if (!is_dir($storagePath)) {
                    mkdir($storagePath, 0777, true);
                }

                // Use file_get_contents and file_put_contents to download and save
                $pdfContent = file_get_contents($pdfUrl);
                if ($pdfContent === false) {
                    // echo "❌ Failed to download PDF.";
                } else {
                    file_put_contents($fullFilePath, $pdfContent);
                    // echo "✅ PDF saved successfully at: $fullFilePath";

                    // Optional: Insert into DB
                    $dataDocGet = [
                        'member_id' =>  $member_id,
                        'doc_name'  =>  $name . 'LoanDocuments_eSigned',
                        'path'      =>  'uploads/loan_doc/eSigned/' . $memberFolder . '/' . $fileName,
                        'eSign'     =>  "Y",
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_on' => date('Y-m-d H:i:s'),
                        'clientID'   => $clientId
                    ];

                    $db = \Config\Database::connect();
                    $db->table('retailer_loan_doc')->insert($dataDocGet);
                }
            }
        }
        // session()->destroy();
        ?>
        <h5 class="mt-3">Redirecting, please wait...</h5>
        <p class="text-warning-message">⚠️ Do not refresh or press back.</p>
    </div>
    <!-- Modal HTML -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title w-100" id="successModalLabel">✅ eSign Completed</h5>
                </div>
                <div class="modal-body">
                    <form id="bankVerifyForm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="bankVerifyModalLabel">Update Loan Application</h5>
                                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                            </div>
                            <div class="modal-body">


                                <input type="hidden" id="applicationid" value="<?= esc($applicationid) ?>">
                                <input type="hidden" id="loan_amount" value="<?= esc($loan_amount) ?>">
                                <input type="hidden" id="tenure" value="<?= esc($tenure) ?>">
                                <input type="hidden" id="roi" value="<?= esc($roi) ?>">
                            </div>
                            <div class="modal-footer">
                                <button type="button" id='submit-btn' class="btn btn-success">Send File to Disbursement</button>
                                <button type="button" onclick="window.close()" class="btn btn-secondary">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Optional auto-redirect (simulated after 5s) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        setTimeout(() => {
            const modal = new bootstrap.Modal(document.getElementById('successModal'));
            modal.show();
        }, 5000); // Show modal after 5 seconds
    </script>
    <script>
        $('#submit-btn').on('click', function(e) {
            e.preventDefault();


            // ✅ Trigger update_of_loan API after successful verification
            const loanData = {
                applicationid: $('#applicationid').val(), // Hidden input or JS var
                status: 'Approved', // or 'Disbursed' or other
                loan_amount: $('#loan_amount').val(), // Hidden or in context
                tenure: $('#tenure').val(), // in months
                roi: $('#roi').val() // e.g. 18
            };

            $.ajax({
                url: "https://crm.retailpe.in/update-loan",
                method: "POST",
                data: loanData,
                success: function(res) {
                    console.log("✅ Loan updated:", res);
                    $('#bankResult').append('<p class="text-success">Loan status updated successfully.</p>');
                },
                error: function(xhr) {
                    console.error("❌ Loan update error:", xhr.responseText);
                    $('#bankResult').append('<p class="text-danger">Loan update failed.</p>');
                }
            });


        });
    </script>
</body>

</html>
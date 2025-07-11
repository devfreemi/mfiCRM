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
                                <h5 class="modal-title" id="bankVerifyModalLabel">Bank Verification</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div id="bankResult" class="alert alert-success d-none mt-3">
                                    <h6>✅ Bank Verified Successfully!</h6>
                                    <p><strong>Holder Name:</strong> <span id="holderName"></span></p>
                                    <p><strong>Bank Name:</strong> <span id="bankName"></span></p>
                                    <p><strong>Branch:</strong> <span id="bankBranch"></span></p>
                                    <p><strong>City:</strong> <span id="bankCity"></span></p>
                                    <p><strong>State:</strong> <span id="bankState"></span></p>
                                    <p><strong>IFSC:</strong> <span id="bankIFSC"></span></p>
                                </div>
                                <div id="bankForm" class="d-block">
                                    <div class="mb-3">
                                        <label for="memberId_bank" class="form-label">Member ID</label>
                                        <input type="text" class="form-control" id="memberId_bank" name="memberId_bank" readonly value="<?= esc($member_id) ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="acc_no" class="form-label">Account Number</label>
                                        <input type="text" class="form-control" id="acc_no" name="acc_no" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="ifsc_code" class="form-label">IFSC Code</label>
                                        <input type="text" class="form-control" id="ifsc_code" name="ifsc_code" required>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" id='submit-btn' class="btn btn-success">Verify</button>
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

            let formData = {
                memberId_bank: $('#memberId_bank').val(),
                acc_no: $('#acc_no').val(),
                ifsc_code: $('#ifsc_code').val()
            };

            $.ajax({
                url: "https://crm.retailpe.in/api/page/verify-bank-v1", // Adjust controller route
                method: "POST",
                data: formData,
                success: function(response) {
                    const data = response.bank_verified?.data?.ifsc_details;

                    if (data) {
                        $('#holderName').text(response.bank_verified.data.full_name);
                        $('#bankName').text(data.bank_name);
                        $('#bankBranch').text(data.branch);
                        $('#bankCity').text(data.city);
                        $('#bankState').text(data.state);
                        $('#bankIFSC').text(data.ifsc);
                        $('#bankResult').removeClass('d-none');
                        $('#bankForm').addClass('d-none');
                        $('#bankForm').removeClass('d-block');

                    } else {
                        $('#bankResult').html('<p class="text-danger">❌ Bank verification failed or no data returned.</p>').removeClass('d-none');
                    }
                    // $('#bankVerifyModal').modal('hide');
                },
                error: function(xhr) {
                    alert('Error verifying bank: ' + xhr.responseText);
                }
            });
        });
    </script>
</body>

</html>
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

                    <div class="row g-4 justify-content-center align-items-stretch" id="receiptContent">
                        <div class="col-md-6 col-12">
                            <?php if (session()->getFlashdata('msg') === 'Y') { ?>
                                <div class="container py-5">
                                    <div class="text-center">
                                        <img src="https://cdn-icons-png.flaticon.com/512/845/845646.png" alt="Success" style="width: 100px;" class="mb-4">
                                        <h2 class="text-success">Field Inspection Completed</h2>
                                        <p class="text-muted mb-4">The FI report has been submitted successfully and marked as <strong>Passed</strong>.</p>

                                        <div class="card shadow-sm border-success mb-4">
                                            <div class="card-body">
                                                <h5 class="card-title">Next Step</h5>
                                                <p class="card-text">You can now proceed to the <strong>Final Approval</strong> and <strong>Document Signing</strong> stage.</p>
                                                <!-- <a href="<?= base_url() ?>preview-pdf/<?= esc($member_id) ?>" class="btn btn-success">Proceed to Final Approval</a> -->
                                                <button type="button" id="next-btn" class="btn btn-success">Verify Customer Bank</button>
                                            </div>
                                        </div>

                                        <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">Back to Dashboard</a>
                                    </div>
                                    <!-- Modal HTML -->
                                    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content text-center">
                                                <div class="modal-header bg-info text-white">
                                                    <h5 class="modal-title w-100" id="successModalLabel">Bank Verification</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="bankVerifyForm">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="bankVerifyModalLabel">Bank Verification</h5>
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
                                                                <button type="button" id='submit-btn' class="btn btn-info">Verify</button>
                                                                <button id="close" onclick="openWin()" disabled class="btn btn-success disabled">Next</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } elseif (session()->getFlashdata('msg') === 'W') { ?>
                                <div class="container py-5">
                                    <div class="text-center">
                                        <img src="https://cdn-icons-png.flaticon.com/512/564/564619.png" alt="Warning" style="width: 100px;" class="mb-4">
                                        <h2 class="text-warning">⚠️ Field Inspection Needs Review</h2>
                                        <p class="text-muted mb-4">Some mismatches and inspection concerns were found during the field visit. Please review the FI report carefully.</p>

                                        <div class="card shadow-sm border-warning mb-4">
                                            <div class="card-body">
                                                <h5 class="card-title">Next Step</h5>
                                                <p class="card-text">You can return to the FI report and make necessary updates or escalate it to the approval team for manual verification.</p>
                                                <a href="#" class="btn btn-warning">Review FI Report</a>
                                            </div>
                                        </div>

                                        <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">Back to Dashboard</a>
                                    </div>
                                </div>

                            <?php  } else { ?>
                                <div class="container py-5">
                                    <div class="text-center">
                                        <img src="https://cdn-icons-png.flaticon.com/512/463/463612.png" alt="Failed" style="width: 100px;" class="mb-4">
                                        <h2 class="text-danger">❌ Field Inspection Failed</h2>
                                        <p class="text-muted mb-4">The field inspection could not be passed due to high mismatch in business details and/or poor shop conditions.</p>

                                        <div class="card shadow-sm border-danger mb-4">
                                            <div class="card-body">
                                                <h5 class="card-title">Next Step</h5>
                                                <p class="card-text">This application cannot proceed further unless a manual override is issued. You may review the FI report or contact the risk team.</p>
                                                <a href="#" class="btn btn-danger">View FI Report</a>
                                            </div>
                                        </div>

                                        <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">Back to Dashboard</a>
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
        $('#next-btn').on('click', function(e) {
            e.preventDefault();
            const modal = new bootstrap.Modal(document.getElementById('successModal'));
            modal.show();
        });
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
                url: "<?= base_url() ?>api/page/verify-bank-v1", // Adjust controller route
                method: "POST",
                data: formData,
                beforeSend: function() {
                    $('#submit-btn').prop('disabled', true).text('Verifying...');
                    $('#bankResult').addClass('d-none');
                },
                success: function(response) {
                    const statusCode = response.bank_verified?.status_code;

                    if (statusCode !== 200) {
                        $('#submit-btn').prop('disabled', false).text('Verify Bank');
                        $('#bankResult').html('<p class="text-danger">❌ Bank verification failed. Server responded with code: ' + statusCode + '</p>').removeClass('d-none');
                        return;
                    }

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
                        $('#submit-btn').hide();
                        $('#close').prop('disabled', false).removeClass('disabled');
                    } else {
                        $('#submit-btn').prop('disabled', false).text('Verify Bank');
                        $('#bankResult').html('<p class="text-danger">❌ Bank verification failed or incomplete data.</p>').removeClass('d-none');
                    }

                    // $('#bankVerifyModal').modal('hide');
                },
                error: function(xhr) {
                    $('#submit-btn').prop('disabled', false).text('Verify Bank');
                    console.log('Error verifying bank: ' + xhr.responseText);
                    $('#bankResult').html('<p class="text-danger">❌ Bank verification failed or no data returned.</p>').removeClass('d-none');
                }
            });
        });
    </script>
    <script>
        function openWin() {
            window.open("<?= base_url() ?>preview-pdf/<?= esc($member_id) ?>");
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
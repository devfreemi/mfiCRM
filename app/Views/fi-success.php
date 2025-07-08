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
                                                <a href="<?= base_url() ?>preview-pdf/<?= esc($member_id) ?>" class="btn btn-success">Proceed to Final Approval</a>
                                            </div>
                                        </div>

                                        <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">Back to Dashboard</a>
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
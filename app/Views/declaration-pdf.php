<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RetailPe - Retailer Declaration Letter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Libertinus+Math&family=Libertinus+Mono&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <header class="py-3">
        <div class="container">
            <nav class="d-flex justify-content-center align-items-center">
                <img src="./img/Ayush Fiscal Logo.png" alt="Ayush Fiscal Logo" class="img-fluid"
                    style="max-height: 100px;">
            </nav>
        </div>
    </header>
    <section>

        <div class="declaration py-4">
            <div class="container">
                <div class="dclrtn-info">
                    <h1 class="mb-4 text-center"><span>Retailer</span> Declaration Letter</h1>
                    <p><strong>To,</strong></p>
                    <p>The Manager</p>
                    <p>Ayush Fiscal Pvt Ltd</p>
                    <p><strong>Address:</strong> 53, SHYAMA PRASAD MUKHERJEE ROAD, KOLKATA - 700026</p>
                    <p class="fw-bold">Subject: Declaration for Daily Equated Payment (DEP) Towards Loan Repayment</p>
                    <p>
                        I, <strong id="retailerName">{var}</strong>, S/o or D/o <strong id="parentName">{var}</strong>,
                        residing at <strong id="RetailerFullAddress">{var}</strong>, and operating my retail business
                        under the name <strong id="ShopName">{var}</strong>, do hereby declare that I have
                        availed a loan facility from Ayush Fiscal Pvt Ltd, vide Loan Account No.
                        <strong id="LoanAccountNumber">{var}</strong>, dated
                        <strong id="LoanSanctionDate">{var}</strong>.
                    </p>
                    <p>As per the agreed terms and conditions, I undertake and confirm that:</p>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item">
                            1. I will repay the loan through <strong>Daily Equated Payments (DEP)</strong> of Rs.
                            <strong id="dailyPaymentAmount]">{var}</strong>, starting from
                            <strong id="startDate">{var}</strong> until the full repayment of the loan amount
                            along with applicable interest, fees, and charges.
                        </li>
                        <li class="list-group-item">
                            2. I understand that the daily repayment amount is calculated based on the loan tenure
                            and rate of interest mutually agreed upon at the time of disbursal.
                        </li>
                        <li class="list-group-item">
                            3. I agree to ensure that the said daily payments will be made through the prescribed
                            mode (cash/UPI/NACH/auto-debit, etc.) and within the stipulated time daily.
                        </li>
                        <li class="list-group-item">
                            4. In case of any delay or non-payment, I understand that penalties and recovery actions
                            as per the loan agreement may be applicable.
                        </li>
                        <li class="list-group-item">
                            5. I confirm that I will maintain transparency and communicate any unforeseen issues that
                            may affect daily payments with Ayush Fiscal Pvt Ltd without delay.
                        </li>
                    </ul>
                    <p>I am signing this declaration voluntarily and with full understanding of the terms of repayment.</p>
                    <p><strong>Date:</strong> DD/MM/YYYY</p>
                    <p><strong>Place:</strong> City/Town Name</p>
                    <div class="dclrtn-sgnr d-flex align-items-center justify-content-between">
                        <p>Signature of Retailer</p>
                    </div>
                    <div class="dclrtn-btm mt-5">
                        <div class="row gy-2">
                            <div class="col-md-6">
                                <p><strong>Retailer Name:</strong> <span id="fullName">{var}</span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Shop Name:</strong> <span id="businessName">{var}</span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Mobile Number:</strong> <span id="mobileNumber">{var}</span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>PAN / Aadhaar No.:</strong> <span id="idNumber">{var}</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>
</body>

</html>
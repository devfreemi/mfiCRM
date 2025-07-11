<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retailer Declaration Letter - Ayush Fiscal Pvt. Ltd.</title>
    <?php date_default_timezone_set('Asia/Kolkata'); ?>
    <style>
        @page {
            margin: 1in 1in 1in 0.6in;
            /* top, right, bottom, left */
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            background: #fff;
        }

        .page-container {
            width: 100%;
            padding: 50px 60px;
            box-sizing: border-box;
        }

        h1,
        h2,
        h3 {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-weight: bold;
            margin-top: 0;
        }

        h1 {
            font-size: 16px;
            text-align: center;
            text-decoration: underline;
            margin-bottom: 24pt;
        }

        h2 {
            font-size: 14px;
            text-align: center;
            border-bottom: 1px solid #9e9e9e;
            padding-bottom: 8pt;
            margin-top: 24pt;
            margin-bottom: 16pt;
        }

        h3 {
            font-size: 12px;
            margin-top: 20pt;
            margin-bottom: 12pt;
            border-left: 3px solid #1a237e;
            padding-left: 8px;
        }

        p,
        ul,
        ol {
            margin-bottom: 12pt;
        }

        strong {
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 30pt;
        }

        .header img {
            max-height: 90px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15pt;
            margin-bottom: 20pt;
            font-size: 11pt;
        }

        th,
        td {
            border: 1px solid #757575;
            padding: 8pt;
            text-align: left;
        }

        .signature-block {
            margin-top: 30pt;
            padding-top: 15pt;
            border-top: 1px solid #ccc;
        }

        .page-break {
            page-break-before: always;
        }

        .footer-info p {
            margin-bottom: 4pt;
            font-size: 11pt;
        }

        .generate-btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #1a237e;
            color: #fff;
            font-size: 14pt;
            font-weight: bold;
            border-radius: 6px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .generate-btn:hover {
            background-color: #0d144e;
        }

        /* Declaration */
        .dclrtn-info {
            position: relative;
        }

        .dclrtn-info::before {
            content: '';
            position: absolute;
            height: 100%;
            width: 100%;
            max-height: 4px;
            max-width: 180px;
            top: 50px;
            left: 50%;
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
            transform: translateX(-50%);
            background-color: #fff;
            z-index: -999;
        }

        .dclrtn-info::after {
            content: '';
            position: absolute;
            height: 100%;
            width: 100%;
            max-height: 4px;
            max-width: 100px;
            top: 70px;
            left: 50%;
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
            transform: translateX(-50%);
            background-color: #fff;
            z-index: -999;
        }

        .dclrtn-info h1 {
            font-family: "Libertinus Math", system-ui;
            padding-bottom: 2rem;
            font-size: 2.3rem;
            font-weight: 1000;
        }

        .dclrtn-info h2 {
            font-family: "Libertinus Math", system-ui;
            font-weight: 550;
        }

        .dclrtn-info h1 span {
            color: #6819e6;
        }

        .dclrtn-info>p {
            font-family: "Libertinus Math", system-ui;
            padding-bottom: 0 !important;
            font-weight: 700;
            font-size: 1.1rem;
            color: #3f3f3f;
        }

        .list-group-item {
            font-weight: 500;
        }

        .dclrtn-sgnr {
            width: fit-content;
            font-size: 1rem;
            font-weight: 800;
            border-bottom: 1px solid #000;
        }

        .list-group-item {
            font-family: "Montserrat", sans-serif;
        }

        .list-group-item strong {
            font-size: 1.2rem;
            font-family: "Libertinus Math", system-ui;
        }

        .dclrtn-sgnr p {
            font-family: "Libertinus Math", system-ui;
            font-size: 1.5rem;
        }

        .dclrtn-btm {
            font-family: "Libertinus Math", system-ui;
            font-size: 1.2rem;
        }

        /* Annexure */
        .subs-inp {
            display: flex;
            flex-direction: column;
        }

        .dclrtn-info-dtls strong {
            font-family: "Libertinus Math", system-ui;
            font-weight: bolder;
            font-size: 1.3rem;
        }

        .dclrtn-info-dtls h4 {
            font-size: 1.5rem;
            font-weight: 550;
        }

        .dclrtn-info-dtls p {
            font-family: "Montserrat", sans-serif;
            font-weight: 600;
            font-size: 1.1rem;
            color: #3f3f3f;
        }

        .dclrtn-info-dtls ul li {
            font-family: "Montserrat", sans-serif;
            font-weight: 550;
            font-size: 1rem;
        }

        .dclrtn-info-dtls tr {
            font-weight: 550;
            font-family: "Montserrat", sans-serif;
        }

        .dclrtn-info-dtls label {
            font-family: "Montserrat", sans-serif;
            font-weight: 550;
            font-size: 0.9rem;
        }

        .form-check-input[type=checkbox] {
            border: 1px solid #000 !important;

        }

        .dclrtn-agr {
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="page-container">
        <div class="declaration py-4 dclrtn-agr">

            <h1>CREDIT FACILITY AGREEMENT No. <?= esc($member_id) ?></h1>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>BETWEEN</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;font-weight: 700'>AYUSH FISCAL PRIVATE LIMITED</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>("LENDER")</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>AND</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;font-weight: 700'><?= esc($panName) ?></p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>("BORROWER")</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>THIS Credit Facility Agreement No. <?= esc($member_id) ?>
                ("Credit Facility Agreement") is made and executed by and between:</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>AYUSH FISCAL PRIVATE LIMITED, Registered Address: 53, SHYAMA PRASAD MUKHERJEE ROAD, KOLKATA - 700026. a
                non-banking financial company incorporated under the provisions of the Act, having its Corporate Identity Number
                U65999WB1992PTC055336 and having its Registered Office at 53, Shyama Prasad Mukherjee Road, Kolkata - 700026 (hereinafter referred to
                as "Lender" which expression shall, unless repugnant to the subject or context thereof include its successors,
                transferees, novatees and assigns) of the FIRST PART;</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>AND</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>THE PERSONS SET FORTH IN SCHEDULE I HERETO, Address <?= esc($location) ?>,<?= esc($pincode) ?>..
                (hereinafter referred to as the "Borrower" which expression shall, unless repugnant to the context or meaning
                thereof, be deemed to include its successors, legal representatives and permitted assigns) of the SECOND PART.
            </p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>The expressions Borrower (as described in Schedule I)
                and "the Lender" shall individually be referred to as a "Party" and collectively be referred to as the
                "Parties".</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>WHEREAS</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>The Borrower requires funds for the Purpose (as defined
                in Schedule II) and has approached Lender for availing the Credit Facility.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Based on the representations and assurances of the
                Obligors, the Lender has agreed to provide the Credit Facility to the Borrower and the Borrower has agreed to
                avail the Credit Facility from Lender on the terms and subject to the conditions contained in this Credit
                Facility Agreement along with the Schedule hereto and the General Terms and Conditions (as defined hereinafter)
                as applicable to the Credit Facility.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Definitions</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>In this Credit Facility Agreement, unless there is
                anything repugnant to the subject or context thereof, the expression listed below shall have the following
                meaning:</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>"Credit Facility Agreement" shall mean the agreement
                entered into between the Lender, the Borrower and the Obligor(s) stating out the terms and conditions of the
                Credit Facility.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>"Credit Facility" shall have the same meaning as Loan
                Facility as defined under the General Terms and Conditions.</p>
            <!-- <p style='font-family:Arial; font-size:16px; margin:6px 0;font-weight: 700'>"General Terms and Conditions" shall mean the General Terms and Conditions as applicable with this Credit Facility Agreement governing the Credit Facility, provided by the Lender herein and duly registered on [DATE OF REGISTRATION], with the Sub-Registrar of Assurance, [SUB-REGISTRAR OFFICE], <?= esc($bankCity) ?> vide registration no. [REGISTRATION NUMBER] in book no. [BOOK NUMBER] Serial No. [SERIAL NUMBER], Registration Year [REGISTRATION YEAR], a copy of which is available on the website of the Lender namely, [LENDER'S WEBSITE FOR GTC]. The General Terms and Conditions shall be deemed to form an integral part of this Credit Facility Agreement and shall be read in conjunction and concurrently as if they are specifically incorporated herein.</p> -->
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>"Last Disbursement Request Date" shall mean, subject to
                the fulfilment of the conditions of this Credit Facility Agreement, the last date of request for Disbursement,
                as provided in Schedule II;</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>"Minimum Disbursement Amount" shall mean the minimum
                amount that the Lender will Disburse, subject to the terms and the conditions of this Credit Facility Agreement,
                as provided in Schedule II;</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>All capitalized term used but not defined in this Credit
                Facility Agreement shall have the respective meaning as assigned to it under the General Terms and Conditions.
            </p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Interpretation</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>In this Credit Facility Agreement, save where the
                context otherwise requires would be interpreted as provided in the General Terms and Conditions.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Acknowledgement</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>The Obligors confirms, represents and acknowledges
                having received a copy of the General Terms and Conditions and that the Obligors have read and understood the
                terms referred therein and agrees to abide by the terms, conditions, covenants and undertaking contained in the
                General Terms and Conditions.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Disbursement of Loan Facility and Application of
                Proceeds</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Lender, at the request and representations of the
                Obligors, agrees to provide to the Borrower and the Borrower agrees to avail from Lender, the Credit Facility
                for the Purpose, on and subject to the fulfilment of the terms, conditions and covenants contained in this
                Credit Facility Agreement, the other Financing Documents and the Schedules written hereunder.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Effective date of the Credit Facility Agreement</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>This Credit Facility Agreement, General Terms and
                Conditions and other Financing Documents are binding on the parties hereto on and from the date of this Credit
                Facility Agreement and shall be in force and effect till the Final Settlement Date, unless terminated in
                accordance with the provisions of the Agreement.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Terms and Conditions</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>The Credit Facility shall be governed by the terms and
                conditions mentioned in Schedule II and written hereunder.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Insolvency & Bankruptcy</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>The Obligors shall not undertake any insolvency
                resolution process or voluntary winding up (if applicable) or file for insolvency under the applicable law
                without the prior written consent of the Lender. Further, regardless of whether the insolvency resolution
                process is voluntary or involuntary, any resolution plan proposed by the Obligors or any of its directors
                including its managing directors/promoters to the resolution professional will need to have the prior written
                consent, permission and no objection from the Lender. Towards this end, the Obligors shall procure necessary
                undertaking from its directors including its managing directors and promoters to act in compliance with this
                clause. In the event upon obtaining the approval of the Lender, the Borrower initiates a voluntary
                insolvency/liquidation under the Insolvency and Bankruptcy Code 2016 ("IBC"), as amended from time to time, it
                shall appoint the resolution professional in consultation with the Lender.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Re-borrowing</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Till the Last Disbursement Request Date, the Borrower
                shall be entitled to request for Disbursement of any amount of the Credit Facility which has been repaid in
                accordance with this Credit Facility Agreement, provided that—</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>no Event of Default exists;</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>no Material Adverse Effect or default has occurred and
                is continuing; and</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>the Disbursement requested along with the outstanding
                Credit Facility shall not have exceeded the Credit Facility.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>All the terms of conditions of this Credit Facility
                Agreement and General Terms and Conditions shall apply to the re-borrowing mentioned in Clause 7.1.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Notwithstanding anything contained herein above, any
                Disbursement from the Credit Facility which has been repaid shall be at the sole discretion of the Lender.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Renewal of the Credit Facility</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>The Lender at its sole discretion, at the request of the
                Borrower, may renew the Credit Facility on such terms and conditions as may be decided by the Lender.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Electronic authentication</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>The Credit Facility Agreement may be authenticated
                electronically. Parties hereto, on such electronic authentication of this Credit Facility Agreement, hereby
                agree to such method of electronic authentication. The Obligor further agrees that such electronic
                authentication shall be legally enforceable against the Obligor and the Obligor shall not challenge the validity
                or enforceability of such authentication.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Applicability of Reserve Bank of India Regulation</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>As per RBI Guidelines the Lender shall recognise
                incipient stress in loan account, immediately on default, and shall classify such assets as Special Mention
                Accounts (SMA) as per the categories/sub-categories mentioned under RBI’s "Master Circular - Prudential norms on
                Income Recognition, Asset Classification and Provisioning pertaining to Advances". For ready references such
                categories/sub-categories are also mentioned under Part A of Annexure I hereof.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>As per RBI Guidelines the Lender shall recognise a loan
                account as Non Performing Assets (NPA) in the event there is a delay in repayment of any amount under the Loan
                continues beyond SMA-2 category as detailed under RBI’s "Master Circular - Prudential norms on Income
                Recognition, Asset Classification and Provisioning pertaining to Advances". For ready references, the relevant
                provisions/criteria in this regard are listed under Part B of Annexure I hereof.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>For better understanding of the issues of SMA and NPA
                classifications, examples are provided under Part C of Annexure I hereof.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>IN WITNESS WHEREOF the Parties have caused this Credit
                Facility Agreement to be executed and acknowledged by their respective authorized officials or representatives
                hereunto duly authorized, as hereinafter appearing on the day, month and year written herein below.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Parties, Signatory Name, Authorization Date,
                Authorization, Signature</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;font-weight: 700'>Lender, Mr. Subhajit Paul, <?= date('l d F Y ') ?>, Authorized Signatory of the Lender, pursuant to the resolution of its Board passed in that
                regard on Authorization Date who has signed these presents in token thereof</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;font-weight: 700'>Borrower, <?= esc($panName) ?>, Not Applicable, Borrower represented by its Proprietor/Authorized Signatory.</p>
            <p style='font-family:Arial; font-size:24px; font-weight:900; margin:6px 0; text-align:center; padding: 12px'>SCHEDULE I DETAILS OF THE BORROWER</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;font-weight: 700'><?= esc($businessName) ?> having office at <?= esc($location) ?> represented by
                its proprietor, <?= esc($panName) ?>, having Permanent Account Number
                <?= esc($pan) ?> and residing at <?= esc($location) ?>.</p>
            <p style='font-family:Arial; font-size:24px; font-weight:900; margin:6px 0; text-align:center; padding: 12px'>SCHEDULE II TERMS AND CONDITIONS OF THE CREDIT FACILITY
            </p>
            <table style='border-collapse:collapse; width:100%; margin:10px 0;'>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Sl. No.</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Particulars</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Details</td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>1</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Place of Agreement</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'><?= esc($location) ?></td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>2</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Date of Agreement</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'><?= date('l d F Y ') ?></td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>3</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Place of Occupation of
                        Borrower</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'><?= esc($location) ?>
                    </td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>4</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Purpose of Credit
                        Facility</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Retail Loan</td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>5</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Sanctioned Credit
                        Facility</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Rs. 2,00,000.00</td>
                </tr>

                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>6a</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Processing Fees</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Not Applicable</td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>6b</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Insurance Premium</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Not Applicable</td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>7</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Tenure</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Maximum of 12 months from each of the individual drawdown.
                        The tenure of the credit facility is 36 months.</td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>9</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Credit Facility Servicing
                        and Repayment of Principal and Interest</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>As would be acknowledged
                        on each of the Disbursement/Drawdown Date(s)</td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>10</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Disbursement and
                        Repayment Method</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>The Credit Facility shall be disbursed by the Lender through the platform of
                        Ntactus Financial Services Private Limited
                        Repayment date: 5th of every month.
                        Repayment options for the borrower: Payment Gateway(Netbanking/UPI), NACH,
                    </td>
                </tr>



                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>11</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Interest Rate</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'><?= esc($roi) ?>% p.a.
                        payable monthly (Fixed)</td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>12</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Delayed Interest</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>The interest rate as in
                        (11) will continue to be charged daily if the drawdown amount is not paid within the withdrawal tenure
                    </td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>13</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Prepayment Charges</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>NIL</td>
                </tr>
                <!-- <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>14</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Bounce Charges</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>[BOUNCE CHARGES, e.g.,
                        Not Applicable or specify amount]</td>
                </tr> -->
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>14</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Charges on miscellaneous
                        request(s) such as, change in servicing date, changes in Credit Facility servicing bank account,
                        replacement of security/servicing cheques etc.</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Not Applicable</td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>16</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Address of the Borrower
                        for the purpose of service of Notice</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>As per Schedule I</td>
                </tr>
            </table>
            <p style='font-family:Arial; font-size:24px; font-weight:900; margin:6px 0; text-align:center; padding: 12px'>Annexure I Part A Special Mention Account (SMA)
                Categories / Sub-Categories</p>
            <table style='border-collapse:collapse; width:100%; margin:10px 0;'>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>SMA Sub-categories</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Basis for classification
                        – payment (principal/interest/any other amount) overdue</td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>SMA-0</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>1-30 days</td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>SMA-1</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>31-60 days</td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>SMA-2</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>61-90 days</td>
                </tr>
            </table>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Part B Non Performing Assets (NPA)</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>As per RBI Guidelines Non Performing Asset (NPA) is a
                loan or an advance where;</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>interest and/ or instalment of principal remains overdue
                for a period of more than 90 days in respect of a term loan,</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>the amount of liquidity facility remains outstanding for
                more than 90 days, in respect of a securitisation transaction undertaken in terms of the Reserve Bank of India
                (Securitisation of Standard Assets) Directions, 2021.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>in case of interest payments in respect of term loans,
                an account will be classified as NPA if the interest applied at specified rests remains overdue for more than 90
                days.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>in respect of accounts where there are potential threats
                for recovery on account of erosion in the value of security or non-availability of security and existence of
                other factors such as frauds committed by borrowers/security providers, the asset should be straightaway
                classified as doubtful or loss asset as appropriate:</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Erosion in the value of security can be reckoned as
                significant when the realisable value of the security is less than 50 per cent of the value assessed by the bank
                or accepted by RBI at the time of last inspection, as the case may be. Such NPAs may be straightaway classified
                under doubtful category.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>If the realisable value of the security, as assessed by
                the lending institution/ approved valuers/ RBI is less than 10 per cent of the outstanding in the borrowable
                accounts, the existence of security should be ignored, and the asset should be straightaway classified as loss
                asset.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Accounts are restructured.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Part C Example for SMA / NPA Classification Dates:</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Classification of borrower accounts as SMA as well as
                NPA shall be done as part of day-end process for the relevant date and the SMA or NPA classification date shall
                be the calendar date for which the day end process is run.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>For clarity over the SMA / NPA classification date and
                better understanding of the issue following examples are provided as per the RBI Guidelines:</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Example: If due date of a loan account is March 31,
                2021, and full dues are not received before the lending institution runs the day-end process for this date, the
                date of overdue shall be March 31, 2021 and this account shall get tagged as SMA-0 upon running day-end process
                on March 31, 2021. If it continues to remain overdue, then this account shall get tagged as SMA-1 upon running
                day-end process on April 30, 2021 i.e. upon completion of 30 days of being continuously overdue. Accordingly,
                the date of SMA-1 classification for that account shall be April 30, 2021.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Similarly, if the account continues to remain overdue,
                it shall get tagged as SMA-2 upon running day-end process on May 30, 2021 and if continues to remain overdue
                further, it shall get classified as NPA upon running day-end process on June 29, 2021.</p>
            <p style='font-family:Arial; font-size:24px; font-weight:900; margin:6px 0; text-align:center; padding: 12px'>Annexure II Key Fact Statement</p>
            <table style='border-collapse:collapse; width:100%; margin:10px 0;'>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Sr. No.</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Particulars</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Details</td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>1</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Limit amount (maximum
                        amount disbursable to the borrower) (in Rupees)</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>INR <?= number_format($loan_amount, 2) ?>/-</td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>2</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Total interest charge
                        during the entire tenure of the individual line drawdown (in Rupees)</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>INR <?= number_format($interest, 2) ?>/-</td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>3</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Other up-front charges
                        (in Rupees)</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'> NIL</td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>a</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Processing fees (in
                        Rupees)</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>NIL</td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>b</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Insurance charges (in
                        Rupees)</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>NIL</td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>c</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Others (in Rupees)</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>NIL</td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>4</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Net disbursable amount
                        ((i)-(iii)) (in Rupees)</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>INR <?= number_format($disbursable_amount, 2) ?>/-</td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>5</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Tenure of the Line (in
                        months)</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'><?= esc($loan_tenure) ?> months
                    </td>
                </tr>
                <!-- <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>6</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Maximum tenure of the
                        Individual Drawdown (in days)</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>[MAXIMUM INDIVIDUAL
                        DRAWDOWN TENURE] months</td>
                </tr> -->
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>6</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Total amount to be paid
                        by the borrower for each drawdown (in Rupees)</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>INR <?= number_format($total_amount, 2) ?>/-</td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>8</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Rate of annualized delay
                        interest in case of delayed payments (if any)</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'><?= esc($roi) ?>%
                    </td>
                </tr>

                <!-- <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>10</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Repayment date</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>[REPAYMENT DAY] of every
                        month</td>
                </tr> -->
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>9</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Cooling off/look-up
                        period</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>No prepayment charges
                        will be applicable throughout the tenure of the limit, hence the loan can be prepaid anytime during the
                        tenure.</td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>10</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Details of LSP acting as
                        recovery agent and authorized to approach the borrower</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Ntactus
                        Financial Services Private Limited</td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>11</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Name, designation,
                        address and phone number of nodal grievance redressal officer</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'> Mr. Bapan Sarkar
                        <br>
                        Contact Number: 033-45064448 – 10:00 am to 6:00 pm (Monday to Friday except National Holidays)
                        <br>
                        Email ID: nodal.grievance@ayushfiscal.com
                    </td>
                </tr>
            </table>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Sample calculation for drawdown</p>
            <table style='border-collapse:collapse; width:100%; margin:10px 0;'>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Sr. No.</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Particulars</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Details</td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>1</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Limit amount</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>INR <?= number_format($loan_amount, 2) ?>
                    </td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>2</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>ROI</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'><?= number_format($roi, 2) ?>%</td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>3</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Processing fee</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'> 4%
                    </td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>4</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Insurance charges</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Nill</td>
                </tr>

                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>5</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Processing fee inclusive
                        of GST</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>INR <?= number_format($chargesandinsurance, 2) ?></td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>6</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Disbursed amount</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>INR <?= number_format($disbursable_amount, 2) ?>
                    </td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>7</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Tenure of drawdown</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'><?= esc($loan_tenure) ?> Months
                    </td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>8</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Total number of
                        instalments (in Days)</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'><?= esc($pending_emi) ?>
                    </td>
                </tr>

                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>9</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Interest applicable</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>INR <?= number_format($interest, 2) ?>
                    </td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>10</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Repayment amount</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>INR <?= number_format($total_amount, 2) ?>
                    </td>
                </tr>
            </table>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Ref.No: <?= esc($member_id) ?></p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>M/s <?= esc($businessName) ?></p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'><?= esc($bankAddress) ?></p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Attention: <?= esc($panName) ?></p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Sub: Sanction of Credit Facility</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Date: <?= date("d/m/Y") ?></p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Ref: Application Number - <?= esc($applicationID) ?></p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Dear Sir,</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>We refer to various discussions we have had with your
                good self, regarding your proposal seeking credit facility of INR <?= esc($loan_amount) ?>
                (Rupees <?php
                        $formatter = new NumberFormatter("en-IN", NumberFormatter::SPELLOUT);
                        $words = $formatter->format($loan_amount);

                        echo ucfirst($words);
                        ?> Only) through the platform of Ntactus Financial Services Private Limited.
            </p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>In this regard, we are pleased to inform you that we
                have considered your request for sanction of an amount not exceeding INR <?= esc($loan_amount) ?>
                (Rupees <?php
                        $formatter = new NumberFormatter("en-IN", NumberFormatter::SPELLOUT);
                        $words = $formatter->format($loan_amount);

                        echo ucfirst($words);
                        ?> Only) on the terms mentioned in the Annexure hereto.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>We would like to further inform you that <?= esc($fiInspector_name) ?> is appointed as our authorized collection agent for the collection of all receivables pertaining to your credit facility.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Please have this letter duly signed by your authorized
                signatory as acknowledgement of your acceptance of the sanction.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Thanking You, Yours faithfully,</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>AGREED AND ACCEPTED</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>For Ayush Fiscal Private Limited</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>For M/s <?= esc($businessName) ?></p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>By:</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Name: Mr. Subhajit Paul</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Title: Signatory Authority</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Name: <?= esc($panName) ?></p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Title: Proprietor</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Ayush Fiscal Private Limited</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Registered Office: 53, SHYAMA PRASAD MUKHERJEE ROAD, KOLKATA - 700026
            </p>
            <!-- <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Date: [DATE OF ESIGNATURE]</p> -->
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>CIN: U65999WB1992PTC055336 Tel: 033 4506 4448</p>
            <!-- <p style='font-family:Arial; font-size:16px; margin:6px 0;'>GSTN No. </p> -->
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Email: support@ayushfiscal.com</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>ANNEXURE - I</p>
            <table style='border-collapse:collapse; width:100%; margin:10px 0;'>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Sl. No.</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Particulars</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Details</td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>1</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Purpose of Credit
                        Facility</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Retail Loan</td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>2</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Sanctioned Credit
                        Facility</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Not exceeding INR
                        <?= esc($loan_amount) ?>
                        (Rupees <?php
                                $formatter = new NumberFormatter("en-IN", NumberFormatter::SPELLOUT);
                                $words = $formatter->format($loan_amount);

                                echo ucfirst($words);
                                ?> only)</td>
                </tr>
                <!-- <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>3</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Current Drawdown Limit
                    </td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>INR [CURRENT DRAWDOWN
                        LIMIT IN NUMBERS]/-(Rupees [CURRENT DRAWDOWN LIMIT IN WORDS] Only)</td>
                </tr> -->
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>4</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Processing Fees</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Not Applicable</td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>5</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Insurance Premium</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Not Applicable</td>
                </tr>
                <!-- <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>6</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Tenure</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Maximum of [MAXIMUM
                        DRAWDOWN TENURE] months from each of the individual drawdown.</td>
                </tr> -->
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>7</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Credit Facility Servicing
                        and Repayment of Principal and Interest</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Repayment date:
                        5th of every month.</td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>8</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Disbursement and
                        Repayment Method</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>The Credit Facility shall
                        be disbursed by the Lender through the platform of Retail Pe</td>
                </tr>
                <!-- <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>9</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Minimum drawdown amount
                    </td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>INR [MINIMUM DRAWDOWN
                        AMOUNT]</td>
                </tr> -->
                <!-- <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>10</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Maximum drawdown amount
                    </td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Current Drawdown Limit
                    </td>
                </tr> -->
                <!-- <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>11</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Number of drawdowns
                        allowed</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>No limit (within the
                        parameters of other terms)</td>
                </tr> -->
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>12</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Interest Rate</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'><?= esc($roi) ?>% p.a.
                        payable monthly (Reducing)</td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>13</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Delayed Interest</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>The interest rate as in
                        (12) will continue to be charged daily if the drawdown amount is not paid within the withdrawal tenure
                    </td>
                </tr>
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>14</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Prepayment Charges</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>NIL</td>
                </tr>
                <!-- <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>15</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Bounce Charges</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>[BOUNCE CHARGES, e.g.,
                        Not Applicable]</td>
                </tr> -->
                <tr>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>16</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Charges on miscellaneous
                        request(s) such as, change in servicing date, changes in Credit Facility servicing bank account,
                        replacement of security/servicing cheques etc.</td>
                    <td style='border:1px solid #000; padding:5px; font-family:Arial; font-size:16px; font-weight:500'>Not Applicable</td>
                </tr>
            </table>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Paste Photo and Sign Across</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Borrower/Proprietor</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'><?= esc($panName) ?></p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Application Details - <?= esc($member_id) ?></p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Amount: INR <?= esc($loan_amount) ?>/-</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Firm Name: <?= esc($businessName) ?></p>
            <!-- <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Firm Website: [BORROWER'S FIRM WEBSITE, e.g., Not Provided]</p> -->
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Tenure: <?= esc($loan_tenure) ?> months</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Firm Type: <?= esc($businessType) ?></p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>GSTN: <?= esc($gst) ?></p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Purpose: Business</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Year of Incorporation: <?= esc($estab) ?></p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Applicants Details</p>
            <!-- <p style='font-family:Arial; font-size:16px; margin:6px 0;'>| Role | Name | Mobile No | POI | POA |</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>|---|---|---|---|---|</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>| Firm | <?= esc($businessName) ?> | <?= esc($mobile) ?> | | |</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>| Proprietor | <?= esc($panName) ?> |
                <?= esc($mobile) ?> | | |</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Addresses</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Registered: <?= esc($location) ?></p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Bank Statement(s)</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>| Account | Bank Account Number | Bank IFSC |</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>|---|---|---|</p> -->
            <div style="font-family: Arial, sans-serif; font-size: 14px; line-height: 1.5; color: #333;">
                <h3 style="margin-top: 0;">Party Details</h3>
                <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%; margin-bottom: 20px;">
                    <thead style="background-color: #f2f2f2;">
                        <tr>
                            <th style="text-align: left;">Role</th>
                            <th style="text-align: left;">Name</th>
                            <th style="text-align: left;">Mobile No</th>
                            <th style="text-align: left;">Addresses</th>
                            <!-- <th style="text-align: left;">POA</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Firm</td>
                            <td><?= esc($businessName) ?></td>
                            <td><?= esc($mobile) ?></td>
                            <td><?= esc($location) ?></td>
                            <!-- <td></td> -->
                        </tr>
                        <tr>
                            <td>Proprietor</td>
                            <td><?= esc($panName) ?></td>
                            <td><?= esc($mobile) ?></td>
                            <td></td>

                        </tr>
                    </tbody>
                </table>


                <h3 style="margin-top: 0;">Bank Statement(s)</h3>
                <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%;">
                    <thead style="background-color: #f2f2f2;">
                        <tr>
                            <th style="text-align: left;">Account</th>
                            <th style="text-align: left;">Bank Account Number</th>
                            <th style="text-align: left;">Bank IFSC</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= esc($bankAccount) ?></td>
                            <td><?= esc($bankName) ?></td>
                            <td><?= esc($ifsc) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>I/We declare that all particulars and information given
                in the application form are true, correct and complete and that they shall form the basis of any credit facility
                Ayush Fiscal Private Limited may decide to grant to me/us.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Represented by its Proprietor</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'><?= esc($panName) ?></p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Ayush Fiscal Private Limited</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Registered Office: </p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>CIN: U65999WB1992PTC055336 Tel: 033 4506 4448</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>GSTN No. </p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Email: support@ayushfiscal.com</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Sign Here</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Represented by its Proprietor</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'><?= esc($panName) ?></p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Ayush Fiscal Private Limited</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Registered Office: 53, Shyama Prasad Mukherjee Road, Kolkata - 700026</p>
            <!-- <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Date: [DATE OF ESIGNATURE]</p> -->
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>CIN: U65999WB1992PTC055336 Tel: 033 4506 4448</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>GSTN No. </p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Email: support@ayushfiscal.com</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Sign Here</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Date: <?= date("d/m/Y") ?></p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>To,</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Ayush Fiscal Private Limited</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>53, Shyama Prasad Mukherjee Road, Kolkata - 700026</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Sub: Declaration of End use of Funds</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Ref: Application Number - <?= esc($member_id) ?></p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Dear Sir/Ma'am,</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>I/we have been sanctioned a Credit Facility of INR
                <?= esc($loan_amount) ?> (Rupees <?php
                                                    $formatter = new NumberFormatter("en-IN", NumberFormatter::SPELLOUT);
                                                    $words = $formatter->format($loan_amount);

                                                    echo ucfirst($words);
                                                    ?> Only) by
                your organization.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>As stated in the application form the said Credit
                Facility is for the Purpose of Business. I/We hereby represent,
                warrant and confirm that the aforesaid Purpose is a valid purpose and is not speculative or illegal/antisocial
                in any manner. Furthermore, proceeds from the Credit Facility, shall not be used for capital market purposes.
            </p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>I/We further agree, confirm and undertake that the
                Purpose of use of funds shall not be changed in any manner during the tenure of the Credit Facility.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>I/We agree that any breach of default in complying with
                all or any aforesaid undertaking(s) shall constitute an Event of Default under the Agreements.</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>(Capitalized term not defined hereunder shall have the
                same meaning as ascribed to under the Agreements)</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Thanking You, Yours Faithfully</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>For <?= esc($businessName) ?></p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'><?= esc($panName) ?></p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>(Proprietor)</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Ayush Fiscal Private Limited</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Registered Office: 53, Shyama Prasad Mukherjee Road, Kolkata - 700026
            </p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>CIN: U65999WB1992PTC055336 Tel: 033 4506 4448</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>GSTN No. </p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Email: support@ayushfiscal.com</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Sign Here</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>For <?= esc($businessName) ?></p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'><?= esc($panName) ?></p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>(Proprietor)</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Ayush Fiscal Private Limited</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Registered Office: 53, Shyama Prasad Mukherjee Road, Kolkata - 700026
            </p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>CIN: U65999WB1992PTC055336 Tel: 033 4506 4448</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>GSTN No. </p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Email: support@ayushfiscal.com</p>
            <p style='font-family:Arial; font-size:16px; margin:6px 0;'>Sign Here</p>
        </div>
        <div class="declaration py-4">
            <div class="container">
                <div class="dclrtn-info">
                    <h1 class="mb-4 text-center"><span>Retailer's Self</span> Declaration Letter</h1>
                    <p><strong>To,</strong></p>
                    <p>The Manager</p>
                    <p>Ayush Fiscal Pvt Ltd</p>
                    <p><strong>Address:</strong> 53, SHYAMA PRASAD MUKHERJEE ROAD, KOLKATA - 700026</p>
                    <p class="fw-bold">Subject: Declaration for Daily Equated Payment (DEP) Towards Loan Repayment</p>
                    <p>
                        I, <strong id="retailerName"><?= esc($panName) ?></strong>, S/o or D/o <strong id="parentName"><?= esc($panName) ?></strong>,
                        residing at <strong id="RetailerFullAddress"><?= esc($location) ?>, <?= esc($pincode) ?></strong>, and operating my retail business
                        under the name <strong id="ShopName"><?= esc($businessName) ?></strong>, do hereby declare that I have
                        availed a loan facility from Ayush Fiscal Pvt Ltd, vide Loan Account No.
                        <strong id="LoanAccountNumber"><?= esc($applicationID) ?></strong>, dated
                        <strong id="LoanSanctionDate"><?= date('d-m-Y') ?></strong>.
                    </p>
                    <p>As per the agreed terms and conditions, I undertake and confirm that:</p>
                    <ul>
                        <li>1. I will repay the loan through <strong>Daily Equated Payments (DEP)</strong> of Rs. <strong id="dailyPaymentAmount"><?= esc($emi / 30) ?></strong>, starting from <strong id="startDate">_____________</strong> until the full repayment of the loan amount along with applicable interest, fees, and charges.</li>
                        <li>2. I understand that the daily repayment amount is calculated based on the loan tenure and rate of interest mutually agreed upon at the time of disbursal.</li>
                        <li>3. I agree to ensure that the said daily payments will be made through the prescribed mode (cash/UPI/NACH/auto-debit, etc.) and within the stipulated time daily.</li>
                        <li>4. In case of any delay or non-payment, I understand that penalties and recovery actions as per the loan agreement may be applicable.</li>
                        <li>5. I confirm that I will maintain transparency and communicate any unforeseen issues that may affect daily payments with Ayush Fiscal Pvt Ltd without delay.</li>
                    </ul>
                    <p>I am signing this declaration voluntarily and with full understanding of the terms of repayment.</p>
                    <p><strong>Date:</strong> <?= date('d-m-Y') ?></p>
                    <p><strong>Place:</strong> <?= esc($location) ?></p>
                    <div class="signature-block">
                        <p>Signature of Retailer</p>
                        <p><strong>Retailer Name:</strong> <?= esc($panName) ?></p>
                        <p><strong>Shop Name:</strong> <?= esc($businessName) ?></p>
                        <p><strong>Mobile Number:</strong> <?= esc($mobile) ?></p>
                        <p><strong>PAN / Aadhaar No.:</strong> <?= esc($pan) ?> / <?= esc($adhar) ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="declaration py-4">
            <div class="container">
                <div class="dclrtn-info">
                    <h1 class="mb-4 text-center"><span>Retailer</span> ANNEXURE – I</h1>
                    <h2 class="text-center pb-0">(Retail Shop Loan Supporting Declarations & Disclosures)</h2>
                    <p class="text-center">To be read as an integral part of the Loan Agreement dated <?= date('d-m-Y') ?> executed
                        between:</p>
                    <div class="dclrtn-info-dtls">
                        <p><strong>Lender:</strong> <span class="text-decoration-underline">Ayush Fiscal Private Limited</span></p>
                        <p><strong>Borrower (Shop Owner):</strong> <span class="text-decoration-underline"><?= esc($panName) ?></span></p>
                    </div>
                    <div class="dclrtn-info-dtls">
                        <strong>A. Borrower KYC Documents Provided</strong>
                        <p>The Borrower hereby affirms that the following KYC documents have been submitted to the Lender
                            and are true to the best of their knowledge:
                        </p>
                        <ol>
                            <li>PAN Card (Copy attached)</li>
                            <li>Aadhaar Card (Copy attached)</li>
                            <li>Passport-size Photograph</li>
                            <li>Bank Passbook / Cancelled Cheque (For disbursement and repayment tracking)</li>
                        </ol>
                    </div>
                    <div class="dclrtn-info-dtls">
                        <div class="dclrtn-info-dtls my-5">
                            <h4 class="mb-4">Borrower Details</h4>
                            <div class="dclrtn-info-dtls">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Particulars</th>
                                            <th>Details Provided by Borrower</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Shop Name</td>
                                            <td class="text-decoration-underline"><?= esc($businessName) ?></td>
                                        </tr>
                                        <tr>
                                            <td>Shop Address</td>
                                            <td class="text-decoration-underline"><?= esc($location) ?></td>
                                        </tr>
                                        <tr>
                                            <td>Type of Business</td>
                                            <td class="text-decoration-underline"><?= esc($businessType) ?></td>
                                        </tr>
                                        <tr>
                                            <td>Nature of Ownership</td>
                                            <td class="text-decoration-underline"><?= esc($businessType) ?></td>
                                        </tr>
                                        <tr>
                                            <td>Ownership Proof Attached</td>
                                            <td class="text-decoration-underline">YES</td>
                                        </tr>
                                        <tr>
                                            <td>Residence Proof Provided</td>
                                            <td class="text-decoration-underline">YES</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="dclrtn-info-dtls">
                        <strong>C. Stock Declaration</strong>
                        <p>The Borrower declares that, as of the date of agreement, the retail shop holds inventory of the
                            following estimated value:
                        </p>
                        <ul>
                            <li><strong>Total Estimated Stock Value:</strong>₹ <span><?= number_format($stock, 2) ?></span></li>
                            <li><strong>Nature of Stock:</strong> <span><?= 'Physical' ?></span></li>
                            <li><strong>Basis of Valuation:</strong> <span>₹ <?= number_format($stock, 2) ?></span></li>
                            <li><strong>Stock Valuation Date:</strong> <span><?= date('Y-m-d') ?></span></li>
                        </ul>
                        <p>Borrower agrees to update the stock valuation quarterly or as requested by Lender.</p>
                    </div>
                    <div class="dclrtn-info-dtls">
                        <strong>D. Daily Sales Declaration</strong>
                        <p>The Borrower voluntarily declares the average daily sales of the retail shop to be:</p>
                        <ul>
                            <li><strong>Estimated Daily Sales: </strong>₹ <span><?= number_format($dailySales, 2) ?></span></li>
                            <!-- <li><strong>Peak Season Variation:</strong> <span></span></li> -->
                            <li><strong>Average Monthly Sales (Last 3 months):</strong>₹ <span><?= number_format($dailySales * 30, 2) ?></span></li>
                        </ul>
                        <p>Supporting evidence such as sales register or UPI/transaction history may be provided upon
                            request.
                        </p>
                    </div>
                    <div class="dclrtn-info-dtls">
                        <strong>E. Affirmation & Good Faith Declaration</strong>
                        <p>The Borrower confirms and agrees that:</p>
                        <ul>
                            <li>1. The above declarations are made voluntarily and in good faith to support the unsecured
                                loan.
                            </li>
                            <li>2. Any misrepresentation shall constitute a breach of agreement.</li>
                            <li>3. The loan is being extended based on trust, without collateral, and subject to periodic
                                review by the Lender.
                            </li>
                            <li>4. The Lender reserves the right to seek additional documentation if needed.</li>
                        </ul>
                        <p>Supporting evidence such as sales register or UPI/transaction history may be provided upon
                            request.
                        </p>
                    </div>
                    <strong>Signed & Declared on this <u><?= date('Y-m-d') ?></u></strong>
                    <div class="dclrtn-info-dtls">
                        <strong>Borrower (Shop Owner)</strong>
                        <p>Signature: <span>Digitally Signed</span></p>
                        <p>Name: <span><?= esc($panName) ?></span></p>
                    </div>
                    <div class="dclrtn-info-dtls">
                        <strong>Enclosures (Tick as attached):</strong>
                        <ul class="list">
                            <li class="list-group-item">
                                <span style="font-family: 'DejaVu Sans'; font-size: 14px; color:green;">&#10004;</span>
                                <label class="form-check-label" for="panCard">PAN Card</label>
                            </li>
                            <li class="list-group-item">
                                <span style="font-family: 'DejaVu Sans'; font-size: 14px; color:green;">&#10004;</span>
                                <label class="form-check-label" for="aadhaarCard">Aadhaar Card</label>
                            </li>
                            <li class="list-group-item">
                                <span style="font-family: 'DejaVu Sans'; font-size: 14px; color:green;">&#10004;</span>
                                <label class="form-check-label" for="shopProof">Shop Ownership/Rental Proof</label>
                            </li>
                            <li class="list-group-item">
                                <span style="font-family: 'DejaVu Sans'; font-size: 14px; color:green;">&#10004;</span>
                                <label class="form-check-label" for="stockSheet">Stock Estimate Sheet</label>
                            </li>
                            <li class="list-group-item">
                                <span style="font-family: 'DejaVu Sans'; font-size: 14px; color:green;">&#10004;</span>
                                <label class="form-check-label" for="salesLog">Sales Log / UPI Statement (if any)</label>
                            </li>
                            <li class="list-group-item">
                                <span style="font-family: 'DejaVu Sans'; font-size: 14px; color:green;">&#10004;</span>
                                <label class="form-check-label" for="residenceProof">Residence Proof</label>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="dclrtn-info">
                    <h1 class="mb-4 text-center"><span>Retailer</span> ANNEXURE – II</h1>
                    <p class="text-center pb-0">Declaration by Shop Owner for Stock Collateral</p>
                    <p class="text-center pb-5">(To be executed on Shop Letterhead or ₹100 Stamp Paper)</p>
                    <div class="dclrtn-info-dtls">
                        <p><strong>To:</strong></p>
                        <p><strong>The Loan Officer</strong></p>
                        <p><strong>Ayush Fiscal Private Limited</strong></p>
                        <p><strong>Reg Address:-</strong> 53, SHYAMA PRASAD MUKHERJEE ROAD, KOLKATA - 700026</p>
                        <p><strong>Subject:</strong> Declaration of Stock as Collateral for Retail Shop Loan</p>
                    </div>
                    <div class="dclrtn-info-dtls">
                        <p>I, <span><?= esc($panName) ?></span>, DOB <span><?= esc($userDOB) ?></span>, residing at <span><?= esc($location) ?></span>, do hereby solemnly affirm and declare as under:
                        </p>
                    </div>
                    <p><strong>1. Personal and Shop Details</strong></p>
                    <ul>
                        <li>Name of Shop: <span><?= esc($businessName) ?></span></li>
                        <li>Shop Address: <span><?= esc($location) ?></span></li>
                        <li>Shop Type: <span><?= esc($businessType) ?></span></li>
                        <li>
                            <strong>Ownership Status:</strong>
                            <div class="subs-inp">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label" for="owned"><span style="font-family: 'DejaVu Sans'; font-size: 14px; color:green;">&#10004;</span><?= esc($shop_ownership) ?></label>
                                </div>

                            </div>
                        </li>
                        <li><strong>KYC Documents Attached:</strong>
                            <div class="dclrtn-info-dtls">
                                <div class="form-check">
                                    <label class="form-check-label" for="aadhaarCard"><span style="font-family: 'DejaVu Sans'; font-size: 14px; color:green;">&#10004;</span>Aadhaar Card</label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label" for="panCard"><span style="font-family: 'DejaVu Sans'; font-size: 14px; color:green;">&#10004;</span>PAN Card</label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label" for="shopCertificate"><span style="font-family: 'DejaVu Sans'; font-size: 14px; color:green;">&#10004;</span>Shop Establishment Certificate</label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label" for="rentAgreement"><span style="font-family: 'DejaVu Sans'; font-size: 14px; color:green;">&#10004;</span>Rent Agreement / Property Tax Receipt</label>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <p><strong>2. Declaration of Stock</strong></p>
                    <p>I hereby declare that as of the date of this declaration, the total estimated value of stock held in
                        my shop is:
                    </p>
                    <p>Rs. <?= number_format($stock, 2) ?> (<?php
                                                            $formatter = new NumberFormatter("en-IN", NumberFormatter::SPELLOUT);
                                                            $words = $formatter->format($stock);

                                                            echo ucfirst($words);
                                                            ?> Only), consisting primarily of the following categories:</p>
                    <div class="dclrtn-info-dtls">
                        <table class="table table-bordered align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Category of Goods</th>
                                    <th>Estimated Value (INR)</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td><?= esc($businessType) ?> </td>
                                    <td>₹ <?= number_format($stock, 2) ?> </td>
                                    <td>Approx ₹ <?= number_format($stock, 2) ?> Stock available, FI: <?= esc($stock_available) ?></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr class="fw-bold">
                                    <td colspan="2">Total</td>
                                    <td>₹ <?= number_format($stock, 2) ?> </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        <p>I understand and agree that this stock shall serve as collateral security against the loan
                            sanctioned by Ayush Fiscal Pvt. Ltd., and I affirm that:
                        </p>
                        <ul>
                            <li>The stock is free from any third-party claims or encumbrances.</li>
                            <li>I retain ownership and control over the said stock.</li>
                            <li>I shall not dispose of, pledge, or hypothecate the declared stock without prior written
                                consent from Ayush Fiscal Pvt. Ltd.
                            </li>
                        </ul>
                    </div>
                    <p><strong>3. Undertaking and Affirmation</strong></p>
                    <p>I further declare and undertake:</p>
                    <p>1. That the above information is true and correct to the best of my knowledge and belief.</p>
                    <p>2. That I shall maintain the stock level at not less than 75% of declared value during the loan
                        tenure.</p>
                    <p>3. That in the event of default, Ayush Fiscal Pvt. Ltd. shall have the right to take possession and
                        dispose of such stock to recover outstanding dues, in accordance with applicable laws.
                    </p>
                    <p>4. That I am executing this declaration voluntarily and with full understanding of its legal
                        implications.</p>
                    <p>Declared on this <span><?= date('l d') ?></span> of <span><?= date('Y') ?></span>, at <?= esc($location) ?><span></span>
                    </p>
                    <div class="dclrtn-info-dtls">
                        <h5 class="mb-3">Digitally Signed Declaration</h5>
                        <div class="dclrtn-info-dtls">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Signed By</th>
                                            <th>Name</th>
                                            <th>Designation</th>
                                            <th>Date</th>
                                            <th>Digital Signature</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Shop Owner (Declarant)</td>
                                            <td><?= esc($panName) ?></td>
                                            <td>Shop Owner</td>
                                            <td><?= date("d-m-Y H:i:s") ?></td>
                                            <td>Digitally Signed</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="dclrtn-info-dtls">
                        <p><strong>Mobile Number:</strong> <?= esc($mobile) ?></p>
                        <p><strong>PAN / Aadhaar No.:</strong> <?= esc($pan) ?> / <?= esc($adhar) ?></p>
                    </div>

                </div>
            </div>
            <div style="text-align: center; margin-top: 40px;">
                <a href="<?= base_url() ?>generate-pdf/<?= $member_id ?>" class="generate-btn">📄 eSign the Documents</a>
            </div>
        </div>
    </div>
</body>

</html>
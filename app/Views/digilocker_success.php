<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Aadhaar Verified - DigiLocker | RetailPe</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            background-color: #f1f3f5;
            font-family: 'Segoe UI', sans-serif;
        }

        .center-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            padding: 20px;
        }

        .success-card {
            width: 100%;
            max-width: 500px;
            background-color: #ffffff;
            padding: 25px 20px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        .digilocker-icon {
            width: auto;
            height: 45px;
            margin-bottom: 20px;
        }

        .success-icon {
            font-size: 50px;
            color: #28a745;
            margin-bottom: 12px;
        }

        h3 {
            font-size: 1.4rem;
        }

        p {
            font-size: 0.95rem;
            color: #6c757d;
        }

        .btn-success,
        .btn-outline-secondary {
            font-size: 1rem;
            padding: 10px 30px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .footer-note {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 30px;
        }

        .footer-note img {
            width: auto;
            height: 40px;
            vertical-align: middle;
            margin-right: 5px;
        }
    </style>
</head>

<body>

    <div class="container-fluid center-container">
        <div class="success-card">
            <!-- DigiLocker Logo -->
            <img src="https://www.digilocker.gov.in/assets/img/digilocker_logo.png" alt="DigiLocker" class="digilocker-icon">

            <!-- Success Emoji -->
            <div class="success-icon">✅</div>

            <!-- Message -->
            <h3 class="text-success">Aadhaar Verified Successfully</h3>
            <p>Your Aadhaar has been verified securely using DigiLocker and is now linked to your profile.</p>

            <!-- Action Buttons -->
            <!-- <a href="/next-step" class="btn btn-success w-100 mt-3">Continue</a> -->
            <button class="btn btn-outline-secondary w-100 mt-2" onclick="tryToClose()">Close</button>

            <!-- Footer Branding -->
            <div class="footer-note">
                🔐 Verified via <strong>DigiLocker</strong><br><br><br>
                ⚙️ Powered by
                <img src="https://www.retailpe.in/assets/img/Logo/Retail%20Pe.webp" alt="RetailPe Logo">
                <!-- <strong>Retail Pe</strong> -->
            </div>
        </div>
    </div>

    <script>
        function tryToClose() {
            window.open('', '_self');
            window.close();
        }
    </script>

</body>

</html>
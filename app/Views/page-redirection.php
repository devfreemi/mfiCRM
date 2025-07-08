<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Redirecting...</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

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
        session_start();

        if (isset($_SESSION['client_id']) && isset($_SESSION['member_id']) && isset($_SESSION['member_name'])) {
            $clientId = $_SESSION['client_id'];
            $member_id = $_SESSION['member_id'];
            $name     =  $_SESSION['member_name'];
        }
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
            $dataDocGet = [
                'member_id' =>  $member_id,
                'doc_name' =>   $name . 'LoanDocuments_eSigned',
                'path' =>  $responseGetPdf_json['data']['url'],
                'eSign' =>  "Y",
                'created_at' =>  date('Y-m-d H:i:s'),
                'updated_on' =>  date('Y-m-d H:i:s'),
                'clientID'  => $clientId
            ];
            $db = \Config\Database::connect();
            $db->table('retailer_loan_doc')->insert($dataDocGet);
        }
        ?>
        <h5 class="mt-3">Redirecting, please wait...</h5>
        <p class="text-warning-message">⚠️ Do not refresh or press back.</p>
    </div>

    <!-- Optional auto-redirect (simulated after 5s) -->
    <!-- <script>
        setTimeout(() => {
            window.location.href = "https://example.com/next-page";
        }, 5000);
    </script> -->

</body>

</html>
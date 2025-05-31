<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;

class ApiController extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        // Step 1: Get Access Token
        $url = 'https://uat.risewithprotean.io/v1/oauth/token';

        $apiKey = "PGI9xIw9g0ADlUODfTjNG9wA8bOjmi40Vjr7ePZKdhyrS0A1";
        $secretKey = "iQ2JY50gbNWY80TLw2AJAllJw7dVhRXkhmf87ts0vIuQKDnDdOFStna0FmTz4OIp";
        $auth = base64_encode("$apiKey:$secretKey");

        $data = http_build_query(['grant_type' => 'client_credentials']);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Basic $auth",
            "Content-Type: application/x-www-form-urlencoded"
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            die("Token Request Error: $error");
        }

        $response = json_decode($response, true);
        $accessToken = $response['access_token'] ?? null;
        if (!$accessToken) {
            die("Access token not found.");
        }

        // Step 2: Prepare Encrypted Request
        $apiPayload = ['vehicleNumber' => 'WB24BA4308'];
        $plainPayload = json_encode($apiPayload);

        // Load API provider's public key
        $apiProviderPublicKey = file_get_contents(WRITEPATH . 'certs/UAT_PublicKey.pem');
        if (!$apiProviderPublicKey) {
            die("API provider public key not found.");
        }
        $publicKeyResource = openssl_pkey_get_public($apiProviderPublicKey);

        if ($publicKeyResource === false) {
            echo "Error loading public key: " . openssl_error_string() . "\n";
        } else {
            echo "Public key loaded successfully.\n";
        }
        // Generate AES key and IV
        $aesKey = openssl_random_pseudo_bytes(32);
        $iv = openssl_random_pseudo_bytes(16);

        // Encrypt data
        $encryptedData = openssl_encrypt($plainPayload, 'AES-256-CBC', $aesKey, OPENSSL_RAW_DATA, $iv);
        $encryptedPayload = base64_encode($iv . $encryptedData);

        // Encrypt AES key with API provider's public key
        if (!openssl_public_encrypt($aesKey, $encryptedAESKey, $apiProviderPublicKey)) {
            die("Failed to encrypt AES key.");
        }
        $symmetricKeyBase64 = base64_encode($encryptedAESKey);

        // Hash the payload
        $hash = base64_encode(hash('sha256', $encryptedPayload, true));

        // Metadata
        $timestamp = gmdate("Y-m-d\TH:i:s.v\Z");
        $requestId = bin2hex(random_bytes(16));

        // Final request payload
        $finalPayload = json_encode([
            "data" => $encryptedPayload,
            "version" => "1.0.0",
            "symmetricKey" => $symmetricKeyBase64,
            "hash" => $hash,
            "timestamp" => $timestamp,
            "requestId" => $requestId
        ], JSON_UNESCAPED_SLASHES);

        // Step 3: Send Request
        $apiUrl = "https://uat.risewithprotean.io/api/v1/protean/vehicle-detailed-advanced";
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $finalPayload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $accessToken",
            "apikey: $apiKey",
            "Content-Type: application/json"
        ]);

        $response_op = curl_exec($ch);
        if (curl_errno($ch)) {
            die("Request Error: " . curl_error($ch));
        }
        curl_close($ch);

        echo "🔐 Encrypted Response:\n$response_op\n";

        // Step 4: Decrypt the Response
        $responseData = json_decode($response_op, true);
        if (!$responseData || !isset($responseData['data'], $responseData['symmetricKey'])) {
            die("Invalid response format.");
        }

        // Load your private key
        $privateKey = file_get_contents(WRITEPATH . 'certs/private_key.pem');
        $privateKeyResource = openssl_pkey_get_private($privateKey);

        if ($privateKeyResource === false) {
            echo "Error loading private key: " . openssl_error_string() . "\n";
        } else {
            echo "Private key loaded successfully.\n";
        }
        if (!$privateKey) {
            die("Private key not found.");
        }
        // Step 1: Load encrypted symmetric key from response
        $encryptedSymmetricKeyBase64 = $responseData['symmetricKey'] ?? null;
        if (!$encryptedSymmetricKeyBase64) {
            die("❌ symmetricKey not found in response.\n");
        }
        $encryptedKey = base64_decode($encryptedSymmetricKeyBase64);
        if (!$encryptedKey) {
            die("❌ Failed to base64-decode symmetricKey.\n");
        }

        // Step 3: Load private key
        $privateKeyPath = WRITEPATH . 'certs/private_key.pem';
        $privateKey = file_get_contents($privateKeyPath);
        if (!$privateKey) {
            die("❌ Failed to read private key.\n");
        }

        // Step 4: Decrypt the AES key
        $decryptedAESKey = null;
        if (!openssl_private_decrypt($encryptedKey, $decryptedAESKey, $privateKey)) {
            echo "❌ OpenSSL error: " . openssl_error_string() . "\n";
            die("❌ Failed to decrypt AES symmetric key.\n");
        }

        // Debug: Check AES key
        echo "✅ Decrypted AES Key Length: " . strlen($decryptedAESKey) . " bytes\n";
        echo "🔓 Decrypted AES Key (hex): " . bin2hex($decryptedAESKey) . "\n";
    }
}

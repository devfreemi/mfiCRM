<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;

class IpCheckController extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        $apiKey = "PGI9xIw9g0ADlUODfTjNG9wA8bOjmi40Vjr7ePZKdhyrS0A1";
        $secretKey = "iQ2JY50gbNWY80TLw2AJAllJw7dVhRXkhmf87ts0vIuQKDnDdOFStna0FmTz4OIp";
        $auth = base64_encode("$apiKey:$secretKey");

        // 1. Get OAuth Token
        $tokenUrl = 'https://uat.risewithprotean.io/v1/oauth/token';
        $data = http_build_query(['grant_type' => 'client_credentials']);
        $headers = [
            "Authorization: Basic $auth",
            "Content-Type: application/x-www-form-urlencoded"
        ];

        $accessToken = $this->sendCurl($tokenUrl, $data, $headers);
        $accessToken = json_decode($accessToken, true)['access_token'] ?? null;
        if (!$accessToken) {
            return $this->fail('Access token retrieval failed.');
        }

        // 2. Encrypt request
        $payload = ['vehicleNumber' => 'WB24BA4308'];
        $plainPayload = json_encode($payload);
        $aesKey = openssl_random_pseudo_bytes(32);
        $iv = openssl_random_pseudo_bytes(16);
        $encryptedData = openssl_encrypt($plainPayload, 'AES-256-CBC', $aesKey, OPENSSL_RAW_DATA, $iv);
        $encryptedPayload = base64_encode($iv . $encryptedData);

        $publicKey = file_get_contents(WRITEPATH . 'certs/UAT_PublicKey.pem');
        if (!openssl_public_encrypt($aesKey, $encryptedAESKey, $publicKey)) {
            return $this->fail('AES key encryption failed.');
        }
        $symmetricKeyBase64 = base64_encode($encryptedAESKey);
        $hash = base64_encode(hash('sha256', $encryptedPayload, true));

        // Metadata
        $timestamp = gmdate("Y-m-d\TH:i:s.v\Z");
        $requestId = bin2hex(random_bytes(16));

        $finalPayload = json_encode([
            "data" => $encryptedPayload,
            "version" => "1.0.0",
            "symmetricKey" => $symmetricKeyBase64,
            "hash" => $hash,
            "timestamp" => $timestamp,
            "requestId" => $requestId
        ], JSON_UNESCAPED_SLASHES);

        // 3. Send encrypted API request
        $apiUrl = "https://uat.risewithprotean.io/api/v1/protean/vehicle-detailed-advanced";
        $response_op = $this->sendCurl($apiUrl, $finalPayload, [
            "Authorization: Bearer $accessToken",
            "apikey: $apiKey",
            "Content-Type: application/json"
        ]);

        // 4. Decrypt response
        $responseData = json_decode($response_op, true);
        if (!isset($responseData['data'], $responseData['symmetricKey'])) {
            return $this->fail('Invalid response format.');
        }

        $encryptedSymmetricKey = base64_decode($responseData['symmetricKey']);
        $privateKey = file_get_contents(WRITEPATH . 'certs/private_key.pem');

        if (!openssl_private_decrypt($encryptedSymmetricKey, $decryptedAESKey, $privateKey)) {
            return $this->fail('Failed to decrypt AES symmetric key.');
        }

        $encryptedPayload = base64_decode($responseData['data']);
        $iv = substr($encryptedPayload, 0, 16);
        $ciphertext = substr($encryptedPayload, 16);
        $decryptedData = openssl_decrypt($ciphertext, 'AES-256-CBC', $decryptedAESKey, OPENSSL_RAW_DATA, $iv);

        return $this->respond([
            'decrypted_data' => json_decode($decryptedData, true)
        ]);
    }

    private function sendCurl($url, $payload, $headers)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            die("Curl error: " . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
}

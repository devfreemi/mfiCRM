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
        //
        $url = 'https://uat.risewithprotean.io/v1/oauth/token';

        // Your API credentials
        $apiKey = "PGI9xIw9g0ADlUODfTjNG9wA8bOjmi40Vjr7ePZKdhyrS0A1";       // Username
        $secretKey = "iQ2JY50gbNWY80TLw2AJAllJw7dVhRXkhmf87ts0vIuQKDnDdOFStna0FmTz4OIp"; // Password

        // Base64 encode for Basic Auth
        $auth = base64_encode("$apiKey:$secretKey");

        // Payload
        $data = http_build_query([
            'grant_type' => 'client_credentials'
        ]);
        // cURL setup
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Basic $auth",
            "Content-Type: application/x-www-form-urlencoded"
        ]);

        // Execute request
        $response = curl_exec($ch);

        // Error handling
        // if (curl_errno($ch)) {
        //     echo 'Error: ' . curl_error($ch);
        // } else {
        //     echo "Response:\n";
        //     echo $response;
        // }
        $error = curl_error($ch);

        curl_close($ch);

        if ($error) {
            return $this->respond(['error' => 'Internal Exception!' . $error], 502);
        } else {
            $response = json_decode($response, true);
            $accessToken = $response['access_token'];


            // return $this->respond(['success' => 'Success! ' . $response], 200);

            // Load public key from secure location
            $publicKeyPath = WRITEPATH . 'certs/public_key.pem';
            $publicKey = file_get_contents($publicKeyPath);

            // Generate AES symmetric key
            $symmetricKey = openssl_random_pseudo_bytes(32); // 256-bit key
            $iv = openssl_random_pseudo_bytes(16); // AES block size IV

            // Prepare the plain payload
            $dataApi = array(
                'vehicleNumber'              => 'WB24BA4308',

            );
            $data_json = json_encode($dataApi);
            $plainPayload = $data_json;
            // Encrypt the payload using AES-256-CBC
            $encryptedData = openssl_encrypt($plainPayload, 'AES-256-CBC', $symmetricKey, OPENSSL_RAW_DATA, $iv);
            $encryptedPayload = base64_encode($iv . $encryptedData); // prepend IV to encrypted content

            // Encrypt the symmetric key using RSA public key
            $encryptedSymmetricKey = base64_decode($symmetricKey);
            openssl_public_encrypt($symmetricKey, $encryptedSymmetricKey, $publicKey);
            $symmetricKeyBase64 = base64_encode($encryptedSymmetricKey);

            // Create SHA-256 hash of the encrypted payload
            $hash = base64_encode(hash('sha256', $encryptedPayload, true));

            // Timestamp and UUID
            $timestamp = gmdate("Y-m-d\TH:i:s.v\Z"); // ISO 8601 in UTC
            $requestId = bin2hex(random_bytes(16));  // UUID alternative

            // Final request payload
            $finalPayload = [
                "data" => $encryptedPayload,
                "version" => "1.0.0",
                "symmetricKey" => $symmetricKeyBase64,
                "hash" => $hash,
                "timestamp" => $timestamp,
                "requestId" => $requestId
            ];
            $jsonPayload = json_encode($finalPayload, JSON_UNESCAPED_SLASHES);

            // Prepare the cURL request
            $apiUrl = "https://uat.risewithprotean.io/api/v1/protean/vehicle-detailed-advanced"; // Replace with actual URL

            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer $accessToken",
                "apikey: " . $apiKey,
                "Content-Type: application/json"
            ]);

            $response_op = curl_exec($ch);

            if (curl_errno($ch)) {
                return ['error' => curl_error($ch)];
            }

            curl_close($ch);

            // return json_decode($response, true);
            echo "Response:\n";
            echo $response_op;
            echo "\n";


            //---------------------- Decrypt the response--------------------------

            // --------------------------------------------------------------------//
            $response_output = json_decode($response_op, true);
            // Step 1: Load your private key (stored securely)
            $privateKeyPath = WRITEPATH . 'certs/private_key.pem';
            $privateKey = file_get_contents($privateKeyPath);
            // Step 2: Decrypt the AES symmetric key
            $encryptedSymmetricKey = base64_decode($response_output['symmetricKey']);
            // openssl_private_decrypt($encryptedSymmetricKey, $symmetricKey, $privateKey);
            $ok = openssl_private_decrypt($encryptedSymmetricKey, $symmetricKey, $privateKey);
            if (!$ok) {
                echo $ok;
                echo "❌ Error decrypting symmetric key. Check your private key.\n";
                return;
            }
            // Step 3: Decode the data (extract IV + encrypted body)
            $encryptedPayload = base64_decode($response_output['data']);

            $iv = substr($encryptedPayload, 0, 16); // First 16 bytes = IV
            $ciphertext = substr($encryptedPayload, 16);

            // Step 4: Decrypt the payload
            $decryptedJson = openssl_decrypt($ciphertext, 'AES-256-CBC', $symmetricKey, OPENSSL_RAW_DATA, $iv);

            // Step 5: Optional - Validate hash
            $hashCalculated = base64_encode(hash('sha256', $encryptedPayload, true));
            echo "Hash Calculated:\n";
            echo $hashCalculated;
            echo "\n";
            $hashReceived = $response_output['hash'];
            echo "Hash Received:\n";
            echo $hashReceived;
            if ($hashCalculated !== $hashReceived) {
                // return ['error' => 'Hash mismatch: Data integrity cannot be verified.'];
                echo "Hash mismatch: Data integrity cannot be verified.";
            } else {
                // Step 6: Decode the JSON
                $decryptedData = json_decode($decryptedJson, true);
                // return $this->respond(['success' => 'Success! ' . $decryptedData], 200);
                echo "Decrypted Data:\n";
                echo json_encode($decryptedData, JSON_PRETTY_PRINT);
            }
        }
    }
}

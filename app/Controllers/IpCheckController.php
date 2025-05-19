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
        $privateKey = file_get_contents(WRITEPATH . 'certs/private_key.pem');

        // Load encrypted file
        $encrypted = json_decode($jsonPayload, true);

        // Decrypt AES key using private key
        $encryptedKey = base64_decode($encrypted['symmetricKey']);
        $decryptedKey = null;
        if (!openssl_private_decrypt($encryptedKey, $decryptedKey, $privateKey)) {
            die("❌ Failed to decrypt AES key\n");
        }

        // Extract IV + cipher
        $rawPayload = base64_decode($encrypted['data']);
        $iv = substr($rawPayload, 0, 16);
        $cipher = substr($rawPayload, 16);

        // Decrypt AES payload
        $decrypted = openssl_decrypt($cipher, 'AES-256-CBC', $decryptedKey, OPENSSL_RAW_DATA, $iv);

        // Output
        echo "✅ Decrypted:\n";
        print_r(json_decode($decrypted, true));
    }
}

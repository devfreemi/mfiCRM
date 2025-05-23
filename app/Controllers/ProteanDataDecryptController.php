<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ProteanDataDecryptController extends BaseController
{
    public function index()
    {
        $privateKeyPem = "-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDUQnEcGEc5bXNq
CfKsoww0/8kLB9nSOJtj+6cLExSOozaq3tmPaupCBU5EO07lVWK8l+LWMaK4Cnar
aWLaDPb/08TEOkOhQJL3zT00PcaH4KQEEA09hQQ3QcRB01CQY5RKAXzHkBlVVRzZ
B7GXGvjEYk1t2JXwLjxlb4lG2A7x3lF/fxPLr37+RbgN8BChOW0sWPxZmjuXEPKC
MjKEwNYAdLJLnbczoNFUz8YoKtkNtx0lSEHAgGfOJFj6HYHhrS9YZfXJFhJzo1mi
XbmXSt0O/lLc86HRz7ETCvAQS9ksxXuJaJGUD4FIHAEC65zY40xoxS6jYC4xUr8D
DrlGNxKVAgMBAAECggEATxF1Cw8sMQNJk/2irZaLLI7/+CDzjjDSCburrR9tb/jy
m1IqXxdnrjJFxnsFV76n6jL2J+4TfFVBgXbfgPSFaiUbzjEaEk54sOB+ZSCB/Qm8
b1r/Y6RSPrc8AX1TLIY7rhnBvqE6HqR2+423JN/CPX2U9E9TA0Zl7y9ORtqjLouB
mHMmZFLBB7JEp5Eh9JXhOXwMkGi00kZHBSofrFe3Rlm6O2KwDvMw9jTLNLYyxCyn
rdke0CFw33XOWZN5BFYmHeQItGMK+YHIfTWuD9NP5n0DPyMDP4Zsn2f4IX0PPS5k
UCNoMsRV4Nw/x6Ud6d/HThV/usEilAzBBvYar0+QXQKBgQD0elrm1PbjtNPs+4NS
IhePSZpPxS1rtCosbJ/d0/EUn2MxYxOySgzxoPZveCHMfCtqQB09AKgabOW0r6El
OifrcJ2FAcE7L7fU9SLeIJcO9/x4jOtcj1qnGghXYO1N6iW8WCHP4B4bzWrFF2/Y
XEpMdopR+qwvbEuWLay7iTQ9xwKBgQDeQ16DGmIaIkktLBjmvuPlK3lu4nM82UuI
YPSb/fcNHtOxEgNRr5C1Oqw+WrRrmejGqAvF7zACSgK3C/q0QhtMGu7jIwvny9r4
l2VVh0bzVBnRCo7zk6p29S0s4cZJd5qv81D3eXH7a2xNK7kd1FFt8rQwfl8l0YDJ
z3eYswPcwwKBgBQ+qZZh3kv+5mnCbh54CkFoU+n1Jwy7L57cI+TnrxgJFtq5HKFv
9mBv9thGN25KfgHfa0xo6IK/r8nlnU+K4FFj0vd3rLus+Oken5OLWVb4/CQzby4M
BPK/eh9aPGxuTk+pcKtZIMUmnofRqAZfphts2YluW6HVsvCtuZcSBoxzAoGBALnr
ttKKLkfYKEguHAJg6beYvzJ/IJBk4CcLm3IlZhRGliQHgllBOBYxJ0XdkBB2JV6P
0ZFX/Fvd1xmRXryMk9L8sJm1hk72movZ8L0ztKj0z8eqQlwESNLLUme50KlB8FaA
fVkV8L+0Y1rGWUpXgWosXIg4HZVAA7BM6KkGDhQLAoGAI+NOPEMctq78AdzYaoH9
v4jZYBbr2Ti8DOniAzg5cNFzfuJY2gjUx11J6rkyGikRIlva/1n9kKmWDjkBUmLx
oeoaQpnIkPnlm3AkcMPNSYCgEW9UnnsYYI4pGmlhRxANn2AaGllY4mpGZiUrlXCX
T7IoVAEXlh8OHFB595lLWp8=
-----END PRIVATE KEY-----";

        $encryptedKey = "SuXCrqvCwVh6/MTA32xXFil309992iTuQUR6uAo2mfN0NmNSC/e0X7C55HkchpRHTpJt0+dOAzowi+zqwlZUbe9I6KVHycT4SIBfyUR8YQoN0bWl+LESVsMILICTKiwi2I696BXXo2fPbfmaR+seXmBzXemN3oDGInVfLrwaxqsZ+XfyD6h28g5o5IjBv7b5xf8DjVC0sOCnhRgJjwWxlD548XEDZZXqqZGOtDlM+iKBvPXvVmYtt/JP3rmuohFicqNOmk+rkRxJEmeO0bg41xnAROWmYWNr3i3qVY62uwzSV326FmD6T/VGnh0y5UV6YYScHHbO375JguRaAgtc9Q==";
        $encData = "2Zew2A0Yu6N81NRE1YplhVc0ZzpYoIYrvdWda9iugT37kMgHWnS7Kg/YVRbmLmKh+G8ZROc=";
        $hash = "3l8tCzEIeXZHbyJ2dfu9od2HW5FDqcmTKsSNhQXbRIQ=";

        $decryptedKey = $this->decryptRSA($encryptedKey, $privateKeyPem);

        if ($decryptedKey === false) {
            echo "Decryption failed<br>";
            return;
        }

        echo "Decrypted Symmetric Key: $decryptedKey<br>";

        $decryptedData = $this->decrypt($encData, $decryptedKey);
        echo "Decoded Data: $decryptedData<br>";

        $calculatedHash = $this->calculateHmacSHA256($decryptedKey, $decryptedData);

        if ($calculatedHash === $hash) {
            echo "Hash matched<br>";
        } else {
            echo "Hash NOT matched<br>";
        }
    }

    private function decryptRSA($data, $privateKeyPem)
    {
        $privateKey = openssl_pkey_get_private($privateKeyPem);
        if (!$privateKey) {
            return false;
        }

        $data = base64_decode($data);
        $decrypted = null;

        $success = openssl_private_decrypt($data, $decrypted, $privateKey, OPENSSL_PKCS1_OAEP_PADDING);

        return $success ? $decrypted : false;
    }

    private function decrypt($encodedData, $decryptedKey)
    {
        $decodedData = base64_decode($encodedData);

        $iv = substr($decodedData, 0, 12);
        $salt = substr($decodedData, 12, 16);
        $ciphertext = substr($decodedData, 28, -16);
        $tag = substr($decodedData, -16);

        $aesKey = $this->getAESKeyFromPassword($decryptedKey, $salt);

        return openssl_decrypt($ciphertext, 'aes-256-gcm', $aesKey, OPENSSL_RAW_DATA, $iv, $tag);
    }

    private function getAESKeyFromPassword($password, $salt)
    {
        return openssl_pbkdf2($password, $salt, 32, 65536, 'sha256');
    }

    private function calculateHmacSHA256($key, $data)
    {
        $hash = hash_hmac('sha256', $data, $key, true);
        return base64_encode($hash);
    }
}

<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;
use App\Models\RetailerDocumentsModel;


class RetailerDocumentsController extends BaseController
{
    use ResponseTrait;
    public function add_doc()
    {
        //
        // date_default_timezone_set('Asia/Kolkata');
        // $model = new RetailerDocumentsModel();


        // $data = [
        //     'member_id'         => $this->request->getVar('member_id'),
        //     'document_path'     => $this->request->getVar('document_path'),
        //     'document_type'     => $this->request->getVar('doc_type'),
        //     'created_at'        => date('Y-m-d H:i:s')
        // ];

        // // $query = $model->save($data);
        // $db = db_connect();
        // $builder = $db->table('retailerdocuments');
        // // $builder->where('member_id', $this->request->getVar('member_id'));
        // $builder->insert($data);
        // $query = $builder->get();
        // if (!$query) {
        //     return $this->respond(['error' => 'Invalid Request.' . $query], 401);
        // } else {
        //     # code...
        //     return $this->respond(['documents' => $data], 200);
        // }
        date_default_timezone_set('Asia/Kolkata');
        log_message('info', 'Doc Upload Request Received!');
        $model = new RetailerDocumentsModel();

        $memberId = $this->request->getVar('member_id');
        $docType = $this->request->getVar('doc_type');
        $docPath = $this->request->getVar('document_path');

        $db = db_connect();
        $builder = $db->table('retailerdocuments');

        // 🔍 Count existing uploads for this doc type and member
        $countQuery = $builder->selectCount('id')
            ->where('member_id', $memberId)
            ->where('document_type', $docType)
            ->get()
            ->getRow();

        $existingCount = $countQuery->id ?? 0;

        // 🛑 Limit logic
        if (($docType === 'Shop_Image' && $existingCount >= 3) ||
            ($docType !== 'Shop_Image' && $existingCount >= 1)
        ) {
            return $this->respond([
                'status' => false,
                'message' => 'Upload limit reached for this document type.'
            ], 406);
            log_message('info', 'Upload limit reached for this document type!: ');
        }

        // ✅ Insert if within limits
        $data = [
            'member_id'     => $memberId,
            'document_path' => $docPath,
            'document_type' => $docType,
            'document_password' => $this->request->getVar('document_password') ?? null,
            'created_at'    => date('Y-m-d H:i:s')
        ];

        $builder->upsert($data);


        return $this->respond([
            'status' => true,
            'message' => 'Document uploaded successfully.',
            'documents' => $data
        ], 200);
        log_message('info', 'Retailer Documents Uploaded successfully!: ' . json_encode($data));
    }

    // Check if the shop image has been uploaded 3 times
    public function check_shop_image_status()
    {
        $memberId = $this->request->getVar('member_id');

        if (!$memberId) {
            return $this->respond([
                'status' => false,
                'message' => 'member_id is required.'
            ], 400);
        }

        $db = db_connect();
        $builder = $db->table('retailerdocuments');

        // Count Shop_Image uploads for this member
        $count = $builder->where('member_id', $memberId)
            ->where('document_type', 'Shop_Image')
            ->countAllResults();

        if ($count >= 3) {
            return $this->respond([
                'status' => true,
                'message' => 'image captured'
            ], 201);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'not captured'
            ], 200);
        }
    }

    // Check PAN AND Voter ID
    public function check_pan_voter_status()
    {
        $memberId = $this->request->getVar('member_id');

        if (!$memberId) {
            return $this->respond([
                'status' => false,
                'message' => 'member_id is required.'
            ], 400);
        }

        $db = db_connect();
        $builder = $db->table('retailerdocuments');

        // Check Pan_Card
        $panExists = $builder->where('member_id', $memberId)
            ->where('document_type', 'PAN_ID')
            ->countAllResults();

        // Reset builder for next query
        $builder = $db->table('retailerdocuments');

        // Check Voter_ID
        $voterExists = $builder->where('member_id', $memberId)
            ->where('document_type', 'VOTER_ID')
            ->countAllResults();

        if ($panExists > 0 && $voterExists > 0) {
            return $this->respond([
                'status' => true,
                'message' => 'captured'
            ], 201);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'not captured'
            ], 200);
        }
    }
    // check house address status
    public function check_home_address_proof_status()
    {
        $memberId = $this->request->getVar('member_id');

        if (!$memberId) {
            return $this->respond([
                'status' => false,
                'message' => 'member_id is required.'
            ], 400);
        }

        $db = db_connect();
        $builder = $db->table('retailerdocuments');

        // Always required
        $requiredDocs = ['Doc_House_Electricity_Bill', 'Doc_House_Property_Tax'];

        // Check if both Rent Agreement pages exist
        $rentPage1 = $builder->where('member_id', $memberId)
            ->where('document_type', 'Doc_House_Rent_Agreement_Page1')
            ->countAllResults();

        $builder = $db->table('retailerdocuments');
        $rentPage2 = $builder->where('member_id', $memberId)
            ->where('document_type', 'Doc_House_Rent_Agreement_Page2')
            ->countAllResults();

        $isRented = ($rentPage1 > 0 && $rentPage2 > 0);

        if ($isRented) {
            $requiredDocs[] = 'Doc_House_Rent_Agreement_Page1';
            $requiredDocs[] = 'Doc_House_Rent_Agreement_Page2';
        }

        // Now check all required documents
        $uploaded = [];
        foreach ($requiredDocs as $docType) {
            $builder = $db->table('retailerdocuments');
            $count = $builder->where('member_id', $memberId)
                ->where('document_type', $docType)
                ->countAllResults();

            $uploaded[$docType] = ($count > 0);
        }

        // Final output
        if (!in_array(false, $uploaded, true)) {
            return $this->respond([
                'status' => true,
                'message' => 'captured',
                'ownership' => $isRented ? 'rented' : 'owned'
            ], 201);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'not captured',
                'ownership' => $isRented ? 'rented' : 'owned',
                'missing_documents' => array_keys(array_filter($uploaded, fn($v) => !$v))
            ], 200);
        }
    }

    // check shop address status
    public function check_shop_address_proof_status()
    {
        $memberId = $this->request->getVar('member_id');

        if (!$memberId) {
            return $this->respond([
                'status' => false,
                'message' => 'member_id is required.'
            ], 400);
        }

        $db = db_connect();
        $builder = $db->table('retailerdocuments');

        // Always required
        $requiredDocs = ['Doc_Shop_Electricity_Bill', 'Doc_Shop_Property_Tax'];

        // Check if both Rent Agreement pages exist
        $rentPage1 = $builder->where('member_id', $memberId)
            ->where('document_type', 'Doc_Shop_Rent_Agreement_Page1')
            ->countAllResults();

        $builder = $db->table('retailerdocuments');
        $rentPage2 = $builder->where('member_id', $memberId)
            ->where('document_type', 'Doc_Shop_Rent_Agreement_Page2')
            ->countAllResults();

        $isRented = ($rentPage1 > 0 && $rentPage2 > 0);

        if ($isRented) {
            $requiredDocs[] = 'Doc_Shop_Rent_Agreement_Page1';
            $requiredDocs[] = 'Doc_Shop_Rent_Agreement_Page2';
        }

        // Now check all required documents
        $uploaded = [];
        foreach ($requiredDocs as $docType) {
            $builder = $db->table('retailerdocuments');
            $count = $builder->where('member_id', $memberId)
                ->where('document_type', $docType)
                ->countAllResults();

            $uploaded[$docType] = ($count > 0);
        }

        // Final output
        if (!in_array(false, $uploaded, true)) {
            return $this->respond([
                'status' => true,
                'message' => 'captured',
                'ownership' => $isRented ? 'rented' : 'owned'
            ], 201);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'not captured',
                'ownership' => $isRented ? 'rented' : 'owned',
                'missing_documents' => array_keys(array_filter($uploaded, fn($v) => !$v))
            ], 200);
        }
    }

    // Check business document uploads
    public function check_business_docs_status()
    {
        $memberId = $this->request->getVar('member_id');

        if (!$memberId) {
            return $this->respond([
                'status' => false,
                'message' => 'member_id is required.'
            ], 400);
        }

        $db = db_connect();
        $builder = $db->table('retailerdocuments');

        // Required business documents
        $requiredDocs = ['Doc_GST', 'Doc_Trade_License', 'Doc_ITR', 'Doc_Purchase_Bill', 'Doc_Sale_Bill'];

        $uploaded = [];

        foreach ($requiredDocs as $docType) {
            $builder = $db->table('retailerdocuments'); // reset builder for each loop
            $count = $builder->where('member_id', $memberId)
                ->where('document_type', $docType)
                ->countAllResults();

            $uploaded[$docType] = ($count > 0);
        }

        // Determine if all are uploaded
        if (!in_array(false, $uploaded, true)) {
            return $this->respond([
                'status' => true,
                'message' => 'captured'
            ], 201);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'not captured',
                'missing_documents' => array_keys(array_filter($uploaded, fn($v) => !$v))
            ], 200);
        }
    }

    // Check bank statement status
    public function check_bank_statement_status()
    {
        $memberId = $this->request->getVar('member_id');

        if (!$memberId) {
            return $this->respond([
                'status' => false,
                'message' => 'member_id is required.'
            ], 400);
        }

        $db = db_connect();
        $builder = $db->table('retailerdocuments');

        // Check if Bank Statement is uploaded
        $count = $builder->where('member_id', $memberId)
            ->where('document_type', 'Bank_Statement')
            ->countAllResults();

        if ($count > 0) {
            return $this->respond([
                'status' => true,
                'message' => 'captured'
            ], 201);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'not captured',
                'missing_documents' => 'Bank_Statement'
            ], 200);
        }
    }
    public function check_bank_statement_analyze()
    {

        $db = db_connect();
        log_message('info', 'Bank Statement Analyze Request Received!');
        $pdf = $this->request->getFile('file');
        $memberId = $this->request->getVar('member_id');
        if (!$pdf->isValid()) {
            return $this->fail('No valid file uploaded.');
        }

        // Move to temp path
        $tempPath = FCPATH . 'uploads/' . $pdf->getRandomName();
        $pdf->move(FCPATH . 'uploads', basename($tempPath));


        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://kyc-api.surepass.app/api/v1/bank/statement/upload',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                'file' => new \CURLFile($tempPath, 'application/pdf', $pdf->getClientName()),
            ],
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . getenv('SUREPASS_API_KEY_PROD'),
                'Accept: application/json',
            ],
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $response_decode = json_decode($response, true);

        $error = curl_error($curl);
        curl_close($curl);

        // return $this->respond([
        //     'bank_analyze_upload' => $response_decode
        // ], 200);
        log_message('info', 'Bank Statement Analyze Request Received!' . $response);
        if ($error) {
            # code...
            return $this->respond(['error' => 'Internal Exception!' . $error], 502);
        } else {
            if ($response_decode['status_code'] == 200) {
                try {
                    // Step 1: Assume you already have $client_id from previous analysis response
                    $client_id = $response_decode['data']['client_id'];

                    // Config
                    $apiKey = getenv('SUREPASS_API_KEY_PROD');
                    $url = 'https://kyc-api.surepass.app/api/v1/bank/statement/download';
                    $maxAttempts = 10; // Maximum retries
                    $attempt = 0;
                    $finalResponse = null;

                    do {
                        $attempt++;

                        $curlDown = curl_init();
                        curl_setopt_array($curlDown, [
                            CURLOPT_URL => $url,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_HTTPHEADER => [
                                'Authorization: Bearer ' . $apiKey,
                                'Accept: */*',
                                'User-Agent: PostmanRuntime/7.45.0',
                                'Cache-Control: no-cache',
                                'Connection: keep-alive'
                            ],
                            // ✅ Multipart like Postman
                            CURLOPT_POSTFIELDS => [
                                'client_id' => $client_id
                            ],
                        ]);

                        $responseDown = curl_exec($curlDown);
                        $httpCode = curl_getinfo($curlDown, CURLINFO_HTTP_CODE);
                        $error = curl_error($curlDown);
                        curl_close($curlDown);

                        log_message('info', "Attempt $attempt | HTTP $httpCode | Resp: $responseDown | Err: $error");

                        if ($error) {
                            return $this->respond(['error' => 'Curl Error: ' . $error], 502);
                        }

                        $response_decode_an = json_decode($responseDown, true);
                        $finalResponse = $response_decode_an;

                        // ✅ Exit loop if status is 200 and statement is ready
                        if ($httpCode === 200 && !empty($response_decode_an['data']['statement'])) {
                            break;
                        }

                        // Wait 3 seconds before retry
                        sleep(3);
                    } while ($attempt < $maxAttempts);

                    // Save to DB without model
                    $jsonString = json_encode($finalResponse, JSON_UNESCAPED_SLASHES);

                    // Check if record exists
                    $existing = $db->table('bank_statement_reports')
                        ->where('member_id', $memberId)
                        ->get()
                        ->getRowArray();

                    if ($existing) {
                        // Update existing record
                        $db->table('bank_statement_reports')
                            ->where('member_id', $memberId)
                            ->update([
                                'client_id' => $client_id,
                                'report_json' => $jsonString
                            ]);
                        log_message('info', "Bank statement updated for member: $memberId");
                    } else {
                        // Insert new record
                        $db->table('bank_statement_reports')->insert([
                            'member_id' => $memberId,
                            'client_id' => $client_id,
                            'report_json' => $jsonString
                        ]);
                        log_message('info', "Bank statement inserted for member: $memberId");
                    }

                    return $this->respond([
                        'message' => 'Bank statement saved successfully',
                        'bank_analyze_report' => $finalResponse
                    ], 200);
                } catch (\Exception $e) {
                    log_message('error', 'Bank Statement Analyze Exception: ' . $e->getMessage());
                    return $this->respond(['error' => 'Internal Exception!' . $e->getMessage()], 500);
                }
                // if ($error_an) {
                //     # code...
                //     log_message('error', 'Bank Statement Analyze Response !' . $error_an);
                //     return $this->respond(['error' => 'Internal Exception!' . $error_an], 502);
                // } else {
                //     # code...
                //     $response_decode_an = json_decode($responseDown, true);
                //     log_message('info', 'Bank Statement Analyze Completed Successfully !' . $responseDown);
                //     return $this->respond([
                //         'bank_analyze_report' => $response_decode_an
                //     ], 200);
                // }
            } else {
                # code...
                log_message('error', 'Internal Exception from API Providers!' . $response);
                return $this->respond(['error' => 'Internal Exception from API Providers!' . $response], 502);
            }
        }



        // Delete temp file
        @unlink($tempPath);

        // Return Surepass response
        // return $this->response
        //     ->setStatusCode($httpCode)
        //     ->setJSON(json_decode($response, true));

    }
    public function checkUploadedDocs()
    {
        $memberId = $this->request->getVar('memberIDFI');

        if (!$memberId) {
            return $this->respond(['error' => 'member_id is required'], 400);
        }

        // Required document types excluding Shop_Image (handled separately)
        $requiredDocuments = [
            'Doc_GST',
            'Doc_Trade_License',
            'Doc_Shop_Property_Tax',
            'Doc_Purchase_Bill',
            'Doc_Sale_Bill',
            'BANK_STATEMENT',
            'Doc_House_Electricity_Bill',
            'VOTER_ID',
            'PAN_ID',
            'Doc_House_Property_Tax',
            'Doc_Shop_Electricity_Bill',
            'Doc_ITR',
        ];

        $db = db_connect();

        // Get all document types uploaded by this member
        $builder = $db->table('retailerdocuments');
        $builder->select('document_type');
        $builder->where('member_id', $memberId);
        $query = $builder->get();
        $uploadedDocsRaw = $query->getResultArray();

        $uploadedDocs = array_column($uploadedDocsRaw, 'document_type');

        // Count number of Shop_Image
        $shopImageCount = 0;
        foreach ($uploadedDocs as $docType) {
            if ($docType === 'Shop_Image') {
                $shopImageCount++;
            }
        }

        // Check if all required documents (excluding Shop_Image) are present
        $missingDocs = array_diff($requiredDocuments, $uploadedDocs);

        // If Shop_Image < 3, add a custom missing message
        if ($shopImageCount < 3) {
            $missingDocs[] = 'Shop_Image (at least 3 required, found ' . $shopImageCount . ')';
        }

        if (empty($missingDocs)) {
            return $this->respond([
                'status' => true,
                'message' => '✅ All required documents including 3 Shop Images are uploaded.',
                'shop_image_count' => $shopImageCount,
                'uploaded_documents' => $uploadedDocs
            ], 200);
        } else {
            return $this->respond([
                'status' => false,
                'message' => '⚠️ Some documents are missing.',
                'missing_documents' => array_values($missingDocs),
                'shop_image_count' => $shopImageCount,
                'uploaded_documents' => $uploadedDocs
            ], 422);
        }
    }
}

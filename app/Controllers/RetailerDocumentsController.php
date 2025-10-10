<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;
use App\Models\RetailerDocumentsModel;

date_default_timezone_set('Asia/Kolkata');
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

        log_message('info', 'Doc Upload Request Received!');
        $model = new RetailerDocumentsModel();

        $memberId = $this->request->getVar('member_id');
        $docType = $this->request->getVar('doc_type');
        $docPath = $this->request->getVar('document_path');

        $db = db_connect();
        $builder = $db->table('retailerdocuments');


        $specialTypes = ['Doc_Sale_Bill', 'Doc_Purchase_Bill_1', 'Doc_Purchase_Bill_2', 'Doc_Purchase_Bill_3'];

        if (in_array($docType, $specialTypes)) {
            // 🔹 Check if this document type already exists for this member
            $existingDoc = $builder->where('member_id', $memberId)
                ->where('document_type', $docType)
                ->get()
                ->getRow();

            $data = [
                'member_id'        => $memberId,
                'document_path'    => $docPath,
                'document_type'    => $docType,
                'document_password' => $this->request->getVar('document_password') ?? null,
                'updated_at'       => date('Y-m-d H:i:s'),
            ];

            if ($existingDoc) {
                // 🔹 Update existing doc
                $builder->where('id', $existingDoc->id)->update($data);
                $message = 'Document updated successfully.';
            } else {
                // 🔹 Insert new doc
                $data['created_at'] = date('Y-m-d H:i:s');
                $builder->insert($data);
                $message = 'Document uploaded successfully.';
            }

            return $this->respond([
                'status' => true,
                'message' => $message,
                'documents' => $data
            ], 200);
        } else {
            // 🔹 Normal document types with upload limits
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
            }

            // ✅ Insert for normal docs
            $data = [
                'member_id'        => $memberId,
                'document_path'    => $docPath,
                'document_type'    => $docType,
                'document_password' => $this->request->getVar('document_password') ?? null,
                'created_at'       => date('Y-m-d H:i:s')
            ];

            $builder->insert($data);

            return $this->respond([
                'status' => true,
                'message' => 'Document uploaded successfully.',
                'documents' => $data
            ], 200);
        }
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
            ->where('document_type', 'PAN')
            ->countAllResults();

        // Reset builder for next query
        $builder = $db->table('retailerdocuments');

        // Check Voter_ID
        $voterExistsF = $builder->where('member_id', $memberId)
            ->where('document_type', 'VOTER_FRONT')
            ->countAllResults();

        $voterExistsB = $builder->where('member_id', $memberId)
            ->where('document_type', 'VOTER_BACK')
            ->countAllResults();
        log_message('info', "PAN Count: $panExists, Voter Front: $voterExistsF, Voter Back: $voterExistsB");
        if ($panExists > 0 && $voterExistsF > 0 && $voterExistsB > 0) {
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
        $requiredDocs = ['Doc_GST', 'Doc_Trade_License', 'Doc_ITR'];

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
                    $maxAttempts = 500; // Maximum retries
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
            'Doc_Purchase_Bill_1',
            'Doc_Purchase_Bill_2',
            'Doc_Purchase_Bill_3',
            'Doc_Sale_Bill',
            'BANK_STATEMENT',
            'Doc_House_Electricity_Bill',
            'VOTER_FRONT',
            'VOTER_BACK',
            'PAN',
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

    // OpenAI api
    public function analyzeUpiPdf()
    {
        $apiKey = getenv('OPENAI_API_KEY'); // from .env
        $pdfUrl = $this->request->getVar('document_path');
        if (!$pdfUrl) {
            return $this->respond([
                'status' => 'error',
                'message' => 'No PDF URL provided'
            ], 400);
        }

        // OpenAI API endpoint
        $url = "https://api.openai.com/v1/responses";
        // Prompt for advanced UPI PDF analysis
        $advancedPrompt = 'You are a financial data analyst. Analyze the provided UPI transaction data (PDF or extracted text). Follow these rules exactly.

            1) IDENTIFY CREDIT TRANSACTIONS (strict):
            - Prefer an explicit transaction type column. Treat as CREDIT any value (case-insensitive) matching any of:
                ["CREDIT","CREDITED","CR","CRDT","CR/NEFT","INWARD","INWARD-CREDIT","Crdt","C"]
            - If no explicit type column exists, INFER type as follows:
                • If amount is positive (no leading " - " or parentheses) treat as CREDIT.
                • If description contains keywords (case-insensitive) like "received", "credited", "received from", "amount credited", or "inward", treat as CREDIT.
                • If none of the above can be determined for a row, IGNORE the row.
            - ALWAYS ignore rows with indicators of failure/reversal: any status or description with ["FAILED","FAIL","REVERSAL","REVERSED","REFUND","CHARGEBACK"].

            2) AMOUNT PARSING:
            - Strip currency symbols and codes (₹, Rs., INR, USD), remove commas and whitespace.
            - Treat parentheses as negative (e.g. "(1,000)" => -1000.00).
            - Accept amounts with decimals and thousands separators.
            - If multiple numeric fields appear, attempt to choose the field labelled "amount","Net amount", "amt", "credit amount", or the numeric token placed next to words "credit"/"credited". If still ambiguous, choose the numeric token that is not labelled as "balance" or "bal".
            - Convert amounts to numeric decimals and round to 2 decimal places.

            3) DATE PARSING:
            - Recognize common date formats (DD-MM-YYYY, DD/MM/YYYY, YYYY-MM-DD, D MMM YYYY, etc.). Normalize to YYYY-MM-DD internally.
            - For unique-day counting, use only the date portion (ignore time).
            - If a row has no parsable date, exclude that row from the unique-date count but still include it in totals if it is a valid credit (see rule 1). If no rows have parsable dates but there are credit transactions, treat total_days = 1 for averaging (to avoid division by zero).

            4) DEDUPLICATION:
            - If transaction IDs/UTR are available, deduplicate by that ID.
            - Otherwise deduplicate by exact match of (normalized date + normalized amount + normalized description). Remove exact duplicates before counting.

            5) FILTERS:
            - Exclude rows where amount is zero or non-numeric after cleaning.
            - Exclude rows flagged as FAILED/REVERSAL/REFUND/CHARGEBACK even if amount appears positive.

            6) OUTPUT RULES (STRICT):
            - Compute:
                credit_transactions = integer count of unique CREDIT rows after deduplication and filtering.
                total_credit_amount = sum of amounts of those credit rows, numeric, rounded to 2 decimal places.
                total_days = count of unique dates among those rows (integer). If zero but credit_transactions > 0, set total_days = 1.
                daily_avg_credit = total_credit_amount ÷ total_days, numeric, rounded to 2 decimal places.
            - If there are zero valid CREDIT transactions OR file contains no valid transaction table, RETURN exactly:
                {
                "credit_transactions": "no data",
                "total_credit_amount": "no data",
                "total_days": "no data",
                "daily_avg_credit": "no data"
                }
            - OTHERWISE return exactly the numeric JSON structure below (no extra keys, no comments, no explanation):
                {
                "credit_transactions": number,
                "total_credit_amount": number,
                "total_days": number,
                "daily_avg_credit": number
                }

            7) STRICT FORMATTING:
            - Output MUST be valid JSON only, nothing else (no leading/trailing text, no markdown).
            - Numbers must be JSON numbers (not strings) except when returning the "no data" object which uses those four fields as the string "no data".
            - Round monetary values to two decimal places.

            8) ERRORS OR AMBIGUITY:
            - If you cannot reliably identify any CREDIT rows, return the "no data" JSON object above.
            - Do not guess or hallucinate transaction IDs, dates, or amounts.

            Now analyze the attached PDF/text using these rules and output the single JSON object described above.
            PROMPT';
        // Prompt for UPI PDF analysis
        $data = array(
            "model" => "gpt-4.1-mini",
            "input" => array(
                array(
                    "role" => "user",
                    "content" => array(
                        array(
                            "type" => "input_text",
                            "text" => $advancedPrompt
                            // "You are a financial data analyst. Analyze the provided UPI transaction data (PDF or extracted text).\n\n
                            //     1. Consider ONLY transactions where the transaction type is CREDIT.\n
                            //     2. Ignore DEBIT, REVERSAL, FAILED, or any non-CREDIT entries.\n
                            //     3. From the CREDIT transactions:\n
                            //        a. Count the total number of CREDIT transactions.\n
                            //        b. Calculate the total credited amount.\n
                            //        c. Count the total number of unique transaction dates (total_days).\n
                            //        d. Calculate the daily average credited amount (total credited amount ÷ total_days).\n
                            //     4. Respond only with valid structured JSON in this exact format:\n
                            //     {\n
                            //       \"credit_transactions\": number,\n
                            //       \"total_credit_amount\": number,\n
                            //       \"total_days\": number,\n
                            //       \"daily_avg_credit\": number\n
                            //     }\n
                            //     5. If there are no CREDIT transactions or the file does not contain valid data, return:\n
                            //     {\n
                            //       \"credit_transactions\": \"no data\",\n
                            //       \"total_credit_amount\": \"no data\",\n
                            //       \"total_days\": \"no data\",\n
                            //       \"daily_avg_credit\": \"no data\"\n
                            //     }"
                        ),
                        array(
                            "type" => "input_file",
                            "file_url" => $pdfUrl
                        )
                    )
                )
            )
        );

        // Initialize cURL
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: ' . 'Bearer ' . $apiKey
            ),
            CURLOPT_POSTFIELDS => json_encode($data),
        ));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return $this->respond([
                'status' => 'error',
                'message' => curl_error($ch)
            ], 500);
        }

        curl_close($ch);

        // Decode AI response
        $responseArray = json_decode($response, true);

        // Extract AI JSON
        $rawJsonText = $responseArray['output'][0]['content'][0]['text'] ?? null;
        log_message('info', 'OpenAI Response: ' . $rawJsonText);

        if ($rawJsonText) {
            $cleanJson = trim($rawJsonText);
            $cleanJson = preg_replace('/^```[a-z]*\n?/i', '', $cleanJson);
            $cleanJson = preg_replace('/```$/', '', $cleanJson);
            $cleanJson = trim($cleanJson);

            // Fix numeric formatting issues
            $cleanJsonText = preg_replace('/(?<=\d),(?=\d)/', '', $cleanJson);

            $upiData = json_decode($cleanJsonText, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($upiData)) {
                return $this->respond([
                    'status' => 'success',
                    'data' => $upiData
                ], 200);
            } else {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Invalid JSON format in AI response',
                    'raw_text' => $rawJsonText
                ], 500);
            }
        } else {
            return $this->respond([
                'status' => 'error',
                'message' => 'AI response did not contain valid UPI data',
                'ai_response' => $responseArray
            ], 500);
        }
    }
    public function analyzeImage()
    {
        $apiKey = getenv('OPENAI_API_KEY'); // from .env
        $imageUrl = $this->request->getVar('document_path');
        if (!$imageUrl) {
            return $this->respond([
                'status' => 'error',
                'message' => 'No image URL provided'
            ], 400);
        }

        // Prepare OpenAI API Request
        $url = "https://api.openai.com/v1/responses";

        $data = array(
            "model" => "gpt-4.1-mini",
            "input" => array(
                array(
                    "role" => "user",
                    "content" => array(
                        array(
                            "type" => "input_text",
                            "text" => "You are an expert data analyst. Analyze the provided image carefully. 
                                        1. Count the total number of rows in the table. 
                                        2. If the total row count is less than 55, return:
                                        {\"total_sale\": \"incorrect\", \"average_sale\": \"incorrect\", \"missing_rows\": \"yes\"}.
                                        3. Otherwise:
                                        a. Identify all rows that contain 'Todays Sale' or similar terms. 
                                        b. Extract the numeric sale values from each of those rows. 
                                        c. Calculate the SUM of all 'Todays Sale' rows. 
                                        d. Calculate the AVERAGE sale value. 
                                        e. Respond only with structured JSON in this format:
                                            {\"total_sale\": number, \"average_sale\": number}.
                                        4. If the image is missing, unreadable, or does not contain sales data,
                                        return: {\"total_sale\": \"no data\", \"average_sale\": \"no data\"}."
                        ),
                        array(
                            "type" => "input_image",
                            "image_url" => $imageUrl
                        )
                    )
                )
            )
        );

        // Initialize cURL
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: ' . 'Bearer ' . $apiKey
            ),
            CURLOPT_POSTFIELDS => json_encode($data),
        ));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return $this->respond([
                'status' => 'error',
                'message' => curl_error($ch)
            ], 500);
        }

        curl_close($ch);

        // Decode the OpenAI full response
        $responseArray = json_decode($response, true);

        // Extract the embedded JSON text
        $rawJsonText = $responseArray['output'][0]['content'][0]['text'] ?? null;
        log_message('info', 'OpenAI Response: ' . $rawJsonText);
        if ($rawJsonText) {
            // 1️⃣ Remove commas between digits
            $cleanJson = trim($rawJsonText);
            $cleanJson = preg_replace('/^```[a-z]*\n?/i', '', $cleanJson); // Remove starting ```json
            $cleanJson = preg_replace('/```$/', '', $cleanJson);           // Remove ending ```
            $cleanJson = trim($cleanJson);

            $cleanJsonText = preg_replace('/(?<=\d),(?=\d)/', '', $cleanJson);

            // 2️⃣ Decode the cleaned JSON
            $salesData = json_decode($cleanJsonText, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($salesData)) {
                $totalSale = $salesData['total_sale'] ?? 'no data';
                $averageSale = $salesData['average_sale'] ?? 'no data';

                // 3️⃣ Convert numeric strings to numbers
                if (is_string($totalSale) && is_numeric($totalSale)) {
                    $totalSale = $totalSale + 0;
                }
                if (is_string($averageSale) && is_numeric($averageSale)) {
                    $averageSale = $averageSale + 0;
                }
                // Return the cleaned response

                if ($totalSale === 'no data' || $averageSale === 'no data') {
                    return $this->respond([
                        'status' => 'error',
                        'message' => 'AI could not extract valid sales data',
                        'total_sale' => $totalSale,
                        'average_sale' => $averageSale
                    ], 500);
                }

                return $this->respond([
                    'status' => 'success',
                    'total_sale' => $totalSale,
                    'average_sale' => $averageSale
                ], 200);
            } else {
                // JSON decoding failed
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Invalid JSON format in AI response text',
                    'raw_text' => $rawJsonText
                ], 500);
            }
        } else {
            // Fallback if OpenAI returned unexpected format
            return $this->respond([
                'status' => 'error',
                'message' => 'AI response did not contain sales data',
                'ai_response' => $responseArray
            ], 500);
        }
    }

    // For Purchase Bill
    public function analyzeImagePurchase()
    {
        $apiKey = getenv('OPENAI_API_KEY'); // from .env

        // Get JSON from React Native
        $rawInput = $this->request->getJSON(true);
        $imageUrls = $rawInput['document_path'] ?? [];
        $memberId = $this->request->getVar('member_id');
        // Validate URLs
        if (!is_array($imageUrls) || count($imageUrls) === 0) {
            return $this->respond([
                'status' => 'error',
                'message' => 'No image URLs provided'
            ], 400);
            log_message('error', 'No image URLs provided');
        }

        // Build OpenAI prompt for analyzing 3 purchase bills
        $prompt = "
                        You are an expert data analyst for retail purchase bills. Analyze the 3 provided images, each containing a purchase bill. Perform the following steps:

                        1. Read and understand all 3 purchase bills from the images.
                        2. Extract the total purchase value from each bill. 
                        - Look for terms like 'Total', 'Grand Total', 'Total Amount', or similar.
                        - Ignore GST breakdown or other intermediate totals unless marked as the final bill total.
                        3. Calculate the following:
                        - total_purchase_bill_1 = total purchase value of the first bill
                        - total_purchase_bill_2 = total purchase value of the second bill
                        - total_purchase_bill_3 = total purchase value of the third bill
                        4. Compute combined and summary values:
                        - average_purchase = average of the 3 total purchase values
                        - highest_purchase_value = the highest total purchase value among the 3 bills
                        - highest_purchase_bill = which bill has the highest purchase (Bill_1, Bill_2, or Bill_3)
                        5. Return ONLY structured JSON in this exact format (numbers without currency symbols):

                        {
                        \"bill_1_total\": number,
                        \"bill_2_total\": number,
                        \"bill_3_total\": number,
                        \"average_purchase\": number,
                        \"highest_purchase_value\": number,
                        \"highest_purchase_bill\": \"Bill_1\" | \"Bill_2\" | \"Bill_3\"
                        }

                        6. If any bill is missing, unreadable, or has no total, return \"no data\" for that bill.
                        7. Do not include any explanation, only JSON output.
                        ";

        // Build OpenAI request content
        $content = [
            [
                "type" => "input_text",
                "text" => $prompt
            ]
        ];

        // Add all images
        foreach ($imageUrls as $urlItem) {
            $content[] = [
                "type" => "input_image",
                "image_url" => $urlItem
            ];
        }

        $data = [
            "model" => "gpt-4.1-mini",
            "input" => [
                [
                    "role" => "user",
                    "content" => $content
                ]
            ]
        ];

        // Initialize cURL
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "https://api.openai.com/v1/responses",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: ' . 'Bearer ' . $apiKey
            ],
            CURLOPT_POSTFIELDS => json_encode($data),
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return $this->respond([
                'status' => 'error',
                'message' => curl_error($ch)
            ], 500);
        }

        curl_close($ch);

        // Decode OpenAI response
        $responseArray = json_decode($response, true);
        $rawJsonText = $responseArray['output'][0]['content'][0]['text'] ?? null;
        log_message('info', 'OpenAI Multi-Image Purchase Bill Response: ' . $rawJsonText);

        if ($rawJsonText) {
            // 1️⃣ Remove commas between digits
            $cleanJson = trim($rawJsonText);
            $cleanJson = preg_replace('/^```[a-z]*\n?/i', '', $cleanJson); // Remove starting ```json
            $cleanJson = preg_replace('/```$/', '', $cleanJson);           // Remove ending ```
            $cleanJson = trim($cleanJson);

            $cleanJsonText = preg_replace('/(?<=\d),(?=\d)/', '', $cleanJson);

            // 2️⃣ Decode the cleaned JSON
            $salesData = json_decode($cleanJsonText, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($salesData)) {


                // ✅ Insert into DB

                if ($memberId) {
                    $db = db_connect();
                    $table = $db->table('retailer_purchase_analysis');
                    // Prepare data (exclude member_id for update)
                    $data = [
                        'bill_1_total' => $salesData['bill_1_total'] ?? 0,
                        'bill_2_total' => $salesData['bill_2_total'] ?? 0,
                        'bill_3_total' => $salesData['bill_3_total'] ?? 0,
                        'average_purchase' => $salesData['average_purchase'] ?? 0,
                        'highest_purchase_value' => $salesData['highest_purchase_value'] ?? 0,
                        'highest_purchase_bill' => $salesData['highest_purchase_bill'] ?? 0,
                        'created_at' => date('Y-m-d H:i:s')
                    ];

                    // Check if record exists for the member
                    $existing = $table->where('member_id', $memberId)->get()->getRow();

                    if ($existing) {
                        // ✅ Update existing record
                        $table->where('member_id', $memberId)->update($data);
                        log_message('info', 'Purchase analysis updated in DB for member: ' . $memberId);
                    } else {
                        // ✅ Insert new record
                        $data['member_id'] = $memberId; // Only add for insert
                        $table->insert($data);
                        log_message('info', 'Purchase analysis inserted in DB for member: ' . $memberId);
                    }
                    return $this->respond([
                        'status' => 'success',
                        'data' => [
                            'bill_1_total' => $salesData['bill_1_total'] ?? 'no data',
                            'bill_2_total' => $salesData['bill_2_total'] ?? 'no data',
                            'bill_3_total' => $salesData['bill_3_total'] ?? 'no data',
                            'average_purchase' => $salesData['average_purchase'] ?? 'no data',
                            'highest_purchase_value' => $salesData['highest_purchase_value'] ?? 'no data',
                            'highest_purchase_bill' => $salesData['highest_purchase_bill'] ?? 'no data'
                        ]
                    ], 200);
                    log_message('info', 'Success of AI Bill Analyzer');
                }
            } else {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Invalid JSON format in AI response',
                    'raw_text' => $rawJsonText
                ], 500);
                log_message('error', 'Invalid JSON format in AI response--> ' . $rawJsonText);
            }
        } else {
            return $this->respond([
                'status' => 'error',
                'message' => 'AI response did not contain Purchase data',
                'ai_response' => $responseArray
            ], 500);
            log_message('error', 'AI response did not contain Purchase data--> ' . $responseArray);
        }
    }
}

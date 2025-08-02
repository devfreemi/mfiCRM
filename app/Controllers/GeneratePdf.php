<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\MemberModel;
use App\Models\RetailerLoanDocModel;
use Predis\Command\Argument\Server\To;

class GeneratePdf extends BaseController
{
    public function previewSanctionLetter($memberId)
    {
        $model = new MemberModel();

        $db = db_connect();
        $builder = $db->table('members');
        $builder->select('
                        members.*, 
                        loans.id as loan_id,
                        loans.groupId as loan_groupId,
                        loans.member_id as loan_member_id,
                        loans.loan_amount,
                        loans.interest,
                        loans.total_amount,
                        loans.disbursable_amount,
                        loans.chargesandinsurance,
                        loans.loan_tenure,
                        loans.roi,
                        loans.emi,
                        loans.loan_due,
                        loans.pending_emi,
                        loans.loan_type,
                        loans.loan_status,
                        loans.application_stage,
                        loans.otpVerify,
                        loans.applicationID,
                        loans.employee_id,
                        loans.created_at as loan_created_at,
                        loans.updated_at as loan_updated_at,

                        field_inspection_feedback.fiInspector_name,
                        field_inspection_feedback.retailer_present,
                        field_inspection_feedback.retailer_behavior_professional,
                        field_inspection_feedback.retailer_aware_products,
                        field_inspection_feedback.retailer_needs_training,
                        field_inspection_feedback.shop_clean,
                        field_inspection_feedback.products_displayed,
                        field_inspection_feedback.stock_available,
                        field_inspection_feedback.promo_materials_visible,
                        field_inspection_feedback.location_accessible,
                        field_inspection_feedback.payment_behavior,
                        field_inspection_feedback.shop_ownership,
                        field_inspection_feedback.house_ownership,
                        field_inspection_feedback.documents_verified,
                        field_inspection_feedback.documents_received,
                        field_inspection_feedback.verified_fields,
                        field_inspection_feedback.inspector_comments,
                        field_inspection_feedback.fi_status,
                        field_inspection_feedback.fi_final,
                        field_inspection_feedback.inspector_daily_sales,
                        field_inspection_feedback.inspector_stock_value,
                        field_inspection_feedback.inspector_month_purchase,
                        field_inspection_feedback.latitude,
                        field_inspection_feedback.longitude,
                        field_inspection_feedback.place_name,
                        field_inspection_feedback.shop_photo,
                        field_inspection_feedback.selfie_with_owner,
                        field_inspection_feedback.created_at as fi_created_at
                    ');
        $builder->join('loans', 'loans.member_id = members.member_id', 'left');
        $builder->join('field_inspection_feedback', 'field_inspection_feedback.member_id = members.member_id', 'left');
        $builder->where('members.member_id', $memberId);

        $record = $builder->get()->getRowArray();

        if (!$record) {
            return "Member not found.";
        }

        $data = [
            // Member fields
            'id'                 => $record['id'],
            'member_id'          => $record['member_id'],
            'groupName'          => $record['groupName'],
            'groupId'            => $record['groupId'],
            'agent'              => $record['agent'],
            'aadhaarVerified'    => $record['aadhaarVerified'],
            'aadhaarData'        => $record['aadhaarData'],
            'authenticatePAN'    => $record['authenticatePAN'],
            'panName'            => $record['panName'],
            'mobile'             => $record['mobile'],
            'pan'                => $record['pan'],
            'gst'                => $record['gst'],
            'gstValidation'      => $record['gstValidation'],
            'adhar'              => $record['adhar'],
            'name'               => $record['name'],
            'location'           => $record['location'],
            'pincode'            => $record['pincode'],
            'gender'             => $record['gender'],
            'marital'            => $record['marital'],
            'occupation'         => $record['occupation'],
            'businessType'       => $record['businessType'],
            'businessName'       => $record['businessName'],
            'footFall'           => $record['footFall'],
            'stock'              => $record['stock'],
            // 'purchase'           => $record['purchase'],
            'outstanding'        => $record['outstanding'],
            'estab'              => $record['estab'],
            'dailySales'         => $record['dailySales'],
            'image'              => $record['image'],
            'bankAccount'        => $record['bankAccount'],
            'ifsc'               => $record['ifsc'],
            'bankName'           => $record['bankName'],
            'bankBranch'         => $record['bankBranch'],
            'bankCity'           => $record['bankCity'],
            'bankState'          => $record['bankState'],
            'bankAddress'        => $record['bankAddress'],
            'eli_run'            => $record['eli_run'],
            'month_purchase'     => $record['month_purchase'],
            'remarks'            => $record['remarks'],
            'comments'           => $record['comments'],
            'created_at'         => $record['created_at'],
            'updated_at'         => $record['updated_at'],
            'userDOB'            => $record['userDOB'] ?? null,

            // 💰 Loan Fields
            'loan_id'            => $record['loan_id'],
            'loan_groupId'       => $record['loan_groupId'],
            'loan_member_id'     => $record['loan_member_id'],
            'loan_amount'        => $record['loan_amount'],
            'interest'           => $record['interest'],
            'total_amount'       => $record['total_amount'],
            'disbursable_amount' => $record['disbursable_amount'],
            'chargesandinsurance' => $record['chargesandinsurance'],
            'loan_tenure'        => $record['loan_tenure'],
            'roi'                => $record['roi'],
            'emi'                => $record['emi'],
            'loan_due'           => $record['loan_due'],
            'pending_emi'        => $record['pending_emi'],
            'loan_type'          => $record['loan_type'],
            'loan_status'        => $record['loan_status'],
            'application_stage'  => $record['application_stage'],
            'otpVerify'          => $record['otpVerify'],
            'applicationID'      => $record['applicationID'],
            'employee_id'        => $record['employee_id'],
            'loan_created_at'    => $record['loan_created_at'],
            'loan_updated_at'    => $record['loan_updated_at'],

            // 📝 Field Inspection Feedback
            'fiInspector_name'   => $record['fiInspector_name'],
            'retailer_present'   => $record['retailer_present'],
            'retailer_behavior_professional' => $record['retailer_behavior_professional'],
            'retailer_aware_products'        => $record['retailer_aware_products'],
            'retailer_needs_training'        => $record['retailer_needs_training'],
            'shop_clean'         => $record['shop_clean'],
            'products_displayed' => $record['products_displayed'],
            'stock_available'    => $record['stock_available'],
            'promo_materials_visible' => $record['promo_materials_visible'],
            'location_accessible' => $record['location_accessible'],
            'payment_behavior'   => $record['payment_behavior'],
            'shop_ownership'     => $record['shop_ownership'],
            'house_ownership'    => $record['house_ownership'],
            'documents_verified' => $record['documents_verified'],
            'documents_received' => $record['documents_received'],
            'verified_fields'    => $record['verified_fields'],
            'inspector_comments' => $record['inspector_comments'],
            'fi_status'          => $record['fi_status'],
            'fi_final'           => $record['fi_final'],
            'inspector_daily_sales'   => $record['inspector_daily_sales'],
            'inspector_stock_value'  => $record['inspector_stock_value'],
            'inspector_month_purchase' => $record['inspector_month_purchase'],
            'latitude'           => $record['latitude'],
            'longitude'          => $record['longitude'],
            'place_name'         => $record['place_name'],
            'shop_photo'         => $record['shop_photo'],
            'selfie_with_owner'  => $record['selfie_with_owner'],
            'fi_created_at'      => $record['fi_created_at'],
        ];


        return view('sanction-letter', $data); // Same view as PDF
    }
    public function generatePdf($member_id)
    {
        $model = new MemberModel();

        // Fetch member data by WHERE member_id = $member_id
        $db = db_connect();
        $builder = $db->table('members');
        $builder->select('
                        members.*, 
                        loans.id as loan_id,
                        loans.groupId as loan_groupId,
                        loans.member_id as loan_member_id,
                        loans.loan_amount,
                        loans.interest,
                        loans.total_amount,
                        loans.disbursable_amount,
                        loans.chargesandinsurance,
                        loans.loan_tenure,
                        loans.roi,
                        loans.emi,
                        loans.loan_due,
                        loans.pending_emi,
                        loans.loan_type,
                        loans.loan_status,
                        loans.application_stage,
                        loans.otpVerify,
                        loans.applicationID,
                        loans.employee_id,
                        loans.created_at as loan_created_at,
                        loans.updated_at as loan_updated_at,

                        field_inspection_feedback.fiInspector_name,
                        field_inspection_feedback.retailer_present,
                        field_inspection_feedback.retailer_behavior_professional,
                        field_inspection_feedback.retailer_aware_products,
                        field_inspection_feedback.retailer_needs_training,
                        field_inspection_feedback.shop_clean,
                        field_inspection_feedback.products_displayed,
                        field_inspection_feedback.stock_available,
                        field_inspection_feedback.promo_materials_visible,
                        field_inspection_feedback.location_accessible,
                        field_inspection_feedback.payment_behavior,
                        field_inspection_feedback.shop_ownership,
                        field_inspection_feedback.house_ownership,
                        field_inspection_feedback.documents_verified,
                        field_inspection_feedback.documents_received,
                        field_inspection_feedback.verified_fields,
                        field_inspection_feedback.inspector_comments,
                        field_inspection_feedback.fi_status,
                        field_inspection_feedback.fi_final,
                        field_inspection_feedback.inspector_daily_sales,
                        field_inspection_feedback.inspector_stock_value,
                        field_inspection_feedback.inspector_month_purchase,
                        field_inspection_feedback.latitude,
                        field_inspection_feedback.longitude,
                        field_inspection_feedback.place_name,
                        field_inspection_feedback.shop_photo,
                        field_inspection_feedback.selfie_with_owner,
                        field_inspection_feedback.created_at as fi_created_at
                    ');
        $builder->join('loans', 'loans.member_id = members.member_id', 'left');
        $builder->join('field_inspection_feedback', 'field_inspection_feedback.member_id = members.member_id', 'left');
        $builder->where('members.member_id', $member_id);

        $record = $builder->get()->getRowArray();

        if (!$record) {
            return "Member not found.";
        }

        $data = [
            // Member fields
            'id'                 => $record['id'],
            'member_id'          => $record['member_id'],
            'groupName'          => $record['groupName'],
            'groupId'            => $record['groupId'],
            'agent'              => $record['agent'],
            'aadhaarVerified'    => $record['aadhaarVerified'],
            'aadhaarData'        => $record['aadhaarData'],
            'authenticatePAN'    => $record['authenticatePAN'],
            'panName'            => $record['panName'],
            'mobile'             => $record['mobile'],
            'pan'                => $record['pan'],
            'gst'                => $record['gst'],
            'gstValidation'      => $record['gstValidation'],
            'adhar'              => $record['adhar'],
            'name'               => $record['name'],
            'location'           => $record['location'],
            'pincode'            => $record['pincode'],
            'gender'             => $record['gender'],
            'marital'            => $record['marital'],
            'occupation'         => $record['occupation'],
            'businessType'       => $record['businessType'],
            'businessName'       => $record['businessName'],
            'footFall'           => $record['footFall'],
            'stock'              => $record['stock'],
            // 'purchase'           => $record['purchase'],
            'outstanding'        => $record['outstanding'],
            'estab'              => $record['estab'],
            'dailySales'         => $record['dailySales'],
            'image'              => $record['image'],
            'bankAccount'        => $record['bankAccount'],
            'ifsc'               => $record['ifsc'],
            'bankName'           => $record['bankName'],
            'bankBranch'         => $record['bankBranch'],
            'bankCity'           => $record['bankCity'],
            'bankState'          => $record['bankState'],
            'bankAddress'        => $record['bankAddress'],
            'eli_run'            => $record['eli_run'],
            'month_purchase'     => $record['month_purchase'],
            'remarks'            => $record['remarks'],
            'comments'           => $record['comments'],
            'created_at'         => $record['created_at'],
            'updated_at'         => $record['updated_at'],
            'userDOB'            => $record['userDOB'] ?? null,

            // 💰 Loan Fields
            'loan_id'            => $record['loan_id'],
            'loan_groupId'       => $record['loan_groupId'],
            'loan_member_id'     => $record['loan_member_id'],
            'loan_amount'        => $record['loan_amount'],
            'interest'           => $record['interest'],
            'total_amount'       => $record['total_amount'],
            'disbursable_amount' => $record['disbursable_amount'],
            'chargesandinsurance' => $record['chargesandinsurance'],
            'loan_tenure'        => $record['loan_tenure'],
            'roi'                => $record['roi'],
            'emi'                => $record['emi'],
            'loan_due'           => $record['loan_due'],
            'pending_emi'        => $record['pending_emi'],
            'loan_type'          => $record['loan_type'],
            'loan_status'        => $record['loan_status'],
            'application_stage'  => $record['application_stage'],
            'otpVerify'          => $record['otpVerify'],
            'applicationID'      => $record['applicationID'],
            'employee_id'        => $record['employee_id'],
            'loan_created_at'    => $record['loan_created_at'],
            'loan_updated_at'    => $record['loan_updated_at'],

            // 📝 Field Inspection Feedback
            'fiInspector_name'   => $record['fiInspector_name'],
            'retailer_present'   => $record['retailer_present'],
            'retailer_behavior_professional' => $record['retailer_behavior_professional'],
            'retailer_aware_products'        => $record['retailer_aware_products'],
            'retailer_needs_training'        => $record['retailer_needs_training'],
            'shop_clean'         => $record['shop_clean'],
            'products_displayed' => $record['products_displayed'],
            'stock_available'    => $record['stock_available'],
            'promo_materials_visible' => $record['promo_materials_visible'],
            'location_accessible' => $record['location_accessible'],
            'payment_behavior'   => $record['payment_behavior'],
            'shop_ownership'     => $record['shop_ownership'],
            'house_ownership'    => $record['house_ownership'],
            'documents_verified' => $record['documents_verified'],
            'documents_received' => $record['documents_received'],
            'verified_fields'    => $record['verified_fields'],
            'inspector_comments' => $record['inspector_comments'],
            'fi_status'          => $record['fi_status'],
            'fi_final'           => $record['fi_final'],
            'inspector_daily_sales'   => $record['inspector_daily_sales'],
            'inspector_stock_value'  => $record['inspector_stock_value'],
            'inspector_month_purchase' => $record['inspector_month_purchase'],
            'latitude'           => $record['latitude'],
            'longitude'          => $record['longitude'],
            'place_name'         => $record['place_name'],
            'shop_photo'         => $record['shop_photo'],
            'selfie_with_owner'  => $record['selfie_with_owner'],
            'fi_created_at'      => $record['fi_created_at'],
        ];
        // Load HTML view into a string
        $html = view('sanction-letter', $data);

        // Initialize Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Output the PDF
        // $dompdf->stream("Member_{$member_id}_Details.pdf", ['Attachment' => true]);


        // Define the storage path
        $fileName = 'LoanPDF_' . $member_id . '.pdf';
        $outputPath = FCPATH . 'uploads/loan_doc/' . $member_id . '/' . $fileName; // inside writable/uploads/pdfs/
        $outputURL  = 'uploads/loan_doc/' . $member_id . '/' . $fileName; // inside writable/uploads/pdfs/
        // Create the directory if not exists
        if (!is_dir(dirname($outputPath))) {
            mkdir(dirname($outputPath), 0777, true);
        }
        date_default_timezone_set('Asia/Kolkata');
        $dataDoc = [
            'member_id' =>  $member_id,
            'doc_name' =>  $record['name'] . 'LoanDocuments',
            'path' =>  $outputURL,
            'created_at' =>  date('Y-m-d H:i:s'),
            'updated_on' =>  date('Y-m-d H:i:s'),
        ];
        $docModel = new RetailerLoanDocModel();
        $docModel->upsert($dataDoc);
        // Save to storage
        file_put_contents($outputPath, $dompdf->output());

        // Start Esign
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://kyc-api.surepass.app/api/v1/esign/set-branding',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                            "brand_image_url": "https://www.retailpe.in/assets/img/Logo/Retail%20Pe.webp",
                            "brand_name": "Retail Pe"
                        }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . getenv('SUREPASS_API_KEY_PROD'),
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);


        $response = curl_exec($curl);


        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {
            return redirect()->to(base_url() . 'preview-pdf/' . $member_id);
        } else {
            $dataApi = array(
                "pdf_pre_uploaded" => true,
                "callback_url" => "https://crm.retailpe.in/redirection",
                "config" => array(
                    "accept_selfie" => true,
                    "allow_selfie_upload" => true,
                    "accept_virtual_sign" => true,
                    "track_location" => true,
                    "auth_mode" => "1",
                    "reason" => "Loan Agreement",
                    "send_email" => false,
                    "positions" => array(
                        "1" => array(
                            array(
                                "x" => 10,
                                "y" => 20
                            )
                        ),
                        "2" => array(
                            array(
                                "x" => 10,
                                "y" => 20
                            )
                        ),
                        "3" => array(
                            array(
                                "x" => 10,
                                "y" => 20
                            )
                        ),
                        "4" => array(
                            array(
                                "x" => 10,
                                "y" => 20
                            )
                        ),
                        "5" => array(
                            array(
                                "x" => 10,
                                "y" => 20
                            )
                        ),
                        "6" => array(
                            array(
                                "x" => 10,
                                "y" => 20
                            )
                        ),
                        "7" => array(
                            array(
                                "x" => 10,
                                "y" => 20
                            )
                        ),
                        "8" => array(
                            array(
                                "x" => 10,
                                "y" => 20
                            )
                        ),
                        "9" => array(
                            array(
                                "x" => 10,
                                "y" => 20
                            )
                        ),
                        "10" => array(
                            array(
                                "x" => 10,
                                "y" => 20
                            )
                        ),
                        "11" => array(
                            array(
                                "x" => 10,
                                "y" => 20
                            )
                        ),
                        "12" => array(
                            array(
                                "x" => 10,
                                "y" => 20
                            )
                        ),
                        "13" => array(
                            array(
                                "x" => 10,
                                "y" => 20
                            )
                        ),
                        "14" => array(
                            array(
                                "x" => 10,
                                "y" => 20
                            )
                        ),
                        "15" => array(
                            array(
                                "x" => 10,
                                "y" => 20
                            )
                        ),
                        "16" => array(
                            array(
                                "x" => 10,
                                "y" => 20
                            )
                        ),
                        "17" => array(
                            array(
                                "x" => 10,
                                "y" => 20
                            )
                        ),
                        "18" => array(
                            array(
                                "x" => 10,
                                "y" => 20
                            )
                        ),
                        "19" => array(
                            array(
                                "x" => 10,
                                "y" => 20
                            )
                        ),
                        "20" => array(
                            array(
                                "x" => 10,
                                "y" => 20
                            )
                        ),
                        "21" => array(
                            array(
                                "x" => 10,
                                "y" => 20
                            )
                        )
                    )
                ),
                "prefill_options" => array(
                    "full_name" => $record['name'],
                    "mobile_number" =>  $record['mobile'],

                )
            );
            $data_json = json_encode($dataApi);
            log_message('info', 'eSign Generated ' . $data_json);

            // print_r($data_json);
            // INIT NSDL
            $curlIn = curl_init();

            curl_setopt_array($curlIn, array(
                CURLOPT_URL => 'https://kyc-api.surepass.app/api/v1/esign/initialize',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data_json,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . getenv('SUREPASS_API_KEY_PROD'),
                    'Content-Type: application/json'
                ),
            ));

            $responseIn = curl_exec($curlIn);
            $errIn = curl_error($curlIn);
            curl_close($curlIn);

            if ($errIn) {
                # code...
                return redirect()->to(base_url() . 'preview-pdf/' . $member_id);
            } else {
                # code...
                log_message('info', 'eSign Completed ' . $responseIn);
                $response_json = json_decode($responseIn, true); // decode as array

                $clientId = $response_json['data']['client_id'];
                $session = session(); // Load the session service

                $session->set('client_id', $clientId);
                $session->set('member_id', $member_id);
                $session->set('member_name', $record['name']);
                $session->set('applicationid', $record['applicationID']);
                $session->set('loan_amount', $record['loan_amount']);
                $session->set('roi', $record['roi']);
                $session->set('tenure', $record['loan_tenure']);


                // Session
                $dataPdf = array(
                    'client_id'              => $clientId,
                    'link'            => base_url() . "uploads/loan_doc/" . $member_id . "/" . $fileName,

                );
                $data_json_pdf = json_encode($dataPdf);
                $curlPdf = curl_init();
                curl_setopt_array($curlPdf, array(
                    CURLOPT_URL => 'https://kyc-api.surepass.app/api/v1/esign/upload-pdf',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => $data_json_pdf,
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Bearer ' . getenv('SUREPASS_API_KEY_PROD'),
                        'Content-Type: application/json'
                    ),
                ));

                $responsePdf = curl_exec($curlPdf);
                $errPdf = curl_error($curlPdf);
                curl_close($curlPdf);
                if ($errPdf) {
                    # code...
                    return redirect()->to(base_url() . 'preview-pdf/' . $member_id);
                } else {
                    log_message('info', 'eSign Uploaded ' . $responsePdf);
                    return redirect()->to($clientId = $response_json['data']['url']);
                }
            }
        }
    }
}

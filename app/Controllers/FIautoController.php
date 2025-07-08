<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;
use App\Models\LoanModel;

class FIautoController extends BaseController
{
    use ResponseTrait;
    public function auto_mail_fi_start()
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

        $documents = $builder
            ->where('member_id', $memberId)
            ->get()
            ->getResultArray();

        if (!empty($documents)) {
            // return $this->respond([
            //     'status' => true,
            //     'message' => 'Documents fetched successfully.',
            //     'data' => $documents
            // ], 200);
            // Note: now make a logic for auto mail of the data.
            // Build email message
            $message = '
                   <div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; border: 1px solid #ddd; padding: 20px;">
                        <h2 style="color: #007bff; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                            📢 Field Investigation Intimation – Member ID: ' . esc($memberId) . '
                        </h2>

                        <p style="font-size: 15px; margin-bottom: 10px;">
                            🔗 <strong>Start Field Investigation:</strong><br>
                            <a href="https://crm.retailpe.in/retailers/fi/' . urlencode($memberId) . '" target="_blank" style="color: #007bff; text-decoration: none;">
                                https://crm.retailpe.in/retailers/fi/' . urlencode($memberId) . '
                            </a>
                        </p>

                        <hr style="margin-top: 30px;">
                        <p style="font-size: 15px; color: #333;">
                            A Field Investigation has been initiated for the following member. Below are the documents uploaded by the retailer which are pending verification:
                        </p>

                        <table style="width:100%; border-collapse: collapse; font-size: 14px; margin-top: 10px;">
                            <thead>
                                <tr>
                                    <th style="text-align: left; padding: 8px; border-bottom: 2px solid #007bff;">#</th>
                                    <th style="text-align: left; padding: 8px; border-bottom: 2px solid #007bff;">Document Type</th>
                                    <th style="text-align: left; padding: 8px; border-bottom: 2px solid #007bff;">Preview / Link</th>
                                </tr>
                            </thead>
                            <tbody>';

            $i = 1;
            foreach ($documents as $doc) {
                $path = esc($doc['document_path']);
                $type = esc($doc['document_type']);
                $isImage = preg_match('/\.(jpg|jpeg|png|webp|gif)$/i', $path);

                $filePreview = $isImage
                    ? '<a href="' . $path . '" target="_blank"><img src="' . $path . '" alt="' . $type . '" style="max-height:50px; border:1px solid #ccc; padding:2px;"></a>'
                    : '<a href="' . $path . '" target="_blank" style="color:#007bff;">View File</a>';

                $message .= '
                            <tr>
                                <td style="padding: 8px; border-bottom: 1px solid #eee;">' . $i++ . '</td>
                                <td style="padding: 8px; border-bottom: 1px solid #eee;">' . $type . '</td>
                                <td style="padding: 8px; border-bottom: 1px solid #eee;">' . $filePreview . '</td>
                            </tr>';
            }

            $message .= '
                            </tbody>
                        </table>
              

                        <p style="font-size: 12px; color: #777;">
                            🔒 <strong>Confidentiality Notice:</strong><br>
                            This email and its contents are confidential and intended solely for the use of the individual or entity to whom they are addressed.
                            If you have received this email in error, please notify the sender and delete it from your system.
                        </p>
                    </div>';


            // Check member details from member table 

            $builderM = $db->table('members');
            $builderM->select('*');
            $builderM->where('member_id', $memberId);
            $queryM = $builderM->get();
            // $countEli = $builderB->countAllResults();
            foreach ($queryM->getResult() as $rowM) {
                // $eli = $rowB->eligibility;
                // $eligible_amount = $rowB->loan_amount;
                // $roi = $rowB->roi;
                // $emi = $rowB->emi;
                // $tenure = $rowB->tenure;
                $mobile = $rowM->mobile;
                $groupId = $rowM->groupId;
                $agent = $rowM->agent;
            }
            // Now insert the data into the ELI table

            $builderB = $db->table('initial_eli_run');
            $builderB->select('*');
            $builderB->where('member_id ', $memberId);
            $queryB = $builderB->get();
            // $countEli = $builderB->countAllResults();
            foreach ($queryB->getResult() as $rowB) {
                $eli = $rowB->eligibility;
                $eligible_amount = $rowB->loan_amount;
                $roi = $rowB->roi;
                $emi = $rowB->emi;
                $tenure = $rowB->tenure;
            }

            $model = new LoanModel();
            $data_loan = [

                'groupId'       => $groupId,
                'member_id'     => $memberId,
                'loan_amount'   => $eligible_amount,
                'loan_tenure'   => $tenure,
                'roi'           =>  $roi,
                'employee_id'   => $agent,
                'loan_status'     => "FI Initiated",
                'application_stage' => 'in_process',
                'applicationID' => date('ym') . str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT) . $mobile,

            ];
            // $query = $model->insert($data);
            $query = $model->save($data_loan);

            // Initialize and configure email
            $email = \Config\Services::email();
            $email->initialize([
                'protocol'   => 'smtp',
                'SMTPHost'   => 'smtp.hostinger.com',
                'SMTPUser'   => 'noreply@retailpe.in',
                'SMTPPass'   => 'Noreply@2025#', // Use Gmail App Password
                'SMTPPort'   => 587,
                'SMTPCrypto' => 'tls',
                'mailType'   => 'html',
                'charset'    => 'utf-8',
                'newline'    => "\r\n",
                'crlf'       => "\r\n"
            ]);

            // Set email params
            $email->setFrom('noreply@retailpe.in', 'RetailPe Field Investigation');
            $email->setTo('subhajit@retailpe.in'); // Change recipient as needed
            // $email->setCC('kousik@retailpe.in');
            $email->setSubject("FI Initiated – Member ID: $memberId | Retailer Documents Attached");
            $email->setMessage($message);

            // Send and respond
            if ($email->send()) {
                return $this->respond([
                    'status' => true,
                    'message' => 'Documents emailed successfully and FI Initiate',
                    'data' => $documents
                ], 200);
            } else {
                return $this->respond([
                    'status' => false,
                    'message' => 'Documents found but failed to send email.',
                    'debug' => $email->printDebugger(['headers'])
                ], 500);
            }
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'No documents found for this member.'
            ], 404);
        }
    }
}

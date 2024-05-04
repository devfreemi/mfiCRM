<?php


//http://stackoverflow.com/questions/18382740/cors-not-working-php
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    // header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}
// echo "You have CORS!";


// Data
$data = json_decode(file_get_contents("php://input"));
$job_id = $data->jobID;

if ($job_id != "") {
    $db = db_connect();
    $builder = $db->table('servicesDetails');
    $builder->where('uniqid', $job_id);
    $query = $builder->get();
    foreach ($query->getResult() as $row) {
        $status = $row->status;
        $payment_status = $row->paymentStatus;
        // Payment Status
        $builder_pay = $db->table('payment');
        $builder_pay->where('jobID', $job_id);
        $query_pay = $builder_pay->get();
        foreach ($query_pay->getResult() as $row_pay) {
            $orderId =  $row_pay->orderID;
            $receipt =  $row_pay->receipt;
            $amount =  $row_pay->amount;
            $email =  $row_pay->email;
            $mobile =  $row_pay->mobile;
            $name =  $row_pay->name;
            $date =  $row_pay->date;
            $time =  $row_pay->time;
        }
    }
    $response = array(
        "applicationStatus" => $status,
        "paymentStatus" => $payment_status,
        "orderID" => $orderId,
        "receipt" => $receipt,
        "amount" => $amount,
        "email" => $email,
        "mobile" => $mobile,
        "name" => $name,
        "AmountUI"      => number_format($amount),
        "dateTime" => $date . " " . $time,
        "status" => "Fetched",
    );
} else {
    $response = 'INTERNAL FAILLURE';
}
echo json_encode($response);

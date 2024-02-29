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
$order_id = $data->razorpay_order_res;
$payment_id = $data->razorpay_payment_res;
$signature = $data->razorpay_signature_id;
if ($order_id != "") {
    $db = db_connect();
    $builder = $db->table('payment');
    $data = [
        'payment_id'                   => $data->razorpay_payment_res,
        'payment_signature'            => $data->razorpay_signature_id,
        'paymentStatus'                 => 'Initiated'
    ];
    $builder->where('orderID', $order_id);
    $builder->update($data);
    $response = array(
        "payment_id" => $payment_id,
        "payment_signature" => $signature,
        "payment_order" => $order_id,
        "status" => "Payment Initiated"
    );
} else {
    $response = 'INTERNAL FAILLURE';
}
echo json_encode($response);

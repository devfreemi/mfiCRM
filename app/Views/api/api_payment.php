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
$customer_id = $data->customerIPL;

if ($customer_id != "") {
    $db = db_connect();
    $builder = $db->table('payment');
    $builder->where('customerID', $customer_id);
    $builder->orderBy('date', 'DESC');
    $builder->orderBy('time', 'DESC');


    $count = $builder->countAllResults();

    if ($count > 0) {
        $query = $builder->get();
        foreach ($query->getResult() as $row) {
            $product_id = $row->p_id;
            $builder_name = $db->table('product');
            $builder_name->where('id', $product_id);
            $query_name = $builder_name->get();
            foreach ($query_name->getResult() as $row_name) {
                $pName =  $row_name->productName;
            }
        }
        $response = array(
            "product"       => $pName,
            "applicationId"       => $row->jobID,
            "AmountUI"      => number_format($row->amount),
            "statusCode"    => 200,
            "status"        => $row->paymentStatus,
            "payment_id"        => $row->payment_id,

        );
    } else {
        $response = array(
            "statusCode"    => 201,
            "status"        => "No Payment Pending",

        );
    }
} else {
    $response = 'INTERNAL FAILLURE';
}
echo json_encode($response);

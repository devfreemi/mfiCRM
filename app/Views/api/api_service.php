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
$productID = $data->productID;
$customer_id = $data->customerID;
$uniqid = uniqid();
$customer_form16_p1 = $data->downloadURLP1;
$bank_statement = $data->downloadURLBrsU;
if ($data->productID != "") {
    $db = db_connect();
    $builder = $db->table('servicesDetails');

    $data = [
        'uniqid'            => $uniqid,
        'product_id'   => $data->productID,
        'customer_id'   => $data->customerID,
        'employment_type'          => $data->selectRadio,
        'customer_income'          => $data->income,
        'customer_pan'   => $data->pan,
        'customer_form16_p1' => $data->downloadURLP1,
        'bank_statement' => $data->downloadURLBrsU,
    ];
    $builder->where('customer_id', $customer_id);
    $builder->where('product_id', $productID);
    $query = $builder->get();
    $count = $builder->countAllResults();
    foreach ($query->getResult() as $row) {
        $ex_uniqID = $row->uniqid;
    }
    if ($count > 0) {
        $data = [
            'uniqid'            => $ex_uniqID,
            'customer_form16_p1' => $customer_form16_p1,
            'bank_statement' => $bank_statement,
        ];
        $builder->upsert($data);
        $response = array(
            "uniqid" => $ex_uniqID,
            "customer_id" => $customer_id,
            "product_id" => $productID,
            "status" => "Your Application Is Already Received"
        );
    } else {
        $builder->insert($data);
        $response = array(
            "uniqid" => $uniqid,
            "customer_id" => $customer_id,
            "product_id" => $productID,
            "status" => "Your Application Is Successfully Submited"
        );
    }
} else {
    $response = 'INTERNAL FAILLURE';
}
echo json_encode($response);

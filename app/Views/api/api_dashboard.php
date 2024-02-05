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
$customer_id = $data->customerID;

if ($customer_id != "") {
    $db = db_connect();
    $builder = $db->table('servicesDetails');
    $builder->where('customer_id', $customer_id);
    $query = $builder->get();
    $count = $builder->countAllResults();
    foreach ($query->getResult() as $row) {
        $product = $row->product_id;
    }
    $response = array(
        "product" => $count,
        "status" => "Fetched"
    );
} else {
    $response = 'INTERNAL FAILLURE';
}
echo json_encode($response);

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
$email = $data->email;

if ($email != "") {
    $db = db_connect();
    $builder = $db->table('customerDetails');
    $builder->where('email', $email);
    $count = $builder->countAllResults();
    if ($count > 0) {
        $builder->select('uniqid');
        $builder->where('email', $email);
        $query = $builder->get();
        foreach ($query->getResult() as $row) {
            $userId = $row->uniqid;
        }
        $response = array(
            "uniqid" => $userId,
            "status" => "Fetched"
        );
    } else {
        $response = array(
            "status" => "New User"
        );
    }
} else {
    $response = 'INTERNAL FAILLURE';
}
echo json_encode($response);

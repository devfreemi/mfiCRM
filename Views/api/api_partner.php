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
$pan = $data->pan;
$email = $data->email;
$name = $data->customerName;
$agentID = 'COMP-0' . rand(1, 100);
if ($pan != "") {
    $db = db_connect();
    $builder = $db->table('agentProfile');

    $builder->where('pan', $pan);
    $count = $builder->countAllResults();

    $data = [
        'agentID'            => $agentID,
        'loginID'            => $data->customerID,
        'name'          => $data->customerName,
        'email'          => $data->email,
        'pan'   => $data->pan,
        'mobile'      => $data->mobile,
    ];
    if ($count > 0) {
        $query = $builder->get();
        foreach ($query->getResult() as $row) {
            $ex_agentID = $row->agentID;
        }
        $response = array(
            "agentID" => $ex_agentID,
            "status" => "Already Registered",
            "statusCode" => 301
        );
    } else {
        $builder->insert($data);
        $response = array(
            "agentID" => $agentID,
            "name" => $name,
            "status" => "Registered",
            "statusCode" => 200
        );
    }
} else {
    $response = 'Internal Error';
}
echo json_encode($response);

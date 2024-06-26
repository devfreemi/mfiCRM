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
$uniqid = $data->uniqid;
$email = $data->email;
$mobile = $data->mobile;
$mobile = preg_replace('/\s+/', '', $mobile);

if ($uniqid != "") {
    $db = db_connect();
    $builder = $db->table('customerDetails');

    $data = [
        'uniqid'            => $data->uniqid,
        'familyName'   => $data->familyName,
        'givenName'   => $data->givenName,
        'name'          => $data->name,
        'mod_name'          => $data->name,
        'photo'          => $data->photo,
        'loginWith'   => $data->loginWith,
        'email'      => $email,
        'uniqidMobile'            => $data->uniqidMobile,
        'mobile'            => $mobile,
    ];
    $builder->where('uniqid', $uniqid);
    $count = $builder->countAllResults();
    if ($count > 0) {
        $response[] = array(
            "uniqid" => $uniqid,
            "status" => "Already Registered"
        );
    } else {
        $builder->insert($data);
        $response[] = array(
            "uniqid" => $uniqid,
            "status" => "New User Registered"
        );
    }
} else {
    $response = 'SIGN IN FAILLED';
}
echo json_encode($response);

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
$mobile = $data->mobile;
$uniqid = uniqid();
$customerName = $data->customerName;
// Retrive product price
$db = db_connect();
$builderPrice = $db->table('price');
$builderPrice->where('pId', $productID);
$queryPrice = $builderPrice->get();
foreach ($queryPrice->getResult() as $rowPrice) {
    $product_gst = $rowPrice->gst;
    $product_price = $rowPrice->price;
    $product_price = round($product_price + ($product_price * ($product_gst / 100)));
    $product_msg = $rowPrice->comments;
}

if ($data->productID != "") {
    $builder = $db->table('servicesDetails');


    $builder->where('customer_id', $customer_id);
    $builder->where('product_id', $productID);

    $count = $builder->countAllResults();

    if ($count > 0) {
        $query = $builder->get();
        foreach ($query->getResult() as $row) {
            $ex_uniqID = $row->uniqid;
            $statusPayment = $row->paymentStatus;
        }
        $response = array(
            "uniqid" => $ex_uniqID,
            "customer_id" => $customer_id,
            "product_id" => $productID,
            "statusPayment" => $statusPayment, //ADD FOR PAYMENT
            "status" => "Your Application Is Already Received"
        );
    } else {

        # code...
        // // PAyment
        date_default_timezone_set('Asia/Kolkata');
        $builderPayment = $db->table('payment');
        // PAYMENT INTEGRATION
        $arrayOrder = array(
            'receipt' => 'INV/' . substr($customer_id, -4) . '/' . $productID,
            'amount' => $product_price * 100,
            'currency' => 'INR',
            'notes' => array(
                'customerReference' => $customer_id,
                'CustomerMobile' => $mobile
            )

        );
        $data_string_order_api = json_encode($arrayOrder); //LOGIN IN FREEMI

        $curlO = curl_init();
        $loginOrderURL = "https://api.razorpay.com/v1/orders";
        $header_js_Order = array('Accept: application/json', 'Content-Type: application/json');
        // print_r($header_js_Order);
        //set cURL options
        curl_setopt($curlO, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curlO, CURLOPT_URL, $loginOrderURL);
        curl_setopt($curlO, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlO, CURLOPT_POST, 1);
        curl_setopt($curlO, CURLOPT_POSTFIELDS, $data_string_order_api);
        curl_setopt($curlO, CURLOPT_USERPWD, "rzp_live_bnIERNe35ujSDt:UAlkDT92HfqVRIiIADQA0d7d");
        curl_setopt($curlO, CURLOPT_HTTPHEADER, $header_js_Order);

        //Execute cURL
        $curl_response_order = curl_exec($curlO);
        $httpCode = curl_getinfo($curlO, CURLINFO_HTTP_CODE);
        if ($httpCode == 200) {
            $orderResponseDecode = json_decode($curl_response_order, true);
            $paymentResponse = $orderResponseDecode['status'];
            $dataPayment = [
                'jobID'             => $uniqid,
                'p_id'             => $productID,
                'orderID'           => $orderResponseDecode['id'],
                'customerID'        => $customer_id,
                'paymentStatus'     =>  $orderResponseDecode['status'],
                'amount'            => $orderResponseDecode['amount_due'] / 100,
                'amountPay'            => $orderResponseDecode['amount_due'],
                'receipt'           => $orderResponseDecode['receipt'],
                'name'           => $customerName,
                'mobile'           => $mobile,
                'date'              => date('Y-m-d'),
                'time'              => date('h:i:s'),
            ];
            $builderPayment->upsert($dataPayment);
            $response = array(
                "uniqid" => $uniqid,
                "customer_id" => $customer_id,
                "product_id" => $productID,
                "OrderId"       => $orderResponseDecode['id'],
                "Receipt"       => $orderResponseDecode['receipt'],
                "Amount"        => $orderResponseDecode['amount_due'],
                "Name"        => $customerName,
                "Mobile"        => substr($mobile, 3),
                "Email"        => "null",
                "AmountUI"      => number_format($orderResponseDecode['amount_due'] / 100),
                "statusCode"    => 200,
                "statusPayment"        => $orderResponseDecode['status'],
                "ProductPriceCommnets" => $product_msg,
                "status" => "Your Application Is Successfully Submited"
            );
        } else {
            $orderResponseDecode = json_decode($curl_response_order, true);
            $paymentResponse = $orderResponseDecode['error']['code'];
            $dataPayment = [
                'jobID'             => $uniqid,
                'p_id'             => $productID,
                'customerID'        => $customer_id,
                'paymentStatus'     =>  $orderResponseDecode['error']['code'],
                'name'           => $customerName,
                'mobile'           => $mobile,
                'date'              => date('Y-m-d'),
                'time'              => date('h:i:s'),
            ];
            $builderPayment->upsert($dataPayment);
            $response = array(
                "uniqid"        => $uniqid,
                "customer_id"   => $customer_id,
                "product_id"    => $productID,
                "statusPayment" => "Payment Failed",
                "status"        => "Your Application Is Successfully Submited"
            );
        }
        curl_close($curlO);
        // // END PAYMENT

        $data = [
            'uniqid'            => $uniqid,
            'product_id'   => $data->productID,
            'customer_id'   => $data->customerID,
            'contact_mail'          => $data->email,
            'contact_mobile'   => $data->mobile,
            'name'   => $data->customerName,
            'date'   => date('Y-m-d'),
            'paymentStatus'   => $paymentResponse,
        ];
        $builder->insert($data);
    }
} else {
    $response = 'INTERNAL FAILLURE';
}
echo json_encode($response);

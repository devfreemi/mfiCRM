<?php
/*
   * Template Name: Member Redirect
   */

get_header();
?>
<script type="text/javascript">
    window.history.forward();

    function noBack() {
        window.history.forward();
    }
</script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="preload" as="style" onload="this.rel='stylesheet'">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.1.0/mdb.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/membership.css">
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.1.0/mdb.min.js"></script> -->
<style>
    .amt-head {
        color: #20509e !important;
        font-size: 18px;
        font-weight: 700;
    }

    .amt-amnt {
        color: #df9e14 !important;
        font-size: 18px;
        font-weight: 700;
    }

    input:focus {
        border: 1px solid #000000 !important;
    }

    .details-name {
        font-size: 14px !important;
        color: #dc9800 !important;
        font-weight: 500 !important;
    }

    .details-param {
        font-size: 13px !important;
        color: #4d4d4d !important;
        font-weight: 500 !important;
    }
</style>

<!-- <div class="mt-4 mt-md-3 pt-3 pt-md-5 pb-2 bg-white px-3"><?php // get_breadcrumb(); 
                                                                ?></div> -->
<div id="primary" class="content-area mt-4 mt-md-3 pt-3 pt-md-5">
    <main id="main" class="site-main">
        <?php

        $data = $_SESSION['dataMemBer'];
        // print_r($data);
        $data_decode = json_decode($data, true);
        $httpCode = $data_decode['statusCode'];
        $customer_Id = $data_decode['Customer_Id'];
        $email  = $data_decode['Email'];
        $Mobile   = $data_decode['Mobile'];
        $Customer_Name   = $data_decode['Customer_Name'];
        $pan1   = $data_decode['pan1'];
        ?>
        <?php
        $dataOrder = $_SESSION['dataOrder'];
        // print_r($dataOrder);
        $data_decode_order = json_decode($dataOrder, true);
        $order_id =  $data_decode_order['id'];
        $amount = $data_decode_order['amount'];
        $customerReference = $data_decode['customerReference'];
        $CustomerMobile   = $data_decode['CustomerMobile'];
        $currency   = $data_decode['currency'];
        $receipt   = $data_decode['receipt'];
        ?>


        <div class=" mx-auto col-12 col-md-4">
            <div class="card">
                <h5 class="card-header p-4 text-center bg-light">Pay Membership Fees</h5>
                <div class="card-body">

                    <?php if ($httpCode == "200") {       ?>

                        <div class="col-12 col-md-8 mx-auto mb-3">
                            <div class="row mx-auto">
                                <p class="col-7 amt-head">Membership Fees</p>
                                <p class="col-5 amt-amnt">Rs. 499.00</p>
                            </div>
                        </div>

                        <div class="row mx-auto">
                            <div class="form-group col-12 ">
                                <label for="CreditCardNumber">Invoice number</label>
                                <input id="CreditCardNumber" class="form-control" value="<?php echo $customer_Id; ?>" type="text" readonly></input>
                            </div>
                            <div class="form-group col-12 ">
                                <label for="ExpiryDate">Invoice date</label>
                                <input id="ExpiryDate" class="form-control" type="date" value="<?php echo date("Y-m-d"); ?>" readonly></input>
                            </div>

                        </div>
                        <div class="row mx-auto">
                            <div class="form-group col-12 ">
                                <label or="NameOnCard">Invoice Name</label>
                                <input id="NameOnCard" class="form-control" value="<?php echo $Customer_Name; ?>" type="text" maxlength="255" readonly></input>
                            </div>
                            <div class="form-group col-12 ">
                                <label for="ExpiryDate">PAN Number</label>
                                <input id="ExpiryDate" class="form-control" value="<?php echo $pan1; ?>" type="text" readonly></input>
                            </div>
                        </div>
                        <div class="row mx-auto">
                            <div class="form-group col-12 ">
                                <label for="ExpiryDate">Mobile Number</label>
                                <input id="ExpiryDate" class="form-control" value="<?php echo $Mobile; ?>" type="text" readonly></input>
                            </div>
                            <div class="form-group col-12 ">
                                <label for="ExpiryDate">Email Id</label>
                                <input id="ExpiryDate" class="form-control" value="<?php echo $email; ?>" type="text" readonly></input>
                            </div>

                        </div>

                        <button id="rzp-button1" class="col-6 mx-auto btn btn-block btn-success submit-button" type="submit">
                            <span class="submit-button-lock"></span>
                            <span class="align-middle">Pay ₹ 499.00</span>
                        </button>

                    <?php } ?>
                    <?php if ($httpCode == "101") {        ?>
                        <div class="row mx-auto">
                            <div class="col-md-12 col-12 mx-auto">
                                <div class="row">
                                    <div class="col-12 pt-0 pb-4 px-4">
                                        <p class="text-center f-14 text-thank">
                                            Thank you for using our service!
                                        </p>
                                    </div>
                                    <div class="col-md-12 mx-auto col-12 text-center ">
                                        <img src="http://localhost/freemi/webapp/resources/images/success.gif" class="img-fluid" alt="">
                                    </div>
                                    <div class="col-12 pt-0 pb-4 px-4">
                                        <p class="text-center fw-bold f-14 text-success">
                                            Your payment is completed!
                                        </p>
                                    </div>
                                    <div class="col-md-12 mt-5 px-0">
                                        <div class="row py-1 px-md-4 px-0">
                                            <p class="details-name col-6 col-md-6 px-0 mb-0">Customer Id</p>
                                            <p class="details-param col-6 col-md-6 border-0 px-0 text-right mb-0"><?php echo $customer_Id; ?></p>
                                        </div>
                                        <div class="row py-1  px-md-4 px-0">
                                            <p class="details-name col-4 col-md-4 px-0 mb-0">Payment Status</p>
                                            <p class="details-param col-8 col-md-8 border-0 px-0 text-right mb-0">Success</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                </div>
            </div>
        </div>






        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <script>
            var options = {
                "key": "rzp_test_xDskoVbdRxNOez", // Enter the Key ID generated from the Dashboard
                "amount": "<?php echo $amount; ?>", // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
                "currency": "INR",
                "name": "FreEMI", //your business name
                "description": "Membership Fees",
                "image": "https://resources.freemi.in/loans/resources/images/freemi.png",
                "order_id": "<?php echo $order_id; ?>", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
                "handler": function(response) {
                    // alert(response.razorpay_payment_id);
                    // alert(response.razorpay_order_id);
                    // alert(response.razorpay_signature)
                    if (typeof response.razorpay_payment_id == 'undefined' || response.razorpay_payment_id < 1) {
                        // redirect_url = 'http://localhost/wordpress/payment-status?payment_status=N&payment_id=' + response.razorpay_payment_id;
                        redirect_url = 'https://www.freemi.in/membership-payment-status?payment_status=N&payment_id=' + response.razorpay_payment_id;
                    } else {
                        // redirect_url = 'http://localhost/wordpress/payment-status?payment_status=Y&payment_id=' + response.razorpay_payment_id;
                        redirect_url = 'https://www.freemi.in/membership-payment-status?payment_status=Y&payment_id=' + response.razorpay_payment_id;
                        console.log(response.razorpay_payment_id);

                    }
                    location.href = redirect_url;
                },
                "prefill": {
                    "name": "<?php echo $Customer_Name; ?>", //your customer's name
                    "email": "<?php echo $email; ?>",
                    "contact": "<?php echo $Mobile; ?>"
                },
                "notes": {
                    "prospect": "FreEMI Premium Membership"
                },
                "theme": {
                    "color": "#3399cc"
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.on('payment.failed', function(response) {
                alert(response.error.code);
                alert(response.error.description);
                alert(response.error.source);
                alert(response.error.step);
                alert(response.error.reason);
                alert(response.error.metadata.order_id);
                alert(response.error.metadata.payment_id);
            });
            document.getElementById('rzp-button1').onclick = function(e) {
                rzp1.open();
                e.preventDefault();
            }
        </script>
    </main>

</div>
<?php
get_footer();

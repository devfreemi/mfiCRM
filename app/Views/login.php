<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>16 ana Digital | Digital Seva</title>
    <meta name="description" content="The small framework with powerful features">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">
    <link rel="icon" type="image/x-icon" href="">
</head>

<body>

    <!-- HEADER: MENU + HEROE SECTION -->


    <!-- CONTENT -->
    <?php
    $uri = service('uri');
    //
    $url_panel = $uri->getSegment(1);
    $session = session();


    if ($session->get("userId") != '') {
        if ($url_panel == 'dashboard') {
            // code...
            include 'dashboard.php';
        } else if ($url_panel == 'customer') {
            include 'customer.php';  //Update customer Profile
        }

    ?>

    <?php } else {
    ?>
        <link href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" rel="stylesheet">
        <link href="https://resources.freemi.in/mdbtheme/4.8.10/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://resources.freemi.in/mdbtheme/4.8.10/css/mdb.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">

        <script type="text/javascript" src="https://resources.freemi.in/mdbtheme/4.8.10/js/jquery-3.4.1.min.js"></script>
        <script type="text/javascript" src="https://resources.freemi.in/mdbtheme/4.8.10/js/popper.min.js" async="true"></script>
        <script type="text/javascript" src="https://resources.freemi.in/mdbtheme/4.8.10/js/bootstrap.min.js" async="true"></script>
        <script type="text/javascript" src="https://resources.freemi.in/mdbtheme/4.8.10/js/mdb.min.js" async="true"></script>
        <!-- STYLES -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/login.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/material-icon/css/material-design-iconic-font.min.css">

        <section class="sign-in my-5">
            <div class="container">
                <h2 class="form-title">Sign in</h2>
                <?php if (session()->getFlashdata('loginmsg')) : ?>
                    <p class="text-center text-danger"><?= session()->getFlashdata('loginmsg') ?></p>
                <?php endif;
                echo $session->get("userId");
                ?>

                <div class="signin-content">
                    <div class="signin-image d-md-block d-none">
                        <figure><img src="<?php echo base_url(); ?>/assets/images/signup-image.jpg" alt="sing up image"></figure>
                    </div>

                    <div class="signin-form">
                        <form method="POST" class="register-form" id="login-form" action="<?php echo base_url(); ?>tax/backoffice-user-login">
                            <div class="form-group">
                                <label for="your_id"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                <input type="text" maxlength="10" name="userid" required id="userid" placeholder="Your User ID" autocomplete="off" />
                            </div>
                            <div class="form-group">
                                <label for="your_pass"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="pass" id="pass" placeholder="Password" required />
                            </div>
                            <div class="form-group form-button">
                                <input type="submit" name="signin" id="signin" class="w-auto btn btn-withdr btn-hover rounded" value="Log in" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- -->
    <?php  }
    ?>
</body>

</html>
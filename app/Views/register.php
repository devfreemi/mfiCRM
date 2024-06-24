<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>MFI CRM | TrueTechnologies
    </title>
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
        } else if ($url_panel == 'customer-list') {
            include 'customer_list.php';  //Update customer Profile
        }

    ?>

    <?php } else {
    ?>
        <link href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

        <!-- STYLES -->
        <link rel="stylesheet" href="assets/css/app.css" />
        <link rel="stylesheet" href="assets/css/material-icon/css/material-design-iconic-font.min.css">

        <main class="d-flex w-100">
            <div class="container d-flex flex-column">
                <div class="row vh-100">
                    <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
                        <div class="d-table-cell align-middle">

                            <div class="text-center mt-4">
                                <h1 class="h2">USER REGISTER</h1>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="m-sm-3">
                                        <form action="<?= base_url() ?>backoffice-user-signup" method="post">
                                            <?= csrf_field('auth') ?>
                                            <div class="mb-3">
                                                <label class="form-label">Full name</label>
                                                <input class="form-control form-control-lg" type="text" name="name" placeholder="Enter your name" autocomplete="on" value="<?= set_value('name'); ?>" />

                                                <mute class="text-danger"><?php
                                                                            if (isset($validation)) {
                                                                                # code...
                                                                                if ($validation->hasError("name")) {
                                                                                    # code...
                                                                                    echo $validation->getError("name");
                                                                                }
                                                                            } ?></mute>


                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">UserId</label>
                                                <input class="form-control form-control-lg" type="text" name="userId" placeholder="Enter your user Id" autocomplete="off" value="<?= set_value('userId'); ?>" />
                                                <mute class="text-danger"><?php
                                                                            if (isset($validation)) {
                                                                                # code...
                                                                                if ($validation->hasError("userId")) {
                                                                                    # code...
                                                                                    echo $validation->getError("userId");
                                                                                }
                                                                            } ?></mute>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Password</label>
                                                <input class="form-control form-control-lg" maxlength="8" max="8" type="password" name="password" placeholder="Enter password" autocomplete="off" />
                                                <mute class="text-danger"><?php
                                                                            if (isset($validation)) {
                                                                                # code...
                                                                                if ($validation->hasError("password")) {
                                                                                    # code...
                                                                                    echo $validation->getError("password");
                                                                                }
                                                                            } ?></mute>
                                            </div>
                                            <div class="d-grid gap-2 mt-3">
                                                <input type="submit" class="btn btn-lg btn-primary" value="Register">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- -->
    <?php  }
    ?>

</body>
<script src="assets/js/app.js"></script>

</html>
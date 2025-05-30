<?php include 'fragments/head.php'; ?>
<?php include 'fragments/sidebar.php'; ?>


<link href="https://cdn.datatables.net/2.1.0/css/dataTables.bootstrap5.css" rel="stylesheet">
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.0/js/dataTables.bootstrap5.js"></script>

<div class="main">

    <?php include 'fragments/nav.php'; ?>
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-xl-12 col-xxl-12 d-flex">
                    <div class="w-100">
                        <div class="position-relative mb-2">
                            <img src="<?= esc($retailers['image']) ?>" class="img-fluid w-100 rounded bg-shop" alt="Shop Image">

                            <!-- Profile Picture Overlay -->
                            <div class=" bottom-0 start-0 translate-middle-y ms-4 mb-2">
                                <img src="https://avatar.iran.liara.run/username?username=<?= esc($retailers['name']) ?>" class="rounded-circle border border-white border-3 shadow" alt="Profile" style="width: 120px; height: 120px;">
                            </div>
                        </div>
                        <h2 class="mb-4">Field Inspection Feedback Form</h2>


                    </div>
                </div>


            </div>





        </div>
    </main>

    <?php include 'fragments/footer.php'; ?>
</div>
</div>
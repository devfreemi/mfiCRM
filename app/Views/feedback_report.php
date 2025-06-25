<?php include 'fragments/head.php'; ?>
<?php include 'fragments/sidebar.php'; ?>


<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<style>
    table.dataTable thead th {
        background-color: #f8f9fa;
    }
</style>
<div class="main">

    <?php include 'fragments/nav.php'; ?>
    <main class="content px-3">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-xl-12 col-xxl-12 d-flex">
                    <div class="w-100 card p-4">
                        <h2 class="mb-4">📝 Field Inspection Feedback Report</h2>
                        <?php if (session()->getFlashdata('msg')) : ?>
                            <div class="col-xl-12 col-xxl-12 d-flex flex-row-reverse my-5">
                                <p class="text-center text-danger"><?= session()->getFlashdata('msg') ?></p>
                            </div>
                        <?php endif; ?>
                        <div class="table-responsive">
                            <table id="feedbackTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th>Member ID</th>
                                        <th>Inspector</th>
                                        <th>Inspector Comments</th>
                                        <th>Retailer Present</th>
                                        <th>Professional</th>
                                        <th>Aware</th>
                                        <th>Original Document Verified</th>
                                        <th>Shop Clean</th>
                                        <th>Product Displayed</th>
                                        <th>Stock available</th>
                                        <th>Promo</th>
                                        <th>Location Accessible</th>
                                        <th>Payment</th>
                                        <th>Shop Ownership</th>
                                        <th>House Ownership</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($feedbacks as $f): ?>
                                        <tr>
                                            <td><?= esc($f['member_id']) ?></td>
                                            <td><?= esc($f['fiInspector_name']) ?></td>
                                            <td><?= esc($f['inspector_comments']) ?></td>
                                            <td><?= esc($f['retailer_present']) ?></td>
                                            <td><?= esc($f['retailer_behavior_professional']) ?></td>
                                            <td><?= esc($f['retailer_aware_products']) ?></td>
                                            <td><?= esc($f['documents_verified']) ?></td>
                                            <td><?= esc($f['shop_clean']) ?></td>
                                            <td><?= esc($f['products_displayed']) ?></td>
                                            <td><?= esc($f['stock_available']) ?></td>
                                            <td><?= esc($f['promo_materials_visible']) ?></td>
                                            <td><?= esc($f['location_accessible']) ?></td>
                                            <td><?= esc($f['payment_behavior']) ?></td>
                                            <td><?= esc($f['shop_ownership']) ?></td>
                                            <td><?= esc($f['house_ownership']) ?></td>
                                            <td><?= date('d M Y, h:i A', strtotime($f['created_at'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


            </div>





        </div>
    </main>
    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#feedbackTable').DataTable({
                responsive: true,
                pageLength: 10,
                language: {
                    search: "🔍 Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ records",
                }
            });
        });
    </script>
    <?php include 'fragments/footer.php'; ?>
</div>
</div>
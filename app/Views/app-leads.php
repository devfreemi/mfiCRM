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
    <main class="content">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3">List of <strong>Retailers</strong></h1>
            <div class="row mx-auto">
                <div class="col-7"></div>
                <div class="col-5 text-end">
                    <!-- <a class="col-md-4 btn btn-success">Add Retailer</a> -->
                    <a href="<?php echo base_url() ?>member-add" class="col-md-4 btn btn-success "> Add Retailer
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
            </div>
            <div class="row">
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="col-xl-12 col-xxl-12 my-5">
                        <p class="text-center text-success fw-bold"><?= session()->getFlashdata('success') ?></p>
                    </div>
                <?php endif; ?>

                <div class="col-xl-12 col-xxl-12 d-flex">

                    <div class="w-100 table-responsive">
                        <table id="feedbackTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Members Id</th>
                                    <th>V1 Checking</th>
                                    <th>V1 Status</th>
                                    <th>V2 Checking</th>
                                    <th>V2 Status</th>
                                    <th>CRE Score V1</th>
                                    <th>CRE Score V2</th>
                                    <th>Credit Score</th>
                                    <th>Pre Approved Amount</th>
                                    <th>Final Approval Amount</th>
                                    <th>Business Name</th>
                                    <th>Business Type</th>
                                    <th>Owner Name</th>
                                    <th>Market Name</th>
                                    <!-- <th>Market Type</th> -->
                                    <th>Agent Name</th>
                                    <th>Mobile</th>
                                    <th>PAN Number</th>
                                    <th>GST Number</th>

                                    <th>Location</th>
                                    <th>Pincode</th>
                                    <th>Daily Sales</th>
                                    <th>Current Stock</th>
                                    <th>Current EMI</th>
                                    <th>Established Year</th>
                                    <th>Images</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $db = db_connect();
                                // $builder = $db->table('members')->select('*, members.name as owner,members.mobile as r_mobile,members.created_at as r_created_at')
                                //     ->join('employees', 'employees.employeeID = members.agent')->join('groups', 'groups.g_id  = members.groupId');
                                $builder = $db->table('members')->select('*, members.name as owner,members.mobile as r_mobile,members.created_at as r_created_at')
                                    ->where('members.groupId', 002)
                                    ->orWhere('members.agent IS NULL');
                                $query = $builder->get();

                                foreach ($query->getResult() as $row) {
                                ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $row->member_id; ?></td>
                                        <?php
                                        $eli = 'Not Run';

                                        if ($row->eli_run === "W" || $row->eli_run === "Y") {
                                            $builderB = $db->table('initial_eli_run');
                                            $builderB->select('*');
                                            $builderB->where('member_id ', $row->member_id);
                                            $queryB = $builderB->get();
                                            // $countEli = $builderB->countAllResults();
                                            foreach ($queryB->getResult() as $rowB) {
                                                $eli = $rowB->eligibility;
                                            }
                                        ?>

                                            <td class="text-success fw-bold">Checked</td>
                                            <td>
                                                <?= $eli == 'Eligible' ? '<span class="text-success fw-bold">' . $eli . '</span>' : '<span class="text-danger fw-bold">' . $eli . '</span>' ?>
                                            </td>
                                        <?php
                                        } else {
                                        ?>
                                            <td class="text-warning">Not Checked</td>
                                            <td class="text-warning text-center">⚠️</td>
                                        <?php  } ?>
                                        <?php
                                        $eliV2 = Null;

                                        if ($row->eli_run === "Y") {
                                            $builderB2 = $db->table('initial_eli_run');
                                            $builderB2->select('*');
                                            $builderB2->where('member_id ', $row->member_id);
                                            $queryB2 = $builderB2->get();
                                            // $countEli = $builderB->countAllResults();
                                            foreach ($queryB2->getResult() as $rowB2) {
                                                $eliV1 = $rowB2->eligibility;
                                                $eliV2 = $rowB2->eligibilityV2;
                                            }
                                        ?>

                                            <td>
                                                <?php if ($eliV2 === null): ?>
                                                    <span class="text-warning text-center">Not Checked</span>
                                                <?php else: ?>
                                                    <span class="text-success fw-bold">Checked</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($eliV2 === null): ?>
                                                    <span class="text-warning">⚠️</span>
                                                <?php elseif ($eliV2 === 'Eligible'): ?>
                                                    <span class="text-success fw-bold"><?= $eliV2 ?></span>
                                                <?php else: ?>
                                                    <span class="text-danger fw-bold"><?= $eliV2 ?></span>
                                                <?php endif; ?>
                                            </td>
                                        <?php
                                        } else {
                                        ?>
                                            <td class="text-warning">Not Checked</td>
                                            <td class="text-warning text-center">⚠️</td>
                                        <?php  } ?>
                                        <?php
                                        $scoreV1 = 0;
                                        $scoreV2 = 0;
                                        $cibil = 0;
                                        $loan_amount = 0;
                                        $loan_amountV2 = 0;
                                        $builderS = $db->table('initial_eli_run');
                                        $builderS->select('*');
                                        $builderS->where('member_id ', $row->member_id);
                                        $queryS = $builderS->get();
                                        // $countEli = $builderB->countAllResults();
                                        foreach ($queryS->getResult() as $rowS) {
                                            $scoreV1 = $rowS->score;
                                            $scoreV2 = $rowS->scoreV2;
                                            $cibil = $rowS->cibil;
                                            $loan_amount = $rowS->loan_amount;
                                            $loan_amountV2 = $rowS->loan_amountV2;
                                        }
                                        ?>
                                        <td><?php echo $scoreV1; ?></td>
                                        <td><?php echo $scoreV2; ?></td>
                                        <td><?php echo $cibil; ?></td>
                                        <td><?php echo $loan_amount; ?></td>
                                        <td><?php echo $loan_amountV2; ?></td>
                                        <td><?php echo $row->businessName; ?></td>
                                        <td><?php echo $row->businessType; ?></td>
                                        <td><?php echo $row->owner ?? 'NULL'; ?></td>
                                        <td><?php echo $row->groupName ?? 'NULL'; ?></td>
                                        <td><?php echo $row->name ?? 'NULL'; ?></td>
                                        <td><?php echo $row->r_mobile ?? 'NULL'; ?></td>
                                        <td><?php echo $row->pan ?? 'NULL'; ?></td>
                                        <td><?php echo $row->gst ?? 'NULL'; ?></td>
                                        <td><?php echo $row->location ?? 'NULL'; ?></td>
                                        <td><?php echo $row->pincode ?? 'NULL'; ?></td>
                                        <td><?php echo $row->dailySales ?? 'NULL'; ?></td>
                                        <td><?php echo $row->stock ?? 'NULL'; ?></td>
                                        <td><?php echo $row->outstanding ?? 'NULL'; ?></td>
                                        <td><?php echo $row->estab ?? 'NULL'; ?></td>

                                        <td>
                                            <a href="<?php echo $row->image; ?>" target="_blank" rel="noopener noreferrer">
                                                <i class="far fa-eye"></i>
                                            </a>

                                        </td>
                                        <td><?php echo $row->r_created_at ?? 'NULL'; ?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-primary  view" id="<?php echo $row->member_id; ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="<?php echo base_url() . 'retailers/details/' . $row->member_id;
                                                            ?>" class="btn btn-danger details" id="" target="_blank">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Id</th>
                                    <th>Members Id</th>
                                    <th>V1 Checking</th>
                                    <th>V1 Status</th>
                                    <th>V2 Checking</th>
                                    <th>V2 Status</th>
                                    <th>CRE Score V1</th>
                                    <th>CRE Score V2</th>
                                    <th>Credit Score</th>
                                    <th>Pre Approved Amount</th>
                                    <th>Final Approval Amount</th>
                                    <th>Business Name</th>
                                    <th>Business Type</th>
                                    <th>Owner Name</th>
                                    <th>Market Name</th>
                                    <!-- <th>Market Type</th> -->
                                    <th>Agent Name</th>
                                    <th>Mobile</th>
                                    <th>PAN Number</th>
                                    <th>GST Number</th>

                                    <th>Location</th>
                                    <th>Pincode</th>
                                    <th>Daily Sales</th>
                                    <th>Current Stock</th>
                                    <th>Current EMI</th>
                                    <th>Established Year</th>
                                    <th>Images</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="modal fade" id="dataModal">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header bg-grad">
                                <h5 class="modal-title text-white fw-bold" id="exampleModalLabel">Member Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="member_detail">

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'fragments/footer.php'; ?>
</div>
</div>

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
            pageLength: 25,
            language: {
                search: "🔍 Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ records",
            }
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#feedbackTable").on("click", ".view", function() {
            var member_id = $(this).attr("id");

            $.ajax({
                url: "<?php echo base_url(); ?>member-edit",
                method: "POST",
                data: {
                    member_id: member_id
                },
                // beforeSend: function() {
                //     $('#dataModal').modal("show");
                // },
                success: function(data) {
                    $('#member_detail').html(data);
                    $('#dataModal').modal("show");

                    console.log(member_id);
                    return false;
                }
            });

        });
    });
</script>
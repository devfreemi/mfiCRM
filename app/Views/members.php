<?php include 'fragments/head.php'; ?>
<?php include 'fragments/sidebar.php'; ?>
<link href="https://cdn.datatables.net/2.1.0/css/dataTables.bootstrap5.css" rel="stylesheet">
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.0/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.html5.min.js"></script>



<div class="main">

    <?php include 'fragments/nav.php'; ?>
    <main class="content">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3">List of <strong>Retailers</strong></h1>

            <div class="row">

                <div class="col-xl-12 col-xxl-12 d-flex">
                    <div class="w-100 table-responsive">
                        <table id="branch" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Members Id</th>
                                    <th>Eligibility</th>
                                    <th>Market Name</th>
                                    <th>Market Type</th>
                                    <th>Agent Name</th>
                                    <th>Mobile</th>
                                    <th>PAN Number</th>
                                    <th>GST Number</th>
                                    <th>Business Name</th>
                                    <th>Business Type</th>
                                    <th>Owner Name</th>
                                    <th>Location</th>
                                    <th>Pincode</th>
                                    <th>Daily Sales</th>
                                    <th>Current Stock</th>
                                    <th>Daily Footfall</th>
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
                                $builder = $db->table('members')->select('*, members.name as owner,members.mobile as r_mobile,members.created_at as r_created_at')
                                    ->join('employees', 'employees.employeeID = members.agent')->join('groups', 'groups.g_id  = members.groupId');
                                $query = $builder->get();
                                foreach ($query->getResult() as $row) {
                                ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $row->member_id; ?></td>
                                        <?php

                                        if ($row->eli_run === "Y") {
                                            $builderB = $db->table('initial_eli_run');
                                            $builderB->select('*');
                                            $builderB->where('member_id ', $row->member_id);
                                            $queryB = $builderB->get();
                                            // $countEli = $builderB->countAllResults();
                                            foreach ($queryB->getResult() as $rowB) {
                                                $eli = $rowB->eligibility;
                                            }
                                        ?>

                                            <td class="text-success fw-bold"><?php echo $eli; ?></td>
                                        <?php
                                        } else {
                                            # code...
                                        ?>
                                            <td class="text-danger">Not Checked</td>
                                        <?php  } ?>
                                        <td><?php echo $row->groupName; ?></td>
                                        <td><?php echo $row->group_type; ?></td>
                                        <td><?php echo $row->name; ?></td>
                                        <td><?php echo $row->r_mobile; ?></td>
                                        <td><?php echo $row->pan; ?></td>
                                        <td><?php echo $row->gst; ?></td>
                                        <td><?php echo $row->businessName; ?></td>
                                        <td><?php echo $row->businessType; ?></td>
                                        <td><?php echo $row->owner; ?></td>
                                        <td><?php echo $row->location; ?></td>
                                        <td><?php echo $row->pincode; ?></td>
                                        <td><?php echo $row->dailySales; ?></td>
                                        <td><?php echo $row->stock; ?></td>
                                        <td><?php echo $row->footFall; ?></td>
                                        <td><?php echo $row->estab; ?></td>
                                        <td>
                                            <a href="<?php echo $row->image; ?>" target="_blank" rel="noopener noreferrer">
                                                <i class="far fa-eye"></i>
                                            </a>

                                        </td>
                                        <td><?php echo $row->r_created_at; ?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-primary  view" id="<?php echo $row->member_id; ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <!-- <button type="button" class="btn btn-danger details" id="<?php //echo $row->applicationID; 
                                                                                                                ?>">
                                                    <i class="align-middle" data-feather="eye"></i>
                                                </button> -->

                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Id</th>
                                    <th>Members Id</th>
                                    <th>Eligibility</th>
                                    <th>Market Name</th>
                                    <th>Market Type</th>
                                    <th>Agent Name</th>
                                    <th>Mobile</th>
                                    <th>PAN Number</th>
                                    <th>GST Number</th>
                                    <th>Business Name</th>
                                    <th>Business Type</th>
                                    <th>Owner Name</th>
                                    <th>Location</th>
                                    <th>Pincode</th>
                                    <th>Daily Sales</th>
                                    <th>Current Stock</th>
                                    <th>Daily Footfall</th>
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

<script>
    // new DataTable('#branch');
    $('#branch').DataTable({
        responsive: true,
        layout: {
            topStart: {
                buttons: ['excelHtml5']
            }
        }
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#branch").on("click", ".view", function() {
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
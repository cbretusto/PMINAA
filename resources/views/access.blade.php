@php
    session_start();
    $layout = 'layouts.layout';
    // if(isset($_SESSION['rapidx_user_accesses'])){
    //     $layout = 'layouts.layout';
    // }else{
    //     $layout = 'layouts.no_access';
    // }
@endphp
@extends($layout)
@section('title', 'Access Details')
@section('content_page')
    <style type="text/css">
        table.table thead th{
            text-align: center;
            vertical-align: middle;
        }

        table.table tbody td{
            vertical-align: middle;
        }

        .input_hidden {
            position: absolute;
            opacity: 0;
        }

        .class-disabled{
            pointer-events: none;
        }

        .nav-tabs .nav-link {
            font-weight: 500;
        }

        .nav-tabs .nav-link.active {
            background-color: #343a40;
            color: white;
        }
    </style>

    <div class="content-wrapper layout-fixed">
        <section class="content p-3">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title" style="margin-top: 8px;">User Access</h3>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <select name="category" id="slctUserAccess">
                                        <option value="" selected disabled>-- Select User Access --</option>
                                        <option value="0">Account Access</option>
                                        <option value="1">Network Folder Access</option>
                                    </select>
                                    <button type="button" class="btn btn-dark" id="buttonAddUserAccess" data-bs-toggle="modal" data-bs-target="#modalCreateUpdateUserAccess">
                                        <i class="fa fa-plus fa-md"></i> New Data
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table id="tableUserAccess" class="table table-bordered table-hover nowrap" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Action</th>
                                                <th>Status</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!------------------------------------------------------------------------------------------------------------------------------------->
    <!------------------------------------------------------------ USER ACCESS ------------------------------------------------------------>
    <!------------------------------------------------------------------------------------------------------------------------------------->
    <!-- Create / Update User Access Modal Start -->
    <div class="modal fade" id="modalCreateUpdateUserAccess" data-bs-keyboard="false" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fas fa-info-circle"></i>&nbsp;User Access</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" id="formUserAccess" autocomplete="off">
                    @csrf
                    <div class="card-body p-3">
                        <input type="text" class="input_hidden" id="textUserAccessId" name="user_access_id" placeholder="╭∩╮( •̀_•́ )╭∩╮" readonly>

                        <div class="row">
                            <div class="col-md-4 d-flex flex-column mb-3">
                                <label for="" class="form-label"><strong>Category</strong></label>
                                <select class="form-select" id="slctCategory" name="category">
                                    <option value="" selected disabled> Select Category </option>
                                    <option value="0"> Account </option>
                                    <option value="1"> Network Folder </option>
                                </select>
                            </div>

                            <div class="col-md-4 d-flex flex-column mb-3">
                                <label for="" class="form-label"><strong>Description</strong></label>
                                <input type="text" class="form-control" id="txtDescription" name="description">
                            </div>

                            <div class="col-md-4 d-flex flex-column mb-3">
                                <label for="" class="form-label"><strong>Details</strong></label>
                                <select class="form-select" id="slctDetails" name="details">
                                    <option value="" selected disabled> Select Details </option>
                                    <option value="0"> With Details </option>
                                    <option value="1"> Without Details </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="btnUserAccess" class="btn btn-dark"><i id="iBtnUserAccessIcon"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- Create / Update User Access Modal End -->

    <!-- User Access Status Modal Start -->
    <div class="modal fade" id="modalUserAccessChangeStatus" data-bs-keyboard="false" data-bs-backdrop="static">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="h4UserAccessChangeStatusTitle"><i class="fa-solid fa-link"></i> Change Status</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" id="formUserAccessChangeStatus" autocomplete="off">
                    @csrf
                    <div class="card-body p-3">
                        <label id="lblUserAccessChangeStatusLabel"></label>
                        <input type="text" class="input_hidden" name="user_access_id" placeholder="User Access Id" id="txtUserAccessChangeStatusId">
                        <input type="text" class="input_hidden" name="status" placeholder="Status" id="txtUserAccessChangeStatus">
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="btnUserAccessChangeStatus" class="btn btn-dark"><i id="iBtnUserAccessChangeStatusIcon"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- User Access Status Modal End -->

    <!------------------------------------------------------------------------------------------------------------------------------------->
    <!-------------------------------------------------------- USER ACCESS DETAILS -------------------------------------------------------->
    <!------------------------------------------------------------------------------------------------------------------------------------->
    <!-- Create / Update User Access Details Modal Start -->
    <div class="modal fade" id="modalCreateUpdateUserAccessDetails" data-bs-keyboard="false" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fas fa-info-circle"></i>&nbsp;User Access Details</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="card-body p-3">
                    <h2 class="fw-semibold"><center id="userAccessDescription"></center></h2>

                    <form method="post" id="formUserAccessDetails" autocomplete="off">
                        @csrf
                        <input type="text" class="input_hidden" id="textUserAccessDetailsId" name="user_access_details_id" placeholder="╭∩╮( •̀_•́ )╭∩╮" readonly>
                        <input type="text" class="input_hidden" id="textGetAccessId" name="get_access_id" placeholder="╭∩╮( •̀_•́ )╭∩╮" readonly>
                        <div class="row g-3 mb-3 align-items-end">
                            <div class="col-md-8 d-flex flex-column">
                                <input type="text" class="form-control" id="txtAccessDetailsDescription" name="description" placeholder="Description">
                            </div>

                            <div class="col-md-4 d-flex">
                                <button type="submit" class="btn btn-dark w-100" id="btnAddDescription">Add Description</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table id="tableUserAccessDetails" class="table table-bordered table-hover nowrap" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Status</th>
                                    <th>Description / Sub-Module</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Create / Update User Access Details Modal End -->

    <!-- Access Details Status Modal Start -->
    <div class="modal fade" id="modalAccessDetailsChangeStatus" data-bs-keyboard="false" data-bs-backdrop="static">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="h4AccessDetailsChangeStatusTitle"><i class="fa-solid fa-link"></i> Change Status</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" id="formAccessDetailsChangeStatus" autocomplete="off">
                    @csrf
                    <div class="card-body p-3">
                        <label id="lblAccessDetailsChangeStatusLabel"></label>
                        <input type="text" class="input_hidden" name="access_details_id" placeholder="Access Details Id" id="txtAccessDetailsChangeStatusId">
                        <input type="text" class="input_hidden" name="status" placeholder="Status" id="txtAccessDetailsChangeStatus">
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="btnAccessDetailsChangeStatus" class="btn btn-dark"><i id="iBtnAccessDetailsChangeStatusIcon"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- Access Details Status Modal End -->
@endsection

<!-- JS CONTENT --}} -->
@section('js_content')
    <script type="text/javascript">
        let dataTableUserAccess
        let dataTableUserAccessDetails
        let userAccessId
        let category

        $(document).ready(function () {
            resetModalFormValues();

            $('.select2bs5').each(function () {
                $(this).select2({
                    theme: 'bootstrap-5',
                    dropdownAutoWidth: true,
                    dropdownParent: $(this).closest('.modal')
                });
            });

            // -------------------------------------------------------------------------------------------------------------------------------------
            // ------------------------------------------------------------ User Access ------------------------------------------------------------
            // -------------------------------------------------------------------------------------------------------------------------------------
            $('#slctUserAccess').change(function (e) {
                e.preventDefault();
                category = $(this).val();
                dataTableUserAccess.draw();
            });

            dataTableUserAccess = $("#tableUserAccess").DataTable({
                "processing" : false,
                "serverSide" : true,
                "responsive": true,
                "order": [[1,'asc'],[2, "asc"]],
                "language": {
                    "info": "Showing _START_ to _END_ of _TOTAL_ User Access Record",
                    "lengthMenu": "Show _MENU_ User Access Record",
                },
                "ajax" : {
                    url: "view_user_access",
                    data: function (d) {
                        d.category = category
                    }
                },
                "columns":[
                    { "data" : "action", orderable:false, searchable:false},
                    { "data" : "status",
                        "defaultContent": 'N/A',
                        "name": 'status',
                        "orderable": true,
                        "searchable": true,
                        "render": function (data, type, row) {
                            let status
                            let badgeClass
                            switch (row.status) {
                                case 0:
                                    status = 'Active';
                                    badgeClass = 'success';
                                    break;
                                case 1:
                                    status = 'Inactive';
                                    badgeClass = 'danger';
                                    break;
                                default:
                                    status = 'Unknown';
                                    badgeClass = 'secondary';
                                    break;
                            }
                            return '<center><span class="badge bg-' + badgeClass + '">' + status + '</span></center>';
                        },
                    },
                    { "data" : "description"},
                ],
            });

            $("#formUserAccess").submit(function(event){
                event.preventDefault();

                CreateUpdateUserAccess();
            });

            $(document).on('click', '.actionUpdateUserAccess', function(e){
                e.preventDefault();
                userAccessId = $(this).attr('access-id');

                $("#textUserAccessId").val(userAccessId);
                UserAccessGetUserInfoByIdToEdit(userAccessId);
            });

            $(document).on('click', '.actionUserAccessChangeStatus', function(){
                let userAccessStatus = $(this).attr('status');
                let userAccessId = $(this).attr('access-id');
                $("#txtUserAccessChangeStatus").val(userAccessStatus);
                $("#txtUserAccessChangeStatusId").val(userAccessId);

                if(userAccessStatus == 0){
                    $("#lblUserAccessChangeStatusLabel").text('Are you sure to activate?');
                    $("#h4UserAccessChangeStatusTitle").html('<i class="fa-solid fa-link"></i> Activate User Access Access');
                }
                else{
                    $("#lblUserAccessChangeStatusLabel").text('Are you sure to deactivate?');
                    $("#h4UserAccessChangeStatusTitle").html('<i class="fa-solid fa-link"></i> Deactivate User Access Access');
                }
            });

            $("#formUserAccessChangeStatus").submit(function(event){
                event.preventDefault();
                UserAccessChangeStatus();
            });

            // -------------------------------------------------------------------------------------------------------------------------------------
            // ------------------------------------------------------ User Access Details ----------------------------------------------------------
            // -------------------------------------------------------------------------------------------------------------------------------------
            $(document).on('click', '.actionUserAccessDetails', function(e){
                e.preventDefault();
                userAccessId = $(this).attr('access-id');
                console.log('userAccessId: ', userAccessId);
                $("#textGetAccessId").val(userAccessId);

                let userAccessDescription = $(this).attr('access-description');
                $("#userAccessDescription").text(userAccessDescription);
                dataTableUserAccessDetails.draw();
            });

            dataTableUserAccessDetails = $("#tableUserAccessDetails").DataTable({
                "processing" : false,
                "serverSide" : true,
                "responsive": true,
                "order": [[1,'asc'],[2, "asc"]],
                "language": {
                    "info": "Showing _START_ to _END_ of _TOTAL_ User Access Details Record",
                    "lengthMenu": "Show _MENU_ User Access Details Record",
                },
                "ajax" : {
                    url: "view_user_access_details",
                    data: function (d) {
                        d.userAccessId = userAccessId;
                    },
                },
                "columns":[
                    { "data" : "action", orderable:false, searchable:false},
                    { "data" : "status",
                        "defaultContent": 'N/A',
                        "name": 'status',
                        "orderable": true,
                        "searchable": true,
                        "render": function (data, type, row) {
                            let status
                            let badgeClass
                            switch (row.status) {
                                case 0:
                                    status = 'Active';
                                    badgeClass = 'success';
                                    break;
                                case 1:
                                    status = 'Inactive';
                                    badgeClass = 'danger';
                                    break;
                                default:
                                    status = 'Unknown';
                                    badgeClass = 'secondary';
                                    break;
                            }
                            return '<center><span class="badge bg-' + badgeClass + '">' + status + '</span></center>';
                        },
                    },
                    { "data" : "description"},
                ],
            });

            $(document).on('click', '.actionUpdateUserAccessDetails', function(e){
                e.preventDefault();
                let userAccessDetailsId = $(this).attr('access_details-id');
                let userAccessDetailsDescription = $(this).attr('access_details-description');
                userAccessId = $(this).attr('access-id');

                $("#textUserAccessDetailsId").val(userAccessDetailsId);
                $("#textGetAccessId").val(userAccessId);
                $("#txtAccessDetailsDescription").val(userAccessDetailsDescription);
            });

            $("#formUserAccessDetails").submit(function(event){
                event.preventDefault();
                CreateUpdateUserAccessDetails();
            });

            $(document).on('click', '.actionUserAccessDetailsChangeStatus', function(){
                $('#modalAccessDetailsChangeStatus').modal('show');
                let accessDetailsStatus = $(this).attr('status');
                let userAccessId = $(this).attr('access_details-id');
                $("#txtAccessDetailsChangeStatus").val(accessDetailsStatus);
                $("#txtAccessDetailsChangeStatusId").val(userAccessId);

                if(accessDetailsStatus == 0){
                    $("#lblAccessDetailsChangeStatusLabel").text('Are you sure to activate?');
                    $("#h4AccessDetailsChangeStatusTitle").html('<i class="fa-solid fa-link"></i> Activate Access Details');
                }
                else{
                    $("#lblAccessDetailsChangeStatusLabel").text('Are you sure to deactivate?');
                    $("#h4AccessDetailsChangeStatusTitle").html('<i class="fa-solid fa-link"></i> Deactivate Access Details');
                }
            });

            $("#formAccessDetailsChangeStatus").submit(function(event){
                event.preventDefault();
                AccessDetailsChangeStatus();
            });

        });
    </script>
@endsection

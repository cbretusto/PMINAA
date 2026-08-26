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
@section('title', 'User Management')
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
                <div class="card shadow-sm">
                    <div class="card-body">
                        <!-- Tabs Navigation -->
                        <ul class="nav nav-tabs mb-3" id="userTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="userManagementTab" data-bs-toggle="tab" data-bs-target="#userManagement" type="button" role="tab" aria-controls="userManagement" aria-selected="true">
                                    <i class="fas fa-users-cog me-1"></i> User Management
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="userApproverTab" data-bs-toggle="tab" data-bs-target="#userApprover" type="button" role="tab" aria-controls="userApprover" aria-selected="false">
                                    <i class="fa-solid fa-person-circle-check me-1"></i> User Approver
                                </button>
                            </li>
                        </ul>

                        <!-- Tabs Content -->
                        <div class="tab-content" id="userTabContent">
                            <!-- Tab 1: User Management -->
                            <div class="tab-pane fade show active" id="userManagement" role="tabpanel" aria-labelledby="userManagementTab">
                                <div class="d-flex justify-content-end mb-3">
                                    <button type="button" class="btn btn-dark" id="buttonCreateUser" data-bs-toggle="modal" data-bs-target="#modalCreateUpdateUserManagement">
                                        <i class="fas fa-plus me-1"></i> New User
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table id="tableUserManagemnet" class="table table-bordered table-striped table-hover align-middle nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th>Action</th>
                                                <th>Status</th>
                                                <th>Employee<br>No.</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Department</th>
                                                <th>Position</th>
                                                <th>User<br>Level</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                            <!-- Tab 2: User Approver -->
                            <div class="tab-pane fade" id="userApprover" role="tabpanel" aria-labelledby="userApproverTab">
                                <div class="d-flex justify-content-end mb-3">
                                    <button type="button" class="btn btn-dark" id="buttonCreateUserApprover" data-bs-toggle="modal" data-bs-target="#modalCreateUpdateUserApprover">
                                        <i class="fas fa-plus me-1"></i> New Approver
                                    </button>
                                </div>

                                <div class="table-responsive">
                                    <table id="tableUserApprover" class="table table-bordered table-striped table-hover align-middle nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th>Action</th>
                                                <th>Name</th>
                                                <th>Classification</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                        </div> <!-- /.tab-content -->
                    </div> <!-- /.card-body -->
                </div> <!-- /.card -->
            </div> <!-- /.container-fluid -->
        </section>
    </div>

    <!------------------------------------------------------------------------------------------------------------------------------------->
    <!---------------------------------------------------------- USER MANAGEMENT ---------------------------------------------------------->
    <!------------------------------------------------------------------------------------------------------------------------------------->
    <!-- Add User Management Modal Start -->
    <div class="modal fade" id="modalCreateUpdateUserManagement" data-bs-keyboard="false" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fas fa-info-circle"></i>&nbsp;User information</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" id="formUserManagement" autocomplete="off">
                    @csrf
                    <div class="card-body p-3">
                        <input type="text" class="input_hidden" id="textUserId" name="user_id" placeholder="╭∩╮( •̀_•́ )╭∩╮" readonly>

                        <div class="row">
                            <div class="col-md-6 d-flex flex-column mb-3">
                                <label for="" class="form-label"><strong>Name</strong></label>
                                <select class="form-select select2bs5 get-rapidx-user" id="slctEmployeeNameWID" name="name_w_id">
                                </select>
                            </div>

                            <div class="col-md-6 d-flex flex-column mb-3">
                                <label for="" class="form-label"><strong>User Level</strong></label>
                                <select class="form-select" id="slctUserLevel" name="user_level">
                                    <option value="" selected disabled> Select User Level </option>
                                    <option value="0"> Admin </option>
                                    <option value="1"> User </option>
                                </select>
                            </div>

                            <div class="col-md-6 d-flex flex-column mb-3">
                                <label for="" class="form-label"><strong>Employee No.</strong></label>
                                <input type="text" class="form-control class-disabled" id="txtEmployeeNo" name="employee_no" placeholder="-- Auto generate employee no. --">
                            </div>

                            <div class="col-md-6 d-flex flex-column mb-3">
                                <label for="" class="form-label"><strong>Email</strong></label>
                                <input type="text" class="form-control class-disabled" id="txtEmployeeEmail" name="email" placeholder="-- Auto generate email --">
                            </div>

                            <div class="col-md-6 d-flex flex-column mb-3">
                                <label for="" class="form-label"><strong>Department</strong></label>
                                <select class="form-select get-systemone-department class-disabled" id="slctEmployeeDepartment" name="department">
                                </select>
                            </div>

                            <div class="col-md-6 d-flex flex-column mb-3">
                                <label for="" class="form-label"><strong>Position</strong></label>
                                <select class="form-select get-systemone-position class-disabled" id="slctEmployeePosition" name="position">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="btnUserManagement" class="btn btn-dark"><i id="iBtnUserManagementIcon"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- Add User Management Modal End -->

    <!-- User Management Status Modal Start -->
    <div class="modal fade" id="modalUserManagementChangeUserStatus" data-bs-keyboard="false" data-bs-backdrop="static">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="h4UserManagementChangeStatusTitle"><i class="fa fa-user"></i> Change Status</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" id="formUserManagementChangeStatus" autocomplete="off">
                    @csrf
                    <div class="card-body p-3">
                        <label id="lblUserManagementChangeStatusLabel"></label>
                        <input type="text" class="input_hidden" name="user_id" placeholder="User Id" id="txtUserManagementChangeStatusId">
                        <input type="text" class="input_hidden" name="status" placeholder="Status" id="txtUserManagementChangeStatus">
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="btnUserManagementChangeUserStatus" class="btn btn-dark"><i id="iBtnUserManagementChangeUserStatusIcon"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- User Management Status Modal End -->

    <!---------------------------------------------------------------------------------------------------------------------------------------->
    <!------------------------------------------------------------- USER APPROVER ------------------------------------------------------------>
    <!---------------------------------------------------------------------------------------------------------------------------------------->
    <!-- Create/Update User Approver Modal Start -->
    <div class="modal fade" id="modalCreateUpdateUserApprover" data-bs-keyboard="false" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-sm border-0">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-user-check me-2"></i>User Approver Information
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form method="post" id="formUserApprover" autocomplete="off">
                    @csrf
                    <div class="modal-body px-4 py-3">
                        <div class="row g-3">
                            <div class="col-md-12 d-flex flex-column mb-3">
                                <label for="" class="form-label"><strong>Name</strong></label>
                                <select class="form-select select2bs5 get-pminaa-user d-none" id="slctEmployeeFromUserManagement" name="user_id" required>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold mb-2">Classification (  <small class="text-muted">Select at least one classification</small> )</label>
                                <div class="d-flex flex-wrap gap-2">
                                    <div class="form-check btn-check-container">
                                        <input class="btn-check user-approver-classification" type="checkbox" name="user_approver_classification[]" value="1" id="classification1">
                                        <label class="btn btn-outline-dark rounded-pill py-2" for="classification1">
                                            Section Head
                                        </label>
                                    </div>

                                    <div class="form-check btn-check-container">
                                        <input class="btn-check user-approver-classification" type="checkbox" name="user_approver_classification[]" value="2" id="classification2">
                                        <label class="btn btn-outline-dark rounded-pill py-2" for="classification2">
                                            Department Head
                                        </label>
                                    </div>

                                    <div class="form-check btn-check-container">
                                        <input class="btn-check user-approver-classification" type="checkbox" name="user_approver_classification[]" value="3" id="classification3">
                                        <label class="btn btn-outline-dark rounded-pill py-2" for="classification3">
                                            ISS Manager
                                        </label>
                                    </div>

                                    <div class="form-check btn-check-container">
                                        <input class="btn-check user-approver-classification" type="checkbox" name="user_approver_classification[]" value="4" id="classification4">
                                        <label class="btn btn-outline-dark rounded-pill py-2" for="classification4">
                                            Admin AVP
                                        </label>
                                    </div>

                                    <div class="form-check btn-check-container">
                                        <input class="btn-check user-approver-classification" type="checkbox" name="user_approver_classification[]" value="5" id="classification5">
                                        <label class="btn btn-outline-dark rounded-pill py-2" for="classification5">
                                            Conformance
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-dark d-none" id="btnUserApprover"><i id="iBtnUserApproverIcon"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- Create/Update User Approver Modal End -->

    <!-- User Management Status Modal Start -->
    <div class="modal fade" id="modalUserManagementRemoveUserApprover" data-bs-keyboard="false" data-bs-backdrop="static">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-user"></i> User Approver</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" id="formUserManagementRemoveUserApprover" autocomplete="off">
                    @csrf
                    <div class="card-body p-3">
                        <label>Are you sure you want to remove the approver?</label>
                        <input type="text" class="input_hidden" name="user_id" placeholder="User Id" id="txtUserManagementRemoveUserApproverId">
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-dark" id="btnUserManagementRemoveUserApprover"><i id="iBtnUserManagementRemoveUserApproverIcon"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- User Management Status Modal End -->

@endsection

<!-- JS CONTENT --}} -->
@section('js_content')
    <script type="text/javascript">
        let dataTableUserManagement
        let dataTableUserApprover

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
            // ---------------------------------------------------------- USER MANAGEMENT ----------------------------------------------------------
            // -------------------------------------------------------------------------------------------------------------------------------------
            UserManagementGetRapidxUserActiveInSystemOne($('.get-rapidx-user'));
            UserManagementGetSystemOneDepartment($('.get-systemone-department'));
            UserManagementGetSystemOnePosition($('.get-systemone-position'));

            dataTableUserManagement = $("#tableUserManagemnet").DataTable({
                "processing" : false,
                "serverSide" : true,
                "responsive": true,
                "order": [[1,'asc'],[4, "asc"]],
                "language": {
                    "info": "Showing _START_ to _END_ of _TOTAL_ User Record",
                    "lengthMenu": "Show _MENU_ User Record",
                },
                "ajax" : {
                    url: "view_user",
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
                                case 2:
                                    status = 'Resigned';
                                    badgeClass = 'warning shadow';
                                    break;
                                default:
                                    status = 'Unknown';
                                    badgeClass = 'secondary';
                                    break;
                            }
                            return '<center><span class="badge bg-' + badgeClass + '">' + status + '</span></center>';
                        },
                    },
                    { "data" : "user_management_rapidx_user_info.employee_number"},
                    { "data" : "user_management_rapidx_user_info.name"},
                    { "data" : "user_management_rapidx_user_info.email"},
                    { "data" : "user_management_systemone_department_info.Department"},
                    { "data" : "user_management_systemone_position_info.Position"},
                    { "data" : "user_level",
                        "defaultContent": 'N/A',
                        "name": 'user_level',
                        "orderable": true,
                        "searchable": true,
                        "render": function (data, type, row) {
                            if(row.user_level == 0){
                                return "Admin";
                            }else{
                                return "User";
                            }
                        },
                        // "createdCell": function (td, cellData, rowData, row, col) {
                        //     $(td).addClass('text-center');
                        // }
                    }
                ],
            });

            $('#slctEmployeeNameWID').change(function (e) {
                e.preventDefault();
                const ajaxSelectName = {
                    url: 'get_rapidx_user_active_in_systemone',
                    method: 'GET',
                    successCallback: (response) => {
                        let getDataById     = $(this).val()
                        let getDataByName   = response['rapidxNameActiveInSystemone']
                        let department      = ''
                        let position        = ''

                        if(getDataByName.length > 0){
                            for(let index = 0; index < getDataByName.length; index++){
                                if(getDataByName[index].id == getDataById){
                                    $('#txtEmployeeNo').val(getDataByName[index].employee_number)
                                    if(getDataByName[index].email != null){
                                        $('#txtEmployeeEmail').val(getDataByName[index].email)
                                    }else{
                                        $('#txtEmployeeEmail').val('N/A')
                                    }

                                    if(getDataByName[index].rapidx_systemone_employee_info != null){
                                        department  = getDataByName[index].rapidx_systemone_employee_info.fkDepartment
                                        position    = getDataByName[index].rapidx_systemone_employee_info.fkPosition
                                    }

                                    $('#slctEmployeeDepartment').val(department).trigger('change')
                                    $('#slctEmployeePosition').val(position).trigger('change')
                                }
                            }
                        }
                    },
                    errorCallback: () => {
                    }
                };
                ajaxRequest(ajaxSelectName);
            });

            $("#formUserManagement").submit(function(event){
                event.preventDefault();

                UserManagementUserCreateUpdate();
            });

            $(document).on('click', '.actionUpdateUserManagement', function(e){
                e.preventDefault();
                let UserId = $(this).attr('user-id');
                    $("#textUserId").val(UserId);
                    UserManagementGetUserInfoByIdToEdit(UserId);
            });

            $(document).on('click', '.actionUserManagementChangeStatus', function(){
                let userStatus = $(this).attr('status');
                let userId = $(this).attr('user-id');
                $("#txtUserManagementChangeStatus").val(userStatus);
                $("#txtUserManagementChangeStatusId").val(userId);

                if(userStatus == 0){
                    $("#lblUserManagementChangeStatusLabel").text('Are you sure to activate?');
                    $("#h4UserManagementChangeStatusTitle").html('<i class="fa fa-user"></i> Activate User');
                }
                else{
                    $("#lblUserManagementChangeStatusLabel").text('Are you sure to deactivate?');
                    $("#h4UserManagementChangeStatusTitle").html('<i class="fa fa-user"></i> Deactivate User');
                }
            });

            $("#formUserManagementChangeStatus").submit(function(event){
                event.preventDefault();
                UserManagementChangeStatus();
            });

            // -------------------------------------------------------------------------------------------------------------------------------------
            // ----------------------------------------------------------- USER APPROVER -----------------------------------------------------------
            // -------------------------------------------------------------------------------------------------------------------------------------
            // GetInfoFromUserManagement($('.get-pminaa-user'));

            $('#userApprover').click(function (e) {
                e.preventDefault();
                GetInfoFromUserManagement($('.get-pminaa-user'));
            });

            dataTableUserApprover = $("#tableUserApprover").DataTable({
                "processing": false,
                "serverSide": true,
                "responsive": true,
                "order": [[1,'asc']],
                "language": {
                    "info": "Showing _START_ to _END_ of _TOTAL_ User Approver Record",
                    "lengthMenu": "Show _MENU_ User Approver Record",
                },
                "ajax" : {
                    url: "view_user_approver",
                },
                "columns":[
                    { "data" : "action", orderable:false, searchable:false},
                    { "data" : "user_management_rapidx_user_info.name"},
                    { "data" : "classification",
                        "defaultContent": 'N/A',
                        "name": 'classification',
                        "orderable": true,
                        "searchable": true,
                        "render": function (data, type, row) {
                            if (!data) return "N/A";

                            let classifications;

                            try {
                                if (typeof data === "string") {
                                    data = data.replace(/&quot;/g, '"');
                                    classifications = JSON.parse(data);
                                } else {
                                    classifications = data;
                                }
                            } catch (e) {
                                return "Invalid Data";
                            }

                            let map = {
                                "1": "Section Head",
                                "2": "Department Head",
                                "3": "ISS Manager",
                                "4": "ADMIN Asst. Vice President",
                                "5": "Conformance"
                            };

                            return classifications.map(val => map[val] || "Unknown").join("<br>");
                        },
                    }
                ],
            });

            $('#buttonCreateUserApprover').click(function (e) {
                e.preventDefault();
                $('#btnUserApprover').addClass('d-none');
            });

            $('.user-approver-classification').change(function (e) {
                e.preventDefault();
                if ($('.user-approver-classification:checked').length > 0) {
                    $('#btnUserApprover').removeClass('d-none');
                } else {
                    $('#btnUserApprover').addClass('d-none');
                }
            });

            $("#formUserApprover").submit(function(event){
                event.preventDefault();
                CreateUpdateUserApprover();
            });

            $(document).on('click', '.actionUserManagementUpdateUserApprover', function(){
                let userId = $(this).attr('user-id');
                GetUserApproverInfoByIdToEdit(userId);
            });

            $(document).on('click', '.actionUserManagementRemoveUserApprover', function(){
                let userId = $(this).attr('user-id');
                $("#txtUserManagementRemoveUserApproverId").val(userId);
            });

            $("#formUserManagementRemoveUserApprover").submit(function(event){
                event.preventDefault();
                UserManagementRemoveUserApprover();
            });
        });
    </script>
@endsection

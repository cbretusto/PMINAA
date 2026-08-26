@php
    session_start();
    $layout = 'layouts.layout';
    // if(isset($_SESSION['rapidx_user_accesses'])){
    //     $layout = 'layouts.layout';
    // }else{
    //     $layout = 'layouts.no_access';
    // }

    if (isset($_SESSION['rapidx_user_id'])){
        $sessionCheck = $_SESSION['rapidx_user_id'];
    }else{
        $sessionCheck = '';
    }

    if (isset($_SESSION['pminaa_request_approver'])){
        $pminaaRequestApprover = $_SESSION['pminaa_request_approver'];
    }else{
        $pminaaRequestApprover = '';
    }

    if (isset($_SESSION['pminaa_request_access_for_conformance'])){
        $pminaaRequestAccessForConformance = $_SESSION['pminaa_request_access_for_conformance'];
    }else{
        $pminaaRequestAccessForConformance = '';
    }
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

        .nav-smartwizard {
            pointer-events: none;
        }

        .step-badge {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #ffffff;
            color: #030303;
            font-size: 18px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .bg-blue-green-gradient {
            background: linear-gradient(75deg, #36517c, #1a6341);
        }
    </style>
    <div class="content-wrapper layout-fixed">
        <section class="content p-3">
            <div class="container-fluid">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <!-- Header -->
                        <div class="d-flex justify-content-between align-items-end mb-3">
                            <div>
                                <label for="requestStatus" class="fw-semibold text-danger mb-0">
                                        Select a request status to display the data.
                                    </label>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-gears fa-spin text-dark"></i>
                                    <select class="form-select shadow-lg border-2" id="requestStatus" name="request_status" style="width: 230px;">
                                        <option data-status="" selected disabled>🔎 Select Status </option>
                                        <option data-status="requestor">👤 Requestor</option>
                                        <option data-status="for_approval">🟡 For Approval</option>
                                        <option data-status="accountSetup">🖥️ For Account Setup</option>
                                        <option data-status="approved">✅ Approved</option>
                                        <option data-status="disapproved">❌ Disapproved</option>
                                    </select>

                                </div>
                            </div>

                            <button type="button"
                                class="btn btn-dark d-none"
                                id="buttonCreatePminaaRequest"
                                data-bs-toggle="modal"
                                data-bs-target="#modalCreateUpdatePminaaRequest">
                                <i class="fas fa-plus me-1"></i> New Request
                            </button>

                        </div>
                        <!-- Table -->
                        <div class="table-responsive">
                            <table id="tablePminaaRequest" class="table table-bordered table-striped table-hover align-middle w-100">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Approval Status</th>
                                        <th>Status</th>
                                        <th>Control No.</th>
                                        <th>Date Filed</th>
                                        <th>Employee<br>No.</th>
                                        <th>Name</th>
                                        <th>Department</th>
                                        <th>Position</th>
                                        <th>Factory</th>
                                        <th>User Type</th>
                                        <th>Requested By</th>
                                        <th>Approvers</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div> <!-- /.card -->
            </div> <!-- /.container-fluid -->
        </section>
    </div>

    <!-- Create/Update PMINAA Modal Start -->
    <div class="modal fade" id="modalCreateUpdatePminaaRequest" data-bs-keyboard="false" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl-custom">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fas fa-info-circle"></i>&nbsp;PMINAA Request</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formPminaaRequest">
                        @csrf
                        <input type="text" class="input_hidden" name="pminaa_id" placeholder="PMINAA Id" id="txtPminaaId" readonly>
                        <input type="text" class="input_hidden" name="user_login" placeholder="User Login" id="txtUserLogin" value="{{ $sessionCheck }}" readonly>
                        <div id="smartwizard" data-color="forest">
                            <ul class="nav">
                                <li class="nav-item col-6">
                                    <a class="nav-link nav-smartwizard fw-semibold">
                                        <div class="step-badge">1</div>
                                        PERSONAL INFO.
                                    </a>
                                </li>
                                <li class="nav-item col-6">
                                    <a class="nav-link nav-smartwizard fw-semibold">
                                        <div class="step-badge">2</div>
                                        EMPLOYMENT DETAILS
                                    </a>
                                </li>
                                <li class="nav-item col-6">
                                    <a class="nav-link nav-smartwizard fw-semibold">
                                        <div class="step-badge">3</div>
                                        INTERNET ACCESS
                                    </a>
                                </li>
                                <li class="nav-item col-6">
                                    <a class="nav-link nav-smartwizard fw-semibold">
                                        <div class="step-badge">4</div>
                                        ACCOUNT ACCESS
                                    </a>
                                </li>
                                <li class="nav-item col-6">
                                    <a class="nav-link nav-smartwizard fw-semibold">
                                        <div class="step-badge">5</div>
                                        NETWORK FOLDER ACCESS
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <!-- STEP 1 - PERSONAL INFO. -->
                                <div id="step-1" class="tab-pane">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="fw-semibold form-label">User Type:</label>
                                            <select class="form-select" id="slctUserType" name="user_type">
                                                <option value="" selected disabled>-- Select User Type --</option>
                                                <option value="PMI">PMI Employee</option>
                                                <option value="SUBCON">SUBCON Employee</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-semibold form-label">Factory:</label>
                                            <select class="form-select" id="slctFactory" name="factory">
                                                <option value="" selected disabled>-- Select Factory --</option>
                                                <option value="1">Factory 1/2</option>
                                                <option value="3">Factory 3</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3 d-flex flex-column">
                                            <label class="fw-semibold form-label">Employee No:</label>
                                            <select class="form-select select2bs5 get_pmi_subcon_employee" id="slctEmployeeNo" name="employee_no">
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="fw-semibold form-label">Last Name:</label>
                                            <input type="text" class="form-control" id="txtEmployeeLastName" name="employee_lastname" placeholder="Last Name" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="fw-semibold form-label">First Name:</label>
                                            <input type="text" class="form-control" id="txtEmployeeName" name="employee_name" placeholder="First Name" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="fw-semibold form-label">Middle Name:</label>
                                            <input type="text" class="form-control" id="txtEmployeeMiddleName" name="employee_middlename" placeholder="Middle Name" readonly>
                                        </div>
                                    </div>

                                    <div class="row mt-5">
                                        <hr>
                                            <h6 class="fw-semibold"><center>APPROVERS</center></h6>
                                        <hr>

                                        <div class="col-md-6 d-flex flex-column">
                                            <label class="fw-semibold form-label">Section Head:</label>
                                            <select class="form-select select2bs5 get_approvers" index="1" id="slctSectionHeadApprover" name="section_head">
                                            </select>
                                        </div>
                                        <div class="col-md-6 d-flex flex-column">
                                            <label class="fw-semibold form-label">Department Head:</label>
                                            <select class="form-select select2bs5 get_approvers" index="2" id="slctDepartmentHeadApprover" name="department_head">
                                            </select>
                                        </div>

                                        <div class="col-md-6 d-flex flex-column mt-4">
                                            <label class="fw-semibold form-label">ISS Manager:</label>
                                            <select class="form-select get_approvers class-disabled" index="3" id="slctIssManagerApprover" name="iss_manager">
                                            </select>
                                        </div>
                                        <div class="col-md-6 d-flex flex-column mt-4">
                                            <label class="fw-semibold form-label">Admin AVP:</label>
                                            <select class="form-select get_approvers class-disabled" index="4" id="slctAdminAvpApprover" name="admin_avp">
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-end mt-3">
                                        <button type="button" class="btn btn-dark" id="btnNextStep1">Next</button>
                                    </div>
                                </div>

                                <!-- STEP 2 - NATURE OF EMPLOYMENT -->
                                <div id="step-2" class="tab-pane">
                                    <p class="mb-2 fw-semibold">Nature of Employment:</p>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="chckRegular" name="employment_type" value="Regular">
                                        <label class="form-check-label" for="chckRegular">Regular</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="chckProbationary" name="employment_type" value="Probationary">
                                        <label class="form-check-label" for="chckProbationary">Probationary</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="chckSubcon" name="employment_type" value="Subcontractor">
                                        <label class="form-check-label" for="chckSubcon">Subcontractor</label>
                                    </div>

                                    <div class="row g-3 mt-3">
                                        <div class="col-md-6">
                                            <label class="fw-semibold form-label">Department / Agency:</label>
                                            <input type="text" class="form-control" id="txtEmployeeDepartmentAgency" name="employee_department_agency" placeholder="Employee Department / Agency" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-semibold form-label">Position / Job Title</label>
                                            <input type="text" class="form-control" id="txtEmployeePositionJobTitle" name="employee_position_job_title" placeholder="Employee Position / Job Title" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-semibold form-label">Section:</label>
                                            <input type="text" class="form-control" id="txtEmployeeSection" name="employee_section" placeholder="Employee Section" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-semibold form-label">Division</label>
                                            <input type="text" class="form-control" id="txtEmployeeDivision" name="employee_division" placeholder="Employee Division" readonly>
                                        </div>
                                        <div class="col-12">
                                            <label class="fw-semibold form-label">Remarks:</label>
                                            <textarea class="form-control" id="txtEmployeeRemarks" rows="3" name="remarks" placeholder="Enter Remarks"></textarea>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between mt-3">
                                        <button type="button" class="btn btn-dark" id="btnPrevStep2">Previous</button>
                                        <button type="button" class="btn btn-dark float-right" id="btnNextStep3">Next</button>
                                    </div>
                                </div>

                                <!-- STEP 3 - INTERNET ACCESS -->
                                <div id="step-3" class="tab-pane">
                                    <input type="text" class="input_hidden" id="jsonInternetAccess" name="get_internet_access">
                                    <p class="mb-2 fw-semibold">Internet Access:</p>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="chckNoInternetAccess" name="internet_access" value="No Internet Access">
                                        <label class="form-check-label" for="chckNoInternetAccess">No Internet Access</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="chckRestrictedInternetAccess" name="internet_access" value="R.I">
                                        <label class="form-check-label" for="chckRestrictedInternetAccess">Restricted Internet Access</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="chckLimitedInternetAccess" name="internet_access" value="Limited Internet Access">
                                        <label class="form-check-label" for="chckLimitedInternetAccess">Limited Internet Access</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="chckOpenInternetAccess" name="internet_access" value="Open Internet Access">
                                        <label class="form-check-label" for="chckOpenInternetAccess">Open Internet Access</label>
                                    </div>

                                    <div class="row g-3 mt-3">
                                        <div class="col-12">
                                            <label class="fw-semibold form-label">Justification:</label>
                                            <textarea class="form-control" id="txtEmployeeJustification" name="justification" rows="3" placeholder="Enter Justification"></textarea>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between mt-3">
                                        <button type="button" class="btn btn-dark" id="btnPrevStep3">Previous</button>
                                        <button type="button" class="btn btn-dark float-right" id="btnNextStep4">Next</button>
                                    </div>
                                </div>

                                <!-- STEP 4 - ACCOUNT ACCESS -->
                                <div id="step-4" class="tab-pane">
                                    <input type="text" class="input_hidden" id="jsonAccountSystemAccess" name="get_account_system_access">
                                    <div class="row g-3 mb-3 align-items-end">
                                        <div class="col-md-3 d-flex flex-column">
                                            <label class="fw-semibold form-label">Account / System Name:</label>
                                            <select class="form-select select2bs5 get_access" index="0" id="slctAccountSystemAccess" name="account_system_access">
                                            </select>
                                        </div>
                                        <div class="col-md-3 d-flex flex-column">
                                            <label class="fw-semibold form-label">Account / System Access:</label>
                                            <select class="form-select select2bs5 get_access_details" id="slctAccountSystemName" name="account_system_name">
                                            </select>
                                        </div>
                                        <div class="col-md-4 d-flex flex-column">
                                            <label class="fw-semibold form-label">Account / System Remark:</label>
                                            <input type="text" class="form-control" id="txtAccountSystemRemark" name="account_system_remark" placeholder="Enter Account / System Remark">
                                        </div>
                                        <div class="col-md-2 d-flex">
                                            <button type="button" class="btn btn-dark w-100" id="btnAddAccountAccess">Add Account Access</button>
                                        </div>
                                    </div>

                                    <table id="tableAccountAccess" class="table table-bordered table-striped table-hover align-middle nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th>Action</th>
                                                <th>Account</th>
                                                <th>Name</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableAccountAccessBody">
                                        </tbody>
                                    </table>
                                    <hr>
                                    <div class="d-flex justify-content-between mt-3">
                                        <button type="button" class="btn btn-dark" id="btnPrevStep4">Previous</button>
                                        <button type="button" class="btn btn-dark float-right" id="btnNextStep5">Next</button>
                                    </div>
                                </div>

                                <!-- STEP 5 - NETWORK FOLDER ACCESS -->
                                <div id="step-5" class="tab-pane">
                                    <input type="text" class="input_hidden" id="jsonFolderAccess" name="get_folder_access">
                                    <div class="row g-3 mb-3 align-items-end">
                                        <div class="col-md-2 d-flex flex-column">
                                            <label class="fw-semibold form-label">Folder Access:</label>
                                            <select class="form-select select2bs5 get_access" index="1" id="slctFolderAccess" name="folder_access">
                                            </select>
                                        </div>
                                        <div class="col-md-3 d-flex flex-column">
                                            <label class="fw-semibold form-label">Folder Name:</label>
                                            <select class="form-select select2bs5 get_access_details" id="slctFolderName" name="folder_name">
                                            </select>
                                        </div>
                                        <div class="col-md-3 d-flex flex-column">
                                            <label class="fw-semibold form-label">Folder Remark:</label>
                                            <input type="text" class="form-control" id="txtFolderRemark" name="folder_remark" placeholder="Enter Folder Remark">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="fw-semibold form-label">Access Type:</label>
                                            <select class="form-select" id="slctAccessTypeSelect" name="access_type">
                                                <option value="" selected disabled>-- Select Access Type --</option>
                                                <option value="Read Only">Read Only</option>
                                                <option value="Full Control">Full Control</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-dark w-100" id="btnAddFolderAccess">Add folder access</button>
                                        </div>
                                    </div>
                                    <table id="tableFolderAccess" class="table table-bordered table-striped table-hover align-middle nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th>Action</th>
                                                <th>Folder Access</th>
                                                <th>Folder Name</th>
                                                <th>Access Type</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableFolderAccessBody">
                                        </tbody>
                                    </table>
                                    <hr>
                                    <div class="d-flex justify-content-between mt-3">
                                        <button type="button" class="btn btn-dark" id="btnPrevStep5">Previous</button>
                                        <button type="submit" class="btn btn-success float-right" id="btnPminaaRequest"> <i id="iBtnPminaaRequestIcon"></i> Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div><!-- Create/Update PMINAA Modal End -->

    <!-- Approval Modal Start -->
    <div class="modal fade" id="modalPminaaRequestApproval" data-bs-keyboard="false" data-bs-backdrop="static">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h4 class="modal-title text-white"><i class="fa fa-file-invoice"></i>&nbsp;&nbsp;Approval of PMINAA Request</h4>
                    <button type="button" class="btn-close white-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" id="formPminaaRequestApproval" autocomplete="off">
                    @csrf
                    <div class="card-body p-3">
                        <input type="text" class="input_hidden" id="txtPminaaApprovalStatusId" name="pminaa_id" placeholder="PMINAA Id">
                        <input type="text" class="input_hidden" id="txtPminaaApprovalStatus" name="approval_status" placeholder="Approval Status">

                        <div class="d-flex flex-column">
                            <label for="" class="form-label"><strong>Remark:</strong></label>
                            <textarea class="form-control" id="textApprovalRemark" name="approval_remark" autocomplete="off" rows="2" placeholder="N/A"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-success" id="btnClosePminaaRequestApproval" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="btnPminaaRequestApproval" class="btn">
                            <label id="iBtnPminaaRequestApprovalIcon"></label>
                            <label id="approvalTitle"></label>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- Approval Modal End -->
@endsection

@section('js_content')
    <script>
        let sessionCheck = {!! json_encode($sessionCheck) !!};
        let pminaaRequestApprover = {!! json_encode($pminaaRequestApprover) !!};
        let pminaaRequestAccessForConformance = {!! json_encode($pminaaRequestAccessForConformance) !!};
        let dataTablePminaaRequest
        let userType
        let status = ''
        let shouldCheckButtonCount = true;

        $(document).ready(function (){
            GetPminaaApprover($('.get_approvers'));
            GetAccountFolderAccess($('.get_access'));

            resetModalFormValues();

            $('.select2bs5').each(function (){
                $(this).select2({
                    theme: 'bootstrap-5',
                    dropdownAutoWidth: true,
                    dropdownParent: $(this).closest('.modal')
                });
            });

            if(sessionCheck == ''){
                alert('Session expired!')
                window.location.reload();
            }

            if(pminaaRequestApprover == ''){
                alert('Session expired!')
                window.location.reload();
            }else{
                if(pminaaRequestApprover == '1'){
                    $('#requestStatus option[data-status="for_approval"]').prop('selected', true);
                    $('#requestStatus').trigger('change');

                    status = $('#requestStatus').find(':selected').data('status');
                }
            }

            if(pminaaRequestAccessForConformance == ''){
                alert('Session expired!')
                window.location.reload();
            }else{
                if(pminaaRequestAccessForConformance == '1'){
                    $('#requestStatus option[data-status="accountSetup"]').prop('selected', true);
                    $('#requestStatus').trigger('change');

                    status = $('#requestStatus').find(':selected').data('status');
                }
            }

            $('#requestStatus').change(function (e) {
                e.preventDefault();
                status = $(this).find(':selected').data('status');
                console.log(status);

                if(status == 'requestor'){
                    $('#buttonCreatePminaaRequest').removeClass('d-none');
                }else{
                    $('#buttonCreatePminaaRequest').addClass('d-none');
                }

                dataTablePminaaRequest.draw();
            });

            // -------------------------------------------------------------------------------------------------------------------------------------
            // --------------------------------------------------------- PMINAA DATATABLE ----------------------------------------------------------
            // -------------------------------------------------------------------------------------------------------------------------------------
            dataTablePminaaRequest = $("#tablePminaaRequest").DataTable({
                "processing" : false,
                "serverSide" : true,
                "responsive": true,
                // "pageLength": 50,
                "order": [[2, "asc"]],
                // "drawCallback": function () {
                //     if (!shouldCheckButtonCount) {
                //         return;
                //     }

                //     shouldCheckButtonCount = false;
                //     let api = this.api();
                //     let buttonCount = $('#tablePminaaRequest tbody button').length;

                //     if (buttonCount > 1) {
                //         let currentOrder = api.order();
                //         if(
                //             !currentOrder.length ||
                //             currentOrder[0][0] != 0 ||
                //             currentOrder[0][1] != 'desc'
                //         ){
                //             api.order([[0, 'desc']]).draw(false);
                //         }
                //     }
                // },
                "language": {
                    "info": "Showing _START_ to _END_ of _TOTAL_ PMINAA Record",
                    "lengthMenu": "Show _MENU_ PMINAA Record",
                },
                "ajax" : {
                    url: "view_pminaa_request",
                    data: function(data){
                        data.status = status;
                    },
                },
                "columns":[
                    { "data" : "action", orderable:false, searchable:false},
                    { "data" : "approval_status",
                        "defaultContent": 'N/A',
                        "name": 'approval_status',
                        "orderable": true,
                        "searchable": true,
                        "render": function (data, type, row) {
                            let status = 'Not found!';
                            let badge = 'secondary';
                            let color = 'white';

                            switch (parseInt(row.approval_status)) {
                                case 0:
                                    status = 'For Section Head <br>Approval';
                                    badge = 'warning';
                                    color = 'dark';
                                    break;
                                case 1:
                                    status = 'For Department Head <br>Approval';
                                    badge = 'warning';
                                    color = 'dark';
                                    break;
                                case 2:
                                    status = 'For ISS Manager <br>Approval';
                                    badge = 'warning';
                                    color = 'dark';
                                    break;
                                case 3:
                                    status = 'For Admin AVP <br>Approval';
                                    badge = 'warning';
                                    color = 'dark';
                                    break;
                                case 4:
                                    status = 'Account on <br>Setup';
                                    badge = 'blue-green-gradient';
                                    color = 'light';
                                    break;
                                case 5:
                                    status = 'Approved';
                                    badge = 'success';
                                    color = 'white';
                                    break;

                                case 6:
                                case 7:
                                case 8:
                                case 9:
                                case 10:
                                    status = 'Disapproved';
                                    badge = 'danger';
                                    color = 'white';
                                    break;
                            }

                            return '<center><span class="badge bg-' + badge + ' text-' + color + '">' + status + '</span></center>';
                        },
                    },
                    { "data" : "status",
                        "defaultContent": 'N/A',
                        "name": 'status',
                        "orderable": true,
                        "searchable": true,
                        "render": function (data, type, row){
                            let status
                            let badgeClass
                            switch (row.status){
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
                    { "data" : "control_no"},
                    { "data" : "created_at"},
                    { "data" : "employee_no"},
                    { "data" : "full_name"},
                    { "data" : "department_agency"},
                    { "data" : "position_job_title"},
                    { "data" : "factory"},
                    { "data" : "user_type"},
                    { "data" : "requested_by"},
                    { "data" : "approvers"},
                ],
                "columnDefs": [
                    {
                        "targets": [3, 8],
                        "className": "text-start"
                    }
                ]
            });

            // -------------------------------------------------------------------------------------------------------------------------------------
            // -------------------------------------------------------- SMART WIZARD SETUP ---------------------------------------------------------
            // -------------------------------------------------------------------------------------------------------------------------------------
            $('#smartwizard').smartWizard({
                initialStep: 0,
                theme: 'arrows',
                transitionEffect: 'slide-horizontal',
                autoAdjustHeight: true,
                showStepURLhash: false,
                keyNavigation: false,
                displayMode: 'none',
                toolbar: { position: 'none' },
            });

            function AdjustWizardHeight(){
                let activeStep = $("#smartwizard .tab-pane.active");
                if(!activeStep.length) return;
                let height = activeStep.outerHeight(true) + 120;
                $("#smartwizard").css("height", height + "px");
            }

            function Resize(){
                setTimeout(function (){
                    window.dispatchEvent(new Event('resize'));
                    AdjustWizardHeight();
                }, 222);
            }

            $('#modalCreateUpdatePminaaRequest').on('shown.bs.modal', function (){
                Resize()
            });

            $('#modalCreateUpdatePminaaRequest').on('hidden.bs.modal', function (){
                $('#smartwizard').smartWizard("reset");

                $("#smartwizard").css("height", "auto");
                $(".modal-content").css("height", "auto");

                $('#tableAccountAccess tbody').empty();
                $('#tableFolderAccess tbody').empty();
            });

            function validateStep(step){
                let errors = {};

                if(step == 1){
                    if(!$('#slctUserType').val()){
                        errors["slctUserType"] = "User Type is required.";
                    }

                    if(!$('#slctFactory').val()){
                        errors["slctFactory"] = "Factory is required.";
                    }

                    if(!$('#slctEmployeeNo').val()){
                        errors["slctEmployeeNo"] = "Employee No is required.";
                    }

                    if(!$('#txtEmployeeName').val()){
                        errors["txtEmployeeName"] = "First Name is required.";
                    }

                    if(!$('#txtEmployeeLastName').val()){
                        errors["txtEmployeeLastName"] = "Last Name is required.";
                    }

                    if(!$('#slctSectionHeadApprover').val()){
                        errors["slctSectionHeadApprover"] = "Section Head is required.";
                    }

                    if(!$('#slctDepartmentHeadApprover').val()){
                        errors["slctDepartmentHeadApprover"] = "Department Head is required.";
                    }

                    if(!$('#slctIssManagerApprover').val()){
                        errors["slctIssManagerApprover"] = "ISS Manager is required.";
                    }

                    if(!$('#slctAdminAvpApprover').val()){
                        errors["slctAdminAvpApprover"] = "Admin AVP is required.";
                    }
                }

                if(step == 2){
                    if(!$('input[name="employment_type"]:checked').val()){
                        errors["employment_type"] = "Select employment type.";
                    }

                    if(!$('#txtEmployeeDepartmentAgency').val()){
                        errors["txtEmployeeDepartmentAgency"] = "Department is required.";
                    }

                    if(!$('#txtEmployeePositionJobTitle').val()){
                        errors["txtEmployeePositionJobTitle"] = "Position is required.";
                    }

                    if(!$('#txtEmployeeSection').val()){
                        errors["txtEmployeeSection"] = "Section is required.";
                    }

                    if(!$('#txtEmployeeDivision').val()){
                        errors["txtEmployeeDivision"] = "Division is required.";
                    }

                    if(!$('#txtEmployeeRemarks').val()){
                        errors["txtEmployeeRemarks"] = "Remarks is required.";
                    }
                }

                if(step == 3){
                    if(!$('input[name="internet_access"]:checked').length){
                        errors["internet_access"] = "Select internet access.";
                    }

                    if(!$('#txtEmployeeJustification').val()){
                        errors["txtEmployeeJustification"] = "Justification is required.";
                    }
                }

                // if (step == 4) {
                //     if ($('#tableAccountAccess tbody tr').length === 0) {
                //         alert("Please add at least one Account Access before proceeding.");
                //         return false;
                //     }
                // }

                // if (step == 5) {
                //     if ($('#tableFolderAccess tbody tr').length === 0) {
                //         alert("Please add at least one Folder Access before submitting.");
                //         return false;
                //     }
                // }

                if(Object.keys(errors).length > 0){
                    handleValidatorErrors(errors);
                    return false;
                }

                clearValidationErrors();
                return true;
            }

            $('[id^="btnNextStep"]').on("click", function (){
                let currentStep = $(this).closest(".tab-pane");
                let stepIndex = currentStep.index() + 1;
                let userType = $('#slctUserType').val();

                if(userType == "PMI"){
                    $('#chckProbationary').prop('checked', true);
                }else{
                    $('#chckSubcon').prop('checked', true);
                }

                $('.get_access').val('').trigger('change');
                if(validateStep(stepIndex)){
                    $('#smartwizard').smartWizard("next");
                }

                Resize()
            });

            $('[id^="btnPrevStep"]').on("click", function (){
                $('.get_access').val('').trigger('change');
                $('#smartwizard').smartWizard("prev");
            });

            $('#slctUserType').change(function (e){
                e.preventDefault();
                userType = $(this).val();
                GetSystemOnePmiSubconEmployee($('.get_pmi_subcon_employee'), userType);
            });

            $('#slctEmployeeNo').change(function (e){
                e.preventDefault();
                let employeeNo = $(this).val();
                userType = $('#slctUserType').val();

                GetEmployeeInfo(employeeNo,userType);
            });

            $('.get_access').change(function (e){
                e.preventDefault();
                let getAccess = $(this).val();
                let getSystemModule = $(this).find("option:selected").text();

                GetAccountSystemFolderName($('.get_access_details'),getAccess,getSystemModule);
            });

            // -------------------------------------------------------------------------------------------------------------------------------------
            // ---------------------------------------------------------- PMINAA STEP 3 ------------------------------------------------------------
            // -------------------------------------------------------------------------------------------------------------------------------------
            // -------------------------------------------------------------------------------------------------------------------------------------
            $("#btnNextStep4").click(function () {
                buildInternetAccessJson();
            });

            function buildInternetAccessJson() {
                let internetItems = [];

                $("input[name='internet_access']:checked").each(function () {
                    internetItems.push($(this).next("label").text().trim());
                });

                const justification = $("#txtEmployeeJustification").val().trim();

                const step3Data = {
                    Justification: justification,
                    Details: internetItems
                };

                console.log("STEP 3 JSON:", step3Data);

                $("#jsonInternetAccess").val(JSON.stringify(step3Data));

                return step3Data;
            }

            // -------------------------------------------------------------------------------------------------------------------------------------
            // ---------------------------------------------------------- PMINAA STEP 4 ------------------------------------------------------------
            // -------------------------------------------------------------------------------------------------------------------------------------
            $("#btnNextStep5").click(function () {
                // let step5Data = $('#tableFolderAccess tbody tr').length;
                // console.log('step5Data: ', step5Data);
                buildAccountSystemAccessJson();
            });

            $("#btnAddAccountAccess").click(function (e) {
                e.preventDefault();

                const accountSystemAccessVal = $("#slctAccountSystemAccess").val();
                const accountSystemAccess = $("#slctAccountSystemAccess option:selected").text();
                const accountSystemName = $("#slctAccountSystemName option:selected").text();
                const remark = $("#txtAccountSystemRemark").val();

                if (!accountSystemAccessVal) return alert("Account System Access is required.");
                if (!remark.trim()) return alert("Account System Remark is required.");

                let isDuplicate = false;
                $("#tableAccountAccessBody tr").each(function () {
                    const rowAccess = $(this).find("td:eq(1)").text().trim();
                    const rowSystem = $(this).find("td:eq(2)").text().trim();

                    if (rowAccess === accountSystemAccess && rowSystem === accountSystemName) {
                        isDuplicate = true;
                    }
                });

                if(isDuplicate){
                    return alert("Data already exists.");
                }

                $("#tableAccountAccessBody").append(`
                    <tr>
                        <td class="text-center">
                            <button class="btn btn-danger btn-sm btnRemoveAccountSystemAccess">Remove</button>
                        </td>
                        <td>${accountSystemAccess}</td>
                        <td>${accountSystemName}</td>
                        <td>${remark}</td>
                    </tr>
                `);

                $("#slctAccountSystemAccess").val("").trigger("change");
                $("#slctAccountSystemName").val("").trigger("change");
                $("#txtAccountSystemRemark").val("");

                buildAccountSystemAccessJson();
                Resize();
            });

            function buildAccountSystemAccessJson() {
                let accountItems = [];

                $("#tableAccountAccessBody tr").each(function () {
                    accountItems.push({
                        accountSystemAccess: $(this).find("td:eq(1)").text().trim(),
                        accountSystemName: $(this).find("td:eq(2)").text().trim(),
                        remark: $(this).find("td:eq(3)").text().trim()
                    });
                });

                const step4Data = {
                    Details: accountItems
                };

                console.log("STEP 4 JSON:", step4Data);

                $("#jsonAccountSystemAccess").val(JSON.stringify(step4Data));

                return step4Data;
            }
            $("#tableAccountAccessBody").on("click", ".btnRemoveAccountSystemAccess", function () {
                $(this).closest("tr").remove();
                buildAccountSystemAccessJson();
            });

            // -------------------------------------------------------------------------------------------------------------------------------------
            // ---------------------------------------------------------- PMINAA STEP 5 ------------------------------------------------------------
            // -------------------------------------------------------------------------------------------------------------------------------------
            $("#btnAddFolderAccess").click(function (e) {
                e.preventDefault();

                const folderAccessVal = $("#slctFolderAccess").val();
                const folderAccess = $("#slctFolderAccess option:selected").text();

                const folderNameVal = $("#slctFolderName").val();
                const folderName = $("#slctFolderName option:selected").text();

                const accessType = $("#slctAccessTypeSelect").val();
                const remark = $("#txtFolderRemark").val();

                if (!folderAccessVal) return alert("Folder Access is required.");
                if (!folderNameVal) return alert("Folder Name is required.");
                if (!accessType) return alert("Access Type is required.");
                if (!remark.trim()) return alert("Folder Remark is required.");

                let isDuplicate = false;

                $("#tableFolderAccessBody tr").each(function () {
                    const rowAccess = $(this).find("td:eq(1)").text().trim();
                    const rowName = $(this).find("td:eq(2)").text().trim();
                    const rowType = $(this).find("td:eq(3)").text().trim();

                    if(
                        rowAccess === folderAccess &&
                        rowName === folderName &&
                        rowType === accessType
                    ){
                        isDuplicate = true;
                    }
                });

                if(isDuplicate){
                    return alert("This Folder Access already exists.");
                }

                $("#tableFolderAccessBody").append(`
                    <tr>
                        <td class="text-center">
                            <button class="btn btn-danger btn-sm btnRemoveFolderAccess">Remove</button>
                        </td>
                        <td>${folderAccess}</td>
                        <td>${folderName}</td>
                        <td>${accessType}</td>
                        <td>${remark}</td>
                    </tr>
                `);

                $("#slctFolderAccess").val("").trigger("change");
                $("#slctFolderName").val("").trigger("change");
                $("#slctAccessTypeSelect").val("");
                $("#txtFolderRemark").val("");

                buildFolderAccessJson();
                Resize();
            });

            function buildFolderAccessJson() {
                let folderItems = [];

                $("#tableFolderAccessBody tr").each(function () {
                    folderItems.push({
                        folder_access: $(this).find("td:eq(1)").text().trim(),
                        folder_name: $(this).find("td:eq(2)").text().trim(),
                        access_type: $(this).find("td:eq(3)").text().trim(),
                        remark: $(this).find("td:eq(4)").text().trim()
                    });
                });

                const step5Data = {
                    Details: folderItems
                };

                console.log("STEP 5 JSON:", step5Data);

                $("#jsonFolderAccess").val(JSON.stringify(step5Data));

                return step5Data;
            }

            $("#tableFolderAccessBody").on("click", ".btnRemoveFolderAccess", function () {
                $(this).closest("tr").remove();
                buildFolderAccessJson();
            });

            // -------------------------------------------------------------------------------------------------------------------------------------
            // ------------------------------------------------ GET PMINAA INFO BY ID TO UPDATE ----------------------------------------------------
            // -------------------------------------------------------------------------------------------------------------------------------------
            $(document).on('click', '.actionUpdatePminaaRequest', function(){
                let pminaaId = $(this).attr('pminaa_details-id');

                $("#txtPminaaId").val(pminaaId);
                GetPminaaRequestInfoByIdToEdit(pminaaId);
            });

            // -------------------------------------------------------------------------------------------------------------------------------------
            // ---------------------------------------------------------- PMINAA SUBMIT ------------------------------------------------------------
            // -------------------------------------------------------------------------------------------------------------------------------------
            $("#formPminaaRequest").submit(function (e){
                e.preventDefault();
                buildFolderAccessJson();

                // if ($('#tableFolderAccess tbody tr').length === 0) {
                //     alert("Please add at least one Folder Access before submitting.");
                //     return false;
                // }else{
                    CreateUpdatePminaaRequest();
                // }
            });

            // -------------------------------------------------------------------------------------------------------------------------------------
            // ----------------------------------------------------- PMINAA REQUEST APPROVAL -------------------------------------------------------
            // -------------------------------------------------------------------------------------------------------------------------------------
            $(document).on('click', '.actionPminaaRequestApprovalButton', function(){
                pminaaId = $(this).attr('pminaa_details-id');
                pminaaApprovalStatus = $(this).attr('approval-status');
                let getBtnValueForApproval = $(this).attr('value');

                $("#txtPminaaApprovalStatusId").val(pminaaId);
                $("#txtPminaaApprovalStatus").val(pminaaApprovalStatus);

                if(getBtnValueForApproval == 'approve'){
                    $("#btnPminaaRequestApproval").addClass('btn-success').removeClass('btn-danger')
                    $('#approvalTitle').text(' Approve')
                    $("#iBtnPminaaRequestApprovalIcon").html('<i class="fa-solid fa-thumbs-up"></i>')
                    $('#btnClosePminaaRequestApproval').addClass('btn-success').removeClass('btn-danger')
                }
                else{
                    $("#btnPminaaRequestApproval").addClass('btn-danger').removeClass('btn-success')
                    $("#iBtnPminaaRequestApprovalIcon").html('<i class="fa-solid fa-thumbs-down"></i>')
                    $('#btnClosePminaaRequestApproval').addClass('btn-danger').removeClass('btn-success')
                    $('#approvalTitle').text(' Disapprove')
                }
            });

            $("#formPminaaRequestApproval").submit(function(event){
                event.preventDefault();
                PminaaRequestChangeApprovalStatus();
            });
        });
    </script>
@endsection

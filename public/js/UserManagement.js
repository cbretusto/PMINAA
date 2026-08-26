// -------------------------------------------------------------------------------------------------------------------------------------
// ---------------------------------------------------------- USER MANAGEMENT ----------------------------------------------------------
// -------------------------------------------------------------------------------------------------------------------------------------
const UserManagementGetRapidxUserActiveInSystemOne = (element) => {
    let result = '';
    const ajaxGetRapidxUser = {
        url: 'get_rapidx_user_active_in_systemone',
        method: 'GET',
        successCallback: (response) => {
            let rapidxNameActiveInSystemone = response['rapidxNameActiveInSystemone']

            if(rapidxNameActiveInSystemone.length > 0){
                result += '<option value="" disabled selected>Select Employee Name</option>';
                for(let index = 0; index < rapidxNameActiveInSystemone.length; index++){
                    if(rapidxNameActiveInSystemone[index].rapidx_systemone_employee_info != null){
                        result += '<option value="' + rapidxNameActiveInSystemone[index].id + '">' + rapidxNameActiveInSystemone[index].name + '</option>';
                    }
                }
            }
            else{
                result += '<option value="" disabled>Not found</option>';
            }
            element.html(result);
        },
        errorCallback: () => {
            result = '<option value="" disabled>Reload Again</option>';
        }
    };
    ajaxRequest(ajaxGetRapidxUser);
}

const UserManagementGetSystemOneDepartment = (element) => {
    let result = '';
    const ajaxGetSystemOneDepartment = {
        url: 'get_systemone_department',
        method: 'GET',
        successCallback: (response) => {
            let systemoneDepartment = response['systemoneDepartment']
            if(systemoneDepartment.length > 0){
                result += '<option value="" disabled selected>-- Auto generate department --</option>';
                for(let index = 0; index < systemoneDepartment.length; index++){
                    result += '<option value="' + systemoneDepartment[index].pkid + '">' + systemoneDepartment[index].Department + '</option>';
                }
            }
            else{
                result += '<option value="" disabled>Not found</option>';
            }
            element.html(result);
        },
        errorCallback: () => {
            result = '<option value="" disabled>Reload Again</option>';
        }
    };
    ajaxRequest(ajaxGetSystemOneDepartment);
}

const UserManagementGetSystemOnePosition = (element) => {
    let result = '';
    const ajaxGetSystemOnePosition = {
        url: 'get_systemone_position',
        method: 'GET',
        successCallback: (response) => {
            
            if(response['systemonePosition'].length > 0){
                result += '<option value="" disabled selected>-- Auto generate position --</option>';
                for(let index = 0; index < response['systemonePosition'].length; index++){
                    result += '<option value="' + response['systemonePosition'][index].pkid + '">' + response['systemonePosition'][index].Position + '</option>';
                }
            }
            else{
                result += '<option value="" disabled>Not found</option>';
            }
            element.html(result);
        },
        errorCallback: () => {
            result = '<option value="" disabled>Reload Again</option>';
        }
    };
    ajaxRequest(ajaxGetSystemOnePosition);
}

function UserManagementUserCreateUpdate() {
    const ajaxUserCreateUpdate = {
        url: "user_create_update",
        method: "POST",
        data: $('#formUserManagement').serialize(),
        dataType: "json",
        beforeSendCallback: function(xhr) {
            $("#iBtnUserManagementIcon").addClass('spinner-border spinner-border-sm');
            $("#btnUserManagement").addClass('disabled');
            $("#iBtnUserManagementIcon").removeClass('fa fa-check');
        },
        successCallback: (response) => {
            if(response['hasError'] == 0) {
                clearValidationErrors();
                $('#modalCreateUpdateUserManagement').modal('hide');
                toastr.success('Successfully saved!!!');
                dataTableUserManagement.draw();
            }else{
                toastr.error('User Access already exist!');
                $('#modalCreateUpdateUserManagement').modal('hide');
            }
            $("#iBtnUserManagementIcon").removeClass('spinner-border spinner-border-sm');
            $("#btnUserManagement").removeClass('disabled');
            $("#iBtnUserManagementIcon").addClass('fa fa-check');
        },
        errorCallback: (xhr) => {
            handleValidatorErrors(xhr.responseJSON.errors);
            toastr.error('Saving user access failed!');

            $("#iBtnUserManagementIcon").removeClass('spinner-border spinner-border-sm');
            $("#btnUserManagement").removeClass('disabled');
            $("#iBtnUserManagementIcon").addClass('fa fa-check');
        }
    };

    ajaxRequest(ajaxUserCreateUpdate);
}

function UserManagementGetUserInfoByIdToEdit(userId){
    const ajaxGetUserByIdToEdit = {
        url: 'get_user_info_by_id',
        method: 'GET',
        data: {
            userId: userId
        },
        successCallback: (response) => {
            let requestUserInfo = response['requestUserInfo']
            if(requestUserInfo.length > 0){
                $("#slctEmployeeNameWID").val(requestUserInfo[0].rapidx_user_id).trigger('change');
                $("#slctUserLevel").val(requestUserInfo[0].user_level).trigger('change');
                $("#slctEmployeeDepartment").val(requestUserInfo[0].department).trigger('change');
                $("#slctEmployeePosition").val(requestUserInfo[0].position).trigger('change');
                
                let selectedDept = requestUserInfo[0].invoice_department;
                setTimeout(() => {
                    console.log(`#dept${selectedDept}`);
                    $(`#dept${selectedDept}`).prop('checked', true);
                }, 500);

            }        
        },
        errorCallback: () => {
            
        }
    };
    ajaxRequest(ajaxGetUserByIdToEdit);
}

function UserManagementChangeStatus(){
    const ajaxChangeUserStatus = {
        url: 'change_user_status',
        method: "POST",
        data: $('#formUserManagementChangeStatus').serialize(),
        dataType: "json",
        beforeSendCallback: function(xhr) {
            $("#iBtnUserManagementChangeUserStatusIcon").addClass('spinner-border spinner-border-sm');
            $("#btnUserManagementChangeUserStatus").addClass('disabled');
            $("#iBtnUserManagementChangeUserStatusIcon").removeClass('fa fa-check');
        },
        successCallback: (response) => {
            if(response['hasError'] == 0){
                if($("#txtUserManagementChangeStatus").val() == 0){
                    toastr.success('User activation success!');
                    $("#txtUserManagementChangeStatus").val() == 1;
                }
                else{
                    toastr.success('User deactivation success!');
                    $("#txtUserManagementChangeStatus").val() == 0;
                }
                $('#modalUserManagementChangeUserStatus').modal('hide');

                dataTableUserManagement.draw();
                dataTableUserApprover.draw();
            }
            $("#iBtnUserManagementChangeUserStatusIcon").removeClass('spinner-border spinner-border-sm');
            $("#btnUserManagementChangeUserStatus").removeClass('disabled');
            $("#iBtnUserManagementChangeUserStatusIcon").addClass('fa fa-check');
        },
        errorCallback: () => {
            toastr.error('An error occurred while processing your request.');
        }
    };
    ajaxRequest(ajaxChangeUserStatus);
}

// -------------------------------------------------------------------------------------------------------------------------------------
// ----------------------------------------------------------- USER APPROVER -----------------------------------------------------------
// -------------------------------------------------------------------------------------------------------------------------------------
const GetInfoFromUserManagement = (element) => {
    let result = '';
    const ajaxGetInfoFromUserManagement = {
        url: 'get_info_from_user_management',
        method: 'GET',
        successCallback: (response) => {
            let infoFromUserManagement = response['infoFromUserManagement']

            if(infoFromUserManagement.length > 0){
                result += '<option value="" disabled selected>Select Approver</option>';
                for(let index = 0; index < infoFromUserManagement.length; index++){
                    result += '<option value="' + infoFromUserManagement[index].id + '">' + infoFromUserManagement[index].user_management_rapidx_user_info.name + '</option>';
                }
            }
            else{
                result += '<option value="" disabled>Not found</option>';
            }
            element.html(result);
        },
        errorCallback: () => {
            result = '<option value="" disabled>Reload Again</option>';
        }
    };
    ajaxRequest(ajaxGetInfoFromUserManagement);
}

function CreateUpdateUserApprover() {
    const ajaxCreateUserApprover = {
        url: "create_update_user_approver",
        method: "POST",
        data: $('#formUserApprover').serialize(),
        dataType: "json",
        beforeSendCallback: function(xhr) {
            $("#iBtnUserApproverIcon").addClass('spinner-border spinner-border-sm');
            $("#btnUserApprover").addClass('disabled');
            $("#iBtnUserApproverIcon").removeClass('fa fa-check');
        },
        successCallback: (response) => {
            if (response['validationHasError'] == 1) {
                toastr.error('Saving user failed!');
            }else if(response['hasError'] == 0) {
                clearValidationErrors();
                $('#modalCreateUpdateUserApprover').modal('hide');
                toastr.success('Successfully saved!');
                dataTableUserApprover.draw();
            }else{
                toastr.error('User already exist!');
                $('#modalCreateUpdateUserApprover').modal('hide');
            }
            $("#iBtnUserApproverIcon").removeClass('spinner-border spinner-border-sm');
            $("#btnUserApprover").removeClass('disabled');
            $("#iBtnUserApproverIcon").addClass('fa fa-check');
        },
        errorCallback: () => {
            toastr.error('An error occurred while processing your request.');
        }
    };

    ajaxRequest(ajaxCreateUserApprover);
}

function GetUserApproverInfoByIdToEdit(userId){
    const ajaxGetUserApproverByIdToEdit = {
        url: 'get_user_approver_info_by_id',
        method: 'GET',
        data: {
            userId: userId
        },
        successCallback: (response) => {
            $('input[name="user_approver_classification[]"]').prop('checked', false);
            $('#btnUserApprover').removeClass('d-none');
            let requestUserApproverInfo = response['requestUserApproverInfo'];

            if(requestUserApproverInfo.length > 0){
                setTimeout(() => {               
                    $("#slctEmployeeFromUserManagement")
                        .val(requestUserApproverInfo[0].id)
                        .trigger('change');
                }, 333);

                let classifications = requestUserApproverInfo[0].classification;

                if (typeof classifications === "string") {
                    classifications = classifications.replace(/&quot;/g, '"');
                    classifications = JSON.parse(classifications);
                }

                classifications.forEach(val => {
                    $(`#classification${val}`).prop('checked', true);
                });
            }
        },
        errorCallback: () => {}
    };

    ajaxRequest(ajaxGetUserApproverByIdToEdit);
}

function UserManagementRemoveUserApprover(){
    const ajaxRemoveUserApprover = {
        url: 'remove_user_approver',
        method: "POST",
        data: $('#formUserManagementRemoveUserApprover').serialize(),
        dataType: "json",
        beforeSendCallback: function(xhr) {
            $("#iBtnUserManagementRemoveUserApproverIcon").addClass('spinner-border spinner-border-sm');
            $("#btnUserManagementRemoveUserApprover").addClass('disabled');
            $("#iBtnUserManagementRemoveUserApproverIcon").removeClass('fa fa-check');
        },
        successCallback: (response) => {
            if(response['hasError'] == 0){
                $('#modalUserManagementRemoveUserApprover').modal('hide');
                dataTableUserApprover.draw();
            }
            $("#iBtnUserManagementRemoveUserApproverIcon").removeClass('spinner-border spinner-border-sm');
            $("#btnUserManagementRemoveUserApprover").removeClass('disabled');
            $("#iBtnUserManagementRemoveUserApproverIcon").addClass('fa fa-check');
        },
        errorCallback: (xhr) => {
            toastr.error('An error occurred while processing your request.');
        }
    };
    ajaxRequest(ajaxRemoveUserApprover);
}
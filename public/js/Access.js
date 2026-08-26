// ========================================================================================================
// ============================================= User Access  =============================================
// ========================================================================================================
function CreateUpdateUserAccess() {
    const ajaxCreateUpdateUserAccess = {
        url: "create_update_user_access",
        method: "POST",
        data: $('#formUserAccess').serialize(),
        dataType: "json",
        beforeSendCallback: function(xhr) {
            $("#iBtnUserAccessIcon").addClass('spinner-border spinner-border-sm');
            $("#btnUserAccess").addClass('disabled');
            $("#iBtnUserAccessIcon").removeClass('fa fa-check');
        },
        successCallback: (response) => {
            if(response['hasError'] == 0) {
                clearValidationErrors();
                $('#modalCreateUpdateUserAccess').modal('hide');
                toastr.success('Successfully saved!!!');
                dataTableUserAccess.draw();
            }else{
                toastr.error('User Access already exist!');
                $('#modalCreateUpdateUserAccess').modal('hide');
            }
            $("#iBtnUserAccessIcon").removeClass('spinner-border spinner-border-sm');
            $("#btnUserAccess").removeClass('disabled');
            $("#iBtnUserAccessIcon").addClass('fa fa-check');
        },
        errorCallback: (xhr) => {
            handleValidatorErrors(xhr.responseJSON.errors);
            toastr.error('Saving user access failed!');

            $("#iBtnUserAccessIcon").removeClass('spinner-border spinner-border-sm');
            $("#btnUserAccess").removeClass('disabled');
            $("#iBtnUserAccessIcon").addClass('fa fa-check');
        }
    };

    ajaxRequest(ajaxCreateUpdateUserAccess);
}

function UserAccessGetUserInfoByIdToEdit(userAccessId){
    const ajaxGetUserAccessInfoByIdToEdit = {
        url: 'get_user_access_info_by_id',
        method: 'GET',
        data: {
            userAccessId: userAccessId
        },
        successCallback: (response) => {
            let requestUserAccessInfo = response['requestUserAccessInfo']
            if(requestUserAccessInfo.length > 0){
                $("#slctCategory").val(requestUserAccessInfo[0].category).trigger('change')
                $("#txtDescription").val(requestUserAccessInfo[0].description)
                $("#slctDetails").val(requestUserAccessInfo[0].details).trigger('change')
            }
        },
        errorCallback: () => {
            
        }
    };
    ajaxRequest(ajaxGetUserAccessInfoByIdToEdit);
}

function UserAccessChangeStatus(){
    const ajaxChangeUserAccessStatus = {
        url: 'change_user_access_status',
        method: "POST",
        data: $('#formUserAccessChangeStatus').serialize(),
        dataType: "json",
        beforeSendCallback: function(xhr) {
            $("#iBtnUserAccessChangeStatusIcon").addClass('spinner-border spinner-border-sm');
            $("#btnUserAccessChangeStatus").addClass('disabled');
            $("#iBtnUserAccessChangeStatusIcon").removeClass('fa fa-check');
        },
        successCallback: (response) => {
            if(response['hasError'] == 0){
                if($("#txtUserAccessChangeStatus").val() == 0){
                    toastr.success('User Access activation success!');
                    $("#txtUserAccessChangeStatus").val() == 1;
                }
                else{
                    toastr.success('User Access deactivation success!');
                    $("#txtUserAccessChangeStatus").val() == 0;
                }
                $('#modalUserAccessChangeStatus').modal('hide');

                dataTableUserAccess.draw();
            }
            $("#iBtnUserAccessChangeStatusIcon").removeClass('spinner-border spinner-border-sm');
            $("#btnUserAccessChangeStatus").removeClass('disabled');
            $("#iBtnUserAccessChangeStatusIcon").addClass('fa fa-check');
        },
        errorCallback: () => {
            toastr.error('An error occurred while processing your request.');
        }
    };
    ajaxRequest(ajaxChangeUserAccessStatus);
}

// ========================================================================================================
// ========================================= User Access Details ==========================================
// ========================================================================================================
function CreateUpdateUserAccessDetails() {
    const ajaxCreateUpdateUserAccessDetails = {
        url: "create_update_user_access_details",
        method: "POST",
        data: $('#formUserAccessDetails').serialize(),
        dataType: "json",
        beforeSendCallback: function(xhr) {

        },
        successCallback: (response) => {
            if(response['hasError'] == 0) {
                dataTableUserAccessDetails.draw();
                clearValidationErrors();
                toastr.success('Successfully saved!!!');
                $('#txtAccessDetailsDescription').val('')
            }else{
                toastr.error('Access Details already exist!');
            }
        },
        errorCallback: (xhr) => {
            handleValidatorErrors(xhr.responseJSON.errors);
            toastr.error('Saving access details failed!');
        }
    };

    ajaxRequest(ajaxCreateUpdateUserAccessDetails);
}

function AccessDetailsChangeStatus(){
    const ajaxChangeAccessDetailsStatus = {
        url: 'change_access_details_status',
        method: "POST",
        data: $('#formAccessDetailsChangeStatus').serialize(),
        dataType: "json",
        beforeSendCallback: function(xhr) {
            $("#iBtnAccessDetailsChangeStatusIcon").addClass('spinner-border spinner-border-sm');
            $("#btnAccessDetailsChangeStatus").addClass('disabled');
            $("#iBtnAccessDetailsChangeStatusIcon").removeClass('fa fa-check');
        },
        successCallback: (response) => {
            if(response['hasError'] == 0){
                if($("#txtAccessDetailsChangeStatus").val() == 0){
                    toastr.success('Access Details activation success!');
                    $("#txtAccessDetailsChangeStatus").val() == 1;
                }
                else{
                    toastr.success('Access Details deactivation success!');
                    $("#txtAccessDetailsChangeStatus").val() == 0;
                }
                $('#modalAccessDetailsChangeStatus').modal('hide');

                dataTableUserAccessDetails.draw();
            }
            $("#iBtnAccessDetailsChangeStatusIcon").removeClass('spinner-border spinner-border-sm');
            $("#btnAccessDetailsChangeStatus").removeClass('disabled');
            $("#iBtnAccessDetailsChangeStatusIcon").addClass('fa fa-check');
        },
        errorCallback: () => {
            toastr.error('An error occurred while processing your request.');
        }
    };
    ajaxRequest(ajaxChangeAccessDetailsStatus);
}
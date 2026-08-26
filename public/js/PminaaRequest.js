const GetSystemOnePmiSubconEmployee = (element,userType) => {
    let result = '';
    const ajaxGetSystemOnePmiSubconEmployee = {
        url: 'get_systemone_pmi_subcon_employee',
        data: {
            userType: userType
        },
        method: 'GET',
        successCallback: (response) => {
            let systemonePmiSubconEmployee = response['systemonePmiSubconEmployee']

            if(systemonePmiSubconEmployee.length > 0){
                result += '<option value="" disabled selected>-- Select Employee --</option>';
                for(let index = 0; index < systemonePmiSubconEmployee.length; index++){
                    result += '<option value="' + systemonePmiSubconEmployee[index].EmpNo + '">' + systemonePmiSubconEmployee[index].EmpNo + '</option>';
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
    ajaxRequest(ajaxGetSystemOnePmiSubconEmployee);
}

function GetEmployeeInfo(employeeNo,userType){
    const ajaxGetEmployeeInfo = {
        url: 'get_employee_info',
        method: 'GET',
        data: {
            employeeNo: employeeNo,
            userType: userType
        },
        successCallback: (response) => {
            let getEmployeeInfo = response['getEmployeeInfo']

            if(getEmployeeInfo == null) return
            if(getEmployeeInfo.length > 0){
                // $("#txtEmployeeLastName").val(getEmployeeInfo[0].LastName)
                // $("#txtEmployeeName").val(getEmployeeInfo[0].FirstName)
                // $("#txtEmployeeMiddleName").val(getEmployeeInfo[0].MiddleName)

                 // Fix corrupted UTF-8 characters
                const fixNameEncoding = (name) => {
                    return name ? name.replace(/Ã±/g, "ñ") : "";
                };

                $("#txtEmployeeLastName").val(fixNameEncoding(getEmployeeInfo[0].LastName));
                $("#txtEmployeeName").val(fixNameEncoding(getEmployeeInfo[0].FirstName));
                $("#txtEmployeeMiddleName").val(fixNameEncoding(getEmployeeInfo[0].MiddleName));
                $("#txtEmployeeDepartmentAgency").val(getEmployeeInfo[0].department_info.Department)
                $("#txtEmployeePositionJobTitle").val(getEmployeeInfo[0].position_info.Position)
                $("#txtEmployeeSection").val(getEmployeeInfo[0].section_info.Section)
                $("#txtEmployeeDivision").val(getEmployeeInfo[0].division_info.Division)
            }
        },
        errorCallback: () => {

        }
    };
    ajaxRequest(ajaxGetEmployeeInfo);
}

const GetPminaaApprover = (elements) => {
    const ajaxGetPminaaApprover = {
        url: 'get_pminaa_approver',
        method: 'GET',
        successCallback: (response) => {
            const pminaaApprover = response['pminaaApprover'];

            if (pminaaApprover.length > 0) {
                elements.each(function () {
                    const $select = $(this);
                    const selectIndex = parseInt($select.attr('index'));

                    let options = ([3, 4].includes(selectIndex))
                        ? ''
                        : '<option value="" disabled selected>-- Select Approver --</option>';

                    // Sort by user name ascending
                    const sortedApprovers = [...pminaaApprover].sort((a, b) => {
                        const nameA = a.user_management_rapidx_user_info?.name || '';
                        const nameB = b.user_management_rapidx_user_info?.name || '';

                        return nameA.localeCompare(nameB);
                    });

                    sortedApprovers.forEach(item => {
                        let classifications = item.classification;

                        if (typeof classifications === 'string') {
                            try {
                                classifications = JSON.parse(classifications);
                            } catch (e) {
                                classifications = classifications.split(',');
                            }
                        }

                        if (!Array.isArray(classifications)) {
                            classifications = [classifications];
                        }

                        classifications = classifications.map(Number);

                        const user = item.user_management_rapidx_user_info;

                        if (
                            user &&
                            classifications.includes(Number(selectIndex))
                        ) {
                            options += `<option value="${user.id}">${user.name}</option>`;
                        }
                    });

                    $select.html(options);
                });
            } else {
                elements.html('<option value="" disabled>No approvers found</option>');
            }
        },
        errorCallback: () => {
            elements.html('<option value="" disabled>Error loading. Reload page.</option>');
        }
    };

    ajaxRequest(ajaxGetPminaaApprover);
};


// const GetPminaaApprover = (elements) => {
//     const ajaxGetPminaaApprover = {
//         url: 'get_pminaa_approver',
//         method: 'GET',
//         successCallback: (response) => {
//             const pminaaApprover = response['pminaaApprover'];

//             if (pminaaApprover.length > 0) {
//                 elements.each(function () {
//                     const $select = $(this);
//                     const selectIndex = parseInt($select.attr('index'));

//                     let options = ([3, 4].includes(selectIndex))
//                                     ? ''
//                                     : '<option value="" disabled selected>-- Select Approver --</option>';

//                         pminaaApprover.forEach(item => {
//                             let classifications = item.classification;

//                             if (typeof classifications === 'string') {
//                                 try {
//                                     classifications = JSON.parse(classifications);
//                                 } catch (e) {
//                                     classifications = classifications.split(',');
//                                 }
//                             }

//                             if (!Array.isArray(classifications)) {
//                                 classifications = [classifications];
//                             }

//                             classifications = classifications.map(Number);

//                             const user = item.user_management_rapidx_user_info;

//                             if (classifications.includes(Number(selectIndex))) {
//                                 options += `<option value="${user.id}">${user.name}</option>`;
//                             }
//                         });

//                     $select.html(options);
//                 });
//             } else {
//                 elements.html('<option value="" disabled>No approvers found</option>');
//             }
//         },
//         errorCallback: () => {
//             elements.html('<option value="" disabled>Error loading. Reload page.</option>');
//         }
//     };

//     ajaxRequest(ajaxGetPminaaApprover);
// }

const GetAccountFolderAccess = (elements) => {
    const ajaxGetAccountFolderAccess = {
        url: 'get_account_system_folder_access',
        method: 'GET',
        successCallback: (response) => {
            const accountSystemFolderAccess = response['accountSystemFolderAccess'];

            if (accountSystemFolderAccess.length > 0) {
                elements.each(function () {
                    const $select = $(this);
                    const selectIndex = parseInt($select.attr('index'));

                    let options = '<option value="" disabled selected>-- Select Access --</option>';

                    accountSystemFolderAccess.forEach(item => {
                        const category = parseInt(item.category);
                        const description = `<option value="${item.id}">${item.description}</option>`;

                        if (selectIndex === 0 && category === 0) {
                            options += description;
                        }

                        if (selectIndex === 1 && category === 1) {
                            options += description;
                        }
                    });

                    $select.html(options);
                });
            }
            else {
                elements.html('<option value="" disabled>No data found</option>');
            }
        },
        errorCallback: () => {
            elements.html('<option value="" disabled>Error loading. Reload page.</option>');
        }
    };

    ajaxRequest(ajaxGetAccountFolderAccess);
}

const GetAccountSystemFolderName = (elements, getAccess, getSystemModule) => {
    const ajax = {
        url:    'get_account_system_folder_name',
        data:   {
                    getAccess: getAccess,
                    getSystemModule: getSystemModule
                },
        method: 'GET',

        successCallback: (response) => {
            const data = response.accountSystemFolderName;

            if(data.length > 0){
                let options = '<option value="" disabled selected>-- Select Name --</option>';

                data.forEach(item => {
                    options += `<option value="${item.id}">${item.text}</option>`;
                });

                elements.html(options);
                $('.get_access_details').prop('disabled', false);
            }else{
                $('.get_access_details').prop('disabled', true);
                elements.html('<option value="" disabled selected>-----</option>');
            }
        },

        errorCallback: () => {
            elements.html('<option value="" disabled>Error loading. Reload page.</option>');
        }
    };

    ajaxRequest(ajax);
};

function CreateUpdatePminaaRequest() {
    const ajaxPminaaRequest = {
        url: "create_update_pminaa_request",
        method: "POST",
        data: $('#formPminaaRequest').serialize(),
        dataType: "json",
        beforeSendCallback: function(xhr) {
            $("#iBtnPminaaRequestIcon").addClass('spinner-border spinner-border-sm');
            $("#btnPminaaRequest").addClass('disabled');
            $("#iBtnPminaaRequestIcon").removeClass('fa fa-check');
        },
        successCallback: (response) => {
            if(response['hasError'] == 0) {
                clearValidationErrors();
                $('#modalCreateUpdatePminaaRequest').modal('hide');
                toastr.success('Successfully saved!!!');
                dataTablePminaaRequest.draw();
            }else{
                toastr.error('Request already exist!');
                $('#modalCreateUpdatePminaaRequest').modal('hide');
            }
            $("#iBtnPminaaRequestIcon").removeClass('spinner-border spinner-border-sm');
            $("#btnPminaaRequest").removeClass('disabled');
            $("#iBtnPminaaRequestIcon").addClass('fa fa-check');
        },
        errorCallback: (xhr) => {
            handleValidatorErrors(xhr.responseJSON.errors);
            toastr.error('Saving user access failed!');

            $("#iBtnPminaaRequestIcon").removeClass('spinner-border spinner-border-sm');
            $("#btnPminaaRequest").removeClass('disabled');
            $("#iBtnPminaaRequestIcon").addClass('fa fa-check');
        }
    };

    ajaxRequest(ajaxPminaaRequest);
}

function GetPminaaRequestInfoByIdToEdit(pminaaId) {
    const ajaxGetPminaaRequestByIdToEdit = {
        url: 'get_pminaa_request_info_by_id',
        method: 'GET',
        data: { pminaaId: pminaaId },
        successCallback: (response) => {
            let pminaaRequestInfo = response['pminaaRequestInfo'];

            if (pminaaRequestInfo.length > 0) {
                const data = pminaaRequestInfo[0];
                console.log('data: ', data);

                // STEP 1 - Personal Info
                $("#slctUserType").val(data.user_type).trigger('change');
                $("#slctFactory").val(data.factory).trigger('change');

                setTimeout(() => {
                    $("#slctEmployeeNo").val(data.employee_no).trigger('change');
                    $("#txtEmployeeName").val(data.employee_name);
                    $("#txtEmployeeLastName").val(data.employee_lastname);
                }, 500);

                $("#slctSectionHeadApprover").val(data.approvers_info[0].section_head).trigger('change');
                $("#slctDepartmentHeadApprover").val(data.approvers_info[0].department_head).trigger('change');

                // STEP 2 - Nature of Employment
                $("input[name='employment_type'][value='" + data.nature_of_employment + "']").prop('checked', true);
                $("#txtEmployeeDepartmentAgency").val(data.department_agency);
                $("#txtEmployeePositionJobTitle").val(data.position_job_title);
                $("#txtEmployeeRemarks").val(data.remarks);

                // STEP 3 - Internet Access
                if (data.internet_access) {
                    const internetAccess = JSON.parse(data.internet_access);
                    $("input[name='internet_access']").prop('checked', false);
                    internetAccess.Details.forEach(item => {
                        if (item === "No Internet Access") $("#chckNoInternetAccess").prop('checked', true);
                        if (item === "Limited Internet Access") $("#chckRestrictedInternetAccess").prop('checked', true);
                        if (item === "CP Internet Access") $("#chckLimitedInternetAccess").prop('checked', true);
                        if (item === "Open Internet Access") $("#chckOpenInternetAccess").prop('checked', true);
                    });
                    $("#txtEmployeeJustification").val(internetAccess.Justification);
                }

                // STEP 4 - Account / System Access
                if (data.account_system_access) {
                    const accountAccess = JSON.parse(data.account_system_access).Details;
                    $("#tableAccountAccessBody").empty();
                    accountAccess.forEach(acc => {
                        let row = `<tr>
                            <td class="text-center"><button type="button" class="btn btn-danger btn-sm btnRemoveAccountSystemAccess">Remove</button></td>
                            <td>${acc.accountSystemAccess}</td>
                            <td>${acc.accountSystemName}</td>
                            <td>${acc.remark}</td>
                        </tr>`;
                        $("#tableAccountAccessBody").append(row);
                    });
                }

                // STEP 5 - Network Folder Access
                if (data.network_folder_access) {
                    const folderAccess = JSON.parse(data.network_folder_access).Details;
                    $("#tableFolderAccessBody").empty();
                    folderAccess.forEach(fld => {
                        let row = `<tr>
                            <td class="text-center"><button type="button" class="btn btn-danger btn-sm btnRemoveFolderAccess">Remove</button></td>
                            <td>${fld.folder_access}</td>
                            <td>${fld.folder_name}</td>
                            <td>${fld.access_type}</td>
                            <td>${fld.remark}</td>
                        </tr>`;
                        $("#tableFolderAccessBody").append(row);
                    });
                }
            }
        },
        errorCallback: () => {
            console.error("Failed to fetch PMINAA request info.");
        }
    };

    ajaxRequest(ajaxGetPminaaRequestByIdToEdit);
}

function PminaaRequestChangeApprovalStatus(){
    const ajaxPminaaRequestChangeApprovalStatus = {
        url: 'pminaa_request_change_approval_status',
        method: "POST",
        data: $('#formPminaaRequestApproval').serialize(),
        dataType: "json",
        beforeSendCallback: function(xhr) {
            $("#iBtnPminaaRequestApprovalIcon").addClass('spinner-border spinner-border-sm');
            $("#btnPminaaRequestApproval").addClass('disabled');
            $("#iBtnPminaaRequestApprovalIcon").removeClass('');
        },
        successCallback: (response) => {
            if(response['hasError'] == 0){
                $('#modalPminaaRequestApproval').modal('hide');
                dataTablePminaaRequest.draw();
            }
            $("#iBtnPminaaRequestApprovalIcon").removeClass('spinner-border spinner-border-sm');
            $("#btnPminaaRequestApproval").removeClass('disabled');
            $("#iBtnPminaaRequestApprovalIcon").addClass('');
        },
        errorCallback: () => {
            $("#iBtnPminaaRequestApprovalIcon").removeClass('spinner-border spinner-border-sm');
            $("#btnPminaaRequestApproval").removeClass('disabled');
            $("#iBtnPminaaRequestApprovalIcon").addClass('');

            toastr.error('An error occurred while processing your request.');
        }
    };
    ajaxRequest(ajaxPminaaRequestChangeApprovalStatus);
}

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Access Request Form</title>
</head>

<body style="
    margin:0;
    padding:0;
    background:#ffffff;
    font-family:Arial, Helvetica, sans-serif;
    color:#000000;
    font-size:13px;
">

    <table width="100%" cellpadding="0" cellspacing="0"
        style="
            max-width:1000px;
            margin:0 auto;
            background:#ffffff;
            border:1px solid #000000;
            border-radius:0;
            overflow:hidden;
        ">

        <!-- HEADER -->
        <tr>
            <td style="
                padding:12px 16px;
                background:#000000;
                color:#ffffff;
            ">
                <div style="
                    font-size:18px;
                    font-weight:bold;
                ">
                    PMI Network Account Activation
                </div>
            </td>
        </tr>


        <!-- EMPLOYEE INFORMATION -->
        <tr>
            <td style="padding:12px 16px 8px;">

                {{-- <div style="
                    font-size:14px;
                    font-weight:bold;
                    color:#000000;
                    border-bottom:2px solid #000000;
                    padding-bottom:4px;
                    margin-bottom:7px;
                ">
                    Employee Information
                </div> --}}

                <table width="100%" cellpadding="0" cellspacing="0"
                    style="border-collapse:collapse;">

                    <tr>
                        <td width="25%" style="padding:3px 8px 3px 0;">
                            <strong>Control No.</strong>
                        </td>
                        <td style="padding:3px 0;">
                            : {{ $data['control_no'] ?? 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td width="25%" style="padding:3px 8px 3px 0;">
                            <strong>Employee No.</strong>
                        </td>
                        <td style="padding:3px 0;">
                            : {{ $data['employee_no'] ?? 'N/A' }}
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:3px 8px 3px 0;">
                            <strong>Employee Name</strong>
                        </td>
                        <td style="padding:3px 0;">
                            :
                            {{ $data['employee_lastname'] ?? '' }},
                            {{ $data['employee_name'] ?? '' }}
                            {{ $data['employee_middlename'] ? substr($data['employee_middlename'], 0, 1) . '.' : '' }}
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:3px 8px 3px 0;">
                            <strong>User Type</strong>
                        </td>
                        <td style="padding:3px 0;">
                            : {{ $data['user_type'] ?? 'N/A' }}
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:3px 8px 3px 0;">
                            <strong>Nature of Employment</strong>
                        </td>
                        <td style="padding:3px 0;">
                            : {{ $data['nature_of_employment'] ?? 'N/A' }}
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:3px 8px 3px 0;">
                            <strong>Position</strong>
                        </td>
                        <td style="padding:3px 0;">
                            : {{ $data['position_job_title'] ?? 'N/A' }}
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:3px 8px 3px 0;">
                            <strong>Department / Agency</strong>
                        </td>
                        <td style="padding:3px 0;">
                            : {{ $data['department_agency'] ?? 'N/A' }}
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:3px 8px 3px 0;">
                            <strong>Remarks</strong>
                        </td>
                        <td style="padding:3px 0;">
                            : {{ $data['remarks'] ?? 'N/A' }}
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
        <br>

        <!-- INTERNET ACCESS -->
        <tr>
            <td style="padding:8px 16px;">

                <div style="
                    font-size:14px;
                    font-weight:bold;
                    color:#000000;
                    border-top:2px solid #000000;
                    border-bottom:2px solid #000000;
                    padding:5px 0 4px;
                    margin-bottom:7px;
                ">
                    Internet Access
                </div>

                <table width="100%" cellpadding="0" cellspacing="0"
                    style="border-collapse:collapse;">

                    <tr>
                        <td width="25%" style="
                            padding:3px 8px 3px 0;
                            font-weight:bold;
                        ">
                            Justification
                        </td>

                        <td style="padding:3px 0;">
                            {{ $data['internet_access']['Justification'] ?? 'N/A' }}
                        </td>
                    </tr>

                </table>

                @if (!empty($data['internet_access']['Details']))

                    <table width="100%" cellpadding="0" cellspacing="0"
                        style="
                            border-collapse:collapse;
                            border:1px solid #000000;
                            margin-top:5px;
                        ">

                        <thead>
                            <tr>
                                <th align="left" style="
                                    padding:5px 7px;
                                    border-bottom:1px solid #000000;
                                    background:#ffffff;
                                ">
                                    Details
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($data['internet_access']['Details'] as $item)
                                <tr>
                                    <td style="
                                        padding:5px 7px;
                                        border-bottom:1px solid #000000;
                                    ">
                                        {{ $item }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                @else

                    <table width="100%" cellpadding="0" cellspacing="0"
                        style="
                            border-collapse:collapse;
                            border:1px solid #000000;
                            margin-top:5px;
                        ">
                        <tr>
                            <td align="center" style="
                                padding:5px;
                                background:#ffffff;
                                color:#000000;
                            ">
                                N/A
                            </td>
                        </tr>
                    </table>

                @endif

            </td>
        </tr>
        <br>

        <!-- ACCOUNT / SYSTEM ACCESS -->
        <tr>
            <td style="padding:8px 16px;">

                <div style="
                    font-size:14px;
                    font-weight:bold;
                    color:#000000;
                    border-top:2px solid #000000;
                    border-bottom:2px solid #000000;
                    padding:5px 0 4px;
                    margin-bottom:7px;
                ">
                    Account / System Access
                </div>

                @if (!empty($data['account_system_access']['Details']))

                    <table width="100%" cellpadding="0" cellspacing="0"
                        style="
                            border-collapse:collapse;
                            border:1px solid #000000;
                        ">

                        <thead>
                            <tr>

                                <th align="left" style="
                                    padding:5px 7px;
                                    border-right:1px solid #000000;
                                    border-bottom:1px solid #000000;
                                    background:#ffffff;
                                ">
                                    Access Type
                                </th>

                                <th align="left" style="
                                    padding:5px 7px;
                                    border-right:1px solid #000000;
                                    border-bottom:1px solid #000000;
                                    background:#ffffff;
                                ">
                                    Account Name
                                </th>

                                <th align="left" style="
                                    padding:5px 7px;
                                    border-bottom:1px solid #000000;
                                    background:#ffffff;
                                ">
                                    Remarks
                                </th>

                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($data['account_system_access']['Details'] as $account)

                                <tr>

                                    <td style="
                                        padding:5px 7px;
                                        border-right:1px solid #000000;
                                        border-bottom:1px solid #000000;
                                    ">
                                        {{ $account['accountSystemAccess'] ?? 'N/A' }}
                                    </td>

                                    <td style="
                                        padding:5px 7px;
                                        border-right:1px solid #000000;
                                        border-bottom:1px solid #000000;
                                    ">
                                        {{ $account['accountSystemName'] ?? 'N/A' }}
                                    </td>

                                    <td style="
                                        padding:5px 7px;
                                        border-bottom:1px solid #000000;
                                    ">
                                        {{ $account['remark'] ?? 'N/A' }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                @else

                    <!-- MERGED N/A ROW -->
                    <table width="100%" cellpadding="0" cellspacing="0"
                        style="
                            border-collapse:collapse;
                            border:1px solid #000000;
                        ">
                        <tr>
                            <td align="center" colspan="3" style="
                                padding:5px;
                                background:#ffffff;
                                color:#000000;
                            ">
                                N/A
                            </td>
                        </tr>
                    </table>

                @endif

            </td>
        </tr>
        <br>

        <!-- NETWORK FOLDER ACCESS -->
        <tr>
            <td style="padding:8px 16px 14px;">

                <div style="
                    font-size:14px;
                    font-weight:bold;
                    color:#000000;
                    border-top:2px solid #000000;
                    border-bottom:2px solid #000000;
                    padding:5px 0 4px;
                    margin-bottom:7px;
                ">
                    Network Folder Access
                </div>

                @if (!empty($data['network_folder_access']['Details']))

                    <table width="100%" cellpadding="0" cellspacing="0"
                        style="
                            border-collapse:collapse;
                            border:1px solid #000000;
                        ">

                        <thead>
                            <tr>

                                <th align="left" style="
                                    padding:5px 7px;
                                    border-right:1px solid #000000;
                                    border-bottom:1px solid #000000;
                                    background:#ffffff;
                                ">
                                    Folder Access
                                </th>

                                <th align="left" style="
                                    padding:5px 7px;
                                    border-right:1px solid #000000;
                                    border-bottom:1px solid #000000;
                                    background:#ffffff;
                                ">
                                    Folder Name
                                </th>

                                <th align="left" style="
                                    padding:5px 7px;
                                    border-right:1px solid #000000;
                                    border-bottom:1px solid #000000;
                                    background:#ffffff;
                                ">
                                    Access Type
                                </th>

                                <th align="left" style="
                                    padding:5px 7px;
                                    border-bottom:1px solid #000000;
                                    background:#ffffff;
                                ">
                                    Remarks
                                </th>

                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($data['network_folder_access']['Details'] as $folder)

                                <tr>

                                    <td style="
                                        padding:5px 7px;
                                        border-right:1px solid #000000;
                                        border-bottom:1px solid #000000;
                                    ">
                                        {{ $folder['folder_access'] ?? 'N/A' }}
                                    </td>

                                    <td style="
                                        padding:5px 7px;
                                        border-right:1px solid #000000;
                                        border-bottom:1px solid #000000;
                                    ">
                                        {{ $folder['folder_name'] ?? 'N/A' }}
                                    </td>

                                    <td style="
                                        padding:5px 7px;
                                        border-right:1px solid #000000;
                                        border-bottom:1px solid #000000;
                                    ">
                                        {{ $folder['access_type'] ?? 'N/A' }}
                                    </td>

                                    <td style="
                                        padding:5px 7px;
                                        border-bottom:1px solid #000000;
                                    ">
                                        {{ $folder['remark'] ?? 'N/A' }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                @else

                    <!-- MERGED N/A ROW -->
                    <table width="100%" cellpadding="0" cellspacing="0"
                        style="
                            border-collapse:collapse;
                            border:1px solid #000000;
                        ">
                        <tr>
                            <td align="center" colspan="4" style="
                                padding:5px;
                                background:#ffffff;
                                color:#000000;
                            ">
                                N/A
                            </td>
                        </tr>
                    </table>

                @endif

            </td>
        </tr>

    </table>

</body>

</html>

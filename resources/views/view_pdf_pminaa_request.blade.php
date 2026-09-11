<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>PMI Network Account Activation</title>

        <style>
            @page {
                size: A4;
                margin: 15px 20px;
            }

            html,
            body {
                width: 100%;
                background: #ffffff;
                font-family: Arial, Helvetica, sans-serif;
                color: #000000;
                font-size: 13px;
            }

            /* =====================================================
            ======================= GENERAL ========================
            ======================================================== */
            table {
                border-collapse: collapse;
            }

            /* =====================================================
            ===================== MAIN HEADER ======================
            ======================================================== */
            .main-header {
                width: 100%;
                border: 1px solid #000000;
                background: #000000;
                color: #ffffff;
            }

            .main-header td {
                padding: 12px 16px;
                font-size: 18px;
                font-weight: bold;
            }

            /* =====================================================
            ================ EMPLOYEE INFORMATION ==================
            ======================================================== */
            .employee-section {
                width: 100%;
                padding: 10px;
            }

            .employee-table {
                width: 100%;
            }

            .employee-table td {
                padding: 3px 8px 3px 0;
                vertical-align: top;
            }

            .employee-label {
                width: 25%;
                font-weight: bold;
            }

            /* =====================================================
            ==================== SECTION TITLE =====================
            ======================================================== */
            .section-title {
                width: 100%;
                font-size: 14px;
                font-weight: bold;

                border-top: 2px solid #000000;
                border-bottom: 2px solid #000000;

                padding: 5px 0 4px 0;
                margin: 0 0 7px 0;
            }

            /* =====================================================
            =================== SECTION WRAPPER ====================
            ======================================================== */
            .section {
                width: 100%;
                margin: 0;
                padding: 8px 0;
            }

            /* =====================================================
            =================== INTERNET ACCESS ====================
            ======================================================== */
            .justification-table {
                width: 100%;
            }

            .justification-table td {
                padding: 3px 8px 3px 0;
                vertical-align: top;
            }

            .justification-label {
                width: 25%;
                font-weight: bold;
            }

            /* =====================================================
            ====================== DATA TABLE ======================
            ======================================================== */
            .data-table {
                width: 100%;
                border: 1px solid #000000;
                page-break-inside: auto;
            }

            .data-table thead {
                display: table-header-group;
            }

            .data-table tbody {
                display: table-row-group;
            }

            .data-table tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            .data-table th {
                padding: 5px 7px;
                text-align: left;
                vertical-align: top;

                background: #ffffff;
                font-weight: bold;

                border-right: 1px solid #000000;
                border-bottom: 1px solid #000000;
            }

            .data-table th:last-child {
                border-right: none;
            }

            .data-table td {
                padding: 5px 7px;
                vertical-align: top;

                border-right: 1px solid #000000;
                border-bottom: 1px solid #000000;
            }

            .data-table td:last-child {
                border-right: none;
            }

            /* =====================================================
            ====================== N/A TABLE =======================
            ======================================================== */
            .na-table {
                width: 100%;
                border: 1px solid #000000;
            }

            .na-table td {
                padding: 5px;
                text-align: center;
            }

            /* =====================================================
            ================= PDF PAGE BREAK RULES =================
            ======================================================== */
            table {
                page-break-inside: auto;
            }

            thead {
                display: table-header-group;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

        </style>
    </head>

    <body>
        <!-- =====================================================
        ========================= HEADER =========================
        ========================================================== -->
        <table class="main-header" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    PMI Network Account Activation
                </td>
            </tr>
        </table>

        <!-- =====================================================
        ================== EMPLOYEE INFORMATION ==================
        ========================================================== -->
        <div class="employee-section">
            <table class="employee-table" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="employee-label">
                        Control No.
                    </td>
                    <td>
                        : {{ $data['control_no'] ?? 'N/A' }}
                    </td>
                </tr>

                <tr>
                    <td class="employee-label">
                        Employee No.
                    </td>
                    <td>
                        : {{ $data['employee_no'] ?? 'N/A' }}
                    </td>
                </tr>

                <tr>
                    <td class="employee-label">
                        Employee Name
                    </td>
                    <td>
                        :
                        {{ $data['employee_lastname'] ?? '' }},
                        {{ $data['employee_name'] ?? '' }}

                        @if (!empty($data['employee_middlename']))
                            {{ substr($data['employee_middlename'], 0, 1) }}.
                        @endif
                    </td>
                </tr>

                <tr>
                    <td class="employee-label">
                        User Type
                    </td>
                    <td>
                        : {{ $data['user_type'] ?? 'N/A' }}
                    </td>
                </tr>

                <tr>
                    <td class="employee-label">
                        Nature of Employment
                    </td>
                    <td>
                        : {{ $data['nature_of_employment'] ?? 'N/A' }}
                    </td>
                </tr>

                <tr>
                    <td class="employee-label">
                        Position
                    </td>
                    <td>
                        : {{ $data['position_job_title'] ?? 'N/A' }}
                    </td>
                </tr>

                <tr>
                    <td class="employee-label">
                        Department / Agency
                    </td>
                    <td>
                        : {{ $data['department_agency'] ?? 'N/A' }}
                    </td>
                </tr>

                <tr>
                    <td class="employee-label">
                        Remarks
                    </td>
                    <td>
                        : {{ $data['remarks'] ?? 'N/A' }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- =====================================================
        ==================== INTERNET ACCESS =====================
        ========================================================== -->
        <div class="section">
            <div class="section-title">
                Internet Access
            </div>

            <table class="justification-table" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="justification-label">
                        Justification
                    </td>
                    <td>
                        {{ $data['internet_access']['Justification'] ?? 'N/A' }}
                    </td>
                </tr>
            </table>

            @if (!empty($data['internet_access']['Details']))
                <table class="data-table" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 100%;">
                                Details
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($data['internet_access']['Details'] as $item)
                            <tr>
                                <td>
                                    {{ $item }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <table class="na-table" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            N/A
                        </td>
                    </tr>
                </table>
            @endif
        </div>


        <!-- =====================================================
        ================ ACCOUNT / SYSTEM ACCESS =================
        ========================================================== -->
        <div class="section">
            <div class="section-title">
                Account / System Access
            </div>

            @if (!empty($data['account_system_access']['Details']))
                <table class="data-table" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 30%;">
                                Access Type
                            </th>

                            <th style="width: 30%;">
                                Account Name
                            </th>

                            <th style="width: 40%;">
                                Remarks
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($data['account_system_access']['Details'] as $account)
                            <tr>
                                <td>
                                    {{ $account['accountSystemAccess'] ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $account['accountSystemName'] ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $account['remark'] ?? 'N/A' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <table class="na-table" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            N/A
                        </td>
                    </tr>
                </table>
            @endif
        </div>

        <!-- =====================================================
        ================= NETWORK FOLDER ACCESS ==================
        ========================================================== -->
        <div class="section">
            <div class="section-title">
                Network Folder Access
            </div>

            @if (!empty($data['network_folder_access']['Details']))
                <table class="data-table" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 20%;">
                                Folder Access
                            </th>

                            <th style="width: 30%;">
                                Folder Name
                            </th>

                            <th style="width: 20%;">
                                Access Type
                            </th>

                            <th style="width: 30%;">
                                Remarks
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($data['network_folder_access']['Details'] as $folder)
                            <tr>
                                <td>
                                    {{ $folder['folder_access'] ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $folder['folder_name'] ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $folder['access_type'] ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $folder['remark'] ?? 'N/A' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <table class="na-table" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            N/A
                        </td>
                    </tr>
                </table>
            @endif
        </div>
    </body>

</html>

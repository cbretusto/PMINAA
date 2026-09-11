<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Access Request Form</title>
</head>

<body style="margin:0;padding:20px;background:#f4f6f9;font-family:Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0"
        style="max-width:900px;margin:auto;background:#ffffff;border-radius:8px;overflow:hidden;">
        <!-- Email Header -->
        <tr>
            <td style="background:#111111;padding:20px;color:#fff;">
                @if (in_array((int) $approval_status, [0, 1, 2, 3, 4]))
                    <p style="margin:0; font-size:16px; line-height:1.6; font-family:Arial, Helvetica, sans-serif;">
                        You have a new <strong>PMINAA Request</strong> awaiting your approval.
                    </p>

                    <p style="margin-top:15px; font-size:14px; color:#fff;">
                        📅 {{ \Carbon\Carbon::now()->toFormattedDateString() }}<br>
                        🕒 {{ \Carbon\Carbon::now()->isoFormat('LT') }}
                    </p>
                @endif

                @if (in_array((int) $approval_status, [6, 7, 8, 9, 10]))
                    <p style="margin:0; font-size:16px; line-height:1.6; font-family:Arial, Helvetica, sans-serif;">
                        Your <strong>PMINAA Request</strong> has been
                        <span style="color:#fecaca; font-weight:bold;">DISAPPROVED</span>.
                    </p>

                    <p style="margin-top:15px; font-size:14px; color:#fff;">
                        📝 <strong> {{ $remark }} </strong><br>
                        📅 {{ \Carbon\Carbon::now()->toFormattedDateString() }}<br>
                        🕒 {{ \Carbon\Carbon::now()->isoFormat('LT') }}
                    </p>
                @endif

                @if ((int) $approval_status == 5)
                    <p style="margin:0; font-size:16px; line-height:1.6; font-family:Arial, Helvetica, sans-serif;">
                        Congratulations! Your <strong>PMINAA Request</strong> has been
                        <span style="color:#bbf7d0; font-weight:bold;">APPROVED</span>.
                    </p>

                    <p style="margin-top:15px; font-size:14px; color:#fff;">
                        📅 {{ \Carbon\Carbon::now()->toFormattedDateString() }}<br>
                        🕒 {{ \Carbon\Carbon::now()->isoFormat('LT') }}
                    </p>
                @endif

            </td>
        </tr>
        <!-- Employee Information -->
        <tr>
            <td style="padding:20px;">
                <h3 style="color:#0a0a0a;border-bottom:2px solid #e5e7eb;padding-bottom:13px;">
                    Employee Information
                </h3>

                <table width="100%" cellpadding="4" cellspacing="0">
                    <tr>
                        <td width="30%"><strong>Control No.</strong></td>
                        <td>: {{ $data['control_no'] }}</td>
                    </tr>

                    <tr>
                        <td width="30%"><strong>Employee No.</strong></td>
                        <td>: {{ $data['employee_no'] }}</td>
                    </tr>

                    <tr>
                        <td><strong>Employee Name</strong></td>
                        <td>:
                            {{ $data['employee_name'] }}
                            {{ $data['employee_lastname'] }}
                        </td>
                    </tr>

                    <tr>
                        <td><strong>User Type</strong></td>
                        <td>: {{ $data['user_type'] }}</td>
                    </tr>

                    <tr>
                        <td><strong>Nature of Employment</strong></td>
                        <td>: {{ $data['nature_of_employment'] }}</td>
                    </tr>

                    <tr>
                        <td><strong>Position</strong></td>
                        <td>: {{ $data['position_job_title'] }}</td>
                    </tr>

                    <tr>
                        <td><strong>Department/Agency</strong></td>
                        <td>: {{ $data['department_agency'] }}</td>
                    </tr>

                    <tr>
                        <td><strong>Remarks</strong></td>
                        <td>: {{ $data['remarks'] }}</td>
                    </tr>
                </table>
            </td>

            @if ((int) $approval_status == 5)
                @php
                    $details = $data['account_system_access']['Details'] ?? [];
                    $isPMI = ($data['user_type'] ?? '') === 'PMI';
                    $systems = collect($details)->pluck('accountSystemAccess')->filter()->unique();
                    $availableSystems = [];

                    if ($systems->contains('Rapid')) {
                        $availableSystems[] = 'Rapid';
                    }

                    if ($isPMI && $systems->contains('SystemOne')) {
                        $availableSystems[] = 'SystemOne';
                    }

                    if ($isPMI && $systems->contains('RapidX')) {
                        $availableSystems[] = 'RapidX';
                    }
                @endphp

                @if (count($availableSystems) > 0)
                    <div
                        style="margin: 10px 0; padding: 14px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;">
                        <div style=" margin-bottom: 18px; color: #1e293b; font-size: 17px; font-weight: 600;">
                            System Access Credentials -
                            {{ implode(', ', $availableSystems) }}
                        </div>

                        <div style="padding: 24px; background: #f8fafc;">
                            {{-- Username --}}
                            <div
                                style=" margin-bottom: 14px; padding: 16px 18px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px;">
                                <div
                                    style=" margin-bottom: 7px; color: #64748b; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                                    Username
                                </div>

                                <div
                                    style=" color: #0f172a; font-size: 15px; font-weight: 600; word-break: break-word;">
                                    {{ $username ?? '' }}
                                </div>
                            </div>

                            {{-- Password --}}
                            <div
                                style=" padding: 16px 18px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px;">
                                <div
                                    style=" margin-bottom: 7px; color: #64748b; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                                    Password
                                </div>

                                <div
                                    style=" color: #0f172a; font-size: 15px; font-weight: 600; word-break: break-word;">
                                    {{ $password ?? 'pmi1234' }}
                                </div>
                            </div>

                            {{-- Security Notice --}}
                            {{-- <div style=" display: flex; align-items: flex-start; margin-top: 16px; padding: 12px 14px; background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px;">
                                    <div style=" color: #92400e; font-size: 12px; line-height: 1.5;">
                                        <strong style="font-weight: 700;">
                                            🔒 Keep your credentials secure.
                                        </strong>
                                        Please do not share your username or password with others.
                                    </div>
                                </div> --}}

                            <div
                                style="display: flex; align-items: flex-start; margin-top: 16px; padding: 12px 14px; background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px;">
                                <div style="color: #92400e; font-size: 12px; line-height: 1.5;"> <strong
                                        style="font-weight: 700;"> 🔒 Keep Your Credentials Secure </strong>
                                    <div>Please do not share your username or password with others.</div>
                                    <div style="margin-top: 4px;"> <strong>Note:</strong> For security purposes, you are
                                        required to change your password upon your first login. </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </tr>

        <!-- Internet Access -->
        <tr style="padding:0 40px 40px">
            <td style=";">
                <h3 style="color:#121213;border-top:2px solid #e5e7eb; border-bottom:2px solid #e5e7eb;padding:8px 0;">
                    Internet Access
                </h3>

                @php
                    $internet = json_decode($data['internet_access'], true);
                @endphp

                <p>
                    <strong>Justification:</strong>
                    {{ $internet['Justification'] }}
                </p>

                <table width="100%" border="1" cellpadding="8" cellspacing="0" style="border-collapse:collapse;">
                    <tr style="background:#f3f4f6;">
                        <th align="left">Details</th>
                    </tr>
                    @foreach ($internet['Details'] as $item)
                        <tr>
                            <td>{{ $item }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>

        <!-- Account System Access -->
        <tr style="padding:0 40px 40px;">
            <td>
                <h3 style="color:#121213;border-top:2px solid #e5e7eb;border-bottom:2px solid #e5e7eb;padding:8px 0;">
                    Account / System Access </h3>
                @if (!empty($data['account_system_access']['Details']))
                    <table width="100%" border="1" cellpadding="8" cellspacing="0"
                        style="border-collapse:collapse;">
                        <thead style="background:#f3f4f6;">
                            <tr>
                                <th>Access Type</th>
                                <th>Account Name</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['account_system_access']['Details'] as $account)
                                <tr>
                                    <td>{{ $account['accountSystemAccess'] ?? '' }}</td>
                                    <td>{{ $account['accountSystemName'] ?? '' }}</td>
                                    <td>{{ $account['remark'] ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </td>
        </tr>

        <!-- Network Folder Access -->
        <tr>
            <td style="padding:0 40px 40px;">
                <h3 style="color:#121213;border-top:2px solid #e5e7eb; border-bottom:2px solid #e5e7eb;padding:8px 0;">
                    Network Folder Access
                </h3>

                <table width="100%" border="1" cellpadding="8" cellspacing="0" style="border-collapse:collapse;">
                    <thead style="background:#f3f4f6;">
                        <tr>
                            <th>Folder Access</th>
                            <th>Folder Name</th>
                            <th>Access Type</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['network_folder_access']['Details'] as $folder)
                            <tr>
                                <td>{{ $folder['folder_access'] ?? '' }}</td>
                                <td>{{ $folder['folder_name'] ?? '' }}</td>
                                <td>{{ $folder['access_type'] ?? '' }}</td>
                                <td>{{ $folder['remark'] ?? '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>

        <tr>
            <td style="padding:20px;border-top:1px solid #e5e7eb;">
                <p style="margin:0 0 15px 0;color:#374151;font-size:14px;line-height:1.6;">
                    Please login your Rapidx account to get more information.
                    Locate the PMI Network Account Activation System v2 at
                    <a href="http://rapidx/" style="color:#2563eb;text-decoration:none;">
                        RapidX
                    </a>.
                </p>

                <!-- Disclaimer -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0 0 20px 0;">
                    <tr>
                        <td style="padding:10px;">
                            <div style="background:#fff8e1;border-left:4px solid #f59e0b;padding:15px;">
                                <p style="margin:0 0 8px 0;font-weight:bold;color:#92400e;">
                                    Notice of Disclaimer
                                </p>
                                <p style="margin:0;color:#78350f;font-size:13px;line-height:1.6;">
                                    This message contains confidential information intended for a specific
                                    individual and purpose. If you are not the intended recipient, you should
                                    delete this message. Any disclosure, copying, or distribution of this
                                    message, or the taking of any action based on it, is strictly prohibited.
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>

                <!-- ISS Service Request -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="padding:10px;">
                            <div style="background:#eff6ff;border:1px solid #bfdbfe;padding:15px;border-radius:4px;">
                                <p style="margin:0;font-size:14px;font-weight:bold;color:#1e3a8a;line-height:1.6;">
                                    For concerns on using the system, please contact ISS at local numbers
                                    205, 206, or 208, or file a ticket at
                                    <a href="http://rapidx/iss_service_request/"
                                        style="color:#2563eb;text-decoration:none;">
                                        ISS Service Request
                                    </a>.
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background:#f3f4f6;padding:15px;text-align:center;color:#6b7280;font-size:12px;">
                This is an automated email generated by the PMI Network Account Activation System v2.
            </td>
        </tr>
    </table>
</body>

</html>

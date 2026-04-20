<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
</head>
<body style="margin:0;padding:0;background:#f7f7f8;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
    @php
        $verificationUrl = $mailData['verification_url'] ?? '#';
        $recipientName = $mailData['name'] ?? 'there';
    @endphp

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#f7f7f8;width:100%;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:620px;background:#ffffff;border:1px solid #e5e7eb;border-radius:20px;overflow:hidden;box-shadow:0 12px 28px rgba(15,23,42,0.06);">
                    <tr>
                        <td style="background:#ff385c;height:8px;line-height:8px;font-size:0;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:30px 28px 8px;">
                            <div style="display:inline-flex;align-items:center;justify-content:center;padding:8px 14px;border-radius:999px;background:rgba(255,56,92,0.08);color:#ff385c;font-size:11px;font-weight:800;letter-spacing:0.14em;text-transform:uppercase;">
                                FindNest
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:10px 28px 0;text-align:center;">
                            <h1 style="margin:0;color:#111827;font-size:26px;line-height:1.2;font-weight:800;letter-spacing:-0.03em;">
                                Confirm your email
                            </h1>
                            <p style="margin:12px 0 0;color:#6b7280;font-size:15px;line-height:1.7;">
                                Hi {{ $recipientName }}, please confirm your email to finish setting up your FindNest account.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px 28px 0;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $verificationUrl }}"
                                           style="display:inline-block;background:#ff385c;color:#ffffff;text-decoration:none;font-size:15px;font-weight:700;line-height:1;border-radius:12px;padding:14px 28px;box-shadow:0 10px 18px rgba(255,56,92,0.16);">
                                            Verify Email
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px 28px 28px;text-align:center;">
                            <p style="margin:0;color:#6b7280;font-size:12px;line-height:1.6;">
                                If you did not register, you can ignore this email safely.
                            </p>
                            <p style="margin:10px 0 0;color:#9ca3af;font-size:11px;line-height:1.6;">
                                Need help? Contact support@findnest.com
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

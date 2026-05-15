<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FindNest Contact Message</title>
</head>
<body style="margin:0; padding:0; background:#f8fafc; font-family:Arial, sans-serif; color:#0f172a;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f8fafc; padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px; background:#ffffff; border:1px solid #e5e7eb; border-radius:18px; overflow:hidden;">
                    <tr>
                        <td style="padding:22px 24px; border-bottom:1px solid #eef2f7;">
                            <p style="margin:0 0 6px; color:#ff385c; font-size:13px; font-weight:700;">FindNest Contact Form</p>
                            <h1 style="margin:0; font-size:22px; line-height:1.3;">New support message received</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:22px 24px;">
                            <p style="margin:0 0 14px; color:#475569; font-size:14px; line-height:1.6;">
                                A visitor submitted the Contact Us form on FindNest.
                            </p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;">
                                <tr>
                                    <td style="padding:10px 0; color:#64748b; font-size:13px; width:120px;">Name</td>
                                    <td style="padding:10px 0; font-size:14px; font-weight:700;">{{ $contact['name'] }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0; color:#64748b; font-size:13px;">Email</td>
                                    <td style="padding:10px 0; font-size:14px;">
                                        <a href="mailto:{{ $contact['email'] }}" style="color:#ff385c; text-decoration:none;">{{ $contact['email'] }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0; color:#64748b; font-size:13px;">Subject</td>
                                    <td style="padding:10px 0; font-size:14px; font-weight:700;">{{ $contact['subject'] }}</td>
                                </tr>
                            </table>

                            <div style="margin-top:18px; padding:16px; border:1px solid #e5e7eb; border-radius:14px; background:#fbfdff;">
                                <p style="margin:0 0 8px; color:#64748b; font-size:13px; font-weight:700;">Message</p>
                                <p style="margin:0; white-space:pre-line; color:#0f172a; font-size:14px; line-height:1.7;">{{ $contact['message'] }}</p>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

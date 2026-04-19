<!DOCTYPE html>
<html>
<head>
    <title>Verify Your Email</title>
</head>
<body>
    <div style="margin: 8px 0 24px; text-align: center;">
        <img src="{{ asset('images/findnest-logo.png') }}" alt="FindNest" style="display:inline-block;width:220px;max-width:100%;height:auto;">
    </div>

    <h2>Hello {{ $mailData['name'] }},</h2>
    <p>Thank you for registering at FindNest.</p>
    <p>Please verify your email by clicking the button below:</p>
    <a href="{{ $mailData['verification_url'] }}" 
       style="padding:10px 20px; background-color:blue; color:white; text-decoration:none;">
       Verify Email
    </a>
    <p style="margin-top: 16px;">If the button does not work, copy and paste this link into your browser:</p>
    <p><a href="{{ $mailData['verification_url'] }}">{{ $mailData['verification_url'] }}</a></p>
    <p>If you did not register, please ignore this email.</p>
</body>
</html>

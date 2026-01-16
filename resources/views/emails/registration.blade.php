<!DOCTYPE html>
<html>
<head>
    <title>Verify Your Email</title>
</head>
<body>
    <h2>Hello {{ $mailData['name'] }},</h2>
    <p>Thank you for registering at FindNest.</p>
    <p>Please verify your email by clicking the button below:</p>
    <a href="{{ url('/verify-email/'.$mailData['token']) }}" 
       style="padding:10px 20px; background-color:blue; color:white; text-decoration:none;">
       Verify Email
    </a>
    <p>If you did not register, please ignore this email.</p>
</body>
</html>

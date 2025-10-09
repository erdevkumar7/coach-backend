<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset Request</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            background-color: #ffffff;
            margin: 40px auto;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .header {
            background-color: #4B49AC;
            color: #ffffff;
            text-align: center;
            padding: 20px 10px;
        }

        .header h2 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }

        .body {
            padding: 30px;
            line-height: 1.6;
            color: #444;
        }

        .body p {
            margin-bottom: 16px;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            margin-top: 20px;
            background-color: #4B49AC;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .footer {
            background-color: #f0f0f0;
            text-align: center;
            padding: 15px;
            font-size: 13px;
            color: #777;
        }

        .footer a {
            color: #4B49AC;
            text-decoration: none;
        }

    </style>
</head>
<body>

    <div class="email-container">
        <div class="header">
            <h2>Reset Your Password</h2>
        </div>

        <div class="body">
            <p>Hi <strong>{{ $user->first_name }}</strong>,</p>
            <p>We received a request to reset your password for your <strong>Coach Sparkle</strong> account.</p>
            <p>Click the button below to reset your password. This link will be valid for a limited time.</p>

            <a href="{{ url('api/verify-reset-token/' . $token) }}" class="btn">Reset Password</a>

            <p>If you didn’t request this, you can safely ignore this email.</p>
            <p>Thanks,<br>The Coach Sparkle Team</p>
        </div>

        <div class="footer">
            <p>Need help? <a href="mailto:support@coachsparkle.com">Contact Support</a></p>
            <p>&copy; {{ date('Y') }} Coach Sparkle. All rights reserved.</p>
        </div>
    </div>

</body>
</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Welcome Email</title>
</head>

<body style="margin: 0; padding: 0; font-family: Poppins, sans-serif; background-color: #f4f4f4;">
    <table cellpadding="0" cellspacing="0" width="100%" style="background-color: #f4f4f4; padding: 40px 0;">
        <tr>
            <td align="center">
                <table cellpadding="0" cellspacing="0" width="600"
                    style="background-color: #ffffff; border-radius: 10px; padding: 32px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
                    <tr>
                        <td align="center" style="padding-bottom: 10px;">
                            <!-- <img src="https://coachsparkle-backend.votivereact.in/public/assets/imges/logo.png"
                                alt="Coach Sparkle Logo" width="130" style="display: block; margin-bottom: 10px;"> -->
                                 <img src="{{ url('public/assets/imges/logo.png') }}"
                                alt="Coach Sparkle Logo" width="130" style="display: block; margin-bottom: 10px;">
                        </td>
                    </tr>
                    <tr>
                        <td align="center"
                            style="font-size: 22px; font-weight: 600; color: #1C1C1E; padding-bottom: 20px;">
                            Welcome to Coach Sparkle
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 15px; color: #1C1C1E; padding-bottom: 10px; text-align: center;">
                            Hi <strong>{{ ucwords($first_name) }} {{ ucwords($last_name) }}</strong>,
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 15px; color: #1C1C1E padding-bottom: 20px;text-align: center;">
                            Thanks for signing up! We’re excited to have you on board. To get started, please verify
                            your email address by clicking the button below.
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="font-size: 15px; color: #1C1C1E; padding-bottom: 25px;text-align: center;padding-top: 15px;">
                            This helps us keep your account safe and secure.
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding-bottom: 30px;">
                            <a href="{{ url('/api/email/changeStatus') }}?user_id={{ $user_id }}"
                                style="background-color: #009BFA; color: #ffffff; text-decoration: none; padding: 12px 22px; border-radius: 10px; font-weight: 600; display: inline-block;">Verify
                                Email</a>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 15px; color: #1C1C1E; padding-bottom: 20px; text-align: center;">
                            If you didn’t create this account, you can safely ignore this email.
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 15px; color: #1C1C1E; padding-bottom: 20px;text-align: center;">
                            Cheers,<br>
                            Coach Sparkle Team
                        </td>
                    </tr>
                    <tr>
                        <td align="center"
                            style="font-size: 14px; color: #1C1C1E; text-align: center;padding-top: 10px;">
                            © 2025 CoachSparkle. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>

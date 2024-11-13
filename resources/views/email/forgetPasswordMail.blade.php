<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .email-container {
            max-width: 650px;
            margin: 0 auto;
            padding-bottom: 20px;
            background-color: #e9ecef !important;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            background-color: #3a4752;
            color: #ffffff;
            padding: 20px 0;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            margin: 20px 0;
            padding: 0 20px;
            line-height: 1.6;
            color: #333333;
        }

        .button-container {
            text-align: center;
            margin: 30px 0;
        }

        .button {
            display: inline-block;
            background-color: #dc3545;
            color: #ffffff !important;
            padding: 15px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .button:hover {
            background-color: #c82333;
            box-shadow: 0 6px 15px rgba(220, 53, 69, 0.4);
        }

        .footer {
            text-align: center;
            color: #777777;
            font-size: 12px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1>Password Reset Form</h1>
        </div>
        <div class="content">
            <p>Hi {{ $username }},</p>
            <p>We received a request to reset your password. You can reset your password by clicking the button below:
            </p>
            <div class="button-container">
                <a href="{{ route('changePasswordForm', ['token' => $token, 'username' => $username]) }}"
                    class="button">Reset Password</a>
            </div>
            <p>If you did not request a password reset, please ignore this email or contact support if you have
                concerns.</p>
            <br>
            <p>Thank you,</p>
            <p>Grandeur Realty Team</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} Grandeur Realty. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
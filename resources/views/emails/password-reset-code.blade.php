<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Reset Code</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 20px;">
    <table style="max-width: 600px; margin: auto; background: #fff; border-radius: 8px; padding: 30px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <tr>
            <td style="text-align: center;">
                <h2 style="color: #007BFF;">Password Reset Request</h2>
                <p style="font-size: 16px; color: #333;">You requested to reset your password. Please use the code below:</p>
                <div style="margin: 30px 0;">
                    <span style="font-size: 36px; letter-spacing: 6px; color: #222; font-weight: bold;">
                        {{ $code }}
                    </span>
                </div>
                <p style="color: #555;">If you did not request this, please ignore this email.</p>
                <p style="font-size: 12px; color: #999;">Tourism App - Syria</p>
            </td>
        </tr>
    </table>
</body>
</html>

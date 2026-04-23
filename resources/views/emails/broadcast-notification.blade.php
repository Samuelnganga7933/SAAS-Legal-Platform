<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #a855f7 0%, #d946ef 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 4px 4px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 0 0 4px 4px;
        }
        .message-box {
            background: white;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #a855f7;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            font-size: 12px;
            color: #888;
            text-align: center;
        }
        .button {
            display: inline-block;
            background: #a855f7;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 15px;
        }
        .badge {
            display: inline-block;
            background: #a855f7;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📣 Important Announcement</h1>
        </div>
        
        <div class="content">
            <p>Hello {{ $clientName }},</p>
            
            <p>The admin team has sent an important announcement to all clients:</p>
            
            <div class="message-box">
                <span class="badge">ANNOUNCEMENT</span>
                <h2 style="margin: 10px 0 0 0;">{{ $subject }}</h2>
                <hr style="border: none; border-top: 1px solid #eee; margin: 15px 0;">
                <p>{!! nl2br(e($body)) !!}</p>
            </div>
            
            <p style="margin-top: 20px; font-style: italic; color: #666;">
                Message from: <strong>{{ $adminName }}</strong> (Admin)
            </p>
            
            <p>Please log in to your account to view all announcements.</p>
            
            <a href="{{ url('/login') }}" class="button">View in Your Account</a>
            
            <div class="footer">
                <p>© {{ date('Y') }} Le Nium Advisors. All rights reserved.</p>
                <p>You received this email because you're a registered client. Please do not reply to this email.</p>
            </div>
        </div>
    </div>
</body>
</html>

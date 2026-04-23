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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            border-left: 4px solid #667eea;
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
            background: #667eea;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📨 New Message from Admin</h1>
        </div>
        
        <div class="content">
            <p>Hello {{ $clientName }},</p>
            
            <p>You have received a new message from the admin:</p>
            
            <div class="message-box">
                <strong>Subject:</strong> {{ $subject }}
                <br><br>
                <strong>Message:</strong><br>
                {!! nl2br(e($body)) !!}
            </div>
            
            <p style="margin-top: 20px;">Please log in to your account to read the full message and reply if needed.</p>
            
            <a href="{{ url('/login') }}" class="button">View Your Messages</a>
            
            <div class="footer">
                <p>© {{ date('Y') }} Le Nium Advisors. All rights reserved.</p>
                <p>You received this email because you're a registered client. Please do not reply to this email.</p>
            </div>
        </div>
    </div>
</body>
</html>

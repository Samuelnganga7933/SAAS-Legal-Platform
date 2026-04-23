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
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
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
        .client-info {
            background: white;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #3b82f6;
            border-radius: 4px;
        }
        .message-box {
            background: white;
            padding: 15px;
            margin: 15px 0;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
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
            background: #3b82f6;
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
            <h1>📬 New Client Message</h1>
        </div>
        
        <div class="content">
            <p>Hello Admin,</p>
            
            <p>A client has sent you a new message:</p>
            
            <div class="client-info">
                <strong>From:</strong> {{ $clientName }}<br>
                <strong>Email:</strong> {{ $clientEmail }}
            </div>
            
            <div class="message-box">
                <strong style="color: #3b82f6;">{{ $subject }}</strong>
                <hr style="border: none; border-top: 1px solid #eee; margin: 10px 0;">
                <p>{!! nl2br(e($body)) !!}</p>
            </div>
            
            <p style="margin-top: 20px;">Please log in to the admin dashboard to reply to this message.</p>
            
            <a href="{{ url('/admin/messages/inbox') }}" class="button">View in Admin Dashboard</a>
            
            <div class="footer">
                <p>© {{ date('Y') }} Le Nium Advisors. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>

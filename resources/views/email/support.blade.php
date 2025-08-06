<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .header {
            text-align: center;
            font-size: 1.2rem;
            color: #555;
            margin-bottom: 20px;
        }
        .content {
            margin-top: 10px;
        }
        .footer {
            margin-top: 20px;
            font-size: 0.9rem;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>New Support Request</h2>
        </div>

        <div class="content">
            <p><strong>Full Name:</strong> {{$fullName }}</p>
            <p><strong>Email Address:</strong> {{ $email }}</p>
            <p><strong>Country:</strong> {{ $country }}</p>
            <p><strong>Message:</strong></p>
            <p>{{ $body }}</p>
        </div>

        <div class="footer">
            <p>This is an automated email. Please do not reply directly to this email.</p>
        </div>
    </div>
</body>
</html>

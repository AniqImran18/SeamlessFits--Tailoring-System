<!DOCTYPE html>
<html>
<head>
    <title>Discount Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #6a11cb;
        }
        p {
            font-size: 16px;
            color: #333;
            line-height: 1.6;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h1>Congratulations! You've Earned a Discount 🎉</h1>
        <p>Dear Customer,</p>
        <p>Thank you for being a loyal customer! You have placed 5 orders with us, which qualifies you for a <strong>10% discount</strong> on all future orders.</p>
        <p>We truly appreciate your continued support and look forward to serving you again soon!</p>
        <p>Your discount will be applied automatically to your future orders. If you have any questions, feel free to reach out to us.</p>
        <p>Best Regards,</p>
        <p><strong>Orchid Tailoring Team</strong></p>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Orchid Tailoring. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

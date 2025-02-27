<!DOCTYPE html>
<html>
<head>
    <title>Your Order Has Been Accepted</title>
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
        <h1>Your Order Has Been Accepted</h1>
        <p>Dear {{ $order->customer->name }},</p>
        <p>We are pleased to inform you that your order (Order ID: <strong>{{ $order->orderID }}</strong>) has been accepted by our tailor and is now being processed.</p>
        <p>Here are the details of your order:</p>
        <ul>
            <li><strong>Order Date:</strong> {{ $order->date }}</li><br>
            <li><strong>Estimated Completion:</strong> Approximately one week from the order date.</li>
        </ul>
        <p>We will notify you once your order is ready for pickup. If you have any questions, feel free to contact us.</p>
        <p>Thank you for choosing Orchid Tailoring!</p>
        <p>Best Regards,</p>
        <p><strong>Orchid Tailoring Team</strong></p>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Orchid Tailoring. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

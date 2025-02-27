<!DOCTYPE html>
<html>
<head>
    <title>Your Order is Complete</title>
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
        <h1>Order Completed</h1>
        <p>Dear {{ $order->customer->name }},</p>
        <p>We are pleased to inform you that your order (Order ID: <strong>{{ $order->orderID }}</strong>) has been successfully completed.</p>
        <p>Your order is now ready for pickup. Please visit our store to collect it at your earliest convenience.</p>
        <p>If you have any questions, feel free to contact us.</p>
        <p>Thank you for choosing Orchid Tailoring. We appreciate your trust in our services!</p>
        <p>Best Regards,</p>
        <p><strong>Orchid Tailoring Team</strong></p>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Orchid Tailoring. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

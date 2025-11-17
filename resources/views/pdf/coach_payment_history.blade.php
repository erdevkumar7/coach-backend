<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Receipt</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #333;
            font-size: 13px;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 20px auto;
            background: #fff;
            padding: 25px 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #1a73e8;
            margin-bottom: 10px;
        }

        h3 {
            text-align: center;
            margin-top: 0;
            color: #444;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        table th, table td {
            text-align: left;
            padding: 10px 8px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        th {
            background-color: #f7f9fc;
            width: 35%;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }

        .highlight {
            color: #1a73e8;
            font-weight: 600;
        }

        .user-info {
            text-align: center;
            margin-bottom: 15px;
        }

        .user-info span {
            display: inline-block;
            background: #1a73e8;
            color: #fff;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">

        <h1>Payment Receipt</h1>

        <table>
            <tr>
                <th>Coach Name</th>
                <td>{{ $paymentHistory->coach_name ?? '' }}</td>
            </tr>
            <tr>
                <th>Plan Name</th>
                <td>{{ $paymentHistory->plan_name ?? '' }}</td>
            </tr>
            <tr>
                <th>Amount</th>
                <td>${{ $paymentHistory->amount ?? '' }}</td>
            </tr>
            <tr>
                <th>Plan Start Date</th>
                <td>{{ \Carbon\Carbon::parse($paymentHistory->start_date)->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <th>Plan End Date</th>
                <td>{{ \Carbon\Carbon::parse($paymentHistory->end_date)->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <th>transection Id</th>
                <td>{{ $paymentHistory->txn_id ?? '' }}</td>
            </tr>
            <tr>
                <th>Payment Date</th>
                <td>{{ \Carbon\Carbon::parse($paymentHistory->created_at)->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <th>Payment status</th>
                <td>Paid</td>
            </tr>           
        </table>

        <div class="footer">
            <p>Generated automatically by <strong>CoachSparkle</strong> on {{ now()->format('d M, Y') }}</p>
        </div>
    </div>
</body>
</html>

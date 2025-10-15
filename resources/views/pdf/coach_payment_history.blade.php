<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <h1>Coach Payment History</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Plan Name</th>
                <th>Plan Content</th>
                <th>Amount</th>
                <th>Transaction ID</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Payment Status</th>
                <th>Payment Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($paymentHistory as $payment)
                <tr>
                    <td>{{ $payment['id'] }}</td>
                    <td>{{ $payment['plan_name'] }}</td>
                    <td>{{ $payment['plan_content'] }}</td>
                    <td>{{ $payment['amount'] }}</td>
                    <td>{{ $payment['txn_id'] }}</td>
                    <td>{{ $payment['start_date'] }}</td>
                    <td>{{ $payment['end_date'] }}</td>
                    <td>{{ $payment['payment_status'] }}</td>
                    <td>{{ $payment['payment_date'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8">

    <title>Payment Receipt</title>

    <style>

        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }

        h1, h3 { color: #333; }

        .section { margin-bottom: 20px; }

        table { width: 100%; border-collapse: collapse; }

        td, th { padding: 8px; border: 1px solid #ccc; }

    </style>

</head>

<body>

<!-- <div style="text-align: center; margin-bottom: 30px;">

  <a href="{{ url('/') }}" class="logo mr-auto">

    <img src="{{ asset('/public/admin_assets/images/main_logo.png') }}" alt="" class="img-fluid" style="width:135px;">
  </a>

</div> -->

    <h1>Payment Receipt</h1>
        <table>
            <tr><td style="width: 25%;"><strong>Coach Name:</strong></td><td>{{ $paymentHistory->coach_name ?? '' }}</td></tr>
            <tr><td style="width: 25%;"><strong>Plan Name:</strong></td><td>{{ $paymentHistory->plan_name ?? '' }}</td></tr>
            <tr><td style="width: 25%;"><strong>Amount:</strong></td><td>${{ $paymentHistory->amount ?? '' }}</td></tr>
            <tr><td style="width: 25%;"><strong>Plan Start Date:</strong></td><td>{{ \Carbon\Carbon::parse($paymentHistory->start_date)->format('d-m-Y') }}</td></tr>
            <tr><td style="width: 25%;"><strong>Plan End Date:</strong></td><td>{{ \Carbon\Carbon::parse($paymentHistory->end_date)->format('d-m-Y') }}</td></tr>
            <tr><td style="width: 25%;"><strong>transection Id:</strong></td><td>{{ $paymentHistory->txn_id ?? '' }}</td></tr>
            <tr><td style="width: 25%;"><strong>Payment Date:</strong></td><td>{{ \Carbon\Carbon::parse($paymentHistory->created_at)->format('d-m-Y') }}</td></tr>
            <tr><td style="width: 25%;"><strong>Payment status:</strong></td><td>Paid</td></tr>
        </table>
    </div>

</body>

</html>


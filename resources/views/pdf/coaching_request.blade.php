<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Coaching Request</title>
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

        <h1>Coaching Request</h1>
        <h3>Requested by {{ $user->first_name ?? $user->name ?? 'N/A' }}</h3>

        <div class="user-info">
            <span>Email: {{ $user->email ?? 'N/A' }}</span>
            @if(!empty($user->country->country_name))
                <span>Country: {{ $user->country->country_name }}</span>
            @endif
        </div>

        <table>
            <tr>
                <th>I am looking for</th>
                <td>{{ $data['type_of_coaching'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Sub Coaching Category</th>
                <td>{{ $data['sub_coaching_category'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Preferred Mode of Delivery</th>
                <td>{{ $data['preferred_mode_of_delivery'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Location</th>
                <td>{{ $data['location'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Goal / Objective</th>
                <td>{{ $data['goal_or_objective'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Language Preference</th>
                <td>{{ $data['language_preference'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Preferred Communication Channel</th>
                <td>{{ $data['preferred_communication_channel'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Target Age Group</th>
                <td>{{ $data['target_age_group'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Preferred Teaching Style</th>
                <td>{{ $data['preferred_teaching_style'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Budget Range</th>
                <td>{{ $data['budget_range'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Coach Gender Preference</th>
                <td>{{ $data['coach_gender'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Coach Experience Level</th>
                <td>{{ $data['coach_experience_level'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Only Certified Coach</th>
                <td>{{ $data['only_certified_coach'] ?? 'No' }}</td>
            </tr>
            <tr>
                <th>Preferred Start Date / Urgency</th>
                <td>{{ $data['preferred_start_date_urgency'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Special Requirements</th>
                <td>{{ $data['special_requirements'] ?? 'Optional' }}</td>
            </tr>
            <tr>
                <th>Share With Coaches</th>
                <td>{{ $data['share_with_coaches'] ?? 'No' }}</td>
            </tr>
        </table>

        <div class="footer">
            <p>Generated automatically by <strong>CoachSparkle</strong> on {{ now()->format('d M, Y') }}</p>
        </div>
    </div>
</body>
</html>

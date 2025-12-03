@extends('admin.layouts.layout') @section('content')
    <style>
        .leaderboard-scroll-add {
            overflow-y: scroll;
            height: 212px;
        }
    </style>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="container">
                <div class="row total-registered">
                    <!-- Bar Chart Section -->
                    <div class="col-md-8">
                        <div class="chart-card">
                            <h3 class="total-text">Total Registered Coaches and Users</h3>
                            <div style="height: 350px;">
                                <canvas id="coachesUsersChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Donut Chart Section -->
                    <div class="col-md-4 mx-auto revenue-chart">
                        <div class="card card-custom">
                            <h5 class="mb-4">Revenue Growth</h5>

                            <div class="chart-container">
                                <canvas id="revenueChart"></canvas>
                            </div>

                            <div class="mt-3 total-revenue-text">
                                <p class="mb-2"><span class="stat-label">Total Revenue</span> <span class="stat-value">
                                        ${{ $totalRevenue }}</span></p>
                                <p class="mb-2"><span class="stat-label">Monthly Recurring Revenue</span> <span
                                        class="stat-value">${{ $totalRevenueThisMonth }}</span></p>
                                <p class="mb-2"><span class="stat-label">Pro Plan Conversions</span> <span
                                        class="stat-value">{{ $proCoachUsers }}</span></p>
                                <!-- <p class="mb-0"><span class="stat-label">Transaction Value</span> <span
                                        class="stat-value">$219,900</span></p> -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row platform-usage">
                    <div class="col-md-6 platform-card">
                        <div class="card-custom">
                            <h5>Platform Usage</h5>
                            <div class="row">
                                <div class="col-md-6 col-6 mb-2">
                                    <span>Total Registered Users</span><br />
                                    <span class="stat-value">{{ $totalUser }}</span>
                                </div>
                                <div class="col-md-6 col-6 mb-2">
                                    <span>Daily Active Users</span><br />
                                    <span class="stat-value">{{ $dailyActiveUsers }}</span>
                                </div>
                                <div class="col-md-6 col-6 mb-2">
                                    <span>Monthly Active Users</span><br />
                                    <span class="stat-value">{{ $monthlyActiveUsers }}</span>
                                </div>
                                <div class="col-md-6 col-6 mb-2">
                                    <span>Users Retention Rate</span><br />
                                    <span class="stat-value">{{ $usersRetentionRate }}%</span>
                                </div>
                                <div class="col-md-6 col-6 mb-2">
                                    <span>Average Session Duration</span><br />
                                    <span class="stat-value">{{ $averageSessionDuration }} min</span>
                                </div>
                                <!-- <div class="col-md-6 col-6 mb-2">
                                    <span>User Onboarding Completion</span><br />
                                    <span class="stat-value">76%</span>
                                </div> -->
                            </div>
                        </div>

                        <!-- <div class="col-md-6 right-activity">
                            <div class="stats-card">
                                <h5>Coach & User Activity</h5>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <h6>Active Coaching This Month</h6>
                                        <div class="stats-value">{{ $activeCoachingThisMonth }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Coach Rating Distribution ({{ number_format($totalCoachAvgRating, 1) }}/5)</h6>
                                        <div class="star-rating">
                                            @php
                                                $rating = round($totalCoachAvgRating); // round to nearest star
                                            @endphp

                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $rating)
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                @else
                                                    <i class="bi bi-star text-warning"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <h6 class="canceled-add-point">Total Coaching Canceled</h6>
                                        <div class="stats-value">{{ $totalCoachingCanceled }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Coach Conversion Rate</h6>
                                        <small class="request-text">(Matched Request to Confirmed Session)</small>
                                        <div class="stats-value">{{ $Matched_Request_to_Confirmed_Session }}%</div>

                                    </div>

                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6 completed">
                                        <h6>Total Coaching Completed</h6>
                                        <div class="stats-value">{{ $totalCoachingCompleted }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Coach Response Time (avg)</h6>
                                        <div class="stats-value">40 min</div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6 completed">
                                        <h6>Total Messages</h6>
                                        <div class="stats-value">{{ $totalMessages }}</div>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                        <div class="col-md-6 right-activity">
                            <div class="stats-card">
                                <h5>Coach & User Activity</h5>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <h6>Active Coaching This Month</h6>
                                        <div class="stats-value">{{ $activeCoachingThisMonth }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Total Coaching Canceled</h6>
                                      <div class="stats-value">{{ $totalCoachingCanceled }}</div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <h6 class="canceled-add-point">Total Coaching Completed</h6>
                                        <div class="stats-value">{{ $totalCoachingCompleted }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Total Messages</h6>
                                        <div class="stats-value">{{ $totalMessages }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6 leaderboard-card">
                        <!-- Leaderboard Section -->
                        <h5 class="mb-3 fw-bold">Leaderboard</h5>
                        <p class="mb-3">Top Coaches By Revenue / Rating / Sessions / Favorite</p>

                        <div class="leaderboard-scroll-add">
                            @foreach ($topCoaches as $coach)
                                <div id="{{ $coach->id }}"
                                    class="d-flex align-items-center justify-content-between mb-3 bates-add">
                                    <div class="d-flex align-items-center">
                                        {{-- <img src="{{ asset('public/uploads/profile_image/' . $coach->profile_image) }}"
                                            class="rounded-circle coach-img me-2" alt="coach" /> --}}

                                        <img src="{{ asset('public/uploads/profile_image/' . $coach->profile_image) }}"
                                            onerror="this.onerror=null; this.src='{{ asset('public/uploads/default_images/default_profile.jpg') }}';"
                                            class="rounded-circle coach-img me-2" alt="coach" />

                                        <span class="coach-name">{{ $coach->first_name }} {{ $coach->last_name }}</span>
                                    </div>
                                    <span class="coach-value">${{ number_format($coach->total_revenue ?? 0, 2) }}</span>
                                </div>
                            @endforeach


                            {{-- <div class="d-flex align-items-center justify-content-between mb-3 bates-add">
                                <div class="d-flex align-items-center">
                                    <img src="/coach-backend/public/assets/imges/ellipse-two.png" class="coach-img me-2"
                                        alt="coach" />
                                    <span class="coach-name">Ryan Seacrest</span>
                                </div>
                                <span class="coach-value">$28,600</span>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-3 bates-add">
                                <div class="d-flex align-items-center">
                                    <img src="/coach-backend/public/assets/imges/ellipse-one.png" class="coach-img me-2"
                                        alt="coach" />
                                    <span class="coach-name">Kate Kidson</span>
                                </div>
                                <span class="coach-value">$14,300</span>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-3 bates-add">
                                <div class="d-flex align-items-center">
                                    <img src="/coach-backend/public/assets/imges/ellipse-two.png" class="coach-img me-2"
                                        alt="coach" />
                                    <span class="coach-name">Jennifer Bates</span>
                                </div>
                                <span class="coach-value">$14,300</span>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-3 bates-add">
                                <div class="d-flex align-items-center">
                                    <img src="/coach-backend/public/assets/imges/ellipse-one.png" class="coach-img me-2"
                                        alt="coach" />
                                    <span class="coach-name">Ryan Seacrest</span>
                                </div>
                                <span class="coach-value">$14,300</span>
                            </div> --}}
                        </div>
                       <br> 
                        <!-- <a href="#" class="view-more">View more</a> -->

                        <!-- Most Engaged Coaches -->

                        <h6 class="mt-4 fw-bold">Top Most Engaged Coaches</h6>

                        @foreach ($topEngagedCoaches as $coach)
                            <div class="d-flex align-items-center justify-content-between mt-3 bates-add">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('public/uploads/profile_image/' . $coach->profile_image) }}"
                                        onerror="this.onerror=null; this.src='{{ asset('public/uploads/default_images/default_profile.jpg') }}';"
                                        class="rounded-circle coach-img me-2" alt="coach" />
                                    <span class="coach-name">{{ $coach->first_name }} {{ $coach->last_name }}</span>
                                </div>
                                <div class="engaged-text">
                                    <a href="#">{{ $coach->session_count }} Sessions Booked</a>
                                    <a href="#">{{ $coach->message_count }} Messages Received</a>
                                    {{-- <a href="#">{{ $coach->match_count }} Matches</a> --}}
                                </div>
                            </div>
                        @endforeach

                        {{-- <h6 class="mt-4 fw-bold">Top 5 Most Engaged Coaches</h6>
                        <div class="d-flex align-items-center justify-content-between mt-3 bates-add">
                            <div class="d-flex align-items-center">
                                <img src="/coach-backend/public/assets/imges/ellipse-two.png" class="coach-img me-2"
                                    alt="coach" />
                                <span class="coach-name">Jennifer Bates</span>
                            </div>
                            <div class="engaged-text">
                                <a href="#">48 Sessions Booked</a>
                                <a href="#">120 messages received</a>
                                <a href="#">100 matches</a>
                            </div>
                        </div> --}}
                    </div>

                </div>


            </div>
        </div>

        <script>
            // Coaches & Users Bar Chart
            // const ctxBar = document.getElementById("coachesUsersChart").getContext("2d");
            // new Chart(ctxBar, {
            //     type: "bar",
            //     data: {
            //         labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
            //         datasets: [
            //             {
            //                 label: "Coaches",
            //                 data: [5, 10, 15, 20, 22, 28],
            //                 backgroundColor: "rgba(221,160,221,0.8)", // light purple
            //                 borderRadius: 6,
            //             },
            //             {
            //                 label: "Users",
            //                 data: [7, 14, 18, 24, 26, 30],
            //                 backgroundColor: "rgba(135,206,250,0.8)", // light blue
            //                 borderRadius: 6,
            //             },
            //             {
            //                 label: "Users Growth Rate",
            //                 data: [8, 12, 20, 27, 29, 35],
            //                 backgroundColor: "rgba(255,182,193,0.8)", // light pink
            //                 borderRadius: 6,
            //             },
            //         ],
            //     },
            //     options: {
            //         responsive: true,
            //         maintainAspectRatio: false,
            //         scales: {
            //             y: {
            //                 beginAtZero: true,
            //             },
            //         },
            //         plugins: {
            //             legend: {
            //                 position: "top",
            //                 labels: {
            //                     usePointStyle: true,
            //                     pointStyle: "circle",
            //                 },
            //             },
            //         },
            //     },
            // });


            const ctxBar = document.getElementById("coachesUsersChart").getContext("2d");
            new Chart(ctxBar, {
                type: "bar",
                data: {
                    labels: @json($months),
                    datasets: [{
                            label: "Coaches",
                            data: @json($coaches),
                            backgroundColor: "rgba(221,160,221,0.8)",
                            borderRadius: 6,
                        },
                        {
                            label: "Users",
                            data: @json($users),
                            backgroundColor: "rgba(135,206,250,0.8)",
                            borderRadius: 6,
                        }
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                        },
                    },
                    plugins: {
                        legend: {
                            position: "top",
                            labels: {
                                usePointStyle: true,
                                pointStyle: "circle",
                            },
                        },
                    },
                },
            });




            // Revenue Donut Chart
            const ctxDonut = document.getElementById("revenueChart").getContext("2d");

            new Chart(ctxDonut, {
                type: "doughnut",
                data: {
                    labels: ["Pro Plan", "Free Users"],
                    datasets: [{
                        data: [@json($proCoachUsers), @json($freeCoachUsers)],
                        backgroundColor: ["#ffc5cd", "#e4b3e4"],
                        borderWidth: 1,
                    }, ],
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: "bottom",
                        },
                    },
                },
            });
        </script>
    </div>
@endsection

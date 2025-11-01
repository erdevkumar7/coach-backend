@extends('admin.layouts.layout')
@php
use Carbon\Carbon;
@endphp
@section('content')
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                  <!-- <a href="" class="btn btn-outline-info btn-fw" style="float: right;">Add Coach</a> -->
                    <h4 class="card-title">Coach Subscriptions </h4>
                    <p class="card-description"> Coach List
                    </p>

                    <form id="" method="POST" action="">
                      @csrf
                      <div class="table-responsive">
                        <table class="table table-striped" id="example">
                          <thead>
                            <tr>
                              <th>S.No </th>
                              <th>Coach Name </th>
                              <th>Plan Name </th>
                              <th>Start Date </th>
                              <th>End Date </th>
                              <th>Plan status</th>
                              <th>Amount</th>
                              <th>Payment Date</th>
                              <th>Transaction Id</th>
                              <th>Receipt</th>
                              <!-- <th> Action</th> -->
                            </tr>
                          </thead>
                          <tbody>
                             @if($coaches)
                                @php $i=1; @endphp
                                @foreach($coaches as $coach)
                                   @php
                                      $startDate = Carbon::parse($coach->start_date);
                                      $endDate = Carbon::parse($coach->end_date);
                                      $formattedStartDate = $startDate->format('d-m-Y');
                                      $formattedEndDate = $endDate->format('d-m-Y');
                                    @endphp
                                <tr>
                                    <td>{{$i }}</td>
                                    <td>{{ $coach->coach_name }}</td>
                                    <td>{{ $coach->plan_name }}</td>
                                    <td>{{ $formattedStartDate }}</td>
                                    <td>{{ $formattedEndDate }}</td>
                                    <td>
                                      @if ($endDate->endOfDay()->lt(now()->startOfDay()))
                                      <span class="btn btn-danger">Expired</span>
                                      @else
                                      <span class="btn btn-success">Active</span>
                                      @endif
                                    </td>
                                    <td>${{ $coach->amount}}</td>
                                    <td>{{ Carbon::parse($coach->created_at)->format('d-m-Y') }}</td>
                                    <td>{{ $coach->txn_id}}</td>
                                       <td style="text-align: center;">
                                        <a href="{{ url('public/pdf/coach_payment_history/coach_payment_history_' . $coach->id . '.pdf') }}" target="_blank">
                                          <i class="fa fa-file-pdf-o" style="font-size: 24px; color: red;"></i>
                                        </a>
                                      </td>                                    
                                </tr>

                                    @php $i++; @endphp
                                @endforeach
                            @endif
                          </tbody>
                        </table>
                      </div>
                    </form>
                    <div class="d-flex add-pagination mt-4">
                        {{ $coaches->links('pagination::bootstrap-4') }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
@endsection



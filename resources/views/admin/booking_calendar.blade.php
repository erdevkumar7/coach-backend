@extends('admin.layouts.layout')

<style>
  #calendar {
    max-width: 1100px;
    margin: 0 auto;
  }
  .status-box {
    display: flex;
    gap: 20px;
    background-color: #f5f5f5;
    padding: 8px 16px;
    border-radius: 20px;
    width: fit-content;
    align-items: center;
    font-family: sans-serif;
    }

    .status-box ul {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .status-box li {
        display: flex;
        align-items: center;
        margin-right: 20px;
        font-size: 14px;
        color: #333;
    }

    .status-box span {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 6px;
    }

</style>

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Bookings (Calendar Overview) {{ $coach->first_name }} {{ $coach->last_name }}</h4>
                <div class="status-box">
                    <ul>
                        <li>
                            <span style="background-color: #00C292;"></span>
                            Confirmed
                        </li>
                        <li>
                            <span style="background-color: #FF9E01;"></span>
                            Pending
                        </li>
                        <li>
                            <span style="background-color: #A6A6A6;"></span>
                            Completed
                        </li>
                        <li>
                            <span style="background-color: #F44236;"></span>
                            Canceled
                        </li>
                        <li>
                            <span style="background-color: #248AFD;"></span>
                            Today
                        </li>
                    </ul>
                </div>
                <p class="card-description"> This month:3 Sessions Schedule, 2 Sessions Pending and 1 Session Completed </p>
                    <div id='calendar'></div>
                </div>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
            left: 'prevYear,prev,next,nextYear today',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek,dayGridDay'
            },
            initialView: 'dayGridMonth',
            initialDate: '2025-06-12',
            navLinks: true,
            editable: true,
            dayMaxEvents: true,
            events: function(fetchInfo, successCallback, failureCallback) {
                $.ajax({
                    url: "{{ url('admin/calendar/events/' . $coach->id) }}",
                    method: 'GET',
                    dataType: 'json',
                    success: function(serverEvents) {
                    const formattedEvents = serverEvents.map(event => {
                        return {
                        title: event.title,
                        start: event.start,
                        end: event.end,
                        color: 'purple',
                        extendedProps: event.extendedProps ?? {}
                        };
                    });

                    successCallback(formattedEvents);
                    },
                    error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                    failureCallback(error);
                    }
                });
            },
            eventDidMount: function(info) {
                const email = info.event.extendedProps.email || '';
                const status = info.event.extendedProps.status || '';
                const statusHtml = `<small style="color: ${status === 'cancelled' ? 'red' : 'green'}">
                                    Status: ${status}
                                </small>`;
            // Replace inner HTML of the event element
                const html = `
                    <div style="padding: 2px;">
                    <strong>${info.timeText}</strong><br>
                    <strong>${info.event.title}</strong><br>
                    <small>Email: ${email}</small><br>
                    ${statusHtml}
                    </div>
                `;
                info.el.innerHTML = html;
            }
        });
        calendar.render();
        });
    </script>
@endpush


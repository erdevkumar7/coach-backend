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
                    <h4 class="card-title"> {{ $name }} Bookings (Calendar Overview)</h4>
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
          <!-- content-wrapper ends -->
        </div>




@endsection

@push('scripts')

 <script>
   document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      headerToolbar: {
        left: 'prevYear,prev,next,nextYear today',
        center: 'title',
        right: 'dayGridMonth,dayGridWeek,dayGridDay'
      },
      initialDate: '2023-01-12',
      navLinks: true, // can click day/week names to navigate views
      editable: true,
      dayMaxEvents: true, // allow "more" link when too many events
      events: [
        {
          title: 'All Day Event',
          start: '2023-01-01'
        },
        {
          title: 'Long Event',
          start: '2023-01-07',
          end: '2023-01-10'
        },
        {
          groupId: 999,
          title: 'Repeating Event',
          start: '2023-01-09T16:00:00'
        },
        {
          groupId: 999,
          title: 'Repeating Event',
          start: '2023-01-16T16:00:00'
        },
        {
          title: 'Conference',
          start: '2023-01-11',
          end: '2023-01-13'
        },
        {
          title: 'Meeting',
          start: '2023-01-12T10:30:00',
          end: '2023-01-12T12:30:00'
        },
        {
          title: 'Lunch',
          start: '2023-01-12T12:00:00'
        },
        {
          title: 'Meeting',
          start: '2023-01-12T14:30:00'
        },
        {
          title: 'Happy Hour',
          start: '2023-01-12T17:30:00'
        },
        {
          title: 'Dinner',
          start: '2023-01-12T20:00:00'
        },
        {
          title: 'Birthday Party',
          start: '2023-01-13T07:00:00'
        },
        {
          title: 'Click for Google',
          url: 'http://google.com/',
          start: '2023-01-28'
        }
      ]
    });

    calendar.render();
  });
</script>
@endpush


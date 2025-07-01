@extends('admin.layouts.layout')

@section('content')
    <!-- partial -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <?php
                    $user_id = $id;
                    $title = $short_description = $description = $coaching_category = $focus = $delivery_mode = $session_count = $session_duration = $age_group = $price = '';
                    $currency = $cancellation_policy = '';
                    
                    if ($package) {
                        $title = $package->title;
                        $short_description = $package->short_description;
                        $description = $package->description;
                        $coaching_category = $package->coaching_category;
                        $focus = $package->focus;
                        $delivery_mode = $package->delivery_mode;
                        $session_count = $package->session_count;
                        $session_duration = $package->session_duration;
                        $age_group = $package->age_group;
                        $price = $package->price;
                        $currency = $package->currency;
                        $cancellation_policy = $package->cancellation_policy;
                    }
                    
                    ?>
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ route('admin.userList') }}" class="btn btn-outline-info btn-fw"
                                style="float: right;">User List</a>
                            <h4 class="card-title">User Management</h4>
                            <p class="card-description"> Add/Update Service-Package </p>
                            <form class="forms-sample" method="post"
                                action="{{ route('admin.addServicePackage', $user_id) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">

                                    <!-- Service Title -->
                                    <div class="form-group col-md-6">
                                        <label>Service Title</label>
                                        <input type="text" name="title" class="form-control"
                                            placeholder="e.g., Confidence Jumpstart Session" value="{{ $title }}">
                                    </div>

                                    <!-- Short Description -->
                                    <div class="form-group col-md-6">
                                        <label>Short Description</label>
                                        <input type="text" name="short_description" class="form-control" maxlength="200"
                                            placeholder="Snapshot descriptions" value="{{ $short_description }}">
                                    </div>

                                    <!-- Coaching Type -->
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">Coaching Category</label>
                                        <select required class="form-select form-select-sm" id="exampleFormControlSelect3"
                                            name="coaching_category">
                                            @if ($category)
                                                <option value="">Select</option>
                                                @foreach ($category as $categ)
                                                    <option value="{{ $categ->id }}"
                                                        {{ $coaching_category == $categ->id ? 'selected' : '' }}>
                                                        {{ $categ->category_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>


                                    <!-- Detail Description -->
                                    <div class="form-group col-md-6">
                                        <label>Detail Descriptions</label>
                                        <textarea name="description" class="form-control" rows="4">{{ $description }}</textarea>
                                    </div>

                                    <!-- Service Focus -->
                                    <div class="form-group col-md-6">
                                        <label>Service Focus</label>
                                        <input type="text" name="focus" class="form-control"
                                            placeholder="e.g., Confidence, Goal Clarity" value="{{ $focus }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">Delivery Mode</label>
                                        <select class="form-select form-select-sm" id="exampleFormControlSelect3"
                                            name="delivery_mode">
                                            @if ($mode)
                                                <option value="">Select</option>
                                                @foreach ($mode as $modes)
                                                    <option value="{{ $modes->id }}"
                                                        {{ $delivery_mode == $modes->id ? 'selected' : '' }}>
                                                        {{ $modes->mode_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>


                                    <!-- No. of Sessions -->
                                    <div class="form-group col-md-3">
                                        <label>Number of Sessions</label>
                                        <input type="number" max="100" min="1" name="session_count"
                                            class="form-control" value="{{ $session_count }}">
                                    </div>

                                    <!-- Session Duration -->
                                    <div class="form-group col-md-3">
                                        <label>Session Duration (Minute/Session)</label>
                                        <input type="text" name="session_duration" class="form-control"
                                            placeholder="e.g., 60 Min/Session" value="{{ $session_duration }}">
                                    </div>

                                    <!-- Targeted Audience -->
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">Target Audience</label>
                                        <select class="form-select form-select-sm" id="exampleFormControlSelect3"
                                            name="age_group">
                                            @if ($age_groups)
                                                <option value="">Select</option>
                                                @foreach ($age_groups as $age)
                                                    <option value="{{ $age->id }}"
                                                        {{ $age_group == $age->id ? 'selected' : '' }}>
                                                        {{ $age->group_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <!-- Price -->
                                    <div class="form-group col-md-3">
                                        <label>Total Price</label>
                                        <input type="text" name="price" class="form-control"
                                            value="{{ $price }}">
                                    </div>

                                    <!-- Currency -->
                                    <div class="form-group col-md-3">
                                        <label>Currency</label>
                                        <select name="currency" class="form-select form-select-sm">
                                            <option value="{{ $currency }}" selected>USD</option>
                                        </select>
                                    </div>

                                    <!-- Booking Slot and Validity -->
                                    <div class="form-group col-md-6">
                                        <label>Slots Available For Booking</label>
                                        <input type="date" name="booking_slot" class="form-control"
                                            value="{{ old('booking_slot') }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Booking Window (Date Range)</label>
                                        <input type="text" name="booking_window" id="date_range" class="form-control"
                                            value="{{ old('booking_window') }}">
                                    </div>

                                    <!-- Cancellation Policy -->
                                    {{-- <div class="form-group col-md-6">
                                        <label>Cancellation Policy</label>
                                        <select name="cancellation_policy" class="form-control">
                                            <option value="flexible">Flexible – Full Refund if canceled ≥24 hrs</option>
                                            <option value="moderate">Moderate – 50% refund if canceled ≥24 hrs</option>
                                            <option value="strict">Strict – No refund if canceled <48 hrs</option>
                                        </select>
                                    </div> --}}

                                    <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">Cancellation Policy</label>
                                        <select class="form-select form-select-sm" id="exampleFormControlSelect3"
                                            name="cancellation_policy">
                                            @if ($cancellation_policies)
                                                <option value="">Select</option>
                                                @foreach ($cancellation_policies as $policy)
                                                    <option value="{{ $policy->id }}"
                                                        {{ $cancellation_policy == $policy->id ? 'selected' : '' }}>
                                                        {{ $policy->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <!-- Rescheduling Policy -->
                                    <div class="form-group col-md-6">
                                        <label>Rescheduling Policy</label>
                                        <input type="text" name="rescheduling_policy" class="form-control"
                                            value="{{ old('rescheduling_policy', 'Free one time reschedule allowed') }}">
                                    </div>

                                    <!-- Media Upload -->
                                    <div class="form-group col-md-6">
                                        <label>Media Upload</label>
                                        <input type="file" name="media_file" class="form-control"
                                            accept="image/*,video/*">
                                    </div>

                                    <!-- Submit -->
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">Submit Package</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
    </div>
    <!-- main-panel ends -->
@endsection
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            document.getElementById("user_timezone").value = userTimezone;
        });
        $(document).ready(function() {
            $(document).on('change', '#country', function() {
                var cid = this.value; //let cid = $(this).val(); we cal also write this.
                $.ajax({
                    url: "{{ url('/admin/getstate') }}",
                    type: "POST",
                    datatype: "json",
                    data: {
                        country_id: cid,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        $('#state').html('<option value="">Select State</option>');
                        $.each(result.state, function(key, value) {
                            $('#state').append('<option value="' + value.state_id +
                                '">' + value.state_name + '</option>');
                        });
                    },
                    errror: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            $('#state').change(function() {
                var sid = this.value;
                $.ajax({
                    url: "{{ url('/admin/getcity') }}",
                    type: "POST",
                    datatype: "json",
                    data: {
                        state_id: sid,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        console.log(result);
                        $('#city').html('<option value="">Select City</option>');
                        $.each(result.city, function(key, value) {
                            $('#city').append('<option value="' + value.city_id + '">' +
                                value.city_name + '</option>')
                        });
                    },
                    errror: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endpush

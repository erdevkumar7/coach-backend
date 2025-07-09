@extends('admin.layouts.layout')

@section('content')
    <!-- partial -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <?php
                    $user_id = $id;
                    $today = date('Y-m-d');
                    $package_id = $title = $short_description = $description = $coaching_category = $focus = $delivery_mode = $session_count = $session_duration = $session_format = $age_group = $price = '';
                    $currency = $price_model = $cancellation_policy = $rescheduling_policy = $media_file = $media_original_name = '';
                    $booking_slots = $booking_availability = $booking_window = '';
                    
                    if ($package) {
                        $package_id = $package->id;
                        $title = $package->title;
                        $short_description = $package->short_description;
                        $description = $package->description;
                        $coaching_category = $package->coaching_category;
                        $focus = $package->focus;
                        $delivery_mode = $package->delivery_mode;
                        $session_count = $package->session_count;
                        $session_duration = $package->session_duration;
                        $session_format = $package->session_format;
                        $age_group = $package->age_group;
                        $price = $package->price;
                        $price_model = $package->price_model;
                        $currency = $package->currency;
                        $cancellation_policy = $package->cancellation_policy;
                        $rescheduling_policy = $package->rescheduling_policy;
                        $media_file = $package->media_file;
                        $media_original_name = $package->media_original_name;
                        $booking_slots = $package->booking_slots;
                        $booking_availability = $package->booking_availability;
                        $booking_window = $package->booking_window;
                    }
                    
                    ?>
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ route('admin.servicePackageList', $user_id) }}" class="btn btn-outline-info btn-fw"
                                style="float: right;">Package List</a>
                            <h4 class="card-title">Coach Management / {{$user_detail->first_name}} / Service-Packages</h4>
                            <p class="card-description"> Add/Update Package </p>
                            <form class="forms-sample" method="post"
                                action="{{ route('admin.addServicePackage', $user_id) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <input type="hidden" name="service_package_id" value="{{ $package_id }}">
                                    <input type="hidden" name="media_file_name" value="{{ $media_file }}">
                                    <!-- Service Title -->
                                    <div class="form-group col-md-6">
                                        <label>Service Title</label>
                                        <input type="text" required name="title" class="form-control"
                                            placeholder="e.g., Confidence Jumpstart Session" value="{{ $title }}">
                                    </div>

                                    <!-- Short Description -->
                                    <div class="form-group col-md-6">
                                        <label>Short Description</label>
                                        <input type="text" name="short_description" class="form-control" maxlength="250"
                                            placeholder="Snapshot descriptions" value="{{ $short_description }}" required>
                                    </div>

                                    <!-- Coaching Type -->
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">Coaching Category</label>
                                        <select class="form-select form-select-sm" id="exampleFormControlSelect3"
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
                                            placeholder="e.g., Confidence, Goal Clarity" value="{{ $focus }}" required>
                                    </div>

                                    <!-- Targeted Audience -->
                                    <div class="form-group col-md-3">
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

                                    <div class="form-group col-md-3">
                                        <label for="exampleInputEmail1">Delivery Mode</label>
                                        <select class="form-select form-select-sm" id="exampleFormControlSelect3"
                                            name="delivery_mode" required>
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
                                            class="form-control" value="{{ $session_count }}" required>
                                    </div>

                                    <!-- Session Duration -->
                                    <div class="form-group col-md-3">
                                        <label>Session Duration (Minute/Session)</label>
                                        <input type="text" name="session_duration" class="form-control"
                                            placeholder="e.g., 60 Min/Session" value="{{ $session_duration }}" required>
                                    </div>

                                    <!-- Session format -->
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">Session Format</label>
                                        <select class="form-select form-select-sm" id="exampleFormControlSelect3"
                                            name="session_format" required>
                                            @if ($session_formats)
                                                <option value="">Select</option>
                                                @foreach ($session_formats as $session)
                                                    <option value="{{ $session->id }}"
                                                        {{ $session_format == $session->id ? 'selected' : '' }}>
                                                        {{ $session->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <!-- Price -->
                                    <div class="form-group col-md-3">
                                        <label>Total Price</label>
                                        <input type="text" name="price" class="form-control"
                                            value="{{ $price }}" required>
                                    </div>

                                    <!-- Currency -->
                                    <div class="form-group col-md-3">
                                        <label>Currency</label>
                                        <select name="currency" class="form-select form-select-sm" required>
                                            <option value="USD" selected>USD</option>
                                        </select>
                                    </div>

                                    <!-- Price model -->
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">Pricing Model</label>
                                        <select class="form-select form-select-sm" id="exampleFormControlSelect3"
                                            name="price_model" required>
                                            @if ($price_models)
                                                <option value="">Select</option>
                                                @foreach ($price_models as $model)
                                                    <option value="{{ $model->id }}"
                                                        {{ $price_model == $model->id ? 'selected' : '' }}>
                                                        {{ $model->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <!-- Booking Slot and Validity -->
                                    <div class="form-group col-md-3">
                                        <label>Slots Available For Booking</label>
                                        <input type="number" max="200" min="1" name="booking_slots"
                                            class="form-control" value="{{ $booking_slots }}" required>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Availability</label>
                                        <input type="date" name="booking_availability" class="form-control"
                                            value="{{ $booking_availability }}" min="{{ $today }}" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Booking Window (Date Range)</label>
                                        <input name="booking_window" id="date_range" class="form-control"
                                            value="{{ $booking_window ?? '' }}" autocomplete="off">
                                    </div>

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
                                            placeholder="One free reschedule allow per session"
                                            value="{{ $rescheduling_policy }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Media Upload</label>
                                        <input type="file" class="form-control form-control-sm document-input"
                                            name="media_file" accept="image/*,video/*">
                                        @if ($media_file)
                                            <div class="mt-1 uploaded-file">
                                                <a href="{{ asset('/public/uploads/service_packages/' . $media_file) }}"
                                                    target="_blank">{{ $media_original_name }}</a>
                                            </div>
                                        @endif
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
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#date_range').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD'
                },
                minDate: moment()
            });
        });
    </script>
@endpush

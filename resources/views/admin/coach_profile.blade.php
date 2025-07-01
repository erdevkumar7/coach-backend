@extends('admin.layouts.layout')

@section('content')
    <!-- partial -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <?php
                    $first_name = $last_name = $email = $contact_number = $fb_link = $insta_link = $linkdin_link = $booking_link = $gender = $user_id = $short_bio = $professional_title = $exp_and_achievement = $is_verified = $detailed_bio = '';
                    $country_id = $state_id = $city_id = 0;
                    if ($user_detail) {
                        $user_id = $user_detail->id;
                        $first_name = $user_detail->first_name;
                        $last_name = $user_detail->last_name;
                        $email = $user_detail->email;
                        $contact_number = $user_detail->contact_number;
                        $gender = $user_detail->gender;
                        $country_id = $user_detail->country_id;
                        $state_id = $user_detail->state_id;
                        $city_id = $user_detail->city_id;
                        $short_bio = $user_detail->short_bio;
                        $professional_title = $user_detail->professional_title;
                        $detailed_bio = $user_detail->detailed_bio;
                        $exp_and_achievement = $user_detail->exp_and_achievement;
                        $is_verified = $user_detail->is_verified;
                    }
                    $video_link = $experience = $coaching_category = $delivery_mode = $free_trial_session = $is_volunteered_coach = '';
                    $volunteer_coaching = $website_link = $objective = $coach_type = $coach_subtype = '';
                    $price = 0;
                    if ($profession) {
                        $video_link = $profession->video_link;
                        $experience = $profession->experience;
                        $coaching_category = $profession->coaching_category;
                        $delivery_mode = $profession->delivery_mode;
                        $free_trial_session = $profession->free_trial_session;
                        $price = $profession->price;
                        $is_volunteered_coach = $profession->is_volunteered_coach;
                        $volunteer_coaching = $profession->volunteer_coaching;
                        $website_link = $profession->website_link;
                        $fb_link = $profession->fb_link;
                        $insta_link = $profession->insta_link;
                        $linkdin_link = $profession->linkdin_link;
                        $booking_link = $profession->booking_link;

                        $objective = $profession->objective;
                        $coach_type = $profession->coach_type;
                        $coach_subtype = $profession->coach_subtype;
                    }
                    ?>
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ route('admin.coachList') }}" class="btn btn-outline-info btn-fw"
                                style="float: right;">Coach List</a>
                            <h4 class="card-title">Coach Management</h4>
                            <!--p class="card-description"> Add / Update Blog  </p-->

                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                        data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                        aria-selected="true">Basic Profile</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                        type="button" role="tab" aria-controls="profile" aria-selected="false"
                                        {{ $user_id == '' ? 'disabled' : '' }}>Professional Profile</button>
                                </li>
                                <!--li class="nav-item" role="presentation">
                                    <button class="nav-link" id="messages-tab" data-bs-toggle="tab" data-bs-target="#messages" type="button" role="tab" aria-controls="messages" aria-selected="false">Messages</button>
                                  </li>
                                  <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false">Settings</button>
                                  </li-->
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                    <form class="forms-sample" method="post" action="{{ route('admin.addCoach') }}"
                                        enctype="multipart/form-data">
                                        {!! csrf_field() !!}
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <input type="hidden" name="user_id" value="{{ $user_id }}">
                                                <label for="exampleInputUsername1">First Name</label>
                                                <input required type="text" class="form-control form-control-sm"
                                                    placeholder="First Name" aria-label="Username" name="first_name"
                                                    value="{{ $first_name }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputUsername1">Last Name</label>
                                                <input required type="text" class="form-control form-control-sm"
                                                    placeholder="Last Name" aria-label="Username" name="last_name"
                                                    value="{{ $last_name }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Email address</label>
                                                <input required type="email" class="form-control form-control-sm"
                                                    id="exampleInputEmail1" placeholder="Email" name="email"
                                                    value="{{ $email }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Contact Number</label>
                                                <input required type="number" class="form-control form-control-sm"
                                                    id="exampleInputEmail1" placeholder="contact number"
                                                    name="contact_number" value="{{ $contact_number }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Password</label>
                                                <input type="password" class="form-control form-control-sm"
                                                    id="exampleInputEmail1" placeholder="Password" name="password">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Coach Type</label>
                                                <select class="form-select form-select-sm" id="coach_type"
                                                    name="coach_type">
                                                    <option>Select Coach Type</option>
                                                    @if ($type)
                                                        @foreach ($type as $types)
                                                            <option value="{{ $types->id }}"
                                                                {{ $coach_type == $types->id ? 'selected' : '' }}>
                                                                {{ $types->type_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Coach SubType</label>
                                                <select class="form-select form-select-sm" id="coach_subtype"
                                                    name="coach_subtype">
                                                    @if ($subtype)
                                                        @foreach ($subtype as $subtypes)
                                                            <option value="{{ $subtypes->id }}"
                                                                {{ $coach_subtype == $subtypes->id ? 'selected' : '' }}>
                                                                {{ $subtypes->subtype_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Gender</label>
                                                <select required class="form-select form-select-sm"
                                                    id="exampleFormControlSelect3" name="gender">
                                                    <option value="1" {{ $gender == 1 ? 'selected' : '' }}>Male
                                                    </option>
                                                    <option value="2" {{ $gender == 2 ? 'selected' : '' }}>Female
                                                    </option>
                                                    <option value="3" {{ $gender == 3 ? 'selected' : '' }}>Other
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Country</label>
                                                <select required class="form-select form-select-sm" id="country"
                                                    name="country_id">
                                                    <option>Select Country</option>
                                                    @if ($country)
                                                        @foreach ($country as $country)
                                                            <option value="{{ $country->country_id }}"
                                                                {{ $country_id == $country->country_id ? 'selected' : '' }}>
                                                                {{ $country->country_name }}</option>
                                                        @endforeach
                                                    @endif

                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">State</label>
                                                <select required class="form-select form-select-sm" id="state"
                                                    name="state_id">
                                                    <option>Select State</option>
                                                    @if ($state)
                                                        @foreach ($state as $states)
                                                            <option value="{{ $states->state_id }}"
                                                                {{ $state_id == $states->state_id ? 'selected' : '' }}>
                                                                {{ $states->state_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">City</label>
                                                <select required class="form-select form-select-sm" id="city"
                                                    name="city_id">
                                                    <option>Select City</option>
                                                    @if ($city)
                                                        @foreach ($city as $cities)
                                                            <option value="{{ $cities->city_id }}"
                                                                {{ $city_id == $cities->city_id ? 'selected' : '' }}>
                                                                {{ $cities->city_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Short Bio</label>
                                                <textarea required class="form-control form-control-sm" name="short_bio" maxlength="300" id="short_bio">{{ $short_bio }}</textarea>
                                                <small id="bioCounter">300 characters remaining</small>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Professional Title</label>
                                                <input required type="text" class="form-control form-control-sm"
                                                    id="exampleInputEmail1" placeholder="Professional Title"
                                                    name="professional_title" value="{{ $professional_title }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Coaching Category</label>
                                                <select required class="form-select form-select-sm"
                                                    id="exampleFormControlSelect3" name="coaching_category">
                                                    @if ($category)
                                                        @foreach ($category as $categ)
                                                            <option value="{{ $categ->id }}"
                                                                {{ $coaching_category == $categ->id ? 'selected' : '' }}>
                                                                {{ $categ->category_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Delivery Mode</label>
                                                <select required class="form-select form-select-sm"
                                                    id="exampleFormControlSelect3" name="delivery_mode">
                                                    @if ($mode)
                                                        @foreach ($mode as $modes)
                                                            <option value="{{ $modes->id }}"
                                                                {{ $delivery_mode == $modes->id ? 'selected' : '' }}>
                                                                {{ $modes->mode_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Service Offered</label>
                                                <select required class="js-example-basic-multiple w-100"
                                                    multiple="multiple" name="service_offered[]">
                                                    @if ($service)
                                                        @foreach ($service as $services)
                                                            <option value="{{ $services->id }}"
                                                                {{ in_array($services->id, $selectedServiceIds) ? 'selected' : '' }}>
                                                                {{ $services->service }}</option>
                                                        @endforeach
                                                    @endif

                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Language</label>
                                                <select required class="js-example-basic-multiple w-100"
                                                    multiple="multiple" name="language[]">
                                                    @if ($language)
                                                        @foreach ($language as $languages)
                                                            <option value="{{ $languages->id }}"
                                                                {{ in_array($languages->id, $selectedLanguageIds) ? 'selected' : '' }}>
                                                                {{ $languages->language }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Free Trial Session</label>
                                                <select required class="form-select form-select-sm"
                                                    id="exampleFormControlSelect3" name="free_trial_session">
                                                    <option value="1"
                                                        {{ $free_trial_session == 1 ? 'selected' : '' }}>Yes
                                                    </option>
                                                    <option value="2"
                                                        {{ $free_trial_session == 2 ? 'selected' : '' }}>No
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Is Volunteered Coach</label>
                                                <select class="form-select form-select-sm" id="exampleFormControlSelect3"
                                                    name="is_volunteered_coach">
                                                    <option value="1"
                                                        {{ $is_volunteered_coach == 1 ? 'selected' : '' }}>
                                                        Yes</option>
                                                    <option value="2"
                                                        {{ $is_volunteered_coach == 2 ? 'selected' : '' }}>No
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6" id="vol_coach">
                                                <label for="exampleInputEmail1">Area of volunteer coaching session</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="exampleInputEmail1"
                                                    placeholder="Area of volunteer coaching session"
                                                    name="volunteer_coaching" value="{{ $volunteer_coaching }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Profile Image</label>
                                                <input type="file" class="form-control form-control-sm"
                                                    id="exampleInputEmail1" name="profile_image"
                                                    accept="image/png, image/gif, image/jpeg">
                                            </div>

                                               <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Verified Profile</label>
                                                <select class="form-select form-select-sm" id="exampleFormControlSelect3"
                                                    name="is_verified">
                                                    <option value="1"
                                                        {{ $is_verified == 1 ? 'selected' : '' }}>
                                                        Yes</option>
                                                    <option value="0"
                                                        {{ $is_verified == 0 ? 'selected' : '' }}>No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <input type="hidden" name="user_time" value="" id="user_timezone">
                                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                                    </form>
                                </div>

                                <!--Coach Professional Profile-->
                                <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                    <form class="forms-sample" method="post"
                                        action="{{ route('admin.addProfessional') }}" enctype="multipart/form-data">
                                        {!! csrf_field() !!}
                                        <div class="row">
                                            <input type="hidden" name="user_id" value="{{ $user_id }}">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputUsername1">Experiance(In year)</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    placeholder="Experiance(In year)" maxlength="2" name="experiance"
                                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                    value="{{ $experience }}">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="exampleInputUsername1">Price($)</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-primary text-white">$</span>
                                                    </div>
                                                    <input type="text" class="form-control form-control-sm"
                                                        placeholder="price($)" maxlength="5" name="price"
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                        value="{{ $price }}">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Video Introduction</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="video-introduction" placeholder="Video Introduction"
                                                    pattern="https?://.+" name="video_introduction"
                                                    value="{{ $video_link }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Website</label>
                                                <input type="text" class="form-control form-control-sm" id="Website"
                                                    placeholder="Website" pattern="https?://.+" name="website"
                                                    value="{{ $website_link }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Instagram</label>
                                                <input type="text" class="form-control form-control-sm" id="Instagram"
                                                    placeholder="Instagram" pattern="https?://.+" name="instagram"
                                                    value="{{ $insta_link }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Facebook</label>
                                                <input type="text" class="form-control form-control-sm" id="Facebook"
                                                    placeholder="Facebook" pattern="https?://.+" name="facebook"
                                                    value="{{ $fb_link }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">LinkDin</label>
                                                <input type="text" class="form-control form-control-sm" id="LinkDin"
                                                    placeholder="LinkDin" pattern="https?://.+" name="linkdin"
                                                    value="{{ $linkdin_link }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Booking</label>
                                                <input type="text" class="form-control form-control-sm" id="Booking"
                                                    placeholder="Booking" pattern="https?://.+" name="booking"
                                                    value="{{ $booking_link }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Objective of Coaching/Learning</label>
                                                <input type="text" class="form-control form-control-sm" id="objective"
                                                    placeholder="Objective of Coaching/Learning" name="objective"
                                                    value="{{ $objective }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Detailed Bio</label>
                                                <textarea class="form-control form-control-sm" name="detailed_bio" maxlength="1000" id="detailed_bio">{{ $detailed_bio }}</textarea>
                                                <small id="bioCounterDetail">1000 characters remaining</small>
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label for="exampleInputEmail1">Experience And Achievement</label>
                                                <textarea class="form-control form-control-sm" name="exp_and_achievement" maxlength="1000" id="exp_and_achievement">{{ $exp_and_achievement }}</textarea>
                                                <small id="achiveCounterDetail">1000 characters remaining</small>
                                            </div>
                                        </div>
                                        <div id="documentContainer">
                                            @php $i=1; @endphp
                                            @if ($document)
                                                @foreach ($document as $documents)
                                                    <div class="row document-group">
                                                        <div class="form-group col-md-5">
                                                            <label>Document</label>
                                                            <input type="hidden" name="doc_id[]"
                                                                value="{{ $documents->id }}">
                                                            <input type="file"
                                                                class="form-control form-control-sm document-input"
                                                                name="document_file[]" accept="application/pdf, image/gif, image/jpeg, image/jpg, image/png">
                                                            @if (!empty($documents->document_file))
                                                                <div class="mt-1 uploaded-file">
                                                                    <a href="{{ asset('/public/uploads/documents/' . $documents->document_file) }}"
                                                                        target="_blank">{{ $documents->original_name }}</a>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="form-group col-md-5">
                                                            <label>Document Type</label>
                                                            <select class="form-select form-select-sm"
                                                                name="document_type[]">
                                                                <option value="1"
                                                                    {{ $documents->document_type == 1 ? 'selected' : '' }}>
                                                                    Certificate</option>
                                                                <option value="2"
                                                                    {{ $documents->document_type == 2 ? 'selected' : '' }}>
                                                                    CV</option>
                                                                <option value="3"
                                                                    {{ $documents->document_type == 3 ? 'selected' : '' }}>
                                                                    Brochure</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-2 d-flex align-items-end">
                                                            <button type="button"
                                                                class="btn btn-outline-danger btn-rounded btn-icon remove-document"
                                                                file_id="{{ $documents->id }}">
                                                                <i class="mdi mdi-minus text-dark"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @php $i++; @endphp
                                                @endforeach
                                            @endif
                                            @if ($i < 5)
                                                <div class="row document-group">
                                                    <div class="form-group col-md-5">
                                                        <label>Document</label>
                                                        <input type="file"
                                                            class="form-control form-control-sm document-input"
                                                            name="document_file[]" accept="application/pdf, image/gif, image/jpeg, image/jpg, image/png">
                                                    </div>
                                                    <div class="form-group col-md-5">
                                                        <label>Document Type</label>
                                                        <select class="form-select form-select-sm" name="document_type[]">
                                                            <option value="1">Certificate</option>
                                                            <option value="2">CV</option>
                                                            <option value="3">Brochure</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-2 d-flex align-items-end">
                                                        <button type="button"
                                                            class="btn btn-outline-secondary btn-rounded btn-icon"
                                                            id="addMoreDocuments">
                                                            <i class="mdi mdi-plus text-dark"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                                    </form>
                                </div>
                                <div class="tab-pane" id="messages" role="tabpanel" aria-labelledby="messages-tab">
                                    Thired</div>
                                <div class="tab-pane" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                                    Fourth</div>
                            </div>
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
        var triggerTabList = [].slice.call(document.querySelectorAll('#myTab a'))
        triggerTabList.forEach(function(triggerEl) {
            var tabTrigger = new bootstrap.Tab(triggerEl)

            triggerEl.addEventListener('click', function(event) {
                event.preventDefault()
                tabTrigger.show()
            })
        })
    </script>
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

            $('#coach_type').change(function() {
                var sid = this.value;
                $.ajax({
                    url: "{{ url('/admin/getsubType') }}",
                    type: "POST",
                    datatype: "json",
                    data: {
                        coach_type_id: sid,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        console.log(result);
                        $('#coach_subtype').html('<option value="">Select SubType</option>');
                        $.each(result.city, function(key, value) {
                            $('#coach_subtype').append('<option value="' + value.id +
                                '">' + value.subtype_name + '</option>')
                        });
                    },
                    errror: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bio = document.getElementById('short_bio');
            const counter = document.getElementById('bioCounter');
            const max = 300;

            function updateCounter() {
                const remaining = max - bio.value.length;
                counter.textContent = `${remaining} characters remaining`;
            }

            bio.addEventListener('input', updateCounter);
            updateCounter(); // initial update
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const select = document.querySelector('select[name="is_volunteered_coach"]');
            const volCoachDiv = document.getElementById('vol_coach');

            function toggleVolCoach() {
                if (select.value === '1') {
                    volCoachDiv.style.display = 'block';
                } else {
                    volCoachDiv.style.display = 'none';
                }
            }

            // Run on page load
            toggleVolCoach();

            // Run when the select changes
            select.addEventListener('change', toggleVolCoach);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const biod = document.getElementById('detailed_bio');
            const counterd = document.getElementById('bioCounterDetail');
            const max = 1000;

            function updateCounterd() {
                const remaining = max - biod.value.length;
                counterd.textContent = `${remaining} characters remaining`;
            }

            biod.addEventListener('input', updateCounterd);
            updateCounterd(); // initial update
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const achieve = document.getElementById('exp_and_achievement');
            const counterd = document.getElementById('achiveCounterDetail');
            const max = 1000;

            function updateCounterd() {
                const remaining = max - achieve.value.length;
                counterd.textContent = `${remaining} characters remaining`;
            }

            achieve.addEventListener('input', updateCounterd);
            updateCounterd(); // initial update
        });
    </script>
    <script>
        $(document).on('click', '#addMoreDocuments', function() {
            // Limit to 5 document upload fields
            if ($('#documentContainer .document-group').length >= 5) {
                alert("You can only upload up to 5 documents.");
                return;
            }

            const newRow = `
              <div class="row document-group mb-2">
                <div class="form-group col-md-5">
                  <label>Document</label>
                  <input type="file" name="document_file[]" class="form-control form-control-sm document-input" accept="application/pdf, image/gif, image/jpeg, image/jpg, image/png">
                </div>
                <div class="form-group col-md-5">
                  <label>Document Type</label>
                  <select name="document_type[]" class="form-select form-select-sm">
                    <option value="1">Certificate</option>
                    <option value="2">CV</option>
                    <option value="3">Brochure</option>
                  </select>
                </div>
                <div class="form-group col-md-2 d-flex align-items-end">
                  <button type="button" class="btn btn-outline-danger btn-rounded btn-icon remove-document">
                    <i class="mdi mdi-minus text-dark"></i>
                  </button>
                </div>
              </div>`;
            $('#documentContainer').append(newRow);
        });

        $(document).on('click', '.remove-document', function() {
            const fileId = $(this).attr('file_id');
            const row = $(this).closest('.document-group');

            if (fileId) {
                // Optional: Confirm deletion
                if (!confirm("Are you sure you want to delete this file?")) return;

                $.ajax({
                    url: "{{ url('/admin/deleteDocument') }}",
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        id: fileId
                    },
                    success: function(response) {
                        if (response.success) {
                            row.remove();
                        } else {
                            alert("Error deleting document.");
                        }
                    },
                    error: function() {
                        alert("Failed to communicate with the server.");
                    }
                });
            } else {
                // Just remove the row if there's no file_id
                row.remove();
            }
        });
    </script>
    <script>
        $(document).on('change', '.document-input', function() {
            const file = this.files[0];
            const parent = $(this).closest('.form-group');

            parent.find('.uploaded-file').remove();
            parent.find('.new-upload-preview').remove();

            if (file && file.type === 'application/pdf, image/gif, image/jpeg, image/jpg, image/png') {
                const fileName = file.name;
                const objectUrl = URL.createObjectURL(file); // temp file URL for preview

                const preview = `<div class="mt-1 new-upload-preview">
                                <a href="${objectUrl}" target="_blank">${fileName}</a>
                              </div>`;
                parent.append(preview);
            }
        });
    </script>
@endpush

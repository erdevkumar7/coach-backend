@extends('admin.layouts.layout')

@section('content')
    <!-- partial -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <?php
                    $first_name = $last_name = $email = $contact_number = $fb_link = $insta_link = $linkdin_link = $booking_link = $gender = $user_id = $short_bio = $professional_title = $exp_and_achievement = $detailed_bio = $is_verified = '';
                    $notification=$privacy = "";
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
                        $notification= $user_detail->notificationSettings ?? null;
                        $privacy = $user_detail->privacySettings ?? null;
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
                        //$coach_subtype = $profession->coach_subtype;
                        $coach_subtype = $profession->coach_subtype_data;
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
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false" {{ $user_id == '' ? 'disabled' : '' }}>Profile Settings</button>
                                </li>

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
                                                    value="{{ $first_name }}" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputUsername1">Last Name</label>
                                                <input required type="text" class="form-control form-control-sm"
                                                    placeholder="Last Name" aria-label="Username" name="last_name"
                                                    value="{{ $last_name }}" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Email address</label>
                                                <input required type="email" class="form-control form-control-sm"
                                                    id="exampleInputEmail1" placeholder="Email" name="email"
                                                    value="{{ $email }}" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputContactNumber">Contact Number</label>
                                                <input required type="number" class="form-control form-control-sm"
                                                    id="exampleInputContactNumber" placeholder="contact number"
                                                    name="contact_number" value="{{ $contact_number }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputPassword">Password</label>
                                                <input type="password" class="form-control form-control-sm"
                                                    id="exampleInputPassword" placeholder="Password" name="password" required>
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
                                                <select required class="js-example-basic-multiple w-100"
                                                    name="coach_subtype[]"  multiple="multiple" id='coach_subtype'>
                                                    @if ($subtype)
                                                        @foreach ($subtype as $subtypes)
                                                            <option value="{{ $subtypes->id }}"
                                                                {{ in_array($subtypes->id, $coach_subtype ?? []) ? 'selected' : '' }}>
                                                                {{ $subtypes->subtype_name  }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Gender</label>
                                                <select required class="form-select form-select-sm"
                                                    id="exampleFormControlSelectgender" name="gender">
                                                    <option value="1" {{ $gender == 1 ? 'selected' : '' }}>Male
                                                    </option>
                                                    <option value="2" {{ $gender == 2 ? 'selected' : '' }}>Female
                                                    </option>
                                                    <option value="3" {{ $gender == 3 ? 'selected' : '' }}>Other
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputCountry">Country</label>
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
                                                <label for="exampleInputState">State</label>
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
                                                <label for="exampleInputCity">City</label>
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
                                                <label for="exampleInputShort">Short Bio</label>
                                                <textarea required class="form-control form-control-sm" name="short_bio" maxlength="300" id="short_bio">{{ $short_bio }}</textarea>
                                                <small id="bioCounter">300 characters remaining</small>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="ProfessionalTitle">Professional Title</label>
                                                <input required type="text" class="form-control form-control-sm"
                                                    id="ProfessionalTitle" placeholder="Professional Title"
                                                    name="professional_title" value="{{ $professional_title }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputCoachingCat">Coaching Category</label>
                                                <select required class="form-select form-select-sm"
                                                    id="exampleFormControlCoachingCategory" name="coaching_category">
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
                                                <label for="exampleInputDelivery">Delivery Mode</label>
                                                <select required class="form-select form-select-sm"
                                                    id="exampleFormControlDeliveryMode" name="delivery_mode">
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
                                                <label for="exampleInputServiced">Service Offered</label>
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
                                                <label for="exampleInputLanguage">Language</label>
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
                                                <label for="exampleInputTrial">Free Trial Session</label>
                                                <select required class="form-select form-select-sm"
                                                    id="exampleFormControlTrial" name="free_trial_session">
                                                    <option value="1"
                                                        {{ $free_trial_session == 1 ? 'selected' : '' }}>Yes
                                                    </option>
                                                    <option value="2"
                                                        {{ $free_trial_session == 2 ? 'selected' : '' }}>No
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputVolunteered">Is Volunteered Coach</label>
                                                <select class="form-select form-select-sm" id="exampleFormControlVolunteered"
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
                                                <label for="exampleInputvolunteerCoaching">Area of volunteer coaching session</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="volunteerCoaching"
                                                    placeholder="Area of volunteer coaching session"
                                                    name="volunteer_coaching" value="{{ $volunteer_coaching }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputProfile">Profile Image</label>
                                                <input type="file" class="form-control form-control-sm"
                                                    id="exampleInputProfile" name="profile_image"
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
                                                     name="video_introduction"
                                                    value="{{ $video_link }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Website</label>
                                                <input type="text" class="form-control form-control-sm" id="Website"
                                                    placeholder="Website"  name="website"
                                                    value="{{ $website_link }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Instagram</label>
                                                <input type="text" class="form-control form-control-sm" id="Instagram"
                                                    placeholder="Instagram"  name="instagram"
                                                    value="{{ $insta_link }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Facebook</label>
                                                <input type="text" class="form-control form-control-sm" id="Facebook"
                                                    placeholder="Facebook"  name="facebook"
                                                    value="{{ $fb_link }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">LinkDin</label>
                                                <input type="text" class="form-control form-control-sm" id="LinkDin"
                                                    placeholder="LinkDin"  name="linkdin"
                                                    value="{{ $linkdin_link }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Booking</label>
                                                <input type="text" class="form-control form-control-sm" id="Booking"
                                                    placeholder="Booking"  name="booking"
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

                                <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                                    <div class="content-wrapper">
                                    <div class="row">
                                        <div class="col-12 grid-margin stretch-card">
                                            <div class="card">
                                                <div class="card-body">
                                                <h4 class="card-title">Change Password</h4>
                                                <form id="updatePasswordForm" class="form-group">
                                                        <input type="hidden" name="user_id" value="{{ $user_id }}"> {{-- Pass user ID here --}}
                                                        <div class="row g-3">
                                                            <div class="col-12 col-md-4">
                                                            <label for="newPassword" class="form-label">New Password</label>
                                                            <input type="password" class="form-control" id="newPassword" name="new_password" placeholder="New Password">
                                                            </div>
                                                            <div class="col-12 col-md-4">
                                                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                                                            <input type="password" class="form-control" id="confirmPassword" name="new_password_confirmation" placeholder="Confirm Password">
                                                            </div>
                                                        </div>
                                                        <div class="mt-4">
                                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                                        </div>
                                                    </form>


                                                <hr/>

                                                <h4 class="card-title">Notifications</h4>

                                                    <div class="row g-3">

                                                    <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-between align-items-center">
                                                        <label class="notification-label">New Coach Match Alert </label>
                                                        <div class="form-check form-switch custom-switch">
                                                        <input class="form-check-input notification-toggle"
                                                            type="checkbox"
                                                            name="new_coach_match_alert"
                                                            data-field="new_coach_match_alert"
                                                            data-user="{{  $user_id }}"
                                                            {{  $notification && $notification?->new_coach_match_alert ? 'checked' : '' }}>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-between align-items-center">
                                                        <label class="notification-label">Message Notifications</label>
                                                        <div class="form-check form-switch custom-switch">
                                                        <input class="form-check-input notification-toggle" type="checkbox" name="message_notifications"
                                                            data-field="message_notifications"
                                                            data-user="{{ $user_id }}"
                                                            {{ $notification && $notification?->message_notifications ? 'checked' : '' }}>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-between align-items-center">
                                                        <label class="notification-label">Booking Reminders</label>
                                                        <div class="form-check form-switch custom-switch">
                                                        <input class="form-check-input notification-toggle" type="checkbox" name="booking_reminders"
                                                            data-field="booking_reminders"
                                                            data-user="{{  $user_id }}"
                                                            {{  $notification && $notification?->booking_reminders ? 'checked' : '' }}>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-between align-items-center">
                                                        <label class="notification-label">Platform Announcements</label>
                                                        <div class="form-check form-switch custom-switch">
                                                        <input class="form-check-input notification-toggle" type="checkbox" name="platform_announcements"
                                                            data-field="platform_announcements"
                                                            data-user="{{  $user_id }}"
                                                            {{  $notification && $notification?->platform_announcements ? 'checked' : '' }}>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-between align-items-center">
                                                        <label class="notification-label">Blog / Article Recommendations</label>
                                                        <div class="form-check form-switch custom-switch">
                                                        <input class="form-check-input notification-toggle" type="checkbox" name="blog_recommendations"
                                                            data-field="blog_recommendations"
                                                            data-user="{{  $user_id }}"
                                                            {{  $notification && $notification?->blog_recommendations ? 'checked' : '' }}>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-between align-items-center">
                                                        <label class="notification-label">Billing Updates</label>
                                                        <div class="form-check form-switch custom-switch">
                                                        <input class="form-check-input notification-toggle" type="checkbox" name="billing_updates"
                                                            data-field="billing_updates"
                                                            data-user="{{  $user_id }}"
                                                            {{  $notification && $notification?->billing_updates ? 'checked' : '' }}>
                                                        </div>
                                                    </div>

                                                    </div>

                                                <hr/>

                                                <h4 class="card-title">Data & Privacy Control</h4>
                                                    <div class="row g-3">
                                                    <!-- Profile Visibility -->
                                                    <div class="col-12">
                                                        <label class="form-label fw-bold"><i class="bi bi-person-circle me-2"></i>Profile Visibility</label>
                                                        <div class="form-check form-check-inline">
                                                        <input class="form-check-input prof-opt" type="radio" name="prof-vis" id="public" value="public"
                                                        {{ $privacy && $privacy->profile_visibility === 'public' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="public">Public</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                        <input class="form-check-input prof-opt" type="radio" name="prof-vis" id="private" value="private"
                                                        {{ $privacy && $privacy->profile_visibility === 'private' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="private">Private</label>
                                                        </div>
                                                    </div>
                                                    <!-- Communication Preferences -->
                                                    <div class="col-12">
                                                        <label class="form-label fw-bold"><i class="bi bi-chat-dots me-2"></i>Communication Preference</label>
                                                        <div class="form-check form-check-inline">
                                                        <input class="form-check-input com-pref" type="checkbox" id="emailComm" name="communication_email" data-type="communication_email"
                                                        {{ $privacy && $privacy->communication_email ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="emailComm">Email</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                        <input class="form-check-input com-pref" type="checkbox" id="inAppComm" name="communication_in_app" data-type="communication_in_app"
                                                        {{ $privacy && $privacy->communication_in_app ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="inAppComm">In-App</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                        <input class="form-check-input com-pref" type="checkbox" id="pushComm" name="communication_push" data-type="communication_push"
                                                        {{ $privacy && $privacy->communication_push ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="pushComm">Push Toggles</label>
                                                        </div>
                                                    </div>
                                                    <!-- AI Matching -->
                                                    <div class="col-12">
                                                        <label class="form-label fw-bold"><i class="bi bi-chat-dots me-2"></i>Allow AI Matching</label>

                                                        <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" id="aiMatching" name="allow_ai_matching"  data-type="ai_personalization_agreed" value="allow_ai_matching"
                                                        {{ $privacy && $privacy->ai_personalization_agreed ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="emailComm">I agree to AI Personalization</label>
                                                        </div>
                                                    </div>
                                                    <!-- Cookie Preferences -->
                                                    <div class="col-12">
                                                        <a href="#" type="button" class="d-block mb-2" data-bs-toggle="modal" data-bs-target="#cookiePreferencesModal"><i class="bi bi-gear me-2"></i>Manage Cookie Preferences</a>

                                                        <a href="#"><i class="bi bi-shield-lock me-2"></i>View Terms of Use & Privacy Policy</a>
                                                    </div>
                                                    </div>
                                                <hr/>

                                                <div class="delete-account-section">
                                                    <h4>Delete Account</h4>

                                                    <p>
                                                    Are you sure you want to delete your account? This action is permanent and cannot be undone.
                                                    All your data, messages, and coaching history will be permanently removed.
                                                    </p>

                                                    <div class="form-check mb-4">
                                                    <input class="form-check-input" type="checkbox" id="confirmDelete">
                                                    <label class="form-check-label" for="confirmDelete">
                                                        I understand and wish to proceed with account deletion.
                                                    </label>
                                                    </div>

                                                    <button class="btn btn-danger delete-btn" id="deleteAccountBtn" disabled>
                                                    <i class="mdi mdi-delete me-2"></i></i>Delete Account
                                                    </button>
                                                </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    </div>

                                </div>
                                {{-- <div class="tab-pane" id="messages" role="tabpanel" aria-labelledby="messages-tab">
                                    Thired</div> --}}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
          @include('admin.components.model_manage_cookies')
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
                        console.log(result,"check ");
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
    <script>
        $(document).ready(function() {
            $('.notification-toggle').on('change', function () {
              const field = $(this).data('field');
              const userId = $(this).data('user');
              const value = $(this).is(':checked') ? 1 : 0;

              $.ajax({
                url: "{{url('/admin/update-notification-setting')}}",
                method: 'POST',
                datatype: "json",
                data: {
                    _token: '{{ csrf_token() }}',
                    user_id: userId,
                    field: field,
                    value: value
                },
                success: function (response) {
                    // console.log('Notification setting updated:', response);
                    if(response.success){
                      Toastify({
                        text: response.message,
                        duration: 2000
                        }).showToast();
                    }
                },
                error: function (xhr) {
                    console.error('Update failed:', xhr.responseText);
                    alert('Something went wrong. Try again.');
                }
              });
            });

            $('.prof-opt').on('change',function(){
                let visibility =$(this).val();

                let userId = $('input[name="user_id"]').val();

                 $.ajax({
                    url: "{{url('/admin/update-profile-visibility') }}",
                    method: "POST",
                    datatype: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                        user_id: userId,
                        profile_visibility: visibility
                    },
                    success: function (response) {
                        console.log("Updated:", response);
                        Toastify({
                            text: response.message,
                            duration: 2000
                        }).showToast();
                    },
                    error: function (xhr) {
                        console.error("Error:", xhr.responseText);
                    }
                });
            });

            $('.com-pref').on('change', function () {
              let userId = $('input[name="user_id"]').val();
              let settingType = $(this).data('type');
              let isEnabled = $(this).is(':checked') ? 1 : 0;

              $.ajax({
                  url: "{{url('/admin/update-communication-preference') }}",
                  type: 'POST',
                  data: {
                      _token: "{{ csrf_token() }}",
                      user_id: userId,
                      type: settingType,
                      value: isEnabled
                  },
                  success: function (response) {
                      console.log('Updated:', response.message);
                      Toastify({
                            text: response.message,
                            duration: 2000
                        }).showToast();
                  },
                  error: function (xhr) {
                      console.error('Failed:', xhr.responseJSON.message);
                  }
              });
            });

            $('#aiMatching').on('change',function(){
                let userId = $('input[name="user_id"]').val();
                let settingType = $(this).data('type');
                let isEnabled = $(this).is(':checked') ? 1 : 0;

                $.ajax({
                  url: "{{url('/admin/update-ai-personalization') }}",
                  type: 'POST',
                  data: {
                      _token: "{{ csrf_token() }}",
                      user_id: userId,
                      type: settingType,
                      value: isEnabled
                  },
                  success: function (response) {
                    //   console.log('Updated:', response.message);
                      Toastify({
                            text: response.message,
                            duration: 2000
                        }).showToast();
                  },
                  error: function (xhr) {
                      console.error('Failed:', xhr.responseJSON.message);
                  }
              });


            });

            $('.cookie-toggle').on('change', function () {
              let userId = $('input[name="user_id"]').val();
              let settingType = $(this).data('type');
              let value = $(this).is(':checked') ? 1 : 0;

              $.ajax({
                  url: "{{url('/admin/update-cookie-preference')}}",
                  type: 'POST',
                  data: {
                      _token:"{{ csrf_token() }}",
                      user_id: userId,
                      type: settingType,
                      value: value
                  },
                  success: function (res) {
                     Swal.fire({
                      title: "Success!",
                      text: "Status updated!",
                      icon: "success"
                    });
                      // console.log('Updated:', res);
                  },
                   error: function (xhr) {
                      console.error('Failed:', xhr.responseJSON.message);
                  }
              });
            });

            $('#acceptAllCookies').on('click', function () {
              let userId = $('input[name="user_id"]').val();
              let cookieData = {
                  _token:"{{ csrf_token() }}",
                  user_id: userId,
                  accept_all: true // we use this flag in controller
              };
              $.ajax({
                   url: "{{url('/admin/update-cookie-preference')}}",
                  type: 'POST',
                  data: cookieData,
                  success: function (res) {
                      // Update checkboxes in UI
                      $('#essential_cookies').prop('checked', true);
                      $('#performance_cookies').prop('checked', true);
                      $('#functional_cookies').prop('checked', true);
                      $('#marketing_cookies').prop('checked', true);

                      Swal.fire({
                        title: "Success!",
                        text: "Status updated!",
                        icon: "success"
                      });
                  },
                   error: function (xhr) {
                      console.error('Failed:', xhr.responseJSON.message);
                  }
              });
            });

            $('#rejectAllCookies').on('click', function () {
              let userId = $('input[name="user_id"]').val();
              let cookieData = {
                  _token:"{{ csrf_token() }}",
                  user_id: userId,
                  accept_all: false // we use this flag in controller
              };
              $.ajax({
                   url: "{{url('/admin/update-cookie-preference')}}",
                  type: 'POST',
                  data: cookieData,
                  success: function (res) {
                      // Update checkboxes in UI
                      $('#essential_cookies').prop('checked', false);
                      $('#performance_cookies').prop('checked', false);
                      $('#functional_cookies').prop('checked', false);
                      $('#marketing_cookies').prop('checked', false);

                       Swal.fire({
                        title: "Success!",
                        text: "Status updated!",
                        icon: "success"
                      });
                  },
                   error: function (xhr) {
                      console.error('Failed:', xhr.responseJSON.message);
                  }
              });
            });

            $('#updatePasswordForm').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                url: "{{ route('admin.updateUserCoachPassword') }}",
                type: "POST",
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                      if(response.success) {
                            $('#updatePasswordForm')[0].reset();
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });

                        } else {
                            Swal.fire({
                                title: 'Failed!',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'Close'
                            });
                        }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let firstError = Object.values(errors)[0][0];
                         Swal.fire({
                                title: 'Failed!',
                                text: firstError,
                                icon: 'error',
                                confirmButtonText: 'Close'
                            });
                    } else {
                    alert('Something went wrong.');
                    }
                }
                });
            });

        });
    </script>
@endpush

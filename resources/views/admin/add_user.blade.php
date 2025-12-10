@extends('admin.layouts.layout')

@section('content')
    <!-- partial -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <?php
                    $first_name = $last_name = $display_name = $professional_profile = $professional_title = $company_name = $short_bio = $detailed_bio = $exp_and_achievement = $email = $contact_number = $gender = $user_id = $notification = $privacy = '';
                    $delivery_mode = $age_group = $coaching_topics = $your_profession = $goal1 = $goal2 = $goal3 = $coaching_time = $pref_lang = '';
                    $country_id = $state_id = $city_id = 0;
                    if ($user_detail) {
                        $user_id = $user_detail->id;
                        $first_name = $user_detail->first_name;
                        $last_name = $user_detail->last_name;
                    
                        $display_name = $user_detail->display_name;
                        $professional_profile = $user_detail->professional_profile;
                        $professional_title = $user_detail->professional_title;
                        $company_name = $user_detail->company_name;
                        $short_bio = $user_detail->short_bio;
                        $detailed_bio = $user_detail->detailed_bio;
                        $exp_and_achievement = $user_detail->exp_and_achievement;
                    
                        $email = $user_detail->email;
                        $contact_number = $user_detail->contact_number;
                        $gender = $user_detail->gender;
                        $country_id = $user_detail->country_id;
                        $state_id = $user_detail->state_id;
                        $city_id = $user_detail->city_id;
                    
                        $notification = $user_detail->notificationSettings ?? null;
                        $privacy = $user_detail->privacySettings ?? null;
                        $age_group = $user_detail->age_group ?? '';
                        $goal1 = $user_detail->coaching_goal_1 ?? '';
                        $goal2 = $user_detail->coaching_goal_2 ?? '';
                        $goal3 = $user_detail->coaching_goal_3 ?? '';
                        $coaching_time = $user_detail->coaching_time ?? '';
                        $delivery_mode = $user_detail->delivery_mode ?? '';
                        $pref_lang = $user_detail->pref_lang ?? '';
                        $coaching_topics = $user_detail->coaching_topics ?? '';
                        $your_profession = $user_detail->user_profession ?? '';
                    }
                    ?>
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ route('admin.userList') }}" class="btn btn-outline-info btn-fw"
                                style="float: right;">User List</a>
                            <h4 class="card-title">User Management</h4>
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="user-addupdate-tab" data-bs-toggle="tab"
                                        data-bs-target="#userAddUpdate" type="button" role="tab"
                                        aria-controls="addupdate" aria-selected="false" tabindex="-1">Add / Update
                                        user</button>
                                </li>
                                <!-- <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="user-settingupdate-tab" data-bs-toggle="tab"
                                        data-bs-target="#userSettingUpdate" type="button" role="tab"
                                        aria-controls="settingupdate" aria-selected="true"
                                        @if (!$user_id) disabled @endif>Profile Setting</button>
                                </li> -->
                            </ul>


                            <div class="tab-content">

                                <div class="tab-pane  fade show active" id="userAddUpdate" role="tabpanel"
                                    aria-labelledby="user-addupdate-tab">
                                    {{-- <p class="card-description"> Add / Update user  </p> --}}
                                    <form class="forms-sample" method="post" action="{{ route('admin.addUser') }}"
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
                                                <label for="exampleInputUsername1">Display Name</label>
                                                <input required type="text" class="form-control form-control-sm"
                                                    placeholder="Display Name" aria-label="Username" name="display_name"
                                                    value="{{ $display_name }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputUsername1">Professional Profile</label>
                                                <input required type="text" class="form-control form-control-sm"
                                                    placeholder="Professional Profile" aria-label="Username"
                                                    name="professional profile" value="{{ $professional_profile }}">
                                            </div>
                                            {{-- <div class="form-group col-md-6">
                                  <label for="exampleInputUsername1">Professional Title</label>
                                  <input required type="text" class="form-control form-control-sm" placeholder="Professional Title" aria-label="Username" name="professional_title" value="{{$professional_title}}">
                                </div> --}}
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Email address</label>
                                                <input required type="email" class="form-control form-control-sm @error('email') is-invalid @enderror"
                                                    id="exampleInputEmail1" placeholder="Email" name="email"
                                                    value="{{ old('email', $email) }}">
                                                      @error('email')
                                                        <span class="text-danger">{{ $message }}</span>
                                                       @enderror
                                            </div>
                                            {{-- <div class="form-group col-md-6">
                                  <label for="exampleInputUsername1">Company Name</label>
                                  <input required type="text" class="form-control form-control-sm" placeholder="Company Name" aria-label="Username" name="company_name" value="{{$company_name}}">
                                </div> --}}
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Contact Number</label>
                                                <input required type="text" class="form-control form-control-sm"
                                                    id="exampleInputEmail1" placeholder="Contact Number"
                                                    name="contact_number" value="{{ $contact_number }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Age Group</label>
                                                <select required class="form-select form-select-sm"
                                                    id="exampleFormControlSelect3" name="age_group">
                                                    @if ($ageGroup)
                                                        <option value="" disabled selected>Select Age Group</option>
                                                        @foreach ($ageGroup as $group)
                                                            <option value="{{ $group->id }}"
                                                                {{ $age_group == $group->id ? 'selected' : '' }}>
                                                                {{ $group->group_name }} ({{ $group->age_range }})
                                                            </option>
                                                        @endforeach
                                                    @else
                                                        <option value="" disabled>No age groups available</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputCoachingTopics">Prefered Coaching Topics</label>
                                                <input required type="text" class="form-control form-control-sm"
                                                    placeholder="Public Speaking , Leadership , Emotional Intelligence"
                                                    aria-label="CoachingTopics" name="coaching_topics"
                                                    value="{{ $coaching_topics }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputYourprofession">Your Profession</label>
                                                <input required type="text" class="form-control form-control-sm"
                                                    placeholder="" aria-label="YourProfession" name="your_profession"
                                                    value="{{ $your_profession }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Password</label>
                                                  @if(empty($user_id))
                                                <input type="password" class="form-control form-control-sm"
                                                id="exampleInputEmail1" placeholder="Password" name="password" required>
                                                @else
                                                    <input type="password" class="form-control form-control-sm"
                                                id="exampleInputEmail1" placeholder="Password" name="password" >
                                                    <small>Leave blank if you don't want to change the password.</small>
                                                @endif
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Gender</label>
                                                <select required class="form-select form-select-sm"
                                                    id="exampleFormControlSelect3" name="gender">
                                                    <option value="1" {{ $gender == 1 ? 'selected' : '' }}>Male</option>
                                                    <option value="2" {{ $gender == 2 ? 'selected' : '' }}>Female</option>
                                                    <option value="3" {{ $gender == 3 ? 'selected' : '' }}>Other</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Country</label>
                                                <select required class="form-select form-select-sm" id="country"
                                                    name="country_id">
                                                    <option value="">Select Country</option>
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
                                                    <option value="">Select State</option>
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
                                                    <option value="">Select City</option>
                                                    @if ($city)
                                                        @foreach ($city as $cities)
                                                            <option value="{{ $cities->city_id }}"
                                                                {{ $city_id == $cities->city_id ? 'selected' : '' }}>
                                                                {{ $cities->city_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            <hr />
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputCoachingGoal1">Coaching Goal #1</label>
                                                <input required type="text" class="form-control form-control-sm"
                                                    placeholder="" aria-label="CoachingGoal1" name="coaching_goal1"
                                                    value="{{ $goal1 }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputCoachingGoal2">Coaching Goal #2</label>
                                                <input required type="text" class="form-control form-control-sm"
                                                    placeholder="" aria-label="CCoachingGoal2" name="coaching_goal2"
                                                    value="{{ $goal2 }}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputCoachingGoal3">Coaching Goal #3</label>
                                                <input required type="text" class="form-control form-control-sm"
                                                    placeholder="" aria-label="CoachingGoal3" name="coaching_goal3"
                                                    value="{{ $goal3 }}">
                                            </div>

                                            <!-- <div class="form-group col-md-6">
                                                <label for="coaching-time">Preferred Coaching Timings</label>
                                                <select required class="form-select form-select-sm" id="coaching-time"
                                                    name="coaching_time">
                                                    <option value="" disabled>e.g., Weekdays evening, after 7pm,
                                                        Saturday morning</option>
                                                    @if ($coachingTiming)
                                                        @foreach ($coachingTiming as $timing)
                                                            <option value="{{ $timing->id }}"
                                                                {{ $coaching_time == $timing->id ? 'selected' : '' }}>
                                                                {{ $timing->timing_label }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div> -->


                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Delivery Mode</label>
                                                <select required class="form-select form-select-sm"
                                                    id="exampleFormControlSelect3" name="delivery_mode">
                                                    @if ($mode)
                                                        <option value="" disabled selected>Select Mode</option>
                                                        @foreach ($mode as $modes)
                                                            <option value="{{ $modes->id }}"
                                                                {{ $delivery_mode == $modes->id ? 'selected' : '' }}>
                                                                {{ $modes->mode_name }}</option>
                                                        @endforeach
                                                    @endif


                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Preferred Language</label>
                                                <select required class="form-select form-select-sm"
                                                    id="exampleFormControlSelect3" name="prefered_lang">
                                                    @if ($language)
                                                        <option value="" disabled selected>Select Preferred Language
                                                        </option>
                                                        @foreach ($language as $lang)
                                                            <option value="{{ $lang->id }}"
                                                                {{ $pref_lang == $lang->id ? 'selected' : '' }}>
                                                                {{ $lang->language }}</option>
                                                        @endforeach
                                                    @endif

                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="exampleInputUsername1">Short Bio</label>
                                                <textarea required type="text" class="form-control textarea.form-control-lg" placeholder="Short Bio"
                                                    aria-label="Username" name="short_bio">{{ $short_bio }}</textarea>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Profile Image</label>
                                                <input type="file" class="form-control form-control-sm"
                                                    id="exampleInputEmail1" name="profile_image"
                                                    accept="image/png, image/gif, image/jpeg">
                                            </div>
                                        </div>
                                        <input type="hidden" name="user_time" value="" id="user_timezone">
                                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="userSettingUpdate" role="tabpanel"
                                    aria-labelledby="user-settingupdate-tab">
                                    <div class="content-wrapper">
                                        <div class="row">
                                            <div class="col-12 grid-margin stretch-card">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h4 class="card-title">Change Password</h4>
                                                        <form id="updatePasswordForm" class="form-group">
                                                            <input type="hidden" name="user_id"
                                                                value="{{ $user_id }}"> {{-- Pass user ID here --}}
                                                            <div class="row g-3">
                                                                <div class="col-12 col-md-4">
                                                                    <label for="newPassword" class="form-label">New
                                                                        Password</label>
                                                                    <input type="password" class="form-control"
                                                                        id="newPassword" name="new_password"
                                                                        placeholder="New Password">
                                                                </div>
                                                                <div class="col-12 col-md-4">
                                                                    <label for="confirmPassword"
                                                                        class="form-label">Confirm Password</label>
                                                                    <input type="password" class="form-control"
                                                                        id="confirmPassword"
                                                                        name="new_password_confirmation"
                                                                        placeholder="Confirm Password">
                                                                </div>
                                                            </div>
                                                            <div class="mt-4">
                                                                <button type="submit" class="btn btn-primary">Save
                                                                    Changes</button>
                                                            </div>
                                                        </form>


                                                        <hr />

                                                        <h4 class="card-title">Notifications</h4>

                                                        <div class="row g-3">

                                                            <div
                                                                class="col-12 col-md-6 col-lg-4 d-flex justify-content-between align-items-center">
                                                                <label class="notification-label">New Coach Match
                                                                    Alert</label>
                                                                <div class="form-check form-switch custom-switch">
                                                                    <input class="form-check-input notification-toggle"
                                                                        type="checkbox" name="new_coach_match_alert"
                                                                        data-field="new_coach_match_alert"
                                                                        data-user="{{ $user_id }}"
                                                                        {{ $notification && $notification?->new_coach_match_alert ? 'checked' : '' }}>
                                                                </div>
                                                            </div>

                                                            <div
                                                                class="col-12 col-md-6 col-lg-4 d-flex justify-content-between align-items-center">
                                                                <label class="notification-label">Message
                                                                    Notifications</label>
                                                                <div class="form-check form-switch custom-switch">
                                                                    <input class="form-check-input notification-toggle"
                                                                        type="checkbox" name="message_notifications"
                                                                        data-field="message_notifications"
                                                                        data-user="{{ $user_id }}"
                                                                        {{ $notification && $notification?->message_notifications ? 'checked' : '' }}>
                                                                </div>
                                                            </div>

                                                            <div
                                                                class="col-12 col-md-6 col-lg-4 d-flex justify-content-between align-items-center">
                                                                <label class="notification-label">Booking Reminders</label>
                                                                <div class="form-check form-switch custom-switch">
                                                                    <input class="form-check-input notification-toggle"
                                                                        type="checkbox" name="booking_reminders"
                                                                        data-field="booking_reminders"
                                                                        data-user="{{ $user_id }}"
                                                                        {{ $notification && $notification?->booking_reminders ? 'checked' : '' }}>
                                                                </div>
                                                            </div>

                                                            <div
                                                                class="col-12 col-md-6 col-lg-4 d-flex justify-content-between align-items-center">
                                                                <label class="notification-label">Platform
                                                                    Announcements</label>
                                                                <div class="form-check form-switch custom-switch">
                                                                    <input class="form-check-input notification-toggle"
                                                                        type="checkbox" name="platform_announcements"
                                                                        data-field="platform_announcements"
                                                                        data-user="{{ $user_id }}"
                                                                        {{ $notification && $notification?->platform_announcements ? 'checked' : '' }}>
                                                                </div>
                                                            </div>

                                                            <div
                                                                class="col-12 col-md-6 col-lg-4 d-flex justify-content-between align-items-center">
                                                                <label class="notification-label">Blog / Article
                                                                    Recommendations</label>
                                                                <div class="form-check form-switch custom-switch">
                                                                    <input class="form-check-input notification-toggle"
                                                                        type="checkbox" name="blog_recommendations"
                                                                        data-field="blog_recommendations"
                                                                        data-user="{{ $user_id }}"
                                                                        {{ $notification && $notification?->blog_recommendations ? 'checked' : '' }}>
                                                                </div>
                                                            </div>

                                                            <div
                                                                class="col-12 col-md-6 col-lg-4 d-flex justify-content-between align-items-center">
                                                                <label class="notification-label">Billing Updates</label>
                                                                <div class="form-check form-switch custom-switch">
                                                                    <input class="form-check-input notification-toggle"
                                                                        type="checkbox" name="billing_updates"
                                                                        data-field="billing_updates"
                                                                        data-user="{{ $user_id }}"
                                                                        {{ $notification && $notification?->billing_updates ? 'checked' : '' }}>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <hr />

                                                        <h4 class="card-title">Data & Privacy Control</h4>
                                                        <div class="row g-3">
                                                            <!-- Profile Visibility -->
                                                            <div class="col-12">
                                                                <label class="form-label fw-bold"><i
                                                                        class="bi bi-person-circle me-2"></i>Profile
                                                                    Visibility</label>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input prof-opt"
                                                                        type="radio" name="prof-vis" id="public"
                                                                        value="public"
                                                                        {{ $privacy && $privacy->profile_visibility === 'public' ? 'checked' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="public">Public</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input prof-opt"
                                                                        type="radio" name="prof-vis" id="private"
                                                                        value="private"
                                                                        {{ $privacy && $privacy->profile_visibility === 'private' ? 'checked' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="private">Private</label>
                                                                </div>
                                                            </div>
                                                            <!-- Communication Preferences -->
                                                            <div class="col-12">
                                                                <label class="form-label fw-bold"><i
                                                                        class="bi bi-chat-dots me-2"></i>Communication
                                                                    Preference</label>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input com-pref"
                                                                        type="checkbox" id="emailComm"
                                                                        name="communication_email"
                                                                        data-type="communication_email"
                                                                        {{ $privacy && $privacy->communication_email ? 'checked' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="emailComm">Email</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input com-pref"
                                                                        type="checkbox" id="inAppComm"
                                                                        name="communication_in_app"
                                                                        data-type="communication_in_app"
                                                                        {{ $privacy && $privacy->communication_in_app ? 'checked' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="inAppComm">In-App</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input com-pref"
                                                                        type="checkbox" id="pushComm"
                                                                        name="communication_push"
                                                                        data-type="communication_push"
                                                                        {{ $privacy && $privacy->communication_push ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="pushComm">Push
                                                                        Toggles</label>
                                                                </div>
                                                            </div>
                                                            <!-- AI Matching -->
                                                            <div class="col-12">
                                                                <label class="form-label fw-bold"><i
                                                                        class="bi bi-chat-dots me-2"></i>Allow AI
                                                                    Matching</label>

                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="aiMatching" name="allow_ai_matching"
                                                                        data-type="ai_personalization_agreed"
                                                                        value="allow_ai_matching"
                                                                        {{ $privacy && $privacy->ai_personalization_agreed ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="emailComm">I
                                                                        agree to AI Personalization</label>
                                                                </div>
                                                            </div>
                                                            <!-- Cookie Preferences -->
                                                            <div class="col-12">
                                                                <a href="#" type="button" class="d-block mb-2"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#cookiePreferencesModal"><i
                                                                        class="bi bi-gear me-2"></i>Manage Cookie
                                                                    Preferences</a>

                                                                <a href="#"><i
                                                                        class="bi bi-shield-lock me-2"></i>View Terms of
                                                                    Use & Privacy Policy</a>
                                                            </div>
                                                        </div>
                                                        <hr />

                                                        <div class="delete-account-section">
                                                            <h4>Delete Account</h4>

                                                            <p>
                                                                Are you sure you want to delete your account? This action is
                                                                permanent and cannot be undone.
                                                                All your data, messages, and coaching history will be
                                                                permanently removed.
                                                            </p>

                                                            <div class="form-check mb-4">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="confirmDelete">
                                                                <label class="form-check-label" for="confirmDelete">
                                                                    I understand and wish to proceed with account deletion.
                                                                </label>
                                                            </div>

                                                            <button class="btn btn-danger delete-btn"
                                                                id="deleteAccountBtn" disabled>
                                                                <i class="mdi mdi-delete me-2"></i></i>Delete Account
                                                            </button>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>

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

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: "Success!",
                    text: "{{ session('success') }}",
                    icon: "success",
                    confirmButtonText: "OK"
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "{{ session('error') }}",
                    confirmButtonText: "OK"
                });
            });
        </script>
    @endif

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
                        //console.log(result);
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

            $('.notification-toggle').on('change', function() {
                const field = $(this).data('field');
                const userId = $(this).data('user');
                const value = $(this).is(':checked') ? 1 : 0;

                $.ajax({
                    url: "{{ url('/admin/update-notification-setting') }}",
                    method: 'POST',
                    datatype: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        user_id: userId,
                        field: field,
                        value: value
                    },
                    success: function(response) {
                        // console.log('Notification setting updated:', response);
                        if (response.success) {
                            Toastify({
                                text: response.message,
                                duration: 2000
                            }).showToast();
                        }
                    },
                    error: function(xhr) {
                        console.error('Update failed:', xhr.responseText);
                        alert('Something went wrong. Try again.');
                    }
                });
            });

            $('.prof-opt').on('change', function() {
                let visibility = $(this).val();

                let userId = $('input[name="user_id"]').val();

                $.ajax({
                    url: "{{ url('/admin/update-profile-visibility') }}",
                    method: "POST",
                    datatype: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                        user_id: userId,
                        profile_visibility: visibility
                    },
                    success: function(response) {
                        console.log("Updated:", response);
                    },
                    error: function(xhr) {
                        console.error("Error:", xhr.responseText);
                    }
                });
            })

            $('.com-pref').on('change', function() {
                let userId = $('input[name="user_id"]').val();
                let settingType = $(this).data('type');
                let isEnabled = $(this).is(':checked') ? 1 : 0;

                $.ajax({
                    url: "{{ url('/admin/update-communication-preference') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        user_id: userId,
                        type: settingType,
                        value: isEnabled
                    },
                    success: function(response) {
                        console.log('Updated:', response.message);
                    },
                    error: function(xhr) {
                        console.error('Failed:', xhr.responseJSON.message);
                    }
                });
            });

            $('#aiMatching').on('change', function() {
                let userId = $('input[name="user_id"]').val();
                let settingType = $(this).data('type');
                let isEnabled = $(this).is(':checked') ? 1 : 0;

                $.ajax({
                    url: "{{ url('/admin/update-ai-personalization') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        user_id: userId,
                        type: settingType,
                        value: isEnabled
                    },
                    success: function(response) {
                        console.log('Updated:', response.message);
                    },
                    error: function(xhr) {
                        console.error('Failed:', xhr.responseJSON.message);
                    }
                });


            });

            $('.cookie-toggle').on('change', function() {
                let userId = $('input[name="user_id"]').val();
                let settingType = $(this).data('type');
                let value = $(this).is(':checked') ? 1 : 0;

                $.ajax({
                    url: "{{ url('/admin/update-cookie-preference') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        user_id: userId,
                        type: settingType,
                        value: value
                    },
                    success: function(res) {
                        Swal.fire({
                            title: "Success!",
                            text: "Status updated!",
                            icon: "success"
                        });
                        // console.log('Updated:', res);
                    },
                    error: function(xhr) {
                        console.error('Failed:', xhr.responseJSON.message);
                    }
                });
            });

            $('#acceptAllCookies').on('click', function() {
                let userId = $('input[name="user_id"]').val();
                let cookieData = {
                    _token: "{{ csrf_token() }}",
                    user_id: userId,
                    accept_all: true // we use this flag in controller
                };
                $.ajax({
                    url: "{{ url('/admin/update-cookie-preference') }}",
                    type: 'POST',
                    data: cookieData,
                    success: function(res) {
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
                    error: function(xhr) {
                        console.error('Failed:', xhr.responseJSON.message);
                    }
                });
            });

            $('#rejectAllCookies').on('click', function() {
                let userId = $('input[name="user_id"]').val();
                let cookieData = {
                    _token: "{{ csrf_token() }}",
                    user_id: userId,
                    accept_all: false // we use this flag in controller
                };
                $.ajax({
                    url: "{{ url('/admin/update-cookie-preference') }}",
                    type: 'POST',
                    data: cookieData,
                    success: function(res) {
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
                    error: function(xhr) {
                        console.error('Failed:', xhr.responseJSON.message);
                    }
                });
            });

            $('#updatePasswordForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('admin.updateUserCoachPassword') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
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
                    error: function(xhr) {
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

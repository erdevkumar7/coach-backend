<div class="modal fade" id="cookiePreferencesModal" tabindex="-1" aria-labelledby="cookieModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-sm rounded-4">
      <div class="modal-header border-bottom-0">
        <h5 class="modal-title fw-bold" id="cookieModalLabel">Manage Cookies</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body pt-0">
        <p class="small text-muted">
          CoachSparkle uses cookies to enhance your experience, personalize content, and analyze traffic. You can manage your preferences anytime.
        </p>

        <div class="border rounded-3 p-2 mb-3">
          @php $privacy = $user_detail->privacySettings ?? null; @endphp

          @foreach([
              'essential_cookies' => ['Essential Cookies', 'Necessary for login, security and session functionality'],
              'performance_cookies' => ['Performance Cookies', 'Help us to improve the platform through usage analytics.'],
              'functional_cookies' => ['Functional Cookies', 'Remember preferences, such as language.'],
              'marketing_cookies' => ['Marketing Cookies', 'Used to personalized advertising'],
          ] as $key => [$label, $desc])
            <div class="d-flex justify-content-between align-items-start py-2 border-bottom">
              <div>
                <strong>{{ $label }}</strong>
                <p class="small text-muted mb-0">{{ $desc }}</p>
              </div>
              <div class="form-check form-switch">
                <input class="form-check-input cookie-toggle"
                       type="checkbox"
                       id="{{ $key }}"
                       data-type="{{ $key }}"
                       {{ $privacy && $privacy->$key ? 'checked' : '' }}>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <div class="modal-footer border-top-0 d-flex justify-content-between">
        <button class="btn btn-outline-primary" id="rejectAllCookies">Reject Cookies</button>
        <button class="btn btn-outline-secondary" id="customizeCookies">Customize Settings</button>
        <button class="btn btn-primary" id="acceptAllCookies">Accept All Cookies</button>
      </div>
    </div>
  </div>
</div>

@extends('admin.layouts.layout')

@section('content')
<style>
  .ck-editor__editable { min-height: 300px !important; }
</style>

<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
                <a href="{{route('admin.coachList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Back</a>
                  <h4 class="card-title">Upgrade Plan</h4>
                    <form id="UpgradeplanForm" method="post" action="{{ route('admin.upgradePlanSubmit') }}">
                        @csrf

                        <input type="hidden" name="user_id" value="{{ $coach_id }}">

                        <div class="form-group col-md-12">
                            <label>Select Plan</label>
                            <select required class="form-control form-control-sm" name="plan_id">
                                <option value="">-- Select Plan --</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}">
                                        {{ $plan->plan_name }} - ${{ number_format($plan->plan_amount, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('plan_id') 
                                <span class="text-danger">{{ $message }}</span> 
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

  @push('scripts')


    <script>
      $(document).ready(function () {
        $('#UpgradeplanForm').validate({
          rules: {
            plan_id: {
              required: true,
            },
          },
          messages: {
            plan_id: {
              required: "Please select a plan",
            },
          },
          errorElement: 'span',
          errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
          },
          highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
          },
          unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
          }
        });
      });
    </script>
  @endpush
@endsection

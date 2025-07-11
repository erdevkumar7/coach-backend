@extends('admin.layouts.layout')

@section('content')
<style>
  .ck-editor__editable {
    min-height: 300px !important; /* Or whatever height you want */
  }
</style>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
              @php
                   $faq_id= $title = $content = $faq_cat_id = $fq_stat ="";

                    if (isset($faqs)) {
                        $faq_id= $faqs->id;
                        $title = $faqs->title;
                        $content = $faqs->content;
                        $faq_cat_id = $faqs->faq_category_id;
                        $fq_stat = $faqs->status;
                    }

                @endphp


                <div class="card">
                  <div class="card-body">
                    <a href="{{ route('admin.faqs.index') }}" class="btn btn-outline-info btn-fw" style="float: right;">FAQa List</a>
                    <h4 class="card-title">FAQs Management</h4>
                    <!--p class="card-description"> Add / Update Blog  </p-->
                    <form class="forms-sample" method="post" action="{{route('admin.addFaqs')}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                      <div class="row">
                        <div class="form-group col-md-6">
                          <input type="hidden" name="faq_id" value="{{ $faq_id ?? ''}}">
                          <label for="exampleInputFAQtitle">FAQ Title</label>
                          <input  type="text" class="form-control form-control-sm"
                           placeholder="Enter FAQs Title" aria-label="FAQtitle"
                            name="faq_title"
                            value="{{ old('faq_title', $faqs->title ?? '') }}">
                        </div>
                        <div class="form-group col-md-12">
                          <label for="faq-content">FAQ Content</label>
                          <textarea  class="form-control form-control-sm" id="faq-content" name="faq_content" placeholder="Enter FAQs content here..." >{{ old('faq_content', $faqs->content ?? '') }}</textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="target_audience">Target Audience</label>
                            <select class="form-select form-control-sm" name="faq_category_id" required>
                                <option>-- Select Audience --</option>
                                @if ($audiance)
                                @foreach ($audiance as $el )
                                     <option value="{{$el->id}}" {{  $faq_cat_id==$el->id?'selected':'' }}>{{ $el->name }}</option>
                                @endforeach

                                @endif
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                        <label for="status">Status</label>
                        <select class="form-select form-control-sm" name="status" required>
                            <option value="">-- Select Status --</option>
                                <option value="0" {{$fq_stat==0?'selected':'' }}>Inactive</option>
                                <option value="1" {{$fq_stat==1?'selected':''}}>Active</option>
                        </select>
                        </div>
                      </div>
                      {{-- <input type="hidden" name="user_time" value="" id="user_timezone"> --}}
                      <button type="submit" class="btn btn-primary me-2">Submit</button>
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

        @if(session('success'))
          <script>
              document.addEventListener('DOMContentLoaded', function () {
                  Swal.fire({
                      title: "Success!",
                      text: "{{ session('success') }}",
                      icon: "success",
                      confirmButtonText: "OK"
                  });
              });
          </script>
        @endif

        @if(session('error'))
          <script>
              document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: "{{ session('error') }}",
                  confirmButtonText: "OK"
                });
              });
          </script>
        @endif

        <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
        <script>
            ClassicEditor
                .create(document.querySelector('#faq-content'))
                .catch(error => {
                    console.error(error);
                });
        </script>
        @endpush

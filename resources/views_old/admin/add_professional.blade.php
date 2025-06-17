@extends('admin.layouts.layout')

@section('content')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <?php
                  $first_name=$last_name=$email=$gender=$user_id=$detailed_bio="";
                  $country_id=$state_id=$city_id=0;
                  if($user_detail)
                  {
                    $user_id=$user_detail->id;
                    $first_name=$user_detail->first_name;
                    $last_name=$user_detail->last_name;
                    $email=$user_detail->email;
                    $gender=$user_detail->gender;
                    $country_id=$user_detail->country_id;
                    $state_id=$user_detail->state_id;
                    $city_id=$user_detail->city_id;
                    $detailed_bio=$user_detail->detailed_bio;
                  }

                  $video_link=$experience=$coaching_category=$delivery_mode=$free_trial_session=$is_volunteered_coach="";
                  $volunteer_coaching=$website_link=$objective="";
                  $price=0;
                  if($profession)
                  {
                    $video_link=$profession->video_link;
                    $experience=$profession->experience;
                    $coaching_category=$profession->coaching_category;
                    $delivery_mode=$profession->delivery_mode;
                    $free_trial_session=$profession->free_trial_session;
                    $price=$profession->price;
                    $is_volunteered_coach=$profession->is_volunteered_coach;
                    $volunteer_coaching=$profession->volunteer_coaching;
                    $website_link=$profession->website_link;
                    $objective=$profession->objective;
                  }
                ?>
                <div class="card">
                  <div class="card-body">
                    <a href="{{route('admin.coachList')}}" class="btn btn-outline-info btn-fw" style="float: right;">Coach List</a>
                    <h4 class="card-title">User Management</h4>
                    <p class="card-description"> Add / Update Professional  </p>
                    <p>Coach : {{$first_name.' '.$last_name}}</p>
                    <form class="forms-sample" method="post" action="{{route('admin.addProfessional')}}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                      <div class="row">
                        <input type="hidden" name="user_id" value="{{$user_id}}">
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Experiance(In year)</label>
                          <input type="text" class="form-control form-control-sm" placeholder="Experiance(In year)" maxlength="2" name="experiance" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="{{$experience}}">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputUsername1">Price($)</label>
                          <input type="text" class="form-control form-control-sm" placeholder="price($)" maxlength="5" name="price" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="{{$price}}">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Video Introduction</label>
                          <input type="text" class="form-control form-control-sm" id="video-introduction" placeholder="Video Introduction" pattern="https?://.+" name="video_introduction" value="{{$video_link}}">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Website</label>
                          <input type="text" class="form-control form-control-sm" id="Website" placeholder="Website" pattern="https?://.+" name="website" value="{{$website_link}}">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Objective of Coaching/Learning</label>
                          <input type="text" class="form-control form-control-sm" id="objective" placeholder="Objective of Coaching/Learning" name="objective" value="{{$objective}}">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="exampleInputEmail1">Detailed Bio</label>
                          <textarea class="form-control form-control-sm" name="detailed_bio" maxlength="1000" id="short_bio">{{$detailed_bio}}</textarea>
                          <small id="bioCounter">1000 characters remaining</small>
                        </div>
                      </div>
                      <div id="documentContainer">
                        @if($document)
                        @php $i=1; @endphp
                        @foreach($document as $documents)
                        <div class="row document-group">
                          <div class="form-group col-md-5">
                            <label>Document</label>
                            <input type="hidden" name="doc_id[]" value="{{$documents->id}}">
                            <input type="file" class="form-control form-control-sm document-input" name="document_file[]" accept="application/pdf">
                            @if(!empty($documents->document_file))
                              <div class="mt-1 uploaded-file">
                                <a href="{{ asset('/public/uploads/documents/' . $documents->document_file) }}" target="_blank">{{ $documents->original_name }}</a>
                              </div>
                            @endif
                          </div>
                          <div class="form-group col-md-5">
                            <label>Document Type</label>
                            <select class="form-select form-select-sm" name="document_type[]">
                              <option value="1" {{ $documents->document_type == 1 ? 'selected' : '' }}>Certificate</option>
                              <option value="2" {{ $documents->document_type == 2 ? 'selected' : '' }}>CV</option>
                              <option value="3" {{ $documents->document_type == 3 ? 'selected' : '' }}>Brochure</option>
                            </select>
                          </div>
                          <div class="form-group col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-danger btn-rounded btn-icon remove-document" file_id="{{$documents->id}}">
                              <i class="mdi mdi-minus text-dark"></i>
                            </button>
                          </div>
                        </div>
                        @php $i++; @endphp
                        @endforeach
                        @endif
                        @if($i<5)
                        <div class="row document-group">
                          <div class="form-group col-md-5">
                            <label>Document</label>
                            <input type="file" class="form-control form-control-sm document-input" name="document_file[]" accept="application/pdf">
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
                            <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon" id="addMoreDocuments">
                              <i class="mdi mdi-plus text-dark"></i>
                            </button>
                          </div>
                        </div>
                        @endif
                      </div>

                      
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
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const bio = document.getElementById('short_bio');
                const counter = document.getElementById('bioCounter');
                const max = 1000;

                function updateCounter() {
                    const remaining = max - bio.value.length;
                    counter.textContent = `${remaining} characters remaining`;
                }

                bio.addEventListener('input', updateCounter);
                updateCounter(); // initial update
            });
        </script>
        <script>
          $(document).on('click', '#addMoreDocuments', function () {
            // Limit to 5 document upload fields
            if ($('#documentContainer .document-group').length >= 5) {
              alert("You can only upload up to 5 documents.");
              return;
            }

            const newRow = `
              <div class="row document-group mb-2">
                <div class="form-group col-md-5">
                  <label>Document</label>
                  <input type="file" name="document_file[]" class="form-control form-control-sm document-input" accept="application/pdf">
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

          $(document).on('click', '.remove-document', function () {
            const fileId = $(this).attr('file_id');
            const row = $(this).closest('.document-group');

            if (fileId) {
              // Optional: Confirm deletion
              if (!confirm("Are you sure you want to delete this file?")) return;

              $.ajax({
                url: "{{url('/admin/deleteDocument')}}",
                type: 'POST',
                data: {
                  '_token':'{{csrf_token()}}',
                  id: fileId
                },
                success: function (response) {
                  if (response.success) {
                    row.remove();
                  } else {
                    alert("Error deleting document.");
                  }
                },
                error: function () {
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
          $(document).on('change', '.document-input', function () {
            const file = this.files[0];
            const parent = $(this).closest('.form-group');
            
            parent.find('.uploaded-file').remove();
            parent.find('.new-upload-preview').remove();

            if (file && file.type === 'application/pdf') {
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
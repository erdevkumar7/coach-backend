@extends('admin.layouts.layout')
@section('content')
<style>
  .ck-editor__editable {
    min-height: 300px !important;
  }
</style>

<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Contact</h4>

            <form id="homeSectionForm" class="forms-sample" method="post" action="{{ route('admin.contact') }}" enctype="multipart/form-data">
              @csrf
              <div class="row">

                <div class="form-group col-md-12">
                  <label>Title</label>
                  <input required type="text" class="form-control form-control-sm" placeholder="Enter Title" name="title" value="{{ $contact->title ?? '' }}">
                </div>

                  <div class="form-group col-md-12">
                    <label>Subtitle</label>
                    <textarea class="form-control form-control-sm" name="subtitle" placeholder="Enter subtitle...">{{ $contact->subtitle ?? '' }}</textarea>
                  </div>                
        
                   <div class="form-group col-md-6">                          
                      <label>Image </label>
                        <input type="file" class="form-control form-control-sm document-input" name="image" 
                        accept=".jpg,.jpeg,.jfif,.png,.webp">

                         @if(!empty($contact->image))
                                  <div class="mt-1 uploaded-file">
                                      <a href="{{ asset('/public/uploads/blog_files/' . $contact->image) }}" target="_blank">
                                          {{ $contact->image }}
                                      </a>
                                  </div>
                          @endif
                    </div>
                    
                <div class="form-group col-md-12">
                  <label>Email</label>
                  <input required type="email" class="form-control form-control-sm" placeholder="Enter Email" name="email" value="{{ $contact->email ?? '' }}">
                </div>

                <div class="form-group col-md-12">
                <label>Address</label>
                <textarea class="form-control form-control-sm" name="address" placeholder="Enter Address...">{{ $contact->address ?? '' }}</textarea>
              </div>       
              
               <div class="form-group col-md-12">
                <label>Business Hours</label>
                <textarea class="form-control form-control-sm" name="business_hourse" placeholder="Enter Business Hourse...">{{ $contact->business_hourse ?? '' }}</textarea>
              </div>               

              </div>

              <button type="submit" class="btn btn-primary me-2">Submit</button>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>



@endsection

@extends('admin.layouts.layout')
@section('content')
<style>
  i.mdi {
    font-size: 18px;
  }
</style>
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">             
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Support Request</h4>
                    <p class="card-description"> Support Request List 
                    </p>
                      @csrf
                      <div class="table-responsive">
                        <table class="table table-striped" id="example">
                          <thead>
                            <tr>
                              <th><input type="checkbox" id="selectAll"></th>
                              <th>S.NO </th>
                              <th>Name </th>                            
                              <th>Email </th>                            
                              <th>User Type </th>                            
                              <th>Reason For Contact</th>
                              <th>Subject</th>
                              <th>Description</th>
                              <th>Image</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if($supportRequest)
                           @php
                              $i = ($supportRequest->currentPage() - 1) * $supportRequest->perPage() + 1;
                          @endphp
                            @foreach($supportRequest as $list)
                            <tr>
                              <td><input type="checkbox" name="ids[]" value="{{ $list->id }}" class="selectBox"></td>
                              <td>{{$i}}</td>
                              <td> {{$list->name ?? ''}} </td>
                              <td> {{$list->email ?? ''}} </td>
                              <td>
                                {{ $list->user_type == 2 ? 'User' : ($list->user_type == 3 ? 'Coach' : '') }}
                              </td>

                              <td>{{$list->reason ?? ''}} </td>
                              <td>{{$list->subject ?? ''}} </td>
                              <td>{{$list->description ?? ''}} </td>
                              <td> <a href="{{asset('/public/uploads/support_request/' . $list->screenshot)}}" target="_blank">{{$list->screenshot}}</a></td>
                            </tr>
                            @php $i++; @endphp 
                            @endforeach
                            @endif
                          </tbody>
                        </table>
                      </div>
                    <div class="d-flex add-pagination mt-4">
                        {{ $supportRequest->links('pagination::bootstrap-4') }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        @endsection
       
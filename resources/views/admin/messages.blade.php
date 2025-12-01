@foreach($messages as $msg)
    @php
        $isSender = $msg->sender_id == $user_id;
         $sender = \App\Models\User::find($receiver_id);
        $senderProfilePicUrl = $sender && $sender->profile_image ? asset('public/uploads/profile_image/' . $sender->profile_image) : asset('public/img/user.png');
    @endphp

    @if($isSender)
        <div class="row no-gutters right">
            <div class="col-md-6 right-side-chat">
                <div class="chat-bubble chat-bubble--right">
                    @if($msg->message)
                        <p class="incoming-msg">{{ $msg->message }}</p>
                    @endif


                    <p class="hey-text">
                        {{ \Carbon\Carbon::parse($msg->created_at)->timezone('Europe/Paris')->format('d-m-Y h:i A') }}
                    </p>
                      @if($msg->is_read==1)
                    <!-- <div class="read-status-icon">
                        <img src="{{ $senderProfilePicUrl  }}"  style="max-width: 20px; border-radius: 50%;">
                    </div> -->
                @endif
                </div>
            </div>
        </div>
    @else
        <div class="row no-gutters">
            <div class="col-md-6">
                <div class="chat-bubble chat-bubble--left">
                    @if($msg->message)
                        <p class="outgoing-msg">{{ $msg->message }}</p>
                    @endif           

                    <p class="hey-text">
                        {{ \Carbon\Carbon::parse($msg->created_at)->timezone('Europe/Paris')->format('d-m-Y h:i A') }}
                    </p>
                       @if($msg->is_read)
                    <div class="read-status-icon">
                        <img src="{{ $senderProfilePicUrl  }}" style="max-width: 20px; border-radius: 50%;">
                    </div>
                @endif
                </div>
            </div>
        </div>
    @endif
@endforeach

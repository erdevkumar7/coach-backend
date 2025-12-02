@foreach($messages as $msg)
    @php
        $isSender = $msg->sender_id == $admin_id;
    @endphp

    @if($isSender)
        <div class="row no-gutters right">
            <div class="col-md-6 right-side-chat">
                <div class="chat-bubble chat-bubble--right">
                    @if($msg->message)
                        <p class="incoming-msg">{{ $msg->message }}</p>
                    @endif

                    <p class="hey-text">
                        {{ \Carbon\Carbon::parse($msg->created_at)->timezone('Asia/Singapore')->format('d-m-Y h:i A') }}
                    </p>
                    
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
                        {{ \Carbon\Carbon::parse($msg->created_at)->timezone('Asia/Singapore')->format('d-m-Y h:i A') }}
                    </p>
                     
                </div>
            </div>
        </div>
    @endif
@endforeach

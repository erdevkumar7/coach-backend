@extends('admin.layouts.layout')
@section('content')

    <style>
    .no-chat-message {
        font-size: 16px;
        color: #888;
        margin-top: 30px;
    }
    .content-wrapper.user-chat-card-add {
        padding: 15px;
    }
    footer { display: none !important; }
    </style>

    <link rel="stylesheet" href="{{ url('/public') }}/admin_assets/css/chat.css">
    <link href="{{ asset('resources/assets/css/chat.css')}}" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.iife.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <div class="content-wrapper user-chat-card-add">
        <section class="content useradmin-chat">
            <div class="container-fluid">
            <div class="card card-default">    
                    <div class="card-body">  
                            <button id="scrollToBottomBtn" title="scroll to latest messages">
                                <i class="bi bi-arrow-down-short"></i>
                            </button>

                        <div class="container-fluid chat-box-add user-admin-chatbox">
                            @if($chatList->isEmpty())
                                <div class="no-chat-message text-center p-4 w-100">
                                    <p class="text-danger"><i class="bi bi-inbox"></i>No Chat Found </p>
                                </div>
                            @else

                            <div class="row no-gutters chat-inner-user">      

                                <div class="col-md-3 border-right left-side-chat">                            
                                    <div class="search-box">
                                        <div class="input-wrapper">
                                            <i class="bi bi-search"></i>
                                            <input type="text" id="chatSearch" placeholder="search here" />
                                        </div>
                                    </div>   

                                    @foreach($chatList as $person)
                                        <div class="friend-drawer friend-drawer--onhover vendor-item"
                                            data-id="{{ $person->id }}"
                                            data-name="{{ $person->first_name . ' ' . $person->last_name }}"
                                            data-image="{{ $person->profile_image ? asset('public/uploads/profile_image/'.$person->profile_image) : url('public/user.png') }}"
                                            data-unread="{{ $person->unread_count }}">

                                            <img class="profile-image" src="{{ $person->profile_image ? asset('public/uploads/profile_image/'.$person->profile_image) : url('public/user.png') }}" alt="profile" />

                                            <div class="text">
                                                <h6>{{ $person->first_name . ' ' . $person->last_name }}</h6>                                      
                                                <p></p>

                                                @if($person->unread_count > 0)
                                                <span class="badge badge-pill badge-danger unread-badge" id="unread-{{ $person->id }}" style="background:red;">
                                                    {{ $person->unread_count }}
                                                </span>
                                                @endif
                                            </div>  

                                            <span class="time small" id="last-time-{{ $person->id }}">
                                                @if($person->last_message_time)
                                                    @php
                                                        $lastMessage = \Carbon\Carbon::parse($person->last_message_time)->timezone('Asia/Singapore');
                                                    @endphp

                                                    {{ $lastMessage->isToday() ? $lastMessage->format('h:i A') : $lastMessage->format('d-m-Y h:i A') }}
                                                @endif
                                            </span>
                                        </div>
                                    @endforeach
                                </div>  

                                <div class="col-md-9 right-side-chat-add d-none" id="chatPanelWrapper">                        
                                    <div class="settings-tray selected-vendor-info"></div>

                                    <div class="chat-panel message-area"></div>                                

                                    <div class="chat-box-tray type-section">
                                        <input type="text" id="messageInput" placeholder="write your message" />

                                        <i class="bi bi-send" id="sendMessageBtn"></i>
                                    </div>
                                </div>

                                <div class="col-md-9" id="noChatSelected" style="height:520px;">
                                    <div class="d-flex justify-content-center align-items-center h-100 flex-column text-center p-5">
                                        <i class="bi bi-chat-dots text-muted" style="font-size: 3rem;"></i>
                                        <h5 class="mt-3 text-muted">select a conversation to start chat</h5>
                                    </div>
                                </div>

                            </div>
                            @endif
                        </div>
                    </div>            
                </div>
            </div>
        </section>
    </div>

    <script>
            const loggedInUserId = {{ auth()->id() }};
            const loadMessagesUrl = "{{ route('admin.loadMessages') }}";
            const sendMessageUrl = "{{ route('admin.sendMessage') }}";
            const APP_URL = "{{ url('/') }}";

            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: '{{ env("PUSHER_APP_KEY") }}',
                cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
                forceTLS: true,
                authEndpoint: `${APP_URL}/broadcasting/auth`
            });

            let receiver_id = null;

            function getChatChannelName(user1, user2) {
                const sorted = [user1, user2].sort((a, b) => a - b);
                return `adminchat.${sorted[0]}_${sorted[1]}`;
            }

            function loadMessages(receiver_id, markAsRead = true) {
                $.post(loadMessagesUrl, {
                    _token: '{{ csrf_token() }}',
                    receiver_id: receiver_id,
                    mark_read: markAsRead ? 1 : 0
                }, function (data) {
                    const wasNearBottom = isNearBottom();
                    $('.message-area').html(data);
                    if (wasNearBottom) scrollToBottom();
                });
            }

            function scrollToBottom(animated = true) {
                const container = $('.message-area');
                if (animated) {
                    container.stop().animate({ scrollTop: container[0].scrollHeight }, 300);
                } else {
                    container.scrollTop(container[0].scrollHeight);
                }
            }

            function isNearBottom(threshold = 100) {
                const container = $('.message-area');
                const scrollTop = container.scrollTop();
                const scrollHeight = container[0].scrollHeight;
                const offsetHeight = container[0].clientHeight;
                return scrollTop + offsetHeight >= scrollHeight - threshold;
            }

            function sendMessage() {
                const message = $('#messageInput').val().trim();
                if (!message) return;
                if (!receiver_id) return;

                let formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('message', message);
                formData.append('receiver_id', receiver_id);

                $.ajax({
                    url: sendMessageUrl,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function () {
                        $('#messageInput').val('');
                        loadMessages(receiver_id);
                        scrollToBottom();
                    }
                });
            }

            $('.vendor-item').each(function () {
                const otherUserId = $(this).data('id');
                const channel = getChatChannelName(loggedInUserId, otherUserId);

                Echo.private(channel).listen('.AdminMessageSent', (e) => {
                    updateUnreadCount(e);
                });
            });

            function updateUnreadCount(e) {
                const senderId = e.message.sender_id;
                const receiverId = e.message.receiver_id;

                const isActive = receiver_id == senderId || receiver_id == receiverId;

                if (isActive) {
                    loadMessages(receiver_id);
                } else {
                    const otherId = loggedInUserId === senderId ? receiverId : senderId;
                    const badge = $('#unread-' + otherId);

                    if (badge.length) {
                        let currentCount = parseInt(badge.text()) || 0;
                        badge.text(currentCount + 1);
                    } else {
                        const vendorItem = $('.vendor-item[data-id="' + otherId + '"]');
                        if (vendorItem.length) {
                            vendorItem.find('.text').append(`
                                <span class="badge badge-pill badge-danger unread-badge" id="unread-${otherId}" style="background:red;">1</span>
                            `);
                        }
                    }
                }
            }

            $(document).on('click', '.vendor-item', function () {
                $('.vendor-item').removeClass('active-chat');
                $(this).addClass('active-chat');

                receiver_id = $(this).data('id');
                const name = $(this).data('name');
                const image = $(this).data('image');

                $('#unread-' + receiver_id).remove();

                $('.selected-vendor-info').html(`
                    <div class="friend-drawer friend-drawer--grey">
                        <img class="profile-image" src="${image}" alt="" />
                        <div class="text"><h6>${name}</h6></div>
                    </div>
                `);

                $('#chatPanelWrapper').removeClass('d-none');
                $('#noChatSelected').addClass('d-none');

                const channel = getChatChannelName(loggedInUserId, receiver_id);
                loadMessages(receiver_id, true);
                scrollToBottom();
            });

            $('#sendMessageBtn').on('click', sendMessage);

            $(document).on('keydown', function (e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    const message = $('#messageInput').val().trim();
                    if (message.length > 0) {
                        e.preventDefault();
                        sendMessage();
                    }
                }
            });

            $('#scrollToBottomBtn').on('click', scrollToBottom);

            $('.message-area').on('scroll', function () {
                const btn = $('#scrollToBottomBtn');
                isNearBottom() ? btn.fadeOut() : btn.fadeIn();
            });

            $('#chatSearch').on('keyup', function () {
                const value = $(this).val().toLowerCase();
                $('.vendor-item').filter(function () {
                    const name = $(this).data('name').toLowerCase();
                    $(this).toggle(name.includes(value));
                });
            });

            $(document).ready(function () {
                $('#chatPanelWrapper').addClass('d-none');
                $('#noChatSelected').removeClass('d-none');
            });
    </script>

@endsection

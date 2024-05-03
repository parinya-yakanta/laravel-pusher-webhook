<div class="row g-0">
    <div class="col-12 col-lg-5 col-xl-3 border-right">

        <div class="py-1 px-4 border-bottom d-none d-lg-block mb-2">
            <div class="d-flex align-items-center py-1">
                <div class="flex-grow-1 ps-3">
                    <h3>
                        User Messages
                    </h3>
                </div>
                <div>
                    <button class="btn btn-light border btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal feather-lg"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg></button>
                </div>
            </div>
        </div>

        @forelse ($users as $user)
        <a wire:click='setActiveUser({{ Arr::get($user, 'id') }})' class="list-group-item list-group-item-action border-0 {{ $userIdActive == $user->id ? 'active' : '' }}">
            @if (auth()->user()?->countUnreadMessages(Arr::get($user, 'id')) > 0)
                <div class="badge bg-success float-end">{{ auth()->user()?->countUnreadMessages(Arr::get($user, 'id')) }}</div>
            @endif
            <div class="d-flex align-items-start">
                <img src="https://ui-avatars.com/api/?length=1&name={{ Arr::get($user, 'name') }}" class="rounded-circle me-1" alt="{{ Arr::get($user, 'name') }}" width="40" height="40">
                <div class="flex-grow-1 ms-3">
                    {{ Arr::get($user, 'name') }}
                    <div class="small"><span class="fas fa-circle chat-online"></span> {{ Arr::get($user, 'email') }}</div>
                </div>
            </div>
        </a>
        @empty
        <span class="list-group-item list-group-item-action border-0">No users found</span>
        @endforelse

        <hr class="d-block d-lg-none mt-1 mb-0">
    </div>
    @if ($userIdActive)
    <div class="col-12 col-lg-7 col-xl-9">
        <div class="py-1 px-4 border-bottom d-none d-lg-block">
            <div class="d-flex align-items-center py-1">
                <div class="flex-grow-1 ps-3">
                    <strong>{{ Arr::get($user, 'name') }}</strong>
                    <div class="text-muted small"><em>Hi...</em></div>
                </div>
                <div>
                    <button class="btn btn-primary btn-sm me-1 px-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone feather-lg"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg></button>
                    <button class="btn btn-info btn-sm me-1 px-3 d-none d-md-inline-block"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video feather-lg"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg></button>
                    <button class="btn btn-light border btn-sm px-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal feather-lg"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg></button>
                </div>
            </div>
        </div>

        <div class="position-relative">
            <div class="chat-messages p-4">

                @forelse ($messages as $message)
                <div class="{{ Arr::get($message, 'from_id') === $userIdActive ? 'chat-message-left':'chat-message-right' }} pb-4">
                    <div>
                        <img src="https://ui-avatars.com/api/?length=1&name={{ Arr::get($message->from, 'name') }}" class="rounded-circle me-1" alt="{{ Arr::get($message->from, 'name') }}" width="40" height="40">
                        <div class="text-muted small text-nowrap mt-2">{{ Arr::get($message, 'created_at')->format('H:i a') }}</div>
                    </div>
                    <div class="flex-shrink-1 rounded py-2 px-3 {{ Arr::get($message, 'from_id') === $userIdActive ? 'ms-4':'me-4' }}">
                        {{ Arr::get($message, 'content') }}
                    </div>
                </div>
                @empty
                <li class="clearfix"></li>
                @endforelse
            </div>
        </div>

        <div class="flex-grow-0 py-3 px-4 border-top">
            <form wire:submit.prevent="sendMessage">
                <div class="input-group mb-3">
                    <button class="btn btn-outline-secondary border border-1" type="submit"><i class="fa fa-send"></i></button>
                    <input type="text" wire:model="message" class="form-control" placeholder="Enter text here..." wire:focus='setActiveUser({{ Arr::get($user, 'id') }})'>
                </div>
            </form>
        </div>

    </div>
    @else
        <div class="col-12 col-lg-7 col-xl-9">
            <div class="d-flex justify-content-center align-items-center" style="height: 80vh;">
                <h3 class="text-danger text-center">
                    Please select a user to start chat
                </h3>
            </div>
        </div>
    @endif
</div>
@push('scripts')
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher("{{config('pusher.key')}}", {
      cluster: "{{config('pusher.options.cluster')}}"
    });

    var channel = pusher.subscribe('new.message.user.{{ Auth::id() }}');
    channel.bind('chat.message', function(data) {
        @this.call('$refresh');
    });
</script>
@endpush

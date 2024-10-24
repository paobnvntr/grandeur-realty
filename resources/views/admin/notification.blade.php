@extends('admin.layouts.app')

@section('contents')
<div class="container-fluid">
    <div class="row">
        @if(Session::has('success'))
            <div class="alert alert-success" id="alert-success" role="alert">
                {{ Session::get('success') }}
            </div>
        @endif

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5>Unread Notifications</h5>
                    @if($unreadNotifications->isNotEmpty())
                        <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-dark">
                                Mark All as Read
                            </button>
                        </form>
                    @endif
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @if($unreadNotifications->isEmpty())
                        <p class="text-muted">No unread notifications.</p>
                    @else
                        <ul class="list-group">
                            @foreach ($unreadNotifications as $notification)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span style="font-size: 0.775rem;">New Listing Request from
                                        {{ $notification->data['name'] }}</span>
                                    <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-link p-0 text-success" title="Mark as read">
                                            <i class="ti ti-check"></i>
                                        </button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="card-footer"></div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5>Read Notifications</h5>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @if($readNotifications->isEmpty())
                        <p class="text-muted">No read notifications.</p>
                    @else
                        <ul class="list-group">
                            @foreach ($readNotifications as $notification)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span style="font-size: 0.775rem;">New Listing Request from
                                        {{ $notification->data['name'] }}</span>
                                    <span class="text-muted"
                                        style="font-size: 0.575rem;">{{ $notification->read_at->diffForHumans() }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="card-footer"></div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        let successAlert = document.getElementById('alert-success');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.transition = "opacity 0.5s ease";
                successAlert.style.opacity = 0;
                setTimeout(() => { successAlert.remove(); }, 500);
            }, 4000);
        }

        let failedAlert = document.getElementById('alert-failed');
        if (failedAlert) {
            setTimeout(() => {
                failedAlert.style.transition = "opacity 0.5s ease";
                failedAlert.style.opacity = 0;
                setTimeout(() => { failedAlert.remove(); }, 500);
            }, 4000);
        }
    });
</script>
@endsection
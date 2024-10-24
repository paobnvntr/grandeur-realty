@extends('admin.layouts.app')

@section('contents')
<div class="container-fluid">
    @if(Session::has('success'))
        <div class="alert alert-success" id="alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif

    @if(Session::has('failed'))
        <div class="alert alert-danger" id="alert-failed" role="alert">
            {{ Session::get('failed') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-start">
                        <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="fs-6 me-2"></iconify-icon>
                        <h5 class="card-title mb-0">List of Users</h5>
                    </div>

                    <div class="table-responsive">
                        <table class="table text-nowrap align-middle mb-0">
                            <thead>
                                <tr class="border-2 border-bottom border-primary border-0">
                                    <th scope="col" class="ps-0 sticky-column-left">No.</th>
                                    <th scope="col" class="ps-0">Username</th>
                                    <th scope="col">Role</th>
                                    <th scope="col" class="text-center sticky-column-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                @if($users->count() > 0)
                                    @foreach($users as $user)
                                        <tr>
                                            <th class="align-middle text-center sticky-column-left">
                                                {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                                            </th>
                                            <td>
                                                <a class="fw-medium d-block link-info userUsername" data-bs-toggle="modal"
                                                    data-bs-target="#userDetailsModal{{$user->id}}">
                                                    {{ $user->username }}
                                                </a>
                                            </td>
                                            <td scope="row" class="ps-0 fw-medium">
                                                <span class="table-link1 text-truncate d-block">{{ $user->level }}</span>
                                            </td>
                                            <td class="text-center fw-medium sticky-column-right">
                                                @if (auth()->user()->level == 'Super Admin')
                                                    <a href="{{ route('users.editUser', $user->id) }}"
                                                        class="btn btn-sm btn-warning">Edit</a>
                                                @endif

                                                @if ($user->level != 'Super Admin')
                                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal{{$user->id}}">Delete</button>
                                                @endif
                                            </td>
                                        </tr>

                                        @include('admin.users.detailsUser')
                                        @include('admin.users.deleteUser')
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center">No users found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <p class="mb-0">Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of
                                {{ $users->total() }} results
                            </p>
                        </div>
                        <div>
                            {{ $users->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Adding of Users</h5>
                    <p class="card-text">You can add new users by clicking the button below.</p>
                    <a href="{{ route('users.addUser') }}" class="btn btn-outline-dark">Add</a>
                </div>
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